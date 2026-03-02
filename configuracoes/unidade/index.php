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
                        <?php if(in_array('adicionar', $_BACKEND['acesso'])): ?>
                        <a class="btn btn-app btn-success" href="<?= $_BACKEND['caminho'] ?>/adicionar.php">
                            <i class="bi-plus-lg"></i> Adicionar
                        </a>
                        <?php endif; ?>
                        <a class="btn btn-app btn-primary" href="#collapse-pesquisa" data-bs-toggle="collapse">
                            <i class="bi-search"></i> Pesquisar
                        </a>
                        <?php if(in_array('excluir', $_BACKEND['acesso'])): ?>
                        <a class="btn btn-app btn-danger btn-trash" href="#" data-bs-toggle="modal" data-bs-target="#modal-excluir">
                            <span class="position-absolute top-0 z-3 start-100 translate-middle badge bg-primary">0</span>
                            <i class="bi-trash"></i> Excluir
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
                <form method="POST" action="<?= $_SERVER['PHP_SELF'] ?>" class="card-body collapse <?= !empty($_SESSION[$_SERVER['SCRIPT_NAME']]) ? 'show' : '' ?>" id="collapse-pesquisa">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Pesquisar</label>
                            <input type="text" class="form-control" id="pesquisa" name="pesquisa" value="<?= $_SESSION[$_SERVER['SCRIPT_NAME']]['pesquisa'] ?>">
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
                                <th>Imagem</th>
                                <th>Nome</th>
                                <th>CNPJ</th>
                                <th>Email</th>
                                <th>Local</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach($_DADOS as $row)
                        {
                            $estado = (new Estado)->id($row['estadoFK']);
                            $cidade = (new Cidade)->id($row['cidadeFK']);
                            ?>
                            <tr>
                                <td><input class="form-check-input" type="checkbox" name="id[<?= $row['id'] ?>]" value="<?= $row['id'] ?>"></td>
                                <td><a href="<?= $_BACKEND['caminho'] ?>/editar.php?id=<?= Extra::enc($row['id']) ?>"><img src="<?= $row['imagem'] ?>" class="img rounded" width="32" alt="" loading="lazy"></a></td>
                                <td><a href="<?= $_BACKEND['caminho'] ?>/editar.php?id=<?= Extra::enc($row['id']) ?>"><?= $row['nome'] ?></a></td>
                                <td><a href="<?= $_BACKEND['caminho'] ?>/editar.php?id=<?= Extra::enc($row['id']) ?>"><?= $row['cnpj'] ?></a></td>
                                <td><a href="<?= $_BACKEND['caminho'] ?>/editar.php?id=<?= Extra::enc($row['id']) ?>"><?= $row['email'] ?></a></td>
                                <td><a href="<?= $_BACKEND['caminho'] ?>/editar.php?id=<?= Extra::enc($row['id']) ?>"><?= "{$cidade['nome']} - {$estado['uf']}" ?></a></td>
                            </tr>
                            <?php
                        }
                        ?>
                        </tbody>
                    </table>
                    <?php include_once("{$_ENV['SG_PATH_PUBLIC']}modal/excluir.php"); ?>
                </form>
                <div class="card-footer">
                    <div class="row justify-content-center">
                        <div class="col-md-12 col-lg-4 col-xl-3 col-xxl-3">
                            <?= Paginacao::select() ?>
                        </div>
                    </div>
                </div>
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