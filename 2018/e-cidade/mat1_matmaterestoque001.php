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
include("dbforms/db_classesgenericas.php");
include("classes/db_matmater_classe.php");
include("classes/db_matmaterestoque_classe.php");
include("classes/db_db_almox_classe.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$clmatmater               = new cl_matmater;
$clmatmaterestoque        = new cl_matmaterestoque;
$cldb_almox               = new cl_db_almox;
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;

$clmatmaterestoque->rotulo->label();

$db_botao = true;
$sqlerro  = false;
$erro_msg = "";

$res_db_almox = $cldb_almox->sql_record($cldb_almox->sql_query_file(null,"m91_codigo as m64_almox",null,"m91_depto = ".db_getsession("DB_coddepto"))); 
if ($cldb_almox->numrows > 0){
     db_fieldsmemory($res_db_almox,0);
     $flag_almox = "true";
} else {
     $flag_almox = "false";
}

if (isset($incluir)){
     db_inicio_transacao();

     $res_matmaterestoque = $clmatmaterestoque->sql_record($clmatmaterestoque->sql_query_file(null,"m64_almox as almox",null,"m64_almox = $m64_almox and m64_matmater = $m64_matmater"));
     if ($clmatmaterestoque->numrows > 0){
          $erro_msg = "Deposito com material ja cadastrado.";
          $sqlerro  = true;
     }

     if ($sqlerro == false){
          $clmatmaterestoque->m64_matmater      = "$m64_matmater";
          $clmatmaterestoque->m64_almox         = "$m64_almox";
          $clmatmaterestoque->m64_estoqueminimo = "$m64_estoqueminimo";
          $clmatmaterestoque->m64_estoquemaximo = "$m64_estoquemaximo";
          $clmatmaterestoque->m64_pontopedido   = "$m64_pontopedido";
          $clmatmaterestoque->m64_localizacao   =  $m64_localizacao;

          $clmatmaterestoque->incluir(null);
          $erro_msg = $clmatmaterestoque->erro_msg;
          if ($clmatmaterestoque->erro_status == 0){
               $sqlerro = true;
          } else {
               $m64_sequencial = $clmatmaterestoque->m64_sequencial;
          }
     }

     db_fim_transacao($sqlerro);
} else if (isset($alterar)){
     db_inicio_transacao();
     $clmatmaterestoque->m64_sequencial    = "$m64_sequencial";
     $clmatmaterestoque->m64_matmater      = "$m64_matmater";
     $clmatmaterestoque->m64_almox         = "$m64_almox";
     $clmatmaterestoque->m64_estoqueminimo = "$m64_estoqueminimo";
     $clmatmaterestoque->m64_estoquemaximo = "$m64_estoquemaximo";
     $clmatmaterestoque->m64_pontopedido   = "$m64_pontopedido";
     $clmatmaterestoque->m64_localizacao   =  $m64_localizacao;

     $clmatmaterestoque->alterar($m64_sequencial);
     $erro_msg = $clmatmaterestoque->erro_msg;
     if ($clmatmaterestoque->erro_status == 0){
          $sqlerro = true;
     }

     db_fim_transacao($sqlerro);
} else if (isset($excluir)){
     db_inicio_transacao();
     $clmatmaterestoque->excluir($m64_sequencial);
     $erro_msg = $clmatmaterestoque->erro_msg;
     if ($clmatmaterestoque->erro_status == 0){
          $sqlerro = true;
     }
     db_fim_transacao($sqlerro);
} else if (isset($opcao) && trim(@$opcao) != 1){
     $res_matmaterestoque = $clmatmaterestoque->sql_record($clmatmaterestoque->sql_query_file($m64_sequencial));

     if ($clmatmaterestoque->numrows > 0){
          db_fieldsmemory($res_matmaterestoque,0);
     }
}
if (!isset($opcao)){
     $m64_estoqueminimo = 0;
     $m64_estoquemaximo = 0;
     $m64_pontopedido   = 0;
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
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmmatmaterestoque.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if(isset($incluir) || isset($alterar) || isset($excluir)){
  if($sqlerro==true){
    $clmatmaterestoque->erro(true,false);
    if($clmatmaterestoque->erro_campo!=""){
      echo "<script> parent.document.form1.".$clmatmaterestoque->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> parent.document.form1.".$clmatmaterestoque->erro_campo.".focus();</script>";
    }
    if (trim(@$erro_msg)!=""){
         db_msgbox($erro_msg);
    }
  }else{
    if (trim(@$erro_msg)!=""){
         db_msgbox($erro_msg);
    }
    echo "<script>
               parent.iframe_matmaterestoque.location.href='mat1_matmaterestoque001.php?m64_matmater=".@$m64_matmater."';\n
	 </script>";
  }
}  
?>