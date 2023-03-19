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
use Joomla\Module\Liqpay\Site\Helper\LiqpayHelper;
use Joomla\Module\Liqpay\Site\Helper\TemplateHelper;

/**
 * @var LiqpayHelper $model
 */



?>

<?php if ($model->attributes->payment_type === $model::PAYMENT_TYPE_SIMPLE) : ?>

    <div class="mod-liqpay-wrapper simple-payment">

        <div class="mod-liqpay-view"
             data-currency="<?= $model->attributes->currency ?>"
             data-module-id="<?= $model->attributes->module_id ?>"
             data-btn-text="<?= Text::_('MOD_LIQPAY_BTN_TEXT') ?>"
             data-route="<?= urlencode($model->service->currentRoute) ?>">

            <div class="row">

                <div class="<?php if ($model->attributes->simple_payment_additional_info !== "") : ?> col-lg-7<?php endif ?> col-md-12">

                    <?= TemplateHelper::renderPartial('logotype', [
                        'model' => $model
                    ], (bool)$model->attributes->settings['show_logo_and_name']) ?>


                    <?= TemplateHelper::renderPartial('amounts', [
                        'model' => $model
                    ], (bool)count($model->attributes->simple_payment_amounts)) ?>


                    <div class="col-md-12">
                        <form method="post" name="<?= $model->form->getName() ?>" class="form-validate row"
                              id="<?= $model->form->getName() ?>">
                            <?php // Set Field Attributes
                            $model->form->setFieldAttribute($model::FIELD_DESCRIPTION, 'readonly', !(bool)$model->attributes->simple_payment_readonly_purpose_of_payment);
                            $model->form->setFieldAttribute($model::FIELD_CURRENCY, 'readonly', !(bool)$model->attributes->disable_currency_select);
                            ?>

                            <div class="col-md-7">
                                <?= $model->form->renderField($model::FIELD_AMOUNT, null, $model->attributes->simple_payment_default_amount) ?>
                            </div>

                            <div class="col-md-5">
                                <?= $model->form->renderField($model::FIELD_CURRENCY, null, $model->attributes->currency) ?>
                            </div>

                            <div class="col-md-12">

                                <div class="fields<?php if ($model->attributes->simple_payment_purpose_of_payment === "") : ?> d-none<?php endif ?>">
                                    <?= $model->form->renderField($model::FIELD_DESCRIPTION, null, $model->attributes->simple_payment_purpose_of_payment) ?>
                                </div>

                                <?= $model->form->renderField($model::FIELD_MODULE_ID, null, $model->attributes->module_id) ?>

                                <?= $model->form->renderField($model::FIELD_BTN_TEXT, null, Text::_('MOD_LIQPAY_BTN_TEXT')) ?>

                                <?= $model->form->renderField($model::FIELD_ROUTE, null, urlencode($model->service->currentRoute)) ?>

                            </div>


                            <?= TemplateHelper::renderPartial('payments', [
                                'model' => $model
                            ], $model->attributes->settings['show_payments_method'] && count($model->attributes->available_payments)) ?>


                            <?= TemplateHelper::renderPartial('agreement', [
                                'model' => $model
                            ]) ?>


                        </form>

                        <div class="liqpay-form-view" id="<?= $model->form->getName() ?>-result">
                            <?= $model->service->inactiveForm() ?>
                        </div>

                    </div>

                </div>


                <?php if ($model->attributes->settings['show_additional_info'] && $model->attributes->simple_payment_additional_info !== "") : ?>
                    <div class="col-lg-5 col-md-12">
                        <div class="mod-liqpay-additional-info">
                            <?= $model->attributes->simple_payment_additional_info ?>
                        </div>
                    </div>
                <?php endif ?>

            </div>

        </div>
    </div>

<?php else : ?>

    <div class="mod-liqpay-wrapper-group group-payment<?php if ($model->attributes->group_payment_as_separate) : ?>-separate<?php endif ?>">

        <div class="mod-liqpay-view"
             data-currency="<?= $model->attributes->currency ?>"
             data-module-id="<?= $model->attributes->module_id ?>"
             data-btn-text="<?= Text::_('MOD_LIQPAY_BTN_TEXT') ?>"
             data-route="<?= urlencode($model->service->currentRoute) ?>">

            <?= TemplateHelper::renderPartial('logotype', [
                'model' => $model
            ], (bool)$model->attributes->settings['show_logo_and_name']) ?>


            <div class="mod-liqpay-table-view">


                <div class="row align-items-center justify-content-end">
                    <div class="col-sm-12 col-md-12 col-lg-8">
                        <?= TemplateHelper::renderPartial('payments', [
                            'model' => $model
                        ], $model->attributes->settings['show_payments_method'] && count($model->attributes->available_payments)) ?>
                    </div>

                    <?php if (!$model->attributes->group_payment_as_separate) : ?>
                    <div class="col-sm-12 col-md-6 col-lg-2">
                        <div class="services-sum d-none">
                            <span>0</span> <?= $model->service->currencySymbol[$model->attributes->currency] ?>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-4 col-lg-2">
                        <?php if (!$model->attributes->group_payment_as_separate) : ?>
                            <div class="services-payment liqpay-form-view" id="liqpay-form-result">
                                <?= $model->service->inactiveForm() ?>
                            </div>
                        <?php endif ?>
                    </div>
                    <?php endif ?>

                    <div class="col-sm-12 col-md-8 col-lg-4">
                        <div class="agreement-align-container">
                            <?= TemplateHelper::renderPartial('agreement', [
                                'model' => $model
                            ]) ?>
                        </div>
                    </div>

                </div>

                <?php $i = 0 ?>
                <?php foreach ($model->attributes->group_payment as $key => $value) : ?>
                    <?php $i++ ?>
                    <div class="row<?php if (($i % 2) === 0) : ?> even<?php else : ?> odd<?php endif ?> align-items-center">
                        <div class="col-md-8">
                            <div class="mod-liqpay-service-heading"><?= $value->name ?></div>
                            <?php if ($value->description !== "") : ?>
                                <div class="mod-liqpay-service-description"><?= $value->description ?></div>
                            <?php endif ?>
                        </div>
                        <div class="col-md-2">
                            <?php if ((int)$value->special_offer === 1) : ?>
                            <?php endif ?>
                            <?php if ($value->old_price) : ?>
                                <span data-value="<?= $value->old_price ?>" class="mod-liqpay-price-old">
                        <?= $value->old_price ?> <?= $model->service->currencySymbol[$model->attributes->currency] ?>
                    </span>
                            <?php endif ?>
                            <span data-value="<?= $value->price ?>"
                                  class="mod-liqpay-price <?php if ($value->old_price) : ?> is-special<?php endif ?>">
                        <?= $value->price ?> <?= $model->service->currencySymbol[$model->attributes->currency] ?>
                    </span>
                        </div>
                        <div class="col-md-2">
                            <?php if ($model->attributes->group_payment_as_separate) : ?>
                                <div class="liqpay-form-view">
                                    <?= $model->service->createLiqPayForm($model->attributes->public_key, $model->attributes->private_key, [
                                        'order_id' => md5(time() . $key),
                                        'amount' => $value->price,
                                        'currency' => $model->attributes->currency,
                                        'description' => Text::sprintf('MOD_LIQPAY_GROUP_PAYMENT_PAYMENT_MESSAGE', $value->name),
                                        'btn_text' => '',
                                        'module_id' => (string)$model->attributes->module_id
                                    ]) ?>
                                </div>
                            <?php else : ?>
                                <div class="check-form-wrapper">
                                    <div class="form-check form-switch">
                                        <input
                                                data-name="<?= $value->name ?>"
                                                data-reference-code="<?= $value->reference_code ?>"
                                                data-value="<?= $value->price ?>"
                                                class="form-check-input service-check"
                                                type="checkbox"
                                                id="<?= $key ?>"
                                                value="<?= $value->price ?>">
                                        <label class="form-check-label" for="<?= $key ?>">
                                            <?= Text::_('MOD_LIQPAY_GROUP_PAYMENT_CHECK_SERVICE_LABEL') ?>
                                        </label>
                                    </div>
                                </div>


                            <?php endif ?>
                        </div>
                    </div>
                <?php endforeach ?>

            </div>

            <?php if ($model->attributes->settings['show_additional_info'] && $model->attributes->group_additional_info !== "") : ?>
                <div class="mod-liqpay-payment-group-additional-info">
                    <?= $model->attributes->group_additional_info ?>
                </div>
            <?php endif ?>

        </div>
    </div>


<?php endif ?>





