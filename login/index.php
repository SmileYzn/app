<?php
include("../autoload.php");
Backend::executar();
?>
<!doctype html>
<html lang="pt-br">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <base href="<?= $_ENV['SG_URL_BACKEND'] ?>">
        <title><?= $_BACKEND['unidade']['nome'] ?> - <?= $_BACKEND['titulo'] ?></title>
        
        <link rel="dns-prefetch" href="//cdn.jsdelivr.net/">
        <link rel="preconnect" href="//cdn.jsdelivr.net/">
        
        <link rel="icon" href="assets/img/favicon.svg">
        <link rel="stylesheet" href="assets/css/main.css">
    </head>
    
    <body class="bg-secondary-subtle">
        <div class="container">
            <div class="row gap-5 vh-100 justify-content-center align-items-center">
                <div class="col-12 col-xs-8 col-sm-8 col-md-8 col-lg-6 col-xl-4">
                    <form method="POST" autocomplete="off">
                        <?= Turnstile::getWidget(); ?>
                        
                        <h6 class="display-6 fw-light text-center"><?= $_BACKEND['unidade']['nome'] ?></h6>

                        <p class="text-center small my-5">Faça login para iniciar a sessão</p>
                        
                        <?= Alert::show() ?>

                        <label class="form-label">Usuário</label>
                        <div class="input-group mb-4">
                            <span class="input-group-text"><i class="bi-person-fill"></i></span>
                            <input type="text" class="form-control" id="login" name="login" placeholder="Digite seu usuário" value="" autocomplete="off" required>
                        </div>

                        <label class="form-label">Senha</label>
                        <div class="input-group mb-4">
                            <span class="input-group-text"><i class="bi-lock"></i></span>
                            <input type="password" class="form-control" id="senha" name="senha" placeholder="Digite sua senha" value="" autocomplete="off" required>
                        </div>
                        
                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" id="cookie" name="cookie" value="1">
                            <label class="form-check-label" for="cookie">Lembrar Sessão</label>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-block w-100" id="entrar" name="entrar" value="entrar">
                            <i class="bi-box-arrow-in-right me-1"></i> ENTRAR
                        </button>

                        <hr class="mt-4"/>

                        <div class="w-100 text-center">
                            <a class="small text-decoration-none text-muted" href="recuperar/">Recuperar Conta</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <button type="button" class="d-none" data-bs-widget="color-mode"></button>

        <script src="//cdn.jsdelivr.net/npm/bootstrap@5/dist/js/bootstrap.bundle.min.js"></script>
        <script src="//challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
        <script src="assets/js/main.js"></script>
    </body>
</html>
