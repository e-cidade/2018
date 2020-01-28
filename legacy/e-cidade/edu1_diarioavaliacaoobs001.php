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
include("classes/db_diarioavaliacao_classe.php");
include("classes/db_diario_classe.php");
include("classes/db_regencia_classe.php");
include("classes/db_periodoavaliacao_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$resultedu= eduparametros(db_getsession("DB_coddepto"));
$cldiarioavaliacao = new cl_diarioavaliacao;
$cldiario = new cl_diario;
$clregencia = new cl_regencia;
$clperiodoavaliacao = new cl_periodoavaliacao;
$db_opcao = 2;
$db_botao = true;
if(isset($alterar)){
 db_inicio_transacao();
 $sql_reg = "SELECT ed95_i_regencia as codregatual
             FROM diarioavaliacao
              inner join diario on ed95_i_codigo = ed72_i_diario
             WHERE ed72_i_codigo = $ed93_i_diarioavaliacao
            ";
 $result_reg = pg_query($sql_reg);
 db_fieldsmemory($result_reg,0);
 $cldiarioavaliacao->ed72_i_valornota = $nota;
 $cldiarioavaliacao->ed72_c_valorconceito = $conceito;
 $cldiarioavaliacao->ed72_i_numfaltas = $faltas;
 $cldiarioavaliacao->ed72_t_parecer = $parecer;
 $cldiarioavaliacao->ed72_t_obs = $ed72_t_obs;
 $cldiarioavaliacao->ed72_i_codigo = $ed93_i_diarioavaliacao;
 $cldiarioavaliacao->alterar($ed93_i_diarioavaliacao);
 $sql_r = "SELECT DISTINCT max(ed09_i_sequencia)
           FROM diarioavaliacao
            inner join diario on diario.ed95_i_codigo = diarioavaliacao.ed72_i_diario
            inner join procavaliacao on procavaliacao.ed41_i_codigo = diarioavaliacao.ed72_i_procavaliacao
            inner join periodoavaliacao on periodoavaliacao.ed09_i_codigo = procavaliacao.ed41_i_periodoavaliacao
           WHERE diario.ed95_i_regencia = $codregatual
           AND
           (diarioavaliacao.ed72_i_numfaltas is not null
            OR diarioavaliacao.ed72_i_valornota is not null
            OR diarioavaliacao.ed72_c_valorconceito != ''
            OR diarioavaliacao.ed72_t_parecer != '')
          ";
 $result_r = pg_query($sql_r);
 $linhas = pg_num_rows($result_r);
 db_fieldsmemory($result_r,0);
 if($max==""){
  $clregencia->ed59_c_ultatualiz = "SI";
  $clregencia->ed59_i_codigo = $codregatual;
  $clregencia->alterar($codregatual);
 }else{
  $result_p = $clperiodoavaliacao->sql_record($clperiodoavaliacao->sql_query_file("","ed09_c_abrev","","ed09_i_sequencia = $max"));
  db_fieldsmemory($result_p,0);
  $clregencia->ed59_c_ultatualiz = $ed09_c_abrev;
  $clregencia->ed59_i_codigo = $codregatual;
  $clregencia->alterar($codregatual);
 }
 $tam = isset($reg_outras)?sizeof($reg_outras):0;
 if($tam>0){
  $regs = "";
  $sep = "";
  for($x=0;$x<$tam;$x++){
   $regs .= $sep.$reg_outras[$x];
   $sep = ",";
  }
  $result = $cldiarioavaliacao->sql_record($cldiarioavaliacao->sql_query("","ed72_i_valornota,ed72_c_valorconceito,ed72_i_numfaltas,ed72_t_parecer,ed95_i_regencia as codregmais,ed72_i_codigo as ed93_i_diarioavaliacao",""," ed95_i_regencia in($regs) AND ed95_i_aluno = $codaluno AND ed72_i_procavaliacao = $codperiodo"));
  $linhas_dia = pg_num_rows($result);
  for($t=0;$t<$linhas_dia;$t++){
   db_fieldsmemory($result,$t);
   $cldiarioavaliacao->ed72_i_valornota = $ed72_i_valornota;
   $cldiarioavaliacao->ed72_c_valorconceito = $ed72_c_valorconceito;
   $cldiarioavaliacao->ed72_i_numfaltas = $ed72_i_numfaltas;
   $cldiarioavaliacao->ed72_t_parecer = $ed72_t_parecer;
   $cldiarioavaliacao->ed72_t_obs = $ed72_t_obs;
   $cldiarioavaliacao->ed72_i_codigo = $ed93_i_diarioavaliacao;
   $cldiarioavaliacao->alterar($ed93_i_diarioavaliacao);
   $sql_r = "SELECT DISTINCT max(ed09_i_sequencia)
             FROM diarioavaliacao
              inner join diario on diario.ed95_i_codigo = diarioavaliacao.ed72_i_diario
              inner join procavaliacao on procavaliacao.ed41_i_codigo = diarioavaliacao.ed72_i_procavaliacao
              inner join periodoavaliacao on periodoavaliacao.ed09_i_codigo = procavaliacao.ed41_i_periodoavaliacao
             WHERE diario.ed95_i_regencia = $codregmais
             AND
             (diarioavaliacao.ed72_i_numfaltas is not null
              OR diarioavaliacao.ed72_i_valornota is not null
              OR diarioavaliacao.ed72_c_valorconceito != ''
              OR diarioavaliacao.ed72_t_parecer != '')
            ";
   $result_r = pg_query($sql_r);
   $linhas = pg_num_rows($result_r);
   db_fieldsmemory($result_r,0);
   if($max==""){
    $clregencia->ed59_c_ultatualiz = "SI";
    $clregencia->ed59_i_codigo = $codregmais;
    $clregencia->alterar($codregmais);
   }else{
    $result_p = $clperiodoavaliacao->sql_record($clperiodoavaliacao->sql_query_file("","ed09_c_abrev","","ed09_i_sequencia = $max"));
    db_fieldsmemory($result_p,0);
    $clregencia->ed59_c_ultatualiz = $ed09_c_abrev;
    $clregencia->ed59_i_codigo = $codregmais;
    $clregencia->alterar($codregmais);
   }
  }
 }
 db_fim_transacao();
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style>
.titulo{
 font-size: 11;
 color: #DEB887;
 background-color:#444444;
 font-weight: bold;
}
.cabec1{
 font-size: 11;
 color: #000000;
 background-color:#999999;
 font-weight: bold;
}
.aluno{
 color: #000000;
 font-family : Tahoma;
 font-size: 9;
}
</style>
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td valign="top" bgcolor="#CCCCCC">
   <center>
    <?include("forms/db_frmdiarioavaliacaoobs.php");?>
   </center>
  </td>
 </tr>
</table>
</body>
</html>
<script>
 js_tabulacaoforms("form1","ed72_t_obs",true,1,"ed72_t_obs",true);
</script>
<?
if(isset($alterar)){
 ?>
 <script>
  parent.location.href = "edu1_diarioavaliacao001.php?regencia=<?=$codregatual?>&ed41_i_codigo=<?=$codperiodo?>"
  parent.db_iframe_obs.hide();
  alert("Alteração efetuada com Sucesso");
 </script>
 <?
}
?>