<?php

namespace Modules\Corona\Http\Controllers;

use Domain\Corona\BotTelegram;
use Illuminate\Http\Client\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Corona\Entities\StatusModel;
use Modules\Corona\Entities\StatusRepository;
use stdClass;

class StatusControllerOld extends Controller
{
    public function index()
    {
        $order = request('order');
        $direction = \request('direction');
        $limit = \request('limit') ?? 10;
        $statusBrasil = StatusModel::query();
        if ($order) {
            $statusBrasil->orderBy($order, $direction);
        }
        if ($limit) {
            $statusBrasil->limit($limit);
        }
        $statusBrasil->paginate($limit)->items();
    }

    public function create()
    {
        return (new StatusRepository)->fill(request()->all())->update();
    }

    public function total(Request $request)
    {
        return (new StatusRepository)->getTotal();
    }

    public function update()
    {
        return (new StatusRepository)->fill(request()->all())->update();
    }

    public function byPage()
    {
        $page = request('page', 1);
        $limit = request('limit', 10);

        $total = StatusModel::query()->count();
        $offset = ($page*$limit) - $limit;
        $query = StatusModel::query();
        if (request('page')) {
            $query->offset($offset)->take($limit);
        }
        $order = request('order');
        if ($order) {
            $query->orderBy($order, request('direction', 'asc'));
        }
        $status = $query->get()->toJson();
        $result = new stdClass();
        $result->items = $status;
        $result->total = $total;
        return $result;
    }

    public function countryDate()
    {
        $result = StatusModel::query()
            ->where('country_id', '=', request('country_id'))
            ->whereDay('last_update', '=', date('d', strtotime(request('date'))));
        if ($result->count() > 0) {
            $result = $result->get()->last()->toJson();
            return $result;
        }
    }

    public function countryById()
    {
        return (new StatusRepository)->getByCountryId(request('id'));
    }

    public function notify()
    {
        $data = request()->all();
//            $data["country_id"] = "23";
//            $data["last_update"] = "2020-03-25 00:34:36";
//            $data["confirmed"] = "2247";
//            $data["recovered"] = "2";
//            $data["deaths"] = "46";
//            $data["active"] = "2199";
//            $data["id"] = 218;
//            $data["country_name"] = "Brazil";
//            $data["telegram_message_id"] = 86;
//            $data["telegram_chat_id"] = -1001442915606;

        return BotTelegram::sendMessage($data);
    }
}
