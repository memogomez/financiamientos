$( document ).ready(function(){
	$( '#folios-table' ).DataTable({
		destroy: true,
		processing: true,
		serverSide: true,
		order: [],
    ordering: false,
    responsive: true,
		ajax: {
			url: urlFoliosPaginate,
			data: function( d ) {
				d.searchValue = $( '#tblVisitas_filter input' ).val();
			}
		},
		columns: [
			{ data: 'id_folio', name: 'id_folio', width: '10%', sercheable: true },
			{ data: 'ticket', name: 'ticket', width: '30%', sercheable: true },
			{ data: 'folio', name: 'folio', width: '30%', sercheable: true },
			{ data: 'created_at', name: 'created_at', width: '20%', sercheable: true },
      { 
        data: null, 
        render: function ( data ) {
          return `<a href="${ context }/folios/${ data.id_folio }" class="btn btn-info">Info</a>`;
        } 
      },
			{
				data:null,
				render: function( data ) {
					return `<a href="${ context }/folios/${ data.id_folio }/edit" class="btn btn-secondary">Editar</a>`;
				}
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
