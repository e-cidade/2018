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
 * Prove dados para a geração do arquivo dos dados da receita do loa no periodo
 * do municipio para o SIGAP
 * @package Pad
 * @author  Luiz Marcelo Schmitt
 * @version $Revision: 1.3 $
 */
final class PadArquivoSigapLoaReceita extends PadArquivoSigap {
  
  private $iCodigoVersao;
  /**
   * Metodo construtor da classe
   */
  public function __construct() {
    
    $this->sNomeArquivo = "LoaReceita";
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
    require_once("model/cronogramaMetaReceita.model.php");
    require_once("model/cronogramaFinanceiro.model.php");
    $oCronograma   = new cronogramaFinanceiro($this->iCodigoVersao);
    $aMetas        = $oCronograma->getMetasReceita();
    $sDiaMesAno    =  "{$iAno}-".str_pad($iMes, 2, "0", STR_PAD_LEFT)."-".str_pad($iDia, 2, "0", STR_PAD_LEFT);
    $iTamanhoCampo = strlen($oInstituicao->codtrib);
    if ($iTamanhoCampo != 4) {
        
      $sMsg  = "Identificação do Orgão/Unidade da instituição ({$oInstituicao->codtrib}) está incorreto. \\n ";
      $sMsg .= "Solicitar para o setor responsável pela configuração do sistema a alteração da informação no Menu: \\n \\n "; 
      $sMsg .= "Configuração -> Cadastros -> Instituições -> Alteração. ";
      
      throw new Exception($sMsg);
    }
    $sOrgao   = substr($oInstituicao->codtrib, 0, 2);
    $sUnidade = substr($oInstituicao->codtrib, 2, 2);
    /*
     * percorremos as metas informadas para a receita
     */
    foreach ($aMetas as $oMeta) {
      
      if (empty($oMeta->o70_codigo)) {
        continue;
      }
      $iNivelConta = 1;
      $sSqlNivel = "select fc_nivel_plano2005(rpad('{$oMeta->o57_fonte}',20,0)) as nivel";
      $rsNivel   = db_query($sSqlNivel);
      $iNivelConta = db_utils::fieldsMemory($rsNivel, 0)->nivel;
      $oCronograma = new stdClass();
      $oCronograma->lreCodigoEntidade            = str_pad($this->iCodigoTCE, 4, "0", STR_PAD_LEFT);
      $oCronograma->lreCodigoContaReceita        = str_pad($oMeta->o57_fonte, 20, "0", STR_PAD_RIGHT);
      $oCronograma->lreCodigoOrgao               = $sOrgao;
      $oCronograma->lreCodigoRecursoVinculado    = str_pad($oMeta->o70_codigo, 6, "0", STR_PAD_LEFT);
      $oCronograma->lreCodigoUnidadeOrcamentaria = $sUnidade;
      $oCronograma->lreDescricao                 = urldecode($oMeta->o57_descr);
      $oCronograma->lreExercicio                 = $iAno;
      $oCronograma->lreMesAnoMovimento           = $sDiaMesAno;
      $nValor1Bim = 0;
      $nValor2Bim = 0;
      $nValor3Bim = 0;
      $nValor4Bim = 0;
      $nValor5Bim = 0;
      $nValor6Bim = 0;
      $nValor1Bim += $oMeta->aMetas->aMeses[0]->valor + $oMeta->aMetas->aMeses[1]->valor;
      $nValor2Bim += $oMeta->aMetas->aMeses[2]->valor + $oMeta->aMetas->aMeses[3]->valor;
      $nValor3Bim += $oMeta->aMetas->aMeses[4]->valor + $oMeta->aMetas->aMeses[5]->valor;
      $nValor4Bim += $oMeta->aMetas->aMeses[6]->valor + $oMeta->aMetas->aMeses[7]->valor;
      $nValor5Bim += $oMeta->aMetas->aMeses[8]->valor + $oMeta->aMetas->aMeses[9]->valor;
      $nValor6Bim += $oMeta->aMetas->aMeses[10]->valor + $oMeta->aMetas->aMeses[11]->valor;
      $oCronograma->lreMetaArrecadacao1oBimestre = $nValor1Bim;
      $oCronograma->lreMetaArrecadacao2oBimestre = $nValor2Bim;
      $oCronograma->lreMetaArrecadacao3oBimestre = $nValor3Bim;
      $oCronograma->lreMetaArrecadacao4oBimestre = $nValor4Bim;
      $oCronograma->lreMetaArrecadacao5oBimestre = $nValor5Bim;
      $oCronograma->lreMetaArrecadacao6oBimestre = $nValor6Bim;
      $oCronograma->lreNumNivelConta             = str_pad($iNivelConta, 2, "0", STR_PAD_LEFT);
      $oCronograma->lreTiponivelConta            = '02';
      $oCronograma->lreValorReceitaOrcada        = $oMeta->o70_valor;
      $this->aDados[] = $oCronograma;
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
                         "lreCodigoEntidade",
                         "lreCodigoContaReceita",
                         "lreCodigoOrgao",
                         "lreCodigoRecursoVinculado",
                         "lreCodigoUnidadeOrcamentaria",
                         "lreDescricao",
                         "lreExercicio",
                         "lreMesAnoMovimento",
                         "lreMetaArrecadacao1oBimestre",
                         "lreMetaArrecadacao2oBimestre",
                         "lreMetaArrecadacao3oBimestre",
                         "lreMetaArrecadacao4oBimestre",
                         "lreMetaArrecadacao5oBimestre",
                         "lreMetaArrecadacao6oBimestre",
                         "lreNumNivelConta",
                         "lreTiponivelConta",
                         "lreValorReceitaOrcada"
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