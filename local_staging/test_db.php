<?php
$conn1 = @mysqli_connect('127.0.0.1', 'root', '', 'visionity_staging');
echo "127.0.0.1: " . ($conn1 ? "OK" : mysqli_connect_error()) . "\n";

$conn2 = @mysqli_connect('localhost', 'root', '', 'visionity_staging');
echo "localhost: " . ($conn2 ? "OK" : mysqli_connect_error()) . "\n";
