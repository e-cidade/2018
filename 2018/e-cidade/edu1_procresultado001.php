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

require_once("libs/db_stdlibwebseller.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_utils.php");
db_postmemory($HTTP_POST_VARS);
$oDaoProcResultado = db_utils::getdao("procresultado"); 
$db_opcao          = 1;
$db_opcao1         = 1;
$db_botao          = true;

function ElementosFreq($ed67_i_procresultado) {
	
  $sSql          = "SELECT * FROM avalfreqres WHERE ed67_i_procresultado = $ed67_i_procresultado ";
  $rsAvalFreqRes = db_query($sSql);
  $iLinhas        = pg_num_rows($rsAvalFreqRes);
  return $iLinhas;
 
}
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

if (isset($incluir)) {
	
  db_inicio_transacao();
  $sSqlUnion    = " SELECT ed41_i_sequencia ";
  $sSqlUnion   .= " FROM procavaliacao ";
  $sSqlUnion   .= " WHERE ed41_i_procedimento = $ed43_i_procedimento ";
  $sSqlUnion   .= " UNION ";
  $sSqlUnion   .= " SELECT ed43_i_sequencia ";
  $sSqlUnion   .= " FROM procresultado ";
  $sSqlUnion   .= " WHERE ed43_i_procedimento = $ed43_i_procedimento ";
  $sSqlUnion   .= " ORDER BY ed41_i_sequencia ";
  $rsUnion      = db_query($sSqlUnion);
  $iLinhasUnion = pg_num_rows($rsUnion);
  
  if ($iLinhasUnion == 0) {
    $max = 0;
  } else {
    $max = pg_result($rsUnion, $iLinhasUnion-1, "ed41_i_sequencia");
  }
  
  $oDaoProcResultado->ed43_c_minimoaprov     = $minimoaprov;
  $oDaoProcResultado->ed43_c_obtencao        = "AT";
  $oDaoProcResultado->ed43_c_geraresultado   = "N";
  $oDaoProcResultado->ed43_c_boletim         = "N";
  $oDaoProcResultado->ed43_c_reprovafreq     = "N";
  $oDaoProcResultado->ed43_c_arredmedia      = "N";
  $oDaoProcResultado->ed43_i_sequencia       = ($max+1);
  $oDaoProcResultado->ed43_c_tipoarred       = "C";
  $oDaoProcResultado->ed43_proporcionalidade = 'false';
  $oDaoProcResultado->incluir($ed43_i_codigo);
  db_fim_transacao();

  $db_botao = false;
 
}
?>
<html>
 <head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
 </head>
 <body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
   <tr>
    <td align="left" valign="top" bgcolor="#CCCCCC">
     <br>
     <center>
      <fieldset style="width:95%"><legend><b>Inclusão de Resultados do Procedimento de Avaliação <?=$ed40_c_descr?></b></legend>
       <?include("forms/db_frmprocresultado.php");?>
      </fielset>
     </center>
    </td>
   </tr>
  </table>  
 </body>
</html>
<script>
js_tabulacaoforms("form1", "ed43_i_resultado", true, 1, "ed43_i_resultado", true);
</script>
<?
if (isset($incluir)) {
	
  if ($oDaoProcResultado->erro_status == "0") {
  	
    $oDaoProcResultado->erro(true, false);
    $db_botao = true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    
    if ($oDaoProcResultado->erro_campo != "") {
    	
      echo "<script> document.form1.".$oDaoProcResultado->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$oDaoProcResultado->erro_campo.".focus();</script>";
      
    }
    
  } else {
  	
    $oDaoProcResultado->erro(true, false);    
    ?>
    <script>
      top.corpo.iframe_a2.location.href = "edu1_avaliacoes.php?ed15_c_nome=RESULTADO"+
                                          "&ed41_i_codigo=<?=$oDaoProcResultado->ed43_i_codigo?>"+
                                          "&opcao=alterar&procedimento=<?=$ed43_i_procedimento?>"+
                                          "&forma=<?=$forma?>&ed40_c_descr=<?=$ed40_c_descr?>"
    </script>
    <?
    
  }
  
}
?>