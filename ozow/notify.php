<?php 
    
    $pfData = $_POST;
    
    // Strip any slashes in data
    foreach( $pfData as $key => $val ) {
        $pfData[$key] = stripslashes( $val );
    }
    
    // Convert posted variables to a string
    // foreach( $pfData as $key => $val ) {
    //     if( $key !== 'signature' ) {
    //         $pfParamString .= $key .'='. urlencode( $val ) .'&';
    //     } else {
    //         break;
    //     }
    // }
    
    // $pfParamString = substr( $pfParamString, 0, -1 );
     $ozow_params = [
      'SiteCode' => $pfData['SiteCode'], # find this here https://dash.ozow.com/MerchantAdmin/Site
      'TransactionId' =>  $pfData['TransactionId'], # only supports ZA currently
      'TransactionReference' =>  $pfData['TransactionReference'], # your internal reference to match against
      'Amount' =>  $pfData['Amount'], # this is R1000, not working well for floats though
      'Status' =>  $pfData['Status'], # only supports ZAR currently
      'Optional1' =>  $pfData['Optional1'],
      'Optional2' =>  $pfData['Optional2'],
      'Optional3' =>  $pfData['Optional3'],
      'Optional4' =>  $pfData['Optional4'],
      'Optional5' =>  $pfData['Optional5'],
      'CurrencyCode' =>  $pfData['CurrencyCode'], # the reference that the customer will see on their bank statement
      "IsTest" =>  $pfData['IsTest'], # pretty self explanatory
      "StatusMessage" =>  $pfData['StatusMessage'],
    ];
    
    $ozow_params['HashCheck'] = hash('sha512', strtolower(implode($ozow_params). "31203cdc0fbb40209a181927444e94e1"), false);
       
    $verify = ($ozow_params['HashCheck'] == $pfData['Hash']) ? 'true' : 'false';
    
    if(!is_file($file)){
        file_put_contents("type.txt", $_SERVER['REQUEST_METHOD']);     // Save our content to the file.
        file_put_contents("input.txt",$pfData['TransactionReference']);     // Save our content to the file.
        file_put_contents("verify.txt",$verify."- response hash -".$pfData['Hash']."- created hash -".$ozow_params['HashCheck']); 
    }
    
?>