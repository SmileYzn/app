<?php
include("../../pre.php");
?>
<div class="container-fluid">
    <div class="row">
        <div class="col">
            <form class="card" method="POST" enctype="multipart/form-data">
                <div class="card-header">Editar</div>
                <div class="card-body">
                    <div class="row g-3 mb-3">
                        <div class="col-md-3">
                            <label class="form-label">CNPJ</label>
                            <div class="input-group">
                                <input class="form-control" type="text" id="cnpj" name="cnpj" value="<?= $_DADOS['cnpj'] ?>" data-mask="00.000.000/0000-00" data-mask-reverse="true">
                                <button class="btn btn-primary dropdown-toggle px-3" type="button" data-bs-toggle="dropdown" aria-expanded="false"></button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><button type="button" class="dropdown-item" id="consulta-cnpj-cnpj-ws"><i class="bi-cloud-arrow-down-fill text-primary me-2"></i> Obter Dados</button></li>
                                    <li><button type="button" class="dropdown-item" id="consulta-cnpj-receita-federal"><i class="bi-search text-primary me-2"></i> Consultar CNPJ</button></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Nome</label>
                            <input type="text" class="form-control" id="nome" name="nome" value="<?= $_DADOS['nome'] ?>" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Razão Social</label>
                            <input class="form-control" type="text" id="razaoSocial" name="razaoSocial" value="<?= $_DADOS['razaoSocial'] ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Nome Fantasia</label>
                            <input class="form-control" type="text" id="nomeFantasia" name="nomeFantasia" value="<?= $_DADOS['nomeFantasia'] ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Abertura</label>
                            <input class="form-control" type="date" id="abertura" name="abertura" value="<?= $_DADOS['abertura'] ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Quadro Sócio / Administrativo</label>
                            <input class="form-control" type="text" id="qsa" name="qsa" value="<?= $_DADOS['qsa'] ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Inscrição Estadual</label>
                            <input class="form-control" type="text" id="inscricaoEstadual" name="inscricaoEstadual" value="<?= $_DADOS['inscricaoEstadual'] ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Inscrição Municipal</label>
                            <input class="form-control" type="text" id="inscricaoMunicipal" name="inscricaoMunicipal" value="<?= $_DADOS['inscricaoMunicipal'] ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Isento</label>
                            <select class="form-select" id="isento" name="isento">
                                <option value="0" <?= $_DADOS['isento'] == '0' ? 'selected' : '' ?>>Não</option>
                                <option value="1" <?= $_DADOS['isento'] == '1' ? 'selected' : '' ?>>Sim</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Regime Tributário</label>
                            <select class="form-select" id="regimeTributarioFK" name="regimeTributarioFK" required>
                                <?php foreach ((new RegimeTributario)->consultarAtivo() as $row): ?>
                                <option value="<?= $row['id'] ?>" <?= $_DADOS['regimeTributarioFK'] == $row['id'] ? 'selected' : '' ?>><?= $row['nome'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-3">
                            <label class="form-label">Abertura</label>
                            <input class="form-control" type="date" id="abertura" name="abertura" value="<?= $_DADOS['abertura'] ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Atualização na Receita Federal</label>
                            <input class="form-control" type="datetime-local" id="ultimaAtualizacao" name="ultimaAtualizacao" value="<?= $_DADOS['ultimaAtualizacao'] ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">CNAE</label>
                            <input class="form-control" type="text" id="cnae" name="cnae" value="<?= $_DADOS['cnae'] ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tipo</label>
                            <input class="form-control" type="text" id="tipo" name="tipo" value="<?= $_DADOS['tipo'] ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Porte</label>
                            <input class="form-control" type="text" id="porte" name="porte" value="<?= $_DADOS['porte'] ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Natureza Jurídica</label>
                            <input class="form-control" type="text" id="naturezaJuridica" name="naturezaJuridica" value="<?= $_DADOS['naturezaJuridica'] ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Ente Federativo Responsável</label>
                            <input class="form-control" type="text" id="efr" name="efr" value="<?= $_DADOS['efr'] ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Capital Social</label>
                            <input class="form-control" type="text" id="capitalSocial" name="capitalSocial" value="<?= $_DADOS['capitalSocial'] ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Situação</label>
                            <input class="form-control" type="text" id="situacao" name="situacao" value="<?= $_DADOS['situacao'] ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Data da Situação</label>
                            <input class="form-control" type="date" id="situacaoData" name="situacaoData" value="<?= $_DADOS['situacaoData'] ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Motivo da Situação</label>
                            <input class="form-control" type="text" id="situacaoMotivo" name="situacaoMotivo" value="<?= $_DADOS['situacaoMotivo'] ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Situação (Especial)</label>
                            <input class="form-control" type="text" id="situacaoEspecial" name="situacaoEspecial" value="<?= $_DADOS['situacaoEspecial'] ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Data da Situação (Especial)</label>
                            <input class="form-control" type="date" id="situacaoEspecialData" name="situacaoEspecialData" value="<?= $_DADOS['situacaoEspecialData'] ?>">
                        </div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-3">
                            <label class="form-label">CEP</label>
                            <div class="input-group">
                                <input class="form-control" type="text" id="cep" name="cep" value="<?= $_DADOS['cep'] ?>" data-mask="00000-000">
                                <button class="btn btn-primary" type="button" id="consulta-cep-viacep"><i class="bi-search"></i></button>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Estado</label>
                            <select class="form-select" id="estadoFK" name="estadoFK" data-select="estado" required>
                                <option value="" selected></option>
                                <?php foreach ((new Estado)->consultar('', 'ORDER BY nome') as $row): ?>
                                <option value="<?= $row['id'] ?>" <?= $_DADOS['estadoFK'] == $row['id'] ? 'selected' : '' ?>><?= $row['nome'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Cidade</label>
                            <select class="form-select" id="cidadeFK" name="cidadeFK" data-select="cidade" required>
                                <option value="" selected></option>
                                <?php
                                if (!empty($_DADOS['estadoFK']))
                                {
                                    foreach ((new Cidade)->consultar("estadoFK = '{$_DADOS['estadoFK']}'", "ORDER BY nome") as $row)
                                    {
                                        ?>
                                        <option value="<?= $row['id'] ?>" <?= $_DADOS['cidadeFK'] == $row['id'] ? 'selected' : '' ?>><?= $row['nome'] ?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Bairro</label>
                            <input type="text" class="form-control" id="bairro" name="bairro" value="<?= $_DADOS['bairro'] ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Logradouro</label>
                            <input type="text" class="form-control" id="logradouro" name="logradouro" value="<?= $_DADOS['logradouro'] ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Número</label>
                            <input type="text" class="form-control" id="numero" name="numero" value="<?= $_DADOS['numero'] ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Complemento</label>
                            <input type="text" class="form-control" id="complemento" name="complemento" value="<?= $_DADOS['complemento'] ?>">
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Telefone</label>
                            <input class="form-control" type="text" id="telefone" name="telefone" value="<?= $_DADOS['telefone'] ?>" data-mask="00 0000 0000">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Celular</label>
                            <input class="form-control" type="text" id="celular" name="celular" value="<?= $_DADOS['celular'] ?>" data-mask="00 0 0000 0000">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Email</label>
                            <input class="form-control" type="email" id="email" name="email" value="<?= $_DADOS['email'] ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Website</label>
                            <input class="form-control" type="text" id="website" name="website" value="<?= $_DADOS['website'] ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Observações</label>
                            <textarea class="form-control" id="observacoes" name="observacoes"><?= $_DADOS['observacoes'] ?></textarea>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Imagem</label>
                            <div class="d-flex align-items-center border rounded">
                                <div class="p-2 flex-shrink-1">
                                    <img id="img-imagem" loading="lazy" src="<?= $_DADOS['imagem'] ?>" width="64" class="rounded" alt="<?= $_DADOS['imagem'] ?>">
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