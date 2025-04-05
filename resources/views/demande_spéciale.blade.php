<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>nouvelle demande spéciale</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .content {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 5px;
            border: 1px solid #dee2e6;
        }
        .field {
            margin-bottom: 15px;
        }
        .field strong {
            color: #495057;
        }
        .message-content {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-top: 10px;
        }
    </style>
</head>
<body>    
    <div class="content">
        <div class="field">
            <strong>Nom :</strong> {{ $demandeSpeciale->nom }}
        </div>
        <div class="field">
            <strong>Email :</strong> {{ $demandeSpeciale->email }}
        </div>
        <div class="field">
            <strong>Téléphone :</strong> {{ $demandeSpeciale->telephone }}
        </div>
        <div class="field">
            <strong>Sujet :</strong> {{ $demandeSpeciale->sujet }}
        </div>
        <div class="field">
            <strong>Demande spéciale:</strong>
            <div class="message-content">
                {{ $demandeSpeciale->message }}
            </div>
        </div>
    </div>
</body>
</html>