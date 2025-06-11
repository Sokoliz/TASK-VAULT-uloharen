<?php
/**
 * Navbar - Univerzálny navigačný panel v OOP štýle
 */
class Navbar {
    private $isPublic;
    private $showHomeButton;
    private $showThemeSwitch;
    private $navbarType;
    private $username;

    public function __construct($isPublic = false, $showHomeButton = true, $showThemeSwitch = true, $navbarType = 'standard') {
        if (!isset($_SESSION)) {
            session_start();
        }
        $this->isPublic = $isPublic;
        $this->showHomeButton = $showHomeButton;
        $this->showThemeSwitch = $showThemeSwitch;
        $this->navbarType = $navbarType;
        $this->username = isset($_SESSION['user']) ? $_SESSION['user'] : '';
    }

    public function render() {
        ob_start();
        ?>
<header class="m-0 p-0">
    <nav class="navbar navbar-expand-lg pt-3 text-dark">
        <div class="menu container">
            <a href="index.php" class="navbar-brand">
                <img src="/public/img/logo1.png" width="45" alt="Kalendar" class="d-inline-block align-middle mr-2">
                <span class="logo_text align-middle">Productivity Hub</span>
            </a>
            <?php if ($this->navbarType != 'simple'): ?>
            <button type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"
                class="navbar-toggler"><span class="navbar-toggler-icon"></span></button>
            <?php if ($this->navbarType == 'standard'): ?>
            <div id="navbarSupportedContent" class="collapse navbar-collapse">
                <ul class="navbar-nav ml-auto">
                    <?php if (!$this->isPublic): ?>
                    <?php if ($this->showHomeButton): ?>
                    <li><a href="/content" class="btn text-primary mr-2"><i class="fas fa-home pr-2"></i>Home</a></li>
                    <?php endif; ?>
                    <li><a href="/logout" class="btn text-primary mr-2">Log out</a></li>
                    <?php else: ?>
                    <li><a href="/login" class="btn text-primary mr-2">Log in</a></li>
                    <li><a href="/register" class="btn btn-primary">Sign Up</a></li>
                    <?php endif; ?>
                    <?php if ($this->showThemeSwitch): ?>
                    <!-- Prepínač tmavého režimu -->
                    <li class="nav-item theme-switch-wrapper">
                        <span class="mode-text btn text-primary">Mode</span>
                        <i class="fas fa-moon mode-icon fa-lg"></i>
                        <div id="toggle-button-ui"></div>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
            <?php elseif ($this->navbarType == 'user_info'): ?>
            <div class="navbar-nav ml-auto d-flex flex-row align-items-center">
                <span class="btn text-primary mx-2 nowrap"><i class="fas fa-user pr-2"></i>Welcome
                    <?= strtoupper($this->username) ?>!</span>
                <span class="btn text-primary mx-2 nowrap"><i class="far fa-calendar-alt pr-2"></i>Date: <span
                        class="d-inline"><?= date('d. m. Y') ?></span></span>
                <span class="btn text-primary mx-2 nowrap"><i class="far fa-clock pr-2"></i>Time: <span
                        class="d-inline clock"></span></span>
                <?php if ($this->showThemeSwitch): ?>
                <div class="theme-switch-wrapper mx-2">
                    <span class="mode-text btn text-primary">Mode</span>
                    <i class="fas fa-moon mode-icon fa-lg"></i>
                </div>
                <?php endif; ?>
                <a href="/logout" class="btn text-primary mx-2 nowrap">Log out</a>
            </div>
            <?php endif; ?>
            <?php endif; ?>
        </div>
    </nav>
</header>
<?php if ($this->navbarType == 'user_info'): ?>
<!-- Script pre zobrazenie hodín v reálnom čase (pre user_info typ) -->
<script src="/public/js/clock.js"></script>
<?php endif; ?>
<?php
        return ob_get_clean();
    }
}
// Príklad použitia (odstráňte alebo upravte podľa potreby):
// $navbar = new Navbar();
// echo $navbar->render();
// ... pôvodný proceduralny kód odstránený ... 