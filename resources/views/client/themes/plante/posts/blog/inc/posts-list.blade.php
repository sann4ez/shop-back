@foreach($posts as $post)
    @include('client.themes.plante.posts.blog.inc.frame', ['post' => $post])
@endforeach
