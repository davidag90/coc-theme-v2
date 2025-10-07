import { setDataSociedades } from "./fetch.js";

function createItem(objSociedad) {
  const { thumbnail, title, slug } = objSociedad;

  const item = document.createElement("div");
  item.classList.add(
    "card",
    "bg-secondary",
    "bg-opacity-10",
    "border-secondary",
    "h-100"
  );

  item.innerHTML = `
    <img src="${thumbnail}" class="card-img-top border-secondary border-bottom" />
    <div class="card-body d-flex flex-column">
      <h3 class="card-title h5 mb-4">${title}</h3>
      <button class="btn btn-sm btn-primary d-inline-block ms-auto mt-auto" data-bs-toggle="modal" data-bs-target="#modal-${slug}">Integrantes &rarr;</button>
    </div><!-- .card-body -->
   `;

  return item;
}

function createModal(objSociedad) {
  const { slug, title, integrantes, infoAdicional } = objSociedad;

  const modal = document.createElement("div");
  modal.classList.add("modal", "fade");
  modal.id = `modal-${slug}`;
  modal.tabIndex = -1;
  modal.setAttribute("aria-labelledby", `modal-${slug}-label`);
  modal.setAttribute("aria-hidden", "true");

  modal.innerHTML = `
         <div class="modal-dialog">
            <div class="modal-content">
               <div class="modal-header">
                  <h1 class="modal-title fs-5" id="modal-${slug}-label">${title}</h1>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
               </div>
               
               <div class="modal-body">
                  ${integrantes}
                  ${infoAdicional}
               </div>
            </div>
         </div>
   `;

  return modal;
}

function fillSociedades(jsonSociedades) {
  preloader.classList.add("d-none");

  jsonSociedades.forEach((element) => {
    appRoot.appendChild(createItem(element));
    modals.appendChild(createModal(element));
  });
}

const appRoot = document.getElementById("app-root");
const modals = document.getElementById("modals");
const preloader = document.getElementById("preloader");
const sociedades = await setDataSociedades(API_SOCIEDADES_URL);

document.addEventListener("DOMContentLoaded", fillSociedades(sociedades));
