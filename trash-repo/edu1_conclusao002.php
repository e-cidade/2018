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
include("classes/db_baseserie_classe.php");
include("classes/db_cursoedu_classe.php");
include("classes/db_serie_classe.php");
include("classes/db_serieequiv_classe.php");
include("classes/db_base_classe.php");
include("classes/db_aluno_classe.php");
include("classes/db_alunocurso_classe.php");
include("classes/db_historicomps_classe.php");
include("classes/db_historico_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clbaseserie = new cl_baseserie;
$clcursoedu = new cl_curso;
$clserie = new cl_serie;
$clserieequiv = new cl_serieequiv;
$clbase = new cl_base;
$claluno = new cl_aluno;
$clalunocurso = new cl_alunocurso;
$clhistoricomps = new cl_historicomps;
$clhistorico = new cl_historico;
$db_opcao = 1;
$db_botao = false;
$escola = db_getsession("DB_coddepto");
$result = $clcursoedu->sql_record($clcursoedu->sql_query("","ed29_c_descr as nomecurso",""," ed29_i_codigo = $curso"));
db_fieldsmemory($result,0);
if(isset($concluir)){
 db_inicio_transacao();
 $alunoconc = explode("#",$concluidos);
 for($x=0;$x<count($alunoconc);$x++){
  $result_cal = $clalunocurso->sql_record($clalunocurso->sql_query("","ed52_i_ano as anoconc,ed52_i_periodo as periodoconc",""," ed56_i_aluno = $alunoconc[$x]"));
  if($clalunocurso->numrows>0){
   db_fieldsmemory($result_cal,0);
  }else{
  	$sql = "SELECT  ed62_i_anoref as anoconc,ed62_i_periodoref as periodoconc
                 FROM historicomps
                 inner join historico on ed61_i_codigo = ed62_i_historico
                 WHERE ed61_i_aluno = $alunoconc[$x]
              UNION
            SELECT ed99_i_anoref as anoconc,ed99_i_periodoref as periodoconc
                 FROM historicompsfora
                 inner join historico on ed61_i_codigo = ed99_i_historico
                 WHERE ed61_i_aluno = $alunoconc[$x]
                 ORDER BY anoconc desc
                 LIMIT 1";
  	$result = pg_query($sql);
        db_fieldsmemory($result,0);
  }
  $sql = "UPDATE alunocurso SET
           ed56_c_situacao = 'CONCLUÍDO'
          WHERE ed56_i_aluno = $alunoconc[$x]
         ";
  $result = pg_query($sql);
  $sql = "UPDATE historico SET
           ed61_i_anoconc = $anoconc,
           ed61_i_periodoconc = $periodoconc
          WHERE ed61_i_aluno = $alunoconc[$x]
          AND ed61_i_curso = $curso
         ";
  $result = pg_query($sql);
 }
 db_fim_transacao();
 ?>
 <script>
  alert("Conclusão Efetuada com Sucesso!");
  parent.db_iframe_concluir.hide();
  parent.location.href = "edu1_conclusao001.php";
 </script>
 <?
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
.cabec2{
 font-size: 11;
 color: #000000;
 background-color:#FF9B9B;
 font-weight: bold;
}
.cabec3{
 font-size: 11;
 color: #000000;
 background-color:#BBFFBB;
 font-weight: bold;
}
.aluno{
 color: #000000;
 font-family : Tahoma;
 font-size: 10;
}
</style>
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="1" cellspacing="0" cellpadding="0">
 <tr>
  <td class="titulo">
   Conclusão do curso <?=$nomecurso?>
  </td>
 </tr>
 <tr>
  <td class="cabec2" align="center">Alunos selecionados que não podem concluir o curso</td>
 </tr>
 <tr>
  <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
  <?
  $codaluno = explode(",",$alunos);
  $alunoconcluido = "";
  $codalunoconcluido = "";
  $sep = "";
  for($x=0;$x<count($codaluno);$x++){
   $sql_hist = "SELECT ed61_i_codigo,ed47_v_nome,ed61_i_aluno
                FROM aluno
                 inner join historico on ed61_i_aluno = ed47_i_codigo
                WHERE ed61_i_curso = $curso
                AND ed47_i_codigo = $codaluno[$x]
                ORDER BY ed47_v_nome
               ";
   $result_hist = pg_query($sql_hist);
   $linhas_hist = pg_num_rows($result_hist);
   if($linhas_hist==0){
    $erro1 = "OK";
    $result_alu = $claluno->sql_record($claluno->sql_query("","ed47_v_nome",""," ed47_i_codigo = $codaluno[$x]"));
    db_fieldsmemory($result_alu,0);
    ?>
    <table width="100%" border="1" cellspacing="0" cellpadding="0">
     <tr>
      <td class="cabec1"><?=$ed47_v_nome?> - <?=$codaluno[$x]?></td>
     </tr>
     <tr>
      <td class="aluno" bgcolor="#f3f3f3">&nbsp;&nbsp; - ALUNO NÃO PODE CONCLUIR POIS NÃO POSSUI HISTÓRICO PARA ESTE CURSO</td>
     </tr>
    </table>
    <?
   }else{
    $erro = "";
    db_fieldsmemory($result_hist,0);
    $result_curso = $clcursoedu->sql_record($clcursoedu->sql_query("","ed29_i_ensino",""," ed29_i_codigo = $curso"));
    db_fieldsmemory($result_curso,0);
    $result_serie = $clserie->sql_record($clserie->sql_query("","ed11_i_codigo as seriedabase,ed11_c_descr as nomeserie","ed11_i_sequencia"," ed11_i_ensino = $ed29_i_ensino"));
    for($y=0;$y<$clserie->numrows;$y++){
     db_fieldsmemory($result_serie,$y);
     $result_serieequiv = $clserieequiv->sql_record($clserieequiv->sql_query("","ed234_i_serieequiv",""," ed234_i_serie = $seriedabase"));
     $equivalencias = "";
     $sep_equiv = "";
     if($clserieequiv->numrows>0){
      for($r=0;$r<$clserieequiv->numrows;$r++){
       db_fieldsmemory($result_serieequiv,$r);
       $equivalencias .= $sep_equiv.$ed234_i_serieequiv;
       $sep_equiv = ",";
      }
     }else{
      $equivalencias = 0;
     }
     $sql_s = "SELECT ed62_c_resultadofinal as resultado
               FROM historicomps
                inner join historico on ed61_i_codigo = ed62_i_historico
               WHERE ed61_i_aluno = $ed61_i_aluno
               AND (ed62_i_serie = $seriedabase OR ed62_i_serie in ($equivalencias))
               UNION
               SELECT ed99_c_resultadofinal as resultado
               FROM historicompsfora
                inner join historico on ed61_i_codigo = ed99_i_historico
               WHERE ed61_i_aluno = $ed61_i_aluno
               AND (ed99_i_serie = $seriedabase OR ed99_i_serie in ($equivalencias))
              ";
     $result_s = pg_query($sql_s);
     $linhas_s = pg_num_rows($result_s);
     if($linhas_s==0){
      $erro .= "&nbsp;&nbsp; - SÉRIE ".$nomeserie." NÃO CONSTA NO HISTÓRICO<br>";
     }elseif($linhas_s==1){
      db_fieldsmemory($result_s,0);
      if($resultado=="R"){
       $erro .= "&nbsp;&nbsp; - SÉRIE ".$nomeserie." CONSTA COMO REPROVADO<br>";
      }
     }elseif($linhas_s>1){
      $sql_s1 = "SELECT ed62_c_resultadofinal as resultado
                 FROM historicomps
                  inner join historico on ed61_i_codigo = ed62_i_historico
                 WHERE ed61_i_aluno = $ed61_i_aluno
                 AND (ed62_i_serie = $seriedabase OR ed62_i_serie in ($equivalencias))
                 AND ed62_c_resultadofinal = 'A'
                 UNION
                 SELECT ed99_c_resultadofinal as resultado
                 FROM historicompsfora
                  inner join historico on ed61_i_codigo = ed99_i_historico
                 WHERE ed61_i_aluno = $ed61_i_aluno
                 AND (ed99_i_serie = $seriedabase OR ed99_i_serie in ($equivalencias))
                 AND ed99_c_resultadofinal = 'A'
                ";
      $results_s1 = pg_query($sql_s1);
      $linhas_s1 = pg_num_rows($results_s1);
      if($linhas_s1==0){
       $erro .= "&nbsp;&nbsp; - SÉRIE ".$nomeserie." CONSTA COMO REPROVADO<br>";
      }
     }
    }
    if($erro!=""){
     ?>
     <table width="100%" border="1" cellspacing="0" cellpadding="0">
      <tr>
       <td class="cabec1"><?=$ed47_v_nome?> - <?=$codaluno[$x]?></td>
      </tr>
      <tr>
       <td class="aluno" bgcolor="#f3f3f3"><?=$erro?></td>
      </tr>
     </table>
     <?
    }else{
     $alunoconcluido .= $sep.$ed47_v_nome." - ".$codaluno[$x];
     $codalunoconcluido .= $sep.$codaluno[$x];
     $sep = "#";
    }
   }
   $erro = "";
  }
  ?>
  <br><br>
  <table width="500" align="center" border="1" cellspacing="0" cellpadding="0">
   <tr>
    <td class="cabec3" align="center">O sistema irá concluir o curso <?=$nomecurso?> para os alunos:</td>
   </tr>
   <?
   if($alunoconcluido!=""){
    $alunoarray = explode("#",$alunoconcluido);
    for($y=0;$y<count($alunoarray);$y++){
     echo "<tr><td class='cabec1'>".$alunoarray[$y]."</td></tr>";
    }
    echo "<tr>
           <td align='center'>
            <form name='form1' method='POST'>
            <input type='submit' name='concluir' value='Concluir'>
            <input type='hidden' name='curso' value='$curso'>
            <input type='hidden' name='concluidos' value='$codalunoconcluido'>
            </form>
           </td>
          </tr>";
   }else{
    echo "<tr><td class='aluno' bgcolor='#f3f3f3'>&nbsp;&nbsp; - NENHUM ALUNO SELECIONADO ESTÁ APTO PARA CONCLUIR ESTE CURSO.</td></tr>";
   }
   ?>
   </table>
   <br><br>
  </td>
 </tr>
</table>
</body>
</html>