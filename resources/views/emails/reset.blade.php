@component('mail::message')

Merci de vous être inscrit. Veuillez cliquer sur le bouton ci-dessous pour vérifier votre adresse email.

@component('mail::button', ['url' => $url])
Vérifier mon email
@endcomponent

Si vous n'avez pas créé de compte, vous pouvez ignorer cet email.

Cordialement,<br>
{{ config('app.name') }}
@endcomponent
