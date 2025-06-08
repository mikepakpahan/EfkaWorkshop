function formatRupiah(number) {
  return "Rp" + number.toLocaleString("id-ID");
}

const orderQuantities = {}; // {id: qty}

document.addEventListener("DOMContentLoaded", function () {
  // Tampilkan overlay login/register
  const loginOverlay = document.getElementById("loginOverlay");
  const container = document.getElementById("container");
  const loginBtnNav = document.getElementById("loginBtn");
  const daftarBtnNav = document.getElementById("daftarBtn");
  const registerBtn = document.getElementById("register");
  const loginBtn = document.getElementById("login");

  // Tampilkan modal login
  loginBtnNav.addEventListener("click", function (e) {
    e.preventDefault();
    loginOverlay.style.display = "flex";
    container.classList.remove("active");
  });

  // Tampilkan modal daftar
  daftarBtnNav.addEventListener("click", function (e) {
    e.preventDefault();
    loginOverlay.style.display = "flex";
    container.classList.add("active");
  });

  // Toggle panel dari dalam modal
  registerBtn.addEventListener("click", function (e) {
    e.preventDefault();
    container.classList.add("active");
  });
  loginBtn.addEventListener("click", function (e) {
    e.preventDefault();
    container.classList.remove("active");
  });

  // Klik di luar modal untuk menutup overlay
  loginOverlay.addEventListener("click", function (e) {
    if (e.target === this) {
      loginOverlay.style.display = "none";
    }
  });
  document.getElementById("checkoutBtn").onclick = async function () {
    // Kumpulkan data pesanan
    const services = [];
    const spareparts = [];
    document.querySelectorAll(".item-servis.selected").forEach((item) => {
      services.push(item.getAttribute("data-id"));
    });
    document.querySelectorAll(".sparepart-item.selected").forEach((item) => {
      const id = item.getAttribute("data-id");
      const qty = orderQuantities[id] || 1;
      spareparts.push({ id, qty });
    });

    // (Contoh) Kirim ke backend untuk buat order
    // Ganti dengan data sebenarnya sesuai kebutuhanmu
    const res = await fetch("backend/orders/create_order.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        services,
        spareparts,
        // schedule: "2025-06-03 10:00:00", // tambahkan jika perlu
      }),
    });
    const data = await res.json();

    if (data.success && data.order_id) {
      // Format order id: FK-yyyymmdd-[id]
      const today = new Date();
      const yyyymmdd = today.getFullYear().toString() + String(today.getMonth() + 1).padStart(2, "0") + String(today.getDate()).padStart(2, "0");
      const orderId = `FK-${yyyymmdd}-${data.order_id}`;

      // Generate QR code url
      const qrUrl = `https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=https://efka-workshop.com/bayar?id=${orderId}`;

      // Tampilkan QR dan Order ID
      document.getElementById("qrImg").src = qrUrl;
      document.getElementById("orderId").textContent = orderId;
      document.getElementById("qrSection").classList.remove("hidden");
      // Sembunyikan tombol checkout
      document.getElementById("checkoutBtn").style.display = "none";
      // Scroll ke QR
      document.getElementById("qrSection").scrollIntoView({ behavior: "smooth" });

      // Disable all item-servis & sparepart-item
      document.querySelectorAll(".item-servis, .sparepart-item").forEach((item) => {
        item.style.pointerEvents = "none";
        item.style.opacity = "0.6"; // opsional, biar kelihatan disabled
      });

      // Sembunyikan tombol increase/decrease quantity
      document.querySelectorAll(".increase, .decrease").forEach((btn) => {
        btn.style.display = "none";
      });
    } else {
      alert("Gagal membuat order!");
    }
  };

  fetch("backend/services/get_services.php")
    .then((res) => res.json())
    .then((data) => {
      const container = document.getElementById("servisList");
      container.innerHTML = "";
      data.forEach((servis) => {
        const div = document.createElement("div");
        div.className = "bg-[#d9d9d9] rounded-2xl w-52 h-52 p-5 flex flex-col items-center justify-center text-center shadow item-servis cursor-pointer";
        div.setAttribute("data-id", servis.id);
        div.setAttribute("data-name", servis.name);
        div.setAttribute("data-price", servis.price);
        // Pilih gambar sesuai nama servis (optional, bisa diatur sesuai kebutuhan)
        let imgSrc = "assets/icon-Servis.png";
        if (servis.name.toLowerCase().includes("oli")) imgSrc = "assets/icon-Ganti-Oli.png";
        else if (servis.name.toLowerCase().includes("tune")) imgSrc = "assets/icon-Tune-Up.png";
        else if (servis.name.toLowerCase().includes("rem")) imgSrc = "assets/icon-Servis-Rem.png";
        div.innerHTML = `
          <img src="${imgSrc}" alt="${servis.name}" class="h-14 mb-3" />
          <h4 class="font-bold text-xl">${servis.name}</h4>
          <p class="text-lg">Rp ${servis.price.toLocaleString("id-ID")}</p>
        `;
        container.appendChild(div);
      });
      attachItemListeners();
    });

  // Render sparepart dari database
  fetch("backend/spareparts/get_spareparts.php")
    .then((res) => res.json())
    .then((data) => {
      const container = document.getElementById("sparepartList");
      if (!container) return;
      container.innerHTML = "";
      data.forEach((sparepart) => {
        const div = document.createElement("div");
        div.className = "bg-[#d9d9d9] rounded-2xl w-52 h-52 p-4 flex flex-col justify-between text-center shadow sparepart-item cursor-pointer transition-all duration-200";
        div.setAttribute("data-id", sparepart.id);
        div.setAttribute("data-name", sparepart.name);
        div.setAttribute("data-price", sparepart.price);
        div.innerHTML = `
          <div class="flex-1 flex items-center justify-center pt-2">
            <img src="assets/${sparepart.name
              .toLowerCase()
              .replace(/\s/g, "-")
              .replace(/[^\w-]/g, "")}.png" alt="${sparepart.name}" class="h-32 w-32 object-contain" />
          </div>
          <div class="pb-2">
            <h4 class="font-bold text-lg mb-1">${sparepart.name}</h4>
            <p class="text-red-600 font-bold text-base">Rp ${sparepart.price.toLocaleString("id-ID")}</p>
          </div>
        `;
        container.appendChild(div);
      });
      attachItemListeners();
    });

  // Handler select/unselect item servis & sparepart (jika ada item-servis statis/dinamis)
  attachItemListeners();

  // Cek status login untuk header
  fetch("backend/utils/is_logged_in.php").then((res) => {
    if (res.ok) {
      // Sudah login: sembunyikan login/daftar, tampilkan logout
      document.getElementById("loginBtn").style.display = "none";
      document.getElementById("daftarBtn").style.display = "none";
      document.getElementById("logoutBtn").style.display = "inline-block";
    } else {
      // Belum login: tampilkan login/daftar, sembunyikan logout
      document.getElementById("loginBtn").style.display = "inline-block";
      document.getElementById("daftarBtn").style.display = "inline-block";
      document.getElementById("logoutBtn").style.display = "none";
    }
  });

  // Logout handler
  const logoutBtn = document.getElementById("logoutBtn");
  if (logoutBtn) {
    logoutBtn.onclick = function () {
      fetch("backend/auth/logout.php").then(() => window.location.reload());
    };
  }
});

function attachItemListeners() {
  document.querySelectorAll(".item-servis, .sparepart-item").forEach((item) => {
    item.onclick = function () {
      const id = item.getAttribute("data-id");
      if (item.classList.toggle("selected")) {
        orderQuantities[id] = 1;
      } else {
        delete orderQuantities[id];
      }
      updateOrderTable();
    };
  });
}

// Cek login sebelum aksi tertentu
function checkLoginBeforeAction(callback) {
  fetch("backend/utils/is_logged_in.php")
    .then((res) => {
      if (res.ok) {
        callback();
      } else {
        alert("Harus login dulu untuk melakukan aksi ini.");
        if (confirm("Mau login sekarang?")) {
          window.location.href = "login.html";
        }
      }
    })
    .catch(() => {
      alert("Gagal memeriksa status login");
    });
}

// Update tabel pesanan sesuai item yang di-select
function updateOrderTable() {
  const orderBody = document.querySelector("#productTable tbody");
  // Hapus semua baris kecuali baris total
  orderBody.querySelectorAll("tr:not(.bg-gray-100)").forEach((row) => row.remove());

  let grandTotal = 0;

  // Gabungkan item servis & sparepart yang di-select
  const selectedServis = document.querySelectorAll(".item-servis.selected");
  const selectedSpareparts = document.querySelectorAll(".sparepart-item.selected");

  // Tampilkan servis (jumlah selalu 1, tanpa tombol)
  selectedServis.forEach((item) => {
    const name = item.getAttribute("data-name");
    const price = parseInt(item.getAttribute("data-price"));
    grandTotal += price;

    const tr = document.createElement("tr");
    tr.innerHTML = `
      <td class="py-3 px-4">${name}</td>
      <td class="text-center py-3 px-4">1</td>
      <td class="text-right py-3 px-4">Rp${price.toLocaleString("id-ID")}</td>
      <td class="text-right py-3 px-4 total-price">Rp${price.toLocaleString("id-ID")}</td>
    `;
    orderBody.insertBefore(tr, orderBody.querySelector(".bg-gray-100"));
  });

  // Tampilkan sparepart (jumlah bisa diubah)
  selectedSpareparts.forEach((item) => {
    const id = item.getAttribute("data-id");
    const name = item.getAttribute("data-name");
    const price = parseInt(item.getAttribute("data-price"));
    const qty = orderQuantities[id] || 1;
    const total = price * qty;
    grandTotal += total;

    const tr = document.createElement("tr");
    tr.innerHTML = `
      <td class="py-3 px-4">${name}</td>
      <td class="text-center py-3 px-4">
        <div class="flex items-center justify-center gap-2">
          <button class="decrease w-6 h-6 bg-red-500 text-white rounded-full text-xs font-bold hover:bg-red-600" data-id="${id}">-</button>
          <span class="inline-flex items-center justify-center w-8 h-6 bg-gray-800 text-white rounded text-xs font-bold quantity">${qty}</span>
          <button class="increase w-6 h-6 bg-green-500 text-white rounded-full text-xs font-bold hover:bg-green-600" data-id="${id}">+</button>
        </div>
      </td>
      <td class="text-right py-3 px-4">Rp${price.toLocaleString("id-ID")}</td>
      <td class="text-right py-3 px-4 total-price">Rp${total.toLocaleString("id-ID")}</td>
    `;
    orderBody.insertBefore(tr, orderBody.querySelector(".bg-gray-100"));
  });

  document.getElementById("grandTotal").textContent = "Rp" + grandTotal.toLocaleString("id-ID");

  // Event listener untuk tombol +/- hanya untuk sparepart
  orderBody.querySelectorAll(".increase").forEach((btn) => {
    btn.onclick = function (e) {
      const id = btn.getAttribute("data-id");
      orderQuantities[id]++;
      updateOrderTable();
    };
  });
  orderBody.querySelectorAll(".decrease").forEach((btn) => {
    btn.onclick = function (e) {
      const id = btn.getAttribute("data-id");
      if (orderQuantities[id] > 1) {
        orderQuantities[id]--;
      } else {
        // Jika qty jadi 0, unselect item dan hapus dari order
        orderQuantities[id] = 1;
        const item = document.querySelector(`.sparepart-item[data-id="${id}"]`);
        if (item) {
          item.classList.remove("selected");
          delete orderQuantities[id];
        }
      }
      updateOrderTable();
    };
  });
}
