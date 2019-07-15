<?php
/**
 * User: jrient
 * Date: 2019/7/14
 * Time: 15:05
 */

namespace App\Http\Components;

class ApiRequest
{
    const STATUS_SUCCESS = 200;
    const STATUS_FAIL = 400;

    static public function successJsonRequest($data, $message = 'success')
    {
        self::jsonRequest(self::STATUS_SUCCESS, $data, $message);
    }

    static public function failJsonRequest($message, $data = [])
    {
        self::jsonRequest(self::STATUS_FAIL, $data, $message);
    }

    static public function jsonRequest($status, $data, $message)
    {
        header('Content-type: application/json');
        echo json_encode([
            'status' => $status,
            'data' => $data,
            'message' => $message
        ]);
        exit;
    }
}

