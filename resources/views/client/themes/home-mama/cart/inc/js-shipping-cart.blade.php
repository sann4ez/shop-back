@push('scripts')
    <script>
        // Зміна select і показ блоків
        initSelectChange('.js-shipping');
        $('.js-select-change2').on('change', function () {
            initSelectChange('.js-shipping')
        });
        function initSelectChange(className) {
            var $select = $(className),
                val = $select.val()

            $(`${className}-block`).hide()
            $(`${className}-block-${val}`).show();
            //$(`${className}-block-${val}`).removeAttr('hidden');

            if (val === 'ukrposhta') {
                $('#payment-received-wrapper').hide();
                $('#payment-liqpay').prop('checked', true);
                initRadioChange('.js-payment');
            } else {
                $('#payment-received-wrapper').show();
                initRadioChange('.js-payment');
            }
        }

        // Зміна radio і показ блоків
        initRadioChange('.js-recipient');
        initRadioChange('.js-payment');
        $('.js-radio-change2').on('change', function () {
            initRadioChange('.js-recipient');
            initRadioChange('.js-payment');
        });
        function initRadioChange(className) {
            var val = $(`${className}:checked`).val()
            $(`${className}-block`).hide()
            $(`${className}-block-${val}`).show();
        }



        // Вибір міста і відділення НОВОЇ ПОШТИ
        function initCartSelect2() {

            // Вибір міста доставки Нової пошти
            $('.js-suggestSettlements').select2({
                ajax: {
                    delay: 1000
                },

                language: {
                    noResults: function (params) {
                        return "Введіть назву міста";
                    },
                    searching: function() {
                        return "Йде пошук...";
                    }
                }
            }).on('select2:select', function (event) {
                var selected = event.params.data,
                    type = '',
                    queryAdd = '';

                if ($(this).data('method') === 'novaposhta_courier') {
                    $('[name="shipping[novaposhta_courier][RecipientCityName]"]').val(selected.text);
                    $('[name="shipping[novaposhta_courier][SettlementTypeCode]"]').val(selected.data.SettlementTypeCode);
                    $('[name="shipping[novaposhta_courier][RecipientArea]"]').val(selected.data.Area);           // обл
                    $('[name="shipping[novaposhta_courier][RecipientAreaRegions]"]').val(selected.data.Region);  // район
                    $('[name="shipping[novaposhta_courier][MainDescription]"]').val(selected.data.MainDescription);  // район
                    return true;
                } else if ($(this).data('method') === 'novaposhta_locker') {
                    $('[name="shipping[novaposhta_locker][CityName]"]').val(selected.text);
                    queryAdd = '&type=locker';
                    type = 'locker';
                } else {
                    $('[name="shipping[novaposhta][CityName]"]').val(selected.text);
                    queryAdd = '&type=office';
                    type = 'office';
                }


                if (selected.data.Warehouses) {
                    $('.js-suggestWarehouses').select2({
                        ajax: {
                            url: '/suggest/novaposhta-warehouses',
                            delay: 1000,
                            data: function (params) {
                                return {
                                    q: params.term,
                                    CityRef: selected.data.DeliveryCity,
                                    type: type
                                };
                            },
                            processResults: function (data) {
                                return {
                                    results: data.results.map(function (item) {
                                        return {
                                            id: item.id,
                                            text: item.text
                                        };
                                    })
                                };
                            }
                        },
                        language: {
                            noResults: function () {
                                return "Відділення не знайдені";
                            },
                            searching: function () {
                                return "Йде пошук...";
                            }
                        }
                    })
                } else {

                    $('.js-suggestWarehouses')//.find('option').remove()
                        .append( '<option value="">Пунктів видачі знайдено</option>' );
                    alert('У вибраному населеному пункті не знайдено пунктів видачі! Виберіть інший населений пункт')
                }
            });

            $('.js-suggestWarehouses').select2({
                language: {
                    noResults: function (params) {
                        return "{{ trans('Вкажіть пункт видачі') }}";
                    }
                }
            }).on('select2:select', function (event) {
                var selected = event.params.data;
                if ($(this).data('method') === 'novaposhta_locker') {
                    $('[name="shipping[novaposhta_locker][WarehouseName]"]').val(selected.text)
                } else {
                    $('[name="shipping[novaposhta][WarehouseName]"]').val(selected.text)
                }
            })
        }
        initCartSelect2()


        // Укрпошта
        $('.js-warehouse').select2();
        $('.js-region').select2({
            ajax: {
                url: '{{ route('suggest.ukrposhta.regions') }}',
                dataType: 'json',
                method: 'GET',
                data: function (params) {
                    return {
                        term: params.term
                    };
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            },
            language: {
                inputTooShort: function () {
                    return 'Введіть більше одного символа';
                }
            }
        }).on('select2:select', function (e) {
            $option = $(this).find('option');
            $option.length > 1 ? $option.first().remove() : null;

            data = e.params.data;
            $('.js-region-name').val(data.text);
            $('.js-city').data('region-id', data.id);
        });

        $('.js-city').select2({
            ajax: {
                url: '{{ route('suggest.ukrposhta.cities') }}',
                dataType: 'json',
                method: 'GET',
                delay: 250,
                data: function (params) {
                    var regionId = $(this).data('region-id');
                    return {
                        term: params.term,
                        region_id: regionId
                    };
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            },
            minimumInputLength: 1,
            language: {
                inputTooShort: function () {
                    return 'Введіть більше одного символа';
                }
            }
        });
        $('.js-city').on('select2:select', function (e) {
            $option = $(this).find('option');
            $option.length > 1 ? $option.first().remove() : null;

            $('.js-city-name').val(e.params.data.text);

            var city = e.params.data
            var $warehouse = $('.js-warehouse');

            $warehouse.find('option').remove();

            if(city.id) {
                $.get('{{ route('suggest.ukrposhta.department') }}?city_id='+city.id, function(data){
                    $.each(data, function(i, item) {
                        if (i === 0) {
                            $warehouse.append( '<option value="'+item.id+'" selected>'+item.text+'</option>' );
                            $('.js-warehouse-name').val(item.text);
                            $('.js-warehouse-zipcode').val(item.postcode);
                        } else {
                            $warehouse.append( '<option value="'+item.id+'">'+item.text+'</option>' );
                        }
                    });
                });
            } else {
                $warehouse.append( '<option value="">Відділення не знайдено</option>' );
            }
        });
        $('.js-warehouse').on('select2:select', function (e) {
            $name = e.params.data.text;
            $zipcode = $name.split(', ')[0];
            $('.js-warehouse-name').val($name);
            $('.js-warehouse-zipcode').val($zipcode);
        })
    </script>
@endpush

@push('styles')
    <style>
        .checkout__select {
            width: 100%;
        }
        .alert p, .alert strong {
            font: 16px/130% "Proxima Nova Rg", sans-serif
        }
        .bold-paragraph {
            font: 600 16px/130% "Proxima Nova Rg", sans-serif;
            margin-bottom: 15px;
        }
    </style>
@endpush
