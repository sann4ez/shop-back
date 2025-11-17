@extends('layouts.app')

@php
    Seo::setModel($post);
@endphp

@section('content')
    <div role="main" class="main">

        <div class="container">

            {{ Breadcrumbs::render('blog.show', $post) }}

            <div class="row">
                <div class="col">
                    <h1 class="font-weight-bold">{{ $post->name }}</h1>
                </div>
            </div>

            <div class="row">
                <aside class="sidebar col-md-4 col-lg-3 order-2">
                    <div class="accordion accordion-default accordion-toggle accordion-style-1 mb-5" role="tablist">

                        <div class="card">
                            <div id="toggleSidebarSearch" class="accordion-body accordion-body-show-border-top collapse show" role="tabpanel" aria-labelledby="sidebarSearchForm">
                                <div class="card-body pt-4">
                                    <form id="sidebarSearchForm" class="sidebar-search" action="page-search-results.html" method="get">
                                        <div class="input-group">
                                            <input type="text" class="form-control line-height-1 bg-light-5" name="s" id="s" placeholder="Search..." required="">
                                            <span class="input-group-btn">
														<button class="btn btn-light" type="submit"><i class="fas fa-search text-color-primary"></i></button>
													</span>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header accordion-header" role="tab" id="categories">
                                <h3 class="text-3 mb-0">
                                    <a href="#" data-toggle="collapse" data-target="#toggleCategories" aria-expanded="false" aria-controls="toggleCategories">CATEGORIES</a>
                                </h3>
                            </div>
                            <div id="toggleCategories" class="accordion-body collapse show" aria-labelledby="categories">
                                <div class="card-body">
                                    <ul class="list list-unstyled">
                                        <li class="mb-2">
                                            <a href="#" class="font-weight-semibold"><i class="fas fa-angle-right ml-1 mr-1"></i> Design</a>
                                        </li>
                                        <li class="mb-2">
                                            <a href="#" class="font-weight-semibold text-color-primary"><i class="fas fa-angle-right ml-1 mr-1" id="photos" data-toggle="collapse" data-target="#submenuPhotos" aria-expanded="true" aria-controls="submenuPhotos" role="list" onclick="return false;"></i> Photos (3)</a>
                                            <ul class="list list-unstyled collapse show" id="submenuPhotos" aria-labelledby="photos">
                                                <li>
                                                    <a href="#">Animals</a>
                                                </li>
                                                <li>
                                                    <a href="#">Business (4)</a>
                                                </li>
                                                <li>
                                                    <a href="#">Sports</a>
                                                </li>
                                            </ul>
                                        </li>
                                        <li class="mb-2">
                                            <a href="#" class="font-weight-semibold"><i class="fas fa-angle-right ml-1 mr-1"></i> Videos</a>
                                        </li>
                                        <li class="mb-2">
                                            <a href="#" class="font-weight-semibold"><i class="fas fa-angle-right ml-1 mr-1"></i> Lifestyle</a>
                                        </li>
                                        <li class="mb-2">
                                            <a href="#" class="font-weight-semibold"><i class="fas fa-angle-right ml-1 mr-1"></i> Technology</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header accordion-header" role="tab" id="popularPosts">
                                <h3 class="text-3 mb-0">
                                    <a href="#" data-toggle="collapse" data-target="#togglePopularPosts" aria-expanded="false" aria-controls="togglePopularPosts">POPULAR</a>
                                </h3>
                            </div>
                            <div id="togglePopularPosts" class="accordion-body collapse show" aria-labelledby="popularPosts">
                                <div class="card-body">

                                    <article class="row align-items-center mb-3">
                                        <div class="col-4 pr-0">
                                            <a href="blog-single-post.html">
                                                <img src="{{ Theme::url('img/blog/posts/post-1-square.jpg') }}" class="img-fluid hover-effect-2" alt="" />
                                            </a>
                                        </div>
                                        <div class="col-8">
                                            <span class="text-color-primary">Jan 17, 2020</span>
                                            <h4 class="text-2 mb-0">
                                                <a href="blog-single-post.html" class="text-1">Lorem ipsum dolor...</a>
                                            </h4>
                                        </div>
                                    </article>

                                    <article class="row align-items-center mb-3">
                                        <div class="col-4 pr-0">
                                            <a href="blog-single-post.html">
                                                <img src="{{ Theme::url('img/blog/posts/post-2-square.jpg') }}" class="img-fluid hover-effect-2" alt="" />
                                            </a>
                                        </div>
                                        <div class="col-8">
                                            <span class="text-color-primary">Jan 16, 2020</span>
                                            <h4 class="text-2 mb-0">
                                                <a href="blog-single-post.html" class="text-1">Lorem ipsum dolor...</a>
                                            </h4>
                                        </div>
                                    </article>

                                    <article class="row align-items-center mb-3">
                                        <div class="col-4 pr-0">
                                            <a href="blog-single-post.html">
                                                <img src="{{ Theme::url('img/blog/posts/post-3-square.jpg') }}" class="img-fluid hover-effect-2" alt="" />
                                            </a>
                                        </div>
                                        <div class="col-8">
                                            <span class="text-color-primary">Jan 15, 2020</span>
                                            <h4 class="text-2 mb-0">
                                                <a href="blog-single-post.html" class="text-1">Lorem ipsum dolor...</a>
                                            </h4>
                                        </div>
                                    </article>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header accordion-header" role="tab" id="tags">
                                <h3 class="text-3 mb-0">
                                    <a href="#" data-toggle="collapse" data-target="#toggleTags" aria-expanded="false" aria-controls="toggleTags">TAGS</a>
                                </h3>
                            </div>
                            <div id="toggleTags" class="accordion-body collapse show" role="tabpanel" aria-labelledby="tags">
                                <div class="card-body">
                                    <ul class="list-inline">
                                        <li class="list-inline-item"><a href="#" class="badge badge-dark badge-sm badge-pill px-3 py-2 mb-2">NEWS</a></li>
                                        <li class="list-inline-item"><a href="#" class="badge badge-dark badge-sm badge-pill px-3 py-2 mb-2">JOBS</a></li>
                                        <li class="list-inline-item"><a href="#" class="badge badge-dark badge-sm badge-pill px-3 py-2 mb-2">POST</a></li>
                                        <li class="list-inline-item"><a href="#" class="badge badge-dark badge-sm badge-pill px-3 py-2 mb-2">PHOTOS</a></li>
                                        <li class="list-inline-item"><a href="#" class="badge badge-dark badge-sm badge-pill px-3 py-2 mb-2">INNOVATION</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header accordion-header" role="tab" id="sidebarInstagram">
                                <h3 class="text-3 mb-0">
                                    <a href="#" data-toggle="collapse" data-target="#toggleSidebarInstagram" aria-expanded="false" aria-controls="toggleSidebarInstagram">FROM INSTAGRAM</a>
                                </h3>
                            </div>
                            <div id="toggleSidebarInstagram" class="accordion-body collapse show" role="tabpanel" aria-labelledby="sidebarInstagram">
                                <div class="card-body">
                                    <div class="instagram-feed" data-type="nomargins" data-items-number="6"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card bg-primary border-0 rounded">
                        <div class="card-body p-4 my-3">
                            <div class="icon-box icon-box-style-1 align-items-center mb-0">
                                <div class="icon-box-icon pr-2">
                                    <img width="60" src="{{ Theme::url('img/icons/envelope.svg') }}" alt="" data-icon data-plugin-options="{'color': '#FFF'}" />
                                </div>
                                <div class="icon-box-info">
                                    <div class="icon-box-info-title">
                                        <span class="icon-box-sub-title text-color-light">SUBSCRIBE TO OUR</span>
                                        <h4 class="text-color-light font-weight-bold line-height-1 mb-0">NEWSLETTER</h4>
                                    </div>
                                </div>
                            </div>
                            <p class="text-color-light opacity-6 mb-3">Enter your email address to subscribe to my newsletter</p>
                            <form class="newsletter-form newsletter-form-error-pos-2 form-errors-light" action="php/newsletter-subscribe.php" method="post">
                                <div class="newsletter-form-success alert alert-success d-none">
                                    <strong>Success!</strong> You've been added to our email list.
                                </div>
                                <div class="newsletter-form-error alert alert-danger d-none">
                                    <strong>Error!</strong> There was an error to add your email.
                                </div>

                                <div class="form-row">
                                    <div class="form-group col mb-0">
                                        <input type="email" class="newsletter-email form-control rounded-0 border-0 line-height-1" placeholder="Email address" aria-label="Email address" required>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col mb-0">
                                        <input type="submit" value="SUBSCRIBE" class="btn btn-quaternary btn-v-3 font-weight-semibold justify-content-center w-100 rounded-0">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </aside>
                <div class="col-md-8 col-lg-9 order-1 mb-5 mb-md-0">
                    <article class="blog-post mb-4">
                        <div class="d-flex mb-3">
                            <span class="post-date text-color-primary pr-3">{{ mb_strtoupper($post->created_at->isoFormat('Do MMMM YYYY')) }}</span>
                            <span class="post-likes d-flex align-items-center border border-top-0 border-bottom-0 px-3"><i class="lnr lnr-heart text-3 mr-1" aria-label="5 users like this post"></i> 5</span>
                            <a href="#comments" data-hash data-hash-offset="100">
                                <span class="post-comments d-flex align-items-center px-3"><i class="lnr lnr-bubble text-3 mr-1" aria-label="3 users comment this post"></i> 3</span>
                            </a>
                        </div>
                        <header class="blog-post-header mb-3">
                            @if($img = $post->getMyFirstMediaUrl('image')) {{--1170*678--}}
                            <img src="{{ $img }}" class="img-fluid" alt="" />
                            @endif
                        </header>

                        {!! $post->body !!}

                        <footer class="blog-post-footer border border-left-0 border-right-0 py-4 mt-5">
                            <div class="row justify-content-between align-items-center">
                                <div class="col-12 col-sm-auto mb-3 mb-sm-0 mb-md-3 mb-lg-0">
                                    <ul class="list-inline mb-0">
                                        <li class="list-inline-item"><a href="#" class="badge badge-light badge-sm badge-pill px-3 py-2">DESIGN</a></li>
                                        <li class="list-inline-item"><a href="#" class="badge badge-light badge-sm badge-pill px-3 py-2">DEV</a></li>
                                        <li class="list-inline-item"><a href="#" class="badge badge-light badge-sm badge-pill px-3 py-2">CODE</a></li>
                                    </ul>
                                </div>
                                <div class="col-12 col-sm-auto">
                                    <div class="d-flex align-items-center">
                                        <span class="text-2">SHARE THIS POST</span>
                                        <ul class="social-icons social-icons-light social-icons-1 ml-3 js-social-share">
                                            <li class="social-icons-facebook"><a href="#" data-social="facebook" target="_blank" title="Facebook"><i class="fab fa-facebook-f"></i></a></li>
                                            <li class="social-icons-twitter"><a href="#" data-social="twitter" target="_blank" title="Twitter"><i class="fab fa-twitter"></i></a></li>
                                            <li class="social-icons-twitter"><a href="#" data-social="telegram" target="_blank" title="Telegram"><i class="fab fa-telegram"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </footer>
                    </article>

                    @if($user = $post->user)
                    <div class="row">
                        <div class="col">
                            <span class="top-sub-title">ABOUT THE AUTHOR</span>
                            <div class="icon-box icon-box-style-1 align-items-center mt-3">
                                <div class="icon-box-icon">
                                    <img src="{{ \Avatar::create($user->name)->toBase64() }}" class="img-fluid rounded-circle mr-2" alt="" />
                                </div>
                                <div class="icon-box-info">
                                    <div class="icon-box-info-title">
                                        <h4 class="font-weight-bold line-height-1 mb-1">{{ $user->name }}</h4>
                                        {{--<p class="text-1 mb-0">Vestibulum accumsan finibus eros sit amet egestas. Vestibulum at quam faucibus, sollicitudin in. </p>--}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <hr class="mt-4 mb-5">
                    <div class="row">
                        <div class="col-12">
                            <h2 class="font-weight-bold text-3 mb-4">RELATED POSTS</h2>
                        </div>
                        <div class="col-lg-4 mb-4 mb-lg-0">
                            <a href="blog-single-post.html">
                                <div class="card card-style-5 bg-light-5 rounded border-0 p-3" data-plugin-image-background data-plugin-options="{'imageUrl': 'img/blog/posts/post-1-square.jpg'}">
                                    <div class="card-body p-4">
                                        <h3 class="font-weight-bold text-4 mb-1">Amazing Space</h3>
                                        <p>
                                            <i class="far fa-clock mt-1 text-color-primary"></i>
                                            <time class="font-tertiary text-1" datetime="2020-01-16">Jan 17, 2020</time>
                                        </p>
                                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                                        <p class="text-color-dark font-weight-semibold mb-0">
                                            <img src="{{ Theme::url('img/authors/author-1.jpg') }}" class="img-thumbnail-small rounded-circle d-inline-block mr-2" width="25" height="25" alt="" />
                                            by Bob Doe
                                        </p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-4 mb-4 mb-lg-0">
                            <a href="blog-single-post.html">
                                <div class="card card-style-5 bg-light-5 rounded border-0 p-3" data-plugin-image-background data-plugin-options="{'imageUrl': 'img/blog/posts/post-2-square.jpg'}">
                                    <div class="card-body p-4">
                                        <h3 class="font-weight-bold text-4 mb-1">Getting Ready</h3>
                                        <p>
                                            <i class="far fa-clock mt-1 text-color-primary"></i>
                                            <time class="font-tertiary text-1" datetime="2020-01-15">Jan 16, 2020</time>
                                        </p>
                                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                                        <p class="text-color-dark font-weight-semibold mb-0">
                                            <img src="{{ Theme::url('img/authors/author-2.jpg') }}" class="img-thumbnail-small rounded-circle d-inline-block mr-2" width="25" height="25" alt="" />
                                            by John Doe
                                        </p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-4 mb-4 mb-lg-0">
                            <a href="blog-single-post.html">
                                <div class="card card-style-5 bg-light-5 rounded border-0 p-3" data-plugin-image-background data-plugin-options="{'imageUrl': 'img/blog/posts/post-3-square.jpg'}">
                                    <div class="card-body p-4">
                                        <h3 class="font-weight-bold text-4 mb-1">Cool Hobbies</h3>
                                        <p>
                                            <i class="far fa-clock mt-1 text-color-primary"></i>
                                            <time class="font-tertiary text-1" datetime="2020-01-14">Jan 15, 2020</time>
                                        </p>
                                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                                        <p class="text-color-dark font-weight-semibold mb-0">
                                            <img src="{{ Theme::url('img/authors/author-3.jpg') }}" class="img-thumbnail-small rounded-circle d-inline-block mr-2" width="25" height="25" alt="" />
                                            by Jessica Doe
                                        </p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <hr class="my-5">
                    <div id="comments" class="row mb-5">
                        <div class="col">
                            <h2 class="font-weight-bold text-3">COMMENTS (3)</h2>
                            <ul class="comments">
                                <li>
                                    <div class="comment">
                                        <div class="d-none d-sm-block">
                                            <img class="avatar rounded-circle" alt="" src="{{ Theme::url('img/authors/author-2.jpg') }}">
                                        </div>
                                        <div class="comment-block">
													<span class="comment-by">
														<strong class="comment-author text-color-dark text-4">Robert Doe</strong>
														<span class="comment-date text-color-light-3">MARCH 5, 2020 at 2:28 pm</span>
														<span class="comment-reply"><a href="#" class="opacity-8">REPLY</a></span>
													</span>
                                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam viverra euismod odio, gravida pellentesque urna varius vitae, gravida pellentesque urna varius vitae. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam viverra euismod odio, gravida pellentesque urna varius vitae. Sed dui lorem, adipiscing in adipiscing et, interdum nec metus. Mauris ultricies, justo eu convallis placerat, felis enim ornare nisi, vitae mattis nulla ante id dui.</p>
                                        </div>
                                    </div>

                                    <ul class="comments reply">
                                        <li>
                                            <div class="comment">
                                                <div class="d-none d-sm-block">
                                                    <img class="avatar rounded-circle" alt="" src="{{ Theme::url('img/authors/author-3.jpg') }}">
                                                </div>
                                                <div class="comment-block">
															<span class="comment-by">
																<strong class="comment-author text-color-dark text-4">Jessica Doe</strong>
																<span class="comment-date text-color-light-3">MARCH 5, 2020 at 2:28 pm</span>
																<span class="comment-reply"><a href="#" class="opacity-8">REPLY</a></span>
															</span>
                                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam viverra euismod odio, gravida pellentesque urna varius vitae, gravida pellentesque urna varius vitae.</p>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="comment">
                                                <div class="d-none d-sm-block">
                                                    <img class="avatar rounded-circle" alt="" src="{{ Theme::url('img/authors/author-1.jpg') }}">
                                                </div>
                                                <div class="comment-block">
															<span class="comment-by">
																<strong class="comment-author text-color-dark text-4">John Doe</strong>
																<span class="comment-date text-color-light-3">MARCH 5, 2020 at 2:28 pm</span>
																<span class="comment-reply"><a href="#" class="opacity-8">REPLY</a></span>
															</span>
                                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam viverra euismod odio, gravida pellentesque urna varius vitae, gravida pellentesque urna varius vitae.</p>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <div class="comment">
                                        <div class="d-none d-sm-block">
                                            <img class="avatar rounded-circle" alt="" src="{{ Theme::url('img/authors/author-1.jpg') }}">
                                        </div>
                                        <div class="comment-block">
													<span class="comment-by">
														<strong class="comment-author text-color-dark text-4">John Doe</strong>
														<span class="comment-date text-color-light-3">MARCH 5, 2020 at 2:28 pm</span>
														<span class="comment-reply"><a href="#" class="opacity-8">REPLY</a></span>
													</span>
                                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="comment">
                                        <div class="d-none d-sm-block">
                                            <img class="avatar rounded-circle" alt="" src="{{ Theme::url('img/authors/author-3.jpg') }}">
                                        </div>
                                        <div class="comment-block">
													<span class="comment-by">
														<strong class="comment-author text-color-dark text-4">Jessica Doe</strong>
														<span class="comment-date text-color-light-3">MARCH 5, 2020 at 2:28 pm</span>
														<span class="comment-reply"><a href="#" class="opacity-8">REPLY</a></span>
													</span>
                                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div id="#leavecomment" class="row">
                        <div class="col">
                            <h2 class="font-weight-bold text-3 mb-3">LEAVE A COMMENT</h2>
                            <form class="form-style-2" action="#" method="post">
                                <div class="form-row">
                                    <div class="form-group col">
                                        <textarea class="form-control bg-light-5 border-0 rounded-0" placeholder="Comment" rows="6" name="comment" required></textarea>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <input type="text" value="" class="form-control border-0 rounded-0" name="name" placeholder="Name" required>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <input type="email" value="" class="form-control border-0 rounded-0" name="email" placeholder="E-mail" required>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <input type="text" value="" class="form-control border-0 rounded-0" name="website" placeholder="Website">
                                    </div>
                                </div>
                                <div class="form-row mt-2">
                                    <div class="col">
                                        <input type="submit" value="POST COMMENT" class="btn btn-primary btn-rounded btn-h-2 btn-v-2 font-weight-bold text-0">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="mb-5"></div>
@endsection