import SimpleBar from "simplebar";
import { addClass } from "../helpers/helpers";

const chaptersList = document.querySelector('.chapters__list');
if(chaptersList){
    new SimpleBar(chaptersList);
}

const divActions = document.querySelector('.manga__actions');

// :FOLLOW/UNFOLLOW MANGA

let followSpam = false;
document.addEventListener('click', async function (e) {

	// If the clicked element does not have the .click-me class, ignore it
	if (!e.target.matches('.manga__actions .action__follow.follow')) return;
    e.preventDefault();
    const btn = e.target;

    const id = btn.getAttribute('data-id');
    if(id == ""){
        Toastify({
            text: "ID requerido",
            className: "error",
            duration: 5000,
            newWindow: true,
            close: true,
            gravity: "top", // `top` or `bottom`
            position: "center", // `left`, `center` or `right`
        }).showToast();

        return true;
    }

    if(followSpam){
        return true;
    }
    followSpam = true;

    const follow = await axios.post(`/u/follow/${id}`, {
        headers:{
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        params: {
            id
        }
    }).then(function (response){
        console.log(response);
        if(response.data.status == "success"){
            btn.remove();
            let unfollowButton = document.createElement('button');
            addClass(unfollowButton, 'action__follow unfollow');
            unfollowButton.setAttribute('data-tippy-content', 'Dejar de seguir')
            unfollowButton.textContent = "Siguiendo";
            unfollowButton.setAttribute('data-id', id);

            divActions.prepend(unfollowButton);
        }
        if(response.data.status == "error"){
            Toastify({
                text: response.data.message,
                className: "error",
                duration: 5000,
                newWindow: true,
                close: true,
                gravity: "top",
                position: "center",
            }).showToast();
        }
        setTimeout(() => {
            followSpam = false;
        }, 1000);
    }).catch(function (error){
        // handle error
        console.log(error);
        Toastify({
            text: error.response.data.message,
            className: "error",
            duration: 5000,
            newWindow: true,
            close: true,
            gravity: "top", // `top` or `bottom`
            position: "center", // `left`, `center` or `right`
        }).showToast();
        setTimeout(() => {
            followSpam = false;
        }, 1000);
    });

});

let unfollowSpam = false;
document.addEventListener('click', async function (e) {

	// If the clicked element does not have the .click-me class, ignore it
	if (!e.target.matches('.manga__actions .action__follow.unfollow')) return;
    e.preventDefault();

    const btn = e.target;

    const id = btn.getAttribute('data-id');
    if(id == ""){
        Toastify({
            text: "ID requerido",
            className: "error",
            duration: 5000,
            newWindow: true,
            close: true,
            gravity: "top", // `top` or `bottom`
            position: "center", // `left`, `center` or `right`
        }).showToast();
        return true;
    }

    if(unfollowSpam){
        return true;
    }
    unfollowSpam = true;

    const follow = await axios.post(`/u/unfollow/${id}`, {
        headers:{
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        params: {
            id
        }
    }).then(function (response){
        console.log(response);
        if(response.data.status == "success"){
            btn.remove();

            let followButton = document.createElement('button');
            addClass(followButton, 'action__follow follow');
            followButton.textContent = "Seguir";
            followButton.setAttribute('data-id', id);

            divActions.prepend(followButton);
        }
        if(response.data.status == "error"){
            Toastify({
                text: response.data.message,
                className: "error",
                duration: 5000,
                newWindow: true,
                close: true,
                gravity: "top",
                position: "center",
            }).showToast();
        }
        setTimeout(() => {
            unfollowSpam = false;
        }, 1000);
    })
    .catch(function (error){
        // handle error
        console.log(error);
        Toastify({
            text: error.response.data.message,
            className: "error",
            duration: 5000,
            newWindow: true,
            close: true,
            gravity: "top",
            position: "center",
        }).showToast();
        setTimeout(() => {
            unfollowSpam = false;
        }, 1000);
    });

});

// :VIEW/UNVIEW MANGA

let viewMangaSpam = false;
document.addEventListener('click', async function (e) {
	if (!e.target.matches('.manga__actions .action__view.view')) return;
    e.preventDefault();
    const btn = e.target;

    const id = btn.getAttribute('data-id');
    if(id == ""){
        Toastify({
            text: "ID requerido",
            className: "error",
            duration: 5000,
            newWindow: true,
            close: true,
            gravity: "top", // `top` or `bottom`
            position: "center", // `left`, `center` or `right`
        }).showToast();

        return true;
    }
    
    if(viewMangaSpam){
        return true;
    }
    viewMangaSpam = true;

    const follow = await axios.post(`/u/view/${id}`, {
        headers:{
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        params: {
            id
        }
    }).then(function (response){
        console.log(response);
        if(response.data.status == "success"){

            const divButtonView = document.querySelector('.manga__actions button.action__view.view');

            let unviewButton = document.createElement('button');
            addClass(unviewButton, 'action__view unview');
            unviewButton.setAttribute('data-tippy-content', 'Desmarcar como visto');
            unviewButton.setAttribute('data-id', id);
            unviewButton.innerHTML = `
                <svg width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M13.2825 14.2104L18.5666 19.4959C18.6276 19.5569 18.7001 19.6053 18.7798 19.6383C18.8595 19.6713 18.9449 19.6883 19.0312 19.6883C19.1175 19.6883 19.203 19.6713 19.2827 19.6383C19.3624 19.6053 19.4348 19.5569 19.4958 19.4959C19.5569 19.4349 19.6053 19.3624 19.6383 19.2827C19.6713 19.203 19.6883 19.1175 19.6883 19.0313C19.6883 18.945 19.6713 18.8595 19.6383 18.7798C19.6053 18.7001 19.5569 18.6276 19.4958 18.5666L2.43335 1.50413C2.37233 1.44311 2.2999 1.39471 2.22018 1.36169C2.14046 1.32867 2.05501 1.31168 1.96872 1.31168C1.88244 1.31168 1.79699 1.32867 1.71727 1.36169C1.63755 1.39471 1.56511 1.44311 1.5041 1.50413C1.44308 1.56515 1.39468 1.63758 1.36166 1.7173C1.32864 1.79702 1.31165 1.88247 1.31165 1.96876C1.31165 2.05504 1.32864 2.14049 1.36166 2.22021C1.39468 2.29993 1.44308 2.37236 1.5041 2.43338L5.74347 6.67276C5.16648 7.0815 4.64729 7.56625 4.19997 8.11388C3.56656 8.89109 3.07073 9.77089 2.73391 10.7153C2.70635 10.7953 2.67222 10.9056 2.67222 10.9056L2.6486 10.9869C2.6486 10.9869 2.54885 11.6393 3.11454 11.7889C3.28266 11.8333 3.46155 11.8092 3.61192 11.7218C3.76228 11.6345 3.87182 11.491 3.91647 11.3229L3.91779 11.319L3.92829 11.2862L3.97422 11.1431C4.26029 10.3458 4.67969 9.60288 5.21454 8.94601C5.63499 8.42922 6.13258 7.98033 6.68979 7.61513L8.76091 9.68626C8.34296 9.94821 7.98945 10.301 7.72667 10.7185C7.46389 11.1359 7.2986 11.6072 7.24309 12.0973C7.18759 12.5874 7.2433 13.0838 7.40608 13.5494C7.56886 14.015 7.83452 14.4379 8.18331 14.7867C8.53209 15.1355 8.95503 15.4012 9.42065 15.564C9.88627 15.7267 10.3826 15.7825 10.8727 15.7269C11.3629 15.6714 11.8342 15.5062 12.2516 15.2434C12.669 14.9806 13.0218 14.6271 13.2838 14.2091L13.2825 14.2104ZM8.29497 5.51119L9.41585 6.63207C9.77533 6.58536 10.1375 6.56213 10.5 6.56251C13.1827 6.56251 14.8128 7.75951 15.7867 8.94732C16.3217 9.60412 16.7411 10.3471 17.027 11.1444C17.0493 11.2074 17.0638 11.256 17.073 11.2875L17.0835 11.3203V11.3243L17.0848 11.3256C17.1333 11.4894 17.2439 11.6278 17.3929 11.7114C17.5419 11.795 17.7176 11.8171 17.8827 11.7732C18.0478 11.7293 18.1893 11.6227 18.277 11.4761C18.3648 11.3296 18.3919 11.1545 18.3527 10.9883V10.9843L18.3513 10.9791L18.3461 10.962C18.3234 10.8791 18.2971 10.7972 18.2673 10.7166C17.9305 9.77221 17.4347 8.8924 16.8013 8.11519C15.6253 6.67801 13.6447 5.25001 10.5026 5.25001C9.69147 5.25001 8.95779 5.3445 8.29629 5.51119H8.29497Z" fill="white"/>
                </svg>
            `;
            divButtonView.after(unviewButton);

            btn.remove();
        }
        if(response.data.status == "error"){
            Toastify({
                text: response.data.message,
                className: "error",
                duration: 5000,
                newWindow: true,
                close: true,
                gravity: "top",
                position: "center",
            }).showToast();
        }
        setTimeout(() => {
            viewMangaSpam = false;
        }, 1000);
    })
    .catch(function (error){
        // handle error
        console.log(error);
        Toastify({
            text: error.response.data.message,
            className: "error",
            duration: 5000,
            newWindow: true,
            close: true,
            gravity: "top", // `top` or `bottom`
            position: "center", // `left`, `center` or `right`
        }).showToast();
        setTimeout(() => {
            viewMangaSpam = false;
        }, 1000);
    });

});

let unviewMangaSpam = false;
document.addEventListener('click', async function (e) {
	if (!e.target.matches('.manga__actions .action__view.unview')) return;
    e.preventDefault();

    const btn = e.target;

    const id = btn.getAttribute('data-id');
    if(id == ""){
        Toastify({
            text: "ID requerido",
            className: "error",
            duration: 5000,
            newWindow: true,
            close: true,
            gravity: "top", // `top` or `bottom`
            position: "center", // `left`, `center` or `right`
        }).showToast();
        return true;
    }

    if(unviewMangaSpam){
        return true;
    }
    unviewMangaSpam = true;

    const follow = await axios.post(`/u/unview/${id}`, {
        headers:{
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        params: {
            id
        }
    }).then(function (response){
        console.log(response);
        if(response.data.status == "success"){
            const divButtonUnview = document.querySelector('.manga__actions button.action__view.unview');

            let viewButton = document.createElement('button');
            addClass(viewButton, 'action__view view');
            viewButton.setAttribute('data-tippy-content', 'Marcar como visto');
            viewButton.setAttribute('data-id', id);
            viewButton.innerHTML = `
                <svg width="18" height="10" viewBox="0 0 18 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M2.29002 6.09833C2.21542 6.30201 2.06433 6.46858 1.86887 6.56263C1.67341 6.65669 1.44897 6.67082 1.24326 6.60203C1.03754 6.53324 0.866749 6.38695 0.767179 6.19424C0.667608 6.00153 0.647103 5.77759 0.710021 5.57C0.693355 5.62 0.710021 5.56833 0.710021 5.56833C0.739658 5.47657 0.774151 5.38644 0.813355 5.29833C0.880021 5.14 0.978355 4.92167 1.11335 4.66333C1.38835 4.14667 1.81669 3.45833 2.44835 2.77C3.72335 1.37833 5.81169 0 9.00002 0C12.1884 0 14.2767 1.37833 15.5517 2.77C16.2322 3.51663 16.7842 4.37101 17.185 5.29833L17.2617 5.48667C17.2667 5.5 17.2834 5.58667 17.3 5.67L17.3334 5.83333C17.3334 5.83333 17.4734 6.38833 16.7634 6.62333C16.5543 6.69316 16.326 6.67729 16.1286 6.57921C15.9312 6.48112 15.7807 6.30881 15.71 6.1V6.095L15.7 6.06833C15.6163 5.85423 15.5206 5.64501 15.4134 5.44167C15.117 4.88253 14.7507 4.36336 14.3234 3.89667C13.3067 2.78833 11.645 1.66667 9.00002 1.66667C6.35502 1.66667 4.69335 2.78833 3.67669 3.89667C3.12625 4.50012 2.67863 5.18985 2.35169 5.93833C2.3342 5.98135 2.31753 6.02469 2.30169 6.06833L2.29002 6.09833ZM5.66669 6.66667C5.66669 5.78261 6.01788 4.93476 6.643 4.30964C7.26812 3.68452 8.11597 3.33333 9.00002 3.33333C9.88408 3.33333 10.7319 3.68452 11.357 4.30964C11.9822 4.93476 12.3334 5.78261 12.3334 6.66667C12.3334 7.55072 11.9822 8.39857 11.357 9.02369C10.7319 9.64881 9.88408 10 9.00002 10C8.11597 10 7.26812 9.64881 6.643 9.02369C6.01788 8.39857 5.66669 7.55072 5.66669 6.66667Z" fill="#FFF"/>
                </svg>
            `;

            divButtonUnview.after(viewButton);

            btn.remove();
        }
        if(response.data.status == "error"){
            Toastify({
                text: response.data.message,
                className: "error",
                duration: 5000,
                newWindow: true,
                close: true,
                gravity: "top",
                position: "center",
            }).showToast();
        }
        setTimeout(() => {
            unviewMangaSpam = false;
        }, 1000);
    })
    .catch(function (error){
        // handle error
        console.log(error);
        Toastify({
            text: error.response.data.message,
            className: "error",
            duration: 5000,
            newWindow: true,
            close: true,
            gravity: "top", // `top` or `bottom`
            position: "center", // `left`, `center` or `right`
        }).showToast();
        setTimeout(() => {
            unviewMangaSpam = false;
        }, 1000);
    });
});

// :FAV/UNFAV MANGA

let favSpam = false;
document.addEventListener('click', async function (e) {
	if (!e.target.matches('.manga__actions .action__fav.fav')) return;
    e.preventDefault();
    const btn = e.target;

    const id = btn.getAttribute('data-id');
    if(id == ""){
        Toastify({
            text: "ID requerido",
            className: "error",
            duration: 5000,
            newWindow: true,
            close: true,
            gravity: "top", // `top` or `bottom`
            position: "center", // `left`, `center` or `right`
        }).showToast();

        return true;
    }

    if(favSpam){
        return true;
    }
    favSpam = true;

    const follow = await axios.post(`/u/fav/${id}`, {
        headers:{
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        params: {
            id
        }
    }).then(function (response){
        console.log(response);
        if(response.data.status == "success"){

            const divButtonFav = document.querySelector('.manga__actions button.action__fav.fav');

            let unfavButton = document.createElement('button');
            addClass(unfavButton, 'action__fav unfav');
            unfavButton.setAttribute('data-tippy-content', 'Desmarcar como favorito');
            unfavButton.setAttribute('data-id', id);
            unfavButton.innerHTML = `
                <svg width="18" height="10" clip-rule="evenodd" fill-rule="evenodd" stroke-linejoin="round" stroke-miterlimit="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="m11.322 2.923c.126-.259.39-.423.678-.423.289 0 .552.164.678.423.974 1.998 2.65 5.44 2.65 5.44s3.811.524 6.022.829c.403.055.65.396.65.747 0 .19-.072.383-.231.536-1.61 1.538-4.382 4.191-4.382 4.191s.677 3.767 1.069 5.952c.083.462-.275.882-.742.882-.122 0-.244-.029-.355-.089-1.968-1.048-5.359-2.851-5.359-2.851s-3.391 1.803-5.359 2.851c-.111.06-.234.089-.356.089-.465 0-.825-.421-.741-.882.393-2.185 1.07-5.952 1.07-5.952s-2.773-2.653-4.382-4.191c-.16-.153-.232-.346-.232-.535 0-.352.249-.694.651-.748 2.211-.305 6.021-.829 6.021-.829s1.677-3.442 2.65-5.44z" fill-rule="nonzero"/>
                </svg>
            `;
            divButtonFav.after(unfavButton);

            btn.remove();
        }
        if(response.data.status == "error"){
            Toastify({
                text: response.data.message,
                className: "error",
                duration: 5000,
                newWindow: true,
                close: true,
                gravity: "top",
                position: "center",
            }).showToast();
        }
        setTimeout(() => {
            favSpam = false;
        }, 1000);
    })
    .catch(function (error){
        // handle error
        console.log(error);
        Toastify({
            text: error.response.data.message,
            className: "error",
            duration: 5000,
            newWindow: true,
            close: true,
            gravity: "top", // `top` or `bottom`
            position: "center", // `left`, `center` or `right`
        }).showToast();
        setTimeout(() => {
            favSpam = false;
        }, 1000);
    });

});


let unfavSpam = false;
document.addEventListener('click', async function (e) {
	if (!e.target.matches('.manga__actions .action__fav.unfav')) return;
    e.preventDefault();

    const btn = e.target;

    const id = btn.getAttribute('data-id');
    if(id == ""){
        Toastify({
            text: "ID requerido",
            className: "error",
            duration: 5000,
            newWindow: true,
            close: true,
            gravity: "top", // `top` or `bottom`
            position: "center", // `left`, `center` or `right`
        }).showToast();
        return true;
    }

    if(unfavSpam){
        return true;
    }
    unfavSpam = true;

    const follow = await axios.post(`/u/unfav/${id}`, {
        headers:{
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        params: {
            id
        }
    }).then(function (response){
        console.log(response);
        if(response.data.status == "success"){
            const divButtonUnFav = document.querySelector('.manga__actions button.action__fav.unfav');

            let favButton = document.createElement('button');
            addClass(favButton, 'action__fav fav');
            favButton.setAttribute('data-tippy-content', 'Marcar como visto');
            favButton.setAttribute('data-id', id);
            favButton.innerHTML = `
                <svg width="18" height="10" clip-rule="evenodd" fill-rule="evenodd" stroke-linejoin="round" stroke-miterlimit="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="m11.322 2.923c.126-.259.39-.423.678-.423.289 0 .552.164.678.423.974 1.998 2.65 5.44 2.65 5.44s3.811.524 6.022.829c.403.055.65.396.65.747 0 .19-.072.383-.231.536-1.61 1.538-4.382 4.191-4.382 4.191s.677 3.767 1.069 5.952c.083.462-.275.882-.742.882-.122 0-.244-.029-.355-.089-1.968-1.048-5.359-2.851-5.359-2.851s-3.391 1.803-5.359 2.851c-.111.06-.234.089-.356.089-.465 0-.825-.421-.741-.882.393-2.185 1.07-5.952 1.07-5.952s-2.773-2.653-4.382-4.191c-.16-.153-.232-.346-.232-.535 0-.352.249-.694.651-.748 2.211-.305 6.021-.829 6.021-.829s1.677-3.442 2.65-5.44z" fill-rule="nonzero"/>
                </svg>
            `;

            divButtonUnFav.after(favButton);

            btn.remove();
        }
        if(response.data.status == "error"){
            Toastify({
                text: response.data.message,
                className: "error",
                duration: 5000,
                newWindow: true,
                close: true,
                gravity: "top",
                position: "center",
            }).showToast();
        }
        setTimeout(() => {
            unfavSpam = false;
        }, 1000);
    })
    .catch(function (error){
        // handle error
        console.log(error);
        Toastify({
            text: error.response.data.message,
            className: "error",
            duration: 5000,
            newWindow: true,
            close: true,
            gravity: "top", // `top` or `bottom`
            position: "center", // `left`, `center` or `right`
        }).showToast();
        setTimeout(() => {
            unfavSpam = false;
        }, 1000);
    });
});

// :VIEW/UNVIEW CHAPTER

let viewSpam = false;
document.addEventListener('click', async function (e) {
	if (!e.target.matches('.chapter__actions .action__view.view')) return;
    e.preventDefault();
    const btn = e.target;

    const id = btn.getAttribute('data-id');
    if(id == ""){
        Toastify({
            text: "ID requerido",
            className: "error",
            duration: 5000,
            newWindow: true,
            close: true,
            gravity: "top", // `top` or `bottom`
            position: "center", // `left`, `center` or `right`
        }).showToast();

        return true;
    }

    if(viewSpam){
        return true;
    }
    viewSpam = true;

    const follow = await axios.post(`/u/view_chapter/${id}`, {
        headers:{
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        params: {
            id
        }
    }).then(function (response){
        console.log(response);
        if(response.data.status == "success"){
            let unviewButton = document.createElement('button');
            addClass(unviewButton, 'action__view unview');
            unviewButton.setAttribute('data-tippy-content', 'Desmarcar como visto');
            unviewButton.setAttribute('data-id', id);
            unviewButton.innerHTML = `
                <svg width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M13.2825 14.2104L18.5666 19.4959C18.6276 19.5569 18.7001 19.6053 18.7798 19.6383C18.8595 19.6713 18.9449 19.6883 19.0312 19.6883C19.1175 19.6883 19.203 19.6713 19.2827 19.6383C19.3624 19.6053 19.4348 19.5569 19.4958 19.4959C19.5569 19.4349 19.6053 19.3624 19.6383 19.2827C19.6713 19.203 19.6883 19.1175 19.6883 19.0313C19.6883 18.945 19.6713 18.8595 19.6383 18.7798C19.6053 18.7001 19.5569 18.6276 19.4958 18.5666L2.43335 1.50413C2.37233 1.44311 2.2999 1.39471 2.22018 1.36169C2.14046 1.32867 2.05501 1.31168 1.96872 1.31168C1.88244 1.31168 1.79699 1.32867 1.71727 1.36169C1.63755 1.39471 1.56511 1.44311 1.5041 1.50413C1.44308 1.56515 1.39468 1.63758 1.36166 1.7173C1.32864 1.79702 1.31165 1.88247 1.31165 1.96876C1.31165 2.05504 1.32864 2.14049 1.36166 2.22021C1.39468 2.29993 1.44308 2.37236 1.5041 2.43338L5.74347 6.67276C5.16648 7.0815 4.64729 7.56625 4.19997 8.11388C3.56656 8.89109 3.07073 9.77089 2.73391 10.7153C2.70635 10.7953 2.67222 10.9056 2.67222 10.9056L2.6486 10.9869C2.6486 10.9869 2.54885 11.6393 3.11454 11.7889C3.28266 11.8333 3.46155 11.8092 3.61192 11.7218C3.76228 11.6345 3.87182 11.491 3.91647 11.3229L3.91779 11.319L3.92829 11.2862L3.97422 11.1431C4.26029 10.3458 4.67969 9.60288 5.21454 8.94601C5.63499 8.42922 6.13258 7.98033 6.68979 7.61513L8.76091 9.68626C8.34296 9.94821 7.98945 10.301 7.72667 10.7185C7.46389 11.1359 7.2986 11.6072 7.24309 12.0973C7.18759 12.5874 7.2433 13.0838 7.40608 13.5494C7.56886 14.015 7.83452 14.4379 8.18331 14.7867C8.53209 15.1355 8.95503 15.4012 9.42065 15.564C9.88627 15.7267 10.3826 15.7825 10.8727 15.7269C11.3629 15.6714 11.8342 15.5062 12.2516 15.2434C12.669 14.9806 13.0218 14.6271 13.2838 14.2091L13.2825 14.2104ZM8.29497 5.51119L9.41585 6.63207C9.77533 6.58536 10.1375 6.56213 10.5 6.56251C13.1827 6.56251 14.8128 7.75951 15.7867 8.94732C16.3217 9.60412 16.7411 10.3471 17.027 11.1444C17.0493 11.2074 17.0638 11.256 17.073 11.2875L17.0835 11.3203V11.3243L17.0848 11.3256C17.1333 11.4894 17.2439 11.6278 17.3929 11.7114C17.5419 11.795 17.7176 11.8171 17.8827 11.7732C18.0478 11.7293 18.1893 11.6227 18.277 11.4761C18.3648 11.3296 18.3919 11.1545 18.3527 10.9883V10.9843L18.3513 10.9791L18.3461 10.962C18.3234 10.8791 18.2971 10.7972 18.2673 10.7166C17.9305 9.77221 17.4347 8.8924 16.8013 8.11519C15.6253 6.67801 13.6447 5.25001 10.5026 5.25001C9.69147 5.25001 8.95779 5.3445 8.29629 5.51119H8.29497Z" fill="white"></path>
                </svg>
            `;
            btn.parentElement.prepend(unviewButton);

            btn.remove();
        }
        if(response.data.status == "error"){
            Toastify({
                text: response.data.message,
                className: "error",
                duration: 5000,
                newWindow: true,
                close: true,
                gravity: "top",
                position: "center",
            }).showToast();
        }
        setTimeout(() => {
            viewSpam = false;
        }, 1000);
    })
    .catch(function (error){
        // handle error
        console.log(error);
        Toastify({
            text: error.response.data.message,
            className: "error",
            duration: 5000,
            newWindow: true,
            close: true,
            gravity: "top", // `top` or `bottom`
            position: "center", // `left`, `center` or `right`
        }).showToast();
        setTimeout(() => {
            viewSpam = false;
        }, 1000);
    });

});

let unviewSpam = false;
document.addEventListener('click', async function (e) {
	if (!e.target.matches('.chapter__actions .action__view.unview')) return;
    e.preventDefault();

    const btn = e.target;

    const id = btn.getAttribute('data-id');
    if(id == ""){
        Toastify({
            text: "ID requerido",
            className: "error",
            duration: 5000,
            newWindow: true,
            close: true,
            gravity: "top", // `top` or `bottom`
            position: "center", // `left`, `center` or `right`
        }).showToast();
        return true;
    }

    if(unviewSpam){
        return true;
    }
    unviewSpam = true;

    const follow = await axios.post(`/u/unview_chapter/${id}`, {
        headers:{
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        params: {
            id
        }
    }).then(function (response){
        console.log(response);
        if(response.data.status == "success"){

            let viewButton = document.createElement('button');
            addClass(viewButton, 'action__view view');
            viewButton.setAttribute('data-tippy-content', 'Marcar como visto');
            viewButton.setAttribute('data-id', id);
            viewButton.innerHTML = `
                <svg width="18" height="10" viewBox="0 0 18 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M2.29002 6.09833C2.21542 6.30201 2.06433 6.46858 1.86887 6.56263C1.67341 6.65669 1.44897 6.67082 1.24326 6.60203C1.03754 6.53324 0.866749 6.38695 0.767179 6.19424C0.667608 6.00153 0.647103 5.77759 0.710021 5.57C0.693355 5.62 0.710021 5.56833 0.710021 5.56833C0.739658 5.47657 0.774151 5.38644 0.813355 5.29833C0.880021 5.14 0.978355 4.92167 1.11335 4.66333C1.38835 4.14667 1.81669 3.45833 2.44835 2.77C3.72335 1.37833 5.81169 0 9.00002 0C12.1884 0 14.2767 1.37833 15.5517 2.77C16.2322 3.51663 16.7842 4.37101 17.185 5.29833L17.2617 5.48667C17.2667 5.5 17.2834 5.58667 17.3 5.67L17.3334 5.83333C17.3334 5.83333 17.4734 6.38833 16.7634 6.62333C16.5543 6.69316 16.326 6.67729 16.1286 6.57921C15.9312 6.48112 15.7807 6.30881 15.71 6.1V6.095L15.7 6.06833C15.6163 5.85423 15.5206 5.64501 15.4134 5.44167C15.117 4.88253 14.7507 4.36336 14.3234 3.89667C13.3067 2.78833 11.645 1.66667 9.00002 1.66667C6.35502 1.66667 4.69335 2.78833 3.67669 3.89667C3.12625 4.50012 2.67863 5.18985 2.35169 5.93833C2.3342 5.98135 2.31753 6.02469 2.30169 6.06833L2.29002 6.09833ZM5.66669 6.66667C5.66669 5.78261 6.01788 4.93476 6.643 4.30964C7.26812 3.68452 8.11597 3.33333 9.00002 3.33333C9.88408 3.33333 10.7319 3.68452 11.357 4.30964C11.9822 4.93476 12.3334 5.78261 12.3334 6.66667C12.3334 7.55072 11.9822 8.39857 11.357 9.02369C10.7319 9.64881 9.88408 10 9.00002 10C8.11597 10 7.26812 9.64881 6.643 9.02369C6.01788 8.39857 5.66669 7.55072 5.66669 6.66667Z" fill="#FFF"/>
                </svg>
            `;

            btn.parentElement.prepend(viewButton);

            btn.remove();
        }
        if(response.data.status == "error"){
            Toastify({
                text: response.data.message,
                className: "error",
                duration: 5000,
                newWindow: true,
                close: true,
                gravity: "top",
                position: "center",
            }).showToast();
        }
        setTimeout(() => {
            unviewSpam = false;
        }, 1000);
    })
    .catch(function (error){
        // handle error
        console.log(error);
        Toastify({
            text: error.response.data.message,
            className: "error",
            duration: 5000,
            newWindow: true,
            close: true,
            gravity: "top", // `top` or `bottom`
            position: "center", // `left`, `center` or `right`
        }).showToast();
        setTimeout(() => {
            unviewSpam = false;
        }, 1000);
    });
});

// :RATE MANGA

let rateSpam = false;
document.addEventListener('click', async function (e) {
	if (!e.target.matches('.manga__detail .manga__rating .rating__label')) return;

    const input = e.target.nextElementSibling;
    const manga_id = input.getAttribute('data-manga-id');
    const btn = e.target;
    
    const numbers = document.querySelector('.manga__detail .manga__rating .rating__count .count__users .users__num');

    if(manga_id == ""){
        Toastify({
            text: "ID requerido",
            className: "error",
            duration: 5000,
            newWindow: true,
            close: true,
            gravity: "top", // `top` or `bottom`
            position: "center", // `left`, `center` or `right`
        }).showToast();
        return true;
    }
    if(input.value == ""){
        Toastify({
            text: "Valor requerido",
            className: "error",
            duration: 5000,
            newWindow: true,
            close: true,
            gravity: "top", // `top` or `bottom`
            position: "center", // `left`, `center` or `right`
        }).showToast();
        return true;
    }
    if(rateSpam){
        return true;
    }
    rateSpam = true;

    await axios.post(`/rate/${manga_id}`, {
        headers:{
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        params: {
            rating: input.value
        }
    }).then(function (response){
        // console.log(response);
        if(response.data.status == "success"){
            Toastify({
                text: response.data.message,
                className: "success",
                duration: 5000,
                newWindow: true,
                close: true,
                gravity: "top",
                position: "center",
            }).showToast();

            numbers.textContent = Number(numbers.textContent) + 1;
        }
        if(response.data.status == "error"){
            Toastify({
                text: response.data.message,
                className: "error",
                duration: 5000,
                newWindow: true,
                close: true,
                gravity: "top",
                position: "center",
            }).showToast();
        }
        setTimeout(() => {
            rateSpam = false;
        }, 1000);
    }).catch(function (error){
        // handle error
        console.log(error);
        Toastify({
            text: error.response.data.message,
            className: "error",
            duration: 5000,
            newWindow: true,
            close: true,
            gravity: "top",
            position: "center",
        }).showToast();
        setTimeout(() => {
            rateSpam = false;
        }, 1000);
    });
});