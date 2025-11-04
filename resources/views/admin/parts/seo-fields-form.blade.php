

<div class="card @isset($seoable) collapsed-card @endisset">
    <div class="card-header" data-card-widget="collapse">
        <h3 class="card-title" data-card-widget="collapse">SEO</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas @isset($seoable) fa-plus @else fa-minus @endisset"></i>
            </button>
        </div>
    </div>
    <div class="card-body">
        @include('admin.parts.seo-fields')
    </div>
</div>
