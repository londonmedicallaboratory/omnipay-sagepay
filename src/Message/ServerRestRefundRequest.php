<?php

namespace Omnipay\SagePay\Message;

use Omnipay\SagePay\Message\ServerRestRefundRequest;

/**
 * Sage Pay REST Server Refund Request
 */
class ServerRestRefundRequest extends AbstractRestRequest
{
    public function getService()
    {
        return static::SERVICE_REST_TRANSACTIONS;
    }
    
    /**
     * @return string the transaction type
     */
    public function getTxType()
    {
        return static::TXTYPE_REFUND;
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
        $data['amount'] = (int) $this->getAmount();
        $data['currency'] = $this->getCurrency();
        $data['NotificationURL'] = $this->getNotifyUrl() ?: $this->getReturnUrl();
        $data['MD'] = $this->getMd();

        $data = $this->getBillingAddressData($data);
        $data = $this->getShippingDetailsData($data);

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
        return $this->response = new ServerRestRefundRequest($this, $data);
    }

    /**
     * @param array $data
     * @return array $data.
     */
    public function getPaymentMethodData($data = [])
    {
        $data['paymentMethod']['card']['merchantSessionKey'] = $this->getMerchantSessionKey();
        $data['paymentMethod']['card']['cardIdentifier'] = $this->getCardIdentifier();
        return $data;
    }
}
