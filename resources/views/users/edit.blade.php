<form action="{{ route('users.update', $user->id) }}" method="POST" class="bg-white p-6 rounded shadow">
    @csrf
    @method('PUT')
    <input type="text" name="name" value="{{ old('name', $user->name) }}" required>
    <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
    <input type="password" name="password" placeholder="Laisser vide pour ne pas changer">
    <input type="password" name="password_confirmation" placeholder="Confirmer le mot de passe">
    <select name="role">
        <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
        <option value="magasinier" {{ $user->role == 'magasinier' ? 'selected' : '' }}>Magasinier</option>
        <option value="caissier" {{ $user->role == 'caissier' ? 'selected' : '' }}>Caissier</option>
    </select>
</form>
