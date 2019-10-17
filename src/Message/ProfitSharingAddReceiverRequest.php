<?php

namespace Omnipay\WechatPay\Message;

use Omnipay\Common\Message\ResponseInterface;
use Omnipay\WechatPay\Helper;

/**
 * 添加分账接收方
 *
 * @package Omnipay\WechatPay\Message
 * @link    https://pay.weixin.qq.com/wiki/doc/api/allocation.php?chapter=27_3&index=4
 * @method  BaseAbstractRequest send()
 */
class ProfitSharingAddReceiverRequest extends BaseAbstractRequest
{
    protected $endpoint = 'https://api.mch.weixin.qq.com/pay/profitsharingaddreceiver';


    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     */
    public function getData()
    {
        $this->validate('app_id', 'mch_id');

        $data = array(
            'appid'            => $this->getAppId(),
            'mch_id'           => $this->getMchId(),
            'sign_type'        => "HMAC-SHA256",
            'receiver'         => $this->getReceiver(),
            'nonce_str'        => md5(uniqid()),
        );

        $data = array_filter($data);

        $data['sign'] = Helper::hmacSha256($data, $this->getApiKey());

        return $data;
    }
    
    public function getReceiver()
    {
        return json_encode($this->getParameter('receiver'),JSON_UNESCAPED_UNICODE);
    }
    
    public function setReceiver($receivers)
    {
        $this->setParameter('receiver', $receivers);
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
        $request      = $this->httpClient->post($this->endpoint)->setBody(Helper::array2xml($data));
        $response     = $request->send()->getBody();
        $responseData = Helper::xml2array($response);

        return $this->response = new ProfitSharingResponse($this, $responseData);
    }
}
