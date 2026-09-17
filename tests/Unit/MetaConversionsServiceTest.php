<?php

namespace Tests\Unit;

use App\Services\MetaConversionsService;
use Tests\TestCase;

class MetaConversionsServiceTest extends TestCase
{
    protected MetaConversionsService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new MetaConversionsService();
    }

    /**
     * Test basic string hashing helper (email, name, city, state).
     */
    public function test_string_hashing(): void
    {
        $input = "  TestEmail@accra.COM ";
        $expected = hash('sha256', 'testemail@accra.com');
        
        $this->assertEquals($expected, $this->service->hashString($input));
    }

    /**
     * Test phone number hashing helper (should strip non-digits).
     */
    public function test_phone_hashing(): void
    {
        $input = " +233 (0) 56-212-634 ";
        $expected = hash('sha256', '233056212634');
        
        $this->assertEquals($expected, $this->service->hashPhone($input));
    }

    /**
     * Test gender normalization and hashing.
     */
    public function test_gender_hashing(): void
    {
        $expectedMale = hash('sha256', 'm');
        $expectedFemale = hash('sha256', 'f');

        $this->assertEquals($expectedMale, $this->service->hashGender('Male'));
        $this->assertEquals($expectedMale, $this->service->hashGender('m'));
        $this->assertEquals($expectedFemale, $this->service->hashGender('Female'));
        $this->assertEquals($expectedFemale, $this->service->hashGender('f'));
    }

    /**
     * Test country normalization and hashing.
     */
    public function test_country_hashing(): void
    {
        $expectedGhana = hash('sha256', 'gh');
        $expectedNigeria = hash('sha256', 'ng');
        $expectedUS = hash('sha256', 'us');

        $this->assertEquals($expectedGhana, $this->service->hashCountry('Ghana'));
        $this->assertEquals($expectedGhana, $this->service->hashCountry('GH'));
        $this->assertEquals($expectedNigeria, $this->service->hashCountry('Nigeria'));
        $this->assertEquals($expectedUS, $this->service->hashCountry('united states'));
        $this->assertEquals($expectedUS, $this->service->hashCountry('US'));
    }

    /**
     * Test event payload builder structure.
     */
    public function test_payload_builder_structure(): void
    {
        // Mock a Pixel ID to ensure service thinks it is configured for building
        config(['services.meta.pixel_id' => '123456789']);
        config(['services.meta.test_event_code' => 'TEST_CODE']);

        $service = new MetaConversionsService();

        $userData = [
            'email' => 'john.doe@example.com',
            'phone' => '+1 (555) 0199',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'gender' => 'male',
            'city' => 'Accra',
            'country' => 'Ghana',
            'client_ip_address' => '1.2.3.4',
            'client_user_agent' => 'MockAgent',
            'fbp' => 'fbp_cookie_value',
            'fbc' => 'fbc_cookie_value',
        ];

        $customData = [
            'value' => '150.00',
            'currency' => 'USD',
        ];

        $eventId = 'mock-event-id-123';

        $payload = $service->buildPayload('Lead', $eventId, $userData, $customData);

        $this->assertArrayHasKey('data', $payload);
        $this->assertArrayHasKey('test_event_code', $payload);
        $this->assertEquals('TEST_CODE', $payload['test_event_code']);

        $event = $payload['data'][0];
        $this->assertEquals('Lead', $event['event_name']);
        $this->assertEquals($eventId, $event['event_id']);
        $this->assertEquals('website', $event['action_source']);
        $this->assertEquals($customData, $event['custom_data']);

        $user = $event['user_data'];
        $this->assertEquals('1.2.3.4', $user['client_ip_address']);
        $this->assertEquals('MockAgent', $user['client_user_agent']);
        $this->assertEquals('fbp_cookie_value', $user['fbp']);
        $this->assertEquals('fbc_cookie_value', $user['fbc']);

        // Hashed checks
        $this->assertEquals([hash('sha256', 'john.doe@example.com')], $user['em']);
        $this->assertEquals([hash('sha256', '15550199')], $user['ph']);
        $this->assertEquals([hash('sha256', 'john')], $user['fn']);
        $this->assertEquals([hash('sha256', 'doe')], $user['ln']);
        $this->assertEquals([hash('sha256', 'm')], $user['ge']);
        $this->assertEquals([hash('sha256', 'accra')], $user['ct']);
        $this->assertEquals([hash('sha256', 'gh')], $user['country']);
    }
}
