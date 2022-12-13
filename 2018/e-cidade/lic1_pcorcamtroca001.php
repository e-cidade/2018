<?php
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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_libsys.php"));
require_once(modification("dbforms/db_funcoes.php"));

require_once(modification("classes/db_pcorcam_classe.php"));
require_once(modification("classes/db_pcorcamjulg_classe.php"));
require_once(modification("classes/db_liclicita_classe.php"));
require_once(modification("classes/db_liclicitaata_classe.php"));
require_once(modification("classes/db_liclicitasituacao_classe.php"));
require_once(modification("classes/db_registroprecojulgamento_classe.php"));
require_once(modification("classes/db_atatemplategeral_classe.php"));

require_once(modification('dbagata/classes/core/AgataAPI.class'));
require_once(modification("model/documentoTemplate.model.php"));

require_once(modification("model/licitacao.model.php"));
require_once(modification("model/MaterialCompras.model.php"));

db_postmemory($HTTP_GET_VARS);
db_postmemory($HTTP_POST_VARS);

$oDaoLogJulgamento     = new cl_pcorcamjulgamentolog();
$oDaoLogJulgamentoItem = new cl_pcorcamjulgamentologitem();

$clrotulo              = new rotulocampo();
$clpcorcam             = new cl_pcorcam;
$clpcorcamjulg         = new cl_pcorcamjulg;
$clliclicita           = new cl_liclicita;
$clliclicitaata        = new cl_liclicitaata;
$clliclicitasituacao   = new cl_liclicitasituacao;
$oDaoRegistroPrecoJulg = new cl_registroprecojulgamento;
$clAtaTemplateGeral    = new cl_atatemplategeral;

$clpcorcam->rotulo->label();
$clrotulo->label("l20_codigo");

$erro_msg       = "";
$db_botao       = true;
$sqlerro        = false;
$lRegistroPreco = false;
$lListaModelos  = false;
$db_opcao       = 1;

if (isset($_SESSION["modeloataselecionadojulgamento"])) {
  $documentotemplateata = $_SESSION["modeloataselecionadojulgamento"];
}

if (isset($l20_codigo) && $l20_codigo) {

  $lListaModelos   = true;

  $sCamposModelos  = "db82_sequencial, ";
  $sCamposModelos .= "db82_descricao   ";

  $sSqlTemplateModalidade = $clliclicita->sql_query_modelosatas($l20_codigo, $sCamposModelos);
  $rsTemplateModalidade   = $clliclicita->sql_record($sSqlTemplateModalidade);

  if ( $clliclicita->numrows > 0 ) {
    $rsModelos = $rsTemplateModalidade;
  } else {

    $rsTemplateGeral = $clAtaTemplateGeral->sql_record($clAtaTemplateGeral->sql_query(null,$sCamposModelos));

    if ( $clAtaTemplateGeral->numrows > 0 ) {
      $rsModelos = $rsTemplateGeral;
    } else {
      $lListaModelos = false;
    }
  }

  $sSqlDadosLicitacao = $clliclicita->sql_query_file($l20_codigo);
  $rsDadosLicitacao   = $clliclicita->sql_record($sSqlDadosLicitacao);
  if ($clliclicita->numrows > 0) {

    $oDadosLicitacao  = db_utils::fieldsMemory($rsDadosLicitacao, 0);
    $lRegistroPreco   = $oDadosLicitacao->l20_usaregistropreco == 't' ? true : false;
    if ($oDadosLicitacao->l20_licsituacao != 0) {
      $db_opcao = 3;
    }
  }
}

if (isset($confirmar) && trim($confirmar) != "") {


  $vitens       = split(":",$itens);
  $vpcorcamjulg = array(array());
  $linha        = 0;

  $_SESSION["modeloataselecionadojulgamento"] = $documentotemplateata;

  for ($i = 0; $i < count($vitens); $i++) {

    $str   = $vitens[$i];
    $vetor = split(",",$str);

    /**
     * verificamos saldo modalidade para cada item da licitacao
     */

    $iModalidade  = $oDadosLicitacao->l20_codtipocom;
    $dtJulgamento = date("Y-m-d", db_getsession("DB_datausu"));
    $iItem        = trim($vetor[0]);

    if ($iItem != null) {
      try {

      $oVerificaSaldo = licitacao::verificaSaldoModalidade( $iModalidade, $iItem, $dtJulgamento);
      } catch ( Exception $oErro) {

        $erro_msg = $oErro->getMessage();
        $sqlerro  = true;
      }
    }

    if (!$sqlerro) {

      if (!$oVerificaSaldo->lPossuiSaldo && $iItem != null) {

        $sqlerro  = true;
        $erro_msg = $oVerificaSaldo->sMensagem;
      }
    }

    if (trim($vetor[0]) != "" && trim($vetor[1]) != "") {

      $vpcorcamjulg[$linha]["item"]  = $vetor[0];
      $vpcorcamjulg[$linha]["forne"] = $vetor[1];
      $linha++;
    }
  }

  if ($linha == 0) {

    $sqlerro  = true;
    $erro_msg = "Nenhum item a ser julgado.";
  }

  if (isset($documentotemplateata) && !empty($documentotemplateata)) {

    if ($sqlerro == false) {

      $clagata = new cl_dbagata("licitacao/atas.agt");
      $oApi    = $clagata->api;

      $iCasasDecimais     = 2;

      $aParametrosEmpenho = db_stdClass::getParametro('empparametro', array(db_getsession('DB_anousu')));

      if (count($aParametrosEmpenho) > 0) {
        $iCasasDecimais = $aParametrosEmpenho[0]->e30_numdec;
      }

      $sNomeArquivoSxw  = "ata_licitacao_{$l20_codigo}.sxw";
      $sCaminhoSalvoSxw = "tmp/{$sNomeArquivoSxw}";

      $oApi->setOutputPath($sCaminhoSalvoSxw);
      $oApi->setParameter('$licitacao', $l20_codigo);

      if ($sqlerro == false) {

        try {
          $oDocumentoTemplate = new documentoTemplate(5, $documentotemplateata);
        } catch (Exception $eException){

          $erro_msg = str_replace("\\n", "\n", $eException->getMessage());
          $sqlerro  = true;
        }

        $lProcessado = $oApi->parseOpenOffice($oDocumentoTemplate->getArquivoTemplate());

        if ($lProcessado) {

          db_inicio_transacao();

          $iOidGravaArquivo = pg_lo_create();
          $sStringArquivo   = file_get_contents($sCaminhoSalvoSxw);

          if ( !$sStringArquivo ) {
            $erro_msg = "Falha ao abrir o arquivo {$sCaminhoSalvoSxw}! Modelo de ata inicial não foi gerado.";
            $sqlerro  = true;
          }

          $oLargeObject = pg_lo_open($iOidGravaArquivo, "w");
          if (!$oLargeObject) {
            $erro_msg = "Falha ao buscar objeto do banco de dados! Modelo de ata inicial não foi gerado.";
            $sqlerro  = true;
          }

          $lObjetoEscrito = pg_lo_write($oLargeObject, $sStringArquivo);
          if (!$lObjetoEscrito) {
            $erro_msg = "Falha na escrita do objeto no banco de dados! Modelo de ata inicial não foi gerado.";
            $sqlerro  = true;
          }

          pg_lo_close($oLargeObject);

          $clliclicitaata->l39_arqnome        = $sNomeArquivoSxw;
          $clliclicitaata->l39_arquivo        = $iOidGravaArquivo;
          $clliclicitaata->l39_liclicita      = $l20_codigo;
          $clliclicitaata->l39_posicaoinicial = 'true';
          $clliclicitaata->incluir(null);
          if ($clliclicitaata->erro_status == 0) {

            $erro_msg = $clliclicitaata->erro_msg;
            $sqlerro  = true;
          }
        } else {

          $erro_msg = "Falha ao gerar modelo de ata inicial!";
          $sqlerro  = true;
        }
      }
      db_fim_transacao($sqlerro);
    }
  }

  if ($sqlerro == false) {

    db_inicio_transacao();

    $dtData  =  date("Y-m-d",db_getsession('DB_datausu'));
    $sHora   =  date("H:i");

    $oDaoLogJulgamento->pc92_sequencial      = null ;
    $oDaoLogJulgamento->pc92_usuario         = db_getsession('DB_id_usuario');
    $oDaoLogJulgamento->pc92_datajulgamento  = $dtData;
    $oDaoLogJulgamento->pc92_hora            = $sHora;
    $oDaoLogJulgamento->pc92_ativo           = "true";
    $oDaoLogJulgamento->incluir(null);

    if ($oDaoLogJulgamento->erro_status == 0) {

      $erro_msg = $oDaoLogJulgamento->erro_msg;
      $sqlerro  = true;
    }

    for ($x = 0; $x < $linha; $x++) {

      $pc24_orcamitem   = $vpcorcamjulg[$x]["item"];
      $pc24_orcamforne  = $vpcorcamjulg[$x]["forne"];

      $sWhereOrcamVal = "pc23_orcamitem = {$pc24_orcamitem} and pc23_orcamforne = {$pc24_orcamforne}";
      $oDaoOrcamVal   = new cl_pcorcamval();
      $sSqlOrcamVal   = $oDaoOrcamVal->sql_query_file(null, null, "pc23_vlrun", null, $sWhereOrcamVal);
      $rsOrcamVal     = $oDaoOrcamVal->sql_record($sSqlOrcamVal);

      $nValorUnitario = db_utils::fieldsMemory($rsOrcamVal, 0)->pc23_vlrun;

      if ($oDaoOrcamVal->numrows == 0) {

        $erro_msg = $oDaoOrcamVal->erro_msg;
        $sqlerro  = true;
        break;
      }

      $clpcorcamjulg->pc24_orcamitem  = $pc24_orcamitem;
      $clpcorcamjulg->pc24_orcamforne = $pc24_orcamforne;
      $clpcorcamjulg->pc24_pontuacao  = 1;

      $clpcorcamjulg->incluir($pc24_orcamitem,$pc24_orcamforne);
      if ($clpcorcamjulg->erro_status == 0){

        $erro_msg = $clpcorcamjulg->erro_msg;
        $sqlerro  = true;
        break;
      }

      if ($lRegistroPreco) {
        /**
         * buscamos o código do item do registro de preço , e o valor unitário para inserir em registroprecojulgamento
         */
        $sSqlDadosRegistroPreco  = "SELECT pc81_solicitem, ";
        $sSqlDadosRegistroPreco .= "       pc23_vlrun ";
        $sSqlDadosRegistroPreco .= "  from pcorcamitem ";
        $sSqlDadosRegistroPreco .= "       inner join pcorcamitemlic on pc26_orcamitem    = pc22_orcamitem ";
        $sSqlDadosRegistroPreco .= "       inner join liclicitem     on l21_codigo        = pc26_liclicitem ";
        $sSqlDadosRegistroPreco .= "       inner join pcprocitem     on l21_codpcprocitem = pc81_codprocitem ";
        $sSqlDadosRegistroPreco .= "       inner join pcorcamval     on pc23_orcamforne   = {$pc24_orcamforne} ";
        $sSqlDadosRegistroPreco .= "                                and pc23_orcamitem    = pc22_orcamitem";
        $sSqlDadosRegistroPreco .= "       inner join solicitem      on pc11_codigo       = pc81_solicitem   ";
        $sSqlDadosRegistroPreco .= " where pc23_orcamforne = {$pc24_orcamforne} ";
        $sSqlDadosRegistroPreco .= "   and pc23_orcamitem  = {$pc24_orcamitem} ";

        $rsRegistroPreco = db_query($sSqlDadosRegistroPreco);
        if (pg_num_rows($rsRegistroPreco) != 1) {

          $sqlerro  = true;
          $erro_msg = "Não existem registros de preços para esta solicitação.";
          break;
        }

        if ($sqlerro == false) {

          $oDadosRegistroPreco = db_utils::fieldsMemory($rsRegistroPreco, 0);
          $oDaoRegistroPrecoJulg->pc65_ativo         = 1;
          $oDaoRegistroPrecoJulg->pc65_orcamforne    = $pc24_orcamforne;
          $oDaoRegistroPrecoJulg->pc65_orcamitem     = $pc24_orcamitem;
          $oDaoRegistroPrecoJulg->pc65_solicitem     = $oDadosRegistroPreco->pc81_solicitem;
          $oDaoRegistroPrecoJulg->pc65_valorunitario = $oDadosRegistroPreco->pc23_vlrun;
          $oDaoRegistroPrecoJulg->pc65_pontuacao     = 1;
          $oDaoRegistroPrecoJulg->incluir(null);

          if ($oDaoRegistroPrecoJulg->erro_status == 0) {

            $erro_msg = $oDaoRegistroPrecoJulg->erro_msg;
            $sqlerro  = true;
            break;
          }
        }

      }

      $oDaoLogJulgamentoItem->pc93_sequencial            = null;
      $oDaoLogJulgamentoItem->pc93_pcorcamjulgamentolog  = $oDaoLogJulgamento->pc92_sequencial;
      $oDaoLogJulgamentoItem->pc93_pcorcamitem           = $pc24_orcamitem;
      $oDaoLogJulgamentoItem->pc93_pcorcamforne          = $pc24_orcamforne;
      $oDaoLogJulgamentoItem->pc93_valorunitario         = $nValorUnitario;
      $oDaoLogJulgamentoItem->pc93_pontuacao             = 1;
      $oDaoLogJulgamentoItem->incluir(null);

      if ($oDaoLogJulgamentoItem->erro_status == 0){

        $erro_msg = $oDaoLogJulgamentoItem->erro_msg;
        $sqlerro  = true;
        break;
      }
    }

    if ($sqlerro == false) {

      $clliclicita->l20_licsituacao = 1;
      $clliclicita->l20_codigo      = $l20_codigo;

      $clliclicita->alterar($l20_codigo);
      if ($clliclicita->erro_status == 0) {

        $erro_msg = $clliclicita->erro_msg;
        $sqlerro  = true;
      }

      if ($sqlerro == false) {

        $l11_sequencial                       = '';
        $clliclicitasituacao->l11_id_usuario  = DB_getSession("DB_id_usuario");
        $clliclicitasituacao->l11_licsituacao = 1;
        $clliclicitasituacao->l11_liclicita   = $clliclicita->l20_codigo;
        $clliclicitasituacao->l11_obs         = "Licitação Julgada";
        $clliclicitasituacao->l11_data        = date("Y-m-d",DB_getSession("DB_datausu"));
        $clliclicitasituacao->l11_hora        = DB_hora();
        $clliclicitasituacao->incluir($l11_sequencial);
        if ($clliclicitasituacao->erro_status == 0) {
          $sqlerro = true;
        }
      }
    }


    db_fim_transacao($sqlerro);
  }

  if ($sqlerro == false) {

    $db_opcao = 3;
    $erro_msg = $linha." item(ns) da licitacao ".$l20_codigo." foram julgado(s).";
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
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1">
<table border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="left" valign="top" bgcolor="#CCCCCC">
    <center>
        <?php require_once(modification("forms/db_frmpcorcamtrocalic.php")) ?>
    </center>
    </td>
  </tr>
</table>
</body>
<?php db_menu() ?>
</html>
<?php
if (trim($erro_msg) != "") {

  $erro_msg = urldecode($erro_msg);
  db_msgbox($erro_msg);
}
