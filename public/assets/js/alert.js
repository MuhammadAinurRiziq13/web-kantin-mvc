function confirmDelete(event) {
  event.preventDefault();

  Swal.fire({
    title: "Apakah Anda Yakin?",
    text: "Anda tidak dapat mengembalikannya!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#d33",
    cancelButtonColor: "#3085d6",
    confirmButtonText: "Hapus",
    cancelButtonText: "Batal",
    reverseButtons: true,
  }).then((result) => {
    if (result.isConfirmed) {
      // If the user confirms, proceed with the deletion by navigating to the specified URL
      window.location.href = event.target.href;
    }
  });
}
