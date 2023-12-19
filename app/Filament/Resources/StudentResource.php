<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentResource\Pages;
use App\Filament\Resources\StudentResource\RelationManagers;
use App\Models\Student;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use Illuminate\Database\Eloquent\Collection;

// komponen untuk form
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TextArea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
// komponen untuk tabel
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Card;

use Filament\Tables\Contracts\HasTable; //untuk number sort

use Filament\Forms\Components\FileUpload; //untuk upload
use Filament\Tables\Columns\ImageColumn; //untuk tampilkan file

use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;

use Filament\Tables\Actions\BulkAction;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()->schema([
                TextInput::make('nis')->label('NIS'),
                TextInput::make('name')->label('Name Student')->required(),
                Select::make('gender')
                    ->options([
                        "Male" => "Male",
                        "Female" => "Female"
                    ]),
                DatePicker::make('birthday')->label('Birthday'),
                Select::make('religion')
                    ->options([
                        'Islam' => "Islam",
                        'Katolik' => "Katolik",
                        'Protestan' => "Protestan",
                        'Hindu' => "Hindu",
                        'Buddha' => "Buddha"
                    ]),
                TextInput::make('contact'),
                //Textarea::make('address'),
                FileUpload::make('profile')->directory('students')
                ])->columns(2) //menjadikan 2 kolom
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
                TextColumn::make('nis')->label('NIS'),
                TextColumn::make('name')->label('Name Student'),
                TextColumn::make('gender'),
                TextColumn::make('birthday')->label('Birthday'),
                TextColumn::make('religion'),
                TextColumn::make('contact'),
                //Textarea::make('address'),
                ImageColumn::make('profile'),
                TextColumn::make('status')
                    ->formatStateUsing(fn (string $state): string => ucwords("{$state}"))
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                //untuk menampilkan popup
                Tables\Actions\ViewAction::make()
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    bulkAction::make('Accept')
                        ->icon('heroicon-m-check')
                        ->requiresConfirmation()
                        ->action(function (Collection $records){
                            return $records->each->update(['status'=>'accept']);
                        }),
                    bulkAction::make('Off')
                        ->icon('heroicon-m-x-circle')
                        ->requiresConfirmation()
                        ->action(function (Collection $records){
                            return $records->each(function ($record){
                                $id = $record->id;
                                Student::where('id', $id)->update(['status'=>'off']);
                            });
                        }),
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
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
            //untuk menampilkan halaman baru
            //'view' => Pages\ViewStudent::route('/{record}')
        ];
    }

    // public static function infolist(Infolist $infolist): Infolist
    // {
    //     return $infolist
    //         ->schema([
    //             TextEntry::make('nis'),
    //             TextEntry::make('name'),
    //             TextEntry::make('gender'),
    //             TextEntry::make('birthday'),
    //             TextEntry::make('religion'),
    //             TextEntry::make('contact')
    //         ]);
    // }
}
