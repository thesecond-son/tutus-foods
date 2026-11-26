<?php

namespace Tests\Feature;

use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * Regression: several resources pointed $recordTitleAttribute at columns that
 * don't exist (e.g. about_us_settings.name), so typing anything into the
 * admin's top search bar crashed with a QueryException.
 */
class FilamentGlobalSearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_global_search_runs_on_every_searchable_resource(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole(Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']));
        $this->actingAs($admin);

        Filament::setCurrentPanel(Filament::getPanel('admin'));

        $searched = 0;
        foreach (Filament::getCurrentPanel()->getResources() as $resource) {
            if (! $resource::canGloballySearch()) {
                continue;
            }

            // Throws a QueryException if the title attribute isn't a real column.
            $resource::getGlobalSearchResults('cake');
            $searched++;
        }

        $this->assertGreaterThan(0, $searched);
    }
}
