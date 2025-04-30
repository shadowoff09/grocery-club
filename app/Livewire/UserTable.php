<?php

namespace App\Livewire;

use App\Models\User;
use Blade;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\On;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class UserTable extends PowerGridComponent
{
    public string $tableName = 'users-table';

    public function setUp(): array
    {
        return [
            PowerGrid::header()
                ->showSearchInput()
                ->showToggleColumns(),
            PowerGrid::footer()
                ->showPerPage()
                ->showRecordCount(),
            PowerGrid::responsive()
                ->fixedColumns('name', 'actions'),
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
                ->slot(Blade::render('<flux:button icon="eye" class="cursor-pointer" wire:click="$dispatch(\'edit\', { rowId: ' . $row->id . ' })">View</flux:button>'))
                ->id(),
        ];


    }
}
