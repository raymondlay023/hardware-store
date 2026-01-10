<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LanguageSwitcher extends Component
{
    public string $currentLocale;

    public array $locales = [
        'id' => ['name' => 'Indonesia', 'flag' => '🇮🇩'],
        'en' => ['name' => 'English', 'flag' => '🇬🇧'],
    ];

    public function mount()
    {
        $this->currentLocale = Session::get('locale', config('app.locale'));
    }

    public function switchLanguage(string $locale)
    {
        if (array_key_exists($locale, $this->locales)) {
            Session::put('locale', $locale);
            App::setLocale($locale);
            $this->currentLocale = $locale;
            
            // Refresh the page to apply translations
            $this->dispatch('language-changed', locale: $locale);
            
            return redirect(request()->header('Referer'));
        }
    }

    public function render()
    {
        return view('livewire.language-switcher');
    }
}
