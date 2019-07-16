<?php

namespace Omnipay\SagePay;

// CHECKME: do we really need these?
use Omnipay\SagePay\Message\ServerAuthorizeRequest;
use Omnipay\SagePay\Message\ServerCompleteAuthorizeRequest;
use Omnipay\SagePay\Message\ServerPurchaseRequest;
use Omnipay\SagePay\Message\ServerNotifyRequest;
use Omnipay\SagePay\Message\SharedTokenRemovalRequest;
use Omnipay\SagePay\Message\ServerTokenRegistrationRequest;
use Omnipay\SagePay\Message\ServerTokenRegistrationCompleteRequest;

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
