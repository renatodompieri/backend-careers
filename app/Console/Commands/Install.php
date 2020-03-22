<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use App\Repositories\RoleRepository;
use App\Repositories\LocaleRepository;
use Spatie\Permission\Models\Permission;
use App\Repositories\PermissionRepository;
use App\Repositories\EmailTemplateRepository;

class Install extends Command
{
    protected $locale;
    protected $role;
    protected $permission;
    protected $email_template;

    /**
     *  This command is used to reset the application to factory condition.
     */

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fresh Installation';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        LocaleRepository $locale,
        RoleRepository $role,
        PermissionRepository $permission,
        EmailTemplateRepository $email_template
    )
    {
        $this->locale = $locale;
        $this->role = $role;
        $this->permission = $permission;
        $this->email_template = $email_template;

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $bar = $this->output->createProgressBar(9);

        // First clear all the directories

        \File::put('storage/logs/laravel.log', '');

        $bar->advance();

        // Clear cache/routes/views

        \Artisan::call('cache:clear');
        \Artisan::call('route:clear');
        \Artisan::call('view:clear');
        \Artisan::call('config:clear');
        \Artisan::call('key:generate');

        $bar->advance();

        // Rollback migration and then migrate again

        \Artisan::call('migrate:fresh');

        $bar->advance();

        $system_variables = getVar('system');
        config(['system' => $system_variables]);

        $bar->advance();

        // Create default roles

        $roles = $this->role->listName();
        foreach (config('system.default_role') as $key => $value) {
            if (!in_array($value, $roles)) {
                Role::create(['name' => strtolower($value)]);
            }
        }

        $bar->advance();

        // Create default permissions

        $permissions = $this->permission->listName();
        foreach (config('system.default_permission') as $value) {
            if (!in_array($value, $permissions)) {
                Permission::create(['name' => strtolower($value)]);
            }
        }

        $bar->advance();

        // Assign default permission to admin roles

        $role = Role::whereName(config('system.default_role.admin'))->first();
        $role->syncPermissions(config('system.default_permission'));

        // Create default locales

        if (!$this->locale->findByLocale('pt')) {
            $this->locale->create([
                'locale' => 'pt',
                'name' => 'Português'
            ]);
        }

        if (!$this->locale->findByLocale('en')) {
            $this->locale->create([
                'locale' => 'en',
                'name' => 'English'
            ]);
        }


        $bar->advance();

        // Import default template contents

        $templates = array();
        foreach (getVar('template') as $key => $value) {
            if (!$this->email_template->findBySlug($key)) {
                $templates[] = array(
                    'is_default' => 1,
                    'name' => toWord($key),
                    'category' => isset($value['category']) ? $value['category'] : '',
                    'slug' => $key,
                    'subject' => isset($value['subject']) ? $value['subject'] : '',
                    'body' => view('emails.default.' . $key)->render()
                );
            }
        }
        if (count($templates)) {
            \App\EmailTemplate::insert($templates);
        }

        $bar->advance();

        \Artisan::call('db:seed');

        $bar->finish();
    }
}
