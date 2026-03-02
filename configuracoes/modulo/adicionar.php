<?php
include("../../pre.php");
?>
<div class="container-fluid">
    <div class="row">
        <div class="col">
            <form class="card" method="POST" enctype="multipart/form-data">
                <div class="card-header">Adicionar</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Módulo Pai</label>
                            <select class="form-select" id="moduloFK" name="moduloFK" required>
                                <option value="0" <?= (($_POST['moduloFK'] == '0') ? 'selected' : '') ?>>Nenhum</option>
                                <?php
                                $run = (new Modulo)->consultarAtivo('moduloFK = 0', 'ORDER BY ordem, nome');
                                foreach($run as $row)
                                {
                                    ?>
                                    <option value="<?= $row['id'] ?>" <?= (($_POST['moduloFK'] == $row['id']) ? 'selected' : '') ?>><?= $row['nome'] ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Ordem</label>
                            <input type="number" class="form-control" id="ordem" name="ordem" value="<?= $_POST['ordem'] ?>" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Nome</label>
                            <input type="text" class="form-control" id="nome" name="nome" value="<?= $_POST['nome'] ?>" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Descrição</label>
                            <input type="text" class="form-control" id="descricao" name="descricao" value="<?= $_POST['descricao'] ?>" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">URL</label>
                            <input type="text" class="form-control" id="url" name="url" value="<?= $_POST['url'] ?>" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Ocultar</label>
                            <select class="form-select" id="ocultar" name="ocultar" required>
                                <option value="0" <?= (($_POST['ocultar'] == '0') ? 'selected' : '') ?>>Não</option>
                                <option value="1" <?= (($_POST['ocultar'] == '1') ? 'selected' : '') ?>>Sim</option>
                            </select>
                        </div> 
                        <div class="col-md-3">
                            <label class="form-label">Ícone</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="icone" name="icone" value="<?= $_POST['icone'] ?>" onkeyup="document.getElementById('bi-icone').setAttribute('class', this.value)" required>
                                <span class="input-group-text"><span id="bi-icone" class="<?= $_POST['icone'] ?>"></span></span>
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