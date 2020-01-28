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

require("libs/db_stdlib.php");
require("libs/db_utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("libs/db_liborcamento.php");
require_once("model/relatorioContabil.model.php");

$oGet = db_utils::postMemory($_GET);

$clrotulo = new rotulocampo;
$clrotulo->label('DBtxt21');
$clrotulo->label('DBtxt22');

$iAnoUso = db_getsession('DB_anousu');

$oRelatorio = new relatorioContabil($oGet->codrel); 
$sLabelMsg = "Anexo III - Receita Corrente Líquida";
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

js_buscaEdicaoLrf(<?=db_getsession("DB_anousu")?>,'con2_lrfreceitacorrente002');

function js_emite(){

    obj = document.form1;
    periodo = obj.o116_periodo.value;

    data_ini = '';
    data_fin = '';
    /*
    if (obj.DBtxt21_dia.value!=''&&obj.DBtxt22_dia.value!=''){
     data_ini = obj.DBtxt21_ano.value+'-'+obj.DBtxt21_mes.value+'-'+obj.DBtxt21_dia.value;
     data_fin = obj.DBtxt22_ano.value+'-'+obj.DBtxt22_mes.value+'-'+obj.DBtxt22_dia.value;
     if (data_ini > data_fin){
          alert("Data inicial maior que a final. Verifique!");
    return false;
     }
    } 
    */
    jan = window.open(sNomeArquivoEdicao+'?db_selinstit=&dtini='+data_ini+'&dtfin='+data_fin+'&periodo='+periodo,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    jan.moveTo(0,0);
}
</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
 <form name="form1" method="post" action="con2_lrfreceitacorrente002.php" >
  <table align="center" border="0" cellpadding="0" cellspacing="0">
   <tr>
    <td >&nbsp;</td>
   </tr>
   <tr>
    <td colspan=3  class='table_header'>
     <?=$sLabelMsg?>
    </td>
   </tr>  
   <tr>
    <td>
      <fieldset>
       <legend><b>Filtros</b></legend>
         <table align="center">
            <tr>
             <td colspan=2 nowrap><b>Período :</b>
             <?
              if ($iAnoUso >= 2010) {
                
                $aPeriodos = $oRelatorio->getPeriodos();
                $aListaPeriodos = array();
                $aListaPeriodos[0] = "Selecione";
                foreach ($aPeriodos as $oPeriodo) {
                  $aListaPeriodos[$oPeriodo->o114_sequencial] = $oPeriodo->o114_descricao;
                }
                db_select("o116_periodo", $aListaPeriodos, true, 1);
              } else {
                echo '
                <select name=o116_periodo> 
                 <option value="1B">Primeiro Bimestre </option>
                 <option value="2B">Segundo  Bimestre </option>
                 <option value="3B">Terceiro Bimestre </option>
                 <option value="4B">Quarto   Bimestre </option>
                 <option value="5B">Quinto   Bimestre </option>
                 <option value="6B">Sexto    Bimestre </option>
               </select>
               ';
              }
               ?>     
              </td> 
            </tr>         
         </table>     
       </fieldset>
       <table align="center">
        <tr>
         <td>&nbsp;</td>
        </tr>
        <tr>
         <td align="center" colspan="2">
          <input  name="emite" id="emite" type="button" value="Imprimir" onclick="js_emite();">
         </td>
        </tr>       
       </table>
    </td>
   </tr>
  </table> 
 </form>
</body>
</html>