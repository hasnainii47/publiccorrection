<?php
$message = '
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0;">
    <div style="max-width: 600px; margin: 30px auto; background-color: #ffffff; padding: 20px; border: 1px solid #e0e0e0; border-radius: 8px;">
        <div style="background-color: #333333; color: white; padding: 15px; text-align: center; font-size: 20px; border-top-left-radius: 8px; border-top-right-radius: 8px;">
            <img src="https://correctionterritory.com/images/logo.png" style="width:200px" />
        </div>
        <div style="padding: 20px; line-height: 1.6;">
            <p>Hi <strong>[User]</strong>,</p>
            <p><strong>[Walmart]</strong> is now down more than <span style="color: #d9534f; font-weight: bold;">[X]%</span> from its all-time high.</p>
            <p>Now might be a great time to review your position and consider potential next steps.</p>
            <p style="text-align: center;">
                <a href="http://CorrectionTerritory.com" style="display: inline-block; padding: 12px 25px; font-size: 16px; color: white; background-color: #f44336; text-align: center; text-decoration: none; border-radius: 5px;">Visit Correction Territory</a>
            </p>
        </div>
        <div style="padding-top: 20px; text-align: center; font-size: 12px; color: #777;">
            Stay informed,<br>
            Correction Territory Team<br>
            <a href="http://CorrectionTerritory.com" style="color: #4CAF50; text-decoration: none;">CorrectionTerritory.com</a>
        </div>
    </div>
</body>
</html>
';

echo $message;
?>
