@component('mail::message')
# Bonjour {{ $user->nom }} !

Merci de vous être inscrit. Voici votre code de vérification pour valider votre adresse email :

@component('mail::panel')
**{{ $code }}**
@endcomponent

Ce code expirera dans **60 minutes**.

Si vous n'avez pas créé de compte, vous pouvez ignorer cet email.

Cordialement,<br>
{{ config('app.name') }}
@endcomponent
