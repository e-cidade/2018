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

require ("libs/db_stdlib.php");
require("libs/db_utils.php");
require("libs/db_libdicionario.php");
require ("libs/db_conecta.php");
include ("libs/db_sessoes.php");
include ("libs/db_usuariosonline.php");
include ("dbforms/db_funcoes.php");
include ("classes/db_matmater_classe.php");
include ("classes/db_transmater_classe.php");
include ("classes/db_matmaterunisai_classe.php");
include ("classes/db_matmaterestoque_classe.php");
include ("classes/db_db_almox_classe.php");
require_once("classes/db_matmatermaterialestoquegrupo_classe.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clmatmater                     = new cl_matmater;
$cltransmater                   = new cl_transmater;
$clmatmaterunisai               = new cl_matmaterunisai;
$clmatmaterestoque              = new cl_matmaterestoque;
$cldb_almox                     = new cl_db_almox;
$clmatmatermaterialestoquegrupo = new cl_matmatermaterialestoquegrupo;

$db_botao = false;
$db_opcao = 33;
if (isset ($excluir)) {
	db_inicio_transacao();
	$sqlerro = false;
	$db_opcao = 3;
	$codigo = $m60_codmater;
	if ($sqlerro == false) {
		
		$clmatmatermaterialestoquegrupo->excluir(null, "m68_matmater = {$codigo}");
	  if ($clmatmatermaterialestoquegrupo->erro_status == 0) {
      $sqlerro = true;
      $erro_msg = $clmatmatermaterialestoquegrupo->erro_msg;
    }		
    
		$cltransmater->excluir(null,"m63_codmatmater=$codigo");
		if ($cltransmater->erro_status == 0) {
			$sqlerro = true;
			$erro_msg = $cltransmater->erro_msg;

		}
	}
	if ($sqlerro == false) {
		$clmatmaterunisai->excluir($codigo);
		if ($clmatmaterunisai->erro_status == 0) {
			$sqlerro = true;
			$erro_msg = $clmatmaterunisai->erro_msg;

		}
	}

  if ($sqlerro == false){
// Verifica departamento logado se eh deposito
       $res_db_almox = $cldb_almox->sql_record($cldb_almox->sql_query_file(null,"m91_codigo as m64_almox",null,"m91_depto = ".db_getsession("DB_coddepto"))); 
       if ($cldb_almox->numrows > 0){
            db_fieldsmemory($res_db_almox,0);
            $clmatmaterestoque->excluir(null,"m64_matmater = $m60_codmater and m64_almox = $m64_almox");
            if ($clmatmaterestoque->erro_status == 0){
                 $sqlerro  = true;
                 $erro_msg = $cl_matmaterestoque->erro_msg;
            }
       }
  }

	if ($sqlerro == false) {
		$clmatmater->excluir($m60_codmater);
		if ($clmatmater->erro_status == 0) {
			$sqlerro = true;
			$erro_msg = $clmatmater->erro_msg;

		}
	}
	
	db_fim_transacao($sqlerro);
} else
	if (isset ($chavepesquisa)) {
		$db_opcao = 3;

    $sSqlGrupo = $clmatmatermaterialestoquegrupo->sql_query(null, '*', null, "m68_matmater = {$chavepesquisa}");
    $rsGrupo   = $clmatmatermaterialestoquegrupo->sql_record($sSqlGrupo);    
	     if ($clmatmatermaterialestoquegrupo->numrows > 0){
        db_fieldsmemory($rsGrupo, 0);
     }		
		
		$result = $clmatmater->sql_record($clmatmater->sql_query($chavepesquisa));
		db_fieldsmemory($result, 0);
		$result_transmater = $cltransmater->sql_record($cltransmater->sql_query($chavepesquisa));
		if ($cltransmater->numrows>0){
		db_fieldsmemory($result_transmater, 0);
		}
		$db_botao = true;
		$result_unisai = $clmatmaterunisai->sql_record($clmatmaterunisai->sql_query($chavepesquisa, null, "m62_codmatunid,matunid.m61_descr as descr_uni"));
		if ($clmatmaterunisai->numrows > 0) {
			db_fieldsmemory($result_unisai, 0);
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
<table  border="0" cellpadding="0" cellspacing="0" bgcolor="#CCCCCC">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
	<?


include ("forms/db_frmmatmater.php");
?>
    </center>
<?
//db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
?>
</body>
</html>
<?


if (isset ($excluir)) {
	if ($clmatmater->erro_status == "0" || $sqlerro == true) {
	$clmatmater->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>";
    if($clmatmater->erro_campo!=""){
      echo "<script> document.form1.".$clmatmater->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clmatmater->erro_campo.".focus();</script>";
    };		
	} else {
		$clmatmater->erro(true, true);
		
	};
};
if ($db_opcao == 33) {
	echo "<script>document.form1.pesquisar.click();</script>";
}
?>