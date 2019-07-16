<?php

namespace Omnipay\SagePay\Message;

/**
 * Sage Pay Abstract Rest Request.
 * Base for Sage Pay Rest Server.
 */
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\SagePay\Extend\Item as ExtendItem;
use Omnipay\SagePay\ConstantsInterface;
use Psr\Http\Message\ResponseInterface;

abstract class AbstractRestRequest extends AbstractRequest implements ConstantsInterface
{


    /**
     * @var string The service name, used in the endpoint URL.
     */
    protected $service;

    /**
     * @var string The protocol version number.
     */
    protected $apiVersion = 'v1';

    /**
     * @var string Endpoint base URLs.
     */
    protected $liveEndpoint = 'https://pi-test.sagepay.com/api';
    protected $testEndpoint = 'https://pi-test.sagepay.com/api';


    /**
     * @return string URL for the test or live gateway, as appropriate.
     */
    public function getEndpoint()
    {
        return sprintf(
            '%s/%s/%s',
            $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint,
            $this->getApiVersion(),
            $this->getService()
        );
    }

    /**
     * Gets the api version for the end point.
     *
     * @return string
     */
    public function getApiVersion()
    {
        return $this->apiVersion;
    }
}
