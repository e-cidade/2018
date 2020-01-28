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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("classes/db_acervo_classe.php");
require_once ("classes/db_autoracervo_classe.php");
require_once ("classes/db_localacervo_classe.php");
require_once ("classes/db_localexemplar_classe.php");
require_once ("classes/db_emprestimoacervo_classe.php");
require_once ("classes/db_assunto_classe.php");
require_once ("classes/db_exemplar_classe.php");
require_once ("classes/db_reserva_classe.php");
require_once ("classes/db_biblioteca_classe.php");
require_once ("classes/db_baixabib_classe.php");
require_once ("classes/db_impexemplaritem_classe.php");
require_once ("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clacervo           = new cl_acervo;
$clbiblioteca       = new cl_biblioteca;
$classunto          = new cl_assunto;
$cllocalacervo      = new cl_localacervo;
$cllocalexemplar    = new cl_localexemplar;
$clautoracervo      = new cl_autoracervo;
$clemprestimoacervo = new cl_emprestimoacervo;
$clexemplar         = new cl_exemplar;
$clreserva          = new cl_reserva;
$clbaixabib         = new cl_baixa;
$climpexemplaritem  = new cl_impexemplaritem;

$db_botao  = false;
$db_opcao  = 33;
$db_opcao1 = 3;
$depto     = db_getsession("DB_coddepto");
$result    = $clbiblioteca->sql_record($clbiblioteca->sql_query("","bi17_codigo,bi17_nome",""," bi17_coddepto = $depto"));
if ($clbiblioteca->numrows != 0) {
  db_fieldsmemory($result,0);
  $bi06_biblioteca = $bi17_codigo;
}
if (isset($excluir)) {
  
  $db_opcao = 3;
  $result = $clemprestimoacervo->sql_record($clemprestimoacervo->sql_query("","bi23_codigo",""," bi06_seq = $bi06_seq"));
  $result = $clreserva->sql_record($clreserva->sql_query("","bi14_codigo",""," bi06_seq = $bi06_seq"));
  if ($clemprestimoacervo->numrows > 0) {
    
    $clacervo->erro_status = "0";
    $clacervo->erro_msg = "Acervo contém registro de Empréstimo. Exclusão não permitida!";
  } elseif ($clreserva->numrows > 0) {
    $clacervo->erro_status = "0";
    $clacervo->erro_msg = "Acervo contém registro de Reserva. Exclusão não permitida!";
  } else {
    
    db_inicio_transacao();
    $clbaixabib->excluir("","bi08_exemplar in (select bi23_codigo from exemplar where bi23_acervo = $bi06_seq)");
    $cllocalexemplar->excluir("","bi27_exemplar in (select bi23_codigo from exemplar where bi23_acervo = $bi06_seq)");
    $climpexemplaritem->excluir("","bi25_exemplar in (select bi23_codigo from exemplar where bi23_acervo = $bi06_seq)");
    $clexemplar->excluir("","bi23_acervo = $bi06_seq");
    $cllocalacervo->excluir("","bi20_acervo = $bi06_seq");
    $clautoracervo->excluir("","bi21_acervo = $bi06_seq");
    $classunto->excluir("","bi15_acervo = $bi06_seq");
    $clacervo->excluir($bi06_seq);
    db_fim_transacao();
  }
} else if(isset($chavepesquisa)) {
  
  $db_opcao = 3;
  $result   = $clacervo->sql_record($clacervo->sql_query($chavepesquisa));
  db_fieldsmemory($result,0);
  $db_botao = true;
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
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<div class="container">
   <fieldset style="width:93%"><legend><b>Exclusão de Acervo</b></legend>
    <?include("forms/db_frmacervo.php");?>
   </fieldset>
</div>
<script>
js_tabulacaoforms("form1","excluir",true,1,"excluir",true);
</script>
</body>
</html>
<?
if(isset($excluir)){
 if($clacervo->erro_status=="0"){
  $clacervo->erro(true,false);
  $db_botao=true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>";
  if($clacervo->erro_campo!=""){
   echo "<script> document.form1.".$clacervo->erro_campo.".style.backgroundColor='#99A9AE';</script>";
   echo "<script> document.form1.".$clacervo->erro_campo.".focus();</script>";
  };
 }else{
  $clacervo->erro(true,true);
 };
}
if($db_opcao==33){
 echo "<script>document.form1.pesquisar.click();</script>";
}
?>