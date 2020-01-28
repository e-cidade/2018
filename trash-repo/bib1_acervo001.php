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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_acervo_classe.php");
include("classes/db_biblioteca_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clacervo     = new cl_acervo;
$clbiblioteca = new cl_biblioteca;

$db_opcao  = 1;
$db_opcao1 = 1;
$db_botao  = true;
$depto     = db_getsession("DB_coddepto");

$result = $clbiblioteca->sql_record($clbiblioteca->sql_query("","bi17_codigo,bi17_nome",""," bi17_coddepto = $depto"));
if ($clbiblioteca->numrows != 0) {
  
  db_fieldsmemory($result,0);
  $bi06_biblioteca = $bi17_codigo;
}
if (isset($incluir)) {
  
  db_inicio_transacao();
  
  if (!empty($bi06_colecaoacervo)) {
    $clacervo->bi06_colecaoacervo = $bi06_colecaoacervo;
  }
  
  $clacervo->incluir(@$bi06_seq);
  db_fim_transacao();
  $db_botao = false;
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
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<div class="container">
   <fieldset style="width:93%"><legend><b>Inclusão de Acervo</b></legend>
    <?include("forms/db_frmacervo.php");?>
   </fieldset>
</div>
<script>
js_tabulacaoforms("form1","bi06_titulo",true,1,"bi06_titulo",true);
</script>
</body>
</html>
<?
if(isset($incluir)){
 if($clacervo->erro_status=="0"){
  $clacervo->erro(true,false);
  $db_botao=true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>";
  if($clacervo->erro_campo!=""){
   echo "<script> document.form1.".$clacervo->erro_campo.".style.backgroundColor='#99A9AE';</script>";
   echo "<script> document.form1.".$clacervo->erro_campo.".focus();</script>";
  };
 }else{
  $clacervo->erro(true,false);
  db_redireciona("bib1_acervo002.php?chavepesquisa=".$clacervo->bi06_seq);
 };
}
?>