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
 
db_app::import('pessoal.CalculoFolha');

/**
 * Definiões sobre o Calculo de Ferias de um servidor em uma competencia
 * 
 * @uses    Ponto
 * @package Pessoal 
 * @author  Renan Melo <renan@dbseller.com.br> 
 */
class CalculoFolhaFerias extends CalculoFolha {
  
  const MENSAGEM     = "recursoshumanos.pessoal.CalculoFolhaFerias.";
  const TABELA       = "gerffer";   
  const SIGLA_TABELA = "r31";       
  
  /**
   * Construtor da classe
   * 
   * @param Servidor $oServidor
   */
  public function __construct ( Servidor $oServidor) {

    parent::__construct($oServidor);

    $this->sTabela = self::TABELA;
    $this->sSigla  = self::SIGLA_TABELA;
  } 
  
  /**
   * Função para gerar ponto para o mes selecionado
   */
  public function calcular() {}

  public function gerar() {}

  /**
   * Retorna o valor maior entre as bases de salário e férias
   * 
   * @param DBCompetencia $oCompetencia
   * @param Instituicao $oInstituicao
   * @return Float
   * @throws DBException
   */
  public function getValorComparativo( DBCompetencia $oCompetencia, Instituicao $oInstituicao) {
      
    LogCalculoFolha::write("========================INICIANDO COMPARATIVO DE 1/3 FÉRIAS========================");

    $fValorSalario = 0;
    $fValorFerias  = 0;
    
    $oParametros  = ParametrosPessoalRepository::getParametros($oCompetencia, $oInstituicao);
    
    if ($oParametros->getBaseSalarioComparativo()) {
      $oBaseSalario = BaseRepository::getBase($oParametros->getBaseSalarioComparativo(), $oCompetencia, $oInstituicao);
    }

    if ($oParametros->getBaseFeriasComparativo()) {
      $oBaseFerias  = BaseRepository::getBase($oParametros->getBaseFeriasComparativo(), $oCompetencia, $oInstituicao);
    }

    LogCalculoFolha::write("Base de salário cadastrada na manutenção de parâmetros: ".$oBaseSalario->getCodigo());
    LogCalculoFolha::write("Base de férias cadastrada na manutenção de parâmetros: ".$oBaseFerias->getCodigo());
    
    $aRubricasSalario = $oBaseSalario->getRubricasBaseServidor($this->getServidor());
    $aRubricasFerias  = $oBaseFerias->getRubricasBaseServidor($this->getServidor());
    
    $aEventosFinancerosSalario = $this->getServidor()->getCalculoFinanceiro(CalculoFolha::CALCULO_SALARIO)->getEventosFinanceiros(0, $aRubricasSalario);
    $aEventosFinancerosFerias  = $this->getServidor()->getCalculoFinanceiro(CalculoFolha::CALCULO_FERIAS)->getEventosFinanceiros(0, $aRubricasFerias);
    
    LogCalculoFolha::write("Rubricas/valor a serem calculadas da base ".$oBaseFerias->getCodigo().":");
    
    if (!$aEventosFinancerosFerias) { 
      LogCalculoFolha::write("Nenhuma rubrica a ser calculada.");
    }

    foreach ($aEventosFinancerosFerias as $oEventoFinanceiro) {

      LogCalculoFolha::write("Rubrica:".$oEventoFinanceiro->getRubrica()->getCodigo()." Valor:".$oEventoFinanceiro->getValor());
      $fValorFerias += $oEventoFinanceiro->getValor();
    }
    
    LogCalculoFolha::write("Base: ".$oBaseFerias->getCodigo(). " Valor total: ".$fValorFerias);
    LogCalculoFolha::write("Rubricas/valor a serem calculadas da base ".$oBaseSalario->getCodigo().":");
    
    if (!$aEventosFinancerosSalario) {
      LogCalculoFolha::write("Nenhuma rubrica a ser calculada.");
    }

    foreach ($aEventosFinancerosSalario as $oEventoFinanceiro) {
  
      LogCalculoFolha::write("Rubrica:".$oEventoFinanceiro->getRubrica()->getCodigo()." Valor:".$oEventoFinanceiro->getValor());
      $fValorSalario += $oEventoFinanceiro->getValor();
    }

    LogCalculoFolha::write("Base: ".$oBaseSalario->getCodigo()." Valor total: ".$fValorSalario);

    if ($fValorSalario > $fValorFerias) {
      LogCalculoFolha::write("Base de salário maior. Valor do 1/3 de férias: ".$fValorSalario/3);
    } else {
      LogCalculoFolha::write("Base de férias maior. Valor do 1/3 de férias: ".$fValorFerias/3);
    }

    LogCalculoFolha::write("========================FINAL COMPARATIVO DE 1/3 FÉRIAS========================");
    
    return ($fValorSalario > $fValorFerias) ? $fValorSalario/3 : $fValorFerias/3;
  }
    
}