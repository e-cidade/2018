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

namespace ECidade\Financeiro\Tesouraria\InfracaoTransito;

/**
 * Class ReceitaInfracao
 * Classe que representa o vinculo de receita com o nivel de infracao.
 * @package ECidade\Financeiro\Tesouraria\InfracaoTransito
 */
class ReceitaInfracao {

  /**
   * @var int
   */
  private $iId;

  /**
   * @var int
   */
  private $iReceitaPrincipal;

  /**
   * @var int
   */
  private $iReceitaDuplicidade;

  /**
   * @var int
   */
  private $iAnoUsu;

  /**
   * @var int
   */
  private $iNivel;

  /**
   * @var int
   */
  private $iConta;


  public function __construct() {

  }

  /**
   * Seta o Id do Objeto
   * @param int $iId [i06_sequencial]
   */
  public function setId($iId){
    $this->iId = $iId;
  }

  /**
   * Retorna o Id do Objeto
   * @return [int] [i06_sequencial]
   */
  public function getId(){
    return $this->iId;
  }

  /**
   * Seta o código do Nivel
   * @param int $iNivel [i06_nivel]
   */
  public function setNivel($iNivel){
    $this->iNivel = $iNivel;
  }

  /**
   * Retorna o código do Nivel
   * @return [int] [i06_nivel]
   */
  public function getNivel(){
    return $this->iNivel;
  }

  /**
   * Seta o Código Receita Principal
   * @param [int] $iReceitaPrincipal [i06_receitaprincipal]
   */
  public function setReceitaPrincipal($iReceitaPrincipal){
    $this->iReceitaPrincipal = $iReceitaPrincipal;
  }

  /**
   * Retorna o código da Receita Principal
   * @return [int] [i06_receitaprincipal]
   */
  public function getReceitaPrincipal(){
    return $this->iReceitaPrincipal;
  }

  /**
   * Seta o Código da Receita para pagamentos em Duplicidade
   * @param [int] $iReceitaDuplicidade [i06_receitaduplicidade]
   */
  public function setReceitaDuplicidade($iReceitaDuplicidade){
    $this->iReceitaDuplicidade = $iReceitaDuplicidade;
  }

  /**
   * Retorna o Código da Receita para pagamentos em Duplicidade
   * @return [int] [i06_receitaduplicidade]
   */
  public function getReceitaDuplicidade(){
    return $this->iReceitaDuplicidade;
  }

  /**
   * Seta o Exercicio da Configuração
   * @param [int] $iAnoUsu [i06_anousu]
   */
  public function setExercicio($iAnoUsu){
    $this->iAnoUsu = $iAnoUsu;
  }

  /**
   * Retorna o Exercicio da Configuracao
   * @return [int] [i06_anousu]
   */
  public function getExercicio(){
    return $this->iAnoUsu;
  }

  /**
   * Seta a Conta da Configuração
   * @param [int] $iConta [i06_conta]
   */
  public function setConta($iConta){
    $this->iConta = $iConta;
  }

  /**
   * Retorna a Conta da Configuracao
   * @return [int] [i06_conta]
   */
  public function getConta(){
    return $this->iConta;
  }

  
  public function validaReceitas() 
  {

    if (empty($this->iReceitaPrincipal)) {
      throw new \Exception("Receita Principal não configurada.");
    }

    if (empty($this->iReceitaDuplicidade)) {
      throw new \Exception("Receita de Pagamentos em Duplicidade não configurada.");
    }

    $oDaoTabRecPrincipal   = new \cl_tabrec();
    $sSqlReceitaPrincipal  = $oDaoTabRecPrincipal->sql_query_file($this->iReceitaPrincipal);
    $rsReceitaPrincipal    = db_query($sSqlReceitaPrincipal);

    if (!empty($rsReceitaPrincipal)) {

      if (pg_num_rows($rsReceitaPrincipal) == 0) {

        throw new \Exception("Receita Principal não localizada, por favor verifique o Código da Receita Principal.");        
      }
    }
    
    $oDaoTabRecDuplicidade  = new \cl_tabrec();
    $sSqlReceitaDuplicidade = $oDaoTabRecPrincipal->sql_query_file($this->iReceitaDuplicidade);
    $rsReceitaDuplicidade   = db_query($sSqlReceitaDuplicidade);

    if (!empty($rsReceitaDuplicidade)) {

      if (pg_num_rows($rsReceitaDuplicidade) == 0) {

        throw new \Exception("Receita de Pagamentos em Duplicidade não localizada, por favor verifique o Código da Receita de Pagamentos em Duplicidade.");        
      }    
    }

    return true;
  }
}