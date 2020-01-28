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

//MODULO: educação
include("libs/db_stdlibwebseller.php");
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_turma_classe.php");
include("classes/db_calendario_classe.php");
include("classes/db_turno_classe.php");
include("classes/db_cursoedu_classe.php");
include("classes/db_procedimento_classe.php");
include("classes/db_sala_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clturma = new cl_turma;
$clcalendario = new cl_calendario;
$clturno = new cl_turno;
$clcurso = new cl_curso;
$clprocedimento = new cl_procedimento;
$clsala = new cl_sala;
$clrotulo = new rotulocampo;
$clturma->rotulo->label("ed57_i_codigo");
$clturma->rotulo->label("ed57_c_descr");
$clturma->rotulo->label("ed57_i_calendario");
$clturma->rotulo->label("ed57_i_turno");
$clrotulo->label("ed31_i_curso");
$clrotulo->label("ed220_i_procedimento");
$clturma->rotulo->label("ed57_i_sala");
$escola = db_getsession("DB_coddepto");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
 <tr>
  <td height="63" align="center" valign="top">
   <table width="35%" border="0" align="center" cellspacing="0">
    <form name="form1" method="post" action="" >
    <tr>
     <td width="4%" nowrap title="<?=$Ted57_i_codigo?>">
      <?=$Led57_i_codigo?>
      <?db_input("ed57_i_codigo",10,$Ied57_i_codigo,true,"text",4,"","chave_ed57_i_codigo");?>
     </td>
     <td width="4%" nowrap title="<?=$Ted57_c_descr?>">
      <?=$Led57_c_descr?>
      <?db_input("ed57_c_descr",10,$Ied57_c_descr,true,"text",4,"","chave_ed57_c_descr");?>
     </td>
     <td width="4%" nowrap title="<?=$Ted31_i_curso?>">
      <?=$Led31_i_curso?>
      <?
      $result_cur = $clcurso->sql_record($clcurso->sql_query_file("","ed29_i_codigo,ed29_c_descr","ed29_c_descr"));
      db_selectrecord("ed31_i_curso",$result_cur,"","","","chave_ed31_i_curso","","  ","",1);
      ?>
     </td>
     <td width="4%" nowrap title="<?=$Ted57_i_sala?>">
      <!--
      <?=$Led57_i_sala?>
      <?
      $result_sala = $clsala->sql_record($clsala->sql_query_file("","ed16_i_codigo,ed16_c_descr","ed16_c_descr"," ed16_i_escola = $escola"));
      if($clsala->numrows==0){
       $x = array(''=>'NENHUM REGISTRO');
       db_select('ed57_i_sala',$x,true,1,"");
      }else{
       db_selectrecord("ed57_i_sala",$result_sala,"","","","chave_ed57_i_sala","","  ","",1);
      }
      ?>
      -->
     </td>
    </tr>
    <tr>
     <td width="4%" nowrap title="<?=$Ted57_i_turno?>">
      <?=$Led57_i_turno?>
      <?
      $sql_tur = "SELECT ed15_i_codigo,ed15_c_nome
                  FROM turno
                   inner join periodoescola on periodoescola.ed17_i_turno = turno.ed15_i_codigo
                  WHERE periodoescola.ed17_i_escola = $escola
                  GROUP BY ed15_i_codigo,ed15_c_nome";
      $result_tur = pg_query($sql_tur);
      $linhas_tur = pg_num_rows($result_tur);
      if($linhas_tur==0){
       $x = array(''=>'NENHUM REGISTRO');
       db_select('ed57_i_turno',$x,true,1,"");
      }else{
       db_selectrecord("ed57_i_turno",$result_tur,"","","","chave_ed57_i_turno","","  ","",1);
      }
      ?>
     </td>
     <td width="4%" nowrap title="<?=$Ted57_i_calendario?>">
      <?=$Led57_i_calendario?>
      <?
      $result_cal = $clcalendario->sql_record($clcalendario->sql_query_calescola("","ed52_i_codigo,ed52_c_descr","ed52_i_ano desc"," ed38_i_escola = $escola"));
      if($clcalendario->numrows==0){
       $x = array(''=>'NENHUM REGISTRO');
       db_select('ed57_i_calendario',$x,true,1,"");
      }else{
       db_selectrecord("ed57_i_calendario",$result_cal,"","","","chave_ed57_i_calendario","","  ","",1);
      }
      ?>
     </td>
     <td width="4%" nowrap title="<?=$Ted220_i_procedimento?>">
      <?=$Led220_i_procedimento?>
      <?
      $result_proc = $clprocedimento->sql_record($clprocedimento->sql_query_procturma("","ed40_i_codigo,ed40_c_descr","ed40_c_descr"," ed86_i_escola = $escola GROUP BY ed40_i_codigo,ed40_c_descr"));
      if($clprocedimento->numrows==0){
       $x = array(''=>'NENHUM REGISTRO');
       db_select('ed220_i_procedimento',$x,true,1,"");
      }else{
       db_selectrecord("ed220_i_procedimento",$result_proc,"","","","chave_ed220_i_procedimento","","  ","",1);
      }
      ?>
     </td>
    </tr>
    <tr>
     <td colspan="4" align="center">
      <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
      <input name="limpar" type="reset" id="limpar" value="Limpar" >
      <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_turma.hide();">
     </td>
    </tr>
    </form>
   </table>
  </td>
 </tr>
 <tr>
  <td align="center" valign="top">
   <?
   if(!isset($pesquisa_chave)){
    if(isset($campos)==false){
     if(file_exists("funcoes/db_func_turma.php")==true){
      include("funcoes/db_func_turma.php");
     }else{
      $campos = "turma.*";
     }
    }
    $where = "";
    $esc = false;
    if(isset($chave_ed57_i_codigo) && (trim($chave_ed57_i_codigo)!="") ){
     $where .= " AND ed57_i_codigo = $chave_ed57_i_codigo";
     $esc = true;
    }
    if(isset($chave_ed57_c_descr) && (trim($chave_ed57_c_descr)!="") ){
     $where .= " AND ed57_c_descr like '$chave_ed57_c_descr%'";
     $esc = true;
    }
    if(isset($chave_ed57_i_calendario) && (trim($chave_ed57_i_calendario)!="") ){
     $where .= " AND ed57_i_calendario = $chave_ed57_i_calendario";
     $esc = true;
    }
    if(isset($chave_ed57_i_turno) && (trim($chave_ed57_i_turno)!="") ){
     $where .= " AND ed57_i_turno =$chave_ed57_i_turno";
     $esc = true;
    }
    if(isset($chave_ed31_i_curso) && (trim($chave_ed31_i_curso)!="") ){
     $where .= " AND ed31_i_curso = $chave_ed31_i_curso";
     $esc = true;
    }
    if(isset($chave_ed220_i_procedimento) && (trim($chave_ed220_i_procedimento)!="") ){
     $where .= " AND exists(select * from turmaserieregimemat where ed220_i_turma = ed57_i_codigo and ed220_i_procedimento = $chave_ed220_i_procedimento) ";
     $esc = true;
    }
    if(isset($chave_ed57_i_sala) && (trim($chave_ed57_i_sala)!="") ){
     $where .= " AND ed57_i_sala = $chave_ed57_i_sala";
     $esc = true;
    }
    if($esc==true){
     $sql = $clturma->sql_query("",$campos,"ed57_c_descr,ed11_i_ensino,ed11_i_sequencia"," ed57_i_escola = $escola ".$where);
    }
    db_lovrot(@$sql,12,"()","",$funcao_js);
   }else{
    if($pesquisa_chave!=null && $pesquisa_chave!=""){
     $result = $clturma->sql_record($clturma->sql_query("","*","ed57_c_descr,ed11_i_ensino,ed11_i_sequencia"," ed57_i_codigo = $pesquisa_chave AND ed57_i_escola = $escola"));
     if($clturma->numrows!=0){
      db_fieldsmemory($result,0);
      echo "<script>".$funcao_js."('$ed57_c_descr','$ed52_c_descr','$ed29_c_descr','$ed11_c_descr','$ed15_c_nome','$ed57_i_numvagas','$ed57_i_nummatr',false);</script>";
     }else{
      echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado','','','','','','','',true);</script>";
     }
    }else{
     echo "<script>".$funcao_js."('',false);</script>";
    }
   }
   ?>
  </td>
 </tr>
</table>
</body>
</html>