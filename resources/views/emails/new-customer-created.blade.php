<!DOCTYPE html>
<html>

<head>
    <title>Welcome to Helly</title>
</head>

<body>
    <p>Welcome {{ $customer->first_name }} {{ $customer->last_name }}!</p>

    <p>Thank you for creating an account with us. Your account details are:</p>

    <p>
        Email: {{ $customer->email }}
    </p>
    <p>
        Password: {{ $password }}
    </p>

    <br>
    <br>
    <p>Best Regards</p>
    <p>Helly</p>


</body>

</html>