<?php

namespace Razorpay\Api;

class Utility
{
    const SHA256 = 'sha256';

    public function verifyPaymentSignature($attributes)
    {
        $expectedSignature = $attributes['razorpay_signature'];
        $paymentId = $attributes['razorpay_payment_id'];

        if (isset($attributes['razorpay_order_id']) === true)
        {
            $orderId = $attributes['razorpay_order_id'];

            $payload = $orderId . '|' . $paymentId;
        }
        else if (isset($attributes['razorpay_subscription_id']) === true)
        {
            $subscriptionId = $attributes['razorpay_subscription_id'];

            $payload = $paymentId . '|' . $subscriptionId ;
        }
        else
        {
            throw new Error('Invalid parameters passed to verifyPaymentSignature:'
                . 'At least razorpay_order_id or razorpay_subscription_id should be set.');
        }

        return self::verifySignature($payload, $expectedSignature);
    }

    public function verifyWebhookSignature($payload, $expectedSignature, $webhookSecret)
    {
        return self::verifySignature($payload, $expectedSignature, $webhookSecret);
    }

    public function verifySignature($payload, $expectedSignature, $webhookSecret = '')
    {
        if (empty($webhookSecret) === false)
        {
            $actualSignature = hash_hmac(self::SHA256, $payload, $webhookSecret);
        }
        else
        {
            $actualSignature = hash_hmac(self::SHA256, $payload, Api::getSecret());
        }

        // Use lang's built-in hash_equals if exists to mitigate timing attacks
        if (function_exists('hash_equals'))
        {
            $verified = hash_equals($actualSignature, $expectedSignature);
        }
        else
        {
            $verified = $this->hashEquals($actualSignature, $expectedSignature);
        }

        if ($verified === false)
        {
            throw new Errors\SignatureVerificationError(
                'Invalid signature passed');
        }
    }

    private function hashEquals($actualSignature, $expectedSignature)
    {
        if (strlen($expectedSignature) === strlen($actualSignature))
        {
            $res = $expectedSignature ^ $actualSignature;
            $return = 0;

            for ($i = strlen($res) - 1; $i >= 0; $i--)
            {
                $return |= ord($res[$i]);
            }

            return ($return === 0);
        }

        return false;
    }
}
