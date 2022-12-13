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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_utils.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_saltes_classe.php");
require_once("classes/db_saltescontrapartida_classe.php");
require_once("classes/db_saltesextra_classe.php");
require_once("dbforms/db_funcoes.php");

db_postmemory($_POST);
$oPost = db_utils::postMemory($_POST);

$clsaltes = new cl_saltes;
$db_opcao = 1;
$db_botao = true;

if ( isset($_POST["db_opcao"]) && $_POST["db_opcao"] == "Incluir" ) {

  $erro=false;
  db_inicio_transacao();

  $dtImplantacao = explode("/",$k13_dtimplantacao,3);
  $dtDia         = $dtImplantacao[0];
  $dtMes         = $dtImplantacao[1];
  $dtAno         = $dtImplantacao[2];
  $dtImplantacao = date('Y-m-d', mktime(0,0,0, $dtMes, $dtDia-1, $dtAno));

  $clsaltes->k13_dtimplantacao = $dtImplantacao;
  $clsaltes->incluir($k13_reduz);

  if ( $clsaltes->erro_status == 0 ) {
    $erro = true;
  }

  if ( !$erro && $k103_contrapartida != '' ) {

    $oDaosaltesContra                     = new cl_saltescontrapartida;
    $oDaosaltesContra->k103_contrapartida = $k103_contrapartida;
    $oDaosaltesContra->k103_saltes        = $k13_reduz;
    $oDaosaltesContra->incluir(null);

    if ( $oDaosaltesContra->erro_status == 0 ){

      $clsaltes->erro_status = 0;
      $clsaltes->erro_msg    = $oDaosaltesContra->erro_msg;
      $erro                  = true;

    }
  }
  if ( !$erro && $k109_saltesextra != '' ) {

    $oDaosaltesExtra                  = new cl_saltesextra;
    $oDaosaltesExtra->k109_contaextra = $k109_saltesextra;
    $oDaosaltesExtra->k109_saltes     = $k13_reduz;
    $oDaosaltesExtra->incluir(null);

    if ($oDaosaltesExtra->erro_status == 0){

      $clsaltes->erro_status = 0;
      $clsaltes->erro_msg    = $oDaosaltesExtra->erro_msg;
      $erro                  = true;

    }
  }
  /** [ExtensaoFiltroDespesa] Modificacao 1 */

  db_fim_transacao($erro);
}
?>
  <html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body style="background-color: #CCCCCC; margin-top: 30px;" >
  <div class="container">
    <?php
    require_once(Modification::getFile("forms/db_frmsaltes.php"));
    ?>
  </div>
  <?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
  ?>
  </body>
  </html>
<?
if(isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"]=="Incluir"){

  if ( $clsaltes->erro_status == "0" ) {

    $clsaltes->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";

    if($clsaltes->erro_campo!=""){

      echo "<script> document.form1.".$clsaltes->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clsaltes->erro_campo.".focus();</script>";
    };
  } else {

    $clsaltes->erro(true,true);
  };
};
?>