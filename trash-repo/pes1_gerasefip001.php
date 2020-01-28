<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa e software livre; voce pode redistribui-lo e/ou     
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versao 2 da      
 *  Licenca como (a seu criterio) qualquer versao mais nova.          
 *                                                                    
 *  Este programa e distribuido na expectativa de ser util, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de              
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM           
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU     
 *  junto com este programa; se nao, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Copia da licenca no diretorio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");

require_once("classes/db_rhsefip_classe.php");
$clRHSefip = new cl_rhsefip();

$oRotulo   = new rotulocampo();
$oRotulo->label('rh90_anousu');
$oRotulo->label('rh90_mesusu');

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
  db_app::load("scripts.js, strings.js,arrays.js, prototype.js,datagrid.widget.js");
  db_app::load("widgets/windowAux.widget.js, widgets/dbmessageBoard.widget.js");
  db_app::load("estilos.css, grid.style.css");
?>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<form name="form1" method="post" action="">
<table align="center" style="padding-top: 25px;">
  <tr> 
    <td>
      <fieldset>
        <legend>
          <b>Geração Sefip</b>
        </legend>
        <table>
          <tr>
            <td>
              <b>Tipo de Processamento:</b>
            </td>
            <td>
              <?php
                $aTipoProcessamento = array("1" => "Geral",
                                            "2" => "Selecionados");
                db_select("iTipoProcessamento", $aTipoProcessamento, true, 1);
              ?>
            </td>
          </tr>
          <tr>
            <td>
              <b>Competência ( Mês / Ano ) :</b>
            </td>
            <td>
              <?php
                 $anousu = db_anofolha();
                 $mesusu = db_mesfolha();
               
                 db_input('mesusu',2,true,$Irh90_anousu,'text',1);
                 echo "/";
                 db_input('anousu',4,true,$Irh90_mesusu,'text',1);                 
              ?>
            </td>
          </tr>
        </table>
      </fieldset>
    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="center">
      <input type="button" id="processar" value="Processar" onClick="js_processar();">
    </td>
  </tr>          
</table>
</form>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
   
  var sUrlRPC           = 'pes4_autprocvalores.RPC.php';
  var oParam            = new Object();
  
  function js_processar() {
  
    var sAnoUsu = new String($F('anousu'));
    var sMesUsu = new String($F('mesusu'));
      
    if ( sAnoUsu.trim() == '' || sMesUsu.trim() == '' ) {
      alert('Competência não informada!');
      return false;
    }
      
    if ( sMesUsu < 1 || sMesUsu > 13  ) {
      alert('Mês inválido!');
      return false; 
    }
      
    oParam.iAnoUsu            = sAnoUsu;
    oParam.iMesUsu            = sMesUsu;
    oParam.iTipoProcessamento = $('iTipoProcessamento').value;

    js_pesquisaGeracao();
  }   

 function js_pesquisaGeracao() {
    
    js_divCarregando('Aguarde, processando...', 'msgbox');
    
    oParam.sMethod = 'verificaGeracaoSefip';
    
    var oAjax   = new Ajax.Request( 
                                   sUrlRPC, 
                                   {
                                     method: 'post', 
                                     parameters: 'json='+Object.toJSON(oParam), 
                                     onComplete: js_retornoGeracao 
                                   }
                                  );      
  
  }
  
  function js_retornoGeracao(oAjax){

    var oRetorno = eval("("+oAjax.responseText+")");
    
    js_removeObj('msgbox');
    
    if ( oRetorno.iStatus == 2 ) {
    
      alert(oRetorno.sMsg.urlDecode());
      return false;
    } else {
    
      if ( oRetorno.lGerado ) {
        
        var sMsg ='Sefip já gerado para a competência informada!\n'
                 +'Deseja cancelar a mesma ?';
        
        if ( confirm(sMsg) ) {
          js_cancelaGeracao();
        } else {
          js_dowloadArquivo(oRetorno.iCodSefip);
        }
          
      } else {
        js_enviaTelaGeracao();
      }
    }
  }
   
  function js_cancelaGeracao() {
  
    js_divCarregando('Aguarde, cancelando sefip...', 'msgbox');
    
    oParam.sMethod = 'cancelaGeracaoSefip';
    
    var oAjax   = new Ajax.Request( 
                                   sUrlRPC, 
                                   {
                                     method: 'post', 
                                     parameters: 'json='+Object.toJSON(oParam), 
                                     onComplete: js_retornoCancelamentoGeracao 
                                   }
                                  );      
  }
  
  function js_retornoCancelamentoGeracao(oAjax){

    var oRetorno = eval("("+oAjax.responseText+")");
    
    js_removeObj('msgbox');
    
    if ( oRetorno.iStatus == 2 ) {
      alert(oRetorno.sMsg.urlDecode());
      return false;
    } else {
      js_enviaTelaGeracao();    
    }
  }
   
  function js_enviaTelaGeracao() {
  
    document.location.href = 'pes1_gerasefip002.php?iAnoUsu='+oParam.iAnoUsu+
                                                   '&iMesUsu='+oParam.iMesUsu+
                                                   '&iTipoProcessamento='+oParam.iTipoProcessamento;
  } 
   
  function js_dowloadArquivo(iCodSefip){
    
    js_divCarregando('Aguarde ...','msgBox');
    
    oParam.sMethod   = 'downloadAquivo';
    oParam.iCodSefip = iCodSefip;
    var oAjax   = new Ajax.Request( sUrlRPC, {
                                         method: 'post',
                                         parameters: 'json='+Object.toJSON(oParam), 
                                         onComplete: js_retornoDownloadArquivo  
                                       }
                                 );
  }
  
  function js_retornoDownloadArquivo(oAjax){

    var sRetorno = eval("("+oAjax.responseText+")");
    
    js_removeObj("msgBox");

	  if (sRetorno.iStatus == 2 ){
	  
	    alert(sRetorno.sMsg.urlDecode());
	    return false;
	  } else {
      
	    var sArquivo = sRetorno.sCaminhoArquivo.urlDecode()+'#Arquivo para envio SEFIP';
      var sLista   = sArquivo;
      js_montarlista(sLista,'form1');	    
	  }
  }
</script>