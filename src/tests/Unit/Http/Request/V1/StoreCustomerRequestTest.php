<?php

namespace Tests\Unit\Http\Resources\V1;

use App\Http\Requests\V1\StoreCustomerRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Tests\TestCase;

/**
 *
 * Run test for this file
 * php artisan test tests/Unit/Http/Resources\V1
 * php artisan test --filter StoreCustomerRequestTest
 *
 * @package Tests\Unit\Http\Resources
 */
class StoreCustomerRequestTest extends TestCase
{
    public function testRules()
    {
        $store_customer_request = new StoreCustomerRequest();

        $response = $store_customer_request->rules();

        $this->assertIsArray($response);
    }

    public function testAuthorize()
    {
        $store_customer_request = new StoreCustomerRequest();

        $response = $store_customer_request->authorize();

        $this->assertIsBool($response);
    }
}
