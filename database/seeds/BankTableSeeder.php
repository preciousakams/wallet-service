<?php

use Illuminate\Database\Seeder;

class BankTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['name' => 'GUARANTY TRUST BANK PLC' , 'alias' => 'GTBANK', 'code' => '058'],
            ['name' => 'FIRST BANK OF NIGERIA PLC' , 'alias' => 'FBN', 'code' => '011'],
            ['name' => 'FIRST CITY MONUMENT BANK' , 'alias' => 'FCMB', 'code' => '214'],
            ['name' => 'UNITY BANK PLC' , 'alias' => 'UNITY BANK PLC', 'code' => '215'],
            ['name' => 'STANBIC IBTC BANK PLC' , 'alias' => 'STANBIC IBTC BANK PLC', 'code' => '221'],
            ['name' => 'STERLING BANK PLC' , 'alias' => 'STERLING BANK PLC', 'code' => '232'],
            ['name' => 'JAIZ BANK PLC' , 'alias' => 'JAIZ BANK PLC', 'code' => '301'],
            ['name' => 'PAGA' , 'alias' => 'PAGA', 'code' => '327'],
            ['name' => 'HERITAGE BANK' , 'alias' => 'HERITAGE BANK', 'code' => '023'],
            ['name' => 'UNION BANK OF NIGERIA PLC' , 'alias' => 'UNION BANK OF NIGERIA PLC', 'code' => '032'],
            ['name' => 'UNITED BANK FOR AFRICA PLC' , 'alias' => 'UBA', 'code' => '033'],
            ['name' => 'WEMA BANK PLC' , 'alias' => 'WEMA BANK PLC', 'code' => '035'],
            ['name' => 'ACCESS BANK NIGERIA LTD' , 'alias' => 'ACCESS BANK', 'code' => '044'],
            ['name' => 'ECOBANK NIGERIA PLC' , 'alias' => 'ECOBANK', 'code' => '050'],
            ['name' => 'FIDELITY BANK PLC' , 'alias' => 'FIDELITY BANK', 'code' => '070'],
            ['name' => 'SKYE BANK PLC' , 'alias' => 'SKYE BANK', 'code' => '076'],
            ['name' => 'KEYSTONE BANK LTD' , 'alias' => 'KEYSTONE BANK LTD', 'code' => '082'],
            ['name' => 'ZENITH INTERNATIONAL BANK LTD' , 'alias' => 'ZENITH INTERNATIONAL BANK LTD', 'code' => '057'],
            ['name' => 'DIAMOND BANK LTD' , 'alias' => 'DIAMOND BANK LTD', 'code' => '063'],
            ['name' => 'STANDARD CHARTERED BANK NIGERIA LTD' , 'alias' => 'STANDARD CHARTERED BANK', 'code' => '068'],
            ['name' => 'NIGERIA INTERNATIONAL BANK (CITIBANK)' , 'alias' => 'CITIBANK', 'code' => '023'],
            ['name' => 'NPF Microfinance Bank' , 'alias' => 'NPF Microfinance Bank', 'code' => '552'],
            ['name' => 'FBNQuest Merchant Bank Limited' , 'alias' => 'FBNQuest Merchant Bank Limited', 'code' => '060002'],
            ['name' => 'IBILE MFB' , 'alias' => 'IBILE MFB', 'code' => '090118'],
            ['name' => 'HASAL MICROFINANCE BANK' , 'alias' => 'HASAL MICROFINANCE BANK', 'code' => '090121'],
            ['name' => 'SUNTRUST BANK' , 'alias' => 'SUNTRUST BANK', 'code' => '100'],
            ['name' => 'PROVIDUS BANK' , 'alias' => 'PROVIDUS BANK', 'code' => '101'],
            ['name' => 'RAND MERCHANT BANK' , 'alias' => 'RAND MERCHANT BANK', 'code' => '502'],
            ['name' => 'PARALLEX MFB' , 'alias' => 'PARALLEX MFB', 'code' => '526'],
            ['name' => 'CORONATION MERCHANT BANK' , 'alias' => 'CORONATION MERCHANT BANK', 'code' => '559'],
            ['name' => 'Page MFBank' , 'alias' => 'Page MFBank', 'code' => '560'],
            ['name' => 'New Prudential Bank' , 'alias' => 'New Prudential Bank', 'code' => '561'],
            ['name' => 'FSDH MERCHANT BANK LIMIT' , 'alias' => 'FSDH MERCHANT BANK LIMIT', 'code' => '601'],
            ['name' => 'FINATRUST MICROFINANCE BANK' , 'alias' => 'FINATRUST MICROFINANCE BANK', 'code' => '608']
        ];
        DB::table('banks')->insert($data);
    }
}
