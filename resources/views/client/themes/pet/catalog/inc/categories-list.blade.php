<div class="row">
    <div class="col">
        <h1 class="font-weight-bold">{{ isset($category) ? $category->name : 'Catalog' }}</h1>
    </div>
</div>

<ul class="nav sort-source mb-3" data-sort-id="portfolio"></ul>

<div class="sort-destination-loader sort-destination-loader-showing min-height-800" data-plugin-remove-min-height>
    <ul class="portfolio-list portfolio-list-style-2 sort-destination" data-sort-id="portfolio">

        @foreach($categories as $category)
            <li class="col-sm-6 col-md-4 col-lg-3 p-0 mb-3 isotope-item">
                <div class="portfolio-item hover-effect-3d text-center">
                    <a href="{{ $category->getUrlClient() }}">
                                <span class="image-frame image-frame-style-1 image-frame-effect-1 mb-3">
                                    <span class="image-frame-wrapper">
                                        <img src="{{ $category->getMyFirstMediaUrl('image') ?: \Theme::url('img/projects/photos/project-1.jpg') }}" class="img-fluid" alt="">
                                        <span class="image-frame-inner-border"></span>
                                        <span class="image-frame-action image-frame-action-effect-1 image-frame-action-sm">
                                            <span class="image-frame-action-icon">
                                                <i class="lnr lnr-link text-color-light"></i>
                                            </span>
                                        </span>
                                    </span>
                                </span>
                    </a>
                    <h2 class="font-weight-bold line-height-2 text-3 mb-0">
                        <a href="{{ $category->getUrlClient() }}" class="link-color-dark">{{ $category->name }}</a>
                    </h2>
                    {{--                            <span class="text-uppercase text-0">brands</span>--}}
                </div>
            </li>
        @endforeach

    </ul>
</div>
<hr>