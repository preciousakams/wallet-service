<?php

namespace App\Models;

use App\Models\IzyInvest\IzyInvestPlan;
use App\Models\IzyInvest\IzyInvestUserPlan;
use App\VirtualBankAccount;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Traits\HasPermissions;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Browser;
use App\Traits\UUID;

class User extends Authenticatable  implements JWTSubject, MustVerifyEmail
{
    use UUID;
    use HasRoles;
    use HasPermissions;
    use Notifiable;

    /**
     * connection used by this model
     *
     * @var string
     */
    protected $guard_name = 'api_user';

    protected static $recordEvents = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function getName()
    {
        return ucfirst($this->first_name . ' ' . $this->last_name);
    }

    public function getNameAttribute()
    {
        return ucfirst($this->first_name . ' ' . $this->last_name);
    }

    public function izyPay()
    {
        return $this->hasOne(IzyPay::class)
            ->withDefault([
                'balance' => 0.00
            ]);
    }

    public function virtualBank()
    {
        return $this->belongsTo(virtualBankAccount::class);
    }

    public function virtualAccount()
    {
        return $this->hasOne(virtualBankAccount::class)
            ->with('bank');
    }

    public function izyBudget()
    {
        return $this->hasOne(IzyBudget::class)
            ->withDefault([
                'balance' => 0.00
            ]);
    }

    public function referralBonusHistory()
    {
        return $this->hasMany(ReferralBonusHistory::class,'user_id');
    }

    public function referralPayoutHistory()
    {
        return $this->hasMany(ReferralPayoutHistory::class, 'refferral_id');
    }

    public function referralCandidates()
    {
        return $this->hasMany(__CLASS__,'referral_id');
    }

    public function referral()
    {
        return $this->belongsTo(__CLASS__, 'referral_id')
            ->withDefault();
    }

    public function hasReferral(): bool
    {
        return $this->referral_id != null;
    }

    public function referralAccount()
    {
        return $this->hasOne(ReferralAccount::class)
            ->withDefault([
                'balance' => 0.00,
                'bank_id' => 0,
                'bank_name' => null,
                'account_number' => null,
                'account_name' => null,
            ]);
    }

    public function hasReferralBankAccountInfo()
    {
        if ($this->bankDetails()->exists()) {
            if ($this->bankDetails->bank_name &&
                $this->bankDetails->account_name &&
                $this->bankDetails->account_number) {
                return true;
            }
        }

        return false;
    }

    public function getFullPlatformDetails(): array
    {
        $platform = [];

        if (Browser::isMobile()) {
            $deviceType = 'Mobile';

        } elseif (Browser::isTablet()) {
            $deviceType = 'Tablet';
        } else {
            $deviceType = 'Desktop';
        }

        $platform['device'] = $deviceType;
        $platform['platform_family'] = Browser::platformFamily() ;
        $platform['device_family'] = Browser::deviceFamily();
        $platform['device_model'] = Browser::deviceModel();
        $platform['browser'] = Browser::browserFamily();
        $platform['device_agent'] = Browser::userAgent();

        return $platform;
    }

    public function investPlans()
    {
        return $this->hasMany(IzyInvestUserPlan::class, 'user_id', 'id');
    }

    public function hasSpecifiedPlan($planId)
    {
        return $this->whereHas('investPlans', function($q) use($planId) {
            $q->where([['active', 1], ['izyinvest_plan_id', $planId]]);
        })->get()->toArray();
    }
}
