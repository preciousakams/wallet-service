<?php


namespace App\Repository\Referral;


use Illuminate\Http\Request;

interface ReferralInterface
{
    public function sendInvitation(string $email, string $callbackUrl);

    public function getBalance();

    public function getAccountDetails();

    public function updateAccountDetails(Request $requestData);

    public function getReferredCandidates();

    public function getReferral();

    //Admin
    public function getReferralsAsAdmin();

    public function updateConfig(Request $request);

    public function getConfig();
}
