<?php

namespace App\Common\Traits;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
// use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;

trait ResourceHasPartner
{
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->columnSpanFull()
                    ->columns(6)
                    ->schema([
                        Forms\Components\Grid::make()
                            ->columnSpan(5)
                            ->columns(3)
                            ->schema([
                                \App\Common\Form::TextTitle()
                                    ->columnSpan(2),
                                Forms\Components\TextInput::make('slug')
                                    ->columnSpan(1)
                                    ->required()
                                    ->unique()
                                    ->maxLength(64),
                                
                                Forms\Components\Textarea::make('content')
                                    ->columnSpanFull()
                                    ->label('Description')
                                    ->maxLength(65535),
                            ]),
                        \App\Common\Form::CroppedImageUpload(
                                width: '100', height: '100', label: 'Icon'
                        )
                        ,
                    ]),
                Forms\Components\Hidden::make('type')->default(self::$partner_type),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->paginated(false)
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('content')
                    ->label('Description')
                    ->limit(100)
                    ->html(),
                Tables\Columns\SpatieMediaLibraryImageColumn::make('logo')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('type', self::$partner_type);
    }
}
