<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-container {
            background: #fff;
            padding: 30px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        .login-container h2 {
            margin-bottom: 20px;
            font-weight: 600;
            color: #333;
        }
        .login-container .icon {
            font-size: 50px;
            color: #007bff;
            margin-bottom: 10px;
        }
        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }
        label {
            font-weight: 500;
        }
        input {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .btn-login {
            width: 100%;
            padding: 10px;
            background: #007bff;
            border: none;
            color: #fff;
            cursor: pointer;
            border-radius: 5px;
            font-weight: 600;
            transition: 0.3s;
        }
        .btn-login:hover {
            background: #5eabfe;
        }
        .error {
            color: red;
            font-size: 0.9em;
            margin-top: 5px;
        }
        .extra-links {
            margin-top: 15px;
            font-size: 0.9em;
        }
        .extra-links a {
            text-decoration: none;
            color: #007bff;
        }
        .extra-links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="login-container">
        <i class="bi bi-person-circle icon"></i>
        <h2>Login ke Ngasir</h2>
        <form action="{{ route('login.process') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
            
            <!-- Semua Error Ditampilkan di Sini -->
            <div class="error">
                @error('email') <p>{{ $message }}</p> @enderror
                @error('password') <p>{{ $message }}</p> @enderror
            </div>
    
            <button type="submit" class="btn btn-login">Login</button>
        </form>
    </div>    

</body>
</html>
