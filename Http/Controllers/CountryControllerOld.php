<?php

namespace Modules\Corona\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Corona\Entities\Country;
use Modules\Corona\Entities\CountryRepository;

class CountryControllerOld extends Controller
{
    public function index()
    {
        return (new Country())->repository()->all();
    }

    public function create()
    {
        $result = CountryRepository::createCountries(\request()->getParsedBody());
//            $result = request()->getParsedBody();
        return $result;
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $id = \request('id');
        CountryRepository::getById($id);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('corona::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
