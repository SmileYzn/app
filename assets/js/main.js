document.addEventListener('DOMContentLoaded', () =>
{
    // Color Mode
    const widgetColorMode = document.querySelector('button[data-bs-widget="color-mode"]');
    
    if (widgetColorMode)
    {
        const getColorMode = () =>
        {
            let colorMode = localStorage.getItem('data-color-mode');

            if (colorMode)
            {
                return colorMode;
            }

            return (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
        }

        const setColorMode = (mode) =>
        {
            localStorage.setItem('data-color-mode', mode);

            document.documentElement.setAttribute('data-bs-theme', mode);

            if (widgetColorMode.firstElementChild)
            {
                if (mode === 'dark')
                {
                    widgetColorMode.firstElementChild.className = 'bi bi-moon-fill';
                }
                else if (mode === 'light')
                {
                    widgetColorMode.firstElementChild.className = 'bi bi-sun-fill';
                }
            }
        }
        
        setColorMode(getColorMode());

        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () =>
        {
            setColorMode(window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
        });

        widgetColorMode.addEventListener('click', () =>
        {
            const mode = localStorage.getItem('data-color-mode');

            if (mode === 'dark')
            {
                setColorMode('light');
            }
            else if (mode === 'light')
            {
                setColorMode('dark');
            }
        });
    }
    //
    // Page Loader
    const pageLoader = document.querySelector('.page-loader');

    if (pageLoader)
    {
        pageLoader.remove();
    }
    //
    // Sidebar Toggle
    const main = document.querySelector('main');
    const aside = document.querySelector('aside');
    
    if (main && aside)
    {
        const asideToggle = (show) =>
        {
            const isMobile = window.matchMedia("(max-width: 767.98px)").matches;
            
            if (isMobile)
            {
                if (show)
                {
                    aside.style.display = 'block';
                    main.style.gridTemplateAreas = "'aside header' 'section section' 'aside footer'";
                }
                else
                {
                    aside.style.display = 'none';
                    main.style.gridTemplateAreas = "'header header' 'section section' 'footer footer'";
                }
            }
            else
            {
                if (show)
                {
                    aside.style.display = 'block';
                    main.style.gridTemplateAreas = "'header header' 'aside section' 'aside footer'";
                }
                else
                {
                    aside.style.display = 'none';
                    main.style.gridTemplateAreas = "'header header' 'section section' 'footer footer'";
                }
            }
        }
        
        const buttonToggle = document.getElementById('aside-toggle');

        if (buttonToggle)
        {
            buttonToggle.addEventListener('click', () =>
            {
                asideToggle(aside.style.display === 'none');
            });
        }
        
        const mmWidth = window.matchMedia("(max-width: 767.98px)");
        
        const matchMediaTest = (event) =>
        {
            asideToggle(!event.matches);
        }
        
        mmWidth.addEventListener('change', matchMediaTest);
        
        matchMediaTest(mmWidth);
    }
});
