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

//MODULO: educa��o
include("libs/db_stdlibwebseller.php");
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_sala_classe.php");
include("classes/db_cursoescola_classe.php");
include("classes/db_calendario_classe.php");
include("classes/db_turma_classe.php");
include("classes/db_turnoreferente_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clsala = new cl_sala;
$clturma = new cl_turma;
$clturnoreferente = new cl_turnoreferente;
$clcursoescola = new cl_cursoescola;
$clcalendario = new cl_calendario;
$clsala->rotulo->label("ed16_i_codigo");
$clsala->rotulo->label("ed16_c_descr");
$result = $clcalendario->sql_record($clcalendario->sql_query("","ed52_d_inicio",""," ed52_i_codigo = $calendario"));
db_fieldsmemory($result,0);
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
   <table width="50%" border="0" align="center" cellspacing="0">
    <form name="form2" method="post" action="" >
    <tr>
     <td width="4%" align="right" nowrap title="<?=$Ted16_i_codigo?>">
      <?=$Led16_i_codigo?>
     </td>
     <td width="96%" align="left" nowrap>
      <?db_input("ed16_i_codigo",10,$Ied16_i_codigo,true,"text",4,"","chave_ed16_i_codigo");?>
     </td>
    </tr>
    <tr>
     <td width="4%" align="right" nowrap title="<?=$Ted16_c_descr?>">
      <?=$Led16_c_descr?>
     </td>
     <td width="96%" align="left" nowrap>
      <?db_input("ed16_c_descr",20,$Ied16_c_descr,true,"text",4,"","chave_ed16_c_descr");?>
     </td>
    </tr>
    <tr>
     <td colspan="2" align="center">
      <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
      <input name="limpar" type="reset" id="limpar" value="Limpar" >
      <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_sala.hide();">
     </td>
    </tr>
   </form>
   </table>
  </td>
 </tr>
 <tr>
  <td align="center" valign="top">
   <?
   $escola = db_getsession("DB_coddepto");
   $result = $clcursoescola->sql_record($clcursoescola->sql_query("","ed71_c_turmasala",""," ed71_i_escola = $escola AND ed71_i_curso = $curso"));
   if($clcursoescola->numrows>0){
    db_fieldsmemory($result,0);
    if($ed71_c_turmasala=="N"){
     $result_ref = $clturnoreferente->sql_record($clturnoreferente->sql_query("","ed231_i_referencia",""," ed231_i_turno = $turno"));
     $referencias = "";
     $sep = "";
     for($t=0;$t<$clturnoreferente->numrows;$t++){
      db_fieldsmemory($result_ref,$t);
      $referencias .= $sep.$ed231_i_referencia;
      $sep = ",";
     }
     $where1 = " AND not exists(SELECT * from turma
                                 inner join turno on ed15_i_codigo = ed57_i_turno
                                 inner join calendario on ed52_i_codigo = ed57_i_calendario
                                 inner join turnoreferente on ed231_i_turno = ed15_i_codigo
                                WHERE ed57_i_sala = ed16_i_codigo
                                AND ed57_i_escola = $escola
                                AND ed231_i_referencia in ($referencias)
                                AND ed57_i_codigo not in ($turma)
                                AND '$ed52_d_inicio' between ed52_d_inicio AND ed52_d_fim
                               )";
    }else{
     $where1 = "";
    }
   }
   $where = " ed16_i_escola = $escola AND ed14_c_aula = 'S' $where1";
   if(!isset($pesquisa_chave)){
    if(isset($campos)==false){
     if(file_exists("funcoes/db_func_sala.php")==true){
      include("funcoes/db_func_sala.php");
     }else{
      $campos = "sala.*";
     }
    }
    if(isset($chave_ed16_i_codigo) && (trim($chave_ed16_i_codigo)!="") ){
     $sql = $clsala->sql_query("",$campos,"ed16_c_descr",$where." AND ed16_i_codigo like '$chave_ed16_i_codigo%' ");
    }else if(isset($chave_ed16_c_descr) && (trim($chave_ed16_c_descr)!="") ){
     $sql = $clsala->sql_query("",$campos,"ed16_c_descr",$where." AND ed16_c_descr like '$chave_ed16_c_descr%' ");
    }else{
     $sql = $clsala->sql_query("",$campos,"ed16_c_descr",$where);
    }
    db_lovrot($sql,15,"()","",$funcao_js);
    $res = pg_query($sql);
    if(pg_num_rows($res)==0){
     echo "<b>
            <br>
            Todas salas ocupadas para o turno e calend�rio selecionados.<br><br>
            Para cadastrar outra sala para  esta turma,<br>
            acesse Cadastros / Depend�ncias da Escola<br><br>
            Para permitir mais de uma turma por sala para o curso selecionado,<br>
            acesse Cadastros / Cursos na Escola / Vincular Curso / Aba Vincular Curso
           </b>";
    }
   }else{
    if($pesquisa_chave!=null && $pesquisa_chave!=""){
     $result = $clsala->sql_record($clsala->sql_query("","*","ed16_c_descr",$where." AND ed16_i_codigo = $pesquisa_chave"));
     if($clsala->numrows!=0){
      db_fieldsmemory($result,0);
      echo "<script>".$funcao_js."('$ed16_c_descr',false,'$ed16_i_capacidade');</script>";
     }else{
      echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") n�o Encontrado',true,'');</script>";
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