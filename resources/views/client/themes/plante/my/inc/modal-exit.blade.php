<div class="modal modal-exit fade" id="logOut" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal__form-input">
                <h3 class="title title--medium title--align-center">Ви впевнені що хочете вийти з особистого кабінету?</h3>
                <!-- <label for="phone" class="modal__form-text main-text">Ви впевнені що хочете вийти з особистого кабінету?</label>
                <div class="modal__form-input-wrapper">
                    <input name="phone" id="password" type="password" class="main-input main-input--white main-input--width100" placeholder="Введіть">
                    <button class="modal__form-input-icon"><svg class="icon-svg icon-svg-eye-show eye-show"><use xlink:href="img/sprite.svg#eye-show"></use></svg></button>
                </div> -->
            </div>
            <button class="main-btn main-btn--modal main-btn--width100 main-btn--second-green main-text main-text--semibold js-click-submit" data-url="{{ route('logout') }}" data-bs-toggle="modal">Вийти</button>
            <button class="main-btn main-btn--modal main-btn--width100 main-btn--light-gray main-text main-text--semibold" data-bs-dismiss="modal">Скасувати</button>
        </div>
    </div>
</div>
