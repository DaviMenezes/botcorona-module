<?php

namespace Modules\Corona\Http\Controllers;

use Modules\Corona\Entities\Country;
use Modules\Corona\Entities\CountryModel;
use Modules\ModuleBase\Http\Controllers\BaseController;

class CountryController extends BaseController
{
    public function createCountries()
    {
        $countries = \request()->all();
        foreach ($countries as $country) {
            (new Country())->repository()->create($country);
        }
        return '{"status":"ok"}';
    }

    /**
     * @inheritDoc
     */
    public function modelClass()
    {
        return CountryModel::class;
    }

    /**
     * @inheritDoc
     */
    public function domainClass()
    {
        return Country::class;
    }
}
