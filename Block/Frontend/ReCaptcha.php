<?php
namespace Dc\GooglereCaptcha\Block\Frontend;

use Dc\GoogleReCaptcha\Helper\Config;

class ReCaptcha extends \Magento\Framework\View\Element\Template
{
    /** @var Config */
    private $config;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param Config $config
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        Config $config,
        array $data = []
    ) {
        $this->config = $config;
        parent::__construct($context, $data);
    }

    /**
     * @return string
     */
    protected function _toHtml() // @codingStandardsIgnoreLine
    {
        return $this->config->isEnabledFrontend() ? parent::_toHtml() : '';
    }

    /**
     * @return string
     */
    public function getPublicKey()
    {
        return $this->config->getPublicKey();
    }
}
