<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Filament\Resources\PostResource\RelationManagers;
use App\Models\Post;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\RichEditor;

use Filament\Forms\Components\TextInput;
use Illuminate\Support\Str;
use Filament\Forms\Set;

use Filament\Forms\Components\Toggle;

use Filament\Tables\Columns\ToggleColumn;

use Filament\Forms\Components\Card;
use Filament\Tables\Columns\TextColumn;

use Filament\Tables\Contracts\HasTable;

use Filament\Forms\Components\FileUpload; //untuk upload
use Filament\Tables\Columns\ImageColumn; //untuk tampilkan file

use Filament\Forms\Components\SpatieMediaLibraryFileUpload; //library upload
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
//untuk filter
use Filament\Tables\Filters\Filter;
//untuk filter select
use Filament\Tables\Filters\SelectFilter;

class PostResource extends Resource
{
    //untuk global search
    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    
    protected static ?string $navigationGroup = 'Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()->schema([
                Select::make('category_id')
                    ->relationship(name: 'category', titleAttribute: 'name'),
                TextInput::make('title')
                    ->required()
                    ->maxLength(255)
                    ->reactive()
                    ->afterStateUpdated(function (Set $set, ?string $state) {
                    $set('slug', \Str::slug($state));
                }),  
                TextInput::make('slug')->required(),
                // FileUpload::make('cover'),
                SpatieMediaLibraryFileUpload::make('cover'),
                RichEditor::make('content'),
                Toggle::make('status')
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
                TextColumn::make('title')->limit('50')->sortable()->searchable(),
                TextColumn::make('category.name'),
                //ImageColumn::make('cover'),
                SpatieMediaLibraryImageColumn::make('cover'),
                ToggleColumn::make('status')
            ])
            ->filters([
                Filter::make('publish')
                ->query(fn (Builder $query): Builder => $query->where('status', true)),
                Filter::make('hidden')
                ->query(fn (Builder $query): Builder => $query->where('status', false)),
                SelectFilter::make('Category')
                ->relationship('category', 'name')
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
            RelationManagers\TagsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            PostResource\Widgets\StatsOverview::class,
            //StatsOverview::class,
        ];
    }
}
