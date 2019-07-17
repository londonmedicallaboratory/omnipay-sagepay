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
        $data['billingAddress']['address1'] = 'abc';
        $data['billingAddress']['city'] = 'abc';
        $data['billingAddress']['postalCode'] = 'EC1V 4AB';
        $data['billingAddress']['country'] = 'GB';
        $data['paymentMethod']['card']['merchantSessionKey'] = '5493A29C-125F-49E9-B21A-FA775F5BDCD3';
        $data['paymentMethod']['card']['cardIdentifier'] = '93F3618E-5B6B-4ABC-8D8E-49A2DB40E138';
 },

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
}
