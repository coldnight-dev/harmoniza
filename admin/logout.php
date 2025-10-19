<?php
/**
 * Logout Admin - Harmon'Iza
 */

session_start();
session_destroy();
header('Location: login.php');
exit;
