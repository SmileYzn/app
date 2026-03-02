document.addEventListener('DOMContentLoaded', function()
{
    // Summernote Editor
    const textSummernote = document.querySelectorAll('textarea[data-editor="summernote"]');
    //
    if(textSummernote.length)
    {
        textSummernote.forEach((element) =>
        {
            $(element).summernote
            ({
                lang: 'pt-BR',
                dialogsInBody: true,
                dialogsFade: false,
                disableDragAndDrop: true,
                tabDisable: true,
                tableClassName: 'table table-bordered table-hover table-sm text-sm',
                callbacks:
                {
                    onImageUpload: function (files)
                    {
                        const editor = $(this);
                        //
                        const formData = new FormData; 
                        //
                        formData.append('textarea', 'summernote');
                        //
                        Array.from(files).forEach((v, k) =>
                        {
                            formData.append(`arquivo[${k}]`, v);
                        });
                        //
                        var req = new XMLHttpRequest;
                        //
                        req.onload = function ()
                        {
                            if (this.readyState === 4 && this.status === 200)
                            {
                                if (this.responseText.length > 0)
                                {
                                    JSON.parse(this.responseText).forEach((url) =>
                                    {
                                        editor.summernote('editor.insertImage', url, (el) =>
                                        {
                                            el.attr('class', 'img img-fluid');
                                        });
                                    });
                                }
                            }
                        };
                        //
                        req.open('POST', 'public/index.php', true);
                        req.send(formData);
                    }
                }
            });
        });
    }
});