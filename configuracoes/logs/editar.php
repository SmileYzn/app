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
                        <div class="col-md-12">
                            <textarea class="form-control" rows="20" onfocus="this.setSelectionRange(this.value.length, this.value.length);" readonly><?= $_DADOS ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
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