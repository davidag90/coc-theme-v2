import { setDataBeneficios } from "./fetch.js";

function createItem(objBeneficio) {
  const { slug, extracto, prestador, rubroSlug, thumbnail } = objBeneficio;
  const item = document.createElement("div");
  item.classList.add("card", "border-secondary");
  item.setAttribute("coc-rubro", rubroSlug);

  item.innerHTML = `
    <div class="row g-0">
      <div class="col-sm-4">
          <img src="${thumbnail}" class="img-fluid" />
      </div><!-- .col-sm-4 -->
      <div class="col-sm-8">
          <div class="card-body d-flex flex-column h-100">
            <h3 class="card-title h5">${prestador}</h3>
            ${extracto}
            <button class="btn btn-sm btn-primary d-inline-block ms-auto mt-auto" data-bs-toggle="modal" data-bs-target="#modal-${slug}">Más información &rarr;</button>
          </div><!-- .card-body -->
      </div><!-- .col-sm-8 -->
    </div><!-- .row -->
   `;

  return item;
}

function createModal(objBeneficio) {
  const { slug, prestador, detalles } = objBeneficio;
  const modal = document.createElement("div");
  modal.classList.add("modal", "fade");
  modal.setAttribute("id", `modal-${slug}`);
  modal.setAttribute("tabindex", "-1");
  modal.setAttribute("aria-labelledby", `modal-${slug}-label`);
  modal.setAttribute("aria-hidden", "true");

  modal.innerHTML = `
      <div class="modal fade" id="modal-${slug}" tabindex="-1" aria-labelledby="modal-${slug}-label" aria-hidden="true">
         <div class="modal-dialog">
            <div class="modal-content">
               <div class="modal-header">
                  <h1 class="modal-title fs-5" id="modal-${slug}-label">${prestador}</h1>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
               </div>
               
               <div class="modal-body">${detalles}</div>
            </div>
         </div>
      </div>
   `;

  return modal;
}

function fillBeneficios(jsonBeneficios) {
  preloader.classList.add("d-none");

  jsonBeneficios.forEach((beneficio) => {
    appRoot.appendChild(createItem(beneficio));
    modals.appendChild(createModal(beneficio));
  });

  pageCount++;
}

function applyRubroFilter() {
  const filters = document.querySelectorAll(".filtro-rubro");

  filters.forEach((filter) => {
    filter.addEventListener("click", (e) => {
      e.preventDefault();

      appRoot.replaceChildren();

      rubroFilter = filter.getAttribute("coc-rubro");
      pageCount = 1;

      filters.forEach((btn) => {
        btn.classList.remove("active");
      });

      e.target.classList.add("active");

      preloader.classList.remove("d-none");

      isLoading = true;

      setDataBeneficios(
        `${API_BENEFICIOS_URL}?rubro=${rubroFilter}&page=${pageCount}`
      )
        .then((beneficios) => {
          if (beneficios && beneficios.length > 0) {
            appRoot.replaceChildren();
            fillBeneficios(beneficios);
            observer.observe(footer);
          } else {
            appRoot.innerHTML =
              "<p>No existen beneficios para esta especialidad.</p>";

            preloader.classList.add("d-none");
            observer.unobserve(footer);
          }
        })
        .catch((error) => {
          console.error("Error cargando beneficios:", error);
          preloader.classList.add("d-none");
        })
        .finally(() => {
          isLoading = false;
        });
    });
  });
}

const filtroMobile = document.getElementById("filtro-rubro-mobile");

filtroMobile.addEventListener("change", (e) => {
  rubroFilter = e.target.value;
  pageCount = 1;

  appRoot.replaceChildren();

  const preloader = document.getElementById("preloader");
  preloader.classList.remove("d-none");

  isLoading = true;

  setDataBeneficios(
    `${API_BENEFICIOS_URL}?rubro=${rubroFilter}&page=${pageCount}`
  )
    .then((beneficios) => {
      if (beneficios && beneficios.length > 0) {
        appRoot.replaceChildren();
        fillCapacitaciones(beneficios);
        observer.observe(footer);
      } else {
        appRoot.innerHTML =
          "<p>No existen beneficios para esta especialidad.</p>";

        preloader.classList.add("d-none");
        observer.unobserve(footer);
      }
    })
    .catch((error) => {
      console.error("Error cargando beneficios:", error);
      preloader.classList.add("d-none");
    })
    .finally(() => {
      isLoading = false;
    });
});

let rubroFilter = "";
let pageCount = 1;
let isLoading = false;

const appRoot = document.getElementById("app-root");
const preloader = document.getElementById("preloader");
const modals = document.getElementById("modals");
const footer = document.querySelector("#page.site > footer");

const observer = new IntersectionObserver(
  (entries, observer) => {
    entries.forEach(async (entry) => {
      if (entry.isIntersecting && !isLoading) {
        isLoading = true;

        preloader.classList.remove("d-none");

        try {
          if (rubroFilter !== "") {
            const beneficios = await setDataBeneficios(
              `${API_BENEFICIOS_URL}?rubro=${rubroFilter}&page=${pageCount}`
            );
            if (beneficios && beneficios.length > 0) {
              fillBeneficios(beneficios);
            } else {
              observer.unobserve(footer);

              const finalMessage = document.createElement("p");
              finalMessage.classList.add("text-center", "mt-4");
              finalMessage.textContent = "No existen más beneficios.";
              appRoot.appendChild(finalMessage);

              preloader.classList.add("d-none");
            }
          } else {
            const beneficios = await setDataBeneficios(
              `${API_BENEFICIOS_URL}?page=${pageCount}`
            );

            if (beneficios && beneficios.length > 0) {
              fillBeneficios(beneficios);
            } else {
              observer.unobserve(footer);

              const finalMessage = document.createElement("p");
              finalMessage.classList.add("text-center", "mt-4");
              finalMessage.textContent = "No existen más beneficios.";
              appRoot.appendChild(finalMessage);

              preloader.classList.add("d-none");
            }
          }
        } catch (error) {
          console.error("Error cargando beneficios: ", error);
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
    const beneficiosStart = await setDataBeneficios(
      API_BENEFICIOS_URL + `?rubro=${rubroFilter}&page=${pageCount}`
    );

    if (beneficiosStart && beneficiosStart.length > 0) {
      fillBeneficios(beneficiosStart);
      observer.observe(footer);
    }
  } catch (error) {
    console.error("Error en carga inicial de beneficios:", error);

    preloader.classList.add("d-none");
  }
});

document.addEventListener("DOMContentLoaded", applyRubroFilter);
