<?php if(!empty($_BACKEND['acesso']) && in_array('excluir', $_BACKEND['acesso'])): ?>
<div class="modal" id="modal-excluir" tabindex="-1" aria-labelledby="modal-excluir-label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modal-excluir-label">Confirmar Exclusão</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-center my-3">Você deseja excluir os registro(s) selecionados(s)?</p>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi-x-lg"></i> Cancelar
                </button>
                <button type="submit" class="btn btn-danger" name="excluir" value="excluir">
                    <i class="bi-trash"></i> Excluir
                </button>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>