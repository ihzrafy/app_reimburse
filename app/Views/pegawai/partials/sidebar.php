<aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">

        <!-- Dashboard -->
        <li class="nav-item">
            <a class="nav-link <?= url_is('pegawai/dashboard') ? '' : 'collapsed' ?>" href="/pegawai/dashboard">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <!-- Reimbursements -->
        <li class="nav-item">
            <a class="nav-link <?= url_is('pegawai/request*') ? '' : 'collapsed' ?>" href="/pegawai/request">
                <i class="bi bi-journal-text"></i>
                <span>Reimbursements</span>
            </a>
        </li>

    </ul>
</aside>
