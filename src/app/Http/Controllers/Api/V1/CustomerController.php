<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreCustomerRequest;
use App\Http\Requests\V1\UpdateCustomerRequest;
use App\Http\Resources\V1\CustomerCollection;
use App\Http\Resources\V1\CustomerResource;
use App\Models\Customer;
use App\Filters\V1\CustomerFilter;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Field used to wrap error messages. Similar to how we use "data" to wrap successful messages
     * that return data, but for errors.
     *
     * @var string
     */
    const FIELD_ERRORS = 'errors';

    /**
     * Field used to wrap message error.
     *
     * @var string
     */
    const FIELD_MESSAGE = 'message';

    /**
     * Field used to wrap body in message error.
     *
     * @var string
     */
    const FIELD_BODY = 'body';

    /**
     * Field used to wrap code in message error.
     *
     * @var string
     */
    const FIELD_CODE = 'code';

    /**
     * Display a listing of the resource.
     *
     * @return CustomerCollection
     */
    public function index(Request $request): CustomerCollection
    {
        $filter = new CustomerFilter;
        $filterItems = $filter->transform($request);

        $includeInvoices = $request->query('includeInvoices');

        $customers = Customer::where($filterItems);

        if ($includeInvoices) {
            $customers = $customers->with('invoices');
        }

        return new CustomerCollection($customers ->paginate()->appends($request->query()));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCustomerRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCustomerRequest $request)
    {
        try {
            return new CustomerResource(Customer::create($request->all()));

        } catch (\Throwable $e) {

            return response()->json([
                self::FIELD_MESSAGE => 'There was an error while creating a client',
                self::FIELD_CODE    => $e->getCode(),
                self::FIELD_ERRORS  => [self::FIELD_BODY => $request->all()]
            ], 500);

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        $includeInvoices = request()->query('includeInvoices');

        if ($includeInvoices) {
            return new CustomerResource($customer->loadMissing('invoices'));
        }

        return (new CustomerResource($customer));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCustomerRequest  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        try {
            $customer->update($request->all());

            return response()->json([], 204);

        } catch (\Throwable $e) {

            return response()->json([
                self::FIELD_MESSAGE => 'There was an error while creating a client',
                self::FIELD_CODE    => $e->getCode(),
                self::FIELD_ERRORS  => [self::FIELD_BODY => $request->all()]
            ], 500);

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        //
    }
}
