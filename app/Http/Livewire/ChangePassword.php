<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ChangePassword extends Component
{
    public $current_password;
    public $password;
    public $password_confirmation;

    protected $rules = [
        'current_password' => 'required',
        'password' => 'required|min:8|confirmed',
    ];

    // Messages personnalisés
    protected $messages = [
        'current_password.required' => 'Veuillez entrer votre mot de passe actuel.',
        'password.required' => 'Veuillez entrer un nouveau mot de passe.',
        'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
        'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
    ];

    public function updatePassword()
    {
        // Utilise la validation avec les messages personnalisés
        $this->validate($this->rules, $this->messages);

        // Récupère l'utilisateur via Eloquent
        $user = User::find(Auth::id());

        // Vérifie le mot de passe actuel
        if (!Hash::check($this->current_password, $user->password)) {
            $this->addError('current_password', 'Le mot de passe actuel est incorrect.');
            return;
        }

        // Hashe et enregistre le nouveau mot de passe
        $user->password = Hash::make($this->password);
        $user->save();

        // Message flash
        session()->flash('message', 'Mot de passe mis à jour avec succès.');

        // Reset du formulaire
        $this->reset(['current_password', 'password', 'password_confirmation']);
    }

    public function render()
    {
        return view('livewire.change-password');
    }
}
