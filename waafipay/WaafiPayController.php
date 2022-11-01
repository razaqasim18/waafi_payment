<?php

namespace App\Http\Controllers;

use App\Models\AdminSettings;
use App\Models\Campaigns;
use App\Models\Donations;
use App\Models\PaymentGateways;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Mail;

class WaafiPayController extends Controller
{
    //
    public function __construct(AdminSettings $settings, Request $request)
    {
        $this->settings = $settings::first();
        $this->request = $request;
    }

    public function charge()
    {
        try {

            //     if (!$this->request->expectsJson()) {
            //         abort(404);
            //     }

            // Campaign
            $campaign = Campaigns::findOrFail($this->request->_id);
            // Get Payment Gateway
            $payment = PaymentGateways::whereId($this->request->payment_gateway)->whereName('Waafi pay')->firstOrFail();

            $amount = $this->request->amount;
            $accountNo = $this->request->account; //'252617347163'

            $url = 'https://api.waafipay.net/asm';
            $client = new Client();
            $promise = $client->postAsync($url, [
                'json' => [
                    "schemaVersion" => "1.0",
                    "requestId" => "101111003",
                    "timestamp" => "client_timestamp",
                    "channelName" => "WEB",
                    "serviceName" => "API_PURCHASE",
                    "serviceParams" => [
                        "merchantUid" => "M0910472",
                        "apiUserId" => $payment->key,
                        "apiKey" => "$payment->key_secret",
                        "paymentMethod" => "mwallet_account",
                        "payerInfo" => ["accountNo" => $accountNo],
                        "transactionInfo" => [
                            "referenceId" => "",
                            "invoiceId" => "1",
                            "amount" => $amount,
                            "currency" => 'USD',
                            "description" => 'Wallet transaction',
                        ],
                    ],
                ],
            ])->then(
                function ($res) {
                    $response = json_decode($res->getBody()->getContents());
                    return $response;
                },
                function ($e) {
                    // $response = [];
                    $response->data = $e->getMessage();
                    return $response;
                }
            );
            $response = $promise->wait();
            //   $response->responseCode == "2001"
            if ($response->responseCode == "2001") {
                return $this->generatePaymentResponse();
            } else {
                return response()->json([
                    'success' => false,
                    'errors' => ['payment is denied please try again'],
                ]);
            }
        } catch (Exception $e) {
            # Display error on client
            return response()->json([
                'success' => false,
                'errors' => $e->getMessage(),
            ]);
        }
    }

    protected function generatePaymentResponse()
    {
        // Insert DB and send Mail
        $sql = new Donations;
        $sql->campaigns_id = $this->request->campaign_id;
        $sql->txn_id = 'null';
        $sql->fullname = $this->request->full_name;
        $sql->email = $this->request->email;
        $sql->country = $this->request->country;
        $sql->postal_code = $this->request->postal_code;
        $sql->donation = $this->request->amount;
        $sql->account_number = $this->request->account;
        $sql->payment_gateway = 'Waafi Pay';
        $sql->comment = $this->request->input('comment', '');
        $sql->anonymous = $this->request->input('anonymous', '0');
        $sql->rewards_id = $this->request->input('_pledge', 0);
        $sql->bank_transfer = strip_tags($this->request->bank_transfer);
        $sql->approved = '0';
        $sql->save();

        // Send Email
        $campaign = Campaigns::find($this->request->campaign_id);

        $sender = $this->settings->email_no_reply;
        $titleSite = $this->settings->title;
        $_emailUser = $this->request->email;
        $campaignID = $campaign->id;
        $campaignTitle = $campaign->title;
        $organizerName = $campaign->user()->name;
        $organizerEmail = $campaign->user()->email;
        $fullNameUser = $this->request->full_name;
        $paymentGateway = 'Waafi pay';

        Mail::send('emails.thanks-donor', array(
            'data' => $campaignID,
            'fullname' => $fullNameUser,
            'title_site' => $titleSite,
            'campaign_id' => $campaignID,
            'organizer_name' => $organizerName,
            'organizer_email' => $organizerEmail,
            'payment_gateway' => $paymentGateway,
        ),
            function ($message) use ($sender, $fullNameUser, $titleSite, $_emailUser, $campaignTitle) {
                $message->from($sender, $titleSite)
                    ->to($_emailUser, $fullNameUser)
                    ->subject(trans('misc.thanks_donation') . ' - ' . $campaignTitle . ' || ' . $titleSite);
            });

        return response()->json([
            'success' => true,
            'url' => url('paypal/donation/success', $this->request->campaign_id),
        ]);
    } // End generatePaymentResponse

}