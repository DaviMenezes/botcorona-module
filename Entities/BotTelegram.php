<?php

namespace Modules\Corona\Entities;

use Carbon\Carbon;
use Telegram;


class BotTelegram
{
    public static function sendMessage($status = null)
    {
        $test = false;
        if ($status['country_name'] == 'Brazil') {
            $chat_id = $test? Channels::getUbuntuChatId() : Channels::coronaInfoNewsChatId();
        } elseif ($status['country_name'] == 'Italy') {
            $chat_id = $test? Channels::getUbuntuChatId() : Channels::coronavirus_italy_dvi();
        } else {
            return '{"status":"ok", "message": "nao tem chat para este pais"}';
        }
        $death_prediction_sub0 = $status['deaths'];
        $death_prediction_sub1 = (new Status)->repository()->getDeathsInSubDay(1, $status['country_id']);
        $death_prediction_sub2 = (new Status)->repository()->getDeathsInSubDay(2, $status['country_id']);
        $death_prediction_sub3 = (new Status)->repository()->getDeathsInSubDay(3, $status['country_id']);

        $param0 = $death_prediction_sub0 - $death_prediction_sub1;
        $param1 = $death_prediction_sub1 - $death_prediction_sub2;
        $param2 = $death_prediction_sub2 - $death_prediction_sub3;
        if ($param0 >= $param1) {
            $death_prediction = $param0 + ($param0 - $param1) + ($param1 - $param2);
        }
        else {
            $death_prediction = $param0 + ($param1 - $param0) + ($param1 - $param2);
        }

        $telegram = new Telegram(env('TELEGRAM_BOT_KEY'));


        $content_file = file_get_contents('https://covid19.mathdro.id/api/og');
        if ($content_file !== false) {
            file_put_contents(storage_path('app/public/img.png'), $content_file);
        }
        $img = new \CURLFile(storage_path('app/public/img.png'), 'image/png', 'status_'.$status['last_update']);
        $content = array('chat_id' => $chat_id, 'photo' => $img );
        $telegram->sendPhoto($content);

        $message = "Corona vírus ".$status['country_name']." ".date('d/m/Y').":\n";
        $message .= "Última atualização: ".$status['last_update']."\n";
        if (isset($status['confirmed'])) {
            $message .= "Confirmados: ".$status['confirmed']." \n";
        }
        if (isset($status['recovered'])) {
            $message .= "Recuperados: ".$status['recovered']." \n";
        }
        if (isset($status['deaths'])) {
            $message .= "Mortes: ".$status['deaths'];
        }
        if (Carbon::now()->hour <= -1 and is_integer($death_prediction)) {
            $message .= " (estimativa para hoje): +- $death_prediction";
        }
        $message .= "\n";
        if (isset($status['active'])) {
            $message .= "Contaminados: ".$status['active']." \n\n";
        }

        $death_rank1 = (new Status)->repository()->getDeathRank();
        $death_rank2 = (new Status)->repository()->getDeathRank([$death_rank1->country_id]);
        $death_rank3 = (new Status)->repository()->getDeathRank([$death_rank1->country_id, $death_rank2->country_id]);

        $message .= "Países mais afetados: \n";
        $message .= $death_rank1->country()->name.": Confirmados:". self::getFormatedNumber($death_rank1->confirmed)." Recuperados: ". self::getFormatedNumber($death_rank1->recovered).' Mortes: '.self::getFormatedNumber($death_rank1->deaths) ."\n";
        $message .= $death_rank2->country()->name.": Confirmados:". self::getFormatedNumber($death_rank2->confirmed)." Recuperados: ". self::getFormatedNumber($death_rank2->recovered).' Mortes: '.self::getFormatedNumber($death_rank2->deaths)."\n";
        $message .= $death_rank3->country()->name.": Confirmados:". self::getFormatedNumber($death_rank3->confirmed)." Recuperados: ". self::getFormatedNumber($death_rank3->recovered).' Mortes: '.self::getFormatedNumber($death_rank3->deaths)."\n\n";

        self::setRandonMessage($message);

        $message .= "=========================\n";

        $content = array('chat_id' => $chat_id, 'text' => $message);
        $result = $telegram->sendMessage($content);
        $result['death_prediction'] = $death_prediction;

        return json_encode($result);
    }

    protected static function setRandonMessage(string &$message)
    {
        $msg = "Um fato histórico: A gripe já matou mais que as 2 grandes guerras juntas. Cuidado faça a sua parte.\n";
        $msg .= "Não é vergonhoso usar máscara ao manter contato com pessoas. Vergonhoso é não se responsabilisar.\n";
        $msg .= "Em caso de: Nariz escorrendo, Dor de garganta, Tosse, Febre, Dificuldade em respirar (casos graves)\n";
        $msg .= "Procure aconselhamento médico urgente.\n";;
        $msgs[] = $msg;
        $msgs[] = "Não subestime os números. Não vai querer fazer parte da estatística\n";
        $msgs[] = "Lembre-se de lavar as mãos. Se tiver contato com outras pessoas seja precavido. Não é só você que corre risco.\n";
        $msgs[] = "Ajude alguém a informação está a seu favor.\n";
        $msgs[] = "Em caso de sintomas não corra ao médico, primeiro verifique se eles são compatíveis.\n";
        $msgs[] = "Os mais afetados não são os idosos, são os que tem maior contato com o vírus. Previna-se. Mesmo que não apresente sintomas não significa que você não esteja infectado. Pense também nos outros ao seu redor.\n";
        $msgs[] = "Antes de sair correndo riscos, pense que mesmo que o vírus em você não surta efeito, antes dele desaperecer dentro de você ele pode ter sido passado a alguém próximo a você e que pode não ter a mesma sorte.\n";
        $message .= $msgs[random_int(0, count($msgs) -1)];
    }

    protected static function getFormatedNumber($number): string
    {
        return number_format($number, 0, ',', '.');
    }

}
