const buttonsRemoveShortcut = document.querySelectorAll('.profile .profile__content .manga__list .manga__item .shortcut__remove')
if(buttonsRemoveShortcut){
    buttonsRemoveShortcut.forEach(item =>{
        item.addEventListener('click', async function(e){
            e.preventDefault();

            const manga_id = e.target.getAttribute('data-manga-id');
            const user_id = e.target.getAttribute('data-user-id');

            if((manga_id == null || user_id == null) || (manga_id == "" || user_id == "")){
                Toastify({
                    text: "Lo siento, ID Requerido",
                    className: "error",
                    duration: 5000,
                    newWindow: true,
                    close: true,
                    gravity: "top",
                    position: "center",
                }).showToast();
                return true;
            }

            await axios.post("/u/remove_shortcut", {
                manga_id,
                user_id
            },{
                headers:{
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            }).then(function(response){
                if(response.data.status){
                    Toastify({
                        text: response.data.message,
                        className: "success",
                        duration: 1000,
                        newWindow: true,
                        close: true,
                        gravity: "top",
                        position: "center",
                    }).showToast();

                    const sidebarShortcut = document.querySelector('#shortcut-'+ manga_id);
                    const profileShortcut = document.querySelector('#shortcut-p-'+ manga_id);
                    if(sidebarShortcut){
                        sidebarShortcut.remove()
                    }
                    if(profileShortcut){
                        profileShortcut.remove()
                    }
                }
                if(!response.data.status){
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
            })
            .catch(function (error){
                console.log('error: ',error);
            });
        });
    });
}