async function fetchData(url) {
  const response = await fetch(url);

  return await response.json();
}

export async function setDataCapacitaciones(url) {
  const response = await fetchData(url);
  const data = response.data;

  const posts = data.map(async (element) => {
    const {
      tipo_capacitacion,
      especialidad_slug,
      especialidad_name,
      dictante_principal,
      titulo,
      fecha_inicio,
      fecha_inicio_df,
      link,
      thumbnail,
    } = element;

    const post = {
      tipoCapacitacion: tipo_capacitacion,
      especialidadSlug: especialidad_slug,
      especialidadNombre: especialidad_name,
      dictante: dictante_principal,
      titulo: titulo,
      fechaInicio: fecha_inicio,
      fechaInicioDF: fecha_inicio_df,
      link: link,
      thumbnail: thumbnail,
    };

    return post;
  });

  return Promise.all(posts);
}

export async function setDataBeneficios(url) {
  const response = await fetchData(url);
  const data = response.data;

  const posts = data.map(async (element) => {
    const {
      id,
      contenido,
      extracto,
      detalles,
      prestador,
      rubro_slug,
      rubro_name,
      link,
      thumbnail,
    } = element;

    const post = {
      id: id,
      contenido: contenido,
      extracto: extracto,
      detalles: detalles,
      prestador: prestador,
      rubroSlug: rubro_slug,
      rubroNombre: rubro_name,
      link: link,
      thumbnail: thumbnail,
    };

    return post;
  });

  return Promise.all(posts);
}

export async function setDataSociedades(url) {
  const response = await fetchData(url);
  const data = response.data;

  const posts = data.map(async (element) => {
    const { slug, title, integrantes, info_adicional, thumbnail } = element;

    const post = {
      slug: slug,
      title: title,
      integrantes: integrantes,
      infoAdicional: info_adicional,
      thumbnail: thumbnail,
    };

    return post;
  });

  return Promise.all(posts);
}
