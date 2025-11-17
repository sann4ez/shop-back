@php($group = \Domain::getId())
@php(\Variable::setGroup($group))

<div class="card">
    <div class="card-header d-flex p-0">
        <h3 class="card-title p-3">Списки</h3>
        <ul class="nav nav-pills ml-auto p-2">
            @foreach(\App\Models\Item::typesList() as $type)
                @if(\App\Models\Item::isAllowedItem($type['type']))
                    <li class="nav-lead"><a class="nav-link @if($loop->first) active @endif" href="#tab_{{ $type['type'] }}" data-toggle="tab">{{ $type['title'] }}</a></li>
                @endif
            @endforeach

            @can('settings.system')
            <li class="nav-lead"><a class="nav-link" href="#tab_settings" data-toggle="tab">Налаштування</a></li>
            @endcan
        </ul>
    </div>

    <div class="card-body">
        <div class="tab-content">
            @foreach(\App\Models\Item::typesList() as $type)
            @php($items = \App\Models\Item::where('type', $type['type'])->with('translations')->get())
            <div class="tab-pane @if($loop->first) active @endif" id="tab_{{ $type['type'] }}">
                @if(\Illuminate\Support\Arr::get($type, 'settings.create') !== false)
                    <div class="text-right mb-3">
                        <a href="#" class="btn btn-flat btn-success mb-1" data-toggle="modal" data-target="#createListItem{{ $type['type'] }}">
                            <i class="fa fa-plus"></i>
                        </a>
                    </div>
                @endif

                @if($items->count())
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Всього: {{ count($items) }}</h3>
                    </div>

                    <div class="card-body table-responsive p-0">
                        <table class="table ">
                            <thead>
                            <tr>
                                <th></th>
                                @foreach($type['fields'] as $field)
                                    <th>
                                        {!! $field['label'] !!}
                                    </th>
                                @endforeach
                            </tr>
                            </thead>
                            <tbody @if($type['settings']['sortable'] ?? false) class="sortable-y" @endif data-url="{{ route('admin.items.order') }}">
                            @foreach ($items as $item)
                                <tr id="{{ $item->id }}" class="va-center">
                                    <td style="width: 1%">
                                        <div class="btn-actions dropdown">
                                            <button type="button" class="btn btn-sm btn-default"
                                                    data-toggle="dropdown"><i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <div class="dropdown-menu" role="menu" style="top: 93%;">
                                                <a href="{{ route('admin.items.edit', [$item, 'type' => $type]) }}"
                                                   class="dropdown-item js-modal-fill-html"
                                                   data-fn-inits="initColorpicker,initLfmBtn"
                                                   data-target="#modal-xl"
                                                >Редагувати</a>
                                                <a href="{{ route('admin.items.destroy', $item) }}"
                                                   class="dropdown-item js-click-submit @if($item->is_guarded) disabled @endif" data-method="delete"
                                                   data-fn-inits="initColorpicker"
                                                   data-confirm="Видалити?">Видалити</a>
                                            </div>
                                        </div>
                                    </td>
                                    @foreach($type['fields'] as $field)
                                        <td>
                                            @if($field['name'] === 'key')
                                                <span>{{ $item->{$field['name']} }}</span>
                                            @else
                                                {!! Lte3::field([
                                                    'data_type' => 'text',
                                                    'type' => $field['editable_type'] ?? 'xEditable',
                                                    'name' => $field['name'],
                                                    'pk' => $item->id . $loop->index,
                                                    'url_save' => route('admin.items.editable', $item),
                                                    'label' => '',
                                                    'value' => $item->{$field['name']},
                                                    'value_title' => $item->{$field['name']},
                                                ]) !!}
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @else
                    @include('admin2.parts.empty-rows')
                @endif
            </div>
            @endforeach

            @can('settings.system')
            <div class="tab-pane" id="tab_settings">
                <div class="text-right">
                    {!! Lte3::formOpen(['action' => route('admin.items.import'), 'files' => true, 'method' => 'POST', 'class' => 'js-form-submit-file-changed', 'style' => 'display: inline-block']) !!}
                    <label class="btn btn-flat btn-default mt-1 mb-0">
                        <i class="fas fa-download"></i>
                        <span style="font-weight: normal">Імпорт</span>
                        <input type="file" name="file" style="display: none;" accept=".csv,.xlsx">
                    </label>
                    {!! Lte3::formClose() !!}
                    <a href="{{ route('admin.items.export', ['_export' => 'csv']) }}" class="btn btn-flat btn-default mt-1"><i class="fa fa-upload"></i> Експорт</a>
                </div>
                {!! Lte3::formOpen(['action' => route('admin.settings.save'), 'method' => 'POST']) !!}
                <input type="hidden" name="_destination" value="{{ Request::fullUrl() }}">
                <input type="hidden" name="group" value="{{ $group }}">
                @foreach(\App\Models\Item::settingsList() as $type => $settings)
                <div class="card mt-3">
                    <div class="card-header">
                        <h3 class="card-title">Тип списку: {{ \App\Models\Item::typesList('title', 'type')[$type] }}</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @php($settingItems = \App\Models\Item::where('type', $type)->with('translations')->get())
                            @foreach($settings as $setting)
                                <div class="col-md-6">
                                {!! Lte3::select2("vars_array[lists][settings][$type][" . $setting['key'] . "]", \Variable::getArray("lists.settings.$type." . $setting['key'], null, $group), $settingItems->pluck('name', 'key')->toArray(), [
                                    'empty_value' => '--',
                                    'label' => $setting['title'],
                                ]) !!}
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endforeach

                <div class="card-footer">
                    <div class="text-right">
                        {!! Lte3::btnSubmit('Зберегти', null, null, ['add' => 'fixed']) !!}
                    </div>
                </div>
                {!! Lte3::formClose() !!}
            </div>
            @endcan
        </div>

    </div>
</div>

@push('modals')
    @foreach(\App\Models\Item::typesList() as $type)
        @include('admin2.items.inc.create', ['type' => $type])
    @endforeach
@endpush

