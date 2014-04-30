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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_transfescolafora_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$cltransfescolafora = new cl_transfescolafora;
$clrotulo = new rotulocampo;
$clrotulo->label("ed47_i_codigo");
$clrotulo->label("ed47_v_nome");
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
      <td width="4%" align="right" >
       <?=$Led47_v_nome?>
      </td>
      <td width="96%" align="left" nowrap>
       <?db_input("ed47_v_nome",40,$Ied47_v_nome,true,"text",4,"","chave_ed47_v_nome");?>
      </td>
     </tr>
     <tr>
      <td width="4%" align="right" nowrap title="<?=$Ted47_v_nome?>">
       <b>Ano do Calendário da Transferência:</b>
      </td>
      <td width="96%" align="left" nowrap>
       <?db_input("ed52_i_ano",4,@$ed52_i_ano,true,"text",4,"","chave_ed52_i_ano");?>
      </td>
     </tr>
     <tr>
      <td colspan="2" align="center">
       <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar" onclick="return js_ano();">
       <input name="limpar" type="reset" id="limpar" value="Limpar" >
       <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_transfescolafora.hide();">
      </td>
     </tr>
     </form>
    </table>
   </td>
 </tr>
 <tr>
  <td align="center" valign="top">
   <?
   if(isset($pesquisar)){
    $campos = "ed104_i_aluno,
               ed47_v_nome,
               turmaatual.ed57_c_descr,
               ed104_i_escolaorigem,
               ed18_c_nome,
               ed104_i_escoladestino,
               ed82_c_nome,
               ed104_d_data,
               matricula.ed60_i_codigo as matricula,
               turma.ed57_i_codigo as turmaorigem,
               turma.ed57_c_descr as ed57_c_descrorigem,
               turmaatual.ed57_i_codigo as turmadestino
               ";
    $sql = "SELECT $campos FROM transfescolafora
             inner join escola  on  escola.ed18_i_codigo = transfescolafora.ed104_i_escolaorigem
             inner join aluno  on  aluno.ed47_i_codigo = transfescolafora.ed104_i_aluno
             inner join escolaproc  on  escolaproc.ed82_i_codigo = transfescolafora.ed104_i_escoladestino
             inner join matricula on matricula.ed60_i_codigo = transfescolafora.ed104_i_matricula
             inner join turma on turma.ed57_i_codigo = matricula.ed60_i_turma
             inner join calendario on calendario.ed52_i_codigo = turma.ed57_i_calendario
             inner join matricula as matriculaatual on matriculaatual.ed60_i_aluno = transfescolafora.ed104_i_aluno
             inner join turma as turmaatual on turmaatual.ed57_i_codigo = matriculaatual.ed60_i_turma
             inner join calendario as calendarioatual on calendarioatual.ed52_i_codigo = turmaatual.ed57_i_calendario
            WHERE matricula.ed60_c_situacao = 'TRANSFERIDO FORA'
            AND matricula.ed60_c_ativa = 'S'
            AND turmaatual.ed57_i_escola = ".db_getsession("DB_coddepto")."
            AND matriculaatual.ed60_c_situacao = 'MATRICULADO'
            AND calendario.ed52_i_ano = $chave_ed52_i_ano
            AND calendarioatual.ed52_i_ano = $chave_ed52_i_ano
            ";
    if(isset($chave_ed47_i_codigo) && (trim($chave_ed47_i_codigo)!="") ){
     $sql .= " AND ed104_i_aluno = $chave_ed47_i_codigo";
    }else if(isset($chave_ed47_v_nome) && (trim($chave_ed47_v_nome)!="") ){
     $sql .= " AND ed47_v_nome like '$chave_ed47_v_nome%'";
    }
    $sql .= " ORDER BY ed47_v_nome";
    $repassa = array();
    if(isset($chave_ed47_i_codigo)){
     $repassa = array("chave_ed47_i_codigo"=>$chave_ed47_i_codigo,"chave_ed47_v_nome"=>$chave_ed47_v_nome,"chave_ed52_i_ano"=>$chave_ed52_i_ano);
    }
    db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
   }
   ?>
  </td>
 </tr>
</table>
</body>
</html>
<script>
js_tabulacaoforms("form2","chave_ed47_i_codigo",true,1,"chave_ed47_i_codigo",true);
function js_ano(){
 if(document.form2.chave_ed52_i_ano.value==""){
  alert("Informe o ano do calendário da transferência!");
  return false;
 }
 return true;
}
</script>