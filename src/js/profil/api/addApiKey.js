let addButton = document.getElementById('addButton');

addButton.addEventListener('click', function (e) {

    e.preventDefault();
    Swal.fire({
        icon: 'add',
        title: 'Créer une clé API',
        input: 'select',
        inputOptions: {
          'R': 'Lecture',
          'RU': 'Lecture et update',
          'D': 'Disponibilité'
        },
        inputPlaceholder: 'Sélectionner les droits',
        showCancelButton: true,
        confirmButtonText: 'OK',
        preConfirm: (value) => {
            if (!value) {
                Swal.showValidationMessage('Veuillez sélectionner les droits');
            }
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const rights = result.value;
            Swal.fire({
                icon: 'info',
                title: "Êtes-vous sûr de vouloir créer une nouvelle clé API ?",
                showCancelButton: true,
                confirmButtonText: 'Oui',
                cancelButtonText: 'Non'
            }).then((result) => {
                if (result.isConfirmed) {
                    //Envoi du formulaire
                    const form = document.getElementById('addForm');
                    const input = document.createElement('input');
                    input.setAttribute('type', 'hidden');
                    input.setAttribute('name', 'rights');
                    input.setAttribute('value', rights);
                    form.appendChild(input);
                    form.submit();
                }
            });
        }
    });
});