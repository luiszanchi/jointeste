<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Categoria.
 *
 * @package namespace App\Entities;
 */
class Categoria extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'descricao'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function produtos()
    {
        return $this->belongsToMany('App\Entities\Produto', 'produto_categoria', 'categoria_id', 'produto_id');
    }
}
