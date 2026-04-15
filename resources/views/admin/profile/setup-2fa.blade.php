@extends('layouts.admin')
@section('content')
<div class="mb-6"><a href="{{ route('admin.profile.index') }}" class="text-sm text-batid-bleu hover:underline">&larr; Retour au profil</a></div>
<h1 class="mb-6 text-2xl font-bold text-batid-marine">Configurer la 2FA</h1>

<div class="mx-auto max-w-lg rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
    <div class="mb-6 space-y-3">
        <h2 class="text-lg font-semibold text-batid-marine">Étape 1 — Scanner le QR code</h2>
        <p class="text-sm text-gray-600">Ouvrez votre application d'authentification (Google Authenticator, Authy, etc.) et scannez ce QR code :</p>
        <div class="flex justify-center rounded-lg bg-gray-50 p-6">
            {!! $qrCodeSvg !!}
        </div>
        <details class="text-sm">
            <summary class="cursor-pointer text-batid-bleu hover:underline">Saisie manuelle</summary>
            <div class="mt-2 rounded-lg bg-gray-50 p-3">
                <p class="mb-1 text-gray-500">Clé secrète :</p>
                <code class="select-all break-all text-sm font-mono font-semibold text-gray-900">{{ $secret }}</code>
            </div>
        </details>
    </div>

    <div class="border-t pt-6">
        <h2 class="mb-3 text-lg font-semibold text-batid-marine">Étape 2 — Confirmer</h2>
        <p class="mb-4 text-sm text-gray-600">Entrez le code à 6 chiffres affiché dans votre application pour finaliser l'activation.</p>
        <form method="POST" action="{{ route('admin.profile.2fa.confirm') }}">
            @csrf
            <div class="mb-4">
                <input type="text" name="code" inputmode="numeric" pattern="[0-9]{6}" maxlength="6" required autofocus placeholder="000000"
                       class="w-full rounded-lg border-gray-300 text-center text-2xl tracking-[0.5em] shadow-sm focus:border-batid-bleu focus:ring-batid-bleu">
                @error('code') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <button type="submit" class="w-full rounded-lg bg-batid-bleu px-4 py-3 text-sm font-semibold text-white transition hover:bg-batid-marine">
                Activer la 2FA
            </button>
        </form>
    </div>
</div>
@endsection
