<aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">

        <!-- Dashboard -->
        <li class="nav-item">
            <a class="nav-link <?= url_is('admin/dashboard') ? '' : 'collapsed' ?>" href="/admin/dashboard">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <!-- Reimbursements -->
        <li class="nav-item">
            <a class="nav-link <?= url_is('admin/reimbursement') ? '' : 'collapsed' ?>" href="/admin/reimbursement">
                <i class="bi bi-journal-text"></i>
                <span>Reimbursements</span>
            </a>
        </li>

        <!-- Payroll -->
        <li class="nav-item">
            <a class="nav-link <?= url_is('admin/payroll') ? '' : 'collapsed' ?>" href="/admin/payroll">
                <i class="bi bi-cash-stack"></i>
                <span>Payroll</span>
            </a>
        </li>

    </ul>
</aside>
