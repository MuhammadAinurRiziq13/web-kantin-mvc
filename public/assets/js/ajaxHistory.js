$(".edit").on("click", function () {
  const id_transaksi = $(this).data("id");
  const url = $(this).data("url");

  $.ajax({
    url: url + "/History/detailHistory",
    data: { id_transaksi: id_transaksi },
    method: "post",
    dataType: "json",
    success: function (data) {
      console.log(data);
      // Populate the modal with data
      populateModalWithDataDetail(data);
    },
  });
});

// Function to populate modal with data
function populateModalWithDataDetail(data) {
  const tbody = $("#detailTable tbody");
  const totalHargaElement = $("#totalHarga");

  tbody.empty(); // Clear existing rows

  // Loop through the data and append rows to the table
  data.forEach(function (item) {
    const row = `<tr>
                            <td>${item.nama_barang}</td>
                            <td>Rp. ${item.harga_jual}</td>
                            <td>${item.qty}</td>
                            <td>Rp. ${item.total_harga}</td>
                        </tr>`;
    tbody.append(row);
  });

  // Calculate total harga
  const totalHarga = data.reduce((sum, item) => sum + item.total_harga, 0);

  // Update the total harga in the modal
  totalHargaElement.text(`Rp. ${totalHarga}`);

  // Show the modal
  $("#detailModal").modal("show");
}
