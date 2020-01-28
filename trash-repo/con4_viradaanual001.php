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


require ("libs/db_stdlib.php");
require ("libs/db_conecta.php");
include ("libs/db_sessoes.php");
include ("libs/db_usuariosonline.php");
include ("libs/db_liborcamento.php");
include ("libs/db_libcontabilidade.php");
include ("dbforms/db_funcoes.php");
include ("classes/db_conplanoexe_classe.php");

db_postmemory($HTTP_POST_VARS);

$clconplanoexe = new cl_conplanoexe;

$erro = false;
$erro_msg = '';

if (isset ($reimporta) || isset ($zeraimporta)) {

	$result = db_planocontassaldo_matriz((db_getsession("DB_anousu") - 1), (db_getsession("DB_anousu") - 1).'-01-01', (db_getsession("DB_anousu") - 1).'-12-31', false, ' c61_instit = '.db_getsession("DB_instit"), '', false);

	$numrows = pg_numrows($result);
	//db_criatabela($result);exit;

	if ($numrows > 0) {

		db_inicio_transacao();

		for ($i = 0; $i < $numrows; $i ++) {
			db_fieldsmemory($result, $i);
			$clconplanoexe->c62_anousu = db_getsession("DB_anousu");
			$clconplanoexe->c62_reduz = $c61_reduz;
			$clconplanoexe->c62_codrec = $c61_codigo;
			if (!isset ($zeraimporta)) {
				if ($sinal_final == 'C') {
					$clconplanoexe->c62_vlrcre = $saldo_final;
					$clconplanoexe->c62_vlrdeb = "0";
				} else
					if ($sinal_final == 'D') {
						$clconplanoexe->c62_vlrdeb = $saldo_final;
						$clconplanoexe->c62_vlrcre = "0";
					} else {
						$clconplanoexe->c62_vlrcre = "0";
						$clconplanoexe->c62_vlrdeb = "0";
					}
			} else {
				$clconplanoexe->c62_vlrcre = "0";
				$clconplanoexe->c62_vlrdeb = "0";
			}
			$clconplanoexe->alterar(db_getsession("DB_anousu"), $c61_reduz);

			if ($clconplanoexe->erro_status == "0") {
				$erro = true;
				$erro_msg = $clconplanoexe->erro_msg;
				break;
			}

		}
		if ($erro == false)
			$erro_msg = $clconplanoexe->erro_msg;
		db_fim_transacao($erro);

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
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<form name="form1" method="post">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td> 
    <center>
    <table>
    <tr>
    <?


echo "<td><br><br><strong>Importar saldos do exercício anterior: (". (db_getsession("DB_anousu") - 1).")</strong></td><tr><td>";
echo '<input name="reimporta" value="Reprocessa saldo Contábil" type="submit">';

echo "&nbsp&nbsp&nbsp".'<input name="zeraimporta" value="Zera saldo Contábil" type="submit">';
?>
    </td>
    </tr>
    </table>
    </center>
    </td>
  </tr>
</table>
</form>
<?


db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
?>
</body>
</html>
<?


if (isset ($zeraimporta) || isset ($reimporta))
	db_msgbox($erro_msg);