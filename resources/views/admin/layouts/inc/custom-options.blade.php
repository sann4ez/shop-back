<div class="modal fade" id="modal-barcode-print">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Кількість для друку:</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body js-barcode-printing">
                <div class="input-group mb-3">
                    <input type="number" value="1" autofocus name="count" class="form-control" min="1"
                        step="1" max="100000">
                    <div class="input-group-prepend">
                        <button type="button" data-barcode=""
                            class="btn btn-info js-barcode-print-button">Друкувати</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-barcode-scan">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Відскануйте штрихкод</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="text-center"><i class="fas fa-sync-alt fa-spin fa-lg"></i></p>
            </div>
        </div>
    </div>
</div>

<iframe id="printFrame" style="display: none;"></iframe>

<style>
    .btn-actions.dropdown:hover .dropdown-menu {
        display: block;
    }
    .btn-actions.dropleft:hover .dropdown-menu {
        top: 0 !important;
        display: block;
    }

    @media (width < 1023px) {
        .lte__scroll-nav {
            flex-wrap: nowrap;
            overflow-y: hidden;
            overflow-x: scroll;
        }
    }
</style>
<script>
    $('.dropdown').hover(
        function() {
            $(this).closest('.table-responsive').css('overflow-x', 'clip');
        }
    );
    $('.dropleft').hover(
        function() {
            $(this).closest('.table-responsive').css('overflow-x', 'clip');
        }
    );
</script>


<script src="{{ asset('vendor/custom/barcode-print/script.js') }}"></script>
<script src="{{ asset('vendor/custom/events-for-barcode-scanner/script.js') }}"></script>
<script src="{{ asset('vendor/custom/JsBarcode.all.min.js') }}"></script>

<script>
    var appName = "{{ config('app.name') }}";
    var initBarcode = function() {
        $('.modal').on("hidden.bs.modal", function(e) {
            if ($('.modal:visible').length) {
                $('body').addClass('modal-open');
            }
        });
        // Сканувати штрихкод
        const barcodeScanElements = document.querySelectorAll('.js-barcode-scan-field');
        barcodeScanElements.forEach(chapter => {
            const el = chapter.querySelector('.js-barcode-scan');
            const input = chapter.querySelector('.form-control');
            const bc = new BarcodeScanner(); // Створюємо новий екземпляр BarcodeScanner
            el.addEventListener("click", () => bc.init(el));
            el.addEventListener('onBarcodeWaiting', e => {
                console.log('Waiting for a scan... 1111');
                $('#modal-barcode-scan').modal();
            });
            el.addEventListener('onBarcodeScanned', e => {
                input.value = e.detail.str;
                console.log(e.detail.str);
                console.log('Scanning is completed');
                $('#modal-barcode-scan').modal('hide');
            });
        });

        // // Вивести штрихкод (фото)
        $('.js-barcode-show').each(function(i, e) {
            $(this).JsBarcode($(this).data('barcode'), {
                format: "CODE128",
                // lineColor: "#0aa",
                // width:4,
                // height:40,
                displayValue: false
            });
        });
    }
    initBarcode();

    {{--// Згенерувати значення штрихкоду--}}
    {{--$(document).on('click', '.js-barcode-make', function(e) {--}}
    {{--    e.preventDefault();--}}
    {{--    var $this = $(this);--}}
    {{--    $.get('{{ route('admin.products.variations.generateValue', 'barcode') }}', function(data) {--}}
    {{--        $this.closest('.form-group').find('input').val(data.value)--}}
    {{--        toastr.success('Згенерований штрихкод ' + data.value)--}}
    {{--    })--}}
    {{--})--}}

    {{--// Згенерувати значення артикулу--}}
    {{--$(document).on('click', '.js-sku-make', function(e) {--}}
    {{--    e.preventDefault();--}}
    {{--    var $this = $(this);--}}
    {{--    $.get('{{ route('admin.products.variations.generateValue', 'sku') }}', function(data) {--}}
    {{--        $this.closest('.form-group').find('input').val(data.value)--}}
    {{--        toastr.success('Згенерований штрихкод ' + data.value)--}}
    {{--    })--}}
    {{--});--}}

    const productName = "LPQ58";
    var isLPQ = false;
    // Оголошуємо екземпляр класу для друку на принтері де можна вказати serialNumber позамовчуванню "LPQ58023220531"
    const bcp = new BarcodePrinter(productName);

    $(window).on('onSelectedPrinterDevice', e => {
        console.log('Selected printer');
        isLPQ = true;
    });

    $(window).on('onNotSelectedPrinterDevice', e => {
        console.log(`Printer not selected`);
        isLPQ = false;
    });

    var bar = undefined;

    // Кнопка виклику модалки Друкувати штрихкод
    $(document).on('click', '.js-barcode-modal-print', function(e) {
        e.preventDefault();
        bar = $(this).data('barcode') || $(this).closest('.form-group').find('input').val();
        if (bar) {
            var $modalBar = $('#modal-barcode-print');
            $modalBar.modal();
            $modalBar.find('[data-barcode]').attr('data-barcode', bar);
        } else {
            alert('Штрихкод не знайдено!');
        }
    });

    var $barcodePr = $('#modal-barcode-print').find('.js-barcode-printing');
    var $barcodePrBtn = $barcodePr.find('.js-barcode-print-button');
    $barcodePrBtn.on("click", () => {
        if (!isLPQ) {
            bcp.requestDevice();
        }
        var $count = $barcodePr.find('.form-control').val();
        bcp.print(
            [
                'SIZE 40 mm,25 mm',
                'CLS',
                'DIRECTION 1',
                'SHIFT 0',
                `BARCODE 30,40,"128",100,1,0,3,3,"${bar}"`,
                `TEXT 30,170,"1",0,1,1,"${appName}"`,
                `PRINT ${$count}`,
                'END',
            ]
        );
    });

    $(document).ready(function() {
        $('input[pattern]').on('keypress', function(event) {
            var pattern = $(this).attr('pattern');

            var isNumericPattern = pattern === '[0-9]*';
            if (isNumericPattern) {
                var char = String.fromCharCode(event.which);
                if (!/^[0-9,.]$/.test(char)) {
                    event.preventDefault();
                }
            }
        });
    });

    // Лишати обрану вкладку (bootstrap tab) при перезагрузці
    $(document).ready(function () {
        var currentUrl = window.location.pathname;
        var storageKey = 'activeTab_' + currentUrl;

        // Перевіряємо, чи є збережена активна вкладка у sessionStorage для цієї сторінки
        var activeTab = sessionStorage.getItem(storageKey);
        if (activeTab) {
            $('.nav-pills a[href="' + activeTab + '"]').tab('show');
        }

        // Слухач подій на зміну вкладки
        $('.nav-pills a').on('shown.bs.tab', function (e) {
            var target = $(e.target).attr("href"); // Отримуємо значення атрибуту href (якір вкладки)
            sessionStorage.setItem(storageKey, target); // Зберігаємо активну вкладку в sessionStorage з унікальним ключем для сторінки
        });
    });

    // Друк без переходу на нову сторінку
    function printImage(imgUrl) {
        const iframe = document.getElementById('printFrame');

        iframe.contentDocument.body.innerHTML = `<img style="width: 30%; height: auto;" src="${imgUrl}" onload="window.print()">`;
    }

    function printHtml(printHtml) {
        const iframe = document.getElementById('printFrame');

        iframe.contentDocument.body.innerHTML = printHtml;
    }

</script>
