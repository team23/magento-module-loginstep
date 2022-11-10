<?php

namespace Team23\LoginStep\Plugin;

use Magento\Checkout\Model\DefaultConfigProvider;
use Magento\Customer\Block\Account\AuthenticationPopup;
use Team23\LoginStep\Model\Config;

/**
 * Class CheckoutConfigProvider
 *
 * @package Team23\LoginStep\Plugin
 */
class CheckoutConfigProvider
{
    /**
     * @var Config
     */
    protected Config $config;
    /**
     * @var AuthenticationPopup
     */
    private AuthenticationPopup $authenticationPopup;

    /**
     * CheckoutConfigProvider constructor.
     *
     * @param Config $config
     * @param AuthenticationPopup $authenticationPopup
     */
    public function __construct(
        Config $config,
        AuthenticationPopup $authenticationPopup
    ) {
        $this->config = $config;
        $this->authenticationPopup = $authenticationPopup;
    }

    /**
     * @param DefaultConfigProvider $subject
     * @param array $result
     * @return array
     */
    public function afterGetConfig(DefaultConfigProvider $subject, array $result): array
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
