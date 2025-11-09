<div class="row">
    <div class="col-md-9">
        {!! Lte3::text('name', null, ['label' => 'Назва', 'placeholder' => 'Про нас']) !!}

        {!! Lte3::textarea('body', null, [
            'label' => 'Контент',
            'class' => 'f-tinymce',
        ]) !!}
    </div>
    <div class="col-md-3">
        {!! Lte3::select2('status', null, \App\Models\Page::statusesList('name', 'key'), [
               'label' => 'Статус',
        ]) !!}

        @if(isset($page) && $page->template)
        {!! Lte3::select2('template', null, \App\Models\Page::templatesList('name', 'key'), [
             'disabled' => true
         ]) !!}
        @else
        {!! Lte3::select2('template', isset($page) ? $page->template : 'default', \App\Models\Page::templatesList('name', 'key'), [
            'label' => 'Шаблон',
            'help' => '* після збереження сторінки, зміна шаблону не можлива!',
        ]) !!}
        @endif

        {!! Lte3::slug('slug', null, ['label' => 'Slug', 'placeholder' => 'about-us']) !!}

    </div>
</div>

<div class="text-right">
    {!! Lte3::btnReset('Вийти', ['url' => route('admin.pages.index')]) !!}
    {!! Lte3::btnSubmit('Зберегти', null, null, ['add' => 'fixed']) !!}
</div>
