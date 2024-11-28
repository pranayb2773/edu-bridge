<?php

namespace App\Filament\Resources;

use App\Enums\ExamPaperStatus;
use App\Enums\ExamPaperType;
use App\Filament\Resources\ExamPaperResource\Pages;
use App\Infolists\Components\ExamPaperViewer;
use App\Models\ExamPaper;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ExamPaperResource extends Resource
{
    protected static ?string $model = ExamPaper::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('name')->required()->maxLength(255),
                        Forms\Components\ToggleButtons::make('type')->required()->inline()->options(ExamPaperType::class)->default(ExamPaperType::MIDTERM),
                        Forms\Components\Select::make('subject_id')
                            ->relationship('subject', 'name')
                            ->required()
                            ->searchable()
                            ->getSearchResultsUsing(function (string $search) {
                                return \App\Models\Subject::query()
                                    ->where('name', 'like', "%{$search}%")
                                    ->orWhereHas('branch', function ($query) use ($search) {
                                        $query->where('name', 'like', "%{$search}%");
                                    })
                                    ->get()
                                    ->mapWithKeys(function ($subject) {
                                        return [
                                            $subject->id => "{$subject->name} - {$subject->branch->name} - {$subject->semester} Semester",
                                        ];
                                    });
                            })
                            ->getOptionLabelUsing(function ($value) {
                                $subject = \App\Models\Subject::find($value);
                                return "{$subject->branch->name} - {$subject->name}";
                            }),

                        Forms\Components\ToggleButtons::make('status')->required()->inline()->options(ExamPaperStatus::class)->default(ExamPaperStatus::ACTIVE),
                        Forms\Components\DatePicker::make('conducted_at')->required(),
                        Forms\Components\RichEditor::make('description')->nullable()->columnSpan(2),
                    ]),
                Forms\Components\Section::make('Attachment')
                    ->schema([
                        Forms\Components\FileUpload::make('file_path')
                            ->required()
                            ->acceptedFileTypes(['application/pdf'])
                            ->maxSize(5000)
                            ->hiddenLabel(),
                    ])
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('subject.name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('subject.branch.name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('type')->badge(),
                Tables\Columns\TextColumn::make('status')->badge(),
                Tables\Columns\TextColumn::make('conducted_at')->date()->sortable(),
                Tables\Columns\TextColumn::make('updated_at')->since()->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')->options(ExamPaperType::class),
                Tables\Filters\SelectFilter::make('status')->options(ExamPaperStatus::class),
            ])
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
                                        Components\TextEntry::make('subject.name'),
                                        Components\TextEntry::make('subject.semester')->label('Semester'),
                                        Components\TextEntry::make('conducted_at')->badge()->date()->color('warning'),
                                    ]),
                                    Components\Group::make([
                                        Components\TextEntry::make('type')->badge(),
                                        Components\TextEntry::make('subject.branch.name')->label('Branch'),
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
                Components\Section::make('Exam Paper')
                    ->schema([
                        ExamPaperViewer::make('file_path')->hiddenLabel(),
                    ])
                    //->extraAttributes(['style' => 'height: 100vh;'])
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
            'index' => Pages\ListExamPapers::route('/'),
            'create' => Pages\CreateExamPaper::route('/create'),
            'view' => Pages\ViewExamPaper::route('/{record}'),
            'edit' => Pages\EditExamPaper::route('/{record}/edit'),
        ];
    }
}
