<?php

namespace App\Providers;

use App\Repositories\CategoriaRepository;
use App\Repositories\ProdutoRepository;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class CustomValidationRuleProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('array_categorias', function ($attribute, $value, $parameters, $validator) {
            // Services
            $categoriaRepository = app(CategoriaRepository::class);

            return count($value) === $categoriaRepository->findWhereIn('id', $value)->count();
        }, 'The :attribute has a id who doesn\'t exists.');

        Validator::extend('non_negative_number', function ($attribute, $value, $parameters, $validator) {
            return (is_float($value) || is_int($value) || ctype_digit($value)) && (float) $value >= 0;
        }, 'The :attribute is negative.');

        Validator::extend('unique_codigo_except_id', function ($attribute, $value, $parameters, $validator) {
            $produtoRepository = app(ProdutoRepository::class);

            return ! (Boolean) $produtoRepository->findWhere([
                'codigo' => $value,
                ['id', '<>', $validator->getData()['id']]
            ])->count();
        }, 'The :attribute has already been taken.');
    }
}
