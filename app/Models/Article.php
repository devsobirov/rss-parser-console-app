<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    /**
     * Метод для получения ссылки самого последнего новостя;
     * Возвращает null если моделей пока не существует;
     *
     * @return string|null
     */
    public static function getLastItemsLink()
    {
        $link = null;
        $latest = self::orderByDesc('id')->first();
        if ($latest) {
            $link = $latest->link;
        }
        return $link;
    }
}
