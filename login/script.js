const container = document.getElementById("container");
const registerBtn = document.getElementById("register");
const loginBtn = document.getElementById("login");

registerBtn.addEventListener("click", () => {
  container.classList.add("active");
});

loginBtn.addEventListener("click", () => {
  container.classList.remove("active");
});

// LOGIN
const loginForm = document.getElementById("loginForm");
if (loginForm) {
  loginForm.addEventListener("submit", function (e) {
    e.preventDefault();
    const email = loginForm.querySelector('input[type="email"]').value;
    const password = loginForm.querySelector('input[type="password"]').value;
    fetch("../backend/auth/login.php", {
      method: "POST",
      body: new URLSearchParams({ email, password }),
    })
      .then((res) => res.json())
      .then((data) => {
        if (data.success) {
          window.location.href = "../index.html";
        } else {
          alert(data.error || "Login gagal!");
        }
      })
      .catch(() => alert("Gagal login, coba lagi."));
  });
}

// REGISTER
const registerForm = document.getElementById("registerForm");
if (registerForm) {
  registerForm.addEventListener("submit", function (e) {
    e.preventDefault();
    const name = registerForm.querySelector('input[placeholder="Name"]').value;
    const email = registerForm.querySelector('input[type="email"]').value;
    const password = registerForm.querySelector('input[type="password"]').value;
    fetch("../backend/auth/register.php", {
      method: "POST",
      body: new URLSearchParams({ name, email, password }),
    })
      .then((res) => res.json())
      .then((data) => {
        if (data.success) {
          alert("Registrasi berhasil, silakan login.");
          document.getElementById("container").classList.remove("right-panel-active");
        } else {
          alert(data.error || "Registrasi gagal!");
        }
      })
      .catch(() => alert("Gagal daftar, coba lagi."));
  });
}
