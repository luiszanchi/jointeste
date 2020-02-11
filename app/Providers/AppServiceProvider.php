<?php

namespace App\Providers;

use App\Repositories\CategoriaRepository;
use App\Repositories\CategoriaRepositoryEloquent;
use App\Repositories\ProdutoRepository;
use App\Repositories\ProdutoRepositoryEloquent;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(CategoriaRepository::class, CategoriaRepositoryEloquent::class);
        $this->app->bind(ProdutoRepository::class, ProdutoRepositoryEloquent::class);
    }
}
