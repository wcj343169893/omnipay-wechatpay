<?php

namespace Omnipay\WechatPay\Message;


/**
 * 请求多次分账
 *
 * @package Omnipay\WechatPay\Message
 * @link    https://pay.weixin.qq.com/wiki/doc/api/allocation.php?chapter=27_6&index=2
 * @method  CreateMicroOrderResponse send()
 */
class ProfitSharingMultiRequest extends ProfitSharingRequest
{
    protected $endpoint = 'https://api.mch.weixin.qq.com/secapi/pay/multiprofitsharing';

}
