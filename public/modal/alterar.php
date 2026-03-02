<?php if(!empty($_BACKEND['acesso']) && in_array('editar', $_BACKEND['acesso'])): ?>
<div class="modal" id="modal-alterar" tabindex="-1" aria-labelledby="modal-alterar-label" aria-hidden="true">
    <div class="modal-dialog modal-warning">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modal-alterar-label">Confirmar Alteração</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-center my-3">Você deseja alterar os registro(s) selecionados(s)?</p>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi-x-lg"></i> Cancelar
                </button>
                <button type="submit" class="btn btn-warning" name="alterar" value="alterar">
                    <i class="bi-toggles2"></i> Alterar
                </button>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>