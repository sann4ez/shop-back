<div class="card card-solid">
    <div class="card-header with-border">
        <h3 class="card-title">Загальне</h3>
    </div>
    <div class="card-body">
        {!! Lte3::formOpen(['action' => route('admin.settings.save'), 'model' => null, 'method' => 'POST']) !!}

        <div class="callout callout-warning">
            <h5>Вітаємо в розділі налаштувань!</h5>
            <p>Для перегляду чи зміни налаштувань перейдіть в потрібний блок в меню з ліва</p>
        </div>
    </div>
        <div class="card-footer">
            {{--
            <div class="text-right">
                {!! Lte3::btnSubmit('Зберегти', null, null, ['add' => 'fixed']) !!}
            </div>
            --}}
        </div>
      {!! Lte3::formClose() !!}
</div>

