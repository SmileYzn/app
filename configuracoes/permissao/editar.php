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
                        <div class="col-md-4">
                            <label class="form-label">Módulo</label>
                            <select class="form-select" id="moduloFK" name="moduloFK" required>
                                <?php
                                $mods = (new Modulo)->consultarAtivo("moduloFK = 0", "ORDER BY ordem");
                                foreach ($mods as $mod)
                                {
                                    ?>
                                    <optgroup label="<?= $mod['ordem'] ?>.  <?= $mod['nome'] ?>">
                                        <option value="<?= $mod['id'] ?>" <?= $_DADOS['moduloFK'] == $mod['id'] ? 'selected' : '' ?>><?= $mod['ordem'] ?>. <?= $mod['nome'] ?></option>
                                        <?php
                                        $subs = (new Modulo)->consultarAtivo("moduloFK = {$mod['id']}", "ORDER BY ordem");
                                        foreach ($subs as $sub)
                                        {
                                            ?>
                                            <option value="<?= $sub['id'] ?>" <?= $_DADOS['moduloFK'] == $sub['id'] ? 'selected' : '' ?>><?= $sub['ordem'] ?>. <?= $sub['nome'] ?></option>
                                            <?php
                                        }
                                        ?>
                                    </optgroup>Acesso
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Acesso</label>
                            <select class="form-select" id="acesso" name="acesso[]" multiple required>
                                <?php foreach((new Acesso)->consultarAtivo() as $row): ?>
                                <option value="<?= $row['acesso'] ?>" <?= ((!empty($_DADOS['acesso']) && (strpos($_DADOS['acesso'], $row['acesso']) !== false)) ? 'selected' : '') ?>><?= $row['nome'] ?></option>
                                <?php endforeach; ?>
                            </select>
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