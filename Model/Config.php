<?php

namespace Team23\LoginStep\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Class Config
 *
 * @package Team23\LoginStep\Model
 */
class Config
{
    const LOGINSTEP_SETTINGS = 'loginstep_general/';

    /**
     * @var ScopeConfigInterface
     */
    protected ScopeConfigInterface $scopeConfig;

    /**
     * @var string
     */
    protected string $storeScope;

    /**
     * Config constructor.
     *
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->storeScope = ScopeInterface::SCOPE_STORE;
    }

    /**
     * Get setting in the LoginStep context or empty string if not set
     *
     * @param string $setting
     * @return mixed|string
     */
    public function getSetting(string $setting = ''): mixed
    {
        return $this->scopeConfig->getValue(self::LOGINSTEP_SETTINGS . $setting, $this->storeScope) ?? '';
    }

    /**
     * Check the module state
     *
     * @return bool
     */
    public function isModuleEnabled(): bool
    {
        return boolval($this->getSetting('loginstep_settings/module_state'));
    }

    /**
     * @return int
     */
    public function getLoginStepOrder(): int
    {
        return (int)$this->getSetting('loginstep_settings/login_step_order');
    }

    /**
     * @return bool
     */
    public function isCustomRegisterMessageEnabled(): bool
    {
        return boolval($this->getSetting('loginstep_settings/enable_custom_register_message'));
    }

    /**
     * Get the register message, that should be output in the login/register checkout step.
     *
     * @return array|string
     */
    public function getRegisterMessage(): array|string
    {
        return $this->getSetting('loginstep_settings/register_message');
    }

    /**
     * @return bool
     */
    public function isCustomLoginMessageEnabled(): bool
    {
        return boolval($this->getSetting('loginstep_settings/enable_custom_login_message'));
    }

    /**
     * Get the login message, that should be output in the login/register checkout step.
     *
     * @return array|string
     */
    public function getLoginMessage(): array|string
    {
        return $this->getSetting('loginstep_settings/login_message');
    }
}
