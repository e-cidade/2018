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
include("classes/db_aluno_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$claluno = new cl_aluno;
$claluno->rotulo->label("ed47_i_codigo");
$clrotulo = new rotulocampo;
$clrotulo->label("ed47_v_nome");

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
   <table width="55%" border="0" align="center" cellspacing="0">
    <form name="form2" method="post" action="" >
    <tr>
     <td width="4%" align="right" nowrap title="<?=$Ted47_i_codigo?>">
      <?=$Led47_i_codigo?>
     </td>
     <td width="96%" align="left" nowrap>
      <?db_input("ed47_i_codigo",10,$Ied47_i_codigo,true,"text",4,"","chave_ed47_i_codigo");?>
     </td>
    </tr>
    <tr>
     <td width="4%" align="right" nowrap title="<?=$Ted47_v_nome?>">
      <?=$Led47_v_nome?>
     </td>
     <td width="96%" align="left" nowrap>
      <?db_input("ed47_v_nome",40,$Ied47_v_nome,true,"text",4,"","chave_ed47_v_nome");?>
     </td>
    </tr>
    <tr>
     <td colspan="2" align="center">
      <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
      <input name="limpar" type="reset" id="limpar" value="Limpar" >
      <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_aluno.hide();">
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
   if(!isset($pesquisa_chave)){
    $sql = "SELECT *
            FROM (
                  SELECT distinct on (aluno.ed47_i_codigo) aluno.ed47_i_codigo,
                         aluno.ed47_v_nome,
                         alunocurso.ed56_c_situacao,
                         serie.ed11_i_codigo as dl_codigo,
                         serie.ed11_c_descr as dl_etapa,
                         case
                          when (alunocurso.ed56_c_situacao != '' or trim(alunocurso.ed56_c_situacao) != 'CANDIDATO') then
                          (select ed57_c_descr from matricula inner join turma on ed57_i_codigo = ed60_i_turma where ed47_i_codigo = ed60_i_aluno order by ed60_i_codigo desc limit 1)
                          else null end as dl_turma,
                         case
                          when alunocurso.ed56_i_codigo is not null
                           then escola.ed18_i_codigo
                          else null end as dl_codigoescola,
                         case
                          when alunocurso.ed56_i_codigo is not null
                           then escola.ed18_c_nome
                          else null end as dl_escola,
                         cursoedu.ed29_i_codigo as dl_codigocurso,
                         cursoedu.ed29_c_descr as dl_curso,
                         calendario.ed52_c_descr as dl_calendario
                  FROM aluno
                   left join alunocurso on alunocurso.ed56_i_aluno = aluno.ed47_i_codigo
                   left join escola on escola.ed18_i_codigo = alunocurso.ed56_i_escola
                   left join calendario on  calendario.ed52_i_codigo = alunocurso.ed56_i_calendario
                   left join base on  base.ed31_i_codigo = alunocurso.ed56_i_base
                   left join cursoedu on  cursoedu.ed29_i_codigo = base.ed31_i_curso
                   left join alunopossib on  alunopossib.ed79_i_alunocurso = alunocurso.ed56_i_codigo
                   left join serie on  serie.ed11_i_codigo = alunopossib.ed79_i_serie
            ";
    if(isset($chave_ed47_i_codigo)){
     $repassa = array("chave_ed47_i_codigo"=>$chave_ed47_i_codigo,"chave_ed47_v_nome"=>$chave_ed47_v_nome);
    }
    if(isset($chave_ed47_i_codigo) && (trim($chave_ed47_i_codigo)!="") ){
     $sql .= " WHERE ed47_i_codigo = $chave_ed47_i_codigo AND ed56_i_escola = $escola AND ed56_c_situacao != 'TRANSFERIDO REDE' AND ed56_c_situacao != 'TRANSFERIDO FORA' AND ed56_c_situacao != 'CANDIDATO' AND ed56_c_situacao != 'FALECIDO' AND ed56_c_situacao != 'ENCERRADO') as x ORDER BY ed47_v_nome";
     db_lovrot(@$sql,12,"()","",$funcao_js,"","NoMe",$repassa);
    }else if(isset($chave_ed47_v_nome) && (trim($chave_ed47_v_nome)!="") ){
     $sql .= " WHERE ed47_v_nome like '$chave_ed47_v_nome%' AND ed56_i_escola = $escola AND ed56_c_situacao != 'TRANSFERIDO REDE' AND ed56_c_situacao != 'TRANSFERIDO FORA' AND ed56_c_situacao != 'CANDIDATO' AND ed56_c_situacao != 'FALECIDO' AND ed56_c_situacao != 'ENCERRADO') as x ORDER BY ed47_v_nome";
     db_lovrot(@$sql,12,"()","",$funcao_js,"","NoMe",$repassa);
    }
   }else{
    if($pesquisa_chave!=null && $pesquisa_chave!=""){
     $result = $claluno->sql_record($claluno->sql_query_pesq("","*","",$where." AND ed47_i_codigo = $pesquisa_chave"));
     if($claluno->numrows!=0){
      db_fieldsmemory($result,0);
      echo "<script>".$funcao_js."('$ed47_i_codigo',false);</script>";
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
js_tabulacaoforms("form2","chave_ed47_v_nome",true,1,"chave_ed47_v_nome",true);
</script>