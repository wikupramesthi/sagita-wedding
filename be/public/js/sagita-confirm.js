window.sagitaConfirm = function (options = {}) {
    const config = typeof options === 'string' ? { name: options } : options;
    const safe = config.variant === 'success' || config.variant === 'unlock';
    const title = config.title || (safe ? 'Konfirmasi' : 'Konfirmasi Penghapusan');
    const message = config.message || (config.name
        ? `${config.name} akan dihapus permanen dan tidak dapat dikembalikan.`
        : 'Data ini akan dihapus permanen dan tidak dapat dikembalikan.');
    const confirmClass = safe ? 'sagita-swal-confirm sagita-swal-confirm--success' : 'sagita-swal-confirm';

    return Swal.fire({
        title,
        text: message,
        icon: config.icon || (safe ? 'question' : 'warning'),
        showCancelButton: true,
        confirmButtonText: config.confirmText || (safe ? 'Ya, Lanjutkan' : 'Ya, Hapus'),
        cancelButtonText: 'Batal',
        reverseButtons: true,
        focusCancel: true,
        focusConfirm: false,
        confirmButtonColor: safe ? '#22c55e' : '#f5365c',
        customClass: {
            popup: 'sagita-swal-popup',
            title: 'sagita-swal-title',
            htmlContainer: 'sagita-swal-text',
            icon: 'sagita-swal-icon',
            actions: 'sagita-swal-actions',
            confirmButton: confirmClass,
            cancelButton: 'sagita-swal-cancel',
        },
    });
};

window.sagitaConfirmDelete = window.sagitaConfirm;
