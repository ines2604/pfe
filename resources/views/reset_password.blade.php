<html>
    <body style="font-family: Arial, sans-serif; color: #333;">
        <p>Bonjour,</p>
        <p>Nous avons reçu une demande de réinitialisation de votre mot de passe. Cliquez sur le lien ci-dessous pour réinitialiser votre mot de passe :</p>
        <a href="{{ url('reset-password/' . $token . '?email=' . urlencode($email)) }}" style="display: inline-block; padding: 10px 20px; background-color: #007BFF; color: white; text-decoration: none; border-radius: 5px; margin: 10px 0;">Réinitialiser le mot de passe</a>
        <p>Après avoir cliqué sur le bouton, vous devrez suivre les étapes suivantes :</p>
        <ol>
            <li>Saisir un nouveau mot de passe.</li>
            <li>Confirmer votre nouveau mot de passe.</li>
            <li>Cliquer sur Réinitialiser.</li>
        </ol>
        <p><strong>Ce lien est valable pour une seule utilisation. Il expire dans une heure.</strong></p>
        <p>Si vous n'avez pas demandé la réinitialisation de votre mot de passe, veuillez ignorer ce message ou contacter notre service client.</p>
        <p>Cordialement,</p>
        <p>Alliance Hospitality</p>
    </body>
</html>