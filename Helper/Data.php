<?php

namespace Team23\LoginStep\Helper;

/**
 * Class Data
 *
 * @package Team23\LoginStep\Helper
 */
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var \Team23\LoginStep\Model\Config
     */
    protected $config;
    /**
     * @var \Magento\Customer\Model\Session
     */
    private $customerSession;

    /**
     * Data constructor.
     *
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Team23\LoginStep\Model\Config $config
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Team23\LoginStep\Model\Config $config,
        \Magento\Customer\Model\Session $customerSession
    ) {
        $this->config = $config;
        $this->customerSession = $customerSession;
    }

    /**
     * Check if the current user is logged in
     *
     * @return bool
     */
    public function isLoggedIn()
    {
        return $this->customerSession->isLoggedIn();
    }
}
