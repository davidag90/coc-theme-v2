<?php

/**
 * Template Post Type: capacitaciones
 *
 */

get_header();

include_once(__DIR__  . '/inc/scripts-capacitaciones.php');

the_post();

$capacitacion_data = get_capacitacion_data(get_the_ID());
$iniciada = check_started($capacitacion_data['fecha_inicio_dateformat']);
$related_especialidades = set_related_especialidades($capacitacion_data['especialidades_relativas'], $capacitacion_data['especialidad_slug']);
?>

<?php if ($capacitacion_data['estado_inscripciones'] === 'abiertas' && !$iniciada) : ?>
   <header id="header-capacitacion" class="bg-<?= esc_attr($capacitacion_data['especialidad_slug']) ?> bg-header-<?= esc_attr($capacitacion_data['especialidad_slug']) ?> border-<?= esc_attr($capacitacion_data['especialidad_slug']) ?>">
   <?php else : ?>
      <header id="header-capacitacion" class="bg-light bg-header-<?= esc_attr($capacitacion_data['especialidad_slug']) ?> border-dark-subtle">
      <?php endif; ?>
      <div class="container">
         <div class="row">
            <div class="col-md-8">
               <div class="detalles-header d-flex flex-column justify-content-end pb-4 text-light">
                  <?php if ($iniciada) : ?><p><span class="text-bg-light rounded p-2">Capacitacion iniciada</span></p><?php endif; ?>
                  <h1 class="mb-0 position-relative">
                     <?php the_title(); ?>
                  </h1>
                  <p class="subtitulo-capacitacion mb-0 fs-5">
                     <?= $capacitacion_data['tipo_capacitacion']; ?> en <strong><?= $capacitacion_data['especialidad_name']; ?></strong>
                  </p>
               </div><!-- .detalles-header -->
            </div><!-- .col -->
         </div><!-- .row -->
      </div><!-- .container -->
      </header>

      <div id="content" class="site-content container">
         <div id="primary" class="content-area">
            <div class="row">
               <div class="col-12 col-md-8">
                  <main id="main" class="site-main">
                     <div class="entry-content pt-5">
                        <div class="row mb-5">
                           <div class="col-md-5 col-lg-4 col-xl-3">
                              <img src="<?= esc_url($capacitacion_data['dictantes_img']) ?>" class="w-100 mb-4" />
                           </div><!-- .col-lg-4 -->
                           <div class="col-md-7 col-lg-8 col-xl-9">
                              <div class="d-flex flex-column">
                                 <div class="dictantes mb-4">
                                    <?= $capacitacion_data['dictantes'] ?>
                                    <?php if (!empty($capacitacion_data['curriculum_dictante'])) : ?>
                                       <a class="btn btn-primary" href="<?= $capacitacion_data['curriculum_dictante'] ?>" target="_blank" rel="noopener noreferrer">Ver curriculum</a>
                                    <?php endif; ?>
                                 </div><!-- .dictantes -->

                                 <?php if (!empty($capacitacion_data['co_dictantes'])) : ?>
                                    <div class="co-dictantes">
                                       <?= $capacitacion_data['co_dictantes']; ?>
                                    </div><!-- .co-dictantes -->
                                 <?php endif; ?>

                                 <?php if (!empty($capacitacion_data['dictantes_invitados'])) : ?>
                                    <div class="dictantes-invitados">
                                       <?= $capacitacion_data['dictantes_invitados']; ?>
                                    </div><!-- .dictantes-invitados -->
                                 <?php endif; ?>
                              </div><!-- .d-flex -->
                           </div><!-- .col-lg-8 -->
                        </div><!-- .row -->

                        <hr class="m-0">

                        <div class="row">
                           <div class="col-12">
                              <div class="highlights pt-5">
                                 <div class="highlight d-flex flex-column mb-5 px-3 justify-content-start align-items-center text-center">
                                    <i class="fa-solid fa-calendar-days d-block fa-4x mb-3 text-secondary"></i>
                                    <h4 class="mb-1 text-secondary">Fecha de Inicio</h4>
                                    <p><?= $capacitacion_data['fecha_inicio']; ?></p>
                                 </div>

                                 <div class="highlight d-flex flex-column mb-5 px-3 justify-content-start align-items-center text-center">
                                    <i class="fa-solid fa-clock d-block fa-4x mb-3 text-secondary"></i>
                                    <h4 class="mb-1 text-secondary">Horarios</h4>
                                    <?= $capacitacion_data['horarios']; ?>
                                 </div>

                                 <div class="highlight d-flex flex-column mb-5 px-3 justify-content-start align-items-center text-center">
                                    <i class="fa-solid fa-users-between-lines d-block fa-4x mb-3 text-secondary"></i>
                                    <h4 class="mb-1 text-secondary">Sesiones</h4>
                                    <p><?= $capacitacion_data['sesiones']; ?> sesiones</p>
                                    <p><?= $capacitacion_data['horas']; ?> horas</p>
                                 </div>
                              </div>
                           </div>
                        </div>

                        <hr class="m-0">

                        <div class="row mt-5">
                           <div class="col-12">
                              <div class="detalles-adicionales">
                                 <?= $capacitacion_data['detalles_adicionales']; ?>
                              </div>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-12">
                              <div class="detalles-pedagogicos py-5">
                                 <div class="accordion" id="acordeon-detalles">
                                    <?php if (!empty($capacitacion_data['objetivos'])) : ?>
                                       <div class="accordion-item">
                                          <h2 class="accordion-header" id="objetivos-enc">
                                             <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#objetivos-collapse" aria-expanded="false" aria-controls="objetivos-collapse">Objetivos</button>
                                          </h2><!-- .accordion-header -->
                                          <div id="objetivos-collapse" class="accordion-collapse collapse" aria-labelledby="objetivos-enc" data-bs-parent="#acordeon-detalles">
                                             <div class="accordion-body"><?= $capacitacion_data['objetivos']; ?></div>
                                          </div><!-- .accordion-collapse -->
                                       </div><!-- .accordion-item -->
                                    <?php endif; ?>

                                    <?php if (!empty($capacitacion_data['temario'])) : ?>
                                       <div class="accordion-item">
                                          <h2 class="accordion-header" id="temario-enc">
                                             <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#temario-collapse" aria-expanded="false" aria-controls="temario-collapse">Temario</button>
                                          </h2><!-- .accordion-header -->
                                          <div id="temario-collapse" class="accordion-collapse collapse" aria-labelledby="temario-enc" data-bs-parent="#acordeon-detalles">
                                             <div class="accordion-body"><?= $capacitacion_data['temario']; ?></div>
                                          </div><!-- .accordion-collapse -->
                                       </div><!-- .accordion-item -->
                                    <?php endif; ?>

                                    <?php if (!empty($capacitacion_data['esquema_actividad'])) : ?>
                                       <div class="accordion-item">
                                          <h2 class="accordion-header" id="esquema-actividad-enc">
                                             <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#esquema-actividad-collapse" aria-expanded="false" aria-controls="esquema-actividad-collapse">Esquema de Actividad</button>
                                          </h2><!-- .accordion-header -->
                                          <div id="esquema-actividad-collapse" class="accordion-collapse collapse" aria-labelledby="esquema-actividad-enc" data-bs-parent="#acordeon-detalles">
                                             <div class="accordion-body"><?= $capacitacion_data['esquema_actividad']; ?></div>
                                          </div><!-- .accordion-collapse -->
                                       </div><!-- .accordion-item -->
                                    <?php endif; ?>

                                    <?php if (!empty($capacitacion_data['listado_materiales'])) : ?>
                                       <div class="accordion-item">
                                          <h2 class="accordion-header" id="listado-materiales-enc">
                                             <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#listado-materiales-collapse" aria-expanded="false" aria-controls="listado-materiales-collapse">Listado de Materiales</button>
                                          </h2><!-- .accordion-header -->
                                          <div id="listado-materiales-collapse" class="accordion-collapse collapse" aria-labelledby="listado-materiales-enc" data-bs-parent="#acordeon-detalles">
                                             <div class="accordion-body"><?= $capacitacion_data['listado_materiales']; ?></div>
                                          </div><!-- .accordion-collapse -->
                                       </div><!-- .accordion-item -->
                                    <?php endif; ?>
                                 </div><!-- #acordeon-detalles -->
                              </div><!-- .detalles-pedagogicos -->
                           </div><!-- .col-12 -->
                        </div><!-- .row -->

                        <?php if (!empty($capacitacion_data['beneficios'])) : ?>
                           <div class="row">
                              <div class="col-12">
                                 <div id="beneficios">
                                    <?= $capacitacion_data['beneficios']; ?>
                                 </div><!-- #beneficios -->
                              </div><!-- .col-12 -->
                           </div><!-- .row -->
                        <?php endif; ?>

                        <?php if (!empty($capacitacion_data['sponsors'])) : ?>
                           <div class="row">
                              <div class="col-12">
                                 <div id="apoyos">
                                    <h3 class="mb-3">Apoyan esta capacitación:</h3>
                                    <?= $capacitacion_data['sponsors']; ?>
                                 </div><!-- #apoyos -->
                              </div><!-- .col-12 -->
                           </div><!-- .row -->
                        <?php endif; ?>

                        <?php if (!empty($capacitacion_data['extra'])) : ?>
                           <div class="row">
                              <div class="col-12">
                                 <div id="extra">
                                    <?= $capacitacion_data['extra']; ?>
                                 </div><!-- #extra -->
                              </div><!-- .col-12 -->
                           </div><!-- .row -->
                        <?php endif; ?>
                     </div><!-- .entry-content -->
                  </main><!-- #main -->
               </div><!-- .col-md-8 -->

               <div class="col-12 col-md-4">
                  <aside id="detalles-inscripcion" class="bg-light border shadow-sm rounded overflow-hidden mb-4">
                     <img src="<?= esc_url($capacitacion_data['img_destacada']); ?>" class="mb-4">

                     <div class="botonera mb-4 px-4">
                        <?php $whatsapp_params = http_build_query(array(
                           'phone'  => '5493517016644',
                           'text'   => 'Hola, me interesa la capacitación ' . html_entity_decode(get_the_title()) . ' dictada por ' . $capacitacion_data['dictante_principal_txt'] . ' y agendada para iniciarse ' . $capacitacion_data['fecha_inicio']
                        ));

                        $whatsapp_contact_url = 'https://api.whatsapp.com/send/?' . $whatsapp_params; ?>

                        <a class="btn btn-success w-100 py-2" href="<?= $whatsapp_contact_url ?>" target="_blank"><i class="fa-brands fa-whatsapp"></i> <span class="d-none d-lg-inline">Contactar por </span>WhatsApp</a>

                        <div class="inscripcion my-3">
                           <?php
                           if (!empty($capacitacion_data['producto_relacionado'])) {
                              echo set_inscripcion_data($capacitacion_data['producto_relacionado']->ID, $capacitacion_data['tipo_inscripcion']['value'], $capacitacion_data['tipo_inscripcion']['label'], $capacitacion_data['estado_inscripciones'], $iniciada);
                           }
                           ?>
                        </div><!-- .inscripcion -->
                     </div><!-- .botonera -->
                     <div id="detalles-aranceles" class="px-4">
                        <h2>Aranceles</h2>
                        <div><?= $capacitacion_data['aranceles']; ?></div>
                     </div>
                  </aside>
                  <aside id="certificacion" class="bg-light border shadow-sm rounded overflow-hidden pt-4 px-4">
                     <h4 class="mb-3">Certifican esta propuesta:</h4>
                     <div id="certificantes" class="d-flex flex-row justify-content-around mb-4">
                        <?php if ($capacitacion_data['certificaciones'] === 'ucc') : ?>
                           <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/instituciones/coc.png" alt="COC">
                           <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/instituciones/ucc.png" alt="COC">
                        <?php else : ?>
                           <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/instituciones/coc.png" alt="COC">
                        <?php endif; ?>
                     </div>
                     <?php if ($capacitacion_data['certificaciones'] === 'ucc') : ?>
                        <div id="aclaracion-ucc" class="text-center">
                           <p class="h6 text-bg-primary p-2 mt-4 mb-2 rounded">Posgrado con Aval Universitario - UCC*</p>
                           <p><small><strong>*Certificación UCC opcional</strong><em> (solicitar trámite y presupuesto en Secretaría de la EPO)</em></small></p>
                        </div>
                     <?php endif; ?>
                     <div class="alert alert-info d-flex align-items-center" role="alert">
                        <div><i class="fa-solid fa-info-circle fa-lg me-3"></i></div>
                        <div>Actividad válida para Reválida Ética de la Matricula</div>
                     </div>
                  </aside>
               </div><!-- .col-md-4 -->
            </div><!-- .row -->

            <div class="row">
               <div class="col-12">
                  <div class="width-100 bg-light bg-gradient py-5 mt-5">
                     <div class="container">
                        <div class="row">
                           <div class="col">
                              <h2 class="text-center mb-4">Otras capacitaciones que podrían interesarte</h2>

                              <div id="capacitaciones-sugeridas" class="mb-5">
                                 <?= get_related_capacitaciones(4, $related_especialidades, get_the_ID()) ?>
                              </div><!-- #capacitaciones-sugeridas -->

                              <div id="acceso-capacitaciones-full" class="d-flex justify-content-center">
                                 <a class="btn btn-secondary btn-lg" href="<?= home_url() ?>/capacitacion/especialidades">Ver nuestra agenda completa</a>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div><!-- #primary -->
      </div><!-- #content -->

      <?php
      get_footer();
