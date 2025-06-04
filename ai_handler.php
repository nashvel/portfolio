<?php
// ai_handler.php

header('Content-Type: application/json');

// 1. REPLACE THIS WITH YOUR NEW, PRIVATE API KEY
$apiKey = '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'; 

// 2. CHOOSE YOUR AI MODEL (example for OpenRouter - replace if using a different provider/model)
// For OpenRouter, find model names on their site. Example: 'nousresearch/nous-hermes-2-mixtral-8x7b-dpo' (a free model)
// For direct OpenAI, it might be 'gpt-3.5-turbo'
$modelName = 'mistralai/devstral-small:free'; // Example free model on OpenRouter

// 3. API ENDPOINT (example for OpenRouter - replace if using a different provider)
$apiUrl = 'https://openrouter.ai/api/v1/chat/completions';

// Get user message from POST request
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

// Prepare data for the AI API
$data = [
    'model' => $modelName,
    'messages' => [
        ['role' => 'system', 'content' => 'You are a helpful assistant.'], // Optional: System message to set AI behavior
        ['role' => 'user', 'content' => $userMessage]
    ]
];

// Use cURL to send request to AI API
$ch = curl_init($apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $apiKey,
    // For OpenRouter, you might need to add your site URL as HTTP-Referer (check their docs)
    // 'HTTP-Referer: https://your-site-url.com', // Replace with your actual site URL if required
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

// Extract the AI's reply (this might vary slightly based on API provider)
$aiReply = $responseData['choices'][0]['message']['content'] ?? 'Sorry, I could not get a response.';

// Send reply back to frontend
echo json_encode(['reply' => $aiReply]);

?>
