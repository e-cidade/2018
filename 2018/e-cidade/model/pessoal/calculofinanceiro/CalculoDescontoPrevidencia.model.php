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

class CalculoDescontoPrevidencia {

  const RUBRICA_DESCONTO_PREVIDENCIA  = 'R993';
  const RUBRICAS_DESCONTO_PREVIDENCIA = "R901,R904,R907,R910,R902,R905,R908,R911,R903,R906,R909,R912";

  private $oCalculo;
  private $oPonto;
  private $oServidor;
  private $oRubricaAbono;

  /**
   * __construct
   *
   * @param Servidor $oServidor
   * @access public
   * @return void
   */
  public function __construct( CalculoFolha $oCalculo) {

    $this->oCalculo      = $oCalculo;
    $this->oServidor     = $oCalculo->getServidor();
    $this->oPonto        = $oCalculo->getServidor()->getPonto(Ponto::FIXO);
    $this->oRubricaAbono = self::getRubricaAbono();
    return;
  }


  /**
   * getRubricaAbono
   *
   * @access public
   * @return void
   */
  public static function getRubricaAbono() {

    $oParametros = ParametrosPessoalRepository::getParametros(
      DBPessoal::getCompetenciaFolha(), 
      InstituicaoRepository::getInstituicaoSessao()
    );

    $oRubrica = $oParametros->getRubricaAbonoPermanencia();

    if ( is_null($oRubrica) ) {
      throw new BusinessException("Rubrica para Abono de Permanência não configurada.");
    }

    return $oParametros->getRubricaAbonoPermanencia();
  }

  /**
   * Valida se o Servidor possui Abono de Permanêmcia.
   *
   * @access private
   * @return Boolean
   */
  private function verificarAbonoPermanencia() {

    return $this->oServidor->hasAbonoPermanencia(); 
  }

  /**
   * Gera evento financeito de abono de permanencia com o valor do desconto de previdencia
   * 
   * @access public
   * @return Boolean
   */
  public function lancarAbonoPermanencia() {

    $aEventos = $this->oCalculo->getEventosFinanceiros(null, explode(",", self::RUBRICAS_DESCONTO_PREVIDENCIA));

    /**
     * Caso não exista desconto de previdencia, não gera rubrica de abono.
     */
    if ( count($aEventos) == 0 ) {
      return false;
    }
    $oEventoDescontoPrevidencia         = $aEventos[0];
  
    $this->oCalculo->limpar( $this->oRubricaAbono->getCodigo() );
    /**
     * Se o servidor não possuí o Abono de permanência, 
     * não executa executa o ajuste de permanência.
     */
    if ( !$this->verificarAbonoPermanencia()) {
      return false;
    }

    $nValor                  = $oEventoDescontoPrevidencia->getValor();
    $oEventoAbonoPermanencia = new EventoFinanceiroFolha();

    if(   $this->oCalculo->getTabela() == CalculoFolha::CALCULO_RESCISAO 
       || $this->oCalculo->getTabela() == CalculoFolha::CALCULO_SALARIO )
    {
      $nValor               = 0;
      foreach ($aEventos as $oEventoDescontoPrevidencia) {
        $nValor             += $oEventoDescontoPrevidencia->getValor();
      }
    }

    if($this->oCalculo->getTabela() == CalculoFolha::CALCULO_RESCISAO) {
      $oEventoAbonoPermanencia = new EventoFinanceiroFolhaRescisao();
      $oEventoAbonoPermanencia->setTipoParaPagamento('S');
    }

    if($this->oCalculo->getTabela() == CalculoFolha::CALCULO_FERIAS) {
      $oEventoAbonoPermanencia = new EventoFinanceiroFolhaFerias();
      $oEventoAbonoPermanencia->setTipoParaPagamento('F');
    }
    LogCalculoFolha::write('Valor do abono de permanencia: '. $nValor);

    $oEventoAbonoPermanencia->setServidor($this->oServidor);
    $oEventoAbonoPermanencia->setRubrica($this->oRubricaAbono);
    $oEventoAbonoPermanencia->setNatureza(EventoFinanceiroFolha::PROVENTO);
    $oEventoAbonoPermanencia->setQuantidade(1);
    $oEventoAbonoPermanencia->setValor($nValor);

    $this->oCalculo->adicionarEvento($oEventoAbonoPermanencia);
    $this->oCalculo->salvar();  

    return true;
  }
}
