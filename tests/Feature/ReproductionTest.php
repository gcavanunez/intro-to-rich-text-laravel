<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Facades\Socialite;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ReproductionTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function nasty_var_scenario(): void
    {
        $response = $this->get(route('repro'));

        $response->assertRedirect(route('login'));
    }

    #[Test]
    public function redirect_back_can_create_user(): void
    {
        $user = $this->mock('SocialiteProviders\Saml2\User', function (MockInterface $mock) {
            $mock->id = '12345';
            $mock->first_name = 'Jax';
            $mock->last_name = 'Doe';
            $mock->email = 'jaxdoe@intxlog.com';
        });

        $provider = $this->mock('Laravel\Socialite\Contracts\Provider', function (MockInterface $mock) use ($user) {
            $mock
                ->shouldReceive('stateless')
                ->andReturnSelf()
                ->shouldReceive('user')
                ->andReturn($user);
        });

        Socialite::shouldReceive('driver')
            ->with('saml2')
            ->andReturn($provider);

        $this->post(route('repro-saml'))
            ->assertRedirect('/dashboard');

        $this->assertDatabaseHas('users', [
            'name' => 'Jax Doe',
            'email' => 'jaxdoe@intxlog.com',
        ]);
    }
}
