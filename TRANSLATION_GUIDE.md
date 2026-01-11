# Guía de Implementación de Traducciones (Laravel + Vue)

Esta guía documenta la configuración implementada para manejar traducciones en el proyecto utilizando **Laravel Lang** para el backend y **Laravel Vue i18n** para el frontend (Vue/Inertia).

## 1. Instalación de Paquetes

Se requieren los siguientes paquetes:

```bash
# Para traducciones de Laravel (Backend)
composer require laravel-lang/common --dev

# Para publicación de archivos de idioma
php artisan lang:publish

# Para integración con Vue
npm install laravel-vue-i18n
```

## 2. Configuración del Backend (Laravel)

1.  **Configurar idioma predeterminado**:
    En el archivo `.env`:

    ```env
    APP_LOCALE=es
    APP_FALLBACK_LOCALE=es
    ```

    Y en `config/app.php` (si es necesario):

    ```php
    'locale' => 'es',
    'fallback_locale' => 'es',
    ```

2.  **Archivos de Idioma**:
    Los archivos JSON de traducción se encuentran en la carpeta `lang/`. Por ejemplo, `lang/es.json` contiene las traducciones clave-valor para la interfaz.

## 3. Configuración del Frontend (Vite & Vue)

### Plugin de Vite (`vite.config.ts`)

Es necesario agregar el plugin de i18n para que Vite procese los archivos de idioma de Laravel.

```typescript
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import i18n from 'laravel-vue-i18n/vite'; // <--- Importar

export default defineConfig({
    plugins: [
        laravel({
            // ...
        }),
        vue({
            // ...
        }),
        i18n(), // <--- Agregar a la lista de plugins
    ],
});
```

### Configuración de Vue (`resources/js/app.ts`)

Configuración para el renderizado del lado del cliente (CSR). Se usa `resolve` asíncrono.

```typescript
import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { i18nVue } from 'laravel-vue-i18n';

createInertiaApp({
    // ...
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(i18nVue, {
                resolve: async (lang) => {
                    const langs = import.meta.glob('../../lang/*.json');
                    return await langs[`../../lang/${lang}.json`]();
                },
            })
            .mount(el);
    },
});
```

### Configuración de SSR (`resources/js/ssr.ts`)

Si usas Server-Side Rendering, la configuración debe ser síncrona (`eager: true`) para evitar problemas de hidratación.

```typescript
import { createSSRApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { i18nVue } from 'laravel-vue-i18n';

export default function render(page) {
    return createInertiaApp({
        // ...
        setup({ App, props, plugin }) {
            return createSSRApp({ render: () => h(App, props) })
                .use(plugin)
                .use(i18nVue, {
                    lang: 'es', // Forzar idioma para SSR si es necesario
                    resolve: (lang) => {
                        const langs = import.meta.glob('../../lang/*.json', {
                            eager: true,
                        });
                        return langs[`../../lang/${lang}.json`];
                    },
                });
        },
    });
}
```

## 4. Uso en Componentes Vue

### En el Template

Usa la función global `$t()` para traducir cadenas.

```vue
<template>
    <h1>{{ $t('Welcome') }}</h1>

    <!-- Usar claves que existen en lang/es.json -->
    <button>{{ $t('Log in') }}</button>
    <label>{{ $t('Email address') }}</label>
</template>
```

### En el Script (`<script setup>`)

Si necesitas traducir dentro de la lógica del componente (por ejemplo, en props o variables reactivas), importa `trans`.

```typescript
<script setup lang="ts">
import { trans } from 'laravel-vue-i18n';

const pageTitle = trans('Dashboard');

const navItems = [
    { title: trans('Profile settings'), href: '/profile' }
];
</script>
```

### Rutas y Props de Inertia

Para componentes que reciben títulos o descripciones vía props (como `Head` o componentes de UI), asegúrate de usar `$t()` al pasarlos.

```vue
<Head :title="$t('Profile settings')" />

<HeadingSmall
    :title="$t('Security')"
    :description="$t('Update your password')"
/>
```

## 5. Flujo de Trabajo para Nuevas Traducciones

1.  **Identificar texto**: Encuentra el texto harcodeado en inglés en tus componentes `.vue`.
2.  **Agregar a JSON**: Agrega la clave en inglés y su traducción al español en `lang/es.json`.
    ```json
    {
        "Hello World": "Hola Mundo"
    }
    ```
3.  **Reemplazar en Vue**: Cambia el texto fijo por `{{ $t('Hello World') }}`.
4.  **Compilar**: Ejecuta `npm run build` o ten `npm run dev` corriendo para ver los cambios.
