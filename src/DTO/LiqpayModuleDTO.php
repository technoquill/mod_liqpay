<?php
declare(strict_types=1);

namespace Joomla\Module\Liqpay\Site\DTO;


use Joomla\Module\Liqpay\Site\Library\Traits\DTOTrait;


/**
 * @since
 */
final class LiqpayModuleDTO
{
    public int $module_id;

    public string $logotype;

    public string $name;

    public string $public_key;

    public string $private_key;

    public string $payment_type;

    public string $currency;

    public int $disable_currency_select;

    public string $action;

    public array $available_payments = [];

    public string $email;

    public array $settings = [
        "show_logo_and_name" => null,
        "show_additional_info" => null,
        "show_payments_method" => null,
    ];

    public ?string $simple_payment_purpose_of_payment;

    public int $simple_payment_readonly_purpose_of_payment;

    public ?int $simple_payment_default_amount;

    public array $simple_payment_amounts = [];

    public ?string $simple_payment_additional_info;

    public int $group_payment_as_separate;

    public array $group_payment = [];

    public ?string $group_payment_additional_info;

    public string $layout;

    public ?string $moduleclass_sfx;

    public int $cache;

    public int $cache_time;

    public string $module_tag;

    public string $bootstrap_size;

    public string $header_tag;

    public ?string $header_class;

    public string $style;

    use DTOTrait;
}