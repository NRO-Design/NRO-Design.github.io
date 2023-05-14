<?php
// Prompt user to enter Ngrok auth token
echo "Enter your Ngrok Auth Token: ";
$authToken = trim(fgets(STDIN));

// Prompt user to enter the TCP port to open
echo "Enter the TCP Port you want to open (maximum 65535): ";
$tcpPort = trim(fgets(STDIN));

// Check system architecture and set Ngrok download URL
$system_arch = shell_exec("uname -m");

if (strpos($system_arch, 'arm') !== false) {
    $ngrok_url = 'https://bin.equinox.io/c/4VmDzA7iaHb/ngrok-stable-linux-arm.zip';
} else if (strpos($system_arch, 'aarch64') !== false) {
    $ngrok_url = 'https://bin.equinox.io/c/4VmDzA7iaHb/ngrok-stable-linux-arm64.zip';
} else if (strpos($system_arch, 'x86_64') !== false) {
    $ngrok_url = 'https://bin.equinox.io/c/4VmDzA7iaHb/ngrok-stable-linux-amd64.zip';
} else {
    die("Error: Unsupported system architecture\n");
}

// Download and extract Ngrok
shell_exec("wget -q $ngrok_url -O ngrok.zip");
shell_exec("unzip -qq ngrok.zip");
shell_exec("rm ngrok.zip");

// Set execute permissions on the Ngrok binary
shell_exec("chmod +x ./ngrok");

// Check if ngrok is executable and can run on this system
$file_type = shell_exec("file ./ngrok");
$is_executable = strpos($file_type, 'executable') !== false;
if (!$is_executable) {
    die("Error: Ngrok is not executable\n");
}

// Check if ngrok is 64-bit or not
$is_64bit = strpos($file_type, '64-bit') !== false;
if ($is_64bit && strpos($system_arch, 'x86_64') === false) {
    die("Error: Ngrok is 64-bit but your system is not\n");
}

// Connect to Ngrok with auth token and open TCP port
$ngrokCommand = "./ngrok authtoken $authToken && ./ngrok tcp $tcpPort";
passthru($ngrokCommand);

?>
