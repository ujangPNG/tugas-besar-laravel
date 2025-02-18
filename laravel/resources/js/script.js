import Swal from "sweetalert2";

function confirmSubmit() {
    Swal.fire({
        title: 'Konfirmasi Pembuatan Lelang',
        text: "Apakah anda yakin ingin membuat lelang ini?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Buat Lelang!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('createAuctionForm').submit();
        }
    });
} 
function berhasil() {
    Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: 'Data berhasil disimpan',
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('createAuctionForm').submit();
        }
    });
}

document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('createAuctionButton').addEventListener('click', confirmSubmit);
});