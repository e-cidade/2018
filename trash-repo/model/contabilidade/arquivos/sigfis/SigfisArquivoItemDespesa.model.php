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

require_once ("interfaces/iPadArquivoTxtBase.interface.php");
require_once ("model/contabilidade/arquivos/sigfis/SigfisArquivoBase.model.php");

/**
 *
 * Classe Responsável pela geração dos dados necessários para o arquivo Itens de Despesa
 * @author Andrio Costa
 * @package contabilidade
 * @subpackage sigfis
 *
 */
class SigfisArquivoItemDespesa extends SigfisArquivoBase implements iPadArquivoTXTBase {
  
  protected $iCodigoLayout     = 117;
  protected $sNomeArquivo      = 'EspDesp';
  
  /**
  * Busca os dados para gerar o Arquivo do Programa do Orçamento
  */
  public function gerarDados() {
  
    /**
     * Busca os dados da db_config
     */
    $oDbConfig       = new db_stdClass();
    $oDadoConfig     = $oDbConfig->getDadosInstit();

		$iInstituicaoSessao = db_getsession('DB_instit');
    
    $clOrcElemento   = db_utils::getDao('orcelemento');
    
    $sCampos         = "distinct orcelemento.o56_elemento, orcelemento.o56_descr, orcelemento.o56_anousu, orcelemento.o56_codele, ";
//    $sCampos        .= "case when c61_reduz is not null then '1' else '2' end as reduz ";
    $sCampos        .= "'1' as reduz ";
    $sOrder          = "o56_elemento";
    
    $sWhere          = "orcelemento.o56_anousu = {$this->iAnoUso} and empempenho.e60_instit = {$iInstituicaoSessao}";
//    $sSqlOrcElemento = $clOrcElemento->sql_query_plano_contas_execucao(null, $sCampos, null, $sWhere);

//    $sSqlOrcElemento .= " union ";

    $sWhere          = "orcelemento.o56_anousu = {$this->iAnoUso} and orcdotacao.o58_instit = {$iInstituicaoSessao}";
    $sSqlOrcElemento = $clOrcElemento->sql_query_plano_contas_dotacao(null, $sCampos, null, $sWhere);

    $sSqlOrcElemento = " select * from ( $sSqlOrcElemento ) as x order by $sOrder";

//die( $sSqlOrcElemento );

    $rsOrcElemento   = $clOrcElemento->sql_record($sSqlOrcElemento);
    
    if ($clOrcElemento->numrows > 0) {
      
      if (empty($this->sCodigoTribunal)) {
        throw new Exception("O código do tribunal deve ser informado para geração do arquivo");
      } 
      
      for ($i = 0; $i < $clOrcElemento->numrows; $i++) {
        
        $oDadosQuery         = db_utils::fieldsMemory($rsOrcElemento, $i);
        $oElementoTCE        = SigfisVinculoDespesa::getVinculoDespesa($oDadosQuery->o56_codele);
        /*
         * Verificamos se o nível da conta é 1 ou 2. caso sejam estas nao serão impressas no arquivo
         */
        $sSqlBuscaNivelConta = "select fc_nivel_plano2005('{$oDadosQuery->o56_elemento}') as nivelconta";
        $rsBuscaNivelConta   = db_query($sSqlBuscaNivelConta);
        $iDadoNivelConta     = db_utils::fieldsMemory($rsBuscaNivelConta, 0)->nivelconta;
        if ($iDadoNivelConta == 1 || $iDadoNivelConta == 2) {
          continue;
        }
        if (substr($oDadosQuery->o56_elemento, 1, 8) == "00000000" ||
            substr($oDadosQuery->o56_elemento, 2, 7) == "0000000") {
          continue;
        }
        
        $oDados                     = new stdClass();
        $oDados->cd_Unidade         = str_pad($this->sCodigoTribunal,                     4, ' ', STR_PAD_LEFT);
        $oDados->cd_ElementoGestor  = str_pad(substr($oDadosQuery->o56_elemento, 1, 8),   8, ' ', STR_PAD_LEFT);
        $oDados->cd_Elemento        = str_pad($oElementoTCE->despesatce,                  8, ' ', STR_PAD_LEFT);
        $oDados->de_ElementoGestor  = str_pad(substr($oDadosQuery->o56_descr,  0, 100), 100, ' ', STR_PAD_RIGHT);
        $oDados->dt_ano             = $oDadosQuery->o56_anousu;
        $oDados->Cd_receblanc       = $oDadosQuery->reduz;
        
        $oDados->codigolinha     = 404;
        
        $this->aDados[] = $oDados;        
      } 
    } 
  }
}