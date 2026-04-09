$( document ).ready( function() {
  $( '#id_delito' ).select2();
});

document.addEventListener("DOMContentLoaded", function(event) {
  setAnio();
  setAcronimo();
});

const setAnio = () => {
  const fechaActual = new Date();
  const anioActual = fechaActual.getFullYear();
  document.getElementById( 'anio' ).value = anioActual;
}

const setAcronimo = () => {
  document.getElementById( 'acronimo' ).value = 'JRDS';
}
