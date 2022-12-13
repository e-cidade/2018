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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("classes/db_caiparametro_classe.php"));
require_once(modification("dbforms/db_funcoes.php"));
db_postmemory($_SERVER);
db_postmemory($_POST);

$clcaiparametro             = new cl_caiparametro;
$db_opcao                   = 22;
$db_botao                   = false;
$k29_instit                 = db_getsession("DB_instit");
$clcaiparametro->k29_instit = db_getsession("DB_instit");

if(isset($alterar)){

  db_inicio_transacao();

  $sSql   = $clcaiparametro->sql_query(db_getsession("DB_instit"));
  $result = $clcaiparametro->sql_record($sSql);

  if ($result == false || $clcaiparametro->numrows == 0) {
    $clcaiparametro->incluir($k29_instit);
  } else {
    $clcaiparametro->alterar($k29_instit);
  }

  $tipo_transmissao = $_POST['tipo_transmissao'];
  $sConvenioBanco   = $_POST['convenio_banco'];

  $oParametrosCaixa = new ParametroCaixa();
  $oParametrosCaixa->setConvenioBanco($sConvenioBanco);
  $oParametrosCaixa->setTipoTramissaoPadrao($tipo_transmissao);
  $oParametrosCaixa->salvar();

  db_fim_transacao();
}

$db_opcao = 2;
$result   = $clcaiparametro->sql_record($clcaiparametro->sql_query(db_getsession("DB_instit")));
if($result != false && $clcaiparametro->numrows > 0 ) {
  db_fieldsmemory($result,0);
}
$db_botao = true;
?>
  <html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body style="background-color: #CCCCCC;" >
  <div class="container">
    <?php
    require_once(modification("forms/db_frmcaiparametro.php"));
    ?>
  </div>

  <?php
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
  ?>
  </body>
  </html>
<?
if (isset($alterar)) {

  if ($clcaiparametro->erro_status == "0" ) {

    $clcaiparametro->erro(true,false);
    $db_botao = true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clcaiparametro->erro_campo!=""){
      echo "<script> document.form1.".$clcaiparametro->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clcaiparametro->erro_campo.".focus();</script>";
    }
  }else{
    $clcaiparametro->erro(true,true);
  }
}
if($db_opcao == 22){
  echo "<script>document.form1.pesquisar.click();</script>";
}
if (isset($k29_orctiporecfundeb) && $k29_orctiporecfundeb != null) {

  echo "<script>js_pesquisaRecurso(false);</script>";
}

?>