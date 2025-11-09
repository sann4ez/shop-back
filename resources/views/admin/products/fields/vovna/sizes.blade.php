<div class="row">
    <div class="col-md-12">
        {!! Lte3::textarea('fields[sizes]', isset($model) ? $model->getFields('sizes') : null, ['label' => 'Таблиця розмірів - Жінки', 'class' => 'f-tinymce']) !!}
        {!! Lte3::textarea('fields[sizes2]', isset($model) ? $model->getFields('sizes2') : null, ['label' => 'Таблиця розмірів - Чоловіки', 'class' => 'f-tinymce']) !!}
        {!! Lte3::textarea('fields[sizes3]', isset($model) ? $model->getFields('sizes3') : null, ['label' => 'Таблиця розмірів - Діти', 'class' => 'f-tinymce']) !!}
    </div>
</div>
