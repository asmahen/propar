import { Notyf } from 'notyf';
import 'notyf/notyf.min.css';

// Create an instance of Notyf
// et permet de créer différent type de message annotation et de les paramétrer
const notyf = new Notyf({
    duration: 5000,
    position: {
        x: 'right',
        y: 'top',
    },
    types: [
        {
            type: 'info',
            background: '#00bfff',
            icon: true
        },
        {
            type: 'warning',
            background: '#ffd700',
            icon: true
        },
        {
            type: 'finish',
            background: '#AAAAAA',
            icon: true
        },
    ]
});

//permet de récupérer dans un tableau les infos et le type de messages et le message
let messages = document.querySelectorAll('#notyf-message');

messages.forEach(message => {
    if (message.className === 'success') {
        notyf.success(message.innerHTML);
    }

    if (message.className === 'error') {
        notyf.error(message.innerHTML);
    }

    if (message.className === 'info') {
        notyf.open({
            type: 'info',
            message: '<b>Info</b> - ' + message.innerHTML,
        });
    }

    if (message.className === 'warning') {
        notyf.open({
            type: 'warning',
            message: '<b>Warning</b> - ' + message.innerHTML
        });
    }
    if (message.className === 'finish') {
        notyf.open({
            type: 'finish',
            message: '<b>Terminer</b> - ' + message.innerHTML,
        });
    }
});
