<?php

namespace App\Repository\Referral;

use App\Models\ReferralAccount;
use App\Models\ReferralConfig;
use App\Models\User;
use App\Notifications\User\Referral\SendInvitation;
use App\Http\Resources\User\Referral\CandidateCollection;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Database\ConnectionInterface as DB;
use Notification;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;

class ReferralService implements ReferralInterface
{
    private DB $db;
    private ReferralAccount $referralAccount;
    private ReferralConfig $config;
    private User $user;

    /**
     * ReferralService constructor.
     * @param ReferralConfig $config
     * @param DB $db
     * @param ReferralAccount $referralAccount
     * @param User $user
     */
    public function __construct(ReferralConfig $config, DB $db, ReferralAccount $referralAccount, User $user)
    {
        $this->db = $db;
        $this->config = $config;
        $this->referralAccount = $referralAccount;
        $this->user = $user;
    }

    /**
     * @param string $email
     * @param string $callbackUrl
     * @return \Symfony\Component\HttpFoundation\Response
     * Send Invitation email to a non-existing user
     */
    public function sendInvitation(string $email, string $callbackUrl)
    {
        Notification::route('mail', $email)
            ->notify(new SendInvitation(request()->user(), $callbackUrl));

        return ResponseBuilder::asSuccess(Response::HTTP_OK)
            ->withHttpCode(Response::HTTP_OK)
            ->withMessage('Invitation sent successfully')
            ->build();
    }

    public function getBalance()
    {
        return ResponseBuilder::asSuccess(Response::HTTP_OK)
            ->withHttpCode(Response::HTTP_OK)
            ->withMessage('Referral Balance')
            ->withData(['balance' => request()->user()->referralAccount->balance])
            ->build();
    }

    public function getAccountDetails()
    {
        return ResponseBuilder::asSuccess(Response::HTTP_OK)
            ->withHttpCode(Response::HTTP_OK)
            ->withMessage('Account Details')
            ->withData(['details' => request()->user()->referralAccount])
            ->build();
    }

    public function updateAccountDetails(Request $request)
    {
        $this->db->beginTransaction();

        if ($request->user()->referralAccount()->exists()) {
            $referralAccount = $request->user()->referralAccount;
        } else {
            $referralAccount = new $this->referralAccount;
            $referralAccount->user_id = $request->user()->id;
        }
        $referralAccount->bank_id =  $request->bank_id;
        $referralAccount->account_number = $request->account_number;
        $referralAccount->account_name = $request->account_name;
        $referralAccount->save();

        $this->db->commit();

        return ResponseBuilder::asSuccess(Response::HTTP_OK)
            ->withHttpCode(Response::HTTP_OK)
            ->withMessage('Account updated successfully')
            ->withData(['details' => $referralAccount])
            ->build();
    }

    public function getReferredCandidates()
    {
        return response()->json([
            'success' => true,
            'message' => 'Ok',
            'candidates' =>  new CandidateCollection(request()->user()->referralCandidates)
        ]);
    }

    public function getReferral()
    {
        return ResponseBuilder::asSuccess(Response::HTTP_OK)
            ->withHttpCode(Response::HTTP_OK)
            ->withMessage('Referral')
            ->withData(['referral' => request()->user()->referral])
            ->build();
    }

    public function getConfig()
    {
        return ResponseBuilder::asSuccess(Response::HTTP_OK)
            ->withHttpCode(Response::HTTP_OK)
            ->withMessage('Payout config')
            ->withData(['config' => $this->config::firstOrFail()])
            ->build();
    }

    public function getReferralsAsAdmin()
    {
        $referralsId = $this->referralAccount->pluck('user_id')->all();

        $referrals = $this->user->whereIn('id', $referralsId)->get();

        return ResponseBuilder::asSuccess(Response::HTTP_OK)
            ->withHttpCode(Response::HTTP_OK)
            ->withMessage('Referrals')
            ->withData(['referrals' => $referrals->load('referralAccount')])
            ->build();
    }

    public function updateConfig(Request $request)
    {
        $this->db->beginTransaction();

        $config = $this->config::firstOrNew();
        $config->commission_percentage = $request->commission_percentage;
        $config->save();

        $this->db->commit();

        return ResponseBuilder::asSuccess(Response::HTTP_OK)
            ->withHttpCode(Response::HTTP_OK)
            ->withMessage('Config updated successfully')
            ->withData(['config' => $config])
            ->build();

    }
}
