<!DOCTYPE html>
<html>
<head>
    <title>Password Changed</title>
</head>
<body>
    <p>Hello <?= $user['fname'];?>,</p>
    <p>Your password has been successfully changed.</p>
    <p>Your new password is: <strong><?= esc($password) ?></strong></p>
    <p>If you didn’t request this change, please contact support immediately.</p>
    <p>Thank you!</p>
</body>
</html>
