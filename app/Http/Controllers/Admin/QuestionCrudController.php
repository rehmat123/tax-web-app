<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\QuestionRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use http\Env\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class QuestionCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class QuestionCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel('App\Models\Question');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/question');
        $this->crud->setEntityNameStrings('question', 'questions');
        if (backpack_user()->hasRole('client')) {
            $this->crud->addClause('where', 'user_id', '=', \Auth::user()->id);
        }

    }

    protected function setupListOperation()
    {
        // TODO: remove setFromDb() and manually define Columns, maybe Filters
       // $this->crud->setFromDb();
       // $this->crud->addField('question');
        $this->crud->addColumn([
            'name' => 'question', // The db column name
            'label' => "Question", // Table column headin
        ]);

        $this->crud->addColumn( [

            'label' => 'Type', // Table column heading
            'type'  => 'select',
            'name' => 'type',
            'entity' => 'types', // the method that defines the relationship in your Model
            'attribute' => 'name', // foreign key attribute that is shown to user
            'model' => "App\Models\QuestionType" // foreign key model

        ]);

        $this->crud->addColumn( [

            'label' => 'User Name', // Table column heading
            'type'  => 'select',
            'name' => 'user_id',
            'entity' => 'user', // the method that defines the relationship in your Model
            'attribute' => 'name', // foreign key attribute that is shown to user
            'model' => "App\User" // foreign key model

        ]);
        if (backpack_user()->hasRole('superadmin')) {
            $this->crud->addColumn([

                'label' => 'Email', // Table column heading
                'type' => 'select',
                'name' => 'email',
                'entity' => 'user', // the method that defines the relationship in your Model
                'attribute' => 'email', // foreign key attribute that is shown to user
                'model' => "App\User" // foreign key model

            ]);
        }
//        $this->crud->setColumnDetails('type', ['attribute' => 'type']);

    }


    protected function setupCreateOperation()
    {
        $this->crud->setValidation(QuestionRequest::class);

        // TODO: remove setFromDb() and manually define Fields
       // $this->crud->setFromDb();
        $this->crud->addField([
            'name' => 'subject',
            'type' => 'text',
            'label' => "Subject"
        ]);
        $this->crud->addField([
            'name' => 'question',
            'type' => 'textarea',
            'label' => "Question "
        ]);
        $this->crud->addField([
            'name' => 'type',
            'type' => 'select',
            'label'=> 'Question Type',
            'entity' => 'types',
            'attribute' => 'name', // foreign key attribute that is shown to user
            'model' => "App\Models\QuestionType" // foreign key model
        ]);
        $this->crud->addField([
            'name'  => 'user_id',
            'label' => 'xxxx',
            'type'  => 'hidden',
            'value'  => Auth::user()->id
        ]);
    }


    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
