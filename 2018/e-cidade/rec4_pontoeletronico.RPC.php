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

use ECidade\RecursosHumanos\RH\Assentamento\AssentamentoJustificativa;
use ECidade\RecursosHumanos\RH\Efetividade\Repository\Periodo as PeriodoRepository;
use ECidade\RecursosHumanos\RH\PontoEletronico\Arquivo\Importacao;
use ECidade\RecursosHumanos\RH\PontoEletronico\Arquivo\Repository\Importacao as ImportacaoRepository;
use ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Model\ProcessamentoPontoEletronico;
use ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Repository\DiaTrabalho as DiaTrabalhoRepository;
use ECidade\RecursosHumanos\RH\PontoEletronico\Configuracao\ParametrosRepository;
use ECidade\RecursosHumanos\RH\PontoEletronico\Manutencao\EspelhoPonto;

require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_utils.php");
require_once modification("libs/db_app.utils.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("dbforms/db_funcoes.php");
require_once modification("libs/JSON.php");

$oPost       = db_utils::postMemory($_REQUEST);
$oPost->json = str_replace("\\","",$oPost->json);
$oParametro  = JSON::create()->parse($oPost->json);
$oRetorno    = (object)array( 'erro' => false, 'mensagem'=> '');

$oDiaTrabalhoRepository = new DiaTrabalhoRepository();
try {

  db_inicio_transacao();

  switch ($oParametro->exec) {

    case "importarArquivo":

      if (empty($_FILES['rh196_arquivo'])) {
        throw new ParameterException('Nenhum arquivo informado.');
      }

      if ($_FILES['rh196_arquivo']['error'] !== UPLOAD_ERR_OK) {
        throw new FileException('Ocorreu um erro ao fazer envio do arquivo.');
      }

      if(!isset($oParametro->periodo->dataInicio) || empty($oParametro->periodo->dataInicio)) {
        throw new ParameterException('Período inicio não informado.');
      }

      if(!isset($oParametro->periodo->dataFim) || empty($oParametro->periodo->dataFim)) {
        throw new ParameterException('Período fim não informado.');
      }

      $oParametro->arquivo = (object)$_FILES['rh196_arquivo'];
      $sNomeArquivo        = 'tmp/'.$oParametro->arquivo->name;

      move_uploaded_file($oParametro->arquivo->tmp_name, $sNomeArquivo);

      $oLayoutArquivo = new \DBLayoutReader(Importacao::CODIGO_LAYOUT_ARQUIVO, $sNomeArquivo, true, false);
      $oLayoutArquivo->processarArquivo(0, true, true);
      $aPisMatriculasProcessar = array();

      $oDataInicioParametro = new DBDate($oParametro->periodo->dataInicio);
      $oDataFinalParametro  =  new DBDate($oParametro->periodo->dataFim);

      foreach($oLayoutArquivo->getLines() as $oLinha) {

        switch($oLinha->TIPO_REGISTRO) {

          case Importacao::REGISTRO_CABECALHO:

            $oDataInicialArquivo = new DBDate(preg_replace("/(\d{2})(\d{2})(\d{4})/", "$3-$2-$1", $oLinha->DATA_INICIAL));
            $oDataFinalArquivo   = new DBDate(preg_replace("/(\d{2})(\d{2})(\d{4})/", "$3-$2-$1", $oLinha->DATA_FINAL));

            if (   ($oDataInicioParametro->getTimeStamp() < $oDataInicialArquivo->getTimeStamp())
                || ($oDataFinalParametro->getTimeStamp() > $oDataFinalArquivo->getTimeStamp()) ) {

              $sMensagemDataInconsistente  = "O período informado deve ser igual ou estar entre o período do arquivo.\n";
              $sMensagemDataInconsistente .= "Período do arquivo: {$oDataInicialArquivo->getDate(DBDate::DATA_PTBR)} - {$oDataFinalArquivo->getDate(DBDate::DATA_PTBR)} \n";
              $sMensagemDataInconsistente .= "Importe um arquivo com o período informado ou altere o período para o correspondente no arquivo.";

              throw new BusinessException($sMensagemDataInconsistente);
            }
            break;

          default:
            if(isset($oLinha->PIS_EMPREGADO)) {
              $aPisMatriculasProcessar[$oLinha->PIS_EMPREGADO] = substr($oLinha->PIS_EMPREGADO, 1);
            }
            continue 2;
            break;
        }
      }

      $oPeriodoRepository = new PeriodoRepository(null, null, true);
      $aPeriodos = $oPeriodoRepository->getPeriodosEntreDatas($oDataInicioParametro, $oDataFinalParametro);

      foreach ($aPeriodos as $oPeriodo) {

        $oImportacao          = new Importacao($sNomeArquivo, $oPeriodo);
        $oImportacao->setSobrescreverMarcacao(!!$oParametro->sobrescrever);
        $aInconsistencias     = $oImportacao->persistirRegistros();
        $iCodigoArquivo       = $oImportacao->getCodigoArquivo();

        if(empty($iCodigoArquivo)) {
          continue;
        }
      }

      unlink($sNomeArquivo);

      $oRetorno->mensagem  = "Arquivo importado";

      if(is_array($aInconsistencias) && !empty($aInconsistencias)) {
        $oRetorno->mensagem .= ".\nPorém, os seguintes PIS/PASEP não foram encontrados:\n\n". implode(", ", $aInconsistencias);
      } else {
        $oRetorno->mensagem .= " com sucesso.";
      }

      break;

    case 'buscaRegistrosPonto':

      if(!isset($oParametro->periodo->dataInicio) || empty($oParametro->periodo->dataInicio)) {
        throw new ParameterException('Data inicio não informada.');
      }

      if(!isset($oParametro->periodo->dataFim) || empty($oParametro->periodo->dataFim)) {
        throw new ParameterException('Data fim não informada.');
      }

      if(!isset($oParametro->matriculas) || empty($oParametro->matriculas)) {
        throw new ParameterException('Nenhuma matrícula informada.');
      }

      $oRetorno->aDados = array();

      foreach($oParametro->matriculas as $matricula) {

        $oServidor          = ServidorRepository::getInstanciaByCodigo($matricula);
        $oInstituicao       = InstituicaoRepository::getInstituicaoSessao();
        $oPeriodoRepository = new PeriodoRepository(null, null, true);
        $aPeriodos          = $oPeriodoRepository->getPeriodosEntreDatas(
          new DBDate($oParametro->periodo->dataInicio),
          new DBDate($oParametro->periodo->dataFim)
        );

        $oEspelhoPonto      = new EspelhoPonto($oServidor, $aPeriodos, $oInstituicao);
        $oRetorno->aDados[] = $oEspelhoPonto->retornaDados();
      }

      break;

    case 'salvarRegistrosPonto':

      $aDatasProcessar     = array();
      $iCodigoData         = null;
      $matriculasProcessar = array();

      foreach ($oParametro->aDados as $oDados) {

        if(empty($iCodigoData)) {
          $iCodigoData = $oDados->codigo_data;
        }

        $oServidor = ServidorRepository::getInstanciaByCodigo($oDados->matricula);
        $aEscalas  = $oServidor->getEscalas();

        list($dia, $mes, $ano)                   = explode("/", $oDados->data);
        $sDatasProcessar                         = $ano .'-'. $mes .'-'. $dia;
        $aDatasProcessar[]                       = $sDatasProcessar;
        $matriculasProcessar[$oDados->matricula] = $oDados->matricula;

        $oData = new DBDate($sDatasProcessar);

        $oDiaTrabalhoRepository = new DiaTrabalhoRepository();
        $oDiaTrabalhoRepository->setEscalaServidor(ProcessamentoPontoEletronico::getEscalaNaData($aEscalas, $oData));
        $oDiaTrabalhoModel      = $oDiaTrabalhoRepository->getDiaTrabalhoServidor($oServidor, $oData);
        $oDiaTrabalhoModel->setAfastamento(null);
        $oDiaTrabalhoRepository->persist($oDiaTrabalhoModel);

        $aAfastamentos          = \AssentamentoRepository::getAssentamentosServidorPorTipoENatureza($oServidor, 'A', $oData);

        if(!empty($aAfastamentos)){

          $oDiaTrabalhoModel->setAfastado(true);
          $oDiaTrabalhoModel->setAfastamento($aAfastamentos[0]);
          $oDiaTrabalhoRepository->persist($oDiaTrabalhoModel);

        }

        $oDiaTrabalhoModel = $oDiaTrabalhoRepository->getDiaTrabalhoServidor($oServidor, $oData);

        ProcessamentoPontoEletronico::salvarMarcacaoEVincularJustificativa($oServidor, $sDatasProcessar, $oDados->aMarcacoes, $oDiaTrabalhoModel);
      }

      $oPeriodoRepository = new PeriodoRepository(null, null, true);
      $aPeriodos          = $oPeriodoRepository->getPeriodosEntreDatas(
        new DBDate($oParametro->periodo->dataInicio),
        new DBDate($oParametro->periodo->dataFim)
      );

      foreach($aPeriodos as $oPeriodo) {

        $oPeriodo = $oPeriodoRepository->getCodigoArquivoPorPeriodo($oPeriodo);
        ProcessamentoPontoEletronico::processarMatriculas($matriculasProcessar, $oPeriodo, $aDatasProcessar);
      }

      $oRetorno->mensagem = "Salvo com sucesso.";
      break;

    case 'criarMarcacoesNasDatas':

      if(empty($oParametro->datas)) {
        throw new ParameterException("Não foram informadas as datas a processar.");
      }

      if(empty($oParametro->matricula)) {
        throw new ParameterException("Informe a matrícula do servidor.");
      }

      $aHorarios     = false;
      $lSobrescrever = false;

      if(isset($oParametro->aHorarios)) {
        $aHorarios = $oParametro->aHorarios;
      }

      if(isset($oParametro->bSobrescrever)) {
        $lSobrescrever = $oParametro->bSobrescrever == 't';
      }

      ProcessamentoPontoEletronico::criarMarcacoesNasDatas($oParametro->matricula, $oParametro->datas, $aHorarios, $lSobrescrever);

      break;

    case 'criarMarcacoesEmLote':

      // Para nao ficar replicando as informacoes a cada interação do loop
      $oServidorBase                = new STDClass();
      // data tem que ser uma propriedade data dentro de um array da propriedade datas
      $oData                        = new STDClass();
      $oData->data = $oParametro->datas[0];
      $oServidorBase->datas         = array($oData);
      $aErros                       = array();

      if(!empty($oParametro->selecao)) {

        // Busca os servidores pela selecao
        $aSelecao = ServidorRepository::getServidoresBySelecao(DBPessoal::getAnoFolha(), DBPessoal::getMesFolha(), $oParametro->selecao);

        // cria a propriedade matriculas
        $oParametro->matriculas = array();

        foreach ($aSelecao as $oSelecao) {

          $oParametro->matriculas[] = $oSelecao->getMatricula();
        }
      }

      $lErroGeral                    = false;
      $oRetorno->lTemInconsistencias = false;
      $oRetorno->matriculas          = array();

      foreach ($oParametro->matriculas as $iMatricula) {

        try {

          $oServidorBase->matricula = $iMatricula;
          ProcessamentoPontoEletronico::criarMarcacoesNasDatas(
            $oServidorBase->matricula,
            $oServidorBase->datas,
            $oParametro->horarios,
            $oParametro->sobrescreverMarcacao == 't'
          );

          $oRetorno->matriculas[] = $iMatricula;
        } catch  (Exception $eErro) {

          //Vamos fazer o tratamento das mensagens por código de erro
          //Os codigos de 1 a 10 foram criados e retornam do ../Repository/DiaTrabalho.php
          $oServidor = ServidorRepository::getInstanciaByCodigo($iMatricula);

          switch ($eErro->getCode()) {

            case 1 :

              // Sempre adiciona ou readiciona, para evitar 1 if.
              $aErros[1]['titulo'] = "Não há escalas para o servidor (RH > Cadastros > Efetividade > Escala de Trabalho).";
              break;

            case 2:

              $aErros[2]['titulo'] = "Não há lotação configurada para o servidor (Pessoal > Cadastro > Servidores > aba Movimentações).";
              break;

            case 3:

              $aErros[3]['titulo'] = "A lotação do servidor não está configurada (RH > Procedimentos > Ponto Eletrônico > Configurações > aba Lotação).";
              break;

            case 4:

              $aErros[4]['titulo'] = "Servidor não possui escala (RH > Procedimentos > Efetividade > Manutenção da Escala de Funcionários).";
              break;

            case 5:

              $aErros[5]['titulo'] = "Servidor não possui escala na data (RH > Procedimentos > Efetividade > Manutenção da Escala de Funcionários).";
              break;

            default:

              db_fim_transacao(true);

              $oRetorno->mensagem = $eErro->getMessage();
              $lErroGeral         = true;

              break 2;
          }

          $aErros[$eErro->getCode()]['matriculas'][] = array('matricula' => $iMatricula, 'nome' =>$oServidor->getCgm()->getNome());
        }
      }

      if(!$lErroGeral) {

        $oRetorno->mensagem = "Informações atualizadas com sucesso.";

        if(!empty($aErros)) {

          $oRetorno->lTemInconsistencias  = true;
          $oRetorno->mensagem             = "Informações atualizadas com sucesso. Porém, foram encontradas inconsistências";
          $oRetorno->mensagem            .= " em alguns servidores. Deseja imprimí-las?";

          file_put_contents('tmp/servidores_inconsistencia.json', json_encode(DBString::utf8_encode_all($aErros)));
        }
      }

      break;

    case 'criarAssentamentosJustificativas':

      $matriculasProcessar = $oParametro->matriculas;
      $tipoassentamento    = $oParametro->tipoassentamento;
      $dataInicio          = new \DBDate($oParametro->dataInicio);
      $dataFim             = new \DBDate($oParametro->dataFim);
      $lErroGeral          = false;
      $datasPeriodo        = \DBDate::getDatasNoIntervalo($dataInicio, $dataFim);
      $oRetorno->lTemInconsistencias = false;

      if($oParametro->tipoFiltro == 1) { // Filtro de seleção

        if(!isset($oParametro->selecao) || empty($oParametro->selecao)) {
          throw new \ParameterException("Informe corretamente uma seleção para fazer as justificativas em lote.");
        }

        $aServidoresPorSelecao = \ServidorRepository::getServidoresBySelecao(
          \DBPessoal::getAnoFolha(),
          \DBPessoal::getMesFolha(),
          $oParametro->selecao
        );

        $matriculasProcessar = array();
        foreach ($aServidoresPorSelecao as $servidorPorSelecao){
          $matriculasProcessar[] = $servidorPorSelecao->getMatricula();
        }

      }

      if(empty($matriculasProcessar)) {
        throw new Exception("Não foi possível identificar os servidores a processar.");
      }

      foreach ($matriculasProcessar as $matricula) {

        $lPersisteAssentamento = true;
        $servidor = \ServidorRepository::getInstanciaByCodigo($matricula);
        $assentamento = new AssentamentoJustificativa();
        $assentamento->setMatricula($matricula);
        $assentamento->setServidor($servidor);
        $assentamento->setTipoAssentamento($tipoassentamento);
        $assentamento->setDataConcessao($dataInicio);
        $assentamento->setDataTermino($dataFim);
        $assentamento->setDias(\DBDate::calculaIntervaloEntreDatas($dataFim, $dataInicio, 'd') +1);
        $assentamento->setDataLancamento(new \DBDate(date('Y-m-d')));

        $assentamento->setPeriodo1((int)(bool)$oParametro->periodoJustificativa1);
        $assentamento->setPeriodo2((int)(bool)$oParametro->periodoJustificativa2);
        $assentamento->setPeriodo3((int)(bool)$oParametro->periodoJustificativa3);

        foreach ($datasPeriodo as $dataPeriodo) {

          $oEscalaServidor = $servidor->getEscalas($dataPeriodo);
          if(empty($oEscalaServidor)){

            $aErros[8]['titulo'] = "Não há escalas para o servidor no período\n(RH > Cadastros > Efetividade > Escala de Trabalho)";
            $aErros[8]['matriculas'][$matricula] = array('matricula' => $servidor->getMatricula(), 'nome' =>$servidor->getCgm()->getNome());
            $lPersisteAssentamento = false;
          }

          $oLotacao = LotacaoRepository::getInstanceByCodigo($servidor->getCodigoLotacao());
          if(empty($oLotacao)){

            $aErros[9]['titulo'] = "Não há lotação configurada para o servidor \n(Pessoal > Cadastro > Servidores > aba Movimentações)";
            $aErros[9]['matriculas'][$matricula] = array('matricula' => $servidor->getMatricula(), 'nome' =>$servidor->getCgm()->getNome());
            $lPersisteAssentamento = false;
          }
          else{

            $oConfiguracoesLotacao = ParametrosRepository::create()->getConfiguracoesLotacao($servidor->getCodigoLotacao());
            if(empty($oConfiguracoesLotacao)){

              $aErros[10]['titulo'] = "A lotação do servidor não está configurada \n(RH > Procedimentos > Ponto Eletrônico > Configurações > aba Lotação).";
              $aErros[10]['matriculas'][$matricula] = array('matricula' => $servidor->getMatricula(), 'nome' =>$servidor->getCgm()->getNome());
              $lPersisteAssentamento = false;
            }
          }

          if($assentamento->validarExistenciaJustificativaNoPeriodo($dataPeriodo)) {
            $inconsistencias[$matricula]['justificativas'][] = $dataPeriodo->getDate(\DBDate::DATA_PTBR);
          }

          if($servidor->isAfastadoNoRH($dataPeriodo)) {
            $inconsistencias[$matricula]['afastamentos'][] = $dataPeriodo->getDate(\DBDate::DATA_PTBR);
          }
        }

        if(!empty($inconsistencias[$matricula]['justificativas'])) {

          $aErros[6]['titulo'] = "Existe justificativa nesta(s) data(s): ". implode(', ', $inconsistencias[$matricula]['justificativas']). "\nRH > Procedimentos > Manutenção de Assentamentos > Assentamentos de Efetividade.";
          $aErros[6]['matriculas'][] = array('matricula' => $servidor->getMatricula(), 'nome' =>$servidor->getCgm()->getNome());
        }

        if(!empty($inconsistencias[$matricula]['afastamentos'])) {

            $aErros[7]['titulo'] = "Existe afastamento do RH nesta(s) data(s): ". implode(', ', $inconsistencias[$matricula]['afastamentos']). "\nRH > Procedimentos > Manutenção de Assentamentos > Assentamentos de Efetividade.";
            $aErros[7]['matriculas'][] = array('matricula' => $servidor->getMatricula(), 'nome' =>$servidor->getCgm()->getNome());
        }

        if($lPersisteAssentamento){
          \AssentamentoRepository::persist($assentamento);
          $oRetorno->mensagem = "Informações atualizadas com sucesso. ";
        }
      }

      if(!empty($aErros)) {

        $oRetorno->lTemInconsistencias  = true;
        $oRetorno->mensagem .= "Foram encontradas inconsistências";
        $oRetorno->mensagem .= " em alguns servidores. Deseja imprimí-las?";

        file_put_contents('tmp/servidores_inconsistencia.json', json_encode(DBString::utf8_encode_all($aErros)));
      }

      break;

    default:
      return;
  }

  db_fim_transacao(false);
} catch (Exception $eErro) {

  db_fim_transacao(true);

  $oRetorno->erro     = true;
  $oRetorno->mensagem = $eErro->getMessage();
}

echo JSON::create()->stringify($oRetorno);
