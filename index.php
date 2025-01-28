<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = strip_tags(trim($_POST["name"]));
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $message = strip_tags(trim($_POST["message"]));

    if (empty($name) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(["success" => false]);
        exit;
    }

    $recipient = "ibrprofile@bk.ru";
    $subject = "Новое сообщение от $name";
    $email_content = "Имя: $name\n";
    $email_content .= "Email: $email\n\n";
    $email_content .= "Сообщение:\n$message\n";

    $email_headers = "From: $name <$email>";

    if (mail($recipient, $subject, $email_content, $email_headers)) {
        http_response_code(200);
        echo json_encode(["success" => true]);
    } else {
        http_response_code(500);
        echo json_encode(["success" => false]);
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Контактная форма</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4a90e2;
            --secondary-color: #f5a623;
            --background-color: #f0f4f8;
            --text-color: #333;
            --error-color: #e74c3c;
            --success-color: #2ecc71;
        }

        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-image: linear-gradient(120deg, #84fab0 0%, #8fd3f4 100%);
            transition: background-image 0.5s ease;
        }

        .container {
            width: 100%;
            max-width: 400px;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.18);
        }

        .settings {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }

        .select-wrapper {
            position: relative;
            display: inline-block;
        }

        .select-wrapper select {
            appearance: none;
            background-color: var(--primary-color);
            color: white;
            padding: 10px 30px 10px 15px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .select-wrapper::after {
            content: '\25BC';
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            color: white;
            pointer-events: none;
        }

        .select-wrapper select:hover {
            background-color: var(--secondary-color);
        }

        h1 {
            color: var(--primary-color);
            font-size: 28px;
            margin-bottom: 10px;
            text-align: center;
        }

        p {
            text-align: center;
            margin-bottom: 30px;
            color: var(--text-color);
        }

        .form-group {
            position: relative;
            margin-bottom: 30px;
        }

        input, textarea {
            width: 100%;
            padding: 10px 0;
            font-size: 16px;
            color: var(--text-color);
            border: none;
            border-bottom: 1px solid var(--text-color);
            outline: none;
            background: transparent;
            transition: border-color 0.3s ease;
        }

        textarea {
            height: 100px;
            resize: none;
        }

        label {
            position: absolute;
            top: 0;
            left: 0;
            padding: 10px 0;
            font-size: 16px;
            color: var(--text-color);
            pointer-events: none;
            transition: 0.3s ease all;
        }

        input:focus ~ label, input:valid ~ label,
        textarea:focus ~ label, textarea:valid ~ label {
            top: -20px;
            font-size: 14px;
            color: var(--primary-color);
        }

        .error {
            color: var(--error-color);
            font-size: 14px;
            position: absolute;
            bottom: -20px;
            left: 0;
        }

        button {
            width: 100%;
            background-color: var(--primary-color);
            color: white;
            padding: 14px 20px;
            margin: 8px 0;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        button:hover {
            background-color: var(--secondary-color);
            transform: translateY(-3px);
        }

        button:active {
            transform: translateY(1px);
        }

        .success-message {
            text-align: center;
            color: var(--success-color);
            font-size: 18px;
            margin-top: 20px;
        }

        /* Темы */
        .theme-dark {
            --primary-color: #bb86fc;
            --secondary-color: #03dac6;
            --background-color: #121212;
            --text-color: #ffffff;
        }

        .theme-light {
            --primary-color: #6200ee;
            --secondary-color: #03dac6;
            --background-color: #ffffff;
            --text-color: #000000;
        }

        .theme-nature {
            --primary-color: #4caf50;
            --secondary-color: #8bc34a;
            --background-color: #e8f5e9;
            --text-color: #1b5e20;
        }

        .theme-ocean {
            --primary-color: #0277bd;
            --secondary-color: #00bcd4;
            --background-color: #e1f5fe;
            --text-color: #01579b;
        }

        .theme-sunset {
            --primary-color: #ff9800;
            --secondary-color: #ff5722;
            --background-color: #fff3e0;
            --text-color: #e65100;
        }

        .theme-lavender {
            --primary-color: #9c27b0;
            --secondary-color: #e1bee7;
            --background-color: #f3e5f5;
            --text-color: #4a148c;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .container {
            animation: fadeIn 0.5s ease-in-out;
        }

        input:focus, textarea:focus {
            border-image: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            border-image-slice: 1;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="settings">
            <div class="select-wrapper">
                <select id="languageSelect" onchange="switchLanguage(this.value)">
                    <option value="ru">Русский</option>
                    <option value="en">English</option>
                </select>
            </div>
            <div class="select-wrapper">
                <select id="themeSelect" onchange="changeTheme(this.value)">
                    <option value="default">По умолчанию</option>
                    <option value="dark">Темная</option>
                    <option value="light">Светлая</option>
                    <option value="nature">Природа</option>
                    <option value="ocean">Океан</option>
                    <option value="sunset">Закат</option>
                    <option value="lavender">Лаванда</option>
                </select>
            </div>
        </div>
        <h1 id="formTitle">Как мы можем помочь?</h1>
        <p id="formDescription">Нужна помощь с вашим проектом? Мы здесь, чтобы помочь вам.</p>
        <form id="contactForm">
            <div class="form-group">
                <input type="text" id="name" name="name" required>
                <label for="name" id="nameLabel">Имя</label>
                <span class="error" id="nameError"></span>
            </div>
            <div class="form-group">
                <input type="email" id="email" name="email" required>
                <label for="email" id="emailLabel">Email</label>
                <span class="error" id="emailError"></span>
            </div>
            <div class="form-group">
                <textarea id="message" name="message" required></textarea>
                <label for="message" id="messageLabel">Сообщение</label>
                <span class="error" id="messageError"></span>
            </div>
            <button type="submit" id="submitButton">Отправить сообщение</button>
        </form>
        <div id="successMessage" class="success-message" style="display: none;">
            <p>Ваше сообщение отправлено. Спасибо!</p>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            loadTheme();
            loadLanguage();
        });

        function changeTheme(theme) {
            document.body.className = 'theme-' + theme;
            setCookie('theme', theme, 30);
        }

        function loadTheme() {
            const theme = getCookie('theme') || 'default';
            document.body.className = 'theme-' + theme;
            document.getElementById('themeSelect').value = theme;
        }

        function switchLanguage(lang) {
            setCookie('lang', lang, 30);
            loadLanguage();
        }

        function loadLanguage() {
            const lang = getCookie('lang') || 'ru';
            const translations = {
                'ru': {
                    'formTitle': 'Как мы можем помочь?',
                    'formDescription': 'Нужна помощь с вашим проектом? Мы здесь, чтобы помочь вам.',
                    'nameLabel': 'Имя',
                    'emailLabel': 'Email',
                    'messageLabel': 'Сообщение',
                    'submitButton': 'Отправить сообщение',
                    'successMessage': 'Ваше сообщение отправлено. Спасибо!'
                },
                'en': {
                    'formTitle': 'How can we help?',
                    'formDescription': 'Need help with your project? We\'re here to assist you.',
                    'nameLabel': 'Name',
                    'emailLabel': 'Email',
                    'messageLabel': 'Message',
                    'submitButton': 'Send Message',
                    'successMessage': 'Your message has been sent. Thank you!'
                }
            };

            document.getElementById('formTitle').textContent = translations[lang].formTitle;
            document.getElementById('formDescription').textContent = translations[lang].formDescription;
            document.getElementById('nameLabel').textContent = translations[lang].nameLabel;
            document.getElementById('emailLabel').textContent = translations[lang].emailLabel;
            document.getElementById('messageLabel').textContent = translations[lang].messageLabel;
            document.getElementById('submitButton').textContent = translations[lang].submitButton;
            document.getElementById('successMessage').querySelector('p').textContent = translations[lang].successMessage;
            document.getElementById('languageSelect').value = lang;
        }

        function setCookie(name, value, days) {
            let expires = "";
            if (days) {
                let date = new Date();
                date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                expires = "; expires=" + date.toUTCString();
            }
            document.cookie = name + "=" + (value || "")  + expires + "; path=/";
        }

        function getCookie(name) {
            let nameEQ = name + "=";
            let ca = document.cookie.split(';');
            for(let i=0; i < ca.length; i++) {
                let c = ca[i];
                while (c.charAt(0)==' ') c = c.substring(1,c.length);
                if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
            }
            return null;
        }

        document.getElementById('contactForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            document.querySelectorAll('.error').forEach(el => el.textContent = '');

            let hasError = false;
            
            if (document.getElementById('name').value.trim() === '') {
                document.getElementById('nameError').textContent = 'Имя обязательно';
                hasError = true;
            }

            const email = document.getElementById('email').value.trim();
            if (email === '') {
                document.getElementById('emailError').textContent = 'Email обязателен';
                hasError = true;
            } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                document.getElementById('emailError').textContent = 'Неверный формат email';
                hasError = true;
            }

            if (document.getElementById('message').value.trim() === '') {
                document.getElementById('messageError').textContent = 'Сообщение обязательно';
                hasError = true;
            }

            if (!hasError) {
                const formData = new FormData(this);
                fetch('index.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('contactForm').style.display = 'none';
                        document.getElementById('successMessage').style.display = 'block';
                    } else {
                        alert('Произошла ошибка при отправке формы. Пожалуйста, попробуйте еще раз.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Произошла ошибка при отправке формы. Пожалуйста, попробуйте еще раз.');
                });
            }
        });

        // Add input animations
        document.querySelectorAll('input, textarea').forEach(element => {
            element.addEventListener('focus', function() {
                this.parentNode.classList.add('focused');
            });
            element.addEventListener('blur', function() {
                if (this.value === '') {
                    this.parentNode.classList.remove('focused');
                }
            });
        });
    </script>
</body>
</html>

