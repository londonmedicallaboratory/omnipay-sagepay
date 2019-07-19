<?php

namespace Omnipay\SagePay\Message;

/**
 * Sage Pay REST Server Purchase Request
 */
class ServerRestPurchaseRequest extends AbstractRestRequest
{
    public function getService()
    {
        return static::SERVICE_REST_PURCHASE;
    }
    
    /**
     * @return string the transaction type
     */
    public function getTxType()
    {
        return static::TXTYPE_PAYMENT;
    }

    /**
     * Add the optional token details to the base data.
     *
     * @return array
     */
    public function getData()
    {
        $data = $this->getBasePurchaseData();

        if ($this->getCardIdentifier() && $this->getMerchantSessionKey()) {
            $data = $this->getPaymentMethodData($data);
        }

        return $data;
    }

    /**
     * The required fields concerning the purchase
     *
     * @return array
     */
    protected function getBasePurchaseData()
    {
        $data = $this->getBaseData();

        $data['transactionType'] = $this->getTxType();
        $data['vendorTxCode'] = $this->getTransactionId();
        
        $data['description'] = $this->getDescription();

        $data['amount'] = (int) $this->getAmount() * 100;
        $data['currency'] = $this->getCurrency();
        $data['customerFirstName'] = 'Test';
        $data['customerLastName'] = 'Test';
        $data['billingAddress']['address1'] = '88 abc';
        $data['billingAddress']['city'] = 'abc';
        $data['billingAddress']['postalCode'] = '412';
        $data['billingAddress']['country'] = 'GB';
        $data['NotificationURL'] = $this->getNotifyUrl() ?: $this->getReturnUrl();



        // $data = $this->getBillingAddressData($data);

        // Shipping details

        // $data = $this->getDeliveryAddressData($data);

        // $card = $this->getCard();

        // if ($card->getEmail()) {
        //     $data['CustomerEMail'] = $card->getEmail();
        // }

        // $data['ApplyAVSCV2'] = $this->getApplyAVSCV2() ?: static::APPLY_AVSCV2_DEFAULT;
        // $data['apply3DSecure'] = $this->getApply3DSecure() ?: static::APPLY_3DSECURE_APPLY;
        // user parent data here and the abstract can provide txtype vendor etc
        return $data;
    }

    /**
     * @param array $data
     * @return ServerRestPurchaseKeyResponse
     */
    protected function createResponse($data)
    {
        return $this->response = new ServerRestPurchaseResponse($this, $data);
    }

    /**
     * A card token is returned if one has been requested.
     *
     * @return string Currently an md5 format token.
     */
    public function getPaymentMethodData($data = [])
    {
        $data['paymentMethod']['card']['merchantSessionKey'] = $this->getMerchantSessionKey();
        $data['paymentMethod']['card']['cardIdentifier'] = $this->getCardIdentifier();
        return $data;
    }

    public function getMerchantSessionKey()
    {
        return $this->getDataItem('merchantSessionKey');
    }

    public function getCardIdentifier()
    {
        return $this->getDataItem('cardIdentifier');
    }
}
