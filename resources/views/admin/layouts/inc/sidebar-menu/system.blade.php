<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column js-activeable"
        data-accordion="false">
        <li class="nav-header">DEVELOPER</li>

        <li class="nav-item">
            <a href="/admin/system/logs" class="nav-link">
                <i class="nav-icon fas fa-book"></i>
                <p>Логи</p>
            </a>
        </li>

        <li class="nav-item">
            <a href="/admin/system/tinker" class="nav-link">
                <i class="nav-icon fas fa-terminal"></i>
                <p>Tinker</p>
            </a>
        </li>

        @if(file_exists(public_path('/docs')))
        <li class="nav-item">
            <a href="/docs" target="_blank" class="nav-link">
                <i class="nav-icon fas fa-file-code"></i>
                <p>API-Docs</p>
            </a>
        </li>
        @endif
    </ul>
</nav>
