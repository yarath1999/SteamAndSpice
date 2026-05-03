<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Jost:wght@400;500;600;700&family=Playfair+Display:wght@600;700;800;900&display=swap');

        body { 
            margin: 0; 
            font-family: 'Jost', 'Segoe UI', sans-serif; 
            background: radial-gradient(circle at 12% 18%, rgba(255, 123, 50, 0.12) 0%, rgba(255, 123, 50, 0) 34%), linear-gradient(180deg, #1a120b, #2b1d14); 
            min-height: 100vh; 
            display: grid; 
            place-items: center; 
            color: #f5f5f5;
            line-height: 1.6;
        }
        h1 {
            font-family: 'Playfair Display', Georgia, serif;
            font-size: 1.8rem;
            font-weight: 700;
            letter-spacing: -0.3px;
            line-height: 1.2;
        }
        .box { 
            background: linear-gradient(165deg, rgba(43, 29, 20, 0.96), rgba(26, 18, 11, 0.9)); 
            border: 1px solid rgba(241, 200, 118, 0.18); 
            border-radius: 14px; 
            padding: 28px; 
            width: min(420px, 92%); 
            box-shadow: 0 10px 24px rgba(0,0,0,.24); 
            color: #f5f5f5; 
        }
        label { 
            display: block; 
            font-weight: 700; 
            margin: 14px 0 8px;
            font-size: 0.95rem;
            font-family: 'Playfair Display', Georgia, serif;
        }
        input { 
            width: 100%; 
            border: 1px solid rgba(241, 200, 118, 0.18); 
            border-radius: 8px; 
            padding: 10px 12px; 
            background: rgba(255,255,255,0.04); 
            color: #f5f5f5;
            font-family: 'Jost', 'Segoe UI', sans-serif;
        }
        input:focus {
            outline: none;
            border-color: rgba(241, 200, 118, 0.36);
            background: rgba(255,255,255,0.06);
        }
        button { 
            margin-top: 18px; 
            width: 100%; 
            border: 0; 
            border-radius: 9px; 
            padding: 11px; 
            background: linear-gradient(135deg, #ff7b32, #f1c876); 
            color: #1a120b; 
            font-weight: 700; 
            cursor: pointer;
            font-family: 'Jost', 'Segoe UI', sans-serif;
            font-size: 1rem;
            transition: filter 0.2s ease, transform 0.2s ease;
        }
        button:hover {
            filter: brightness(1.05);
            transform: translateY(-2px);
        }
        .error-msg {
            color: #ff6b6b;
            font-size: 0.9rem;
            margin-bottom: 16px;
        }
    </style>
</head>
<body>
<form class="box" method="POST" action="{{ route('admin.login.submit') }}">
    @csrf
    <h1 style="margin-top: 0; margin-bottom: 20px;">Admin Login</h1>
    @if($errors->any())
        <p class="error-msg">{{ $errors->first() }}</p>
    @endif
    <label for="email">Email</label>
    <input id="email" type="email" name="email" value="{{ old('email') }}" required>
    <label for="password">Password</label>
    <input id="password" type="password" name="password" required>
    <label style="font-weight: 500; margin-top: 14px; display:flex; gap:10px; align-items:center;">
        <input type="checkbox" name="remember" style="width:auto; cursor: pointer;"> Remember me
    </label>
    <button class="cta-btn" type="submit">Login</button>
</form>
</body>
</html>
