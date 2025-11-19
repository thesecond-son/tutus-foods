<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>New Corporate Request</title>
</head>
<body style="margin:0; padding:0; background-color:#fafafa; font-family: Arial, sans-serif; color:#333;">
<table width="100%" cellpadding="0" cellspacing="0" border="0" style="padding:40px 0; background-color:#fafafa;">
    <tr>
        <td align="center">
            <table width="600" cellpadding="0" cellspacing="0" border="0" style="background-color:#fff8f9; border-radius:12px; padding:25px; border:1px solid #ff88a6;">
                <tr>
                    <td align="center" style="font-size:24px; font-weight:bold; color:#ff5887; padding-bottom:20px;">
                        New Corporate Request Submitted
                    </td>
                </tr>
                <tr>
                    <td style="background-color:#ffffff; padding:18px; border-radius:10px;">
                        <p><strong>Name:</strong> {{ $request->name }}</p>
                        <p><strong>Phone:</strong> {{ $request->phone }}</p>
                        <p><strong>Email:</strong> {{ $request->email }}</p>
                        <p><strong>Purpose:</strong> {{ $request->purpose }}</p>
                        <p><strong>Number of People:</strong> {{ $request->people }}</p>
                        <p><strong>Type:</strong> {{ $request->type }}</p>
                    </td>
                </tr>
                <tr>
                    <td align="center" style="padding:25px 0;">
                        <a href="{{ url('https://tutusfoods.com/admin/corporate-requests/' . $request->id) }}" style="background-color:#ff5887; color:#ffffff; text-decoration:none; padding:12px 22px; border-radius:6px; font-weight:bold; display:inline-block;">
                            View Request in Admin
                        </a>
                    </td>
                </tr>
                <tr>
                    <td align="center" style="font-size:0.9rem; color:#555; padding-top:25px;">
                        Thanks,<br>
                        <strong style="color:#ff5887;">Tutu’s Foods</strong>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
