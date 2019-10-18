<?php

namespace Omnipay\WechatPay\Message;

use Omnipay\Common\Message\ResponseInterface;
use Omnipay\WechatPay\Helper;

/**
 * 请求单次分账
 *
 * @package Omnipay\WechatPay\Message
 * @link    https://pay.weixin.qq.com/wiki/doc/api/allocation.php?chapter=27_1&index=1
 * @method  CreateMicroOrderResponse send()
 */
class ProfitSharingRequest extends CreateOrderRequest
{
    protected $endpoint = 'https://api.mch.weixin.qq.com/secapi/pay/profitsharing';


    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     */
    public function getData()
    {
        $this->validate('app_id', 'mch_id', 'out_order_no', 'transactionId', 'cert_path', 'key_path');

        $data = array(
            'appid'            => $this->getAppId(),
            'mch_id'           => $this->getMchId(),
            'sign_type'        => "HMAC-SHA256",
            'transaction_id'   => $this->getTransactionId(),
            'out_order_no'     => $this->getOutOrderNo(),
            'receivers'        => $this->getReceivers(),
            'nonce_str'        => md5(uniqid()),
        );

        $data = array_filter($data);

        $data['sign'] = Helper::hmacSha256($data, $this->getApiKey());

        return $data;
    }
    /**
     * @return mixed
     */
    public function getOutOrderNo()
    {
        return $this->getParameter('out_order_no');
    }
    
    
    public function setOutOrderNo($out_order_no)
    {
        $this->setParameter('out_order_no', $out_order_no);
    }
    
    public function getReceivers()
    {
        return json_encode($this->getParameter('receivers'),JSON_UNESCAPED_UNICODE);
    }
    
    public function setReceivers($receivers)
    {
        $this->setParameter('receivers', $receivers);
    }
    /**
     * @return mixed
     */
    public function getCertPath()
    {
        return $this->getParameter('cert_path');
    }
    
    
    /**
     * @param mixed $certPath
     */
    public function setCertPath($certPath)
    {
        $this->setParameter('cert_path', $certPath);
    }
    
    
    /**
     * @return mixed
     */
    public function getKeyPath()
    {
        return $this->getParameter('key_path');
    }
    
    
    /**
     * @param mixed $keyPath
     */
    public function setKeyPath($keyPath)
    {
        $this->setParameter('key_path', $keyPath);
    }

    /**
     * Send the request with specified data
     *
     * @param  mixed $data The data to send
     *
     * @return ResponseInterface
     */
    public function sendData($data)
    {
        $options = array(
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_SSLCERTTYPE    => 'PEM',
            CURLOPT_SSLKEYTYPE     => 'PEM',
            CURLOPT_SSLCERT        => $this->getCertPath(),
            CURLOPT_SSLKEY         => $this->getKeyPath(),
        );
        
        $body         = Helper::array2xml($data);
        $request      = $this->httpClient->post($this->endpoint, null, $data)->setBody($body);
        $request->getCurlOptions()->overwriteWith($options);
        $response     = $request->send()->getBody();
        $responseData = Helper::xml2array($response);

        return $this->response = new ProfitSharingResponse($this, $responseData);
    }
}
