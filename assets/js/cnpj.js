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
                    console.log(json);
                    if ('estabelecimento' in json)
                    {
                        // Nome
                        document.querySelector('input[name="nome"]').value = json.estabelecimento.nome_fantasia;
                        
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
                            
                            // VIACEP Button
                            const buttonViaCep = document.getElementById('consulta-cep-viacep');

                            if (buttonViaCep)
                            {
                                buttonViaCep.dispatchEvent(new Event('click'));
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