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

require_once ("interfaces/iPadArquivoTxtBase.interface.php");
require_once ("model/contabilidade/arquivos/sigfis/SigfisArquivoBase.model.php");

/**
 *
 * Classe Responsável pela geração dos dados necessários para o arquivo Dotacao Orcamentaria
 * @author Andrio Costa
 * @package contabilidade
 * @subpackage sigfis
 *
 */
class SigfisArquivoDotacaoOrcamentaria extends SigfisArquivoBase implements iPadArquivoTXTBase {
  
  protected $iCodigoLayout     = 120;
  protected $sNomeArquivo      = 'Dotacao';
  
  /**
  * Busca os dados para gerar o Arquivo do Dotacao Orcamentaria
  */
  public function gerarDados() {
  
    /**
     * Busca os dados da db_config
     */
    $oDbConfig    = new db_stdClass();
    $oDadoConfig  = $oDbConfig->getDadosInstit();
    
    $clOrcDotacao = db_utils::getDao('orcdotacao');
    
    $sCampos      = "orcdotacao.o58_anousu, orcdotacao.o58_coddot, orcdotacao.o58_orgao, orcdotacao.o58_unidade, ";
    $sCampos     .= "orcdotacao.o58_subfuncao, orcdotacao.o58_projativ, orcdotacao.o58_codigo, orcdotacao.o58_funcao, ";
    $sCampos     .= "orcdotacao.o58_programa, orcdotacao.o58_codele, orcdotacao.o58_valor, orcdotacao.o58_instit, ";
    $sCampos     .= "orcelemento.o56_elemento, orcelemento.o56_codele, orctiporec.o15_descr, orcprojativ.o55_tipo  ";
    $sOrder       = "orcdotacao.o58_coddot";
    $sWhere       = "     orcdotacao.o58_anousu = {$this->iAnoUso}";
    $sWhere      .= " and orcdotacao.o58_instit = " . db_getsession('DB_instit');
    
    $sSqlOrcDotacao = $clOrcDotacao->sql_query_dotacao(null, null, $sCampos, $sOrder, $sWhere);
    $rsOrcDotacao   = $clOrcDotacao->sql_record($sSqlOrcDotacao );
    
    $this->addLog("==== Iniciando Processamento Arquivo {$this->getNomeArquivo()}\n");
    
    if ($clOrcDotacao->numrows > 0) {
      
      if (empty($this->sCodigoTribunal)) {
        throw new Exception("O código do tribunal deve ser informado para geração do arquivo");
      }
      
      for ($i = 0; $i < $clOrcDotacao->numrows; $i++) {
        
        $oDadosQuery  = new stdClass();
        $oDadosQuery  = db_utils::fieldsMemory($rsOrcDotacao, $i);
        $oElementoTCE = SigfisVinculoDespesa::getVinculoDespesa($oDadosQuery->o56_codele);
        
        $oDados       = new stdClass();
        
       // if ($oDadosQuery->o58_valor != 0) {
          
          /*
          * Verifica Dados de Recurso
          */
          $iCodigoRecursoTCE = '';
          if ($oRecursoTCE = SigfisVinculoRecurso::getVinculoRecurso($oDadosQuery->o58_codigo)) {
            $iCodigoRecursoTCE = $oRecursoTCE->recursotce;
          } else {
          
            $sErroLog  = "Recurso {$oDadosQuery->o58_codigo} - {$oDadosQuery->o15_descr} ";
            $sErroLog .= "Sem Vinculo com Dotação {$oDadosQuery->o58_coddot} do SIGFIS.\n";
            $this->addLog($sErroLog);
          }
          
//          $sUnidadeOrcamentaria  = str_pad($oDadosQuery->o58_orgao,   2, ' ', STR_PAD_LEFT);
          $sUnidadeOrcamentaria = str_pad($oDadosQuery->o58_unidade, 4, ' ', STR_PAD_LEFT);
          
          $oDados->Cd_Unidade             = str_pad($this->sCodigoTribunal,      4, ' ', STR_PAD_LEFT);
          $oDados->Cd_Elemento            = str_pad(substr($oDadosQuery->o56_elemento,1,8), 8, ' ', STR_PAD_LEFT);
          $oDados->Cd_UnidadeOrcamentaria = str_pad($sUnidadeOrcamentaria,       4, ' ', STR_PAD_LEFT);    
          $oDados->Dt_Ano                 = $oDadosQuery->o58_anousu;   
          $oDados->Tp_ProjetoAtividade    = $oDadosQuery->o55_tipo;     
          $oDados->Nu_ProjetoAtividade    = str_pad($oDadosQuery->o58_projativ,  4, ' ', STR_PAD_LEFT);
          $oDados->Cd_FonteRecurso        = str_pad($oDadosQuery->o58_codigo,    4, " ", STR_PAD_LEFT);
          $oDados->Cd_Funcao              = str_pad($oDadosQuery->o58_funcao,    2, ' ', STR_PAD_LEFT);   
          $oDados->Cd_Programa            = str_pad($oDadosQuery->o58_subfuncao, 4, ' ', STR_PAD_LEFT);   
          $oDados->Cd_SubPrograma         = str_pad($oDadosQuery->o58_programa,  4, ' ', STR_PAD_LEFT);   
          $oDados->Vl_Dotacao             = str_pad(number_format($oDadosQuery->o58_valor, 2 , "", ""), 16, ' ', STR_PAD_LEFT);
          $oDados->Cd_Supervisionada      = 1;
          $oDados->Reservado_tce1         = str_pad('0', 6, '0', STR_PAD_LEFT);
          $oDados->Reservado_tce2         = str_pad('0', 6, '0', STR_PAD_LEFT); 
          $oDados->Cd_Orgao               = str_pad($oDadosQuery->o58_orgao,  4, ' ', STR_PAD_LEFT);
          
          $oDados->codigolinha            = 407;
          $this->aDados[]                 = $oDados;        
        //}
        
      } 
    } 
    
    $this->addLog("==== Fim do Arquivo {$this->getNomeArquivo()}\n");
  }
}