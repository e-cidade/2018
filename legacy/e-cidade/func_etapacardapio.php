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
require("libs/db_utils.php");
include("classes/db_matricula_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clmatricula  = new cl_matricula;
$db_opcao     = 1;
$db_botao     = true;
$oGet = db_utils::postMemory($_GET);
$rsMatriculaTurma = $clmatricula->sql_record(
                     $clmatricula->sql_query("",
                                             "count(*) as qtde,
                                              turma.ed57_i_codigo,
                                              turma.ed57_c_descr,
                                              turno.ed15_c_nome,
                                              serie.ed11_c_descr,
                                              escola.ed18_c_nome",                          
                                             "turma.ed57_c_descr",
                                             "turma.ed57_i_escola = ($oGet->escola) 
                                              AND calendario.ed52_i_ano = ($oGet->ano)
                                              AND matriculaserie.ed221_i_serie = ($oGet->serie)
                                              AND ed60_c_situacao = 'MATRICULADO' 
                                              GROUP BY turma.ed57_i_codigo,
                                                       turma.ed57_c_descr,
                                                       turno.ed15_c_nome,
                                                       serie.ed11_c_descr,
                                                       escola.ed18_c_nome"
                                             ));
if ($clmatricula->numrows==0) {?>
  <table width='100%'>
   <tr>
    <td align='center'>
     <font color='#FF0000' face='arial'>
      <b>Nenhum registro encontrado.<br>
      <input type='button' value='Fechar' onclick='window.close()'></b>
     </font>
    </td>
   </tr>
  </table>
 <?
 exit;
}else{
  $oMatriculaTurma = db_utils::fieldsMemory($rsMatriculaTurma,0);	
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
.cabec{
 text-align: left;
 font-size: 13;
 font-weight: bold;
 color: #DEB887;
 background-color:#444444;
 border:1px solid #CCCCCC;
}
.aluno{
 font-size: 11;
}
</style>
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<a name="topo"></a>
<form name="form1" method="post" action="">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center" valign="top" bgcolor="#CCCCCC">
      <br>
      <fieldset style="width:95%"><legend><b>Escola: <?=$oMatriculaTurma->ed18_c_nome?> - Etapa: <?=$oMatriculaTurma->ed11_c_descr?> - Alunos Matriculados</b></legend>
        <table width="100%" bgcolor="#cccccc" style="" cellspacing="0px">
          <tr bgcolor="">
            <td>
              <table border='1px' width="100%" cellpading="0" cellspacing="0">
                <?
                for ($w=0;$w<$clmatricula->numrows;$w++) {
                	
                  $oMatriculaTurma = db_utils::fieldsMemory($rsMatriculaTurma, $w);
           	      ?>
           	      <tr> 
                    <td width="35%">&nbsp;&nbsp;&nbsp;&nbsp;Turma: 
                      <a href="javascript:js_alunos(<?=$oMatriculaTurma->ed57_i_codigo?>,<?=$oGet->serie?>)" 
                         title="Veja os alunos matriculados nesta turma"><?=$oMatriculaTurma->ed57_c_descr?></a></td>
                    <td width="35%">Qtde Alunos: <?=$oMatriculaTurma->qtde?></td>                         
                    <td width="30%">Turno: <?=$oMatriculaTurma->ed15_c_nome?></td>
                  </tr>
                  <?
                  
                }
                ?>
              </table>
            </td>
          </tr>         
        </table>
      </fieldset>
    </td>
  </tr>
</table>
</form>
</body>
</html>
<script>
function js_alunos(turma,ed11_i_codigo) {
	
  js_OpenJanelaIframe("",
                      "db_iframe_matriculas",
                      "func_etapacardapioescola.php?turma="+turma+"&etapaserie="+ed11_i_codigo,
                      "Alunos Matriculados na Turma",true,0, 25, screen.availWidth-100,screen.availHeight-100);
	 	
}
</script>