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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("classes/db_tipcalcexe_classe.php");
require_once("classes/db_tipcalc_classe.php");
require_once("dbforms/db_funcoes.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$cltipcalcexe = new cl_tipcalcexe;
$cltipcalc    = new cl_tipcalc;
$db_opcao     = 22;
$db_botao     = false;

if (isset($alterar) || isset($excluir) || isset($incluir)) {
  
  $sqlerro = false;
  
}

if (isset($incluir)) {
  
  if ($sqlerro == false) {
    
    db_inicio_transacao();

    if ( ($q83_cadvencdescsimples == null) || ($q83_cadvencdescsimples == "") ) {
    
    	$cltipcalcexe->q83_cadvencdescsimples = "null";
    }    
    
    $cltipcalcexe->incluir(null);
    $erro_msg = $cltipcalcexe->erro_msg;
    if ($cltipcalcexe->erro_status == 0) {
      $sqlerro = true;
    }
    
    if ($sqlerro == false) {
      
    	if ($q83_anousu == db_getsession("DB_anousu")){
    	  
    	  $cltipcalc->q81_codigo = $q83_tipcalc;
    	  $cltipcalc->alterar($q83_tipcalc);
  			if($cltipcalc->erro_status==0){
    			$sqlerro=true;
  			} 
    	}
    }
    
    db_fim_transacao($sqlerro);
  }
  
} else if (isset($alterar)) {
  
  if ($sqlerro == false) {
    
    db_inicio_transacao();
    
    if ( ($q83_cadvencdescsimples == null) || ($q83_cadvencdescsimples == "") ) {
    
    	$cltipcalcexe->q83_cadvencdescsimples = "null";
    }    
    
    $cltipcalcexe->alterar($q83_codigo);
    $erro_msg = $cltipcalcexe->erro_msg;
    if($cltipcalcexe->erro_status == 0){
      $sqlerro = true;
    }
    if ($sqlerro == false) {
      
    	if ($q83_anousu == db_getsession("DB_anousu")) {
    	  
    		$cltipcalc->q81_codigo = $q83_tipcalc;
    		$cltipcalc->alterar($q83_tipcalc);
  			if($cltipcalc->erro_status==0){
    			$sqlerro=true;
  			} 
    	}
    }
    db_fim_transacao($sqlerro);
  }
  
} else if (isset($excluir)){
  
  if ($sqlerro == false) {
    
    db_inicio_transacao();
    $cltipcalcexe->excluir($q83_codigo);
    $erro_msg = $cltipcalcexe->erro_msg;
    if ($cltipcalcexe->erro_status == 0) {
      $sqlerro = true;
    }
    db_fim_transacao($sqlerro);
  }
  
} else if (isset($opcao)) {
  
   $result = $cltipcalcexe->sql_record($cltipcalcexe->sql_query_venc($q83_codigo));
   if ($result != false && $cltipcalcexe->numrows > 0) {
     db_fieldsmemory($result, 0);
   }
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

    <center>
    	<?
    	  require_once("forms/db_frmtipcalcexe.php");
    	?>
    </center>

</body>
</html>
<?
if (isset($alterar) || isset($excluir) || isset($incluir)) {
  
    db_msgbox($erro_msg);
    if ($sqlerro == false) {
      
    	if (isset($alterar) || isset($incluir)) {
    	  
    	 echo "
          		<script>      	
                top.corpo.iframe_tipcalc.location.href='iss1_tipcalc005.php?chavepesquisa=".@$q83_tipcalc."';
              </script>
            ";
    	}
    }
}

?>