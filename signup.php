<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Animated Registration Form with OTP & Password</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
  <style>
    body {
      font-family: 'Montserrat', sans-serif;
      background: linear-gradient(to right, #6a11cb, #2575fc);
      height: 100vh;
      margin: 0;
     
      justify-content: center;
      align-items: center;
      padding: 15px;
    }
    .container {
      background: white;
      width: 420px;
      border-radius: 12px;
      padding: 30px;
      box-shadow: 0 8px 40px rgba(0,0,0,0.2);
      text-align: center;
      overflow: hidden;
    }
    h2 {
      margin-bottom: 20px;
      color: #333;
    }
    input, select {
      width: 100%;
      padding: 14px;
      margin: 8px 0 18px 0;
      border: 1.7px solid #ddd;
      border-radius: 8px;
      font-size: 17px;
      outline: none;
      transition: border-color 0.3s ease;
    }
    input:focus, select:focus {
      border-color: #2575fc;
    }
    button {
      background: #2575fc;
      border: none;
      color: white;
      font-size: 19px;
      padding: 16px;
      border-radius: 8px;
      cursor: pointer;
      width: 100%;
      transition: background 0.25s ease;
      font-weight: 600;
      user-select: none;
    }
    button:disabled {
      background: #a1c0fc;
      cursor: not-allowed;
    }
    button:hover:not(:disabled) {
      background: #1a5ddb;
    }
    .hidden {
      display: none;
    }
    .error {
      color: #e74c3c;
      font-weight: 600;
      margin-top: -14px;
      margin-bottom: 16px;
      text-align: left;
      font-size: 14px;
      min-height: 18px;
    }
    .otp-inputs {
      display: flex;
      justify-content: space-between;
      margin-bottom: 22px;
    }
    .otp-inputs input {
      width: 60px;
      font-size: 26px;
      text-align: center;
      border: 1.7px solid #ddd;
      border-radius: 8px;
      outline: none;
      padding: 12px;
      transition: border-color 0.3s ease;
      user-select: none;
    }
    .otp-inputs input:focus {
      border-color: #2575fc;
    }
    .success-message {
      font-size: 22px;
      font-weight: 700;
      color: #27ae60;
      margin-top: 28px;
    }
  </style>
</head>
<body>
    <div>
    <?php include 'header.php'; ?>
    </div>

  <div class="container">
    <!-- Step 1: User Details + Password -->
    <div id="step1" class="animate__animated animate__fadeIn">
      <h2>Create Account</h2>
      
      <input type="text" id="name" placeholder="Full Name" autocomplete="off" />
      <div class="error" id="nameError"></div>
      
      <select id="gender">
        <option value="">Select Gender</option>
        <option value="male">Male</option>
        <option value="female">Female</option>
        <option value="other">Other</option>
      </select>
      <div class="error" id="genderError"></div>
      
      <input type="number" id="age" placeholder="Age" min="1" max="120" autocomplete="off" />
      <div class="error" id="ageError"></div>
      
      <input type="date" id="dob" max="" placeholder="Date of Birth" autocomplete="off" />
      <div class="error" id="dobError"></div>
      
      <input type="email" id="email" placeholder="Email Address" autocomplete="off" />
      <div class="error" id="emailError"></div>
      
      <input type="tel" id="mobile" placeholder="Mobile Number (10 digits)" autocomplete="off" />
      <div class="error" id="mobileError"></div>
      
      <input type="password" id="password" placeholder="New Password" autocomplete="off" />
      <div class="error" id="passwordError"></div>
      
      <input type="password" id="confirmPassword" placeholder="Confirm Password" autocomplete="off" />
      <div class="error" id="confirmPasswordError"></div>
      
      <button id="nextBtn" disabled>Next</button>
    </div>

    <!-- Step 2: OTP Verification -->
    <div id="step2" class="hidden animate__animated">
      <h2>Verify OTPs</h2>
      <p>Enter OTP sent to your Email</p>
      <div class="otp-inputs" id="emailOtpInputs">
        <input type="text" maxlength="1" />
        <input type="text" maxlength="1" />
        <input type="text" maxlength="1" />
        <input type="text" maxlength="1" />
      </div>
      <p>Enter OTP sent to your Mobile</p>
      <div class="otp-inputs" id="mobileOtpInputs">
        <input type="text" maxlength="1" />
        <input type="text" maxlength="1" />
        <input type="text" maxlength="1" />
        <input type="text" maxlength="1" />
      </div>
      <button id="verifyOtpBtn" disabled>Verify OTPs & Register</button>
      <div class="error" id="otpError"></div>
    </div>

    <!-- Step 3: Success -->
    <div id="step3" class="hidden animate__animated animate__fadeIn">
      <h2>Registration Successful!</h2>
      <p class="success-message">Thank you for registering. Your email and mobile are verified.</p>
    </div>
  </div>

<script>
  // Fields
  const nameInput = document.getElementById('name');
  const genderSelect = document.getElementById('gender');
  const ageInput = document.getElementById('age');
  const dobInput = document.getElementById('dob');
  const emailInput = document.getElementById('email');
  const mobileInput = document.getElementById('mobile');
  const passwordInput = document.getElementById('password');
  const confirmPasswordInput = document.getElementById('confirmPassword');
  const nextBtn = document.getElementById('nextBtn');

  // Errors
  const nameError = document.getElementById('nameError');
  const genderError = document.getElementById('genderError');
  const ageError = document.getElementById('ageError');
  const dobError = document.getElementById('dobError');
  const emailError = document.getElementById('emailError');
  const mobileError = document.getElementById('mobileError');
  const passwordError = document.getElementById('passwordError');
  const confirmPasswordError = document.getElementById('confirmPasswordError');

  // OTP step elements
  const step1 = document.getElementById('step1');
  const step2 = document.getElementById('step2');
  const step3 = document.getElementById('step3');
  const emailOtpInputs = document.querySelectorAll('#emailOtpInputs input');
  const mobileOtpInputs = document.querySelectorAll('#mobileOtpInputs input');
  const verifyOtpBtn = document.getElementById('verifyOtpBtn');
  const otpError = document.getElementById('otpError');

  let generatedEmailOtp = '';
  let generatedMobileOtp = '';

  dobInput.max = new Date().toISOString().split('T')[0]; // max DOB today

  // Validation functions
  function validateName() {
    if (!nameInput.value.trim()) {
      nameError.textContent = 'Name is required'; return false;
    }
    nameError.textContent = ''; return true;
  }

  function validateGender() {
    if (!genderSelect.value) {
      genderError.textContent = 'Please select gender'; return false;
    }
    genderError.textContent = ''; return true;
  }

  function validateAge() {
    const age = parseInt(ageInput.value, 10);
    if (!ageInput.value.trim() || isNaN(age) || age < 1 || age > 120) {
      ageError.textContent = 'Please enter a valid age between 1 and 120'; return false;
    }
    ageError.textContent = ''; return true;
  }

  function validateDob() {
    if (!dobInput.value) {
      dobError.textContent = 'Date of birth is required'; return false;
    }
    const dob = new Date(dobInput.value);
    if (dob > new Date()) {
      dobError.textContent = 'Date of Birth cannot be in the future'; return false;
    }
    dobError.textContent = ''; return true;
  }

  function validateEmail() {
    let val = emailInput.value.trim();
    let regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!val) { emailError.textContent = 'Email is required'; return false; }
    if (!regex.test(val)) { emailError.textContent = 'Enter a valid email'; return false; }
    emailError.textContent = ''; return true;
  }

  function validateMobile() {
    let val = mobileInput.value.trim();
    let regex = /^\d{10}$/;
    if (!val) { mobileError.textContent = 'Mobile number is required'; return false; }
    if (!regex.test(val)) { mobileError.textContent = 'Enter a 10-digit mobile number'; return false; }
    mobileError.textContent = ''; return true;
  }

  function validatePassword() {
    const pwd = passwordInput.value;
    if (!pwd) { passwordError.textContent = 'Password is required'; return false; }
    if (pwd.length < 6) { passwordError.textContent = 'Password must be at least 6 characters'; return false; }
    passwordError.textContent = ''; return true;
  }

  function validateConfirmPassword() {
    const pwd = passwordInput.value;
    const cpwd = confirmPasswordInput.value;
    if (!cpwd) { confirmPasswordError.textContent = 'Confirm your password'; return false; }
    if (pwd !== cpwd) { confirmPasswordError.textContent = 'Passwords do not match'; return false; }
    confirmPasswordError.textContent = ''; return true;
  }

  // Enable next button only if all fields valid
  function validateForm() {
    const valid = validateName() && validateGender() && validateAge() && validateDob()
                && validateEmail() && validateMobile()
                && validatePassword() && validateConfirmPassword();
    nextBtn.disabled = !valid;
    return valid;
  }

  // Real-time validation triggers
  [nameInput, genderSelect, ageInput, dobInput, emailInput, mobileInput, passwordInput, confirmPasswordInput]
    .forEach(el => el.addEventListener('input', validateForm));

  // OTP inputs auto-focus
  function setupOtpInputs(inputs) {
    inputs.forEach((input, idx) => {
      input.addEventListener('input', e => {
        if (e.target.value.length === 1 && idx < inputs.length - 1) inputs[idx + 1].focus();
        checkOtpFilled();
      });
      input.addEventListener('keydown', e => {
        if (e.key === 'Backspace' && input.value === '' && idx > 0) inputs[idx - 1].focus();
      });
    });
  }
  function clearOtpInputs(inputs) { inputs.forEach(i => i.value = ''); }

  setupOtpInputs(emailOtpInputs);
  setupOtpInputs(mobileOtpInputs);

  // Enable verify button only when all OTP boxes are filled
  function checkOtpFilled() {
    const emailFilled = Array.from(emailOtpInputs).every(i => i.value.length === 1);
    const mobileFilled = Array.from(mobileOtpInputs).every(i => i.value.length === 1);
    verifyOtpBtn.disabled = !(emailFilled && mobileFilled);
    otpError.textContent = '';
  }

  // Generate simple 4-digit OTP
  function generateOtp() { return Math.floor(1000 + Math.random() * 9000).toString(); }

  // On Next button clicked: validate & generate OTPs & transition
  nextBtn.addEventListener('click', () => {
    if (!validateForm()) return;

    generatedEmailOtp = generateOtp();
    generatedMobileOtp = generateOtp();

    alert(`OTP sent to Email: ${generatedEmailOtp}\nOTP sent to Mobile: ${generatedMobileOtp}`);

    step1.classList.add('animate__fadeOutLeft');
    setTimeout(() => {
      step1.classList.add('hidden');
      step1.classList.remove('animate__fadeOutLeft', 'animate__fadeIn');
      step2.classList.remove('hidden');
      step2.classList.add('animate__fadeInRight');
      clearOtpInputs(emailOtpInputs);
      clearOtpInputs(mobileOtpInputs);
      verifyOtpBtn.disabled = true;
    }, 500);
  });

  // On Verify OTP: check match then register backend
  verifyOtpBtn.addEventListener('click', () => {
    const enteredEmailOtp = Array.from(emailOtpInputs).map(i => i.value).join('');
    const enteredMobileOtp = Array.from(mobileOtpInputs).map(i => i.value).join('');

    if (enteredEmailOtp === generatedEmailOtp && enteredMobileOtp === generatedMobileOtp) {
      // Send registration data to backend
      registerUser();
    } else {
      otpError.textContent = 'Invalid OTP(s), please try again.';
      step2.classList.add('animate__shakeX');
      setTimeout(() => step2.classList.remove('animate__shakeX'), 600);
    }
  });

  function registerUser() {
    const data = {
      name: nameInput.value.trim(),
      gender: genderSelect.value,
      age: ageInput.value,
      dob: dobInput.value,
      email: emailInput.value.trim(),
      mobile: mobileInput.value.trim(),
      password: passwordInput.value
    };

    fetch('register.php', {
      method: 'POST',
      headers: {'Content-Type': 'application/json'},
      body: JSON.stringify(data)
    })
    .then(res => res.text())
    .then(resp => {
      if(resp.trim().toLowerCase().includes('success')) {
        step2.classList.add('animate__fadeOutLeft');
        setTimeout(() => {
          step2.classList.add('hidden');
          step2.classList.remove('animate__fadeOutLeft', 'animate__fadeInRight');
          step3.classList.remove('hidden');
          step3.classList.add('animate__fadeIn');
        }, 500);  
      } else {
        otpError.textContent = 'Registration failed: ' + resp;
      }
    })
    .catch(() => {
      otpError.textContent = 'Registration failed due to network error.';
    });
  }
</script>

</body>
</html>
