<?php
// Construct variables
$cartTotal = 10.00; // This amount needs to be sourced from your application
$passphrase = 'jt7NOE43FZPn';
$data = array(
    // Merchant details
    'merchant_id' => $_POST['merchant_id'],
    'merchant_key' => $_POST['merchant_key'],
    'return_url' => $_POST['return_url'],
    'cancel_url' => $_POST['cancel_url'],
    'notify_url' => $_POST['notify_url'],
    // Buyer details
    'name_first' => $_POST['name_first'],
    'name_last' => $_POST['name_last'],
    'email_address' => 'test@test.com',
    // Transaction details
    'm_payment_id' => '1234', //Unique payment ID to pass through to notify_url
    'amount' => number_format(sprintf('%.2f', $cartTotal), 2, '.', ''),
    'item_name' => 'Order#123',
);

$signature = generateSignature($data, $passphrase);
$data['signature'] = $signature;

// If in testing mode make use of either sandbox.payfast.co.za or www.payfast.co.za
$testingMode = true;
$pfHost = $testingMode ? 'sandbox.payfast.co.za' : 'www.payfast.co.za';
$htmlForm = '<form action="https://' . $pfHost . '/eng/process" method="post">';

foreach ($data as $name => $value) {
    $htmlForm .= '<input name="' . $name . '" type="hidden" value=\'' . $value . '\' />';
}
$htmlForm .= '<input type="submit" value="Pay Now" /></form>';
echo ($htmlForm);

function generateSignature($data, $passPhrase = null)
{
    // Create parameter string
    $pfOutput = '';
    foreach ($data as $key => $val) {
        if ($val !== '') {
            $pfOutput .= $key . '=' . urlencode(trim($val)) . '&';
        }
    }
    // Remove last ampersand
    $getString = substr($pfOutput, 0, -1);
    if ($passPhrase !== null) {
        $getString .= '&passphrase=' . urlencode(trim($passPhrase));
    }
    return md5($getString);
}