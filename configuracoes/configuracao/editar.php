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
                            <label class="form-label">Chave</label>
                            <input type="text" class="form-control" id="chave" name="chave" value="<?= $_DADOS['chave'] ?>" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Valor</label>
                            <input type="text" class="form-control" id="valor" name="valor" value="<?= $_DADOS['valor'] ?>" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Descrição</label>
                            <input type="text" class="form-control" id="descricao" name="descricao" value="<?= $_DADOS['descricao'] ?>" required>
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