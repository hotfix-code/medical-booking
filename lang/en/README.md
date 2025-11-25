# 🌐 Laravel Language Files – Translation Guide

## 📁 Location: `lang/{locale}/`

Each folder corresponds to a language code (e.g. `en`, `es`, `fr`).

---

## 📝 How to Add a New Locale

1. Create a folder inside `resources/lang/` with the locale code.
   Example:

   ```
   resources/lang/en/       ← English
   resources/lang/es/       ← Spanish
   ```

2. Inside that folder, create one or more PHP files returning translation arrays.

---

## 🧪 Example: `resources/lang/en/messages.php`

```php
<?php

return [
    'welcome' => 'Welcome to the system!',
    'dashboard' => 'Dashboard',
];
```

### `resources/lang/es/messages.php`

```php
<?php

return [
    'welcome' => '¡Bienvenido al sistema!',
    'dashboard' => 'Tablero',
];
```

---

## 🧑‍💻 How to Use in Blade or Controllers

```blade
<h1>{{ __('messages.welcome') }}</h1>
```

```php
echo __('messages.dashboard');
```

---

## 🛠️ Optional: Use `json` files for flat strings

Instead of groups, you can use JSON-based translations:

* `resources/lang/en.json`
* `resources/lang/es.json`

**en.json**

```json
{
  "Welcome": "Welcome",
  "Dashboard": "Dashboard"
}
```

**es.json**

```json
{
  "Welcome": "Bienvenido",
  "Dashboard": "Tablero"
}
```

Use in Blade:

```blade
{{ __('Welcome') }}
```

---

## 📌 Best Practices

* Use **grouped files** (`messages.php`, `auth.php`, etc.) for structured apps.
* Use `__('...')` helper to retrieve all translations.
* Keep locale codes consistent with your `locales` table (`en`, `es`, etc.)
