<?php

namespace IDAnalyzer2\Api\Profile;

use IDAnalyzer2\ApiBase;
use IDAnalyzer2\RequestPayload;use IDAnalyzer2\SDKException;

class RmProfile extends ApiBase
{
    public string $uri = "/profile/{profileId}";
    public string $method = "DELETE";

    function __construct()
    {
        $this->initFields([
            RequestPayload::RouteParam('profileId', 'string', true, null, 'KYC Profile ID'),
        ]);
    }
}