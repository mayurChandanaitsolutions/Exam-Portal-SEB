// ── index Page JS ────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', function () {

  const form    = document.getElementById('loginForm');
  const btn     = document.getElementById('loginBtn');
  const pwdInput = document.getElementById('password');
  const togglePwd = document.getElementById('togglePwd');
  const pwdIcon   = document.getElementById('pwdIcon');

  // Password visibility toggle
  togglePwd.addEventListener('click', function () {
    const type = pwdInput.type === 'password' ? 'text' : 'password';
    pwdInput.type = type;
    pwdIcon.className = type === 'text' ? 'fas fa-eye-slash' : 'fas fa-eye';
  });

  // Input icon colour on focus
  document.querySelectorAll('.input-wrap .form-ctrl').forEach(input => {
    input.addEventListener('focus', function () {
      const icon = this.parentElement.querySelector(':scope > i');
      if (icon) icon.style.color = 'var(--navy-500)';
    });
    input.addEventListener('blur', function () {
      if (!this.value) {
        const icon = this.parentElement.querySelector(':scope > i');
        if (icon) icon.style.color = '';
      }
    });
  });

  // Form submit
  form.addEventListener('submit', function (e) {
    e.preventDefault();
    const userId   = document.getElementById('userId').value.trim();
    const password = pwdInput.value.trim();

    if (!userId || !password) {
      alert('Please enter both User ID and Password.');
      return;
    }

    btn.innerHTML  = '<i class="fas fa-spinner fa-spin me-2"></i> Signing In…';
    btn.disabled   = true;

    setTimeout(() => {
      alert('Login Successful!');
      window.location.href = 'profile.html';
    }, 1200);
  });

  // Enter key
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Enter') btn.click();
  });

  // Auto-focus
  document.getElementById('userId').focus();
});
