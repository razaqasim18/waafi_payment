 <?php 
        $ozow_params = [
          'SiteCode' => $_POST['SiteCode'], # find this here https://dash.ozow.com/MerchantAdmin/Site
          'CountryCode' => 'ZA', # only supports ZA currently
          'CurrencyCode' => 'ZAR', # only supports ZAR currently
          'Amount' =>  $_POST['Amount'], # this is R1000, not working well for floats though
          'TransactionReference' => $_POST['TransactionReference'], # your internal reference to match against
          'BankReference' => $_POST['BankReference'], # the reference that the customer will see on their bank statement
          'Customer' => $_POST['Customer'],
          'CancelUrl' => $_POST['CancelUrl'],
          'SuccessUrl' => $_POST['NotifyUrl'],
          'NotifyUrl' => $_POST['NotifyUrl'], # needs to be an endpoint that accepts a POST request
          "IsTest" => "false", # pretty self explanatory
        ];
        $HashCheck = hash('sha512', strtolower(implode($ozow_params). "31203cdc0fbb40209a181927444e94e1"), false);
        
        $client = new Client();
        
        // Send Request
        $response = $client->post( 'https://api.ozow.com/postpaymentrequest', [
            'headers' => [
                'ApiKey' => 'de260d3ca28f43eb9d7f5c8ef915557c',
                'Content-Type' => 'application/json',
                'Accept' =>'application/json',
            ],
            'json' => [
              'SiteCode' => $_POST['SiteCode'], # find this here https://dash.ozow.com/MerchantAdmin/Site
              'CountryCode' => 'ZA', # only supports ZA currently
              'CurrencyCode' => 'ZAR', # only supports ZAR currently
              'Amount' =>  $_POST['Amount'], # this is R1000, not working well for floats though
              'TransactionReference' => $_POST['TransactionReference'], # your internal reference to match against
              'BankReference' => $_POST['BankReference'], # the reference that the customer will see on their bank statement
              'Customer' => $_POST['Customer'],
              'CancelUrl' => $_POST['CancelUrl'],
              'SuccessUrl' => $_POST['NotifyUrl'],
              'NotifyUrl' => $_POST['NotifyUrl'], # needs to be an endpoint that accepts a POST request
              "IsTest" => "false", # pretty self explanatory
              "HashCheck" => $HashCheck,
              "GenerateShortUrl" => "true",
            ]
        ]);
        $result = json_decode($response->getBody()->getContents()); 
        return redirect($result->url);

?>