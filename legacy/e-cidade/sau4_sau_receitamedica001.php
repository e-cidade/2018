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
require_once("classes/db_sau_receitamedica_classe.php");
require_once("dbforms/db_funcoes.php");

db_postmemory($HTTP_POST_VARS);
$oDaoSauReceitaMedica       = db_utils::getdao('sau_receitamedica');
$oDaoFarTipoReceita         = db_utils::getdao('far_tiporeceita');
$oDaoSauFormaAdmMedicamento = db_utils::getdao('sau_formaadmmedicamento');
$oDaoDbDocumentoTemplate    = db_utils::getdao('db_documentotemplate');

$db_opcao    = 1;
$db_botao    = true;
$lImpedirAlt = false;

if (isset($chavepesquisa)) {

 $sSql = $oDaoSauReceitaMedica->sql_query_prontuario($chavepesquisa);
 $rs   = $oDaoSauReceitaMedica->sql_record($sSql);
 if ($oDaoSauReceitaMedica->numrows > 0) {

   db_fieldsmemory($rs, 0);
   $db_opcao    = 2;
   $lImpedirAlt = $s158_i_situacao == 1 ? false : true; // Receitas atendidas ou anuladas não podem ser alteradas

 }

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
<script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/widgets/dbautocomplete.widget.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1">
<br>
<center>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
      <center>
        <fieldset style='width: 75%;'> <legend><b>Receita Médica</b></legend>
          <?
          require_once('forms/db_frmsau_receitamedica.php');
          ?>
        </fieldset>
      </center>
    </td>
  </tr>
</table>
</center>
<?
/*
db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"),
        db_getsession("DB_anousu"), db_getsession("DB_instit")
       );
*/
?>
</body>
</html>
<script>
js_tabulacaoforms("form1","s158_i_profissional",true,1,"s158_i_profissional",true);
</script>