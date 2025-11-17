@foreach($categories as $category)
    <a href="{{ $category->getUrlClient() }}" class="category_img">
        <img src="{{ $category->getFirstMediaUrl('image', 'preview') ?: Theme::url('img/nophoto.webp') }}"
             alt="{{ $category->name }}" width="164" height="160"
             class="category-img">
        <span>{{ $category->name }}</span>
    </a>
@endforeach
