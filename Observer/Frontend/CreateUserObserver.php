<?php
namespace Dc\GoogleReCaptcha\Observer\Frontend;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Customer\Model\Session;
use Magento\Framework\HTTP\PhpEnvironment\RemoteAddress;
use Magento\Framework\UrlInterface;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\App\ActionFlag;
use Magento\Framework\App\Action\Action;
use Dc\GoogleReCaptcha\Api\ValidateInterface;
use Dc\GoogleReCaptcha\Helper\Config;

class CreateUserObserver implements ObserverInterface
{
    /** @var ValidateInterface */
    private $validate;

    /** @var ManagerInterface */
    private $messageManager;

    /** @var Session */
    private $customerSession;

    /** @var ActionFlag */
    private $actionFlag;

    /** @var UrlInterface */
    private $url;

    /** @var RemoteAddress */
    private $remoteAddress;

    /** @var Config */
    private $config;

    /**
     * @param ValidateInterface $validate
     * @param Config $config
     * @param ManagerInterface $messageManager
     * @param Session $customerSession
     * @param ActionFlag $actionFlag
     * @param UrlInterface $url
     * @param RemoteAddress $remoteAddress
     */
    public function __construct(
        ValidateInterface $validate,
        Config $config,
        ManagerInterface $messageManager,
        Session $customerSession,
        ActionFlag $actionFlag,
        UrlInterface $url,
        RemoteAddress $remoteAddress
    ) {
        $this->validate = $validate;
        $this->config = $config;
        $this->messageManager = $messageManager;
        $this->customerSession = $customerSession;
        $this->actionFlag = $actionFlag;
        $this->url = $url;
        $this->remoteAddress = $remoteAddress;
    }

    public function execute(Observer $observer)
    {
        if (!$this->config->isEnabledFrontendCreate()) {
            return;
        }

        /** @var \Magento\Framework\App\Action\Action $controller */
        $controller = $observer->getControllerAction();
        $request = $controller->getRequest();
        $reCaptchaResponse = $request->getParam(ValidateInterface::PARAM_RECAPTCHA_RESPONSE);
        $remoteIp = $this->remoteAddress->getRemoteAddress();
        // 验证recaptcha
        if (!$this->validate->validate($reCaptchaResponse, $remoteIp)) {
            $this->messageManager->addErrorMessage(__('recaptcha error'));
            // 取消操作
            $this->actionFlag->set('', Action::FLAG_NO_DISPATCH, true);
            // 把数据设置到session
            $this->customerSession->setCustomerFormData($request->getPostValue());
            $url = $this->url->getUrl('*/*/create', ['_secure' => true]);
            $controller->getResponse()->setRedirect($url);
        }
    }
}
