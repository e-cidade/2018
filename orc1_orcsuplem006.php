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
include("classes/db_orcsuplem_classe.php");
include("dbforms/db_funcoes.php");
include("classes/db_orcsuplemval_classe.php");


parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clorcsuplem = new cl_orcsuplem;
$clorcsuplemval = new cl_orcsuplemval;  // usada para exlusao de itens 

$db_botao = false;
$db_opcao = 33;
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Excluir"){
    $db_opcao = 3;
     // exclui registros filhos 
       $codsup = $o46_codsup;
       $clorcsuplemval->sql_record($clorcsuplemval->sql_query_file($codsup));
       if($clorcsuplemval->numrows > 0 ){
	  $clorcsuplemval->o47_codsup = $codsup;
          $clorcsuplemval->excluir($codsup); 
	  $clorcsuplemval->erro(true,false);
           if($clorcsuplemval->erro_status==0){
                $sqlerro=true; 
		}	 
         }
  db_inicio_transacao();
  // exclui o pai 
  $clorcsuplem->excluir($o46_codsup);   
  db_fim_transacao();

  $db_botao= false;  // chave pesquisa vazia
  
}else if(isset($chavepesquisa)){
   $db_opcao = 3;
   $result = $clorcsuplem->sql_record($clorcsuplem->sql_query($chavepesquisa)); 
   db_fieldsmemory($result,0);
   $db_botao = true;
   // aki tem alguma coisa na chavepesquisa...
   echo "
       <script>
           function js_xy(){
               parent.document.formaba.orcsuplemval.disabled=false;\n
               top.corpo.iframe_orcsuplemval.location.href='orc1_orcsuplemval001.php?o47_codsup=$o46_codsup&db_opcao=33';\n
              // parent.mo_camada('orcsuplemval');
		
              }
              js_xy();
        </script>
         ";

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

<!---
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
--->

<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmorcsuplem.php");
	?>
    </center>
	</td>
  </tr>
</table>
<?
// db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
// era um menu .....
?>
</body>
</html>
<?
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Excluir"){
  if($clorcsuplem->erro_status=="0"){
    $clorcsuplem->erro(true,false); // 
  }else{
    $clorcsuplem->erro(true,false);  // true-true = reload na pagina
    echo "

    <script>
           parent.location.href='orc1_orcsuplem003.php';
    </script>

    ";
  };
};
// se opção 33 simula clique no pesquisa...
if($db_opcao==33){
 echo "<script>document.form1.pesquisar.click();</script>";
 }



?>