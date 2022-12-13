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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("classes/db_lab_resultado_classe.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification('libs/db_utils.php'));

db_postmemory( $_POST );

$cllab_resultado = new cl_lab_resultado;
$db_opcao        = 1;
$db_botao        = true;

if ( isset( $incluir ) ) {

  db_inicio_transacao();
  $cllab_resultado->incluir($la52_i_codigo);
  db_fim_transacao();
}

/**
 * Função para descobrir o laboratorio que o usuario esta logado
 * @return integer Codigo do laboratorio logado
 */
function laboratorioLogado() {

  $iUsuario        = db_getsession('DB_id_usuario');
  $iDepto          = db_getsession('DB_coddepto');
  $oLab_labusuario = new cl_lab_labusuario();
  $oLab_labdepart  = new cl_lab_labdepart();
  $sWhere          = " la05_i_usuario = {$iUsuario}";
  $sql             = $oLab_labusuario->sql_query(null,'la02_i_codigo, la02_c_descr',"la02_i_codigo", $sWhere);
  $rResult         = $oLab_labusuario->sql_record($sql);

  if ( $oLab_labusuario->numrows == 0 ) {

    $sWhere  = " la03_i_departamento = {$iDepto}";
	  $sql     = $oLab_labdepart->sql_query(null,'la02_i_codigo, la02_c_descr',"la02_i_codigo", $sWhere );
	  $rResult = $oLab_labdepart->sql_record($sql);

    if ( $oLab_labdepart->numrows == 0 ) {
  	  return 0;
    }
  }

  $oLab = db_utils::getCollectionByRecord($rResult);
  return $oLab[0]->la02_i_codigo;
}

$iLaboratorioLogado = laboratorioLogado();

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?php

db_app::load("scripts.js");
db_app::load("prototype.js");
db_app::load("datagrid.widget.js");
db_app::load("strings.js");
db_app::load("grid.style.css");
db_app::load("estilos.css");
db_app::load("/widgets/dbautocomplete.widget.js");
db_app::load("webseller.js");

?>
<script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/widgets/windowAux.widget.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/widgets/dbmessageBoard.widget.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/widgets/DBTreeView.widget.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/classes/laboratorio/LancarMedicamentoExame.classe.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/classes/laboratorio/LancamentoExameLaboratorio.classe.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class="body-default">
  <?php
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
  ?>
  <?php if( $iLaboratorioLogado == 0 ) { ?>
    <div class="container">

      <br><br>
      <font color='#FF0000' face='arial'>
        <b>Usuário ou departamento não consta como laboratório!</b>
      </font>
      <br>
    </div>
  <?php

    exit;
    }
    require_once(modification("forms/db_frmlab_resultado.php"));
    ?>

</body>
</html>
<script>
  js_tabulacaoforms("form1","la22_i_codigo",true,1,"la22_i_codigo",true);
</script>
<?php
  if ( isset( $incluir ) ) {

    if ( $cllab_resultado->erro_status == "0" ) {

      $cllab_resultado->erro( true, false );
      $db_botao = true;
      echo "<script> document.form1.db_opcao.disabled=false;</script>  ";

      if ( $cllab_resultado->erro_campo != "" ) {

        echo "<script> document.form1.".$cllab_resultado->erro_campo.".style.backgroundColor='#99A9AE';</script>";
        echo "<script> document.form1.".$cllab_resultado->erro_campo.".focus();</script>";
      }

    } else {
      $cllab_resultado->erro( true, true );
    }
  }
?>