<?php
session_start();
require 'config.php';
if (!isset($_SESSION['logged_in']) && (!isset($_POST['password']) || $_POST['password'] !== $admin_password)) {
  echo '<form method="post"><input type="password" name="password"><button>Login</button></form>';
  exit;
}
$_SESSION['logged_in'] = true;

// Liste commandes
$commandes = glob('commandes/*.json');
foreach ($commandes as $file) {
  $data = json_decode(file_get_contents($file), true);
  echo '<div><h3>' . $data['order_id'] . '</h3><pre>' . print_r($data, true) . '</pre>';
  echo '<form method="post" action=""><input name="status" value="' . $data['status'] . '"><input name="file" value="' . $file . '"><button>Mettre à jour</button></form></div>';
}

// Update status
if (isset($_POST['status']) && isset($_POST['file'])) {
  $data = json_decode(file_get_contents($_POST['file']), true);
  $data['status'] = trim(htmlspecialchars($_POST['status']));
  file_put_contents($_POST['file'], json_encode($data));
}
?>
