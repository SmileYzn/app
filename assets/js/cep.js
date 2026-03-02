document.addEventListener('DOMContentLoaded', function()
{
    // Estado / Cidades
    const selectEstado = document.querySelector('select[name="estadoFK"]');
    const selectCidade = document.querySelector('select[name="cidadeFK"]');
    //
    if(selectEstado && selectCidade)
    {
        selectEstado.addEventListener('change', (event) =>
        {
            fetch(`public/?ajax=cidade&pesquisa=${selectEstado.value}`).then(resultado => resultado.json()).then((resultado) =>
            {
                selectCidade.innerHTML = '';
                
                if (selectCidade.tomselect)
                {
                    selectCidade.tomselect.clearOptions();
                    selectCidade.tomselect.sync(true);
                }
                
                selectCidade.append(new Option('', '', false, false));
                
                Array.from(resultado).forEach((row) =>
                {
                    selectCidade.append(new Option(row.nome, row.id, false, false));
                });
                
                if (event.detail && event.detail.ibge)
                {
                    selectCidade.value = event.detail.ibge;
                }
                
                selectCidade.dispatchEvent(new Event('change'));
                
                if (selectCidade.tomselect)
                {
                    selectCidade.tomselect.sync(true);
                }
            });
        });
    }
    //
    // API CEP (ViaCep)
    const buttonViaCep = document.getElementById('consulta-cep-viacep');
    //
    if (buttonViaCep)
    {
        const inputViaCep = document.querySelector('input[name="cep"]');
        
        if (inputViaCep)
        {
            buttonViaCep.addEventListener('click', () =>
            {
                const cep = inputViaCep.value.replace(/\D/g, '');
                // 
                if(cep.length === 8)
                {
                    fetch(`https://viacep.com.br/ws/${cep}/json/`).then(viacep => viacep.json()).then((viacep) =>
                    {
                        if (!('erro' in viacep))
                        {
                            if (viacep.ibge)
                            {
                                if (selectEstado)
                                {
                                    selectEstado.value = viacep.ibge.substring(0, 2);
                                    
                                    selectEstado.dispatchEvent(new CustomEvent('change', {detail: viacep}));
                                    
                                    if (selectCidade.tomselect)
                                    {
                                        selectEstado.tomselect.sync(true);
                                    }
                                }
                            }
                            
                            if (viacep.bairro)
                            {
                                document.querySelector('input[name="bairro"]').value = viacep.bairro;
                            }
                            
                            if (viacep.logradouro)
                            {
                                document.querySelector('input[name="logradouro"]').value = viacep.logradouro;
                            }
                            
                            if (viacep.complemento)
                            {
                                document.querySelector('input[name="complemento"]').value = viacep.complemento;
                            }
                        }
                        else
                        {
                            alert(`CEP ${cep} não encontrado.`);
                        }
                    });
                }
                else
                {
                    alert(`O CEP ${cep} não é válido`);
                }
            });
        }
    }
});
