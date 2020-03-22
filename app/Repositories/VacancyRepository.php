<?php

namespace App\Repositories;

use App\Vacancy;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class VacancyRepository
{
    private $vacancy;

    /**
     * Instantiate a new instance.
     *
     * @return void
     */
    public function __construct(Vacancy $vacancy)
    {
        $this->vacancy = $vacancy;
    }

    /**
     * Get vacancy query
     *
     * @return Vacancy query
     */

    public function getQuery()
    {
        return $this->vacancy;
    }

    /**
     * Find vacancy with given id or throw an error.
     *
     * @param integer $id
     * @return Vacancy
     */

    public function findOrFail($id)
    {
        $vacancy = $this->vacancy->find($id);

        if (!$vacancy) {
            throw ValidationException::withMessages(['message' => trans('vacancy.could_not_find')]);
        }

        return $vacancy;
    }

    /**
     * Paginate all vacancies using given params.
     *
     * @param array $params
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */

    public function paginate($params)
    {
        $sort_by = isset($params['sort_by']) ? $params['sort_by'] : 'created_at';
        $order = isset($params['order']) ? $params['order'] : 'desc';
        $page_length = isset($params['page_length']) ? $params['page_length'] : config('config.page_length');
        $keyword = isset($params['keyword']) ? $params['keyword'] : null;
        $status = isset($params['status']) ? $params['status'] : '';

        $query = $this->vacancy->filterByTitleOrDescription($keyword)->filterClosedVacancy($status);

        return $query->orderBy($sort_by, $order)->paginate($page_length);
    }

    /**
     * Create a new vacancy.
     *
     * @param array $params
     * @return Vacancy
     */
    public function create($params)
    {
        return $this->vacancy->forceCreate($this->formatParams($params));
    }

    /**
     * Prepare given params for inserting into database.
     *
     * @param array $params
     * @param string $type
     * @return array
     */
    private function formatParams($params, $action = 'create')
    {
        $formatted = [
            'title' => isset($params['title']) ? $params['title'] : null,
            'description' => isset($params['description']) ? $params['description'] : null,
            'address' => isset($params['address']) ? $params['address'] : null,
            'complement' => isset($params['complement']) ? $params['complement'] : null,
            'number' => isset($params['number']) ? $params['number'] : null,
            'city' => isset($params['city']) ? $params['city'] : null,
            'state' => isset($params['state']) ? $params['state'] : null,
            'salary' => isset($params['salary']) ? $params['salary'] : null,
            'zipcode' => isset($params['zipcode']) ? $params['zipcode'] : null,
        ];

        return $formatted;
    }

    /**
     * Update given vacancy.
     *
     * @param Vacancy $vacancy
     * @param array $params
     *
     * @return Vacancy
     */
    public function update(Vacancy $vacancy, $params)
    {
        $vacancy->forceFill($this->formatParams($params, 'update'))->save();

        return $vacancy;
    }

    /**
     * Delete vacancy.
     *
     * @param integer $id
     * @return bool|null
     */
    public function delete(Vacancy $vacancy)
    {
        return $vacancy->delete();
    }

    /**
     * Delete multiple vacancies.
     *
     * @param array $ids
     * @return bool|null
     */
    public function deleteMultiple($ids)
    {
        return $this->vacancy->whereIn('id', $ids)->delete();
    }

    /**
     * Toggle given vacancy status.
     *
     * @param Vacancy $vacancy
     * @param array $params
     *
     * @return Vacancy
     */
    public function toggle(Vacancy $vacancy)
    {
        $vacancy->forceFill([
            'status' => $vacancy->status == 'open' ? 'closed' : 'open'
        ])->save();

        return $vacancy;
    }
}
