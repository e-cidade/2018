<?php
/*
 *     E-cidade Software P�blico para Gest�o Municipal                
 *  Copyright (C) 2014  DBseller Servi�os de Inform�tica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa � software livre; voc� pode redistribu�-lo e/ou     
 *  modific�-lo sob os termos da Licen�a P�blica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a vers�o 2 da      
 *  Licen�a como (a seu crit�rio) qualquer vers�o mais nova.          
 *                                                                    
 *  Este programa e distribu�do na expectativa de ser �til, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia impl�cita de              
 *  COMERCIALIZA��O ou de ADEQUA��O A QUALQUER PROP�SITO EM           
 *  PARTICULAR. Consulte a Licen�a P�blica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU     
 *  junto com este programa; se n�o, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  C�pia da licen�a no diret�rio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

  require_once ("libs/db_stdlib.php");
  require_once ("libs/db_conecta.php");
  require_once ("libs/db_sessoes.php");
  require_once ("libs/db_usuariosonline.php");
  require_once ("libs/db_app.utils.php");
  require_once ("libs/db_utils.php");
  require_once ("dbforms/db_funcoes.php");

  // Valores padr�o do filtro
  $aRecursos      = array ( 'T' => 'Todos');

  // Filtro tipo de impressao
  $aTipoImpressao = array( ''  => 'SELECIONE',
                           'A' => 'Anal�tico',
                           'S' => 'Sint�tico'                           
                         );

  // Filtro tipo de impressao
  $aFormatoImpressao = array( ''  => 'SELECIONE',
                              'pdf' => 'PDF',
                              'csv' => 'CSV'                           
                            );

  $aQuebraBanco = array( '' => 'SELECIONE',
                         1  => 'Sim',
                         2  => 'N�o');
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBDownload.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/classes/DBViewFormularioFolha/CompetenciaFolha.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/geradorrelatorios.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>

  <body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#cccccc">
  <div class="container" style="width:700px !important;">
    
    <fieldset>
    
      <legend>L�quidos da Folha por Recurso</legend>

      <table border="0"  class="form-container">
              
        <tr>
          <td><strong>Recurso:</strong></td>
          <td><?php db_select('recurso', $aRecursos, true, 1) ?></td>
        </tr>
        <tr>
          <td><strong>Tipo de Impress�o:</strong></td>
          <td><?php db_select('tipoImpressao', $aTipoImpressao, true, 1, 'onChange="js_tipoImpressao()"') ?></td>
        </tr>
        <tr>
          <td><strong>Formato:</strong></td>
          <td><?php db_select('formatoImpressao', $aFormatoImpressao, true, 1) ?></td>
        </tr>       
        <tr id="trQuebraBanco">
          <td><strong>Quebra por Banco:</strong></td>
          <td><?php db_select('quebraBanco', $aQuebraBanco, true, 1) ?></td>
        </tr>       
        <tr>
          <td colspan="2">&nbsp;</td>
        </tr>

        <tr>
          <td style="text-align:center !important;" colspan="2"><strong>ESTE RELAT�RIO DEVE SER GERADO AP�S A <u>GERA��O EM DISCO</u></strong></td>
        </tr>       
       
      </table>
    
    </fieldset>
    
    <input type="submit" id="btGerar" name="btGerar" value="Gerar" onclick="js_processar();"/>
    
  </div>
    
    <?php
      db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
    ?>
  </body>
</html>

<script type="text/javascript">

  var sUrlRPC    = 'pes2_liquidosporrecurso.RPC.php';
   
 (function(){

    $('trQuebraBanco').style.display = 'none';

    var oDadosRequisicao          = new Object();
    oDadosRequisicao.method       = 'post';
    oDadosRequisicao.asynchronous = false;
    oDadosRequisicao.parameters   = 'json='+Object.toJSON({sExecucao:'getRecursos'});
    oDadosRequisicao.onComplete   = function(oAjax){
        
      var oRetorno = eval("("+oAjax.responseText+")");
      if (oRetorno.iStatus == "2") {

         /*
          * Desabilita Bot�o Gerar caso n�o existam recursos
          */
         $('btGerar').disabled = true;  
         alert(oRetorno.sMensagem.urlDecode());
         return;
      }

      var oSelect      = $("recurso");


      for ( var iRecurso = 0; iRecurso < oRetorno.aRecursos.length; iRecurso++) {

       var oDadosRecursos = oRetorno.aRecursos[iRecurso];

       var oOpcao         = document.createElement("option");
           oOpcao.value   = oDadosRecursos.o15_codigo;
           oOpcao.text    = oDadosRecursos.o15_descr.urlDecode();
           oSelect.appendChild(oOpcao);
      }
    }
    var oAjax  = new Ajax.Request( sUrlRPC, oDadosRequisicao );
  }
 )();

 /**
  * Exibe o combo de quebra banco quando o tipo de Impress�o for anal�tico
  */
 function js_tipoImpressao() {

  $('trQuebraBanco').style.display = 'none';

  if ( $F('tipoImpressao') == 'A' ) {

    $('quebraBanco').value           = '';
    $('trQuebraBanco').style.display = '';
  }

  if ( $F('tipoImpressao') == 'S' ) {

    $('quebraBanco').value           = '';
  }
 }
 
  /**
    * Fun��o responsavel pela valida��o e tratamento 
    * dos dados para gera��o do relat�rio.
    */
  function js_processar() {

    /**
     * Valida se o Tipo de impress�o foi informado
     */
    if ( $F('tipoImpressao') == '' ) {

      alert( _M('recursoshumanos.pessoal.pes2_liquidosporrecurso.preenchimento_tipoImpressao_obrigatorio') );
      return false;
    }

    /**
     * Valida se o Tipo de impress�o foi informado
     */
    if ( $F('formatoImpressao') == '' ) {

      alert( _M('recursoshumanos.pessoal.pes2_liquidosporrecurso.preenchimento_formatoImpressao_obrigatorio') );
      return false;
    }

    if ( $F('tipoImpressao') == 'A' && $F('quebraBanco') == '' ) {

      alert( _M('recursoshumanos.pessoal.pes2_liquidosporrecurso.preenchimento_quebraBanco_obrigatorio') );
      return false;
    }
        
    /**
     * Geramos o relat�rio de acordo com os filtros
     */
    var aRecursos      = new Array();
    var sRecurso       = $('recurso').value;
    var iTotalRecursos = $('recurso').length;
 
    /**
     * Realiza o tratamendo dos recursos selecionados para enviar para o relat�rio
     * ex.: os recursos 1,2,3,4 devem ser enviados para o relatorio na seguinte forma 1','2','3','4
     */
    if ( $('recurso').value == 'T') {

      for (var iIndex = 0; iIndex < iTotalRecursos; iIndex++) {

        if ( $('recurso').options[iIndex].value != 'T' ){
          aRecursos.push($('recurso').options[iIndex].value);
        }
      }
      sRecurso = aRecursos.join("','");
    }

    var sFormatoImpressao = $('formatoImpressao').value;
    var sTipoImpressao    = $('tipoImpressao').value;
    var sQuebraBanco      = $('quebraBanco').value;
    var aCodigoRelatorio  = new Array();

    var iCodigoRelatorio = 0;

    switch(sFormatoImpressao){
      case 'pdf':  

        iCodigoRelatorio = '17';
        if (sTipoImpressao == 'S'){
          iCodigoRelatorio = '19';
        }

        if(sQuebraBanco == 1) {
          iCodigoRelatorio = '25';
        }
      break
      case 'csv':  

        iCodigoRelatorio = '18';
        if (sTipoImpressao == 'S'){
          iCodigoRelatorio = '20';
        }

        if(sQuebraBanco == 1) {
          iCodigoRelatorio = '26';
        }
      break
    }

    var aParametros = new Array(); 
    var sVariavel   = sRecurso;                                       
    var sDescricao  = '$sRecursos';                                     
    var objVariavel = new js_criaObjetoVariavel(sDescricao,sVariavel);  
    aParametros.push( objVariavel );

    js_imprimeRelatorio(iCodigoRelatorio,js_downloadArquivo,Object.toJSON(aParametros));
  }

  /**
   * Trata o retorno da fun��o js_imprimeRelatorio
   */
  function js_downloadArquivo(oAjax) {

    js_removeObj('msgBox');
    var oRetorno = eval("("+oAjax.responseText+")");

    if ( oRetorno.erro ){

      alert(oRetorno.sMsg.urlDecode());
      return false;
    }

    var sUrl = oRetorno.sMsg;
    sUrl     = sUrl.urlDecode();
    sArquivo = sUrl.split('/');


    var oDBDownload = new DBDownload();
    oDBDownload.addFile(sUrl,sArquivo[1]);
    oDBDownload.show();
  }
</script>