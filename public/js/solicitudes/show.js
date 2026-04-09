$(document).ready(function () {
  $('#solicitudes-table').DataTable({
    destroy: true,
    processing: true,
    serverSide: true,
    order: [],
    ordering: false,
    responsive: true,
    ajax: {
      url: urlSolicitudesPaginate,
    },
    columns: [
      { data: 'nombre_area', name: 'nombre_area', width: '20%', searchable: true },
      { data: 'solicita',    name: 'solicita',    width: '22%', searchable: true },
      { data: 'dirigido',    name: 'dirigido',    width: '22%', searchable: true },
      { data: 'fecha',       name: 'fecha',       width: '10%', searchable: true },
      {
        data: 'comprobacion',
        name: 'comprobacion',
        width: '8%',
        searchable: false,
        className: 'text-center',
        render: function (data) {
          if (parseInt(data) === 1) {
            return '<span style="color:#28a745;font-size:1.4rem;" title="Con comprobación">&#10003;</span>';
          }
          return '<span style="color:#dc3545;font-size:1.4rem;" title="Sin comprobación">&#10007;</span>';
        },
      },
      {
        data: null,
        width: '9%',
        searchable: false,
        render: function (data) {
          return `<a href="${context}/solicitudes/${data.id}/edit?modo=oficios" class="btn btn-sm btn-secondary">Oficios</a>`;
        },
      },
      {
        data: null,
        width: '9%',
        searchable: false,
        render: function (data) {
          return `<a href="${context}/solicitudes/${data.id}/edit" class="btn btn-sm btn-warning">Editar</a>`;
        },
      },
    ],
    pageLength: 10,
    lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
    language: {
      lengthMenu: 'Mostrando _MENU_ registros por página',
      zeroRecords: 'No se ha encontrado información',
      emptyTable: 'No hay solicitudes registradas',
      info: 'Mostrando página _PAGE_ de _PAGES_',
      infoEmpty: 'No hay información disponible',
      infoFiltered: '(filtrado de _MAX_ registros totales)',
      search: 'Buscar:',
      paginate: {
        previous: 'Anterior',
        next: 'Siguiente',
      },
    },
  });
});
