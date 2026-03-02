<?php
include("../../pre.php");
?>
<div class="container-fluid">
    <div class="row">
        <div class="col">
            <form class="card" method="POST" enctype="multipart/form-data">
                <div class="card-header">Editar</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Módulo Pai</label>
                            <select class="form-select" id="moduloFK" name="moduloFK" required>
                                <option value="0" <?= (($_DADOS['moduloFK'] == '0') ? 'selected' : '') ?>>Nenhum</option>
                                <?php
                                $run = (new Modulo)->consultarAtivo('moduloFK = 0', 'ORDER BY ordem, nome');
                                foreach($run as $row)
                                {
                                    ?>
                                    <option value="<?= $row['id'] ?>" <?= (($_DADOS['moduloFK'] == $row['id']) ? 'selected' : '') ?>><?= $row['nome'] ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Ordem</label>
                            <input type="number" class="form-control" id="ordem" name="ordem" value="<?= $_DADOS['ordem'] ?>" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Nome</label>
                            <input type="text" class="form-control" id="nome" name="nome" value="<?= $_DADOS['nome'] ?>" required>
                        </div> 
                        <div class="col-md-3">
                            <label class="form-label">Descrição</label>
                            <input type="text" class="form-control" id="descricao" name="descricao" value="<?= $_DADOS['descricao'] ?>" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">URL</label>
                            <input type="text" class="form-control" id="url" name="url" value="<?= $_DADOS['url'] ?>" required>
                        </div> 
                        <div class="col-md-3">
                            <label class="form-label">Ocultar</label>
                            <select class="form-select" id="ocultar" name="ocultar" required>
                                <option value="0" <?= (($_DADOS['ocultar'] == '0') ? 'selected' : '') ?>>Não</option>
                                <option value="1" <?= (($_DADOS['ocultar'] == '1') ? 'selected' : '') ?>>Sim</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Ícone</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="icone" name="icone" value="<?= $_DADOS['icone'] ?>" onchange="document.getElementById('bi-icone').setAttribute('class', this.value)" required>
                                <span class="input-group-text"><span id="bi-icone" class="<?= $_DADOS['icone'] ?>"></span></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-success" id="salvar" name="salvar" value="salvar">
                        <i class="bi-floppy me-1"></i> Salvar
                    </button>
                    <a href="<?= $_BACKEND['caminho'] ?>" class="btn btn-secondary">
                        <i class="bi-x-lg me-1"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
<?php
include("../../pro.php");