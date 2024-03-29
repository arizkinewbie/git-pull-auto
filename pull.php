<?php 
$output = shell_exec('git pull');
echo "status:<br> " . $output;
?>