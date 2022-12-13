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

require("libs/db_stdlib.php");
require("libs/db_utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("model/cdaLivro.model.php");
include("dbforms/db_funcoes.php");
include("libs/JSON.php");

$oJson    = new services_json();
$oParam   = $oJson->decode(str_replace("\\","",$_POST["json"]));

$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = '';
switch ($oParam->exec) {

  case "getProximaPagina" :

    $oCdaLivro = new cdaLivro(db_getsession("DB_instit"), $oParam->livro);
    $oRetorno->proximapagina = $oCdaLivro->getProximaPagina();
    break;

  case "processaLivro" :

    $oCdaLivro = new cdaLivro(db_getsession("DB_instit"), $oParam->options->livro);
    db_inicio_transacao();
    try {
      $oCdaLivro->processaLivro($oParam->options);
      db_fim_transacao(false);
    } catch (Exception $eErro) {

      $oRetorno->status  = 3;
      $oRetorno->message = urlencode($eErro->getMessage());
      db_fim_transacao(true);
    }

    break;

  case "alterarCDA":

    $oCertidao = new Certidao( $oParam->v13_certid );

    if ( $oCertidao->isCobrancaExtrajudicial() ) {

      $oRetorno->status  = 2;
      $oRetorno->message = urlencode("Certidão {$oParam->v13_certid} está sob Cobrança Extrajudicial.");
    }

    /**
     * Alteramos a data da cda
     */
    if ( $oRetorno->status == 1 and $oParam->v13_dtemis != "") {

      db_inicio_transacao();
      $oDaoCertid = db_utils::getDao("certid");
      $oDaoCertid->v13_dtemis = implode("-", array_reverse(explode("/", $oParam->v13_dtemis)));
      $oDaoCertid->v13_certid = $oParam->v13_certid;
      $oDaoCertid->alterar($oParam->v13_certid);
      if ($oDaoCertid->erro_status == 0) {

        $oRetorno->status == 2;
        $oRetorno->message = urlDecode($oDaoCertid->erro_status);

      }
      if ($oRetorno->status == 1 && (empty($oParam->livro) || empty($oParam->pagina))) {

        $oDaoCertidLivroFolha = db_utils::getDao("certidlivrofolha");
        $oDaoCertidLivroFolha->excluir(null,"v26_certid={$oParam->v13_certid}");
        if ($oDaoCertidLivroFolha->erro_status == 0) {

          $oRetorno->status  = 2;
          $oRetorno->message = urlencode($oDaoCertidLivroFolha->erro_msg);

        }
      } else if ($oRetorno->status == 1) {


        $oDaoCertidLivroFolha = db_utils::getDao("certidlivrofolha");
        $oDaoCertidLivroFolha->excluir(null,"v26_certid={$oParam->v13_certid}");
        $oCdaLivro = new cdaLivro(db_getsession("DB_instit"), $oParam->livro);
        if ($oCdaLivro->getCodigoLivro() != "") {

           $oDaoCertidLivroFolha->v26_certid      = $oParam->v13_certid;
           $oDaoCertidLivroFolha->v26_numerofolha = $oParam->pagina;
           $oDaoCertidLivroFolha->v26_certidlivro = $oCdaLivro->getCodigoLivro();
           $oDaoCertidLivroFolha->incluir(null);
           if ($oDaoCertidLivroFolha->erro_status == 0) {

            $oRetorno->status  = 2;
            $oRetorno->message = urlencode($oDaoCertidLivroFolha->erro_msg);

          }
        } else {

          $oRetorno->status = 2;
          $oRetorno->message = urlencode("Livro ({$oParam->livro}) não Encontrado. Escolha um livro existente");

        }
      }
    }

    if ($oRetorno->status == 1) {

      $oRetorno->status  = 2;
      $oRetorno->message = urlencode("Informe a data da CDA!");
    }

    if ($oRetorno->status == 2) {
      db_fim_transacao(true);
    } else {
      db_fim_transacao(false);
    }
    break;
  default:

    $oRetorno->status = 2;
    $oRetorno->message = urlencode("Método solicitado não configurado");
    break;


}
echo $oJson->encode($oRetorno);

?>