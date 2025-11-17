<div class="categories swiper-category">
    <div class="swiper-wrapper">
        <a href="{{ route('blog.index') }}" class="category swiper-slide {{ request()->is('blog') ? 'active' : '' }}">Всі</a>
        @foreach($categories as $category)
            <a href="{{ $category->getUrlClient() }}" class="category swiper-slide {{ request()->is('blog/' . $category->slug) ? 'active' : '' }}">{{ $category->name }}</a>
        @endforeach
    </div>
</div>
