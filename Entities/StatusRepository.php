<?php
namespace Modules\Corona\Entities;

use Carbon\Carbon;
use Illuminate\Http\Response;
use Modules\ModuleBase\Repository\RepositoryBase;
use stdClass;

class StatusRepository extends RepositoryBase
{
    public function instance()
    {
        return new StatusModel();
    }

    public function getDeathsInSubDay(int $sub, $country_id)
    {
        return $this->exec(function () use ($sub, $country_id) {
            $subDay = Carbon::now()->subDays($sub);
            $month = $subDay->month;

            $query = StatusModel::query()
                ->where('country_id', '=', $country_id)
                ->whereMonth('last_update', '=', $month)
                ->whereDay('last_update', '=', $subDay->day)
                ->latest("deaths")->limit(1)->first();
            return $query->deaths ?? 0;
        });
    }

    public function getByCountryId($id)
    {
        return $this->exec(function () use ($id) {
            return $this->domain->modelClass()::query()->where('country_id', '=', $id)->get()->all();
        });
    }

    public function getTotal()
    {
        return $this->exec(function () {
            return StatusModel::count();
        });
    }

    public function page($page, $limit = 10, $order = 'id', $direction= 'asc')
    {
        return $this->exec(function () use ($page, $limit, $order, $direction) {
            $total = StatusModel::query()->count();
            $offset = ($page*$limit) - $limit;
            $query = StatusModel::query();

            $query->offset($offset)->take($limit);

            if ($order) {
                $query->orderBy($order, $direction);
            }
            $status = $query->get()->all();
            $result = new stdClass();
            $result->items = $status;
            $result->total = $total;

            return $result;
        });
    }

    public function getCountryByLastDate()
    {
        return $this->exec(function () {
            $result = StatusModel::query()
                ->where('country_id', '=', request('country_id'))
                ->whereDay('last_update', '=', date('d', strtotime(request('last_date'))));
            return $result->get()->last();
        });
    }

    public function byPage(string $page, string $limit, string $order, string $direction)
    {
        return $this->exec(function () use ($page, $limit, $order, $direction) {
            $total = StatusModel::query()->count();
            $offset = ($page*$limit) - $limit;
            $query = StatusModel::query();
            if ($page) {
                $query->offset($offset)->take($limit);
            }
            if ($order) {
                $query->orderBy($order, $direction);
            }
            $status = $query->get();
            $result['items'] = $status;
            $result['total'] = $total;

            return $result;
        });
    }

    public function countryDate($country_id, $date)
    {
        return $this->exec(function () use ($country_id, $date) {
            $result = StatusModel::query()
                ->where('country_id', '=', $country_id)
                ->whereDay('last_update', '=', date('d', strtotime($date)));
            if ($result->count() > 0) {
                return $result->get()->last();
            }
            return null;
        });
    }

    /**
     * @param array|null $country_ids
     * @return Response
     */
    public function getDeathRank($country_ids = null)
    {
        return $this->exec(function () use ($country_ids) {
            $query = $this->domain->modelClass()::query()->select(['country_id', 'confirmed', 'recovered', 'deaths']);
            if ($country_ids) {
                $query->whereNotIn('country_id', $country_ids);
            }
            return $query->orderBy('deaths', 'desc')
            ->limit(1)->first();
        });
    }
}
