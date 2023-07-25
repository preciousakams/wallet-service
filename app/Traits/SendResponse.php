<?php
namespace App\Traits;

use MarcinOrlowski\ResponseBuilder\ResponseBuilder;
use Symfony\Component\HttpFoundation\Response;

trait SendResponse
{
    /**
     * @param array $data
     * @param string $message
     * @param array $extra
     * @return Response
     */
    public function sendSuccess($data, string $message = '', array $extra = []): Response
    {
        $responseTemp = [
            'success' => true,
            'message' => $message,
            'data' => $data
        ];
        return response()->json($responseTemp);
        // return ResponseBuilder::asSuccess(\Illuminate\Http\Response::HTTP_OK)
        //     ->withHttpCode(Response::HTTP_OK)
        //     ->withMessage($message)
        //     ->withData($data)->build();
    }

    /**
     * @param $error_code
     * @param $message
     * @param int $status_code
     * @return Response
     */
    public function sendError($error_code, $message, $status_code = Response::HTTP_BAD_REQUEST): Response
    {
        /** Because http status code has to be between 100 and 1024 */
        if($error_code < 100) {
            $error_code = $error_code * 10;
        }

        $responseTemp = [
            'message' => $message
        ];

        // return response()->json($responseTemp, $error_code);
        return ResponseBuilder::asError($error_code)
            ->withHttpCode($status_code)
            ->withMessage($message)
            ->build();
    }
}
