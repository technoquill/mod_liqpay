<?php
declare(strict_types=1);

/**
 * @package     Joomla.Site
 * @subpackage  mod_liqpay
 *
 * @copyright   M.Kulyk
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

\defined('_JEXEC') or die;


use Joomla\Module\Liqpay\Site\Helper\LiqpayHelper;

/**
 * @var LiqpayHelper $model
 */

?>


<div class="col-md-12">
    <div class="mod-liqpay-amounts">
        <?php foreach ($model->attributes->simple_payment_amounts as $amount) : ?>
            <span data-value="<?= $amount->value ?>"
                  class="mod-liqpay-amount-tag<?php if ((int)$model->attributes->simple_payment_default_amount === (int)$amount->value) : ?> active<?php endif ?>">
                                <em class="value"><?= $amount->value ?></em>
                                <em class="symbol"><?= $model->service->currencySymbol[$model->attributes->currency] ?></em></span>
        <?php endforeach; ?>
    </div>
</div>
