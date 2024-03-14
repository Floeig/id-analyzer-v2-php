<?php

namespace IDAnalyzer2\Api\Transaction;

use IDAnalyzer2\ApiBase;
use IDAnalyzer2\RequestPayload;use IDAnalyzer2\SDKException;

class RmTransaction extends ApiBase
{
    public string $uri = "/transaction/{transactionId}";
    public string $method = "DELETE";

    function __construct()
    {
        $this->initFields([
            RequestPayload::RouteParam("transactionId", "string", true, null, "Transaction ID")
        ]);
    }
}