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
require_once(modification("classes/db_liccomissaocgm_classe.php"));
require_once(modification("classes/db_liccomissao_classe.php"));
require_once(modification("dbforms/db_funcoes.php"));


parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$oPOST = db_utils::postMemory($_POST);
$oGET = db_utils::postMemory($_GET);
$iCodigoParticipante = null;

$clliccomissaocgm = new cl_liccomissaocgm;
$clliccomissao    = new cl_liccomissao;

$lTemVinculo     = false;
$iCodigoGrupo    = null;
$l31_codigo      = isset($l31_codigo) ? $l31_codigo : null;
$l31_liccomissao = isset($l31_liccomissao) ? $l31_liccomissao : null;

$oDaoComissaoAtributosDinamico = new cl_liccomissaocgmcadattdinamicovalorgrupo;
$db_opcao = 22;
$db_botao = false;
$sqlerro  = false;

/**
 * Limpa os campos quando o botão "Novo" é utilizado
 */
if (isset($oPOST->novo) || isset($incluir)) {

  $l31_codigo            = null;
  $l31_numcgm            = null;
  $z01_nome              = null;
  $l31_tipo              = null;
  $cargo                 = null;
  $datadesignacao        = null;
  $numeroatodesignacao   = null;
  $anoatodesignacao      = null;
  $tipocargo             = null;
  $grupo_valor_atributos = null;
  $novo                  = null;
}

/**
 * Busca código do grupo de atributos dinâmicos a partir do código do registro
 */
$iCodigoParticipante = !empty($l31_codigo) ? $l31_codigo : null;
if ($iCodigoParticipante) {

  $sCampos                = 'l15_cadattdinamicovalorgrupo';
  $sWhereVinculoAtributos = "l15_liccomissaocgm = {$iCodigoParticipante}";
  $sSql                   = $oDaoComissaoAtributosDinamico->sql_query_file(null, $sCampos, null, $sWhereVinculoAtributos);
  $rsAtributoDinamico     = db_query($sSql);

  if ($rsAtributoDinamico && pg_num_rows($rsAtributoDinamico) > 0) {

    $iCodigoGrupo = db_utils::fieldsMemory($rsAtributoDinamico, 0)->l15_cadattdinamicovalorgrupo;
    $lTemVinculo  = true;
  }
}

db_inicio_transacao();

if (isset($incluir)) {

  if ($sqlerro == false) {

    $clliccomissaocgm->incluir(null);
    $erro_msg = $clliccomissaocgm->erro_msg;
    $l31_codigo = $clliccomissaocgm->l31_codigo;

    if ($clliccomissaocgm->erro_status == 0) {
      $sqlerro = true;
    }
  }

} else if(isset($alterar)) {

  if ( $sqlerro == false) {

    $clliccomissaocgm->alterar($l31_codigo);
    $erro_msg = $clliccomissaocgm->erro_msg;
    $l31_codigo = $clliccomissaocgm->l31_codigo;

    if ($clliccomissaocgm->erro_status == 0) {
      $sqlerro = true;
    }
  }

} else if(isset($excluir)) {

  if ($sqlerro == false) {

    if ($lTemVinculo) {

      $oDaoComissaoAtributosDinamico->excluir(null, $sWhereVinculoAtributos);
      $oAtributoDinamico = new DBAttDinamicoGrupo($iCodigoGrupo);
      $oAtributoDinamico->excluir();
    }

    $clliccomissaocgm->excluir($l31_codigo);
    $erro_msg = $clliccomissaocgm->erro_msg;

    if ($clliccomissaocgm->erro_status == 0) {
      $sqlerro=true;
    }
  }

} else if (isset($opcao)) {

  $sSql   = $clliccomissaocgm->sql_query(null,"*",null,"l31_codigo = $l31_codigo");
  $result = $clliccomissaocgm->sql_record($sSql);

  if($result != false && $clliccomissaocgm->numrows > 0){
    db_fieldsmemory($result,0);
  }
}

/**
 * Salva atributos dinâmicos e vínculo com o registro
 */
if (isset($incluir) || isset($alterar)) {

  try {

    $sDataDesignacao = null;
    if (!empty($oPOST->datadesignacao)) {

      $oDataDesignacao = new DBDate($oPOST->datadesignacao);
      $sDataDesignacao = $oDataDesignacao->getDate();
    }

    $oGrupoAtributoDinamico = new DBAttDinamicoGrupo($iCodigoGrupo);
    $oAtributoDinamico = new DBAttDinamico(DBAttDinamico::getCodigoPorArquivo(1325));
    $oGrupoAtributoDinamico->setAtributoDinamico($oAtributoDinamico);
    $oGrupoAtributoDinamico->setValor('cargo', $oPOST->cargo);
    $oGrupoAtributoDinamico->setValor('datadesignacao', $sDataDesignacao);
    $oGrupoAtributoDinamico->setValor('numeroatodesignacao', $oPOST->numeroatodesignacao);
    $oGrupoAtributoDinamico->setValor('anoatodesignacao', $oPOST->anoatodesignacao);
    $oGrupoAtributoDinamico->setValor('tipocargo', $oPOST->tipocargo);
    $iCodigoGrupo = $oGrupoAtributoDinamico->salvar();

    if (!$lTemVinculo) {

      $oDaoComissaoAtributosDinamico->l15_cadattdinamicovalorgrupo = $iCodigoGrupo;
      $oDaoComissaoAtributosDinamico->l15_liccomissaocgm           = $clliccomissaocgm->l31_codigo;
      $oDaoComissaoAtributosDinamico->incluir(null);

      if ($oDaoComissaoAtributosDinamico->erro_status == 0) {
        throw new DBException("Não foi possível salvar o registro");
      }
    }

  } catch (Exception $oException) {

    $erro_msg = $oException->getMessage();
    $sqlerro  = true;
  }
}

db_fim_transacao($sqlerro);
?>
<html>
<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/classes/DBAtributoDinamico.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class="body-default" >

  <div class="container">
    <?php include(modification("forms/db_frmliccomissaocgm.php")); ?>
  </div>

</body>
</html>
<?php
if (isset($alterar) || isset($excluir) || isset($incluir)) {

    db_msgbox($erro_msg);
    if ($clliccomissaocgm->erro_campo != "") {
        echo "<script> document.form1.".$clliccomissaocgm->erro_campo.".style.backgroundColor='#99A9AE';</script>";
        echo "<script> document.form1.".$clliccomissaocgm->erro_campo.".focus();</script>";
    }

    if ($sqlerro == false){
    	echo "<script>lRedireciona = true; location.href='lic1_liccomissaocgm001.php?l31_liccomissao={$l31_liccomissao}';</script>";
    }
}
