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

require("libs/db_stdlibwebseller.php");
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_amparo_classe.php");
include("classes/db_regencia_classe.php");
include("classes/db_diarioavaliacao_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clamparo = new cl_amparo;
$clregencia = new cl_regencia;
$cldiarioavaliacao = new cl_diarioavaliacao;
$result = $clregencia->sql_record($clregencia->sql_query("","ed220_i_procedimento,ed59_c_freqglob","","ed59_i_codigo = $regencia"));
db_fieldsmemory($result,0);
if(isset($opcao) && $opcao=="I"){
 $db_opcao = 1;
}elseif(isset($opcao) && $opcao=="A"){
 $db_opcao = 2;
}elseif(isset($opcao) && $opcao=="E"){
 $db_opcao = 3;
}
$db_botao = true;
if(isset($incluir)){
 db_inicio_transacao();
 $tam_alunos = sizeof($alunos);
 $tam_aval = sizeof($avaliacoes);
 $ed81_c_todoperiodo = $ed81_i_convencaoamp!=""?"S":$ed81_c_todoperiodo;
 $ed81_i_justificativa = $ed81_i_justificativa==""?"":$ed81_i_justificativa;
 $ed81_i_convencaoamp = $ed81_i_convencaoamp==""?"":$ed81_i_convencaoamp;
 for($x=0;$x<$tam_alunos;$x++){
  $ed81_i_codigo = "";
  db_inicio_transacao();
  $clamparo->ed81_i_diario = $alunos[$x];
  $clamparo->ed81_i_justificativa = $ed81_i_justificativa;
  $clamparo->ed81_i_convencaoamp = $ed81_i_convencaoamp;
  $clamparo->ed81_c_todoperiodo = $ed81_c_todoperiodo;
  $clamparo->ed81_c_aprovch = $ed81_c_aprovch;
  $clamparo->incluir($ed81_i_codigo);
  db_fim_transacao();
  for($y=0;$y<$tam_aval;$y++){
   $sql = "UPDATE diarioavaliacao SET
            ed72_c_amparo = 'S'
           WHERE ed72_i_diario = $alunos[$x]
           AND ed72_i_procavaliacao = $avaliacoes[$y]
          ";
   $query = pg_query($sql);
  }
  if($ed81_i_convencaoamp!=""){
   $result11 = $cldiarioavaliacao->sql_record($cldiarioavaliacao->sql_query("","max(ed41_i_sequencia)",""," ed72_i_diario = $alunos[$x] AND ed72_c_amparo = 'S'"));
   db_fieldsmemory($result11,0);
   $result12 = $cldiarioavaliacao->sql_record($cldiarioavaliacao->sql_query("","ed72_i_codigo as diarioavalamparado",""," ed72_i_diario = $alunos[$x] AND ed41_i_sequencia > $max AND ed09_c_somach = 'S'"));
   if($cldiarioavaliacao->numrows>0){
    for($y=0;$y<$cldiarioavaliacao->numrows;$y++){
     db_fieldsmemory($result12,$y);
     $sql = "UPDATE diarioavaliacao SET
              ed72_c_amparo = 'S'
             WHERE ed72_i_codigo = $diarioavalamparado
            ";
     $query = pg_query($sql);
    }
   }
  }
 }
 db_fim_transacao();
}
if(isset($alterar)){
 $ed81_c_todoperiodo = $ed81_i_convencaoamp!=""?"S":$ed81_c_todoperiodo;
 $ed81_i_justificativa = $ed81_i_justificativa==""?"null":$ed81_i_justificativa;
 $ed81_i_convencaoamp = $ed81_i_convencaoamp==""?"null":$ed81_i_convencaoamp;
 $tam_aval = sizeof($avaliacoes);
 $procaval = "";
 $sep = "";
 for($x=0;$x<$tam_aval;$x++){
  $sql = "UPDATE diarioavaliacao SET
           ed72_c_amparo = 'S'
          WHERE ed72_i_diario = $alunosdiario
          AND ed72_i_procavaliacao = $avaliacoes[$x]
         ";
  $query = pg_query($sql);
  $procaval .= $sep.$avaliacoes[$x];
  $sep = ",";
 }
 $sql = "UPDATE diarioavaliacao SET
          ed72_c_amparo = 'N'
         WHERE ed72_i_diario = $alunosdiario
         AND ed72_i_procavaliacao not in ($procaval);
        ";
 $query = pg_query($sql);
 $sql = "UPDATE amparo SET
          ed81_c_todoperiodo = '$ed81_c_todoperiodo',
          ed81_i_justificativa = $ed81_i_justificativa,
          ed81_i_convencaoamp = $ed81_i_convencaoamp,
          ed81_c_aprovch = '$ed81_c_aprovch'
         WHERE ed81_i_diario = $alunosdiario
        ";
 $query = pg_query($sql);
 if($ed81_i_convencaoamp!="" && $ed81_i_convencaoamp!="null"){
  $result11 = $cldiarioavaliacao->sql_record($cldiarioavaliacao->sql_query("","max(ed41_i_sequencia)",""," ed72_i_diario = $alunosdiario AND ed72_c_amparo = 'S'"));
  db_fieldsmemory($result11,0);
  $result12 = $cldiarioavaliacao->sql_record($cldiarioavaliacao->sql_query("","ed72_i_codigo as diarioavalamparado",""," ed72_i_diario = $alunosdiario AND ed41_i_sequencia > $max AND ed09_c_somach = 'S'"));
  if($cldiarioavaliacao->numrows>0){
   for($y=0;$y<$cldiarioavaliacao->numrows;$y++){
    db_fieldsmemory($result12,$y);
    $sql = "UPDATE diarioavaliacao SET
             ed72_c_amparo = 'S'
            WHERE ed72_i_codigo = $diarioavalamparado
           ";
    $query = pg_query($sql);
   }
  }
 }
}
if(isset($excluir)){
 $tam_alunos = sizeof($alunos);
 for($x=0;$x<$tam_alunos;$x++){
  db_inicio_transacao();
  $clamparo->excluir(""," ed81_i_diario = $alunos[$x]");
  db_fim_transacao();
  $sql = "UPDATE diarioavaliacao SET
           ed72_c_amparo = 'N'
          WHERE ed72_i_diario = $alunos[$x]
         ";
  $query = pg_query($sql);
 }
}
if(isset($incluir) || isset($alterar) || isset($excluir )){
 $sql = "SELECT DISTINCT ed73_i_procresultado as frame
         FROM diario
          inner join diarioresultado on ed73_i_diario = ed95_i_codigo
         WHERE ed95_i_regencia = $regencia
        ";
 $query = pg_query($sql);
 $linhas = pg_num_rows($query);
 for($y=0;$y<$linhas;$y++){
  db_fieldsmemory($query,$y);
  ?>
  <script>
   parent.iframe_R<?=$frame?>.location.href = "edu1_diarioresultado001.php?regencia=<?=$regencia?>&ed43_i_codigo=<?=$frame?>";
  </script>
  <?
 }
 ?>
 <script>
  parent.iframe_RF.location.href = "edu1_diariofinal001.php?regencia=<?=$regencia?>";
 </script>
 <?
 db_redireciona("edu1_amparo001.php?regencia=$regencia");
 exit;
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
<table width="96%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td valign="top" bgcolor="#CCCCCC">
   <center>
    <input type="radio" name="opcao" value="I" onclick="location.href='edu1_amparo001.php?regencia=<?=$regencia?>&opcao=I'" <?=@$opcao=="I"?"checked":""?>> Incluir
    <input type="radio" name="opcao" value="A" onclick="location.href='edu1_amparo001.php?regencia=<?=$regencia?>&opcao=A'" <?=@$opcao=="A"?"checked":""?>> Alterar
    <input type="radio" name="opcao" value="E" onclick="location.href='edu1_amparo001.php?regencia=<?=$regencia?>&opcao=E'" <?=@$opcao=="E"?"checked":""?>> Excluir
    <?
    if(isset($opcao) && $opcao=="I"){
     ?><fieldset style="width:95%"><legend><b>Inclus�o de Amparo</b></legend><?
      include("forms/db_frmamparo001.php");
     ?></fieldset><?
    }elseif(isset($opcao) && $opcao=="A"){
     ?><fieldset style="width:95%"><legend><b>Altera��o de Amparo</b></legend><?
      include("forms/db_frmamparo002.php");
     ?></fieldset><?
    }elseif(isset($opcao) && $opcao=="E"){
     ?><fieldset style="width:95%"><legend><b>Exclus�o de Amparo</b></legend><?
      include("forms/db_frmamparo003.php");
     ?></fieldset><?
    }
    ?>
   </center>
  </td>
 </tr>
</table>
</body>
</html>
<script>
//js_tabulacaoforms("form1","ed81_i_diario",true,1,"ed81_i_diario",true);
</script>