<?php

namespace Modules\Corona\Entities;

use Modules\ModuleBase\Domain\DomainBase;

/**
 * @author     Davi Menezes
 * @copyright  Copyright (c) 2020. (davimenezes.dev@gmail.com)
 * @see https://github.com/DaviMenezes
 */
class Country extends DomainBase
{
    /**
     * @inheritDoc
     */
    public function modelClass()
    {
        return CountryModel::class;
    }
}
