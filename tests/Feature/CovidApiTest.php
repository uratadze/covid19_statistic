<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Str;
use Tests\TestCase;

class CovidApiTest extends TestCase
{
    const TEST_USER_EMAIL = 'unit-test@example.com';
    const TEST_USER_PASSWORD = 'testtest';

    public function test_registration_new_test_user()
    {
        $response = $this->postJson(route('user.registration'), [
            'name' => 'test',
            'email' => Str::random(10) . '@gmail.com',
            'password' => 'testtest',
            'password_confirmation' => 'testtest'
        ]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'success',
            'data' => [
                'user_name',
                'user_email',
                'user_token'
            ]
        ]);
    }

    public function test_user_login()
    {
        User::firstOrCreate([
            'email' => self::TEST_USER_EMAIL
        ], [
            'name' => 'test',
            'email_verified_at' => now(),
            'password' => bcrypt(self::TEST_USER_PASSWORD)
        ]);

        $response = $this->postJson(
            route('user.login'),
            [
                'email' => self::TEST_USER_EMAIL,
                'password' => self::TEST_USER_PASSWORD
            ]
        );
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'success',
            'data' => [
                'user_token'
            ]
        ]);
    }

    public function test_country_data()
    {
        $token = $this->getTestUserToken();
        $response = $this->withHeader('Authorization', "Bearer $token")->postJson(route('country.data'));
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'success',
            'data' => [
                '*' => [
                    'id',
                    'code',
                    'name',
                    'statistic' => [
                        'confirmed',
                        'recovered',
                        'critical',
                        'deaths',
                        'created_at',
                        'updated_at'
                    ]
                ]
            ]
        ]);
    }

    public function test_statistic_summary()
    {
        $token = $this->getTestUserToken();
        $response = $this->postJson(
            route('country.statistics'),
            [
                'codes' => ['GE', "AF", "BR"]
            ]
        );
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'success',
            'data' => [
                'confirmed',
                'recovered',
                'critical',
                'deaths',
                'created_at',
                'updated_at'
            ]
        ]);
    }

    public function getTestUserToken()
    {
        $response = $this->postJson(route('user.login'), ['email' => self::TEST_USER_EMAIL, 'password' => self::TEST_USER_PASSWORD]);
        return $response->json()['data']['user_token'];
    }
}
