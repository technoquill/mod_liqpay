# mod_liqpay – LiqPay Payment Module for Joomla 4.2.x or higher

![Joomla](https://img.shields.io/badge/Joomla-4.2%2B-blue?style=flat-square&logo=joomla)
![PHP](https://img.shields.io/badge/PHP-8.x-8892BF?style=flat-square&logo=php)
![License](https://img.shields.io/badge/license-MIT-green?style=flat-square)
![Repo size](https://img.shields.io/github/repo-size/technoquill/mod_liqpay?style=flat-square)

The **mod_liqpay** module allows you to integrate the **LiqPay** payment system into your Joomla 4+ based website.  
LiqPay is a widely used Ukrainian payment service that supports multiple payment methods, including bank cards (Visa, MasterCard), Google Pay, Apple Pay, QR-code, and more.

> **Note:**  
> This module supports **Joomla 4.2 and higher**, using the new OOP-based module structure introduced in Joomla 4.2.x.

---

## 🌍 Multilingual Support

The module includes interface translations for:
- 🇺🇦 Ukrainian (uk-UA)
- 🇬🇧 English (en-GB)
- 🇩🇪 German (de-DE)
- 🇷🇺 Russian (ru-RU)

---

## 🔧 Key Features

- Support for **UAH**, **USD**, and **EUR** currencies
- Two operation modes:
  - **Simple Payment** – one-time payment with fixed or preset amounts
  - **Grouped Payment** – structured list of services or items
- Optional donation mode for NGOs or fundraising campaigns
- Built-in test mode for sandbox transactions
- LiqPay callback support for auto status updates
- Email notifications for administrators
- Option to show/hide UI elements: logo, amount, extended info, payment methods

---

## ⚙️ Configuration Fields

### General Settings
- **Logo** – image displayed in the module (optional)
- **Title** – visible name of the module/cause
- **Public / Private Key** – credentials from LiqPay merchant account
- **Module Type** – choose between simple or grouped payment
- **Currency** – default currency (UAH, USD, EUR)
- **Allow currency change** – optional switch for frontend users
- **Payment Type** – `pay`, `donate`, or custom value
- **Terms URL** – optional link to public offer/agreement
- **Available Payment Methods** – checkboxes for LiqPay, Privat24, Apple Pay, etc.
- **Notification Email** – address for transaction alerts

### Simple Payment Mode
- **Payment Purpose** – description for the transaction
- **Default Amount** – prefilled value
- **Amount Options** – list of quick-pick sums (e.g. 100, 200, 500)
- **Extra Info** – optional rich text (HTML supported)

### Grouped Payment Mode
- **Services List** – name, description, price, old price, optional flags (e.g. special offer)
- **Post-list Text** – HTML block after item list

---

## 🛠 Requirements

- Joomla **4.2 or newer**
- PHP **7.2.5 or higher**
- Valid **LiqPay merchant account**

---

## 📦 Installation

1. Download the latest release from [GitHub Releases](https://github.com/technoquill/mod_liqpay/releases).
2. In Joomla Admin Panel, go to **Extensions → Install** and upload the module ZIP.
3. Navigate to **Extensions → Modules**, find **LiqPay Module**, and enable it.
4. Open the module configuration and enter your **Public** and **Private keys**.
5. Select module type, enter payment info, and configure settings as needed.

---

## 📜 License

This module is licensed under the **MIT License**.  
You are free to use, modify, and distribute it in commercial and non-commercial projects.

---

## 💡 Sample Interface (Frontend View)

Preview of the donation/payment form rendered by the module:

![LiqPay Module Frontend Preview](https://raw.githubusercontent.com/technoquill/project-media/main/mod_liqpay/frontend-preview.png)


---

**Repository:** [github.com/technoquill/mod_liqpay](https://github.com/technoquill/mod_liqpay)
