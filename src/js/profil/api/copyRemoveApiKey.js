
//Ajouter un event listener sur chaque ul

const uls = document.querySelectorAll('ul');

uls.forEach(ul => {
    ul.addEventListener('click', (e) => {

        let key = ul.querySelector('li:first-child').textContent;

        Swal.fire({
            icon: 'info',
            title: "Copier ou supprimer cette clé API ?",
            showCancelButton: true,
            confirmButtonText: 'Copier',
            cancelButtonText: 'Annuler',
            showDenyButton: true,
            denyButtonText: 'Supprimer',
        }).then((result) => {
            if (result.isConfirmed) {
                // Copie de la clé
                navigator.clipboard.writeText(key);
                Swal.fire({
                    icon: 'success',
                    title: "La clé a été copiée !",
                    showConfirmButton: false,
                    timer: 1500
                });
            } else if (result.isDenied) {
                Swal.fire({
                    icon: 'warning',
                    title: "Êtes-vous sûr de vouloir supprimer cette clé API ?",
                    showCancelButton: true,
                    confirmButtonText: 'Oui',
                    cancelButtonText: 'Non',
                    confirmButtonColor: '#dc3741',
                    
                }).then((deleteResult) => {
                    if (deleteResult.isConfirmed) {
                        // Envoi du formulaire
                        const form = document.getElementById('removeForm');
                        const input = document.createElement('input');
                        input.setAttribute('type', 'hidden');
                        input.setAttribute('name', 'key');
                        input.setAttribute('value', key);
                        form.appendChild(input);
                        form.submit();
                    }
                });
            }
        });
    })
})
