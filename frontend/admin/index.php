<?php include 'includes/auth.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-commerce Website - Admin Access</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .main-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
            width: 90%;
            max-width: 1200px;
            min-height: 600px;
            display: grid;
            grid-template-columns: 1fr 400px;
        }
        
        .customer-section {
            padding: 60px;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        }
        
        .admin-section {
            padding: 60px 40px;
            background: #1a1a1a;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .logo {
            font-size: 32px;
            font-weight: bold;
            color: #333;
            margin-bottom: 20px;
        }
        
        .welcome-text {
            font-size: 48px;
            font-weight: 300;
            color: #333;
            margin-bottom: 20px;
            line-height: 1.2;
        }
        
        .description {
            font-size: 18px;
            color: #666;
            margin-bottom: 40px;
            line-height: 1.6;
        }
        
        .features {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 40px;
        }
        
        .feature {
            background: white;
            padding: 24px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        
        .feature-icon {
            width: 40px;
            height: 40px;
            background: #667eea;
            border-radius: 8px;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
        }
        
        .feature h3 {
            font-size: 16px;
            color: #333;
            margin-bottom: 8px;
        }
        
        .feature p {
            font-size: 14px;
            color: #666;
            line-height: 1.4;
        }
        
        .admin-title {
            font-size: 24px;
            margin-bottom: 16px;
            color: #fff;
        }
        
        .admin-description {
            font-size: 14px;
            color: #aaa;
            margin-bottom: 30px;
            line-height: 1.5;
        }
        
        .login-form {
            margin-bottom: 30px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #ccc;
            font-size: 14px;
        }
        
        .form-group input {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #333;
            border-radius: 8px;
            background: #2a2a2a;
            color: white;
            font-size: 14px;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #667eea;
        }
        
        .btn {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: transform 0.2s;
        }
        
        .btn:hover {
            transform: translateY(-2px);
        }
        
        .admin-links {
            border-top: 1px solid #333;
            padding-top: 20px;
        }
        
        .admin-link {
            display: block;
            color: #667eea;
            text-decoration: none;
            padding: 8px 0;
            border-bottom: 1px solid #333;
            transition: color 0.3s;
        }
        
        .admin-link:hover {
            color: #764ba2;
        }
        
        .demo-credentials {
            background: #2a2a2a;
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #667eea;
        }
        
        .demo-credentials h4 {
            color: #667eea;
            margin-bottom: 8px;
            font-size: 14px;
        }
        
        .demo-credentials p {
            color: #aaa;
            font-size: 12px;
            margin: 4px 0;
        }
        
        @media (max-width: 968px) {
            .main-container {
                grid-template-columns: 1fr;
                width: 95%;
                margin: 20px auto;
            }
            
            .customer-section {
                padding: 40px 30px;
            }
            
            .admin-section {
                padding: 40px 30px;
            }
            
            .welcome-text {
                font-size: 36px;
            }
            
            .features {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="main-container">
      <?php 
        $page = $_GET['pages'] ?? 'main';
        switch ($page){
          case 'main':
            require 'pages/master.php';
            break;
          case 'product-management':
            require 'pages/product-management.php';
            break;
          case 'logout':
            logout();
            header('Location: index.php?pages=main');
            exit();
            break;
          default : 
            require 'pages/master.php';
            break;
        }
      ?>
    </div>

    <script>
        function openAdminDirect() {
            window.location.href = 'index.php?pages=product-management';
        }
    </script>
    <?php include 'includes/script.php' ?>
</body>
</html>