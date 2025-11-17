<div class="articles__wrapper">
    <div class="articles__grid js-perpage-source">
        @include('posts.blog.inc.posts-list')
    </div>

    @include('parts.pagination-navigation', ['items' => $posts])

</div>
