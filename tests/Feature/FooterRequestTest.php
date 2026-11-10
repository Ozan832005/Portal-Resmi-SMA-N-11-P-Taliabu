<?php

namespace Tests\Feature;

use App\Http\Requests\FooterRequest;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class FooterRequestTest extends TestCase
{
    public function test_create_request_returns_logo_validation_error_when_logo_is_missing(): void
    {
        $this->registerValidationRoutes();

        $response = $this->from('/')->post('/_test/footer', $this->validFooterFields());

        $response
            ->assertRedirect('/')
            ->assertSessionHasErrors(['logo']);

        $this->assertSame(
            'Logo Sekolah tidak boleh kosong',
            $response->getSession()->get('errors')->getBag('default')->first('logo'),
        );
    }

    public function test_update_request_accepts_existing_logo_when_no_new_logo_is_uploaded(): void
    {
        $this->registerValidationRoutes();

        $this->put('/_test/footer', $this->validFooterFields())
            ->assertOk()
            ->assertSeeText('valid');
    }

    private function registerValidationRoutes(): void
    {
        Route::middleware('web')->group(function (): void {
            Route::post('/_test/footer', function (FooterRequest $request) {
                return response('valid');
            });

            Route::put('/_test/footer', function (FooterRequest $request) {
                return response('valid');
            });
        });
    }

    /**
     * @return array<string, string>
     */
    private function validFooterFields(): array
    {
        return [
            'facebook' => 'sma11',
            'instagram' => 'sma11',
            'twitter' => 'sma11',
            'youtube' => 'sma11',
            'whatsapp' => '08123456789',
            'telp' => '090123456',
            'email' => 'admin@example.test',
            'desc' => 'SMA Negeri 11 Pulau Taliabu.',
        ];
    }
}
