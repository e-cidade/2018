<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
 * Model para Consulta as Contas Correntes
 * @author Andrio Costa
 * @package contabilidade
 * @version $Revision: 1.8 $
 */

class ContaCorrente {

  /**
   * C�digo Sequencial da Conta Corrente
   * @var integer
   */
  protected $iCodigo;

  /**
   * C�digo String da Conta Corrente
   * @var string
   */
  protected $sContaCorrente;

  /**
   * Descri��o da Conta Corrente
   * @var string
   */
  protected $sDescricao;

  /**
   * Monta um objeto com as propriedades da Conta Corrente
   * @param integer $iCodigoContaCorrente
   * @return ContaCorrente
   */
  public function __construct($iCodigoContaCorrente = null) {

    if (!empty($iCodigoContaCorrente)) {

      $oDaoContaCorrente = db_utils::getDao("contacorrente");
      $sSqlContaCorrente = $oDaoContaCorrente->sql_query($iCodigoContaCorrente);
      $rsContaCorrente   = $oDaoContaCorrente->sql_record($sSqlContaCorrente);

      if ($oDaoContaCorrente->numrows != 0) {

        $oContaCorrente = db_utils::fieldsMemory($rsContaCorrente, 0);
        $this->setCodigo($oContaCorrente->c17_sequencial);
        $this->setContaCorrente($oContaCorrente->c17_contacorrente);
        $this->setDescricao($oContaCorrente->c17_descricao);
        unset($oContaCorrente);
      }
    }
    return true;
  }


  /**
   * Retorna o c�digo sequencial da conta corrente
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * Seta o c�digo sequencial
   * @param integer $iCodigo
   */
  public function setCodigo($iCodigo) {
    $this->iCodigo = $iCodigo;
  }

  /**
   * Retorna o c�digo da conta corrente: EX: CC 01
   * @return string
   */
  public function getContaCorrente() {
    return $this->sContaCorrente;
  }

  /**
   * Seta o c�digo da conta corrente: EX: CC 01
   * @param string $sContaCorrente
   */
  public function setContaCorrente($sContaCorrente) {
    $this->sContaCorrente = $sContaCorrente;
  }

  /**
   * Retorna a descri��o da conta corrente
   * @return string
   */
  public function getDescricao() {
    return $this->sDescricao;
  }

  /**
   * Seta a descri��o da conta corrente
   * @param unknown $sDescricao
   */
  public function setDescricao($sDescricao) {
    $this->sDescricao = $sDescricao;
  }

  /**
   * M�todo que vincula uma conta corrente a contas cont�beis
   * @param  string $sEstrutural
   * @throws BusinessException
   * @return boolean true
   */
  public function vincularContasContabeisPorEstrutural($sEstrutural) {

    $iAnoSessao         = db_getsession('DB_anousu');
    $iInstituicaoSessao = db_getsession('DB_instit');
    $oDaoConPlanoReduz  = db_utils::getDao('conplanoreduz');
    $sWhereContas       = "    c60_estrut ilike '{$sEstrutural}%' ";
    $sWhereContas      .= "and c60_anousu = {$iAnoSessao} ";
    $sWhereContas      .= "and c61_instit = {$iInstituicaoSessao} ";
    $sWhereContas      .= "and c18_sequencial is null";
    $sSqlBuscaContas    = $oDaoConPlanoReduz->sql_query_analitica(null, null, 'c60_codcon', null, $sWhereContas);

    $rsBuscaConta = $oDaoConPlanoReduz->sql_record($sSqlBuscaContas);

    if ($oDaoConPlanoReduz->numrows == 0) {
      throw new BusinessException("Nenhuma conta cont�bil localizada com o estrutural {$sEstrutural}.");
    }

    $iTotalLinhasContas = $oDaoConPlanoReduz->numrows;
    for ($iRowConta = 0; $iRowConta < $iTotalLinhasContas; $iRowConta++) {

      $iCodigoConta = db_utils::fieldsMemory($rsBuscaConta, $iRowConta)->c60_codcon;

      $sWhereUltimoAno  = " c61_codcon =  {$iCodigoConta} ";
      $sWhereUltimoAno .= "and c61_instit = {$iInstituicaoSessao} ";

      $sSqlUltimoAnoConta = $oDaoConPlanoReduz->sql_query_file(null, null, "max(c61_anousu) as ultimo_ano",
                                                                null, $sWhereUltimoAno
                                                               );

      $rsUltimoAnoConta = $oDaoConPlanoReduz->sql_record($sSqlUltimoAnoConta);
      $iUltimoAno       = db_utils::fieldsMemory($rsUltimoAnoConta, 0)->ultimo_ano;
      for ($iAno = $iAnoSessao; $iAno <= $iUltimoAno; $iAno++) {

        $oDaoContaCorrenteConPlano                    = db_utils::getDao('conplanocontacorrente');
        $oDaoContaCorrenteConPlano->c18_sequencial    = null;
        $oDaoContaCorrenteConPlano->c18_codcon        = $iCodigoConta;
        $oDaoContaCorrenteConPlano->c18_anousu        = $iAno;
        $oDaoContaCorrenteConPlano->c18_contacorrente = $this->getCodigo();
        $oDaoContaCorrenteConPlano->incluir($oDaoContaCorrenteConPlano->c18_sequencial);

        if ($oDaoContaCorrenteConPlano->erro_status == "0") {

          throw new BusinessException("N�o foi poss�vel salvar o v�nculo entre a conta corrente e a conta cont�bil." .
                                      $oDaoContaCorrenteConPlano->erro_msg
                                     );
        }
        unset($oDaoContaCorrenteConPlano);
      }
    }
    return true;
  }

  /**
   * Retorna as contas contabeis vinculadas ao objeto conta corrente
   * @return array ContaPlanoPCASP[]
   */
  public function getContasContabeis() {

    $iAnoSessao                = db_getsession("DB_anousu");
    $iInstituicaoSessao        = db_getsession("DB_instit");
    $aContasContabeis          = array();
    $sWhereContas              = "     c18_contacorrente = {$this->getCodigo()} ";
    $sWhereContas             .= " and c18_anousu        = {$iAnoSessao}";
    $sWhereContas             .= " and c60_anousu        = {$iAnoSessao}";
    $sWhereContas             .= " and c61_anousu        = {$iAnoSessao}";
    $sWhereContas             .= " and c61_instit        = {$iInstituicaoSessao}";
    $oDaoContaCorrenteConPlano = db_utils::getDao('conplanocontacorrente');
    $sSqlBuscaVinculo          = $oDaoContaCorrenteConPlano->sql_query_conplano_contacorrente(null,
                                                                                              'c18_codcon',
                                                                                              'c60_estrut',
                                                                                              $sWhereContas);
    $rsBuscaVinculo            = $oDaoContaCorrenteConPlano->sql_record($sSqlBuscaVinculo);

    if ($oDaoContaCorrenteConPlano->numrows > 0) {

      for ($iRowConta = 0; $iRowConta < $oDaoContaCorrenteConPlano->numrows; $iRowConta++) {

        $iCodigoConta       = db_utils::fieldsMemory($rsBuscaVinculo, $iRowConta)->c18_codcon;
        $aContasContabeis[] = ContaPlanoPcaspRepository::getContaByCodigo($iCodigoConta, $iAnoSessao);
      }
    }
    return $aContasContabeis;
  }

  /**
   * M�todo que exclui o v�nculo de uma conta cont�bil com uma conta corrente
   * @param  integer $iCodigoConta
   * @throws BusinessException
   * @return boolean true
   */
  public function excluirVinculoComConta($iCodigoConta) {

    $iAnoSessao = db_getsession('DB_anousu');

    $sWhereExcluir  = "     c18_codcon = {$iCodigoConta}";
    $sWhereExcluir .= " and c18_anousu >= {$iAnoSessao}";
    $sWhereExcluir .= " and c18_contacorrente = {$this->getCodigo()}";

    $oDaoConPlanoContaCorrente = db_utils::getDao('conplanocontacorrente');
    $oDaoConPlanoContaCorrente->excluir(null, $sWhereExcluir);
    if ($oDaoConPlanoContaCorrente->erro_status == "0") {
      throw new BusinessException("N�o foi poss�vel excluir o v�nculo da conta corrente com a conta cont�bil.");
    }
    return true;
  }


  /**
   * funcao estatica criada para retornar array de atributos da conta corrente passada
   * @param integer $iContaCorrente
   * @return array
   */
  static function getAtributos($iContaCorrente) {

    /*
     * defini��o dos atributos
    */
    switch ($iContaCorrente){

      // disponibilidade financeira
      case 1 :

        $aAtributos["c58_descr"] = "Caract. Peculiar: ";
        $aAtributos["o15_descr"] = "Recurso Vinculado: ";
        $aAtributos["nomeinst"]  = "Institui��o: ";
        $aAtributos["c19_reduz"] = "Conta: ";
        break;

        // Domicilio Bancario
      case 2 :

        $aAtributos["db83_descricao"]     = "Banco: ";
        $aAtributos["db83_dvconta"]       = "D�gito da Conta Corrente: ";
        $aAtributos["db83_conta"]         = "Conta Corrente: ";
        $aAtributos["db83_identificador"] = "D�gito da Ag�ncia: ";
        $aAtributos["db83_bancoagencia"]  = "Ag�ncia Banc�ria: ";
        $aAtributos["nomeinst"]           = "Institui��o: ";
        $aAtributos["c19_reduz"]          = "Conta: ";
        break;

        // credor forncedor devedor
      case 3 :

        $aAtributos["z01_numcgm"] = "C�digo no CGM";
        $aAtributos["z01_nome"]   = "Nome do Credor";
        $aAtributos["z01_cgccpf"] = "CPF / CNPJ";
        $aAtributos["nomeinst"]   = "Institui��o";
        $aAtributos["c19_reduz"]  = "Conta: ";
        break;

        // adiantamentos : concessao, utilizado , devolucao
      case  4 :
      case 19 :

        $aAtributos["z01_numcgm"] = "C�digo no CGM: ";
        $aAtributos["z01_nome"]   = "Nome do Credor: ";
        $aAtributos["z01_cgccpf"] = "CPF / CNPJ: ";
        $aAtributos["o40_descr"]  = "�rg�o: ";
        $aAtributos["o41_descr"]  = "Unidade: ";
        $aAtributos["nomeinst"]   = "Institui��o: ";
        $aAtributos["c19_reduz"]  = "Conta: ";

        break;
      case 25:

        $aAtributos["nomeinst"]    = "Institui��o: ";
        $aAtributos["z01_numcgm"]  = "C�digo Contratado: ";
        $aAtributos["z01_nome"]    = "Nome do Contratado: ";
        $aAtributos["z01_cgccpf"]  = "CPF / CNPJ: ";
        $aAtributos["ac16_numero"] = "N�mero: ";
        $aAtributos["ac16_anousu"] = "Ano: ";
        $aAtributos["c19_reduz"]   = "Conta: ";

        break;


    }

    return $aAtributos;

  }


}