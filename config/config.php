<?php

session_start();

define('BASE_URL', '/Web-Portfolio');

//local turnstile keys are gitignored
if (file_exists(__DIR__.'/config.local.php')) {
    require_once 'config.local.php';
}