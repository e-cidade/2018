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
require_once("libs/db_utils.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_medicos_classe.php");
require_once("classes/db_cgm_classe.php");
require_once("dbforms/db_funcoes.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$oDaoMedicos            = new cl_medicos;
$oDaoCgm                = new cl_cgm;
$oDaoSauMedicosForaRede = db_utils::getdao('sau_medicosforarede');
$db_botao               = false;
$db_opcao               = 33;

if (isset($excluir)) {

  db_inicio_transacao();
/*
  // Excluo os registros da tabela sau_medicosforarede, caso exista
  $oDaoSauMedicosForaRede->excluir(null, ' s154_i_medico = '.$sd03_i_codigo);
*/
  $db_opcao = 3;
  $oDaoMedicos->excluir($sd03_i_codigo);
  db_fim_transacao($oDaoMedicos->erro_status == '0' ? true : false);

} elseif (isset($chavepesquisa)) {

  $db_opcao = 3;
  $sSql     = $oDaoMedicos->sql_query_cgm_fora_rede(null, '*', '', ' sd03_i_codigo = '.$chavepesquisa);
  $rs       = $oDaoMedicos->sql_record($sSql);
  db_fieldsmemory($rs, 0);
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
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/widgets/dbautocomplete.widget.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
      <br>
      <center>
        <?
        require_once("forms/db_frmmedicos.php");
        ?>
      </center>
    </td>
  </tr>
</table>
</body>
</html>
<?
if (isset($excluir)) {

  if ($oDaoMedicos->erro_status == '0') {
    $oDaoMedicos->erro(true, false);
  } else {
    $oDaoMedicos->erro(true, true);
  }

}
if ($db_opcao == 33) {
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
<script>
js_tabulacaoforms("form1", "excluir", true, 1, "excluir", true);
</script>