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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("classes/db_cgs_classe.php");
require_once("classes/db_cgs_und_classe.php");
require_once("classes/db_sau_fatorderisco_classe.php");
require_once("classes/db_cgsfatorderisco_classe.php");
require_once("dbforms/db_funcoes.php");

db_postmemory($HTTP_POST_VARS);

$oDaoCgsUnd          = new cl_cgs_und;
$oDaoSauFatorDeRisco = new cl_sau_fatorderisco;
$oDaoCgsFatorDeRisco = new cl_cgsfatorderisco;

$db_opcao            = 2;
$db_botao            = true;

if (isset($botao_ok) && $botao_ok == 'Ok') {

	db_inicio_transacao();
	$oDaoCgsUnd->z01_i_cgsund = $chavepesquisacgs;
	$oDaoCgsUnd->alterar($chavepesquisacgs);
	
	//Fatores de Risco
	if (isset($codigosfatores)) {

		$oDaoCgsFatorDeRisco->excluir(null, "s106_i_cgs = $chavepesquisacgs");
		$codigos = explode(',', $codigosfatores);

		$oDaoCgsFatorDeRisco->s106_i_cgs = $chavepesquisacgs;
		for ($x = 0; $x < count($codigos); $x++) {

			$oDaoCgsFatorDeRisco->s106_i_fatorderisco = $codigos[$x];
			$oDaoCgsFatorDeRisco->incluir(null);
      if ($oDaoCgsFatorDeRisco->erro_status == '0') {
        break;
      }

		}

	}

	db_fim_transacao($oDaoCgsFatorDeRisco->erro_status == '0' ? true : false);

} else if (isset($chavepesquisacgs) && !empty($chavepesquisacgs)) {

   $sSql = $oDaoCgsUnd->sql_query_file($chavepesquisacgs, 'z01_t_obs');
   $rs   = $oDaoCgsUnd->sql_record($sSql);
   db_fieldsmemory($rs, 0);

}

?>


<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style type="text/css">

.estiloBotaoSelect {

  border: 1px outset;
  border-top-color: #f3f3f3;
  border-left-color: #f3f3f3;
  background: #cccccc;
  font-size: 12px;
  font-weight: bold;
  width: 30px;
  height: 15px;
  padding: 0px;

}

</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="1" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="100%" align="left" valign="top" bgcolor="#CCCCCC">
    <center>
      <?
      require_once("forms/db_frmfatoresderisco.php");
      ?>
    </center>
    </td>
  </tr>
</table>
</body>
</html>
<script>
  js_tabulacaoforms("form1","z01_t_obs",true,1,"z01_t_obs",true);
</script>
<?
if (isset($botao_ok) && $botao_ok == "Ok") {

  if ($oDaoCgsUnd->erro_status=="0") {

    $oDaoCgsUnd->erro(true,false);
    $db_botao = true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";

    if ($oDaoCgsUnd->erro_campo != '') {

      echo "<script> document.form1.".$oDaoCgsUnd->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$oDaoCgsUnd->erro_campo.".focus();</script>";

    }

  } else {

	  if ($oDaoCgsFatorDeRisco->erro_status == '0') {

	    $oDaoCgsFatorDeRisco->erro(true, false);
	    $db_botao = true;

	    if ($oDaoCgsFatorDeRisco->erro_campo != '') {
	      echo "<script> document.form1.".$oDaoCgsFatorDeRisco->erro_campo.".style.backgroundColor='#99A9AE';</script>";
	    }

	  } else {

	    $oDaoCgsUnd->erro(true, false);
	    db_redireciona("sau4_consultamedica006.php?chavepesquisacgs=$chavepesquisacgs");

	  }

  }

}
?>