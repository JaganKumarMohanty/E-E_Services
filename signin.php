<?php include 'header.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Sign In</title>
<style>
  body {
    font-family: Arial, sans-serif;
    background: linear-gradient(to right, #667eea, #764ba2);
    height: 100vh; margin: 0;
    justify-content: center; 
  }
  .sign-in-container {
    background: white;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 4px 30px rgba(0,0,0,0.2);
    width: 320px;
    text-align: center;
    margin: 200px 800px;
  }
  input {
    width: 100%;
    padding: 12px;
    margin: 12px 0;
    border: 1.5px solid #ddd;
    border-radius: 7px;
    font-size: 16px;
    outline: none;
  }
  input:focus {
    border-color: #667eea;
  }
  button {
    width: 100%;
    padding: 14px;
    border: none;
    background: #667eea;
    color: white;
    font-size: 18px;
    border-radius: 7px;
    cursor: pointer;
  }
  .error {
    color: #e74c3c;
    font-size: 14px;
    margin-top: -8px;
    margin-bottom: 12px;
    text-align: left;
  }
</style>
</head>
<body>

<div class="sign-in-container">
  <h2>Sign In</h2>
  <form id="loginForm" method="POST" action="login.php">
    <input type="email" id="email" name="email" placeholder="Email" required />
    <div class="error" id="emailError"></div>

    <input type="password" id="password" name="password" placeholder="Password" required />
    <div class="error" id="passwordError"></div>

    <button type="submit">Log In</button>
  </form>
</div>

<script>
  const loginForm = document.getElementById('loginForm');
  const emailInput = document.getElementById('email');
  const passwordInput = document.getElementById('password');
  const emailError = document.getElementById('emailError');
  const passwordError = document.getElementById('passwordError');

  loginForm.addEventListener('submit', function(e) {
    let valid = true;
    emailError.textContent = '';
    passwordError.textContent = '';

    if (!emailInput.value.trim()) {
      emailError.textContent = 'Please enter your email.';
      valid = false;
    }
    if (!passwordInput.value) {
      passwordError.textContent = 'Please enter your password.';
      valid = false;
    }
    if (!valid) e.preventDefault();
  });
</script>

</body>
</html>
