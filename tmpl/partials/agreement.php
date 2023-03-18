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


use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\Module\Liqpay\Site\Helper\LiqpayHelper;

/**
 * @var LiqpayHelper $model
 */

$label = Text::sprintf('MOD_LIQPAY_AGREEMENT_LABEL', Route::_("index.php?Itemid={$model->attributes->agreement_menu_item}"));
$model->form->setFieldAttribute($model::FIELD_AGREEMENT, 'label', $label);
$model->form->setFieldAttribute($model::FIELD_AGREEMENT, 'class', 'form-check');
$model->form->setFieldAttribute($model::FIELD_AGREEMENT, 'data-message', Text::_('MOD_LIQPAY_YOU_NEED_TO_AGREEMENT'));

?>

<div class="mod-liqpay-agreement-wrapper">
    <?= $model->form->renderField($model::FIELD_AGREEMENT, null, $model->attributes->agreement) ?>
</div>

