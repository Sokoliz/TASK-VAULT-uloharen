<?php
/**
 * Navbar - Univerzálny navigačný panel pre vloženie do stránok
 * Použitie: include_once __DIR__.'/../parts/navbar.php';
 * 
 * Nastavte pred includom tieto premenné:
 * - $isPublic = true/false (ak nie je nastavené, použije sa false = privátna stránka)
 * - $showHomeButton = true/false (ak nie je nastavené, použije sa true = zobrazí tlačidlo Home)
 * - $showThemeSwitch = true/false (ak nie je nastavené, použije sa true = zobrazí prepínač tmavého režimu)
 * - $navbarType = 'standard'|'user_info'|'simple' (ak nie je nastavené, použije sa 'standard')
 *   - 'standard': bežný navigačný panel s Home a Logout/Login tlačidlami
 *   - 'user_info': navigačný panel s uvítaním používateľa, dátumom a časom (ako v content.view.php)
 *   - 'simple': jednoduchý navigačný panel len s logom (ako v login.view.php)
 */

// Zapnutie sessions pre kontrolu používateľa
if (!isset($_SESSION)) {
    session_start();
}

// Default hodnoty
if (!isset($isPublic)) {
    $isPublic = false;
}

if (!isset($showHomeButton)) {
    $showHomeButton = true;
}

if (!isset($showThemeSwitch)) {
    $showThemeSwitch = true;
}

if (!isset($navbarType)) {
    $navbarType = 'standard';
}

// Pre user_info typ potrebujeme meno používateľa
$username = isset($_SESSION['user']) ? $_SESSION['user'] : '';
?>

<header class="m-0 p-0">
    <nav class="navbar navbar-expand-lg pt-3 text-dark">
        <div class="menu container">
            <a href="index.php" class="navbar-brand">
                <img src="/public/img/logo1.png" width="45" alt="Kalendar" class="d-inline-block align-middle mr-2">    
                <span class="logo_text align-middle">Productivity Hub</span>
            </a>
            
            <?php if ($navbarType != 'simple'): ?>
                <button type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation" class="navbar-toggler"><span class="navbar-toggler-icon"></span></button>
                
                <?php if ($navbarType == 'standard'): ?>
                <div id="navbarSupportedContent" class="collapse navbar-collapse">
                    <ul class="navbar-nav ml-auto">
                        <?php if (!$isPublic): ?>
                            <?php if ($showHomeButton): ?>
                                <li><a href="/content" class="btn text-primary mr-2"><i class="fas fa-home pr-2"></i>Home</a></li>    
                            <?php endif; ?>
                            <li><a href="/logout" class="btn text-primary mr-2">Log out</a></li>
                        <?php else: ?>
                            <li><a href="/login" class="btn text-primary mr-2">Log in</a></li>
                            <li><a href="/register" class="btn btn-primary">Sign Up</a></li>
                        <?php endif; ?>
                        
                        <?php if ($showThemeSwitch): ?>
                        <!-- Prepínač tmavého režimu -->
                        <li class="nav-item theme-switch-wrapper">
                            <span class="mode-text btn text-primary">Mode</span>
                            <i class="fas fa-moon mode-icon fa-lg"></i>
                            <div id="toggle-button-ui"></div>
                        </li>
                        <?php endif; ?>
                    </ul>
                </div>
                <?php elseif ($navbarType == 'user_info'): ?>
                <div class="navbar-nav ml-auto d-flex flex-row align-items-center">
                    <span class="btn text-primary mx-2 nowrap"><i class="fas fa-user pr-2"></i>Welcome <?= strtoupper($username) ?>!</span>
                    <span class="btn text-primary mx-2 nowrap"><i class="far fa-calendar-alt pr-2"></i>Date: <span class="d-inline"><?= date('d. m. Y') ?></span></span>
                    <span class="btn text-primary mx-2 nowrap"><i class="far fa-clock pr-2"></i>Time: <span class="d-inline clock"></span></span>
                    
                    <?php if ($showThemeSwitch): ?>
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

<?php if ($navbarType == 'user_info'): ?>
<!-- Script pre zobrazenie hodín v reálnom čase (pre user_info typ) -->
<script src="/public/js/clock.js"></script>
<?php endif; ?> 