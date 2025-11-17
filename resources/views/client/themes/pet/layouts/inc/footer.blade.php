<footer id="footer" class="footer-hover-links-light mt-0 pb-5">
    <div class="container">

        <div class="row">
            <div class="col-lg-5 mb-4 mb-lg-0">

                <ul class="social-icons social-icons-transparent social-icons-icon-light social-icons-lg mb-3">
                    @foreach (['facebook', 'twitter', 'instagram'] as $key)
                        @if ($url = \Variable::getArray("site.{$key}", null, \Domain::getGroup()))
                            <li class="social-icons-{{ $key }}"><a href="{{ $url }}"
                                    title="{{ $key }}"><i class="fab fa-{{ $key }}"></i></a></li>
                        @endif
                    @endforeach
                </ul>

                @include('parts.payments')

                <p class="text-md-left pb-0 mb-0">{{ \Variable::getArray('site.copyright') }}</p>
            </div>
            <div class="col-lg-4 mb-4 mb-lg-0">
                @if(($items = \App\Models\Menu\Menu::itemsBySlug('main')) && $items->count())
                    <h2 class="text-3 mb-3">MAIN MENU</h2>
                    <ul class="list list-icon list-unstyled">
                        @foreach ($items as $item)
                            <li class="mb-2"><i class="fas fa-angle-right mr-2 ml-1"></i><a
                                    href="{{ $item->getUrlClient() }}" target="{{ $item->target }}">{{ $item->name }}</a></li>
                        @endforeach
                    </ul>
                @endif
            </div>
            <div class="col-lg-3 mb-4 mb-lg-0">
                @if (\App\Models\Menu\Menu::itemsBySlug('pages-menu')->count())
                    <h2 class="text-3 mb-3">PAGES</h2>
                    <ul class="list list-icon list-unstyled">
                        @foreach (\App\Models\Menu\Menu::itemsBySlug('pages-menu') as $item)
                            <li class="mb-2"><i class="fas fa-angle-right mr-2 ml-1"></i><a
                                    href="{{ $item->getUrlClient() }}">{{ $item->name }}</a></li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
</footer>
