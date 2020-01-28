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
require_once("libs/db_usuariosonline.php");

require_once("classes/db_itbi_classe.php");
require_once("classes/db_itbiavalia_classe.php");
require_once("classes/db_itbinumpre_classe.php");
require_once("classes/db_itbinome_classe.php");
require_once("classes/db_itbiavaliaformapagamentovalor_classe.php");
require_once("dbforms/db_funcoes.php");

$oGet   = db_utils::postMemory($_GET);
$oPost  = db_utils::postMemory($_POST);

$clitbi                          = new cl_itbi();
$itbiavalia                      = new cl_itbiavalia();
$clitbinumpre                    = new cl_itbinumpre();
$clitbinome                      = new cl_itbinome();
$clitbiavaliaformapagamentovalor = new cl_itbiavaliaformapagamentovalor();

$clitbi->rotulo->label();
$clitbinome->rotulo->label();

$lSqlErro = false;
$iAnoUsu  = db_getsession('DB_anousu');

if (isset($oPost->cancelarliberacao)) {
  db_inicio_transacao();

  /**
   * VERIFICA SE EXISTE RECIBO GERADO PARA O NUMPRE DA ITBI QUE ESTA SENDO EXCLUIDA.
   */
  $oDaoItbiNumpre = db_utils::getDao('itbinumpre');
  $sSqlItbiNumpre = $oDaoItbiNumpre->sql_query_recibo($it01_guia);
  $rsItbiNumpre   = pg_query($sSqlItbiNumpre);

  if( pg_num_rows($rsItbiNumpre) > 0) {
    $lSqlErro = true;
    $sMsgErro = 'Já existe recibo gerado para esta ITBI.';
  }


  if (! $lSqlErro) {
    $clitbiavaliaformapagamentovalor->excluir(null," it24_itbiavalia = {$it01_guia}");
    if ($clitbiavaliaformapagamentovalor->erro_status == 0) {
      $lSqlErro = true;
    }
          
    $sMsgErro = $clitbiavaliaformapagamentovalor->erro_msg;
  }  
  
  if (! $lSqlErro) {
	  $itbiavalia->excluir($it01_guia);
	  if ($itbiavalia->erro_status == 0) {
	    $lSqlErro = true;
	  }
	        
	  $sMsgErro = $itbiavalia->erro_msg;
  } 
  
  db_fim_transacao($lSqlErro);
  
  $it01_guia = "";
  $it03_nome = "";
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1">
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
<form name="form1" method="post" action="">
  <table align="center" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td> 
        <fieldset>
        <legend><b>Cancela Liberação</b></legend>
          <table border="0">
              <tr>
                <td title="<?=@$Tit01_guia?>">
                  <?
                    db_ancora(@$Lit01_guia,"js_pesquisait01_guia(true);",1);
                  ?>&nbsp;
                </td>
                <td> 
                  <?
                    db_input('it01_guia',10,$Iit01_guia,true,'text',1," onchange='js_pesquisait01_guia(false);'");
                  ?>
                </td>
                <td>
                  <?
                    db_input('it03_nome',40,$Iit03_nome,true,'text',3,'');
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
    <tr align="center">
      <td>
        <input name="cancelarliberacao" id="cancelarliberacao" disabled="disabled" type="submit" onclick="return js_valida()" value="Cancelar Liberação">
      </td>
    </tr>    
  </table>
</form>
</body>
</html>
<script>
function js_pesquisait01_guia(mostra){
  if ( mostra == true ) {
    var sUrl = 'func_itbiliberado.php?funcao_js=parent.js_mostraitbi1|it01_guia|it03_nome';
    js_OpenJanelaIframe('top.corpo','db_iframe_itbiliberada',sUrl,'Pesquisa',true);
  } else {
  
    if ( document.form1.it01_guia.value != '' ) { 
      var iGuia = document.form1.it01_guia.value;
      var sUrl  = 'func_itbiliberado.php?pesquisa_chave='+iGuia+'&funcao_js=parent.js_mostraitbi';
      js_OpenJanelaIframe('top.corpo','db_iframe_itbiliberada',sUrl,'Pesquisa',false);
    } else {
       document.form1.it01_guia.value = ''; 
       document.form1.it03_nome.value = ''; 
     }  
  }
}

function js_mostraitbi(chave1,erro,chave2){

  
  if (erro == true) { 
    document.form1.cancelarliberacao.disabled = true;
    document.form1.it01_guia.focus(); 
    document.form1.it01_guia.value = '';
    document.form1.it03_nome.value = chave1;
  } else {
    document.form1.it03_nome.value = chave2;    
    document.form1.cancelarliberacao.disabled = false;
  }
}

function js_mostraitbi1(chave1,chave2){
  document.form1.it01_guia.value = chave1;
  document.form1.it03_nome.value = chave2;
  document.form1.cancelarliberacao.disabled = false;
  db_iframe_itbiliberada.hide();
}

function js_valida() {

  if ( document.form1.it01_guia.value == '' || document.form1.it03_nome.value == '' ) {

    alert('Por favor, selecione um código de ITBI Válido');
    return false;
  }

  return true;
}
</script>
<?
if ( isset($oPost->cancelarliberacao) ) {
  db_msgbox($sMsgErro);
}
?>