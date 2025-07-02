async function fetchData(url) {
  const response = await fetch(url);

  return await response.json();
}

async function setData(url) {
  const data = await fetchData(url);

  const posts = data.map(async (element) => {
    let post = {};

    post.slug = element.slug;
    post.title = element.title.rendered;
    post.integrantes = element.acf.integrantes;
    post.infoAdicional = element.acf.info_adicional;

    const thumbURL = element?._embedded?.["wp:featuredmedia"]?.[0]?.["media_details"]?.["sizes"]?.["medium"]?.["source_url"] ?? null;

    if (element.featured_media !== null) {
      post.thumbnail = thumbURL;
    } else {
      post.thumbnail = THEME_URL + "img/beneficios/placeholder.jpg";
    }

    return post;
  });

  return Promise.all(posts);
}

function createItem(objSociedad) {
  let item = `
      <div class="card bg-secondary bg-opacity-10 capacitacion border-secondary h-100">
         <img src="${objSociedad.thumbnail}" class="card-img-top border-secondary border-bottom" />
         <div class="card-body d-flex flex-column">
            <h3 class="card-title h5 mb-4">${objSociedad.title}</h3>
            <button class="btn btn-sm btn-primary d-inline-block ms-auto mt-auto" data-bs-toggle="modal" data-bs-target="#modal-${objSociedad.slug}">Integrantes &rarr;</button>
         </div><!-- .card-body -->
      </div><!-- .card -->
   `;

  appRoot.innerHTML += item;
}

function createModals(objSociedad) {
  let modal = `
      <div class="modal fade" id="modal-${objSociedad.slug}" tabindex="-1" aria-labelledby="modal-${objSociedad.slug}-label" aria-hidden="true">
         <div class="modal-dialog">
            <div class="modal-content">
               <div class="modal-header">
                  <h1 class="modal-title fs-5" id="modal-${objSociedad.slug}-label">${objSociedad.title}</h1>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
               </div>
               
               <div class="modal-body">
                  ${objSociedad.integrantes}
                  ${objSociedad.infoAdicional}
               </div>
            </div>
         </div>
      </div>
   `;

  modals.innerHTML += modal;
}

function fillSociedades(jsonSociedades) {
  let preloader = document.getElementById("preloader");
  preloader.classList.add("d-none");

  jsonSociedades.sort((a, b) => {
    let x = a.title.toLowerCase();
    let y = b.title.toLowerCase();

    if (x < y) {
      return -1;
    }
    if (x > y) {
      return 1;
    }

    return 0;
  });

  jsonSociedades.forEach((element) => {
    createItem(element);
    createModals(element);
  });
}

const appRoot = document.getElementById("app-root");
const modals = document.getElementById("modals");
const sociedades = await setData(API_SOCIEDADES_URL);

document.addEventListener("DOMContentLoaded", fillSociedades(sociedades));
document.addEventListener("DOMContentLoaded", setFiltros());
