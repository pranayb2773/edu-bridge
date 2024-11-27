<?php

namespace App\Filament\Resources;

use App\Enums\UserRoles;
use App\Enums\UserStatus;
use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->autocapitalize()
                    ->required()
                    ->validationAttribute('name')
                    ->rules([
                        function () {
                            return function (string $attribute, $value, $fail) {
                                $words = explode(' ', trim($value));

                                if (count($words) < 2) {
                                    $fail('The name must contain at least 2 words.');
                                }

                                // Optional: Additional checks
                                foreach ($words as $word) {
                                    if (strlen($word) < 2) {
                                        $fail('Each word must be at least 2 characters long.');
                                    }
                                }
                            };
                        },
                    ]),
                Forms\Components\TextInput::make('email')->required()->email(),
                Forms\Components\TextInput::make('password')
                    ->label(__('filament-panels::pages/auth/register.form.password.label'))
                    ->password()
                    ->revealable(filament()->arePasswordsRevealable())
                    ->required()
                    ->rule(Password::default())
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                    ->same('passwordConfirmation')
                    ->validationAttribute(__('filament-panels::pages/auth/register.form.password.validation_attribute'))
                    ->hiddenOn('edit'),
                Forms\Components\TextInput::make('passwordConfirmation')
                    ->label(__('filament-panels::pages/auth/register.form.password_confirmation.label'))
                    ->password()
                    ->revealable(filament()->arePasswordsRevealable())
                    ->required()
                    ->dehydrated(false)
                    ->hiddenOn('edit'),
                Forms\Components\ToggleButtons::make('role')
                    ->options(UserRoles::class)
                    ->default(UserRoles::STUDENT->value)
                    ->required()
                    ->inline(),
                Forms\Components\ToggleButtons::make('status')
                    ->options(UserStatus::class)
                    ->default(UserStatus::ACTIVE->value)
                    ->required()
                    ->inline(),
            ]);
    }

    /**
     * @throws \Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('email')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('status')->badge(),
                Tables\Columns\TextColumn::make('role')->badge(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Last Modified At')
                    ->since()->sortable(),
            ])
            ->defaultSort('updated_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options(UserStatus::class),
                Tables\Filters\SelectFilter::make('role')->options(UserRoles::class),
            ])->hiddenFilterIndicators()
            ->actions([
                Tables\Actions\Action::make('changePassword')
                    ->label('Change Password')
                    ->icon('heroicon-o-lock-closed')
                    ->color('warning')
                    ->form([
                        Forms\Components\TextInput::make('password')
                            ->label(__('filament-panels::pages/auth/register.form.password.label'))
                            ->password()
                            ->revealable(filament()->arePasswordsRevealable())
                            ->required()
                            ->rule(Password::default())
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->same('passwordConfirmation')
                            ->validationAttribute(__('filament-panels::pages/auth/register.form.password.validation_attribute')),
                        Forms\Components\TextInput::make('passwordConfirmation')
                            ->label(__('filament-panels::pages/auth/register.form.password_confirmation.label'))
                            ->password()
                            ->revealable(filament()->arePasswordsRevealable())
                            ->required()
                            ->dehydrated(false),
                    ])->modalWidth('lg'),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageUsers::route('/'),
        ];
    }
}
