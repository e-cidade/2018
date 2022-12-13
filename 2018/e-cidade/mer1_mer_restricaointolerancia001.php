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
include("classes/db_mer_restricaointolerancia_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clmer_restricaointolerancia = new cl_mer_restricaointolerancia;
$db_opcao          = 1;
$db_botao          = true;
if (isset($opcao)) {
  $result1 = $clmer_restricaointolerancia->sql_record($clmer_restricaointolerancia->sql_query("",
                                                                                              "*",
                                                                                              "",
                                                                                              "me34_i_codigo=$me34_i_codigo"
                                                                                             )
                                                     );
  if ($clmer_restricaointolerancia->numrows>0) {
    db_fieldsmemory($result1,0);
  }
  if ($opcao == "alterar") {
    $db_opcao = 2;
  } else {
    
    if ($opcao == "excluir" || isset($db_opcao) && $db_opcao == 3) {
     $db_opcao = 3;
    } else {
        
      if (isset($alterar)) {
        $db_opcao = 2;
      }
    }    
  }
}
if (isset($incluir)) {
    
 db_inicio_transacao();
 $clmer_restricaointolerancia->incluir($me34_i_codigo);
 db_fim_transacao();
 
}
if (isset($alterar)) {
    
 $db_opcao = 2;
 db_inicio_transacao();
 $clmer_restricaointolerancia->alterar($me34_i_codigo);
 db_fim_transacao();
 
}
if (isset($excluir)) {
    
 $db_opcao = 3;
 db_inicio_transacao();
 $clmer_restricaointolerancia->excluir($me34_i_codigo);
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="100%" align="left" valign="top" bgcolor="#CCCCCC"> 
    <br><br>
    <center>
    <fieldset style="width:95%"><legend><b>Inclusão de Intolerância Alimentar</b></legend>
	<?
	include("forms/db_frmmer_restricaointolerancia.php");
	?>
	</fieldset>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<script>
js_tabulacaoforms("form1", "me34_i_restricao", true, 1, "me34_i_restricao", true);
</script>
<?if (isset($incluir) || isset($alterar) || isset($excluir)) {
    
  if ($clmer_restricaointolerancia->erro_status=="0") {
    
    $clmer_restricaointolerancia->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    
    if ($clmer_restricaointolerancia->erro_campo!="") {
        
      echo "<script> document.form1.".$clmer_restricaointolerancia->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clmer_restricaointolerancia->erro_campo.".focus();</script>";
      
    }
  } else {
    
    $clmer_restricaointolerancia->erro(true,false);
    db_redireciona("mer1_mer_restricaointolerancia001.php?me34_i_restricao=$me34_i_restricao&me24_i_aluno=$me24_i_aluno&ed47_v_nome=$ed47_v_nome"
                  );
    
  }
}
?>