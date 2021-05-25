import {createModal} from './modal.js';

const APP =  {

    host: 'http://' + window.location.host,

    constant: function(){
        let url = this.host + '/public/constants.json'

        return fetch(url)
            .then((response) =>{
                response.json().then((data)=>{
                    console.log(data);
                })
            })
    },

    init(){

        let deleteButtons = document.querySelectorAll('.deleteButton');
        for (let i = 0; i < deleteButtons.length; i++){

            deleteButtons[i].addEventListener('click', (e) =>{
                let id = e.target.id;
                let card = document.querySelectorAll('.entityCard'+id)[0];
                card.innerHTML = '<hr>';
                let spinner = APP.spinner();
                card.append(spinner);

                APP.deleteEntity(id).then(

                    resolve =>{
                        setTimeout(() =>{
                            card.removeChild(spinner);
                            card.innerHTML = '<hr>';
                            card.innerHTML += resolve;
                        }, 500);

                    },
                    reject =>{
                        setTimeout(() =>{
                            card.removeChild(spinner);
                            card.innerHTML = '<hr>';
                            card.innerHTML += reject;
                        }, 500);
                    }
                )
            })
        }
    },

    modal: function(){
        const $modal =  createModal();
        const ANIM_SPEED = 200;
        let closing = false;
        let destroyed = false;
        const spinner = APP.spinner();
        const modal = {
            open() {
                if (!closing) {
                    $modal.classList.add('open');
                }
            },
            close() {
                closing = true;
                $modal.classList.remove('open');
                $modal.classList.add('hide');
                setTimeout(() => {
                    $modal.classList.remove('hide');
                    closing = false;
                    this.destroy();
                }, ANIM_SPEED);

            },

            destroy(){
                $modal.parentNode.removeChild($modal);
                $modal.removeEventListener('click', modalClickHandler);
                destroyed = true;
            },

            async send(){
                let modalBody = $modal.querySelector('.modal-content');
                modalBody.appendChild(APP.spinner());

                let name = $modal.querySelector('input').value;
                let descr = $modal.querySelector('textarea').value;

                if(!name.match(/[a-zа-я0-9_]+/i) ){
                    throw 'Недопустимое название позиции';
                }else if(!descr.match(/[a-zа-я0-9]+/i)){
                    throw 'Использованы недопустимые символы в описании';
                }

                let url = APP.host + '/entity/create';
                let formData = new FormData();
                formData.append('name', JSON.stringify($modal.querySelector('input').value))
                formData.append('description', JSON.stringify($modal.querySelector("textarea").value))

                const response = await fetch(url, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                    }
                })
                return response.text();

            }
        }



        const modalClickHandler = async (e)=>{
            if(e.target.dataset.close){
                modal.close();
            }

            if(e.target.dataset.send){
                modal.send().then(resolve => {
                    setTimeout(() =>{
                        let message = document.createElement('span')
                        message.innerHTML = '&#10003;' + resolve;
                        message.classList.add('success');
                        console.log(spinner);
                        spinner.parentNode.append(message);
                        spinner.parentNode.removeChild(spinner);
                    }, 500);

                },
                reject =>{
                    setTimeout(() =>{
                        let message = document.createElement('span');
                        message.innerHTML = '&#10060;  ' + reject;
                        message.classList.add('error');
                        spinner.parentNode.append(message);
                        spinner.parentNode.removeChild(spinner);
                    }, 500);
                }
                );
            }
        }


        $modal.addEventListener('click', modalClickHandler);

        return modal;
    },

    deleteEntity: async function (id = {}){
        let url = this.host + '/entity/delete';

        let formData = new FormData();
        formData.append('id', JSON.stringify(id))
        const response = await fetch(url, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            }
        })
        if(!response.ok){
            throw 'Ошибка!';
        }
        return response.text();

    },
    spinner: function(){
        let $spinner = document.createElement('div');
        $spinner.classList.add('spinner');
        return $spinner;
    },

    findMode: function(){
        let findMode = false;
        const content = document.querySelector('.card-container');
        const spinner = APP.spinner();

        return {
            start(){
                if(!findMode){
                    let cards = this.getCards();
                    cards.parentNode.removeChild(cards);
                }
                let data = document.querySelector('#find').value;
                this.send(data).then(
                    resolve =>{
                        setTimeout(()=>{
                            content.removeChild(spinner);
                        }, 200)
                        this.render(resolve);
                    },
                    reject=>{
                        setTimeout(()=>{
                            content.removeChild(spinner);
                        }, 200)
                        this.render(JSON.stringify([reject]));
                    }
                )
            },

            async send(data){
                findMode = true;
                if(!data){
                    throw 'Пустой запрос!';
                }else if(!data.match(/[a-zа-я0-9\s]+/i) || data.match(/[';":]/)){
                    throw 'Недопустимые символы в запросе';
                }

                content.append(spinner);
                let formData = new FormData();
                formData.append('text', JSON.stringify(data));
                const response = await fetch(APP.host + '/entity/find', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })

                return response.text();
            },

            render(entityData){
                entityData = JSON.parse(entityData);
                let layout = document.createElement('div');
                layout.classList.add('cards-wrapper');
                content.appendChild(layout);

                entityData.forEach(entity=>{

                    let html = `
                                <div class="${'entityCard' + entity.id + ' card-main'}">
                                <hr>
                                <p class="name"> ${entity.name} </p>
                                <p class="created"> ${entity.created.date} </p>
                                <p> ${entity.description}</p>
                                <button id="${entity.id}" class="b-close deleteButton">Удалить</button>
                            `;
                    layout.insertAdjacentHTML('afterbegin',html);
                });
                APP.init();
                findMode = false;

            },

            getCards(){
                return document.querySelector('.cards-wrapper');
            }
        }
    }

}


document.addEventListener('DOMContentLoaded', ()=>{
    APP.init();
    const findMode = APP.findMode();
    let findBtn = document.getElementById('find-btn');
    findBtn.addEventListener('click', (e)=>{
        e.preventDefault();
        findMode.start();
    })

    let modalBtn = document.querySelector('.addEntity');

    modalBtn.addEventListener('click', (e) =>{
        let $modal = APP.modal();
        setTimeout(function(){
            $modal.open();
        }, 10);
    })
})



