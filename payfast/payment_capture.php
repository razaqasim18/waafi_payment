<?php 
function generateSignature($data, $passPhrase = null) {
    // Create parameter string
    $pfOutput = '';
    foreach( $data as $key => $val ) {
        if($val !== '') {
            $pfOutput .= $key .'='. urlencode( trim( $val ) ) .'&';
        }
    }
    var_dump($pfOutput);
    // Remove last ampersand
    $getString = substr( $pfOutput, 0, -1 );
   
    if( $passPhrase !== null ) {
        $getString .= '&passphrase='. urlencode( trim( $passPhrase ) );
    }
     var_dump($getString);
    return md5($getString);
}

// Construct variables
$cartTotal = 10.00; // This amount needs to be sourced from your application
$passphrase = 'jt7NOE43FZPn';
$data = array(
    // Merchant details
    'merchant_id' => '10000100',
    'merchant_key' => '46f0cd694581a',
    'return_url' => 'http://meat.digitallinkcard.xyz/payment_finished.php',
    'cancel_url' => 'http://meat.digitallinkcard.xyz/payment_cancel.php',
    'notify_url' => 'http://meat.digitallinkcard.xyz/payment_notify.php',
    // Buyer details
    'name_first' => 'First Name',
    'name_last'  => 'Last Name',
    'email_address'=> 'test@test.com',
    // Transaction details
    'm_payment_id' => '1234', //Unique payment ID to pass through to notify_url
    'amount' => number_format( sprintf( '%.2f', $cartTotal ), 2, '.', '' ),
    'item_name' => 'Order#123'
);

$signature = generateSignature($data, $passphrase);
$data['signature'] = $signature;

// If in testing mode make use of either sandbox.payfast.co.za or www.payfast.co.za
$testingMode = true;
$pfHost = $testingMode ? 'sandbox.payfast.co.za' : 'www.payfast.co.za';
$htmlForm = '<form action="https://'.$pfHost.'/eng/process" method="post">';
foreach($data as $name=> $value)
{
    $htmlForm .= '<input type="text" name="'.$name.'" type="hidden" value=\''.$value.'\' />';
}
$htmlForm .= '<input type="submit" value="Pay Now" /></form>';

?>
<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>
        PayFast Test Payment
    </title>
    <script src="PayFast.js" type="text/javascript"></script>
</head>

<body>
    <!--https://www.payfast.co.za/eng/process-->
    <!-- <form method="POST" action="process.php"> -->
    <!-- <input type="hidden" name="payfast_url" id="payfast_url" value="https://sandbox.payfast.co.za/eng/process">
        <input type="hidden" name="merchant_id" id="merchant_id" value="10000100">
        <input type="hidden" name="merchant_key" id="merchant_key" value="46f0cd694581a">
        <input type="hidden" name="return_url" id="return_url"
            value="http://localhost/php/payfast/payment_finished.php">
        <input type="hidden" name="cancel_url" id="cancel_url"
            value="http://localhost/php/payfast/payment_cancelled.php">
        <input type="hidden" name="notify_url" id="notify_url" value="http://localhost/php/payfast/payment_notify.php">
        <input type="hidden" name="item_name" id="item_name" value="[YOUR PRODUCT]">
        <input type="hidden" name="item_description" id="item_description" value="">
        <input type="hidden" name="email_confirmation" id="email_confirmation" value="1">
        <input type="hidden" name="confirmation_address" id="confirmation_address" value="">

        <h2>PayFast Payment Test</h2>
        <div>
            <p align="justify">Enter the payment information below, the payment will be forwarded to the PayFast testing
                sandbox system.</p>
            <p align="justify">
            <table>
                <tr>
                    <td>Invoice Number: </td>
                    <td><input type="text" name="payment_id" id="payment_id" value="FAKE5551234" /></td>
                </tr>
                <tr>
                    <td>Amount (Rand): </td>
                    <td><input type="text" name="amount" id="amount" value="121.40" /></td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>First Name: </td>
                    <td><input type="text" name="name_first" id="name_first" value="Bob" /></td>
                </tr>
                <tr>
                    <td>Last Name: </td>
                    <td><input type="text" name="name_last" id="name_last" value="Smith" /></td>
                </tr>
                <tr>
                    <td>Receipt Email: </td>
                    <td><input type="text" name="email_address" id="email_address" value="sbtu01@payfast.co.za" /></td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                </tr>

            </table>
            </p>
        </div>
        <form>  -->
    <?php echo $htmlForm ?>
</body>

</html>