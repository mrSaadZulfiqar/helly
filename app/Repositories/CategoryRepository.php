<?php

namespace App\Repositories;

use App\Models\Invoice\Category;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * Class UserRepository
 */
class CategoryRepository extends BaseRepository
{
    public $fieldSearchable = [
        'name',
    ];

    /**
     * {@inheritDoc}
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * {@inheritDoc}
     */
    public function model()
    {
        return Category::class;
    }

    public function store($input): bool
    {
        try {
            DB::beginTransaction();

            $category = Category::create($input);

            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function updateCategory(array $input, int $id): bool
    {
        try {
            DB::beginTransaction();

            $category = Category::where(['id' => $id, 'vendor_id' => auth('vendor')->user()->id])->firstOrFail();
            $category->update($input);

            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }
}
