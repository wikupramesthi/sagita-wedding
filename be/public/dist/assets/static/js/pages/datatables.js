const setTableColor = () => {
    document.querySelectorAll('.dataTables_paginate .pagination').forEach(dt => {
        dt.classList.add('pagination-primary')
    })
}
setTableColor()

const jqueryTable = $("#table1")
if (jqueryTable.length) {
    const jquery_datatable = jqueryTable.DataTable({
        responsive: true,
        language: {
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            infoEmpty: "Menampilkan 0 dari 0 data",
            infoFiltered: " (difilter dari _MAX_ total data)",
            lengthMenu: "Tampilkan _MENU_ data",
            search: "Cari:",
            searchPlaceholder: "Cari data...",
            zeroRecords: "Data tidak ditemukan",
            paginate: {
                first: "Pertama",
                last: "Terakhir",
                next: "Berikutnya",
                previous: "Sebelumnya"
            }
        }
    })
    jquery_datatable.on('draw', setTableColor)
}

const customizedTable = $("#table2")
if (customizedTable.length) {
    customizedTable.DataTable({
        responsive: true,
        pagingType: 'simple',
        dom:
            "<'row'<'col-3'l><'col-9'f>>" +
            "<'row dt-row'<'col-sm-12'tr>>" +
            "<'row'<'col-4'i><'col-8'p>>",
        language: {
            info: "Halaman _PAGE_ dari _PAGES_",
            lengthMenu: "_MENU_ data",
            search: "Cari:",
            searchPlaceholder: "Cari data..."
        }
    })
}
