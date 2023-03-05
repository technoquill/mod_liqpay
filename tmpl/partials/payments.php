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


use Joomla\CMS\HTML\HTMLHelper;
use Joomla\Module\Liqpay\Site\Helper\LiqpayHelper;

/**
 * @var LiqpayHelper $model
 */

?>


<?php if (count($model->attributes->available_payments)) : ?>
    <div class="col-md-12">
        <ul class="mod-liqpay-payment-methods">
            <?php foreach ($model->attributes->available_payments as $value) : ?>
                <?php $imageSrc = JUri::base() . "modules/mod_liqpay/assets/images/methods/icon-{$value}.svg" ?>
                <li data-bs-toggle="tooltip" data-bs-placement="top"
                    title="<?= $model->service->paymentTypes[$value] ?>">
                                        <span class="mod-liqpay-logo">
                                               <?= HTMLHelper::_('image', $imageSrc, $model->service->paymentTypes[$value], [
                                                   'class' => 'img-fluid'
                                               ], false) ?>
                                        </span>
                    <span class="mod-liqpay-name">
                                            <?= $model->service->paymentTypes[$value] ?>
                                        </span>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif ?>