<?php
// Get Ngrok Auth Token from file
$token_file = "token.txt";
$token = trim(file_get_contents($token_file));

// Get TCP Port from file
$port_file = "port.txt";
$port = trim(file_get_contents($port_file));

// Download ngrok
system("wget https://bin.equinox.io/c/4VmDzA7iaHb/ngrok-stable-linux-arm.zip");

// Unzip ngrok
system("yes | unzip ngrok-stable-linux-arm.zip");

// Start Ngrok
system("./ngrok --authtoken=$token tcp $port");
?>