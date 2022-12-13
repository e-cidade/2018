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
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$db_opcao = 1;
$db_botao = true;
$ed129_i_escola = db_getsession("DB_coddepto");
$ed18_c_nome = db_getsession("DB_nomedepto");
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
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
 <tr>
  <td width="360" height="18">&nbsp;</td>
  <td width="263">&nbsp;</td>
  <td width="25">&nbsp;</td>
  <td width="140">&nbsp;</td>
 </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td align="left" valign="top" bgcolor="#CCCCCC">
   <br>
   <fieldset style="width:95%"><legend><b>Exportar dados da escola para a secretaria</b></legend>
    <?
    if(isset($destino)){
     echo "<br><br>";
     ?>
     <b>Criado arquivo de exportação: </b>
     <a href="<?=$destino?>"><?=$destino?></a><br><br>
     <input type="button" value="Nova Exportação" name="nova" onclick="location.href='edu4_exportar_es.php'">
     <br><br>
     Para salvar arquivo, clique com o botão direito do mouse sobre
     o nome do arquivo. Após escolha <b>Salvar/Guardar Destino Como</b>.
     <?
    }else{
     ?>
     <form name="form1" method="post" action="">
     <table border="0">
      <tr>
       <td nowrap title="<?=@$Ted17_i_turno?>">
        <?db_ancora("<b>Escola:</b>","js_pesquisaed129_i_escola(true);",3);?>
       </td>
       <td>
        <?db_input('ed129_i_escola',15,@$Ied129_i_escola,true,'text',3," onchange='js_pesquisaed129_i_escola(false);'")?>
        <?db_input('ed18_c_nome',50,@$Ied18_c_nome,true,'text',3,'')?>
        <input type="button" value="Processar" name="processar" onclick="js_processar();">
       </td>
      </tr>
     </table>
     <br>
     </form>
     <table id="aviso" style="visibility:hidden;">
      <tr align="center">
       <td bgcolor="#DBDBDB" style="border:2px solid #000000;text-decoration:blink;">
        <table cellpadding="5" cellspacing="2">
         <tr align="center">
          <td bgcolor="#f3f3f3" style="border:2px solid #888888;text-decoration:blink;">
           <b><div id="id_escola"></div></b>
           <b>Iniciando exportação dos dados...Aguarde</b>
          </td>
         </tr>
        </table>
       </td>
      </tr>
     </table>
     <br><br>
     <?
    }
    if(isset($escola) && $escola!=""){
     set_time_limit(0);
     $base = db_base_ativa();
     $depto = $escola;
     $tempo = time();
     $destino_tar = "tmp/".$depto."_".$base."_".$tempo."_ES.tar";
     $destino = "tmp/".$depto."_".$base."_".$tempo."_ES.sql";
     //$destino = "tmp/".$depto."_".$base."_01_ES.sql";
     //seleciona a sequencia inicial e final da escola
     $sql = "select *
             from escola_sequencias
             where ed129_i_escola = $depto
             ";
     $result = pg_query($sql);
     $iniciosequencia = pg_result($result,0,'ed129_i_inicio');
     $finalsequencia = pg_result($result,0,'ed129_i_final');
     $ultatualizse = pg_result($result,0,'ed129_i_ultatualizse');
     //deletando registros da escola
     system("echo \"--\"> $destino");
     system("echo \"--DELETANDO REGISTROS DA ESCOLA\">> $destino");
     system("echo \"--\">> $destino");
     $sql1 = "select nomearq,nomecam
              from db_syscampo
               inner join db_sysprikey on db_sysprikey.codcam = db_syscampo.codcam
               inner join db_sysarqcamp on db_sysarqcamp.codcam = db_syscampo.codcam
               inner join db_sysarqmod  on db_sysarqmod.codarq = db_sysarqcamp.codarq
               inner join db_sysarquivo  on db_sysarquivo.codarq = db_sysarqmod.codarq
               left join edutabelasdump on trim(lower(edutabelasdump.ed130_c_tabela)) = trim(lower(db_sysarquivo.nomearq))
              where db_sysarqmod.codmod = 1008004
              and ed130_c_tipo = 'ES'
              order by ed130_i_sequencia desc
             ";
     $result1 = pg_query($sql1);
     $linhas1 = pg_num_rows($result1);
     for($t=0;$t<$linhas1;$t++){
      $dados1 = pg_fetch_array($result1);
      $tabela = trim(strtolower($dados1["nomearq"]));
      $prikey = trim(strtolower($dados1["nomecam"]));
      if($tabela!="rechumano"){
       $delete = "DELETE FROM $tabela WHERE $prikey >= $iniciosequencia AND $prikey <= $finalsequencia;";
       system("echo \"$delete\">> $destino");
      }
     }
     echo "...Deletando registros da escola <br>";
     ///////////////////////////////////////////////////////////////////////////////////////////
     //insert dos dados da escola
     system("echo \"--\">> $destino");
     system("echo \"--INSERINDO REGISTROS DA ESCOLA\">> $destino");
     system("echo \"--\">> $destino");
     $sql_ins = "select nomearq,nomecam
                 from db_syscampo
                  inner join db_sysprikey on db_sysprikey.codcam = db_syscampo.codcam
                  inner join db_sysarqcamp on db_sysarqcamp.codcam = db_syscampo.codcam
                  inner join db_sysarqmod  on db_sysarqmod.codarq = db_sysarqcamp.codarq
                  inner join db_sysarquivo  on db_sysarquivo.codarq = db_sysarqmod.codarq
                  left join edutabelasdump on trim(lower(edutabelasdump.ed130_c_tabela)) = trim(lower(db_sysarquivo.nomearq))
                 where db_sysarqmod.codmod = 1008004
                 and ed130_c_tipo = 'ES'
                 order by ed130_i_sequencia asc
                ";
     $result_ins = pg_query($sql_ins);
     $linhas_ins = pg_num_rows($result_ins);
     for($t=0;$t<$linhas_ins;$t++){
      $dados1 = pg_fetch_array($result_ins);
      $tabela = trim(strtolower($dados1["nomearq"]));
      $prikey = trim(strtolower($dados1["nomecam"]));
      if($tabela=="matricula"){
       ////////////////////////////////////////////////////////////////////////////////
       //Atualizando registros das tabelas alunocurso, alunopossib, aluno, historico
       $sql = "SELECT * FROM alunocurso
               WHERE ed56_i_escola = $depto
              ";
       $result = pg_query($sql);
       $linhas = pg_num_rows($result);
       $ncampos = pg_num_fields($result);
       if($linhas>0){
        system("echo \"--\">> $destino");
        system("echo \"--ATUALIZANDO TABELAS alunocurso, alunopossib, aluno e historico/derivados\">> $destino");
        system("echo \"--\">> $destino");
        for($r=0;$r<$linhas;$r++){
         $insert_docaluno = "";
         $cod_alunocurso = pg_result($result,$r,'ed56_i_codigo');
         $cod_aluno = pg_result($result,$r,'ed56_i_aluno');
         $cod_base = pg_result($result,$r,'ed56_i_base');
         system("echo \"--\">> $destino");
         system("echo \"--ATUALIZANDO Aluno ($cod_aluno)\">> $destino");
         system("echo \"--\">> $destino");
         ///tabela alunopossib
         $sql1 = "SELECT * FROM alunopossib
                  WHERE ed79_i_alunocurso = $cod_alunocurso
                 ";
         $result1 = pg_query($sql1);
         $linhas1 = pg_num_rows($result1);
         $ncampos1 = pg_num_fields($result1);
         if($linhas1>0){
          $insert1 = "";
          $update1 = "";
          $delete1 = "";
          $cod_alunopossib = pg_result($result1,0,'ed79_i_codigo');
          if($cod_alunopossib>=$iniciosequencia && $cod_alunopossib<=$finalsequencia){
           $delete1 = "DELETE FROM alunopossib WHERE ed79_i_codigo = $cod_alunopossib;";
           $insert1 = "INSERT INTO alunopossib VALUES(";
          }else{
           $update1 .= "UPDATE alunopossib SET ";
          }
          $sep1 = "";
          for($w=0;$w<$ncampos1;$w++){
           $nomecampo = trim(pg_field_name($result1,$w));
           $tipocampo = trim(pg_field_type($result1,$w));
           $valorcampo = trim(pg_result($result1,0,$w));
           if($tipocampo=="varchar" || $tipocampo=="bpchar" || $tipocampo=="text" || $tipocampo=="date"){
            if($valorcampo!=""){
             $aspas = "'";
            }else{
             $aspas = "";
             $valorcampo = "null";
            }
           }else{
            if($valorcampo==""){
             $valorcampo = "null";
            }
            $aspas = "";
           }
           if($cod_alunopossib>=$iniciosequencia && $cod_alunopossib<=$finalsequencia){
            $insert1 .= $sep1.$aspas.$valorcampo.$aspas;
           }else{
            $update1 .= $sep1.$nomecampo." = ".$aspas.$valorcampo.$aspas;
           }
           $sep1 = ",";
          }
          if($cod_alunopossib>=$iniciosequencia && $cod_alunopossib<=$finalsequencia){
           $insert1 .= ");";
          }else{
           $update1 .= " WHERE ed79_i_codigo = $cod_alunopossib;";
          }
         }
         ///tabela docaluno
         $sql3 = "SELECT * FROM docaluno
                  WHERE ed49_i_aluno = $cod_aluno
                  AND ed49_i_escola = $depto
                 ";
         $result3 = pg_query($sql3);
         $linhas3 = pg_num_rows($result3);
         $ncampos3 = pg_num_fields($result3);
         if($linhas3>0){
          for($k=0;$k<$linhas3;$k++){
           $insert3 = "";
           $delete3 = "";
           $cod_docaluno = pg_result($result3,$k,'ed49_i_codigo');
           $delete3 = "DELETE FROM docaluno WHERE ed49_i_codigo = $cod_docaluno;";
           system("echo \"$delete3\" >> ".$destino);
           $insert_docaluno .= "INSERT INTO docaluno VALUES(";
           $sep3 = "";
           for($w=0;$w<$ncampos3;$w++){
            $nomecampo = trim(pg_field_name($result3,$w));
            $tipocampo = trim(pg_field_type($result3,$w));
            $valorcampo = trim(pg_result($result3,$k,$w));
            if($tipocampo=="varchar" || $tipocampo=="bpchar" || $tipocampo=="text" || $tipocampo=="date"){
             if($valorcampo!=""){
              $aspas = "'";
             }else{
              $aspas = "";
              $valorcampo = "null";
             }
            }else{
             if($valorcampo==""){
              $valorcampo = "null";
             }
             $aspas = "";
            }
            $insert_docaluno .= $sep3.$aspas.$valorcampo.$aspas;
            $sep3 = ",";
           }
           $insert_docaluno .= ");##";
          }
         }
         //tabela alunocurso
         $insert = "";
         $update = "";
         $delete = "";
         if($cod_alunocurso>=$iniciosequencia && $cod_alunocurso<=$finalsequencia){
          $delete = "DELETE FROM alunocurso WHERE ed56_i_codigo = $cod_alunocurso;";
          $insert = "INSERT INTO alunocurso VALUES(";
         }else{
          $update .= "UPDATE alunocurso SET ";
         }
         $sep = "";
         for($w=0;$w<$ncampos;$w++){
          $nomecampo = trim(pg_field_name($result,$w));
          $tipocampo = trim(pg_field_type($result,$w));
          $valorcampo = trim(pg_result($result,$r,$w));
          if($tipocampo=="varchar" || $tipocampo=="bpchar" || $tipocampo=="text" || $tipocampo=="date"){
           if($valorcampo!=""){
            $aspas = "'";
           }else{
            $aspas = "";
            $valorcampo = "null";
           }
          }else{
           if($valorcampo==""){
            $valorcampo = "null";
           }
           $aspas = "";
          }
          if($cod_alunocurso>=$iniciosequencia && $cod_alunocurso<=$finalsequencia){
           $insert .= $sep.$aspas.$valorcampo.$aspas;
          }else{
           $update .= $sep.$nomecampo." = ".$aspas.$valorcampo.$aspas;
          }
          $sep = ",";
         }
         if($cod_alunocurso>=$iniciosequencia && $cod_alunocurso<=$finalsequencia){
          $insert .= ");";
         }else{
          $update .= " WHERE ed56_i_codigo = $cod_alunocurso;";
         }
         ///tabela aluno
         $sql2 = "SELECT * FROM aluno
                  WHERE ed47_i_codigo = $cod_aluno
                  ";
         $result2 = pg_query($sql2);
         $linhas2 = pg_num_rows($result2);
         $ncampos2 = pg_num_fields($result2);
         if($linhas2>0){
          $insert2 = "";
          $update2 = "";
          $delete2 = "";
          $delete2 = "DELETE FROM aluno WHERE ed47_i_codigo = $cod_aluno;";
          $insert2 = "INSERT INTO aluno VALUES(";
          $update2 .= "UPDATE aluno SET ";
          $sep2 = "";
          for($w=0;$w<$ncampos2;$w++){
           $nomecampo = trim(pg_field_name($result2,$w));
           $tipocampo = trim(pg_field_type($result2,$w));
           $valorcampo = trim(pg_result($result2,0,$w));
           if($tipocampo=="varchar" || $tipocampo=="bpchar" || $tipocampo=="text" || $tipocampo=="date"){
            if($valorcampo!=""){
             $aspas = "'";
            }else{
             $aspas = "";
             $valorcampo = "null";
            }
           }else{
            if($valorcampo==""){
             $valorcampo = "null";
            }
            $aspas = "";
           }
           $insert2 .= $sep2.$aspas.$valorcampo.$aspas;
           $update2 .= $sep2.$nomecampo." = ".$aspas.$valorcampo.$aspas;
           $sep2 = ",";
          }
          $insert2 .= ");";
          $update2 .= " WHERE ed47_i_codigo = $cod_aluno;";
         }
         if($cod_aluno>=$iniciosequencia && $cod_aluno<=$finalsequencia){
          system("echo \"$delete1\" >> ".$destino);
          system("echo \"$delete\" >> ".$destino);
         }else{
          if($cod_alunocurso>=$iniciosequencia && $cod_alunocurso<=$finalsequencia){
           system("echo \"$delete1\" >> ".$destino);
           system("echo \"$delete\" >> ".$destino);
          }else{
           system("echo \"$update1\" >> ".$destino);
           system("echo \"$update\" >> ".$destino);
          }
         }
         ///tabela historico
         $sql3 = "SELECT ed31_i_curso FROM alunocurso
                   inner join base on ed31_i_codigo = ed56_i_base
                  WHERE ed56_i_codigo = $cod_alunocurso
                 ";
         $result3 = pg_query($sql3);
         $cod_curso = pg_result($result3,0,'ed31_i_curso');
         $sql4 = "SELECT * FROM historico WHERE ed61_i_aluno = $cod_aluno AND ed61_i_curso = $cod_curso";
         $result4 = pg_query($sql4);
         $linhas4 = pg_num_rows($result4);
         $ncampos4 = pg_num_fields($result4);
         if($linhas4>0){
          $insert_geral = "";
          //tabelas histmpsdisc e histmpsdiscfora
          $sql5 = "SELECT 'H' as nametabela,histmpsdisc.*
                   FROM histmpsdisc
                    inner join historicomps on ed62_i_codigo = ed65_i_historicomps
                    inner join historico on ed61_i_codigo = ed62_i_historico
                   WHERE ed61_i_aluno = $cod_aluno
                   AND ed61_i_curso = $cod_curso
                   UNION
                   SELECT 'HF' as nametabela,histmpsdiscfora.*
                   FROM histmpsdiscfora
                    inner join historicompsfora on ed99_i_codigo = ed100_i_historicompsfora
                    inner join historico on ed61_i_codigo = ed99_i_historico
                   WHERE ed61_i_aluno = $cod_aluno
                   AND ed61_i_curso = $cod_curso
                  ";
          $result5 = pg_query($sql5);
          $linhas5 = pg_num_rows($result5);
          $ncampos5 = pg_num_fields($result5);
          if($linhas5>0){
           for($k=0;$k<$linhas5;$k++){
            $cod_histmpsdisc = pg_result($result5,$k,'ed65_i_codigo');
            $tipotabela = pg_result($result5,$k,'nametabela');
            $nametabela = trim($tipotabela)=="H"?"histmpsdisc":"histmpsdiscfora";
            $campochave = trim($tipotabela)=="H"?"ed65_i_codigo":"ed100_i_codigo";
            if($cod_histmpsdisc>=$iniciosequencia && $cod_histmpsdisc<=$finalsequencia){
             $delete5 = "DELETE FROM $nametabela WHERE $campochave = $cod_histmpsdisc;";
             system("echo \"$delete5\" >> ".$destino);
             $insert_geral .= "INSERT INTO $nametabela VALUES(";
            }else{
             $update5 = "UPDATE $nametabela SET ";
            }
            $sep5 = "";
            for($w=1;$w<$ncampos5;$w++){
             $nomecampo = trim(pg_field_name($result5,$w));
             $tipocampo = trim(pg_field_type($result5,$w));
             $valorcampo = trim(pg_result($result5,$k,$w));
             if($tipotabela=="HF"){
              $nomecampo = str_replace("ed65","ed100",$nomecampo);
             }
             if($nomecampo=="ed100_i_historicomps"){
              $nomecampo = "ed100_i_historicompsfora";
             }
             if($tipocampo=="varchar" || $tipocampo=="bpchar" || $tipocampo=="text" || $tipocampo=="date"){
              if($valorcampo!=""){
               $aspas = "'";
              }else{
               $aspas = "";
               $valorcampo = "null";
              }
             }else{
              if($valorcampo==""){
               $valorcampo = "null";
              }
              $aspas = "";
             }
             if($cod_histmpsdisc>=$iniciosequencia && $cod_histmpsdisc<=$finalsequencia){
              $insert_geral .= $sep5.$aspas.$valorcampo.$aspas;
             }else{
              $update5 .= " ".$sep5.$nomecampo." = ".$aspas.$valorcampo.$aspas;
             }
             $sep5 = ",";
            }
            if($cod_histmpsdisc>=$iniciosequencia && $cod_histmpsdisc<=$finalsequencia){
             $insert_geral .= ");##";
            }else{
             $update5 .= " WHERE $campochave = $cod_histmpsdisc;";
             system("echo \"$update5\" >> ".$destino);
            }
           }
          }
          //tabela historicomps
          $sql5 = "SELECT historicomps.*
                   FROM historicomps
                    inner join historico on ed61_i_codigo = ed62_i_historico
                   WHERE ed61_i_aluno = $cod_aluno
                   AND ed61_i_curso = $cod_curso
                  ";
          $result5 = pg_query($sql5);
          $linhas5 = pg_num_rows($result5);
          $ncampos5 = pg_num_fields($result5);
          if($linhas5>0){
           for($k=0;$k<$linhas5;$k++){
            $cod_historicomps = pg_result($result5,$k,'ed62_i_codigo');
            if($cod_historicomps>=$iniciosequencia && $cod_historicomps<=$finalsequencia){
             $delete5 = "DELETE FROM historicomps WHERE ed62_i_codigo = $cod_historicomps;";
             system("echo \"$delete5\" >> ".$destino);
             $insert_geral .= "INSERT INTO historicomps VALUES(";
            }else{
             $update5 = "UPDATE historicomps SET ";
            }
            $sep5 = "";
            for($w=0;$w<$ncampos5;$w++){
             $nomecampo = trim(pg_field_name($result5,$w));
             $tipocampo = trim(pg_field_type($result5,$w));
             $valorcampo = trim(pg_result($result5,$k,$w));
             if($tipocampo=="varchar" || $tipocampo=="bpchar" || $tipocampo=="text" || $tipocampo=="date"){
              if($valorcampo!=""){
               $aspas = "'";
              }else{
               $aspas = "";
               $valorcampo = "null";
              }
             }else{
              if($valorcampo==""){
               $valorcampo = "null";
              }
              $aspas = "";
             }
             if($cod_historicomps>=$iniciosequencia && $cod_historicomps<=$finalsequencia){
              $insert_geral .= $sep5.$aspas.$valorcampo.$aspas;
             }else{
              $update5 .= " ".$sep5.$nomecampo." = ".$aspas.$valorcampo.$aspas;
             }
             $sep5 = ",";
            }
            if($cod_historicomps>=$iniciosequencia && $cod_historicomps<=$finalsequencia){
             $insert_geral .= ");##";
            }else{
             $update5 .= " WHERE ed62_i_codigo = $cod_historicomps;";
             system("echo \"$update5\" >> ".$destino);
            }
           }
          }
          //tabela historicompsfora
          $sql5 = "SELECT historicompsfora.*
                   FROM historicompsfora
                    inner join historico on ed61_i_codigo = ed99_i_historico
                   WHERE ed61_i_aluno = $cod_aluno
                   AND ed61_i_curso = $cod_curso
                  ";
          $result5 = pg_query($sql5);
          $linhas5 = pg_num_rows($result5);
          $ncampos5 = pg_num_fields($result5);
          if($linhas5>0){
           for($k=0;$k<$linhas5;$k++){
            $cod_historicompsfora = pg_result($result5,$k,'ed99_i_codigo');
            if($cod_historicompsfora>=$iniciosequencia && $cod_historicompsfora<=$finalsequencia){
             $delete5 = "DELETE FROM historicompsfora WHERE ed99_i_codigo = $cod_historicompsfora;";
             system("echo \"$delete5\" >> ".$destino);
             $insert_geral .= "INSERT INTO historicompsfora VALUES(";
            }else{
             $update5 = "UPDATE historicompsfora SET ";
            }
            $sep5 = "";
            for($w=0;$w<$ncampos5;$w++){
             $nomecampo = trim(pg_field_name($result5,$w));
             $tipocampo = trim(pg_field_type($result5,$w));
             $valorcampo = trim(pg_result($result5,$k,$w));
             if($tipocampo=="varchar" || $tipocampo=="bpchar" || $tipocampo=="text" || $tipocampo=="date"){
              if($valorcampo!=""){
               $aspas = "'";
              }else{
               $aspas = "";
               $valorcampo = "null";
              }
             }else{
              if($valorcampo==""){
               $valorcampo = "null";
              }
              $aspas = "";
             }
             if($cod_historicompsfora>=$iniciosequencia && $cod_historicompsfora<=$finalsequencia){
              $insert_geral .= $sep5.$aspas.$valorcampo.$aspas;
             }else{
              $update5 .= " ".$sep5.$nomecampo." = ".$aspas.$valorcampo.$aspas;
             }
             $sep5 = ",";
            }
            if($cod_historicompsfora>=$iniciosequencia && $cod_historicompsfora<=$finalsequencia){
             $insert_geral .= ");##";
            }else{
             $update5 .= " WHERE ed99_i_codigo = $cod_historicompsfora;";
             system("echo \"$update5\" >> ".$destino);
            }
           }
          }
          //tabela historico
          $sql5 = "SELECT historico.*
                   FROM historico
                   WHERE ed61_i_aluno = $cod_aluno
                   AND ed61_i_curso = $cod_curso
                  ";
          $result5 = pg_query($sql5);
          $linhas5 = pg_num_rows($result5);
          $ncampos5 = pg_num_fields($result5);
          if($linhas5>0){
           for($k=0;$k<$linhas5;$k++){
            $cod_historico = pg_result($result5,$k,'ed61_i_codigo');
            $sql_ver = "SELECT ed61_i_codigo as nada
                        FROM historico
                         inner join historicomps on ed62_i_historico = ed61_i_codigo
                         inner join historicompsfora on ed99_i_historico = ed61_i_codigo
                        WHERE ed61_i_codigo = $cod_historico
                        AND ((ed62_i_codigo<$iniciosequencia OR ed62_i_codigo>$finalsequencia)
                        OR (ed99_i_codigo<$iniciosequencia OR ed99_i_codigo>$finalsequencia))
                       ";
            $result_ver = pg_query($sql_ver);
            $linhas_ver = pg_num_rows($result_ver);
            if($cod_historico>=$iniciosequencia && $cod_historico<=$finalsequencia && $linhas_ver==0){
             $delete5 = "DELETE FROM historico WHERE ed61_i_codigo = $cod_historico;";
             system("echo \"$delete5\" >> ".$destino);
             $insert_geral .= "INSERT INTO historico VALUES(";
            }else{
             $update5 = "UPDATE historico SET ";
            }
            $sep5 = "";
            for($w=0;$w<$ncampos5;$w++){
             $nomecampo = trim(pg_field_name($result5,$w));
             $tipocampo = trim(pg_field_type($result5,$w));
             $valorcampo = trim(pg_result($result5,$k,$w));
             if($tipocampo=="varchar" || $tipocampo=="bpchar" || $tipocampo=="text" || $tipocampo=="date"){
              if($valorcampo!=""){
               $aspas = "'";
              }else{
               $aspas = "";
               $valorcampo = "null";
              }
             }else{
              if($valorcampo==""){
               $valorcampo = "null";
              }
              $aspas = "";
             }
             if($cod_historico>=$iniciosequencia && $cod_historico<=$finalsequencia && $linhas_ver==0){
              $insert_geral .= $sep5.$aspas.$valorcampo.$aspas;
             }else{
              $update5 .= " ".$sep5.$nomecampo." = ".$aspas.$valorcampo.$aspas;
             }
             $sep5 = ",";
            }
            if($cod_historico>=$iniciosequencia && $cod_historico<=$finalsequencia && $linhas_ver==0){
             $insert_geral .= ");##";
            }else{
             $update5 .= " WHERE ed61_i_codigo = $cod_historico;";
             system("echo \"$update5\" >> ".$destino);
            }
           }
          }
          if($cod_aluno>=$iniciosequencia && $cod_aluno<=$finalsequencia){
           if($linhas_ver==0){
            system("echo \"$delete2\" >> ".$destino);
            system("echo \"$insert2\" >> ".$destino);
           }else{
            system("echo \"$update2\" >> ".$destino);
           }
           system("echo \"$insert\" >> ".$destino);
           system("echo \"$insert1\" >> ".$destino);
          }else{
           if($cod_alunocurso>=$iniciosequencia && $cod_alunocurso<=$finalsequencia){
            system("echo \"$insert\" >> ".$destino);
            system("echo \"$insert1\" >> ".$destino);
           }
           system("echo \"$update2\" >> ".$destino);
          }
          $insert_array = explode("##",$insert_geral);
          $quant = count($insert_array)-1;
          for($h=$quant;$h>=0;$h--){
           if(trim($insert_array[$h]!="")){
            system("echo \"$insert_array[$h]\" >> ".$destino);
           }
          }
         }else{
          if($cod_aluno>=$iniciosequencia && $cod_aluno<=$finalsequencia){
           system("echo \"$delete2\" >> ".$destino);
           system("echo \"$insert2\" >> ".$destino);
           system("echo \"$insert\" >> ".$destino);
           system("echo \"$insert1\" >> ".$destino);
          }else{
           if($cod_alunocurso>=$iniciosequencia && $cod_alunocurso<=$finalsequencia){
            system("echo \"$insert\" >> ".$destino);
            system("echo \"$insert1\" >> ".$destino);
           }
           system("echo \"$update2\" >> ".$destino);
          }
         }
         $insert_array1 = explode("##",$insert_docaluno);
         $quant = count($insert_array1)-1;
         for($h=$quant;$h>=0;$h--){
          if(trim($insert_array1[$h]!="")){
           system("echo \"$insert_array1[$h]\" >> ".$destino);
          }
         }
        }
       }
       ////////////////////////////////////////////////////////////////////////////////
      }
      if($tabela=="rechumano"){
       $sql2 = "select ed20_i_codigo from rechumano
                 inner join rechumanoescola on rechumanoescola.ed75_i_rechumano = rechumano.ed20_i_codigo
                where ed75_i_escola = $depto
               ";
      }else{
       $sql2 = "select * from $tabela
                where $prikey >= $iniciosequencia
                and $prikey <= $finalsequencia
               ";
      }
      $result2 = pg_query($sql2);
      $linhas2 = pg_num_rows($result2);
      if($linhas2>0){
       system("echo \"--\">> $destino");
       system("echo \"--TABELA $tabela\">> $destino");
       system("echo \"--\">> $destino");
       for($r=0;$r<$linhas2;$r++){
        $ncampos = pg_num_fields($result2);
        $insert = "INSERT INTO $tabela VALUES(";
        $sep = "";
        for($y=0;$y<$ncampos;$y++){
         $tipocampo = trim(pg_field_type($result2,$y));
         $valorcampo = trim(pg_result($result2,$r,$y));
         if($tipocampo=="varchar" || $tipocampo=="bpchar" || $tipocampo=="text" || $tipocampo=="date"){
          if($valorcampo!=""){
           $aspas = "'";
          }else{
           $aspas = "";
           $valorcampo = "null";
          }
         }else{
          if($valorcampo==""){
           $valorcampo = "null";
          }
          $aspas = "";
         }
         $insert .= $sep.$aspas.$valorcampo.$aspas;
         $sep = ",";
        }
        $insert .= ");";
        system("echo \"$insert\">> $destino");
       }
      }
     }
     echo "...Inserindo registros da escola <br>";
     system("echo \"--\">> $destino");
     system("echo \"--ATUALIZANDO TABELA escola_sequencias\">> $destino");
     system("echo \"--\">> $destino");
     $up_timees = "UPDATE escola_sequencias SET ed129_i_ultatualizes = ".$tempo." WHERE ed129_i_escola = $depto;";
     $up_timese = "UPDATE escola_sequencias SET ed129_i_ultatualizse = $ultatualizse WHERE ed129_i_escola = $depto;";
     system("echo \"$up_timees\" >> ".$destino);
     system("echo \"$up_timese\" >> ".$destino);
     $sql_tr = "SELECT * FROM transflocal WHERE ed131_i_escola = $depto AND ed131_c_situacao = 'A'";
     $result_tr = pg_query($sql_tr);
     $linhas_tr = pg_num_rows($result_tr);
     if($linhas_tr>0){
      system("echo \"--\">> $destino");
      system("echo \"--ATUALIZANDO TRANSFERÊNCIAS\">> $destino");
      system("echo \"--\">> $destino");
      for($q=0;$q<$linhas_tr;$q++){
       $cod_transflocal = pg_result($result_tr,$q,'ed131_i_codigo');
       $cod_transfrede = pg_result($result_tr,$q,'ed131_i_transfrede');
       $cod_alunocurso = pg_result($result_tr,$q,'ed131_i_alunocurso');
       $update4 = "UPDATE transfescolarede SET ed103_c_situacao = 'F' WHERE ed103_i_codigo = $cod_transfrede;";
       system("echo \"$update4\" >> ".$destino);
       $update5 = "UPDATE transflocal SET ed131_c_situacao = 'F' WHERE ed131_i_codigo = $cod_transflocal;";
       system("echo \"$update5\" >> ".$destino);
      }
     }
     $sql_tl = "SELECT ed102_i_escola as codescoladestino, ed56_i_codigo as cod_alcurso
                FROM transfescolarede
                 inner join atestvaga on ed102_i_codigo = ed103_i_atestvaga
                 inner join aluno on ed47_i_codigo = ed102_i_aluno
                 inner join alunocurso on ed56_i_aluno = ed47_i_codigo
                WHERE ed103_i_escolaorigem = $depto
                AND ed103_c_situacao = 'A'
                ";
     $result_tl = pg_query($sql_tl);
     $linhas_tl = pg_num_rows($result_tl);
     if($linhas_tl>0){
      for($q=0;$q<$linhas_tl;$q++){
       $codescoladestino = pg_result($result_tl,$q,'codescoladestino');
       $cod_alcurso = pg_result($result_tl,$q,'cod_alcurso');
       $update6 = "UPDATE alunocurso SET ed56_i_escola = $codescoladestino, ed56_c_situacao = 'TRANSFERIDO REDE' WHERE ed56_i_codigo = $cod_alcurso;";
       system("echo \"$update6\" >> ".$destino);
       pg_exec("UPDATE alunocurso SET ed56_i_escola = $codescoladestino, ed56_c_situacao = 'TRANSFERIDO REDE' WHERE ed56_i_codigo = $cod_alcurso");
      }
     }
     ///*
     echo "...Compactando arquivo ";
     system("tar -cvf $destino_tar $destino");
     system("bzip2 $destino_tar");
     system("rm $destino");
     //seleciona todos usuários locais desta escola
     $sql3 = "select distinct db_usuarios.id_usuario as cod_usuarios
              from db_usuarios
               inner join db_depusu on db_depusu.id_usuario = db_usuarios.id_usuario
               inner join db_usumod on db_usumod.id_usuario = db_usuarios.id_usuario
               inner join db_permherda on db_permherda.id_usuario = db_usuarios.id_usuario
               inner join db_usuarios as b on b.id_usuario = db_permherda.id_perfil
              where db_depusu.coddepto = $depto
              and db_usumod.id_item = 1100747
              and b.login = 'eduescolalocal'
             ";
     $result3 = pg_query($sql3);
     $linhas3 = pg_num_rows($result3);
     if($linhas3>0){
      //pega codigo do perfil eduescolalocal
      $result4 = pg_exec("select id_usuario from db_usuarios where trim(login) = 'eduescolalocal'");
      $perfil_local = pg_result($result4,0,'id_usuario');
      //pega codigo do perfil edubloqueado
      $result5 = pg_exec("select id_usuario from db_usuarios where trim(login) = 'edubloqueado'");
      $perfil_bloq = pg_result($result5,0,'id_usuario');
      for($d=0;$d<$linhas3;$d++){
       $cod_usu = pg_result($result3,$d,'cod_usuarios');
       $update_perfil = pg_exec("UPDATE db_permherda SET id_perfil = $perfil_bloq
                                 WHERE id_usuario = $cod_usu
                                 AND id_perfil = $perfil_local
                                ");
      }
     }
     $result6 = pg_exec("update escola_sequencias set ed129_c_ulttransacao = 'ES' where ed129_i_escola = $depto");
     db_redireciona("edu4_exportar_es.php?destino=".$destino_tar.".bz2");
     //*/
    }
    ?>
   </fieldset>
  </td>
 </tr>
</table>
<?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
</body>
</html>
<script>
js_tabulacaoforms("form1","ed129_i_escola",true,1,"ed129_i_escola",true);
function js_processar(){
 if(document.form1.ed129_i_escola.value==""){
  alert("Informe a escola para exportar os dados!");
  document.form1.ed129_i_escola.focus();
 }else{
  if(confirm("Após a exportação dos dados,\no usuário não terá mais acesso aos cadastros e procedimentos.\nApós o retorno dos dados da Secretaria de Educação\no acesso será novamente permitido! Confirmar exportação?")){
   document.getElementById("aviso").style.visibility = "visible";
   document.getElementById("id_escola").innerHTML = "ESCOLA: "+document.form1.ed129_i_escola.value+"-"+document.form1.ed18_c_nome.value;
   location.href = "edu4_exportar_es.php?escola="+document.form1.ed129_i_escola.value;
  }
 }
}
function js_pesquisaed129_i_escola(mostra){
 if(mostra==true){
  js_OpenJanelaIframe('','db_iframe_escola','func_escola_sequencias.php?funcao_js=parent.js_mostraescola1|ed18_i_codigo|ed18_c_nome','Pesquisa Escolas Locais',true);
 }else{
  if(document.form1.ed129_i_escola.value != ''){
   js_OpenJanelaIframe('','db_iframe_escola','func_escola_sequencias.php?pesquisa_chave='+document.form1.ed129_i_escola.value+'&funcao_js=parent.js_mostraescola','Pesquisa Escolas Locais',false);
  }else{
   document.form1.ed18_c_nome.value = '';
  }
 }
}
function js_mostraescola(chave,erro){
 document.form1.ed18_c_nome.value = chave;
 if(erro==true){
  document.form1.ed129_i_escola.focus();
  document.form1.ed129_i_escola.value = '';
 }
}
function js_mostraescola1(chave1,chave2){
 document.form1.ed129_i_escola.value = chave1;
 document.form1.ed18_c_nome.value = chave2;
 db_iframe_escola.hide();
}
</script>