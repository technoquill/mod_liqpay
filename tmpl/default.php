<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_liqpay
 *
 * @copyright   M.Kulyk
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

\defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\Module\Liqpay\Site\Helper\LiqpayHelper;

/** @var LiqpayHelper $model */
/** @var \stdClass $module */

?>

<div class="liqpay-wrapper">

    <div class="liqpay-view">


        <div class="row">

            <?php if ($model->attributes->payment_type === $model::PAYMENT_TYPE_SIMPLE) : ?>

                <div class="col-lg-7 col-md-12">

                    <?php if ($model->attributes->logotype !== "" && $model->attributes->name !== "") : ?>

                        <div class="row">
                            <div class="col-auto">
                                <div class="logotype-wrapper">
                                    <?= HTMLHelper::_('image', $model->attributes->logotype, $model->attributes->name, [
                                        'class' => 'img-fluid'
                                    ], false) ?>
                                </div>
                            </div>
                            <div class="col-auto align-self-center">
                                <h5><?= $model->attributes->name ?></h5>
                            </div>
                        </div>
                    <?php endif ?>

                    <?php if (count($model->attributes->simple_payment_amounts)) : ?>
                        <div class="col-md-12">
                            <div class="amounts">
                                <?php foreach ($model->attributes->simple_payment_amounts as $amount) : ?>
                                    <span data-value="<?= $amount['value'] ?>"
                                          class="amount-tag<?php if ((int)$model->attributes->default_amount === (int)$amount['value']) : ?> active<?php endif ?>">
                                <em class="value"><?= $amount['value'] ?></em>
                                <em class="symbol"><?= $model->service->currencySymbol[$model->attributes->currency] ?></em></span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif ?>

                    <div class="col-md-12">
                        <form method="post" name="<?= $model->form->getName() ?>" class="form-validate row"
                              id="<?= $model->form->getName() ?>">
                            <?php // Set Field Attributes
                            $model->form->setFieldAttribute($model::FIELD_DESCRIPTION, 'readonly', !(bool)$model->attributes->readonly_purpose_of_payment)
                            ?>

                            <div class="col-md-7">
                                <?= $model->form->renderField($model::FIELD_AMOUNT, null, $model->attributes->simple_payment_default_amount) ?>
                            </div>

                            <div class="col-md-5">
                                <?= $model->form->renderField($model::FIELD_CURRENCY, null, $model->attributes->currency) ?>
                            </div>


                            <div class="col-md-12">

                                <div class="fields<?php if ($model->attributes->purpose_of_payment === "") : ?> d-none<?php endif ?>">
                                    <?= $model->form->renderField($model::FIELD_DESCRIPTION, null, $model->attributes->simple_payment_purpose_of_payment) ?>
                                </div>


                                <?= $model->form->renderField($model::FIELD_MODULE_ID, null, $module->id) ?>

                                <?= $model->form->renderField($model::FIELD_BTN_TEXT, null, Text::_('MOD_LIQPAY_BTN_TEXT')) ?>

                                <?= $model->form->renderField($model::FIELD_ROUTE, null, urlencode($model->service->currentRoute)) ?>

                                <?= HTMLHelper::_('form.csrf') ?>
                            </div>

                            <?php if (count($model->attributes->available_payments)) : ?>
                                <div class="col-md-12">
                                    <ul class="payment-methods">
                                        <?php foreach ($model->attributes->available_payments as $value) : ?>
                                            <?php $imageSrc = JUri::base() . "modules/mod_liqpay/assets/images/methods/icon-{$value}.svg" ?>
                                            <li data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="<?= $model->service->paymentTypes[$value] ?>">
                                        <span class="logo">
                                               <?= HTMLHelper::_('image', $imageSrc, $model->service->paymentTypes[$value], [
                                                   'class' => 'img-fluid'
                                               ], false) ?>
                                        </span>
                                                <span class="name">
                                            <?= $model->service->paymentTypes[$value] ?>
                                        </span>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif ?>


                        </form>

                        <div id="<?= $model->form->getName() ?>-result">
                            <?= $model->service->inactiveForm() ?>
                        </div>

                    </div>

                </div>


                <?php if ($model->attributes->additional_info !== "") : ?>
                    <div class="col-lg-5 col-md-12">
                        <div class="additional-info">
                            <?= $model->attributes->additional_info ?>
                        </div>
                    </div>
                <?php endif ?>

            <?php else : ?>

            <!-- Here is a group payment -->

            <?= dd($model->attributes->group_payment) ?>

            <?php endif ?>


        </div>

    </div>
</div>


