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


/**
 * Model para geração de arquivos do pit
 * @package empenho
 */
abstract class arquivoPit {
  
  /**
   * Codigo do usuario
   *
   * @var integer
   */
  protected $iIdUsuario    = "";
  
  /**
   * Notas no arquivo
   *
   * @var unknown_type
   */
  protected $aNotasMes  = array();
  
  /**
   * Data da Criaçao do Arquivo
   *
   * @var string
   */
  protected $dtDataGeracao = "";
  
  /**
   * tipo do documento fiscal
   *
   * @var integer
   */
  protected $iTipoDocumento = null;
  
  /**
   * situacao do arquivo 
   *
   * @var integer
   */
  protected $iSituacao ;
  
  /**
   * Código do arquivo 
   *
   * @var integer
   */
  protected $idArquivo = null; 
  /**
   * Os retornos de dados tipo string deve ser codificados por URLDECODE
   *
   * @var bool
   */
  protected $lEncode = false;
  
  /**
   * cria Arquivospara importação do pit
   * @param integer $idArquivo = Código do arquivo gerado
   */
  
  
  function __construct($idArquivo = null) {
   
    if ($idArquivo == null) {
      
      $this->iIdUsuario    = db_getsession("DB_id_usuario");
      $this->dtDataGeracao = date("Y-m-d", db_getsession("DB_datausu"));
       
    } else {
      
      $oDaoEmpnotasDadosPit = db_utils::getDao("emparquivopit");
      $sSqlDadosPit         = $oDaoEmpnotasDadosPit->sql_query_file($idArquivo);
      $rsDadosPit           = $oDaoEmpnotasDadosPit->sql_record($sSqlDadosPit);
      if ($oDaoEmpnotasDadosPit->numrows == 0) {
        throw new Exception("Arquivo {$idArquivo} não encontrado");        
      }
      
      $oDadosArquivo       = db_utils::fieldsMemory($rsDadosPit, 0, false, $this->lEncode); 
      $this->iSituacao     = $oDadosArquivo->e14_situacao;
      $this->iIdUsuario    = $oDadosArquivo->e14_idusuario;
      $this->dtDataGeracao = $oDadosArquivo->e14_dtarquivo;
      $this->idArquivo     = $oDadosArquivo->e14_sequencial;
      
    }
  }
  
  /**
   * @return bool
   */
  public function getEncode() {

    return $this->lEncode;
  }
  
  /**
   * Define metodo de retorno das propriedades tipo string TRUE = Codificado por URLENCODE - False - string plana
   * @param bool $lEncode
   */
  public function setEncode($lEncode) {

    $this->lEncode = $lEncode;
  }
  
  /**
   * @return string
   */
  public function getDataGeracao() {

    return $this->dtDataGeracao;
  }
  
  /**
   * @param string $dtDataGeracao
   */
  public function setDataGeracao($dtDataGeracao) {

    $this->dtDataGeracao = $dtDataGeracao;
  }
  
  /**
   * @return integer
   */
  public function getIdArquivo() {

    return $this->idArquivo;
  }
  
  /**
   * @return integer
   */
  public function getUsuario() {

    return $this->iIdUsuario;
  }
  
  /**
   * @param integer $iIdUsuario
   */
  public function setUsuario($iIdUsuario) {

    $this->iIdUsuario = $iIdUsuario;
  }
  
  /**
   * @return integer
   */
  public function getSituacao() {

    return $this->iSituacao;
  }
  
  /**
   * @param integer $iSituacao
   */
  public function setSituacao($iSituacao) {
    $this->iSituacao = $iSituacao;
  }


  
  /**
   * Retorna as notas recebidas no periodo
   *
   * @param string $dtInicial data inicial
   * @param string $dtFinal   data final
   * @return array
   */
  
  function getNotasPorPeriodo($dtInicial='', $dtFinal='', $sWhereExtra = '') {
    
    $oDaoEmpnotasDadosPit  = db_utils::getDao("empnotadadospit");
    $sWhere                = " e60_instit   = ".db_getsession("DB_instit");
    if($dtInicial != "" && $dtFinal != ""){
      $sWhere               .= " and e69_dtnota between '{$dtInicial}' and '{$dtFinal}'";
    }
    $sWhere               .= " and e70_vlranu < e70_valor";
    $sWhere               .= " and not exists(select 1 ";
    $sWhere               .= "                  from emparquivopitnotas a";
    $sWhere               .= "                       inner join  emparquivopit  b on a.e15_emparquivopit = b.e14_sequencial";
    $sWhere               .= "                 where a.e15_empnotadadospit = e11_sequencial";
    $sWhere               .= "                   and b.e14_situacao = 1)";
    if($sWhereExtra != ""){
    	$sWhere .= " and ".$sWhereExtra;
    }
    
    
    if ($this->iTipoDocumento != null) {
      $sWhere .= " and e69_tipodocumentosfiscal = {$this->iTipoDocumento}";
    }
    
    $sCampos    = "e69_numero,     "; 
    $sCampos   .= "z01_nome, ";  
    $sCampos   .= "z01_numcgm, ";  
    $sCampos   .= "z01_incest,";
    $sCampos   .= "e60_numemp,";
    $sCampos   .= "(case when e11_seriefiscal = 0 then null else e11_seriefiscal end) as e11_seriefiscal,";
    $sCampos   .= "z01_cgccpf, ";
    $sCampos   .= "e69_dtnota, ";
    $sCampos   .= "z01_uf, ";
    $sCampos   .= "e10_cfop, ";
    $sCampos   .= "e11_sequencial, ";
    $sCampos   .= "e11_inscricaosubstitutofiscal,";
    $sCampos   .= "e11_basecalculoicms::varchar as e11_basecalculoicms,";
    $sCampos   .= "e11_valoricms::varchar as e11_valoricms,";
    $sCampos   .= "e11_basecalculosubstitutotrib,";
    $sCampos   .= "e11_valoricmssubstitutotrib,";
    $sCampos   .= "sum(e70_valor)::varchar as valornota";
    
    $sOrder     = "e69_dtnota, z01_numcgm,e69_numero ";
    
    $sGroupBy   = " group by e69_numero,     "; 
    $sGroupBy  .= "z01_nome, ";  
    $sGroupBy  .= "z01_numcgm, ";  
    $sGroupBy  .= "z01_incest,";
    $sGroupBy  .= "e60_numemp,";
    $sGroupBy  .= "e11_seriefiscal,";
    $sGroupBy  .= "z01_cgccpf, ";
    $sGroupBy  .= "e69_dtnota, ";
    $sGroupBy  .= "z01_uf, ";
    $sGroupBy  .= "e10_cfop, ";
    $sGroupBy  .= "e11_sequencial, ";
    $sGroupBy  .= "e11_inscricaosubstitutofiscal,";
    $sGroupBy  .= "e11_basecalculoicms,";
    $sGroupBy  .= "e11_valoricms,";
    $sGroupBy  .= "e11_basecalculosubstitutotrib,";
    $sGroupBy  .= "e11_valoricmssubstitutotrib";
     
    $sSqlNotas = $oDaoEmpnotasDadosPit->sql_query_notas(null, $sCampos, null, $sWhere.$sGroupBy." order by {$sOrder}");
    $rsNotas   = $oDaoEmpnotasDadosPit->sql_record($sSqlNotas);
    $aNotas    = db_utils::getColectionByRecord($rsNotas, false, false, $this->getEncode());
    return $aNotas;
    
  }
  
  /**
   * Retorna as notas recebidas no periodo
   *
   * @param string $dtInicial data inicial
   * @param string $dtFinal   data final
   * @return array
   */
  
  function getNotasParaArquivo($dtInicial, $dtFinal) {
    
    $oDaoEmpnotasDadosPit  = db_utils::getDao("empnotadadospit");
    $sWhere                = " e69_dtnota between '{$dtInicial}' and '{$dtFinal}'";
    $sWhere               .= " and e60_instit   = ".db_getsession("DB_instit");
    $sWhere               .= " and e70_vlranu < e70_valor";
    $sWhere               .= " and not exists(select 1 ";
    $sWhere               .= "                  from emparquivopitnotas a";
    $sWhere               .= "                       inner join  emparquivopit  b on a.e15_emparquivopit = b.e14_sequencial";
    $sWhere               .= "                 where a.e15_empnotadadospit = e11_sequencial";
    $sWhere               .= "                   and b.e14_situacao = 1)";
    
    if ($this->iTipoDocumento != null) {
      $sWhere .= " and e69_tipodocumentosfiscal = {$this->iTipoDocumento}";
    }
    
    $sCampos    = "e69_numero,     "; 
    $sCampos   .= "z01_nome, ";  
    $sCampos   .= "z01_numcgm, ";  
    $sCampos   .= "z01_incest,";
    $sCampos   .= "(case when e11_seriefiscal = 0 then null else e11_seriefiscal end) as e11_seriefiscal,";
    $sCampos   .= "z01_cgccpf, ";
    $sCampos   .= "e69_dtnota, ";
    $sCampos   .= "z01_uf, ";
    $sCampos   .= "e10_cfop, ";
    $sCampos   .= "e11_inscricaosubstitutofiscal,";
    $sCampos   .= "sum(e70_valor)::varchar as valornota,";
    $sCampos   .= "sum(e11_basecalculoicms)::varchar as e11_basecalculoicms,";
    $sCampos   .= "sum(e11_valoricms)::varchar as e11_valoricms,";
    $sCampos   .= "sum(e11_basecalculosubstitutotrib)::varchar as e11_basecalculosubstitutotrib,";
    $sCampos   .= "sum(e11_valoricmssubstitutotrib)::varchar as e11_valoricmssubstitutotrib";
    
    $sOrder     = "e69_dtnota, z01_numcgm,e69_numero ";
    
    $sGroupBy   = " group by e69_numero,     "; 
    $sGroupBy  .= "z01_nome, ";  
    $sGroupBy  .= "z01_numcgm, ";  
    $sGroupBy  .= "z01_incest,";
    $sGroupBy  .= "e11_seriefiscal,";
    $sGroupBy  .= "z01_cgccpf, ";
    $sGroupBy  .= "e69_dtnota, ";
    $sGroupBy  .= "z01_uf, ";
    $sGroupBy  .= "e10_cfop, ";
    $sGroupBy  .= "e11_inscricaosubstitutofiscal";
     
    $sSqlNotas = $oDaoEmpnotasDadosPit->sql_query_notas(null, $sCampos, null, $sWhere.$sGroupBy." order by {$sOrder}");
    $rsNotas   = $oDaoEmpnotasDadosPit->sql_record($sSqlNotas);
    $aNotas    = db_utils::getColectionByRecord($rsNotas, false, false, $this->getEncode());
    return $aNotas;
    
  }
  
  
  /**
   * Gera o cabecalho do arquivo 
   *
   * @param cl_db_layoutxt $oArquivoLayout objeto com os campos 
   */
  function writeHeader(db_layouttxt &$oArquivoLayout,$dtInicial,$dtFinal) {
    
    $oDadosPref = db_stdClass::getDadosInstit();
    if ($oDadosPref->db21_codigomunicipoestado == "") {
      throw new Exception("Código do município não informado.\nArquivo não poderá ser Gerado.");
    }
    
    $oArquivoLayout->setCampoTipoLinha(1);
    $oArquivoLayout->setCampo("tipodocumento",'00');
    $oArquivoLayout->setCampo("codigomunicipio",str_pad($oDadosPref->db21_codigomunicipoestado,3,"0",STR_PAD_LEFT));
    $oArquivoLayout->setCampo("brancos",str_repeat(" ",224));
    $oArquivoLayout->geraDadosLinha();
    
    $oDaoEmpArquivoPIT = db_utils::getDao("emparquivopit");
    $oDaoEmpArquivoPIT->e14_dtarquivo    = date("Ymd", db_getsession("DB_datausu"));      
    $oDaoEmpArquivoPIT->e14_idusuario    = db_getsession("DB_id_usuario");      
    $oDaoEmpArquivoPIT->e14_hora         = db_hora();      
    $oDaoEmpArquivoPIT->e14_nomearquivo  = "arquivo_pit_".date("Ymd", db_getsession("DB_datausu")).".txt";      
    $oDaoEmpArquivoPIT->e14_situacao     = "1";
    $oDaoEmpArquivoPIT->e14_dtinicial    = $dtInicial;
    $oDaoEmpArquivoPIT->e14_dtfinal      = $dtFinal;
    $oDaoEmpArquivoPIT->incluir(null);
    if ($oDaoEmpArquivoPIT->erro_status == 0) {
      throw new Exception("Erro ao salvar arquivo.\nArquivo não poderá ser Gerado.\n{$oDaoEmpArquivoPIT->erro_msg}");
    }
    $this->idArquivo = $oDaoEmpArquivoPIT->e14_sequencial;
  }
  
  /**
   * Inclui a nota no arquivo
   *
   * @param db_layouttxt $oArquivoLayout
   * @param db_utils $oDados dados com a nota;
   */
  function writeLine(db_layouttxt &$oArquivoLayout, $oDados) {
    
    $oDadosPref = db_stdClass::getDadosInstit();
    $oArquivoLayout->setCampoTipoLinha(3);
    $oArquivoLayout->setCampo("tipodocumento" , $this->iTipoDocumento);
    $oArquivoLayout->setCampo("cnpjemitente"  , trim($oDados->z01_cgccpf));
    $oArquivoLayout->setCampo("ieemitente"    , str_replace("/","",trim($oDados->z01_incest)));
    $oArquivoLayout->setCampo("serie"         , $oDados->e11_seriefiscal);
    $oArquivoLayout->setCampo("numero"        , $oDados->e69_numero);
    $oArquivoLayout->setCampo("ufemitente"    , $oDados->z01_uf);
    $oArquivoLayout->setCampo("dataemissao"   , str_replace("-","",$oDados->e69_dtnota));
    $oArquivoLayout->setCampo("cfop"          , $oDados->e10_cfop);
    $oArquivoLayout->setCampo("tipodeoperacao", 1);
    $oArquivoLayout->setCampo("iesubstributario", str_replace("/","", $oDados->e11_inscricaosubstitutofiscal));
    $oArquivoLayout->setCampo("cnpjcfpdest"     ,  $oDadosPref->cgc);
    $oArquivoLayout->setCampo("iedestinatario"  ,    "");
    $oArquivoLayout->setCampo("ufdestinatario"  ,    "");
    $oArquivoLayout->setCampo("basecalculoicms" , number_format($oDados->e11_basecalculoicms, 2, "", ""));
    $oArquivoLayout->setCampo("valoricms"                      , number_format($oDados->e11_valoricms, 2, "", ""));
    $oArquivoLayout->setCampo("basecalculoicmssubsttributario" , number_format($oDados->e11_basecalculosubstitutotrib, 2, "", ""));
    $oArquivoLayout->setCampo("valoricmssubsttributario"       , number_format($oDados->e11_valoricmssubstitutotrib, 2, "", ""));
    $oArquivoLayout->setCampo("valortotaldocumento"            , number_format($oDados->valornota, 2, "", ""));
    $oArquivoLayout->setCampo("tipodocumentopref"              , 3);
    $oArquivoLayout->setCampo("brancos"                        , str_repeat(" ", 66));
    $oArquivoLayout->geraDadosLinha();
    
    $sWhereExtra = " z01_numcgm = ".$oDados->z01_numcgm." and e69_numero = '".$oDados->e69_numero."'";
    
    $aNotas = $this->getNotasPorPeriodo('','',$sWhereExtra);
    foreach ($aNotas as $oNota){
    
	    $oDaoEmpArquivoPITNota = db_utils::getDao("emparquivopitnotas");
	    $oDaoEmpArquivoPITNota->e15_emparquivopit   = $this->idArquivo;
	    $oDaoEmpArquivoPITNota->e15_empnotadadospit = $oNota->e11_sequencial;
	    $oDaoEmpArquivoPITNota->incluir(null);
	    if ($oDaoEmpArquivoPITNota->erro_status == 0) {
	       throw new Exception("Erro ao incluir nota no arquivo.\nArquivo não poderá ser Gerado.\n{$oDaoEmpArquivoPITNota->erro_msg}");
	    }
    }
  }
  
  /**
   * Salva as informações do txt na base de dados
   *
   * @param db_layouttxt $oArquivoLayout
   */
  function saveArquivo(db_layouttxt &$oArquivoLayout) {
    
      $oArquivoLayout->carregaTxt("tmp/arquivo_pit_".date("Ymd", db_getsession("DB_datausu")).".txt");
      $sStringArquivo = "";
      foreach ($oArquivoLayout->_arquivo as $aLinha => $ssLinha) {
        $sStringArquivo .= "$ssLinha"; 
      }
      
      $oDaoEmpArquivoPIT = db_utils::getDao("emparquivopit");
      $oDaoEmpArquivoPIT->e14_sequencial = $this->idArquivo;
      $oDaoEmpArquivoPIT->e14_corpoarquivo = $sStringArquivo;
      $oDaoEmpArquivoPIT->alterar($this->idArquivo);
      if ($oDaoEmpArquivoPIT->erro_status == 0) {
        throw new Exception("Erro ao salvar arquivo.\nArquivo não poderá ser Gerado.\n{$oDaoEmpArquivoPIT->erro_msg}");
      }
  }
  
  function anularArquivo($sMotivo) {
    
    if ($this->getSituacao() == 2) {
      throw new Exception("Arquivo({$this->idArquivo}) já cancelado.");
    }
    $oDaoEmpArquivoPIT = db_utils::getDao("emparquivopit");
    $oDaoEmpArquivoPIT->e14_sequencial = $this->idArquivo;
    $oDaoEmpArquivoPIT->e14_situacao   = 2;
    $oDaoEmpArquivoPIT->alterar($this->idArquivo);
    if ($oDaoEmpArquivoPIT->erro_status == 0) {
      throw new Exception("Erro ao salvar arquivo.\n Arquivo não cancelado.\n{$oDaoEmpArquivoPIT->erro_msg}");
    }
    
    $oDaoEmpArquivoPITAnulado = db_utils::getDao("emparquivopitanulado");
    $oDaoEmpArquivoPITAnulado->e16_dtanulacao    = date("Ymd", db_getsession("DB_datausu"));
    $oDaoEmpArquivoPITAnulado->e16_horaanulacao  = db_hora(); 
    $oDaoEmpArquivoPITAnulado->e16_idusuario     = db_getsession("DB_id_usuario"); 
    $oDaoEmpArquivoPITAnulado->e16_motivo        = $sMotivo; 
    $oDaoEmpArquivoPITAnulado->e16_emparquivopit = $this->idArquivo;
    $oDaoEmpArquivoPITAnulado->incluir(null);
    if ($oDaoEmpArquivoPITAnulado->erro_status == 0) {
   
      $serroMsg =  "Erro ao salvar arquivo.\n Arquivo não cancelado.\n{$oDaoEmpArquivoPITAnulado->erro_msg}";
      throw new Exception($serroMsg);
      
    }
  }
  
  /**
   * Retorna a dadas cadastradas para o usuário
   *
   * @return array 
   */
  function getNotasArquivo () {
    
    $oDaoEmpnotasDadosPit  = db_utils::getDao("empnotadadospit");
    $sWhere                = " e60_instit   = ".db_getsession("DB_instit");
    $sWhere               .= " and e15_emparquivopit = {$this->idArquivo}";
    
    if ($this->iTipoDocumento != null) {
      $sWhere .= " and e69_tipodocumentosfiscal = {$this->iTipoDocumento}";
    }
    
    $sCampos    = "e69_numero,     "; 
    $sCampos   .= "z01_nome, ";  
    $sCampos   .= "z01_numcgm, ";  
    $sCampos   .= "z01_incest,";
    $sCampos   .= "e60_numemp,";
    $sCampos   .= "(case when e11_seriefiscal = 0 then null else e11_seriefiscal end) as e11_seriefiscal,";
    $sCampos   .= "z01_cgccpf, ";
    $sCampos   .= "e69_dtnota, ";
    $sCampos   .= "z01_uf, ";
    $sCampos   .= "e10_cfop, ";
    $sCampos   .= "e11_sequencial, ";
    $sCampos   .= "e11_inscricaosubstitutofiscal,";
    $sCampos   .= "e11_basecalculoicms::varchar as e11_basecalculoicms,";
    $sCampos   .= "e11_valoricms::varchar as e11_valoricms,";
    $sCampos   .= "e11_basecalculosubstitutotrib,";
    $sCampos   .= "e11_valoricmssubstitutotrib,";
    $sCampos   .= "sum(e70_valor)::varchar as valornota";
    
    $sOrder     = "e69_dtnota, z01_numcgm,e69_numero ";
    
    $sGroupBy   = " group by e69_numero,     "; 
    $sGroupBy  .= "z01_nome, ";  
    $sGroupBy  .= "z01_numcgm, ";  
    $sGroupBy  .= "z01_incest,";
    $sGroupBy  .= "e60_numemp,";
    $sGroupBy  .= "e11_seriefiscal,";
    $sGroupBy  .= "z01_cgccpf, ";
    $sGroupBy  .= "e69_dtnota, ";
    $sGroupBy  .= "z01_uf, ";
    $sGroupBy  .= "e10_cfop, ";
    $sGroupBy  .= "e11_sequencial, ";
    $sGroupBy  .= "e11_inscricaosubstitutofiscal,";
    $sGroupBy  .= "e11_basecalculoicms,";
    $sGroupBy  .= "e11_valoricms,";
    $sGroupBy  .= "e11_basecalculosubstitutotrib,";
    $sGroupBy  .= "e11_valoricmssubstitutotrib";
     
    $sSqlNotas = $oDaoEmpnotasDadosPit->sql_query_notas(null, $sCampos, null, $sWhere.$sGroupBy." order by {$sOrder}");
    $rsNotas   = $oDaoEmpnotasDadosPit->sql_record($sSqlNotas);
    $aNotas    = db_utils::getColectionByRecord($rsNotas, false, false, $this->getEncode());
    return $aNotas;
    
  }
  /**
   * 
   */
  function __destruct() {

  }
}

?>