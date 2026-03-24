# Joomla! 3,4,5 to Joomla! 6 Code Changes

## 1. Core Application & Input Changes

### Application Input Property is Deprecated
**File:** `libraries/src/Application/WebApplication.php`
**Old Code:**
```php
$app->input->get('foo');
Factory::getApplication()->input->get('foo');
```
**New Code:**
```php
$app->getInput()->get('foo');
Factory::getApplication()->getInput()->get('foo');
```
**Note:** This triggers a deprecation warning in Joomla 6. The `input` property was deprecated in Joomla 4 but is now actively being removed .

### JPATH_PLATFORM Constant Removed
**File:** `libraries/bootstrap.php`
**Old Code:**
```php
defined('JPATH_PLATFORM') or die;
```
**New Code:**
```php
defined('_JEXEC') or die;
```
**Note:** Use the standard `_JEXEC` check for security. This was deprecated in earlier versions .

### PunycodeHelper: emailToPunycode() No Longer Accepts Null
**File:** `libraries/src/String/PunycodeHelper.php`
**Change:** The function now throws a deprecation notice if `NULL` is passed as an email address. Ensure you pass a valid string .

---

## 2. HTTP Client Changes

### Joomla\CMS\Http Package is Removed
**File:** Entire `libraries/src/Http/` directory
**Change:** The entire `Joomla\CMS\Http` package is deprecated in Joomla 6 and will be removed in Joomla 8 .
**New Code:**
Use the Joomla Framework `Joomla\Http` package instead.

**Important:** When migrating, note that the `Response` object changes.
- **Old:** Access data via magic attributes (e.g., `$response->body`).
- **New:** Use PSR-7 standard methods (e.g., `(string) $response->getBody()`).

---

## 3. Content Component Changes

### Featured Articles Merged into Articles
**Context:** The duplicate code for featured articles has been removed .

**Model Changes:**
- **Old:** `Joomla\Component\Content\Administrator\Model\FeaturedModel`
- **New:** `Joomla\Component\Content\Administrator\Model\ArticlesModel`

**Instantiation Example:**
```php
$model = Factory::getApplication()
    ->bootComponent('com_content')
    ->getMVCFactory()
    ->createModel('Articles', 'Administrator', ['ignore_request' => true]);
$model->setState('filter.featured', 1);
```

**Controller Changes:**
- **Old:** `Joomla\Component\Content\Administrator\Controller\FeaturedController`
- **New:** `Joomla\Component\Content\Administrator\Controller\ArticlesController`

**View Changes:**
- **Old:** `HtmlView` for featured articles.
- **New:** No replacement. Use the Articles view with the featured filter applied.

**Menu Link:**
The new menu link to featured articles is:
`administrator/index.php?option=com_content&view=articles&filter[featured]=1`

---

## 4. Tags & Batching Changes

### TagsHelper: postStoreProcess() Deprecated
**File:** `libraries/src/Helper/TagsHelper.php`
**Old Function:** `postStoreProcess()`
**New Function:** `postStore()`
**Change:** The new function adds an optional boolean parameter `$remove` (default `false`). If set to `true`, the `$replace` parameter is ignored and tags are removed instead .

### AdminModel: batchTag() Deprecated
**File:** `libraries/src/MVC/Model/AdminModel.php`
**Old Function:** `batchTag()`
**New Function:** `batchTags()`
**Change:** The new function adds an optional boolean parameter `$removeTags` (default `false`) .

---

## 5. User Class Changes

### $aid Property Removed
**File:** `libraries/src/User/User.php`
**Change:** The `$aid` property is removed.
**Replacement:** None. User roles and access levels should be managed through the standard Access Level system. The property was deprecated because it did not accurately reflect complex role structures .

---

## 6. Workflow Changes

### Workflow Class Requires Database and Application
**File:** `libraries/src/Workflow/Workflow.php`
**Old Code:**
`$workflow = new Workflow($extension);`
**New Code:**
`$workflow = new Workflow($extension, $app, $db);`
**Note:** The Application and Database objects are now mandatory in the constructor .

### WorkflowBehaviorTrait Requires DatabaseAwareTrait
**File:** `libraries/src/MVC/Model/WorkflowBehaviorTrait.php`
**Change:** If you use `WorkflowBehaviorTrait` in a model, you must also use `DatabaseAwareTrait` to ensure the `getDatabase` function exists.
**Example:**
```php
class Foo {
  use MVC/Model/WorkflowBehaviorTrait;
  use Joomla\Database\DatabaseAwareTrait;
}
```

---

## 7. Namespace & Filesystem Changes

### Joomla\CMS\Filesystem Namespace Transition
**Context:** The CMS-specific filesystem classes are being phased out in favor of the Framework classes .

**Classes Affected:** `File`, `Folder`, `Path`, `Stream`
**Old Namespace:** `Joomla\CMS\Filesystem`
**New Namespace:** `Joomla\Filesystem`

**Important Note:** The Framework classes (in `libraries/vendor/joomla/filesystem`) **do not** support the FTP layer that the CMS classes did. If your extension relied on FTP, you will need to refactor that logic.

**Code Example for Cross-Version Compatibility:**
If you need to support Joomla 4 and 6 simultaneously, use a conditional check:
```php
use Joomla\CMS\Version;

if (version_compare(Version::MAJOR_VERSION, '5', '>=')) {
    \Joomla\Filesystem\Path::clean($filename);
} else {
    \Joomla\CMS\Filesystem\Path::clean($filename);
}
```

---

## 8. Deprecations Pushed to Joomla 7 (Extended Support)

These items were originally scheduled for removal in Joomla 6 but have been postponed to Joomla 7. However, it is best practice to update them now .

| Area | Details |
| :--- | :--- |
| **Event API** | Support for the old event API (without event classes) remains in Joomla 6 but will be removed in 7. |
| **Editor & Captcha APIs** | The deprecated APIs for editors and captchas remain available in Joomla 6. They will be removed in 7. |
| **JLoader::register()** | The `JLoader::register()` method (a legacy autoloader) will be removed in Joomla 7. Use standard Composer autoloading or `JLoader::registerNamespace()` instead. |

---

## 9. General Requirements & Features

### PHP 8.2 Minimum
Joomla 6 requires PHP 8.2 or higher. Ensure your code is compatible with PHP 8.2+ features and types .

### Backward Compatibility (BC) Plugin
The **"Behaviour - Backward Compatibility 6"** plugin is introduced in Joomla 5.4 .
- **In Joomla 5:** This plugin helps developers test compatibility by simulating Joomla 6 deprecations.
- **In Joomla 6:** The **old** BC plugin (for Joomla 4 compatibility) is **removed**. Your extension must be updated to the latest standards; there is no BC layer for Joomla 4 code in Joomla 6.

### Lazy Loading in DI Container
Joomla 6 introduces support for lazy objects in the Dependency Injection (DI) container. This improves performance by deferring the initialization of services until they are actually used . When defining services, consider implementing them as lazy if they are resource-intensive but not always needed during a request.

---

## Summary Checklist for AI Coder Input

If you are feeding this list to an AI assistant to help refactor code, instruct it to look specifically for:

1.  **`$app->input`** → Replace with `$app->getInput()`
2.  **`JPATH_PLATFORM`** → Replace with `_JEXEC`
3.  **`use Joomla\CMS\Http`** → Replace with `use Joomla\Http` and update Response handling.
4.  **`FeaturedModel` / `FeaturedController`** → Replace logic with `ArticlesModel` + `filter.featured=1`.
5.  **`batchTag`** → Rename to `batchTags` and add the `$removeTags` parameter.
6.  **`postStoreProcess`** → Rename to `postStore` and evaluate the new `$remove` flag.
7.  **`Joomla\CMS\Filesystem`** → Update namespace to `Joomla\Filesystem` and verify FTP dependencies.
8.  **`$user->aid`** → Remove references and rely on ACL checks.
9.  **Workflow constructors** → Ensure `$app` and `$db` are passed.
10. **Search extensions** (`com_search`) → All related language strings and helpers are removed (no replacement).