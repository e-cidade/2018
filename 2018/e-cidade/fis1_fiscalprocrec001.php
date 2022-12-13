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
require_once("libs/db_usuariosonline.php");
require_once("classes/db_fiscalprocrec_classe.php");
require_once("dbforms/db_funcoes.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clfiscalprocrec = new cl_fiscalprocrec;
$db_opcao        = 1;
$db_botao        = true;

$sSql            = $clfiscalprocrec->sql_query($y45_codtipo);
$rsFiscalProcRec = $clfiscalprocrec->sql_record($sSql);
$aFiscalProcRec  = db_utils::getCollectionByRecord($rsFiscalProcRec);

define("MENSAGENS", "tributario.fiscal.db_frmfiscalprocrec.");

global $y45_codtipo;
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Incluir"){

  try {

    db_inicio_transacao();

    if (!empty($aFiscalProcRec)) {
      throw new Exception( _M( MENSAGENS . 'erro_receita_existente' ) );
    }

    switch ($formacalculo) {
      case 1:
        $clfiscalprocrec->y45_vlrfixo    = "true";
        $clfiscalprocrec->y45_percentual = "false";
        break;

      case 2:
        $clfiscalprocrec->y45_vlrfixo    = "false";
        $clfiscalprocrec->y45_percentual = "false";
        $clfiscalprocrec->y45_valor      = "0";
        break;

      case 3:
        $clfiscalprocrec->y45_vlrfixo    = "true";
        $clfiscalprocrec->y45_percentual = "true";
        break;

      case 4:
        $clfiscalprocrec->y45_vlrfixo    = "false";
        $clfiscalprocrec->y45_percentual = "true";
        $clfiscalprocrec->y45_valor      = "0";
        break;

      default:
        break;
    }

    $clfiscalprocrec->incluir($y45_codtipo,$y45_receit);
  } catch (Exception $oErro){

    db_fim_transacao(true);
    echo "<script> alert('" . $oErro->getMessage() . "');</script>";
  }

  db_fim_transacao();
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body>
  <div class="container">
  	<?php
  	  include("forms/db_frmfiscalprocrec.php");
  	?>
  </div>
</body>
</html>
<?php
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Incluir"){

  if($clfiscalprocrec->erro_status=="0"){

    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clfiscalprocrec->erro_campo!=""){

      echo "<script> document.form1.".$clfiscalprocrec->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clfiscalprocrec->erro_campo.".focus();</script>";
    }
  }else{

    $clfiscalprocrec->erro(true,false);
    echo "<script>parent.iframe_fiscalprocrec.location.href='fis1_fiscalprocrec001.php?y45_codtipo=$y45_codtipo';</script>";
  }
}
?>