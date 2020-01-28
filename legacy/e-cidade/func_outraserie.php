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

//MODULO: educação
include("libs/db_stdlibwebseller.php");
require("libs/db_stdlib.php");
require("libs/db_utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_serie_classe.php");
include("classes/db_baseserie_classe.php");
include("classes/db_basemps_classe.php");
include("classes/db_base_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clserie     = new cl_serie;
$clbaseserie = new cl_baseserie;
$clbasemps   = new cl_basemps;
$clbase      = new cl_base;

$lErro = false;

if (isset($serie_codigos)) {

	$rsOrdenacao = $clbasemps->sql_record($clbasemps->sql_query_file("","ed34_i_ordenacao",""," ed34_i_base = {$base} AND ed34_i_serie = {$serie} and ed34_i_disciplina = {$disciplina}"));
	if($clbasemps->numrows>0){
		$ordenacao = db_utils::fieldsMemory($rsOrdenacao, 0)->ed34_i_ordenacao;
	}

	db_inicio_transacao();

  $cod_unit = explode(",",$serie_codigos);
  for ($x = 0; $x < count($cod_unit); $x++) {

    $ed34_i_codigo = "";

    $clbasemps->ed34_lancarhistorico = 'true';
    if (isset($ed34_c_condicao) && $ed34_c_condicao == 'OP') {
      $clbasemps->ed34_lancarhistorico = $ed34_lancarhistorico == 't'? 'true':'false';
    }

    $clbasemps->ed34_i_base          = $base;
    $clbasemps->ed34_i_serie         = $cod_unit[$x];
    $clbasemps->ed34_i_disciplina    = $disciplina;
    $clbasemps->ed34_i_qtdperiodo    = $nperiodos;
    $clbasemps->ed34_i_chtotal       = 0;
    $clbasemps->ed34_i_ordenacao     = $ordenacao;
    $clbasemps->ed34_c_condicao      = $condicao;
    $clbasemps->incluir($ed34_i_codigo);
    if ($clbasemps->erro_status == "0") {
    	$lErro = true;
    }


  }

  db_fim_transacao($lErro);

  $clbasemps->erro(true,false);

  ?>
  <script>
   parent.db_iframe_outraserie.hide();
   parent.js_refresh();
  </script>
  <?
  exit;
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<form name="form2" method="post" action="">
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
 <tr>
  <td align="center" valign="top">
   <b>Selecione as outras etapas para conter esta disciplina.</b>
   <?
   $sCampos = "si.ed11_i_sequencia as inicial,sf.ed11_i_sequencia as final,si.ed11_i_ensino as ensino";
   $sql     = $clbaseserie->sql_query("",
                                      $sCampos,
                                      "",
                                      " ed87_i_codigo = $base"
                                     );
   $result  = $clbaseserie->sql_record($sql);
   db_fieldsmemory($result,0);
   $sql1    = " SELECT ed11_i_codigo,ed11_c_descr ";
   $sql1   .= " FROM serie ";
   $sql1   .= " WHERE ed11_i_codigo not in((select distinct ed11_i_codigo ";
   $sql1   .= "                            from basemps ";
   $sql1   .= "                             inner join serie on ed11_i_codigo = ed34_i_serie ";
   $sql1   .= "                             where ed34_i_base = $base ";
   $sql1   .= "                             and ed34_i_disciplina in ($disciplina))) ";
   $sql1   .= " AND ed11_i_sequencia >= $inicial ";
   $sql1   .= " AND ed11_i_sequencia <= $final ";
   $sql1   .= " AND ed11_i_ensino = $ensino ";
   $sql1   .= " AND ed11_i_codigo not in ($serie) ";
   $sql1   .= " ORDER BY ed11_i_sequencia ";
   $result1 = db_query($sql1);
   $linhas1 = pg_num_rows($result1);
   if ($linhas1 == 0) {

     ?>
     <script>
      parent.db_iframe_outraserie.hide();
      parent.js_refresh();
     </script>
     <?
     exit;

   }
   ?>
   <select name="series[]" id="series" size="10" style="font-size:9px;width:330px;" multiple>
   <?
   for ($x = 0; $x < $linhas1; $x++) {

     db_fieldsmemory($result1,$x);
     echo "<option value='$ed11_i_codigo'>$ed11_c_descr</option>";

   }
   ?>
   </select><br><br>
   <input type="button" value="Confirmar" onClick="js_confirmaserie();">
   <input type="button" value="Cancelar" onClick="js_fechar();">
  </td>
 </tr>
 <tr>
  <td align="center" valign="top">
  </td>
 </tr>
</table>
</form>
</body>
</html>
<script>
function js_confirmaserie(){

  qtd      = document.form2.series.length;
  sel      = 0;
  codserie = "";
  sep      = "";

  for (i = 0; i < qtd; i++) {

    if (document.form2.series.options[i].selected == true) {

      sel++;
      codserie += sep+document.form2.series.options[i].value;
      sep       = ",";

    }
  }

  if (sel == 0) {
    alert("Seleciona alguma Etapa!");
  } else {

    location.href = "func_outraserie.php?serie_codigos="+codserie+"&base=<?=$base?>&disciplina=<?=$disciplina?>"+
                     "&nperiodos=<?=$nperiodos?>&serie=<?=$serie?>&condicao=<?=$condicao?>"+
                     "&discglob=<?=$discglob?>&qtdper=<?=$qtdper?>&lLancarHistorico=<?=$lLancarHistorico?>";

  }
}

function js_fechar() {

  <?
  $sql    = $clbasemps->sql_query("",
                                  "distinct ed31_i_curso,ed31_c_descr,ed11_c_descr",
                                  "",
                                  " ed34_i_base = $base AND ed34_i_serie = $serie"
                                 );
  $result = $clbasemps->sql_record($sql);
  db_fieldsmemory($result,0);
  ?>
  parent.location.href = "edu1_basemps001.php?ed34_i_base=<?=$base?>&ed31_c_descr=<?=$ed31_c_descr?>"+
                         "&curso=<?=$ed31_i_curso?>&ed34_i_serie=<?=$serie?>&ed11_c_descr=<?=$ed11_c_descr?>"+
                         "&discglob=<?=$discglob?>&qtdper=<?=$qtdper?>&lLancarHistorico=<?=$lLancarHistorico?>";
  parent.db_iframe_outraserie.hide();

}

parent.db_iframe_outraserie.liberarJanBTFechar('false');
parent.db_iframe_outraserie.liberarJanBTMinimizar('false');
parent.db_iframe_outraserie.liberarJanBTMaximizar('false');
</script>
