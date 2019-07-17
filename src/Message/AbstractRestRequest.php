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
    protected $testEndpoint = 'https://test.inlec.com';


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

    
    public function getUsername()
    {
        return $this->getParameter('username');
    }

    public function setUsername($value)
    {
        return $this->setParameter('username', $value);
    }

    public function getPassword()
    {
        return $this->getParameter('password');
    }

    public function setPassword($value)
    {
        return $this->setParameter('password', $value);
    }

    /**
     * Send data to the remote gateway, parse the result into an array,
     * then use that to instantiate the response object.
     *
     * @param  array
     * @return Response The reponse object initialised with the data returned from the gateway.
     */
    public function sendData($data)
    {
        // Issue #20 no data values should be null.

        array_walk($data, function (&$value) {
            if (! isset($value)) {
                $value = '';
            }
        });

        $httpResponse = $this
            ->httpClient
            ->request(
                'POST',
                $this->getEndpoint(),
                [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Basic '.base64_encode($this->getUsername() . ':' . $this->getPassword()),
                ],
                http_build_query($data)
            );

        // We might want to check $httpResponse->getStatusCode()

        // $responseData = json_decode($httpResponse, true);
        $responseData = static::parseBodyData($httpResponse);

        return $this->createResponse($responseData);
    }
}
