document.addEventListener('DOMContentLoaded', function()
{
    // Validar CNPJ
    function validarCNPJ(numero)
    {
        let cnpj = numero.replace(/\D/g, '');
        
        if ((cnpj === '') || (cnpj.length !== 14) || (/^(\d)\1{13}$/).test(cnpj))
        {
            return false;
        }
        
        let tamanho = cnpj.length - 2
        let numeros = cnpj.substring(0, tamanho);
        let digitos = cnpj.substring(tamanho);
        let soma = 0;
        let pos = tamanho - 7;
        
        for (let i = tamanho; i >= 1; i--)
        {
            soma += numeros.charAt(tamanho - i) * pos--;
            
            if (pos < 2)
            {
                pos = 9;
            }
        }
        
        let resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
        
        if (resultado != digitos.charAt(0))
        {
            return false;
        }
        
        tamanho = tamanho + 1;
        numeros = cnpj.substring(0, tamanho);
        soma = 0;
        pos = tamanho - 7;
        
        for (let i = tamanho; i >= 1; i--)
        {
            soma += numeros.charAt(tamanho - i) * pos--;
            
            if (pos < 2)
            {
                pos = 9;
            }
        }
        
        resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
        
        if (resultado != digitos.charAt(1))
        {
            return false;
        }
        
        return true;
    }
    
    // API CNPJ (CNPJ WS) (https://publica.cnpj.ws/cnpj/17441323000113)
    const buttonConsultaCnpjWS = document.getElementById('consulta-cnpj-cnpj-ws');
    
    if (buttonConsultaCnpjWS)
    {
        buttonConsultaCnpjWS.addEventListener('click', () =>
        {
            const cnpj = String(document.querySelector('input[name="cnpj"]').value).replace(/\D/g, '');
            
            if (validarCNPJ(cnpj))
            {
                fetch(`https://publica.cnpj.ws/cnpj/${cnpj}`).then(json => json.json()).then((json) =>
                {
                    if ('estabelecimento' in json)
                    {
                        // Nome
                        document.querySelector('input[name="nome"]').value = json.razao_social;
                        
                        // Razão Social
                        document.querySelector('input[name="razaoSocial"]').value = json.razao_social;
                        
                        // Nome Fantasia
                        document.querySelector('input[name="nomeFantasia"]').value = json.estabelecimento.nome_fantasia;
                        
                        // Inscrição Estadual
                        if ('inscricoes_estaduais' in json.estabelecimento)
                        {
                            const inputInscricaoEstadual = document.querySelector('input[name="inscricaoEstadual"]');
                            
                            if (inputInscricaoEstadual)
                            {
                                Array.from(json.estabelecimento.inscricoes_estaduais).forEach((row) =>
                                {
                                    inputInscricaoEstadual.value = row.inscricao_estadual;
                                });
                            }
                        }
                        
                        // Quadro Sócio / Administrativo
                        if ('socios' in json)
                        {
                            // Texto
                            let texto = [];
                            
                            // Loop
                            Array.from(json.socios).forEach((row) =>
                            {
                                // Nome Responsável
                                texto.push(`${row.nome} (${row.qualificacao_socio.id} - ${row.qualificacao_socio.descricao})`);
                            });
                            
                            // Input
                            document.querySelector('input[name="qsa"]').value = texto.join(' | ');
                        }
                        
                        // Abertura
                        document.querySelector('input[name="abertura"]').value = String(json.estabelecimento.data_inicio_atividade).split('/').reverse().join('-');
                        
                        // Última Atualização
                        document.querySelector('input[name="ultimaAtualizacao"]').value = (new Date(json.estabelecimento.atualizado_em)).toISOString().slice(0,16);
                        
                        // Tipo
                        document.querySelector('input[name="tipo"]').value = json.estabelecimento.tipo;
                        
                        // Porte
                        document.querySelector('input[name="porte"]').value = `${json.porte.id} - ${json.porte.descricao}`;
                        
                        // Natureza Jurídica
                        document.querySelector('input[name="naturezaJuridica"]').value = json.natureza_juridica.descricao;
                        
                        // Ente Federativo Responsável
                        document.querySelector('input[name="efr"]').value = json.responsavel_federativo;
                        
                        // Capital Social
                        document.querySelector('input[name="capitalSocial"]').value = json.capital_social;
                        
                        // Situação
                        document.querySelector('input[name="situacao"]').value = json.estabelecimento.situacao_cadastral;
                        
                        // Data da Situação
                        document.querySelector('input[name="situacaoData"]').value = String(json.estabelecimento.data_situacao_cadastral).split('/').reverse().join('-');
                        
                        // Motivo da Situação
                        document.querySelector('input[name="situacaoMotivo"]').value = json.estabelecimento.motivo_situacao_cadastral;
                        
                        // Situação (Especial)
                        document.querySelector('input[name="situacaoEspecial"]').value = json.estabelecimento.situacao_especial;
                        
                        // Data da Situação (Especial)
                        document.querySelector('input[name="situacaoEspecialData"]').value = String(json.estabelecimento.data_situacao_especial).split('/').reverse().join('-');
                        
                        // Telefone
                        const inputTelefone = document.querySelector('input[name="telefone"]');
                        
                        if (inputTelefone)
                        {
                            inputTelefone.value = (json.estabelecimento.ddd1 + json.estabelecimento.telefone1);
                            inputTelefone.dispatchEvent(new Event('input'));
                        }
                        
                        // Celular
                        if (json.estabelecimento.telefone2)
                        {
                            const inputCelular = document.querySelector('input[name="celular"]');
                            //
                            if (inputCelular)
                            {
                                inputCelular.value = (json.estabelecimento.ddd2 + json.estabelecimento.telefone2);
                                
                                inputCelular.dispatchEvent(new Event('input'));
                            }
                        }
                        
                        // Email
                        document.querySelector('input[name="email"]').value = json.estabelecimento.email;
                        
                        // Endereço
                        document.querySelector('input[name="logradouro"]').value = json.estabelecimento.logradouro;
                        
                        // Número
                        document.querySelector('input[name="numero"]').value = json.estabelecimento.numero;
                        
                        // Complemento
                        document.querySelector('input[name="complemento"]').value = json.estabelecimento.complemento;
                        
                        // Bairro
                        document.querySelector('input[name="bairro"]').value = json.estabelecimento.bairro;
                        
                        // CEP
                        const inputCep = document.querySelector('input[name="cep"]');
                        
                        if (inputCep)
                        {
                            inputCep.value = String(json.estabelecimento.cep).replace(/\D/g,'');
                            inputCep.dispatchEvent(new Event('input'));
                        }
                        
                        // VIACEP Button
                        const buttonViaCep = document.getElementById('consulta-cep-viacep');
                        
                        if (buttonViaCep)
                        {
                            buttonViaCep.dispatchEvent(new Event('click'));
                        }
                        
                        // Observações
                        if ('atividade_principal' in json.estabelecimento)
                        {
                            // Campo observações
                            let inputObservacoes = document.querySelector('textarea[name="observacoes"]');
                            //
                            // Se houver o campo
                            if (inputObservacoes)
                            {
                                // Observações
                                inputObservacoes.value = (json.estabelecimento.atividade_principal.id + ' - ' + json.estabelecimento.atividade_principal.descricao + '\n');

                                // CNAE
                                document.querySelector('input[name="cnae"]').value = inputObservacoes.value;
                                
                                // Se houver atividades
                                if ('atividades_secundarias' in json.estabelecimento)
                                {
                                    // Loop
                                    Array.from(json.estabelecimento.atividades_secundarias).forEach((row) =>
                                    {
                                        // Observações
                                        inputObservacoes.value = (inputObservacoes.value + row.id + ' - ' + row.descricao + '\n');
                                    });
                                }
                            }
                        }
                    }
                    else
                    {
                        alert(`Não foi possível buscar o CNPJ informado: ${cnpj}`);
                    }
                });
            }
            else
            {
                alert(`O CNPJ ${cnpj} não é válido.`);
            }
        });
    }
});