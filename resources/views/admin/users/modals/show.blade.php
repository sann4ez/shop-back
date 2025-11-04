<div class="modal-header"><h4 class="modal-title">Перегляд </h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
    </button>
</div>

<div class="modal-body">

    <div class="table-responsive">
        <table class="table">
            <tbody>
            <tr style="width:30%">
                <th>ПІБ</th>
                <td>{{ $user->fullname }}</td>
            </tr>
            <tr>
                <th>Телефон</th>
                <td>{{ $user->phone }}</td>
            </tr>
            <tr>
                <th>Email</th>
                <td>{{ $user->email }}</td>
            </tr>
            <tr>
                <th>Статус</th>
                <td>{{ $user->getStatus() }}</td>
            </tr>
            <tr>
                <th>Реєстрація</th>
                <td>{{ $user->getDatetime('created_at') }}</td>
            </tr>
            <tr>
                <th>Активність</th>
                <td>
                    {{ $user->getDatetime('activity_at') }}
                    @if ($user->isOnline())
                        <small class="text-success" title="On-Line">
                            <i class="fas fa-circle"></i>
                        </small>
                    @endif
                </td>
            </tr>

            </tbody>
        </table>
    </div>
</div>
