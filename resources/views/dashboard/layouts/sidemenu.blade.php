<nav class="side-nav">
    <a href="" class="intro-x flex items-center pl-5 pt-4">
        <img alt="Midone - HTML Admin Template" class="w-6" src="/assets/images/logo.svg">
        <span class="hidden xl:block text-white text-lg ml-3"> Indorack </span>
    </a>
    <div class="side-nav__devider my-6"></div>
    <ul>
        <li>
            <a href="/dashboard" class="side-menu {{ Request::is('dashboard') ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon"> <i data-lucide="home"></i> </div>
                <div class="side-menu__title">
                    Dashboard
                </div>
            </a>
        </li>
        <li class="side-nav__devider my-6"></li>
        <li>
            <a href="side-menu-light-inbox.html" class="side-menu">
                <div class="side-menu__icon"> <i data-lucide="users" class="block mx-auto"></i> </div>
                <div class="side-menu__title"> Data User </div>
            </a>
        </li>
        <li>
            <a href="side-menu-light-file-manager.html" class="side-menu">
                <div class="side-menu__icon"> <i data-lucide="shopping-cart"></i> </div>
                <div class="side-menu__title"> Product </div>
            </a>
        </li>
        <li>
            <a href="/dashboard/category"
                class="side-menu {{ Request::is('dashboard/category') ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon"> <i data-lucide="layers"></i> </div>
                <div class="side-menu__title"> Category </div>
            </a>
        </li>
        <li>
            <a href="/dashboard/file-manager"
                class="side-menu {{ Request::is('dashboard/file-manager') ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon"> <i data-lucide="folder"></i> </div>
                <div class="side-menu__title"> File Manager </div>
            </a>
        </li>
        <li>
            <a href="/dashboard/info" class="side-menu {{ Request::is('dashboard/info') ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon"> <i data-lucide="airplay"></i> </div>
                <div class="side-menu__title"> Info </div>
            </a>
        </li>

        <li class="side-nav__devider my-6"></li>
    </ul>
</nav>
