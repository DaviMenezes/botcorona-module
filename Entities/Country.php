<?php

namespace Modules\Corona\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\ModuleBase\Domain\DomainBase;

/**
 * @author     Davi Menezes
 * @copyright  Copyright (c) 2020. (davimenezes.dev@gmail.com)
 * @see https://github.com/DaviMenezes
 */
class Country extends DomainBase
{
    public function repository()
    {
        return $this->repository = $this->repository ?? new CountryRepository($this);
    }

    /**
     * @inheritDoc
     */
    public function modelClass()
    {
        return CountryModel::class;
    }
}
