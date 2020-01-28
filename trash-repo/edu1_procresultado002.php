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
require_once("classes/db_rescompoeres_classe.php");
require_once("classes/db_avalfreqres_classe.php");
require_once("classes/db_conceito_classe.php");
require_once("classes/db_edu_parametros_classe.php");
require_once("model/educacao/ArredondamentoNota.model.php");
require_once("libs/db_utils.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$oDaoConceito      = db_utils::getdao("conceito");
$oDaoProcResultado = db_utils::getdao("procresultado");
$oDaoProcAvaliacao = db_utils::getdao("procavaliacao");
$oDaoAvalCompoeRes = db_utils::getdao("avalcompoeres");
$oDaoResCompoeRes  = db_utils::getdao("rescompoeres");
$oDaoAvalFreqres   = db_utils::getdao("avalfreqres");
$oDaoEduParametros = db_utils::getdao("edu_parametros");
$db_opcao          = 2;
$db_opcao1         = 3;
$db_botao          = false;
$sWhereParametros  = "ed233_i_escola = ".db_getsession("DB_coddepto");
$sSqlParametros    = $oDaoEduParametros->sql_query("", "ed233_c_avalalternativa", "", $sWhereParametros);
$rsParametros      = $oDaoEduParametros->sql_record($sSqlParametros);
if ($oDaoEduParametros->numrows>0) {
  db_fieldsmemory($rsParametros, 0);
}else{
  $ed233_c_avalalternativa = "N";
}

function ElementosFreq($ed67_i_procresultado) {
	
 $sSql    = "SELECT * FROM avalfreqres WHERE ed67_i_procresultado = $ed67_i_procresultado ";
 $rs      = pg_query($sSql);
 $iLinhas = pg_num_rows($rs);
 return $iLinhas;
 
}

if (isset($alterar)) {
	
  $db_opcao  = 2;
  $db_opcao1 = 3;
  $db_botao  = true;
  
  if ($ed43_c_obtencao != "AT" && ElementosAprov($chavepesquisa) == 0) {
  	
    db_msgbox("Informe os elementos para compor este resultado!");
    ?>
    <script>
      parent.iframe_c2.location = 'edu1_avalcompoeres001.php?procedimento=<?=$ed43_i_procedimento?>'+
                                  '&ed44_i_procresultado=<?=$ed43_i_codigo?>'+
                                  '&ed42_c_descr=<?=$ed42_c_descr?>&forma=<?=$forma?>';
      parent.mo_camada('c2');
    </script>
    <?
    $semelementos = true;
    
  } else {
  	
    db_inicio_transacao();
    if (@$minimo == "definido") {
      $ed43_c_minimoaprov = $minimodaforma;
    } else {
      $ed43_c_minimoaprov = $forma == "NOTA" ? number_format(@$ed43_c_minimoaprov, 2, ".", ".") : @$ed43_c_minimoaprov;
    }
    
    if ($ed43_c_obtencao == "AT") {
    	
      $oDaoAvalCompoeRes->excluir("", " ed44_i_procresultado = $ed43_i_codigo");
      $oDaoResCompoeRes->excluir("", " ed68_i_procresultado = $ed43_i_codigo");
      
    }
    
    if ($ed43_c_geraresultado == "N") {
    	
      $oDaoAvalFreqres->excluir("", " ed67_i_procresultado = $ed43_i_codigo");
      $oDaoProcResultado->ed43_c_reprovafreq = "N";
      
    } elseif ($ed43_c_geraresultado == "S" && ElementosFreq($ed43_i_codigo) == 0) {
      $oDaoProcResultado->ed43_c_reprovafreq = "N";
    }
    
    $oDaoProcResultado->ed43_c_minimoaprov = $ed43_c_minimoaprov;
    $oDaoProcResultado->alterar($ed43_i_codigo);
    db_fim_transacao();
    
 }
 
 $sWhereProcResultado = " ed43_i_procedimento = $ed43_i_procedimento";
 $sSqlProcResultado   = $oDaoProcResultado->sql_query("", "ed43_i_resultado as resjacad", "", $sWhereProcResultado);
 $rsProcResultado     = $oDaoProcResultado->sql_record($sSqlProcResultado);
 
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
 
} elseif (isset($chavepesquisa) && !isset($alterar)) {
	
  $db_opcao      = 2;
  $db_opcao1     = 3;
  $db_botao      = true;
  $sSqlResultado = $oDaoProcResultado->sql_query($chavepesquisa);
  $rsResultado   = $oDaoProcResultado->sql_record($sSqlResultado);
  db_fieldsmemory($rsResultado, 0);
  
  $sWhereProcAvaliacao = " ed41_i_procedimento = $ed43_i_procedimento AND ed37_c_tipo = '$forma'";
  $sSqlProcAvaliacao   = $oDaoProcAvaliacao->sql_query("", "*", "", $sWhereProcAvaliacao);
  $rsProcAvaliacao     = $oDaoProcAvaliacao->sql_record($sSqlProcAvaliacao);
  $qtdperiodos         = $oDaoProcAvaliacao->numrows;
  
  if ($oDaoProcAvaliacao->numrows > 0) {
  	
    ?>
    <script>
     parent.document.formaba.c2.disabled    = false;
     parent.document.formaba.c2.style.color = "black";
     parent.iframe_c2.location.href         = 'edu1_avalcompoeres001.php?procedimento=<?=$ed43_i_procedimento?>'+
                                              '&ed44_i_procresultado=<?=$ed43_i_codigo?>'+
                                              '&ed42_c_descr=<?=$ed42_c_descr?>&forma=<?=$forma?>';
    </script>
    <?
   
  }
 
  $sSqlAvaliacao = $oDaoProcAvaliacao->sql_query("", "*", "", " ed41_i_procedimento = $ed43_i_procedimento");
  $rsAvaliacao   = $oDaoProcAvaliacao->sql_record($sSqlAvaliacao);
  
  if ($oDaoProcAvaliacao->numrows > 0) {
 	
    ?>
     <script>
      parent.document.formaba.c3.disabled    = false;
      parent.document.formaba.c3.style.color = "black";
      parent.iframe_c3.location.href         = 'edu1_avalfreqres001.php?procedimento=<?=$ed43_i_procedimento?>'+
                                               '&ed67_i_procresultado=<?=$ed43_i_codigo?>'+
                                               '&ed42_c_descr=<?=$ed42_c_descr?>&forma=<?=$forma?>';
     </script>
    <?
   
  }
 
  $sWhereProcResult = " ed43_i_procedimento = $ed43_i_procedimento";
  $sSqlProcResult   = $oDaoProcResultado->sql_query("", "ed43_i_resultado as resjacad", "", $sWhereProcResult);
  $rsProcResult     = $oDaoProcResultado->sql_record($sSqlProcResult);
  
  if ($oDaoProcResultado->numrows > 0) {
  	
    $sep     = "";
    $res_cad = "";
    
    for ($c = 0; $c < $oDaoProcResultado->numrows; $c++) {
    	
      db_fieldsmemory($rsProcResult, $c);
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
  <table width="100%" border="0" cellspacing="0" cellpadding="0" align="left">
   <tr>
    <td valign="top" bgcolor="#CCCCCC">
     <br>
     <center>
      <fieldset style="width:95%;"><legend><b>Altera��o do Resultado <?=@$ed42_c_descr?></b></legend>
        <?include("forms/db_frmprocresultado.php");?>
      </fieldset>
     </center>
    </td>
   </tr>
  </table>
 </body>
</html>
<script>
js_tabulacaoforms("form1", "ed43_c_obtencao", true, 1, "ed43_c_obtencao", true);
</script>
<?
if (isset($alterar)) {
	
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
    if (!isset($semelementos)) {
    	
      ?>
      <script>
       top.corpo.iframe_a2.location.href = "edu1_avaliacoes.php?procedimento=<?=$ed43_i_procedimento?>"+
                                           "&forma=<?=$forma?>&ed40_c_descr=<?=$ed40_c_descr?>"
      </script>
      <?
      
    }
    
  }
  
}

function ElementosAprov($chavepesquisa) {
	
  $sSqlUnion    = " SELECT ed44_i_codigo,  ";
  $sSqlUnion   .= "        ed44_i_procavaliacao, "; 
  $sSqlUnion   .= "        ed09_c_descr, "; 
  $sSqlUnion   .= "        case ";
  $sSqlUnion   .= "          when ed44_i_codigo>0 then 'AVALIA��O PERI�DICA' end as ed14_c_descr, "; 
  $sSqlUnion   .= "        ed44_i_peso, ";
  $sSqlUnion   .= "        ed44_c_minimoaprov,  ";
  $sSqlUnion   .= "        ed44_c_obrigatorio,  ";
  $sSqlUnion   .= "        ed41_i_sequencia ";
  $sSqlUnion   .= " FROM avalcompoeres ";
  $sSqlUnion   .= "      inner join procavaliacao on procavaliacao.ed41_i_codigo = avalcompoeres.ed44_i_procavaliacao ";
  $sSqlUnion   .= "      inner join periodoavaliacao on periodoavaliacao.ed09_i_codigo = procavaliacao.ed41_i_periodoavaliacao ";
  $sSqlUnion   .= " WHERE ed44_i_procresultado = $chavepesquisa ";
  $sSqlUnion   .= " UNION ";
  $sSqlUnion   .= " SELECT ed68_i_codigo,  ";
  $sSqlUnion   .= "        ed68_i_procresultcomp, "; 
  $sSqlUnion   .= "        ed42_c_descr, "; 
  $sSqlUnion   .= "        case ";
  $sSqlUnion   .= "         when ed68_i_codigo>0 then 'RESULTADO' end as ed14_c_descr, "; 
  $sSqlUnion   .= "        ed68_i_peso,  ";
  $sSqlUnion   .= "        ed68_c_minimoaprov, "; 
  $sSqlUnion   .= "        ed43_c_boletim, ";
  $sSqlUnion   .= "        ed43_i_sequencia ";
  $sSqlUnion   .= " FROM rescompoeres ";
  $sSqlUnion   .= "      inner join procresultado on procresultado.ed43_i_codigo = rescompoeres.ed68_i_procresultcomp ";
  $sSqlUnion   .= "      inner join resultado on resultado.ed42_i_codigo = procresultado.ed43_i_resultado ";
  $sSqlUnion   .= " WHERE ed68_i_procresultado = $chavepesquisa ";
  $sSqlUnion   .= " ORDER BY ed41_i_sequencia ";
  $rsUnion      = pg_query($sSqlUnion);
  $iLinhasUnion = pg_num_rows($rsUnion);
  return $iLinhasUnion;
  
}

if (ElementosAprov($chavepesquisa) > 0 && $ed233_c_avalalternativa == "S" && $ed43_c_obtencao == "SO") {

  ?>
  <script>
  parent.document.formaba.c4.disabled    = false;
  parent.document.formaba.c4.style.color = "black";
  parent.iframe_c4.location.href         = 'edu1_procavalalternativa001.php?procedimento=<?=$ed43_i_procedimento?>'+
                                           '&ed281_i_procresultado=<?=$ed43_i_codigo?>'+
                                           '&ed42_c_descr=<?=$ed42_c_descr?>&forma=<?=$forma?>';
  </script>
  <?

}
?>