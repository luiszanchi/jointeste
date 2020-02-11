<?php

namespace App\Rules;

use App\Repositories\CategoriaRepository;
use Illuminate\Contracts\Validation\Rule;

class ArrayCategorias implements Rule
{
    private $categoriaRepository;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->categoriaRepository = app(CategoriaRepository::class);
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        try {
            foreach($value as $idCategoria) {
                $categoria = $this->categoriaRepository->find($idCategoria);

                if (! $categoria) {
                    return false;
                }
            }
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The validation error message.';
    }
}
