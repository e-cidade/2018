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
include("libs/db_utils.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_cfpatri_classe.php");
include("classes/db_bens_classe.php");
db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);

$clcfpatri       = new cl_cfpatri;
$clbens          = new cl_bens;

$db_opcao        = 22;
$db_botao        = false;

if (isset($alterar)) {
  
   db_inicio_transacao();
   $result = $clcfpatri->sql_record($clcfpatri->sql_query());

   if($result == false || $clcfpatri->numrows == 0) {
     $clcfpatri->incluir($t06_codcla);
   }else{
     
     $clcfpatri->t06_codcla                   = $t06_codcla;
     $clcfpatri->t06_pesqorgao                = $t06_pesqorgao;
     $clcfpatri->t06_bensmodeloetiqueta       = trim($t06_bensmodeloetiqueta);
     $clcfpatri->t06_controlaplacainstituicao = $t06_controlaplacainstituicao;
     $clcfpatri->alterarModeloEtiquetaNulo($t06_codcla); 
     
   }
   
   db_fim_transacao();
} else {

//   die ($clcfpatri->sql_query());
  $result = $clcfpatri->sql_record($clcfpatri->sql_query());
  if ($result != false && $clcfpatri->numrows > 0) {
    db_fieldsmemory($result, 0);
  }
}

if (db_getsession("DB_login")!="dbseller") {
  
     $result = $clbens->sql_record($clbens->sql_query_file(null,"*","t52_instit","t52_instit = ".db_getsession("DB_instit")));
     if ($clbens->numrows > 0){
       
          $db_opcao = 3;
          db_msgbox(_M('patrimonial.patrimonio.db_frmcfpatri.contate_dbseller'));
     } else {
       
          $db_opcao = 2;
          $db_botao = true;
     }
} else {
  
     $db_opcao = 2;
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
<body bgcolor=#CCCCCC >
	<?
	include("forms/db_frmcfpatri.php");
	?>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if(isset($alterar)){
  if($clcfpatri->erro_status=="0"){
    $clcfpatri->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clcfpatri->erro_campo!=""){
      echo "<script> document.form1.".$clcfpatri->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clcfpatri->erro_campo.".focus();</script>";
    }
  }else{
    $clcfpatri->erro(true,true);
  }
}
if($db_opcao==22){
 // echo "<script>document.form1.pesquisar.click();</script>";
}
?>