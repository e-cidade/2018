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
include("dbforms/db_funcoes.php");
include("classes/db_portaria_classe.php");
include("classes/db_assenta_classe.php");
include("classes/db_rhpessoal_classe.php");
include("classes/db_portariaassenta_classe.php");
include("classes/db_portariatipo_classe.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clportaria        = new cl_portaria;
$classenta         = new cl_assenta;
$clrhpessoal       = new cl_rhpessoal;
$clportariaassenta = new cl_portariaassenta;
$clportariatipo    = new cl_portariatipo;

$db_botao = false;
$db_opcao = 33;
$sqlerro  = false;
$erro_msg = "";

$lExibirNumeracaoPortaria = true;
$db_opcao_numero = 3;
    
if(isset($excluir)){
  db_inicio_transacao();

  $db_opcao = 3;
  $clportariaassenta->excluir(null,"h33_portaria = $h31_sequencial ");

  if ($clportariaassenta->erro_status == "0"){
       $sqlerro  = true;
       $erro_msg = $clportariaassenta->erro_msg;
  }

  if ($sqlerro == false){
       $clportaria->excluir($h31_sequencial);
       if ($clportaria->erro_status == "0"){
            $sqlerro  = true;
            $erro_msg = $clportaria->erro_msg;
       }
  }

  if ($sqlerro == false && !empty($h16_codigo)) {
       $classenta->excluir($h16_codigo);
       if ($classenta->erro_status == "0"){
            $sqlerro  = true;
            $erro_msg = $classenta->erro_msg;
       }
  }
  db_fim_transacao($sqlerro);
}else if(isset($chavepesquisa)){
   $db_opcao = 3;
   $result = $clportaria->sql_record($clportaria->sql_query($chavepesquisa)); 
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
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >

	<?php include("forms/db_frmportaria.php"); ?>
  <?php db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit")); ?>

</body>
</html>
<?
if(isset($excluir)){
  if($clportaria->erro_status=="0"){
    $clportaria->erro(true,false);
  }else{
    $clportaria->erro(true,true);
  }
}
if($db_opcao==33){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
<script>
js_tabulacaoforms("form1","excluir",true,1,"excluir",true);
</script>