<?php get_header(); ?>
<?php the_post(); ?>
<div class="w-100 page-img-header border-bottom border-subtle-secondary">
  <div class="container">
    <div class="col-12">
      <header class="py-3 py-lg-5">
        <h1 class="m-0 text-primary"><?php the_title(); ?></h1>
      </header>
    </div>
  </div>
</div>

<div id="content" class="site-content container py-5">
  <div id="primary" class="content-area">
    <div class="row">
      <div class="col-12">
        <main id="main" class="site-main">
          <div class="entry-content">
            <?php the_content(); ?>
          </div>
        </main>
      </div>
    </div>
  </div>
</div>

<?php get_footer();
