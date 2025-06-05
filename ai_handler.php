<?php

header('Content-Type: application/json');

$apiKey = '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'; 

$modelName = 'mistralai/devstral-small:free'; 

$apiUrl = 'https://openrouter.ai/api/v1/chat/completions';

$input = json_decode(file_get_contents('php://input'), true);
$userMessage = $input['message'] ?? '';

if (empty($userMessage)) {
    echo json_encode(['reply' => 'Error: No message received.']);
    exit;
}

if ($apiKey === 'YOUR_NEW_PRIVATE_API_KEY_HERE') {
    echo json_encode(['reply' => 'Error: API key not configured in ai_handler.php.']);
    exit;
}

$data = [
    'model' => $modelName,
    'messages' => [
        ['role' => 'user', 'content' => $userMessage]
    ]
];

$ch = curl_init($apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $apiKey,
    'HTTP-Referer: https://dev-nacht.unaux.com/',
    // 'X-Title: My Portfolio Chatbot' // Optional: A name for your app for OpenRouter logs
]);

$response = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);

if ($curlError) {
    echo json_encode(['reply' => 'Error communicating with AI API: ' . $curlError]);
    exit;
}

if ($httpcode != 200) {
    echo json_encode(['reply' => 'Error from AI API: HTTP ' . $httpcode . ' - Response: ' . $response]);
    exit;
}

$responseData = json_decode($response, true);

$aiReply = $responseData['choices'][0]['message']['content'] ?? 'Sorry, I could not get a response.';

echo json_encode(['reply' => $aiReply]);

?>
