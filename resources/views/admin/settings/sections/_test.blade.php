<div class="col-md-12">
    <div class="box box-solid">
        <div class="box-header with-border">
            <h3 class="box-title">SEO</h3>
        </div>

        <div class="box-body">
            <form role="form" action="{{ route('admin.settings.save') }}" method="POST">
                @csrf
                <input type="hidden" name="_destination" value="{{ Request::fullUrl() }}">
                <input type="hidden" name="group" value="{{ \Domain::getSelected('id') }}">

                <div class="row">
                    <div class="col-md-6">
                        <div class="box box-warning box-solid">
                            <div class="box-header with-border">
                                <h3 class="box-title">TEST</h3>
                            </div>
                            <div class="box-body">

                            </div>
                        </div>
                    </div>
                </div>


                @include('lte::fields.field-form-buttons')
            </form>
        </div>

    </div>
</div>