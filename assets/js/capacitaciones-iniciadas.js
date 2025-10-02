async function fetchData(url) {
  const response = await fetch(url);

  return await response.json();
}

async function setData(url) {
  const response = await fetchData(url);
  const data = response.data;

  const posts = data.map(async (element) => {
    let post = {};

    post.tipoCapacitacion = element.tipo_capacitacion;
    post.especialidadSlug = element.especialidad_slug;
    post.especialidadNombre = element.especialidad_name;
    post.dictante = element.dictante_principal;
    post.titulo = element.titulo;
    post.fechaInicio = element.fecha_inicio;
    post.fechaInicioDF = element.fecha_inicio_df;
    post.link = element.link;

    if (element.thumbnail !== null) {
      post.thumbnail = element.thumbnail;
    } else {
      post.thumbnail = THEME_URL + "img/capacitaciones/placeholder.jpg";
    }

    return post;
  });

  return Promise.all(posts);
}

function createItem(objCapacitacion) {
  let item = `
      <div class="card capacitacion border-dark-subtle bg-dark bg-opacity-10" coc-especialidad="${objCapacitacion.especialidadSlug}">
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
                  <a href="${objCapacitacion.link}" class="btn btn-sm btn-dark d-inline-block ms-auto mt-auto">Más información &rarr;</a>
               </div><!-- .card-body -->
            </div><!-- .col-sm-8 -->
         </div><!-- .row -->
      </div><!-- .card -->
   `;

  appRoot.innerHTML += item;
}

function fillCapacitaciones(jsonCapacitaciones) {
  jsonCapacitaciones.sort((a, b) => {
    const dateA = new Date(
      a.fechaInicioDF.slice(0, 4),
      a.fechaInicioDF.slice(4, 6) - 1,
      a.fechaInicioDF.slice(6, 8)
    );
    const dateB = new Date(
      b.fechaInicioDF.slice(0, 4),
      b.fechaInicioDF.slice(4, 6) - 1,
      b.fechaInicioDF.slice(6, 8)
    );
    return dateB - dateA;
  });

  const preloader = document.getElementById("preloader");
  preloader.classList.add("d-none");

  jsonCapacitaciones.forEach((element) => {
    createItem(element);
  });

  pageCount++;
}

let pageCount = 1;
let isLoading = false; // Flag para evitar múltiples cargas simultáneas

const footer = document.querySelector("#page.site > footer");
const appRoot = document.getElementById("app-root");

const observer = new IntersectionObserver(
  (entries, observer) => {
    entries.forEach(async (entry) => {
      if (entry.isIntersecting && !isLoading) {
        isLoading = true;

        const preloader = document.getElementById("preloader");
        preloader.classList.remove("d-none");

        try {
          const capacitaciones = await setData(
            `${API_CAPACITACIONES_INICIADAS_URL}?page=${pageCount}`
          );

          if (capacitaciones && capacitaciones.length > 0) {
            fillCapacitaciones(capacitaciones);
          } else {
            observer.unobserve(footer);
            preloader.classList.add("d-none");
          }
        } catch (error) {
          console.error("Error cargando capacitaciones:", error);
          preloader.classList.add("d-none");
        } finally {
          isLoading = false;
        }
      }
    });
  },
  {
    root: null,
    rootMargin: "0px",
    scrollMargin: "0px",
    threshold: 0.25,
  }
);

document.addEventListener("DOMContentLoaded", async () => {
  try {
    const capacitacionesStart = await setData(
      `${API_CAPACITACIONES_INICIADAS_URL}?page=${pageCount}`
    );

    if (capacitacionesStart && capacitacionesStart.length > 0) {
      fillCapacitaciones(capacitacionesStart);

      observer.observe(footer);
    }
  } catch (error) {
    console.error("Error en carga inicial de capacitaciones:", error);
    const preloader = document.getElementById("preloader");
    preloader.classList.add("d-none");
  }
});
