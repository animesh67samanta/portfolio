<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Reply</title>
</head>
<body style="font-family: Arial, sans-serif; color: #1f2937; line-height: 1.5;">
    <p>Hello {{ $contact->name }},</p>
    <p>Thanks for reaching out. Here is our response:</p>
    <p style="white-space: pre-wrap;">{{ $messageBody }}</p>
    <p>Best regards,<br>{{ config('app.name') }}</p>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Reply</title>
</head>
<body style="font-family: Arial, sans-serif; color: #1f2937; line-height: 1.5;">
    <p>Hello {{ $contact->name }},</p>

    <p>Thanks for reaching out. Here is our response:</p>

    <p style="white-space: pre-wrap;">{{ $messageBody }}</p>

    <p>Best regards,<br>{{ config('app.name') }}</p>
</body>
</html>
