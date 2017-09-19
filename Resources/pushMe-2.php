



<?php

// Put your device token here (without spaces):
// bb49b26e410f0d3aa944cccd79acf975371c65d3388ea795653b2bdc378ed5d7
$deviceToken = '5fdc6f7714eff0794493d807dbed143334069aa428590a6d901f316364eb60ea';
// $deviceToken = '5fdc6f7714eff0794493d807dbed143334069aa428590a6d901f316364eb60ea';

// ida1234
$passphrase = '';

// Put your alert message here:
$message = '视频通话邀请。。。';

////////////////////////////////////////////////////////////////////////////////

// $ctx = stream_context_create();
$ctx = stream_context_create([
            'ssl' => [
                'verify_peer'      => false,
                'verify_peer_name' => false
            ]
        ]);
stream_context_set_option($ctx, 'ssl', 'local_cert', 'VoIP_cer_key.pem');
stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

// Open a connection to the APNS server
$fp = stream_socket_client('ssl://gateway.sandbox.push.apple.com:2195', $err,$errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);

if (!$fp)
	exit("Failed to connect: $err $errstr" . PHP_EOL);

echo 'Connected to APNS' . PHP_EOL;

// Create the payload body
$body['aps'] = array(
    'content-available' => '1',
	'alert' => $message,
	'sound' => 'voip_call.caf',
    'badge' => 10,
		'UUID' => 'asssssssss',
		'handle' => 'Elkins',
		'hasVideo' => true
	);

// Encode the payload as JSON
$payload = json_encode($body);

// Build the binary notification
$msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;

// Send it to the server
$result = fwrite($fp, $msg, strlen($msg));

if (!$result)
	echo 'Message not delivered' . PHP_EOL;
else
	echo 'Message successfully delivered' . PHP_EOL;

// Close the connection to the server
fclose($fp);

?>
