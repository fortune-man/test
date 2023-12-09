<?php
/*
if(isset($_GET['mode']) && $_GET['mode']) $mode =  trim(strip_tags(clean_xss_attributes($_GET['mode'])));
if(isset($_GET['com']) && $_GET['com']) $com =  trim(strip_tags(clean_xss_attributes($_GET['com'])));
if(isset($_GET['usr']) && $_GET['usr']) $usr =  trim(strip_tags(clean_xss_attributes($_GET['usr'])));
if(isset($_GET['sm']) && $_GET['sm']) $sm =  trim(strip_tags(clean_xss_attributes($_GET['sm'])));

set_time_limit(3);
if(!$mode || !$com || !$usr) exit;

include_once('./sub_site.php');
$datetime = date("Y-m-d H:i:s");

$chat_f = mysqli_fetch_array(mysqli_query($sub_site['proxy']," select * a_chat_b where computer ='{$com}' and profile = '{$usr}'  "));

*/
// Create a TCP/IP socket

echo $address = '127.0.0.1';// 61.109.34.245
$port = 80;
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

// Bind the socket to an address and port
socket_bind($socket, $address, $port);

// Listen for incoming connections
socket_listen($socket);

// Create an array to hold client sockets
$clients = array($socket);

while (true) {
    // Create a copy of the clients array to use with socket_select()
    $read = $clients;

    // Set up a blocking call to socket_select()
    if (socket_select($read, $write = NULL, $except = NULL, $tv_sec = NULL) < 1) {
        continue;
    }

    // Handle new client connections
    if (in_array($socket, $read)) {
        $newSocket = socket_accept($socket);
        $clients[] = $newSocket;

        // Send a welcome message to the client
        $message = "Welcome to the chat server!";
        socket_write($newSocket, $message, strlen($message));

        // Remove the listening socket from the read array
        $key = array_search($socket, $read);
        unset($read[$key]);
    }

    // Handle client messages
    foreach ($read as $client) {
        $data = socket_read($client, 1024);
        if ($data === false) {
            // Remove disconnected client
            $key = array_search($client, $clients);
            unset($clients[$key]);
            continue;
        }

        // Broadcast the received message to all clients
        foreach ($clients as $sendClient) {
            if ($sendClient !== $socket && $sendClient !== $client) {
                socket_write($sendClient, $data, strlen($data));
            }
        }
    }
}

// Close the sockets
foreach ($clients as $client) {
    socket_close($client);
}
socket_close($socket);

?>