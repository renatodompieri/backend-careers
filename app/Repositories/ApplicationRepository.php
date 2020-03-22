<?php
namespace App\Repositories;

use App\Application;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class ApplicationRepository
{
    private $application;

    /**
     * Instantiate a new instance.
     *
     * @return void
     */
    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    /**
     * Get application query
     *
     * @return Application query
     */

    public function getQuery()
    {
        return $this->application;
    }

    /**
     * Find application with given id or throw an error.
     *
     * @param integer $id
     * @return Application
     */

    public function findOrFail($id)
    {
        $application = $this->application->find($id);

        if (! $application) {
            throw ValidationException::withMessages(['message' => trans('application.could_not_find')]);
        }

        return $application;
    }

    /**
     * Paginate all applications using given params.
     *
     * @param array $params
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */

    public function paginate($params)
    {
        $sort_by     = isset($params['sort_by']) ? $params['sort_by'] : 'created_at';
        $order      = isset($params['order']) ? $params['order'] : 'desc';
        $page_length = isset($params['page_length']) ? $params['page_length'] : config('config.page_length');
        $keyword     = isset($params['keyword']) ? $params['keyword'] : null;
        $status     = isset($params['status']) ? $params['status'] : 0;
        $start_date = isset($params['start_date']) ? $params['start_date'] : null;
        $end_date   = isset($params['end_date']) ? $params['end_date'] : null;

        $query = $this->application->filterByUserId(\Auth::user()->id)->filterByTitleOrDescription($keyword)->filterCompletedApplication($status)->dateBetween([
            'start_date' => $start_date,
            'end_date' => $end_date
        ]);

        return $query->orderBy($sort_by, $order)->paginate($page_length);
    }

    /**
     * Create a new application.
     *
     * @param array $params
     * @return Application
     */
    public function create($params)
    {
        return $this->application->forceCreate($this->formatParams($params));
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
            'title'       => isset($params['title']) ? $params['title'] : null,
            'description' => isset($params['description']) ? $params['description'] : null,
            'date'        => isset($params['date']) ? $params['date'] : null
        ];

        if ($action === 'create') {
            $formatted['user_id'] = \Auth::user()->id;
        }

        return $formatted;
    }

    /**
     * Update given application.
     *
     * @param Application $application
     * @param array $params
     *
     * @return Application
     */
    public function update(Application $application, $params)
    {
        $application->forceFill($this->formatParams($params, 'update'))->save();

        return $application;
    }

    /**
     * Delete application.
     *
     * @param integer $id
     * @return bool|null
     */
    public function delete(Application $application)
    {
        return $application->delete();
    }

    /**
     * Delete multiple applications.
     *
     * @param array $ids
     * @return bool|null
     */
    public function deleteMultiple($ids)
    {
        return $this->application->whereIn('id', $ids)->delete();
    }

    /**
     * Toggle given application status.
     *
     * @param Application $application
     * @param array $params
     *
     * @return Application
     */
    public function toggle(Application $application)
    {
        $application->forceFill([
            'completed_at' => (! $application->status) ? Carbon::now() : null,
            'status'       => ! $application->status
        ])->save();

        return $application;
    }
}
