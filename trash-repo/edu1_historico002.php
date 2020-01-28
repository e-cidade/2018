<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

require_once("libs/db_stdlibwebseller.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_utils.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$oDaoHistorico  = db_utils::getdao("historico");
$oDaoAlunoCurso = db_utils::getdao("alunocurso");
$db_opcao       = 2;
$db_opcao1      = 3;
$db_opcao2      = 3;
$db_botao       = false;

if (isset($alterar)) {
	
  db_inicio_transacao();
  $db_opcao  = 2;
  $db_opcao1 = 3;
  $db_opcao2 = 3;
  $oDaoHistorico->alterar($ed61_i_codigo);
  db_fim_transacao();
  
} elseif (isset($chavepesquisa)) {
	
  $db_opcao      = 2;
  $db_opcao1     = 3;
  $db_opcao2     = 3;
  $sSqlHistorico = $oDaoHistorico->sql_query($chavepesquisa);
  $rsHistorico   = $oDaoHistorico->sql_record($sSqlHistorico);
  db_fieldsmemory($rsHistorico, 0);
  $sSqlAlunoCurso = $oDaoAlunoCurso->sql_query("", "ed56_c_situacao", "", " ed56_i_aluno = $ed61_i_aluno");
  $rsAlunoCurso   = $oDaoAlunoCurso->sql_record($sSqlAlunoCurso,  0);
  
  if ($oDaoAlunoCurso->numrows > 0) {
  	
    db_fieldsmemory($rsAlunoCurso, 0);
    $situacao = $ed56_c_situacao == "CONCLUÍDO" ? "CONCLUÍDO" : "EM ANDAMENTO";
    
  } else {
    $situacao = "CADASTRADO";
  }
  
  $db_botao = true;
  
}

if (isset($excluir)) {
	
  db_inicio_transacao();
  $db_opcao = 3;
  $oDaoHistorico->excluir($ed61_i_codigo);
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
 </head>
 <body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
   <tr>
    <td align="left" valign="top" bgcolor="#CCCCCC">
     <center>
      <fieldset style="width:95%;height:445px;"><legend><b>Curso</b></legend>
       <?include("forms/db_frmhistoricoesc.php");?>
      </fieldset>
     </center>
    </td>
   </tr>
  </table>
 </body>
</html>
<?
if (isset($alterar)) {
	
  if ($oDaoHistorico->erro_status == "0") {
  	
    $oDaoHistorico->erro(true, false);
    $db_botao = true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    
    if ($oDaoHistorico->erro_campo != "") {
    	
      echo "<script> document.form1.".$oDaoHistorico->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$oDaoHistorico->erro_campo.".focus();</script>";
      
    }
    
  } else {
  	
    $oDaoHistorico->erro(true, false);
    ?>
    <script>
     location.href = "edu1_historico002.php?chavepesquisa=<?=$ed61_i_codigo?>";
     parent.arvore.location.href = "edu1_historicoarvore.php?ed61_i_aluno=<?=$ed61_i_aluno?>&ed47_v_nome=<?=$ed47_v_nome?>";
    </script>
    <?
    
  }
  
}

if (isset($excluir)) {
	
  if ($oDaoHistorico->erro_status == "0") {
    $oDaoHistorico->erro(true, false);
  } else {
  	
    $oDaoHistorico->erro(true, false);
    ?>
    <script>
     location.href = "edu1_historico001.php?ed61_i_aluno=<?=$ed61_i_aluno?>&ed47_v_nome=<?=$ed47_v_nome?>";
     parent.arvore.location.href = "edu1_historicoarvore.php?ed61_i_aluno=<?=$ed61_i_aluno?>&ed47_v_nome=<?=$ed47_v_nome?>";
    </script>
    <?
    
  }
  
}
?>
<script>
js_tabulacaoforms("form1", "ed61_i_escola", true, 1, "ed61_i_escola", true);
</script>