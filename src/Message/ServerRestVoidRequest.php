<?php

namespace Omnipay\SagePay\Message;

use Omnipay\SagePay\Message\ServerRestRefundResponse;

/**
 * Sage Pay REST Server Refund Request
 */
class ServerRestVoidRequest extends AbstractRestRequest
{
    public function getService()
    {
        return static::SERVICE_REST_INSTRUCTIONS;
    }

    public function getParentService()
    {
        return static::SERVICE_REST_TRANSACTIONS;
    }
    
    /**
     * @return string the transaction type
     */
    public function getTxType()
    {
        return static::TXTYPE_VOID;
    }

    /**
     * Instruction data.
     *
     * @return array
     */
    public function getData()
    {
        $data = $this->getBaseData();

        $data['instructionType'] = $this->getTxType();
        return $data;
    }

    public function getParentServiceReference()
    {
        return $this->getParameter('transactionId');
    }
}
