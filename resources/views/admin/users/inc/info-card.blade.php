<div class="card">
    <div class="card-body">
        @isset($user)
            <div class="text-center">
                <a href="{{ $img = $user->getAvatar() }}" class="js-popup-image">
                    <img src="{{ $img }}" style="width: 100px" class="profile-user-img img-responsive img-circle">
                </a>
            </div>
            <br>

            <div class="text-center">
                <strong>{{ $user->fullname }}</strong>
            </div>
            <br>
            <table class="table">
                <tr>
                    <th style="width: 50%">Дата реєстрації:</th>
                    <td>{{ $user->getDatetime('created_at') }}</td>
                </tr>
                <tr>
                    <th>Активність:</th>
                    <td>
                        {{ $user->getDatetime('activity_at') ?: '-' }}
                        @if ($user->isOnline())
                            <small class="text-success" data-toggle="tooltip" title="Зараз OnLine"><i class="fas fa-circle"></i></small>
                        @endif
                    </td>
                </tr>
{{--                <tr>--}}
{{--                    <th>Замовлення:</th>--}}
{{--                    <td><a href="{{ route('admin.orders.index', ['user_id' => $user->id]) }}" target="_blank">[ {{ $user->ordersOrdered->count() }} ]</a></td>--}}
{{--                </tr>--}}
            </table>
        @else
            <div class="text-center">
                <a href="/vendor/lte3/img/no-avatar.png" class="js-popup-image">
                    <img src="/vendor/lte3/img/no-avatar.png" style="width: 100px" class="profile-user-img img-responsive img-circle">
                </a>
            </div>
            <br>

            <div class="text-center">
                <strong>Створення нового <br> користувача</strong>
            </div>
        @endisset
    </div>
</div>
