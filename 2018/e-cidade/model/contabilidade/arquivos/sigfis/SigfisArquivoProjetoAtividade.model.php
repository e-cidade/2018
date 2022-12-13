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
 * Classe Responsável pela geração dos dados necessários para o arquivo Projeto/Atividade
 * @author Andrio Costa
 * @package contabilidade
 * @subpackage sigfis
 *
 */
class SigfisArquivoProjetoAtividade extends SigfisArquivoBase implements iPadArquivoTXTBase {
  
  protected $iCodigoLayout     = 115;
  protected $sNomeArquivo      = 'ProjAtv';
  
  /**
  * Busca os dados para gerar o Arquivo do Projeto/Atividade
  */
  public function gerarDados() {
  
    /**
     * Busca os dados da db_config
     */

    $iInstituicaoSessao = db_getsession('DB_instit');

    $oDbConfig    = new db_stdClass();
    $oDadoConfig  = $oDbConfig->getDadosInstit();
                 
    $clProjAtiv   = db_utils::getDao('orcprojativ');
    $clDotacao    = db_utils::getDao('orcdotacao');
                 
    $sCampos      = "distinct orcprojativ.o55_anousu, orcprojativ.o55_tipo, orcprojativ.o55_projativ, orcprojativ.o55_descr, ";
    $sCampos     .= "orcprojativ.o55_descrunidade, orcprojativ.o55_especproduto, orcproduto.o22_descrprod, ";
    $sCampos     .= "orcdotacao.o58_funcao, orcdotacao.o58_subfuncao, orcdotacao.o58_programa ";
    $sOrder       = "orcprojativ.o55_projativ";
    $sWhere       = "orcprojativ.o55_anousu = {$this->iAnoUso} and o58_instit = {$iInstituicaoSessao}";
    $sSqlProjAtiv = $clProjAtiv->sql_query_projetoAtividade($this->iAnoUso, null, $sCampos, $sOrder, $sWhere);
    
    $rsProjAtiv   = $clProjAtiv->sql_record($sSqlProjAtiv);
    
    if ($clProjAtiv->numrows > 0) {
      
      if (empty($this->sCodigoTribunal)) {
        throw new Exception("O código do tribunal deve ser informado para geração do arquivo");
      }
      
      for ($i = 0; $i < $clProjAtiv->numrows; $i++) {
        
        $oDadoFuncao     = new stdClass();
        $oDadoSubFuncao  = new stdClass();
        $oDadoPrograma   = new stdClass();
        $oDadosQuery     = new stdClass();
        $oDadosQuery     = db_utils::fieldsMemory($rsProjAtiv, $i);
                         
        $oDados          = new stdClass();
        
        /**
         * Busca todas Funções para o projeto de Atividade
         */
        $sSqlFuncao    = $clDotacao->sql_query_file($oDadosQuery->o55_anousu,
                                                    null,
                                                    "o58_funcao, count(*)",
                                                    " 2 desc ,1 asc",
                                                    "o58_funcao = {$oDadosQuery->o58_funcao} group by 1"); 
        $rsFuncao      = $clDotacao->sql_record($sSqlFuncao);
        $oDadoFuncao   = db_utils::fieldsMemory($rsFuncao, 0);
        
        /**
        * Busca todas SubFunções para o projeto de Atividade
        */
        $sSqlSubFuncao = $clDotacao->sql_query_file($oDadosQuery->o55_anousu,
                                                    null,
                                                    "o58_subfuncao, count(*)",
                                                    " 2 desc ,1 asc",
                                                    "o58_subfuncao = {$oDadosQuery->o58_subfuncao} group by 1");
        $rsSubFuncao   = $clDotacao->sql_record($sSqlSubFuncao);
        $oDadoSubFuncao  = db_utils::fieldsMemory($rsSubFuncao, 0);
        /**
        * Busca todos Programas para o projeto de Atividade
        */
        $sSqlPrograma  = $clDotacao->sql_query_file($oDadosQuery->o55_anousu,
                                                    null,
                                                    "o58_programa, count(*)",
                                                    " 2 desc ,1 asc",
                                                    "o58_programa = {$oDadosQuery->o58_programa} group by 1");
        $rsPrograma    = $clDotacao->sql_record($sSqlPrograma);
        $oDadoPrograma  = db_utils::fieldsMemory($rsPrograma, 0);
        
            
        $oDados->dt_Ano                      = $oDadosQuery->o55_anousu;
        $oDados->cd_Unidade                  = str_pad($this->sCodigoTribunal, 4, ' ', STR_PAD_LEFT);
        $oDados->tp_ProjetoAtividade         = $oDadosQuery->o55_tipo;
        $oDados->nu_ProjetoAtividade         = str_pad(substr($oDadosQuery->o55_projativ,  0, 4), 4, ' ', STR_PAD_LEFT);
        $oDados->de_ProjetoAtividade         = str_pad(substr($oDadosQuery->o55_descr, 0, 255), 255, ' ', STR_PAD_RIGHT);
        $oDados->de_ObjetivoProjetoAtividade = str_pad(substr($oDadosQuery->o22_descrprod, 0, 120), 120, ' ', STR_PAD_RIGHT);
        $oDados->cd_Funcao                   = str_pad($oDadoFuncao->o58_funcao,       2, ' ', STR_PAD_LEFT);
        $oDados->cd_Programa                 = str_pad($oDadoSubFuncao->o58_subfuncao, 4, ' ', STR_PAD_LEFT);
        $oDados->cd_SubPrograma              = str_pad($oDadoPrograma->o58_programa,   4, ' ', STR_PAD_LEFT);
        $oDados->de_UniMed                   = str_pad(substr($oDadosQuery->o22_descrprod, 0, 120), 120, ' ', STR_PAD_RIGHT); 
        $oDados->de_Meta                     = str_pad(substr($oDadosQuery->o55_especproduto, 0, 120), 120, ' ', STR_PAD_RIGHT);
        $oDados->Reservado_TCE               = str_pad("", 6, '0', STR_PAD_BOTH); 
        
        $oDados->codigolinha     = 402;
      
        $this->aDados[] = $oDados;
      }
    } 
  }
}