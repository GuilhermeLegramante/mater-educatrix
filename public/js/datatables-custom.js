$.extend(true, $.fn.dataTable.defaults, {
    responsive: true,
    pageLength: 25,
    ordering: true,
    searching: true,
    autoWidth: false,
    language: {
        url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json'
    },
    dom:
        "<'flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4'<'flex-1'f><'flex'i>>" +
        "rt" +
        "<'flex flex-col md:flex-row md:items-center md:justify-between gap-4 mt-4'<'flex-1'l><'flex'p>>"
});