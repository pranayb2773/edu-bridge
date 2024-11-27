<?php

namespace App\Filament\Resources;

use App\Enums\BranchStatus;
use App\Enums\SubjectStatus;
use App\Filament\Resources\SubjectResource\Pages;
use App\Models\Subject;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Form;
use Filament\Infolists\Components;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SubjectResource extends Resource
{
    protected static ?string $model = Subject::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('name')->required()->maxLength(255),
                        Forms\Components\TextInput::make('code')->required()->maxLength(255),
                        Forms\Components\TextInput::make('semester')->required()->numeric()->minValue(1)->maxValue(8),
                        Forms\Components\ToggleButtons::make('status')->options(SubjectStatus::class)->required()->inline()->default(SubjectStatus::ACTIVE),
                        Forms\Components\Select::make('branch_id')
                            ->relationship('branch', 'name')
                            ->required()
                            ->searchable()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(),
                                Forms\Components\TextInput::make('no_of_semesters')
                                    ->required()
                                    ->numeric()
                                    ->minValue(1)
                                    ->maxValue(8),
                                Forms\Components\ToggleButtons::make('status')
                                    ->options(BranchStatus::class)
                                    ->required()
                                    ->inline()
                                    ->default(BranchStatus::ACTIVE),
                            ])->createOptionAction(function (Action $action) {
                                return $action
                                    ->modalHeading('Create Branch')
                                    ->modalSubmitActionLabel('Create Branch')
                                    ->modalWidth('lg');
                            }),
                        Forms\Components\RichEditor::make('description')->nullable()->columnSpan(2),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('code')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('semester')->sortable()->alignCenter(),
                Tables\Columns\TextColumn::make('status')->badge()->alignLeft(),
                Tables\Columns\TextColumn::make('branch.name')->sortable(),
                Tables\Columns\TextColumn::make('updated_at')->sortable()->since(),
            ])
            ->filters([
                Tables\Filters\Filter::make('semester')
                    ->form([
                        Forms\Components\TextInput::make('semester')
                            ->numeric()
                            ->minValue(0)->maxValue(9),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['semester'],
                            fn (Builder $query, $semester): Builder => $query->where('semester', $semester)
                        );
                    }),
                Tables\Filters\SelectFilter::make('status')->options(SubjectStatus::class),
            ])->hiddenFilterIndicators()
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])->defaultSort('updated_at', 'desc');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Components\Section::make()
                    ->schema([
                        Components\Split::make([
                            Components\Grid::make(2)
                                ->schema([
                                    Components\Group::make([
                                        Components\TextEntry::make('name'),
                                        Components\TextEntry::make('semester'),
                                        Components\TextEntry::make('branch.name'),
                                    ]),
                                    Components\Group::make([
                                        Components\TextEntry::make('code'),
                                        Components\TextEntry::make('status')->badge(),
                                        Components\TextEntry::make('updated_at')
                                            ->badge()
                                            ->date()
                                            ->color('success'),
                                    ]),
                                ]),
                        ])->from('lg'),
                    ]),
                Components\Section::make('Content')
                    ->schema([
                        Components\TextEntry::make('description')
                            ->prose()
                            ->markdown()
                            ->hiddenLabel(),
                    ])
                    ->collapsible(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSubjects::route('/'),
            'create' => Pages\CreateSubject::route('/create'),
            'view' => Pages\ViewSubject::route('/{record}'),
            'edit' => Pages\EditSubject::route('/{record}/edit'),
        ];
    }
}
