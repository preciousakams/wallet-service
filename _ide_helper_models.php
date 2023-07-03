<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\Transaction
 *
 * @property string $id
 * @property float $amount
 * @property string $type
 * @property string $reference
 * @property array|null $meta
 * @property string $transactionable_id
 * @property string $transactionable_type
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $transactionable
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Transaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Transaction newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Transaction onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Transaction query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Transaction whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Transaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Transaction whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Transaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Transaction whereMeta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Transaction whereReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Transaction whereTransactionableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Transaction whereTransactionableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Transaction whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Transaction whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Transaction withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Transaction withoutTrashed()
 */
	class Transaction extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\IzyPay
 *
 * @property string $id
 * @property string $user_id
 * @property float $balance
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Transaction[] $transactions
 * @property-read int|null $transactions_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\IzyPay newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\IzyPay newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\IzyPay onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\IzyPay query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\IzyPay whereBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\IzyPay whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\IzyPay whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\IzyPay whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\IzyPay whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\IzyPay whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\IzyPay withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\IzyPay withoutTrashed()
 */
	class IzyPay extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property string $id
 * @property string $liveizy_id
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string $password
 * @property string|null $date_of_birth
 * @property string|null $phone
 * @property string|null $profile_image
 * @property string|null $gender
 * @property string|null $city_of_origin
 * @property string|null $state_of_origin
 * @property string $country
 * @property string|null $marital_status
 * @property float $monthly_income
 * @property string|null $number_of_children
 * @property string|null $office_address
 * @property string|null $place_of_business
 * @property string|null $previous_property_manager
 * @property string|null $referral_id
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string|null $remember_token
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\IzyBudget $izyBudget
 * @property-read \App\Models\IzyPay $izyPay
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereCityOfOrigin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereDateOfBirth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereLiveizyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereMaritalStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereMonthlyIncome($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereNumberOfChildren($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereOfficeAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User wherePlaceOfBusiness($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User wherePreviousPropertyManager($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereProfileImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereReferralId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereStateOfOrigin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereUpdatedAt($value)
 */
	class User extends \Eloquent implements \Tymon\JWTAuth\Contracts\JWTSubject, \Illuminate\Contracts\Auth\MustVerifyEmail {}
}

namespace App\Models{
/**
 * App\Models\Activity
 *
 * @property int $id
 * @property string|null $log_name
 * @property string $description
 * @property string|null $subject_id
 * @property string|null $subject_type
 * @property string|null $causer_id
 * @property string|null $causer_type
 * @property \Illuminate\Support\Collection|null $properties
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $causer
 * @property-read \Illuminate\Support\Collection $changes
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $subject
 * @method static \Illuminate\Database\Eloquent\Builder|\Spatie\Activitylog\Models\Activity causedBy(\Illuminate\Database\Eloquent\Model $causer)
 * @method static \Illuminate\Database\Eloquent\Builder|\Spatie\Activitylog\Models\Activity forSubject(\Illuminate\Database\Eloquent\Model $subject)
 * @method static \Illuminate\Database\Eloquent\Builder|\Spatie\Activitylog\Models\Activity inLog($logNames)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Activity newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Activity newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Activity query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Activity whereCauserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Activity whereCauserType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Activity whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Activity whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Activity whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Activity whereLogName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Activity whereProperties($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Activity whereSubjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Activity whereSubjectType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Activity whereUpdatedAt($value)
 */
	class Activity extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\IzyBudget
 *
 * @property string $id
 * @property string $user_id
 * @property float $balance
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Transaction[] $transactions
 * @property-read int|null $transactions_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\IzyBudget newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\IzyBudget newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\IzyBudget onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\IzyBudget query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\IzyBudget whereBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\IzyBudget whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\IzyBudget whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\IzyBudget whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\IzyBudget whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\IzyBudget whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\IzyBudget withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\IzyBudget withoutTrashed()
 */
	class IzyBudget extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Administrator
 *
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Administrator newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Administrator newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Administrator query()
 */
	class Administrator extends \Eloquent implements \Tymon\JWTAuth\Contracts\JWTSubject {}
}

namespace App\Models{
/**
 * App\Models\IzyBudgetPlan
 *
 * @property string $id
 * @property float $amount
 * @property float $discount
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\IzyBudget[] $izyBudgets
 * @property-read int|null $izy_budgets_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\IzyBudgetPlan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\IzyBudgetPlan newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\IzyBudgetPlan onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\IzyBudgetPlan query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\IzyBudgetPlan whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\IzyBudgetPlan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\IzyBudgetPlan whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\IzyBudgetPlan whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\IzyBudgetPlan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\IzyBudgetPlan whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\IzyBudgetPlan withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\IzyBudgetPlan withoutTrashed()
 */
	class IzyBudgetPlan extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ReferralConfig
 *
 * @property int $id
 * @property int $commission_percentage
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReferralConfig newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReferralConfig newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReferralConfig query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReferralConfig whereCommissionPercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReferralConfig whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReferralConfig whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReferralConfig whereUpdatedAt($value)
 */
	class ReferralConfig extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ReferralAccount
 *
 * @property int $id
 * @property float $balance
 * @property string|null $bank_id
 * @property int|null $account_number
 * @property string|null $account_name
 * @property string $user_id
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReferralAccount newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReferralAccount newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReferralAccount query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReferralAccount whereAccountName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReferralAccount whereAccountNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReferralAccount whereBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReferralAccount whereBankId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReferralAccount whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReferralAccount whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReferralAccount whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReferralAccount whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReferralAccount whereUserId($value)
 */
	class ReferralAccount extends \Eloquent {}
}

namespace App{
/**
 * App\Bank
 *
 * @property int $id
 * @property string $name
 * @property string $alias
 * @property string $code
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Bank newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Bank newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Bank query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Bank whereAlias($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Bank whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Bank whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Bank whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Bank whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Bank whereUpdatedAt($value)
 */
	class Bank extends \Eloquent {}
}

