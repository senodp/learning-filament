<?php

namespace App\Filament\Resources\TagResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

//untuk form
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Str;
use Filament\Forms\Set;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload; //library upload
use Filament\Forms\Components\Toggle;
//untuk table
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\ToggleColumn;

class PostsRelationManager extends RelationManager
{
    protected static string $relationship = 'posts';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()->schema([
                Select::make('category_id')
                    ->relationship(name: 'category', titleAttribute: 'name'),
                TextInput::make('title')
                    ->required()
                    ->maxLength(255)
                    ->live()
                    ->afterStateUpdated(function (Set $set, $state) {
                    $set('slug', Str::slug($state));
                }),  
                TextInput::make('slug')->required(),
                // FileUpload::make('cover'),
                SpatieMediaLibraryFileUpload::make('cover'),
                RichEditor::make('content'),
                Toggle::make('status')
                ])
            ]);
    }

    public function table(Table $table): Table
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
                TextColumn::make('title')->limit('50')->sortable(),
                TextColumn::make('category.name'),
                //ImageColumn::make('cover'),
                SpatieMediaLibraryImageColumn::make('cover'),
                ToggleColumn::make('status')
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
