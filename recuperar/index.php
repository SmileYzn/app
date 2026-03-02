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

                        <?php if(empty($_GET['token'])): ?>
                        <p class="text-center small my-5">Digite o seu E-mail para recuperar a conta</p>
                        <?php else: ?>
                        <p class="text-center small my-5">Digite a nova senha para sua conta</p>
                        <?php endif; ?>
                        
                        <?= Alert::show() ?>
                        
                        <?php if(empty($_GET['token'])): ?>
                        <label class="form-label">Email</label>
                        <div class="input-group mb-4">
                            <span class="input-group-text"><i class="bi-envelope"></i></span>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Insira o email da conta" value="" autocomplete="off" required>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-block w-100" id="recuperar" name="recuperar" value="recuperar">
                            <i class="bi-arrow-repeat me-1"></i> RECUPERAR CONTA
                        </button>
                        <?php else: ?>
                        <label class="form-label">Senha</label>
                        <div class="input-group mb-4">
                            <span class="input-group-text"><i class="bi-asterisk"></i></span>
                            <input type="password" class="form-control" id="senha[0]" name="senha[0]" placeholder="Digite a senha" value="" autocomplete="off" required>
                        </div>
                        
                        <label class="form-label">Confirmar Senha</label>
                        <div class="input-group mb-4">
                            <span class="input-group-text"><i class="bi-asterisk"></i></span>
                            <input type="password" class="form-control" id="senha[1]" name="senha[1]" placeholder="Confirme a senha" value="" autocomplete="off" required>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-block w-100" id="alterar" name="alterar" value="alterar">
                            <i class="bi-arrow-repeat me-1"></i> ALTERAR SENHA
                        </button>
                        <?php endif; ?>

                        <hr class="mt-4"/>

                        <div class="w-100 text-center">
                            <a class="small text-decoration-none text-muted" href="login/">Fazer Login</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <button type="button" class="d-none" data-bs-widget="color-mode"></button>

        <script src="//cdn.jsdelivr.net/npm/bootstrap@5/dist/js/bootstrap.bundle.min.js"></script>
        <script src="//challenges.cloudflare.com/turnstile/v0/api.js" defer></script>
        <script src="assets/js/main.js"></script>
    </body>
</html>
