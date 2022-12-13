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

require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("libs/db_usuariosonline.php");
require_once modification("dbforms/db_funcoes.php");

$oParam   = JSON::create()->parse(str_replace("\\", "", $_POST["json"]));
$oRetorno = (object) array(
  "message" => '',
  "erro" => false
);

function retornoEconomia(AguaContratoEconomia $oEconomia) {

  return (object) array(
    'iCodigo'               => $oEconomia->getCodigo(),
    'iCgmCodigo'            => $oEconomia->getCodigoCgm(),
    'sCgmDescricao'         => $oEconomia->getCgm()->getNome(),
    'iCategoriaCodigo'      => $oEconomia->getCodigoCategoriaConsumo(),
    'sCategoriaDescricao'   => $oEconomia->getCategoriaConsumo()->getDescricao(),
    'sDataValidadeCadastro' => $oEconomia->getDataValidadeCadastro() ? $oEconomia->getDataValidadeCadastro()->getDate(DBDate::DATA_PTBR) : null,
    'sNis'                  => $oEconomia->getNis(),
    'sComplemento'          => $oEconomia->getComplemento(),
    'sObservacoes'          => $oEconomia->getObservacoes(),
  );
}

try {

  db_inicio_transacao();

  switch ($oParam->exec) {

    case "salvarContrato":

      $oContrato = new AguaContrato();
      $oContrato->setCodigoCgm((integer) $oParam->iCgm);
      $oContrato->setDiaVencimento((integer) $oParam->iDiaVencimento);
      $oContrato->setCodigoMatricula((integer) $oParam->iMatricula);
      $oContrato->setCondominio($oParam->lCondominio);
      $oContrato->setNis($oParam->sNis);

      if ($oParam->iTipoContrato) {
        $oContrato->setCodigoTipoContrato((integer) $oParam->iTipoContrato);
      }

      if ($oParam->iCategoriaConsumo) {
        $oContrato->setCodigoCategoriaConsumo((integer) $oParam->iCategoriaConsumo);
      }

      if ($oParam->iCodigo) {
        $oContrato->setCodigo((integer) $oParam->iCodigo);
      }

      if ($oParam->iHidrometro) {

        $oHidrometro = new AguaHidrometro((integer) $oParam->iHidrometro);
        $oContrato->adicionarHidrometro($oHidrometro);
      }

      if ($oParam->sDataInicial) {

        $oDataInicial = new DBDate($oParam->sDataInicial);
        $oContrato->setDataInicial($oDataInicial);
      }

      if ($oParam->sDataFinal) {

        $oDataFinal = new DBDate($oParam->sDataFinal);
        $oContrato->setDataFinal($oDataFinal);
      }

      if ($oParam->sDataValidadeCadastro) {

        $oDataValidadeCadastro = new DBDate($oParam->sDataValidadeCadastro);
        $oContrato->setDataValidadeCadastro($oDataValidadeCadastro);
      }

      $oContrato->setResponsavelPagamento($oParam->iResponsavelPagamento);

      $oContrato->salvar();

      $oRetorno->message = "Contrato salvo com sucesso.";
      $oRetorno->contrato = (object) array(
        'iCodigo' => $oContrato->getCodigo()
      );

      break;

    case "excluirContrato":

      $oContrato = new AguaContrato((integer) $oParam->iCodigo);
      $oContrato->excluir();

      $oRetorno->message = "Contrato excluído com sucesso.";
      break;

    case "carregarContrato":

      $oContrato = new AguaContrato((integer) $oParam->iCodigo);
      $oRetorno->contrato = (object) array(
        "iCodigo"                => $oContrato->getCodigo(),
        "iCgm"                   => $oContrato->getCgm()->getCodigo(),
        "sDataInicial"           => $oContrato->getDataInicial()->getDate(DBDate::DATA_PTBR),
        "iDiaVencimento"         => $oContrato->getDiaVencimento(),
        "iHidrometro"            => current($oContrato->getHidrometros())->getCodigo(),
        "sCgmDescricao"          => $oContrato->getCgm()->getNome(),
        "sHidrometroDescricao"   => current($oContrato->getHidrometros())->getNumero(),
        "sNis"                   => $oContrato->getNis(),
        "lCondominio"            => $oContrato->isCondominio(),
        "iResponsavelPagamento"  => $oContrato->getResponsavelPagamento(),
        "iMatricula"             => null,
        "sMatriculaDescricao"    => null,
        "sDataFinal"             => null,
        "sDataValidadeCadastro"  => null,
        "iTipoContrato"          => null,
        "sTipoContratoDescricao" => null,
        "iCategoriaConsumo"      => null,
        "sCategoriaDescricao"    => null,
      );

      if ($oContrato->getCodigoCategoriaConsumo()) {

        $oRetorno->contrato->iCategoriaConsumo = $oContrato->getCodigoCategoriaConsumo();
        $oRetorno->contrato->sCategoriaDescricao = $oContrato->getCategoriaConsumo()->getDescricao();
      }

      if ($oContrato->getMatricula()) {

        $oRetorno->contrato->iMatricula = $oContrato->getCodigoMatricula();
        $oRetorno->contrato->sMatriculaDescricao = $oContrato->getMatricula()->getProprietario()->getNome();
      }

      if ($oContrato->getDataValidadeCadastro()) {
        $oRetorno->contrato->sDataValidadeCadastro = $oContrato->getDataValidadeCadastro()->getDate(DBDate::DATA_PTBR);
      }

      if ($oContrato->getDataFinal()) {
        $oRetorno->contrato->sDataFinal = $oContrato->getDataFinal()->getDate(DBDate::DATA_PTBR);
      }

      if ($oContrato->getTipoContrato()) {

        $oRetorno->contrato->iTipoContrato = $oContrato->getTipoContrato()->getCodigo();
        $oRetorno->contrato->sTipoContratoDescricao = $oContrato->getTipoContrato()->getDescricao();
      }

      break;

    case "salvarEconomia":

      $oEconomia = new AguaContratoEconomia;
      $oEconomia->setCodigo((int) $oParam->iCodigo);
      $oEconomia->setCodigoCgm((int) $oParam->iCgm);
      $oEconomia->setCodigoContrato((int) $oParam->iContrato);
      $oEconomia->setCodigoCategoriaConsumo((int) $oParam->iCategoriaConsumo);
      $oEconomia->setNis($oParam->sNis);
      $oEconomia->setComplemento($oParam->sComplemento);
      $oEconomia->setObservacoes($oParam->sObservacoes);

      if ($oParam->sDataValidadeCadastro) {
        $oEconomia->setDataValidadeCadastro(new DBDate($oParam->sDataValidadeCadastro));
      }

      $oEconomia->salvar();

      $oRetorno->lAdicionar = (int) $oParam->iCodigo ? false : true;
      $oRetorno->oEconomia  = retornoEconomia($oEconomia);
      $oRetorno->message    = "Economia salva com sucesso.";

      break;

    case "excluirEconomia":

      $iCodigo = (int) $oParam->iCodigo;
      if (empty($iCodigo)) {
        throw new ParameterException('Código da Economia não informado.');
      }

      $oEconomia = new AguaContratoEconomia;
      $oEconomia->carregar($iCodigo);
      $oEconomia->excluir();

      $oRetorno->message = "Economia excluída com sucesso.";

      break;

    case "carregarEconomia":

      $iCodigo = (int) $oParam->iCodigo;
      if (empty($iCodigo)) {
        throw new ParameterException('Código da Economia não informado.');
      }

      $oEconomia = new AguaContratoEconomia;
      $oEconomia->carregar($iCodigo);

      $oRetorno->oEconomia = retornoEconomia($oEconomia);

      break;

    case "importarEconomias":

      $oRetorno->message = "Economias importadas com sucesso.";
      $oAguaContratoEconomia = new AguaContratoEconomia;
      $oAguaContratoEconomia->importarEconomias((int) $oParam->iCodigo, (int) $oParam->iCategoriaConsumo);

      break;

    case "listarEconomias":

      $iCodigo = (int) $oParam->iCodigo;
      if (empty($iCodigo)) {
        throw new ParameterException('Código do contrato não informado.');
      }

      $aEconomias = array();
      $oAguaContrato = new AguaContrato($iCodigo);
      foreach ($oAguaContrato->getEconomias() as $oEconomia) {
        $aEconomias[] = retornoEconomia($oEconomia);
      }

      $oRetorno->aEconomias = $aEconomias;

      break;

    case "emitirContrato":

      $sDataDocumento = date('Y-m-d', db_getsession("DB_datausu"));
      $oContratoImpresso = new ECidade\Tributario\Agua\Documento\Contrato();
      $oContratoImpresso->setCodigoContrato($oParam->iCodigo);
      $oContratoImpresso->setAguaEmissao(new AguaEmissao());
      $oContratoImpresso->setDataDocumento(new DBDate($sDataDocumento));
      $aDocumento = $oContratoImpresso->emitir();

      $oRetorno->sCaminhoArquivo = $aDocumento['path'];
      $oRetorno->sNomeArquivo = $aDocumento['name'];
      $oRetorno->message = 'Contrato emitido com sucesso.';
      break;

    default:
      throw new Exception("Opção é inválida.");
  }

  db_fim_transacao();
} catch (Exception $exception) {

  db_fim_transacao(true);

  $oRetorno->message = $exception->getMessage();
  $oRetorno->erro = true;
}

echo JSON::create()->stringify($oRetorno);
