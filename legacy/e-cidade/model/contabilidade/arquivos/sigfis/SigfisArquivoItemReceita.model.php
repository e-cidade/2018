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
 * Classe Responsável pela geração dos dados necessários para o arquivo Itens da Receita
 * @author Andrio Costa
 * @package contabilidade
 * @subpackage sigfis
 *
 */
class SigfisArquivoItemReceita extends SigfisArquivoBase implements iPadArquivoTXTBase {
  
  protected $iCodigoLayout     = 116;
  protected $sNomeArquivo      = 'EspRec';
  
  /**
  * Busca os dados para gerar o Arquivo do Programa do Orçamento
  */
  public function gerarDados() {
  
    /**
     * Busca os dados da db_config
     */
    $oDbConfig     = new db_stdClass();
    $oDadoConfig   = $oDbConfig->getDadosInstit();
                   
    $clOrcFontes   = db_utils::getDao('orcfontes');
                   
    $sCampos       = "distinct orcfontes.o57_fonte, orcfontes.o57_descr, orcfontes.o57_anousu, orcfontes.o57_codfon, ";
    $sCampos      .= "'1' as reduz ";
    $sOrder        = "orcfontes.o57_fonte";
    $sSqlOrcFontes = $clOrcFontes->sql_query_previsao(null, $this->iAnoUso, $sCampos, $sOrder);

    $sSqlOrcFontes = "select substr(o57_fonte,1,9) as o57_fonte , max(o57_descr) as o57_descr, o57_anousu, max(o57_codfon) as o57_codfon, '1' as reduz 
                      from (".$sSqlOrcFontes.") as x group by substr(o57_fonte,1,9), o57_anousu order by o57_fonte ";
    
//  die( $sSqlOrcFontes );
    $rsOrcFontes   = $clOrcFontes->sql_record($sSqlOrcFontes);
    
    $this->addLog("=====Arquivo: ".$this->getNomeArquivo()." Erros:\n");
    if ($clOrcFontes->numrows > 0) {
  
      if (empty($this->sCodigoTribunal)) {
        throw new Exception("O código do tribunal deve ser informado para geração do arquivo");
      }
      
      for($i = 0; $i < $clOrcFontes->numrows; $i++) {
  
        $oDadosQuery = new stdClass();
        $oDadosQuery = db_utils::fieldsMemory($rsOrcFontes, $i);
        $oDados      = new stdClass();

        if ($oVinculo = SigfisVinculoReceita::getVinculoReceita($oDadosQuery->o57_codfon)) {

          $oDados->cd_Unidade           = str_pad($this->sCodigoTribunal,                  4, ' ', STR_PAD_LEFT);
          if(substr($oDadosQuery->o57_fonte, 0,  1) == '9'   ){
            $oDados->cd_ItemReceitaGestor = str_pad( '9'.substr($oDadosQuery->o57_fonte, 2,  7),  8, " ", STR_PAD_LEFT);
          }else{
            $oDados->cd_ItemReceitaGestor = str_pad(substr($oDadosQuery->o57_fonte, 1,  8),  8, " ", STR_PAD_LEFT);
          }
          $oDados->de_ItemReceita       = str_pad(substr($oDadosQuery->o57_descr, 0, 50), 50, ' ', STR_PAD_RIGHT);
          $oDados->cd_ItemReceita       = $oVinculo->receitatce;
          $oDados->dt_ano               = $oDadosQuery->o57_anousu;
          $oDados->Cd_receblanc         = $oDadosQuery->reduz;
          $oDados->codigolinha          = 403;

          $this->aDados[] = $oDados;
          
        } else {
        
          $sErroLog  = "Receita {$oDadosQuery->o57_fonte} do ano de {$this->iAnoUso} ";
          $sErroLog .= "não tem vinculo com Recita Sigfis.\n";
          $this->addLog($sErroLog);
        }
      }
    } 
    $this->addLog("===== Fim do Arquivo: ".$this->getNomeArquivo()."\n");
  }
}