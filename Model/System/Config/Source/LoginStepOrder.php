<?php

namespace Team23\LoginStep\Model\System\Config\Source;

/**
 * Class LoginStepOrder
 *
 * @package Team23\LoginStep\Model\System\Config\Source
 */
class LoginStepOrder extends \Magento\Framework\DataObject
{
    const LOGIN_FIRST = 1;
    const LOGIN_AFTER = 2;

    /**
     * @{inheritDoc}
     *
     * @return array
     */
    public static function toOptionArray()
    {
        return [
            self::LOGIN_FIRST => __('Login before register'),
            self::LOGIN_AFTER => __('Login after register')
        ];
    }
}
