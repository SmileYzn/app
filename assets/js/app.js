document.addEventListener('DOMContentLoaded', () =>
{
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // Checkbox for tables
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    const checkboxList = document.querySelectorAll('input[type="checkbox"]');
    //
    if (checkboxList)
    {
        const checkboxBodyList = document.querySelectorAll('td input[type="checkbox"]');
        const buttonTrashList = document.querySelectorAll('.btn-trash');
        
        checkboxList.forEach((checkbox) =>
        {
            checkbox.addEventListener('change', () =>
            {
                if (checkbox.parentElement.nodeName === 'TH')
                {
                    checkboxBodyList.forEach((checkboxBody) =>
                    {
                        checkboxBody.checked = checkbox.checked;
                    });
                }
                
                if (buttonTrashList)
                {
                    const checkboxChecked = document.querySelectorAll('td input[type="checkbox"]:checked');

                    buttonTrashList.forEach((buttonTrash) =>
                    {
                        buttonTrash.style.display = (checkboxChecked.length ? 'block' : 'none');
                        
                        if (buttonTrash.firstElementChild)
                        {
                            buttonTrash.firstElementChild.innerText = checkboxChecked.length;
                        }
                    });
                }
            });
        });
    }
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // Consultar CNPJ (Receita Federal)
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    const buttonConsultaReceita = document.getElementById('consulta-cnpj-receita-federal');
    //
    if (buttonConsultaReceita)
    {
        let input = document.querySelector('input[name="cnpj');
        
        if (input)
        {
            buttonConsultaReceita.addEventListener('click', () =>
            {
                if (input.value)
                {
                    window.open('https://solucoes.receita.fazenda.gov.br/Servicos/cnpjreva/cnpjreva_Solicitacao.asp?cnpj=' + input.value, '_blank').focus();
                }
                else
                {
                    input.focus();
                    alert('Preencha o CNPJ do cadastro.');
                }
            });
        }
    }
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // Image Uploader
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    let inputImagem = document.querySelectorAll('input[type="file"][data-bs-img]');
    
    if (inputImagem)
    {
        inputImagem.forEach((input) =>
        {
            input.addEventListener('change', () =>
            {
                // Se houver arquivo(s) selecionado(s) no input
                if(input.files.length > 0)
                {
                    // Buscar a tag <img>
                    let imagem = document.querySelector(input.dataset.bsImg);
                    
                    // Se encontrar a tag img correspondente
                    if(imagem)
                    {
                        // Loop para cada arquivo 
                        for (const arquivo of input.files)
                        {
                            // Se for do tipo que o atributo accept do input
                            if(arquivo.type.match(input.accept))
                            {
                                // File Reader
                                let fileReader = new FileReader();
                                //
                                // no fim do carregamento do file reader
                                fileReader.onloadend = function(e)
                                {
                                    // Se houver o resultado
                                    if(e.target.result)
                                    {
                                        imagem.setAttribute('src', e.target.result);
                                    }
                                }
                                //
                                // Ler arquivo enviado como dados
                                fileReader.readAsDataURL(arquivo);
                            }
                            else
                            {
                                // Limpar input file
                                input.value = '';
                                //
                                // Restaurar imagem original
                                imagem.setAttribute('src', 'assets/img/default.svg');
                                //
                                // Alerta
                                alert('Formato ' + arquivo.type + ' inválido.');
                            }
                        }
                    }   
                }
            });
        });
    }
});
