<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("libs/db_liborcamento.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("model/relatorioContabil.model.php"));

$clrotulo = new rotulocampo;
$clrotulo->label('DBtxt21');
$clrotulo->label('DBtxt22');

db_postmemory($HTTP_POST_VARS);
$oGet               = db_utils::postMemory($_GET);
$oRelatorioContabil = new relatorioContabil($oGet->c83_codrel);
$anousu             = db_getsession("DB_anousu");
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

js_buscaEdicaoLrf(<?=db_getsession("DB_anousu")?> ,'con2_lrfdivida002');


function js_emite(sFonte){

		
  obj = document.form1;
  
  data_ini = '';
  data_fin = '';
 	
 	executar = sNomeArquivoEdicao;
  var sInstit  = '';
  if(obj.db_selinstit) {
    
    sel_instit  = new Number(document.form1.db_selinstit.value);
    if (sel_instit == 0) {
    
      alert('Você não escolheu nenhuma Instituição. Verifique!');
      return false;
    } else {
      sInstit = obj.db_selinstit.value;
    }
  }
  var sTrajetoria = '';
  if (obj.trajetoria) {
    sTrajetoria = obj.trajetoria.value;
  } 
  var sUrl = executar+'?db_selinstit='+sInstit+'&dtini=&dtfin=&periodo='+obj.o116_periodo.value+
            '&trajetoria='+sTrajetoria
  jan = window.open(sUrl,
                    '',
                    'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
		
}
</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <form name="form1" method="post" action="con2_lrfdivida002.php">
  <? 
    if ($anousu < 2010) {
   ?>       
    <table  align="center">
      <tr>
        <td align="center" colspan="3">
        <?	db_selinstit('',300,100);	?>
        </td>
      </tr>
      <tr>
        <td colspan=2 nowrap><b>Período:</b>
          <select name='o116_periodo'> 
          <option value="1Q">Primeiro Quadrimestre</option>
          <option value="2Q">Segundo  Quadrimestre</option>
          <option value="3Q">Terceiro Quadrimestre</option>
          <option value="1S">Primeiro Semestre</option>
          <option value="2S">Segundo  Semestre</option>
          </select>
        </td> 
      </tr>
      <tr> 
        <td colspan=2 nowrap><b>Imprimir Trajetória de Ajuste</b>
          <?
          $matriz = array("N"=>"Não","S"=>"Sim");             
          db_select("trajetoria", $matriz,true,1,""); 
          ?>
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
        <td colspan=3  class='table_header'>Anexo II - Demonstrativo da Dívida Consolidada Líquida</td>
      </tr>
      <tr>
        <td>
          <fieldset>
            <table>
              <tr>
                <td nowrap>
                  <b>Período:</b>
                </td>
                <td>
                   <?
                     $aPeriodos = $oRelatorioContabil->getPeriodos();
                     $aListaPeriodos = array();
                     $aListaPeriodos[0] = "Selecione";
                     foreach ($aPeriodos as $oPeriodo) {
                       $aListaPeriodos[$oPeriodo->o114_sequencial] = $oPeriodo->o114_descricao;
                     }
                     db_select("o116_periodo", $aListaPeriodos, true, 1);
                    ?>
                </td> 
              </tr>
                <td colspan=2>&nbsp; </td>
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
</body>
</html>