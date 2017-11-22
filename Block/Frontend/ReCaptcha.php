<?php
namespace Dc\GooglereCaptcha\Block\Frontend;

use Dc\GoogleReCaptcha\Helper\Config;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

class ReCaptcha extends Template
{
    /** @var Config */
    private $config;

    /**
     * @param Context $context
     * @param Config $config
     * @param array $data
     */
    public function __construct(
        Context $context,
        Config $config,
        array $data = []
    ) {
        $this->config = $config;
        parent::__construct($context, $data);
    }

    /**
     * @inheritDoc
     */
    protected function _toHtml() // @codingStandardsIgnoreLine
    {
        return $this->config->isEnabledFrontend() ? parent::_toHtml() : '';
    }

    /**
     * 返回google recaptcha的site key
     * @return string
     */
    public function getPublicKey()
    {
        return $this->config->getPublicKey();
    }
}
