<script type='text/javascript'>
$(document).ready(function () {
    $('#datatable').DataTable({
        responsive: true,
        "pageLength": 21,
        "columnDefs": [{
            "targets": 4,
            "orderable": false
        }, {
            "targets": 5,
            "orderable": false
        }],
        "language": {
            "lengthMenu": "Zeige _MENU_ Einträge pro Seite",
            "zeroRecords": "Es wurden keine passenden Einträge gefunden",
            "info": "Zeige Seite _PAGE_ von _PAGES_",
            "infoEmpty": "Keine Einträge verfügbar",
            "infoFiltered": "(gefiltert aus _MAX_ Einträgen)"
        }
    });
});
</script>