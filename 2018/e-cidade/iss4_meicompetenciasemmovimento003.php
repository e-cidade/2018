<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_meiimporta_classe.php");
require_once("classes/db_meiimportasemmov_classe.php");

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);

$clmeiimporta       = new cl_meiimporta();
$clmeiimportasemmov = new cl_meiimportasemmov();
$clmeiimporta->rotulo->label();
$clmeiimportasemmov->rotulo->label();

$db_opcao = 3;
$db_botao = true;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?   
  db_app::load("scripts.js, strings.js, prototype.js, datagrid.widget.js");
  db_app::load("widgets/messageboard.widget.js, widgets/windowAux.widget.js");
  db_app::load("estilos.css, grid.style.css");
?>
<style>
  td {
    white-space: nowrap
  }
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_pesquisar();">
<table border="0" align="center" cellspacing="0" cellpadding="0" style="padding-top:20px;">
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td valign="top" bgcolor="#CCCCCC"> 
      <center>       
        <form  name="form1" method="post" action="">
          <fieldset>
            <legend><b>Cancelamento de Competência sem Movimento</b></legend>
            <table border="0" align="center">
              <tr>
                <td title="<?=@$Tq104_mesusu?>" width="10%">
                  <?=@$Lq104_mesusu?>
                </td>
                <td width="25%">
                  <?
                    db_input('q104_mesusu',10,@$Iq104_mesusu,true,'text',$db_opcao);
                  ?>
                </td>
                <td title="<?=@$Tq104_anousu?>" width="10%">
                  <?=@$Lq104_anousu?>
                </td>
                <td>
                  <?
                    db_input('q104_anousu',10,@$Iq104_anousu,true,'text',$db_opcao);
                  ?>
                </td>
              </tr>
              <tr>
                <td colspan="4">
                  <fieldset>
                    <legend>
                      <b>Motivo</b>
                    </legend>
                      <?
                        db_textarea('q114_motivo',5,60,@$Iq114_motivo,true,'text',$db_opcao,"");
                      ?>
                  </fieldset>
                </td>
              </tr>
            </table>
          </fieldset>
          <table>
            <tr>
              <td colspan="4">&nbsp;</td>
            </tr>
            <tr>
              <td>
                <input name="cancelar" type="button" id="cancelar" value="Cancelar" 
                       onClick="return js_processarDados();" disabled> 
              </td>
              <td>
                <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onClick="return js_pesquisar();">
              </td>
            </tr>
          </table>
        </form>
      </center>
    </td>
  </tr>
</table>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
<script>
/**
 * Pesquisa competencia sem movimentacao
 */
function js_pesquisar() {

  $('cancelar').disabled  = true;
  var sUrl                = 'func_meiimporta.php?cancelamentos=true'; 
      sUrl               += '&funcao_js=parent.js_mostrarpesquisa|q104_mesusu|q104_anousu|q114_motivo';
      
  js_OpenJanelaIframe('top.corpo', 
                      'db_iframe_competenciasemmovimentacao', 
                      sUrl,
                      'Pesquisar Competência sem Movimentação',
                      true);
}

/**
 * Retorno da pesquisa competencia sem movimentacao
 */
function js_mostrarpesquisa(chave1,chave2,chave3) {

  $('cancelar').disabled  = false;
  $('q104_mesusu').value  = chave1;
  $('q104_anousu').value  = chave2;
  $('q114_motivo').value  = chave3;
  db_iframe_competenciasemmovimentacao.hide();
}

/**
 * Incluir dados da competencia sem movimento
 */
function js_processarDados() {

  var sUrl     = 'mei4_competenciasemmovimento.RPC.php';
  var mesExpr  = new RegExp("[1-9]");    
  var anoexpr  = new RegExp("[12][0-9][0-9][0-9]");
  var iMes     = $F('q104_mesusu');
  var iAno     = $F('q104_anousu');
  
  if (iMes.match(mesExpr) == null ||  iMes > 12 || iMes == "00") {
    
    alert("Mês inválido!");
    $('q104_mesusu').value  = "";
    $('q104_mesusu').focus();
    return false;
  }
  
  if (iAno.match(anoexpr) == null) {
  
    alert("Ano inválido!");
    $('q104_anousu').value  = "";
    $('q104_anousu').focus();
    return false;
  }
  
  js_divCarregando('Aguarde cancelando competência sem movimento...','msgBoxCompetenciasemMovimento');
   
  var oParam     = new Object();
  oParam.exec    = "cancelarCompetenciaSemMovimentacao";
  oParam.mes     = $F('q104_mesusu');
  oParam.ano     = $F('q104_anousu');
  oParam.motivo  = encodeURIComponent(tagString($F('q114_motivo')));
    
  var oAjax   = new Ajax.Request( sUrl, {
                                          method: 'post', 
                                          parameters: 'json='+js_objectToJson(oParam), 
                                          onComplete: js_retornoDadosCompetencia
                                        }
                                );
}
  
/**
 * Retorna os dados competencia sem movimento
 */
function js_retornoDadosCompetencia(oAjax) {
  
  js_removeObj("msgBoxCompetenciasemMovimento");
  
  var oRetorno = eval("("+oAjax.responseText+")");
     
  $('q104_mesusu').value  = "";
  $('q104_anousu').value  = "";
  $('q114_motivo').value  = "";

  if (oRetorno.status == 2) {
    alert(oRetorno.erro.urlDecode());
  } else {
    alert("Cancelamento efetuada com Sucesso.");
  }
  
  js_pesquisar();
  
}
</script>
</html>