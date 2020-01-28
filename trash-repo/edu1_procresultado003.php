<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

require_once("libs/db_stdlibwebseller.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_utils.php");
require_once("model/educacao/ArredondamentoNota.model.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$oDaoProcResultado = db_utils::getdao("procresultado"); 
$oDaoConceito      = db_utils::getdao("conceito");
$db_botao          = false;
$db_opcao          = 33;
$db_opcao1         = 3;
function ElementosFreq($ed67_i_procresultado) {
	
 $sSql          = "SELECT * FROM avalfreqres WHERE ed67_i_procresultado = $ed67_i_procresultado";
 $rsAvalFreqRes = pg_query($sSql);
 $iLinhas       = pg_num_rows($rsAvalFreqRes);
 return $iLinhas;
 
}

if (isset($excluir)) {
	
  $sWhere            = " ed43_i_procedimento = $ed43_i_procedimento";
  $sSqlProcResultado = $oDaoProcResultado->sql_query("", "ed43_i_resultado as resjacad", "", $sWhere); 
  $rsProcResultado   = $oDaoProcResultado->sql_record($sSqlProcResultado);
  if ($oDaoProcResultado->numrows > 0) {
  	
    $sep     = "";
    $res_cad = "";
    
    for ($iCont = 0; $iCont < $oDaoProcResultado->numrows; $iCont++) {
    	
      db_fieldsmemory($rsProcResultado, $iCont);
      $res_cad .= $sep.$resjacad;
      $sep      = ", ";
      
    }
    
  } else {
    $res_cad = 0;
  }
  
  $db_opcao  = 3;
  $db_opcao1 = 3;
  db_inicio_transacao();
  $oDaoProcResultado->excluir($ed43_i_codigo);
  db_fim_transacao();
  
} elseif (isset($chavepesquisa) && !isset($excluir)) {
	
  $db_opcao    = 3;
  $db_opcao1   = 3;
  $db_botao    = true;
  $sSql        = $oDaoProcResultado->sql_query($chavepesquisa);
  $rsResultado = $oDaoProcResultado->sql_record($sSql);
  db_fieldsmemory($rsResultado, 0);
  
  $sWhereResultado = " ed43_i_procedimento = $ed43_i_procedimento";
  $sSqlResultado   = $oDaoProcResultado->sql_query("", "ed43_i_resultado as resjacad", "", $sWhereResultado);
  $rs              = $oDaoProcResultado->sql_record($sSqlResultado);
  
  if ($oDaoProcResultado->numrows > 0) {
  	
    $sep     = "";
    $res_cad = "";
    
    for ($iContResult = 0; $iContResult < $oDaoProcResultado->numrows; $iContResult++) {
    	
      db_fieldsmemory($rs, $iContResult);
      $res_cad .= $sep.$resjacad;
      $sep      = ", ";
      
    }
    
  } else {
    $res_cad = 0;
  }
  
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
 <body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
   <tr>
    <td valign="top" bgcolor="#CCCCCC">
     <br>
     <center>
      <fieldset style="width:95%" ><legend><b>Exclus�o do Resultado <?=@$ed42_c_descr?></b></legend>
       <?include("forms/db_frmprocresultado.php");?>
      </fieldset>
     </center>
    </td>
   </tr>
  </table>
 </body>
</html>
<script>
js_tabulacaoforms("form1", "excluir", true, 1, "excluir", true);
</script>
<?
if (isset($excluir)) {
	
  if ($oDaoProcResultado->erro_status == "0") {
    $oDaoProcResultado->erro(true, false);
  } else {
  	
    $oDaoProcResultado->erro(true, false);
    ?>
    <script>
      top.corpo.iframe_a2.location.href = "edu1_avaliacoes.php?procedimento=<?=$ed43_i_procedimento?>"+
                                          "&ed40_c_descr=<?=$ed40_c_descr?>&forma=<?=$forma?>"
    </script>
    <?
    
  }
  
}
?>