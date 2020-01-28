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

$oParam   = JSON::create()->parse(stripslashes($_POST['json']));
$oRetorno = (object) array(
  'message' => '',
  'erro'    => false
);

try {

  db_inicio_transacao();

  switch ($oParam->exec) {

    case 'carregar':

      if (empty($oParam->iCodigo)) {
        throw new ParameterException('Código não informado.');
      }

      $oIsencao = new AguaIsencaoCgm((int) $oParam->iCodigo);

      $oRetorno->isencao = (object) array(
        'iCodigo'        => $oIsencao->getCodigo(),
        'iCgm'           => $oIsencao->getCgm()->getCodigo(),
        'sCgmDescricao'  => $oIsencao->getCgm()->getNome(),
        'iTipo'          => $oIsencao->getTipoIsencao()->getCodigo(),
        'sTipoDescricao' => $oIsencao->getTipoIsencao()->getDescricao(),
        'sDataInicial'   => $oIsencao->getDataInicial()->getDate(DBDate::DATA_PTBR),
        'sDataFinal'     => ($oIsencao->getDataFinal() ? $oIsencao->getDataFinal()->getDate(DBDate::DATA_PTBR) : null),
        'sProcesso'      => $oIsencao->getNumeroProcesso(),
        'sObservacao'    => $oIsencao->getObservacoes(),
      );

      break;

    case 'excluir':

      if (empty($oParam->iCodigo)) {
        throw new ParameterException('Código não informado.');
      }

      $oIsencao = new AguaIsencaoCgm((int) $oParam->iCodigo);
      $oIsencao->excluir();

      $oRetorno->message = 'Isenção excluída com sucesso.';
      break;

    case 'salvar':

      if (empty($oParam->iCgm)) {
        throw new ParameterException('O campo Nome/Razão Social é de preenchimento obrigatório.');
      }

      if (empty($oParam->iTipo)) {
        throw new ParameterException('O campo Tipo de Isenção é de preenchimento obrigatório.');
      }

      if (empty($oParam->sDataInicial)) {
        throw new ParameterException('O campo Data Inicial é de preenchimento obrigatório.');
      }

      $oIsencao = new AguaIsencaoCgm();
      $oIsencao->setCodigo($oParam->iCodigo);
      $oIsencao->setCodigoCgm((int) $oParam->iCgm);
      $oIsencao->setCodigoTipoIsencao((int) $oParam->iTipo);
      $oIsencao->setNumeroProcesso((string) $oParam->sProcesso);
      $oIsencao->setObservacoes((string) $oParam->sObservacao);
      $oIsencao->setDataInicial(new DBDate($oParam->sDataInicial));

      if ($oParam->sDataFinal) {
        $oIsencao->setDataFinal(new DBDate($oParam->sDataFinal));
      }

      $oIsencao->salvar();

      $oRetorno->isencao = (object) array(
        'iCodigo' => $oIsencao->getCodigo(),
      );

      $oRetorno->message = 'Isenção salva com sucesso.';
      break;

    default:
      throw new Exception('Opção é inválida.');
  }

  db_fim_transacao();
} catch (Exception $exception) {

  db_fim_transacao(true);

  $oRetorno->message = $exception->getMessage();
  $oRetorno->erro    = true;
}

echo JSON::create()->stringify($oRetorno);
