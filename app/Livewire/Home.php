<?php

namespace App\Livewire;

use Filament\Forms\Form;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;

use Filament\Forms\Components\Card;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Livewire\Component;

class Home extends Component implements HasForms
{

    use InteractsWithForms;

    public $name = '';
    public $gender = '';
    public $birthday = '';
    public $religion = '';
    public $contact = '';
    public $profile;

    public function form(Form $form): Form
    {
        return $form
        ->schema([
            Card::make()->schema([
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
            TextInput::make('profile')
                ->type('file')
                ->extraAttributes(['class' => 'rounded'])
            ])
        ]);
    }

    public function render()
    {
        return view('livewire.home');
    }

    public function save(): void
    {
        $data = $this->form->getState();
        //process upload
        if($this->profile){
            $uploadedFile = $this->profile;
            $fileName = time() . '_' . $uploadedFile->getClientOriginalName();
            $path = $uploadedFile->storeAs('public/students',$fileName);

            $data['profile'] = 'students/'.$fileName;
        }

        Student::insert($data);

        session()->flash('message', 'Save Successfully');
    }
}
