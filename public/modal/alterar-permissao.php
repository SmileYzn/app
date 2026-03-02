<?php if(!empty($_BACKEND['acesso']) && in_array('editar', $_BACKEND['acesso'])): ?>
<div class="modal" id="modal-alterar-acesso" aria-labelledby="modal-alterar-label" aria-hidden="true">
    <div class="modal-dialog modal-warning">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modal-alterar-acesso-label">Confirmar Alteração</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <label class="form-label" for="acesso">Acesso</label>
                        <select class="form-select" id="acesso" name="acesso[]" multiple>
                            <?php foreach ((new Acesso)->consultarAtivo() as $row): ?>
                            <option value="<?= $row['acesso'] ?>" <?= ((!empty($_POST['acesso']) && in_array($row['acesso'], $_POST['acesso'])) ? 'selected' : '') ?>><?= $row['nome'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi-x-lg"></i> Cancelar
                </button>
                <button type="submit" class="btn btn-warning" name="alterarAcesso" value="alterarAcesso">
                    <i class="bi-toggles2"></i> Alterar
                </button>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>