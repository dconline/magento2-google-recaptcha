<?php
namespace Dc\GoogleReCaptcha\Model;

use Dc\GoogleReCaptcha\Api\ValidateInterface;
use Dc\GoogleReCaptcha\Helper\Config;
use ReCaptcha\ReCaptcha;

class Validate implements ValidateInterface
{
    /**
     * @var Config
     */
    private $config;
    public function __construct(
        Config $config
    ) {
        $this->config = $config;
    }

    /**
     * Return true if reCaptcha validation has passed
     * @param string $reCaptchaResponse
     * @param string $remoteIp
     * @return bool
     */
    public function validate($reCaptchaResponse, $remoteIp)
    {
        $secret = $this->config->getPrivateKey();
        if ($reCaptchaResponse) {
            // @codingStandardsIgnoreStart
            $reCaptcha = new ReCaptcha($secret);
            // @codingStandardsIgnoreEmd
            $res = $reCaptcha->verify($reCaptchaResponse, $remoteIp);
            if ($res->isSuccess()) {
                return true;
            }
        }
        return false;
    }
}
