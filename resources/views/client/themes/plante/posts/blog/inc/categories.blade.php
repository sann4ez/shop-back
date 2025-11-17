<ul class="side-menu" aria-label="menuNavigation">
    <li class="side-menu__item"><a href="{{ route('blog.index') }}" class="side-menu__link main-text {{ request()->is('blog') ? 'side-menu__link--active' : '' }}">Всі статті</a></li>
    @foreach($categories as $category)
        <li class="side-menu__item"><a href="{{ $category->getUrlClient() }}" class="side-menu__link main-text {{ request()->is('blog/' . $category->slug) ? 'side-menu__link--active' : '' }}">{{ $category->name }}</a></li>
    @endforeach
</ul>
