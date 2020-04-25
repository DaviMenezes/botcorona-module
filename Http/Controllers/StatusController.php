<?php

namespace Modules\Corona\Http\Controllers;

use Modules\Corona\Entities\BotTelegram;
use Illuminate\Routing\Controller;
use Modules\Corona\Entities\Status;
use Modules\Corona\Entities\StatusModel;

class StatusController extends Controller
{
    public function total()
    {
        return (new Status())->repository()->getTotal();
    }

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
        $repo = (new Status)->repository();
        return $repo->create(request()->all());
    }

    public function update()
    {
        return (new Status)->repository()->update(request()->all());
    }

    public function byPage()
    {
        $page = request('page', 1);
        $limit = request('limit', 10);
        $order = request('order', 'id');
        $direction = request('direction', 'asc');

        $result = (new Status())->repository()->byPage($page, $limit, $order, $direction);
        return $result;
    }

    public function countryDate()
    {
        $country_id = request('country_id');
        $date = request('date');
        return (new Status())->repository()->countryDate($country_id, $date);
    }

    public function countryById()
    {
        return (new Status)->repository()->getByCountryId(request('id'));
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
