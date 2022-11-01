
<html>
<head>
    <title>Merchant Check Out Page</title>
</head>
<body>
    <center><h1>Please do not refresh this page...</h1></center>
     <center> 
        <img width="300" src="https://admin.meatxpress.shop/public/assets/admin/img/payment/ozow.png"/>
        <br><br><br>
            <form action="process.php" method="post">
                <input type="hidden" name="SiteCode" type="hidden" value='PUL-PUL-001' />
                <input type="hidden" name="CountryCode" type="hidden" value='ZA' />
                <input type="hidden" name="CurrencyCode" type="hidden" value='ZAR' />
                <input type="hidden" name="Amount" type="hidden" value='1786.8' />
                <input type="hidden" name="TransactionReference" type="hidden" value='100004' />
                <input type="hidden" name="BankReference" type="hidden" value='100004' />
                <input type="hidden" name="Customer" type="hidden" value='1' />
                <input type="hidden" name="CancelUrl" type="hidden" value='http://ozowpayment.digitallinkcard.xyz/cancel.php' />
                <input type="hidden" name="SuccessUrl" type="hidden" value='http://ozowpayment.digitallinkcard.xyz/finished.php' />
                <input type="hidden" name="NotifyUrl" type="hidden" value='http://ozowpayment.digitallinkcard.xyz/notify.php' />
                <input type="hidden" name="IsTest" type="hidden" value='true' />
                <input type="hidden" name="HashCheck" type="hidden" value='f9e7fb7c2f2dbdb92c490c46ccb72d0d16bacd987225f20dc9a0b2ad31def53f7d88059fcd115440bef9a36c5e60b223599f6225d412151ee8eae438ab958f20' />
                <input type="submit" value="Pay Now" />
            </form>
    </center>
</body>
</html>


