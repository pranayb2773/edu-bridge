<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Enums\UserRoles;
use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Database\Eloquent\Builder;

class ManageUsers extends ManageRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make(),
            'teachers' => Tab::make('Teachers')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('role', UserRoles::TEACHER)),
            'students' => Tab::make('Students')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('role', UserRoles::STUDENT)),
        ];
    }
}
