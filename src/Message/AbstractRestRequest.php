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
            $this->isSubservice() ? $this->getSubService() : $this->getService()
        );
    }

    public function isSubservice()
    {
        return !empty($this->getParentService());
    }

    /**
     * The name of the service used in the endpoint to send the message.
     * With override for services used on specific parent services
     *
     * @return string Sage Pay endpoint service name.
     */
    public function getSubService()
    {
        if ($this->isSubservice()) {
            return sprintf(
                '%s/%s/%s',
                $this->getParentService(),
                $this->getParentServiceReference(),
                $this->getService()
            );
        }
        return $this->getService();
    }

    public function getParentService()
    {
        return false;
    }

    public function getParentServiceReference()
    {
        return false;
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

    public function getMerchantSessionKey()
    {
        return $this->getParameter('merchantSessionKey');
    }

    public function getCardIdentifier()
    {
        return $this->getParameter('cardIdentifier');
    }

    public function setMerchantSessionKey($value)
    {
        return $this->setParameter('merchantSessionKey', $value);
    }

    public function setCardIdentifier($value)
    {
        return $this->setParameter('cardIdentifier', $value);
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
                json_encode($data)
            );

        // We might want to check $httpResponse->getStatusCode()

        $responseData = static::parseBodyData($httpResponse);

        return $this->createResponse($responseData);
    }

    /**
     * The payload consists of json.
     *
     * @param ResponseInterface $httpResponse
     * @return array
     */
    public static function parseBodyData(ResponseInterface $httpResponse)
    {
        $bodyText = (string)$httpResponse->getBody();

        $responseData = json_decode($bodyText, true);

        return $responseData;
    }
}
