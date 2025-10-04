async function fetchData(url) {
  const response = await fetch(url);

  return await response.json();
}

export async function setData(url) {
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
