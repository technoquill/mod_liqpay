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


<?php if ($model->attributes->logotype !== "" && $model->attributes->name !== "") : ?>
    <div class="row mod-liqpay-logotype-company-name-wrapper">
        <div class="col-auto">
            <div class="mod-liqpay-logotype">
                <?= HTMLHelper::_('image', $model->attributes->logotype, $model->attributes->name, [
                    'class' => 'img-fluid'
                ], false) ?>
            </div>
        </div>
        <div class="col-auto align-self-center">
            <h5 class="mod-liqpay-company-name"><?= $model->attributes->name ?></h5>
        </div>
    </div>
<?php endif ?>
