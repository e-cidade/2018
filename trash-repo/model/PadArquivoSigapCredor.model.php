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
 * Prove dados para a geração do arquivo dos Credores do municipio para o SIGAP
 * @package Pad
 * @author  Iuri Guncthnigg
 * @version $Revision: 1.3 $
 */
final class PadArquivoSigapCredor extends PadArquivoSigap {
  
  /**
   * 
   */
  public function __construct() {
    
    $this->sNomeArquivo = "Credor";
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
    $sWhereInstit = " and o58_instit = ".db_getsession("DB_instit");
    list($iAno, $iMes, $iDia) = explode("-",$this->sDataFinal);
    $oInstituicao = db_stdClass::getDadosInstit(db_getsession("DB_instit"));
    $sSqlCredor  = "select distinct z01_numcgm as codigo,";
    $sSqlCredor .= "       z01_nome   as nome,";
    $sSqlCredor .= "       z01_cgccpf as cnpj,";
    $sSqlCredor .= "       z01_cgccpf as cgc,";
    $sSqlCredor .= "       '' as iss,";
    $sSqlCredor .= "       z01_ender as endereco,";
    $sSqlCredor .= "       z01_munic as cidade,";
    $sSqlCredor .= "       z01_uf as uf,";
    $sSqlCredor .= "       z01_cepcon as cep,";
    $sSqlCredor .= "       z01_telcon as fone,";
    $sSqlCredor .= "       z01_telcon as fax,";
    $sSqlCredor .= "      '1' as tipo"; 
    $sSqlCredor .= "  from cgm";
    $sSqlCredor .= "       inner join empempenho on e60_numcgm = z01_numcgm";
    $rsCredor    = db_query($sSqlCredor);
    $iTotalLinhas = pg_num_rows($rsCredor);

    $aCnpjsIncluidos = array();
    $this->addLog("\n".str_repeat("-", 30)."\nCredor\n");
    for ($i = 0; $i < $iTotalLinhas; $i++) {
      
      $sDiaMesAno =  "{$iAno}-".str_pad($iMes, 2, "0", STR_PAD_LEFT)."-".str_pad($iDia, 2, "0", STR_PAD_LEFT);
      $oCredor   = db_utils::fieldsMemory($rsCredor, $i);

      if (trim($oCredor->cidade) == "" || strlen($oCredor->cidade) < 2) {
        $oCredor->cidade = $oInstituicao->munic;
      }
      $oCredorRetorno                                = new stdClass();
      $oCredorRetorno->creCodigoEntidade             = str_pad($this->iCodigoTCE, 4, "0", STR_PAD_LEFT);
      $oCredorRetorno->creMesAnoMovimento            = $sDiaMesAno;
      $oCredorRetorno->creNomeCredor                 = substr($oCredor->nome, 0, 80);
      $iTamanhoPad = strlen($oCredor->cnpj);
      $oCredorRetorno->creCNPJCPF                    = str_pad($oCredor->cnpj, $iTamanhoPad, 0, STR_PAD_LEFT);
      $oCredorRetorno->creInscricaoEstadual          = str_pad($oCredor->cgc, 15, 0, STR_PAD_LEFT);
      $oCredorRetorno->creInscricaoMunicipal         = str_pad($oCredor->iss, 15, 0, STR_PAD_LEFT);
      $oCredorRetorno->creEndereco                   = substr($oCredor->endereco, 0, 50);
      $oCredorRetorno->creCidade                     = substr($oCredor->cidade, 0, 30);
      if ($oCredor->uf == "") {
        $oCredor->uf = $oInstituicao->uf;
      }
      $oCredorRetorno->creUF                         = str_pad($oCredor->uf, 2, "0");
      $sCep                                          = str_replace(".","",str_replace("-","",$oCredor->cep));
      $oCredorRetorno->creCEP                        = str_pad($sCep, 8, "0", STR_PAD_LEFT);
      $sFone                                         = str_replace(array("(",")","-"),
                                                                   array("","",""),                                        
                                                                   $oCredor->fone);
      $sFax                                          = str_replace(array("(",")","-"),
                                                                   array("","",""),                                        
                                                                   $oCredor->fax);                                                                   
      $oCredorRetorno->creFone                       = $sFone;
      $oCredorRetorno->creFax                        = $sFax;
      $oCredorRetorno->creTipoCredor                 = str_pad($oCredor->tipo, 2, "0", STR_PAD_LEFT);
      
      array_push($this->aDados, $oCredorRetorno);
      if (in_array($oCredor->cnpj, $aCnpjsIncluidos)) {
        
         $sLog = "Cnpj Duplicado:{$oCredorRetorno->creCNPJCPF} - CGM:{$oCredor->codigo} - Nome:{$oCredor->nome}\n";
         $this->addLog($sLog);
      }
      array_push($aCnpjsIncluidos, $oCredor->cnpj);      
      if (strlen($oCredor->cnpj) < 11) {

        $sLog = "Cnpj Tamanho (".strlen($oCredor->cnpj).") Inválido:{$oCredorRetorno->creCNPJCPF} - CGM:{$oCredor->codigo} - Nome:{$oCredor->nome}\n";
        $this->addLog($sLog);
        
      }
      
      if (strlen($oCredor->cnpj) == 11 && !validaCPF($oCredor->cnpj)) {
        
        $sLog = "Cpf Inválido:{$oCredorRetorno->creCNPJCPF} - CGM:{$oCredor->codigo} - Nome:{$oCredor->nome}\n";
        $this->addLog($sLog);
      }
      
      if (strlen($oCredor->cnpj) == 14 && !validaCNPJ($oCredor->cnpj)) {
        
        $sLog = "Cnpj Inválido:{$oCredorRetorno->creCNPJCPF} - CGM:{$oCredor->codigo} - Nome:{$oCredor->nome}\n";
        $this->addLog($sLog);
      }
      
    }
    $this->addLog("\n".str_repeat("-", 30)."\n");
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
                        "creCodigoEntidade",
                        "creMesAnoMovimento",
                        "creNomeCredor",
                        "creCNPJCPF",
                        "creInscricaoEstadual",
                        "creInscricaoMunicipal",
                        "creEndereco",
                        "creCidade",
                        "creUF",
                        "creCEP",
                        "creFone",
                        "creFax",
                        "creTipoCredor",
                       );
    return $aElementos;  
  }
  
}
function validaCPF($vcic) {
  
  if (strlen($vcic) != 11) {
    return false;
  }
  for ($vdigpos = 10; $vdigpos < 12; $vdigpos++ ){
    
    $vdig = 0;
    $vpos = 0;
    for ($vfator = $vdigpos; $vfator >= 2; $vfator-- ) {
      
      $vdig = ($vdig + substr($vcic, $vpos, 1) * $vfator);
      $vpos++;
    }
    $vdig  = 11 -($vdig % 11) < 10 ? (11 - $vdig % 11) : 0;
    if ($vdig != substr($vcic, $vdigpos-1,1)) {
     return false;
    }
  }
  return true;
}

function validaCNPJ($vcnpj) {
  
  if (strlen($vcnpj) != 14) {
    return false;
  }
  for ($vdigpos = 13; $vdigpos < 15; $vdigpos++ ) {
     
    $vdig = 0;
    $vpos = 0;
    for ($vfator = $vdigpos - 8; $vfator >= 2; $vfator-- ) {

      $vdig = $vdig + substr($vcnpj, $vpos,1) * $vfator;
      $vpos++;
    }
    for ($vfator = 9; $vfator >= 2; $vfator-- ) {
      
      $vdig = $vdig + substr($vcnpj, $vpos,1) * $vfator;
      $vpos++;
    }
    $vdig  = (11 -($vdig % 11)) < 10 ? (11 - $vdig % 11) : 0;
    if ($vdig != substr($vcnpj, $vdigpos-1,1)) {
      return false;
    }
  }
  return true;
}

?>