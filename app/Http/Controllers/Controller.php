<?php

namespace App\Http\Controllers;

use App\Utils\ApiResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    private $apiResponse;
    public function __construct()
    {
        $this->apiResponse = new ApiResponse();
    }

    public function validateErrorOrSuccess($data)
    {
        if (is_object($data)) {
            $this->apiResponse->setData($data);
        } else {
            $this->apiResponse->setMessageError($data);
        }
    }

    public function setData($data)
    {
        $this->apiResponse->setData($data);
    }

    public function setStatusCode($statusCode)
    {
        $this->apiResponse->setStatusCode($statusCode);
    }

    public function setMessageError($message)
    {
        $this->apiResponse->setMessageError($message);
    }

    public function returnData()
    {
        return $this->apiResponse->returnData();
    }

    public function getElements($model, $element)
    {
        try {
            $element = $model->$element()->where('status', 'A')->get();
            return $element;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}