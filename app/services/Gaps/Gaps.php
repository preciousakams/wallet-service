<?php

namespace App\Services\Gaps;

class Gaps
{
    protected static function getPayload($data = [])
    {
        $payload  = "
                        <soapenv:Envelope xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\" xmlns:fil=\"http://tempuri.org/GAPS_Uploader/FileUploader\">
                           <soapenv:Header/>
                           <soapenv:Body>
                              $data
                           </soapenv:Body>
                        </soapenv:Envelope>
                    ";
        return $payload;
    }

    protected static function getHeader($payload,$soapAction)
    {
        return  $headers = array(
            "POST /Gaps_FileUploader/FileUploader.asmx HTTP/1.1",
            "Host: ebank2.gtbank.com",
            "Content-Type: text/xml; charset=utf-8",
            "Content-Length: ".strlen($payload),
            "SOAPAction:" . $soapAction
        );
    }

    protected static function initializeCurl($payload,$headers)
    {
        $ch = curl_init();
        $url = config('gaps.endpoint');
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        curl_close($ch);
        $response1 = str_replace("<soap:Body>","",$response);
        $response2 = str_replace("</soap:Body>","",$response1);
        $parsedXml = simplexml_load_string($response2);
        return $parsedXml;
    }

}
