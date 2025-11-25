# 🇪🇸 Laravel Archivos de Idiomas – Guía de Traducción

## 📁 Ubicación: `lang/{locale}/`

Cada carpeta representa un idioma según su código (`en`, `es`, etc.).

---

## 📝 ¿Cómo agregar un nuevo idioma?

1. Crea una carpeta en `resources/lang/` con el código del idioma.
   Ejemplo:

   ```
   resources/lang/en/       ← Inglés  
   resources/lang/es/       ← Español
   ```

2. Dentro, crea archivos `.php` que retornen arreglos con las traducciones.

---

## 🧪 Ejemplo: `resources/lang/en/messages.php`

```php
return [
    'welcome' => 'Welcome to the system!',
    'dashboard' => 'Dashboard',
];
```

### `resources/lang/es/messages.php`

```php
return [
    'welcome' => '¡Bienvenido al sistema!',
    'dashboard' => 'Tablero',
];
```

---

## 🧑‍💻 ¿Cómo se usa en Blade o Controladores?

```blade
<h1>{{ __('messages.welcome') }}</h1>
```

```php
echo __('messages.dashboard');
```

---

## 🛠️ Opcional: archivos JSON para traducciones simples

Usa archivos `en.json`, `es.json`, etc.

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

Uso:

```blade
{{ __('Welcome') }}
```

---

## 📌 Buenas prácticas

* Usa archivos agrupados (`messages.php`, `auth.php`, etc.) para mantener orden.
* Usa `__('...')` siempre que necesites mostrar texto traducible.
* Alinea los códigos de idioma con los definidos en la tabla `locales`.
