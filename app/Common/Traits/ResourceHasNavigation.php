<?php

namespace App\Common\Traits;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

trait ResourceHasNavigation
{
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Fieldset::make('Menu')
                    ->schema([
                        Forms\Components\TextInput::make('title')->label('Title'),
                        \App\Common\Form::SelectStatus()->default('published'),
                    ]),
                Forms\Components\Fieldset::make('Menu Items')
                    // ->icon('heroicon-o-queue-list')
                    // ->collapsible()
                    ->schema([
                        Forms\Components\Repeater::make('items')
                            ->label(false)
                            ->columnSpanFull()
                            ->relationship()
                            // ->collapsed()
                            ->reorderableWithButtons()
                            ->orderColumn('sort')
                            ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                            ->columns(4)
                            ->schema([
                                Forms\Components\TextInput::make('title')->label('Title'),
                                Forms\Components\TextInput::make('json.link')->label('Slug or Link to Page'),
                                Forms\Components\Select::make('type')
                                    ->options([
                                        'internal'  =>  'Slug or Link, page on this website',
                                        'external'  =>  'External URL, will open in a new tab/page',
                                    ])->default('internal'),
                                \App\Common\Form::SelectStatus()->default('published'),
                            ])
                            ->mutateRelationshipDataBeforeCreateUsing(function (array $data): array {
                                $data['user_id'] = auth()->id();
                                return $data;
                            }),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->reorderable('sort')
            ->paginated(false)
            ->columns([
                Tables\Columns\TextColumn::make('title'),
                \App\Common\Table::PublishStatus(),
            ])
            ->filters([
                // Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->slideOver(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }
}
