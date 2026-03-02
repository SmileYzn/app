<?php
include("../../pre.php");
?>
<div class="container-fluid">
    <div class="row">
        <div class="col">
            <form class="card" method="POST" enctype="multipart/form-data" autocomplete="off">
                <div class="card-header">Adicionar</div>
                <div class="card-body">
                    <div class="row g-3 mb-3">
                        <div class="col-md-3">
                            <label class="form-label">Nome</label>
                            <input type="text" class="form-control" id="nome" name="nome" value="<?= $_POST['nome'] ?>" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Nascimento</label>
                            <input type="date" class="form-control" id="nascimento" name="nascimento" value="<?= $_POST['nascimento'] ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Unidade</label>
                            <select class="form-select" id="unidadeFK" name="unidadeFK" required>
                                <?php foreach ((new Unidade)->consultarAtivo('', 'ORDER BY nome') as $row): ?>
                                <option value="<?= $row['id'] ?>" <?= $_POST['unidadeFK'] == $row['id'] ? 'selected' : '' ?>><?= $row['nome'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Área</label>
                            <select class="form-select" id="areaFK" name="areaFK" required>
                                <?php foreach ((new Area)->consultarAtivo("id >= " . Sessao::get('areaFK'), 'ORDER BY nome') as $row): ?>
                                <option value="<?= $row['id'] ?>" <?= $_POST['areaFK'] == $row['id'] ? 'selected' : '' ?>><?= $row['nome'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Situação</label>
                            <select class="form-select" id="situacaoFK" name="situacaoFK" required>
                                <?php foreach ((new Situacao)->consultarAtivo('', 'ORDER BY nome') as $row): ?>
                                <option value="<?= $row['id'] ?>" <?= $_POST['situacaoFK'] == $row['id'] ? 'selected' : '' ?>><?= $row['nome'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Usuário</label>
                            <input class="form-control" type="text" id="login" name="login" value="<?= $_POST['login'] ?>" autocomplete="new-password" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Senha</label>
                            <input class="form-control" type="password" id="senha[0]" name="senha[0]" value="<?= $_POST['senha'][0] ?>" autocomplete="new-password" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Repetir Senha</label>
                            <input class="form-control" type="password" id="senha[1]" name="senha[1]" value="<?= $_POST['senha'][1] ?>" autocomplete="new-password" required>
                        </div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-3">
                            <label class="form-label">CEP</label>
                            <div class="input-group">
                                <input class="form-control" type="text" id="cep" name="cep" value="<?= $_POST['cep'] ?>" data-mask="00000-000">
                                <button class="btn btn-primary" type="button" id="consulta-cep-viacep"><i class="bi-search"></i></button>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Estado</label>
                            <select class="form-select" id="estadoFK" name="estadoFK" data-select="estado" required>
                                <option value="" selected></option>
                                <?php foreach ((new Estado)->consultar('', 'ORDER BY nome') as $row): ?>
                                <option value="<?= $row['id'] ?>" <?= $_POST['estadoFK'] == $row['id'] ? 'selected' : '' ?>><?= $row['nome'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Cidade</label>
                            <select class="form-select" id="cidadeFK" name="cidadeFK" data-select="cidade" required>
                                <option value="" selected></option>
                                <?php
                                if (!empty($_POST['estadoFK']))
                                {
                                    foreach ((new Cidade)->consultar("estadoFK = '{$_POST['estadoFK']}'", "ORDER BY nome") as $row)
                                    {
                                        ?>
                                        <option value="<?= $row['id'] ?>" <?= $_POST['cidadeFK'] == $row['id'] ? 'selected' : '' ?>><?= $row['nome'] ?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Bairro</label>
                            <input type="text" class="form-control" id="bairro" name="bairro" value="<?= $_POST['bairro'] ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Logradouro</label>
                            <input type="text" class="form-control" id="logradouro" name="logradouro" value="<?= $_POST['logradouro'] ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Número</label>
                            <input type="text" class="form-control" id="numero" name="numero" value="<?= $_POST['numero'] ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Complemento</label>
                            <input type="text" class="form-control" id="complemento" name="complemento" value="<?= $_POST['complemento'] ?>">
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Telefone</label>
                            <input class="form-control" type="text" id="telefone" name="telefone" value="<?= $_POST['telefone'] ?>" data-mask="00 0000 0000">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Celular</label>
                            <input class="form-control" type="text" id="celular" name="celular" value="<?= $_POST['celular'] ?>" data-mask="00 0 0000 0000">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Email</label>
                            <input class="form-control" type="email" id="email" name="email" value="<?= $_POST['email'] ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Website</label>
                            <input class="form-control" type="text" id="website" name="website" value="<?= $_POST['website'] ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Observações</label>
                            <textarea class="form-control" id="observacoes" name="observacoes"><?= $_POST['observacoes'] ?></textarea>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Imagem</label>
                            <div class="d-flex align-items-center border rounded">
                                <div class="p-2 flex-shrink-1">
                                    <img id="img-imagem" loading="lazy" src="assets/img/default.svg" width="64" class="rounded" alt="Enviar Imagem">
                                </div>
                                <label class="p-2 w-100">
                                    <span type="button" class="btn btn-sm w-100 btn-primary"><i class="bi bi-upload me-1"></i> Enviar Imagem</span>
                                    <input data-bs-img="#img-imagem" type="file" id="imagem" name="imagem" accept="image/*" hidden>
                                </label>
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