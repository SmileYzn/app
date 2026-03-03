<?php
exit;
include("class/Extra.php");
include("class/Setup.php");
//
Setup::iniciar();
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <title>Instalação Dashboard</title>
        
        <link rel="dns-prefetch" href="//cdn.jsdelivr.net/">
        <link rel="preconnect" href="//cdn.jsdelivr.net/">
        
        <link rel="icon" href="assets/img/favicon.svg">
        <link href="//cdn.jsdelivr.net/npm/bootstrap@5/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
        <link href="//cdn.jsdelivr.net/npm/bootstrap-icons@1/font/bootstrap-icons.min.css" rel="stylesheet" crossorigin="anonymous">
    </head>
    <body>
        <div class="page-loader position-fixed vw-100 vh-100 bg-dark-subtle opacity-75" data-bs-theme="dark">
            <div class="d-flex align-items-center justify-content-center h-100 w-100">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden"></span>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row gap-5 vh-100 justify-content-center align-items-center">
                <div class="col-12">
                    <h3 class="text-center mt-5">Assistende de instalação</h3>
                    <p class="text-center mb-5">Siga os passos para completar a instalação</p>
                    
                    <?php if(!empty($_SESSION['alert'])): ?>
                    <div class="alert alert-dismissible fade show alert-<?= (!empty($_SESSION['alert']['tipo']) ? $_SESSION['alert']['tipo'] : "primary") ?>" role="alert">
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        <?= $_SESSION['alert']['mensagem'] ?>
                    </div>
                    <?php endif; ?>
                    
                    <?php if(empty($_SESSION['conexao'])): ?>
                    <form method="POST" class="card" autocomplete="off">
                        <div class="card-header">
                            01. Dados de conexão com banco de dados
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label">Servidor</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control w-50" name="host" value="<?= (!empty($_POST['host']) ? $_POST['host'] : "") ?>" placeholder="Endereço" required>
                                        <input type="text" class="form-control" name="port" value="<?= (!empty($_POST['port']) ? $_POST['port'] : "") ?>" placeholder="Porta" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Nome do banco</label>
                                    <input type="text" class="form-control" name="dbName" value="<?= (!empty($_POST['dbName']) ? $_POST['dbName'] : "") ?>" placeholder="Nome do banco" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Usuário</label>
                                    <input type="text" class="form-control" name="user" value="<?= (!empty($_POST['user']) ? $_POST['user'] : "") ?>" placeholder="Usuário" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Senha</label>
                                    <input type="password" class="form-control" name="password" value="<?= (!empty($_POST['password']) ? $_POST['password'] : "") ?>" placeholder="Senha" required>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-success btn-sm float-end" name="instalar" value="conexao">
                                <i class="bi bi-arrow-right"></i> Avançar
                            </button>
                        </div>
                    </form>
                    <?php endif; ?>
                    
                    <?php if(!empty($_SESSION['conexao']) && empty($_SESSION['configuracao'])): ?>
                    <form method="POST" class="card" autocomplete="off">
                        <div class="card-header">
                            02. Dados do servidor
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label">Servidor SMTP</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control w-50" name="SG_SMTP_HOST" value="<?= (!empty($_POST['SG_SMTP_HOST']) ? $_POST['SG_SMTP_HOST'] : "") ?>" placeholder="Endereço" required>
                                        <input type="text" class="form-control" name="SG_SMTP_PORT" value="<?= (!empty($_POST['SG_SMTP_PORT']) ? $_POST['SG_SMTP_PORT'] : "") ?>" placeholder="Porta" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Autenticação</label>
                                    <select class="form-select" name="SG_SMTP_MODE" required>
                                        <option value="tls" <?= (!empty($_POST['SG_SMTP_MODE']) && ($_POST['SG_SMTP_MODE'] == 'tls')) ? 'selected' : '' ?>>TLS</option>
                                        <option value="ssl" <?= (!empty($_POST['SG_SMTP_MODE']) && ($_POST['SG_SMTP_MODE'] == 'ssl')) ? 'selected' : '' ?>>SSL</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" name="SG_SMTP_USER" value="<?= (!empty($_POST['SG_SMTP_USER']) ? $_POST['SG_SMTP_USER'] : "") ?>" placeholder="Email do usuário" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Senha</label>
                                    <input type="password" class="form-control" name="SG_SMTP_PASS" value="<?= (!empty($_POST['SG_SMTP_PASS']) ? $_POST['SG_SMTP_PASS'] : "") ?>" placeholder="Senha" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Nome do email</label>
                                    <input type="text" class="form-control" name="SG_SMTP_NAME" value="<?= (!empty($_POST['SG_SMTP_NAME']) ? $_POST['SG_SMTP_NAME'] : "") ?>" placeholder="Nome do usuário" required>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-secondary btn-sm" name="voltar" value="conexao" formnovalidate>
                                <i class="bi bi-arrow-left"></i> Voltar
                            </button>
                            <button type="submit" class="btn btn-success btn-sm float-end" name="instalar" value="configuracao">
                                <i class="bi bi-arrow-right"></i> Avançar
                            </button>
                        </div>
                    </form>
                    <?php endif; ?>
                    
                    <?php if(!empty($_SESSION['conexao']) && !empty($_SESSION['configuracao']) && empty($_SESSION['usuario'])): ?>
                    <form method="POST" class="card" autocomplete="off">
                        <div class="card-header">
                            03. Dados do usuário
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label">Nome</label>
                                    <input type="text" class="form-control" name="nome" value="<?= (!empty($_POST['nome']) ? $_POST['nome'] : "") ?>" placeholder="Nome completo" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" name="email" value="<?= (!empty($_POST['email']) ? $_POST['email'] : "") ?>" placeholder="Email" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Usuário</label>
                                    <input type="text" class="form-control" name="login" value="<?= (!empty($_POST['login']) ? $_POST['login'] : "") ?>" placeholder="Usuário" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Senha</label>
                                    <input type="password" class="form-control" name="senha[]" value="<?= (!empty($_POST['senha'][0]) ? $_POST['senha'][0] : "") ?>" placeholder="Senha" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Confirmar Senha</label>
                                    <input type="password" class="form-control" name="senha[]" value="<?= (!empty($_POST['senha'][1]) ? $_POST['senha'][1] : "") ?>" placeholder="Confirme a senha" required>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-secondary btn-sm" name="voltar" value="configuracao" formnovalidate>
                                <i class="bi bi-arrow-left"></i> Voltar
                            </button>
                            <button type="submit" class="btn btn-success btn-sm float-end" name="instalar" value="usuario">
                                <i class="bi bi-save"></i> Instalar
                            </button>
                        </div>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <script type="text/javascript" src="//cdn.jsdelivr.net/npm/bootstrap@5/dist/js/bootstrap.bundle.min.js"></script>
        <script type="text/javascript" src="assets/js/main.js"></script>
    </body>
</html>
