<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title ?? 'Cybersecurity & Innovations Club - SLAU' }}</title>
    <meta name="description" content="Cybersecurity and Innovations Club at St. Lawrence University, Uganda - Empowering the next generation of cybersecurity professionals">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        /* Cybersecurity theme colors */
        :root {
            --cyber-primary: #00ff41;
            --cyber-secondary: #0ea5e9;
            --cyber-dark: #0a0e27;
            --cyber-darker: #050814;
            --cyber-accent: #8b5cf6;
        }
        
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background-color: var(--cyber-darker);
            color: #e5e7eb;
        }
        
        .cyber-gradient {
            background: linear-gradient(135deg, var(--cyber-dark) 0%, var(--cyber-darker) 100%);
        }
        
        .cyber-text-gradient {
            background: linear-gradient(135deg, var(--cyber-primary) 0%, var(--cyber-secondary) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .cyber-glow {
            box-shadow: 0 0 20px rgba(0, 255, 65, 0.3);
        }
        
        .nav-link {
            position: relative;
            transition: color 0.3s ease;
        }
        
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--cyber-primary);
            transition: width 0.3s ease;
        }
        
        .nav-link:hover::after,
        .nav-link.active::after {
            width: 100%;
        }
        
        .cyber-card {
            background: rgba(10, 14, 39, 0.6);
            border: 1px solid rgba(0, 255, 65, 0.2);
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }
        
        .cyber-card:hover {
            border-color: rgba(0, 255, 65, 0.5);
            box-shadow: 0 0 30px rgba(0, 255, 65, 0.2);
            transform: translateY(-5px);
        }
        
        .cyber-button {
            background: linear-gradient(135deg, var(--cyber-primary) 0%, var(--cyber-secondary) 100%);
            color: var(--cyber-darker);
            font-weight: 600;
            padding: 0.75rem 2rem;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }
        
        .cyber-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 255, 65, 0.3);
        }
        
        .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body class="cyber-gradient">
    @include('frontend.components.navbar')
    
    <main>
        @yield('content')
    </main>
    
    @include('frontend.components.footer')
    
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</body>
</html>
