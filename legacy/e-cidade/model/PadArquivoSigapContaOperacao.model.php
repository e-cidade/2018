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
 * Prove dados para a geraчуo do arquivo das Contas de operaчуo para o SIGAP
 * @package Pad
 * @author Iuri Guncthnigg
 * @version $Revision: 1.3 $
 */
final class PadArquivoSigapContaOperacao extends PadArquivoSigap {
  
  /**
   * 
   */
  public function __construct() {
    
    $this->sNomeArquivo = "ContaOperacao";
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
      throw new Exception("Data final nуo informada!");
    }
    /**
     * Separamos a data do em ano, mes, dia
     */
    $sWhereInstit = db_getsession("DB_instit");
    list($iAno, $iMes, $iDia) = explode("-",$this->sDataFinal);
    $oInstituicao = db_stdClass::getDadosInstit(db_getsession("DB_instit"));
    
    $iTotalLinhas = 0; 
    return true;
  }
  
  /**
   * Publica quais elementos/Campos estуo disponiveis para 
   * o uso no momento da geraчуo do arquivo
   *
   * @return array com elementos disponibilizados para a geraчуo dos arquivo
   */
  public function getNomeElementos() {
    
    $aElementos = array(
                        "opeCodigoEntidade",
                        "opeMesAnoMovimento",
                        "opeCodigoOperacao",
                        "opeData",
                        "opeValor",
                        "opeSinal",
                        "opeCodigoRecursoVinculado",
                        "opeCodigoContaReceita",
                        "opeCodigoOrgaoReceita",
                        "opeCodigoUnidadeOrcamentariaReceita",
                        "opeCodigoOrgaoBalanceteVerificacao",
                        "opeCodigoUnidadeBalanceteVerificaca",
                        "opeCodigoContaBalanceteVerificacao",
                        "opeCodigoOrgaoUnidadeOrcamentariaBalVer"
                       );
                       
    return $aElementos;  
  }
}
?>