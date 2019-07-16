<?php

namespace Omnipay\SagePay\Message;

/**
 * Sage Pay REST Server Merchant Session Key Request
 */
class ServerRestMerchantSessionKeyRequest extends DirectAuthorizeRequest
{
    public function getService()
    {
        return static::SERVICE_SERVER_MSK;
    }

    /**
     * Add the optional token details to the base data.
     *
     * @return array
     */
    public function getData()
    {
        $data['vendorName'] = $this->getVendor();

        return $data;
    }

    /**
     * @param array $data
     * @return ServerRestMerchantSessionKeyResponse
     */
    protected function createResponse($data)
    {
        return $this->response = new ServerRestMerchantSessionKeyResponse($this, $data);
    }
}
