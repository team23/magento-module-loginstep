<?php

namespace Team23\LoginStep\Model\System\Config\Source;

use Magento\Framework\DataObject;

/**
 * Class LoginStepOrder
 *
 * @package Team23\LoginStep\Model\System\Config\Source
 */
class LoginStepOrder extends DataObject
{
    const LOGIN_FIRST = 1;
    const LOGIN_AFTER = 2;

    /**
     * @{inheritDoc}
     *
     * @return array
     */
    public static function toOptionArray(): array
    {
        return [
            self::LOGIN_FIRST => __('Login before register'),
            self::LOGIN_AFTER => __('Login after register')
        ];
    }
}
