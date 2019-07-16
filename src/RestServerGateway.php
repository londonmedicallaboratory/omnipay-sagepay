<?php

namespace Omnipay\SagePay;

use Omnipay\SagePay\Message\ServerRestMerchantSessionKeyRequest;

/**
 * Sage Pay Rest Server Gateway
 */
class RestServerGateway extends ServerGateway
{
    public function getName()
    {
        return 'Sage Pay REST Server';
    }

    /**
     * Create merchant session key (MSK).
     */
    public function createMerchantSessionKey(array $parameters = array())
    {
        return $this->createRequest(ServerRestMerchantSessionKeyRequest::class, $parameters);
    }
}
