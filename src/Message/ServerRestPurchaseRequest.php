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

        $data['amount'] = $this->getAmount();
        $data['currency'] = $this->getCurrency();

        $data = $this->getBillingAddressData($data);

        // Shipping details

        $data = $this->getDeliveryAddressData($data);

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
     * @return ServerRestMerchantSessionKeyResponse
     */
    protected function createResponse($data)
    {
        return $this->response = new ServerRestMerchantSessionKeyResponse($this, $data);
    }
}
