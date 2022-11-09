<?php

namespace Team23\LoginStep\Helper;

use Magento\Customer\Model\Session;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Team23\LoginStep\Model\Config;

/**
 * Class Data
 *
 * @package Team23\LoginStep\Helper
 */
class Data extends AbstractHelper
{
    /**
     * @var Config
     */
    protected Config $config;
    /**
     * @var Session
     */
    private Session $customerSession;

    /**
     * Data constructor.
     *
     * @param Context $context
     * @param Config $config
     * @param Session $customerSession
     */
    public function __construct(
        Context $context,
        Config  $config,
        Session $customerSession
    ) {
        $this->config = $config;
        $this->customerSession = $customerSession;
    }

    /**
     * Check if the current user is logged in
     *
     * @return bool
     */
    public function isLoggedIn(): bool
    {
        return $this->customerSession->isLoggedIn();
    }
}
