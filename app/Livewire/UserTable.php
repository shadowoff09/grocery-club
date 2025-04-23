<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\On;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class UserTable extends PowerGridComponent
{
    public string $tableName = 'user-table-4yw1y7-table';

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            PowerGrid::header()
                ->showSearchInput(),
            PowerGrid::footer()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return User::query();
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('name')
            ->add('email')
            ->add('type', function (User $user) {
                return ucfirst($user->type);
            })
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::add()
                ->title('ID')
                ->field('id'),

            Column::add()
                ->title('Name')
                ->field('name')
                ->sortable()
                ->searchable(),

            Column::add()
                ->title('Email')
                ->field('email')
                ->sortable()
                ->searchable(),

            Column::add()
                ->title('Role')
                ->field('type')
                ->sortable(),

            Column::add()
                ->title('Created at')
                ->field('created_at')
                ->sortable(),

            Column::action('Action')
        ];
    }

    public function filters(): array
    {
        return [
        ];
    }

    #[On('edit')]
    public function edit($rowId): void
    {
        redirect()->route('board.users.show', $rowId);
    }

    public function actions(User $row): array
    {
        return [
            Button::add('edit')
                ->slot('View')
                ->id()
                ->class('pg-btn-white dark:ring-pg-primary-600 dark:border-pg-primary-600 dark:hover:bg-pg-primary-700 dark:ring-offset-pg-primary-800 dark:text-pg-primary-300 dark:bg-pg-primary-700')
                ->dispatch('edit', ['rowId' => $row->id])
        ];
    }

    /*
    public function actionRules($row): array
    {
       return [
            // Hide button edit for ID 1
            Rule::button('edit')
                ->when(fn($row) => $row->id === 1)
                ->hide(),
        ];
    }
    */
}
