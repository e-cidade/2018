<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

require_once ('interfaces/iPadArquivoTxtBase.interface.php');
require_once ('model/contabilidade/arquivos/sigfis/SigfisArquivoBase.model.php');
require_once ('model/ppaVersao.model.php');
require_once ('model/ppadespesa.model.php');

/**
 * 
 * Retorna os dados dos Indicadores
 * @package contabilidade
 * @subpackage sigfis
 * @author Iuri Guntchnigg
 *
 */
class SigfisArquivoIndicadorPpa extends SigfisArquivoBase implements iPadArquivoTXTBase {
  
  protected $iCodigoLayout = 111;
  protected $sNomeArquivo  = 'IndProg';
  
  /**
   * gera os dados para o arquivo
   */
  public function gerarDados() {
   
    $oDaoPpaintegracao    = db_utils::getDao('ppaintegracao');
    $oDaoOrcprograma      = db_utils::getDao('orcindicaprograma');
    
    $iAnoSessao           = db_getsession('DB_anousu');
    $iInstituicaoSessao   = db_getsession('DB_instit');
    $oDadosInstit         = db_stdClass::getDadosInstit();
    
    /**
     * Retorna qual versao do ppa que foi realizada a integração com o orçamento
     */
    $sCampos               = "     ppaintegracao.*                                           ";
    $sWhereBuscaVersaoPpa  = "     ppaintegracao.o123_ano            = {$iAnoSessao}         ";
    $sWhereBuscaVersaoPpa .= " AND ppaintegracao.o123_situacao       = 1                     ";
    $sWhereBuscaVersaoPpa .= " AND ppaintegracao.o123_tipointegracao = 1                     ";
    $sWhereBuscaVersaoPpa .= " AND ppaintegracao.o123_instit         = {$iInstituicaoSessao} ";
    $sSqlBuscaVersaoPpa    = $oDaoPpaintegracao->sql_query_versaoppa(null, $sCampos, null, $sWhereBuscaVersaoPpa);
    $rsSqlBuscaVersaoPpa   = $oDaoPpaintegracao->sql_record($sSqlBuscaVersaoPpa);
    $this->addLog("==== Iniciando Processamento Arquivo {$this->getNomeArquivo()} ======\n");
    if ($oDaoPpaintegracao->numrows == 1) {
     
      $oVersao     = db_utils::fieldsMemory($rsSqlBuscaVersaoPpa, 0);
      $oPPAVersao  = new ppaVersao($oVersao->o123_ppaversao);
      $oPPADespesa = new ppaDespesa($oVersao->o123_ppaversao);
      /**
       * Montamos o quadro das estimativas pelo programa
       */
      $aProgramas  = $oPPADespesa->getQuadroEstimativas(null, 5);
      foreach ($aProgramas as $oPrograma) {
        
        $sWhere        = " o18_orcprograma = {$oPrograma->iCodigo} ";
        $sWhere       .= " and o18_anousu  = {$iAnoSessao} ";
        $sSqlIncadores = $oDaoOrcprograma->sql_query(null, "orcindica.*", null, $sWhere);
        $rsIndicadores = $oDaoOrcprograma->sql_record($sSqlIncadores);
        if ($oDaoOrcprograma->numrows > 0) {

          if (empty($this->sCodigoTribunal)) {
            throw new Exception("O código do tribunal deve ser informado para geração do arquivo");
          }
          for ($iIndicador = 0 ; $iIndicador < $oDaoOrcprograma->numrows; $iIndicador++) {
            
            $oDadosIndicador                     = db_utils::fieldsMemory($rsIndicadores, $iIndicador);
            $oDadosPrograma                      = new stdClass();
            $oDadosPrograma->codigolinha         = 398;
            $oDadosPrograma->nu_Indicador        = str_pad($oDadosIndicador->o10_indica,  6, " ", STR_PAD_LEFT);
            $oDadosPrograma->cd_Unidade          = str_pad($this->sCodigoTribunal,        4, " ", STR_PAD_LEFT);
            $oDadosPrograma->cd_SubPrograma      = str_pad($oPrograma->iCodigo,           4, "0", STR_PAD_LEFT);
            $oDadosPrograma->de_Indicador        = str_pad($oDadosIndicador->o10_descr, 120, " ", STR_PAD_RIGHT);
            $nValorInicial                       = number_format($oDadosIndicador->o10_valorindiceref, 2, "", "");
            $oDadosPrograma->Situacao_Inicial    = str_pad($nValorInicial,               16, " ", STR_PAD_LEFT);
            $nValorModificado                    = number_format($oDadosIndicador->o10_valorindicefinal, 2, "", "");
            $oDadosPrograma->Situacao_Modificada = str_pad($nValorModificado,            16, " ", STR_PAD_LEFT);
            $oDadosPrograma->dt_AnoInicio        = $oPPAVersao->getAnoinicio();
            $oDadosPrograma->dt_AnoFim           = $oPPAVersao->getAnofim();
            $this->aDados[] = $oDadosPrograma;
          }
        } else {
          $sLogErro = "Programa {$oPrograma->iCodigo} sem cadastro de Indicadores.\n";
          $this->addLog($sLogErro);
        }
      }
    } else {
      throw new Exception("Não foi encontrado nenhuma integração com do orçamento no ano {$iAnoSessao}");
    }
    $this->addLog("==== fim do  processamento arquivo {$this->getNomeArquivo()} ====\n");
    return $this->aDados;
  }

}

?>