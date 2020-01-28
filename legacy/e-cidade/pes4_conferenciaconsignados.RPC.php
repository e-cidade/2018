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
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("model/pessoal/arquivos/consignet/GeracaoArquivoConsignet.model.php"));


define('ARQUIVO_MENSAGEM', 'recursoshumanos.pessoal.pes4_conferenciaconsignados.');

$oJson              = new services_json();
$oParametros        = $oJson->decode(utf8_decode(str_replace("\\", "", urldecode($_POST["json"]))));
$oRetorno           = new stdClass();
$oRetorno->status   = 1;
$oRetorno->mensagem = '';
$oRetorno->erro     = false;
$aMotivos = array(
                  '' => 'ACEITO',
                  1 => 'FALECIMENTO',
                  2 => 'SERVIDOR NÃO IDENTIFICADO',
                  3 => "TIPO DE CONTRATO NÃO PERMITE EMPRÉSTIMO",
                  4 => "MARGEM CONSIGNÁVEL EXCEDIDA",
                  5 => "NÃO DESCONTADO - OUTROS MOTIVOS",
                  6 => "SERVIDOR DESLIGADO",
                  7 => "SERVIDOR AFASTADO EM LICENÇA SAÚDE",
                  8 => "EXCLUÍDO",
                  9 => "SALDO INSUFICIENTE"
                );
try {

  db_inicio_transacao();
  $oInstituicao = InstituicaoRepository::getInstituicaoSessao();
  $oCompetencia = DBPessoal::getCompetenciaFolha();
  switch ($oParametros->exec) {

    case 'getDados':

      if (empty($oParametros->banco)) {
        throw new ParameterException(_M(ARQUIVO_MENSAGEM."banco_nao_informado"));
      }

      $oRetorno->arquivo_processado = false;
      $oRetorno->consignacoes       = array();
      $oBanco   = new Banco($oParametros->banco);

      $oArquivo = ArquivoConsignadoRepository::getUltimoArquivoNaCompetenciaDoBanco($oInstituicao, null, $oBanco);
      if (empty($oArquivo)) {
        throw new BusinessException(_M(ARQUIVO_MENSAGEM."arquivo_nao_encontrado"));
      }
      $oRetorno->arquivo_processado = $oArquivo->isProcessado();
      $oRetorno->ano_arquivo        = $oArquivo->getCompetencia()->getAno();
      $oRetorno->mes_arquivo        = $oArquivo->getCompetencia()->getMes();

      $iMatricula = null; 

      if (isset($oParametros->matricula)){
        $iMatricula = $oParametros->matricula;
      }

      $aRegistros = $oArquivo->getRegistrosDaMatricula((int) $iMatricula);
      foreach ($aRegistros as $oRegistro) {

        $oStdRegistro                   = new \stdClass();
        $oStdRegistro->codigo           = $oRegistro->getCodigo();
        $oStdRegistro->matricula        = $oRegistro->getMatricula();
        $oStdRegistro->nome             = urlencode($oRegistro->getNome());
        $oStdRegistro->valor            = $oRegistro->getValorDescontar();
        $oStdRegistro->motivo           = $oRegistro->getMotivo();
        $oStdRegistro->parcela          = $oRegistro->getParcela();
        $oStdRegistro->descricao_motivo = urlencode($aMotivos[$oRegistro->getMotivo()]);
        $oRetorno->consignacoes[]       = $oStdRegistro;
      }

      if (count($oRetorno->consignacoes) == 0) {
        throw new BusinessException(_M(ARQUIVO_MENSAGEM."sem_registros_filtro"));
      }
      break;

    case 'salvar':

      if (empty($oParametros->codigo_registro)) {
        throw new ParameterException(_M(ARQUIVO_MENSAGEM."registro_nao_informado"));
      }
      $oRetorno->codigo_registro = $oParametros->codigo_registro;
      $oRetorno->motivo          = '';

      $oRegistroConsignado = RegistroConsignadoRepository::getRegistroByCodigo($oParametros->codigo_registro);
      if (empty($oRegistroConsignado)) {
        throw new BusinessException(_M(ARQUIVO_MENSAGEM."sem_registros_filtro"));
      }
      switch ($oRegistroConsignado->getMotivo()) {

        case null:

          $oRegistroConsignado->setMotivo(RegistroConsignado::MOTIVO_EXCLUIDO);
          break;
        case RegistroConsignado::MOTIVO_EXCLUIDO:

          $oRegistroConsignado->setMotivo(null);
          break;
      }

      RegistroConsignadoRepository::persist($oRegistroConsignado);
      $iMotivo                    =  ($oRegistroConsignado->getMotivo() === null) ? '' : $oRegistroConsignado->getMotivo();
      $oRetorno->motivo           = $oRegistroConsignado->getMotivo();
      $oRetorno->descricao_motivo = urlencode($aMotivos[$iMotivo]);
      break;
  }
  db_fim_transacao(false);
} catch (Exception $oErro) {

  db_fim_transacao(true);
  $oRetorno->status   = 0;
  $oRetorno->mensagem = urlencode($oErro->getMessage());
  $oRetorno->erro     = true;
}

$oRetorno->mensagem = $oRetorno->mensagem;
echo $oJson->encode($oRetorno);
