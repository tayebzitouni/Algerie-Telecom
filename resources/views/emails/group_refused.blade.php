<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #f7f7f7;
            padding: 20px;
            color: #333;
            line-height: 1.6;
        }
        .container {
            background: #ffffff;
            padding: 25px;
            border-radius: 8px;
            max-width: 650px;
            margin: auto;
            box-shadow: 0 0 8px rgba(0,0,0,0.1);
        }
        .title {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 15px;
            color: #1a73e8;
        }
        .footer {
            margin-top: 25px;
            font-size: 12px;
            color: #777;
            text-align: center;
        }
        .note-box {
            background: #f2f6ff;
            border-left: 4px solid #1a73e8;
            padding: 12px;
            margin: 15px 0;
            border-radius: 4px;
        }
        strong {
            color: #000;
        }
    </style>
</head>
<body>
    <div class="container">

        <p class="title">Notification — Demande de groupe refusée</p>

        <p>Bonjour <strong>{{ $stagiaire->firstname }} {{ $stagiaire->lastname }}</strong>,</p>

        <p>
            Nous vous informons que la demande concernant le groupe 
            <strong>{{ $group->name }}</strong> a été <strong>refusée</strong> par l’administration.
        </p>

        <p>Veuillez trouver ci-dessous le motif de refus :</p>

        <div class="note-box">
            <strong>Motif du refus :</strong><br>
            {{ $note }}
        </div>

        <p>
            Nous vous invitons à prendre connaissance de cette décision et à contacter 
            votre encadreur ou l’administration en cas de besoin d’éclaircissements supplémentaires.
        </p>

        <p>
            Merci pour votre compréhension.<br>
            Cordialement,<br>
            <strong>Service Administration – Centre de formation</strong>
        </p>

        <div class="footer">
            Cet email a été envoyé automatiquement, merci de ne pas y répondre directement.
        </div>
    </div>
</body>
</html>
