document.addEventListener('DOMContentLoaded', function () {
    const pelanggan = document.querySelector('[name="pelanggan_id"]');
    const kendaraan = document.querySelector('#kendaraan_id');

    if (pelanggan && kendaraan) {
        function filterKendaraan() {
            const idPelanggan = pelanggan.value;

            [...kendaraan.options].forEach(option => {
                if (!option.value) return;

                option.hidden = idPelanggan &&
                    option.dataset.pelanggan !== idPelanggan;
            });

            if (
                kendaraan.selectedOptions.length &&
                kendaraan.selectedOptions[0].hidden
            ) {
                kendaraan.value = '';
            }
        }

        pelanggan.addEventListener('change', filterKendaraan);
        filterKendaraan();
    }
});
