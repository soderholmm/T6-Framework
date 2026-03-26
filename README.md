# 🧩 T6 Framework for Joomla 6

A modern Joomla template framework providing advanced layout management, theme customization, and megamenu functionality for Joomla 6.

[![Joomla](https://img.shields.io/badge/Joomla-6.0-f44321.svg)](https://www.joomla.org)
[![PHP](https://img.shields.io/badge/PHP-8.4+-777BB4.svg)](https://php.net)
[![License: GPL v3](https://img.shields.io/badge/License-GPLv3-blue.svg)](https://www.gnu.org/licenses/gpl-3.0)

## Overview

The T6 Framework consists of two main components:

- **T6 System Plugin** (`t6-system-plugin/`) - Core framework plugin that provides the template engine, admin interface, and framework functionality
- **T6 Bootstrap 5 Template** (`tpl_t6_bs5_blank/`) - A blank Bootstrap 5 starter template built on the T6 Framework

## 🤝 Support the work
[![PayPal](https://img.shields.io/badge/PayPal-donate-00457C?style=for-the-badge&logo=paypal&logoColor=white)](https://paypal.me/soderholmm)

## ✨ Key Features

- **Layout Management** - Visual block-based layout system with drag-and-drop functionality
- **Theme Customization** - Color palettes, presets, and custom CSS/JS support
- **Megamenu System** - Advanced navigation with multi-column dropdown menus
- **Font Management** - Google Fonts integration with weight and subset controls
- **Responsive Design** - Mobile-first Bootstrap 5 based templates
- **Addon System** - Extensible addon architecture for additional functionality
- **Import/Export** - Backup and restore template configurations

## 🔄 Migration from Joomla 3/4/5

This version has been fully migrated to native Joomla 6 compatibility. The migration involved:

- PHP 8.4 modernization with type declarations and modern PHP features
- Removal of all Joomla 3/4/5 backward compatibility code
- Updated to use Joomla 6 native APIs exclusively
- MySQL strict mode compatibility
- Modernized error handling

For detailed migration documentation, see the `Conversion Documents/` folder.

## 📦 Requirements

- **Joomla**: 6.x
- **PHP**: 8.4+
- **Database**: MySQL
- **PHP Extensions**: json, simplexml, dom, zlib, gd, mbstring, curl

## 📁 Project Structure

```
t6-framework/
├── t6-system-plugin/          # T6 System Plugin
│   ├── t6.php                # Plugin entry point
│   ├── admin/                # Admin interface and custom fields
│   ├── src/t6/               # Core T6 framework classes
│   └── themes/               # Base theme templates
│
├── tpl_t6_bs5_blank/         # T6 Bootstrap 5 Template
│   ├── index.php             # Template entry point
│   ├── etc/                  # Configuration files (JSON)
│   ├── html/                 # Template overrides
│   ├── scss/                 # SCSS source files
│   └── css/                  # Compiled CSS
│
└── Conversion Documents/     # Migration documentation
    ├── README-MIGRATION.md   # Detailed migration guide
    ├── T4_ARCHITECTURE.md    # Framework architecture guide
    ├── Joomla_Code_Changes.md # Joomla API changes
    └── PHP_Code_Changes.md   # PHP version changes
```

## 🎯 Current Status

The framework is fully functional with all core features working:

- [X] Plugin installation and activation
- [X] Template style editing and global settings
- [X] Layout and block management
- [X] Theme customization and color palettes
- [X] Navigation/megamenu management
- [X] Font and typography management
- [X] Custom CSS/JS editing

### Not tested
- [ ] Import/export functionality


## ⚠️ Needs to be fixed
- [ ] `CSS` files have old Joomla! 3/4/5 specific code
- [ ] Check for other old code that can be removed
- [ ] `Reload Preview` button don't load `Menu Item --> Options --> Title` and `Menu Item --> Page Display --> Show Page Heading`

## ✅ Fixed
- [X] Add `Link Active` color to `Theme Color --> Palettes`


## 📚 Documentation

For detailed technical documentation, architecture guides, and migration notes, refer to the documents in the `Conversion Documents/` folder.


## 📄 License
[GNU General Public License version 3 or later](https://www.gnu.org/licenses/gpl-3.0.txt)