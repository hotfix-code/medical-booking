<?php

namespace Database\Seeders;

use App\Models\ChangelogEntry;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class ChangelogSeeder extends Seeder
{
    public function run(): void
    {
        if (!app()->environment('local') || ! config('changelog.enabled')) {
            return;
        }

        if (!Schema::hasTable('changelog_entries') || ! Schema::hasTable('changelog_entry_translations')) {
            return;
        }

        ChangelogEntry::query()->where('version', '1.1.0')->each(function (ChangelogEntry $entry) {
            $entry->translations()->delete();
            $entry->delete();
        });

        $entries = [
            [
                'version' => '1.0.0',
                'released_at' => '2025-09-25',
                'category' => 'added',
                'sort_order' => 1,
                'translations' => [
                    'en' => 'Initial release of the Medical Booking application.',
                    'es' => 'Versión inicial de la aplicación Medical Booking.',
                ],
            ],
            [
                'version' => '1.1.0',
                'released_at' => '2026-09-11',
                'category' => 'added',
                'sort_order' => 1,
                'translations' => [
                    'en' => 'English and Spanish across the app: layout, pages, forms, login, DataTables, JavaScript and backend messages.',
                    'es' => 'Inglés y español en toda la aplicación: layout, páginas, formularios, login, DataTables, JavaScript y mensajes del backend.',
                ],
            ],
            [
                'version' => '1.1.0',
                'released_at' => '2026-09-11',
                'category' => 'added',
                'sort_order' => 2,
                'translations' => [
                    'en' => 'Language switcher for signed-in users. Guests and error pages follow the default locale.',
                    'es' => 'Selector de idioma para usuarios autenticados. Invitados y páginas de error usan el idioma por defecto.',
                ],
            ],
            [
                'version' => '1.1.0',
                'released_at' => '2026-09-11',
                'category' => 'added',
                'sort_order' => 3,
                'translations' => [
                    'en' => 'Changelog with English and Spanish entries.',
                    'es' => 'Changelog con entradas en inglés y español.',
                ],
            ],
            [
                'version' => '1.1.0',
                'released_at' => '2026-09-11',
                'category' => 'changed',
                'sort_order' => 1,
                'translations' => [
                    'en' => 'Role and permission labels use translations instead of slugs.',
                    'es' => 'Las etiquetas de roles y permisos usan traducciones en lugar de slugs.',
                ],
            ],
            [
                'version' => '1.1.0',
                'released_at' => '2026-09-11',
                'category' => 'fixed',
                'sort_order' => 1,
                'translations' => [
                    'en' => '404 and 419 pages now follow the current locale.',
                    'es' => 'Las páginas 404 y 419 ahora respetan el idioma actual.',
                ],
            ],
            [
                'version' => '1.1.0',
                'released_at' => '2026-09-11',
                'category' => 'technical',
                'sort_order' => 1,
                'translations' => [
                    'en' => 'Strings live in lang/{en,es}. Locale is applied on the web stack and when rendering errors.',
                    'es' => 'Los textos viven en lang/{en,es}. El idioma se aplica en el stack web y al renderizar errores.',
                ],
            ],
        ];

        foreach ($entries as $entryData) {
            $translations = $entryData['translations'];
            unset($entryData['translations']);

            $entry = ChangelogEntry::query()->updateOrCreate(
                [
                    'version' => $entryData['version'],
                    'category' => $entryData['category'],
                    'sort_order' => $entryData['sort_order'],
                ],
                $entryData
            );

            foreach ($translations as $locale => $description) {
                $entry->translations()->updateOrCreate(
                    ['locale' => $locale],
                    ['description' => $description]
                );
            }
        }
    }
}
