<?php 
define('BASEPATH', true); 
require 'application/config/config.php'; 
var_dump($config['csrf_protection']); 
echo '<br>PHP Version: ' . phpversion(); 
$op = function_exists('opcache_get_status') ? opcache_get_status() : false; 
echo '<br>Opcache aktif: ' . ($op ? 'YA' : 'TIDAK'); 
if ($op) { echo '<br>Validate timestamps: ' . (ini_get('opcache.validate_timestamps') ? 'ON' : 'OFF'); 
} echo '<br>File terakhir diubah: ' . date('Y-m-d H:i:s', filemtime('application/config/config.php'));