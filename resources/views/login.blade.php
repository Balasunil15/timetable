<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <style>
    body {
        background: linear-gradient(135deg, #6a11cb, #2575fc);
        font-family: 'Roboto', sans-serif;
        color: #333;
    }

     .login-container {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        padding: 20px;
    }

    .login-card {
        background: #fff;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 400px;
        animation: fadeIn 0.5s ease-in-out;
    }

    .login-card h3 {
        text-align: center;
        margin-bottom: 20px;
        color: #4e73df;
        font-weight: bold;
    }

    .form-control {
        border-radius: 10px;
        padding: 10px;
        border: 1px solid #ddd;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        box-shadow: 0 0 8px rgba(78, 115, 223, 0.5);
        border-color: #4e73df;
    }

    .password-toggle {
        position: relative;
    }

    .password-toggle .toggle-icon {
        position: absolute;
        top: 50%;
        right: 10px;
        transform: translateY(-50%);
        cursor: pointer;
        color: #6c757d;
        transition: color 0.3s ease;
    }

    .password-toggle .toggle-icon:hover {
        color: #4e73df;
    }

    .btn-primary {
        background: linear-gradient(135deg, #4e73df, #1cc88a);
        border: none;
        border-radius: 10px;
        padding: 10px 15px;
        font-size: 1rem;
        font-weight: bold;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #1cc88a, #4e73df);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }

    .btn-primary:active {
        transform: translateY(0);
        box-shadow: none;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="login-card">
            <h3>Faculty Login</h3>
            <form id="loginForm" method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-3">
                    <label for="facultyId" class="form-label">Faculty ID</label>
                    <input type="text" class="form-control" id="facultyId" name="facultyId" required>
                </div>
                <div class="mb-3 password-toggle">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                    <i class="fas fa-eye toggle-icon" id="togglePassword"></i>
                </div>
                <button type="submit" class="btn btn-primary w-100">Login</button>
            </form>
        </div>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const passwordField = document.getElementById('password');
        const togglePassword = document.getElementById('togglePassword');
        const loginForm = document.getElementById('loginForm');

        // Toggle password visibility
        togglePassword.addEventListener('click', function() {
            const type = passwordField.type === 'password' ? 'text' : 'password';
            passwordField.type = type;
            this.classList.toggle('fa-eye-slash');
        });

        // Prevent copy/paste in password field
        passwordField.addEventListener('copy', (e) => e.preventDefault());
        passwordField.addEventListener('paste', (e) => e.preventDefault());

        // Reset form on page reload
        loginForm.reset();
    });
    </script>
</body>

</html>