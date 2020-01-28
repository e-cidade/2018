<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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
 * Prove dados para a geração do arquivo de Receita no periodo anterior 
 * do municipio para o SIGAP
 * @package Pad
 * @author  Iuri Guncthnigg
 * @version $Revision: 1.1 $
 */
final class PadArquivoSigapReceitaAnterior extends PadArquivoSigap {
  
  /**
   * 
   */
  public function __construct() {
    
    $this->sNomeArquivo = "ReceitaAnterior";
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
    $oInstituicao = db_stdClass::getDadosInstit(db_getsession("DB_instit"));
    $sListaInstit  = db_getsession("DB_instit");
    $oReceitaMes = new cl_receita_saldo_mes;
    $oReceitaMes->dtini               = (db_getsession('DB_anousu')-1)."-01-01";
    $oReceitaMes->dtfim               = (db_getsession('DB_anousu')-1)."-12-31";
    $oReceitaMes->anousu              = (db_getsession('DB_anousu')-1);;
    $oReceitaMes->instit              = $sListaInstit;
    $oReceitaMes->lPrevisaoCronograma = true;
    $oReceitaMes->sql_record();
    
    $iTotalLinhas = pg_num_rows($oReceitaMes->result);
    for ($i = 1; $i < $iTotalLinhas; $i++) {
      
      /**
       * Acertamos o estrutural, caso seje receita, devemos retirar o primeiro caracter;
       */
      $oReceita = db_utils::fieldsMemory($oReceitaMes->result, $i);
      if ($oReceita->o70_anousu > 2007) {
        
        if (db_conplano_grupo($oReceita->o70_anousu,substr($oReceita->o57_fonte,0,1)."%", 9000) == false) {
          $oReceita->o57_fonte = str_pad(substr($oReceita->o57_fonte,1,14), 20, '0', STR_PAD_RIGHT); // recompisoção
        } else {
          $oReceita->o57_fonte  = str_pad(substr($oReceita->o57_fonte,0,15), 20, '0', STR_PAD_RIGHT); // recompisoção
        }
      } else {
        $oReceita->o57_fonte = str_pad(substr($oReceita->o57_fonte,1,14), 20, '0', STR_PAD_RIGHT); // recompisoção
      }
      /**
       * AJuste no mes de dezemro, quando conta de Deducao
       */
      if (db_conplano_grupo($oReceita->o70_anousu, substr($oReceita->o57_fonte,0,2)."%",9000) == true) {  // 49

        if ($oReceita->dezembro <> 0) {
          $oReceita->dezembro = abs($oReceita->dezembro) *-1;
        }
        
        if ($oReceita->prev_dez <> 0) {
          $oReceita->prev_dez = abs($oReceita->prev_dez) *-1;
        }
      }
      
      $sDiaMesAno =  "{$iAno}-".str_pad($iMes, 2, "0", STR_PAD_LEFT)."-".str_pad($iDia, 2, "0", STR_PAD_LEFT);
      $oReceitaRetorno = new stdClass();
      $oReceitaRetorno->reaCodigoEntidade                 = str_pad($this->iCodigoTCE, 4, "0", STR_PAD_LEFT);
      $oReceitaRetorno->reaMesAnoMovimento                = $sDiaMesAno;
      $oReceitaRetorno->reaCodigoContaReceita             = str_pad($oReceita->o57_fonte, 20, 0, STR_PAD_RIGHT);
      $oReceitaRetorno->reaCodigoOrgaoUnidadeOrcamentaria = str_pad($oInstituicao->codtrib, 4, "0", STR_PAD_LEFT);
      $oReceitaRetorno->reaRealizadaJaneiro               = $this->corrigeValor($oReceita->janeiro, 13);
      $oReceitaRetorno->reaRealizadaFevereiro             = $this->corrigeValor($oReceita->fevereiro, 13);
      $oReceitaRetorno->reaRealizadaMarco                 = $this->corrigeValor($oReceita->marco, 13);
      $oReceitaRetorno->reaRealizadaAbril                 = $this->corrigeValor($oReceita->abril, 13);
      $oReceitaRetorno->reaRealizadaMaio                  = $this->corrigeValor($oReceita->maio, 13);
      $oReceitaRetorno->reaRealizadaJunho                 = $this->corrigeValor($oReceita->junho, 13);
      $oReceitaRetorno->reaRealizadaJulho                 = $this->corrigeValor($oReceita->julho, 13);
      $oReceitaRetorno->reaRealizadaAgosto                = $this->corrigeValor($oReceita->agosto, 13);
      $oReceitaRetorno->reaRealizadaSetembro              = $this->corrigeValor($oReceita->setembro, 13);
      $oReceitaRetorno->reaRealizadaOutubro               = $this->corrigeValor($oReceita->outubro, 13);
      $oReceitaRetorno->reaRealizadaNovembro              = $this->corrigeValor($oReceita->novembro, 13);
      $oReceitaRetorno->reaRealizadaDezembro              = $this->corrigeValor($oReceita->dezembro, 13);
      $this->aDados[] =  $oReceitaRetorno; 
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
                        "reaCodigoEntidade",
                        "reaMesAnoMovimento",
                        "reaCodigoContaReceita",
                        "reaCodigoOrgaoUnidadeOrcamentaria",
                        "reaRealizadaJaneiro",
                        "reaRealizadaFevereiro",
                        "reaRealizadaMarco",
                        "reaRealizadaAbril",
                        "reaRealizadaMaio",
                        "reaRealizadaJunho",
                        "reaRealizadaJulho",
                        "reaRealizadaAgosto",
                        "reaRealizadaSetembro",
                        "reaRealizadaOutubro",
                        "reaRealizadaNovembro",
                        "reaRealizadaDezembro"
                       );
    return $aElementos;  
  }
  
  private function corrigeValor ($valor, $quant) {
    
    if ($valor < 0) {
      
      $valor *= -1;
      $valor  = "-".str_pad(number_format($valor, 2, ".",""),  $quant-1, '0', STR_PAD_LEFT);
    } else {
      $valor  = str_pad(number_format($valor, 2, ".",""), $quant, '0', STR_PAD_LEFT);
    }
    return $valor;
  }
}

?>