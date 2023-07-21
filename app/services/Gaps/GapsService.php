<?php

namespace App\Services\Gaps;

use App\Gaps\Exceptions\ServerErrorException;
use Carbon\Carbon;
use Illuminate\Support\Str;

class GapsService extends Gaps
{
    public static function singleTransfer(array $data)
    {
        $data = array_merge($data, [
            'gaps_username' => config('gaps.username'),
            'gaps_accesscode' => config('gaps.access_code'),
            'gaps_password' => config('gaps.password')
        ]);

        $amount = $data['amount'];
        $currentDate = Carbon::now();
        $reference = Str::limit((String)Str::uuid(),8,'') . time();
        $remarks = $data['remarks'];
        $vendorCode = $data['vendorcode'];
        $vendorName = $data['vendorname'];
        $bankCode = $data['vendorbankcode'];
        $accountNumber = $data['vendoracctnumber'];
        $credentials = $data['gaps_accesscode'] . $data['gaps_username'] . $data['gaps_password'] ;
        $transactionData = "<transactions><transaction><amount>$amount</amount><paymentdate>$currentDate</paymentdate><reference>$reference</reference><remarks>$remarks</remarks><vendorcode>$vendorCode</vendorcode><vendorname>$vendorName</vendorname><vendoracctnumber>$accountNumber</vendoracctnumber><vendorbankcode>$bankCode</vendorbankcode></transaction></transactions>$credentials";
        $decodedData = "&lt;transactions&gt;&lt;transaction&gt;&lt;amount&gt;$amount&lt;/amount&gt;&lt;paymentdate&gt;$currentDate&lt;/paymentdate&gt;&lt;reference&gt;$reference&lt;/reference&gt;&lt;remarks&gt;$remarks&lt;/remarks&gt;&lt;vendorcode&gt;$vendorCode&lt;/vendorcode&gt;&lt;vendorname&gt;$vendorName&lt;/vendorname&gt;&lt;vendoracctnumber&gt;$accountNumber&lt;/vendoracctnumber&gt;&lt;vendorbankcode&gt;$bankCode&lt;/vendorbankcode&gt;&lt;/transaction&gt;&lt;/transactions&gt;";
        $hash = hash(config('gaps.hash'),$transactionData);
        $parameters = "<fil:SingleTransfers>
                                 <!--Optional:-->
                              <fil:xmlRequest><![CDATA[
                                <SingleTransfers><transdetails>$decodedData</transdetails>
                                  <accesscode>". $data['gaps_accesscode'] ."</accesscode>
                                  <username>". $data['gaps_username'] ."</username>
                                  <password>". $data['gaps_password'] ."</password>
                                  <hash>$hash</hash>
                                </SingleTransfers>]]>
                              </fil:xmlRequest>
                        </fil:SingleTransfers>";
        $payload = Gaps::getPayload($parameters);
        $headers = Gaps::getHeader($payload,"http://tempuri.org/GAPS_Uploader/FileUploader/SingleTransfers");
        $response = Gaps::initializeCurl($payload,$headers);
        if (!$response) {
            throw new ServerErrorException("Internal server error, try again.");
        }
        $xmlResult = $response->SingleTransfersResponse->SingleTransfersResult;
        $parsedXml =  simplexml_load_string($xmlResult);
        $responseCode = (String) $parsedXml->Code[0];
        $responseMessage = (String) $parsedXml->Message[0];
        return [
            'code' => $responseCode,
            'message' => $responseMessage,
            'reference' => $reference
        ];
    }

    public static function bulkTransfer()
    {
       // $parameters = "";
       // $payload = Gaps::getPayload($parameters);
       // $headers = Gaps::getHeader($payload,"")
       // dd("currently not available");
    }

    public static function transactionQuery($data=[])
    {
        $reference = $data['reference'];
        $username = $data['gaps_username'];
        $accesscode = $data['gaps_accesscode'];
        $password = $data['gaps_password'];
        $parameters = "<TransactionRequery xmlns=\"http://tempuri.org/GAPS_Uploader/FileUploader\">
                            <fil:xmlstring><![CDATA[
                            <TransactionRequery>
                             <transref>$reference</transref>
                             <customerid>$accesscode</customerid>
                             <username>$username</username>
                             <password>$password</password>
                             </TransactionRequery>
                            ]]></fil:xmlstring>
                          <customerid>$accesscode</customerid>
                          <username>$username</username>
                          <password>$password</password>
                        </TransactionRequery>";
        $payload = self::getPayload($parameters);
        $headers = self::getHeader($payload,"http://tempuri.org/GAPS_Uploader/FileUploader/TransactionRequery");
        $response = self::initializeCurl($payload,$headers);
        if (!$response) {
            throw new ServerErrorException("Internal server error, check connection settings");
        }
        $xmlResult = $response->TransactionRequeryResponse->TransactionRequeryResult;
        $parsedXml = simplexml_load_string($xmlResult);
        $responseCode = (String) $parsedXml->Code[0];
        $responseMessage = (String) $parsedXml->Message[0];
        return [
                    'code' => $responseCode,
                    'message' => $responseMessage
               ];
    }

    public static function validateAccountGtb($accountNumber)
    {
        $parameters = "<fil:GetAccountInGTB xmlns=\"http://tempuri.org/GAPS_Uploader/FileUploader\">
                              <accountNo>$accountNumber</accountNo>
                           </fil:GetAccountInGTB>";
        $payload = self::getPayload($parameters);
        $headers = self::getHeader($payload,"http://tempuri.org/GAPS_Uploader/FileUploader/GetAccountInGTB");
        $response = self::initializeCurl($payload,$headers);
        if (!$response) {
            throw new ServerErrorException("Internal server error, try again.");
        }
        $xmlResult = $response->GetAccountInGTBResponse->GetAccountInGTBResult;
        $newParsedXml = simplexml_load_string($xmlResult);
        $responseCode = (String)$newParsedXml->CODE[0];
        if ($responseCode == "1000"){
            $responseMessage = (String)$newParsedXml->ACCOUNTNAME[0];
        } else {
            $responseMessage = (String)$newParsedXml->Error[0];
        }
        return  [
                    'code' => $responseCode,
                    'message' => $responseMessage
                ];
    }

    public static function validateAccountOtherBank($accountNumber,$bankCode)
    {
        $parameters = "<fil:GetAccountInOtherBank xmlns=\"http://tempuri.org/GAPS_Uploader/FileUploader\">
                            <bankcode>$bankCode</bankcode>
                            <accountNo>$accountNumber</accountNo>
                       </fil:GetAccountInOtherBank>";
        $payload = self::getPayload($parameters);
        $headers = self::getHeader($payload,"http://tempuri.org/GAPS_Uploader/FileUploader/GetAccountInOtherBank");
        $response = self::initializeCurl($payload,$headers);
        if (!$response) {
            throw new ServerErrorException("Internal server error, try again.");
        }
        $xmlResult = $response->GetAccountInOtherBankResponse->GetAccountInOtherBankResult;
        $newParsedXml = simplexml_load_string($xmlResult);
        $responseCode = (String)$newParsedXml->CODE[0];
        if ($responseCode == "1000"){
            $responseMessage = (String)$newParsedXml->ACCOUNTNAME[0];
        } else {
            $responseMessage = (String)$newParsedXml->Error[0];
        }
        return  [
                    'code' => $responseCode,
                    'message' => $responseMessage
                ];
    }

    public static function accountBalance($accountNumber)
    {
        $hashed =  hash(config('gaps.hash'),"205140019adewotolabcd1234*0231291771");
        $parameters = "<AccountBalanceRetrieval>
                        <customerid>205140019</customerid>
                        <username>adewotol</username>
                        <password>abcd1234*</password>
                        <accountnumber>0231291771</accountnumber>
                        <hash>$hashed</hash>
                       </AccountBalanceRetrieval>";
        $payload = self::getPayload($parameters);
        $headers = self::getHeader($payload,"http://tempuri.org/GAPS_Uploader/FileUploader/GetAccountInGTB");
        $response = self::initializeCurl($payload,$headers);
        if (!$response) {
            throw new ServerErrorException("Internal server error, try again.");
        }
        dd($response);
        $xmlResult = $response->AccountBalanceRetrievalResponse->AccountBalanceRetrievalResult;
        $parsedXml = simplexml_load_string($xmlResult);
        $responseCode = (String) $parsedXml->Code[0];
        $responseMessage = (String) $parsedXml->Message[0];
        return response()->json([
            'code' => $responseCode,
            'message' => $responseMessage
        ],200);
    }

    public static function accountStatement()
    {
        $parameters = "";
        $payload = self::getPayload($parameters);
        $headers = self::getHeader($payload,"http://tempuri.org/GAPS_Uploader/FileUploader/GetAccountInOtherBank");
        $response = self::initializeCurl($payload,$headers);
        if (!$response) {
            throw new ServerErrorException("Internal server error, try again.");
        }
    }

    public static function directDebit()
    {
        $parameters = "";
        $payload = self::getPayload($parameters);
        $headers = self::getHeader($payload,"http://tempuri.org/GAPS_Uploader/FileUploader/GetAccountInOtherBank");
        $response = self::initializeCurl($payload,$headers);
        if (!$response) {
            throw new ServerErrorException("Internal server error, try again.");
        }
    }

    public static function cardlessWithdrawal()
    {
        $parameters = "";
        $payload = self::getPayload($parameters);
        $headers = self::getHeader($payload,"http://tempuri.org/GAPS_Uploader/FileUploader/GetAccountInOtherBank");
        $response = self::initializeCurl($payload,$headers);
        if (!$response) {
            throw new ServerErrorException("Internal server error, try again.");
        }
    }
}
