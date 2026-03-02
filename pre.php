<?php
include("autoload.php");
$_DADOS = Backend::executar();
?>
<!DOCTYPE html>
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
    <body>
        <div class="page-loader position-fixed vw-100 vh-100 bg-dark-subtle opacity-75" data-bs-theme="dark">
            <div class="d-flex align-items-center justify-content-center h-100 w-100">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden"></span>
                </div>
            </div>
        </div>
        <main>
            <header>
                <div class="navbar navbar-expand-lg bg-dark-subtle border-bottom border-dark-subtle shadow">
                    <div class="container-fluid align-items-center">
                        <a class="navbar-brand d-none d-md-block text-truncate" href="<?= $_ENV['SG_URL_BACKEND'] ?>"><?= $_BACKEND['unidade']['nome'] ?></a>
                        <button type="button" class="btn d-inline border-0 me-auto" id="aside-toggle">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="d-flex align-items-center justify-content-end">
                            <button type="button" class="btn border-0 me-2" data-bs-widget="color-mode">
                                <i class="bi"></i>
                            </button>

                            <div class="dropdown me-3">
                                <a href="#" role="button" class="nav-link dropdown-toggle d-flex align-items-center" data-bs-toggle="dropdown" aria-expanded="false" data-bs-boundary="body">
                                    <img loading="lazy" width="32" height="32" class="img-fluid rounded-circle shadow mx-2" src="<?= Sessao::get('imagem') ?>" alt="">
                                    <span class="d-none d-md-inline"><?= Sessao::get('nome') ?></span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end w-auto position-absolute" data-bs-display="static">
                                    <div class="text-center p-2">
                                        <img loading="lazy" width="64" height="64" class="img-fluid rounded-circle shadow" src="<?= Sessao::get('imagem') ?>" alt="">
                                        <p class="mt-2 mb-0"><?= Sessao::get('nome') ?></p>
                                        <small class="mt-0"><?= Sessao::get('email') ?></small>
                                    </div>
                                    <?php if(Permissao::verificar("configuracoes/usuario/editar.php", Sessao::get('areaFK'), false)): ?>
                                    <li class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="configuracoes/usuario/editar.php?id=<?= Extra::enc(Sessao::get('id')) ?>"><i class="bi-person-fill me-1"></i> Perfil</a></li>
                                    <?php endif; ?>
                                    
                                    <li class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="login/logout.php"><i class="bi-box-arrow-left me-1"></i> Sair</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            
            <aside class="p-2 border-end bg-body shadow">
                <div class="p-2">
                    <ul class="navbar-nav w-100">
                        <li class="nav-item mb-3">DASHBOARD</li>
                        <?php foreach($_BACKEND['modulo'] as $mod): ?>
                        <li class="nav-item">
                            <a class="nav-link <?= $mod['atual'] ? 'active' : '' ?>" <?= empty($mod['subModulo']) ? "href=\"{$mod['url']}\"" : "data-bs-target=\"#collapse-{$mod['id']}\" role=\"button\" data-bs-toggle=\"collapse\"" ?> title="<?= $mod['descricao'] ?>">
                                <i class="text-primary-emphasis <?= (!empty($mod['icone']) ? $mod['icone'] : 'bi-dash') ?> me-1"></i> <?= $mod['nome'] ?>
                            </a>
                            <?php if(!empty($mod['subModulo'])): ?>
                            <ul class="collapse navbar-nav w-100 <?= $mod['atual'] ? 'show' : '' ?>" id="collapse-<?= $mod['id'] ?>" tabindex="-1">
                                <?php foreach($mod['subModulo'] as $subMod): ?>
                                <li class="nav-item ms-3">
                                    <a class="nav-link <?= $subMod['atual'] ? 'active' : '' ?>" href="<?= $subMod['url'] ?>" title="<?= $subMod['descricao'] ?>">
                                        <i class="text-primary-emphasis <?= (!empty($subMod['icone']) ? $subMod['icone'] : 'bi-dash') ?> me-1"></i> <?= $subMod['nome'] ?>
                                    </a>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                            <?php endif; ?>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </aside>
            
            <section class="bg-body-secondary">
                <div class="container-fluid">
                    <?php if(!empty($_BACKEND['breadcrumb'])): ?>
                    <div class="row">
                        <div class="col justify-content-between align-items-center d-none d-md-flex">
                            <h5><?= end($_BACKEND['breadcrumb'])['nome'] ?></h5>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <?php foreach($_BACKEND['breadcrumb'] as $row): ?>
                                    <li class="breadcrumb-item"><a class="small text-decoration-none" <?= !empty($row['url']) ? "href=\"{$row['url']}\"" : '' ?>><?= $row['nome'] ?></a></li>
                                    <?php endforeach; ?>
                                </ol>
                            </nav>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <div class="row">
                        <div class="col"><?= Alert::show(); ?></div>
                    </div>
                </div>
