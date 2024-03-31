<?php

namespace App\Http\Controllers\API;

class BaseApiController
{
    protected function responseFailure($message, $data = null)
    {
        $structure = [
            'status' => false,
            'message' => $message
        ];

        if ($data != null) {
            $structure['data'] = $data;
        }

        return $structure;
    }

    protected function responseSuccess($data = null)
    {
        $structure = [
            'status' => true,
            'message' => 'OK',
        ];

        if ($data != null) {
            $structure['data'] = $data;
        }

        return $structure;
    }
}
