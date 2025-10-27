<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Affiche la liste des utilisateurs
    public function index()
    {
        $users = User::paginate(10);
        return view('users.index', compact('users'));
    }

    // Affiche le formulaire de création
    public function create()
    {
        return view('users.create');
    }

    // Stocke un nouvel utilisateur
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role'     => 'required|in:admin,magasinier,caissier',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
        ]);

        return redirect()->route('users.index')
                         ->with('success', 'Employé ajouté avec succès ✅');
    }

    // Affiche le formulaire d'édition
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    // Met à jour un utilisateur
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role'  => 'required|in:admin,magasinier,caissier',
        ]);

        $user->update([
            'name'  => $request->name,
            'email' => $request->email,
            'role'  => $request->role,
        ]);

        // Mise à jour du mot de passe seulement si rempli
        if ($request->filled('password')) {
            $request->validate([
                'password' => 'string|min:6|confirmed'
            ]);
            $user->password = Hash::make($request->password);
            $user->save();
        }

        return redirect()->route('users.index')
                         ->with('success', 'Employé mis à jour avec succès ✅');
    }

    // Supprime un utilisateur
    public function destroy(User $user)
    {
        if ($user->role === 'admin') {
            return redirect()->route('users.index')
                             ->with('error', 'Impossible de supprimer un administrateur ✅');
        }

        $user->delete();

        return redirect()->route('users.index')
                         ->with('success', 'Employé supprimé avec succès ✅');
    }
}
