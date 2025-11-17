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
            console.log(val)
            $(`${className}-block`).hide()
            $(`${className}-block-${val}`).show();
        }

        function initCartSelect2() {
            function customizeSelect2($select2Element) {
                function addSvgToArrow() {
                    $(".select2-selection__arrow")
                        .append(`<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M3.46875 8.53033L4.52941 7.46967L11.9991 14.9393L19.4688 7.46967L20.5294 8.53033L11.9991 17.0607L3.46875 8.53033Z" fill="black"></path>
            </svg>`);
                }

                // Видаляємо всі елементи svg перед відкриттям випадаючого списку
                $(".select2-selection__arrow b, .select2-selection__arrow svg").remove();

                // Додаємо svg при виборі елементу
                addSvgToArrow();

                $select2Element.on('select2:open', function () {
                    // Видаляємо всі елементи svg перед відкриттям випадаючого списку
                    $(".select2-selection__arrow b, .select2-selection__arrow svg").remove();
                }).on('select2:select', function (event) {
                    // Додаємо svg при виборі елементу
                    addSvgToArrow();
                    // Додатковий код, якщо потрібно
                });
            }

            // Ініціалізація для міст
            customizeSelect2($('.js-suggestSettlements'));

            // Вибір міста доставки Нової пошти
            $('.js-suggestSettlements').on('select2:select', function (event) {
                var selected = event.params.data

                //$('[name="shipping[city]"]').val(selected.text)
                //$('[name="shipping[region]"]').val(selected.text)
                    //$('[name="shipping[city_id]"]').val(selected.data.DeliveryCity)
                $('[name="shipping[novaposhta][CityName]"]').val(selected.text);

                //$('.js-block-np-warehouse').show();

                if ($(this).data('method') === 'courier') {
                    return true;
                }

                $('.js-suggestWarehouses').find('option').remove()
                console.log(selected.data.Warehouses)
                if (selected.data.Warehouses) {
                    //console.log(selected.data.DeliveryCity)
                    $.get('/suggest/novaposhta-warehouses?CityRef=' + selected.data.DeliveryCity, function (data, status) {
                        $.each(data.results, function (i, item) {
                            $('.js-suggestWarehouses').append('<option value="' + item.text + '">' + item.text + '</option>');
                        });
                    });
                } else {
                    $('.js-suggestWarehouses')//.find('option').remove()
                        .append('<option value="">Відділень не знайдено</option>');
                    alert('У вибраному місті відділень не знайдено! Виберіть інший населений пункт')
                }
            });

            // Ініціалізація для відділень
            customizeSelect2($('.js-suggestWarehouses'));

            $('.js-suggestWarehouses').select2({
                language: {
                    noResults: function (params) {
                        return "{{ trans('Вкажіть вище ваше Місто') }}";
                    }
                }
            });
        }
        initCartSelect2();

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
