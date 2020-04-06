<?php

namespace PockDoc\Http\Controllers\Api;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use PockDoc\Http\Controllers\Controller;
use PockDoc\Http\Requests\Api\StoreAddressRequest;
use PockDoc\Models\Address;

class AddressController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return $request->user()->addresses;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request|StoreAddressRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAddressRequest $request)
    {
        return \DB::transaction(function() use ($request) {
            $address = Address::create(array_merge($request->all(), [
                'user_id' => $request->user()->id
            ]));
            return $address;
        });
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param Address $address
     * @return Address
     * @internal param int $id
     */
    public function show(Request $request, Address $address)
    {
        if($address->user_id != $request->user()->id) {
            throw new ModelNotFoundException('Card not found');
        }
        return $address;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request|StoreAddressRequest $request
     * @param Address $address
     * @return \Illuminate\Http\Response
     */
    public function update(StoreAddressRequest $request, Address $address)
    {
        if($address->user_id != $request->user()->id) {
            throw new ModelNotFoundException('Card not found');
        }
        return \DB::transaction(function() use ($request, $address) {
            $address->update($request->all());
            return $address;
        });
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param Address $address
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function destroy(Request $request, Address $address)
    {
        if($address->user_id != $request->user()->id) {
            throw new ModelNotFoundException('Card not found');
        }
        $address->delete();
        return null;
    }
}
