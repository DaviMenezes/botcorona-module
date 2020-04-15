<?php

namespace Modules\Corona\Entities;

use Modules\ModuleBase\Entites\BaseModel;

/**
 * @property $id
 * @property $country_id
 * @property $last_update
 * @property $confirmed
 * @property $recovered
 * @property $deaths
 * @property $active
 * @property $telegram_message_id
 * @property $telegram_chat_id
 * @property $deaths_prediction
 */
class StatusModel extends BaseModel
{
    protected $connection = 'corona';
    protected $table = 'status';
    protected $fillable = ['id', 'country_id', 'last_update', 'confirmed', 'recovered', 'deaths', 'active', 'telegram_message_id', 'telegram_chat_id', 'deaths_prediction'];
    /**
     * @var CountryModel
     */
    protected $country;

    public function country()
    {
        return $this->country = $this->country ?? CountryModel::query()->findOrFail($this->country_id);
    }
}
