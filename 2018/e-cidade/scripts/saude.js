
/**
 * Valida se o departamento logado é uma UPS
 * @param  {HTMLFormElement} oForm         Object form
 * @param  {boolean}         lBloquearForm
 * @return {boolean}
 */
function validarDepartamentoUPS( oForm, lBloquearForm ) {

  var lRetorno = true;
  var oAjax    = new AjaxRequest('amb4_saude.RPC.php ', {exec: 'validaDepartamentoUPS'}, function (oRetorno, lErro) {

    if (lErro) {

      if (lBloquearForm) {
        setFormReadOnly(oForm, true);
      }
      alert(oRetorno.sMessage.urlDecode());
      lRetorno = false;
    }

  });
  oAjax.asynchronous(false);
  oAjax.execute();

  return lRetorno;
}