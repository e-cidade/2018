<?
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

require_once(modification('libs/db_utils.php'));
require_once(modification('std/DBDate.php'));
/**
 * Utilidades para uso do Tributário
 */
abstract class DBTributario {

  /**
   * Buscas os Tipos de Debitos pela Origem
   * @param string  $sTipoOrigem 
   *                | M - Matricula
   *                | I - Inscricao
   *                | C - CGM
   *                | N - Numpre
   * @param integer $iChavePesquisa - Numero base para Pesquisa
   * @return stdClass[] Com as Definições dos Tipos de Débito encontrados
   */
  public static function getTiposDebitoByOrigem( $sTipoOrigem, $iChavePesquisa, $iInstituicao = null ) {


    $oDaoArretipo = db_utils::getDao("arretipo");

    if ( empty($iInstituicao) ) {
      $iInstituicao = db_getsession('DB_instit');
    }

    $sCampos      = "distinct                         ";
    $sCampos     .= "arretipo.k00_tipo,               ";
    $sCampos     .= "arretipo.k03_tipo,               ";
    $sCampos     .= "arretipo.k00_descr,              ";
    $sCampos     .= "arretipo.k00_marcado,            ";
    $sCampos     .= "arretipo.k00_emrec,              ";
    $sCampos     .= "arretipo.k00_agnum,              ";
    $sCampos     .= "arretipo.k00_agpar,              ";

    switch ($sTipoOrigem) {

    case "M": //Matricula
      $sCampos     .= "iptubase.j01_numcgm as k00_numcgm";
      $sSqlArretipo = $oDaoArretipo->sql_query_tiposDebitosByMatricula( $iChavePesquisa, $iInstituicao, $sCampos );
      break;                                                                                                      
    case "I": //Inscricao                                                                                       
      $sCampos     .= "issbase.q02_numcgm  as k00_numcgm";                                                    
      $sSqlArretipo = $oDaoArretipo->sql_query_tiposDebitosByInscricao( $iChavePesquisa, $iInstituicao, $sCampos );
      break;                                                                                                      
    case "C": //CGM                                                                                             
      $sCampos     .= "arrenumcgm.k00_numcgm as k00_numcgm";                                                    
      $sSqlArretipo = $oDaoArretipo->sql_query_tiposDebitosByCgm      ( $iChavePesquisa, $iInstituicao, $sCampos );
      break;                                                                                                    
    case "N": //Numpre                                                                                          
      $sCampos     .= "arrenumcgm.k00_numcgm as k00_numcgm";                                                    
      $sSqlArretipo = $oDaoArretipo->sql_query_tiposDebitosByNumpre   ( $iChavePesquisa, $iInstituicao, $sCampos );
      break;
    }
    $rsTipos = db_query($sSqlArretipo);

    if (!$rsTipos) {
      throw new DBException("Erro ao Buscar dados dos Tipos de Débitos:".pg_last_error());
    }

    return db_utils::getCollectionByRecord($rsTipos);
  }

  /**
   * Retorna o Nome da Secretaria da Fazenda do Municipio
   */
  public static function getNomeSecretariaFazenda() {
    return db_getNomeSecretaria();
  }

  public static function getCadbanCobranca($arretipo,$ip,$datahj,$instit,$tipomod) {
    return db_getcadbancobranca($arretipo,$ip,$datahj,$instit,$tipomod);
  }

  public static function emitirBic($parametro,$pdf,$tipo,$geracalculo) {
    return db_emitebic($parametro,$pdf,$tipo,$geracalculo);
  }
  
  /**
   * Retorna dados Basicos Referentes a Parcela de Débito 
   * 
   * @param mixed $iNumpre 
   * @param mixed $iNumpar 
   * @static
   * @access public
   * @return stdClass
   */
  public static function getMensagensParcela( $iNumpre, $iNumpar, $dDataEmissao ) {
    
    $oRetorno                        = new stdClass();
    $oRetorno->sMensagemContribuinte = "";
    $oRetorno->sMensagemCaixa        = "";
    /**
     * Para Buscar valor deve-se implentar busca na função debitos_numpre
     */
    $sSql  = "select distinct                                                    ";
    if ( !empty($iNumpar) ) {
      $sSql .= "       k00_dtvenc,                                               ";
    }
    $sSql .= "       k00_msguni,                                                 ";
    $sSql .= "       k00_msguni2,                                                ";
    $sSql .= "       k00_msgparc,                                                ";
    $sSql .= "       k00_msgparc2,                                               ";
    $sSql .= "       k00_msgparcvenc,                                            ";
    $sSql .= "       k00_msgparcvenc2,                                           ";
    $sSql .= "       arrecad.k00_tipo                                            ";
    $sSql .= "  from arrecad                                                     ";
    $sSql .= "       inner join arretipo on arrecad.k00_tipo = arretipo.k00_tipo ";
    $sSql .= " where k00_numpre = $iNumpre  ";
    if ( !empty($iNumpar) ) {
      $sSql .= "   AND k00_numpar = $iNumpar  ";
    }
    $rsSql = db_query($sSql);
   
    if ( !$rsSql ) {
      throw new DBException("Erro ao Buscar os Dados da Parcela. Descrição do Erro:". pg_last_error());
    }

    $oDadosDebito = db_utils::fieldsMemory($rsSql, 0);

    if ( empty($iNumpar) ) {

      $oRetorno->sMensagemContribuinte = $oDadosDebito->k00_msguni2;
      $oRetorno->sMensagemCaixa        = $oDadosDebito->k00_msguni; 
      $oRetorno->sTipoMensagem         = "Unica"; 
      return $oRetorno;
    }

    $oDataVencimentoDebito            = new DBDate( $oDadosDebito->k00_dtvenc );
    $oDataEmissao                     = new DBDate( $dDataEmissao );

    if ( $oDataEmissao->getTimeStamp() <= $oDataVencimentoDebito->getTimeStamp() ) {
    
      $oRetorno->sMensagemContribuinte = $oDadosDebito->k00_msgparc;
      $oRetorno->sMensagemCaixa        = $oDadosDebito->k00_msgparc2;
      $oRetorno->sTipoMensagem         = "Parcela Normal"; 
      return $oRetorno;                                             
    }

    $oRetorno->sMensagemContribuinte = $oDadosDebito->k00_msgparcvenc;
    $oRetorno->sMensagemCaixa        = $oDadosDebito->k00_msgparcvenc2;
    $oRetorno->sTipoMensagem         = "Parcela Vencida"; 



    return $oRetorno;
  }

}
/**
 * @deprecated Utilizar DBTributario::getNomeSecretariaFazenda()
 * Retorna O nome da Secretaria da Fazenda
 * @return string - Nome da Secretaria da Instituicao
 */
function db_getNomeSecretaria(){
  $nomeSecretaria = "SECRETARIA DA FAZENDA";
  $sqlparag   = " select db02_texto ";
  $sqlparag  .= "   from db_documento ";
  $sqlparag  .= "        inner join db_docparag  on db03_docum   = db04_docum ";
  $sqlparag  .= "        inner join db_tipodoc   on db08_codigo  = db03_tipodoc ";
  $sqlparag  .= "        inner join db_paragrafo on db04_idparag = db02_idparag ";
  $sqlparag  .= " where db03_tipodoc = 1017 ";
  $sqlparag  .= "   and db03_instit = ".db_getsession("DB_instit")." ";
  $sqlparag  .= " order by db04_ordem ";
  $resparag  = db_query($sqlparag);
  if (pg_numrows($resparag) > 0) {
    $nomeSecretaria = pg_result($resparag,0,'db02_texto');
  }
  return $nomeSecretaria;  
}

/**
 * @deprecated Utilizar DBTributario::getCadbanCobranca();
 */
function db_getcadbancobranca($arretipo,$ip,$datahj,$instit,$tipomod){

  global $k47_tipoconvenio; // 1 se for arrecadacao ou 2 se for cobranca
  global $k22_cadban;       // codigo do banco no caso de ser cobranca

  $intnumexe   = 0;
  $intnumtipo  = 0;
  $intnumgeral = 0;
  $achou       = 0;

  $sqlexe  = " select k47_tipoconvenio,k22_cadban ";
  $sqlexe .= "   from cadmodcarne ";
  $sqlexe .= "        inner join modcarnepadrao         on cadmodcarne.k47_sequencial                = modcarnepadrao.k48_cadmodcarne ";
  $sqlexe .= "        inner join modcarnepadraocobranca on modcarnepadraocobranca.k22_modcarnepadrao = modcarnepadrao.k48_sequencial ";
  $sqlexe .= "        left  join modcarnepadraotipo     on modcarnepadrao.k48_sequencial             = modcarnepadraotipo.k49_modcarnepadrao ";
  $sqlexe .= "   where case ";
  $sqlexe .= "           when modcarnepadraotipo.k49_modcarnepadrao is not null then ";
  $sqlexe .= "             case ";
  $sqlexe .= "	              when modcarnepadraotipo.k49_tipo = $arretipo then ";
  $sqlexe .= "							    true ";
  $sqlexe .= "								else ";
  $sqlexe .= "									false ";
  $sqlexe .= "							end ";
  $sqlexe .= "           else ";
  $sqlexe .= "             true ";														
  $sqlexe .= "         end ";
  $sqlexe .= "     and k48_dataini    <='".$datahj."'";
  $sqlexe .= "     and k48_datafim    >='".$datahj."'";
  $sqlexe .= "     and k48_instit     =    $instit ";
  $sqlexe .= "     and k48_cadtipomod =    $tipomod "; 

  //*   die ($sqlexe);
  $rsModexe   = db_query($sqlexe) or die ($sqlexe);
  $intnumexe  = pg_numrows($rsModexe);
  if(isset($intnumexe) && $intnumexe > 0 ){
    db_fieldsmemory($rsModexe,0);
    return true;
  }else{
    return false; 
  }
}

/**
 * @deprecated - Utilizar DBTributario::emitirBic();
   */
function db_emitebic($parametro,$pdf,$tipo,$geracalculo){

    include(modification("classes/db_cgm_classe.php"));
    require_once(modification("libs/db_utils.php"));
    require_once(modification("classes/db_iptuconstrhabite_classe.php"));

    $post  = db_utils::postmemory($_POST);
    $clcgm = new cl_cgm;
    $cliptuconstrhabite = new cl_iptuconstrhabite();

    for ($totalRegistos=0;$totalRegistos<sizeof($parametro);$totalRegistos++){

      $lTemCalculo = true;
      if($parametro[$totalRegistos]!="") {

        $iMatriculaAtual = (gettype($parametro)=="array"?$parametro[$totalRegistos]:$parametro);
        // $parametro recebe cod_matricula
        $sql  = " select iptubaixa.*,                                                                                             ";
        $sql .= "        iptubaixaproc.*,                                                                                         ";
        $sql .= "        proprietario.* ,                                                                                         ";
        $sql .= "        c.z01_nome as promitente,                                                                                ";
        $sql .= "        c.z01_ender as ender_promitente,                                                                         ";
        $sql .= "        c.z01_numero as numero_promitente,                                                                       ";
        $sql .= "        c.z01_compl as compl_promitente,                                                                         ";
        $sql .= "        c.z01_munic as munic_promitente,                                                                         ";
        $sql .= "        c.z01_uf as uf_promitente,                                                                               ";
        $sql .= "        c.z01_telef as telef_promitente,                                                                         ";
        $sql .= "        j.z01_nome as imobiliaria,                                                                               ";
        $sql .= "        j.z01_ender as ender_imobiliaria,                                                                        ";
        $sql .= "        loteloc.*,                                                                                               ";
        $sql .= "        setorloc.*,                                                                                              ";
        $sql .= "        loteloteam.j34_loteam,                                                                                   ";
        $sql .= "        loteam.j34_descr,                                                                                        ";
        $sql .= "        bairro.j13_descr as bairro,                                                                              ";
        $sql .= "        round(((                                                                                                 ";
        $sql .= "              round(( select rnfracao                                                                            ";
        $sql .= "                       from fc_iptu_fracionalote({$iMatriculaAtual},". db_getsession("DB_datausu").",true,false) ";
        $sql .= "                   ),10)                                                                                         ";
        $sql .= "            * lote.j34_area)/100),2) as areafracionada                                                           ";
        $sql .= "   from proprietario                                                                                     ";
        $sql .= "        left join iptubaixa     on j02_matric                 = j01_matric                               ";
        $sql .= "        left join iptubaixaproc on j02_matric                 = j03_matric                               ";
        $sql .= "        left outer join cgm c   on j41_numcgm                 = c.z01_numcgm                             ";
        $sql .= "        left outer join cgm j   on j44_numcgm                 = j.z01_numcgm                             ";
        $sql .= "        left join  loteloc      on loteloc.j06_idbql          = j01_idbql                                ";
        $sql .= "        left join  setorloc     on setorloc.j05_codigo        = loteloc.j06_setorloc                     ";

        $sql .= "       inner join  lote               on lote.j34_idbql                 = j01_idbql                      ";
        $sql .= "        left join  loteloteam         on loteloteam.j34_idbql           = lote.j34_idbql                 ";
        $sql .= "        left join  loteam             on loteam.j34_loteam              = loteloteam.j34_loteam          ";
        $sql .= "        left join  bairro             on bairro.j13_codi                = lote.j34_bairro                ";  

        $sql .= "   where j01_matric = {$iMatriculaAtual} limit 1   ";

        $sqlVerificaCalculoAtual  = " select * ";
        $sqlVerificaCalculoAtual .= "   from iptucalc ";
        $sqlVerificaCalculoAtual .= "  where j23_matric = ".(gettype($parametro)=="array"?$parametro[$totalRegistos]:$parametro)."";
        $sqlVerificaCalculoAtual .= "    and j23_anousu = ".db_getsession('DB_anousu');
        $rsVerificaCalculoAtual   = db_query($sqlVerificaCalculoAtual);

        if (pg_num_rows($rsVerificaCalculoAtual) == 0 ) {

          $sqlUltimoExerc  = " select max(j23_anousu) as exerciciocalculo ";
          $sqlUltimoExerc .= "   from iptucalc ";
          $sqlUltimoExerc .= "  where j23_matric = ".(gettype($parametro)=="array"?$parametro[$totalRegistos]:$parametro);
          $rsUltimoExerc   = db_query($sqlUltimoExerc);
          $oUltimoExerc    = db_utils::fieldsMemory($rsUltimoExerc,0);
          $exerciciocalculo = $oUltimoExerc->exerciciocalculo;

        }else{

          $exerciciocalculo = $_SESSION['DB_anousu'];

        }

        if ( $exerciciocalculo == "" ){ 
          $lTemCalculo = false;
          $exerciciocalculo = $_SESSION['DB_anousu'];
        }

        $matriculaSelecionada = db_query($sql) or die($sql);
        $numMatriculaSelecionada = pg_numrows($matriculaSelecionada);
        if ($numMatriculaSelecionada == 0) {
          $pdf->AddPage();
          $pdf->SetFont('Arial','B',9);
          $pdf->setX(5);
          $pdf->Cell(200,4,"Matrícula ".(gettype($parametro)=="array"?$parametro[$totalRegistos]:$parametro)." Inexistente","LRBT",1,"C",0);
        } else {
          $fieldmatriculaSelecionada  = db_utils::fieldsMemory($matriculaSelecionada,0);

          $resultcgm = $clcgm->sql_record($clcgm->sql_query($fieldmatriculaSelecionada->z01_numcgm, "z01_ender as ender_propri, z01_numero as numero_propri, z01_compl as compl_propri, z01_bairro as bairro_propri, z01_munic as munic_propri, z01_uf as uf_propri, z01_telef as telef_propri, z01_cep as cep_propri")); 
          $fieldcgm  = db_utils::fieldsMemory($resultcgm,0);

          $sqlareatotal = " select sum(j34_area) as areatotal 
            from (select distinct j34_idbql, j34_area
            from lote 
            inner join iptubase on j01_idbql = j34_idbql 
            where j34_setor  = '".$fieldmatriculaSelecionada->j34_setor."'
            and j34_quadra = '".$fieldmatriculaSelecionada->j34_quadra."' 
            and j34_lote   = '".$fieldmatriculaSelecionada->j34_lote."'
            and j01_baixa is null) as x";

          $resultareatotal = db_query($sqlareatotal);
          $linhasareatotal = pg_num_rows($resultareatotal);
          if ($linhasareatotal>0){
            $fieldareatotal = db_utils::fieldsMemory($resultareatotal,0);
          }

          $sqlareaconst = "
            select sum(j39_area) as areaconst
            from iptuconstr 
            inner join iptubase on j01_matric = j39_matric 
            where j39_matric = ".$fieldmatriculaSelecionada->j01_matric."
            and j39_dtdemo is null 
            and j01_baixa is null";
          // echo "xxxxxxxxxxxxxxxxxxxxxxxxxx sql 4 areaconst = ".$sqlareaconst;
          $resultareaconst = db_query($sqlareaconst);
          $linhasareaconst = pg_num_rows($resultareaconst);
          if ($linhasareaconst>0){
            $fieldareaconst = db_utils::fieldsMemory($resultareaconst,0);
            //db_fieldsmemory($resultareaconst,0);
          }

          $sqlareaconsttotal = "
            select j34_totcon 
            from lote 
            where j34_setor = '".$fieldmatriculaSelecionada->j34_setor."' 
            and j34_quadra  = '".$fieldmatriculaSelecionada->j34_quadra."' 
            and j34_lote    = '".$fieldmatriculaSelecionada->j34_lote."'  limit 1";
          //echo "xxxxxxxxxxxxxxxxxxxxxxxxxx sql 5 areaconsttotal = ".$sqlareaconsttotal;

          $resultareaconsttotal = db_query($sqlareaconsttotal);
          $linhasareaconsttotal = pg_num_rows($resultareaconsttotal);
          if ($linhasareaconsttotal>0){
            $fieldareaconsttotal = db_utils::fieldsMemory($resultareaconsttotal,0);
            //db_fieldsmemory($resultareaconsttotal,0);
          }

          $sqledifmat = "
            select count(*) as edmat
            from iptuconstr 
            inner join iptubase on j01_matric = j39_matric 
            where j39_matric =  ".$fieldmatriculaSelecionada->j01_matric."
            and j39_dtdemo is null 
            and j01_baixa is null";
          //die ($sqledif);
          //echo "xxxxxxxxxxxxxxxxxxxxxxxxxx sql 6 sqledifmat = ".$sqledifmat;
          $resultedifmat = db_query($sqledifmat);
          $linhasedifmat = pg_num_rows($resultedifmat);
          if ($linhasedifmat>0){
            $fieldedifmat = db_utils::fieldsMemory($resultedifmat,0);
            //db_fieldsmemory($resultedifmat,0);
          }

          $sqled="
            select count(*) as ed
            from iptuconstr 
            inner join iptubase on j01_matric = j39_matric 
            inner join lote on j34_idbql = j01_idbql
            where j34_setor = '".$fieldmatriculaSelecionada->j34_setor."' 
            and j34_quadra  = '".$fieldmatriculaSelecionada->j34_quadra."'      
            and j34_lote    = '".$fieldmatriculaSelecionada->j34_lote."' ";

          $resulted = db_query($sqled);
          $linhased = pg_num_rows($resulted);
          if ($linhased>0){
            $fielded = db_utils::fieldsMemory($resulted,0);
            //db_fieldsmemory($resulted,0);
          }

          global $head3;
          global $head4;
          global $head5;

          $head3 = "Dados do Imóvel";
          $head4 = "Matrícula: ".$fieldmatriculaSelecionada->j01_matric;
          $head5 = "Setor: ".$fieldmatriculaSelecionada->j34_setor." Quadra: ".$fieldmatriculaSelecionada->j34_quadra." Lote: ".$fieldmatriculaSelecionada->j34_lote;

          $pdf->AddPage();
          $pdf->SetFillColor(220);
          $pdf->SetFont('Arial','B',9);

          $pdf->setX(5);
          $pdf->Cell(200,4,"DADOS CADASTRAIS DO IMÓVEL","LRBT",1,"C",0);
          $pdf->setX(5);

          $pdf->Cell(200,0,"","",1,"C",0);

          $pdf->setX(5);
          $pdf->SetFont('Arial','',9);
          $pdf->Cell(20,4,"Matrícula :","",0,"L",0);
          $pdf->SetFont('Arial','B',9);
          $pdf->Cell(100,4,"$fieldmatriculaSelecionada->j01_matric","",0,"L",0);
          $pdf->SetFont('Arial','',9);
          $pdf->Cell(30,4,"Referência Anterior:","",0,"L",0);
          $pdf->SetFont('Arial','B',9);
          $pdf->Cell(60,4,"$fieldmatriculaSelecionada->j40_refant","",1,"L",0);

          //    $pdf->setX(5);
          //    $pdf->Cell(200,0,"","B",1,"L",0);

          // parte 1
          $pdf->setX(5);
          $pdf->SetFont('Arial','',9);
          $pdf->Cell(20,4,"Proprietário :","",0,"L",1);
          $pdf->SetFont('Arial','B',9);
          $pdf->Cell(100,4,$fieldmatriculaSelecionada->z01_numcgm . " - " . $fieldmatriculaSelecionada->proprietario,"",0,"L",1);

          $pdf->SetFont('Arial','',9);
          $pdf->Cell(20,4,"CPF/CNPJ:","",0,"L",1);
          $pdf->SetFont('Arial','B',9);
          if ( strlen($fieldmatriculaSelecionada->z01_cgccpf) == 14 ) {
            $fieldmatriculaSelecionada->z01_cgccpfpropri = db_formatar($fieldmatriculaSelecionada->z01_cgccpfpropri,'cnpj');
          } else {
            $fieldmatriculaSelecionada->z01_cgccpfpropri = db_formatar($fieldmatriculaSelecionada->z01_cgccpfpropri,'cpf');
          }
          $pdf->Cell(60,4,$fieldmatriculaSelecionada->z01_cgccpfpropri,"",0,"L",1);

          $pdf->ln();
          $pdf->setX(5);
          $pdf->SetFont('Arial','',9);
          $pdf->Cell(20,4,"Endereço:","",0,"L",1);
          $pdf->SetFont('Arial','B',9);
          $pdf->Cell(100,4,$fieldcgm->ender_propri . ($fieldcgm->numero_propri != ""?", " . $fieldcgm->numero_propri :"") . ($fieldcgm->compl_propri != ""?" / " . $fieldcgm->compl_propri:""),"",0,"L",1);
          $pdf->SetFont('Arial','',9);
          $pdf->Cell(20,4,"Bairro:","",0,"L",1);
          $pdf->SetFont('Arial','B',9);
          $pdf->Cell(60,4,@$fieldcgm->bairro_propri,"",1,"L",1);

          // parte 2
          $pdf->setX(5);
          $pdf->SetFont('Arial','',9);
          $pdf->Cell(20,4,"Municipio:","",0,"L",1);
          $pdf->SetFont('Arial','B',9);
          $pdf->Cell(70,4,$fieldcgm->munic_propri . "/" . $fieldcgm->uf_propri,"",0,"L",1);

          $pdf->SetFont('Arial','',9);
          $pdf->Cell(10,4,"CEP:","",0,"L",1);
          $pdf->SetFont('Arial','B',9);
          $pdf->Cell(20,4,$fieldcgm->cep_propri,"",0,"L",1);

          $pdf->SetFont('Arial','',9);
          $pdf->Cell(20,4,"Telefone:","",0,"L",1);
          $pdf->SetFont('Arial','B',9);
          $pdf->Cell(60,4,$fieldcgm->telef_propri,"",1,"L",1);

          //    $pdf->setX(5);
          //    $pdf->Cell(200,0,"","B",1,"L",0);
          $sqlpromitente = "  select  j41_tipopro,
            z01_numcgm,
            z01_nome   as promitente,
            z01_munic  as munic_promitente,
            z01_ender  as ender_promitente,
            z01_numero as numero_promitente,
            z01_compl  as compl_promitente,
            z01_uf     as uf_promitente,
            z01_cgccpf,
            z01_telef  as telef_promitente 
            from promitente 
            inner join cgm on j41_numcgm = z01_numcgm 
            where j41_matric = $fieldmatriculaSelecionada->j01_matric 
            order by  j41_tipopro desc";

          $resultpromitente = db_query($sqlpromitente);
          $linhaspromitente = pg_num_rows($resultpromitente);

          if ($linhaspromitente>0) {
            for($pro=0;$pro< $linhaspromitente; $pro++){
              $fieldpromitente = db_utils::fieldsMemory($resultpromitente,$pro);
              $pdf->setX(5);
              $pdf->SetFont('Arial','',9);
              $pdf->Cell(20,4,"Promitente :","",0,"L",0);
              $pdf->SetFont('Arial','B',9);
              $pdf->Cell(100,4,$fieldpromitente->z01_numcgm . "-" . $fieldpromitente->promitente,"",0,"L",0);
              $pdf->SetFont('Arial','',9);
              $pdf->Cell(20,4,"Endereço :","",0,"L",0);
              $pdf->SetFont('Arial','B',9);
              $num = "";
              $comple ="";

              if(trim($fieldpromitente->numero_promitente) != ""){
                $num= ",".trim($fieldpromitente->numero_promitente);
              }
              if(trim($fieldpromitente->compl_promitente) != ""){
                $comple = " / ".trim($fieldpromitente->compl_promitente);
              }
              $ender1 = trim($fieldpromitente->ender_promitente)." $num $comple "; 
              $ender  = substr($ender1,0,30);
              //$ender = substr($fieldmatriculaSelecionada->ender_promitente . ($fieldmatriculaSelecionada->numero_promitente != ""?", ".$fieldmatriculaSelecionada->numero_promitente:"") . ($fieldmatriculaSelecionada->compl_promitente != ""?" / $fieldmatriculaSelecionada->compl_promitente":""),0,30);
              $pdf->Cell(60,4,"$ender ","",1,"L",0);

              $pdf->setX(5);
              $pdf->SetFont('Arial','',9);
              $pdf->Cell(20,4,"Municipio:","",0,"L",0);
              $pdf->SetFont('Arial','B',9);
              $pdf->Cell(100,4,$fieldpromitente->munic_promitente . "/" . $fieldpromitente->uf_promitente,"",0,"L",0);
              $pdf->SetFont('Arial','',9);
              $pdf->Cell(20,4,"Telefone:","",0,"L",0);
              $pdf->SetFont('Arial','B',9);
              $pdf->Cell(60,4,$fieldpromitente->telef_promitente,"",1,"L",0);
            }
          }

          if ( isset($fieldmatriculaSelecionada->imobiliaria) and trim($fieldmatriculaSelecionada->imobiliaria) != "" ) {
            $pdf->setX(5);
            $pdf->SetFont('Arial','',9);
            $pdf->Cell(20,4,"Imobiliária :","",0,"L",0);
            $pdf->SetFont('Arial','B',9);
            $pdf->Cell(100,4,"$fieldmatriculaSelecionada->imobiliaria","",0,"L",0);
            $pdf->SetFont('Arial','',9);
            $pdf->Cell(20,4,"Endereço :","",0,"L",0);
            $pdf->SetFont('Arial','B',9);
            $ender =substr($fieldmatriculaSelecionada->ender_imobiliaria,0,30);
            $pdf->Cell(60,4,"$ender","",1,"L",0);
          }

          $pdf->setX(5);
          $pdf->Cell(200,4,"","",1,"C",0);

          $pdf->setX(5);
          $pdf->SetFont('Arial','',9);
          $pdf->Cell(10,4,"Setor :","",0,"L",0);
          $pdf->SetFont('Arial','B',9);
          $pdf->Cell(10,4,"$fieldmatriculaSelecionada->j34_setor","",0,"L",0);
          $pdf->SetFont('Arial','',9);
          $pdf->Cell(15,4,"Quadra :","",0,"L",0);
          $pdf->SetFont('Arial','B',9);
          $pdf->Cell(10,4,"$fieldmatriculaSelecionada->j34_quadra","",0,"L",0);
          $pdf->SetFont('Arial','',9);
          $pdf->Cell(10,4,"Lote :","",0,"L",0);
          $pdf->SetFont('Arial','B',9);
          $pdf->Cell(10,4,"$fieldmatriculaSelecionada->j34_lote","",0,"L",0);
          $pdf->SetFont('Arial','',9);
          $pdf->Cell(10,4,"Zona :","",0,"L",0);
          $pdf->SetFont('Arial','B',9);
          $pdf->Cell(10,4,"$fieldmatriculaSelecionada->j34_zona","",0,"L",0);

          $pdf->SetFont('Arial','',9);
          $pdf->Cell(15,4,"Bairro :","",0,"L",0);
          $pdf->SetFont('Arial','B',9);
          $pdf->Cell(35,4,$fieldmatriculaSelecionada->j13_codi . " - " . $fieldmatriculaSelecionada->bairro,"",1,"L",0);

          if ( isset($fieldmatriculaSelecionada->j34_loteam) and trim($fieldmatriculaSelecionada->j34_loteam) != "" ) {
            $pdf->setX(5);
            $pdf->SetFont('Arial','',9);
            $pdf->Cell(20,4,"Loteamento :","",0,"L",0);
            $pdf->SetFont('Arial','B',9);
            $pdf->Cell(20,4,"$fieldmatriculaSelecionada->j34_loteam","",0,"L",0);
            $pdf->SetFont('Arial','',9);
            $pdf->Cell(18,4,"Descrição :","",0,"L",0);
            $pdf->SetFont('Arial','B',9);
            $pdf->Cell(50,4,"$fieldmatriculaSelecionada->j34_descr","",1,"L",0);
            $pdf->SetFont('Arial','',9);
            $pdf->setX(5);
          }

          /**
           * loteloteam.j34_loteam,                                                                           ";
          $sql .= "        loteam.j34_descr,                                                                                ";
          $sql .= "        bairro.j13_descr as bairro
   */


  // Busca a descrição do tipo da RUA
  $sSqlEndeTipo  = " select j88_sigla, j88_descricao from ruas "; 
  $sSqlEndeTipo .= " join ruastipo on ruastipo.j88_codigo = ruas.j14_tipo ";
  $sSqlEndeTipo .= " where ruas.j14_codigo = {$fieldmatriculaSelecionada->codpri} ";

  $rsEndeTipo    = db_query($sSqlEndeTipo);
  $oEndeTipo     = db_utils::fieldsMemory($rsEndeTipo,0);

  $pdf->setX(5);
  $pdf->SetFont('Arial','',9);
  $pdf->Cell(20,4,"Logradouro:","",0,"L",0);
  $pdf->SetFont('Arial','B',9);
  $pdf->Cell(180,4," $fieldmatriculaSelecionada->codpri - $oEndeTipo->j88_sigla $fieldmatriculaSelecionada->nomepri, $fieldmatriculaSelecionada->j39_numero / $fieldmatriculaSelecionada->j39_compl","",1,"L",0);

  //Localização do Loteamento
  if ( isset($fieldmatriculaSelecionada->j05_codigoproprio) and trim($fieldmatriculaSelecionada->j05_codigoproprio) != "" ) {
    $pdf->setX(5);
    $pdf->SetFont('Arial','',9);
    $pdf->Cell(20,4,"Setor Loc:","",0,"L",0);
    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(102,4,"$fieldmatriculaSelecionada->j05_codigoproprio - ".substr($fieldmatriculaSelecionada->j05_descr, 0,37)."","",0,"L",0);
    $pdf->SetFont('Arial','',9);
    $pdf->Cell(20,4,"Quadra Loc:","",0,"L",0);
    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(15,4,"$fieldmatriculaSelecionada->j06_quadraloc","",0,"L",0);
    $pdf->SetFont('Arial','',9);
    $pdf->Cell(15,4,"Lote Loc:","",0,"L",0);
    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(15,4,"$fieldmatriculaSelecionada->j06_lote","",1,"L",0);
  }

  $pdf->setX(5);
  $pdf->Cell(200,4,"","",1,"C",0);

  $pdf->setX(5);
  $pdf->SetFont('Arial','',9);
  $pdf->Cell(20,4,"Área lote :","",0,"L",0);
  $pdf->SetFont('Arial','B',9);

  $pdf->Cell(50,4,"{$fieldmatriculaSelecionada->areafracionada} m2",0,1,"L",0);

  // dados da baixa
  if (isset($fieldmatriculaSelecionada->j01_baixa) && $fieldmatriculaSelecionada->j01_baixa != ""){
    $pdf->Cell(200,4,"","",1,"C",0);
    $pdf->setX(5);
    $pdf->Cell(200,4,"DADOS DA BAIXA","B",1,"L",0);
    $pdf->SetFont('Arial','B',9);
    $pdf->setX(5);
    $pdf->Cell(20,4,"Data baixa :",0,0,"L",0);
    $pdf->SetFont('Arial','',9);
    $pdf->Cell(50,4,db_formatar($fieldmatriculaSelecionada->j01_baixa,'d'),0,0,"L",0);
    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(35,4,"Processo da baixa :",0,0,"L",0);
    $pdf->SetFont('Arial','',9);
    $pdf->Cell(50,4,($fieldmatriculaSelecionada->j03_codproc!=""?$fieldmatriculaSelecionada->j03_codproc:""),0,1,"L",0);
    $pdf->setX(5);
    $pdf->SetFont('Arial','B',9);
    $pdf->setX(5);
    $pdf->Cell(20,4,"Motivo :",0,0,"L",0);
    $pdf->SetFont('Arial','',9);
    $pdf->MultiCell(($pdf->w-20),4,($fieldmatriculaSelecionada->j02_motivo!=""?$fieldmatriculaSelecionada->j02_motivo:"") ,0,1,"L",0);
  }

  $pdf->Cell(40,4,@$data,"",0,"L",0);
  $pdf->Cell(1,4,"","",1,"L",0);

  $pdf->setX(5);
  $pdf->SetFont('Arial','',9);
  $pdf->Cell(30,4,"Área real do lote :","",0,"L",0);
  $pdf->SetFont('Arial','B',9);
  $pdf->Cell(40,4,$fieldareatotal->areatotal." m2","",0,"L",0);

  $pdf->SetFont('Arial','',9);
  $pdf->Cell(20,4,"Data baixa :","",0,"L",0);
  $pdf->SetFont('Arial','B',9);
  if ($fieldmatriculaSelecionada->j01_baixa == "") {
    $data = "";
  } else {
    $data = db_formatar($fieldmatriculaSelecionada->j01_baixa,'d');
  }
  $pdf->Cell(40,4,@$data,"",0,"L",0);
  $pdf->Cell(1,4,"","",1,"L",0);

  $pdf->setX(5);
  $pdf->SetFont('Arial','',9);
  $pdf->Cell(45,4,"Área construida da matrícula:","",0,"L",0);
  $pdf->SetFont('Arial','B',9);
  $pdf->Cell(25,4,$fieldareaconst->areaconst." m2","",0,"L",0);
  $pdf->SetFont('Arial','',9);
  $pdf->Cell(45,4,"Área real construida no lote:","",0,"L",0);
  $pdf->SetFont('Arial','B',9);
  $pdf->Cell(20,4,$fieldareaconsttotal->j34_totcon." m2","",1,"L",0);

  $pdf->setX(5);
  $pdf->SetFont('Arial','',9);
  $pdf->Cell(55,4,"Número de edificações na matrícula:","",0,"L",0);
  $pdf->SetFont('Arial','B',9);
  $pdf->Cell(15,4,$fieldedifmat->edmat,"",0,"L",0);
  $pdf->SetFont('Arial','',9);
  $pdf->Cell(45,4,"Número edificações no lote:","",0,"L",0);
  $pdf->SetFont('Arial','B',9);
  $pdf->Cell(20,4,@$ed,"",1,"L",0);

  // ###################### dados do registro de imóveis. v######################
  $sqlreg = " select * from iptubaseregimovel inner join setorregimovel on j69_sequencial = j04_setorregimovel where j04_matric = $fieldmatriculaSelecionada->j01_matric";
  $resultreg = db_query($sqlreg);
  $linhasreg = pg_num_rows($resultreg);
  if($linhasreg>0){
    $fieldreg = db_utils::fieldsMemory($resultreg,0);
    // db_fieldsmemory($resultreg,0);
    $pdf->setX(5);
    $pdf->Cell(200,4,"","",1,"C",0);
    $pdf->setX(5);
    $pdf->Cell(200,4,"DADOS DO REGISTRO DE IMÓVEIS","B",1,"L",0);
    $pdf->SetFont('Arial','',9);
    $pdf->setX(5);
    $pdf->Cell(92,5,"Matrícula do registro: ".$fieldreg->j04_matricregimo,0,0,"L",0);
    $pdf->Cell(98,5,"Setor do registro: ".$fieldreg->j04_setorregimovel." - ".$fieldreg->j69_descr,0,1,"L",0);

    $pdf->setX(5);
    $pdf->Cell(92,5,"Quadra do registro: ".$fieldreg->j04_quadraregimo,0,0,"L",0);
    $pdf->Cell(998,5,"Lote do registro: ".$fieldreg->j04_loteregimo,0,1,"L",0);
  }
  $pdf->setX(5);
  $pdf->Cell(200,4,"",0,1,"C",0);
  if ($tipo == 2){
    $pdf->setX(5);
    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(200,4,"CARACTERÍSTICAS DO LOTE","B",1,"L",0);
    $pdf->SetFont('Arial','',9);
    $pdf->setX(5);
    $pdf->Cell(200,4,"",0,1,"C",0);


    $result = db_query("select carlote.*,caracter.*,upper(j32_descr) as j32_descr
      from carlote, caracter 
      left outer join cargrup on j31_grupo = j32_grupo
      where j35_idbql = {$fieldmatriculaSelecionada->j01_idbql} 
      and j35_caract = j31_codigo order by j31_grupo ");

    if( pg_numrows($result) != 0 ) {
      $pdf->SetFont('Arial','',8);
      $lado= 0;
      $pdf->setX(5);
      for ($contador=0;$contador < pg_numrows($result);$contador ++ ){
        $field1 = db_utils::fieldsMemory($result,$contador);
        $pdf->Cell(15,3,"$field1->j35_caract","",0,"R",1);
        $descr = substr($field1->j31_descr,0,20).' ('.substr($field1->j32_descr,0,20).')';
        $pdf->Cell(80,3,"$descr","",$lado,"L",0);
        if($lado==0){
          $pdf->setX(100);
          $lado = 1;
        }else{
          $pdf->Ln(1);
          $pdf->setX(5);
          $lado = 0;
        }
      }
    } else {
      $pdf->setX(5);
      $pdf->SetFont('Arial','',9);
      $pdf->Cell(200,3,"Sem características cadastrada.","",1,"C",0);
    }

    $pdf->setX(5);
    $pdf->Cell(200,4,"","",1,"C",0);

    $sSqlIsencao  = " select distinct ";
    $sSqlIsencao .= "        iptuisen.*,";
    $sSqlIsencao .= "        tipoisen.* ";
    $sSqlIsencao .= "   from iptuisen ";
    $sSqlIsencao .= "        inner join isenexe on iptuisen.j46_codigo = isenexe.j47_codigo,tipoisen";
    $sSqlIsencao .= "  where j46_matric = ".(gettype($parametro)=="array"?$parametro[$totalRegistos]:$parametro);
    $sSqlIsencao .= "    and j47_anousu >= {$exerciciocalculo} ";
    $sSqlIsencao .= "    and tipoisen.j45_tipo = iptuisen.j46_tipo ";

    $result = db_query($sSqlIsencao);
    $pdf->setX(5);
    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(200,4,"ISENÇÕES","B",(pg_numrows($result) == 0?0:1),"L",0);
    $pdf->setX(5);
    //    $pdf->Cell(200,4,"","",1,"C",0);

    if( pg_numrows($result) == 0 ) {
      $pdf->setX(5);
      $pdf->SetFont('Arial','',8);
      $pdf->Cell(200,3,"Sem isenções.","",1,"C",0);
    } else {
      for ($contador=0;$contador < pg_numrows($result);$contador ++ ){
        $field2 = db_utils::fieldsMemory($result,$contador);
        //db_fieldsmemory($result,$contador);
        $result_lim = db_query("select j47_anousu from isenexe where j47_codigo = ".$field2->j46_codigo." order by j47_anousu ");
        $numrows = pg_numrows($result_lim);
        $field_lim = db_utils::fieldsMemory($result_lim,0);
        //db_fieldsmemory($result_lim,0);
        $anoini = $field_lim->j47_anousu;
        $field_lim2 = db_utils::fieldsMemory($result_lim,$numrows-1);
        //db_fieldsmemory($result_lim,$numrows-1);
        $anofim = $field_lim2->j47_anousu;
        $pdf->setX(5);
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(15,4,"Validade :","",0,"L",0);
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(20,4,"$anoini - $anofim","",0,"L",0);

        $pdf->SetFont('Arial','',9);
        $pdf->Cell(10,4,"Data :","",0,"L",0);
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(20,4,db_formatar($field2->j46_dtinc,'d'),"",0,"L",0);

        $pdf->SetFont('Arial','',9);
        $pdf->Cell(15,4,"Tipo :","",0,"L",0);
        $pdf->SetFont('Arial','B',9);
        $xtipo = substr($field2->j46_hist,0,20);
        $pdf->Cell(50,4,"$xtipo","",0,"L",0);
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(15,4,"Motivo :","",0,"L",0);
        $pdf->SetFont('Arial','B',9);
        $motivo =substr($field2->j45_descr,0,20);
        $pdf->Cell(50,4,"$motivo","",1,"L",0);
      }
    }

    $pdf->setX(5);
    $pdf->Cell(200,4,"","",1,"C",0);

    $pdf->setX(5);
    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(200,4,"TESTADA","B",1,"L",0);
    $pdf->setX(5);

    $result = db_query("select * 
      from testada 
      inner join face on j37_face = j36_face,ruas,ruastipo
      where j36_idbql  = $fieldmatriculaSelecionada->j01_idbql  
      and j36_codigo = j14_codigo and j88_codigo = j14_tipo");

    if( pg_numrows($result) != 0 ) {
      for ($contador=0;$contador < pg_numrows($result);$contador ++ ){
        $field3  = db_utils::fieldsMemory($result,$contador);
        $pdf->setX(5);
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(20,4,"Logradouro: ","",0,"L",0);
        $pdf->SetFont('Arial','B',9);
        $nomerua = $field3->j14_nome;
        $pdf->Cell(70,4,"{$field3->j36_codigo} {$field3->j88_sigla} {$nomerua}","",0,"L",0);
        $testad="";
        if (@$field3->j36_testad!=""){
          $testad="$field3->j36_testad m";
        }
        $pdf->Cell(20,4,"$testad","",0,"L",0);
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(10,4,"Face: ","",0,"L",0);
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(40,4,"$field3->j36_face","",0,"L",0);
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(15,4,"Zona: ","",0,"L",0);
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(35,4,"$field3->j37_zona","",1,"L",0);

        $result3 = db_query("select carface.*, caracter.*,upper(j32_descr) as j32_descr
          from carface
          inner join caracter on j38_caract = j31_codigo
          left outer join cargrup on j31_grupo = j32_grupo
          where j38_face = {$field3->j37_face} 
          order by j31_grupo ");

        if( pg_numrows($result) != 0 ) {

          $pdf->Cell(200,4,"",0,1,"C",0);
          $pdf->setX(5);
          $pdf->Cell(200,4,"CARACTERÍSTICAS DA FACE","B",1,"L",0);
          $pdf->setX(5);
          $pdf->Cell(200,2,"",0,1,"C",0);
          $pdf->Cell(200,4,"",0,1,"C",0);

          $pdf->SetFont('Arial','',8);
          $lado= 0;
          $pdf->setX(5);
          for ($contador3=0;$contador3 < pg_numrows($result3);$contador3 ++ ) {
            $field4 = db_utils::fieldsMemory($result3,$contador3);
            $pdf->Cell(15,3,"$field4->j38_caract","",0,"R",1);
            $descr = substr($field4->j31_descr,0,20).' ('.substr($field4->j32_descr,0,20).')';
            $pdf->Cell(80,3,"$descr","",$lado,"L",0);
            if($lado==0){
              $pdf->setX(100);
              $lado = 1;
            }else{
              $pdf->Ln(1);
              $pdf->setX(5);
              $lado = 0;
            }
          }

          if ( $contador < pg_numrows($result) - 1 ) {
            $pdf->ln(5);
          }

        }

      }

    } else {
      $pdf->setX(5);
      $pdf->SetFont('Arial','',8);
      $pdf->Cell(200,3,"Sem testada","",1,"C",0);
    }
    /* Comentado ate a conclusao da rotina de cadastro das testadas internas (robson) */
    $sql  = " select tesinter.*, ";
    $sql .= "		     lote.*,orientacao.*,";
    $sql .= "				 tesinterlote.*, ";
    $sql .= "	       case ";
    $sql .= "	         when interno.j34_lote is null ";
    $sql .= "	           then tesintertipo.j92_descr ";
    $sql .= "	         else interno.j34_lote ";
    $sql .= "        end as loteinterno ";
    $sql .= "	  from tesinter ";
    $sql .= "        inner join lote            on j39_idbql         = j34_idbql ";
    $sql .= "        inner join orientacao      on j39_orientacao    = j64_sequencial ";
    $sql .= "        left  join tesinteroutros  on j84_tesinter      = j39_sequencial ";
    $sql .= "        left  join tesintertipo    on j92_sequencial    = j84_tesintertipo ";
    $sql .= "        left  join tesinterlote    on j39_sequencial    = j69_tesinter ";
    $sql .= "        left  join iptubase        on j01_idbql         = j69_idbql ";
    $sql .= "        left  join lote as interno on interno.j34_idbql = j69_idbql ";  
    $sql .= " where j39_idbql = {$fieldmatriculaSelecionada->j01_idbql} ";

    $result = db_query($sql);
    if( pg_numrows($result) != 0 ) {

      $pdf->setX(5);
      $pdf->Cell(200,4,"","",1,"C",0);
      $pdf->setX(5);
      $pdf->SetFont('Arial','B',9);
      $pdf->Cell(200,4,"TESTADAS INTERNAS","B",1,"L",0);
      $pdf->setX(5);
      $pdf->Cell(200,4,"","",1,"C",0);


      for ($contador=0;$contador < pg_numrows($result);$contador ++ ){

        $oTestadasInternas = db_utils::fieldsMemory($result,$contador);
        $pdf->setX(5);
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(20,4,"Metragem: ","",0,"L",0);
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(15,4,"{$oTestadasInternas->j39_testad}","",0,"L",0);

        $pdf->SetFont('Arial','',9);
        $pdf->Cell(20,4,"Confrontação: ",0,0,"L",0);
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(20,4,$oTestadasInternas->j64_descricao,0,0,"L",0);

        $pdf->SetFont('Arial','',9);
        if ($oTestadasInternas->j69_idbql != '') {
          $pdf->Cell(15,4," Lote : ",0,0,"L",0);
        }else{
          $pdf->Cell(15,4," Outro : ",0,0,"L",0);
        }
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(20,4,($oTestadasInternas->loteinterno!=""?$oTestadasInternas->loteinterno:"Nenhum "),0,1,"L",0);

        $pdf->SetFont('Arial','B',9);
      }
    }
    /* */
  }
  $pdf->setX(5);
  $pdf->Cell(200,4,"","",1,"C",0);

  $sql = " select * 
    from iptuender
    where j43_matric = ".(gettype($parametro)=="array"?$parametro[$totalRegistos]:$parametro)." ";

  $result = db_query($sql);
  
  $pdf->setX(5);
  $pdf->SetFont('Arial','B',9);
  $pdf->Cell(50,4,"Endereço de entrega","B",(pg_numrows($result) == 0?0:1),"L",0);
  if (pg_numrows($result) != 0) {
    $field4 = db_utils::fieldsMemory($result,0);
    //db_fieldsmemory($result,0);	  
    $pdf->setX(5);
    $pdf->setX(5);
    $pdf->SetFont('Arial','',9);
    $pdf->Cell(20,4,"Endereço:","",0,"L",0);
    $pdf->SetFont('Arial','B',9);
    
    $sEndereco = $field4->j43_ender;
    if (!empty($field4->j43_numimo)) {
      $sEndereco .= ", {$field4->j43_numimo}";
    }
    $pdf->Cell(100, 4, $sEndereco, "", 1, "L", 0);
    $pdf->setX(5);
    $pdf->SetFont('Arial','',9);
    $pdf->Cell(20,4,"Município:","",0,"L",0);
    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(100,4,"$field4->j43_munic","",1,"L",0);
    $pdf->setX(5);
    $pdf->SetFont('Arial','',9);
    $pdf->Cell(20,4,"CEP:","",0,"L",0);
    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(100,4,"$field4->j43_cep","",1,"L",0);
    $pdf->setX(5);
    $pdf->SetFont('Arial','',9);
    $pdf->Cell(20,4,"UF:","",0,"L",0);
    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(100,4,"$field4->j43_uf","",1,"L",0);
  } else {
    //      $pdf->setX(5);
    $pdf->SetFont('Arial','',8);
    $pdf->Cell(20,3,"Sem endereco de entrega cadastrado","",1,"L",0);
  }









  $sql = " select *
    from matricobs
    where j26_matric = ".(gettype($parametro)=="array"?$parametro[$totalRegistos]:$parametro)." ";
  $result = db_query($sql);

  if (pg_numrows($result) != 0) {
    $field4 = db_utils::fieldsMemory($result,0);

    $pdf->Ln(5);

    $pdf->setX(5);
    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(200,4,"Outros dados","B",(pg_numrows($result) == 0?0:1),"L",0);

    $pdf->setX(5);
    $pdf->SetFont('Arial','B',9);
    //$pdf->Cell(100,4,"$field4->j26_obs","",1,"L",0);
    $pdf->Multicell(0,4,$field4->j26_obs,0,"J",0);

  }

  $pdf->setX(5);
  $pdf->Cell(200,4,"","",1,"C",0);

  $pdf->setX(5);
  $pdf->SetFont('Arial','B',9);
  $pdf->Cell(200,4,"EDIFICAÇÕES (Construções lançadas)","B",1,"L",0);
  $pdf->setX(5);
  //  $pdf->Cell(200,4,"","",1,"C",0);

  $result = db_query("select * 
    from iptuconstr, carconstr, ruas, caracter
    left outer join cargrup on j31_grupo = j32_grupo     
    where j39_matric = ".(gettype($parametro)=="array"?$parametro[$totalRegistos]:$parametro)." 
    and j39_matric = j48_matric 
    and j39_idcons = j48_idcons 
    and j48_caract = j31_codigo 
    and j39_codigo = j14_codigo
    order by j39_idcons, j31_grupo ");


  $numero = 0;
  if( pg_numrows($result) != 0 ) {
    $ladoimp=90;
    for ($contador=0;$contador < pg_numrows($result);$contador ++ ){
      $field5 =  db_utils::fieldsMemory($result,$contador);
      //db_fieldsmemory($result,$contador);
      if( $numero != $field5->j39_idcons ){
        $confere = 0;
        $impcar = 0;
        $numero = $field5->j39_idcons;
        $pdf->setX(5);
        $pdf->SetFont('Arial','',9);
        $pdf->Ln(4); 
        $pdf->Cell(18,4,"Construção:","",0,"L",0);
        $pdf->Cell(05,4,"$field5->j39_idcons","",0,"L",0);
        $pdf->Cell(10,4,"Área:","",0,"L",0);
        $pdf->Cell(15,4,"$field5->j39_area","",0,"L",0);
        $pdf->Cell( 8,4,"Ano:","",0,"L",0);
        $pdf->Cell(10,4,$field5->j39_ano,"",0,"L",0);
        $pdf->Cell(14,4,"Inclusao:","",0,"L",0);
        $pdf->Cell(17,4,db_formatar($field5->j39_dtlan,"d"),"",0,"L",0);
        $pdf->Cell(12,4,"Frente:","",0,"L",0);
        $pdf->Cell(80,4,"$field5->j14_nome $field5->j39_numero $field5->j39_compl","",1,"L",0);

        $sCampos = "j131_sequencial, 
          login, 
          j131_data, 
          j131_hora,
          j131_obs,
          ob09_data, 
          case 
            when obrashabite.ob09_codhab is null 
            then j131_cadhab 
      else cast(ob09_habite as varchar) 
        end as j131_cadhab,
        case 
          when protprocesso.p58_codproc is null 
          then j131_codprot 
  else cast(p58_codproc as varchar)
    end as j131_codprot,
    j131_dthabite";
  $rsHabite = $cliptuconstrhabite->sql_record($cliptuconstrhabite->sql_query_dados(null, $sCampos, null, "j131_matric = {$field5->j39_matric} and j131_idcons = {$field5->j39_idcons}"));
  if ($cliptuconstrhabite->numrows > 0) {
    for ($iIndiceHabite = 0; $iIndiceHabite < pg_numrows($rsHabite); $iIndiceHabite++) {
      $field_habite =  db_utils::fieldsMemory($rsHabite, $iIndiceHabite);
      $pdf->setX(5);
      $pdf->SetFont('Arial','',9);
      $pdf->Cell(20,4,"Sequencial:","",0,"L",0);
      $pdf->Cell(20,4,"$field_habite->j131_sequencial","",0,"L",0);
      $pdf->Cell(20,4,"Protocolo:","",0,"L",0);
      $pdf->Cell(20,4,"$field_habite->j131_codprot","",0,"L",0);
      $pdf->Cell(20,4,"Habite-se:","",0,"L",0);
      $pdf->Cell(20,4,$field_habite->j131_cadhab,"",0,"L",0);
      $pdf->Cell(20,4,"Dt Habite-se:","",0,"L",0);
      $pdf->Cell(20,4,db_formatar($field_habite->j131_dthabite,"d"),"",0,"L",0);
      $pdf->Cell(20,4,"Dt Lanc:","",0,"L",0);
      $pdf->Cell(20,4,db_formatar($field_habite->j131_data,"d"),"",1,"L",0);
      $pdf->Cell(0,4,'Observação : '.$field_habite->j131_obs,"",1,"L",0);
    }
  }

  if ($field5->j39_dtdemo!=""){            
    $pdf->Cell(30,4,"Demolição Total: ","",0,"L",0);
    $pdf->Cell(25 ,4,db_formatar($field5->j39_dtdemo,'d'),"",1,"L",0);
  }else{

    $result_demoparc = db_query("select * from iptuconstrdemo where j60_matric = $field5->j39_matric and j60_idcons=$field5->j39_idcons");
    $numrows_demoparc = pg_numrows($result_demoparc);
    for($w=0;$w<$numrows_demoparc;$w++){
      $field_demoparc = db_utils::fieldsMemory($result_demoparc,$w);
      // db_fieldsmemory($result_demoparc,$w);
      $pdf->Cell(30,4,"Demolição Parcial: ","",0,"L",0);
      $pdf->Cell(25 ,4,db_formatar($field_demoparc->j60_datademo,'d'),"",0,"L",0);
      $pdf->Cell(25 ,4,"Área demolida: ","",0,"L",0);
      $pdf->Cell(15 ,4,"$field_demoparc->j60_area","",0,"L",0);
      $pdf->Cell(20,4,"Processo : ",0,0,"L",0);
      $pdf->Cell(20 ,4,"$field_demoparc->j60_codproc",0,1,"L",0);
    }
  }
  $pdf->setX(5);
  $pdf->Ln(2);
  $pdf->SetFont('Arial','B',9);
  $pdf->Cell(200,4,"Caracteristicas: ","",1,"L",0);
  $pdf->Ln(2);
      }
      ($ladoimp==5?$ladoimp=90:$ladoimp=5);
      $pdf->setX($ladoimp);
      $pdf->SetFont('Arial','B',8);
      $pdf->Cell(10 ,3,"$field5->j48_caract","",0,"R",1);
      $pdf->Cell(2,4,"","",0,"L",0);
      $pdf->Cell(100,3,substr($field5->j31_descr,0,20).' ('.strtoupper(substr($field5->j32_descr,0,20)).')',"",($ladoimp==90?1:0),"L",0);
      $pdf->Cell(8,4,"","",0,"L",0);
      $pdf->Ln(1);
    }
  } else {
    $pdf->setX(5);
    $pdf->SetFont('Arial','',8);
    $pdf->Cell(20,3,"Sem construções lançadas","",1,"L",0);
  }

  $sql = " select j52_matric as matric,  
    j52_idcons as idcons, 
    j52_codigo as cod
    , j52_area   as area, 
    j52_ano    as ano, 
    j52_numero as numero, 
    j52_compl  as compl, 
    j53_caract, 
    j31_descr
    from constrescr, constrcar, caracter, ruas
    where j52_matric = ".(gettype($parametro)=="array"?$parametro[$totalRegistos]:$parametro)." 
    and j52_matric = j53_matric 
    and j52_idcons = j53_idcons 
    and j53_caract = j31_codigo 
    and j52_codigo = j14_codigo
    order by j52_idcons ";
  $result = db_query($sql);
  $id_numero = 0;
  if( pg_numrows($result) != 0 ) {

    $pdf->setX(5);
    $pdf->Cell(200,4,"","",1,"C",0);

    $pdf->setX(5);
    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(200,4,"EDIFICAÇÕES ESCRITURADAS","B",1,"L",0);
    $pdf->setX(5);
    $pdf->Cell(200,4,"","",1,"C",0);
    $ladoimp = 90;
    for ($contador=0;$contador < pg_numrows($result);$contador ++ ){
      db_fieldsmemory($result,$contador);
      if( $id_numero != $idcons ){
        $confere = 0;
        $impcar = 0;
        $id_numero = $idcons;
        $pdf->setX(5);
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(50,4,"Construção : ","",0,"L",0);
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(50 ,4,"$idcons","",0,"L",0);
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(50,4,"Ano da Construção :","",0,"L",0);
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(50 ,4,"$ano","",1,"L",0);
        $pdf->setX(5);
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(50,4,"Área : ","",0,"L",0);
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(50 ,4,"$area","",0,"L",0);
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(50,4,"Frente :","",0,"L",0);
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(50 ,4,"$j14_nome $numero $compl","",1,"L",0);
        $pdf->setX(5);
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(200,4,"Características desta construção : ","",1,"L",0);
      }
      ($ladoimp==5?$ladoimp=90:$ladoimp=5);
      $pdf->setX($ladoimp);
      $pdf->SetFont('Arial','B',9);
      $pdf->Cell(10 ,4,"$j53_caract","",0,"L",0);
      $pdf->Cell(100,4,"$j31_descr","",($ladoimp==90?1:0),"L",0);
    }
  }

  if ($tipo == 2) {      

    $pdf->setX(5);
    $pdf->Cell(200,4,"","",1,"C",0);
    $pdf->Ln(2);
    $pdf->setX(5);
    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(200,4,"OUTROS PROPRIETÁRIOS","B",1,"L",0);
    $pdf->setX(5);
    $pdf->Cell(200,4,"","",1,"C",0);

    $result = db_query("select z01_numcgm,
      z01_nome,
      z01_ender 
      from propri,cgm
      where j42_matric = ".(gettype($parametro)=="array"?$parametro[$totalRegistos]:$parametro)." 
      and j42_numcgm = z01_numcgm ");

    if( pg_numrows($result) != 0 ) {
      for ($contador=0;$contador < pg_numrows($result);$contador ++ ){
        $field6 = db_utils::fieldsMemory($result,$contador);
        //db_fieldsmemory($result,$contador);
        $pdf->setX(5);
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(20,4,"Nome : ","",0,"L",0);
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(50 ,4,"$field6->z01_numcgm - $field6->z01_nome","",1,"L",0);
        $pdf->SetFont('Arial','',9);
        $pdf->setX(5);
        $pdf->Cell(20 ,4,"Endereço : ","",0,"L",0);
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(150 ,4,"$field6->z01_ender","",1,"L",0);
      }
      $pdf->ln();

    } else {
      $pdf->setX(5);
      $pdf->SetFont('Arial','',8);
      $pdf->Cell(200,3,"Sem registros.","",1,"C",0);
    }

    if ($lTemCalculo) { 

      $pdf->setX(5);
      $pdf->SetFont('Arial','B',9);
      $pdf->Cell(200,4,"DADOS DO CÁLCULO - " . $exerciciocalculo ,"B",1,"L",0);
      $pdf->setX(5);
      $pdf->Cell(200,4,"","",1,"C",0);

      $sqlIptucale = " select * 
        from iptucale
        inner join iptuconstr on j22_matric = j39_matric and  j22_idcons = j39_idcons
        where j22_matric = ".(gettype($parametro)=="array"?$parametro[$totalRegistos]:$parametro)." 
        and j22_anousu = ".$exerciciocalculo;

      $resultSqlIptucale = db_query($sqlIptucale);
      $vlrpred = 0;
      if( pg_numrows($resultSqlIptucale) != 0 ) {
        for ($i=0;$i<pg_numrows($resultSqlIptucale);$i++) {
          $fieldSqlIptucale = db_utils::fieldsMemory($resultSqlIptucale,$i);
          $vlrpred += $fieldSqlIptucale->j22_valor;
        }
      }
      $j23_matric = gettype($parametro)=="array"?$parametro[$totalRegistos]:$parametro;
      $sql = "select iptucalc.j23_anousu,         
        iptucalc.j23_matric ,
        iptucalc.j23_testad , 
        iptucalc.j23_arealo ,  
        iptucalc.j23_areafr ,
        iptucalc.j23_areaed ,
        iptucalc.j23_m2terr ,
        iptucalc.j23_vlrter ,
        iptucalc.j23_aliq   ,
        iptucalc.j23_vlrisen
        from iptucalc 
        where j23_matric = ".(gettype($parametro)=="array"?$parametro[$totalRegistos]:$parametro)." 
        and j23_anousu = ".$exerciciocalculo." ";  
      
      $result = db_query($sql);
      if( pg_numrows($result) > 0 ) {
        $field7 = db_utils::fieldsMemory($result,0);
        //db_fieldsmemory($result,0);
        $pdf->setX(5);
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(200,1,"","",1,"C",0);
        $pdf->setX(5);
        $pdf->Cell(20 ,4," Área Lote:","",0,"R",0);
        $pdf->Cell(20 ,4,db_formatar($field7->j23_arealo,'f'),"",0,"R",0);
        $pdf->Cell(25 ,4," Fração:","",0,"R",0);
        $pdf->Cell(20 ,4,number_format($field7->j23_areafr,6,',','.'),"",0,"R",0);
        $pdf->Cell(25 ,4," Valor m2 Ter.","",0,"R",0);
        $pdf->Cell(40 ,4,db_formatar($field7->j23_m2terr,'f'),"",0,"R",0);
        $pdf->Cell(20 ,4," Alíquota:","",0,"R",0);
        $pdf->Cell(20 ,4,db_formatar($field7->j23_aliq,'f'),"",1,"R",0);
        $pdf->setX(5);
        $pdf->Cell(20 ,4," Venal Terreno:","",0,"R",0);
        $pdf->Cell(20 ,4,db_formatar($field7->j23_vlrter,'f'),"",0,"R",0);
        $pdf->Cell(25 ,4," Venal Constr.:","",0,"R",0);
        $pdf->Cell(20 ,4,db_formatar($vlrpred,'f'),"",0,"R",0);
        $pdf->Cell(25 ,4," Valor Venal  :","",0,"R",0);
        $pdf->Cell(40 ,4,db_formatar($field7->j23_vlrter+$vlrpred,'f'),"",1,"R",0);
        $j23_anousu = $field7->j23_anousu;
      } else {
        $j23_anousu = $exerciciocalculo;
      }

      if( pg_numrows($resultSqlIptucale) != 0 ) {
        $pdf->setX(5);
        $pdf->Ln(3);
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(200,4,"Construções:","",1,"L",0);
        $pdf->Ln(2);
        $pdf->setX(5);
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(10,4,"Cod","",0,"L",0);
        $pdf->Cell(40 ,4,"Área Construída","",0,"R",0);
        $pdf->Cell(10 ,4,"Ano","",0,"R",0);
        $pdf->Cell(40 ,4,"Valor M2","",0,"R",0);
        $pdf->Cell(20 ,4,"Pontos","",0,"C",0);
        $pdf->Cell(40 ,4,"Valor Venal","",1,"R",0);
        for ($i=0;$i<pg_numrows($resultSqlIptucale);$i++) {
          $fieldSqlIptucale = db_utils::fieldsMemory($resultSqlIptucale,$i);
          //db_fieldsmemory($resultSqlIptucale,$i);
          $pdf->setX(5);
          $pdf->SetFont('Arial','',9);
          $pdf->Cell(10,4,"$fieldSqlIptucale->j22_idcons","",0,"L",0);
          $pdf->Cell(40 ,4,db_formatar($fieldSqlIptucale->j22_areaed,'f'),"",0,"R",0);
          $pdf->Cell(10 ,4,"$fieldSqlIptucale->j39_ano","",0,"R",0);
          $pdf->Cell(40 ,4,db_formatar($fieldSqlIptucale->j22_vm2,'f'),"",0,"R",0);
          $pdf->Cell(20 ,4,"$fieldSqlIptucale->j22_pontos","",0,"C",0);
          $pdf->Cell(40 ,4,db_formatar($fieldSqlIptucale->j22_valor,'f'),"",1,"R",0);
        }
      }


      $sql2  = " select k02_codigo,";
      $sql2 .= "        k02_descr,";
      $sql2 .= "        ( select j17_descr ";
      $sql2 .= "            from iptucalh ";
      $sql2 .= "                 inner join iptucalv on iptucalh.j17_codhis = iptucalv.j21_codhis ";
      $sql2 .= "                                    and iptucalv.j21_receit = total.k02_codigo ";
      $sql2 .= "        limit 1 ) as j17_descr, ";
      $sql2 .= "        ( select j17_codhis ";
      $sql2 .= "            from iptucalh ";
      $sql2 .= "                 inner join iptucalv on iptucalh.j17_codhis = iptucalv.j21_codhis ";
      $sql2 .= "                                    and iptucalv.j21_receit = total.k02_codigo ";
      $sql2 .= "        limit 1 ) as j17_codhis, ";
      $sql2 .= "        sum(j21_valor) as j21_valor,";
      $sql2 .= "        sum(j21_valorisen) as j21_valorisen ";
      $sql2 .= " from ( select k02_codigo, ";
      $sql2 .= "               k02_descr, ";
      $sql2 .= "               min(j17_codhis) as j17_codhis, ";
      $sql2 .= "               j17_descr, ";
      $sql2 .= "               sum(j21_valor) as j21_valor, ";
      $sql2 .= "               sum(j21_valorisen) as j21_valorisen ";
      $sql2 .= "           from ( select k02_codigo, ";
      $sql2 .= "                         k02_descr, ";
      $sql2 .= "                         j17_codhis, ";
      $sql2 .= "                         j17_descr, ";
      $sql2 .= "                         case  ";
      $sql2 .= "                            when iptucalh.j17_codhis = iptucadtaxaexe.j08_iptucalh or iptucalh.j17_codhis = 1 then ";
      $sql2 .= "                              j21_valor ";
      $sql2 .= "                            else 0 ";
      $sql2 .= "                          end as j21_valor, ";
      $sql2 .= "                            case  ";
      $sql2 .= "                              when iptucalh.j17_codhis = iptucadtaxaexe.j08_histisen or iptucalh.j17_codhis = (select j18_iptuhistisen from cfiptu where j18_anousu = $j23_anousu) then ";
      $sql2 .= "                                j21_valor ";
      $sql2 .= "                              else 0 "; 
      $sql2 .= "                          end as j21_valorisen ";
      $sql2 .= "                     from iptucalv  "; 
      $sql2 .= "                          inner join iptucalh       on iptucalh.j17_codhis       = j21_codhis  ";
      $sql2 .= "                          inner join tabrec         on tabrec.k02_codigo         = j21_receit  ";
      $sql2 .= "                          left  join iptucadtaxaexe on iptucadtaxaexe.j08_tabrec = j21_receit  ";
      $sql2 .= "                                                   and iptucadtaxaexe.j08_anousu = $j23_anousu ";
      $sql2 .= "                    where j21_matric = ".$field7->j23_matric;
      $sql2 .= "                      and j21_anousu = $j23_anousu ";
      $sql2 .= "                    order by iptucalh.j17_codhis ";
      $sql2 .= "               ) as x ";
      $sql2 .= "               group by k02_codigo, ";
      $sql2 .= "                        k02_descr, ";
      $sql2 .= "                        j17_descr ";  
      $sql2 .= "   having sum(j21_valor) <> 0 or sum(j21_valorisen) <> 0 ";
      $sql2 .= " ) as total ";
      $sql2 .= " group by k02_codigo, ";
      $sql2 .= "          k02_descr ";
      $sql2 .= " order by k02_codigo ";

      $result2 = db_query($sql2);
      if(pg_numrows($result2)>0){
        $pdf->setX(5);
        $pdf->Ln(3);
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(200,4,"Valores Lançados:","",1,"L",0);
        $pdf->Ln(2);
        $pdf->setX(5);
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(10, 4, "Rec",            0,0,"C",0);
        $pdf->Cell(50 ,4, "Descrição",      0,0,"C",0);
        $pdf->Cell(10 ,4, "Hist",           0,0,"C",0);
        $pdf->Cell(50 ,4, "Descrição",      0,0,"C",0);
        $pdf->Cell(25 ,4, "Vlr. Calculado ",0,0,"C",0);
        $pdf->Cell(25 ,4, "Vlr. Isen",      0,0,"C",0);
        $pdf->Cell(30 ,4, "Saldo a pagar",  0,1,"C",0);

        $soma     = 0;
        $somacalc = 0;
        $somaisen = 0;

        for ($contador=0;$contador < pg_numrows($result2);$contador ++ ){
          $field8 = db_utils::fieldsMemory($result2,$contador);
          //db_fieldsmemory($result2,$contador);
          $soma     = $soma     + ($field8->j21_valor-abs($field8->j21_valorisen));
          $somacalc = $somacalc + $field8->j21_valor;
          $somaisen = $somaisen + $field8->j21_valorisen;

          $pdf->setX(5);
          $pdf->SetFont('Arial','',9);
          $pdf->Cell(10, 4,"$field8->k02_codigo",    0,0,"L",0);
          $pdf->Cell(50 ,4,"$field8->k02_descr",     0,0,"L",0);
          $pdf->Cell(10 ,4,"$field8->j17_codhis",    0,0,"L",0);
          $pdf->Cell(50 ,4,"$field8->j17_descr",     0,0,"L",0);
          $pdf->Cell(25 ,4,"$field8->j21_valor",     0,0,"R",0);
          $pdf->Cell(25 ,4,"$field8->j21_valorisen", 0,0,"R",0);
          $pdf->Cell(30 ,4, db_formatar(($field8->j21_valor-abs($field8->j21_valorisen)),'f'),0,1,"R",0) ;
        }

        $pdf->setX(5);
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(10 ,4,"","",0,"L",0);
        $pdf->Cell(50 ,4,"","",0,"L",0);
        $pdf->Cell(10 ,4,"","",0,"L",0);
        $pdf->Cell(50 ,4,"Total : ","",0,"L",0);
        $pdf->Cell(25 ,4,db_formatar($somacalc,'f'),"",0,"R",0);
        $pdf->Cell(25 ,4,db_formatar($somaisen,'f'),"",0,"R",0);
        $pdf->Cell(30 ,4,db_formatar($soma,'f'),"",1,"R",0) ; 


      } else {
        $pdf->setX(5);
        $pdf->SetFont('Arial','',8);
        $pdf->Cell(200,3,"Sem Cálculo.","",1,"C",0);
      }

    }


    if ( $geracalculo == 'true' ){
      ////// DEMONSTRATIVO DE CÁLCULO
      $exercicio = $exerciciocalculo;
      $xmatric = gettype($parametro)=="array"?$parametro[$totalRegistos]:$parametro;
      $sql2 = "select j23_manual 
        from iptucalc 
        where j23_anousu =  $exercicio
        and j23_matric = $xmatric ";

      $result2 = db_query($sql2);
      if (pg_numrows($result2) > 0) {
        $field9 = db_utils::fieldsMemory($result2,0);
        //db_fieldsmemory($result2,0);
        $pdf->SetFont('Arial','B',10);
        $pdf->Ln(6);
        $pdf->Cell(200,4,"DEMONSTRATIVO DO CÁLCULO","B",1,"L",0);
        $pdf->Ln(3);
        $pdf->SetFont('courier','',9);
        $pdf->Multicell(0,4,$field9->j23_manual,0,"L",0);
      }
    }
    // Insere valor venal ultimo calculo
    $sqlUltimoCalculo = "select iptucalc.j23_anousu as ultimocalculo,
      (select sum(iptucale.j22_valor) 
      from iptucale 
      where j22_matric = iptucalc.j23_matric 
      and j22_anousu = iptucalc.j23_anousu) as venaledificacao,
      iptucalc.j23_vlrter as venalterreno
      from iptucalc
      where j23_matric = {$fieldmatriculaSelecionada->j01_matric}
      order by j23_anousu
      desc limit 1 ";

    $resultUltimoCalculo = db_query($sqlUltimoCalculo) or die($sqlUltimoCalculo);

    if ((!empty($resultUltimoCalculo)) and (pg_num_rows($resultUltimoCalculo) == 1)){ 
      $fieldUltimoCalculo = db_utils::fieldsMemory($resultUltimoCalculo,0);
      db_fieldsmemory($resultUltimoCalculo,0);
      $pdf->SetFont('Arial','B',10); 
      $pdf->Ln(2); 
      $pdf->Cell(200,4,"VALORES VENAIS ULTIMO CALCULO - EXERCICIO $fieldUltimoCalculo->ultimocalculo","B",1,"L",0); 
      $pdf->Ln(1); 
      $pdf->SetFont('Arial','',9);
      $pdf->setX(7);
      $pdf->Cell(30 ,4,"   Valor Venal Terreno:","",0,"L",0);
      $pdf->Cell(40 ,4,db_formatar($fieldUltimoCalculo->venalterreno,'f'),"",0,"R",0);
      $pdf->Cell(30 ,4,"","",0,"L",0);
      $pdf->Cell(30 ,4,"Valor Venal Edificacao:","",0,"L",0);
      $pdf->Cell(40 ,4,db_formatar($fieldUltimoCalculo->venaledificacao,'f'),"",1,"R",0);
    }else{
      $pdf->SetFont('Arial','B',10); 
      $pdf->Ln(3); 
      $pdf->Cell(200,4,"VALORES VENAIS ULTIMO CALCULO - EXERCICIO ".@$fieldUltimoCalculo->ultimocalculo,"B",1,"L",0); 
      $pdf->SetFont('Arial','B',9);
      $pdf->setX(7);
      $pdf->Cell(200,4,"Nao possui valores disponiveis","",1,"C",0);  
    }
  }
        }
      }
    }

  }

?>