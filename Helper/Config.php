<?php
namespace Dc\GoogleReCaptcha\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Config extends AbstractHelper
{
    const XML_PATH_ENABLED_FRONTEND = 'google_recaptcha/frontend/enabled';
    const XML_PATH_ENABLED_FRONTEND_CREATE = 'google_recaptcha/frontend/enabled_create';

    const XML_PATH_PUBLIC_KEY = 'google_recaptcha/publickey';
    const XML_PATH_PRIVATE_KEY = 'google_recaptcha/privatekey';

    /**
     * @return bool
     */
    public function isEnabledFrontend($store = null)
    {
        return $this->getPublicKey($store) && $this->getPrivateKey($store) && $this->scopeConfig->isSetFlag(self::XML_PATH_ENABLED_FRONTEND, ScopeInterface::SCOPE_STORE, $store);
    }

    /**
     * @return bool
     */
    public function isEnabledFrontendCreate($store = null)
    {
        return $this->isEnabledFrontend($store) && $this->scopeConfig->isSetFlag(self::XML_PATH_ENABLED_FRONTEND_CREATE, ScopeInterface::SCOPE_STORE, $store);
    }

    /**
     * @return string|null
     */
    public function getPublicKey($store = null)
    {
        return $this->scopeConfig->getValue(self::XML_PATH_PUBLIC_KEY, ScopeInterface::SCOPE_STORE, $store);
    }

    /**
     * @return string|null
     */
    public function getPrivateKey($store = null)
    {
        return $this->scopeConfig->getValue(self::XML_PATH_PRIVATE_KEY, ScopeInterface::SCOPE_STORE, $store);
    }
}
