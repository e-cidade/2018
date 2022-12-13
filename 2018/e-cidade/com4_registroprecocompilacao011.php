<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_app.utils.php");
require_once ("std/db_stdClass.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("libs/db_liborcamento.php");
require_once ("dbforms/db_funcoes.php");
require_once ("classes/db_solicita_classe.php");
require_once ("classes/db_solicitem_classe.php");
require_once ("classes/db_solicitemele_classe.php");
require_once ("classes/db_solicitemunid_classe.php");
require_once ("classes/db_solicitempcmater_classe.php");
require_once ("classes/db_pcdotac_classe.php");
require_once ("classes/db_pcdotaccontrapartida_classe.php");
require_once ("classes/db_solicitatipo_classe.php");
require_once ("classes/db_orcreserva_classe.php");
require_once ("classes/db_orcreservasol_classe.php");
require_once ("classes/db_db_config_classe.php");
require_once ("classes/db_pctipocompra_classe.php");
require_once ("classes/db_db_depart_classe.php");
require_once ("classes/db_pcsugforn_classe.php");
require_once ("classes/db_pcparam_classe.php");
require_once ("classes/db_protprocesso_classe.php");
require_once ("classes/db_solicitemprot_classe.php");
require_once ("classes/db_pcproc_classe.php");
require_once ("classes/db_liclicitem_classe.php");
require_once ("classes/db_pactovalormov_classe.php");
require_once ("classes/db_pactovalormovsolicitem_classe.php");
require_once ("classes/db_orctiporecconveniosolicita_classe.php");

$oGet = db_utils::postmemory($_GET);
db_postmemory($HTTP_POST_VARS);

$clsolicita                  = new cl_solicita;
$clsolicitem                 = new cl_solicitem;
$clsolicitemele              = new cl_solicitemele;
$clsolicitemunid             = new cl_solicitemunid;
$clsolicitempcmater          = new cl_solicitempcmater;
$clpcdotac                   = new cl_pcdotac;
$clorcreserva                = new cl_orcreserva;
$clorcreservasol             = new cl_orcreservasol;
$clsolicitatipo              = new cl_solicitatipo;
$clpctipocompra              = new cl_pctipocompra;
$cldb_depart                 = new cl_db_depart;
$clpcsugforn                 = new cl_pcsugforn;
$clpcparam                   = new cl_pcparam;
$cldb_config                 = new cl_db_config;
$clprotprocesso              = new cl_protprocesso;
$clsolicitemprot             = new cl_solicitemprot;
$clpcproc                    = new cl_pcproc;
$clliclicitem                = new cl_liclicitem;
$oDaoContrapartida           = new cl_pcdotaccontrapartida();
$oDaoItemPacto               = new cl_pactovalormovsolicitem();
$oDaoItemPactomov            = new cl_pactovalormov;
$oDaoOrctiporecConvenioPacto = new cl_orctiporecconveniosolicita();

$opselec   = 1;
$db_opcao  = 1;
$db_botao  = true;
$departusu = true;
$confirma  = false;
unset($_SESSION["oAbertura"]);
unset($_SESSION["oSolicita"]);
$lBtnShowBtnConsulta = false;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<?
db_app::load("scripts.js, strings.js, prototype.js");
?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<?
 include ("forms/db_frmsolicitacompilacaoregistropreco.php");
?>
</body>
</html>