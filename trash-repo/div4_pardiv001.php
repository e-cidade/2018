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
include("classes/db_pardiv_classe.php");
include("dbforms/db_funcoes.php");

db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);

$clpardiv = new cl_pardiv;
$db_botao = true;

if(isset($incluir)){
	
	db_inicio_transacao();
  
	$clpardiv->incluir($v04_instit);
  
  db_fim_transacao();
	
} 

if(isset($alterar)){

	db_inicio_transacao();
   
  $clpardiv->alterar($v04_instit);
  
  db_fim_transacao();
  
} 

  $v04_instit = db_getsession('DB_instit');  
	
	$db_opcao = 2;
	$campos = "*, tipocert.k00_descr as descrTipoCertidao, tipoini.k00_descr as descrTipoInicial";
	$sSqlParDiv = $clpardiv->sql_query_param(db_getsession("DB_instit"),$campos);
	$rsPardiv   = $clpardiv->sql_record($sSqlParDiv);
	
	if($clpardiv->numrows>0){
		db_fieldsmemory($rsPardiv,0);
	} else {
		$db_opcao = 1;
		$descrtipocertidao = '';
		$descrtipoinicial  = '';
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
<body bgcolor=#CCCCCC onLoad="a=1" >
<form class="container" name="form1" method="post" action="">


      <?
        include("forms/db_frmpardiv.php");
      ?>

</form>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if( isset($alterar) || isset($incluir) ){
    $clpardiv->erro(true,false);
}
?>