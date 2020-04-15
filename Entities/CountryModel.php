<?php

namespace Modules\Corona\Entities;

use Modules\ModuleBase\Entites\BaseModel;

/**
 * @property $id
 * @property $name
 * @property $iso2
 * @property $iso3
 */
class CountryModel extends BaseModel
{
    protected $connection = 'corona';
    protected $table = 'countries';
    protected $fillable = ['id', 'name', 'iso2', 'iso3'];
}
