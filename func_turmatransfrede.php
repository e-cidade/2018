<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clturma = new cl_turma;
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
   <br>
   <b>
    Turmas disponíveis para matricular o aluno <?=$aluno?>:
   </b>
  </td>
 </tr>
 <tr>
  <td align="center" valign="top">
   <?
   if($avalparcial == 2) {

     $sSqlReg  = " AND not exists";
     $sSqlReg .= " (SELECT * ";
     $sSqlReg .= " FROM histmpsdisc";
     $sSqlReg .= "  inner join historicomps on ed62_i_codigo = ed65_i_historicomps";
     $sSqlReg .= "  inner join historico on ed61_i_codigo = ed62_i_historico";
     $sSqlReg .= " WHERE ed61_i_aluno  = $codaluno";
     $sSqlReg .= " AND ed61_i_curso = ed29_i_codigo";
     $sSqlReg .= " AND ed62_i_serie = $serie";
     $sSqlReg .= " AND ed62_c_resultadofinal = 'P'";
     $sSqlReg .= " AND exists(select * from regencia";
     $sSqlReg .= "            where ed59_i_turma = ed57_i_codigo";
     $sSqlReg .= "            and ed59_i_serie = $serie";
     $sSqlReg .= "            and ed59_i_disciplina = ed65_i_disciplina))";

   } else {
     $sSqlReg = "";
   }
   if (isset($turmasprogressao) && $turmasprogressao == 'f') {
     $sSqlReg .= " and ed57_i_tipoturma <> 6";
   }
   if(!isset($pesquisa_chave)){
    if(isset($campos)==false){
     if(file_exists("funcoes/db_func_turma.php")==true){
      include("funcoes/db_func_turma.php");
     }else{
      $campos = "turma.*";
     }
    }
    $sql = $clturma->sql_query_turmaserie("","DISTINCT {$campos},
                                          ed52_d_inicio,ed52_d_fim",
                                          "ed57_c_descr",
                                          " ed57_i_escola = {$escola}
                                           AND ed57_i_calendario = {$calendario}
                                           AND ed223_i_serie = {$serie} {$sSqlReg}"
                                         );
    db_lovrot(@$sql,15,"()","",$funcao_js);

   }else{

    if($pesquisa_chave!=null && $pesquisa_chave!=""){
     $result = $clturma->sql_record($clturma->sql_query_turmaserie("","*","ed57_c_descr"," ed57_i_codigo = $pesquisa_chave AND ed57_i_escola = $escola AND ed57_i_calendario = $calendario AND ed223_i_serie = $serie $sSqlReg "));
     if($clturma->numrows!=0){
      db_fieldsmemory($result,0);
      echo "<script>".$funcao_js."('$ed57_c_descr',false);</script>";
     }else{
      echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
     }
    }else{
     echo "<script>".$funcao_js."('','',false);</script>";
    }
   }
   ?>
  </td>
 </tr>
</table>
</body>
</html>