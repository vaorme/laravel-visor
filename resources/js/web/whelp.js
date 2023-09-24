document.addEventListener('DOMContentLoaded', function(){
    if(!window.adsbygoogle || (window.adsbygoogle && !window.adsbygoogle.loaded)){
        crearAyudanos();
    }
});

function crearAyudanos(){
    const bdy = document.querySelector('body');
    const createDiv = document.createElement('div');
    createDiv.classList.add('w-help');
    createDiv.innerHTML = `
        <div class="w-overlay"></div>
        <div class="w-box">
            <div class="w-icon">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-hand-stop" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M8 13v-7.5a1.5 1.5 0 0 1 3 0v6.5"></path>
                    <path d="M11 5.5v-2a1.5 1.5 0 1 1 3 0v8.5"></path>
                    <path d="M14 5.5a1.5 1.5 0 0 1 3 0v6.5"></path>
                    <path d="M17 7.5a1.5 1.5 0 0 1 3 0v8.5a6 6 0 0 1 -6 6h-2h.208a6 6 0 0 1 -5.012 -2.7a69.74 69.74 0 0 1 -.196 -.3c-.312 -.479 -1.407 -2.388 -3.286 -5.728a1.5 1.5 0 0 1 .536 -2.022a1.867 1.867 0 0 1 2.28 .28l1.47 1.47"></path>
                    </svg>
            </div>
            <h4>Bloqueador de anuncions detectado</h4>
            <p>Por favor, si quieres ayudarnos, desactivalo para seguir ofreciendo contenido.</p>
            <p>Gracias.</p>
            <div class="w-image">
                <img src="http://127.0.0.1:8000/storage/images/chibi-rama.jpg" alt="Apoyanos">
            </div>
        </div>
    `;

    bdy.append(createDiv);

    // BRC
    document.addEventListener("contextmenu", (event) => {
        event.preventDefault();
    });
}