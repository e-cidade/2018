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

/**
 * Representa��o de um lan�amento de RRA
 *
 * @package Pessoal
 * @revision $Author: dbiuri $
 * @version  $Revision: 1.16 $
 */
class LancamentoRRA {

  /**
   *  Sequencial do Lan�amento da tebela lancamentorra
   *  @var integer
   */
  private $iCodigo;

  /**
   *  Assentamento original
   *  @var AssentamentoRRA
   */
  private $oAssentamento;

  /**
   *  Valores que ser�o pago na parcela do lancamentorra(Digitados em tela)
   *  @var number
   */
  private $nValorLancado;
  private $nValorEncargos;
  private $nValorPensao;
  private $nValorBasePrevidencia;
  private $nValorBaseIrrf;

  /**
   * @var PensionistaRRA[]
   */
  private $aPensionistas;

  /**
   *  Resultado do Calculo da Previdencia do RRA sobre a parcela
   *  @var Number
   */
  private $nValorCalculadoPrevidencia = 0.00;

  /**
   *  Resultado do Calculo do IRRF do RRA sobre a parcela
   *  @var Number
   */
  private $nValorCalculadoIrrf = 0.00;

  /**
   *  Lote onde o regisro ser� lan�ado
   *  @var LoteRegistrosPonto
   */
  private $oLoteRegistroPonto = null;

  /**
   * Valor da Parcela Isenta de aposentado/pensionista maior de 65 anos
   *
   * @var $nValorParcelaIsenta
   */
  private $nValorParcelaIsenta = 0;

  /**
   * Valor da Isen�a� por Molestia
   *
   * @var $nValorBaseMolestia
   */
  private $nValorMolestia = 0;


  /**
   *  Numero de meses proporcional ao total do RRA
   *  @var Number
   */
  private $nValorNM = 0.00;

  function __construct($iSequencial = null) {

    if(empty($iSequencial)) {
      return;
    }

    $this->iCodigo = $iSequencial;
    return;
  }


  /**
   * Define o C�digo o Lan�amento de RRA
   *
   * @param Integer
   */
  public function setCodigo ($iCodigo) {
    $this->iCodigo = $iCodigo;
  }

  /**
   * Retorna o C�digo o Lan�amento de RRA
   *
   * @return Integer
   */
  public function getCodigo () {
    return $this->iCodigo; 
  }

  /**
   * Define Assentamento de RRA do lan�amento
   *
   * @param Assentamento
   */
  public function setAssentamento ($oAssentamento) {
    $this->oAssentamento = $oAssentamento;
  }

  /**
   * Retorna Assentamento de RRA do lan�amento
   *
   * @return Assentamento
   */
  public function getAssentamento () {
    return $this->oAssentamento; 
  }

  /**
   * Define o valor lan�ado
   *
   * @param Number
   */
  public function setValorLancado ($nValorLancado) {
    $this->nValorLancado = $nValorLancado;
  }

  /**
   * Retorna o valor lan�ado
   *
   * @return Number
   */
  public function getValorLancado () {
    return $this->nValorLancado; 
  }

  /**
   * Define o valor de encargos para o lan�amento
   *
   * @param Number
   */
  public function setValorEncargos ($nValorEncargos) {
    $this->nValorEncargos = $nValorEncargos;
  }

  /**
   * Retorna o valor de encargos para o lan�amento
   *
   * @return Number
   */
  public function getValorEncargos () {
    return $this->nValorEncargos; 
  }

  /**
   * Define o valor de pens�o para o lan�amento
   *
   * @param Number
   */
  public function setValorPensao ($nValorPensao) {
    $this->nValorPensao = $nValorPensao;
  }

  /**
   * Retorna o valor de pens�o para o lan�amento
   *
   * @return Number
   */
  public function getValorPensao () {
    return $this->nValorPensao; 
  }

  /**
   * Define o valor de previdencia do lan�amento
   *
   * @param Number
   */
  public function setValorBasePrevidencia ($nValorBasePrevidencia) {
    $this->nValorBasePrevidencia = $nValorBasePrevidencia;
  }

  /**
   * Retorna o valor de Base de Previd�ncia do lan�amento
   *
   * @return Number
   */
  public function getValorBasePrevidencia () {
    return $this->nValorBasePrevidencia; 
  }

  /**
   * Define o valor base de IRRF para o lan�amento
   *
   * @param Number
   */
  public function setValorBaseIrrf ($nValorBaseIrrf) {
    $this->nValorBaseIrrf = $nValorBaseIrrf;
  }

  /**
   * Retorna o valor base de IRRF para o lan�amento
   *
   * @return Number
   */
  public function getValorBaseIrrf () {
    return $this->nValorBaseIrrf; 
  }

  /**
   * Define o valor calculado de previd�ncia do lan�amento de RRA
   *
   * @param Number
   */
  public function setValorCalculadoPrevidencia ($nValorCalculadoPrevidencia) {
    $this->nValorCalculadoPrevidencia = $nValorCalculadoPrevidencia;
  }

  /**
   * Retorna o valor calculado de previd�ncia do lan�amento de RRA
   *
   * @return Number
   */
  public function getValorCalculadoPrevidencia () {
    return $this->nValorCalculadoPrevidencia; 
  }

  /**
   * Define o valor calculado de IRRF do lan�amento de RRA
   *
   * @param $nValorCalculadoIrrf
   */
  public function setValorCalculadoIrrf ($nValorCalculadoIrrf) {
    $this->nValorCalculadoIrrf = $nValorCalculadoIrrf;
  }

  /**
   * Retorna o valor calculado de IRRF do lan�amento de RRA
   *
   * @return $nValorCalculadoIrrf
   */
  public function getValorCalculadoIrrf () {
    return $this->nValorCalculadoIrrf; 
  }

  /**
   * Define o Lote de Registros do Ponto ao qual o lan�amento est� vinculado
   *
   * @param LoteRegistroPonto
   */
  public function setLoteRegistroPonto ($oLoteRegistroPonto) {
    $this->oLoteRegistroPonto = $oLoteRegistroPonto;
  }

  /**
   * Retorna o Lote de Registros do Ponto ao qual o lan�amento est� vinculado
   *
   * @return LoteRegistrosPonto
   */
  public function getLoteRegistroPonto () {
    return $this->oLoteRegistroPonto; 
  }

  /**
   * Define o valor da Parcela isenta
   * @param Number
   */
  public function setValorParcelaIsenta ($nValorParcelaIsenta) {
    $this->nValorParcelaIsenta = $nValorParcelaIsenta;
  }

  /**
   * Retorna o valor da Parcela isenta
   * @return Number
   */
  public function getValorParcelaIsenta () {
    return $this->nValorParcelaIsenta; 
  }

  /**
   * Define o valor da Isen��o de Mol�stia
   * @param Number
   */
  public function setValorMolestia ($nValorMolestia) {
    $this->nValorMolestia = $nValorMolestia;
  }

  /**
   * Retorna o valor da Isen��o de Mol�stia
   * @return Number
   */
  public function getValorMolestia () {
    return $this->nValorMolestia; 
  }

  /**
   * Respons�vel por calcular os valores de previd�ncia, IRRF e dedu��es
   */
  public function calcular () {

    $this->calcularNM();
    $this->calcularPrevidencia();
    $this->calcularIRRF();
  }

  /**
   * Calcula o valor do NM a ser utilizado no c�lculo do IRRF
   */
  private function calcularNM() {

    $this->nValorNM = $this->arredondarNM($this->oAssentamento->getNumeroDeMeses() * ($this->nValorLancado / $this->oAssentamento->getValorTotalDevido()));
  }

  /**
   *  Arredonda o numero de meses da parcela la�ada
   *
   *  @param  Number $nNM Quantidade relativa de meses do total quanto a parcela
   *  @return float Valor arredondado
   */
  public function arredondarNM($nNM) {

    $nNM = number_format($nNM , 3 , "." , "" );

    /**
     * Verifica se o valor � decimal
     */
    if(preg_match('/\.(\d)?(\d)?(\d)?/', $nNM, $nDecimais)) {

      /**
       *  For�ando ser inteiro os resultados dos decimais
       */
      if(isset($nDecimais[1])) {
        $nDecimais[1] = (int)$nDecimais[1];
      }

      if(isset($nDecimais[2])) {
        $nDecimais[2] = (int)$nDecimais[2];
      }

      if(isset($nDecimais[3])) {
        $nDecimais[3] = (int)$nDecimais[3];
      }

      if (!isset($nDecimais[2])) {
        return round($nNM, 1);
      }

      /**
       *  valida se o valor da segunda casa � menor que 5
       */
      if($nDecimais[2] <> 5) {
        return round($nNM, 1);
      }

      /**
       *  Se for 5 verifica a 3 casa decimal
       */
      if(!isset($nDecimais[3]) || $nDecimais[3] < 5) {

        $nNM = preg_replace("/(\d+)(\.)(\d+)/", "$1$2", $nNM) . $nDecimais[1];
        return (float)number_format($nNM, 1, ".", "");
      }

      return round($nNM, 1);

    }
    return $nNM;
  }

  /**
   * Respons�vel por calcular o valor de previd�ncia para a parcela do RRA
   * @return float
   * @throws \BusinessException
   */
  private function calcularPrevidencia () {

    $oServidor                   = $this->getAssentamento()->getServidor();
    $oVinculo                    = $oServidor->getVinculo();
    $lServidorInativoPensionista = ($oVinculo->getTipo() == VinculoServidor::VINCULO_INATIVO || VinculoServidor::VINCULO_PENSIONISTA);

    if (!$oServidor) {
      throw new BusinessException("Erro ao buscar o servidor");
    }
    $iCodigoPrevidencia = $oServidor->getTabelaPrevidencia();
    /**
     * Caso nao exista tabela cadastrada para o servidor, zeramos a base de calculo,
     * e retornamos como valor calculado "0"
     */
    if (empty($iCodigoPrevidencia)) {

      $this->nValorBasePrevidencia = 0;
      return 0;
    }
    $iCodigoPrevidencia = $iCodigoPrevidencia + 2; // Para pular as tabelas de IRRF da estrutura antiga



    if (!$iCodigoPrevidencia) {
      throw new BusinessException("Erro ao buscar a tabela de previd�ncia do servidor({$oServidor->getMatricula()})");
    }

    $oTabelaPrevidencia = TabelaValoresPrevidenciaRepository::getByCodigo($iCodigoPrevidencia);
    $oFaixaPrevidencia  = $oTabelaPrevidencia->getFaixaPeloValor($this->nValorBasePrevidencia);

    /**
     * Verificamos se o servidor � inativo, e realizamos o c�lculo do teto para inativos.
     */
    $nValorBasePrevidencia = $this->nValorBasePrevidencia;
    if ($lServidorInativoPensionista) {

      $nValorPrevidenciaInativo = 0;

      if ($nValorBasePrevidencia > $oFaixaPrevidencia->getTetoInativos()){
        $nValorPrevidenciaInativo = $nValorBasePrevidencia  - $oFaixaPrevidencia->getTetoInativos();
      }

      $nValorBasePrevidencia = $nValorPrevidenciaInativo;
      $oFaixaPrevidencia     = $oTabelaPrevidencia->getFaixaPeloValor($nValorBasePrevidencia);
    }

    if ($nValorBasePrevidencia > $oTabelaPrevidencia->getUltimaFaixa()->getFim()) {
      $nValorBasePrevidencia = $oTabelaPrevidencia->getUltimaFaixa()->getFim();
    }


    $this->nValorCalculadoPrevidencia = (float)$nValorBasePrevidencia * ((float)$oFaixaPrevidencia->getPercentual()/100);

    if ($this->nValorCalculadoPrevidencia < 0) {
      throw new BusinessException("Valor calculado para previd�ncia, negativo.");
    }

    return $this->nValorCalculadoPrevidencia;
  }

  /**
   * Respons�vel por calcular o valor de IRRF para a parcela do RRA
   */
  private function calcularIRRF () {

    /**
     * Inicilializando valores de vari�veis locais para o c�lculo
     */
    $nValorBaseIrrf            = $this->nValorBaseIrrf;
    $nValorPrevidencia         = $this->nValorCalculadoPrevidencia;
    $nValorParcelaIsenta       = 0;
    $this->nValorCalculadoIrrf = 0;
    $oServidor                 = $this->oAssentamento->getServidor();

    /**
     * Deduz da base de c�lculo de IRRF os valores de previd�nciam, encargos judiciais e pens�o
     */
    $nValorBaseIrrf     -= $nValorPrevidencia;
    $nValorBaseIrrf     -= $this->nValorEncargos;
    $nValorBaseIrrf     -= $this->nValorPensao;

    /**
     * Busca nos par�metros configurados a tabela de IRRF selecionada e multiplica pelo NM,
     * para obter as faixas j� ajustadas para o c�lculo
     */
    $oParametros    = ParametrosPessoalRepository::getParametros(DBPessoal::getCompetenciaFolha());

    /**
     * Verifica se foi poss�vel obter as faixas da tabela configurada
     */
    if(!DBNumber::isInteger($oParametros->getCodigoTabelaIRRFRRA())) {
      throw new BusinessException("C�digo da tabela de valores do IRRF sobre o RRA n�o est� configurado.");
    }

    $sFormula = '(NM = '.$this->oAssentamento->getNumeroDeMeses().' * ('.$this->nValorLancado.' / '.$this->oAssentamento->getValorTotalDevido().'))';
    $sMsg     = "A seguinte f�rmula foi executada: {$sFormula}\nO valor do NM est� zerado, portanto o c�lculo n�o pode ser realizado.\n\nContate o suporte.";

    if($this->nValorNM == 0) {
      throw new BusinessException($sMsg);
    }

    $oTabelaIRRFRRA = TabelaValoresRRARepository::getByCodigo($oParametros->getCodigoTabelaIRRFRRA());
    $oTabelaIRRFRRA->setNm($this->nValorNM);
    $oTabelaIRRFRRA->multiplicarNM();
    $aFaixasIRRFRRA = $oTabelaIRRFRRA->getFaixas();

    if(count($aFaixasIRRFRRA) <> 5) {
      throw new BusinessException("As faixas da tabela de valores do IRRF sobre o RRA est�o inconsistentes.\nS�o necess�rias 5 faixas para o funcionamento do c�lculo.");
    }

    /**
     * Percorre as faixas para obter o valor da parcela isenta, � o m�ximo da primeira faixa (onde percentual for 0)
     */
    foreach ($aFaixasIRRFRRA as $oFaixa) {

      if((float)$oFaixa->getPercentual() == 0) {

        $nValorParcelaIsenta = $oFaixa->getFim();
      }
    }

    /**
     * Verifica se o servidor possui mol�stia, caso possua n�o deve calcular o IRRF, para isso zera a base
     */
    if ($oServidor->getMolestiaGrave() && $oServidor->getVinculo()->getTipo() <> VinculoServidor::VINCULO_ATIVO) {

      $nValorBaseIrrf = 0;
      $this->nValorMolestia = $this->nValorBaseIrrf;

    } else {

      /**
       * Verifica se o servidor � inativo/aposentado e tem mais de 65 anos para abater parcela de dedu��o
       */
      if ((int)$oServidor->getIdade() >= 65) {

        switch ($oServidor->getVinculo()->getTipo()) {

        case VinculoServidor::VINCULO_INATIVO:
        case VinculoServidor::VINCULO_PENSIONISTA:

          $nValorBaseIrrf            -= $nValorParcelaIsenta;
          $this->nValorParcelaIsenta  = $nValorParcelaIsenta;
          if ($nValorBaseIrrf < 0) {
            $nValorBaseIrrf = 0;
          }

          if ($this->nValorParcelaIsenta > $this->getValorLancado()) {
            $this->setValorParcelaIsenta($this->getValorLancado());
          }
          break;
        }
      }
    }

    /**
     * Verifica se ap�s todas as dedu��es ainda sobrou valor de base para c�lculo do IRRF
     */
    if($nValorBaseIrrf > 0) {

      /**
       * Percorre novamente as faixas para verificar em qual se enquadra o valor obtido
       */
      foreach ($aFaixasIRRFRRA as $oFaixa) {

        if( ($nValorBaseIrrf >= $oFaixa->getInicio()) && ($nValorBaseIrrf <= $oFaixa->getFim()) ) {
          $oFaixaUtilizar = $oFaixa;
        }
      }

      /**
       * Se n�o se enquadrou em nenhuma faixa n�o deve seguir pois torna-se imposs�vel aplicar
       * percentual e dedu��o no imposto
       */
      if(!isset($oFaixaUtilizar)) {
        throw new BusinessException("N�o foi poss�vel verificar a faixa que se encontra a base de IRRF");
      }

      $this->nValorCalculadoIrrf = $nValorBaseIrrf * ($oFaixaUtilizar->getPercentual()/100) - $oFaixaUtilizar->getDeducao();
    }
  }

  /**
   * Retorna os valores dos pensionistas do RRA
   * @return \PensionistaRRA[]
   * @throws \DBException
   */
  public function getPensionistas() {

    if (!empty($this->aPensionistas)) {
      return $this->aPensionistas;
    }
    $oDaoLancamentoRRAPensionista = new cl_lancamentorrapensionista();
    $sWhere           = "rh201_lancamentorra = {$this->getCodigo()}";
    $sSqlPensionistas = $oDaoLancamentoRRAPensionista->sql_query(null, "z01_numcgm, z01_nome,rh201_valor", "z01_nome", $sWhere);
    $rsPensionistas   = db_query($sSqlPensionistas);
    if (!$rsPensionistas) {
      throw new DBException("Erro ao pesquisar dados dos pensionistas do RRA.");
    }
    $this->aPensionistas  = db_utils::makeCollectionFromRecord($rsPensionistas, function($oDadosPensionista) {

      $oPensionista = new PensionistaRRA();
      $oPensionista->setPensionista(CgmRepository::getByCodigo($oDadosPensionista->z01_numcgm));
      $oPensionista->setValor($oDadosPensionista->rh201_valor);
      return $oPensionista;
    });
    return $this->aPensionistas;
  }

  /**
   * Adiciona um pensionista ao lan�amento
   * @param \CgmFisico $cgmFisico
   * @param            $valor
   * @return \PensionistaRRA
   */
  public function adicionarPensionista(CgmFisico $cgmFisico, $valor) {

    $aPensionistas = array();
    if (!$this->iCodigo == '') {
      $aPensionistas = $this->getPensionistas();
    }
    foreach ($aPensionistas as $oPensionista) {

      if ($oPensionista->getPensionista()->getCodigo() == $cgmFisico->getCodigo()) {

        $oPensionista->setValor($valor);
        return $oPensionista;
      }
    }
    $oPensionista = new PensionistaRRA();
    $oPensionista->setPensionista($cgmFisico);
    $oPensionista->setValor($valor);
    $this->aPensionistas[] = $oPensionista;
    return $oPensionista;

  }
}
