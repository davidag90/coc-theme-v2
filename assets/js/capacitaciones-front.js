import { setData } from "./fetch.js";

function startSplide() {
  const splideCapacitaciones = new Splide(".splide", {
    type: "slide",
    mediaQuery: "min",
    gap: "1rem",
    padding: "1rem",
    pagination: false,
    breakpoints: {
      0: {
        perPage: 1,
        perMove: 1,
      },
      576: {
        perPage: 2,
        perMove: 1,
      },
      768: {
        perPage: 3,
        perMove: 1,
      },
      1200: {
        perPage: 4,
        perMove: 1,
      },
    },
  });

  splideCapacitaciones.mount();
}

function createItem(objCapacitacion) {
  const slide = document.createElement("li");
  slide.className = "splide__slide";
  slide.setAttribute("coc-especialidad", objCapacitacion.especialidadSlug);

  slide.innerHTML = `
    <div class="card capacitacion border-${objCapacitacion.especialidadSlug} h-100 position-relative" coc-especialidad="${objCapacitacion.especialidadSlug}">
      <img src="${objCapacitacion.thumbnail}" class="card-img-top" />
      <div class="card-body d-flex flex-column">
          <h3 class="h5 card-title">${objCapacitacion.titulo}</h3>
          <span class="d-block text-secondary mb-3"><small>${objCapacitacion.tipoCapacitacion} en ${objCapacitacion.especialidadNombre}</small></span>
          <p class="card-text">${objCapacitacion.dictante}</p>
          <p class="card-text opacity-75">${objCapacitacion.fechaInicio}</p>
          <a href="${objCapacitacion.link}" class="btn btn-sm btn-${objCapacitacion.especialidadSlug} ms-auto mt-auto stretched-link">Más información &rarr;</a>
      </div><!-- .card-body -->
    </div><!-- .capacitacion -->
   `;

  return slide;
}

function fillCapacitaciones(jsonCapacitaciones, especialidad) {
  preloader.classList.add("d-none");

  let slides = [];

  if (!especialidad || especialidad === "") {
    slides = jsonCapacitaciones.map((objCapacitacion) =>
      createItem(objCapacitacion)
    );
  } else {
    const filterSlides = jsonCapacitaciones.filter(
      (element) => especialidad === element.especialidadSlug
    );

    slides = filterSlides.map((objCapacitacion) => createItem(objCapacitacion));
  }

  console.log(slides);

  appRoot.replaceChildren(...slides);
  startSplide();
}

function setFiltros() {
  const filtros = document.querySelectorAll(".filtro-espec");

  filtros.forEach((filtro) => {
    const especialidad = filtro.getAttribute("coc-especialidad");

    filtro.addEventListener("click", (event) => {
      appRoot.replaceChildren();

      fillCapacitaciones(capacitaciones, especialidad);

      filtros.forEach((elem) => {
        let especialidadIn = elem.getAttribute("coc-especialidad");
        elem.classList.remove(`btn-${especialidadIn}`);
        elem.classList.add(`btn-outline-dark`);
      });

      event.target.classList.remove(`btn-outline-dark`);
      event.target.classList.add(`btn-${especialidad}`);
    });
  });

  const filtrosMobile = document.querySelector(
    "#filtros-espec-mobile > select"
  );

  filtrosMobile.addEventListener("change", (event) => {
    const especialidad = event.target.value;

    appRoot.replaceChildren();

    fillCapacitaciones(capacitaciones, especialidad);
  });
}

const appRoot = document.querySelector(
  ".splide > .splide__track > .splide__list"
);
const preloader = document.getElementById("preloader");
const capacitaciones = await setData(API_CAPACITACIONES_VIGENTES_URL);

document.addEventListener(
  "DOMContentLoaded",
  fillCapacitaciones(capacitaciones)
);

document.addEventListener("DOMContentLoaded", setFiltros());
