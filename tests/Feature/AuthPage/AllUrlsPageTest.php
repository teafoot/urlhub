<?php

namespace Tests\Feature\AuthPage;

use App\Models\Url;
use Tests\TestCase;
use Vinkla\Hashids\Facades\Hashids;

class AllUrlsPageTest extends TestCase
{
    protected function hashIdRoute($routeName, $url_id)
    {
        $hashids = Hashids::connection(Url::class);

        return route($routeName, $hashids->encode($url_id));
    }

    /**
     * @test
     * @group f-allurl
     */
    public function auAdminCanAccessThisPage()
    {
        $response = $this->actingAs($this->adminUser())
            ->get(route('dashboard.allurl'));

        $response->assertOk();
    }

    /**
     * @test
     * @group f-allurl
     */
    public function auNormalUserCantAccessThisPage()
    {
        $response = $this->actingAs($this->normalUser())
            ->get(route('dashboard.allurl'));

        $response->assertForbidden();
    }

    /**
     * @test
     * @group f-allurl
     */
    public function auAdminCanDelete()
    {
        $url = Url::factory()->create();

        $response = $this->actingAs($this->adminUser())
            ->from(route('dashboard.allurl'))
            ->get($this->hashIdRoute('dashboard.allurl.su_delete', $url->id));

        $response->assertRedirectToRoute('dashboard.allurl')
            ->assertSessionHas('flash_success');

        $this->assertCount(0, Url::all());
    }

    /**
     * @test
     * @group f-allurl
     */
    public function auNormalUserCantDelete()
    {
        $url = Url::factory()->create();

        $response = $this->actingAs($this->normalUser())
            ->from(route('dashboard.allurl'))
            ->get($this->hashIdRoute('dashboard.allurl.su_delete', $url->id));

        $response->assertForbidden();
        $this->assertCount(1, Url::all());
    }
}
