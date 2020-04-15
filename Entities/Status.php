<?php

namespace Modules\Corona\Entities;

use Modules\ModuleBase\Domain\DomainBase;


class Status extends DomainBase
{
    public function repository():StatusRepository
    {
        return $this->repository = $this->repository ?? new StatusRepository($this);
    }

    /**
     * @inheritDoc
     */
    public function modelClass()
    {
        return StatusModel::class;
    }
}
