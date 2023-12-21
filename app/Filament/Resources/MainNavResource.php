<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MainNavResource\Pages;
use App\Filament\Resources\MainNavResource\RelationManagers;
use App\Models\MainNav;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MainNavResource extends Resource
{
    use \App\Common\Traits\ResourceHasNavigation;

    protected static ?string $model = MainNav::class;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    // Custom Attributes
    protected static ?string $navigationLabel = 'Utama';
    protected static ?string $navigationGroup = 'Navigasi';
    protected static ?int $navigationSort = 1;
    protected static ?string $recordTitleAttribute = 'title';
    protected static ?string $modelLabel = 'Menu';
    protected static ?string $pluralModelLabel = 'Menu Utama';
    protected static ?string $slug = 'navigasi/utama';

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
            ]);
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageMainNavs::route('/'),
        ];
    }    
    
}
