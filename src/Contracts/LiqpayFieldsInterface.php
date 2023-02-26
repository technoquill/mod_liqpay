<?php
declare(strict_types=1);
/**
 * @package         Joomla.Site
 * @subpackage      mod_liqpay
 *
 * @author          M.Kulyk
 *
 * @copyright   (C) 2006 Open Source Matters, Inc. <https://www.joomla.org>
 * @license         GNU General Public License version 2 or later; see LICENSE.txt
 * @since
 */

namespace Joomla\Module\Liqpay\Site\Contracts;


interface LiqpayFieldsInterface
{

    /**
     * @var string
     * @since 4.2.0
     */
    public const PAYMENT_TYPE_SIMPLE = 'simple';

    /**
     * @var string
     * @since 4.2.0
     */
    public const PAYMENT_TYPE_GROUP = 'group';

    /**
     * @var string
     * @since
     */
    public const FORM_NAME = 'liqpay-form';

    /**
     * @var string
     * @since
     */
    public const FIELD_AMOUNT = 'amount';

    /**
     * @var string
     * @since
     */
    public const FIELD_CURRENCY = 'currency';

    /**
     * @var string
     * @since
     */
    public const FIELD_DESCRIPTION = 'description';

    /**
     * @var string
     * @since
     */
    public const FIELD_MODULE_ID = 'module_id';

    /**
     * @var string
     * @since
     */
    public const FIELD_BTN_TEXT = 'btn_text';

    /**
     * @var string
     * @since
     */
    public const FIELD_ROUTE = 'route';
}