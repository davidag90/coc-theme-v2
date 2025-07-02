async function fetchData(url) {
  const response = await fetch(url);

  return await response.json();
}

async function setData(url) {
  const data = await fetchData(url);

  const posts = data.map(async (element) => {
    let post = {};

    post.tipoCapacitacion = element.acf.tipo_capacitacion;
    post.especialidadSlug = element._embedded["wp:term"][0][0].slug;
    post.especialidadNombre = element._embedded["wp:term"][0][0].name;
    post.dictante = element.acf.dictante_principal_txt;
    post.titulo = element.title.rendered;
    post.fechaInicio = element.acf.fecha_inicio;
    post.fechaInicioDF = element.acf.fecha_inicio_dateformat;
    post.link = element.link;

    const thumbURL = element?._embedded?.["wp:featuredmedia"]?.[0]?.["media_details"]?.["sizes"]?.["medium"]?.["source_url"] ?? null;

    if (element.featured_media !== null) {
      post.thumbnail = thumbURL;
    } else {
      post.thumbnail = THEME_URL + "img/capacitaciones/placeholder.jpg";
    }

    return post;
  });

  return Promise.all(posts);
}

function createItem(objCapacitacion) {
  let item = `
      <div class="card capacitacion border-${objCapacitacion.especialidadSlug}" coc-especialidad="${objCapacitacion.especialidadSlug}">
         <div class="row g-0">
            <div class="col-sm-4">
               <img src="${objCapacitacion.thumbnail}" class="img-fluid" />
            </div><!-- .col-sm-4 -->
            <div class="col-sm-8">
               <div class="card-body d-flex flex-column h-100">
                  <h3 class="card-title h5">${objCapacitacion.titulo}</h3>
                  <span class="d-block text-secondary mb-3"><small>${objCapacitacion.tipoCapacitacion} en ${objCapacitacion.especialidadNombre}</small></span>
                  <p class="card-text">${objCapacitacion.dictante}</p>
                  <p class="card-text opacity-75">${objCapacitacion.fechaInicio}</p>
                  <a href="${objCapacitacion.link}" class="btn btn-sm btn-${objCapacitacion.especialidadSlug} d-inline-block ms-auto mt-auto">Más información &rarr;</a>
               </div><!-- .card-body -->
            </div><!-- .col-sm-8 -->
         </div><!-- .row -->
      </div><!-- .card -->
   `;

  appRoot.innerHTML += item;
}

function fillCapacitaciones(jsonCapacitaciones, especialidad = "todos") {
  jsonCapacitaciones.sort((a, b) => {
    const dateA = new Date(a.fechaInicioDF.slice(0, 4), a.fechaInicioDF.slice(4, 6) - 1, a.fechaInicioDF.slice(6, 8));
    const dateB = new Date(b.fechaInicioDF.slice(0, 4), b.fechaInicioDF.slice(4, 6) - 1, b.fechaInicioDF.slice(6, 8));

    return dateA - dateB;
  });

  let preloader = document.getElementById("preloader");
  preloader.classList.add("d-none");

  jsonCapacitaciones.forEach((element) => {
    const minuto = 1000 * 60;
    const hora = minuto * 60;
    const dia = hora * 24;

    const hoy = new Date().valueOf();
    const limite = new Date(hoy - dia * 2);
    console.log(limite.toString());

    const fechaCapacitacion = new Date(element.fechaInicioDF.slice(0, 4), element.fechaInicioDF.slice(4, 6) - 1, element.fechaInicioDF.slice(6, 8));

    if (limite < fechaCapacitacion) {
      if (especialidad === "todos") {
        createItem(element);
      } else {
        if (especialidad === element.especialidadSlug) {
          createItem(element);
        }
      }
    }
  });
}

function setFiltros() {
  const filtros = document.querySelectorAll(".filtro-espec");

  filtros.forEach((filtro) => {
    let especialidad = filtro.getAttribute("coc-especialidad");

    filtro.addEventListener("click", (event) => {
      appRoot.innerHTML = "";
      fillCapacitaciones(capacitaciones, especialidad);

      filtros.forEach((elem) => {
        elem.classList.remove("active");
      });

      event.target.classList.add("active");
    });
  });

  const filtrosMobile = document.querySelector("#filtros-espec-mobile > select");

  filtrosMobile.addEventListener("change", (event) => {
    let especialidad = event.target.value;

    appRoot.innerHTML = "";

    fillCapacitaciones(capacitaciones, especialidad);
  });
}

const appRoot = document.getElementById("app-root");
const capacitaciones = await setData(API_CAPACITACIONES_URL);

document.addEventListener("DOMContentLoaded", fillCapacitaciones(capacitaciones));
document.addEventListener("DOMContentLoaded", setFiltros());
