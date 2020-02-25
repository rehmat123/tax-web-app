<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\QuestionRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use http\Env\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\QuestionAnswered;
use App\Models\Question;
use App\User;

/**
 * Class QuestionCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class QuestionCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation {
        update as traitUpdate;
    }
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel('App\Models\Question');
        $this->crud->addButtonFromModelFunction('line', 'edit', 'edit', 'beginning');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/question');
        $this->crud->setEntityNameStrings('question', 'questions');
        if (backpack_user()->hasRole('client')) {
            $this->crud->addClause('where', 'user_id', '=', \Auth::user()->id);
        }
    }

    protected function setupShowOperation()
    {
        $this->crud->set('show.setFromDb', true);

        $this->crud->addColumn([

            'label' => 'Type', // Table column heading
            'type'  => 'select',
            'name' => 'type',
            'entity' => 'types', // the method that defines the relationship in your Model
            'attribute' => 'name', // foreign key attribute that is shown to user
            'model' => "App\Models\QuestionType" // foreign key model
        ]);
        $this->crud->addColumn([

            'label' => 'UserName', // Table column heading
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
            $this->crud->addColumn([
                'name' => 'created_at', // The db column name
                'label' => "Question Created At", // Table column headin
            ]);
            $this->crud->addColumn([
                'name' => 'answer_at', // The db column name
                'label' => " Answer given At", // Table column headin
            ]);
        }
        $this->crud->addColumn([
            'name' => 'image',
            'label' => 'Document Attachment',
            'type' => 'upload_multiple',
            'upload' => false,
            'prefix' => '../'

        ]);
        // $this->crud->removeColumn('date');
        // $this->crud->removeColumn('extras');
    }
    protected function setupListOperation()
    {
        if (backpack_user()->hasRole('superadmin')) {
            $this->crud->removeButton('create');
            $this->crud->removeButton('update');
            $this->crud->addButtonFromModelFunction('line', 'answer', 'answer', 'beginning');
        }

        $this->crud->removeButton('update');
        // TODO: remove setFromDb() and manually define Columns, maybe Filters
        // $this->crud->setFromDb();
        // $this->crud->addField('question');
        $this->crud->addColumn([
            'name' => 'question', // The db column name
            'label' => "Question", // Table column headin
        ]);

        $this->crud->addColumn([
            'name' => 'answer', // The db column name
            'label' => "Answer", // Table column headin
        ]);
        $this->crud->addColumn([

            'label' => 'Type', // Table column heading
            'type'  => 'select',
            'name' => 'type',
            'entity' => 'types', // the method that defines the relationship in your Model
            'attribute' => 'name', // foreign key attribute that is shown to user
            'model' => "App\Models\QuestionType" // foreign key model

        ]);

        $this->crud->addColumn([

            'label' => 'User Name', // Table column heading
            'type'  => 'select',
            'name' => 'user_id',
            'entity' => 'user', // the method that defines the relationship in your Model
            'attribute' => 'name', // foreign key attribute that is shown to user
            'model' => "App\User" // foreign key model

        ]);

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
            'label' => 'Question Type',
            'entity' => 'types',
            'attribute' => 'name', // foreign key attribute that is shown to user
            'model' => "App\Models\QuestionType" // foreign key model
        ]);
        if (backpack_user()->hasRole('superadmin')) {
            $this->crud->addField([
                'name' => 'answer',
                'type' => 'summernote',
                'label' => "Answer ",
                'default' => "<br>-------------------------------------------------DISCLAIMER--------------------------------------------------------------We can only provide information / point of view and not legal advice. Further we accept no responsibility or liability for any losses occasioned as a result of reliance on the information included in this portal.",
                'options' => [
                    'minheight: 300'
                ]
            ]);
        }

        $this->crud->addField([
            'name'  => 'user_id',
            'label' => 'xxxx',
            'type'  => 'hidden',
            'value'  => Auth::user()->id
        ]);

        if (backpack_user()->hasRole('superadmin')) {
            $this->crud->addField([
                'name'  => 'answer_at',
                'label' => 'xxxx',
                'type'  => 'hidden',
                'value'  => date("Y-m-d H:i:s")
            ]);
        }
        $this->crud->addField([
            'name' => 'image',
            'label' => 'Document Attachment',
            'type' => 'upload_multiple',
            'upload' => true,
            'prefix' => '../'

        ]);
    }


    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
    public function update(QuestionRequest $request)
    {
        $id = \Route::current()->parameter('id');
        $question = Question::join('users', 'users.id', 'questions.user_id')->where('questions.id', '=', intval($id))->first();
        $this->crud->removeField('user_id');
        $redirect_location = $this->traitUpdate($request);
        Mail::to($question->email)->send(new QuestionAnswered());

        return $redirect_location;
    }
}
