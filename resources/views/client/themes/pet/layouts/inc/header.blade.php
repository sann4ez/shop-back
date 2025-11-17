<header id="header" class="header-effect-shrink"
        data-plugin-options="{'stickyEnabled': true, 'stickyEnableOnBoxed': true, 'stickyEnableOnMobile': true, 'stickyStartAt': 120, 'stickyChangeLogo': false}">
    <div class="header-body">
        @if($val = \Variable::getArray('site.header_info', null, \Domain::getGroup()))
            <div class="header-top">
                <div class="header-top-container container">
                    <div class="header-row">
                        {{--
                        <div class="header-column justify-content-start">
                            @if($phone = \Variable::get('company_phone'))
                            <span class="d-none d-md-flex align-items-center">
                                <a href="tel:{{ $phone }}">PHONE: {{ $phone }}</a>
                            </span>
                            @endif
                            @if($email = \Variable::get('company_email'))
                            <span class="d-none d-md-flex align-items-center ml-4 text-uppercase">
                                <a href="mailto:{{ $email }}">EMAIL: {{ $email }}</a>
                            </span>
                            @endif
                        </div>
                        --}}

                        <div class="header-column justify-content-center">{!! $val !!}</div>

                        {{--
                        <div class="header-column justify-content-end">
                            <ul class="nav">
                                <li class="nav-item">
                                    <a href="{{ route('my.profile.edit') }}" class="nav-link">ACCOUNT</a>
                                </li>
                                <li class="nav-item">
                                    <a href="/about" class="nav-link">ABOUT</a>
                                </li>
                                --}}{{--
                                <li class="nav-item d-none d-sm-block">
                                    <a href="/blog" class="nav-link">BLOG</a>
                                </li>
                                --}}{{--
                            </ul>

                        </div>
                        --}}
                    </div>
                </div>
            </div>
        @endif
        <div class="header-container container">
            <div class="header-row">
                <div class="header-column justify-content-start">
                    <div class="header-logo">
                        <a href="/">
                            <img alt="Logo" width="128" height="32"
                                 src="{{ \Variable::get('site_file_logo', null, \Domain::getGroup()) ? \Storage::disk('public')->url(\Variable::get('site_file_logo', null, \Domain::getGroup())) : Theme::url('img/logo-shop.png') }}">
                        </a>
                    </div>
                </div>
                <div class="header-column justify-content-end">
                    <div class="header-search-expanded">
                        <form method="GET" action="/catalog/search">
                            <div class="input-group bg-light border">
                                <input type="text" class="form-control text-4" name="q" placeholder="I'm looking for..."
                                       aria-label="I'm looking for...">
                                <span class="input-group-btn">
                                    <button class="btn" type="submit"><i class="lnr lnr-magnifier text-color-dark"></i></button>
                                </span>
                            </div>
                        </form>
                    </div>
                    <div class="header-nav justify-content-start">
                        <a href="#" class="header-search-button order-1 text-5 d-none d-sm-block mt-1 mr-xl-2">
                            <i class="lnr lnr-magnifier"></i>
                        </a>
                        <div class="header-nav-main header-nav-main-effect-1 header-nav-main-sub-effect-1">
                            <nav class="collapse">
                                @if(($items = \App\Models\Menu\Menu::itemsBySlug('main')) && $items->count())
                                    <ul class="nav flex-column flex-lg-row" id="mainNav">
                                        @foreach($items as $item)
                                            @if($item->type === \App\Models\Menuitem::TYPE_PRODUCT_CATEGORIES)
                                                <li class="dropdown">
                                                    <a class="dropdown-item dropdown-toggle"
                                                       target="{{ $item->target }}" href="{{ $item->getUrlClient() }}">
                                                        {{ $item->name }}
                                                    </a>
                                                    @if(($categories = \App\Models\Term::byVocabulary(\App\Models\Term::VOCABULARY_PRODUCT_CATEGORIES)
                                                            ->withTrans()->get()) && $categories->count())
                                                        <ul class="dropdown-menu">
                                                            {{-- TODO tree --}}
                                                            @foreach($categories as $category)
                                                                <li>
                                                                    <a href="{{ $category->getUrlClient() }}"
                                                                       target="{{ $item->target }}">{{ $category->name }}</a>
                                                                </li>
                                                            @endforeach
                                                            {{--
                                                             <li>
                                                                 <a href="#">Layout Options 4</a>
                                                             </li>
                                                             <li class="dropdown-submenu">
                                                                 <a class="dropdown-item dropdown-toggle" href="#">Extra</a>
                                                                 <ul class="dropdown-menu">
                                                                     <li><a class="dropdown-item" href="#">Typography</a></li>
                                                                     <li><a class="dropdown-item" href="#">Grid System</a></li>
                                                                     <li><a class="dropdown-item" href="#">Page Loading</a></li>
                                                                     <li><a class="dropdown-item" href="#">Lazy Load</a></li>
                                                                 </ul>
                                                             </li>
                                                             --}}
                                                        </ul>
                                                    @endif
                                                </li>
                                            @else
                                                <li class="dropdown dropdown-mega">
                                                    <a href="{{ $item->getUrlClient() }}"
                                                       target="{{ $item->target }}">{{ $item->name }}</a>
                                                </li>
                                            @endif
                                        @endforeach

                                    </ul>
                                @endif
                            </nav>
                        </div>

                        @if(count(\Domain::getSupportedLocales()) > 1)
                            @foreach(\Domain::getSupportedLocales() as $key => $val)
                                @if(LaravelLocalization::getCurrentLocale() === $key)
                                    <a href="#"
                                       class="btn btn-link text-color-default font-weight-bold order-3 d-none d-sm-block text-1"
                                       data-url="/logout">{{ mb_strtoupper($val['code']) }}</a>
                                @else
                                    <a href="{{ \LaravelLocalization::getLocalizedURL($key) }}"
                                       class="btn btn-link text-color-default order-3 d-none d-sm-block text-1"
                                       data-url="/logout">{{ mb_strtoupper($val['code']) }}</a>
                                @endif
                            @endforeach
                        @endif


                        @auth
                            <a href="#"
                               class="btn btn-link text-color-default font-weight-bold order-3 d-none d-sm-block ml-auto mr-2 pt-1 text-1 js-action-form"
                               data-url="/logout">Logout</a>
                        @else
                            <a href="{{ route('login') }}"
                               class="btn btn-link text-color-default font-weight-bold order-3 d-none d-sm-block ml-auto mr-2 pt-1 text-1">Login</a>
                        @endauth

                        <div class="mini-cart order-4">
                            <span class="font-weight-bold font-primary">Cart / <span class="cart-total"
                                                                                     data-currency="{{optional(\Cart::order())->currency_code}}">{{ \Cart::purchasesSum() }}</span></span>
                            <div class="mini-cart-icon">
                                <img src="{{ Theme::url('img/icons/cart-bag.svg') }}" class="img-fluid" alt=""/>
                                <span class="badge badge-primary rounded-circle">{{ \Cart::quantity() }}</span>
                            </div>

                            <div class="mini-cart-content">
                                <div class="inner-wrapper bg-light rounded">
                                    @foreach(\Cart::purchases() as $purchase)
                                        <div class="mini-cart-product">
                                            <div class="row">
                                                <div class="col-7">
                                                    <a href="{{ $purchase->getUrlClient() }}">
                                                        <h2 class="text-color-default font-secondary text-1 mt-3 mb-0">{{ $purchase->model->getName() }}</h2>
                                                    </a>
                                                    <strong class="text-color-dark">
                                                        <span class="qty">{{$purchase->quantity}}x</span>
                                                        <span class="product-price"
                                                              data-currency="{{optional(\Cart::order())->currency_code}}">{{$purchase->price}}</span>
                                                    </strong>
                                                </div>
                                                <div class="col-5">
                                                    <div class="product-image">
                                                        <a href="#" data-url="{{ route('cart.remove', $purchase) }}"
                                                           class="btn btn-light btn-rounded justify-content-center align-items-center js-action-form"><i
                                                                    class="fas fa-times"></i></a>
                                                        <img src="{{ $purchase->getImageUrl() ?: Theme::url('img/products/product-1.jpg') }}"
                                                             class="img-fluid rounded" alt=""/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    <div class="mini-cart-total">
                                        <div class="row">
                                            <div class="col">
                                                <strong class="text-color-dark">TOTAL:</strong>
                                            </div>
                                            <div class="col text-right">
                                                <strong class="total-value text-color-dark">${{ \Cart::totalSum() }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mini-cart-actions">
                                        <div class="row">
                                            <div class="col pr-1">
                                                <a href="{{ route('cart.index') }}"
                                                   class="btn btn-dark font-weight-bold rounded text-0">VIEW CART</a>
                                            </div>
                                            <div class="col pl-1">
                                                <a href="{{ route('cart.checkout') }}"
                                                   class="btn btn-primary font-weight-bold rounded text-0">CHECKOUT</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <button class="header-btn-collapse-nav order-4 ml-3" data-toggle="collapse"
                                data-target=".header-nav-main nav">
                            <span class="hamburguer">
                                <span></span>
                                <span></span>
                                <span></span>
                            </span>
                            <span class="close">
                                <span></span>
                                <span></span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
