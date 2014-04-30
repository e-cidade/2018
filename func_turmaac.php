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

include("libs/db_stdlibwebseller.php");
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_turmaac_classe.php");
include("classes/db_calendario_classe.php");
include("classes/db_turno_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clturmaac = new cl_turmaac;
$clcalendario = new cl_calendario;
$clturno = new cl_turno;
$clturmaac->rotulo->label("ed268_i_codigo");
$clturmaac->rotulo->label("ed268_c_descr");
$clturmaac->rotulo->label("ed268_i_tipoatend");
$clrotulo = new rotulocampo;
$clrotulo->label("ed57_i_calendario");
$clrotulo->label("ed57_i_turno");
$escola = db_getsession("DB_coddepto");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
 <tr>
  <td height="63" align="center" valign="top">
   <table width="35%" border="0" align="center" cellspacing="0">
    <form name="form1" method="post" action="" >
    <tr>
     <td width="4%" nowrap title="<?=$Ted268_i_codigo?>">
      <?=$Led268_i_codigo?>
      <?db_input("ed268_i_codigo",10,$Ied268_i_codigo,true,"text",4,"","chave_ed268_i_codigo");?>
      <?=$Led268_c_descr?>
      <?db_input("ed268_c_descr",10,$Ied268_c_descr,true,"text",4,"","chave_ed268_c_descr");?>
      <?=$Led57_i_turno?>
      <?
      $sql_tur = "SELECT ed15_i_codigo,ed15_c_nome,ed15_i_sequencia
                  FROM turno
                   inner join periodoescola on periodoescola.ed17_i_turno = turno.ed15_i_codigo
                  WHERE periodoescola.ed17_i_escola = $escola
                  GROUP BY ed15_i_codigo,ed15_c_nome,ed15_i_sequencia
                  ORDER BY ed15_i_sequencia
                  ";
      $result_tur = pg_query($sql_tur);
      $linhas_tur = pg_num_rows($result_tur);
      if($linhas_tur==0){
       $x = array(''=>'NENHUM REGISTRO');
       db_select('ed268_i_turno',$x,true,1,"");
      }else{
       db_selectrecord("ed268_i_turno",$result_tur,"","","","chave_ed268_i_turno","","  ","",1);
      }
      ?>
     </td>
    </tr>
    <tr>
     <td width="4%" nowrap title="<?=$Ted57_i_calendario?>">
      <?=$Led57_i_calendario?>
      <?
      $result_cal = $clcalendario->sql_record($clcalendario->sql_query_calescola("","ed52_i_codigo,ed52_c_descr","ed52_i_ano desc","  ed52_c_passivo = 'N' AND ed38_i_escola = $escola"));
      if($clcalendario->numrows==0){
       $x = array(''=>'NENHUM REGISTRO');
       db_select('ed268_i_calendario',$x,true,1,"");
      }else{
       db_selectrecord("ed268_i_calendario",$result_cal,"","","","chave_ed268_i_calendario","","  ","",1);
      }
      ?>
      <?=$Led268_i_tipoatend?>
      <?
      $x = array(''=>'','4'=>'ATIVIDADE COMPLEMENTAR','5'=>'ATENDIMENTO EDUCACIONAL ESPECIAL - AEE');
      db_select('chave_ed268_i_tipoatend',$x,true,1,"");
      ?>
     </td>
    </tr>
    <tr>
     <td align="center">
      <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
      <input name="limpar" type="reset" id="limpar" value="Limpar" >
      <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_turmaac.hide();">
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
     if(file_exists("funcoes/db_func_turmaac.php")==true){
      include("funcoes/db_func_turmaac.php");
     }else{
      $campos = "turmaac.*";
     }
    }

    $where = "";
    $esc = false;
    if(isset($chave_ed268_i_codigo) && (trim($chave_ed268_i_codigo)!="") ){
     $where .= " AND ed268_i_codigo = $chave_ed268_i_codigo";
     $esc = true;
    }
    if(isset($chave_ed268_c_descr) && (trim($chave_ed268_c_descr)!="") ){
     $where .= " AND ed268_c_descr like '$chave_ed268_c_descr%'";
     $esc = true;
    }
    if(isset($chave_ed268_i_calendario) && (trim($chave_ed268_i_calendario)!="") ){
     $where .= " AND ed268_i_calendario = $chave_ed268_i_calendario";
     $esc = true;
    }
    if(isset($chave_ed268_i_turno) && (trim($chave_ed268_i_turno)!="") ){
     $where .= " AND ed268_i_turno = $chave_ed268_i_turno";
     $esc = true;
    }
    if(isset($chave_ed268_i_tipoatend) && (trim($chave_ed268_i_tipoatend)!="") ){
     $where .= " AND ed268_i_tipoatend = $chave_ed268_i_tipoatend";
     $esc = true;
    }
    if($esc==true){
     $sql = $clturmaac->sql_query("",$campos,"ed268_c_descr"," ed52_c_passivo = 'N' AND ed268_i_escola = $escola ".$where);
    }
    $repassa = array();
    if(isset($chave_ed268_i_codigo)){
     $repassa = array("chave_ed268_i_codigo"=>$chave_ed268_i_codigo,"chave_ed268_c_descr"=>$chave_ed268_c_descr,"chave_ed268_i_turno"=>$chave_ed268_i_turno,"chave_ed268_i_calendario"=>$chave_ed268_i_calendario,"chave_ed268_i_tipoatend"=>$chave_ed268_i_tipoatend);
    }
    db_lovrot(@$sql,15,"()","",$funcao_js,"","NoMe",$repassa);
   }else{
    if($pesquisa_chave!=null && $pesquisa_chave!=""){
     $result = $clturmaac->sql_record($clturmaac->sql_query("","*",""," ed268_i_escola = $escola AND ed268_i_codigo = $pesquisa_chave"));
     if($clturmaac->numrows!=0){
      db_fieldsmemory($result,0);
      echo "<script>".$funcao_js."('$ed268_c_descr',false);</script>";
     }else{
      echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
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
<script>
js_tabulacaoforms("form2","chave_ed268_c_descr",true,1,"chave_ed268_c_descr",true);
</script>