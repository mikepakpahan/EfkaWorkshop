// Bungkus SEMUA kode di dalam event listener ini
document.addEventListener("DOMContentLoaded", function () {
  // --- BAGIAN 1: KODE UI ANDA ---
  const navToggle = document.getElementById("navToggle");
  const mobileNav = document.getElementById("mobileNav");
  if (navToggle && mobileNav) {
    navToggle.addEventListener("click", function () {
      mobileNav.classList.toggle("open");
    });
    mobileNav.querySelectorAll("a").forEach((link) => {
      link.addEventListener("click", () => {
        mobileNav.classList.remove("open");
      });
    });
  }

  document.querySelectorAll(".feature-card").forEach((card) => {
    card.addEventListener("mouseenter", function () {
      this.classList.add("feature-card-hover");
    });
    card.addEventListener("mouseleave", function () {
      this.classList.remove("feature-card-hover");
    });
    card.addEventListener("click", function () {
      document.querySelectorAll(".feature-card").forEach((c) => c.classList.remove("feature-card-active"));
      this.classList.add("feature-card-active");
    });
  });

  // --- BAGIAN 2: KODE BOOKING FORM ---
  const bookingForm = document.querySelector(".booking-form");
  if (bookingForm) {
    bookingForm.addEventListener("submit", function (event) {
      event.preventDefault();

      const submitButton = bookingForm.querySelector('button[type="submit"]');
      submitButton.disabled = true;
      submitButton.textContent = "Mengirim...";

      fetch("backend/proses_booking.php", {
        method: "POST",
        body: new FormData(bookingForm),
      })
        .then((response) => response.json())
        .then((data) => {
          alert(data.message);
          if (data.status === "success") {
            bookingForm.reset();
          }
        })
        .catch((error) => {
          console.error("Error:", error);
          alert("Terjadi masalah koneksi. Silakan coba lagi.");
        })
        .finally(() => {
          submitButton.disabled = false;
          submitButton.textContent = "Kirim Jadwal Booking";
        });
    });
  }
});
// Fungsi goHome bisa di luar atau di dalam, tapi lebih aman jika terdefinisi global jika dipanggil dari HTML onclick
function goHome() {
  window.scrollTo({ top: 0, behavior: "smooth" });
}
