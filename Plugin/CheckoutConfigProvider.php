<?php

namespace Team23\LoginStep\Plugin;

/**
 * Class CheckoutConfigProvider
 *
 * @package Team23\LoginStep\Plugin
 */
class CheckoutConfigProvider
{
    /**
     * @var \Team23\LoginStep\Model\Config
     */
    protected $config;
    /**
     * @var \Magento\Customer\Block\Account\AuthenticationPopup
     */
    private $authenticationPopup;

    /**
     * CheckoutConfigProvider constructor.
     *
     * @param \Team23\LoginStep\Model\Config $config
     */
    public function __construct(
        \Team23\LoginStep\Model\Config $config,
        \Magento\Customer\Block\Account\AuthenticationPopup $authenticationPopup
    ) {
        $this->config = $config;
        $this->authenticationPopup = $authenticationPopup;
    }

    /**
     * @inheritdoc
     */
    public function afterGetConfig(\Magento\Checkout\Model\DefaultConfigProvider $subject, array $result)
    {
        $result['login_step']['settings'] = $this->authenticationPopup->getConfig();

        $customRegister = $this->config->isCustomRegisterMessageEnabled();
        $customLogin = $this->config->isCustomLoginMessageEnabled();
        $result['login_step']['config']['login_step_order'] =  $this->config->getLoginStepOrder();
        $result['login_step']['config']['custom_register'] = $customRegister;
        $result['login_step']['config']['register_msg'] = $customRegister ? $this->config->getRegisterMessage(): '';
        $result['login_step']['config']['custom_login'] = $customLogin;
        $result['login_step']['config']['login_msg'] = $customLogin ? $this->config->getLoginMessage(): '';

        return $result;
    }
}
