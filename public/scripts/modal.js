export function createModal(options){
    const modal = document.createElement('div');
    modal.classList.add('modal');
    modal.insertAdjacentHTML('afterbegin', `
    <div class="modal-layout" data-close="true">
        <div class="modal-window">
            <div class="modal-header">
                <span class="modal-title">Добавить позицию</span>
                <span class="modal-exit" data-close="true">&times</span>
            </div>
            <div class="modal-content">
                <form action="">
                    <div class="modal-field">
                        <label for="name">Имя</label>
                        <input type="text" name="name">
                    </div>
                    <div class="modal-field">
                        <label for="descr">Описание</label>
                            <textarea name="description" id="descr" cols="17" rows="2"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="modal-btn b-ok" data-send="true">Добавить</button>
                <button class="modal-btn b-close" data-close="true">Отмена</button>
            </div>
        </div>
    </div>
    `)
    document.body.appendChild(modal);
    return modal;
}