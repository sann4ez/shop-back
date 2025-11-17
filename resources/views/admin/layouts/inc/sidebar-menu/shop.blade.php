<!-- Sidebar Menu -->
<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column js-activeable" data-widget="treeview" role="menu" data-accordion="false">

        <li class="nav-item">
            <a href="#" class="nav-link">
                <i class="nav-icon far fa-futbol"></i>
                <p>Товари<i class="fas fa-angle-left right"></i></p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="/admin/products" class="nav-link" data-pat="products/">
                        <i class="nav-icon far fa-circle"></i>
                        <p>Всі товари</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="/admin/terms?vocabulary=product_categories" class="nav-link"
                       data-pat="vocabulary=product_categories">
                        <i class="nav-icon far fa-circle"></i>
                        <p>Категорії</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/admin/attributes" class="nav-link" data-pat="attributes|properties">
                        <i class="nav-icon far fa-circle"></i>
                        <p>Атрибути</p>
                    </a>
                </li>
            </ul>
        </li>

        <li class="nav-item">
            <a href="/admin/orders" class="nav-link" data-pat="orders">
                <i class="nav-icon fas fa-inbox"></i>
                <p>
                    Замовлення
                    @if($count = \App\Models\Order::whereType(\App\Models\Order::TYPE_ORDER)->wherePerform(\App\Models\Order::PERFORM_PENDING)->count())
                        <span class="badge badge-warning right">{{ $count }}</span>
                    @endif
                </p>
            </a>
        </li>

{{--        @if(\Domain::getOpt('leads.on', null, 'lead.read'))--}}
{{--            <li class="nav-item">--}}
{{--                <a href="/admin/leads" class="nav-link">--}}
{{--                    --}}{{--<i class="nav-icon fas fa-address-card"></i>--}}
{{--                    <i class="nav-icon fas fa-dove"></i>--}}
{{--                    <p>--}}
{{--                        Ліди--}}
{{--                        @if($count = \App\Models\Lead::whereStatus(\App\Models\Item::getSettingsValue(\App\Models\Item::TYPE_LEAD_STATUS, \App\Models\Item::SETTINGS_LEAD_CREATED, 'new'))->count())--}}
{{--                            <span class="badge badge-warning right">{{ $count }}</span>--}}
{{--                        @endif--}}
{{--                    </p>--}}
{{--                </a>--}}
{{--            </li>--}}
{{--        @endif--}}

{{--        @if(\Domain::getOpt('comments.on', null, 'comment.read'))--}}
{{--            <li class="nav-item">--}}
{{--                <a href="/admin/comments" class="nav-link">--}}
{{--                    <i class="nav-icon far fa-comment-dots"></i>--}}
{{--                    <p>--}}
{{--                        Коментарі--}}
{{--                        @if($count = \App\Models\Extern\Comment::whereStatus(\App\Models\Extern\Comment::STATUS_MODERATION)->count())--}}
{{--                            <span class="badge badge-warning right">{{ $count }}</span>--}}
{{--                        @endif--}}
{{--                    </p>--}}
{{--                </a>--}}
{{--            </li>--}}
{{--        @endif--}}

        <li class="nav-item">
            <a href="/admin/pages" class="nav-link" data-pat="pages">
                <i class="nav-icon far fa-file-alt"></i>
                <p>Сторінки</p>
            </a>
        </li>

{{--        <li class="nav-item">--}}
{{--            <a href="#" class="nav-link">--}}
{{--                <i class="nav-icon fas fa-file-image"></i>--}}
{{--                <p>Блог<i class="fas fa-angle-left right"></i></p>--}}
{{--            </a>--}}
{{--            <ul class="nav nav-treeview">--}}
{{--                <li class="nav-item">--}}
{{--                    <a href="/admin/posts" class="nav-link" data-pat="posts">--}}
{{--                        <i class="nav-icon far fa-circle"></i>--}}
{{--                        <p>Публікації</p>--}}
{{--                    </a>--}}
{{--                </li>--}}
{{--                    <li class="nav-item">--}}
{{--                        <a href="/admin/terms?vocabulary=post_categories" class="nav-link"--}}
{{--                           data-pat="vocabulary=post_categories">--}}
{{--                            <i class="nav-icon far fa-circle"></i>--}}
{{--                            <p>Категорії</p>--}}
{{--                        </a>--}}
{{--                    </li>--}}
{{--                    <li class="nav-item">--}}
{{--                        <a href="/admin/terms?vocabulary=tags" class="nav-link" data-pat="vocabulary=tags">--}}
{{--                            <i class="nav-icon far fa-circle"></i>--}}
{{--                            <p>Теги</p>--}}
{{--                        </a>--}}
{{--                    </li>--}}
{{--            </ul>--}}
{{--        </li>--}}

        <li class="nav-item">
            <a href="/admin/blocks" class="nav-link" data-pat="blocks">
                <i class="nav-icon fas fa-shapes"></i>
                <p>Блоки</p>
            </a>
        </li>

{{--        @if(\Domain::getOpt('menu', null, 'menu.read'))--}}
{{--            <li class="nav-item">--}}
{{--                <a href="/admin/menu" class="nav-link" data-pat="menu|menu-items">--}}
{{--                    <i class="nav-icon fas fa-list-ul"></i>--}}
{{--                    <p>Меню</p>--}}
{{--                </a>--}}
{{--            </li>--}}
{{--        @endif--}}

        <li class="nav-item">
            <a href="/admin/settings" class="nav-link" data-pat="settings|translations">
                <i class="nav-icon fas fa-cogs"></i>
                <p>Налаштування</p>
            </a>
        </li>

        <li class="nav-item">
            <a href="/admin/users" class="nav-link" data-pat="users">
                <i class="nav-icon fas fa-users"></i>
                <p>Користувачі</p>
            </a>
        </li>

{{--        <li class="nav-item">--}}
{{--            <a href="/admin/roles" class="nav-link" data-pat="roles">--}}
{{--                <i class="nav-icon fas fa-user-tag"></i>--}}
{{--                <p>Ролі</p>--}}
{{--            </a>--}}
{{--        </li>--}}
    </ul>
</nav>
