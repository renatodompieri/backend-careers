<?php

namespace App\Http\Controllers;

use App\Vacancy;
use Illuminate\Http\Request;
use App\Http\Requests\VacancyRequest;
use App\Repositories\VacancyRepository;
use App\Repositories\ActivityLogRepository;

class VacancyController extends Controller
{
    protected $module = 'vacancy';

    private $request;
    private $repo;
    protected $activity;

    /**
     * Instantiate a new Vacancy Controller instance.
     *
     * @return void
     */
    public function __construct(Request $request, VacancyRepository $repo, ActivityLogRepository $activity)
    {
        $this->request = $request;
        $this->repo = $repo;
        $this->activity = $activity;

        $this->middleware('feature.available:vacancy');
    }

    /**
     * Used to get all vacancies
     * Public Function
     * @get ("/api/vacancies")
     * @return Response
     */
    public function index()
    {
        return $this->ok($this->repo->paginate($this->request->all()));
    }

    /**
     * Used to store a new Vacancy
     * @post ("/api/vacancies")
     * @param ({
     * @Parameter("title", type="string", required="true", description="Title of the Vacancy"),
     * @Parameter("description", type="string", required="true", description="Description of the Vacancy"),
     * @Parameter("salary", type="string", required="false", description="The expected salary for a year of work"),
     * @Parameter("Address", type="Address", required="false", description="The work address for the Vacancy")
     * })
     * @return Vacancy
     */
    public function store(VacancyRequest $request)
    {
        $this->authorize('create', Vacancy::class);
        $vacancy = $this->repo->create($this->request->all());

        $this->activity->record([
            'module' => $this->module,
            'module_id' => $vacancy->id,
            'activity' => 'added'
        ]);

        return $this->success(['message' => trans('vacancies.added')]);
    }

    /**
     * Used to get Vacancy detail
     * @get ("/api/vacancy/{id}")
     * @param ({
     * @Parameter("id", type="integer", required="true", description="Id of the Vacancy"),
     * })
     * @return Vacancy
     */
    public function show($id)
    {
        $vacancy = $this->repo->findOrFail($id);

        return $this->ok($vacancy);
    }

    /**
     * Used to update Vacancy status
     * @post ("/api/vacancy/{id}/status")
     * @param ({
     * @Parameter("id", type="integer", required="true", description="Id of the Vacancy"),
     * })
     * @return Response
     */
    public function toggleStatus($id)
    {
        $vacancy = $this->repo->findOrFail($id);

        $this->authorize('update', $vacancy);

        $vacancy = $this->repo->toggle($vacancy);

        $this->activity->record([
            'module' => $this->module,
            'module_id' => $vacancy->id,
            'activity' => 'updated'
        ]);

        return $this->success(['message' => trans('vacancies.updated'), 'vacancy' => $vacancy]);
    }

    /**
     * Used to update Vacancy
     * @patch ("/api/vacancy/{id}")
     * @param ({
     * @Parameter("id", type="integer", required="true", description="Id of the Vacancy"),
     * @Parameter("title", type="string", required="true", description="Title of the Vacancy"),
     * @Parameter("description", type="string", required="true", description="Description of the Vacancy"),
     * @Parameter("salary", type="string", required="false", description="The expected salary for a year of work"),
     * @Parameter("Address", type="Address", required="false", description="The work address for the Vacancy")
     * })
     * @return Response
     */
    public function update($id, VacancyRequest $request)
    {
        $vacancy = $this->repo->findOrFail($id);

        $this->authorize('update', $vacancy);

        $vacancy = $this->repo->update($vacancy, $this->request->all());

        $this->activity->record([
            'module' => $this->module,
            'module_id' => $vacancy->id,
            'activity' => 'updated'
        ]);

        return $this->success(['message' => trans('vacancies.updated')]);
    }

    /**
     * Used to delete Vacancy
     * @delete ("/api/vacancy/{id}")
     * @param ({
     * @Parameter("id", type="integer", required="true", description="Id of the Vacancy"),
     * })
     * @return Response
     */
    public function destroy($id)
    {
        $vacancy = $this->repo->findOrFail($id);

        $this->authorize('delete', $vacancy);

        $this->activity->record([
            'module' => $this->module,
            'module_id' => $vacancy->id,
            'activity' => 'deleted'
        ]);

        $this->repo->delete($vacancy);

        return $this->success(['message' => trans('vacancies.deleted')]);
    }
}
