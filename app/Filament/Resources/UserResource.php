<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
//untuk form
use Filament\Forms\Components\Card;
use Filament\Forms\Components\TextInput;
//untuk table
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;


class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'Setting';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()->schema([
                    TextInput::make('name')->required(),
                    TextInput::make('email')->email(),
                    TextInput::make('password')->password(),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('No.')->state(
                static function (HasTable $livewire, $rowLoop): string {
                    return (string) (
                        $rowLoop->iteration +
                        ($livewire->getTableRecordsPerPage() * (
                            $livewire->getTablePage() - 1
                        ))
                    );
                }
            ),
                TextColumn::make('name')->limit('50')->sortable()->searchable(),
                TextColumn::make('email')->limit('50')->searchable()
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
