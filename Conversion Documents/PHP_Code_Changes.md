# PHP Code Changes for the different PHP versions that Joomla has used

## 1. PHP Version Requirements Across Joomla Versions

This table shows the minimum and recommended PHP versions for each Joomla major version.

| Joomla Version | Minimum PHP | Recommended PHP | Key PHP Version Changes |
|----------------|-------------|-----------------|------------------------|
| **Joomla 3.x** | PHP 5.3.10 | PHP 7.0 - 8.0 | Originally supported PHP 5.3.10; later versions added PHP 7 support  |
| **Joomla 4.x** | PHP 7.2.5 | PHP 8.0 - 8.2 | Dropped PHP 5 and PHP 7.1 entirely; introduced PHP 7.2+ features  |
| **Joomla 5.x** | PHP 8.1.0 | PHP 8.2 - 8.4 | PHP 8.1 minimum; PHP 8.0 no longer supported  |
| **Joomla 6.x** | PHP 8.3.0 | PHP 8.4 | PHP 8.3 minimum; requires modern PHP features  |

**Important Notes:**
- PHP 7.4 reached end of life in November 2022 and is not supported in Joomla 5 or 6.
- Joomla 4.4 serves as a bridge version that works with both PHP 7.4 and PHP 8.x to facilitate upgrades.
- PHP 8.0 reached end of life in November 2023 and is not supported in Joomla 5+.

---

## 2. PHP Module Requirements Across Versions

### Required PHP Extensions (All Joomla 4+)

These extensions must be enabled on the server :

| Extension | Joomla 3 | Joomla 4 | Joomla 5 | Joomla 6 |
|-----------|----------|----------|----------|----------|
| `json` | ✅ Required | ✅ Required | ✅ Required | ✅ Required |
| `simplexml` | ✅ Required | ✅ Required | ✅ Required | ✅ Required |
| `dom` | ✅ Required | ✅ Required | ✅ Required | ✅ Required |
| `zlib` | ✅ Required | ✅ Required | ✅ Required | ✅ Required |
| `gd` | ✅ Required | ✅ Required | ✅ Required | ✅ Required |
| `mbstring` | ⭐ Recommended | ⭐ Recommended | ✅ Required | ✅ Required |
| `curl` | ⭐ Recommended | ⭐ Recommended | ⭐ Recommended | ⭐ Recommended |

### Database Driver Changes

| Database | Joomla 3 | Joomla 4+ |
|----------|----------|-----------|
| **MySQL** | `ext/mysql` (deprecated), `ext/mysqli`, `pdo_mysql` | Only `ext/mysqli` or `pdo_mysql`  |
| **PostgreSQL** | `ext/pgsql`, `pdo_pgsql` | Only `pdo_pgsql`  |
| **SQL Server** | Supported | **Dropped entirely** in Joomla 4  |

### PHP Configuration Requirements

| Setting | Joomla 3 | Joomla 4+ |
|---------|----------|-----------|
| `memory_limit` | 128M | 256M+ (512M recommended)  |
| `upload_max_filesize` | 20M | 32M+  |
| `post_max_size` | 32M | 128M+  |
| `max_execution_time` | 120 | 300+  |
| `magic_quotes_gpc` | Must be off | No longer exists in PHP 8+ |

---

## 3. PHP Language Features by Joomla Version

This table organizes PHP language features by when they were introduced to Joomla.

### PHP 7.2+ Features (Joomla 4+)

| Feature | Description | Example |
|---------|-------------|---------|
| **Void Return Types** | Methods can declare `: void` for no return value | `public function save(array $data): void { }`  |
| **Native Sodium Encryption** | PHP's libsodium extension replaces mcrypt | Used for WebAuthn passwordless login  |
| **Object Type Hinting** | `object` type declaration | `public function process(object $data): void { }` |
| **`json` Exception** | `json_decode()` throws exceptions on errors | `json_decode($string, null, 512, JSON_THROW_ON_ERROR);` |

### PHP 7.4+ Features (Joomla 4.4+)

| Feature | Description | Example |
|---------|-------------|---------|
| **Typed Properties** | Class properties can have type declarations | `public string $name;` |
| **Arrow Functions** | Shorter closure syntax | `$ids = array_map(fn($item) => $item->id, $items);` |
| **Null Coalescing Assignment** | `??=` operator | `$data['key'] ??= 'default';` |
| **Preloading** | OPcache preloading for performance | Server-level configuration |

### PHP 8.0+ Features (Joomla 5+)

| Feature | Description | Example |
|---------|-------------|---------|
| **Named Arguments** | Specify arguments by parameter name | `$result = $this->getItem(id: $id, loadChildren: true);`  |
| **Match Expression** | Enhanced switch with return value | `$result = match($type) { 'article' => 'content', default => 'other' };`  |
| **Nullsafe Operator** | Safe chaining of method calls | `$name = $user?->getProfile()?->getDisplayName();`  |
| **Constructor Property Promotion** | Define properties in constructor | `public function __construct(public string $name) { }`  |
| **Union Types** | Multiple type declarations | `public function process(int\|string $input): void { }` |
| **`str_contains()`, `str_starts_with()`** | Native string functions | `if (str_contains($string, 'search')) { }` |

### PHP 8.1+ Features (Joomla 5+)

| Feature | Description | Example |
|---------|-------------|---------|
| **Enums** | Native enumeration support | `enum Status: string { case DRAFT = 'draft'; }` |
| **First-class Callable Syntax** | `...` operator for callables | `$callback = $object->method(...);` |
| **`never` Return Type** | Functions that never return | `function die(): never { exit; }` |
| **Array Unpacking with String Keys** | Spread operator works with string keys | `$merged = [...$array1, ...$array2];` |

### PHP 8.2+ Features (Joomla 6+)

| Feature | Description | Example |
|---------|-------------|---------|
| **Readonly Classes** | Entire class declared readonly | `readonly class ImmutableData { }` |
| **`true` Type** | Boolean true as standalone type | `function alwaysTrue(): true { return true; }` |
| **Disjunctive Normal Form Types** | Intersection + union types | `function process((A&B)\|C $input): void { }` |
| **`null` as Standalone Type** | Null type declaration | `function find(): null\|User { }` |

---

## 4. PHP Database Changes Across Versions

### MySQL Strict Mode (Joomla 4+)

Joomla 4+ enables MySQL strict mode by default with these flags :

```sql
STRICT_TRANS_TABLES
ERROR_FOR_DIVISION_BY_ZERO
NO_AUTO_CREATE_USER
NO_ENGINE_SUBSTITUTION
```

**Impact on code:**
- `0000-00-00 00:00:00` is no longer allowed for date fields; must use `NULL` instead 
- Division by zero now throws errors
- Invalid date values cause SQL errors

### Database Query Changes

| Aspect | Joomla 3 | Joomla 4+ |
|--------|----------|-----------|
| **Quote Method** | `$db->quote()` | `$db->quote()` still works, but `$db->quoteName()` preferred |
| **Prepared Statements** | Optional | Encouraged for all queries |
| **`loadObjectList()`** | Returns array of objects | Same, but object properties must match database columns |

---

## 5. Joomla-Specific PHP Changes

### Removed PHP Extensions and Functions

| Removed Item | Joomla Version | Replacement |
|--------------|----------------|-------------|
| `mcrypt` extension | Joomla 4+ | Sodium extension  |
| `ext/mysql` driver | Joomla 4+ | `ext/mysqli` or `pdo_mysql`  |
| `ext/pgsql` driver | Joomla 4+ | `pdo_pgsql`  |
| `JLoader::register()` | Joomla 7 (postponed) | Composer autoloading or `JLoader::registerNamespace()` |

### Namespace Requirements (Joomla 4+)

All Joomla core classes moved to namespaces. Third-party extensions must follow suit :

```php
// Joomla 3 style (deprecated)
jimport('joomla.filesystem.file');
JFile::write($path, $content);

// Joomla 4+ style
use Joomla\Filesystem\File;
File::write($path, $content);
```

**Third-party namespace convention:** Use your company name as root namespace, e.g., `Akeeba\` .

### OPcache and Autoloader Considerations (Joomla 4+)

When updating extensions, Joomla stores autoloader information in `administrator/cache/autoload_psr4.php`. If this file becomes stale, it can cause "class not found" errors. Solutions :

1. Delete `administrator/cache/autoload_psr4.php`
2. Clear OPcache via your hosting control panel
3. Wait for OPcache to expire (configurable, typically seconds to hours)

---

## 6. PHP Error Handling Changes

### Error Reporting Levels

| Joomla Version | Default Error Handling |
|----------------|------------------------|
| Joomla 3 | Suppressed many notices and warnings |
| Joomla 4+ | All PHP errors become exceptions where possible |

### Example: Catching Exceptions

```php
// Joomla 3 - error suppression
$content = @file_get_contents($url);

// Joomla 4+ - explicit exception handling
use Joomla\CMS\Factory;

try {
    $content = file_get_contents($url);
    if ($content === false) {
        throw new \RuntimeException('Failed to fetch URL');
    }
} catch (\Throwable $e) {
    Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
    return false;
}
```

---

## Summary Checklist for AI Coder

When refactoring code from Joomla 3.x to Joomla 6.x, instruct the AI to handle these PHP-specific changes:

1. **PHP Version Target:** Set target to PHP 8.3 minimum for Joomla 6

2. **PHP Extensions:** Verify `json`, `simplexml`, `dom`, `zlib`, `gd`, `mbstring`, `curl` are enabled

3. **Database Driver:** Replace any `ext/mysql` or `ext/pgsql` calls with `mysqli` or PDO equivalents

4. **MySQL Strict Mode:** Replace `0000-00-00 00:00:00` date values with `NULL`

5. **Type Declarations:** Add return types and parameter types to all methods

6. **PHP 8 Features:** Use match expressions, nullsafe operator, named arguments where appropriate

7. **Joomla Namespaces:** Replace `J*` global classes with namespaced equivalents

8. **Error Handling:** Replace `@` suppression with try-catch blocks

9. **Removed Functions:** Remove any `each()`, `create_function()`, `mcrypt_*` calls

10. **Database Queries:** Use prepared statements and `$db->quoteName()` for all queries

This list should provide complete coverage of PHP changes across all three Joomla major versions (4, 5, 6) when upgrading from Joomla 3.