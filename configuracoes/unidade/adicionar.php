<?php
include("../../pre.php");
?>
<div class="container-fluid">
    <div class="row">
        <div class="col">
            <form class="card" method="POST" enctype="multipart/form-data">
                <div class="card-header">Adicionar</div>
                <div class="card-body">
                    <div class="row g-3 mb-3">
                        <div class="col-md-3">
                            <label class="form-label">CNPJ</label>
                            <div class="input-group">
                                <input class="form-control" type="text" id="cnpj" name="cnpj" value="<?= $_POST['cnpj'] ?>" data-mask="00.000.000/0000-00" data-mask-reverse="true">
                                <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"></button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><button type="button" class="dropdown-item" id="consulta-cnpj-cnpj-ws"><i class="bi-cloud-arrow-down-fill text-primary me-2"></i> Obter Dados</button></li>
                                    <li><button type="button" class="dropdown-item" id="consulta-cnpj-receita-federal"><i class="bi-search text-primary me-2"></i> Consultar CNPJ</button></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Nome</label>
                            <input type="text" class="form-control" id="nome" name="nome" value="<?= $_POST['nome'] ?>" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Razão Social</label>
                            <input class="form-control" type="text" id="razaoSocial" name="razaoSocial" value="<?= $_POST['razaoSocial'] ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Nome Fantasia</label>
                            <input class="form-control" type="text" id="nomeFantasia" name="nomeFantasia" value="<?= $_POST['nomeFantasia'] ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Abertura</label>
                            <input class="form-control" type="date" id="abertura" name="abertura" value="<?= $_POST['abertura'] ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Quadro Sócio / Administrativo</label>
                            <input class="form-control" type="text" id="qsa" name="qsa" value="<?= $_POST['qsa'] ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Inscrição Estadual</label>
                            <input class="form-control" type="text" id="inscricaoEstadual" name="inscricaoEstadual" value="<?= $_POST['inscricaoEstadual'] ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Inscrição Municipal</label>
                            <input class="form-control" type="text" id="inscricaoMunicipal" name="inscricaoMunicipal" value="<?= $_POST['inscricaoMunicipal'] ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Isento</label>
                            <select class="form-select" id="isento" name="isento">
                                <option value="0" <?= $_POST['isento'] == '0' ? 'selected' : '' ?>>Não</option>
                                <option value="1" <?= $_POST['isento'] == '1' ? 'selected' : '' ?>>Sim</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Regime Tributário</label>
                            <select class="form-select" id="regimeTributarioFK" name="regimeTributarioFK" required>
                                <?php foreach ((new RegimeTributario)->consultarAtivo() as $row): ?>
                                <option value="<?= $row['id'] ?>" <?= $_POST['regimeTributarioFK'] == $row['id'] ? 'selected' : '' ?>><?= $row['nome'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-3">
                            <label class="form-label">Abertura</label>
                            <input class="form-control" type="date" id="abertura" name="abertura" value="<?= $_POST['abertura'] ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Atualização na Receita Federal</label>
                            <input class="form-control" type="datetime-local" id="ultimaAtualizacao" name="ultimaAtualizacao" value="<?= $_POST['ultimaAtualizacao'] ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">CNAE</label>
                            <input class="form-control" type="text" id="cnae" name="cnae" value="<?= $_POST['cnae'] ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tipo</label>
                            <input class="form-control" type="text" id="tipo" name="tipo" value="<?= $_POST['tipo'] ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Porte</label>
                            <input class="form-control" type="text" id="porte" name="porte" value="<?= $_POST['porte'] ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Natureza Jurídica</label>
                            <input class="form-control" type="text" id="naturezaJuridica" name="naturezaJuridica" value="<?= $_POST['naturezaJuridica'] ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Ente Federativo Responsável</label>
                            <input class="form-control" type="text" id="efr" name="efr" value="<?= $_POST['efr'] ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Capital Social</label>
                            <input class="form-control" type="text" id="capitalSocial" name="capitalSocial" value="<?= $_POST['capitalSocial'] ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Situação</label>
                            <input class="form-control" type="text" id="situacao" name="situacao" value="<?= $_POST['situacao'] ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Data da Situação</label>
                            <input class="form-control" type="date" id="situacaoData" name="situacaoData" value="<?= $_POST['situacaoData'] ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Motivo da Situação</label>
                            <input class="form-control" type="text" id="situacaoMotivo" name="situacaoMotivo" value="<?= $_POST['situacaoMotivo'] ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Situação (Especial)</label>
                            <input class="form-control" type="text" id="situacaoEspecial" name="situacaoEspecial" value="<?= $_POST['situacaoEspecial'] ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Data da Situação (Especial)</label>
                            <input class="form-control" type="date" id="situacaoEspecialData" name="situacaoEspecialData" value="<?= $_POST['situacaoEspecialData'] ?>">
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
                                    <span type="button" class="btn btn-sm w-100 btn-primary"><i class="bi-upload me-1"></i> Enviar Imagem</span>
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