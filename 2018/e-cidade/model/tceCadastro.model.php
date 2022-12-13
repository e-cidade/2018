<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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


require_once(modification('model/tceEstruturaBasica.php'));

class tceCadastro extends tceEstruturaBasica {
  
  const NOME_ARQUIVO = 'CADASTRO.TXT';
  const CODIGO_ARQUIVO = 39;

  public $iInstit      = "";
  public $sDataIni     = "";
  public $sDataFim     = "";
  public $sCodRemessa  = "";
  public $oOutrosDados = null;
  
  private $oLeiaute      = null;
  
  function __construct($iInstit, $sCodRemessa, $sDataIni, $sDataFim, $oData, $oLeiaute = null) {
    
    try {
      parent::__construct(self::CODIGO_ARQUIVO, self::NOME_ARQUIVO);
    } catch ( Exception $e ) {
      throw $e->getMessage();
    }
    
    $this->iInstit      = $iInstit;
    $this->sDataIni     = $sDataIni;
    $this->sDataFim     = $sDataFim;
    $this->sCodRemessa  = $sCodRemessa;
    $this->oOutrosDados = $oData;
    if ($oLeiaute != null) {
      $this->oLeiaute =$oLeiaute;
    }
  
  }
  
  function getNomeArquivo() {

    return self::NOME_ARQUIVO;
  }
  
  function geraArquivo() {

    db_criatermometro('terTCECADASTRO', 'Arquivo CADASTRO...', 'blue', 1);
    $this->oTxtLayout->setByLineOfDBUtils($this->cabecalhoPadrao($this->iInstit, $this->sDataIni, $this->sDataFim, $this->sCodRemessa), 1);
    $rsCadastro      = db_query($this->sqlCadastro($this->iInstit));
    $iNumRows        = pg_num_rows($rsCadastro);
    $iTotalRegistros = 0;
    $iContador       = 0;
    $oResponsaveis   = db_utils::getDao('contcearquivoresp');
    $iQuant          = 0;
    
    for($i = 0; $i < $iNumRows; $i ++) {

      $iNew = intval($i*100/$iNumRows);
      if ($iNew > $iQuant) {

        $iQuant = $iNew;
        db_atutermometro($i, $iNumRows, "terTCECADASTRO");
      }

      $oCadastro = db_utils::fieldsMemory($rsCadastro, $i);
      
      // titular responsavel
      $sSqlTitularResponsavel = $oResponsaveis->sql_query_file(null,
                                                              "c12_nome,c12_cargo,c12_nrodoc",
                                                              null,
                                                              "c12_contcearquivo = {$this->oOutrosDados->codigoremessa} and c12_tipo = 1");
      $rsTitularResponsavel = $oResponsaveis->sql_record($sSqlTitularResponsavel);
      $oTitularResponsavel  = db_utils::fieldsMemory($rsTitularResponsavel,0);
      // responsavel da epoca
      $sSqlResponsavelEpoca = $oResponsaveis->sql_query_file(null,
                                                             "c12_nome,c12_cargo,c12_nrodoc",
                                                             null,
                                                             "c12_contcearquivo = {$this->oOutrosDados->codigoremessa} and c12_tipo = 2");
      $rsResponsavelEpoca = $oResponsaveis->sql_record($sSqlResponsavelEpoca);
      $oResponsavelEpoca  = db_utils::fieldsMemory($rsResponsavelEpoca,0);
      // responsavel pela geracao dos dados
      $sSqlRespGeracaoDados = $oResponsaveis->sql_query_file(null,
                                                             "c12_nome,c12_cargo,c12_nrodoc",
                                                             null,
                                                             "c12_contcearquivo = {$this->oOutrosDados->codigoremessa} and c12_tipo = 3");
      $rsRespGeracaoDados = $oResponsaveis->sql_record($sSqlRespGeracaoDados);
      $oRespGeracaoDados  = db_utils::fieldsMemory($rsRespGeracaoDados,0);

      // Contador responsavel
      $sSqlContadorResponsal = $oResponsaveis->sql_query_file(null,
                                                             "c12_nome,c12_cargo,c12_nrodoc",
                                                             null,
                                                             "c12_contcearquivo = {$this->oOutrosDados->codigoremessa} and c12_tipo = 4");
      $rsContadorResponsal = $oResponsaveis->sql_record($sSqlContadorResponsal);
      $oContadorResponsal  = db_utils::fieldsMemory($rsContadorResponsal,0);
      // responsavel pelo controle interno
      $sSqlControleInterno = $oResponsaveis->sql_query_file(null,
                                                             "c12_nome,c12_cargo,c12_nrodoc",
                                                             null,
                                                             "c12_contcearquivo = {$this->oOutrosDados->codigoremessa} and c12_tipo = 5");
      $rsControleInterno = $oResponsaveis->sql_record($sSqlControleInterno);
      $oControleInterno  = db_utils::fieldsMemory($rsControleInterno,0);
		  $oCadastro->cargoresponsavelcontroleinterno                    = $oControleInterno->c12_cargo;
			$oCadastro->cargoresponsavelgeracaodadosinfomacoes             = $oRespGeracaoDados->c12_cargo;
			$oCadastro->cargotitularresponsavelentejurisdicioepocainformac = $oTitularResponsavel->c12_cargo;
			$oCadastro->cargoatualtitularreponsavelentejurisdiciocando     = $oTitularResponsavel->c12_cargo;
			$oCadastro->datafinalinformacao                                = $this->sDataFim;
			$oCadastro->datageracaoinformacao                              = date('Y-m-d',db_getsession('DB_datausu'));
			$oCadastro->datainicialinformacoes                             = $this->sDataIni;
			$oCadastro->nomeatualtitularresponsavelpeloentejurisdicionado  = $oTitularResponsavel->c12_nome;
			$oCadastro->nomecontabilistaresponsaveldadosinformacoes        = $oContadorResponsal->c12_nome;
			$oCadastro->nomeempresaprestadoraservicoinformatica            = 'DBSeller Serviços de Informática LTDA';
			$oCadastro->nomeresponsavelcontroleinterno                     = $oControleInterno->c12_nome;
			$oCadastro->nomeresponsavelgeracaodosdadosinformacoes          = $oRespGeracaoDados->c12_nome;
			$oCadastro->nometitularresponsavelentejurisdicionalepocainform = $oTitularResponsavel->c12_nome;
			$oCadastro->numerocrccontabilistaresponsaveldadosinformacoes   = $oContadorResponsal->c12_nrodoc;
			$oCadastro->numerosequencialregistro                           = ++$iContador;
			$oCadastro->observacoes                                        = '';
			$oCadastro->responsavelempresaprestadoraservicoinformatica     = 'Paulo Ricardo da Silva';
			$oCadastro->telefoneempresaprestadoraservicoinformatica        = '5130765101';
      $this->oTxtLayout->setByLineOfDBUtils($oCadastro, 3);
      
      $iTotalRegistros ++;
      
    }
    
    $this->oTxtLayout->setByLineOfDBUtils($this->rodapePadrao($iTotalRegistros), 5);
    unset($rsCadastro);
  
  }
  
  function sqlCadastro($iInstit) {

    $sSqlCadastro  = " select '' as cargoresponsavelcontroleinterno,                    ";
    $sSqlCadastro .= "        '' as cargoresponsavelgeracaodadosinfomacoes,             ";
    $sSqlCadastro .= "        '' as cargotitularresponsavelentejurisdicioepocainformac, ";
    $sSqlCadastro .= "        '' as cargoatualtitularreponsavelentejurisdiciocando,     ";
    $sSqlCadastro .= "        cgc as cnpjentejurisdicional,                             ";
    $sSqlCadastro .= "        '' as datafinalinformacao,                                ";
    $sSqlCadastro .= "        '' as datageracaoinformacao,                              ";
    $sSqlCadastro .= "        '' as datainicialinformacoes,                             ";
    $sSqlCadastro .= "        '' as nomeatualtitularresponsavelpeloentejurisdicionado,  ";
    $sSqlCadastro .= "        '' as nomecontabilistaresponsaveldadosinformacoes,        ";
    $sSqlCadastro .= "        '' as nomeempresaprestadoraservicoinformatica,            ";
    $sSqlCadastro .= "        nomeinst as nomeentejurisdicional,                        ";
    $sSqlCadastro .= "        '' as nomeresponsavelcontroleinterno,                     ";
    $sSqlCadastro .= "        '' as nomeresponsavelgeracaodosdadosinformacoes,          ";
    $sSqlCadastro .= "        '' as nometitularresponsavelentejurisdicionalepocainform, ";
    $sSqlCadastro .= "        '' as numerocrccontabilistaresponsaveldadosinformacoes,   ";
    $sSqlCadastro .= "        '' as numerosequencialregistro,                           ";
    $sSqlCadastro .= "        '' as observacoes,                                        ";
    $sSqlCadastro .= "        '' as responsavelempresaprestadoraservicoinformatica,     ";
    $sSqlCadastro .= "        '' as telefoneempresaprestadoraservicoinformatica         ";
    $sSqlCadastro .= "   from db_config            ";
    $sSqlCadastro .= "  where codigo = {$iInstit}  ";
    
    return $sSqlCadastro;
  
  }

}

?>