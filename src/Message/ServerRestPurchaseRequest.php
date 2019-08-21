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
        $card = $this->getCard();

        $data = $this->getBaseData();

        $data['transactionType'] = $this->getTxType();
        $data['vendorTxCode'] = $this->getTransactionId();
        
        $data['description'] = $this->getDescription();

        $data['amount'] = (int) $this->getAmount();
        $data['currency'] = $this->getCurrency();
        $data['customerFirstName'] = $card->getFirstName();
        $data['customerLastName'] = $card->getLastName();
        
        $data['billingAddress']['address1'] = $card->getBillingAddress1();
        $data['billingAddress']['address2'] = $card->getBillingAddress2();
        $data['billingAddress']['city'] = $card->getBillingCity();
        $data['billingAddress']['postalCode'] = $card->getBillingPostcode();
        $data['billingAddress']['state'] = $card->getBillingState();
        $data['billingAddress']['country'] = $card->getBillingCountry();


        $data['shippingDetails']['recipientFirstName'] = $card->getShippingFirstName();
        $data['shippingDetails']['recipientLastName'] = $card->getShippingLastName();
        $data['shippingDetails']['shippingAddress1'] = $card->getShippingAddress1();
        $data['shippingDetails']['shippingAddress2'] = $card->getShippingAddress2();
        $data['shippingDetails']['shippingCity'] = $card->getShippingCity();
        $data['shippingDetails']['shippingPostalCode'] = $card->getShippingPostcode();
        $data['shippingDetails']['shippingState'] = $card->getShippingState();
        $data['shippingDetails']['shippingCountry'] = $card->getShippingCountry();

        $data['NotificationURL'] = $this->getNotifyUrl() ?: $this->getReturnUrl();
        $data['MD'] = $this->getMd();

        // $data = $this->getBillingAddressData($data);

        // Shipping details

        // $data = $this->getDeliveryAddressData($data);

        // $card = $this->getCard();

        if ($card->getEmail()) {
            $data['customerEmail'] = $card->getEmail();
        }

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
}
