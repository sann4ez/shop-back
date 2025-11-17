<div class="price__row">
    <div class="price__row-stat">{{ $variation->getName() }}</div>
    <div class="price__row-stat">{{ $variation->getPrice() }} грн</div>
    <div class="price__row-stat">{{ $variation->getAttributesPropertiesListStr() }}</div>
    <div class="price__row-stat">

        <div class="price__counter js-counter-wrapper">
            <button type="button" class="main-btn main-btn--counter main-btn--counter-locked js-counter-decrease">-</button>
            <input type="number"
                   class="price__amount main-input main-input--add js-counter-input"
                   min="0"
                   max="{{ $variation->getMaxQty() }}"
                   step="{{ $variation->getStep() }}"
                   data-variation-id="{{ $variation->id }}"
                   placeholder="0"
                   autocomplete="off">
            <button type="button" class="main-btn main-btn--counter js-counter-increase">+</button>
        </div>

    </div>
</div>
