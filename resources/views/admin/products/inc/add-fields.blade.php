@foreach($fields as $field)
    @includeFirst([
        'admin.products.fields.' . $field,
        'admin.products.fields.default',
    ], ['model' => $fieldsModel ?? null])
@endforeach


