@extends('layouts.admin')
@section('content')
<h1 class="mb-6 text-2xl font-bold text-batid-marine">Mon profil</h1>

<div class="grid gap-6 lg:grid-cols-2">
    <!-- Informations -->
    <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
        <h2 class="mb-4 text-lg font-semibold text-batid-marine">Informations</h2>
        <dl class="space-y-3 text-sm">
            <div class="flex justify-between"><dt class="text-gray-500">Nom</dt><dd class="font-medium text-gray-900">{{ $admin->full_name }}</dd></div>
            <div class="flex justify-between"><dt class="text-gray-500">Email</dt><dd class="font-medium text-gray-900">{{ $admin->email }}</dd></div>
            <div class="flex justify-between"><dt class="text-gray-500">Rôle</dt><dd class="font-medium text-gray-900">{{ ucfirst(str_replace('_', ' ', $admin->role)) }}</dd></div>
            <div class="flex justify-between"><dt class="text-gray-500">Dernière connexion</dt><dd class="font-medium text-gray-900">{{ $admin->last_login_at?->format('d.m.Y H:i') ?? '—' }}</dd></div>
        </dl>
    </div>

    <!-- Mot de passe -->
    <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
        <h2 class="mb-4 text-lg font-semibold text-batid-marine">Changer le mot de passe</h2>
        <form method="POST" action="{{ route('admin.profile.password') }}" class="space-y-4">
            @csrf
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700">Mot de passe actuel</label>
                <input type="password" name="current_password" required class="w-full rounded-lg border-gray-300 text-sm">
                @error('current_password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700">Nouveau mot de passe</label>
                <input type="password" name="password" required class="w-full rounded-lg border-gray-300 text-sm">
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700">Confirmer</label>
                <input type="password" name="password_confirmation" required class="w-full rounded-lg border-gray-300 text-sm">
                @error('password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <button type="submit" class="rounded-lg bg-batid-bleu px-4 py-2 text-sm font-semibold text-white hover:bg-batid-marine">Mettre à jour</button>
        </form>
    </div>

    <!-- 2FA -->
    <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-100 lg:col-span-2">
        <h2 class="mb-4 text-lg font-semibold text-batid-marine">Authentification à deux facteurs (2FA)</h2>
        @if($admin->hasTwoFactor())
            <div class="mb-4 flex items-center gap-3">
                <span class="inline-flex items-center gap-1.5 rounded-full bg-green-100 px-3 py-1 text-sm font-medium text-green-700">
                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.06l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/></svg>
                    Activée
                </span>
                <span class="text-sm text-gray-500">depuis le {{ $admin->two_factor_confirmed_at->format('d.m.Y à H:i') }}</span>
            </div>
            <p class="mb-4 text-sm text-gray-600">Pour désactiver la 2FA, entrez votre mot de passe.</p>
            <form method="POST" action="{{ route('admin.profile.2fa.disable') }}" class="flex items-end gap-3" onsubmit="return confirm('Êtes-vous sûr de vouloir désactiver la 2FA ?')">
                @csrf
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Mot de passe</label>
                    <input type="password" name="password" required class="rounded-lg border-gray-300 text-sm">
                    @error('password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <button type="submit" class="rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">Désactiver la 2FA</button>
            </form>
        @else
            <div class="mb-4 flex items-center gap-3">
                <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-100 px-3 py-1 text-sm font-medium text-amber-700">
                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 6a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 6zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/></svg>
                    Désactivée
                </span>
            </div>
            <p class="mb-4 text-sm text-gray-600">Protégez votre compte avec une application d'authentification (Google Authenticator, Authy, etc.).</p>
            <a href="{{ route('admin.profile.2fa.setup') }}" class="inline-block rounded-lg bg-batid-bleu px-4 py-2 text-sm font-semibold text-white hover:bg-batid-marine">Configurer la 2FA</a>
        @endif
    </div>
</div>
@endsection
