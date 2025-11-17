@php($group = \Domain::getId())

<div class="card card-solid">
    <div class="card-header with-border">
        <h3 class="card-title">Info</h3>
    </div>
    <div class="card-body">

        <div class="callout callout-warning">
            <h5>Довідкова інформація та команди системи!</h5>
            <p>Перед запуском команди переконайтеся в доцільності її виконання</p>
        </div>


        <table class="table table-sm">
            <thead>
            <tr>
                <th style="width: 40%">Команда</th>
                <th>Опис</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td><code>php artisan backup:run</code></td>
                <td>Створити бекап БД та файлів</td>
            </tr>
            <tr>
                <td><code>php artisan checkbox:export</code></td>
                <td>Експортувати всі товари в Checkbox</td>
            </tr>
            </tbody>
        </table>

    </div>
</div>

