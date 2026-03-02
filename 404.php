<!doctype html>
<html lang="pt-br">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <base href="<?= $_ENV['SG_URL_BACKEND'] ?>">
        <title>404 - Página não encontrada.</title>
        
        <base href="<?= (!empty($_SERVER['HTTPS']) ? "https://" : "http://")?><?= $_SERVER['HTTP_HOST'] ?>/<?= basename(__DIR__) ?>/">
        
        <link rel="icon" href="assets/img/favicon.svg">
        <link rel="stylesheet" href="assets/css/main.css">
    </head>
    <body class="bg-secondary-subtle">             
        <div class="container">
            <div class="row gap-5 vh-100 justify-content-center align-items-center">
                <div class="col-12 text-center">
                    <h1 class="display-1 text-warning">404</h1>
                    <h6 class="display-6"><i class="bi-exclamation-triangle-fill text-warning me-1"></i> Página não encontrada</h6>
                    <small>A página que você procura não foi encontrada ou você não tem permissão de acesso</small>
                    <br>
                    <a class="btn btn-link text-warning text-decoration-none mt-5" href="./">Clique aqui para voltar ao início</a>
                </div>
            </div>
        </div>
        <button type="button" class="d-none" data-bs-widget="color-mode"></button>
        
        <script src="//cdn.jsdelivr.net/npm/bootstrap@5/dist/js/bootstrap.bundle.min.js"></script>
        <script src="assets/js/main.js"></script>
    </body>
</html>
