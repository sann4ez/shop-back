@extends('admin.layouts.app')

@section('btn-content-header')
    <div class="btn-actions  dropleft d-inline-flex">
        <button type="button" class="btn btn-flat btn-default" data-toggle="dropdown"><i class="fas fa-ellipsis-v"></i></button>
        <div class="dropdown-menu" role="menu" style="top: 93%;">

            @if(Domain::getOptIs('products.operations.import', null, 'product.create'))
                {!! Lte3::formOpen(['action' => route('admin.products.import'), 'files' => true, 'method' => 'POST', 'class' => 'js-form-submit-file-changed', 'style' => 'display: inline-block']) !!}
                    <label class="dropdown-item" style="cursor: pointer; font-weight: 400;"> Імпорт варіацій з файлу <input type="file" name="file" style="display: none;" accept=".csv,.xlsx"> </label>
                {!! Lte3::formClose() !!}
            @endif

            @if(Domain::getOptIs('products.operations.export'))
                <a href="{{ \Illuminate\Support\Facades\Request::fullUrlWithQuery(['_export' => 'csv']) }}"
                   class="dropdown-item "
                >Експорт варіацій у файл</a>
            @endif

            <a href="#" data-url="{{ route('admin.products.variations.task', ['task' => 'reindex']) }}" data-method="POST" class="dropdown-item js-click-submit" title="Запустити реіндексацію варіацій (для покращення пошуку)">{{--<i class="fas fa-retweet"></i>--}} Реіндексація варіацій</a>

            @if(Domain::getOptIs('products.operations.seo', null, ['seo.manage']))
            {!! Lte3::formOpen([
                'action' => route('admin.seos.import', ['model_class' => \App\Models\Shop\ProductVariation::class, 'search_field' => 'sku']),
                'files' => true,
                'method' => 'POST',
                'class' => 'js-form-submit-file-changed',
            ]) !!}
                <label class="dropdown-item mb-0" style="cursor: pointer; font-weight: 400;"> Імпорт SEO з файлу <input type="file" name="file" style="display: none;" accept=".csv,"> </label>
            {!! Lte3::formClose() !!}
            <a href="{{ \Illuminate\Support\Facades\Request::fullUrlWithQuery(['_export' => 'csv', '_export_seo' => 1]) }}" class="dropdown-item">{{--<i class="fa fa-upload"></i>--}} Експорт SEO у файл</a>
            @endif
        </div>
    </div>

    @if(Domain::getOptIs('products.fields.category', null, 'product.create'))
        <a href="#" class="btn btn-flat btn-success mb-1" data-toggle="modal" title="Додати варіацію" data-target="#selectCat"><i class="fa fa-plus"></i></a>
    @elseif(auth()->user()->can('product.create')) {{-- якщо нема категорій--}}
        <a href="{{ route('admin.products.create') }}" class="btn btn-flat btn-success mb-1" title="Додати варіацію" data-toggle="tooltip"><i class="fa fa-plus"></i></a>
    @endif
@endsection

@section('content')
    @include('admin.parts.content-header', [
        'page_title' => "Товари: <span title='Товарів'>{$products->total()}</span>/<span title='Варіацій'>{$variationsCount}</span>",
        //'small_page_title' => ,
        'btn_search' => true,
        'btn_filter' => true,
    ])

    <section class="content">

        @include('admin.products.inc.filter')

        @if($products->total())
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <input type="checkbox" class="js-mass-check-all">
                        </h3>
                        <div class="card-tools">
                        </div>
                    </div>
                    <div class="card-body  table-responsive p-0">

                        <table class="table table-sm">
                            <tbody>
                            @foreach($products as $product)
                                @php($variationsCount = $product->variations->count())
                                <tr class="lte-table-tr-bg">
                                    <td style="width: 1%">
                                        <div class="form-check">
                                            <input class="form-check-input js-mass-check" type="checkbox" value="{{ $product->id }}">
                                        </div>
                                    </td>
                                    <td style="width: 65px">
                                        <div class="btn-actions dropdown">
                                            <button type="button" class="btn btn-sm btn-default" style="background: lightblue" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></button>

                                            <div class="dropdown-menu" role="menu" style="top: 93%;">
                                                @can('product.update')
                                                <a href="{{ route('admin.products.edit', $product) }}" class="dropdown-item">Редагувати товар</a>
                                                @endcan
                                                @if(\Domain::getOpt('products.has_variations', null, 'product.create'))
                                                <a href="{{ route('admin.products.variations.create', $product) }}" data-target="#modal-xl"
                                                   class="dropdown-item js-modal-fill-html" data-fn-inits="initSelect2">Додати варіацію</a>
                                                @endif
                                                <div class="dropdown-divider"></div>
                                                @can('product.delete')
                                                <a href="{{ route('admin.products.destroy', $product) }}" class="dropdown-item js-click-submit"
                                                   data-method="DELETE"
                                                   data-confirm="Видалити товар та всі його варіації?">Видалити товар</a>
                                                @endcan
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @can('product.update')
                                            <a href="{{ route('admin.products.edit', $product) }}" class="hover-edit lead" {{--style="font-size: 16px; font-weight: bold;"--}}>{{ $product->name ?? '- товар -' }}</a>
                                        @else
                                            {{ $product->name ?? '- товар -' }}
                                        @endcan
                                        @if($product->status !== \App\Models\Shop\Product::STATUS_PUBLISHED)
                                        - <small>({{ $product->getStatus() }})</small>
                                        @endif
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td colspan="4">
                                        @if($variationsCount)
                                            <div class="{{--collapse--}} {{ session('product_group_collapse') }}" id="collapse_{{$product->id}}">
                                                @include('admin.products.inc.variations-table', ['product' => $product])
                                            </div>
                                        @else
                                            <div class="callout callout-danger">
                                                @can('product.create')
                                                <h5>Додати варіацію</h5>
                                                <a href="{{ route('admin.products.variations.create', $product) }}" data-target="#modal-lg" class="btn btn-flat btn-success btn-xs js-modal-fill-html" data-fn-inits="initSelect2"><i class="fa fa-plus"></i></a>
{{--                                                <p></p>--}}
                                                @endcan
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                    </div>
                    <div class="card-footer clearfix">
                        <div class="form-inline">
                            Показати &nbsp;
                            <select class="{{--form-control --}}js-change-url-submit">
                                @foreach([15, 30, 50, 100, 500] as $n)
                                    <option value="{{ \Illuminate\Support\Facades\Request::fullUrlWithQuery(['per_page' => $n]) }}" @if($n == session('per_page', 15)) selected @endif>{{ $n }}</option>
                                @endforeach
                            </select> &nbsp;
                            записів
                        </div>

                        {!! Lte3::pagination($products ?? null) !!}
                    </div>
                </div>
            </div>
        </div>
        @else
            @include('admin.parts.empty-rows')
        @endif


        <div class="card collapsed-card" hidden>
            <div class="card-header" data-card-widget="collapse">
                <h3 class="card-title"><i class="far fa-check-square"></i> Масові операції</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-1">
                        {!! Lte3::select2('select', request('select', 'checked'), ['checked' => 'Відмічені', 'page' => 'Всі на сторінці', /*'Всі у таблиці', */'all' => 'Всі абсолютно'], [
                           'label' => 'Застосувати до',
                        ]) !!}
                    </div>
                    <div class="col-md-3">
                        {!! Lte3::select2Tree('category_id', [
                           'label' => 'Категорія',
                           'multiple' => 0,
                           'method_get' => 'GET',
                           'empty_value' => '-',
                           'url_tree' => route('admin.suggest.terms', [
                                'vocabulary' => \App\Models\Term::VOCABULARY_PRODUCT_CATEGORIES,
                                'selected' => null,
                                'format' => 'treeselect',
                                'empty_value' => '--',
                            ]),
                        ]) !!}
                    </div>
                    <div class="col-md-3">
                        {!! Lte3::select2('brand_id', null, [], [
                           'label' => 'Бренд',
                           'empty_value' => '-',
                        ]) !!}
                    </div>
                    <div class="col-md-1">
                        <label>&nbsp; </label><br>
                        <button type="button" class="btn btn-success">Зберегти</button>
                    </div>
                    <div class="col-md-3">
                        <label>&nbsp; </label><br>
                        <button type="button" class="btn btn-danger">Видалити відмічені</button>
                    </div>
                    <div class="col-md-3">

                    </div>
                    <div class="col-md-1">

                    </div>
                </div>
            </div>
        </div>

    </section>
@endsection

@push('scripts')
    <script>
        $('.js-mass-check-all').on('change', function () {
            if(this.checked){
                $('.js-mass-check').prop('checked', true);
            } else {
                $('.js-mass-check').prop('checked', false);
            }
        })
    </script>
@endpush

@push('modals')
    <div class="modal fade" id="selectCat">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Виберіть потрібну категорію</h4>
                    <button type="button" class="close"
                            data-dismiss="modal"
                            aria-label="Close"><span
                            aria-hidden="true">&times;</span>
                    </button>
                </div>
                {!! Lte3::formOpen(['action' => route('admin.products.create'), 'method' => 'GET']) !!}
                <div class="modal-body">

                    {!! Lte3::select2Tree('category_id', [
                        'label' => '',
                        'multiple' => 0,
                        'method_get' => 'GET',
                        'required' => 1,
                        'url_tree' => route('admin.suggest.terms', [
                             'vocabulary' => \App\Models\Term::VOCABULARY_PRODUCT_CATEGORIES,
                             'format' => 'treeselect'
                         ]),
                     ]) !!}

                </div>
                <div class="modal-footer justify-content-between">
                    {!! Lte3::btnReset('Вийти') !!}
                    {!! Lte3::btnSubmit('Створити') !!}
                </div>
                {!! Lte3::formClose() !!}
            </div>
        </div>
    </div>
@endpush
