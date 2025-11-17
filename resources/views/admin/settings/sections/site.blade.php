@php($varGroup = \Domain::getId())
@php(\Variable::setGroup($varGroup))

<div class="card card-solid">
    <div class="card-header with-border">
        <h3 class="card-title">Сайт </h3>
    </div>

    {!! Lte3::formOpen(['action' => route('admin.settings.save'), 'method' => 'POST']) !!}
    <input type="hidden" name="group" value="{{ $varGroup }}">

    <div class="card-body">

        {!! Lte3::checkbox('vars_array[seo][close]', \Variable::getArray('seo.close', 0) , [
           'label' => 'Закрити сайт від індексації',
           'class_control' => 'custom-switch'
        ]) !!}

        {!! Lte3::lfmImage('vars_array[seo][og_image]', \Variable::getArray('seo.og_image', ''), [
            'label' => 'OG зображення по замовчуванню (1200X630)'
        ]) !!}

        <div class="row">
            <div class="col-md-12">
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">Скрипти та HTML</h3>
                    </div>
                    <div class="card-body">
                        {!! Lte3::textarea('vars_array[site][asset_in_head]', \Variable::getArray('site.asset_in_head', null, $varGroup), [
                            'label' => '&lt;head&gt;CODE',
                            'rows' => 7
                        ]) !!}

                        {!! Lte3::textarea('vars_array[site][asset_start_body]', \Variable::getArray('site.asset_start_body', null, $varGroup), [
                            'label' => '&lt;body&gt;CODE',
                            'rows' => 7
                        ]) !!}

                        {!! Lte3::textarea('vars_array[site][asset_end_body]', \Variable::getArray('site.asset_end_body', null, $varGroup), [
                            'label' => '&lt;/body&gt;CODE',
                            'rows' => 7
                        ]) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <div class="text-right">
            {!! Lte3::btnSubmit('Зберегти', null, null, ['add' => 'fixed']) !!}
        </div>
    </div>

    {!! Lte3::formClose() !!}
