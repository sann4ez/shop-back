@php($collapsed = isset($collapsed) ? $collapsed : !request()->has('_f'))

{!! Lte3::formOpen(['action' => Request::fullUrl(), 'method' => 'GET']) !!}
<div class="collapse @if(!$collapsed) show @endif" id="collapseFilter">
    <div class="card">
        <div class="card-body">
            <input type="hidden" name="_f" value="1">
            @yield('body')
        </div>
        <div class="card-footer text-right">
            {!! Lte3::btnReset('Очистити') !!}
            {!! Lte3::btnSubmit('Застосувати') !!}
        </div>
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
                            return '<span class="lte-filter-searched">' + match + '</span>';
                        });
                        $(this).replaceWith(highlightedText);
                    }
                }
            });
        </script>
    @endpush
@endif
