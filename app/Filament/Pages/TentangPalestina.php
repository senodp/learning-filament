<?php

namespace App\Filament\Pages;

use Filament\Forms;
use Filament\Pages\Page;

use App\Models\Section;

class TentangPalestina extends Page
{
    // protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.tentang-palestina';

    protected static ?int $navigationSort = 1;
    protected static ?string $navigationGroup = 'Tentang Kemanusiaan';
    protected static ?string $navigationLabel = 'Sejarah Palestina';
    protected static ?string $title = 'Sejarah Palestina';
    protected static ?string $slug = 'tentang-palestina';

    public ?array $data = [];
    public $section;
    protected $section_type = 'TentangPalestina';

    public function mount(): void
    {
        $this->section = Section::where('type', $this->section_type)->first() ?? new Section;
        $this->form->fill($this->section->toArray());
        // $this->form->model($this->section);
    }

    public function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->columnSpanFull()
                    ->columns(4)
                    ->schema([
                        Forms\Components\RichEditor::make('content')
                            ->required()
                            ->columnSpan(3),
                        // \App\Common\Form::HeaderImageUploadJson()
                        //     ->columnSpan(1),
                    ])
            ])
            ->statePath('data')
            ->model($this->section);
    }

    public function save()
    {
        $this->section->update($this->form->getState());
        // $section = Post::updateOrCreate(
        //     [ 
        //         'title' => 'Tentang Sejarah',
        //         'slug' => 'sejarah',
        //         'type' => $this->section_type
        //     ],
        //     $this->form->getState()
        // );

        // $this->form->model($section)->saveRelationships();

        \App\Common\Notify::success();
    }
}
