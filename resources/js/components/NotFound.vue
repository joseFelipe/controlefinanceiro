<template>
  <div class="container">
    <div class="content-wrapper" style="min-height: 1589.56px;">
      <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-12">
              <h1>Página não encontrada</h1>
            </div>
          </div>
        </div>
      </section>

      <section class="content">
        <div class="error-page">
          <h2 class="headline text-warning">404</h2>

          <div class="error-content">
            <h3>
              <i class="fas fa-exclamation-triangle text-warning"></i> Oops! Página não encontrada.
            </h3>

            <p>A página que você está tentando acessar não existe ou você não possui acesso à ela.</p>
          </div>
        </div>
      </section>
    </div>
  </div>
</template>

<script>
export default {
  mounted() {
    console.log("Component mounted.");
  }
};
</script>
