<?php

namespace App\Http\Controllers;

use App\Repositories\CategoriaRepositoryEloquent;
use App\Validators\CategoriaValidator;
use Illuminate\Http\Request;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;

class CategoriaController extends Controller
{
    private $repository;
    private $validator;

    public function __construct(
        CategoriaRepositoryEloquent $repository,
        CategoriaValidator $validator
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
                'data' => $this->repository->with(['produtos'])->paginate($request->get('perPage') ?: 10 )
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $produto = $this->repository->create($request->all());

            $produto->produtos()->sync($request->get('produtos') ?: []);

            $produto->load('produtos');

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
            'data' => $this->repository->with(['produtos'])->find($id)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        try {
            $input = $request->all();

            $input['id'] = $id;

            $this->validator->with($input)->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $produto = $this->repository->update($input, $id);

            $produto->produtos()->sync($request->get('produtos') ?: []);

            $produto->load('produtos');

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
        $categoria = $this->repository->with(['produtos'])->find($id);

        $categoria->produtos()->detach();

        return response()->json([
            'data' => $categoria->delete()
        ]);
    }
}
