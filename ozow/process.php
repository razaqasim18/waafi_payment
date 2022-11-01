<?php 

    $ozow_params = [
          'SiteCode' => $_POST['SiteCode'], # find this here https://dash.ozow.com/MerchantAdmin/Site
          'CountryCode' =>  $_POST['CountryCode'], # only supports ZA currently
          'CurrencyCode' =>  $_POST['CurrencyCode'], # only supports ZAR currently
          'Amount' =>  $_POST['Amount'], # this is R1000, not working well for floats though
          'TransactionReference' =>  $_POST['TransactionReference'], # your internal reference to match against
          'BankReference' =>  $_POST['BankReference'], # the reference that the customer will see on their bank statement
          'Customer' =>  $_POST['Customer'],
          'CancelUrl' =>  $_POST['CancelUrl'],
          'SuccessUrl' =>  $_POST['SuccessUrl'],
          'NotifyUrl' =>  $_POST['NotifyUrl'], # needs to be an endpoint that accepts a POST request
          "IsTest" => "true", # pretty self explanatory
        ];
        
        $ozow_params['HashCheck'] = hash('sha512', strtolower(implode($ozow_params). "31203cdc0fbb40209a181927444e94e1"), false);
       
       
        $htmlForm = '<form action="https://pay.ozow.com/" method="post">';
        foreach($ozow_params as $name => $value)
        {
            $htmlForm .= '<input type="hidden" name="'.$name.'" type="hidden" value=\''.$value.'\' />';
        }
        $htmlForm .= '<input type="submit" value="Pay Now" /></form>';
        echo $htmlForm;

?>