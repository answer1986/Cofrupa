<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value_es',
        'value_en',
        'section',
        'type',
        'description'
    ];

    /**
     * Obtener el contenido en el idioma actual
     */
    public function getValue($lang = null)
    {
        $lang = $lang ?: app()->getLocale();
        
        if ($lang === 'es') {
            return $this->value_es ?: $this->value_en;
        } else {
            return $this->value_en ?: $this->value_es;
        }
    }

    /**
     * Obtener contenido por clave
     */
    public static function getByKey($key, $lang = null)
    {
        $content = self::where('key', $key)->first();
        
        if (!$content) {
            return $key; // Retorna la clave si no encuentra el contenido
        }
        
        return $content->getValue($lang);
    }
}
