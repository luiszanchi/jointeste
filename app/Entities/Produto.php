<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Produto.
 *
 * @package namespace App\Entities;
 */
class Produto extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'descricao',
        'valor',
        'codigo'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categorias()
    {
        return $this->belongsToMany('App\Entities\Categoria', 'produto_categoria', 'produto_id', 'categoria_id');
    }

}
