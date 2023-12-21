<?php

namespace App\Common;

use Filament\Forms;
use Illuminate\Support\Str;

class Form
{
    public static function ProgramSchema(bool $withTaxonomy=true)
    {   
        $GridSchema = [
            \App\Common\Form::TextTitle()
                ->columnSpan(2),
            Forms\Components\TextInput::make('slug')
                ->columnSpan(1)
                ->required()
                ->maxLength(64)
        ];

        if ($withTaxonomy){
            $GridSchema[] = \App\Common\Form::SelectTaxonomy('PILAR', 'Program')
                ->columnSpanFull();
        }

        $GridSchema[] = Forms\Components\RichEditor::make('content')
            ->label('Description')
            ->maxLength(65535)
            ->columnSpanFull();

        return [
            Forms\Components\Grid::make()
                ->columnSpan(3)
                ->columns(3)
                ->schema($GridSchema),
            \App\Common\Form::CroppedImageUpload(
                width: '1920', height: '964'
            ),
        ];
    }

    public static function TextTitle(string $label="Title")
    {
        return Forms\Components\TextInput::make('title')
            ->label($label)
            ->required()
            ->maxLength(255)
            ->live(debounce: 500)
            ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set, ?string $old, ?string $state) {
                if (($get('slug') ?? '') !== Str::slug($old)) {
                    return;
                }
            
                $set('slug', Str::slug($state));
            });
    }

    public static function SelectTaxonomy(string $label, string $type, string $relationship = 'taxonomy', bool $searchable = false)
    {
        $form = Forms\Components\Select::make('taxonomy_id')
            ->label($label)
            ->required()
            // ->columnSpanFull()
            ->relationship(name: $relationship, titleAttribute: 'title')
            ->createOptionForm([
                Forms\Components\Grid::make()
                    ->columns(3)
                    ->columnSpanFull()
                    ->schema([
                        \App\Common\Form::TextTitle()
                            ->columnSpan(2),
                        Forms\Components\TextInput::make('slug')
                            ->columnSpan(1)
                            ->required(),
                        Forms\Components\RichEditor::make('description')
                            ->columnSpanFull(),
                        Forms\Components\Hidden::make('type')->default($type),
                    ])
            ])
            ->editOptionForm([
                \App\Common\Form::TextTitle(),
                Forms\Components\TextInput::make('slug')
                    ->required(),
                Forms\Components\RichEditor::make('description'),
            ]);


        if ($searchable){
            return $form->searchable()->preload();
        }

        return $form;
    }

    public static function SelectStatus()
    {
        return Forms\Components\Select::make('status')
        ->options([
            'draft' => 'Draft',
            'published' => 'Published',
        ])
        ->live();
    }

    public static function Date($name, $label)
    {
        return Forms\Components\DatePicker::make($name)
            ->label($label)
            ->native(false)
            ->displayFormat('d F Y')
            ->required()
            ->default(time());
    }

    public static function DateTime($name, $label)
    {
        return Forms\Components\DateTimePicker::make($name)
            ->label($label)
            ->seconds(false)
            ->default(time())
            ->native(false)
            ->displayFormat('d F Y H:i');
    }

        public static function DateTimePublished()
        {
            return \App\Common\Form::DateTime('published_at', 'Publish Date')
                ->hidden(fn (Forms\Get $get): bool => ($get('status') != 'published') );
        }

    public static function CroppedImageUpload($width = '1920', $height = '1080', $name = 'image', $label = 'Image', $spatiemedialibrary = true)
    {
        if ($spatiemedialibrary){
            $ImageForm = Forms\Components\SpatieMediaLibraryFileUpload::make($name);
        } else {
            $ImageForm = Forms\Components\FileUpload::make($name);
        }

        return $ImageForm
            ->label($label)
            ->image()
            ->required()
            ->preserveFilenames()
            ->panelAspectRatio("$width:$height")
            ->helperText("Recommended image dimension is {$width}x{$height} pixels, image with different dimension will be cropped to fit this dimension")
            // ->imageEditor()->imageEditorAspectRatios(["$width:$height])->imageEditorMode(3)->imageEditorViewportWidth($width)->imageEditorViewportHeight($height)
            ->imageResizeMode('cover')
            ->imageResizeTargetWidth($width)
            ->imageResizeTargetHeight($height);
    }

        public static function HeaderImageUpload()
        {
            return \App\Common\Form::CroppedImageUpload(
                width: '1920', height: '1072'
            );
        }
        
        public static function HeaderImageUploadJson()
        {
            return \App\Common\Form::CroppedImageUpload(
                width: '1920', height: '1072', name: 'json.image', spatiemedialibrary: false
            );
        }

        public static function CoverImageUpload()
        {
            return \App\Common\Form::CroppedImageUpload(
                width: '1080', height: '1528', label: 'Cover Image'
            );
        }

        public static function LandscapeCoverImageUpload()
        {
            return \App\Common\Form::CroppedImageUpload(
                width: '1528', height: '1080', label: 'Cover Image'
            );
        }
}
