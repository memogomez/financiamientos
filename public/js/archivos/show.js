$(document).ready(function () {
  $('#archivos-table').DataTable({
    destroy: true,
    processing: true,
    serverSide: true,
    order: [],
    ordering: false,
    responsive: true,
    ajax: {
      url: urlArchivosPaginate,
    },
    columns: [
      { data: 'nombre_archivo', name: 'nombre_archivo', width: '30%', searchable: true },
      { data: 'numero_oficio',  name: 'numero_oficio',  width: '15%', searchable: true },
      { data: 'nombre_area',    name: 'nombre_area',    width: '20%', searchable: true },
      { data: 'fecha_archivo',  name: 'fecha_archivo',  width: '12%', searchable: false },
      { data: 'fecha_subida',   name: 'fecha_subida',   width: '12%', searchable: false },
      {
        data: null,
        width: '11%',
        searchable: false,
        className: 'text-center',
        render: function (data) {
          return `<a href="${context}/archivos/descargar/${data.archivo_fisico}" class="btn btn-sm btn-info" title="Descargar">Descargar</a>`;
        },
      },
    ],
    pageLength: 10,
    lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
    language: {
      lengthMenu: 'Mostrando _MENU_ registros por página',
      zeroRecords: 'No se ha encontrado información',
      emptyTable: 'No hay archivos registrados',
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
