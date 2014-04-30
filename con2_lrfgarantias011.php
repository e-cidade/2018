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
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_liborcamento.php");
require_once("model/relatorioContabil.model.php");

$clrotulo = new rotulocampo;
$clrotulo->label('DBtxt21');
$clrotulo->label('DBtxt22');
$oGet = db_utils::postMemory($_GET);
db_postmemory($HTTP_POST_VARS);

$oRelatorio  = new relatorioContabil($oGet->c83_codrel, false);
$anousu = db_getsession("DB_anousu");
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script>

variavel = 1;

function js_buscaEdicaoLrf(iAnousu,sFontePadrao){
  
  var url       = 'con4_lrfbuscaedicaoRPC.php';
  var parametro = 'ianousu='+iAnousu+'&sfontepadrao='+sFontePadrao ;
  var objAjax   = new Ajax.Request (url, { method:'post',
                                           parameters:parametro, 
                                           onComplete:js_setNomeArquivo}
                                    );  
}

function js_setNomeArquivo(oResposta){
  sNomeArquivoEdicao = oResposta.responseText;
}

js_buscaEdicaoLrf(<?=db_getsession("DB_anousu")?>,'con2_lrfgarantias002');

function js_emite(sFonte){


  var obj     = document.form1;
  var sInstit = '';  
  if (document.form1.db_selinstit) {
  
      
    sInstit  = new Number(document.form1.db_selinstit.value);
    if(sInstit == 0){
      alert('Você não escolheu nenhuma Instituição. Verifique!');
      return false;
  	}	
	}
  executar = sNomeArquivoEdicao;					
  jan = window.open(executar+'?db_selinstit='+sInstit+'&periodo='+obj.o116_periodo.value,
                    '','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}
</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <center>
  <form name="form1" method="post" action="con2_lrfdivida002.php" > 
  <?
    if ($anousu < 2010) {
  ?>
   <table  align="center">
    <tr>
         <td >&nbsp;</td>
         <td >&nbsp;</td>
    </tr>
    <tr>
        <td align="center" colspan="3">
	     <?	db_selinstit('',300,100);	?>
	    </td>
    </tr>
    
    <tr>
        <td colspan=2 nowrap><b>Período :</b>
	    <select name=periodo> 
               <option value="1Q">Primeiro Quadrimestre </option>
               <option value="2Q">Segundo  Quadrimestre </option>
               <option value="3Q">Terceiro Quadrimestre </option>
	       <option value="1S">Primeiro Semestre </option>
               <option value="2S">Segundo  Semestre </option>	       
            </select>
        </td> 
    </tr>
     
     <tr>
        <td colspan=2>&nbsp; </td>
    </tr>
    
    <tr>
        <td align="center" colspan="2">
           <input  name="emite" id="emite" type="button" value="Imprimir" onclick="js_emite(<?=$anousu?>);">
        </td>
   </tr>
  </table>
  <?
   } else {
  ?>
  <table  align="center">
    <tr>
      <td class='table_header'>Anexo III - Demonstrativo  Gararantias e Contragarantias de valores</td>
    </tr>
    <tr>
      <td>
        <fieldset>
          <table>
            <tr>
              <td nowrap>
                <b>Período :</b>
              </td>
              <td>
                 <?                 
                  $aPeriodos = $oRelatorio->getPeriodos();
                  $aListaPeriodos = array();
                  $aListaPeriodos[0] = "Selecione";
                  foreach ($aPeriodos as $oPeriodo) {
                    $aListaPeriodos[$oPeriodo->o114_sequencial] = $oPeriodo->o114_descricao;
                  }
                  db_select("o116_periodo", $aListaPeriodos, true, 1);
                ?>
              </td> 
            </tr>
            <tr>
           </tr>
          </table>
        </fieldset>
      </td>
    </tr>
    <tr>
      <td align="center" colspan="2">
        <input  name="emite" id="emite" type="button" value="Imprimir" onclick="js_emite(<?=$anousu?>);">
      </td>
    </tr>          
  </table>        
  <?
   }
   ?>
</form>
<center>
</body>
</html>