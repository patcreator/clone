<?php 
    $url = $_GET['page']??'';
    $goto = '';
    if (preg_match("/^s\/(.+)$/", $url)) {
        $goto = str_replace('s/','',$url);
        $goto = strtolower($goto);
        $goto = file_exists('app/pages/s/'.$goto.'.php')?'app/pages/s/'.$goto.'.php':'app/pages/s/'.$goto.'.html';
    }elseif(preg_match("/^logout[\/]?$/", $url)){
        include_once 'app/auth/logout.php';
    }elseif(preg_match("/^@support\/(.+){2,}$/", $url)){
        $goto = 'app/pages/other/'.str_replace('@support/', '', $url).'.php';
    }elseif(preg_match("/^(\@)?users$/", $url)){
        $goto = 'app/pages/other/users.php';
    }elseif(preg_match("/^(@)?cog\/(.+)$/", $url)){
        include_once 'app/system/api/'.str_replace('@cog/', '', $url).'.php';
        exit;
    }elseif (preg_match("/^logout[\/]?$/", $url)){
    switch ($url) {
        case 's/Dashboard':
            $goto = 'app/pages/s/dashboard.php';
            break;
        case '':
            $goto = './';
            break;
        case 'api.php':
            $goto = './';
            break;
        case 'auth/create-account':
            $goto = 'app/auth/create-account.php';
            break;
        case 'auth/forgot-password':
            $goto = 'app/auth/forgot-password.php';
            break;
        case 'auth/reset-password':
            $goto = 'app/auth/reset-password.php';
            break;
        case 'auth/send-email':
            $goto = 'app/auth/send-email.php';
            break;
        default:
            $goto = 'app/system/errors/404.html';
            break;
    }
}
    include_once 'app/pages/sections/h.php';
    ?>
    <title>  <?= strtoupper(basename($_SERVER['REQUEST_URI'])) ?> . <?= $site_name?></title>
<main id="main-content" class="<?= $class3 ?> pb-4 h-screen">

<div class="pt-20 rounded-xl pb-8 px-0">
    <?php
    if (file_exists($goto)) {
        include_once $goto;
    }else{
        include_once 'app/system/errors/404.html';
    }
    ?>
</div>
</main>
    <?php
    include_once 'app/pages/sections/f.php';
?>