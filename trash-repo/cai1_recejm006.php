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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_tabrecjm_classe.php");

db_postmemory($HTTP_POST_VARS);

$db_opcao   = 33;
$instit     = db_getsession("DB_instit");
$cltabrecjm = new cl_tabrecjm;

if(isset($excluir)) {
 
  $db_opcao     = 3;
  $sqlregras    = "select * from tabrecregrasjm where k04_codjm = $k02_codjm";
  $resultregras = pg_query($sqlregras);
  $linhasregras = pg_num_rows($resultregras);
  
  if($linhasregras > 0) {
    db_msgbox("Exclusão não realizada. Existe regra cadastrada para este tipo de juro e multa.");
  } else { 

    pg_exec("BEGIN");

    if ($cltabrecjm->excluir($k02_codjm) == false){
      pg_exec("ROLLBACK");
    } else {
      pg_exec("COMMIT");
    }
  }
} elseif (isset($chavepesquisa)) {

   $db_opcao = 3;
   $result   = $cltabrecjm->sql_record($cltabrecjm->sql_query($chavepesquisa)); 
   db_fieldsmemory($result,0);
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
<body bgcolor="#CCCCCC" leftmargin="0" onLoad="a=1;" >

  <?php include("forms/db_frmrecejm.php"); ?>

</body>
</html>
<?
if ( isset($chavepesquisa) ) {

  echo "  <script>                                                                                                    "; 
  echo "  function js_db_libera() {                                                                                   "; 
  echo "    parent.document.formaba.multas.disabled = false;                                                          "; 
  echo "    parent.iframe_multas.location.href      = 'cai1_recejmmulta001.php?k140_tabrecjm=$k02_codjm&db_opcao=3'; "; 
  if ( isset($liberaaba) ) {                                                                             
    echo "  parent.mo_camada('multas');                                                                               ";
  }                                                                                                                  
  echo "}                                                                                                             "; 
  echo "js_db_libera();                                                                                               ";       
  echo "</script>                                                                                                     ";  
}

if($db_opcao == 33){
  echo "<script>document.form1.pesquisar.click();</script>";
}

if ($cltabrecjm->erro_status == 0) {
  $cltabrecjm->erro(true, false);
} else {
  $cltabrecjm->erro(true, true);
}
?>