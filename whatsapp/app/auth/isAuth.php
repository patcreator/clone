<?php
    session_start();
    if(!isset($_SESSION['user']) || !isset($_SESSION['token']) || !isset($_SESSION['project_id']) || $_SESSION['project_id'] !== '423561'){
        header('location:/clone/whatsapp/login');
    }