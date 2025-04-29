<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\On;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class UserTable extends PowerGridComponent
{
    public string $tableName = 'user-table-4yw1y7-table';

    public function setUp(): array
    {
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

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('photo', function (User $user) {
                return '<img class="w-8 h-8 shrink-0 grow-0 rounded-md" src="' . asset('storage/users/' . $user->photo) . '">';
            })

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
                ->title('Photo')
                ->field('photo'),

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
                ->class('inline-flex items-center justify-center gap-2
       rounded-lg px-5 py-2
       bg-white text-black
       dark:bg-zinc-900 dark:text-white
       hover:bg-zinc-100 dark:hover:bg-zinc-800
       hover:shadow-md transition-all duration-200
       focus:outline-none focus:ring-2 focus:ring-offset-2
       focus:ring-blue-500 dark:focus:ring-blue-400
       cursor-pointer')
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
