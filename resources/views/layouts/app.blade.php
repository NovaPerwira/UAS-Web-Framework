<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Freelance App</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            background: #f7f7f7;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
        }
        th {
            background: #333;
            color: white;
        }
        a, button {
            padding: 8px 12px;
            background: #2563eb;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            border: none;
            cursor: pointer;
        }
        form {
            background: white;
            padding: 20px;
            width: 400px;
        }
        input, select {
            width: 100%;
            padding: 8px;
            margin-bottom: 12px;
        }
        .error {
            color: red;
            font-size: 14px;
        }
    </style>
</head>
<body>

    <h1>Freelance Management</h1>
    <hr>

    {{-- INI WAJIB ADA --}}
    @yield('content')

</body>
</html>
