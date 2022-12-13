<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (c) 2014  DBSeller Servicos de Informatica
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

namespace ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Horas;

use ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Model\DiaTrabalho;
use ECidade\RecursosHumanos\RH\PontoEletronico\Marcacao\MarcacaoPonto;
use ECidade\RecursosHumanos\RH\PontoEletronico\Marcacao\MarcacoesPontoCollection;

/**
 * Classe responsável pelo cálculo do adicional noturno de um servidor em um dia de trabalho
 * Class AdicionalNoturno
 * @package ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Horas
 * @author Fábio Esteves <fabio.esteves@dbseller.com.br>
 */
class AdicionalNoturno extends BaseHora implements Horas {

  public function __construct(DiaTrabalho $oDiaTrabalho) {

    $this->setDiaTrabalho($oDiaTrabalho);
    $this->setTipoHora(BaseHora::HORAS_ADICIONAL_NOTURNO);
    parent::__construct();
  }

  /**
   * @return string
   */
  public function calcular() {

    $mHora                        = new \DateTime("00:00");
    $oCollectionMarcacoesNoturnas = new MarcacoesPontoCollection();
    $aMarcacoes                   = $this->getMarcacoesReais()->getMarcacoes();
    $aHorasJornada                = $this->getDiaTrabalho()->getJornada()->getHoras();

    /**
     * Se não há horas de jornada nem marcação não há valor de adicional noturno
     */
    if(empty($aHorasJornada) && empty($aMarcacoes)) {
      return $mHora->format('H:i');
    }

    /**
     * Monta objetos com o intervalo do horário de adicional noturno
     */
    $oDataFinal = clone $this->getDiaTrabalho()->getData();
    $oDataFinal = $oDataFinal->adiantarPeriodo(1, 'd');
    $oHoraInicioAdicionalNoturno = new \DateTime($this->getDiaTrabalho()->getData()->getDate(\DBDate::DATA_EN) .' 22:00:00');
    $oHoraFinalAdicionalNoturno  = new \DateTime($oDataFinal->getDate(\DBDate::DATA_EN) .' 05:00:00');
    
    $oHoraFinalAdicionalNoturnoNoDia  = new \DateTime($this->getDiaTrabalho()->getData()->getDate(\DBDate::DATA_EN) .' 05:00:00');

    /**
     * Bloco que valida a seguinte situação específica:
     * 1 - Ter somente 2 marcações
     * 2 - A primeira marcação antes das 22:00
     * 3 - A segunda marcação depois das 05:00
     *
     * Clonado o array das marcações, reiniciando o índice
     *
     * Neste caso, retorna as 7h noturnas de direito
     */
    if(count($aMarcacoes) == 2) {

      $aCloneMarcacoes = array_map(function($chave, $valor) {
        return $valor;
      }, range(0, (count($aMarcacoes)-1)), $aMarcacoes);

      if($aCloneMarcacoes[0]->getMarcacao()->getTimestamp() < $oHoraInicioAdicionalNoturno->getTimestamp()) {

        if($aCloneMarcacoes[1]->getMarcacao()->getTimestamp() > $oHoraFinalAdicionalNoturno->getTimestamp()) {
          return '07:00';
        }
      }
    }

    switch (count($aHorasJornada)) {
      case 2:
        break;

      default: //jornada com 4 marcações
        if(count($aMarcacoes) == 3) { //caso não tenha marcações, mantém apenas as 2 primeiras
          array_pop($aMarcacoes);
        }
        break;
    }

    /**
     * Percorre as marcações e verifica se há alguma marcação
     * no período do adicinal noturno, entre 22:00 e 05:00
     * caso exista coloca as marcações existentes em outro array
     */
    if(!empty($aMarcacoes)) {

      foreach ($aMarcacoes as $key => $oMarcacao) {

        if($oMarcacao->getMarcacao() == null) {
          continue;
        }

        if($oMarcacao->getMarcacao()->getTimestamp() > $oHoraInicioAdicionalNoturno->getTimestamp() || $oMarcacao->getMarcacao()->getTimestamp() < $oHoraFinalAdicionalNoturnoNoDia->getTimestamp()) {

          if($oMarcacao->getMarcacao()->getTimestamp() < $oHoraFinalAdicionalNoturno->getTimestamp()) {
            $oCollectionMarcacoesNoturnas->add($oMarcacao);
          }
        }
      }
    }

    /**
     * Se o array das marcações estiver vazio não há adicinal noturno
     */
    if($oCollectionMarcacoesNoturnas->getMarcacoes() == null) {
      return $mHora->format('H:i');
    }

    /**
     * caso exista apenas uma marcação como noturno, devemos verificar se a entrada foi realizada no horaio de termino do adicional noturno.
     */
    $aMarcacoesNoturnas      = $oCollectionMarcacoesNoturnas->getMarcacoes();
    $iTotalMarcacoesNoturnas = count($aMarcacoesNoturnas);
    if ($iTotalMarcacoesNoturnas == 1 && !empty($aMarcacoesNoturnas[1])) {
      
      $oMarcacao     = $aMarcacoesNoturnas[1];
      $iHoraMarcacao = $oMarcacao->getMarcacao()->getTimestamp();
      if ($iHoraMarcacao < $oHoraFinalAdicionalNoturnoNoDia->getTimestamp()) {
          $mHora->add($oMarcacao->getMarcacao()->diff($oHoraFinalAdicionalNoturnoNoDia));  
        }
    } 
    /**
     * Verifica se o número de marcações em horário noturno
     * está diferente do que a quantidade de marcações da jornada
     */
    if(count($aHorasJornada) != $iTotalMarcacoesNoturnas) {

      switch ($iTotalMarcacoesNoturnas) {

        case 1:

          if($oCollectionMarcacoesNoturnas->getMarcacaoEntrada1() == null) {
            $oCollectionMarcacoesNoturnas->add(new MarcacaoPonto($oHoraInicioAdicionalNoturno, MarcacaoPonto::ENTRADA_1));
          }

          break;

        case 2:
        default: //jornada com 4 marcações

          if($oCollectionMarcacoesNoturnas->getMarcacaoEntrada1() == null) {
            $oCollectionMarcacoesNoturnas->add(new MarcacaoPonto($oHoraInicioAdicionalNoturno, MarcacaoPonto::ENTRADA_1));
          }

          if($oCollectionMarcacoesNoturnas->getMarcacaoSaida2() == null) {
            $oCollectionMarcacoesNoturnas->add(new MarcacaoPonto($oHoraFinalAdicionalNoturno, MarcacaoPonto::SAIDA_2));
          }
          break;
      }

    }

    /**
     * Reordena o array e reseta as chaves
     */
    $aItensColecaoMarcacoesNoturna = $oCollectionMarcacoesNoturnas->getMarcacoes();
    ksort($aItensColecaoMarcacoesNoturna);
    $aItensColecaoMarcacoesNoturna = array_map(function($chave, $valor) {
      return $valor;
    }, range(0, (count($aItensColecaoMarcacoesNoturna)-1)), $aItensColecaoMarcacoesNoturna);

    /**
     * Percorre o array com as marcações no
     * intervalo para somar as horas trabalhadas
     */

    foreach ($aItensColecaoMarcacoesNoturna as $key => $oMarcacaoNoturna) {

      if($key < count($aItensColecaoMarcacoesNoturna) && $key % 2 == 0) {

        if(!array_key_exists($key+1, $aItensColecaoMarcacoesNoturna)) {
          continue;
        }

        /**
         * Calcula o intervalo entre a marcação atual e a próxima ($key+1)
         */
        $oIntervalo = $oMarcacaoNoturna->getMarcacao()->diff($aItensColecaoMarcacoesNoturna[$key+1]->getMarcacao());

        /**
         * Adiciona o intervalo ao objeto data que foi criado
         * no primeiro laço varíavel mHora recebe o objeto
         */
        if($key > 0) {
          $oData->add($oIntervalo);
          $mHora = $oData;
        }
      }

      /**
       * No primeiro laço coloca o intervalo na variável
       * e seta uma objeto data para próximo laço
       */
      if($key == 0) {
        $mHora = $oIntervalo;
        $oData = new \DateTime($mHora->format('%H:%i'));
      }
    }

    return $mHora instanceof \DateTime ? $mHora->format('H:i') : $mHora->format('%H:%i');
  }
}
