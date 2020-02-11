<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProdutoRequest;
use App\Repositories\ProdutoRepositoryEloquent;
use App\Validators\ProdutoValidator;
use Illuminate\Http\Request;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;

class ProdutoController extends Controller
{
    private $repository;
    private $validator;

    public function __construct(
        ProdutoRepositoryEloquent $repository,
        ProdutoValidator $validator
    )
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        if ($request->get('perPage') == '0') {
            return response()->json(
                [
                    'data' => $this->repository->all()
                ]
            );
        }

        return response()->json(
            [
                'data' => $this->repository->with(['categorias'])->paginate($request->get('perPage') ?: 10 )
            ]
        );
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkIfCodigoExists(Request $request)
    {
        return response()->json([
            'data' => $this->repository->findWhere([
                'codigo' => $request->get('codigo'),
                ['id', '<>', $request->get('id')]
            ])->count()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ProdutoRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(ProdutoRequest $request)
    {
        try {
            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $produto = $this->repository->create($request->all());

            $produto->categorias()->sync($request->get('categorias') ?: []);

            $produto->load('categorias');

            return response()->json(
                [
                    'data' => $produto
                ]
            );

        } catch (ValidatorException $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessageBag()
            ], 403);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        return response()->json([
            'data' => $this->repository->with(['categorias'])->find($id)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ProdutoRequest $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(ProdutoRequest $request, $id)
    {
        try {
            $input = $request->all();

            $input['id'] = $id;

            $this->validator->with($input)->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $produto = $this->repository->update($input, $id);

            $produto->categorias()->sync($request->get('categorias') ?: []);

            $produto->load('categorias');

            return response()->json(
                [
                    'data' => $produto
                ]
            );
        } catch (ValidatorException $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessageBag()
            ], 403);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $produto = $this->repository->with(['categorias'])->find($id);

        $produto->categorias()->detach();

        return response()->json([
            'data' => $produto->delete()
        ]);
    }
}
