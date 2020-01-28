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


require ("libs/db_stdlib.php");
require ("libs/db_conecta.php");
include ("libs/db_sessoes.php");
include ("libs/db_usuariosonline.php");
include ("dbforms/db_funcoes.php");
include ("classes/db_pcforne_classe.php");
include ("classes/db_pcfornecon_classe.php");
include ("classes/db_pcforneconpad_classe.php");
include ("classes/db_pcfornemov_classe.php");
include ("classes/db_pcfornecert_classe.php");
include ("classes/db_pcfornesubgrupo_classe.php");
$clpcforne = new cl_pcforne;
$clpcfornecon = new cl_pcfornecon;
$clpcforneconpad = new cl_pcforneconpad;
$clpcfornemov = new cl_pcfornemov;
$clpcfornecert = new cl_pcfornecert;
$clpcfornesubgrupo = new cl_pcfornesubgrupo;
db_postmemory($HTTP_POST_VARS);
$db_opcao = 33;
$db_botao = false;
if (isset ($excluir)) {
	$sqlerro = false;
	db_inicio_transacao();
	$result = $clpcfornecon->sql_record($clpcfornecon->sql_query_file(null, "pc63_contabanco as contabco", "pc63_contabanco", "pc63_numcgm = $pc60_numcgm"));
	if ($result != 0 && $clpcfornecon->numrows) {
		for ($i = 0; $i < $clpcfornecon->numrows; $i ++) {
			db_fieldsmemory($result, $i);
			$clpcforneconpad->excluir($contabco);

		}
	}
	$clpcfornecon->excluir(null, "pc63_numcgm = $pc60_numcgm");
	if ($clpcfornecon->erro_status == "0") {
		$sqlerro = true;
		$erro_msg = $clpcfornecon->erro_msg;
	} else {
		$erro_msg = $clpcfornecon->erro_msg;
		$clpcfornemov->excluir(null, "pc62_numcgm = $pc60_numcgm");

		if ($clpcfornemov->erro_status == "0") {
			$erro_msg = $clpcfornemov->erro_msg;
			$sqlerro = true;
		} else {
			$erro_msg = $clpcfornesubgrupo->erro_msg;
			$clpcfornesubgrupo->excluir(null, "pc76_pcforne = $pc60_numcgm");
			if ($clpcfornesubgrupo->erro_status == "0") {
				$erro_msg = $clpcfornesubgrupo->erro_msg;
				$sqlerro = true;
			} else {
				$erro_msg = $clpcfornemov->erro_msg;
				$clpcforne->excluir($pc60_numcgm);
				if ($clpcforne->erro_status == "0") {
					$sqlerro = true;

				}
				$erro_msg = $clpcforne->erro_msg;
			}
		}
		db_fim_transacao($sqlerro);
		$db_opcao = 3;
		$db_botao = true;
	}
} else
	if (isset ($chavepesquisa)) {
		$db_opcao = 3;
		$db_botao = true;
		$result = $clpcforne->sql_record($clpcforne->sql_query($chavepesquisa));
		db_fieldsmemory($result, 0);
		$permissao=db_permissaomenu(db_getsession("DB_anousu"),28,5002);
     	if ($permissao=='false'){
     		$result_conta = $clpcfornecon->sql_record($clpcfornecon->sql_query_file(null, "*",null, "pc63_numcgm = $chavepesquisa"));
     		if ($clpcfornecon->numrows>0){
     			$db_botao = false;
     			db_msgbox("Fornecedor com Conta Banco cadastrada!!Sem permissão para exclusão!!");
     		}
     	}
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
<br /> 
    <center>
	  <?
      include ("forms/db_frmpcforne.php");
    ?>
    </center>
</body>
</html>
<?



if (isset ($excluir)) {
	if ($sqlerro == true) {
		db_msgbox($erro_msg);
		if ($clpcforne->erro_campo != "") {
			echo "<script> document.form1.".$clpcforne->erro_campo.".style.backgroundColor='#99A9AE';</script>";
			echo "<script> document.form1.".$clpcforne->erro_campo.".focus();</script>";
		};
	} else {
		db_msgbox($erro_msg);
		echo "
		  <script>
		    function js_db_tranca(){
		      parent.location.href='com1_pcforne003.php';
		    }\n
		    js_db_tranca();
		  </script>\n
		 ";
	}
}
if (isset ($chavepesquisa)) {
	echo "
  <script>
      function js_db_libera(){
         parent.document.formaba.pcfornecon.disabled=false;
         top.corpo.iframe_pcfornecon.location.href='com1_pcfornecon001.php?db_opcaoal=33&pc63_numcgm=".@ $pc60_numcgm."';
         parent.document.formaba.pcfornemov.disabled=false;
         top.corpo.iframe_pcfornemov.location.href='com1_pcfornemov001.php?db_opcaoal=33&pc62_numcgm=".@ $pc60_numcgm."';
         parent.document.formaba.pcfornecert.disabled=false;
         top.corpo.iframe_pcfornecert.location.href='com1_pcfornecert001.php?db_opcaoal=33&pc61_numcgm=".@ $pc60_numcgm."';
         parent.document.formaba.subgrupo.disabled=false;
         top.corpo.iframe_subgrupo.location.href='com1_pcfornesub001.php?db_opcao=3&pc76_pcforne=".@ $pc60_numcgm."';
         parent.document.formaba.pcfornereprlegal.disabled=false;
         top.corpo.iframe_pcfornereprlegal.location.href='com1_pcfornereprlegal001.php?db_opcaoal=3&pc81_cgmforn=".@ $pc60_numcgm."';
         top.corpo.iframe_pcforneidentificacaocredor.location.href='com1_pcfornetipoidentificacaocredorgenerica001.php?pc81_cgmforn=".@$pc60_numcgm."';
         parent.document.formaba.pcforneidentificacaocredor.disabled=false;
     ";
	if (isset ($liberaaba)) {
		echo "  parent.mo_camada('pcfornecon');";
	}
	echo "}\n
    js_db_libera();
  </script>\n
 ";
}
if ($db_opcao == 22 || $db_opcao == 33) {
	echo "<script>document.form1.pesquisar.click();</script>";
}
?>