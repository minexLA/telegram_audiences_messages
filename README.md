# Telegram Audiences Messages

[![Latest Version](https://img.shields.io/packagist/v/minex/telegram_audiences_messages.svg?style=flat-square)](https://packagist.org/packages/vendor/package-name)
[![Total Downloads](https://img.shields.io/packagist/dt/minex/telegram_audiences_messages.svg?style=flat-square)](https://packagist.org/packages/vendor/package-name)
[![License](https://img.shields.io/packagist/l/minex/telegram_audiences_messages.svg?style=flat-square)](LICENSE)

This package allow you to easily create cohorts of users with custom filters support and then create a messages to send to them.

---

## ✨ Features

✅ Audience Management 
- Create and manage audiences dynamically.
- Supports custom filters for flexible targeting.
- Useful for mass campaigns or segmented user messaging.

✅ Telegram Message Builder
- Easily create Telegram messages
- Mass broadcasts.
- On-demand sending using triggers or scheduled workflows.
- Supports:
    - Plain text and HTML formatting. 
    - Inline buttons with links. 
    - Image or video attached to message.

✅ Trigger-Based Sending
- Custom triggers from your workflows.

✅ Built for Laravel
- Supports **Laravel 8, 9, 10, 11, 12, 13**.

---

## 🚀 Installation

You can install the package via Composer:

```bash
composer require minex/telegram_audiences_messages
```

## 📦 Publishing Assets

You can publish the package assets using the `vendor:publish` command.

### 🚀 Publish Configuration

To publish the configuration file:

```bash
php artisan vendor:publish --tag=telegram_audiences_messages-config
```