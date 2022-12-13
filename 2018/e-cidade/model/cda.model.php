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


class cda {


  /**
   *
   */
  protected $iCodigo     = null;

  protected $aEnvolvidos = array();

  protected $aDebitos    = array();

  protected $dataEmissao = null;

  protected $iFolha      = 0;

  protected $iLivro      = 0;

  protected $iTipo       = 0;

  protected $dataLivro   = null;

  protected $massafalida = 0;

  protected $oDaoCertid  = null;

  protected $iAno        = null;

  protected $lComposicao = false;

  protected $oDataRecalculoJurosMulta = null;

  /**
   *
   */
  public function __construct($iCodigo) {

  	db_utils::getDao('certid', false);

    if (!empty($iCodigo)) {

      $this->oDaoCertid    = new cl_certid;
      $sSqlDadosCertidao  = "select certid.v13_certid,";
      $sSqlDadosCertidao .= "       v13_dtemis,";
      $sSqlDadosCertidao .= "       coalesce(certidmassa.v13_certid) as v13_certidmassa,";
      $sSqlDadosCertidao .= "       v26_numerofolha as folha,";
      $sSqlDadosCertidao .= "       v25_numero as livro,";
      $sSqlDadosCertidao .= "       v25_datainc as datalivro,";
      $sSqlDadosCertidao .= "       (case when certter.v14_certid is not null then 1 ";
      $sSqlDadosCertidao .= "             when certdiv.v14_certid is not null then 2 end) as tipocertidao";
      $sSqlDadosCertidao .= "  from certid";
      $sSqlDadosCertidao .= "       left outer join certidlivrofolha on v26_certid         = v13_certid";
      $sSqlDadosCertidao .= "       left outer join certdiv          on certid.v13_certid         = certdiv.v14_certid";
      $sSqlDadosCertidao .= "       left outer join certter          on certid.v13_certid         = certter.v14_certid";
      $sSqlDadosCertidao .= "       left outer join certidlivro      on v26_certidlivro    = v25_sequencial ";
      $sSqlDadosCertidao .= "       left outer join certidmassa  on certidmassa.v13_certid = certid.v13_certid ";
      $sSqlDadosCertidao .= "  where certid.v13_certid = {$iCodigo} ";
      $sSqlDadosCertidao .= "  limit 1";

      $rsCertid           = $this->oDaoCertid->sql_record($sSqlDadosCertidao);

      if ($rsCertid != false && $this->oDaoCertid->numrows > 0) {

        $oDadosCertid      = db_utils::fieldsMemory($rsCertid, 0);
        $this->iCodigo     = $oDadosCertid->v13_certid;
        $this->dataEmissao = $oDadosCertid->v13_dtemis;
        $this->iFolha      = $oDadosCertid->folha;
        $this->iLivro      = $oDadosCertid->livro;
        $this->dataLivro   = $oDadosCertid->datalivro;
        $this->iTipo       = $oDadosCertid->tipocertidao;
        $this->massafalida = $oDadosCertid->v13_certidmassa;
        $this->iAno        = substr($oDadosCertid->v13_dtemis,0,4);

      }
    }
  }

  /**
   * @return unknown
   */
  public function getDataEmissao() {

    return $this->dataEmissao;
  }

  /**
   * @param unknown_type $dataEmissao
   */
  public function setDataEmissao($dataEmissao) {

    $this->dataEmissao = $dataEmissao;
  }

  /**
   * @return unknown
   */
  public function getDataLivro() {

    return $this->dataLivro;
  }

  /**
   * @param unknown_type $dataLivro
   */
  public function setDataLivro($dataLivro) {

    $this->dataLivro = $dataLivro;
  }

  /**
   * @return unknown
   */
  public function getCodigo() {

    return $this->iCodigo;
  }

  /**
   * @return unknown
   */
  public function getFolha() {

    return $this->iFolha;
  }

  /**
   * @param unknown_type $iFolha
   */
  public function setFolha($iFolha) {

    $this->iFolha = $iFolha;
  }

  public function setDataRecalculoJurosMulta($oDataRecalculoJurosMulta){
    $this->oDataRecalculoJurosMulta = $oDataRecalculoJurosMulta;
  }

  /**
   * @return unknown
   */
  public function getLivro() {

    return $this->iLivro;
  }

  /**
   * @param unknown_type $iLivro
   */
  public function setLivro($iLivro) {

    $this->iLivro = $iLivro;
  }

  /**
   * @return unknown
   */
  public function getTipo() {

    return $this->iTipo;
  }

  /**
   * @return unknown
   */
  public function getMassafalida() {

    return $this->massafalida;
  }

  /**
   * @param unknown_type $massafalida
   */
  public function setMassafalida($massafalida) {

    $this->massafalida = $massafalida;
  }


  public function getAno() {

    return $this->iAno;
  }


  public function getOrigensDebito(){

    $aOrigem = array();
    if ($this->getTipo() == 2) {
      $aOrigem = $this->getOrigemDebitoDivida();
    } else if ($this->getTipo() == 1) {
      $aOrigem = $this->getOrigemDebitoParcelamento();
    }
    if (count($aOrigem) == 0) {
      throw new Exception("CDA {$this->getCodigo()} sem Débitos.");
    }

     return $aOrigem;
  }


  protected function getOrigemDebitoDivida() {

    $sqlOrigemMatric  = "  select v01_numpre as numpre,                                                ";
    $sqlOrigemMatric .= "         v01_numpar as numpar,                                                ";
    $sqlOrigemMatric .= "         coalesce(arrematric.k00_matric,0) as matric,                         ";
    $sqlOrigemMatric .= "         coalesce(arreinscr.k00_inscr,0) as inscr,                            ";
    $sqlOrigemMatric .= "               k00_numcgm as numcgm                                           ";
    $sqlOrigemMatric .= "    from certdiv                                                              ";
    $sqlOrigemMatric .= "         inner join divida on v14_coddiv = v01_coddiv                         ";
    $sqlOrigemMatric .= "                          and v01_instit = ".db_getsession('DB_instit')."     ";
    $sqlOrigemMatric .= "         left join arrematric  on arrematric.k00_numpre = divida.v01_numpre   ";
    $sqlOrigemMatric .= "         left join arreinscr   on arreinscr.k00_numpre  =  divida.v01_numpre  ";
    $sqlOrigemMatric .= "         left join arrenumcgm  on arrenumcgm.k00_numpre  =  divida.v01_numpre ";
    $sqlOrigemMatric .= "   where v14_certid = {$this->iCodigo}                                        ";
    $sqlOrigemMatric .= "   order by v01_numpre,v01_numpar                                             ";

    $rsOrigemDebitos  = db_query($sqlOrigemMatric);
    $aOrigem          = array();
    $aOrigem          = db_utils::getCollectionByRecord($rsOrigemDebitos);
    return  $aOrigem;
  }


  protected function getOrigemDebitoParcelamento() {

    $sqlOrigemMatric  = "  select v07_numpre as numpre, ";
    $sqlOrigemMatric .= "         -1 as numpar, ";
    $sqlOrigemMatric .= "         coalesce(arrematric.k00_matric,0) as matric, ";
    $sqlOrigemMatric .= "         coalesce(arreinscr.k00_inscr,0) as inscr, ";
    $sqlOrigemMatric .= "         v07_numcgm as numcgm ";
    $sqlOrigemMatric .= "    from certter  ";
    $sqlOrigemMatric .= "         inner join termo      on v14_parcel = v07_parcel ";
    $sqlOrigemMatric .= "                              and v07_instit = ".db_getsession('DB_instit')." ";
    $sqlOrigemMatric .= "         left join arrematric  on arrematric.k00_numpre = termo.v07_numpre ";
    $sqlOrigemMatric .= "         left join arreinscr   on arreinscr.k00_numpre  = termo.v07_numpre ";
    $sqlOrigemMatric .= "   where v14_certid = {$this->iCodigo} ";
    $sqlOrigemMatric .= "   order by v07_numpre  ";
    $rsOrigemDebitos  = db_query($sqlOrigemMatric);
    $aOrigem          = array();
    $aOrigem          = db_utils::getCollectionByRecord($rsOrigemDebitos);
    return  $aOrigem;

  }


  function getProcessoParcelamento() {


    $sSqlProcParc   = "  select distinct                                                                              ";
    $sSqlProcParc  .= "         protprocesso.p58_codproc||'/'||extract(year from protprocesso.p58_dtproc) as processo ";
    $sSqlProcParc  .= "    from certter                                                                               ";
    $sSqlProcParc  .= "         inner join termoprotprocesso  on termoprotprocesso.v27_termo = certter.v14_parcel     ";
    $sSqlProcParc  .= "         inner join protprocesso       on protprocesso.p58_codproc    = termoprotprocesso.v27_protprocesso ";
    $sSqlProcParc  .= "                                      and protprocesso.p58_instit     = ".db_getsession('DB_instit');
    $sSqlProcParc  .= "   where v14_certid = {$this->iCodigo} ";

    $rsProcParc      = db_query($sSqlProcParc);
    $aDadosProcParc  = db_utils::getCollectionByRecord($rsProcParc);
  	$aAgrupaProcParc = array();

    foreach ($aDadosProcParc as $oDadosProcParc) {
    	$aAgrupaProcParc[] = $oDadosProcParc->processo;
    }

    if ( count($aAgrupaProcParc) > 0 ) {
	    $sRetorno = "Processo de Protocolo: ".implode(",",$aAgrupaProcParc);
    } else {
    	$sRetorno = '';

    	if (!isset($lRemissao)) {

        $lRemissao = false;
      }

      $aDebitos = $this->getDebitosDivida($lRemissao);

      $iAnoCDA = 0;

      foreach ($aDebitos as $oOrigem) {
        $iAnoCDA = $oOrigem->exercicio;
        $iProcedenciaCDA = $oOrigem->codigoprocedencia;
        break;
      }

      // COLOCAR IR PARA CANELA

      if (isset($iProcedenciaCDA) && isset($iAnoCDA) && ( $iProcedenciaCDA == 1  or
           $iProcedenciaCDA == 2  or
           $iProcedenciaCDA == 4  or
           $iProcedenciaCDA == 7  or
           $iProcedenciaCDA == 10 or
           $iProcedenciaCDA == 11 or
           $iProcedenciaCDA == 310 )) {

        if ( $iAnoCDA == 2009 ) {
          $sRetorno = "Processo de Protocolo: 30/2010";
        } elseif ( $iAnoCDA == 2008 ) {
          $sRetorno = "Processo de Protocolo: 11344/2008";
        } elseif ( $iAnoCDA == 2007 ) {
          $sRetorno = "Processo de Protocolo: 1153/2008";
        } elseif ( $iAnoCDA == 2006 ) {
          $sRetorno = "Processo de Protocolo: 1152/2008";
        }

      } elseif (isset($iProcedenciaCDA) && ($iProcedenciaCDA == 13 or $iProcedenciaCDA == 253)) {
        $sRetorno = "Processo de Protocolo: 6948/2010";
      }

    }

    return  $sRetorno;

  }

  function getObsNumpreParcelamento() {

    $sSqlObsNumpre   = "  select distinct                                                                     ";
    $sSqlObsNumpre  .= "         k00_histtxt as hist                                                          ";
    $sSqlObsNumpre  .= "    from certter                                                                      ";
    $sSqlObsNumpre  .= "         inner join termo     on termo.v07_parcel    = certter.v14_parcel             ";
    $sSqlObsNumpre  .= "                             and termo.v07_instit    = ".db_getsession('DB_instit')." ";
    $sSqlObsNumpre  .= "         inner join arrehist  on arrehist.k00_numpre = termo.v07_numpre               ";
    $sSqlObsNumpre  .= "   where v14_certid = {$this->iCodigo} ";

    $rsObsNumpre      = db_query($sSqlObsNumpre);
    $aDadosObsNumpre  = db_utils::getCollectionByRecord($rsObsNumpre);
    $aAgrupaObsNumpre = array();

    foreach ($aDadosObsNumpre as $oDadosObsNumpre) {
      $aAgrupaObsNumpre[] = $oDadosObsNumpre->hist;
    }

    if ( count($aAgrupaObsNumpre) > 0 ) {
	    $sRetorno = "Observações: ".implode(" - ",$aAgrupaObsNumpre);
    } else {
      $sRetorno = '';
    }

    return  $sRetorno;

  }


  function getDevedoresEnvolvidos($sTipoEndereco = 'o') {

    $aParams  = db_stdClass::getParametro("pardiv",array(db_getsession("DB_instit")));

    if (count($aParams) == 0) {
      throw new Exception("Sem parametros para o módulo dívida configurados");
    }

    $oPardiv = $aParams[0];

    $sExpressaoFalecimento = "";
    if ( $oPardiv->v04_confexpfalec != 2 ) {
      $sExpressaoFalecimento = $oPardiv->v04_expfalecimentocda;
    }

    if ($oPardiv->v04_envolprinciptu == "f") {
      $lRegra = "false";
    }else{
      $lRegra = "true";
    }

    $aMatric              = array();
    $aInscr               = array();
    $aCgm                 = array();
    $aImoveisEnvolvidos   = array();
    $aEmpresasEnvolvidos  = array();
    $aDevedoresEnvolvidos = array();
    $aOrigens = $this->getOrigensDebito();

    foreach ($aOrigens as $oOrigens) {

      if ($oOrigens->matric > 0 && in_array($oOrigens->matric,$aMatric) ){
        continue;
      } else {

        if ($oOrigens->matric > 0) {

          /**
           * Procuramos o texto para o possuidor da matricula
           */
          $sqlPossuidor    = " select j18_textoprom                           ";
          $sqlPossuidor   .= "   from cfiptu                                  ";
          $sqlPossuidor   .= "  where j18_anousu= ".db_getsession("DB_anousu") ;
          $resultPossuidor = db_query($sqlPossuidor);
          $linhasPossuidor = pg_num_rows($resultPossuidor);
          $possuidor = "POSSUIDOR";
          if ($linhasPossuidor > 0) {

            $oTextoPossuido = db_utils::fieldsmemory($resultPossuidor,0);
            if (trim($oTextoPossuido->j18_textoprom) != "") {
               $possuidor = $oTextoPossuido->j18_textoprom;
            }
          }

          /**
           * Buscamos as matriculas da divida
           */
          $sSqlEnvol    = " select * from fc_busca_envolvidos({$lRegra},{$oPardiv->v04_envolcdaiptu},'M',{$oOrigens->matric})";
          $rsEnvol      = db_query($sSqlEnvol) or die($sSqlEnvol);
          $iLinhasEnvol = pg_num_rows($rsEnvol);
          if ($oPardiv->v04_envolcdaiptu == 2 && $iLinhasEnvol == 0 ) {

             $sSqlEnvol  = " select j01_numcgm as rinumcgm,   ";
             $sSqlEnvol .= "        1          as ritipoenvol ";
             $sSqlEnvol .= "   from iptubase                  ";
             $sSqlEnvol .= "  where j01_matric = {$oOrigens->matric}    ";
             $rsEnvol      = db_query($sSqlEnvol) or die($sSqlEnvol);
             $iLinhasEnvol = pg_num_rows($rsEnvol);

          }

          for ($i = 0; $i < $iLinhasEnvol; $i++) {

            $oDevedor = new stdClass();
            $oEnvol   = db_utils::fieldsMemory($rsEnvol,$i);

            $sSqlDadosEnvol  = " select z01_numcgm,                     ";
            $sSqlDadosEnvol .= "        z01_nome,                       ";
            $sSqlDadosEnvol .= "        z01_cgccpf,                     ";
            $sSqlDadosEnvol .= "        z01_telef,                      ";
            $sSqlDadosEnvol .= "        z01_ender,                      ";
            $sSqlDadosEnvol .= "        z01_numero,                     ";
            $sSqlDadosEnvol .= "        z01_compl,                      ";
            $sSqlDadosEnvol .= "        z01_bairro,                     ";
            $sSqlDadosEnvol .= "        z01_munic,                      ";
            $sSqlDadosEnvol .= "        z01_cep,                        ";
            $sSqlDadosEnvol .= "        z01_uf,                         ";
            $sSqlDadosEnvol .= "        z01_dtfalecimento               ";
            $sSqlDadosEnvol .= "   from cgm                             ";
            $sSqlDadosEnvol .= "  where z01_numcgm = {$oEnvol->rinumcgm}";
            $rsDadosEnvol      = db_query($sSqlDadosEnvol) or die($sSqlDadosEnvol);
            $iLinhasDadosEnvol = pg_num_rows($rsDadosEnvol);
            if ($iLinhasDadosEnvol > 0) {

              $oDadosEnvol = db_utils::fieldsMemory($rsDadosEnvol,0);
              if (trim($oDadosEnvol->z01_dtfalecimento) != '' && strlen($oDadosEnvol->z01_cgccpf) == 11
                  && $oDadosEnvol != '00000000000') {
                $oDevedor->nome = $sExpressaoFalecimento." ".$oDadosEnvol->z01_nome;
              } else {
                $oDevedor->nome = $oDadosEnvol->z01_nome;
              }
              $oDevedor->numcgm   = $oDadosEnvol->z01_numcgm;
              $oDevedor->telefone = $oDadosEnvol->z01_telef;
              $oDevedor->endereco = "";
              $oDevedor->endereco = $oDadosEnvol->z01_ender;
              if (trim($oDadosEnvol->z01_numero) !="0" and trim($oDadosEnvol->z01_numero)!="") {
                $oDevedor->endereco .= ",{$oDadosEnvol->z01_numero} ";
              }
              if (trim($oDadosEnvol->z01_compl)  !="0" and trim($oDadosEnvol->z01_compl) !="") {
                $oDevedor->endereco .= ",{$oDadosEnvol->z01_compl} ";
              }
              if (trim($oDadosEnvol->z01_bairro) !="0" and trim($oDadosEnvol->z01_bairro)!="") {
                $oDevedor->endereco .= ",{$oDadosEnvol->z01_bairro} ";
              }
              if (trim($oDadosEnvol->z01_munic)  !="0" and trim($oDadosEnvol->z01_munic) !="") {
                $oDevedor->endereco .= ",{$oDadosEnvol->z01_munic}/{$oDadosEnvol->z01_uf} ";
              }
              if (trim($oDadosEnvol->z01_cep) !="0" and trim($oDadosEnvol->z01_cep) !="") {
                $oDevedor->endereco .= "- CEP {$oDadosEnvol->z01_cep} .";
              }

              /**
               * Verifica o tipo do Devedor
               */
              if ($oEnvol->ritipoenvol == "1" || $oEnvol->ritipoenvol == "2") {
                $oDevedor->tipo = "PROPRIETÁRIO";
              }else{
                $oDevedor->tipo = $possuidor;
              }

              if (strlen($oDadosEnvol->z01_cgccpf) == 14){
                $oDevedor->cgcCpf = db_formatar($oDadosEnvol->z01_cgccpf,"cnpj");
              }else{
                $oDevedor->cgcCpf = db_formatar($oDadosEnvol->z01_cgccpf,"cpf");
              }
              $aDevedoresEnvolvidos[] = $oDevedor;
            }
          }

          /**
           * Retornamos os dados do imovel
           */
          $sSqlProprietario  = " select *                    ";
          $sSqlProprietario .= "   from proprietario         ";
          $sSqlProprietario .= "  where j01_matric = $oOrigens->matric ";
          $rsProprietario    = db_query($sSqlProprietario) or die($sSqlProprietario);
          $oProprietario     = db_utils::fieldsMemory($rsProprietario,0);
          $oImovel           = new stdClass();

          /**
           * quando solicitado  o endereço de origem
           */
          if ($sTipoEndereco == "o") {

            $oImovel->endereco = $oProprietario->nomepri.(isset($oProprietario->j39_numero)?", ".$oProprietario->j39_numero:"")
                                .(isset($oProprietario->j39_compl)?", ".$oProprietario->j39_compl:"");
            $oImovel->bairro  = $oProprietario->j13_descr;

            $sqlcidade        = "select munic, uf, cep from db_config where codigo = ".db_getsession('DB_instit');
            $resultcidade     = db_query($sqlcidade);
            $oCidade          = db_utils::fieldsmemory($resultcidade,0);
            $oImovel->cidade = $oCidade->munic.' / '.$oCidade->uf;
            $oImovel->cep    = $oCidade->cep;

          } elseif ($sTipoEndereco == "c") {

            $oImovel->endereco = $oProprietario->z01_ender.
                                 ($oProprietario->z01_numero != ""?', ' . $oProprietario->z01_numero:"").
                                 ($oProprietario->z01_compl != ""?"/" . $oProprietario->z01_compl:"");
            $oImovel->bairro   = $oProprietario->z01_bairro;
            $oImovel->cidade   = $oProprietario->z01_munic.' / '.$oProprietario->z01_uf;
            $oImovel->cep      = $oProprietario->z01_cep;

          }

          $oImovel->setor       = $oProprietario->j34_setor;
          $oImovel->quadra      = $oProprietario->j34_quadra;
          $oImovel->lote        = $oProprietario->j34_lote;
          $oImovel->matricula   = $oOrigens->matric;
          $oImovel->refanterior = $oProprietario->j40_refant;

          $oImovel->setorloc      = $oProprietario->pql_localizacao;

          $aImoveisEnvolvidos[] = $oImovel;
          $aMatric[]            = $oOrigens->matric;
        }
      }

      /**
       * Verificando as inscrições
       */
      if ($oOrigens->inscr > 0 && in_array($oOrigens->inscr,$aInscr)) {
          continue;
      } else {

        if ($oOrigens->inscr > 0) {

          $sSqlEnvol = " select * from fc_busca_envolvidos({$lRegra},{$oPardiv->v04_envolcdaiss},'I',{$oOrigens->inscr})";
          $rsEnvol      = db_query($sSqlEnvol) or die($sSqlEnvol);
          $iLinhasEnvol = pg_num_rows($rsEnvol);
          for ($i = 0; $i < $iLinhasEnvol; $i++ ) {

          	$oDevedor = new stdClass();
            $oEnvol = db_utils::fieldsMemory($rsEnvol, $i);
            if (empty($oEnvol->rinumcgm)) {
              continue;
            }
            $sSqlDadosEnvol  = " select z01_numcgm,                     ";
            $sSqlDadosEnvol .= "        z01_nome,                       ";
            $sSqlDadosEnvol .= "        z01_cgccpf,                     ";
            $sSqlDadosEnvol .= "        z01_telef,                      ";
            $sSqlDadosEnvol .= "        z01_numero,                     ";
            $sSqlDadosEnvol .= "        z01_ender  as ender,            ";
            $sSqlDadosEnvol .= "        z01_numero as numero,           ";
            $sSqlDadosEnvol .= "        z01_compl  as compl,            ";
            $sSqlDadosEnvol .= "        z01_bairro as bairro,           ";
            $sSqlDadosEnvol .= "        z01_munic  as munic,            ";
            $sSqlDadosEnvol .= "        z01_cep    as cep,              ";
            $sSqlDadosEnvol .= "        z01_uf     as uf,               ";
            $sSqlDadosEnvol .= "        z01_dtfalecimento               ";
            $sSqlDadosEnvol .= "   from cgm                             ";
            $sSqlDadosEnvol .= "  where z01_numcgm = {$oEnvol->rinumcgm}";
            $rsDadosEnvol      = db_query($sSqlDadosEnvol);
            $iLinhasDadosEnvol = pg_num_rows($rsDadosEnvol);
            if ($iLinhasDadosEnvol > 0) {

              $oDadosEnvol = db_utils::fieldsMemory($rsDadosEnvol,0);


              if ( trim($oDadosEnvol->z01_dtfalecimento) != '' && strlen($oDadosEnvol->z01_cgccpf) == 11) {
                $oDevedor->nome = $sExpressaoFalecimento." ".$oDadosEnvol->z01_nome;
              } else {
                $oDevedor->nome = $oDadosEnvol->z01_nome;
              }

              $oDevedor->numcgm   = $oDadosEnvol->z01_numcgm;
              $oDevedor->telefone = $oDadosEnvol->z01_telef;
              $oDevedor->endereco = "";
              $oDevedor->endereco = $oDadosEnvol->ender;
              if (trim($oDadosEnvol->numero) !="0" and trim($oDadosEnvol->numero)!="") {
                $oDevedor->endereco .= ",{$oDadosEnvol->numero} ";
              }
              if (trim($oDadosEnvol->compl)  !="0" and trim($oDadosEnvol->compl) !="") {
                $oDevedor->endereco .= ",{$oDadosEnvol->compl} ";
              }
              if (trim($oDadosEnvol->bairro) !="0" and trim($oDadosEnvol->bairro)!="") {
                $oDevedor->endereco .= ",{$oDadosEnvol->bairro} ";
              }
              if (trim($oDadosEnvol->munic)  !="0" and trim($oDadosEnvol->munic) !="") {
                $oDevedor->endereco .= ",{$oDadosEnvol->munic}/{$oDadosEnvol->uf}";
              }
              if (trim($oDadosEnvol->cep)    !="0" and trim($oDadosEnvol->cep)   !="") {
                $oDevedor->endereco .= "- CEP {$oDadosEnvol->cep} .";
              }

              if (strlen($oDadosEnvol->z01_cgccpf) > 11) {
                if ($oEnvol->ritipoenvol == "4"){
                  $oDevedor->tipo = "EMPRESA";
                } else if ($oEnvol->ritipoenvol == "5") {
                  $oDevedor->tipo = "SÓCIO";
                }
              } else {
              	$oDevedor->tipo = "CONTRIBUINTE";
              }

              if (strlen($oDadosEnvol->z01_cgccpf) > 11){
                $oDevedor->cgcCpf = db_formatar($oDadosEnvol->z01_cgccpf,"cnpj");
              } else {
                $oDevedor->cgcCpf = db_formatar($oDadosEnvol->z01_cgccpf,"cpf");
              }

            }
            $aDevedoresEnvolvidos[] = $oDevedor;
          }

          /**
           * Retorna os dados da Inscrição
           */
          $sSqlEmpresa  = " select *                  ";
          $sSqlEmpresa .= "   from empresa            ";
          $sSqlEmpresa .= "  where q02_inscr = $oOrigens->inscr ";
          $rsEmpresa    = db_query($sSqlEmpresa) or die($sSqlEmpresa);
          $oEmpresa     = db_utils::fieldsMemory($rsEmpresa,0);
          $oEmpresa->inscricao = $oOrigens->inscr;
          $oEmpresa->endereco  = $oEmpresa->j14_tipo.' '.$oEmpresa->z01_ender.', '.$oEmpresa->z01_numero.'  '.
                                 $oEmpresa->z01_compl;
          $oEmpresa->bairro    = $oEmpresa->z01_bairro;
          $oEmpresa->cidade    = $oEmpresa->z01_munic.' / '.$oEmpresa->z01_uf;
          $oEmpresa->cep       = $oEmpresa->z01_cep;
          $aInscr[]            = $oOrigens->inscr;
          $aEmpresasEnvolvidos[] = $oEmpresa;
        }
      }

      /**
       * Verificamos o CGM
       */
      if (in_array($oOrigens->numcgm, $aCgm) ) {
        continue;
      } else {

        if ( $oOrigens->matric == 0  && $oOrigens->inscr == 0 ) {

          $sSqlCgm  = " select *                             ";
          $sSqlCgm .= "   from cgm                           ";
          $sSqlCgm .= "  where z01_numcgm = $oOrigens->numcgm";
          $rsCgm = db_query($sSqlCgm) or die($sSqlCgm);
          $oCgm  = db_utils::fieldsMemory($rsCgm,0);
          $oDevedor = new stdClass();
          $oDevedor->endereco = $oCgm->z01_ender;

          if (trim($oCgm->z01_numero)!="0" and trim($oCgm->z01_numero)!="") {
            $oDevedor->endereco .= ",{$oCgm->z01_numero} ";
          }
          if (trim($oCgm->z01_compl)!="0" and trim($oCgm->z01_compl)!="") {
             $oDevedor->endereco .= ",{$oCgm->z01_compl} ";
          }
          if (trim($oCgm->z01_bairro)!="0" and  trim($oCgm->z01_bairro)!="") {
            $oDevedor->endereco .= ",{$oCgm->z01_bairro} ";
          }
          if (trim($oCgm->z01_munic) !="0" and trim($oCgm->z01_munic)!="") {
            $oDevedor->endereco .= ",{$oCgm->z01_munic}/{$oCgm->z01_uf} ";
          }

          if (trim($oCgm->z01_cep) !="0" and trim($oCgm->z01_cep)!="") {
            $oDevedor->endereco .= "- CEP {$oCgm->z01_cep} .";
          }

          $oDevedor->numcgm   = $oCgm->z01_numcgm;
          $oDevedor->telefone = $oCgm->z01_telef;
          $oDevedor->nome     = $oCgm->z01_nome;
          if (strlen($oCgm->z01_cgccpf) > 11){
            $oDevedor->cgcCpf = db_formatar($oCgm->z01_cgccpf,'cnpj');
          } else {
          	$oDevedor->cgcCpf = db_formatar($oCgm->z01_cgccpf,'cpf');
          }
          $oDevedor->tipo = "";
          $aCgm[]    = $oOrigens->numcgm;
          $aDevedoresEnvolvidos[] =  $oDevedor;

        }
      }
    }
    $oRetorno = new stdClass();
    $oRetorno->aDevedores = array_map( "unserialize", array_unique( array_map( "serialize", $aDevedoresEnvolvidos) ) );
    $oRetorno->aImoveis   = $aImoveisEnvolvidos;
    $oRetorno->aEmpresas  = $aEmpresasEnvolvidos;
    return $oRetorno;
  }

  function getDebitos($lRemissao = false) {

    $aDebitos = array();

    if ($this->getTipo() == 2) {
      $aDebitos = $this->getDebitosDivida($lRemissao);
    } else if ($this->getTipo() == 1) {
      $aDebitos = $this->getDebitosParcelamento($lRemissao);
    }

    /**
     * Verificamos se existem procedencias que devemos agrupar, e
     * agrupamos com a seguinte lógica :
     *   - Criamos um hash com os campos exercício/parcela/origem/procedência (v24_procedagrupa)
     *   - Comparar exercício/parcela/origem/procedência
     *   - é somado todos os valores e e os outros campos (exercício/livro e folha/data inscrição/data vencimento)
     *     é utilizado sempre o do registro da procedência principal que está agrupando
     */

    /**
     * Array com todos os debitos agrupados
     */
    $aDebitosAgrupado    = array();

    /**
     * Debitos que sao agrupadores debitos sem procedenciaagrupa
     */
    $aDebitosAgrupadores = array();

    /**
     * Debitos com procedenciaagrupa
     */
    $aDebitosParaAgrupar = array();

    /**
     * Verificamos quais debitos estao configurados para agrupar
     */
    $i     = 0;
    $sHash = "";

    foreach ($aDebitos as $oOrigem) {

      if ($oOrigem->procedenciaagrupar != "") {

		    $sHash = $oOrigem->exercicio.$oOrigem->numpar.$oOrigem->procedenciaagrupar;
        if  (isset($aDebitosParaAgrupar[$sHash]))	{

	   	    $aDebitosParaAgrupar[$sHash]->valorhistorico += $oOrigem->valorhistorico;
	        $aDebitosParaAgrupar[$sHash]->valorcorrigido += $oOrigem->valorcorrigido;
	   	    $aDebitosParaAgrupar[$sHash]->valorcorrecao  += $oOrigem->valorcorrigido - $oOrigem->valorhistorico;
	        $aDebitosParaAgrupar[$sHash]->valormulta     += $oOrigem->valormulta;
	        $aDebitosParaAgrupar[$sHash]->valorjuros     += $oOrigem->valorjuros;
	        $aDebitosParaAgrupar[$sHash]->valortotal     += $oOrigem->valortotal;

	      } else {
          $aDebitosParaAgrupar[$sHash] = $oOrigem;
          $aDebitosParaAgrupar[$sHash]->hash = $sHash;

	      }

      } else {

        $sHash = $oOrigem->exercicio.$oOrigem->numpar.$oOrigem->codigoprocedencia;
        $aDebitosAgrupadores[$i] = $oOrigem;
        $aDebitosAgrupadores[$i]->hash = $sHash;
        $i++;
      }

    }

    foreach ($aDebitosParaAgrupar as $sHash => $oDebitoAgrupar) {

      $iTotalDebitosAgrupadores = count($aDebitosAgrupadores);

      $lFound = '';
      for ($i=0; $i < $iTotalDebitosAgrupadores; $i ++) {

       	 if ($aDebitosAgrupadores[$i]->hash == $sHash) {

           $aDebitosAgrupadores[$i]->valorhistorico += $oDebitoAgrupar->valorhistorico;
           $aDebitosAgrupadores[$i]->valorcorrigido += $oDebitoAgrupar->valorcorrigido;
           $aDebitosAgrupadores[$i]->valorcorrecao  += $oDebitoAgrupar->valorcorrigido - $oDebitoAgrupar->valorhistorico;
           $aDebitosAgrupadores[$i]->valormulta     += $oDebitoAgrupar->valormulta;
           $aDebitosAgrupadores[$i]->valorjuros     += $oDebitoAgrupar->valorjuros;
           $aDebitosAgrupadores[$i]->valortotal     += $oDebitoAgrupar->valortotal;
           $lFound = true;
           break;

         } else {
         	$lFound = false;
         }

       }

       if (!$lFound) {
       	  $aDebitosAgrupadores[] = $oDebitoAgrupar;
       }

    }

    ksort($aDebitosAgrupadores);

    /**
     * Percorremos os outros debitos e fizemso os agrupamentos
     */
    unset($aDebitosParaAgrupar);

    return $aDebitosAgrupadores;
  }


  protected function getDebitosDivida($lRemissao = false) {

     $sqlDadosDivida  = "select v01_numpre, ";
     $sqlDadosDivida .= "       v01_numpar, ";
     $sqlDadosDivida .= "       v01_exerc,  ";
     $sqlDadosDivida .= "       v01_livro,  ";
     $sqlDadosDivida .= "       v01_coddiv, ";
     $sqlDadosDivida .= "       v01_folha,  ";
     $sqlDadosDivida .= "       case when v01_processo = '' then p58_codproc::varchar";
     $sqlDadosDivida .= "       else v01_processo end as v01_processo,";
     $sqlDadosDivida .= "       case when v01_dtprocesso is null then p58_dtproc ";
     $sqlDadosDivida .= "       else v01_dtprocesso end as v01_dtprocesso, ";
     $sqlDadosDivida .= "       v01_obs, ";
     $sqlDadosDivida .= "       v01_numcgm, ";
     $sqlDadosDivida .= "       v01_proced, ";
     $sqlDadosDivida .= "       v01_dtinsc, ";
     $sqlDadosDivida .= "       lote.*,    ";
     $sqlDadosDivida .= "       coalesce(certidmassa.v13_certid) as v13_certidmassa, ";
     $sqlDadosDivida .= "       coalesce(arrematric.k00_matric,0) as matric, ";
     $sqlDadosDivida .= "       coalesce(arreinscr.k00_inscr,0) as inscr, ";
     $sqlDadosDivida .= "       v03_descr, ";
     $sqlDadosDivida .= "       v13_dtemis, ";
     $sqlDadosDivida .= "       v24_procedagrupa, ";
     $sqlDadosDivida .= "       v03_tributaria ";
     $sqlDadosDivida .= "  from certdiv  ";
     $sqlDadosDivida .= "       inner join divida           on v14_coddiv             = v01_coddiv ";
     $sqlDadosDivida .= "                                  and v01_instit             = ".db_getsession('DB_instit');
     /* Olhar somente os debitos abertos na emissao atualizada */
     if (!$lRemissao) {
        $sqlDadosDivida .= "    inner join arrecad on arrecad.k00_numpre  = divida.v01_numpre      ";
        $sqlDadosDivida .= "                              and arrecad.k00_numpar  = divida.v01_numpar      ";
     }
     $sqlDadosDivida .= "       left join certid            on certid.v13_certid      = certdiv.v14_certid ";
     $sqlDadosDivida .= "                                  and certid.v13_instit      = ".db_getsession('DB_instit');
     $sqlDadosDivida .= "       left join certidmassa       on certidmassa.v13_certid = certid.v13_certid ";
     $sqlDadosDivida .= "       left join arrematric        on arrematric.k00_numpre  = divida.v01_numpre ";
     $sqlDadosDivida .= "       left join iptubase a        on arrematric.k00_matric  = a.j01_matric ";
     $sqlDadosDivida .= "       left join lote              on lote.j34_idbql         = a.j01_idbql ";
     $sqlDadosDivida .= "       left join arreinscr         on arreinscr.k00_numpre   =  divida.v01_numpre ";
     $sqlDadosDivida .= "       left join proced            on proced.v03_codigo      = divida.v01_proced ";
     $sqlDadosDivida .= "                                  and proced.v03_instit      = ".db_getsession('DB_instit');
     $sqlDadosDivida .= "       left join procedenciaagrupa on   v03_codigo           = v24_proced ";
     $sqlDadosDivida .= "       left join dividaprotprocesso on dividaprotprocesso.v88_divida = divida.v01_coddiv ";
     $sqlDadosDivida .= "       left join protprocesso on protprocesso.p58_codproc = dividaprotprocesso.v88_protprocesso ";
     $sqlDadosDivida .= " where v14_certid = {$this->getCodigo()}";
     $sqlDadosDivida .= " order by v03_tributaria,v01_exerc, v01_proced,v01_numpre,v01_numpar,v24_procedagrupa ";

     $rsDadosDivida   = $this->oDaoCertid->sql_record($sqlDadosDivida);
     $aDebitos        = array();

     if ($this->oDaoCertid->numrows > 0) {

      $oInstituicao      = new Instituicao(db_getsession('DB_instit'));
      $oParametrosDivida = db_stdClass::getParametro("pardiv", array($oInstituicao->getSequencial()));

       for ($i = 0; $i < $this->oDaoCertid->numrows; $i++) {

         $oDivida = db_utils::fieldsmemory($rsDadosDivida, $i);

         /**
          * Verificamos o debito, e o corrigimos (rodamos fc_calcula)
          */
        if (!empty($this->oDataRecalculoJurosMulta)) {

          $sData = $this->oDataRecalculoJurosMulta->getDate();

           $dataemis = mktime(0,0,0,substr($sData,5,2),
                                    substr($sData,8,2),
                                    substr($sData,0,4)
                              );
           $anoemis  = substr($sData,0,4);
           $xmes     = substr($sData,5,2);
           $xdia     = substr($sData,8,2);
           $xano     = substr($sData,0,4);
        }else{

         if ($lRemissao){

           $dataemis = mktime(0,0,0,substr($oDivida->v13_dtemis,5,2),
                                    substr($oDivida->v13_dtemis,8,2),
                                    substr($oDivida->v13_dtemis,0,4)
                              );
           $anoemis  = substr($oDivida->v13_dtemis,0,4);
           $xmes     = substr($oDivida->v13_dtemis,5,2);
           $xdia     = substr($oDivida->v13_dtemis,8,2);
           $xano     = substr($oDivida->v13_dtemis,0,4);
         } else {

           $dataemis = db_getsession("DB_datausu");
           $anoemis  = db_getsession("DB_anousu");
           $xmes = date('m');
           $xdia = date('d');
           $xano = date('Y');
         }
        }


         if ( $this->isComposicao() ) {


           $sSqlVerificaArrecad = "select arrecad.*,
                                          arrecadcompos.k00_vlrhist  as vlrhis,
                                          (arrecadcompos.k00_vlrhist + arrecadcompos.k00_correcao) as vlrcor,
                                          arrecadcompos.k00_juros    as vlrjuros,
                                          arrecadcompos.k00_multa    as vlrmulta
                                     from arrecad
                                          inner join arreckey      on arrecad.k00_numpre         = arreckey.k00_numpre
                                                                  and arrecad.k00_numpar         = arreckey.k00_numpar
                                                                  and arrecad.k00_receit         = arreckey.k00_receit
                                          inner join arrecadcompos on arrecadcompos.k00_arreckey = arreckey.k00_sequencial
                                          inner join tabrec        on tabrec.k02_codigo          = arrecad.k00_receit
                                    where arrecad.k00_numpre = {$oDivida->v01_numpre}
                                      and arrecad.k00_numpar = {$oDivida->v01_numpar}
                                    order by arrecad.k00_numpre,
                                             arrecad.k00_numpar,
                                             arrecad.k00_receit ";

           $rsArrecad = db_query($sSqlVerificaArrecad) or die($sSqlVerificaArrecad);


           if ( pg_num_rows($rsArrecad) == 0 ) {

	           $sSqlVerificaArreold = "select arreold.*,
	                                          arrecadcompos.k00_vlrhist  as vlrhis,
	                                          (arrecadcompos.k00_vlrhist + arrecadcompos.k00_correcao) as vlrcor,
	                                          arrecadcompos.k00_juros    as vlrjuros,
	                                          arrecadcompos.k00_multa    as vlrmulta
	                                     from arreold
	                                          inner join arreckey      on arreold.k00_numpre         = arreckey.k00_numpre
	                                                                  and arreold.k00_numpar         = arreckey.k00_numpar
	                                                                  and arreold.k00_receit         = arreckey.k00_receit
	                                          inner join arrecadcompos on arrecadcompos.k00_arreckey = arreckey.k00_sequencial
	                                          inner join tabrec        on tabrec.k02_codigo          = arreold.k00_receit
	                                    where arreold.k00_numpre = {$oDivida->v01_numpre}
	                                      and arreold.k00_numpar = {$oDivida->v01_numpar}
	                                    order by arreold.k00_numpre,
	                                             arreold.k00_numpar,
	                                             arreold.k00_receit ";

	           $rsArrecad = db_query($sSqlVerificaArreold) or die($sSqlVerificaArreold);

           } else {

             throw new Exception("certidao ({$this->getCodigo()}) com Débitos pagos ou cancelados, consulte pagamentos!");
             exit;

           }

         } else {

           $sSqlVerificaArrecad = "select * from arrecad where k00_numpre = {$oDivida->v01_numpre}";
	         $rsArrecad          = db_query($sSqlVerificaArrecad) or die($sSqlVerificaArrecad);
               /*@todo: verifica reemissao para considerar a arrecad */
	         if (pg_num_rows($rsArrecad) > 0 && !$lRemissao) {
              $rsArrecad  = debitos_numpre($oDivida->v01_numpre,0,0,
                                           $dataemis,
                                           $anoemis,$oDivida->v01_numpar,
                                           "",
                                           "",
                                           " and y.k00_hist <> 918");
	         } else {

	           $sSqlVerificaArrecad = "select * from arreold where k00_numpre = $oDivida->v01_numpre";
	           $rsArrecad = db_query($sSqlVerificaArrecad) or die($sSqlVerificaArrecad);
	           if (pg_num_rows($rsArrecad) > 0) {

	              $rsArrecad = debitos_numpre_old($oDivida->v01_numpre,0,0,
	                                              $dataemis,
	                                              $anoemis,
	                                              $oDivida->v01_numpar,'',''
	                                             );
	           } else {

	             /**
	              * TODO Verificar com evandro o porque dessa logica
	              */
	             $sqlprocuraarreforo  = "select k00_numpre,
	                                            k00_numpar,
	                                            k00_numcgm,
	                                            k00_dtoper,
	                                            k00_receit,
	                                            k00_hist,
	                                            k00_valor,
	                                            k00_dtvenc,
	                                            k00_numtot,
	                                            k00_numdig,
	                                            k00_tipo
	                                       from arreforo
	                                      where k00_certidao = {$this->getCodigo()}";
	             $resultprocuraarreforo = db_query($sqlprocuraarreforo) or die($sqlprocuraarreforo);
	             if (pg_num_rows($resultprocuraarreforo) > 0) {
	                 $sqlInsertArreold = "insert into arreold (k00_numpre,k00_numpar,
	                                                           k00_numcgm,k00_dtoper,
	                                                           k00_receit,
	                                                           k00_hist,
	                                                           k00_valor,
	                                                           k00_dtvenc,
	                                                           k00_numtot,
	                                                           k00_numdig,
	                                                           k00_tipo )
	                                                           {$sqlprocuraarreforo}
	                                                           and not exists ( select 1
	                                                                              from arreold
	                                                                             where arreold.k00_numpre = arreforo.k00_numpre
	                                                                               and arreold.k00_numpar = arreforo.k00_numpar
	                                                                               and arreold.k00_receit = arreforo.k00_receit)";
	                  db_query($sqlInsertArreold) or die($sqlInsertArreold);
	                  $rsArrecad = debitos_numpre_old($oDivida->v01_numpre,0,0,$dataemis,$anoemis,$oDivida->v01_numpar,'','');

	             } else {

	               throw new Exception("certidao ({$this->getCodigo()}) com Débitos pagos ou cancelados, consulte pagamentos!");
	               exit;

	             }
	           }
	         }
         }

         if ($rsArrecad){
           $iNumRowsArrecad = pg_num_rows($rsArrecad);
         } else {
           $iNumRowsArrecad = 0;
         }

         /**
          * percorremos os debitos da arrecad
          */
         for ($iArrecad = 0;$iArrecad < $iNumRowsArrecad; $iArrecad++) {

           $oDadosDebitoAtualizado            = db_utils::fieldsmemory($rsArrecad ,$iArrecad);
           $oDividaCda                        = new stdClass();
           $oDividaCda->exercicio             = $oDivida->v01_exerc;
           $oDividaCda->livro                 = $oDivida->v01_livro;
           $oDividaCda->codigodivida          = $oDivida->v01_coddiv;
           $oDividaCda->folha                 = $oDivida->v01_folha;
           $oDividaCda->certidmassa           = $oDivida->v13_certidmassa;
           $oDividaCda->observacao            = $oDivida->v01_obs . "\nProcesso:" . $oDivida->v01_processo . " Data:" . db_formatar($oDivida->v01_dtprocesso, 'd');
           $oDividaCda->procedenciaagrupar    = $oDivida->v24_procedagrupa;

           if ($oDivida->v03_tributaria == "t" || $oDivida->v03_tributaria == 1) {
             $oDividaCda->procedenciatributaria = true;
           } else {
           	 $oDividaCda->procedenciatributaria = false;
           }

           if ($oDivida->matric != 0) {

             $oDividaCda->origem       = "mat";
             $oDividaCda->codigoorigem = $oDivida->matric;

             if (isset($oDivida->j34_setor) && $oDivida->j34_setor != "" && isset($oDivida->j34_quadra)
                 && $oDivida->j34_quadra != "" && isset($oDivida->j34_lote) && $oDivida->j34_lote != "") {
               $oDividaCda->origemdebito = $oDivida->j34_setor."/".$oDivida->j34_quadra."/".$oDivida->j34_lote;
             } else {
               $oDividaCda->origemdebito = $oDivida->j34_lote;
             }

           } elseif ($oDivida->inscr != 0) {

             $oDividaCda->origem       = "inscr";
             $oDividaCda->codigoorigem = $oDivida->inscr;
             $oDividaCda->origemdebito = ucfirst($oDividaCda->origem)." - ".$oDivida->inscr;

           } else {

             $oDividaCda->origem       = "cgm";
             $oDividaCda->codigoorigem = $oDivida->v01_numcgm;
             $oDividaCda->origemdebito = ucfirst($oDividaCda->origem)." - ".$oDivida->v01_numcgm;

           }

           $oDividaCda->procedencia       = $oDivida->v03_descr;
           $oDividaCda->codigoprocedencia = $oDivida->v01_proced;

           $dDataLancamento = $this->getDataLancamentoDebito($oDadosDebitoAtualizado->k00_numpre, $oDadosDebitoAtualizado->k00_numpar);
           if ($dDataLancamento == '') {
           	$dDataLancamento = $oDadosDebitoAtualizado->k00_dtoper;
           }


           $oDividaCda->datalancamento    = $dDataLancamento;
           $oDividaCda->datainscricao     = $oDivida->v01_dtinsc;
           $oDividaCda->datavencimento    = $oDadosDebitoAtualizado->k00_dtvenc;
           $oDividaCda->dataoperacao      = $oDadosDebitoAtualizado->k00_dtoper;
           $oDividaCda->numpre            = $oDadosDebitoAtualizado->k00_numpre;
           $oDividaCda->numpar            = $oDadosDebitoAtualizado->k00_numpar;
           $oDividaCda->valorcorrecao     = $oDadosDebitoAtualizado->vlrcor - $oDadosDebitoAtualizado->vlrhis;
           $oDividaCda->valorhistorico    = $oDadosDebitoAtualizado->vlrhis;
           $oDividaCda->valorcorrigido    = $oDadosDebitoAtualizado->vlrcor;
           $oDividaCda->valormulta        = $oDadosDebitoAtualizado->vlrmulta;
           $oDividaCda->valorjuros        = $oDadosDebitoAtualizado->vlrjuros;
           $oDividaCda->valortotal        = $oDadosDebitoAtualizado->vlrjuros +
                                            $oDadosDebitoAtualizado->vlrmulta +
                                            $oDadosDebitoAtualizado->vlrcor;
           $aDebitos[]                    = $oDividaCda;
         }
       }
     }

     return $aDebitos;
  }


  protected function getDebitosParcelamento($lRemissao = false) {

    $aOrigens       = $this->getOrigensDebito();
    $oDaoTermo      = new cl_termo;
    $oDaoCertter    = db_utils::getDao('certter');
    $aOrigensNova[] = $aOrigens;

    $sCampos =  " v01_coddiv as codigodivida,
                  v01_exerc  as exercicio,
                  v03_descr  as procedencia,
                  v01_dtinsc as datainscricao,
                  v01_dtvenc as datavencimento,
                  v01_dtoper as dataoperacao,
                  v01_livro  as livro,
                  v01_folha  as folha,
                  v01_proced as codigoprocedencia,
                  v01_obs    as observacao,
                  v01_numpar as numpar,
                  v01_numpre as numpre,
                  v01_vlrhis as valorhistorico,
                  0 as valorcorrigido,
                  0 as valorcorrecao,
                  0 as valormulta,
                  0 as valorjuros,
                  0 as valortotal,
                  v03_tributaria as procedenciatributaria,
                  lote.*,
                  v07_numpre,
                  v07_parcel,
                  (select fc_parc_gettipoparcelamento(v07_parcel) limit 1) as tipoparc,
                  case
                    when arrematric.k00_numpre is not null then 'M - '||arrematric.k00_matric
                    when arreinscr.k00_numpre  is not null then 'I - '||arreinscr.k00_inscr
                    else 'C - '||arrenumcgm.k00_numcgm
                  end as origem ";

    $aDebitos        = array();
    $aRetornoDebitos = array();
    $aDebitosFinal   = Array();

    $oInstituicao      = new Instituicao(db_getsession('DB_instit'));
    $oParametrosDivida = db_stdClass::getParametro("pardiv", array($oInstituicao->getSequencial()));

    foreach ($aOrigens as $oOrigem) {

      $sqlProc   = $oDaoTermo->sql_query_origem_divida($oOrigem->numpre,$sCampos,true);
      $sqlProc  .= " order by procedenciatributaria,exercicio, codigoprocedencia, numpre, numpar";
      $rsDebitos = $oDaoTermo->sql_record($sqlProc);
      $aDebitos  = db_utils::getCollectionByRecord($rsDebitos);

      $aRetornoDebitos = array();
      for ( $i = 0; $i < count($aDebitos); $i++) {

		    if ($aDebitos[$i]->origem{0} == 'M') {

		      if (isset($aDebitos[$i]->j34_setor) && $aDebitos[$i]->j34_setor != "" && isset($aDebitos[$i]->j34_quadra)
		        && $aDebitos[$i]->j34_quadra != "" && isset($aDebitos[$i]->j34_lote) && $aDebitos[$i]->j34_lote != "") {
		        $aDebitos[$i]->origemdebito = $aDebitos[$i]->j34_setor."/".$aDebitos[$i]->j34_quadra."/".$aDebitos[$i]->j34_lote;
		      } else {
		        $aDebitos[$i]->origemdebito = $aDebitos[$i]->j34_lote;
		      }
		    } else if ($aDebitos[$i]->origem{0} == 'I') {

		      $aDebitos[$i]->origemdebito = ucfirst($aDebitos[$i]->origem);
		    } else {

		      $aDebitos[$i]->origemdebito = ucfirst($aDebitos[$i]->origem);
		    }

        if ($aDebitos[$i]->procedenciatributaria == 't' || $aDebitos[$i]->procedenciatributaria == 1) {
          $aDebitos[$i]->procedenciatributaria = true;
        } else {
          $aDebitos[$i]->procedenciatributaria = false;
        }

        $aDebitos[$i]->procedenciaagrupar = null;
        $aDebitos[$i]->codigoorigem       = "";
        $aDebitos[$i]->certidmassa        = 0;
        $aDebitos[$i]->valortotal         = 0;

        $sSqlProcedenciaagrupar  = "select v24_procedagrupa ";
        $sSqlProcedenciaagrupar .= "  from procedenciaagrupa";
        $sSqlProcedenciaagrupar .= " where v24_proced = {$aDebitos[$i]->codigoprocedencia}";
        $rsProcedenciaagrupar    = db_query($sSqlProcedenciaagrupar);

        if (pg_num_rows($rsProcedenciaagrupar) > 0) {
          $aDebitos[$i]->procedenciaagrupar = db_utils::fieldsMemory($rsProcedenciaagrupar, 0)->v24_procedagrupa;
        }

        if ( $this->isComposicao() ) {

          $sSqlVerificaArreold = "select distinct
                                         arreckey.*,
                                         arrecadcompos.k00_vlrhist  as vlrhis,
                                         (arrecadcompos.k00_vlrhist + arrecadcompos.k00_correcao) as vlrcor,
                                         arrecadcompos.k00_juros    as vlrjuros,
                                         arrecadcompos.k00_multa 		as vlrmulta
			                              from arreckey
			                                   inner join arrecadcompos on arrecadcompos.k00_arreckey = arreckey.k00_sequencial
			                                   inner join tabrec        on tabrec.k02_codigo          = arreckey.k00_receit
			                             where arreckey.k00_numpre = {$aDebitos[$i]->v07_numpre}
			                             order by arreckey.k00_numpar,
			                                      arreckey.k00_receit ";
          $rsArreold = db_query($sSqlVerificaArreold) or die($sSqlVerificaArreold);


          if ( pg_num_rows($rsArreold) == 0 ) {

            throw new Exception("1 - Certidao ({$this->getCodigo()}) com Débitos pagos ou cancelados, consulte pagamentos!");
            exit();
          }

      	} else {

	        $sSqlVerificaArreold = "select * from arreold where k00_numpre = ".$aDebitos[$i]->numpre." limit 1";
	        $rsArreold = db_query($sSqlVerificaArreold) or die($sSqlVerificaArreold);

	        if ( pg_num_rows($rsArreold) > 0) {

            $oDaoCertid      = db_utils::getDao('certid');

					  $sSqlOrigemTermo = $oDaoCertid->sql_query_origem_termo_parcelamento ($aDebitos[$i]->numpre, $aDebitos[$i]->numpar);
            $rsArreold       = $oDaoCertid->sql_record($sSqlOrigemTermo);

	        } else {

	          $sqlprocuraarreforo  = "select k00_numpre,
	                                         k00_numpar,
	                                         k00_numcgm,
	                                         k00_dtoper,
	                                         k00_receit,
	                                         k00_hist,
	                                         k00_valor,
	                                         k00_dtvenc,
	                                         k00_numtot,
	                                         k00_numdig,
	                                         k00_tipo
	                                    from arreforo
	                                   where k00_certidao = {$this->getCodigo()}";
	          $resultprocuraarreforo = db_query($sqlprocuraarreforo) or die($sqlprocuraarreforo);

	          if (pg_num_rows($resultprocuraarreforo) > 0) {

	            $sqlInsertArreold = "insert into arreold (k00_numpre,k00_numpar,
	                                                      k00_numcgm,k00_dtoper,
	                                                      k00_receit,
	                                                      k00_hist,
	                                                      k00_valor,
	                                                      k00_dtvenc,
	                                                      k00_numtot,
	                                                      k00_numdig,
	                                                      k00_tipo )
	                                                      {$sqlprocuraarreforo}
	                                                       and not exists ( select 1
	                                                                          from arreold
	                                                                         where arreold.k00_numpre = arreforo.k00_numpre
	                                                                           and arreold.k00_numpar = arreforo.k00_numpar
	                                                                           and arreold.k00_receit = arreforo.k00_receit)";
	            db_query($sqlInsertArreold) or die($sqlInsertArreold);

	            $oDaoCertid      = db_utils::getDao('certid');
	            $sSqlOrigemTermo = $oDaoCertid->sql_query_origem_termo_parcelamento ($aDebitos[$i]->numpre, $aDebitos[$i]->numpar);
	            $rsArrecad       = $oDaoCertid->sql_record($sSqlOrigemTermo);

	          } else {

	            throw new Exception("certidao ({$this->getCodigo()}) com Débitos pagos ou cancelados, consulte pagamentos!");
	            exit();
	          }
	        }
        }

	      if ($rsArreold){
	        $iNumRowsArreold = pg_num_rows($rsArreold);
	      } else {
	        $iNumRowsArreold = 0;
	      }

        $aDebitos[$i]->valorcorrecao   = 0;
        $aDebitos[$i]->valorhistorico  = 0;
        $aDebitos[$i]->valorcorrigido  = 0;
        $aDebitos[$i]->valormulta      = 0;
        $aDebitos[$i]->valorjuros      = 0;
        $aDebitos[$i]->valortotal      = 0;

        for ( $iArreold = 0; $iArreold < $iNumRowsArreold; $iArreold++) {

          $oDadosDebitoAtualizado = db_utils::fieldsMemory($rsArreold,$iArreold);

          $aDebitos[$i]->valorcorrecao   += $oDadosDebitoAtualizado->vlrcor;
          $aDebitos[$i]->valorhistorico  += $oDadosDebitoAtualizado->vlrhis;
          $aDebitos[$i]->valorcorrigido  += $oDadosDebitoAtualizado->vlrcor;
          $aDebitos[$i]->valormulta      += $oDadosDebitoAtualizado->vlrmulta;
          $aDebitos[$i]->valorjuros      += $oDadosDebitoAtualizado->vlrjuros;
          $aDebitos[$i]->valortotal      += $oDadosDebitoAtualizado->total;

        	$dDataLancamento = $this->getDataLancamentoDebito($oDadosDebitoAtualizado->k00_numpre, $oDadosDebitoAtualizado->k00_numpar);

        	if ($dDataLancamento == '') {
        		$dDataLancamento = $oDadosDebitoAtualizado->k00_dtoper;
        	}

        	$aDebitos[$i]->datalancamento   = $dDataLancamento;

        }
        $aRetornoDebitos[] = $aDebitos[$i];
      }
      $aDebitosFinal[] = $aRetornoDebitos;
    }

    $aRetorno = array();

    foreach ($aDebitosFinal as $oDebito) {
      foreach ($oDebito as $oOrigem){
	      $aRetorno[] = $oOrigem;
      }
    }

    return $aRetorno ;
  }


  public function getProcedencias() {

    if ($this->getTipo() == 1) {

      require_once(modification("classes/db_termo_classe.php"));
      $aOrigens     = $this->getOrigensDebito();
      $oDaoTermo    = new cl_termo;
      $campos       = " distinct v01_proced";
      $sProcedencia = "";
      $sVirgula     = "";
      $aProcedenciasAgrupadas = array();
      foreach ($aOrigens as $oOrigem) {

        $sqlProc        = $oDaoTermo->sql_query_origem_divida ($oOrigem->numpre,$campos,true);
        $rsProcedencias = $oDaoTermo->sql_record($sqlProc);
        $aProcedencias  = db_utils::getCollectionByRecord($rsProcedencias);
        foreach ($aProcedencias as $oProcedencia) {
          $aProcedenciasAgrupadas[$oProcedencia->v01_proced] = $oProcedencia->v01_proced;
        }
      }
    } else if ($this->getTipo() == 2) {

      $sqlDadosDivida  = "select distinct v01_proced ";
      $sqlDadosDivida .= "  from certdiv  ";
      $sqlDadosDivida .= "       inner join divida           on v14_coddiv             = v01_coddiv ";
      $sqlDadosDivida .= "                                  and v01_instit             = ".db_getsession('DB_instit');
      $sqlDadosDivida .= " where certdiv.v14_certid = {$this->getCodigo()}";
      $rsDadosDivida   = $this->oDaoCertid->sql_record($sqlDadosDivida);
      $aProced         = db_utils::getCollectionByRecord($rsDadosDivida);
      foreach ($aProced as $oProced) {
        $aProcedenciasAgrupadas[$oProced->v01_proced] = $oProced->v01_proced;
      }
    }

    if (count($aProcedenciasAgrupadas) > 1) {

      $sSqlPRocedAgrupa  = "Select * from procedenciaagrupa where v24_proced in(".implode(",", $aProcedenciasAgrupadas).")";
      $rsProcedAgrupa    = db_query($sSqlPRocedAgrupa);
      $aProcedencias     = db_utils::getCollectionByRecord($rsProcedAgrupa);
      foreach($aProcedencias as $oProcedAgrupa) {

        if (in_array($oProcedAgrupa->v24_procedagrupa, $aProcedenciasAgrupadas)) {

          $aProcedenciasAgrupadas[$oProcedAgrupa->v24_procedagrupa] = $oProcedAgrupa->v24_procedagrupa;
          unset ($aProcedenciasAgrupadas[$oProcedAgrupa->v24_proced]);
        }
      }
    }
    return $aProcedenciasAgrupadas;
  }




  public function geraLoteCertidao($iTipoDebito='',$aDebitos=array(),$iMatric=''){

    if ( !db_utils::inTransaction() ){
      throw new Exception("Nenhuma transação encontrada!");
    }

    if (empty($aDebitos)) {
      throw new Exception('Dados do débito não informado!');
    }

    if (trim($iTipoDebito) == '') {
      throw new Exception('Tipo de débito não informado!');
    }

    $oDaoCertid           = db_utils::getDao('certid');
    $oDaoPardivUltCodCert = db_utils::getDao('pardivultcodcert');
    $oDaoMassamat         = db_utils::getDao('massamat');
    $oDaoCertidMassa      = db_utils::getDao('certidmassa');

    $iCodCertidao = $this->getNovoCodCertidao();

    /**
     *  Acerta código da última certidão
     */

    $oDaoPardivUltCodCert->v05_codultcert = $iCodCertidao;
    $oDaoPardivUltCodCert->alterar(null);

    if ($oDaoPardivUltCodCert->erro_status == '0') {
      throw new Exception($oDaoPardivUltCodCert->erro_msg);
    }

    $oDaoCertid->v13_certid  = $iCodCertidao;
    $oDaoCertid->v13_dtemis  = date("Y-m-d",db_getsession("DB_datausu"));
    $oDaoCertid->v13_memo    = db_getsession("DB_id_usuario");
    $oDaoCertid->v13_login   = db_getsession("DB_id_usuario");
    $oDaoCertid->v13_instit  = db_getsession('DB_instit');
    $oDaoCertid->incluir($iCodCertidao);

    if ($oDaoCertid->erro_status == '0') {
      throw new Exception($oDaoCertid->erro_msg);
    }

    if (trim($iMatric) != "") {

      $sSqlMassaMat = $oDaoMassamat->sql_query_file(null,null,"*",null,"j59_matric = {$iMatric}");
      $rsMassaMat   = $oDaoMassamat->sql_record($sSqlMassaMat);

      if ($oDaoMassamat->numrows > 0) {

        $oDaoCertidMassa->v13_certid = $iCodCertidao;
        $oDaoCertidMassa->incluir($iCodCertidao);

        if ($oDaoCertidMassa->erro_status == '0') {
          throw new Exception($oDaoCertidMassa->erro_msg);
        }
      }
    }

    $aListaCertidao = array();

    foreach ($aDebitos as $oDebito) {
    	$iCodCertidao = $this->geraCertidao($oDebito->iNumpre,$oDebito->iNumpar,$iTipoDebito,$iCodCertidao,$iMatric);
    }

    return $iCodCertidao;

  }


  public function getNovoCodCertidao(){

  	$oDaoPardivUltCodCert = db_utils::getDao('pardivultcodcert');

    $sSqlCodCert  = $oDaoPardivUltCodCert->sql_query_file(null,"(max(v05_codultcert) + 1) as certid");
    $rsCodCert    = $oDaoPardivUltCodCert->sql_record($sSqlCodCert);
    $iCodCertidao = db_utils::fieldsMemory($rsCodCert,0)->certid;

    if ( $iCodCertidao == "") {
    	$sMsgErro = "Nenhum numeração encontrada para geração da Certidão na tabela pardivultcodcert. Contate o Suporte!";
    	throw new Exception($sMsgErro);
    }

    return $iCodCertidao;
  }


  public function geraCertidao($iNumpre='', $iNumpar='',$iTipoDebito='',$iCodCertidao='',$iMatric=''){

    if ( !db_utils::inTransaction() ){
      throw new Exception("Nenhuma transação encontrada!");
    }

  	if (trim($iNumpre) == '' || trim($iNumpar) == '') {
      throw new Exception('Dados do débito não informado!');
  	}

    if (trim($iTipoDebito) == '') {
      throw new Exception('Tipo de débito não informado!');
    }

  	$oDaoCertid           = db_utils::getDao('certid');
  	$oDaoPardivUltCodCert = db_utils::getDao('pardivultcodcert');
  	$oDaoMassamat         = db_utils::getDao('massamat');
  	$oDaoCertidMassa      = db_utils::getDao('certidmassa');
  	$oDaoPardiv           = db_utils::getDao('pardiv');
  	$oDaoDivida           = db_utils::getDao('divida');
  	$oDaoTermo            = db_utils::getDao('termo');
  	$oDaoCertdiv          = db_utils::getDao('certdiv');
  	$oDaoCertter          = db_utils::getDao('certter');
  	$oDaoArreforo         = db_utils::getDao('arreforo');
  	$oDaoArrecad          = db_utils::getDao('arrecad');

  	if ( trim($iCodCertidao) == '') {

  		$iCodCertidao = $this->getNovoCodCertidao();

  		/**
  		 *  Acerta código da última certidão
  		 */

  		$oDaoPardivUltCodCert->v05_codultcert = $iCodCertidao;
  		$oDaoPardivUltCodCert->alterar(null);
  		if ($oDaoPardivUltCodCert->erro_status == '0') {
  			throw new Exception($oDaoPardivUltCodCert->erro_msg);
  		}

      $oDaoCertid->v13_certid  = $iCodCertidao;
      $oDaoCertid->v13_dtemis  = date("Y-m-d",db_getsession("DB_datausu"));
      $oDaoCertid->v13_memo    = db_getsession("DB_id_usuario");
      $oDaoCertid->v13_login   = db_getsession("DB_id_usuario");
      $oDaoCertid->v13_instit  = db_getsession('DB_instit');
      $oDaoCertid->incluir($iCodCertidao);

      if ($oDaoCertid->erro_status == '0') {
        throw new Exception($oDaoCertid->erro_msg);
      }

	    if (trim($iMatric) != "") {

	    	$sSqlMassaMat = $oDaoMassamat->sql_query_file(null,null,"*",null,"j59_matric = {$iMatric}");
	      $rsMassaMat   = $oDaoMassamat->sql_record($sSqlMassaMat);

	      if ($oDaoMassamat->numrows > 0) {

	      	$oDaoCertidMassa->v13_certid = $iCodCertidao;
	      	$oDaoCertidMassa->incluir($iCodCertidao);

	      	if ($oDaoCertidMassa->erro_status == '0') {
	      		throw new Exception($oDaoCertidMassa->erro_msg);
	      	}
	      }
	    }
  	}


    $sSqlPardiv = $oDaoPardiv->sql_query_file(db_getsession('DB_instit'),"v04_tipocertidao as tipocertidao");
    $rsPardiv   = $oDaoPardiv->sql_record($sSqlPardiv);

    if ( pg_num_rows($rsPardiv) > 0) {
      $iTipoCertidao = db_utils::fieldsMemory($rsPardiv,0)->tipocertidao;
    } else {
      throw new Exception("Configure o parametro para o tipo de debito de certidao do foro ");
    }

    if ($iTipoDebito == 5) {

    	$sWhereDivida  = "     v01_numpre = {$iNumpre}                 ";
      $sWhereDivida .= " and v01_numpar = {$iNumpar}                 ";
      $sWhereDivida .= " and v01_instit = ".db_getsession('DB_instit');

    	$sSqlDadosDivida = $oDaoDivida->sql_query_file(null,"*",null,$sWhereDivida);
      $rsDadosDivida   = $oDaoDivida->sql_record($sSqlDadosDivida);

      if ( pg_num_rows($rsDadosDivida) == 0) {
        throw new Exception('Dados da dívida não encontrados!');
      }

      $oDadosDivida  = db_utils::fieldsMemory($rsDadosDivida,0);
      $rsDadosDebito = debitos_numpre($iNumpre,0,0,db_getsession("DB_datausu"),db_getsession("DB_anousu"),$iNumpar,"k00_numpre,k00_numpar");

      if ( pg_num_rows($rsDadosDebito) == 0) {
        throw new Exception('Débitos não encontrados!');
      }

      $oDadosDebito = db_utils::fieldsMemory($rsDadosDebito,0);

      $oDaoCertdiv->v14_certid  = $iCodCertidao;
      $oDaoCertdiv->v14_coddiv  = $oDadosDivida->v01_coddiv;
      $oDaoCertdiv->v14_vlrhis  = $oDadosDebito->vlrhis;
      $oDaoCertdiv->v14_vlrcor  = $oDadosDebito->vlrcor;
      $oDaoCertdiv->v14_vlrjur  = $oDadosDebito->vlrjuros;
      $oDaoCertdiv->v14_vlrmul  = $oDadosDebito->vlrmulta;

      $oDaoCertdiv->incluir($iCodCertidao,$oDadosDivida->v01_coddiv);

      if ($oDaoCertdiv->erro_status == '0'){
        throw new Exception($oDaoCertdiv->erro_msg);
      }



    } else if ($iTipoDebito == 6) {

      $sWhereTermo  = "     v07_numpre = {$iNumpre}                 ";
      $sWhereTermo .= " and v07_instit = ".db_getsession('DB_instit');

      $sSqlDadosTermo = $oDaoTermo->sql_query_file(null,"*",null,$sWhereTermo);
      $rsDadosTermo   = $oDaoTermo->sql_record($sSqlDadosTermo);

      if (pg_num_rows($rsDadosTermo)  == 0) {
        throw new Exception("Dados do parcelamento não encontrado!");
      }

      $oDadosTermo   = db_utils::fieldsMemory($rsDadosTermo,0);
      $rsDadosDebito = debitos_numpre($iNumpre,0,0,db_getsession("DB_datausu"),db_getsession("DB_anousu"),0,"k00_numpre,k00_numpar");
      $oDadosDebito  = db_utils::fieldsMemory($rsDadosDebito,0);

      $sWhereCertter  = "     v14_certid = {$iCodCertidao}            ";
      $sWhereCertter .= " and v14_parcel = {$oDadosTermo->v07_parcel} ";

      $sSqlVerificaCertter = $oDaoCertter->sql_query_file(null,null,"*",null,$sWhereCertter);
      $rsVerificaCertter   = $oDaoCertter->sql_record($sSqlVerificaCertter);

      if ($oDaoCertter->numrows == 0){

        $oDaoCertter->v14_certid  = $iCodCertidao;
        $oDaoCertter->v14_coddiv  = $oDadosTermo->v07_parcel;
        $oDaoCertter->v14_vlrhis  = $oDadosDebito->vlrhis;
        $oDaoCertter->v14_vlrcor  = $oDadosDebito->vlrcor;
        $oDaoCertter->v14_vlrjur  = $oDadosDebito->vlrjuros;
        $oDaoCertter->v14_vlrmul  = $oDadosDebito->vlrmulta;

        $oDaoCertter->incluir($iCodCertidao,$oDadosTermo->v07_parcel);

        if ($oDaoCertter->erro_status == '0'){
          throw new Exception($oDaoCertter->erro_msg);
        }
      }
    }

    $sCamposArrecad  = " arrecad.k00_numcgm, ";
    $sCamposArrecad .= " arrecad.k00_dtoper, ";
    $sCamposArrecad .= " arrecad.k00_receit, ";
    $sCamposArrecad .= " arrecad.k00_hist,   ";
    $sCamposArrecad .= " arrecad.k00_valor,  ";
    $sCamposArrecad .= " arrecad.k00_dtvenc, ";
    $sCamposArrecad .= " arrecad.k00_numpre, ";
    $sCamposArrecad .= " arrecad.k00_numpar, ";
    $sCamposArrecad .= " arrecad.k00_numtot, ";
    $sCamposArrecad .= " arrecad.k00_numdig, ";
    $sCamposArrecad .= " arrecad.k00_tipo,   ";
    $sCamposArrecad .= " arrecad.k00_tipojm  ";

    $sWhereArrecad   = "     arrecad.k00_numpre    = {$iNumpre} ";
    $sWhereArrecad  .= " and arrecad.k00_numpar    = {$iNumpar} ";
    $sWhereArrecad  .= " and arreinstit.k00_instit = ".db_getsession('DB_instit');

    $sSqlArrecad   = $oDaoArrecad->sql_query_file_instit(null,$sCamposArrecad,null,$sWhereArrecad);
    $rsArrecad     = $oDaoArrecad->sql_record($sSqlArrecad);
    $aDadosArrecad = db_utils::getCollectionByRecord($rsArrecad);

    foreach ($aDadosArrecad as $oDadosArrecad) {

      $oDaoArreforo->k00_certidao  = $iCodCertidao;
      $oDaoArreforo->k00_numcgm    = $oDadosArrecad->k00_numcgm;
      $oDaoArreforo->k00_dtoper    = $oDadosArrecad->k00_dtoper;
      $oDaoArreforo->k00_receit    = $oDadosArrecad->k00_receit;
      $oDaoArreforo->k00_hist      = $oDadosArrecad->k00_hist;
      $oDaoArreforo->k00_valor     = $oDadosArrecad->k00_valor;
      $oDaoArreforo->k00_dtvenc    = $oDadosArrecad->k00_dtvenc;
      $oDaoArreforo->k00_numpre    = $oDadosArrecad->k00_numpre;
      $oDaoArreforo->k00_numpar    = $oDadosArrecad->k00_numpar;
      $oDaoArreforo->k00_numtot    = $oDadosArrecad->k00_numtot;
      $oDaoArreforo->k00_numdig    = $oDadosArrecad->k00_numdig;
      $oDaoArreforo->k00_tipo      = $oDadosArrecad->k00_tipo;
      $oDaoArreforo->k00_tipojm    = $oDadosArrecad->k00_tipojm;

      $oDaoArreforo->incluir(null);

      if ($oDaoArreforo->erro_status == '0') {
        throw new Exception($oDaoArreforo->erro_msg);
      }

      $sSqlUpdateArrecad   = "     k00_numpre = {$oDadosArrecad->k00_numpre} ";
      $sSqlUpdateArrecad  .= " and k00_numpar = {$oDadosArrecad->k00_numpar} ";
      $oDaoArrecad->k00_tipo = $iTipoCertidao;
      $oDaoArrecad->alterar(null,$sSqlUpdateArrecad);

      if ($oDaoArrecad->erro_status == '0') {
        throw new Exception($oDaoArrecad->erro_msg);
      }
    }
    return $iCodCertidao;
  }


  /*
   *  Asl
   *
   */
  public function setComposicao($lComposicao=false){

    $this->lComposicao = $lComposicao;
  }


  public function isComposicao() {

  	return $this->lComposicao;
  }

  /**
   * Retorna o model inicial já passando código da inicial
   * @throws Exception Código da certidão não informado
   * @throws Exception Erro na consulta ou inicial não encontrada
   */
  public function getInicial() {

  	if(empty($this->iCodigo)) {
  		throw new Exception('Código da certidão não informado ou inválido.');
  	}

  	$oDaoInicialCert = db_utils::getDao('inicialcert');

		$rsInicialCert   = $oDaoInicialCert->sql_record($oDaoInicialCert->sql_query_file(null, $this->iCodigo));

		if (!$rsInicialCert || $oDaoInicialCert->numrows == 0) {
			throw new Exception('Nenhuma inicial encontrada para a certidão.');
		}

		db_app::import('inicial');

  	return new inicial(db_utils::fieldsMemory($rsInicialCert, 0)->v51_inicial);

  }

  public function getDataLancamentoDebito($iNumpre, $iNumpar) {

  	$oDaoInformacaoDebito = db_utils::getDao('informacaodebito');
  	$sSqlInformacaoDebito = $oDaoInformacaoDebito->sql_query_retorna_dados_origem("*", $iNumpre, $iNumpar);
  	$rsInformacaoDebito   = $oDaoInformacaoDebito->sql_record($sSqlInformacaoDebito);
  	$dDataLancamento      = null;

  	if ($oDaoInformacaoDebito->numrows > 0) {
  		$dDataLancamento = db_utils::fieldsMemory($rsInformacaoDebito, 0)->k163_data;
  	}

  	return $dDataLancamento;

  }

}
?>
