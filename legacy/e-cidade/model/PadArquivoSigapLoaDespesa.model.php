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

require_once ('model/PadArquivoSigap.model.php');
/**
 * Prove dados para a geração do arquivo dos dados da despesa do loa no periodo
 * do municipio para o SIGAP
 * @package Pad
 * @author  Luiz Marcelo Schmitt
 * @version $Revision: 1.3 $
 */
final class PadArquivoSigapLoaDespesa extends PadArquivoSigap {
  
  private $iCodigoVersao;
  /**
   * Metodo construtor da classe
   */
  public function __construct() {
    
    $this->sNomeArquivo = "LoaDespesa";
    $this->aDados       = array();
  }
  
  /**
   * Gera os dados para utilizacao posterior. Metodo geralmente usado 
   * em conjuto com a classe PadArquivoEscritorXML
   * @return true;
   */
  public function gerarDados() {
    
    if (empty($this->sDataInicial)) {
      throw new Exception("Data inicial nao informada!");
    }
    
    if (empty($this->sDataFinal)) {
      throw new Exception("Data final não informada!");
    }
    /**
     * Separamos a data do em ano, mes, dia
     */
    list($iAno, $iMes, $iDia) = explode("-",$this->sDataFinal);
    $oInstituicao  = db_stdClass::getDadosInstit(db_getsession("DB_instit"));
    $sListaInstit  = db_getsession("DB_instit");
    require_once("model/cronogramaMetaDespesa.model.php");
    require_once("model/cronogramaFinanceiro.model.php");
    $oCronograma = new cronogramaFinanceiro($this->iCodigoVersao);
    $aMetas      = $oCronograma->getMetasDespesa(99);
    
     $aMeses = array( 1 => "Janeiro",
                     2 => "Fevereiro", 
                     3 => "Marco",
                     4 => "Abril", 
                     5 => "Maio", 
                     6 => "Junho", 
                     7 => "Julho", 
                     8 => "Agosto", 
                     9 => "Setembro", 
                    10 => "Outubro", 
                    11 => "Novembro", 
                    12 => "Dezembro", 
                   );
     
    $sDiaMesAno =  "{$iAno}-".str_pad($iMes, 2, "0", STR_PAD_LEFT)."-".str_pad($iDia, 2, "0", STR_PAD_LEFT);
    foreach ($aMetas as  $oMeta) {
      
      $oCronograma = new stdClass();
      $oCronograma->ldeCodigoEntidade            = str_pad($this->iCodigoTCE, 4, "0", STR_PAD_LEFT);
      $oCronograma->ldeCodigoFuncao              = $oMeta->o58_funcao;
      $oCronograma->ldeCodigoOrgao               = $oMeta->o58_orgao;
      $oCronograma->ldeCodigoPrograma            = $oMeta->o58_programa;
      $oCronograma->ldeCodigoProjetoAtividade    = $oMeta->o58_projativ;
      $oCronograma->ldeCodigoRecursoVinculado    = $oMeta->o58_codigo;
      $oCronograma->ldeCodigoRubricaDespesa      = $oMeta->o58_codele;
      $oCronograma->ldeCodigoSubFuncao           = $oMeta->o58_subfuncao;
      $oCronograma->ldeCodigoUnidadeOrcamentaria = $oMeta->o58_unidade;
      foreach ($aMeses as $iMes => $sMes) {
        
        $nValorMes = 0;
        if (isset($oMeta->aMetas->aMeses[$iMes-1])) {
          $nValorMes = $oMeta->aMetas->aMeses[$iMes-1]->valor;
        }
        $oCronograma->{"ldeDesembolso{$sMes}"} = $nValorMes;
      }
      $oCronograma->ldeExercicio       = $iAno;
      $oCronograma->ldeMesAnoMovimento = $sDiaMesAno;
      $this->aDados[]                  = $oCronograma;
    }

    
    return true;
  }
  
  /**
   * Publica quais elementos/Campos estão disponiveis para 
   * o uso no momento da geração do arquivo
   *
   * @return array com elementos disponibilizados para a geração dos arquivo
   */
  public function getNomeElementos() {
    
    $aElementos = array(
                         "ldeCodigoEntidade",
                         "ldeCodigoFuncao",
                         "ldeCodigoOrgao",
                         "ldeCodigoPrograma",
                         "ldeCodigoProjetoAtividade",
                         "ldeCodigoRecursoVinculado",
                         "ldeCodigoRubricaDespesa",
                         "ldeCodigoSubFuncao",
                         "ldeCodigoUnidadeOrcamentaria",
                         "ldeDesembolsoJaneiro",
                         "ldeDesembolsoFevereiro",
                         "ldeDesembolsoMarco",
                         "ldeDesembolsoAbril",
                         "ldeDesembolsoMaio",
                         "ldeDesembolsoJunho",
                         "ldeDesembolsoJulho",
                         "ldeDesembolsoAgosto",
                         "ldeDesembolsoSetembro",
                         "ldeDesembolsoOutubro",
                         "ldeDesembolsoNovembro",
                         "ldeDesembolsoDezembro",
                         "ldeExercicio",
                         "ldeMesAnoMovimento"
                       );
    return $aElementos;  
  }
  
  private function corrigeValor($valor, $quant) {
  	
    if (empty($valor)) {
      $valor = 0;
    }
    
    if ($valor < 0) {
      
      $valor *= -1;
      $valor  = "-".str_pad(number_format($valor, 2, ".",""),  $quant-1, '0', STR_PAD_LEFT);
    } else {
      $valor  = str_pad(number_format($valor, 2, ".",""), $quant, '0', STR_PAD_LEFT);
    }
    return $valor;
  }
  public function setCodigoVersao($iCodigoVersao) {
    $this->iCodigoVersao = $iCodigoVersao;
  }
}
?>