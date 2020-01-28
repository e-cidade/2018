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
require_once("classes/db_parprojetos_classe.php");
require_once("dbforms/db_funcoes.php");
$oPost         = db_utils::postmemory($HTTP_POST_VARS);
$clparprojetos = new cl_parprojetos;
$db_opcao      = 22;
$db_botao      = false;
$iAnoUsu       = isset($oPost->ob21_anousu) ? $oPost->ob21_anousu : db_getsession("DB_anousu");
$rsParProjetos = $clparprojetos->sql_record($clparprojetos->sql_query_pesquisaParametros($iAnoUsu));

if(isset($oPost->alterar) || isset($oPost->incluir)){

	db_inicio_transacao();

	if ( $clparprojetos->numrows > 0 ) {
		$clparprojetos->alterar($oPost->ob21_anousu);
	}else{
		$clparprojetos->incluir($oPost->ob21_anousu);
	}

	$db_opcao = 2;
	db_fim_transacao();

}

if ( pg_num_rows($rsParProjetos) > 0 ) {

	$oParProjetos = db_utils::fieldsMemory($rsParProjetos,0);

	$ob21_anousu					    = $oParProjetos->ob21_anousu;
	$ob21_numeracaohabite     = $oParProjetos->ob21_numeracaohabite;
	$ob21_ultnumerohabite     = $oParProjetos->ob21_ultnumerohabite;
	$ultnumerohabite			    = $oParProjetos->ob21_ultnumerohabite;
	$ob21_grupotipoocupacao   = $oParProjetos->ob21_grupotipoocupacao;
	$ob21_grupotipoconstrucao = $oParProjetos->ob21_grupotipoconstrucao;
	$ob21_grupotipolancamento = $oParProjetos->ob21_grupotipolancamento;
	$ob21_tipocartaalvara     = $oParProjetos->ob21_tipocartaalvara;
	$ob21_tipocartahabite     = $oParProjetos->ob21_tipocartahabite;
	$descr_tipoocupacao       = $oParProjetos->ocupacao_descricao;
	$descr_tipoconstrucao     = $oParProjetos->construcao_descricao;
  $descr_tipolancamento     = $oParProjetos->lancamento_descricao;
  
	if ($oParProjetos->ob21_numeracaohabite == 1){
		$db_opcaoNumero	= 3;
	}else{
		$db_opcaoNumero = 1;
	}

	$db_opcao = 2;

}else{
	$db_opcao = 1;
	$db_opcaoNumero = 1;
}

$db_botao = true;

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC>
				<?
					include("forms/db_frmparprojetos.php");
				?>
	<?
	db_menu(db_getsession("DB_id_usuario"),
	        db_getsession("DB_modulo"),
	        db_getsession("DB_anousu"),
	        db_getsession("DB_instit")
	       );
	?>
</body>
</html>
<?
if(isset($alterar)){
  if($clparprojetos->erro_status=="0"){
    $clparprojetos->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clparprojetos->erro_campo!=""){
      echo "<script> document.form1.".$clparprojetos->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clparprojetos->erro_campo.".focus();</script>";
    }
  }else{
    $clparprojetos->erro(true,true);
  }
}
?>