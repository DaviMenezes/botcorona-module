<?php

namespace Modules\Corona\Entities;

/**
 * @author     Davi Menezes
 * @copyright  Copyright (c) 2020. (davimenezes.dev@gmail.com)
 * @see https://github.com/DaviMenezes
 */
class Channels
{
    public static function getUbuntuChatId()
    {
        return env('TELEGRAM_CHAT_UBUNTU');
    }
    public static function coronaInfoNewsChatId()
    {
        return env('TELEGRAM_CHAT_CORONA_INFO_NEWS');
    }

    public static function coronavirus_italy_dvi()
    {
        return env('TELEGRAM_CHAT_CORONA_ITALY_DVI');
    }
}
