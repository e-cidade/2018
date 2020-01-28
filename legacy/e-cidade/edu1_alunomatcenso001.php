<?
/*
 *     E-cidade Software P�blico para Gest�o Municipal                
 *  Copyright (C) 2014  DBseller Servi�os de Inform�tica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa � software livre; voc� pode redistribu�-lo e/ou     
 *  modific�-lo sob os termos da Licen�a P�blica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a vers�o 2 da      
 *  Licen�a como (a seu crit�rio) qualquer vers�o mais nova.          
 *                                                                    
 *  Este programa e distribu�do na expectativa de ser �til, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia impl�cita de              
 *  COMERCIALIZA��O ou de ADEQUA��O A QUALQUER PROP�SITO EM           
 *  PARTICULAR. Consulte a Licen�a P�blica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU     
 *  junto com este programa; se n�o, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  C�pia da licen�a no diret�rio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_alunomatcenso_classe.php");
include("classes/db_aluno_classe.php");
include("dbforms/db_funcoes.php");

db_postmemory($HTTP_POST_VARS);

$clalunomatcenso = new cl_alunomatcenso;
$claluno         = new cl_aluno;
$db_opcao        = 1;
$db_botao        = true;
$result_inep     = $claluno->sql_record($claluno->sql_query("","ed47_c_codigoinep",""," ed47_i_codigo = $ed280_i_aluno"));
db_fieldsmemory($result_inep,0);

if (isset($incluir)) {
  
 db_inicio_transacao();
 $clalunomatcenso->incluir($ed280_i_codigo);
 db_fim_transacao();
}

if (isset($alterar)) {
  
  db_inicio_transacao();
  $db_opcao = 2;
  $clalunomatcenso->alterar($ed280_i_codigo);
  db_fim_transacao();
}

if (isset($excluir)) {
  
  db_inicio_transacao();
  $db_opcao = 3;
  $clalunomatcenso->excluir($ed280_i_codigo);
  db_fim_transacao();
}
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#CCCCCC">
	<?
	  include("forms/db_frmalunomatcenso.php");
	?>
</body>
</html>
<script>
js_tabulacaoforms("form1","ed280_i_turmacenso",true,1,"ed280_i_turmacenso",true);
</script>
<?
if (isset($incluir)) {
  
  if ($clalunomatcenso->erro_status == "0") {
    
    $clalunomatcenso->erro(true, false);
    $db_botao = true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>";
    
   if ($clalunomatcenso->erro_campo != "") {
    
     echo "<script> document.form1.".$clalunomatcenso->erro_campo.".style.backgroundColor='#99A9AE';</script>";
     echo "<script> document.form1.".$clalunomatcenso->erro_campo.".focus();</script>";
   }
   
  } else {
   	
    $clalunomatcenso->erro(true,false);  
    db_redireciona("edu1_alunomatcenso001.php?ed280_i_aluno=$ed280_i_aluno&ed47_v_nome=$ed47_v_nome");
  }
}

if (isset($alterar)) {

  if($clalunomatcenso->erro_status == "0") {

    $clalunomatcenso->erro(true, false);
    $db_botao = true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>";
    
    if($clalunomatcenso->erro_campo != "") {

      echo "<script> document.form1.".$clalunomatcenso->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clalunomatcenso->erro_campo.".focus();</script>";
    }
    
  } else {

    $clalunomatcenso->erro(true, false);
    db_redireciona("edu1_alunomatcenso001.php?ed280_i_aluno=$ed280_i_aluno&ed47_v_nome=$ed47_v_nome");
  }
}

if (isset($excluir)) {

  if($clalunomatcenso->erro_status == "0") {
    $clalunomatcenso->erro(true, false);
  } else {
 
    $clalunomatcenso->erro(true, false);
    db_redireciona("edu1_alunomatcenso001.php?ed280_i_aluno=$ed280_i_aluno&ed47_v_nome=$ed47_v_nome");
  }
}

if (isset($cancelar)) {
  db_redireciona("edu1_alunomatcenso001.php?ed280_i_aluno=$ed280_i_aluno&ed47_v_nome=$ed47_v_nome");
}
?>