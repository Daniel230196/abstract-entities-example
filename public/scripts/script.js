import {createModal} from './modal.js';

const APP =  {

    host: 'http://' + window.location.host,

    init(){

        let deleteButtons = document.querySelectorAll('.deleteButton');
        for (let i = 0; i < deleteButtons.length; i++){

            deleteButtons[i].addEventListener('click', (e) =>{
                let id = e.target.id;
                let card = document.querySelectorAll('.entityCard'+id)[0];
                card.innerHTML = '<hr>';
                APP.addSpinner(card);

                APP.deleteEntity(id).then(

                    resolve =>{
                        setTimeout(() =>{
                            APP.removeSpinner(card);
                            card.innerHTML = '<hr>';
                            card.innerHTML += resolve;
                        }, 500);

                    },
                    reject =>{
                        setTimeout(() =>{
                            APP.removeSpinner(card);
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
        let modalBody = $modal.querySelector('.modal-content');

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

                APP.addSpinner(modalBody);

                let name = $modal.querySelector('input').value;
                let descr = $modal.querySelector('textarea').value;

                if(!name.match(/[a-zа-я0-9_]+/i)){
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
                        APP.removeSpinner(modalBody);
                        modalBody.appendChild(message);
                    }, 500);

                },
                reject =>{
                    setTimeout(() =>{
                        let message = document.createElement('span');
                        message.innerHTML = '&#10060;  ' + reject;
                        message.classList.add('error');
                        APP.removeSpinner(modalBody);
                        modalBody.appendChild(message);
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
    addSpinner(node){
        let $spinner = document.createElement('div');
        $spinner.classList.add('spinner');
        node.appendChild($spinner);
    },

    removeSpinner(node){
        let $spinner = document.querySelector('.spinner');
        node.removeChild($spinner);
    },

    findMode: function(){

        let cleared = false;
        const layout = document.querySelector('.card-container');

        return {
            start(){
                if(!cleared){
                    let cards = this.getCards();
                    cards.parentNode.removeChild(cards);
                    cleared = true;
                }

                let text = document.getElementById('find').value;
                let cards = document.createElement('div');
                cards.classList.add('cards-wrapper');

                layout.append(cards);
                cleared = false;
                APP.addSpinner(cards);
                this.send(text).then(
                    resolve=>{
                        setTimeout(function(){
                            APP.removeSpinner(cards);
                        },500);
                        this.render(resolve, cards);
                    },
                    reject=>{
                        setTimeout(function(){
                            APP.removeSpinner(cards);
                        },500);
                        this.renderError(reject, cards);
                    }
                )
            },

            async send(data){
                if(!data){
                    throw 'Пустой запрос';
                }else if(!data.match(/[a-zа-я0-9\s]+/i) || data.match(/[';":]/)){
                    throw 'Недопустимые символы в запросе'
                }

                let formData = new FormData();
                formData.append('text', JSON.stringify(data));

                let response = await fetch(APP.host + '/entities/find', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })

                return response.text();
            },

            render(entityData, node){
                entityData = JSON.parse(entityData);
                if(entityData.length !== 0){
                    entityData.forEach(entity=>{
                        let html = `
                                <div class="${'entityCard' + entity.id + ' card-main'}">
                                <hr>
                                <p class="name"> ${entity.name} </p>
                                <p class="created"> ${entity.created.date} </p>
                                <p> ${entity.description}</p>
                                <button id="${entity.id}" class="b-close deleteButton">Удалить</button>
                            `;
                        node.insertAdjacentHTML('afterbegin',html);
                    });
                }else{
                    node.insertAdjacentHTML('afterbegin','<p class="success">Ничего не найдено</p>');
                }

                APP.init();

            },
            renderError(error, node){
              let message = JSON.stringify(error);
              node.insertAdjacentHTML('afterbegin', `<p class="error">${message}</p>`)
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



