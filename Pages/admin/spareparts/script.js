document.addEventListener("DOMContentLoaded", function () {
  const orderList = document.querySelector(".order-list");

  if (orderList) {
    orderList.addEventListener("click", function (event) {
      const button = event.target.closest("button");
      if (!button) return;

      const orderId = button.dataset.orderid;

      // Logika untuk tombol ACCEPT
      if (button.classList.contains("btn-accept")) {
        if (confirm(`Proses pesanan dengan ID ${orderId} dan pindahkan ke riwayat?`)) {
          // Arahkan ke skrip 'accept' dengan membawa ID
          window.location.href = "/EfkaWorkshop/backend/proses_accept_order.php?id=" + orderId;
        }
      }

      // Logika untuk tombol DELETE (CANCEL)
      if (button.classList.contains("btn-delete")) {
        if (confirm(`Anda yakin ingin MEMBATALKAN pesanan dengan ID ${orderId}?`)) {
          // Arahkan ke skrip 'cancel' dengan membawa ID
          window.location.href = "/EfkaWorkshop/backend/proses_cancel_order.php?id=" + orderId;
        }
      }
    });
  }
});
