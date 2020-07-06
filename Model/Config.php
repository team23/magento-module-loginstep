<?php

namespace Team23\LoginStep\Model;

/**
 * Class Config
 *
 * @package Team23\LoginStep\Model
 */
class Config
{
    const loginstep_settings = 'loginstep_general/';

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;
    /**
     * @var string
     */
    protected $storeScope;
    /**
     * @var \Magento\Framework\Escaper
     */
    private $escaper;

    /**
     * Config constructor.
     *
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Framework\Escaper $escaper
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\Escaper $escaper
    )
    {
        $this->scopeConfig = $scopeConfig;
        $this->storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        $this->escaper = $escaper;
    }

    /**
     * Get setting in the LoginStep context or empty string if not set
     *
     * @param string $setting
     * @return mixed|string
     */
    public function getSetting($setting = '')
    {
        return $this->scopeConfig->getValue(self::loginstep_settings . $setting, $this->storeScope) ?? '';
    }

    /**
     * Check the module state
     *
     * @return bool
     */
    public function isModuleEnabled()
    {
        return boolval($this->getSetting('loginstep_settings/module_state'));
    }

    /**
     * @return int
     */
    public function getLoginStepOrder()
    {
        return (int)$this->getSetting('loginstep_settings/login_step_order');
    }

    /**
     * @return bool
     */
    public function isCustomRegisterMessageEnabled()
    {
        return boolval($this->getSetting('loginstep_settings/enable_custom_register_message'));
    }

    /**
     * Get the register message, that should be output in the login/register checkout step.
     *
     * @return array|string
     */
    public function getRegisterMessage()
    {
        return $this->getSetting('loginstep_settings/register_message');
//        return $this->escaper->escapeHtml($this->getSetting('loginstep_settings/register_message'));
    }

    /**
     * @return bool
     */
    public function isCustomLoginMessageEnabled()
    {
        return boolval($this->getSetting('loginstep_settings/enable_custom_login_message'));
    }

    /**
     * Get the login message, that should be output in the login/register checkout step.
     *
     * @return array|string
     */
    public function getLoginMessage()
    {
        return $this->getSetting('loginstep_settings/login_message');
//        return $this->escaper->escapeHtml($this->getSetting('loginstep_settings/login_message'));
    }
}
