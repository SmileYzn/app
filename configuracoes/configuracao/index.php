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
                                <th>Chave</th>
                                <th>Valor</th>
                                <th>Descrição</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach($_DADOS as $row)
                        {
                            ?>
                            <tr>
                                <td><a href="<?= $_BACKEND['caminho'] ?>/editar.php?id=<?= Extra::enc($row['id']) ?>"><?= $row['chave'] ?></a></td>
                                <td><a href="<?= $_BACKEND['caminho'] ?>/editar.php?id=<?= Extra::enc($row['id']) ?>"><?= $row['valor'] ?></a></td>
                                <td><a href="<?= $_BACKEND['caminho'] ?>/editar.php?id=<?= Extra::enc($row['id']) ?>"><?= $row['descricao'] ?></a></td>
                            </tr>
                            <?php
                        }
                        ?>
                        </tbody>
                    </table>
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