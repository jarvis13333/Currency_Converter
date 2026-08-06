<?php
/**
 * Currency Converter API Proxy
 * Fetches live rates from Frankfurter API and returns JSON.
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed. Use GET.']);
    exit;
}

$amount = isset($_GET['amount']) ? floatval($_GET['amount']) : 0;
$from   = isset($_GET['from'])   ? strtoupper(trim($_GET['from'])) : '';
$to     = isset($_GET['to'])     ? strtoupper(trim($_GET['to']))   : '';

$allowed = ['USD', 'EUR', 'GBP', 'MYR', 'JPY', 'CAD', 'AUD', 'SGD'];

if ($amount < 0.01) {
    http_response_code(400);
    echo json_encode(['error' => 'Amount must be at least 0.01.']);
    exit;
}

if (!in_array($from, $allowed, true) || !in_array($to, $allowed, true)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid currency code.']);
    exit;
}

if ($from === $to) {
    echo json_encode([
        'amount'    => $amount,
        'from'      => $from,
        'to'        => $to,
        'rate'      => 1,
        'result'    => round($amount, 4),
        'date'      => date('Y-m-d'),
    ]);
    exit;
}

$url = sprintf(
    'https://api.frankfurter.app/latest?amount=%s&from=%s&to=%s',
    urlencode((string) $amount),
    urlencode($from),
    urlencode($to)
);

$response = false;
$errorMsg = '';

if (function_exists('curl_init')) {
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 10,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_HTTPHEADER     => ['Accept: application/json'],
    ]);
    $response = curl_exec($ch);
    if ($response === false) {
        $errorMsg = curl_error($ch);
    }
    $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($response !== false && $httpCode >= 400) {
        $errorMsg = "Upstream API returned HTTP {$httpCode}";
        $response = false;
    }
} else {
    $ctx = stream_context_create([
        'http' => [
            'timeout' => 10,
            'header'  => "Accept: application/json\r\n",
        ],
        'ssl' => [
            'verify_peer'      => true,
            'verify_peer_name' => true,
        ],
    ]);
    $response = @file_get_contents($url, false, $ctx);
    if ($response === false) {
        $errorMsg = 'Failed to reach exchange rate API.';
    }
}

if ($response === false) {
    http_response_code(502);
    echo json_encode(['error' => 'Unable to fetch exchange rates. ' . ($errorMsg ?: 'Please try again.')]);
    exit;
}

$data = json_decode($response, true);

if (
    !is_array($data)
    || !isset($data['rates'][$to])
    || !isset($data['amount'], $data['base'])
) {
    http_response_code(502);
    echo json_encode(['error' => 'Unexpected response from exchange rate API.']);
    exit;
}

$rate   = floatval($data['rates'][$to]) / floatval($data['amount']);
$result = floatval($data['rates'][$to]);

echo json_encode([
    'amount' => floatval($data['amount']),
    'from'   => $data['base'],
    'to'     => $to,
    'rate'   => round($rate, 6),
    'result' => round($result, 4),
    'date'   => $data['date'] ?? date('Y-m-d'),
]);
