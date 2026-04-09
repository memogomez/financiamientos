$( document ).ready(function(){
	$( '#historicos-table' ).DataTable({
		destroy: true,
		processing: true,
		serverSide: true,
		order: [],
    ordering: false,
    responsive: true,
		ajax: {
			url: urlHistoricosPaginate,
		},
		columns: [
			{ data: 'consecutivo', name: 'consecutivo', width: '10%', sercheable: true },
			{ data: 'nombre_solicitante', name: 'nombre_solicitante', width: '35%', sercheable: true },
			{ data: 'agencia_mp', name: 'agencia_mp', width: '35%', sercheable: true },
			{ data: 'fecha', name: 'fecha', width: '10%', sercheable: true },
			{ 
        data: null, 
				width: '10%',
        render: function ( data ) {
          return `<a href="${ context }/historicos/${ data.id_historico }/detail" class="btn btn-info">Consultar</a>`;
        },
      },
		],
		pageLength: 10,
		lengthMenu: [ [ 10, 25, 50, 100 ], [ 10, 25, 50, 100 ] ],
		language: {
			lengthMenu: 'Mostrando _MENU_ Registros por página',
			zeroRecords: 'No se ha encontrado información - Lo sentimos',
			emptyTable: 'No se ha encontrado información - Lo sentimos',
			info: 'Mostrando página _PAGE_ de _PAGES_',
			infoEmpty: 'No hay información disponible - Lo sentimos',
			infoFiltered: '(filtrado de _MAX_ registros totales)',
			search: 'Buscar:',
			paginate: {
				previous: "Anterior",
				next: "Siguiente"
			}
		}
	});
});
