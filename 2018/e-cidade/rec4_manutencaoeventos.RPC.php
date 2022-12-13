<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2017  DBSeller Servicos de Informatica
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

use ECidade\RecursosHumanos\RH\PontoEletronico\Evento\Model\Evento as Evento;
use ECidade\RecursosHumanos\RH\PontoEletronico\Evento\Repository\Evento as EventoRepository;

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));

$oPost       = db_utils::postMemory($_REQUEST);
$oPost->json = str_replace("\\","",$oPost->json);
$oParametro  = JSON::create()->parse($oPost->json);
$oRetorno    = (object)array( 'erro' => false, 'mensagem'=> '');

try {

  db_inicio_transacao();

  switch ($oParametro->exec) {

    case 'salvar':

      $evento = $oParametro->evento;

      if(empty($evento->titulo)) {
        throw new ParameterException("Informe um título válido para o evento.");
      }

      if(empty($evento->dataInicial)) {
        throw new ParameterException("Informe uma data inicial válida para o evento.");
      }

      if(empty($evento->dataFinal)) {
        throw new ParameterException("Informe uma data final válida para o evento.");
      }

      if(empty($evento->entradaUm)) {
        throw new ParameterException("Informe uma entrada válida para o período do evento.");
      }

      if(empty($evento->saidaUm)) {
        throw new ParameterException("Informe um saída válida para o período do evento.");
      }

      if(empty($evento->tipoHoraUm)) {
        throw new ParameterException("Informe a hora extra do primeiro período.");
      }

      $eventoModel = new Evento();
      $eventoModel->setCodigo($evento->codigo);
      $eventoModel->setInstituicao(InstituicaoRepository::getInstituicaoSessao());
      $eventoModel->setTitulo($evento->titulo);
      $eventoModel->setDataInicial(new DBDate($evento->dataInicial));
      $eventoModel->setDataFinal(new DBDate($evento->dataFinal));
      $eventoModel->setEntradaUm(new DateTime($eventoModel->getDataInicial()->getDate() .' '. $evento->entradaUm));
      $eventoModel->setSaidaUm(new DateTime($eventoModel->getDataInicial()->getDate() .' '. $evento->saidaUm));
      $eventoModel->setTipoHoraExtraUm($evento->tipoHoraUm);

      if(!empty($evento->entradaDois)) {
        $eventoModel->setEntradaDois(new DateTime($eventoModel->getDataInicial()->getDate() .' '. $evento->entradaDois));
      }
      if(!empty($evento->saidaDois)) {
        $eventoModel->setSaidaDois(new DateTime($eventoModel->getDataInicial()->getDate() .' '. $evento->saidaDois));
      }

      if(!empty($evento->tipoHoraDois)) {
        $eventoModel->setTipoHoraExtraDois($evento->tipoHoraDois);
      }

      foreach ($evento->matriculas as $servidor) {
        $eventoModel->adicionarServidor(ServidorRepository::getInstanciaByCodigo($servidor->sCodigo));
      }

      EventoRepository::getInstance()->salvar($eventoModel);
      $oRetorno->relatorioDeInconsistencia = false;
      $oRetorno->mensagem = "Evento salvo com sucesso.";
      if (file_exists('tmp/servidores_inconsistencia.json')) {

        $oRetorno->relatorioDeInconsistencia = true;
        $oRetorno->mensagem  = "Informações atualizadas com sucesso. Foram encontradas inconsistências em alguns servidores. Deseja imprimí-las?";
      }


      break;

    case 'getEventos':

      if(!empty($oParametro->codigo)) {

        $eventos = array(EventoRepository::getInstance()->getPorCodigo($oParametro->codigo));
        if(empty($eventos)) {
          throw new BusinessException("Não há eventos cadastrados com o código informado ({$oParametro->codigo}).");
        }

      } else {
        $eventos = EventoRepository::getInstance()->getTodos();
      }

      $oRetorno->eventos = array();
      foreach ($eventos as $evento) {

        $oStdEvento = (object)array(
          'codigo'       => $evento->getCodigo(),
          'titulo'       => $evento->getTitulo(),
          'dataInicial'  => $evento->getDataInicial()->getDate(DBDate::DATA_PTBR),
          'entradaUm'    => $evento->getEntradaUm()->format('H:i'),
          'saidaUm'      => $evento->getSaidaUm()->format('H:i'),
          'tipoHoraUm'   => $evento->getTipoHoraExtraUm(),
          'entradaDois'  => ($evento->getEntradaDois() instanceof DateTime ? $evento->getEntradaDois()->format('H:i') : null),
          'saidaDois'    => ($evento->getSaidaDois() instanceof DateTime ? $evento->getSaidaDois()->format('H:i') : null),
          'tipoHoraDois' => $evento->getTipoHoraExtraDois(),
          'matriculas'   => array()
        );

        $eventoServidores = $evento->getServidores();

        if(is_array($eventoServidores) && count($eventoServidores) > 0) {

          foreach ($evento->getServidores() as $servidor) {

            $oStdEvento->matriculas[] =  (object)array(
              'codigo' => $servidor->getMatricula(),
              'nome'   => $servidor->getCgm()->getNome()
            );
          }
        }

        $oRetorno->eventos[] = $oStdEvento;
      }
      break;

    case 'excluir':

      if(empty($oParametro->codigo)) {
        throw new ParameterException("Informe o código do evento para excluir.");
      }

      $evento = EventoRepository::getInstance()->getPorCodigo($oParametro->codigo);
      if(empty($evento)) {
        throw new BusinessException("Não há eventos cadastrados com o código informado ({$oParametro->codigo}).");
      }

      EventoRepository::getInstance()->excluir($oParametro->codigo);
      $oRetorno->mensagem = "Evento excluído com sucesso.";
      break;
  }

  db_fim_transacao(false);

} catch (Exception $eErro) {

  db_fim_transacao(true);

  $oRetorno->erro     = true;
  $oRetorno->mensagem = $eErro->getMessage();
}

echo JSON::create()->stringify($oRetorno);
