<?php

namespace IDAnalyzer2\Api\Contract;

use IDAnalyzer2\ApiBase;
use IDAnalyzer2\RequestPayload;use IDAnalyzer2\SDKException;

class RmTemplate extends ApiBase
{
    public string $uri = "/contract/{templateId}";
    public string $method = "DELETE";

    function __construct()
    {
        $this->initFields([
            self::RouteParam("templateId", "string", true, null, "Template ID to delete"),
        ]);
    }
}