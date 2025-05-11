# mod_liqpay – LiqPay Payment Module for Joomla 4+

![Joomla](https://img.shields.io/badge/Joomla-4.2%2B-blue?style=flat-square&logo=joomla)
![PHP](https://img.shields.io/badge/PHP-8.x-8892BF?style=flat-square&logo=php)
![License](https://img.shields.io/badge/license-MIT-green?style=flat-square)
![Repo size](https://img.shields.io/github/repo-size/technoquill/mod_liqpay?style=flat-square)

The **mod_liqpay** module allows you to integrate the **LiqPay** payment system into your Joomla 4+ based website. LiqPay is a popular Ukrainian payment service supporting various payment methods, including bank cards (Visa, MasterCard), e-wallets, and others.

> **Note:**  
> This module supports **Joomla 4.2 and higher**, as it utilizes Joomla's new object-oriented programming (OOP) approach for module development.

## Key Features

- Supports payments in Ukrainian hryvnia (UAH), US dollar (USD), and Euro (EUR).
- Two operating modes:
  - **Payment mode** – standard payments for products and services.
  - **Donation mode** – accepts donations from users.
- Quick and easy setup via Joomla administrator interface.
- Integration of LiqPay payment page within order checkout processes.
- Test mode available for transaction simulation without real payments.
- Callback support from LiqPay for automatic payment status updates.

## Requirements

- Joomla **4.2 or newer**
- LiqPay merchant account (public and private keys)

## Installation

1. Download and extract the module archive.
2. In Joomla admin panel, navigate to **Extensions → Install** and upload the module.
3. Go to **Extensions → Modules** and activate the LiqPay module.
4. Enter your LiqPay public and private keys obtained from your LiqPay merchant account.
5. Select the desired operating mode (**payment** or **donation**) in the module settings.

## License

This module is distributed under the **MIT license**. You are free to use, modify, and distribute it in your projects.
