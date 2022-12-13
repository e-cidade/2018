<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2016  DBselller Servicos de Informatica
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
namespace Ecidade\RecursosHumanos\RH\PontoEletronico\Manutencao;

use ECidade\RecursosHumanos\RH\Efetividade\Model\Periodo as Periodo;
use ECidade\RecursosHumanos\RH\PontoEletronico\Manutencao\EspelhoPonto;
use ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Model\ProcessamentoPontoEletronico;
use ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Repository\DiaTrabalho as DiaTrabalhoRepository;


class ProcessamentoAssentamentoRepository {

  public static function processarAssentamentosNoPeriodo(Periodo $oPeriodo, array $aServidores, array $aTiposAssentamentos, \Instituicao $oInstituicao) {

    $aDatasEfetividade = \DBDate::getDatasNoIntervalo($oPeriodo->getDataInicio(), $oPeriodo->getDataFim());
    $barraProgresso    = new \ProgressBar('barraProgresso');
    $barraProgresso->updateMaxProgress(count($aServidores));
    $i = 0;

    foreach ($aServidores as $oServidor) {


      $dadosPonto = array(
        'nTotalHorasExt50diurnas'   => array('0:00'),
        'nTotalHorasExt75diurnas'   => array('0:00'),
        'nTotalHorasExt100diurnas'  => array('0:00'),
        'nTotalHorasExt50noturnas'  => array('0:00'),
        'nTotalHorasExt75noturnas'  => array('0:00'),
        'nTotalHorasExt100noturnas' => array('0:00'),
        'nTotalHorasAdicional'      => array('0:00'),
        'nTotalHorasFaltas'         => array('0:00'),
      );

      do {

        $oDataEfetividade       = current($aDatasEfetividade);
        $oDiaTrabalhoRepository = new DiaTrabalhoRepository();
        $oDiaTrabalho           = $oDiaTrabalhoRepository->getApenasHorasCalculadasPorServidorNaData($oServidor, $oDataEfetividade);
      
        $dadosPonto['nTotalHorasExt50diurnas'][]   = $oDiaTrabalho->getHorasExtra50();
        $dadosPonto['nTotalHorasExt75diurnas'][]   = $oDiaTrabalho->getHorasExtra75();
        $dadosPonto['nTotalHorasExt100diurnas'][]  = $oDiaTrabalho->getHorasExtra100();
        $dadosPonto['nTotalHorasExt50noturnas'][]  = $oDiaTrabalho->getHorasExtra50Noturna();
        $dadosPonto['nTotalHorasExt75noturnas'][]  = $oDiaTrabalho->getHorasExtra75Noturna();
        $dadosPonto['nTotalHorasExt100noturnas'][] = $oDiaTrabalho->getHorasExtra100Noturna();
        $dadosPonto['nTotalHorasAdicional'][]      = $oDiaTrabalho->getHorasAdicionalNoturno();
        $dadosPonto['nTotalHorasFaltas'][]         = $oDiaTrabalho->getHorasFalta();

      } while(next($aDatasEfetividade) !== false);
      reset($aDatasEfetividade);

      $sHoras = null;
      foreach ($aTiposAssentamentos as $sTipoHora => $oTipoAssentamento) {

        $sHoras = null;

        switch ($sTipoHora) {
          case 'extra50diurna':
            $sHoras = EspelhoPonto::somarTotalizador($dadosPonto['nTotalHorasExt50diurnas']);
            break;

          case 'extra75diurna':
            $sHoras = EspelhoPonto::somarTotalizador($dadosPonto['nTotalHorasExt75diurnas']);
            break;

          case 'extra100diurna':
            $sHoras = EspelhoPonto::somarTotalizador($dadosPonto['nTotalHorasExt100diurnas']);
            break;

          case 'extra50noturna':
            $sHoras = EspelhoPonto::somarTotalizador($dadosPonto['nTotalHorasExt50noturnas']);
            break;

          case 'extra75noturna':
            $sHoras = EspelhoPonto::somarTotalizador($dadosPonto['nTotalHorasExt75noturnas']);
            break;

          case 'extra100noturna':
            $sHoras = EspelhoPonto::somarTotalizador($dadosPonto['nTotalHorasExt100noturnas']);
            break;

          case 'adicionalnoturno':
            $sHoras = EspelhoPonto::somarTotalizador($dadosPonto['nTotalHorasAdicional']);
            break;

          case 'falta':
            $sHoras = EspelhoPonto::somarTotalizador($dadosPonto['nTotalHorasFaltas']);
            break;
          
          case 'faltas_dsr':

            $datasFaltas = ProcessamentoPontoEletronico::getDatasFaltas($oServidor, $oPeriodo);

            foreach ($datasFaltas as $dataFaltasDSR) {

              $dataConcessaoFaltasDSR = new \DBDate($dataFaltasDSR);
              $dataTerminoFaltasDSR   = clone $dataConcessaoFaltasDSR;

              $oAssentamento = new \Assentamento;
              $oAssentamento->setMatricula($oServidor->getMatricula());
              $oAssentamento->setServidor($oServidor);
              $oAssentamento->setTipoAssentamento($oTipoAssentamento->getSequencial());
              $oAssentamento->setDataConcessao($dataConcessaoFaltasDSR);
              $oAssentamento->setDataTermino($dataTerminoFaltasDSR);
              $oAssentamento->setDias(1);
              $oAssentamento->setDataLancamento(new \DBDate(date('Y-m-d')));
              
              \AssentamentoRepository::persist($oAssentamento);
            }
            break;
        }

        if($sTipoHora != 'faltas_dsr') {

          if (empty($sHoras) || $sHoras == '00:00') {
            continue;
          }

          $oAssentamento = new \Assentamento;
          $oAssentamento->setMatricula($oServidor->getMatricula());
          $oAssentamento->setServidor($oServidor);
          $oAssentamento->setTipoAssentamento($oTipoAssentamento->getSequencial());
          $oAssentamento->setDataConcessao(new \DBDate(date('Y-m-d')));
          $oAssentamento->setDataLancamento(new \DBDate(date('Y-m-d')));
          $oAssentamento->setHora($sHoras);
          
          \AssentamentoRepository::persist($oAssentamento);
        }
      }
    
      $i++;
      $barraProgresso->updatePercentual($i);
    }
  }
}
