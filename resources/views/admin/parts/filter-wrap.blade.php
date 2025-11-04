@php($collapsed = isset($collapsed) ? $collapsed : !request()->has('_f'))

{!! Lte3::formOpen(['action' => Request::fullUrl(), 'method' => 'GET']) !!}
<div class="card @if($collapsed) collapsed-card @endif">
    <div class="card-header" data-card-widget="collapse">
        <h3 class="card-title lte-pointer" data-card-widget="collapse">Фільтр <i class="fas fa-angle-down"></i></h3>

        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                @if($collapsed) <i class="fas fa-plus"></i> @else <i class="fas fa-minus"></i> @endif
            </button>
        </div>
    </div>
    <div class="card-body" @if($collapsed) style="display: none" @endif>
        <input type="hidden" name="_f" value="1">
        @yield('body')
    </div>
    <div class="card-footer text-right">
        {!! Lte3::btnReset('Очистити') !!}
        {!! Lte3::btnSubmit('Застосувати') !!}
    </div>
</div>
{!! Lte3::formClose() !!}

@if(request('q'))
    @push('scripts')
        <script>
            // Отримуємо текст, який потрібно знайти
            var searchText = "{{ request('q') }}";

            // Функція для екранування регулярного виразу
            function escapeRegExp(string) {
                return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
            }

            // Створюємо регулярний вираз з врахуванням регістру
            var searchRegExp = new RegExp(escapeRegExp(searchText), 'gi');

            // Обробляємо всі тексти на сторінці
            $('body .content').find('*').contents().each(function() {
                if (this.nodeType === 3 && this.nodeValue.trim() !== '') {
                    var text = this.nodeValue.trim();
                    if (searchRegExp.test(text)) {
                        var highlightedText = text.replace(searchRegExp, function(match) {
                            return '<span style="background-color: yellow;">' + match + '</span>';
                        });
                        $(this).replaceWith(highlightedText);
                    }
                }
            });
        </script>
    @endpush
@endif
