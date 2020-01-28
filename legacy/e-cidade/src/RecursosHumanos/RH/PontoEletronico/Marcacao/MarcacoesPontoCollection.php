<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2016  DBseller Servicos de Informatica
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

namespace ECidade\RecursosHumanos\RH\PontoEletronico\Marcacao;

use ECidade\RecursosHumanos\RH\PontoEletronico\Marcacao\MarcacaoPonto;
use ECidade\RecursosHumanos\RH\PontoEletronico\Marcacao\MarcacoesPontoFactory;


/**
 * Classe responsável por montar uma coleção de marcações
 * Class MarcacoesPontoCollection
 * @package ECidade\RecursosHumanos\RH\PontoEletronico\Marcacao
 * @author Renan Silva <renan.silva@dbseller.com.br>
 */
class MarcacoesPontoCollection {

  /**
   * @var MarcacaoPonto[]
   */
  private $aMarcacoes = array();

  public static function getCollectionMarcacoesFromArray(array $aMarcacoes) {

    $oCollection = new MarcacoesPontoCollection;

    for ($iMarcacoes=0; $iMarcacoes < count($aMarcacoes); $iMarcacoes++) {

      $oStdMarcacao = $aMarcacoes[$iMarcacoes];
      $oDadosMarcacao =!empty($oStdMarcacao->hora) ? new \DateTime($oStdMarcacao->data .' '. $oStdMarcacao->hora) : null;
      $oMarcacao    = MarcacoesPontoFactory::create($oDadosMarcacao, ($iMarcacoes+1), $oStdMarcacao->codigo);

      if($oMarcacao instanceof MarcacaoPontoSaida && $iMarcacoes % 2 != 0) {
        $oMarcacao->setMarcacaoEntrada($oCollection->getMarcacao($iMarcacoes)->getMarcacao());
      }
      
      if($oMarcacao instanceof MarcacaoPonto) {

        $oMarcacao->setManual((boolean)$oStdMarcacao->manual);
        $oMarcacao->setData(new \DBDate($oStdMarcacao->data));

        if(!empty($oStdMarcacao->justificativa)) {
          $oMarcacao->setJustificativa($oStdMarcacao->justificativa);
        }
      }

      $oCollection->add($oMarcacao);
    }

    return $oCollection;
  }

  public function add($oMarcacao) {

    if($oMarcacao instanceof MarcacaoPonto) {
      $this->aMarcacoes[$oMarcacao->getTipo()] = $oMarcacao;
    }
  }

  public function getMarcacaoEntrada1() {
    return $this->getMarcacao(MarcacaoPonto::ENTRADA_1);
  }
  
  public function getMarcacaoSaida1() {
    return $this->getMarcacao(MarcacaoPonto::SAIDA_1);
  }

  public function getMarcacaoEntrada2() {
    return $this->getMarcacao(MarcacaoPonto::ENTRADA_2);
  }
  
  public function getMarcacaoSaida2() {  
    return $this->getMarcacao(MarcacaoPonto::SAIDA_2);
  }
  
  public function getMarcacaoEntrada3() {
    return $this->getMarcacao(MarcacaoPonto::ENTRADA_3);
  }
  
  public function getMarcacaoSaida3() {  
    return $this->getMarcacao(MarcacaoPonto::SAIDA_3);
  }

  public function getMarcacao($iTipo) {
    return isset($this->aMarcacoes[$iTipo]) ? $this->aMarcacoes[$iTipo] : null;
  }

  public function getMarcacoesEntrada() {
    
    $aMarcacoes   = array();
    
    if($this->getMarcacaoEntrada1() !== null) {
      $aMarcacoes[] = $this->getMarcacaoEntrada1();
    }

    if($this->getMarcacaoEntrada2() !== null) {
      $aMarcacoes[] = $this->getMarcacaoEntrada2();
    }

    return $aMarcacoes;
  }
  
  public function getMarcacoesSaida() {
    
    $aMarcacoes   = array();
    
    if($this->getMarcacaoSaida1() !== null) {
      $aMarcacoes[] = $this->getMarcacaoSaida1();
    }
    
    if($this->getMarcacaoSaida2() !== null) {
      $aMarcacoes[] = $this->getMarcacaoSaida2();
    }

    return $aMarcacoes;
  }

  /**
   * @return MarcacaoPonto[]
   */
  public function getMarcacoes() {
    return $this->aMarcacoes;
  }

  /**
   * @return \ECidade\RecursosHumanos\RH\PontoEletronico\Marcacao\MarcacaoPonto
   */
  public function getUltimaMarcacao() {
    return $this->aMarcacoes[count($this->aMarcacoes)];
  }

  /**
   * @return \ECidade\RecursosHumanos\RH\PontoEletronico\Marcacao\MarcacaoPonto|null
   */
  public function getUltimaMarcacaoComRegistro() {

    $oUltimaMarcacao = null;

    foreach($this->aMarcacoes as $oMarcacao) {

      if ($oMarcacao->getMarcacao() != null) {
        $oUltimaMarcacao = $oMarcacao;
      }
    }

    return $oUltimaMarcacao;
  }

  /** 
   * Verifica se a coleção está vazia
   */
  public function isEmpty() {

    if(empty($this->aMarcacoes)) {
      return true;
    }

    foreach($this->aMarcacoes as $oMarcacao) {

      if($oMarcacao->getMarcacao() != null) {
        return false;
      }
    }

    return true;
  }

  /**
   * @return bool
   */
  public function temTodasMarcacoes() {

    if(count($this->aMarcacoes) < 6) {
      return false;
    }

    return true;
  }

  public function atualizaMarcacao(MarcacaoPonto $oMarcacao) {
    $this->aMarcacoes[$oMarcacao->getTipo()]->setMarcacao($oMarcacao->getMarcacao());
  }

  /**
   * Retorna quantidade de marcações existentes.
   *
   * @return integer
   */
  public function getQuantidadeMarcacoes() {

    $iContador = 0;

    if (empty($this->aMarcacoes)) {
      return $iContador;
    }

    foreach($this->aMarcacoes as $oMarcacao) {

      if($oMarcacao->getMarcacao() != null) {
        $iContador++;
      }
    }

    return $iContador;
  }
}
