<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $userMessage = trim($_POST['message'] ?? '');
    $apiKey = 'AIzaSyD9BB5pVH8fXwTBK1ldPI9dtFGdBxQUOjg'; // API Key

    if (empty($userMessage)) {
        echo json_encode(['reply' => 'Missing message.']);
        exit;
    }

    $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=" . $apiKey;
    
    $prompt = "User said: \"$userMessage\"";

    $postData = json_encode([
        'contents' => [[
            'parts' => [[ 'text' => $prompt ]]
        ]]
    ]);

    $headers = [
        'Content-Type: application/json'
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);
    $error = curl_error($ch);
    curl_close($ch);

    if ($error) {
        echo json_encode(['reply' => 'Error: ' . $error]);
        exit;
    }

    $responseData = json_decode($response, true);
    // Extract the bot's reply from the Gemini API response
    $botReply = $responseData['candidates'][0]['content']['parts'][0]['text'] ?? 'No valid response.';
    echo json_encode(['reply' => $botReply]);
}
?>