# T6 System Plugin - Joomla 6 Native Version

## Overview
This is a Joomla 6 native version of the T6 System plugin and template. The plugin has been modernized to work with Joomla 6 while maintaining compatibility with the T6 template framework.

This is a completely changed T4 framwork to native Joomla! 6 conversion.

## Key Changes Made

### 1. Core Plugin Modernization
- Updated to PHP 8.4 compatibility
- Added proper type declarations and return types
- Modernized error handling with try-catch blocks
- Added strict type declarations

### 2. Joomla 6 Compatibility Fixes
- Removed all Joomla 3/4/5 backward compatibility code
- Updated to use Joomla 6 native APIs
- Fixed deprecated function calls
- Updated to use WebAssetManager for asset management

### 3. Admin Interface Updates
- Fixed method_exists null concatenation error in tplhelper.php
- Fixed undefined variable errors in featured/default.php
- Fixed open_basedir error in Path::saveLocalContent()
- Fixed Global Settings save functionality
- Fixed block list not showing for non-default templates in RowColumnSettings.php

### 4. Database and SQL
- Fixed SQL installation by adding sql folder to t4.xml
- Confirmed SQL is already MySQL strict mode compatible
- Fixed installer script class name

### 5. Template System
- Fixed T6\T6::render() type error
- Fixed json_decode() deprecation warning
- Fixed TagsHelperRoute class not found error

## Current Status

### Working Features
- ✅ Plugin installation and activation
- ✅ Template style editing
- ✅ Global Settings save functionality
- ✅ Layout management
- ✅ Theme customization
- ✅ Navigation/megamenu management
- ✅ Site settings
- ✅ Custom CSS/JS editing
- ✅ Block management
- ✅ Addon management
- ✅ Font management
- ✅ Color palettes
- ✅ Preset management
- ✅ Template preview
- ✅ Export/Import functionality

### Known Issues
- ⚠️ Joomla 6 deprecation warnings (2221 warnings from Joomla core) - These are from Joomla itself, not the T4 plugin
- ⚠️ Some Joomla 6 features may not be fully compatible with T6's override system


## Save Flow

### Template Save Flow
1. User clicks Save in Joomla admin backend
2. Joomla triggers `onExtensionBeforeSave` event (context: com_templates.style)
3. `PlgSystemT6::onExtensionBeforeSave()` is called
4. `Params::beforeSave()` is called
   - Saves global params (system_*) to `etc/global.json` file
5. Joomla saves template to `#__template_styles` table
6. Joomla triggers `onExtensionAfterSave` event
7. `PlgSystemT6::onExtensionAfterSave()` is called
   - Cleans T4 cache
   - Cleans draft data

### AJAX Save Flow (Layout, Megamenu, etc.)
1. User makes changes in T4 admin interface
2. AJAX request is sent to `onAjaxT6` with `t6do` parameter
3. `Action::run()` is called
4. Specific action handler is called (e.g., `doSaveLayout()`)
5. `Draft::store()` saves data to cache
6. Response is returned as JSON

## Debugging

To enable debugging:
1. Go to System → Global Configuration → System tab
2. Set Debug System to Yes
3. Go to System → Global Configuration → Logging tab
4. Set Log Almost Everything to Yes
5. Check `/administrator/logs/error.php` for messages starting with "T6"

## Testing Checklist

- [x] Plugin installation
- [x] Template style editing
- [x] Global Settings save
- [x] Layout management
- [x] Theme customization
- [x] Navigation/megamenu
- [x] Site settings
- [x] Custom CSS/JS
- [x] Block management
- [x] Addon management
- [x] Font management
- [x] Color palettes
- [x] Preset management
- [x] Template preview
- [x] Export/Import

## Notes

- This version is specifically for Joomla 6 and PHP 8.4
- All backward compatibility code for Joomla 3/4/5 has been removed
- Joomla 3/4/5 specific things still exists in a lot of files
- The plugin uses Joomla 6 native APIs exclusively
- Debug logging is available for troubleshooting