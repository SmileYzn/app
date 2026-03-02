document.addEventListener('DOMContentLoaded', function()
{
    // Select (Multiple)
    const selectMultiple = document.querySelectorAll('select[multiple]');
    //
    if (selectMultiple.length)
    {
        selectMultiple.forEach((select) =>
        {
            new TomSelect(select,
            {
                plugins:
                {
                    remove_button:
                    {
                        title: 'Remover Seleção', 
                    }
                },
                render:
                {
                    no_results: function(data)
                    {
                        return (`<div class="no-results">Nenhum resultado: <b>${data.input}</b></div>`);
                    }
                }
            });
        });
    }
    //
    // Select (Pesquisa)
    const selectPesquisa = document.querySelectorAll('select[data-select]');
    //
    if(selectPesquisa.length)
    {
        selectPesquisa.forEach((el) =>
        {
            new TomSelect(el,
            {
                maxOptions:  el.dataset.maxOptions || 100,
                render:
                {
                    no_results: function(data)
                    {
                        return (`<div class="no-results">Nenhum resultado: <b>${data.input}</b></div>`);
                    }
                }
            });
        });
    }
    //
    // Select (AJAX)
    const selectAjax = document.querySelectorAll('select[data-ajax]');
    //
    if(selectAjax.length)
    {
        selectAjax.forEach((el) =>
        {
            new TomSelect(el,
            {
		valueField: el.dataset.valueField || 'id',
                labelField: el.dataset.labelField || 'nome',
                searchField: el.dataset.searchField || 'nome',
                maxOptions:  el.dataset.maxOptions || 100,
                render:
                {
                    no_results: function(data)
                    {
                        return (`<div class="no-results">Nenhum resultado: <b>${data.input}</b></div>`);
                    }
                },
                shouldLoad: function(query)
                {
                    return (query.length >= (el.dataset.searchMinLength || 3));
                },
		load: function(query, callback)
                {
                    fetch(`public/?ajax=${el.dataset.ajax}&pesquisa=${query}`).then(resposta => resposta.json()).then(json =>
                    {
                        callback(json);
                    }).catch(() =>
                    {
                        callback();
                    });
		}
            });
        });
    }
});
