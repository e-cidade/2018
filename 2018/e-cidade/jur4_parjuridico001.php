<?php
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_utils.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_parjuridico_classe.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_app.utils.php");

$oPost              = db_utils::postMemory($_POST);

db_postmemory($_POST);
db_postmemory($_GET);

$clparjuridico      = new cl_parjuridico;
$db_opcao           = 2;
$lErro              = false;
$tipoInicialQuitada = 0;
$tipoParcelamento   = 0;

if ( isset($alterar) ) {

  db_inicio_transacao();
  $db_opcao = 2;
  $clparjuridico->v19_envolinicialiptu        = $oPost->v19_envolinicialiptu;
  $clparjuridico->v19_envolinicialiss         = $oPost->v19_envolinicialiss;
  $clparjuridico->v19_envolprinciptu          = $oPost->v19_envolprinciptu;
  $clparjuridico->v19_vlrexecmin              = $oPost->v19_vlrexecmin;
  $clparjuridico->v19_partilha                = $oPost->v19_partilha;
  $clparjuridico->v19_templateinicialquitada  = $oPost->tipoInicialQuitada == 1 ? $oPost->v19_templateinicialquitada : '';
  $clparjuridico->v19_templateparcelamento    = $oPost->tipoParcelamento   == 1 ? $oPost->v19_templateparcelamento   : '';
  $clparjuridico->v19_urlwebservice           = $oPost->v19_urlwebservice;
  $clparjuridico->v19_login                   = $oPost->v19_login;
  $clparjuridico->v19_senha                   = $oPost->v19_senha;
  $clparjuridico->v19_codorgao                = $oPost->v19_codorgao;

  $clparjuridico->alterar_camposNulos($v19_anousu, $v19_instit);

  if ( $clparjuridico->erro_status == "0" ) {
    $lErro = true;
  }
  db_fim_transacao($lErro);

} else if( isset($incluir) ) {

  db_inicio_transacao();
  $clparjuridico->v19_envolinicialiptu        = $oPost->v19_envolinicialiptu;
  $clparjuridico->v19_envolinicialiss         = $oPost->v19_envolinicialiss;
  $clparjuridico->v19_envolprinciptu          = $oPost->v19_envolprinciptu;
  $clparjuridico->v19_vlrexecmin              = $oPost->v19_vlrexecmin;
  $clparjuridico->v19_partilha                = $oPost->v19_partilha;
  $clparjuridico->v19_templateinicialquitada  = $oPost->tipoInicialQuitada == 1 ? $oPost->v19_templateinicialquitada : '';
  $clparjuridico->v19_templateparcelamento    = $oPost->tipoParcelamento   == 1 ? $oPost->v19_templateparcelamento   : '';
  $clparjuridico->v19_urlwebservice           = $oPost->v19_urlwebservice;
  $clparjuridico->v19_login                   = $oPost->v19_login;
  $clparjuridico->v19_senha                   = $oPost->v19_senha;
  $clparjuridico->v19_codorgao                = $oPost->v19_codorgao;
  $clparjuridico->incluir(db_getsession('DB_anousu'),db_getsession('DB_instit'));

  if ($clparjuridico->erro_status == "0") {
    $lErro = true;
  }

  db_fim_transacao($lErro);
}else{

  $sCampos = "*, b.db82_descricao as db82_descricao_parcelamento, a.db82_descricao as db82_descricao_inicialquitada";
  $sSql = $clparjuridico->sql_query_alternativo(db_getsession('DB_anousu'),
                                                db_getsession('DB_instit'),
                                                $sCampos);

  $result   = $clparjuridico->sql_record($sSql);

  if ($clparjuridico->numrows > 0) {

    db_fieldsmemory($result, 0);
    $tipoInicialQuitada = empty($db82_descricao_inicialquitada) ? 0 : 1;
    $tipoParcelamento   = empty($db82_descricao_parcelamento)   ? 0 : 1;

  } else {

    $db_opcao   = 1;
    $v19_instit = db_getsession('DB_instit');
    $v19_anousu = db_getsession('DB_anousu');
  }
}

?>
<html>
<head>
<?php
  db_app::load("scripts.js, prototype.js, strings.js, estilos.css");
?>
</head>

<body class="body-default">
  <div class="container">
    <?php
      include("forms/db_frmparjuridico.php");
      db_menu(db_getsession("DB_id_usuario"),
              db_getsession("DB_modulo"),
              db_getsession("DB_anousu"),
              db_getsession("DB_instit"));
    ?>
  </div>
</body>
</html>

<?php

if(isset($alterar) || isset($incluir)){

  if($clparjuridico->erro_status=="0"){

    $clparjuridico->erro(true,false);
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";

    if($clparjuridico->erro_campo!=""){

      echo "<script> document.form1.".$clparjuridico->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clparjuridico->erro_campo.".focus();</script>";
    }

  } else {

    $clparjuridico->erro(true,true);
  }
}
?>