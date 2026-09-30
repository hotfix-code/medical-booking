<?php

namespace Database\Seeders;

use App\Models\ChangelogEntry;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use RuntimeException;

class ChangelogSeeder extends Seeder
{
    public function run(): void
    {
        if (!app()->environment('local') || ! config('changelog.enabled')) {
            return;
        }

        if (!Schema::hasTable('changelog_entries') || !Schema::hasTable('changelog_entry_translations')) {
            throw new RuntimeException(__('changelog.setup.migration_required'));
        }

        ChangelogEntry::query()->delete();

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
            [
                'version' => '1.1.1',
                'released_at' => '2026-09-11',
                'category' => 'fixed',
                'sort_order' => 1,
                'translations' => [
                    'en' => 'Remember me on the login screen now persists the session after the browser is closed.',
                    'es' => '“Mantener sesión” en el login ahora conserva la sesión al cerrar el navegador.',
                ],
            ],
            [
                'version' => '1.1.2',
                'released_at' => '2026-09-12',
                'category' => 'fixed',
                'sort_order' => 1,
                'translations' => [
                    'en' => 'Select2, Flatpickr, FullCalendar and Choices now follow the signed-in language (empty results, calendar days, doctor specialties).',
                    'es' => 'Select2, Flatpickr, FullCalendar y Choices ahora siguen el idioma de la sesión (sin resultados, días del calendario, especialidades del doctor).',
                ],
            ],
            [
                'version' => '1.1.3',
                'released_at' => '2026-09-15',
                'category' => 'fixed',
                'sort_order' => 1,
                'translations' => [
                    'en' => 'Confirming an appointment no longer fails, a doctor cannot be double-booked, and only cancelled appointments release their slot. The create form no longer offers occupied slots.',
                    'es' => 'Confirmar una cita ya no falla, un médico no puede tener doble reserva y solo las citas canceladas liberan su cupo. El formulario de creación ya no ofrece cupos ocupados.',
                ],
            ],
            [
                'version' => '1.2.0',
                'released_at' => '2026-09-23',
                'category' => 'added',
                'sort_order' => 1,
                'translations' => [
                    'en' => 'Role-based dashboard with KPIs, charts, and live appointment, patient, and specialty summaries.',
                    'es' => 'Dashboard adaptado al rol, con indicadores, gráficos y resúmenes dinámicos de citas, pacientes y especialidades.',
                ],
            ],
            [
                'version' => '1.3.0',
                'released_at' => '2026-09-30',
                'category' => 'added',
                'sort_order' => 1,
                'translations' => [
                    'en' => 'Customizable sidebar appearance, refreshed app branding, and matching login colors.',
                    'es' => 'Apariencia personalizable del sidebar, actualización de la marca de la aplicación y colores acordes en el login.',
                ],
            ],
            [
                'version' => '1.3.0',
                'released_at' => '2026-09-30',
                'category' => 'added',
                'sort_order' => 2,
                'translations' => [
                    'en' => 'Password visibility controls are available in login, profile, and create/edit forms.',
                    'es' => 'Hay controles para mostrar u ocultar la contraseña en el login, el perfil y los formularios de creación y edición.',
                ],
            ],
            [
                'version' => '1.3.0',
                'released_at' => '2026-09-30',
                'category' => 'changed',
                'sort_order' => 1,
                'translations' => [
                    'en' => 'Profile language labels are translated, with English as the fallback when no locale is set.',
                    'es' => 'Las etiquetas de idioma del perfil se traducen y usan inglés como alternativa cuando no hay idioma configurado.',
                ],
            ],
        ];

        foreach ($entries as $entryData) {
            $translations = $entryData['translations'];
            unset($entryData['translations']);

            $entry = ChangelogEntry::query()->create($entryData);

            foreach ($translations as $locale => $description) {
                $entry->translations()->create([
                    'locale' => $locale,
                    'description' => $description,
                ]);
            }
        }
    }
}
