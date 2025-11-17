@foreach($posts as $post)
    @include('client.themes.home-mama.posts.blog.inc.frame', ['post' => $post])
@endforeach
