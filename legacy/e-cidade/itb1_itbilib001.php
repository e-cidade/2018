<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_utils.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("dbforms/db_classesgenericas.php"));
include(modification("classes/db_paritbi_classe.php"));

$oGet       = db_utils::postMemory($_GET);
$iAnoUsu    = db_getsession('DB_anousu');

$clcriaabas = new cl_criaabas;
$clparitbi  = new cl_paritbi;

$sParam          = 'paritbi.it24_alteraguialib';
$rsVerificaParam = $clparitbi->sql_record($clparitbi->sql_query($iAnoUsu,$sParam));

if ($clparitbi->numrows > 0) {

	$oParItbi = db_utils::fieldsMemory($rsVerificaParam,0);
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table valign="top" marginwidth="0" width="790" border="0" cellspacing="0" cellpadding="0">
  <tr>
     <td>
     <?
       $clcriaabas->identifica = array("datas"   =>"Datas",
                                       "dados"   =>"Dados do imovel",
                                       "transm"  =>"Transmitentes",
                                       "compnome"=>"Adquirentes",
                                       "constr"  =>"Benfeitorias");

       $clcriaabas->title      = array("datas"   =>"Datas",
                                       "dados"   =>"Dados do imovel",
                                       "transm"  =>"Transmitentes",
                                       "compnome"=>"Adquirentes",
                                       "constr"  =>"Benfeitorias");

       $aUrl = array();
       $aUrl[0] = "itb1_itbilib002.php?alteraguialib={$oParItbi->it24_alteraguialib}";
       $aUrl[1] = "itb1_itbilibdadosimovel002.php?alteraguialib={$oParItbi->it24_alteraguialib}";
       $aUrl[2] = "itb1_itbinome001.php?tiponome=t";
       $aUrl[3] = "itb1_itbinomecomp001.php?tiponome=c";
       $aUrl[4] = "itb1_itbiconstr001.php";

       $clcriaabas->src        = array("datas"    => $aUrl[0],
                                       "dados"    => $aUrl[1],
                                       "transm"   => $aUrl[2],
                                       "compnome" => $aUrl[3],
                                       "constr"   => $aUrl[4]);

       if (isset($oParItbi->it24_alteraguialib) && $oParItbi->it24_alteraguialib == 1) {

	       $clcriaabas->disabled   = array("datas"    => "false",
	                                       "dados"    => "true",
	                                       "transm"   => "true",
	                                       "compnome" => "true",
	                                       "constr"   => "true");
       } else if (isset($oParItbi->it24_alteraguialib) && $oParItbi->it24_alteraguialib == 2) {

         $clcriaabas->disabled   = array("datas"=>"true",
                                         "dados"=>"false",
                                         "transm"=>"true",
                                         "compnome"=>"true",
                                         "constr"=>"true");

       } else if (isset($oParItbi->it24_alteraguialib) && $oParItbi->it24_alteraguialib == 3) {

         $clcriaabas->disabled   = array("datas"=>"false",
                                         "dados"=>"false",
                                         "transm"=>"true",
                                         "compnome"=>"true",
                                         "constr"=>"true");
       }

       $clcriaabas->cria_abas();
     ?>
     </td>
  </tr>
<tr>
</tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
  document.formaba.datas.size    = 20;
  document.formaba.dados.size    = 20;
  document.formaba.compnome.size = 20;
  document.formaba.inter.size    = 20;
  document.formaba.transm.size   = 20;
  document.formaba.constr.size   = 20;
</script>