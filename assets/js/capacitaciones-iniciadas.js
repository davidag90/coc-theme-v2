import { setData } from "./capacitaciones.js";

function createItem(objCapacitacion) {
  const card = document.createElement("div");
  card.className = "card capacitacion border-dark-subtle bg-dark bg-opacity-10";
  card.setAttribute("coc-especialidad", objCapacitacion.especialidadSlug);

  card.innerHTML = `
    <div class="row g-0">
      <div class="col-sm-4">
        <img src="${objCapacitacion.thumbnail}" class="img-fluid" />
      </div>
      <div class="col-sm-8">
        <div class="card-body d-flex flex-column h-100">
          <h3 class="card-title h5">${objCapacitacion.titulo}</h3>
          <span class="d-block text-secondary mb-3"><small>${objCapacitacion.tipoCapacitacion} en ${objCapacitacion.especialidadNombre}</small></span>
          <p class="card-text">${objCapacitacion.dictante}</p>
          <p class="card-text opacity-75">${objCapacitacion.fechaInicio}</p>
          <a href="${objCapacitacion.link}" class="btn btn-sm btn-dark d-inline-block ms-auto mt-auto">Más información &rarr;</a>
        </div>
      </div>
    </div>
  `;

  return card;
}

function fillCapacitaciones(jsonCapacitaciones) {
  preloader.classList.add("d-none");

  const capacitaciones = jsonCapacitaciones.map((objCapacitacion) =>
    createItem(objCapacitacion)
  );

  if (pageCount === 1) {
    appRoot.replaceChildren(...capacitaciones);
  } else {
    appRoot.append(...capacitaciones);
  }

  pageCount++;
}

function applyEspecialidadFilter() {
  const filters = document.querySelectorAll(".filtro-espec");

  filters.forEach((filter) => {
    filter.addEventListener("click", (e) => {
      e.preventDefault();

      appRoot.replaceChildren();

      especialidadFilter = filter.getAttribute("coc-especialidad");
      pageCount = 1;

      filters.forEach((btn) => {
        btn.classList.remove("active");
      });

      e.target.classList.add("active");

      preloader.classList.remove("d-none");

      isLoading = true;

      setData(
        `${API_CAPACITACIONES_INICIADAS_URL}?especialidad=${especialidadFilter}&page=${pageCount}`
      )
        .then((capacitaciones) => {
          if (capacitaciones && capacitaciones.length > 0) {
            appRoot.replaceChildren();
            fillCapacitaciones(capacitaciones);
            observer.observe(footer);
          } else {
            appRoot.innerHTML =
              "<p>No existen capacitaciones para esta especialidad.</p>";

            preloader.classList.add("d-none");
            observer.unobserve(footer);
          }
        })
        .catch((error) => {
          console.error("Error cargando capacitaciones:", error);
          preloader.classList.add("d-none");
        })
        .finally(() => {
          isLoading = false;
        });
    });
  });
}

const filtroMobile = document.getElementById("filtro-espec-mobile");

filtroMobile.addEventListener("change", (e) => {
  especialidadFilter = e.target.value;
  pageCount = 1;

  appRoot.replaceChildren();

  const preloader = document.getElementById("preloader");
  preloader.classList.remove("d-none");

  isLoading = true;

  setData(
    `${API_CAPACITACIONES_INICIADAS_URL}?especialidad=${especialidadFilter}&page=${pageCount}`
  )
    .then((capacitaciones) => {
      if (capacitaciones && capacitaciones.length > 0) {
        appRoot.replaceChildren();
        fillCapacitaciones(capacitaciones);
        observer.observe(footer);
      } else {
        appRoot.innerHTML =
          "<p>No existen capacitaciones para esta especialidad.</p>";

        preloader.classList.add("d-none");
        observer.unobserve(footer);
      }
    })
    .catch((error) => {
      console.error("Error cargando capacitaciones:", error);
      preloader.classList.add("d-none");
    })
    .finally(() => {
      isLoading = false;
    });
});

let especialidadFilter = "";
let pageCount = 1;
let isLoading = false;

const footer = document.querySelector("#page.site > footer");
const appRoot = document.getElementById("app-root");
const preloader = document.getElementById("preloader");

const observer = new IntersectionObserver(
  (entries, observer) => {
    entries.forEach(async (entry) => {
      if (entry.isIntersecting && !isLoading) {
        isLoading = true;

        preloader.classList.remove("d-none");

        try {
          if (especialidadFilter !== "") {
            const capacitaciones = await setData(
              `${API_CAPACITACIONES_INICIADAS_URL}?especialidad=${especialidadFilter}&page=${pageCount}`
            );
            if (capacitaciones && capacitaciones.length > 0) {
              fillCapacitaciones(capacitaciones);
            } else {
              observer.unobserve(footer);

              const finalMessage = document.createElement("p");
              finalMessage.classList.add("text-center", "mt-4");
              finalMessage.textContent = "No existen más capacitaciones.";
              appRoot.appendChild(finalMessage);

              preloader.classList.add("d-none");
            }
          } else {
            const capacitaciones = await setData(
              `${API_CAPACITACIONES_INICIADAS_URL}?page=${pageCount}`
            );

            if (capacitaciones && capacitaciones.length > 0) {
              fillCapacitaciones(capacitaciones);
            } else {
              observer.unobserve(footer);

              const finalMessage = document.createElement("p");
              finalMessage.classList.add("text-center", "mt-4");
              finalMessage.textContent = "No existen más capacitaciones.";
              appRoot.appendChild(finalMessage);

              preloader.classList.add("d-none");
            }
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

    preloader.classList.add("d-none");
  }
});

document.addEventListener("DOMContentLoaded", applyEspecialidadFilter);
