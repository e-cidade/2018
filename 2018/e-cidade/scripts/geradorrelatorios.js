
function js_imprimeRelatorio(iCodRelatorio,sCallBackFunction,aParametros){

  js_divCarregando('Aguarde, Gerando Relatório ...','msgBox');

  var sQuery  = "iCodRelatorio="+iCodRelatorio;
  sQuery += "&aParametros="+encodeURIComponent(aParametros);

  var url   = "sys4_processarelatorioRPC.php";
  var oAjax = new Ajax.Request(url, 
    {
      method: 'post', 
      parameters: sQuery,
      onComplete: sCallBackFunction  
    }
  );
}

function js_criaObjetoVariavel(sNome,sValor){

  this.sNome  = sNome;   
  this.sValor = sValor;
}


function js_downloadArquivo(oAjax){

  js_removeObj("msgBox");

  var sRetorno = eval("("+oAjax.responseText+")");

  if (sRetorno.erro == true){
    alert(sRetorno.sMsg.urlDecode());
  } else {
    var sArquivos = sRetorno.sMsg.urlDecode();
    jan = window.open(sArquivos,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    jan.moveTo(0,0);
  }

}	

