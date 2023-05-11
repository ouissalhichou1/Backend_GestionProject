<!DOCTYPE html>
<html>
<head>
    <title>Votre nouveau mot de passe</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        h1 {
            color: #333;
        }

        p {
            margin-bottom: 10px;
        }

        strong {
            color: #FF0000;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #F5F5F5;
            border: 1px solid #DDD;
        }
    </style>
</head>
<body>
    
        <h1>Votre nouveau mot de passe</h1>

        <p>Bonjour {{ $userName }},</p>

        <p>Nous avons généré un nouveau mot de passe pour vous :</p>

        <p><strong>{{ $password }}</strong></p>

        <p>Veuillez vous connecter en utilisant ce nouveau mot de passe et n'oubliez pas de le changer après vous être connecté(e).</p>

        <p>Cordialement,</p>
        <p>Gestion_PFE</p>
    </div>
</body>
</html>
