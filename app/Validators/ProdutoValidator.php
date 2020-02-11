<?php

namespace App\Validators;

use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;

/**
 * Class ProdutoValidatorValidator.
 *
 * @package namespace App\Validators;
 */
class ProdutoValidator extends LaravelValidator
{
    /**
     * Validation Rules
     *
     * @var array
     */
    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
            'descricao' => 'required|string|max:255',
            'valor' => 'required|numeric|non_negative_number',
            'codigo' => 'required|numeric|integer|unique:App\Entities\Produto,codigo',
            'categorias' => 'array|array_categorias',
        ],
        ValidatorInterface::RULE_UPDATE => [
            'descricao' => 'required|string|max:255',
            'valor' => 'required|numeric|non_negative_number',
            'codigo' => 'required|numeric|integer|unique_codigo_except_id',
            'categorias' => 'array|array_categorias',
        ],
    ];
}
