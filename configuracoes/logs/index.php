<?php
include("../../pre.php");
?>
<div class="container-fluid">
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header"><?= $_BACKEND['titulo'] ?></div>
                <div class="card-body">
                    <div class="g-2 d-flex gap-2">
                        <a class="btn btn-app btn-primary" href="#collapse-pesquisa" data-bs-toggle="collapse">
                            <i class="bi-search"></i> Pesquisar
                        </a>
                        <?php if(in_array('excluir', $_BACKEND['acesso'])): ?>
                        <a class="btn btn-app btn-danger btn-trash" href="#" data-bs-toggle="modal" data-bs-target="#modal-excluir">
                            <span class="position-absolute top-0 z-3 start-100 translate-middle badge bg-primary">0</span>
                            <i class="bi-trash"></i> Excluir
                        </a>
                        <?php endif;?>
                    </div>
                </div>
                <form method="POST" action="<?= $_SERVER['PHP_SELF'] ?>" class="card-body collapse <?= !empty($_SESSION[$_SERVER['SCRIPT_NAME']]) ? 'show' : '' ?>" id="collapse-pesquisa">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Período</label>
                            <div class="input-group">
                                <input type="date" class="form-control" id="periodo[0]" name="periodo[0]" value="<?= !empty($_SESSION[$_SERVER['SCRIPT_NAME']]['periodo'][0]) ? $_SESSION[$_SERVER['SCRIPT_NAME']]['periodo'][0] : '' ?>">
                                <span class="input-group-text"><i class="bi-arrows-expand-vertical"></i></span>
                                <input type="date" class="form-control" id="periodo[1]" name="periodo[1]" value="<?= !empty($_SESSION[$_SERVER['SCRIPT_NAME']]['periodo'][1]) ? $_SESSION[$_SERVER['SCRIPT_NAME']]['periodo'][1] : '' ?>">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <div class="input-group mb-3">
                                <button type="submit" class="btn btn-primary" id="buscar" name="buscar" value="buscar" title="buscar">
                                    <i class="bi-search"></i>
                                </button>
                                <?php if(!empty($_SESSION[$_SERVER['SCRIPT_NAME']])): ?>
                                <button type="submit" class="btn btn-primary" id="limpar" name="limpar" value="limpar" title="Limpar">
                                    <i class="bi-x-lg"></i>
                                </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </form>
                <?php if(!empty($_DADOS)): ?>
                <form method="POST" class="card-body table-responsive p-0">
                    <table class="table table-striped table-hover align-middle text-nowrap text-center m-0">
                        <thead>
                            <tr>
                                <th><input class="form-check-input" type="checkbox"></th>
                                <th>Nome</th>
                                <th>Data</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach($_DADOS as $key => $row)
                        {
                            ?>
                            <tr>
                                <td><input class="form-check-input" type="checkbox" name="id[<?= Extra::enc($key) ?>]" value="<?= Extra::enc($key) ?>"></td>
                                <td><a href="<?= $_BACKEND['caminho'] ?>/editar.php?id=<?= Extra::enc($key) ?>"><?= $row['basename'] ?></a></td>
                                <td><a href="<?= $_BACKEND['caminho'] ?>/editar.php?id=<?= Extra::enc($key) ?>"><?= (new DateTime($row['filename']))->format('d/m/Y') ?></a></td>
                            </tr>
                            <?php
                        }
                        ?>
                        </tbody>
                    </table>
                    <?php include_once("{$_ENV['SG_PATH_PUBLIC']}modal/excluir.php"); ?>
                </form>
                <?php else: ?>
                <div class="card-body">
                    <div class="row justify-content-center">
                        <div class="col">
                            <p class="text-center"><?= Paginacao::frase() ?></p>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php
include("../../pro.php");
