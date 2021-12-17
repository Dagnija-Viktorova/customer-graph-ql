<?php

namespace ScandiPWA\CustomerGraphQl\Controller\Subscriber;

class Confirm extends \Magento\Newsletter\Controller\Subscriber\Confirm
{
    /**
     * Subscription confirm action.
     *
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        $id = (int)$this->getRequest()->getParam('id');
        $code = (string)$this->getRequest()->getParam('code');

        if ($id && $code) {
            /** @var \Magento\Newsletter\Model\Subscriber $subscriber */
            $subscriber = $this->_subscriberFactory->create()->load($id);

            if ($subscriber->getId() && $subscriber->getCode()) {
                if ($subscriber->confirm($code)) {
                    $this->messageManager->addSuccessMessage(__('Your subscription has been confirmed.'));
                } else {
                    $this->messageManager->addErrorMessage(__('This is an invalid subscription confirmation code.'));
                }
            } else {
                $this->messageManager->addErrorMessage(__('This is an invalid subscription ID.'));
            }
        }
        /** @var \Magento\Framework\Controller\Result\Redirect $redirect */
        $redirect = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_REDIRECT);
        $redirectUrl = $this->_storeManager->getStore()->getBaseUrl().'newsletter/confirm/id/'.$id.'/code/'.$code;
        return $redirect->setUrl($redirectUrl);
    }
}
