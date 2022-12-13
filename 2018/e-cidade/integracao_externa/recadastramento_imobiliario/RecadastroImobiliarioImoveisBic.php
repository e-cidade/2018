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

class RecadastroImobiliarioImoveisBic {

  private $iMatricula;

  private $sNomeArquivo;

  public function __construct( $iMatricula, $sNomeArquivo = null ) {

    if ( empty($iMatricula) ) {
      throw new Exception('Matrícula não informada para geração da bic.');
    }

    $this->iMatricula             = $iMatricula;
    $this->sNomeArquivo           = 'tmp/' . empty($sNomeArquivo) ? $iMatricula . "_recadastramento" : $sNomeArquivo;
  }

  public function processar() {

    global $conn;
    $conn = Conexao::getInstancia()->getConexao();

    require_once("fpdf151/fpdf.php");
    require_once("fpdf151/pdf.php");
    require_once("std/DBLargeObject.php");

    require_once("classes/db_cfiptu_classe.php");
    require_once("classes/db_iptucalc_classe.php");
    require_once("classes/db_iptucale_classe.php");
    require_once("classes/db_cadimobil_classe.php");

    $clcfiptu     = new cl_cfiptu;
    $cliptucalc   = new cl_iptucalc;
    $cliptucale   = new cl_iptucale;
    $clcadimobil  = new cl_cadimobil;

    db_putsession('DB_anousu', date('Y'));
    db_putsession('DB_instit', 1);
    db_putsession('DB_id_usuario', 1);
    db_putsession('DB_datausu', time(date('Y-m-d')));
    db_putsession('DB_modulo', 578);
    db_putsession('DB_itemmenu_acessado', 1721);

    $iAnoUsu      = db_getsession('DB_anousu');

    $iFontBic     = 8;
    $iFontDados   = 6;

    $lImprimeNulo = true;

    $sSqlConsulta   = " select iptubase.j01_fracao,                                                                                                                    ";
    $sSqlConsulta  .= "        lote.j34_areapreservada,                                                                                                                ";
    $sSqlConsulta  .= "        round(((round((select rnfracao                                                                                                          ";
    $sSqlConsulta  .= "                         from fc_iptu_fracionalote(iptubase.j01_matric, {$iAnoUsu}, true, false)), 10) * lote.j34_area)/100), 10) as area_lote, ";
    $sSqlConsulta  .= "        loteloteam.j34_loteam,                                                                                                                  ";
    $sSqlConsulta  .= "        loteam.j34_descr,                                                                                                                       ";
    $sSqlConsulta  .= "        setor.j30_codi,                                                                                                                         ";
    $sSqlConsulta  .= "        setor.j30_descr,                                                                                                                        ";
    $sSqlConsulta  .= "        zonas.j50_zona,                                                                                                                         ";
    $sSqlConsulta  .= "        zonas.j50_descr,                                                                                                                        ";
    $sSqlConsulta  .= "        case                                                                                                                                    ";
    $sSqlConsulta  .= "          when ac.j107_sequencial is null                                                                                                       ";
    $sSqlConsulta  .= "            then condominio.j107_sequencial                                                                                                     ";
    $sSqlConsulta  .= "          else ac.j107_sequencial                                                                                                               ";
    $sSqlConsulta  .= "        end as j107_sequencial,                                                                                                                 ";
    $sSqlConsulta  .= "        case                                                                                                                                    ";
    $sSqlConsulta  .= "          when ac.j107_nome is null                                                                                                             ";
    $sSqlConsulta  .= "            then condominio.j107_nome                                                                                                           ";
    $sSqlConsulta  .= "          else ac.j107_nome                                                                                                                     ";
    $sSqlConsulta  .= "        end as j107_nome,                                                                                                                       ";
    $sSqlConsulta  .= "        proprietario.j01_matric,                                                                                                                ";
    $sSqlConsulta  .= "        proprietario.j01_idbql,                                                                                                                 ";
    $sSqlConsulta  .= "        proprietario.j34_setor,                                                                                                                 ";
    $sSqlConsulta  .= "        proprietario.j34_quadra,                                                                                                                ";
    $sSqlConsulta  .= "        proprietario.j34_lote,                                                                                                                  ";
    $sSqlConsulta  .= "        proprietario.j34_bairro,                                                                                                                ";
    $sSqlConsulta  .= "        proprietario.j40_refant,                                                                                                                ";
    $sSqlConsulta  .= "        proprietario.j13_descr,                                                                                                                 ";
    $sSqlConsulta  .= "        proprietario.codpri,                                                                                                                    ";
    $sSqlConsulta  .= "        proprietario.nomepri,                                                                                                                   ";
    $sSqlConsulta  .= "        proprietario.j44_numcgm,                                                                                                                ";
    $sSqlConsulta  .= "        ruastipo.j88_descricao,                                                                                                                 ";
    $sSqlConsulta  .= "        setorloc.j05_descr,                                                                                                                     ";
    $sSqlConsulta  .= "        setorloc.j05_codigoproprio,                                                                                                             ";
    $sSqlConsulta  .= "        loteloc.j06_setorloc,                                                                                                                   ";
    $sSqlConsulta  .= "        loteloc.j06_quadraloc,                                                                                                                  ";
    $sSqlConsulta  .= "        loteloc.j06_lote                                                                                                                        ";
    $sSqlConsulta  .= "   from proprietario                                                                                                                            ";
    $sSqlConsulta  .= "        inner  join  lote               on lote.j34_idbql                 = proprietario.j01_idbql                                              ";
    $sSqlConsulta  .= "        left   join  loteloteam         on loteloteam.j34_idbql           = lote.j34_idbql                                                      ";
    $sSqlConsulta  .= "        left   join  loteam             on loteam.j34_loteam              = loteloteam.j34_loteam                                               ";
    $sSqlConsulta  .= "        inner  join  setor              on setor.j30_codi                 = lote.j34_setor                                                      ";
    $sSqlConsulta  .= "        inner  join  zonas              on zonas.j50_zona                 = lote.j34_zona                                                       ";
    $sSqlConsulta  .= "        left   join  iptubaixa          on iptubaixa.j02_matric           = proprietario.j01_matric                                             ";
    $sSqlConsulta  .= "        inner  join  iptubase           on iptubase.j01_matric            = proprietario.j01_matric                                             ";
    $sSqlConsulta  .= "        left   join  iptubasepredio     on iptubasepredio.j109_matric     = iptubase.j01_matric                                                 ";
    $sSqlConsulta  .= "        left   join  predio             on predio.j111_sequencial         = iptubasepredio.j109_predio                                          ";
    $sSqlConsulta  .= "        left   join  condominio  ac     on ac.j107_sequencial             = predio.j111_condominio                                              ";
    $sSqlConsulta  .= "        left   join  iptubasecondominio on iptubasecondominio.j108_matric = iptubase.j01_matric                                                 ";
    $sSqlConsulta  .= "        left   join  condominio         on condominio.j107_sequencial     = iptubasecondominio.j108_condominio                                  ";
    $sSqlConsulta  .= "        left   join  iptubaixaproc      on iptubaixa.j02_matric           = iptubaixaproc.j03_matric                                            ";
    $sSqlConsulta  .= "        left   join  cgm          c     on c.z01_numcgm                   = proprietario.j41_numcgm                                             ";
    $sSqlConsulta  .= "        left   join  cgm          j     on j.z01_numcgm                   = proprietario.j44_numcgm                                             ";
    $sSqlConsulta  .= "        left   join  ruas               on ruas.j14_codigo                = proprietario.j14_codigo                                             ";
    $sSqlConsulta  .= "        left   join  ruastipo           on ruastipo.j88_codigo            = ruas.j14_tipo                                                       ";
    $sSqlConsulta  .= "        left   join  loteloc            on loteloc.j06_idbql              = proprietario.j01_idbql                                              ";
    $sSqlConsulta  .= "        left   join  setorloc           on setorloc.j05_codigo     = loteloc.j06_setorloc                                                       ";
    $sSqlConsulta  .= "  where proprietario.j01_matric = {$this->iMatricula} limit 1                                                                                   ";
    $rsSqlConsulta  = db_query($sSqlConsulta);
    $iNumRows       = pg_num_rows($rsSqlConsulta);
    if ($iNumRows == 0){
      db_redireciona('db_erros.php?fechar=true&db_erro=Não existem registros cadastrados.');
    }

    $head2 = "RELATÓRIO BIC - MODELO NOVO";

    $pdf = new PDF();
    $pdf->Open();
    $pdf->AliasNbPages();

    $pdf->addpage("P");
    $pdf->setfillcolor(235);

    $iAlt                      = 4;

    $aDadosImovel              = array();
    $aDadosCaractLote          = array();
    $aDadosTestadaLote         = array();
    $aDadosTestadaInterna      = array();
    $aDadosCaractFace          = array();
    $aDadosProprietario        = array();
    $aDadosOutrosProprietarios = array();
    $aDadosPromitentes         = array();
    $aDadosOutrosPromitentes   = array();
    $aDadosImobiliaria         = array();
    $aDadosEnderecoEntrega     = array();
    $aDadosEdificacoes         = array();
    $aDadosRegistroImovel      = array();
    $aDadosIsencoes            = array();
    $aDadosAverbacoes          = array();
    $aDadosCalculos            = array();
    $aDadosOutrosDados         = array();

    for ( $iInd = 0; $iInd  < $iNumRows; $iInd++ ) {

      $oDados = db_utils::fieldsMemory($rsSqlConsulta, $iInd);

      /**
       * Dados Imovel
      */
      if (true) {

        $oDadosImovel = new stdClass();
        $oDadosImovel->iMatricula        = $oDados->j01_matric;
        $oDadosImovel->iSetor            = $oDados->j34_setor;
        $oDadosImovel->iQuadra           = $oDados->j34_quadra;
        $oDadosImovel->iLote             = $oDados->j34_lote;
        $oDadosImovel->iSetorLoc         = $oDados->j05_codigoproprio;
        $oDadosImovel->iQuadraLoc        = $oDados->j06_quadraloc;
        $oDadosImovel->iLoteLoc          = $oDados->j06_lote;
        $oDadosImovel->sDescrLoc         = $oDados->j05_descr;
        $oDadosImovel->sRefAnterior      = $oDados->j40_refant;
        $oDadosImovel->iBairro           = $oDados->j34_bairro;
        $oDadosImovel->sDescricao        = $oDados->j13_descr;
        $oDadosImovel->iLoteamento       = $oDados->j34_loteam;
        $oDadosImovel->sLoteDescr        = $oDados->j34_descr;
        $oDadosImovel->iCodLogradouro    = $oDados->codpri;
        $oDadosImovel->sLogradouroDescr  = $oDados->nomepri;
        $oDadosImovel->sRuaTipoDescr     = $oDados->j88_descricao;

        $nAreaLote = '0';
        if (!empty($oDados->area_lote)) {
          $nAreaLote = $oDados->area_lote;
        }

        $oDadosImovel->nAreaLote         = $nAreaLote;

        $sSqlAreaTotal  = " select sum(j34_area) as areatotal                                                   ";
        $sSqlAreaTotal .= "   from ( select distinct j34_idbql,                                                 ";
        $sSqlAreaTotal .= "                 j34_area                                                            ";
        $sSqlAreaTotal .= "            from lote                                                                ";
        $sSqlAreaTotal .= "                 inner join iptubase on j01_idbql = j34_idbql                        ";
        $sSqlAreaTotal .= "           where j34_setor  = '{$oDados->j34_setor}'                                 ";
        $sSqlAreaTotal .= "             and j34_quadra = '{$oDados->j34_quadra}'                                ";
        $sSqlAreaTotal .= "             and j34_lote   = '{$oDados->j34_lote}'                                  ";
        $sSqlAreaTotal .= "             and j01_baixa is null ) as x                                            ";
        $rsSqlAreaTotal    = db_query($sSqlAreaTotal);
        $iNumRowsAreaTotal = pg_num_rows($rsSqlAreaTotal);

        $nAreaRealLote = '0';
        if ($iNumRowsAreaTotal > 0) {

          $oAreaRealLote = db_utils::fieldsMemory($rsSqlAreaTotal, 0);
          $nAreaRealLote = $oAreaRealLote->areatotal;
        }

        $nAreaPreservada = $oDados->j34_areapreservada;
        if (empty($nAreaPreservada)) {
          $nAreaPreservada = '0';
        }

        $nFracaoIdeal = $oDados->j01_fracao;
        if (empty($nFracaoIdeal)) {
          $nFracaoIdeal = '0';
        }

        $oDadosImovel->nAreaRealLote     = trim(db_formatar($nAreaRealLote,'p',' ',15,'e',2));
        $oDadosImovel->nAreaPreservada   = trim(db_formatar($nAreaPreservada,'p',' ',15,'e',2));
        $oDadosImovel->nFracaoIdeal      = trim(db_formatar($nFracaoIdeal,'p',' ',15,'e',2));

        $sZonaFiscal = '';
        if (!empty($oDados->j50_zona) && !empty($oDados->j50_descr)) {
          $sZonaFiscal = $oDados->j50_zona.'-'.$oDados->j50_descr;
        }

        $sSetorFiscal = '';
        if (!empty($oDados->j30_codi) && !empty($oDados->j30_descr)) {
          $sSetorFiscal = $oDados->j30_codi.'-'.$oDados->j30_descr;
        }

        $sCondominio = '';
        if (!empty($oDados->j107_sequencial) && !empty($oDados->j107_nome)) {
          $sCondominio = $oDados->j107_sequencial.'-'.$oDados->j107_nome;
        }

        $oDadosImovel->sZonaFiscal       = $sZonaFiscal;
        $oDadosImovel->sSetorFiscal      = $sSetorFiscal;
        $oDadosImovel->sCondominio       = $sCondominio;

        $aDadosImovel['oDadosImovel']    = $oDadosImovel;
      }

      /**
       * Características do Lote
       */
      if ( true ) {

        $sSqlCaractLote     = "  select caracter.*,                                                             ";
        $sSqlCaractLote    .= "         upper(j32_descr) as j32_descr                                           ";
        $sSqlCaractLote    .= "    from carlote                                                                 ";
        $sSqlCaractLote    .= "         inner join  caracter     on caracter.j31_codigo = carlote.j35_caract    ";
        $sSqlCaractLote    .= "         left  outer join cargrup on caracter.j31_grupo  = cargrup.j32_grupo     ";
        $sSqlCaractLote    .= "   where carlote.j35_idbql  = {$oDados->j01_idbql}                               ";
        $rsSqlCaractLote    = db_query($sSqlCaractLote);
        $iNumRowsCaractLote = pg_num_rows($rsSqlCaractLote);

        if ( $iNumRowsCaractLote > 0 ) {

          for ( $xIndCaractLote = 0; $xIndCaractLote  < $iNumRowsCaractLote; $xIndCaractLote++ ) {

            $oDadosCarLote = db_utils::fieldsMemory($rsSqlCaractLote, $xIndCaractLote);

            $oDadosCaractLote                       = new stdClass();
            $oDadosCaractLote->iCodigo              = $oDadosCarLote->j31_codigo;
            $oDadosCaractLote->sDescricao           = $oDadosCarLote->j31_descr;
            $oDadosCaractLote->iCodGrupo            = $oDadosCarLote->j31_grupo;
            $oDadosCaractLote->sGrupoDescr          = $oDadosCarLote->j32_descr;
            $oDadosCaractLote->iPonto               = $oDadosCarLote->j31_pontos;

            $aDadosCaractLote['oDadosCaractLote'][] = $oDadosCaractLote;
          }
        }
      }

      /**
       * Características da Face
       */
      if ( true ) {

        $sSqlCaractFace     = " select caracter.j31_codigo,                                               ";
        $sSqlCaractFace    .= "        caracter.j31_descr,                                                ";
        $sSqlCaractFace    .= "        caracter.j31_grupo,                                                ";
        $sSqlCaractFace    .= "        caracter.j31_pontos,                                               ";
        $sSqlCaractFace    .= "        cargrup.j32_descr                                                  ";
        $sSqlCaractFace    .= "   from testada                                                            ";
        $sSqlCaractFace    .= "        left outer join testpri on testpri.j49_idbql = testada.j36_idbql   ";
        $sSqlCaractFace    .= "                               and testpri.j49_face  = testada.j36_face    ";
        $sSqlCaractFace    .= "        inner join face         on face.j37_face     = testada.j36_face    ";
        $sSqlCaractFace    .= "        inner join carface  on carface.j38_face     = face.j37_face        ";
        $sSqlCaractFace    .= "        inner join caracter on caracter.j31_codigo  = carface.j38_caract   ";
        $sSqlCaractFace    .= "        inner join cargrup  on cargrup.j32_grupo    = caracter.j31_grupo   ";
        $sSqlCaractFace    .= "  where testada.j36_idbql  = {$oDados->j01_idbql}                          ";
        $rsSqlCaractFace    = db_query($sSqlCaractFace);
        $iNumRowsCaractFace = pg_num_rows($rsSqlCaractFace);

        if ($iNumRowsCaractFace > 0) {

          for ( $xIndCaractFace = 0; $xIndCaractFace  < $iNumRowsCaractFace; $xIndCaractFace++ ) {

            $oDadoCaractFace = db_utils::fieldsMemory($rsSqlCaractFace, $xIndCaractFace);

            $oDadosCaractFace = new stdClass();
            $oDadosCaractFace->iCodigo              = $oDadoCaractFace->j31_codigo;
            $oDadosCaractFace->sDescricao           = $oDadoCaractFace->j31_descr;
            $oDadosCaractFace->iCodGrupo            = $oDadoCaractFace->j31_grupo;
            $oDadosCaractFace->sGrupoDescr          = $oDadoCaractFace->j32_descr;
            $oDadosCaractFace->iPonto               = $oDadoCaractFace->j31_pontos;

            $aDadosCaractFace['oDadosCaractFace'][] = $oDadosCaractFace;
          }
        }
      }

      /**
       * Testadas do Lote
       */
      if ( true ) {

        $sSqlDadosTestadaLote     = "  select *                                                                       ";
        $sSqlDadosTestadaLote    .= "    from testada                                                                 ";
        $sSqlDadosTestadaLote    .= "         left  join testadanumero on testadanumero.j15_idbql = testada.j36_idbql ";
        $sSqlDadosTestadaLote    .= "                                 and testadanumero.j15_face  = testada.j36_face  ";
        $sSqlDadosTestadaLote    .= "         inner join face          on face.j37_face           = testada.j36_face  ";
        $sSqlDadosTestadaLote    .= "         inner join ruas          on ruas.j14_codigo         = face.j37_codigo   ";
        $sSqlDadosTestadaLote    .= "   where j36_idbql = {$oDados->j01_idbql}                                        ";
        $rsSqlDadosTestadaLote    = db_query($sSqlDadosTestadaLote);
        $iNumRowsDadosTestadaLote = pg_num_rows($rsSqlDadosTestadaLote);

        if ( $iNumRowsDadosTestadaLote > 0 ) {

          for ( $xIndTestadaLote = 0; $xIndTestadaLote  < $iNumRowsDadosTestadaLote; $xIndTestadaLote++ ) {

            $oDadoTestadaLote = db_utils::fieldsMemory($rsSqlDadosTestadaLote, $xIndTestadaLote);

            $oDadosTestadaLote               = new stdClass();
            $oDadosTestadaLote->iCodigoMI    = $oDadoTestadaLote->j36_testad;
            $oDadosTestadaLote->iMedida      = $oDadoTestadaLote->j36_testle;
            $oDadosTestadaLote->iTipo        = $oDadoTestadaLote->j14_tipo;
            $oDadosTestadaLote->iCodigoLogr  = $oDadoTestadaLote->j14_codigo;
            $oDadosTestadaLote->sDescrLogr   = substr($oDadoTestadaLote->j14_nome,0 ,20);
            $oDadosTestadaLote->iNumero      = $oDadoTestadaLote->j15_numero;
            $oDadosTestadaLote->sComplemento = $oDadoTestadaLote->j15_compl;

            $aDadosTestadaLote['oDadosTestadaLote'][$oDadoTestadaLote->j36_testad] = $oDadosTestadaLote;
          }
        }
      }

      /**
       * Testadas Internas
       */
      if ( true ) {

        $sSqlDadosTestadaInterna     = " select tesinter.j39_idbql,                                                ";
        $sSqlDadosTestadaInterna    .= "        tesinter.j39_orientacao,                                           ";
        $sSqlDadosTestadaInterna    .= "        tesinter.j39_testad,                                               ";
        $sSqlDadosTestadaInterna    .= "        tesinter.j39_testle,                                               ";
        $sSqlDadosTestadaInterna    .= "        lote.j34_lote,                                                     ";
        $sSqlDadosTestadaInterna    .= "        case                                                               ";
        $sSqlDadosTestadaInterna    .= "          when interno.j34_lote is null                                    ";
        $sSqlDadosTestadaInterna    .= "            then tesintertipo.j92_descr                                    ";
        $sSqlDadosTestadaInterna    .= "          else interno.j34_lote                                            ";
        $sSqlDadosTestadaInterna    .= "        end as loteinterno                                                 ";
        $sSqlDadosTestadaInterna    .= "   from tesinter                                                           ";
        $sSqlDadosTestadaInterna    .= "        inner join lote            on j39_idbql         = j34_idbql        ";
        $sSqlDadosTestadaInterna    .= "        inner join orientacao      on j39_orientacao    = j64_sequencial   ";
        $sSqlDadosTestadaInterna    .= "        left  join tesinteroutros  on j84_tesinter      = j39_sequencial   ";
        $sSqlDadosTestadaInterna    .= "        left  join tesintertipo    on j92_sequencial    = j84_tesintertipo ";
        $sSqlDadosTestadaInterna    .= "        left  join tesinterlote    on j39_sequencial    = j69_tesinter     ";
        $sSqlDadosTestadaInterna    .= "        left  join iptubase        on j01_idbql         = j69_idbql        ";
        $sSqlDadosTestadaInterna    .= "        left  join lote as interno on interno.j34_idbql = j69_idbql        ";
        $sSqlDadosTestadaInterna    .= "  where j39_idbql = {$oDados->j01_idbql}                                   ";
        $rsSqlDadosTestadaInterna    = db_query($sSqlDadosTestadaInterna);
        $iNumRowsDadosTestadaInterna = pg_num_rows($rsSqlDadosTestadaInterna);

        if ( $iNumRowsDadosTestadaInterna > 0 ) {

          for ( $xIndTestadaInterna = 0; $xIndTestadaInterna  < $iNumRowsDadosTestadaInterna; $xIndTestadaInterna++ ) {

            $oDadoTestadaInterna  = db_utils::fieldsMemory($rsSqlDadosTestadaInterna, $xIndTestadaInterna);

            $oDadosTestadaInterna = new stdClass();
            $oDadosTestadaInterna->iCodigoLote = $oDadoTestadaInterna->j34_lote;
            $oDadosTestadaInterna->iOutro      = $oDadoTestadaInterna->j39_idbql;
            $oDadosTestadaInterna->iOrientacao = $oDadoTestadaInterna->j39_orientacao;
            $oDadosTestadaInterna->iTestadaMI  = $oDadoTestadaInterna->j39_testad;
            $oDadosTestadaInterna->iTestadaMed = $oDadoTestadaInterna->j39_testle;

            $aDadosTestadaInterna['oDadosTestadaInterna'][] = $oDadosTestadaInterna;
          }
        }
      }

      /**
       * Proprietarios
       */
      if ( true ) {

        $sSqlDadosProprietario     = "  select cgm.z01_numcgm   as numcgm,                                      ";
        $sSqlDadosProprietario    .= "         cgm.z01_nome     as nome,                                        ";
        $sSqlDadosProprietario    .= "         cgm.z01_cgccpf   as cgccpf,                                      ";
        $sSqlDadosProprietario    .= "         cgm.z01_ender    as ender,                                       ";
        $sSqlDadosProprietario    .= "         cgm.z01_numero   as numero,                                      ";
        $sSqlDadosProprietario    .= "         cgm.z01_compl    as compl,                                       ";
        $sSqlDadosProprietario    .= "         cgm.z01_bairro   as bairro,                                      ";
        $sSqlDadosProprietario    .= "         cgm.z01_munic    as munic,                                       ";
        $sSqlDadosProprietario    .= "         cgm.z01_uf       as uf,                                          ";
        $sSqlDadosProprietario    .= "         cgm.z01_cep      as cep,                                         ";
        $sSqlDadosProprietario    .= "         cgm.z01_cxpostal as cxpostal,                                    ";
        $sSqlDadosProprietario    .= "         cgm.z01_telef    as telef,                                       ";
        $sSqlDadosProprietario    .= "         cgm.z01_telcel   as telcel,                                      ";
        $sSqlDadosProprietario    .= "         cgm.z01_fax      as fax,                                         ";
        $sSqlDadosProprietario    .= "         cgm.z01_email    as email                                        ";
        $sSqlDadosProprietario    .= "    from proprietario                                                     ";
        $sSqlDadosProprietario    .= "         inner join cgm on cgm.z01_numcgm = proprietario.z01_numcgm       ";
        $sSqlDadosProprietario    .= "   where proprietario.j01_matric = {$oDados->j01_matric}                  ";
        $rsSqlDadosProprietario    = db_query($sSqlDadosProprietario);
        $iNumRowsDadosProprietario = pg_num_rows($rsSqlDadosProprietario);

        if ( $iNumRowsDadosProprietario > 0 ) {

          for ( $xIndProprietario = 0; $xIndProprietario  < $iNumRowsDadosProprietario; $xIndProprietario++ ) {

            $oDadoProprietario = db_utils::fieldsMemory($rsSqlDadosProprietario, $xIndProprietario);

            $oDadosProprietario = new stdClass();
            $oDadosProprietario->iCgm                  = $oDadoProprietario->numcgm;
            $oDadosProprietario->sTipo                 = 'P';
            $oDadosProprietario->sNome                 = $oDadoProprietario->nome;
            $oDadosProprietario->iCgcCpf               = $oDadoProprietario->cgccpf;
            $oDadosProprietario->sLogradouro           = $oDadoProprietario->ender;
            $oDadosProprietario->iNumero               = $oDadoProprietario->numero;
            $oDadosProprietario->sComplemento          = $oDadoProprietario->compl;
            $oDadosProprietario->iCaixaPostal          = $oDadoProprietario->cxpostal;
            $oDadosProprietario->sBairro               = $oDadoProprietario->bairro;
            $oDadosProprietario->sCidade               = $oDadoProprietario->munic;
            $oDadosProprietario->sUf                   = $oDadoProprietario->uf;
            $oDadosProprietario->sCep                  = $oDadoProprietario->cep;
            $oDadosProprietario->iTelefone             = $oDadoProprietario->telef;
            $oDadosProprietario->iCelular              = $oDadoProprietario->telcel;
            $oDadosProprietario->iFax                  = $oDadoProprietario->fax;
            $oDadosProprietario->sEmail                = $oDadoProprietario->email;

            $aDadosProprietario['oDadosProprietario']  = $oDadosProprietario;
          }
        }
      }

      /**
       * Outros Proprietarios
       */
      if ( true ) {

        $sSqlOutrosProprietarios     = "   select *                                                                       ";
        $sSqlOutrosProprietarios    .= "     from propri                                                                  ";
        $sSqlOutrosProprietarios    .= "          inner join cgm          on cgm.z01_numcgm          = propri.j42_numcgm  ";
        $sSqlOutrosProprietarios    .= "    where propri.j42_matric = {$oDados->j01_matric}                               ";
        $rsSqlOutrosProprietarios    = db_query($sSqlOutrosProprietarios);
        $iNumRowsOutrosProprietarios = pg_num_rows($rsSqlOutrosProprietarios);

        if ($iNumRowsOutrosProprietarios > 0) {

          for ( $xIndOutrosProprietarios = 0; $xIndOutrosProprietarios  < $iNumRowsOutrosProprietarios; $xIndOutrosProprietarios++ ) {

            $oDadoOutrosProprietarios = db_utils::fieldsMemory($rsSqlOutrosProprietarios, $xIndOutrosProprietarios);

            $oDadosOutrosProprietarios = new stdClass();
            $oDadosOutrosProprietarios->iCgm         = $oDadoOutrosProprietarios->z01_numcgm;
            $oDadosOutrosProprietarios->sTipo        = 'S';
            $oDadosOutrosProprietarios->sNome        = $oDadoOutrosProprietarios->z01_nome;
            $oDadosOutrosProprietarios->iCgcCpf      = $oDadoOutrosProprietarios->z01_cgccpf;
            $oDadosOutrosProprietarios->sLogradouro  = $oDadoOutrosProprietarios->z01_ender;
            $oDadosOutrosProprietarios->iNumero      = $oDadoOutrosProprietarios->z01_numero;
            $oDadosOutrosProprietarios->sComplemento = $oDadoOutrosProprietarios->z01_compl;
            $oDadosOutrosProprietarios->iCaixaPostal = $oDadoOutrosProprietarios->z01_cxpostal;
            $oDadosOutrosProprietarios->sBairro      = $oDadoOutrosProprietarios->z01_bairro;
            $oDadosOutrosProprietarios->sCidade      = $oDadoOutrosProprietarios->z01_munic;
            $oDadosOutrosProprietarios->sUf          = $oDadoOutrosProprietarios->z01_uf;
            $oDadosOutrosProprietarios->sCep         = $oDadoOutrosProprietarios->z01_cep;
            $oDadosOutrosProprietarios->iTelefone    = $oDadoOutrosProprietarios->z01_telef;
            $oDadosOutrosProprietarios->iCelular     = $oDadoOutrosProprietarios->z01_telcel;
            $oDadosOutrosProprietarios->iFax         = $oDadoOutrosProprietarios->z01_fax;
            $oDadosOutrosProprietarios->sEmail       = $oDadoOutrosProprietarios->z01_email;

            $aDadosOutrosProprietarios[$oDadoOutrosProprietarios->z01_numcgm]['oDadosOutrosProprietarios'] = $oDadosOutrosProprietarios;
          }
        }
      }

      /**
       * Promitentes
       */
      if ( true ) {

        $sSqlPromitentes     = "    select *                                                                    ";
        $sSqlPromitentes    .= "      from promitente                                                           ";
        $sSqlPromitentes    .= "           inner join cgm on cgm.z01_numcgm = promitente.j41_numcgm             ";
        $sSqlPromitentes    .= "     where j41_matric = {$oDados->j01_matric}                                   ";
        $sSqlPromitentes    .= "       and promitente.j41_tipopro is true                                       ";
        $rsSqlPromitentes    = db_query($sSqlPromitentes);
        $iNumRowsPromitentes = pg_num_rows($rsSqlPromitentes);

        if ($iNumRowsPromitentes > 0) {

          for ( $xIndPromitentes = 0; $xIndPromitentes  < $iNumRowsPromitentes; $xIndPromitentes++ ) {

            $oDadoPromitentes = db_utils::fieldsMemory($rsSqlPromitentes, $xIndPromitentes);

            $oDadosPromitentes = new stdClass();
            $oDadosPromitentes->iCgm         = $oDadoPromitentes->z01_numcgm;
            $oDadosPromitentes->sTipo        = 'P';
            $oDadosPromitentes->sNome        = $oDadoPromitentes->z01_nome;
            $oDadosPromitentes->iCgcCpf      = $oDadoPromitentes->z01_cgccpf;
            $oDadosPromitentes->sContato     = $oDadoPromitentes->z01_contato;
            $oDadosPromitentes->sLogradouro  = $oDadoPromitentes->z01_ender;
            $oDadosPromitentes->iNumero      = $oDadoPromitentes->z01_numero;
            $oDadosPromitentes->sComplemento = $oDadoPromitentes->z01_compl;
            $oDadosPromitentes->iCaixaPostal = $oDadoPromitentes->z01_cxpostal;
            $oDadosPromitentes->sBairro      = $oDadoPromitentes->z01_bairro;
            $oDadosPromitentes->sCidade      = $oDadoPromitentes->z01_munic;
            $oDadosPromitentes->sUf          = $oDadoPromitentes->z01_uf;
            $oDadosPromitentes->sCep         = $oDadoPromitentes->z01_cep;
            $oDadosPromitentes->iTelefone    = $oDadoPromitentes->z01_telef;
            $oDadosPromitentes->iCelular     = $oDadoPromitentes->z01_telcel;
            $oDadosPromitentes->iFax         = $oDadoPromitentes->z01_fax;
            $oDadosPromitentes->sEmail       = $oDadoPromitentes->z01_email;

            $aDadosPromitentes[$oDadoPromitentes->z01_numcgm]['oDadosPromitentes'] = $oDadosPromitentes;
          }
        }
      }

      /**
       * Outros Promitentes
       */
      if ( true ) {

        $sSqlOutrosPromitentes     = "    select *                                                              ";
        $sSqlOutrosPromitentes    .= "      from promitente                                                     ";
        $sSqlOutrosPromitentes    .= "           inner join cgm on cgm.z01_numcgm = promitente.j41_numcgm       ";
        $sSqlOutrosPromitentes    .= "     where j41_matric = {$oDados->j01_matric}                             ";
        $sSqlOutrosPromitentes    .= "       and promitente.j41_tipopro is false                                ";
        $rsSqlOutrosPromitentes    = db_query($sSqlOutrosPromitentes);
        $iNumRowsOutrosPromitentes = pg_num_rows($rsSqlOutrosPromitentes);

        if ($iNumRowsOutrosPromitentes > 0) {

          for ( $xIndOutrosPromitentes = 0; $xIndOutrosPromitentes  < $iNumRowsOutrosPromitentes; $xIndOutrosPromitentes++ ) {

            $oDadoOutrosPromitentes = db_utils::fieldsMemory($rsSqlOutrosPromitentes, $xIndOutrosPromitentes);

            $oDadosOutrosPromitentes = new stdClass();
            $oDadosOutrosPromitentes->iCgm         = $oDadoOutrosPromitentes->z01_numcgm;
            $oDadosOutrosPromitentes->sTipo        = 'S';
            $oDadosOutrosPromitentes->sNome        = $oDadoOutrosPromitentes->z01_nome;
            $oDadosOutrosPromitentes->iCgcCpf      = $oDadoOutrosPromitentes->z01_cgccpf;
            $oDadosOutrosPromitentes->sContato     = $oDadoOutrosPromitentes->z01_contato;
            $oDadosOutrosPromitentes->sLogradouro  = $oDadoOutrosPromitentes->z01_ender;
            $oDadosOutrosPromitentes->iNumero      = $oDadoOutrosPromitentes->z01_numero;
            $oDadosOutrosPromitentes->sComplemento = $oDadoOutrosPromitentes->z01_compl;
            $oDadosOutrosPromitentes->iCaixaPostal = $oDadoOutrosPromitentes->z01_cxpostal;
            $oDadosOutrosPromitentes->sBairro      = $oDadoOutrosPromitentes->z01_bairro;
            $oDadosOutrosPromitentes->sCidade      = $oDadoOutrosPromitentes->z01_munic;
            $oDadosOutrosPromitentes->sUf          = $oDadoOutrosPromitentes->z01_uf;
            $oDadosOutrosPromitentes->sCep         = $oDadoOutrosPromitentes->z01_cep;
            $oDadosOutrosPromitentes->iTelefone    = $oDadoOutrosPromitentes->z01_telef;
            $oDadosOutrosPromitentes->iCelular     = $oDadoOutrosPromitentes->z01_telcel;
            $oDadosOutrosPromitentes->iFax         = $oDadoOutrosPromitentes->z01_fax;
            $oDadosOutrosPromitentes->sEmail       = $oDadoOutrosPromitentes->z01_email;

            $aDadosOutrosPromitentes[$oDadoOutrosPromitentes->z01_numcgm]['oDadosOutrosPromitentes'] = $oDadosOutrosPromitentes;
          }
        }
      }

      /**
       * Imobiliarias
       */
      if (!empty($oDados->j44_numcgm)) {

        $sSqlImobiliaria   = $clcadimobil->sql_query($oDados->j44_numcgm);
        $rsSqlImobiliaria  = $clcadimobil->sql_record($sSqlImobiliaria);

        if ($clcadimobil->numrows > 0) {

          $oDadoImobiliaria                  = db_utils::fieldsMemory($rsSqlImobiliaria, 0);

          $oDadosImobiliaria                 = new stdClass();
          $oDadosImobiliaria->iCgm           = $oDadoImobiliaria->z01_numcgm;
          $oDadosImobiliaria->sNome          = $oDadoImobiliaria->z01_nome;
          $oDadosImobiliaria->iCgcCpf        = $oDadoImobiliaria->z01_cgccpf;
          $oDadosImobiliaria->sLogradouro    = $oDadoImobiliaria->z01_ender;
          $oDadosImobiliaria->iNumero        = $oDadoImobiliaria->z01_numero;
          $oDadosImobiliaria->sComplemento   = $oDadoImobiliaria->z01_compl;
          $oDadosImobiliaria->iCaixaPostal   = $oDadoImobiliaria->z01_cxpostal;
          $oDadosImobiliaria->sBairro        = $oDadoImobiliaria->z01_bairro;
          $oDadosImobiliaria->sCidade        = $oDadoImobiliaria->z01_munic;
          $oDadosImobiliaria->sUf            = $oDadoImobiliaria->z01_uf;
          $oDadosImobiliaria->sCep           = $oDadoImobiliaria->z01_cep;
          $oDadosImobiliaria->iTelefone      = $oDadoImobiliaria->z01_telef;
          $oDadosImobiliaria->iCelular       = $oDadoImobiliaria->z01_telcel;
          $oDadosImobiliaria->iFax           = $oDadoImobiliaria->z01_fax;
          $oDadosImobiliaria->sEmail         = $oDadoImobiliaria->z01_email;

          $aDadosImobiliaria['oDadosImobiliaria'] = $oDadosImobiliaria;
        }
      }

      /**
       * Endereco de Entrega
       */
      if ( true ) {

        $sSqlEnderecoEntrega     = " select *                                                                   ";
        $sSqlEnderecoEntrega    .= "   from iptuender                                                           ";
        $sSqlEnderecoEntrega    .= "  where j43_matric = {$oDados->j01_matric}                                  ";

        $rsSqlEnderecoEntrega    = db_query($sSqlEnderecoEntrega);
        $iNumRowsEnderecoEntrega = pg_num_rows($rsSqlEnderecoEntrega);

        if ($iNumRowsEnderecoEntrega > 0) {

          for ( $xIndEnderecoEntrega = 0; $xIndEnderecoEntrega  < $iNumRowsEnderecoEntrega; $xIndEnderecoEntrega++ ) {

            $oDadoEnderecoEntrega = db_utils::fieldsMemory($rsSqlEnderecoEntrega, $xIndEnderecoEntrega);

            $oDadosEnderecoEntrega = new stdClass();
            $oDadosEnderecoEntrega->sLogradouro      = $oDadoEnderecoEntrega->j43_ender;
            $oDadosEnderecoEntrega->iNumero          = $oDadoEnderecoEntrega->j43_numimo;
            $oDadosEnderecoEntrega->sComplemento     = $oDadoEnderecoEntrega->j43_comple;
            $oDadosEnderecoEntrega->iCaixaPostal     = $oDadoEnderecoEntrega->j43_cxpost;
            $oDadosEnderecoEntrega->sBairro          = $oDadoEnderecoEntrega->j43_bairro;
            $oDadosEnderecoEntrega->sCidade          = $oDadoEnderecoEntrega->j43_munic;
            $oDadosEnderecoEntrega->sUf              = $oDadoEnderecoEntrega->j43_uf;
            $oDadosEnderecoEntrega->sCep             = $oDadoEnderecoEntrega->j43_cep;
            $oDadosEnderecoEntrega->sNomeDest        = $oDadoEnderecoEntrega->j43_dest;

            $aDadosEnderecoEntrega['oDadosEnderecoEntrega'] = $oDadosEnderecoEntrega;
          }
        }
      }

      /**
       * Edificações
       */
      if ( true ) {

        $sSqlEdificacoes     = "    select *                                                                          ";
        $sSqlEdificacoes    .= "      from iptuconstr                                                                 ";
        $sSqlEdificacoes    .= "           inner join carconstr     on carconstr.j48_matric  = iptuconstr.j39_matric  ";
        $sSqlEdificacoes    .= "                                   and carconstr.j48_idcons  = iptuconstr.j39_idcons  ";
        $sSqlEdificacoes    .= "           inner join ruas          on ruas.j14_codigo       = iptuconstr.j39_codigo  ";
        $sSqlEdificacoes    .= "            left join ruastipo      on ruastipo.j88_codigo   = ruas.j14_tipo          ";
        $sSqlEdificacoes    .= "           left outer join caracter on caracter.j31_codigo   = carconstr.j48_caract   ";
        $sSqlEdificacoes    .= "           left outer join cargrup  on cargrup.j32_grupo     = caracter.j31_grupo     ";
        $sSqlEdificacoes    .= "     where iptuconstr.j39_matric = {$oDados->j01_matric}                              ";
        $sSqlEdificacoes    .= "  order by iptuconstr.j39_idcons                                                      ";
        $rsSqlEdificacoes    = db_query($sSqlEdificacoes);


        $iNumRowsEdificacoes = pg_num_rows($rsSqlEdificacoes);

        if ($iNumRowsEdificacoes > 0) {

          for ( $xIndEdificacoes = 0; $xIndEdificacoes  < $iNumRowsEdificacoes; $xIndEdificacoes++ ) {

            $oDadoEdificacoes = db_utils::fieldsMemory($rsSqlEdificacoes, $xIndEdificacoes);

            $oDadosEdificacoes = new stdClass();
            $oDadosEdificacoes->iCodConstrucao = $oDadoEdificacoes->j39_idcons;
            $oDadosEdificacoes->iAno           = $oDadoEdificacoes->j39_ano;
            $oDadosEdificacoes->dtInclusao     = $oDadoEdificacoes->j39_dtlan;
            $oDadosEdificacoes->dtHabite       = $oDadoEdificacoes->j39_habite;
            $oDadosEdificacoes->nAreaEdificada = $oDadoEdificacoes->j39_area;
            $oDadosEdificacoes->iNumeroConstr  = $oDadoEdificacoes->j39_numero;
            $oDadosEdificacoes->sComplemento   = $oDadoEdificacoes->j39_compl;
            $oDadosEdificacoes->iPavimento     = $oDadoEdificacoes->j39_pavim;
            $oDadosEdificacoes->iOrigem        = $oDadoEdificacoes->j39_idaument;
            $oDadosEdificacoes->sSituacao      = (empty($oDadoEdificacoes->j39_dtdemo)?'ATIVA':'DEMOLIDA');
            $oDadosEdificacoes->dtDemolicao    = $oDadoEdificacoes->j39_dtdemo;
            $oDadosEdificacoes->iAreaPricada   = $oDadoEdificacoes->j39_areap;
            $oDadosEdificacoes->sTipo          = ($oDadoEdificacoes->j39_idprinc=='t'?'P':'S');
            $oDadosEdificacoes->sLogradouro    = $oDadoEdificacoes->j14_nome;
            $oDadosEdificacoes->iNumeroEnder   = $oDadoEdificacoes->j14_codigo;
            $oDadosEdificacoes->sComplEnder    = $oDadoEdificacoes->j39_compl;

            $oCaractEdificacao = new stdClass();
            $oCaractEdificacao->iCodigo        = $oDadoEdificacoes->j31_codigo;
            $oCaractEdificacao->sDescricao     = $oDadoEdificacoes->j31_descr;

            $aDadosEdificacoes[$oDadoEdificacoes->j39_idcons]['oDadosEdificacoes']       = $oDadosEdificacoes;

            /**
             * Características da Construção
             */
            if ( true ) {

              if ( !isset($aDadosEdificacoes[$oDadoEdificacoes->j39_idcons]) ) {
                $aDadosEdificacoes[$oDadoEdificacoes->j39_idcons]['aCaractEdificacao'][]   = $oCaractEdificacao;
              } else {
                $aDadosEdificacoes[$oDadoEdificacoes->j39_idcons]['aCaractEdificacao'][]   = $oCaractEdificacao;
              }
            }
          }
        }
      }

      /**
       * Registro de Imoveis
       */
      if ( true ) {

        $sSqlRegistroImoveis     = " select *                                                                                                  ";
        $sSqlRegistroImoveis    .= "   from iptubaseregimovel                                                                                  ";
        $sSqlRegistroImoveis    .= "        inner join setorregimovel on setorregimovel.j69_sequencial = iptubaseregimovel.j04_setorregimovel  ";
        $sSqlRegistroImoveis    .= "  where iptubaseregimovel.j04_matric = {$oDados->j01_matric}                                               ";
        $rsSqlRegistroImoveis    = db_query($sSqlRegistroImoveis);
        $iNumRowsRegistroImoveis = pg_num_rows($rsSqlRegistroImoveis);

        if ($iNumRowsRegistroImoveis > 0) {

          for ( $xIndRegistroImoveis = 0; $xIndRegistroImoveis  < $iNumRowsRegistroImoveis; $xIndRegistroImoveis++ ) {

            $oDadoRegistroImoveis = db_utils::fieldsMemory($rsSqlRegistroImoveis, $xIndRegistroImoveis);

            $oDadosRegistroImoveis = new stdClass();
            $oDadosRegistroImoveis->iCodRegistro              = $oDadoRegistroImoveis->j69_sequencial;
            $oDadosRegistroImoveis->sDescricao                = $oDadoRegistroImoveis->j69_descr;
            $oDadosRegistroImoveis->iMatricRegime             = $oDadoRegistroImoveis->j04_matricregimo;
            $oDadosRegistroImoveis->iQuadraRegime             = $oDadoRegistroImoveis->j04_quadraregimo;
            $oDadosRegistroImoveis->iLoteRegime               = $oDadoRegistroImoveis->j04_loteregimo;

            $aDadosRegistroImovel['oDadosRegistroImoveis'][]  = $oDadosRegistroImoveis;
          }
        }
      }

      /**
       * Isensões
       */
      if ( true ) {

        $sSqlIsencoes     = "   select iptuisen.*,                                                              ";
        $sSqlIsencoes    .= "          isenproc.*,                                                              ";
        $sSqlIsencoes    .= "          tipoisen.*                                                               ";
        $sSqlIsencoes    .= "     from iptuisen                                                                 ";
        $sSqlIsencoes    .= "          inner join isenproc    on isenproc.j61_codigo    = iptuisen.j46_codigo   ";
        $sSqlIsencoes    .= "          inner join isenexe     on isenexe.j47_codigo     = iptuisen.j46_codigo   ";
        $sSqlIsencoes    .= "          inner join tipoisen    on tipoisen.j45_tipo      = iptuisen.j46_tipo     ";
        $sSqlIsencoes    .= "    where j46_matric = {$oDados->j01_matric}                                       ";
        $sSqlIsencoes    .= "      and tipoisen.j45_tipo = iptuisen.j46_tipo                                    ";
        $sSqlIsencoes    .= " order by j46_dtini desc                                                           ";
        $rsSqlIsencoes    = db_query($sSqlIsencoes);
        $iNumRowsIsencoes = pg_num_rows($rsSqlIsencoes);

        if ($iNumRowsIsencoes > 0) {

          for ( $xIndIsencoes = 0; $xIndIsencoes  < $iNumRowsIsencoes; $xIndIsencoes++ ) {

            $oDadoIsencoes = db_utils::fieldsMemory($rsSqlIsencoes, $xIndIsencoes);

            $oDadosIsencoes = new stdClass();
            $oDadosIsencoes->iCodIsencao    = $oDadoIsencoes->j46_codigo;
            $oDadosIsencoes->iCodProcesso   = $oDadoIsencoes->j61_codproc;
            $oDadosIsencoes->sTipoIsencao   = $oDadoIsencoes->j45_descr;
            $oDadosIsencoes->dtInicial      = $oDadoIsencoes->j46_dtini;
            $oDadosIsencoes->dtFinal        = ( empty($oDadoIsencoes->j46_dtfim) ? "null" : $oDadoAverbacoes->j46_dtfim );
            $oDadosIsencoes->nPercentual    = $oDadoIsencoes->j46_perc;
            $oDadosIsencoes->sObservacao    = $oDadoIsencoes->j46_hist;
            $oDadosIsencoes->aTaxas         = array();

            $sSqlTaxas  = " select j56_perc,                                                                                   ";
            $sSqlTaxas .= "        k02_codigo,                                                                                 ";
            $sSqlTaxas .= "        k02_descr                                                                                   ";
            $sSqlTaxas .= "   from iptucadtaxa                                                                                 ";
            $sSqlTaxas .= "        inner join iptucadtaxaexe on iptucadtaxa.j07_iptucadtaxa = iptucadtaxaexe.j08_iptucadtaxa   ";
            $sSqlTaxas .= "                                 and iptucadtaxaexe.j08_anousu   = {$iAnoUsu}                       ";
            $sSqlTaxas .= "        inner join tabrec         on tabrec.k02_codigo           = iptucadtaxaexe.j08_tabrec        ";
            $sSqlTaxas .= "        inner join isentaxa       on isentaxa.j56_codigo         = {$oDadoIsencoes->j46_codigo}     ";
            $sSqlTaxas .= "                                 and isentaxa.j56_receit         = tabrec.k02_codigo                ";
            $sSqlTaxas .= "  order by k02_codigo                                                                               ";
            $rsSqlTaxas    = db_query($sSqlTaxas);
            $iNumRowsTaxas = pg_num_rows($rsSqlTaxas);

            if ($iNumRowsTaxas > 0) {

              for ( $yInd = 0; $yInd  < $iNumRowsTaxas; $yInd++ ) {

                $oDadoTaxas = db_utils::fieldsMemory($rsSqlTaxas, $yInd);

                $oDadosTaxa = new stdClass();
                $oDadosTaxa->nPercentual   = $oDadoIsencoes->j46_perc;
                $oDadosTaxa->iCodigo       = $oDadoTaxas->k02_codigo;
                $oDadosTaxa->sDescricao    = $oDadoTaxas->k02_descr;
                $oDadosTaxa->nDescontoTaxa = $oDadoTaxas->j56_perc;

                $oDadosIsencoes->aTaxas[] = $oDadosTaxa;
              }
            }

            $aDadosIsencoes['oDadosIsencoes'][] = $oDadosIsencoes;
          }
        }
      }

      /**
       * Averbações
       */
      if ( true ) {

        $sSqlAverbacoes     = "  select *                                                                       ";
        $sSqlAverbacoes    .= "    from averbacao                                                               ";
        $sSqlAverbacoes    .= "         inner join averbatipo on j93_codigo = j75_tipo                          ";
        $sSqlAverbacoes    .= "         inner join iptubase   on j01_matric = j75_matric                        ";
        $sSqlAverbacoes    .= "         inner join cgm        on z01_numcgm = j01_numcgm                        ";
        $sSqlAverbacoes    .= "   where j75_matric = {$oDados->j01_matric}                                      ";
        $rsSqlAverbacoes    = db_query($sSqlAverbacoes);
        $iNumRowsAverbacoes = pg_num_rows($rsSqlAverbacoes);

        if ($iNumRowsAverbacoes > 0) {

          for ( $xIndAverbacoes = 0; $xIndAverbacoes  < $iNumRowsAverbacoes; $xIndAverbacoes++ ) {

            $oDadoAverbacoes  = db_utils::fieldsMemory($rsSqlAverbacoes, $xIndAverbacoes);

            $oDadosAverbacoes = new stdClass();
            $oDadosAverbacoes->iCodigo     = $oDadoAverbacoes->j75_codigo;
            $oDadosAverbacoes->dtData      = $oDadoAverbacoes->j75_data;
            $oDadosAverbacoes->sTipo       = $oDadoAverbacoes->j93_descr;
            $oDadosAverbacoes->dtDataTipo  = $oDadoAverbacoes->j75_dttipo;
            $oDadosAverbacoes->sObservacao = $oDadoAverbacoes->j75_obs;
            $oDadosAverbacoes->sSituacao   = ( $oDadoAverbacoes->j75_situacao==1?"Não Processado":"Processado" );

            $aDadosAverbacoes['oDadosAverbacoes'][] = $oDadosAverbacoes;
          }
        }

      }

      /**
       * Dados Para Cálculo
       */
      if ( true ) {

        $sSqlIptuCalc  = "   select distinct iptucalc.j23_anousu,                                               ";
        $sSqlIptuCalc .= "          iptucalc.j23_matric,                                                        ";
        $sSqlIptuCalc .= "          iptucalc.j23_testad,                                                        ";
        $sSqlIptuCalc .= "          iptucalc.j23_arealo,                                                        ";
        $sSqlIptuCalc .= "          iptucalc.j23_areafr,                                                        ";
        $sSqlIptuCalc .= "          iptucalc.j23_areaed,                                                        ";
        $sSqlIptuCalc .= "          iptucalc.j23_m2terr,                                                        ";
        $sSqlIptuCalc .= "          iptucalc.j23_vlrter,                                                        ";
        $sSqlIptuCalc .= "          iptucalc.j23_aliq,                                                          ";
        $sSqlIptuCalc .= "          iptucale.j22_pontos,                                                        ";
        $sSqlIptuCalc .= "          (select sum(iptucale.j22_valor)                                             ";
        $sSqlIptuCalc .= "             from iptucale                                                            ";
        $sSqlIptuCalc .= " 				    where j22_anousu = iptucalc.j23_anousu                                    ";
        $sSqlIptuCalc .= "              and j22_matric = iptucalc.j23_matric) as valorvenalconstr,              ";
        $sSqlIptuCalc .= "          (select j20_numpre                                                          ";
        $sSqlIptuCalc .= "					   from iptunump                                                            ";
        $sSqlIptuCalc .= "						where j20_matric = iptucalc.j23_matric                                    ";
        $sSqlIptuCalc .= "							and j20_anousu = iptucalc.j23_anousu) as k00_numpre                     ";
        $sSqlIptuCalc .= "     from iptucalc                                                                    ";
        $sSqlIptuCalc .= "          left outer join iptucale on iptucale.j22_matric = iptucalc.j23_matric       ";
        $sSqlIptuCalc .= "                                  and iptucale.j22_anousu = iptucalc.j23_anousu       ";
        $sSqlIptuCalc .= "    where j23_matric = {$oDados->j01_matric}                                          ";

        if ( false ) {
          $sSqlIptuCalc .= "    and j23_anousu = {$iAnoUsu}                                                     ";
        }

        $sSqlIptuCalc .= " group by iptucalc.j23_anousu,                                                        ";
        $sSqlIptuCalc .= "          iptucalc.j23_matric,                                                        ";
        $sSqlIptuCalc .= "          iptucalc.j23_testad,                                                        ";
        $sSqlIptuCalc .= "          iptucalc.j23_arealo,                                                        ";
        $sSqlIptuCalc .= "          iptucalc.j23_areafr,                                                        ";
        $sSqlIptuCalc .= "          iptucalc.j23_areaed,                                                        ";
        $sSqlIptuCalc .= "          iptucalc.j23_m2terr,                                                        ";
        $sSqlIptuCalc .= "          iptucalc.j23_vlrter,                                                        ";
        $sSqlIptuCalc .= "          iptucalc.j23_aliq,                                                          ";
        $sSqlIptuCalc .= "          iptucale.j22_idcons,                                                        ";
        $sSqlIptuCalc .= "          iptucale.j22_pontos                                                         ";
        $sSqlIptuCalc .= " order by iptucalc.j23_anousu desc                                                    ";

        $rsSqlIptuCalc    = db_query($sSqlIptuCalc);
        $iNumRowsIptuCalc = pg_num_rows($rsSqlIptuCalc);

        if ($iNumRowsIptuCalc > 0) {

          for ( $xIndIptuCalc = 0; $xIndIptuCalc  < $iNumRowsIptuCalc; $xIndIptuCalc++ ) {

            $oDadoCalculo   = db_utils::fieldsMemory($rsSqlIptuCalc, $xIndIptuCalc);

            $oDadosCalculos = new stdClass();
            $oDadosCalculos->iAnoCalculo             = $oDadoCalculo->j23_anousu;
            $oDadosCalculos->iNumpre                 = $oDadoCalculo->k00_numpre;
            $oDadosCalculos->nAreaLote               = $oDadoCalculo->j23_arealo;
            $oDadosCalculos->iFracao                 = $oDadoCalculo->j23_areafr;
            $oDadosCalculos->nValor                  = $oDadoCalculo->j23_m2terr;
            $oDadosCalculos->nAliquota               = $oDadoCalculo->j23_aliq;
            $oDadosCalculos->nValorVenalTerreno      = $oDadoCalculo->j23_vlrter;
            $oDadosCalculos->nTestada                = $oDadoCalculo->j23_testad;

            $oDadosCalculos->iCodigo                 = '';
            $oDadosCalculos->iAreaConstr             = '';
            $oDadosCalculos->iAnoConstr              = '';
            $oDadosCalculos->iPontuacao              = $oDadoCalculo->j22_pontos;
            $oDadosCalculos->nValorVenalConstr       = $oDadoCalculo->valorvenalconstr;
            $oDadosCalculos->nValorVenalTotal        = ( $oDadoCalculo->valorvenalconstr
                + $oDadoCalculo->j23_vlrter );

            $oDadosCalculos->aConstrucoes            = array();
            $oDadosCalculos->aValorlancado           = array();

            $sWhere  = " iptucale.j22_matric = {$oDadoCalculo->j23_matric} ";
            $sWhere .= " and iptucale.j22_anousu = {$oDadoCalculo->j23_anousu} ";
            $sSqlIptucale  = $cliptucale->sql_query(null, null, null, "*", null, $sWhere);
            $rsSqlIptucale = $cliptucale->sql_record($sSqlIptucale);

            if ($cliptucale->numrows > 0) {

              for ( $yInd = 0; $yInd  < $cliptucale->numrows; $yInd++ ) {

                $oDadoConstrucao   = db_utils::fieldsMemory($rsSqlIptucale, $yInd);

                $oDadosConstrucao = new stdClass();
                $oDadosConstrucao->iCodConstrucao = $oDadoConstrucao->j22_idcons;
                $oDadosConstrucao->nAreaConstr    = $oDadoConstrucao->j22_areaed;
                $oDadosConstrucao->iAnoExercicio  = $oDadoConstrucao->j39_ano;
                $oDadosConstrucao->nValor         = $oDadoConstrucao->j22_vm2;
                $oDadosConstrucao->iPontuacao     = $oDadoConstrucao->j22_pontos;
                $oDadosConstrucao->nValorVenal    = $oDadoConstrucao->j22_valor;

                $oDadosCalculos->aConstrucoes[]   = $oDadosConstrucao;

                $oDadosCalculos->iAreaConstr      = $oDadoConstrucao->j39_area;
                $oDadosCalculos->iAnoConstr       = $oDadoConstrucao->j39_ano;
              }
            }

            $sSqlCfiptu  = $clcfiptu->sql_query_file($oDadoCalculo->j23_anousu, "j18_iptuhistisen", null, '');
            $rsSqlCfiptu = $clcfiptu->sql_record($sSqlCfiptu);
            $oDadoCfiptu = (object)array("j18_iptuhistisen" => 0);
            if ($clcfiptu->numrows > 0) {
              $oDadoCfiptu = db_utils::fieldsMemory($rsSqlCfiptu, 0);
            }

            $sSqlValoresLancados  = "  select k02_codigo,                                                                                                                            ";
            $sSqlValoresLancados .= "         k02_descr,                                                                                                                             ";
            $sSqlValoresLancados .= "         ( select j17_descr                                                                                                                     ";
            $sSqlValoresLancados .= "             from iptucalh                                                                                                                      ";
            $sSqlValoresLancados .= "                  inner join iptucalv on iptucalh.j17_codhis = iptucalv.j21_codhis                                                              ";
            $sSqlValoresLancados .= "                                     and iptucalv.j21_receit = total.k02_codigo                                                                 ";
            $sSqlValoresLancados .= "                  limit 1 ) as j17_descr,                                                                                                       ";
            $sSqlValoresLancados .= "          ( select j17_codhis                                                                                                                   ";
            $sSqlValoresLancados .= "              from iptucalh                                                                                                                     ";
            $sSqlValoresLancados .= "                   inner join iptucalv on iptucalh.j17_codhis = iptucalv.j21_codhis                                                             ";
            $sSqlValoresLancados .= "                                      and iptucalv.j21_receit = total.k02_codigo                                                                ";
            $sSqlValoresLancados .= "                   limit 1 ) as j17_codhis,                                                                                                     ";
            $sSqlValoresLancados .= "          sum(j21_valor) as j21_valor,                                                                                                          ";
            $sSqlValoresLancados .= "          sum(j21_valorisen) as j21_valorisen                                                                                                   ";
            $sSqlValoresLancados .= "     from ( select k02_codigo,                                                                                                                  ";
            $sSqlValoresLancados .= "                   k02_descr,                                                                                                                   ";
            $sSqlValoresLancados .= "                   min(j17_codhis) as j17_codhis,                                                                                               ";
            $sSqlValoresLancados .= "                   j17_descr,                                                                                                                   ";
            $sSqlValoresLancados .= "                   sum(j21_valor) as j21_valor,                                                                                                 ";
            $sSqlValoresLancados .= "                   sum(j21_valorisen) as j21_valorisen                                                                                          ";
            $sSqlValoresLancados .= "              from ( select k02_codigo,                                                                                                         ";
            $sSqlValoresLancados .= "                            k02_descr,                                                                                                          ";
            $sSqlValoresLancados .= "                            j17_codhis,                                                                                                         ";
            $sSqlValoresLancados .= "                            j17_descr,                                                                                                          ";
            $sSqlValoresLancados .= "                            case                                                                                                                ";
            $sSqlValoresLancados .= "                               when iptucalh.j17_codhis = iptucadtaxaexe.j08_iptucalh or iptucalh.j17_codhis = 1                                ";
            $sSqlValoresLancados .= "                                 then j21_valor                                                                                                 ";
            $sSqlValoresLancados .= "                               else 0                                                                                                           ";
            $sSqlValoresLancados .= "                             end as j21_valor,                                                                                                  ";
            $sSqlValoresLancados .= "                             case                                                                                                               ";
            $sSqlValoresLancados .= "                               when iptucalh.j17_codhis = iptucadtaxaexe.j08_histisen or iptucalh.j17_codhis = {$oDadoCfiptu->j18_iptuhistisen} ";
            $sSqlValoresLancados .= "                                 then j21_valor                                                                                                 ";
            $sSqlValoresLancados .= "                               else 0                                                                                                           ";
            $sSqlValoresLancados .= "                             end as j21_valorisen                                                                                               ";
            $sSqlValoresLancados .= "                        from iptucalv                                                                                                           ";
            $sSqlValoresLancados .= "                             inner join iptucalh       on iptucalh.j17_codhis       = j21_codhis                                                ";
            $sSqlValoresLancados .= "                             inner join tabrec         on tabrec.k02_codigo         = j21_receit                                                ";
            $sSqlValoresLancados .= "                             left  join iptucadtaxaexe on iptucadtaxaexe.j08_tabrec = j21_receit                                                ";
            $sSqlValoresLancados .= "                                                    and iptucadtaxaexe.j08_anousu = {$oDadoCalculo->j23_anousu}                                 ";
            $sSqlValoresLancados .= "                       where j21_matric = {$oDadoCalculo->j23_matric}                                                                           ";
            $sSqlValoresLancados .= "                         and j21_anousu = {$oDadoCalculo->j23_anousu}                                                                           ";
            $sSqlValoresLancados .= "                    order by iptucalh.j17_codhis                                                                                                ";
            $sSqlValoresLancados .= "                   ) as x                                                                                                                       ";
            $sSqlValoresLancados .= "          group by k02_codigo,                                                                                                                  ";
            $sSqlValoresLancados .= "                   k02_descr,                                                                                                                   ";
            $sSqlValoresLancados .= "                   j17_descr                                                                                                                    ";
            $sSqlValoresLancados .= "            having sum(j21_valor) <> 0 or sum(j21_valorisen) <> 0                                                                               ";
            $sSqlValoresLancados .= "          ) as total                                                                                                                            ";
            $sSqlValoresLancados .= " group by k02_codigo,                                                                                                                           ";
            $sSqlValoresLancados .= "          k02_descr                                                                                                                             ";
            $sSqlValoresLancados .= " order by k02_codigo                                                                                                                            ";

            $rsSqlValoresLancados    = db_query($sSqlValoresLancados);

            $iNumRowsValoresLancados = pg_num_rows($rsSqlValoresLancados);
            if ($iNumRowsValoresLancados > 0) {

              for ( $zInd = 0; $zInd  < $iNumRowsValoresLancados; $zInd++ ) {

                $oDadoValorLancado     = db_utils::fieldsMemory($rsSqlValoresLancados, $zInd);

                $oDadosValoresLancados = new stdClass();
                $oDadosValoresLancados->iCodRec          = $oDadoValorLancado->k02_codigo;
                $oDadosValoresLancados->sDescricao       = $oDadoValorLancado->k02_descr;
                $oDadosValoresLancados->iCodHist         = $oDadoValorLancado->j17_codhis;
                $oDadosValoresLancados->sDescrHist       = $oDadoValorLancado->j17_descr;
                $oDadosValoresLancados->nValorCalculado  = $oDadoValorLancado->j21_valor;
                $oDadosValoresLancados->nValorIsento     = $oDadoValorLancado->j21_valorisen;
                $oDadosValoresLancados->nValorTotalPagar = ( $oDadoValorLancado->j21_valor - $oDadoValorLancado->j21_valorisen );

                $oDadosCalculos->aValorlancado[]         = $oDadosValoresLancados;
              }
            }

            $aDadosCalculos['oDadosCalculos'][]         = $oDadosCalculos;
          }
        }
      }

      /**
       * Outros Dados
       */
      if ( true ) {

        $sSqlOutrosDados     = " select *                                                                       ";
        $sSqlOutrosDados    .= "   from matricobs                                                               ";
        $sSqlOutrosDados    .= " where j26_matric = {$oDados->j01_matric}                                       ";
        $rsSqlOutrosDados    = db_query($sSqlOutrosDados);
        $iNumRowsOutrosDados = pg_num_rows($rsSqlOutrosDados);

        if ($iNumRowsOutrosDados > 0) {

          for ( $xIndOutrosDados = 0; $xIndOutrosDados  < $iNumRowsOutrosDados; $xIndOutrosDados++ ) {

            $oDadoOutrosDados  = db_utils::fieldsMemory($rsSqlOutrosDados, $xIndOutrosDados);

            $oDadosOutrosDados = new stdClass();
            $oDadosOutrosDados->sObservacao = $oDadoOutrosDados->j26_obs;

            $aDadosOutrosDados['oDadosOutrosDados'] = $oDadosOutrosDados;
          }
        }

      }
    }

    /**
     * Imprime Dados do Imovel
     */
    if ( true ) {

      $this->imprimirCabecalhoFixo($pdf, $iAlt, true, 'Dados do Imóvel');

      if ( count($aDadosImovel) > 0 ) {

        foreach ( $aDadosImovel as $oDadoImovel ) {

          $pdf->setfont('arial','B',$iFontBic);
          $pdf->cell(25, $iAlt, 'Matricula'                                                           ,0,0,"L",0);
          $pdf->cell(25, $iAlt, 'Setor'                                                               ,0,0,"L",0);
          $pdf->cell(25, $iAlt, 'Quadra'                                                              ,0,0,"L",0);
          $pdf->cell(25, $iAlt, 'Lote'                                                                ,0,0,"L",0);
          $pdf->cell(93, $iAlt, 'Referência Anterior'                                                 ,0,1,"L",0);

          $pdf->setfont('arial','',$iFontBic);
          $pdf->cell(25, $iAlt, $oDadoImovel->iMatricula                                              ,0,0,"L",0);
          $pdf->cell(25, $iAlt, $oDadoImovel->iSetor                                                  ,0,0,"L",0);
          $pdf->cell(25, $iAlt, $oDadoImovel->iQuadra                                                 ,0,0,"L",0);
          $pdf->cell(25, $iAlt, $oDadoImovel->iLote                                                   ,0,0,"L",0);
          $pdf->cell(93, $iAlt, $oDadoImovel->sRefAnterior                                            ,0,1,"L",0);

          $pdf->cell(193, 1, ''                                                                       ,0,1,"L",0);

          $pdf->setfont('arial','B',$iFontBic);
          $pdf->cell(25, $iAlt, 'Bairro'                                                              ,0,0,"L",0);
          $pdf->cell(50, $iAlt, 'Descrição'                                                           ,0,0,"L",0);
          $pdf->cell(25, $iAlt, 'Loteamento'                                                          ,0,0,"L",0);
          $pdf->cell(93, $iAlt, 'Descrição'                                                           ,0,1,"L",0);

          $pdf->setfont('arial','',$iFontBic);
          $pdf->cell(25, $iAlt, $oDadoImovel->iBairro                                                 ,0,0,"L",0);
          $pdf->cell(50, $iAlt, $oDadoImovel->sDescricao                                              ,0,0,"L",0);
          $pdf->cell(25, $iAlt, $oDadoImovel->iLoteamento                                             ,0,0,"L",0);
          $pdf->cell(93, $iAlt, $oDadoImovel->sLoteDescr                                              ,0,1,"L",0);

          $pdf->cell(193, 1, ''                                                                       ,0,1,"L",0);

          $pdf->setfont('arial','B',$iFontBic);
          $pdf->cell(25, $iAlt, 'Cód. Log.'                                                           ,0,0,"L",0);
          $pdf->cell(25, $iAlt, 'Tipo'                                                                ,0,0,"L",0);
          $pdf->cell(168, $iAlt, 'Logradouro'                                                         ,0,1,"L",0);

          $pdf->setfont('arial','',$iFontBic);
          $pdf->cell(25, $iAlt, $oDadoImovel->iCodLogradouro                                          ,0,0,"L",0);
          $pdf->cell(25, $iAlt, $oDadoImovel->sRuaTipoDescr                                           ,0,0,"L",0);
          $pdf->cell(168, $iAlt, $oDadoImovel->sLogradouroDescr                                       ,0,1,"L",0);

          $pdf->cell(193, 1, ''                                                                       ,0,1,"L",0);

          $pdf->setfont('arial','B',$iFontBic);
          $pdf->cell(100, $iAlt, 'Setor de Localização'                                                ,0,0,"L",0);
          $pdf->cell(30, $iAlt, 'Quadra de Localização'                                               ,0,0,"L",0);
          $pdf->cell(30, $iAlt, 'Lote de Localização'                                                 ,0,1,"L",0);

          $pdf->setfont('arial','',$iFontBic);
          $pdf->cell(100, $iAlt, "$oDadosImovel->iSetorLoc - $oDadoImovel->sDescrLoc"                                            ,0,0,"L",0);
          $pdf->cell(30, $iAlt, $oDadoImovel->iQuadraLoc                                              ,0,0,"L",0);
          $pdf->cell(30, $iAlt, $oDadoImovel->iLoteLoc                                                ,0,1,"L",0);

          $pdf->cell(193, 1, ''                                                                       ,0,1,"L",0);

          $pdf->setfont('arial','B',$iFontBic);
          $pdf->cell(48, $iAlt, 'Área do Lote'                                                        ,0,0,"L",0);
          $pdf->cell(48, $iAlt, 'Área Real do Lote'                                                   ,0,0,"L",0);
          $pdf->cell(48, $iAlt, 'Área Preservada'                                                     ,0,0,"L",0);
          $pdf->cell(48, $iAlt, 'Fração Ideal'                                                        ,0,1,"L",0);

          $pdf->setfont('arial','',$iFontBic);
          $pdf->cell(48, $iAlt, trim(db_formatar($oDadoImovel->nAreaLote, 'f'))                       ,0,0,"L",0);
          $pdf->cell(48, $iAlt, trim(db_formatar($oDadoImovel->nAreaRealLote, 'f'))                   ,0,0,"L",0);
          $pdf->cell(48, $iAlt, trim(db_formatar($oDadoImovel->nAreaPreservada, 'f'))                 ,0,0,"L",0);
          $pdf->cell(48, $iAlt, trim(db_formatar($oDadoImovel->nFracaoIdeal, 'f'))                    ,0,1,"L",0);

          $pdf->cell(193, 1, ''                                                                       ,0,1,"L",0);

          $pdf->setfont('arial','B',$iFontBic);
          $pdf->cell(48, $iAlt, 'Zona Fiscal'                                                         ,0,0,"L",0);
          $pdf->cell(48, $iAlt, 'Setor Fiscal'                                                        ,0,0,"L",0);
          $pdf->cell(48, $iAlt, 'Condomínio'                                                          ,0,1,"L",0);

          $pdf->setfont('arial','',$iFontBic);
          $pdf->cell(48, $iAlt, $oDadoImovel->sZonaFiscal                                             ,0,0,"L",0);
          $pdf->cell(48, $iAlt, $oDadoImovel->sSetorFiscal                                            ,0,0,"L",0);
          $pdf->cell(96, $iAlt, $oDadoImovel->sCondominio                                             ,0,1,"L",0);
        }
      } else {
        $pdf->cell(193, $iAlt, 'Nenhum dado do imóvel encontrado.'                                    ,0,1,"L",0);
      }

      $pdf->cell(193, 1, ''                                                                         ,'B',1,"L",0);
      $pdf->cell(193, $iAlt, ''                                                                       ,0,1,"L",0);
    }

    /**
     * Imprime Características do Lote
     */
    $iTotalCaracteristicaLote = 0;
    if ( true ) {

      $this->imprimirCabecalhoFixo($pdf, $iAlt, true, 'Características do Lote');

      if ( count($aDadosCaractLote) > 0 ) {

        $iAlturaLote = $pdf->GetY() ;

        $pdf->setfont('arial','B',$iFontDados);
        $pdf->cell(20, $iAlt, 'Cód.'                                                                  ,0,0,"L",0);
        $pdf->cell(20, $iAlt, 'Descrição'                                                             ,0,0,"L",0);
        $pdf->cell(20, $iAlt, 'Cód.'                                                                  ,0,0,"L",0);
        $pdf->cell(20, $iAlt, 'Grupo'                                                                 ,0,0,"L",0);
        $pdf->cell(20, $iAlt, 'Ponto'                                                                 ,0,1,"L",0);
        foreach ( $aDadosCaractLote as $aDadoCaractLote ) {

          foreach ( $aDadoCaractLote as $oDadoCaractLote ) {

            $iTotalCaracteristicaLote ++;

            if ($iTotalCaracteristicaLote <= 5) {

              $pdf->setfont('arial','',$iFontDados);
              $pdf->cell(20, $iAlt, $oDadoCaractLote->iCodigo                                           ,0,0,"L",0);
              $pdf->cell(20, $iAlt, substr($oDadoCaractLote->sDescricao, 0, 20)                         ,0,0,"L",0);
              $pdf->cell(20, $iAlt, $oDadoCaractLote->iCodGrupo                                         ,0,0,"L",0);
              $pdf->cell(20, $iAlt, substr($oDadoCaractLote->sGrupoDescr, 0, 20)                        ,0,0,"L",0);
              $pdf->cell(20, $iAlt, $oDadoCaractLote->iPonto                                            ,0,1,"L",0);

            } else if ($iTotalCaracteristicaLote <= 10) {

              if ($iTotalCaracteristicaLote == 6) {

                $pdf->SetY($iAlturaLote);
                $pdf->SetX(110);
                $pdf->setfont('arial','B',$iFontDados);
                $pdf->cell(20, $iAlt, 'Cód.'                                                                  ,0,0,"L",0);
                $pdf->cell(20, $iAlt, 'Descrição'                                                             ,0,0,"L",0);
                $pdf->cell(20, $iAlt, 'Cód.'                                                                  ,0,0,"L",0);
                $pdf->cell(20, $iAlt, 'Grupo'                                                                 ,0,0,"L",0);
                $pdf->cell(20, $iAlt, 'Ponto'                                                                 ,0,1,"L",0);
              }
              $pdf->SetX(110);
              $pdf->setfont('arial','',$iFontDados);
              $pdf->cell(20, $iAlt, $oDadoCaractLote->iCodigo                                           ,0,0,"L",0);
              $pdf->cell(20, $iAlt, substr($oDadoCaractLote->sDescricao, 0, 20)                         ,0,0,"L",0);
              $pdf->cell(20, $iAlt, $oDadoCaractLote->iCodGrupo                                         ,0,0,"L",0);
              $pdf->cell(20, $iAlt, substr($oDadoCaractLote->sGrupoDescr, 0, 20)                        ,0,0,"L",0);
              $pdf->cell(20, $iAlt, $oDadoCaractLote->iPonto                                            ,0,1,"L",0);

            }

          }
        }
        if ($iTotalCaracteristicaLote > 10) {
          $pdf->cell(20, $iAlt, "O lote possui caracteristicas não exibidas."                           ,0,1,"L",0);
        }
      } else {
        $pdf->cell(193, $iAlt, 'Nenhuma característica do lote encontrada.'                             ,0,1,"L",0);
      }

      $pdf->cell(193, 1, ''                                                                         ,'B',1,"L",0);
      $pdf->cell(193, $iAlt, ''                                                                       ,0,1,"L",0);
    }

    /**
     * Imprime Testadas do Lote
     */
    if ( true ) {

      $this->imprimirCabecalhoFixo($pdf, $iAlt, true, 'Testadas do Lote');

      if ( count($aDadosTestadaLote) > 0 ) {

        $pdf->setfont('arial','B',$iFontDados);
        $pdf->cell(19, $iAlt, 'Testada  MI'                                                           ,0,0,"L",0);
        $pdf->cell(19, $iAlt, 'Testada Medida'                                                        ,0,0,"L",0);
        $pdf->cell(19, $iAlt, 'Tipo'                                                                  ,0,0,"L",0);
        $pdf->cell(19, $iAlt, 'Cód. Log.'                                                             ,0,0,"L",0);
        $pdf->cell(49, $iAlt, 'Descrição Logradouro'                                                  ,0,0,"L",0);
        $pdf->cell(19, $iAlt, 'Número'                                                                ,0,0,"L",0);
        $pdf->cell(49, $iAlt, 'Complemento'                                                           ,0,1,"L",0);

        foreach ( $aDadosTestadaLote as $aDadoTestadaLote ) {

          foreach ( $aDadoTestadaLote as $oDadoTestLote ) {

            $pdf->setfont('arial','',$iFontBic);
            $pdf->cell(19, $iAlt, $oDadoTestLote->iCodigoMI                                           ,0,0,"L",0);
            $pdf->cell(19, $iAlt, $oDadoTestLote->iMedida                                             ,0,0,"L",0);
            $pdf->cell(19, $iAlt, $oDadoTestLote->iTipo                                               ,0,0,"L",0);
            $pdf->cell(19, $iAlt, $oDadoTestLote->iCodigoLogr                                         ,0,0,"L",0);
            $pdf->cell(49, $iAlt, $oDadoTestLote->sDescrLogr                                          ,0,0,"L",0);
            $pdf->cell(19, $iAlt, $oDadoTestLote->iNumero                                             ,0,0,"L",0);
            $pdf->cell(49, $iAlt, $oDadoTestLote->sComplemento                                        ,0,1,"L",0);
          }
        }
      } else {
        $pdf->cell(193, $iAlt, 'Nenhuma testada do lote encontrada.'                                  ,0,1,"L",0);
      }

      $pdf->cell(193, 1, ''                                                                         ,'B',1,"L",0);
      $pdf->cell(193, $iAlt, ''                                                                       ,0,1,"L",0);
    }

    /**
     * Imprime Testadas Internas
     */
    if ( true ) {

      //$lMostraBrancos = false;
      if ( count($aDadosTestadaInterna) > 0 ) {
        $this->imprimirCabecalhoFixo($pdf, $iAlt, true, 'Testadas Internas');

        $pdf->setfont('arial','B',$iFontDados);
        $pdf->cell(38, $iAlt, 'Cód. Lote'                                                             ,0,0,"L",0);
        $pdf->cell(38, $iAlt, 'Outro'                                                                 ,0,0,"L",0);
        $pdf->cell(38, $iAlt, 'Orientação'                                                            ,0,0,"L",0);
        $pdf->cell(38, $iAlt, 'Testada MI'                                                            ,0,0,"L",0);
        $pdf->cell(41, $iAlt, 'Testada Medida'                                                        ,0,1,"L",0);

        foreach ( $aDadosTestadaInterna as $aDadoTestadaInterna ) {

          foreach ( $aDadoTestadaInterna as $oDadoTestInterna ) {

            $pdf->setfont('arial','',$iFontBic);
            $pdf->cell(38, $iAlt, $oDadoTestInterna->iCodigoLote                                      ,0,0,"L",0);
            $pdf->cell(38, $iAlt, $oDadoTestInterna->iOutro                                           ,0,0,"L",0);
            $pdf->cell(38, $iAlt, $oDadoTestInterna->iOrientacao                                      ,0,0,"L",0);
            $pdf->cell(38, $iAlt, $oDadoTestInterna->iTestadaMI                                       ,0,0,"L",0);
            $pdf->cell(41, $iAlt, $oDadoTestInterna->iTestadaMed                                      ,0,1,"L",0);
          }
        }
      } else if(count($aDadosTestadaInterna) == 0 && $lImprimeNulo) {
        $this->imprimirCabecalhoFixo($pdf, $iAlt, true, 'Testadas Internas');
        $pdf->cell(193, $iAlt, 'Nenhuma testada interna encontrada.'                                  ,0,1,"L",0);

        $pdf->cell(193, 1, ''                                                                         ,'B',1,"L",0);
        $pdf->cell(193, $iAlt, ''                                                                       ,0,1,"L",0);
      }
    }

    /**
     * Imprime Características da Face
     */
    if ( true ) {

      $iTotalCaracteristicaFace = 0;
      $this->imprimirCabecalhoFixo($pdf, $iAlt, true, 'Características da Face');

      if ( count($aDadosCaractFace) > 0 ) {

        $pdf->setfont('arial','B',$iFontDados);

        $iAlturaFace = $pdf->GetY() ;
        $pdf->cell(10, $iAlt, 'Cód.'                                                                  ,0,0,"L",0);
        $pdf->cell(30, $iAlt, 'Descrição'                                                             ,0,0,"L",0);
        $pdf->cell(20, $iAlt, 'Cód.'                                                                  ,0,0,"L",0);
        $pdf->cell(20, $iAlt, 'Grupo'                                                                 ,0,0,"L",0);
        $pdf->cell(20, $iAlt, 'Ponto'                                                                 ,0,1,"L",0);

        foreach ( $aDadosCaractFace as $aDadoCaractFace ) {

          foreach ( $aDadoCaractFace as $oDadoCaracterFace ) {

            $iTotalCaracteristicaFace ++;
            $pdf->setfont('arial','',$iFontDados);

            if ($iTotalCaracteristicaFace <= 5) {

              $pdf->cell(10, $iAlt, $oDadoCaracterFace->iCodigo                                         ,0,0,"L",0);
              $pdf->cell(30, $iAlt, substr($oDadoCaracterFace->sDescricao, 0, 20)                       ,0,0,"L",0);
              $pdf->cell(20, $iAlt, $oDadoCaracterFace->iCodGrupo                                       ,0,0,"L",0);
              $pdf->cell(20, $iAlt, substr($oDadoCaracterFace->sGrupoDescr, 0, 20)                      ,0,0,"L",0);
              $pdf->cell(20, $iAlt, $oDadoCaracterFace->iPonto                                          ,0,1,"L",0);

            } else if ($iTotalCaracteristicaFace <= 10) {

              if ($iTotalCaracteristicaFace == 6) {

                $pdf->SetY($iAlturaFace);
                $pdf->SetX(110);
                $pdf->setfont('arial','B',$iFontDados);
                $pdf->cell(10, $iAlt, 'Cód.'                                                            ,0,0,"L",0);
                $pdf->cell(30, $iAlt, 'Descrição'                                                       ,0,0,"L",0);
                $pdf->cell(20, $iAlt, 'Cód.'                                                            ,0,0,"L",0);
                $pdf->cell(20, $iAlt, 'Grupo'                                                           ,0,0,"L",0);
                $pdf->cell(20, $iAlt, 'Ponto'                                                           ,0,1,"L",0);
              }
              $pdf->SetX(110);
              $pdf->cell(10, $iAlt, $oDadoCaracterFace->iCodigo                                         ,0,0,"L",0);
              $pdf->cell(30, $iAlt, substr($oDadoCaracterFace->sDescricao, 0, 20)                       ,0,0,"L",0);
              $pdf->cell(20, $iAlt, $oDadoCaracterFace->iCodGrupo                                       ,0,0,"L",0);
              $pdf->cell(20, $iAlt, substr($oDadoCaracterFace->sGrupoDescr, 0, 20)                      ,0,0,"L",0);
              $pdf->cell(20, $iAlt, $oDadoCaracterFace->iPonto                                          ,0,1,"L",0);

            }
          }
        }
        if ($iTotalCaracteristicaFace > 10) {
          $pdf->cell(20, $iAlt, "A face possui caracteristicas não exibidas."                           ,0,1,"L",0);
        }
      } else {
        $pdf->cell(193, $iAlt, 'Nenhuma característica da face encontrada.'                             ,0,1,"L",0);
      }

      $pdf->cell(193, 1, ''                                                                         ,'B',1,"L",0);
      $pdf->cell(193, $iAlt, ''                                                                       ,0,1,"L",0);
    }

    /**
     * Imprime Proprietario
     */
    if ( true ) {

      $this->imprimirCabecalhoFixo($pdf, $iAlt, true, 'Proprietário');

      if ( count($aDadosProprietario) > 0 ) {

        foreach ( $aDadosProprietario as $oDadoPropri ) {

          $pdf->setfont('arial','B',$iFontDados);
          $pdf->cell(25, $iAlt, 'CGM'                                                                 ,0,0,"L",0);
          $pdf->cell(25, $iAlt, 'Tipo'                                                                ,0,0,"L",0);
          $pdf->cell(95, $iAlt, 'Nome'                                                                ,0,0,"L",0);
          $pdf->cell(40, $iAlt, 'CPF/CNPJ'                                                            ,0,1,"L",0);

          $pdf->setfont('arial','',$iFontBic);
          $pdf->cell(25, $iAlt, $oDadoPropri->iCgm                                                    ,0,0,"L",0);
          $pdf->cell(25, $iAlt, $oDadoPropri->sTipo                                                   ,0,0,"L",0);
          $pdf->cell(95, $iAlt, substr($oDadoPropri->sNome, 0, 40)                                    ,0,0,"L",0);

          $sCgcCpf = '';
          if (strlen($oDadoPropri->iCgcCpf) == 11) {
            $sCgcCpf = db_formatar($oDadoPropri->iCgcCpf, 'cpf');
          } else if (strlen($oDadoPropri->iCgcCpf) == 14) {
            $sCgcCpf = db_formatar($oDadoPropri->iCgcCpf, 'cnpj');
          }

          $pdf->cell(40, $iAlt, $sCgcCpf                                                              ,0,1,"L",0);

          $pdf->cell(193, 1, ''                                                                       ,0,1,"L",0);

          $pdf->setfont('arial','B',$iFontDados);
          $pdf->cell(60, $iAlt, 'Logradouro'                                                          ,0,0,"L",0);
          $pdf->cell(25, $iAlt, 'Número'                                                              ,0,0,"L",0);
          $pdf->cell(60, $iAlt, 'Complemento'                                                         ,0,0,"L",0);
          $pdf->cell(40, $iAlt, 'Caixa Postal'                                                        ,0,1,"L",0);

          $pdf->setfont('arial','',$iFontBic);
          $pdf->cell(60, $iAlt, substr($oDadoPropri->sLogradouro, 0, 40)                              ,0,0,"L",0);
          $pdf->cell(25, $iAlt, $oDadoPropri->iNumero                                                 ,0,0,"L",0);
          $pdf->cell(60, $iAlt, substr($oDadoPropri->sComplemento, 0, 40)                             ,0,0,"L",0);
          $pdf->cell(40, $iAlt, $oDadoPropri->iCaixaPostal                                            ,0,1,"L",0);

          $pdf->cell(193, 1, ''                                                                       ,0,1,"L",0);

          $pdf->setfont('arial','B',$iFontDados);
          $pdf->cell(60, $iAlt, 'Bairro'                                                              ,0,0,"L",0);
          $pdf->cell(60, $iAlt, 'Cidade'                                                              ,0,0,"L",0);
          $pdf->cell(40, $iAlt, 'UF'                                                                  ,0,0,"L",0);
          $pdf->cell(40, $iAlt, 'CEP'                                                                 ,0,1,"L",0);

          $pdf->setfont('arial','',$iFontBic);
          $pdf->cell(60, $iAlt, $oDadoPropri->sBairro                                                 ,0,0,"L",0);
          $pdf->cell(60, $iAlt, $oDadoPropri->sCidade                                                 ,0,0,"L",0);
          $pdf->cell(40, $iAlt, $oDadoPropri->sUf                                                     ,0,0,"L",0);
          $pdf->cell(40, $iAlt, $oDadoPropri->sCep                                                    ,0,1,"L",0);

          $pdf->cell(193, 1, ''                                                                       ,0,1,"L",0);

          $pdf->setfont('arial','B',$iFontDados);
          $pdf->cell(60, $iAlt, 'Telefone'                                                            ,0,0,"L",0);
          $pdf->cell(60, $iAlt, 'Celular'                                                             ,0,0,"L",0);
          $pdf->cell(20, $iAlt, 'Fax'                                                                 ,0,0,"L",0);
          $pdf->cell(60, $iAlt, 'E-mail'                                                              ,0,1,"L",0);

          $pdf->setfont('arial','',$iFontBic);
          $pdf->cell(60, $iAlt, $oDadoPropri->iTelefone                                               ,0,0,"L",0);
          $pdf->cell(60, $iAlt, $oDadoPropri->iCelular                                                ,0,0,"L",0);
          $pdf->cell(20, $iAlt, $oDadoPropri->iFax                                                    ,0,0,"L",0);
          $pdf->cell(60, $iAlt, $oDadoPropri->sEmail                                                  ,0,1,"L",0);
        }
      } else {
        $pdf->cell(193, $iAlt, 'Nenhum proprietário encontrado.'                                      ,0,1,"L",0);
      }

      $pdf->cell(193, 1, ''                                                                         ,'B',1,"L",0);
      $pdf->cell(193, $iAlt, ''                                                                       ,0,1,"L",0);
    }

    /**
     * Imprime Outros Proprietarios
     */
    if ( true ) {

      if ( count($aDadosOutrosProprietarios) > 0 ) {

        $this->imprimirCabecalhoFixo($pdf, $iAlt, true, 'Outros Proprietários');

        foreach ( $aDadosOutrosProprietarios as $aOutrosPropri ) {

          foreach ( $aOutrosPropri as $oOutrosPropri ) {


            $pdf->setfont('arial','B',$iFontDados);
            $pdf->cell(25, $iAlt, 'CGM'                                                               ,0,0,"L",0);
            $pdf->cell(25, $iAlt, 'Tipo'                                                              ,0,0,"L",0);
            $pdf->cell(80, $iAlt, 'Nome'                                                              ,0,0,"L",0);
            $pdf->cell(40, $iAlt, 'CPF/CNPJ'                                                          ,0,1,"L",0);

            $pdf->setfont('arial','',$iFontBic);
            $pdf->cell(25, $iAlt, $oOutrosPropri->iCgm                                                ,0,0,"L",0);
            $pdf->cell(25, $iAlt, $oOutrosPropri->sTipo                                               ,0,0,"L",0);
            $pdf->cell(80, $iAlt, substr($oOutrosPropri->sNome, 0, 30)                                ,0,0,"L",0);

            $sCgcCpf = '';
            if (strlen($oOutrosPropri->iCgcCpf) == 11) {
              $sCgcCpf = db_formatar($oOutrosPropri->iCgcCpf, 'cpf');
            } else if (strlen($oOutrosPropri->iCgcCpf) == 14) {
              $sCgcCpf = db_formatar($oOutrosPropri->iCgcCpf, 'cnpj');
            }

            $pdf->cell(40, $iAlt, $sCgcCpf                                                            ,0,1,"L",0);

            $pdf->cell(193, 1, ''                                                                     ,0,1,"L",0);

            $pdf->setfont('arial','B',$iFontDados);
            $pdf->cell(60, $iAlt, 'Logradouro'                                                        ,0,0,"L",0);
            $pdf->cell(25, $iAlt, 'Número'                                                            ,0,0,"L",0);
            $pdf->cell(60, $iAlt, 'Complemento'                                                       ,0,0,"L",0);
            $pdf->cell(40, $iAlt, 'Caixa Postal'                                                      ,0,1,"L",0);

            $pdf->setfont('arial','',$iFontBic);
            $pdf->cell(60, $iAlt, substr($oOutrosPropri->sLogradouro, 0, 40)                          ,0,0,"L",0);
            $pdf->cell(25, $iAlt, $oOutrosPropri->iNumero                                             ,0,0,"L",0);
            $pdf->cell(60, $iAlt, substr($oOutrosPropri->sComplemento, 0, 30)                         ,0,0,"L",0);
            $pdf->cell(40, $iAlt, $oOutrosPropri->iCaixaPostal                                        ,0,1,"L",0);

            $pdf->cell(193, 1, ''                                                                     ,0,1,"L",0);

            $pdf->setfont('arial','B',$iFontDados);
            $pdf->cell(60, $iAlt, 'Bairro'                                                            ,0,0,"L",0);
            $pdf->cell(60, $iAlt, 'Cidade'                                                            ,0,0,"L",0);
            $pdf->cell(40, $iAlt, 'UF'                                                                ,0,0,"L",0);
            $pdf->cell(40, $iAlt, 'CEP'                                                               ,0,1,"L",0);

            $pdf->setfont('arial','',$iFontBic);
            $pdf->cell(60, $iAlt, $oOutrosPropri->sBairro                                             ,0,0,"L",0);
            $pdf->cell(60, $iAlt, $oOutrosPropri->sCidade                                             ,0,0,"L",0);
            $pdf->cell(40, $iAlt, $oOutrosPropri->sUf                                                 ,0,0,"L",0);
            $pdf->cell(40, $iAlt, $oOutrosPropri->sCep                                                ,0,1,"L",0);

            $pdf->cell(193, 1, ''                                                                     ,0,1,"L",0);

            $pdf->setfont('arial','B',$iFontDados);
            $pdf->cell(60, $iAlt, 'Telefone'                                                          ,0,0,"L",0);
            $pdf->cell(60, $iAlt, 'Celular'                                                           ,0,0,"L",0);
            $pdf->cell(20, $iAlt, 'Fax'                                                               ,0,0,"L",0);
            $pdf->cell(60, $iAlt, 'E-mail'                                                            ,0,1,"L",0);

            $pdf->setfont('arial','',$iFontBic);
            $pdf->cell(60, $iAlt, $oOutrosPropri->iTelefone                                           ,0,0,"L",0);
            $pdf->cell(60, $iAlt, $oOutrosPropri->iCelular                                            ,0,0,"L",0);
            $pdf->cell(20, $iAlt, $oOutrosPropri->iFax                                                ,0,0,"L",0);
            $pdf->cell(60, $iAlt, $oOutrosPropri->sEmail                                              ,0,1,"L",0);

            $pdf->cell(193, $iAlt, ''                                                                 ,0,1,"L",0);
          }
        }
      } else if(count($aDadosOutrosProprietarios) == 0 && $lImprimeNulo) {

        $this->imprimirCabecalhoFixo($pdf, $iAlt, true, 'Outros Proprietários');
        $pdf->cell(193, $iAlt, 'Nenhum outro proprietário encontrado.'                                ,0,1,"L",0);
        $pdf->cell(193, $iAlt, ''                                                                     ,'T',1,"L",0);
      }

    }

    /**
     * Imprime Promitentes
     */
    if ( true ) {

      if ( count($aDadosPromitentes) > 0 ) {

        $this->imprimirCabecalhoFixo($pdf, $iAlt, true, 'Promitentes');

        foreach ( $aDadosPromitentes as $aPromitentes ) {

          foreach ( $aPromitentes as $oPromitentes ) {

            $pdf->setfont('arial','B',$iFontDados);
            $pdf->cell(15, $iAlt, 'CGM'                                                               ,0,0,"L",0);
            $pdf->cell(10, $iAlt, 'Tipo'                                                              ,0,0,"L",0);
            $pdf->cell(70, $iAlt, 'Nome'                                                              ,0,0,"L",0);
            $pdf->cell(38, $iAlt, 'CPF/CNPJ'                                                          ,0,0,"L",0);
            $pdf->cell(62, $iAlt, 'Contato'                                                           ,0,1,"L",0);

            $pdf->setfont('arial','',$iFontBic);
            $pdf->cell(15, $iAlt, $oPromitentes->iCgm                                                 ,0,0,"L",0);
            $pdf->cell(10, $iAlt, $oPromitentes->sTipo                                                ,0,0,"L",0);
            $pdf->cell(70, $iAlt, substr($oPromitentes->sNome, 0, 30)                                 ,0,0,"L",0);

            $sCgcCpf = '';
            if (strlen($oPromitentes->iCgcCpf) == 11) {
              $sCgcCpf = db_formatar($oPromitentes->iCgcCpf, 'cpf');
            } else if (strlen($oPromitentes->iCgcCpf) == 14) {
              $sCgcCpf = db_formatar($oPromitentes->iCgcCpf, 'cnpj');
            }

            $pdf->cell(38, $iAlt, $sCgcCpf                                                            ,0,0,"L",0);
            $pdf->cell(62, $iAlt, substr($oPromitentes->sContato, 0, 30)                              ,0,1,"L",0);

            $pdf->cell(193, 1, ''                                                                     ,0,1,"L",0);

            $pdf->setfont('arial','B',$iFontDados);
            $pdf->cell(60, $iAlt, 'Logradouro'                                                        ,0,0,"L",0);
            $pdf->cell(25, $iAlt, 'Número'                                                            ,0,0,"L",0);
            $pdf->cell(60, $iAlt, 'Complemento'                                                       ,0,0,"L",0);
            $pdf->cell(40, $iAlt, 'Caixa Postal'                                                      ,0,1,"L",0);

            $pdf->setfont('arial','',$iFontBic);
            $pdf->cell(60, $iAlt, substr($oPromitentes->sLogradouro, 0, 40)                           ,0,0,"L",0);
            $pdf->cell(25, $iAlt, $oPromitentes->iNumero                                              ,0,0,"L",0);
            $pdf->cell(60, $iAlt, substr($oPromitentes->sComplemento, 0, 30)                          ,0,0,"L",0);
            $pdf->cell(40, $iAlt, $oPromitentes->iCaixaPostal                                         ,0,1,"L",0);

            $pdf->cell(193, 1, ''                                                                     ,0,1,"L",0);

            $pdf->setfont('arial','B',$iFontDados);
            $pdf->cell(60, $iAlt, 'Bairro'                                                            ,0,0,"L",0);
            $pdf->cell(60, $iAlt, 'Cidade'                                                            ,0,0,"L",0);
            $pdf->cell(40, $iAlt, 'UF'                                                                ,0,0,"L",0);
            $pdf->cell(40, $iAlt, 'CEP'                                                               ,0,1,"L",0);

            $pdf->setfont('arial','',$iFontBic);
            $pdf->cell(60, $iAlt, $oPromitentes->sBairro                                              ,0,0,"L",0);
            $pdf->cell(60, $iAlt, $oPromitentes->sCidade                                              ,0,0,"L",0);
            $pdf->cell(40, $iAlt, $oPromitentes->sUf                                                  ,0,0,"L",0);
            $pdf->cell(40, $iAlt, $oPromitentes->sCep                                                 ,0,1,"L",0);

            $pdf->cell(193, 1, ''                                                                     ,0,1,"L",0);

            $pdf->setfont('arial','B',$iFontDados);
            $pdf->cell(60, $iAlt, 'Telefone'                                                          ,0,0,"L",0);
            $pdf->cell(60, $iAlt, 'Celular'                                                           ,0,0,"L",0);
            $pdf->cell(20, $iAlt, 'Fax'                                                               ,0,0,"L",0);
            $pdf->cell(60, $iAlt, 'E-mail'                                                            ,0,1,"L",0);

            $pdf->setfont('arial','',$iFontBic);
            $pdf->cell(60, $iAlt, $oPromitentes->iTelefone                                            ,0,0,"L",0);
            $pdf->cell(60, $iAlt, $oPromitentes->iCelular                                             ,0,0,"L",0);
            $pdf->cell(20, $iAlt, $oPromitentes->iFax                                                 ,0,0,"L",0);
            $pdf->cell(60, $iAlt, $oPromitentes->sEmail                                               ,0,1,"L",0);

            $pdf->cell(193, $iAlt, ''                                                                 ,0,1,"L",0);
          }
        }
      } else if(count($aDadosPromitentes) == 0 && $lImprimeNulo) {

        $this->imprimirCabecalhoFixo($pdf, $iAlt, true, 'Promitentes');
        $pdf->cell(193, $iAlt, 'Nenhum promitente encontrado.'                                        ,0,1,"L",0);
        $pdf->cell(193, $iAlt, ''                                                                     ,'T',1,"L",0);
      }

    }

    /**
     * Imprime Outros Promitentes
     */
    if ( true ) {

      if ( count($aDadosOutrosPromitentes) > 0 ) {

        $this->imprimirCabecalhoFixo($pdf, $iAlt, true, 'Outros Promitentes');
        foreach ( $aDadosOutrosPromitentes as $aOutrosPromitentes ) {

          foreach ( $aOutrosPromitentes as $oOutrosPromitentes ) {

            $pdf->setfont('arial','B',$iFontDados);
            $pdf->cell(15, $iAlt, 'CGM'                                                               ,0,0,"L",0);
            $pdf->cell(10, $iAlt, 'Tipo'                                                              ,0,0,"L",0);
            $pdf->cell(70, $iAlt, 'Nome'                                                              ,0,0,"L",0);
            $pdf->cell(38, $iAlt, 'CPF/CNPJ'                                                          ,0,0,"L",0);
            $pdf->cell(62, $iAlt, 'Contato'                                                           ,0,1,"L",0);

            $pdf->setfont('arial','',$iFontBic);
            $pdf->cell(15, $iAlt, $oOutrosPromitentes->iCgm                                           ,0,0,"L",0);
            $pdf->cell(10, $iAlt, $oOutrosPromitentes->sTipo                                          ,0,0,"L",0);
            $pdf->cell(70, $iAlt, substr($oOutrosPromitentes->sNome, 0, 30)                           ,0,0,"L",0);

            $sCgcCpf = '';
            if (strlen($oOutrosPromitentes->iCgcCpf) == 11) {
              $sCgcCpf = db_formatar($oOutrosPromitentes->iCgcCpf, 'cpf');
            } else if (strlen($oOutrosPromitentes->iCgcCpf) == 14) {
              $sCgcCpf = db_formatar($oOutrosPromitentes->iCgcCpf, 'cnpj');
            }

            $pdf->cell(38, $iAlt, $sCgcCpf                                                            ,0,0,"L",0);
            $pdf->cell(62, $iAlt, substr($oOutrosPromitentes->sContato, 0, 30)                        ,0,1,"L",0);

            $pdf->cell(193, 1, ''                                                                     ,0,1,"L",0);

            $pdf->setfont('arial','B',$iFontDados);
            $pdf->cell(60, $iAlt, 'Logradouro'                                                        ,0,0,"L",0);
            $pdf->cell(25, $iAlt, 'Número'                                                            ,0,0,"L",0);
            $pdf->cell(60, $iAlt, 'Complemento'                                                       ,0,0,"L",0);
            $pdf->cell(40, $iAlt, 'Caixa Postal'                                                      ,0,1,"L",0);

            $pdf->setfont('arial','',$iFontBic);
            $pdf->cell(60, $iAlt, substr($oOutrosPromitentes->sLogradouro, 0, 40)                     ,0,0,"L",0);
            $pdf->cell(25, $iAlt, $oOutrosPromitentes->iNumero                                        ,0,0,"L",0);
            $pdf->cell(60, $iAlt, substr($oOutrosPromitentes->sComplemento, 0, 30)                    ,0,0,"L",0);
            $pdf->cell(40, $iAlt, $oOutrosPromitentes->iCaixaPostal                                   ,0,1,"L",0);

            $pdf->cell(193, 1, ''                                                                     ,0,1,"L",0);

            $pdf->setfont('arial','B',$iFontDados);
            $pdf->cell(60, $iAlt, 'Bairro'                                                            ,0,0,"L",0);
            $pdf->cell(60, $iAlt, 'Cidade'                                                            ,0,0,"L",0);
            $pdf->cell(40, $iAlt, 'UF'                                                                ,0,0,"L",0);
            $pdf->cell(40, $iAlt, 'CEP'                                                               ,0,1,"L",0);

            $pdf->setfont('arial','',$iFontBic);
            $pdf->cell(60, $iAlt, $oOutrosPromitentes->sBairro                                        ,0,0,"L",0);
            $pdf->cell(60, $iAlt, $oOutrosPromitentes->sCidade                                        ,0,0,"L",0);
            $pdf->cell(40, $iAlt, $oOutrosPromitentes->sUf                                            ,0,0,"L",0);
            $pdf->cell(40, $iAlt, $oOutrosPromitentes->sCep                                           ,0,1,"L",0);

            $pdf->cell(193, 1, ''                                                                     ,0,1,"L",0);

            $pdf->setfont('arial','B',$iFontDados);
            $pdf->cell(60, $iAlt, 'Telefone'                                                          ,0,0,"L",0);
            $pdf->cell(60, $iAlt, 'Celular'                                                           ,0,0,"L",0);
            $pdf->cell(20, $iAlt, 'Fax'                                                               ,0,0,"L",0);
            $pdf->cell(60, $iAlt, 'E-mail'                                                            ,0,1,"L",0);

            $pdf->setfont('arial','',$iFontBic);
            $pdf->cell(60, $iAlt, $oOutrosPromitentes->iTelefone                                      ,0,0,"L",0);
            $pdf->cell(60, $iAlt, $oOutrosPromitentes->iCelular                                       ,0,0,"L",0);
            $pdf->cell(20, $iAlt, $oOutrosPromitentes->iFax                                           ,0,0,"L",0);
            $pdf->cell(60, $iAlt, $oOutrosPromitentes->sEmail                                         ,0,1,"L",0);

            $pdf->cell(193, $iAlt, ''                                                                 ,0,1,"L",0);
          }
        }
      } else if(count($aDadosOutrosPromitentes) == 0 && $lImprimeNulo) {

        $this->imprimirCabecalhoFixo($pdf, $iAlt, true, 'Outros Promitentes');
        $pdf->cell(193, $iAlt, 'Nenhum outro promitente encontrado.'                                  ,0,1,"L",0);
        $pdf->cell(193, $iAlt, ''                                                                     ,'T',1,"L",0);
      }

    }

    /**
     * Imprime Imobiliarias
     */
    if ( true ) {

      if ( count($aDadosImobiliaria) > 0 ) {

        $this->imprimirCabecalhoFixo($pdf, $iAlt, true, 'Imobiliária');

        foreach ( $aDadosImobiliaria as $oDadoImobiliaria ) {

          $pdf->setfont('arial','B',$iFontDados);
          $pdf->cell(25, $iAlt, 'CGM'                                                                 ,0,0,"L",0);
          $pdf->cell(100, $iAlt, 'Nome'                                                               ,0,0,"L",0);
          $pdf->cell(40, $iAlt, 'CPF/CNPJ'                                                            ,0,1,"L",0);

          $pdf->setfont('arial','',$iFontBic);
          $pdf->cell(25, $iAlt, $oDadoImobiliaria->iCgm                                               ,0,0,"L",0);
          $pdf->cell(100, $iAlt, substr($oDadoImobiliaria->sNome, 0, 40)                              ,0,0,"L",0);

          $sCgcCpf = '';
          if (strlen($oDadoImobiliaria->iCgcCpf) == 11) {
            $sCgcCpf = db_formatar($oDadoImobiliaria->iCgcCpf, 'cpf');
          } else if (strlen($oDadoImobiliaria->iCgcCpf) == 14) {
            $sCgcCpf = db_formatar($oDadoImobiliaria->iCgcCpf, 'cnpj');
          }

          $pdf->cell(40, $iAlt, $sCgcCpf                                                              ,0,1,"L",0);

          $pdf->cell(193, 1, ''                                                                       ,0,1,"L",0);

          $pdf->setfont('arial','B',$iFontDados);
          $pdf->cell(90, $iAlt, 'Logradouro'                                                          ,0,0,"L",0);
          $pdf->cell(15, $iAlt, 'Número'                                                              ,0,0,"L",0);
          $pdf->cell(50, $iAlt, 'Complemento'                                                         ,0,0,"L",0);
          $pdf->cell(30, $iAlt, 'Caixa Postal'                                                        ,0,1,"L",0);

          $pdf->setfont('arial','',$iFontBic);
          $pdf->cell(90, $iAlt, substr($oDadoImobiliaria->sLogradouro, 0, 40)                         ,0,0,"L",0);
          $pdf->cell(15, $iAlt, $oDadoImobiliaria->iNumero                                            ,0,0,"L",0);
          $pdf->cell(50, $iAlt, substr($oDadoImobiliaria->sComplemento, 0, 30)                        ,0,0,"L",0);
          $pdf->cell(30, $iAlt, $oDadoImobiliaria->iCaixaPostal                                       ,0,1,"L",0);

          $pdf->cell(193, 1, ''                                                                       ,0,1,"L",0);

          $pdf->setfont('arial','B',$iFontDados);
          $pdf->cell(90, $iAlt, 'Bairro'                                                              ,0,0,"L",0);
          $pdf->cell(50, $iAlt, 'Cidade'                                                              ,0,0,"L",0);
          $pdf->cell(20, $iAlt, 'UF'                                                                  ,0,0,"L",0);
          $pdf->cell(30, $iAlt, 'CEP'                                                                 ,0,1,"L",0);

          $pdf->setfont('arial','',$iFontBic);
          $pdf->cell(90, $iAlt, $oDadoImobiliaria->sBairro                                            ,0,0,"L",0);
          $pdf->cell(50, $iAlt, $oDadoImobiliaria->sCidade                                            ,0,0,"L",0);
          $pdf->cell(20, $iAlt, $oDadoImobiliaria->sUf                                                ,0,0,"L",0);
          $pdf->cell(30, $iAlt, $oDadoImobiliaria->sCep                                               ,0,1,"L",0);

          $pdf->cell(193, 1, ''                                                                       ,0,1,"L",0);

          $pdf->setfont('arial','B',$iFontDados);
          $pdf->cell(60, $iAlt, 'Telefone'                                                            ,0,0,"L",0);
          $pdf->cell(60, $iAlt, 'Celular'                                                             ,0,0,"L",0);
          $pdf->cell(20, $iAlt, 'Fax'                                                                 ,0,0,"L",0);
          $pdf->cell(60, $iAlt, 'E-mail'                                                              ,0,1,"L",0);

          $pdf->setfont('arial','',$iFontBic);
          $pdf->cell(60, $iAlt, $oDadoImobiliaria->iTelefone                                          ,0,0,"L",0);
          $pdf->cell(60, $iAlt, $oDadoImobiliaria->iCelular                                           ,0,0,"L",0);
          $pdf->cell(20, $iAlt, $oDadoImobiliaria->iFax                                               ,0,0,"L",0);
          $pdf->cell(60, $iAlt, $oDadoImobiliaria->sEmail                                             ,0,1,"L",0);

          $pdf->cell(193, $iAlt, ''                                                                   ,0,1,"L",0);
        }
      } else if(count($aDadosImobiliaria) == 0 && $lImprimeNulo) {

        $this->imprimirCabecalhoFixo($pdf, $iAlt, true, 'Imobiliária');
        $pdf->cell(193, $iAlt, 'Nenhuma imobiliária encontrada.'                                      ,0,1,"L",0);
        $pdf->cell(193, $iAlt, ''                                                                     ,'T',1,"L",0);
      }

    }

    /**
     * Imprime Endereco de Entrega
     */
    if ( true ) {

      if ( count($aDadosEnderecoEntrega) > 0 ) {

        $this->imprimirCabecalhoFixo($pdf, $iAlt, true, 'Endereço de Entrega');

        foreach ( $aDadosEnderecoEntrega as $oEnderecoEntrega ) {

          $pdf->setfont('arial','B',$iFontDados);
          $pdf->cell(70, $iAlt, 'Logradouro'                                                          ,0,0,"L",0);
          $pdf->cell(20, $iAlt, 'Número'                                                              ,0,0,"L",0);
          $pdf->cell(70, $iAlt, 'Complemento'                                                         ,0,0,"L",0);
          $pdf->cell(30, $iAlt, 'Caixa Postal'                                                        ,0,1,"L",0);

          $pdf->setfont('arial','',$iFontBic);
          $pdf->cell(70, $iAlt, substr($oEnderecoEntrega->sLogradouro, 0, 40)                         ,0,0,"L",0);
          $pdf->cell(20, $iAlt, $oEnderecoEntrega->iNumero                                            ,0,0,"L",0);
          $pdf->cell(70, $iAlt, substr($oEnderecoEntrega->sComplemento, 0, 30)                        ,0,0,"L",0);
          $pdf->cell(30, $iAlt, $oEnderecoEntrega->iCaixaPostal                                       ,0,1,"L",0);

          $pdf->cell(193, 1, ''                                                                       ,0,1,"L",0);

          $pdf->setfont('arial','B',$iFontDados);
          $pdf->cell(70, $iAlt, 'Bairro'                                                              ,0,0,"L",0);
          $pdf->cell(70, $iAlt, 'Cidade'                                                              ,0,0,"L",0);
          $pdf->cell(20, $iAlt, 'UF'                                                                  ,0,0,"L",0);
          $pdf->cell(30, $iAlt, 'CEP'                                                                 ,0,1,"L",0);

          $pdf->setfont('arial','',$iFontBic);
          $pdf->cell(70, $iAlt, $oEnderecoEntrega->sBairro                                            ,0,0,"L",0);
          $pdf->cell(70, $iAlt, $oEnderecoEntrega->sCidade                                            ,0,0,"L",0);
          $pdf->cell(20, $iAlt, $oEnderecoEntrega->sUf                                                ,0,0,"L",0);
          $pdf->cell(30, $iAlt, $oEnderecoEntrega->sCep                                               ,0,1,"L",0);

          $pdf->cell(193, 1, ''                                                                       ,0,1,"L",0);

          $pdf->setfont('arial','B',$iFontDados);
          $pdf->cell(70, $iAlt, 'Nome Destinatário'                                                   ,0,1,"L",0);

          $pdf->setfont('arial','',$iFontBic);
          $pdf->cell(70, $iAlt, $oEnderecoEntrega->sNomeDest                                          ,0,1,"L",0);

          $pdf->cell(193, $iAlt, ''                                                                   ,0,1,"L",0);
        }
      } else if(count($aDadosEnderecoEntrega) == 0 && $lImprimeNulo) {

        $this->imprimirCabecalhoFixo($pdf, $iAlt, true, 'Endereço de Entrega');
        $pdf->cell(193, $iAlt, 'Nenhum endereço de entrega encontrado.'                               ,0,1,"L",0);
        $pdf->cell(193, $iAlt, ''                                                                     ,'T',1,"L",0);
      }

    }

    /**
     * Imprime Edificações
     */
    if ( true ) {

      if ( count($aDadosEdificacoes) > 0 ) {

        $this->imprimirCabecalhoFixo($pdf, $iAlt, true, 'Cadastro Edificações');

        foreach ( $aDadosEdificacoes as $oEdificacoes ) {

          $pdf->setfont('arial','B',$iFontDados);
          $pdf->cell(30, $iAlt, 'Cód. Construção'                                                     ,0,0,"L",0);
          $pdf->cell(30, $iAlt, 'Ano'                                                                 ,0,0,"L",0);
          $pdf->cell(30, $iAlt, 'Data Inclusão'                                                       ,0,0,"L",0);
          $pdf->cell(30, $iAlt, 'Data Habite-se'                                                      ,0,1,"L",0);

          $pdf->setfont('arial','',$iFontDados);
          $pdf->cell(30, $iAlt, $oEdificacoes['oDadosEdificacoes']->iCodConstrucao                    ,0,0,"L",0);
          $pdf->cell(30, $iAlt, $oEdificacoes['oDadosEdificacoes']->iAno                              ,0,0,"L",0);

          $dtInclusao = '';
          if ( !empty($oEdificacoes['oDadosEdificacoes']->dtInclusao) ) {
            $dtInclusao = db_formatar($oEdificacoes['oDadosEdificacoes']->dtInclusao, 'd');
          }

          $pdf->cell(30, $iAlt, $dtInclusao                                                           ,0,0,"L",0);

          $dtHabite = '';
          if ( !empty($oEdificacoes['oDadosEdificacoes']->dtHabite) ) {
            $dtHabite = db_formatar($oEdificacoes['oDadosEdificacoes']->dtHabite, 'd');
          }

          $pdf->cell(30, $iAlt, $dtHabite                                                             ,0,1,"L",0);

          $pdf->cell(193, 1, ''                                                                       ,0,1,"L",0);

          $pdf->setfont('arial','B',$iFontDados);
          $pdf->cell(30, $iAlt, 'Área Edificada'                                                      ,0,0,"L",0);
          $pdf->cell(10, $iAlt, 'Número /'                                                            ,0,0,"L",0);
          $pdf->cell(80, $iAlt, 'Complemento'                                                         ,0,0,"L",0);
          $pdf->cell(20, $iAlt, 'Pavimento'                                                           ,0,0,"L",0);
          $pdf->cell(20, $iAlt, 'Origem da Construção'                                                ,0,1,"L",0);

          $pdf->setfont('arial','',$iFontDados);
          $pdf->cell(30, $iAlt, $oEdificacoes['oDadosEdificacoes']->nAreaEdificada                    ,0,0,"L",0);
          $pdf->cell(10, $iAlt, $oEdificacoes['oDadosEdificacoes']->iNumeroConstr                     ,0,0,"L",0);
          $pdf->cell(80, $iAlt, $oEdificacoes['oDadosEdificacoes']->sComplemento                      ,0,0,"L",0);
          $pdf->cell(20, $iAlt, $oEdificacoes['oDadosEdificacoes']->iPavimento                        ,0,0,"L",0);
          $pdf->cell(20, $iAlt, $oEdificacoes['oDadosEdificacoes']->iOrigem                           ,0,1,"L",0);

          $pdf->cell(193, 1, ''                                                                       ,0,1,"L",0);

          $pdf->setfont('arial','B',$iFontDados);
          $pdf->cell(30, $iAlt, 'Situção'                                                             ,0,0,"L",0);
          $pdf->cell(30, $iAlt, 'Data Demolição'                                                      ,0,0,"L",0);
          $pdf->cell(30, $iAlt, 'Área Privada'                                                        ,0,0,"L",0);
          $pdf->cell(30, $iAlt, 'Tipo'                                                                ,0,1,"L",0);

          $pdf->setfont('arial','',$iFontDados);
          $pdf->cell(30, $iAlt, $oEdificacoes['oDadosEdificacoes']->sSituacao                         ,0,0,"L",0);

          $dtDemolicao = '';
          if ( !empty($oEdificacoes['oDadosEdificacoes']->dtDemolicao) ) {
            $dtDemolicao = db_formatar($oEdificacoes['oDadosEdificacoes']->dtDemolicao, 'd');
          }

          $pdf->cell(30, $iAlt, $dtDemolicao                                                          ,0,0,"L",0);
          $pdf->cell(30, $iAlt, $oEdificacoes['oDadosEdificacoes']->iAreaPricada                      ,0,0,"L",0);
          $pdf->cell(30, $iAlt, $oEdificacoes['oDadosEdificacoes']->sTipo                             ,0,1,"L",0);

          $pdf->cell(193, 1, ''                                                                       ,0,1,"L",0);

          $pdf->setfont('arial','B',$iFontDados);
          $pdf->cell(30, $iAlt, 'Cód. Log.'                                                           ,0,0,"L",0);
          $pdf->cell(120, $iAlt, 'Logradouro'                                                         ,0,1,"L",0);

          $pdf->setfont('arial','',$iFontDados);
          $pdf->cell(30, $iAlt, $oEdificacoes['oDadosEdificacoes']->iNumeroEnder                      ,0,0,"L",0);
          $pdf->cell(120, $iAlt, $oEdificacoes['oDadosEdificacoes']->sLogradouro                      ,0,1,"L",0);



          if ( true ) {

            $iTotalCaracteristicaEdificacao = 0;

            $pdf->cell(193, 1, ''                                                                     ,0,1,"L",0);
            $iAlturaEdificacao = $pdf->GetY() ;
            $pdf->setfont('arial','B',$iFontDados);
            $pdf->cell(20, $iAlt, 'Código'                                                            ,0,0,"L",0);
            $pdf->cell(173, $iAlt, 'Características'                                                  ,0,1,"L",0);

            foreach ( $oEdificacoes['aCaractEdificacao'] as $oCaractEdificacao ) {

              $iTotalCaracteristicaEdificacao ++;
              $pdf->setfont('arial','',$iFontDados);

              if ($iTotalCaracteristicaEdificacao <= 5) {

                $pdf->cell(20, $iAlt, $oCaractEdificacao->iCodigo                                       ,0,0,"L",0);
                $pdf->cell(173, $iAlt, $oCaractEdificacao->sDescricao                                   ,0,1,"L",0);

              } else if ($iTotalCaracteristicaEdificacao <= 10) {

                if ($iTotalCaracteristicaEdificacao == 6) {

                  $pdf->SetY($iAlturaEdificacao);
                  $pdf->SetX(110);
                  $pdf->setfont('arial','B',$iFontDados);
                  $pdf->cell(20, $iAlt, 'Código'                                                        ,0,0,"L",0);
                  $pdf->cell(173, $iAlt, 'Características'                                              ,0,1,"L",0);
                }
                $pdf->SetX(110);
                $pdf->setfont('arial','',$iFontDados);
                $pdf->cell(20, $iAlt, $oCaractEdificacao->iCodigo                                       ,0,0,"L",0);
                $pdf->cell(173, $iAlt, $oCaractEdificacao->sDescricao                                   ,0,1,"L",0);
              }

            }

            //$pdf->cell(193, $iAlt, ''                                                                     ,'T',1,"L",0);

            if ($iTotalCaracteristicaEdificacao > 10) {
              $pdf->cell(20, $iAlt, "A Edificação possui caracteristicas não exibidas."                ,0,1,"L",0);
            }
          }

          $pdf->cell(193, $iAlt, ''                                                                   ,0,1,"L",0);
        }
      } else if(count($aDadosEdificacoes) == 0 && $lImprimeNulo) {

        $this->imprimirCabecalhoFixo($pdf, $iAlt, true, 'Cadastro Edificações');
        $pdf->cell(193, $iAlt, 'Nenhuma edificação encontrada.'                                       ,0,1,"L",0);
        $pdf->cell(193, $iAlt, ''                                                                     ,'T',1,"L",0);
      }

    }

    /**
     * Imprime Dados Regime Imoveis
     */
    if ( true ) {

      if ( count($aDadosRegistroImovel) > 0 ) {

        $this->imprimirCabecalhoFixo($pdf, $iAlt, true, 'Dados Registro de Imóveis');

        foreach ( $aDadosRegistroImovel as $aRegistroImovel ) {

          foreach ( $aRegistroImovel as $oRegistroImovel ) {

            $pdf->setfont('arial','B',$iFontDados);
            $pdf->cell(193, $iAlt, 'Registro de Imóvel'                                               ,0,1,"L",0);

            $pdf->setfont('arial','',$iFontBic);
            $pdf->cell(193, $iAlt, $oRegistroImovel->iCodRegistro.'-'.$oRegistroImovel->sDescricao    ,0,1,"L",0);

            $pdf->setfont('arial','B',$iFontDados);
            $pdf->cell(30, $iAlt, 'Matrícula'                                                         ,0,0,"L",0);
            $pdf->cell(30, $iAlt, 'Quadra'                                                            ,0,0,"L",0);
            $pdf->cell(30, $iAlt, 'Lote'                                                              ,0,1,"L",0);

            $pdf->setfont('arial','',$iFontBic);
            $pdf->cell(30, $iAlt, $oRegistroImovel->iMatricRegime                                     ,0,0,"L",0);
            $pdf->cell(30, $iAlt, $oRegistroImovel->iQuadraRegime                                     ,0,0,"L",0);
            $pdf->cell(30, $iAlt, $oRegistroImovel->iLoteRegime                                       ,0,1,"L",0);

            $pdf->cell(193, $iAlt, ''                                                                 ,0,1,"L",0);
          }
        }
      } else if(count($aDadosRegistroImovel) == 0 && $lImprimeNulo) {

        $this->imprimirCabecalhoFixo($pdf, $iAlt, true, 'Dados Registro de Imóveis');
        $pdf->cell(193, $iAlt, 'Nenhum dado registro de imóvel encontrado.'                           ,0,1,"L",0);
        $pdf->cell(193, $iAlt, ''                                                                     ,'T',1,"L",0);
      }

    }

    /**
     * Isensões
     */
    if ( true ) {

      if ( count($aDadosIsencoes) > 0 ) {

        $this->imprimirCabecalhoFixo($pdf, $iAlt, true, 'Isenções');

        foreach ( $aDadosIsencoes as $aIsencoes ) {

          foreach ( $aIsencoes as $oIsencoes ) {

            $pdf->setfont('arial','B',$iFontDados);
            $pdf->cell(30, $iAlt, 'Código Isenção'                                                    ,0,0,"L",0);
            $pdf->cell(30, $iAlt, 'Processo'                                                          ,0,0,"L",0);
            $pdf->cell(60, $iAlt, 'Tipo Isenção'                                                      ,0,0,"L",0);
            $pdf->cell(20, $iAlt, 'Data Inicial'                                                      ,0,0,"L",0);
            $pdf->cell(23, $iAlt, 'Data Final'                                                        ,0,0,"L",0);
            $pdf->cell(30, $iAlt, '% Desconto IPTU'                                                   ,0,1,"L",0);

            $pdf->setfont('arial','',$iFontBic);
            $pdf->cell(30, $iAlt, $oIsencoes->iCodIsencao                                             ,0,0,"L",0);
            $pdf->cell(30, $iAlt, $oIsencoes->iCodProcesso                                            ,0,0,"L",0);
            $pdf->cell(60, $iAlt, $oIsencoes->sTipoIsencao                                            ,0,0,"L",0);

            $dtInicial = '';
            if ( !empty($oIsencoes->dtInicial) ) {
              $dtInicial = db_formatar($oIsencoes->dtInicial, 'd');
            }

            $pdf->cell(20, $iAlt, $dtInicial                                                          ,0,0,"L",0);

            $dtFinal = '';
            if ( !empty($oIsencoes->dtFinal) ) {
              $dtFinal = db_formatar($oIsencoes->dtFinal, 'd');
            }

            $pdf->cell(23, $iAlt, $dtFinal                                                            ,0,0,"L",0);
            $pdf->cell(30, $iAlt, $oIsencoes->nPercentual                                             ,0,1,"L",0);

            $pdf->setfont('arial','B',$iFontDados);
            $pdf->cell(30, $iAlt, 'Código'                                                            ,0,0,"L",0);
            $pdf->cell(80, $iAlt, 'Descrição'                                                         ,0,0,"L",0);
            $pdf->cell(83, $iAlt, '% Desconto Taxas'                                                  ,0,1,"L",0);

            if (isset($oIsencoes->aTaxas)) {

              foreach ( $oIsencoes->aTaxas as $oTaxas ) {

                $pdf->setfont('arial','',$iFontBic);
                $pdf->cell(30, $iAlt, $oTaxas->iCodigo                                                ,0,0,"L",0);
                $pdf->cell(80, $iAlt, $oTaxas->sDescricao                                             ,0,0,"L",0);
                $pdf->cell(83, $iAlt, $oTaxas->nDescontoTaxa                                          ,0,1,"L",0);
              }
            }

            $pdf->setfont('arial','B',$iFontDados);
            $pdf->cell(193, $iAlt, 'Observações'                                                      ,0,1,"L",0);

            $pdf->setfont('arial','',$iFontBic);
            $pdf->MultiCell(193, $iAlt, $oIsencoes->sObservacao,                                      0, 'L', 0, 0);

            $pdf->cell(193, $iAlt, ''                                                                 ,0,1,"L",0);
          }
        }
      } else if(count($aDadosIsencoes) == 0 && $lImprimeNulo) {

        $this->imprimirCabecalhoFixo($pdf, $iAlt, true, 'Isenções');
        $pdf->cell(193, $iAlt, 'Nenhuma isenção encontrada.'                                          ,0,1,"L",0);
        $pdf->cell(193, $iAlt, ''                                                                     ,'T',1,"L",0);
      }

    }

    /**
     * Imprime Averbações
     */
    if ( true ) {

      if ( count($aDadosAverbacoes) > 0 ) {

        $this->imprimirCabecalhoFixo($pdf, $iAlt, true, 'Averbações');

        foreach ( $aDadosAverbacoes as $aAverbacoes ) {

          $pdf->setfont('arial','B',$iFontDados);
          $pdf->cell(20, $iAlt, 'Código'                                                              ,0,0,"L",0);
          $pdf->cell(20, $iAlt, 'Data'                                                                ,0,0,"L",0);
          $pdf->cell(40, $iAlt, 'Tipo Averbação'                                                      ,0,0,"L",0);
          $pdf->cell(20, $iAlt, 'Data Tipo'                                                           ,0,0,"L",0);
          $pdf->cell(63, $iAlt, 'Observação'                                                          ,0,0,"L",0);
          $pdf->cell(30, $iAlt, 'Situação'                                                            ,0,1,"L",0);

          foreach ( $aAverbacoes as $oAverbacao ) {

            $pdf->setfont('arial','',$iFontBic);
            $pdf->cell(20, $iAlt, $oAverbacao->iCodigo                                                ,0,0,"L",0);

            $dtData = '';
            if ( !empty($oAverbacao->dtData) ) {
              $dtData = db_formatar($oAverbacao->dtData, 'd');
            }

            $dtDataTipo = '';
            if ( !empty($oAverbacao->dtDataTipo) ) {
              $dtDataTipo = db_formatar($oAverbacao->dtDataTipo, 'd');
            }

            $pdf->cell(20, $iAlt, $dtData                                                             ,0,0,"L",0);
            $pdf->cell(40, $iAlt, $oAverbacao->sTipo                                                  ,0,0,"L",0);
            $pdf->cell(20, $iAlt, $dtDataTipo                                                         ,0,0,"L",0);
            $pdf->cell(63, $iAlt, $oAverbacao->sObservacao                                            ,0,0,"L",0);
            $pdf->cell(30, $iAlt, $oAverbacao->sSituacao                                              ,0,1,"L",0);
          }

          $pdf->cell(193, $iAlt, ''                                                                   ,0,1,"L",0);
        }
      } else if(count($aDadosAverbacoes) == 0 && $lImprimeNulo) {

        $this->imprimirCabecalhoFixo($pdf, $iAlt, true, 'Averbações');
        $pdf->cell(193, $iAlt, 'Nenhuma averbação encontrada.'                                        ,0,1,"L",0);
        $pdf->cell(193, $iAlt, ''                                                                     ,'T',1,"L",0);
      }

    }

    /**
     * Imprime Cálculos
     */
    if ( true ) {

      $this->imprimirCabecalhoFixo($pdf, $iAlt, true, 'Dados para Cálculo');

      if ( count($aDadosCalculos) > 0 ) {

        foreach ( $aDadosCalculos as $aCalculos ) {

          foreach ( $aCalculos as $oCalculo ) {

            $pdf->setfont('arial','B',$iFontDados);

            $pdf->cell(193, $iAlt, "Cálculo: {$oCalculo->iAnoCalculo} - Numpre: {$oCalculo->iNumpre}"      ,0,1,"R",0);

            $pdf->cell(33, $iAlt, 'Área do lote m²'                                                     ,0,0,"L",0);
            $pdf->cell(32, $iAlt, 'Fração'                                                              ,0,0,"L",0);
            $pdf->cell(32, $iAlt, 'Valor m²'                                                            ,0,0,"L",0);
            $pdf->cell(32, $iAlt, 'Alíquota'                                                            ,0,0,"L",0);
            $pdf->cell(32, $iAlt, 'Valor Venal Terreno'                                                 ,0,0,"L",0);
            $pdf->cell(32, $iAlt, 'Testada'                                                             ,0,1,"L",0);

            $pdf->setfont('arial','',$iFontBic);
            $pdf->cell(33, $iAlt, $oCalculo->nAreaLote                                                  ,0,0,"L",0);
            $pdf->cell(32, $iAlt, $oCalculo->iFracao                                                    ,0,0,"L",0);
            $pdf->cell(32, $iAlt, trim(db_formatar($oCalculo->nValor, 'f'))                             ,0,0,"L",0);
            $pdf->cell(32, $iAlt, $oCalculo->nAliquota                                                  ,0,0,"L",0);
            $pdf->cell(32, $iAlt, trim(db_formatar($oCalculo->nValorVenalTerreno, 'f'))                 ,0,0,"L",0);
            $pdf->cell(32, $iAlt, $oCalculo->nTestada.'m'                                               ,0,1,"L",0);

            $pdf->setfont('arial','B',$iFontDados);
            $pdf->cell(33, $iAlt, ''                                                                    ,0,0,"L",0);
            $pdf->cell(32, $iAlt, 'Área Construída'                                                     ,0,0,"L",0);
            $pdf->cell(32, $iAlt, 'Ano'                                                                 ,0,0,"L",0);
            $pdf->cell(32, $iAlt, 'Pontuação'                                                           ,0,0,"L",0);
            $pdf->cell(64, $iAlt, 'Valor Venal Construído'                                              ,0,1,"L",0);

            $pdf->setfont('arial','',$iFontBic);
            $pdf->cell(33, $iAlt, $oCalculo->iCodigo                                                    ,0,0,"L",0);

            $iAreaConstr = '';
            if ( isset($oCalculo->iAreaConstr) ) {
              $iAreaConstr = $oCalculo->iAreaConstr;
            }

            $pdf->cell(32, $iAlt, $iAreaConstr                                                          ,0,0,"L",0);

            $iAnoConstr = '';
            if ( isset($oCalculo->iAnoConstr) ) {
              $iAnoConstr = $oCalculo->iAnoConstr;
            }

            $pdf->cell(32, $iAlt, $iAnoConstr                                                           ,0,0,"L",0);
            $pdf->cell(32, $iAlt, $oCalculo->iPontuacao                                                 ,0,0,"L",0);
            $pdf->cell(64, $iAlt, trim(db_formatar($oCalculo->nValorVenalConstr, 'f'))                  ,0,1,"L",0);

            $pdf->setfont('arial','B',$iFontDados);
            $pdf->cell(129, $iAlt, ''                                                                   ,0,0,"L",0);
            $pdf->cell(64, $iAlt, 'Valor Venal Total'                                                   ,0,1,"L",0);

            $pdf->setfont('arial','',$iFontBic);
            $pdf->cell(129, $iAlt, ''                                                                   ,0,0,"L",0);
            $pdf->cell(64, $iAlt, trim(db_formatar($oCalculo->nValorVenalTotal, 'f'))                   ,0,1,"L",0);

            $pdf->cell(193, $iAlt, ''                                                                   ,0,1,"L",0);

            $pdf->setfont('arial','B',$iFontDados);
            $pdf->cell(193, $iAlt, 'Valor Lançado'                                                      ,0,1,"C",0);

            $pdf->cell(15, $iAlt, 'Cód. Rec.'                                                           ,0,0,"L",0);
            $pdf->cell(44, $iAlt, 'Descrição Rec.'                                                      ,0,0,"L",0);
            $pdf->cell(15, $iAlt, 'Cód. Hist.'                                                          ,0,0,"L",0);
            $pdf->cell(44, $iAlt, 'Descrição Hist.'                                                     ,0,0,"L",0);
            $pdf->cell(25, $iAlt, 'Valor Calculado'                                                     ,0,0,"L",0);
            $pdf->cell(25, $iAlt, 'Valor Isento'                                                        ,0,0,"L",0);
            $pdf->cell(25, $iAlt, 'Total a Pagar'                                                       ,0,1,"L",0);

            if ( isset($oCalculo->aValorlancado) ) {

              if ( count($oCalculo->aValorlancado) > 0 ) {

                $nTotalCalculado  = 0;
                $nTotalIsento     = 0;
                $nTotalTotalPagar = 0;
                foreach ( $oCalculo->aValorlancado as $oValorLancado ) {

                  $pdf->setfont('arial','',$iFontBic);
                  $pdf->cell(15, $iAlt, $oValorLancado->iCodRec                                         ,0,0,"L",0);
                  $pdf->cell(44, $iAlt, $oValorLancado->sDescricao                                      ,0,0,"L",0);
                  $pdf->cell(15, $iAlt, $oValorLancado->iCodHist                                        ,0,0,"L",0);
                  $pdf->cell(44, $iAlt, $oValorLancado->sDescrHist                                      ,0,0,"L",0);
                  $pdf->cell(25, $iAlt, trim(db_formatar($oValorLancado->nValorCalculado, 'f'))         ,0,0,"L",0);
                  $pdf->cell(25, $iAlt, trim(db_formatar($oValorLancado->nValorIsento, 'f'))            ,0,0,"L",0);
                  $pdf->cell(25, $iAlt, trim(db_formatar($oValorLancado->nValorTotalPagar, 'f'))        ,0,1,"L",0);

                  $nTotalCalculado  += $oValorLancado->nValorCalculado;
                  $nTotalIsento     += $oValorLancado->nValorIsento;
                  $nTotalTotalPagar += $oValorLancado->nValorTotalPagar;
                }

                if (count($oCalculo->aValorlancado) > 1) {

                  $pdf->setfont('arial','B',$iFontDados);
                  $pdf->cell(118, $iAlt, 'Total:'                                                         ,0,0,"R",0);

                  $pdf->setfont('arial','',$iFontBic);
                  $pdf->cell(25, $iAlt, trim(db_formatar($nTotalCalculado, 'f'))                          ,0,0,"L",0);
                  $pdf->cell(25, $iAlt, trim(db_formatar($nTotalIsento, 'f'))                             ,0,0,"L",0);
                  $pdf->cell(25, $iAlt, trim(db_formatar($nTotalTotalPagar, 'f'))                         ,0,1,"L",0);
                }

              } else {

                $pdf->setfont('arial','',$iFontBic);
                $pdf->cell(193, $iAlt, 'Nao possui valores disponiveis'                                 ,0,1,"L",0);
              }

            }

            $pdf->cell(193, $iAlt, ''                                                                   ,0,1,"L",0);

            $pdf->setfont('arial','B',$iFontDados);
            $pdf->cell(193, $iAlt, 'Cálculo das Edificações'                                            ,0,1,"C",0);

            $pdf->cell(32, $iAlt, 'Nº.'                                                                 ,0,0,"L",0);
            $pdf->cell(32, $iAlt, 'Área m²'                                                             ,0,0,"L",0);
            $pdf->cell(32, $iAlt, 'Exercício'                                                           ,0,0,"L",0);
            $pdf->cell(32, $iAlt, 'Valor m²'                                                            ,0,0,"L",0);
            $pdf->cell(32, $iAlt, 'Pontuação'                                                           ,0,0,"L",0);
            $pdf->cell(33, $iAlt, 'Valor Venal'                                                         ,0,1,"L",0);

            if (isset($oCalculo->aConstrucoes)) {

              if ( count($oCalculo->aConstrucoes) > 0 ) {

                foreach ( $oCalculo->aConstrucoes as $oConstrucoes ) {

                  $pdf->setfont('arial','',$iFontBic);
                  $pdf->cell(32, $iAlt, $oConstrucoes->iCodConstrucao                                   ,0,0,"L",0);
                  $pdf->cell(32, $iAlt, $oConstrucoes->nAreaConstr                                      ,0,0,"L",0);
                  $pdf->cell(32, $iAlt, $oConstrucoes->iAnoExercicio                                    ,0,0,"L",0);
                  $pdf->cell(32, $iAlt, trim(db_formatar($oConstrucoes->nValor, 'f'))                   ,0,0,"L",0);
                  $pdf->cell(32, $iAlt, $oConstrucoes->iPontuacao                                       ,0,0,"L",0);
                  $pdf->cell(33, $iAlt, trim(db_formatar($oConstrucoes->nValorVenal, 'f'))              ,0,1,"L",0);
                }
              } else {

                $pdf->setfont('arial','',$iFontBic);
                $pdf->cell(193, $iAlt, 'Nao possui construções'                                         ,0,1,"L",0);
              }
            }

            $pdf->cell(193, $iAlt, ''                                                                   ,0,1,"L",0);
            $pdf->cell(193, $iAlt, ''                                                                   ,'T',1,"L",0);
          }

        }
      } else {
        $pdf->cell(193, $iAlt, 'Nenhum cálculo encontrado.'                                           ,0,1,"L",0);
        $pdf->cell(193, $iAlt, ''                                                                     ,0,1,"L",0);
        $pdf->cell(193, $iAlt, ''                                                                     ,'T',1,"L",0);
      }

    }

    /**
     * Imprime Outros Dados
     */
    if ( true ) {

      if ( count($aDadosOutrosDados) > 0 ) {

        $this->imprimirCabecalhoFixo($pdf, $iAlt, true, 'Outros Dados');

        foreach ( $aDadosOutrosDados as $oOutrosDados ) {

          $pdf->setfont('arial','B',$iFontDados);
          $pdf->cell(193, $iAlt, 'Observações'                                                        ,0,1,"L",0);

          $pdf->setfont('arial','',$iFontBic);
          $pdf->MultiCell(193, $iAlt, $oOutrosDados->sObservacao, 0, 'L', 0, 0);

          $pdf->cell(193, $iAlt, ''                                                                   ,0,1,"L",0);
        }
      } else if(count($aDadosOutrosDados) == 0 && $lImprimeNulo) {

        $this->imprimirCabecalhoFixo($pdf, $iAlt, true, 'Outros Dados');
        $pdf->cell(193, $iAlt, 'Nenhum outro dado encontrado.'                                        ,0  ,1, "L", 0);
        $pdf->cell(193, $iAlt, ''                                                                     ,'T',1, "L", 0);
      }


    }

    $pdf->Output($this->sNomeArquivo, false, true);

    return $this->salvarArquivo();
  }

  public function salvarArquivo() {

    $iOidBic     = DBLargeObject::criaOID(true);
    $lSalvou     = DBLargeObject::escrita($this->sNomeArquivo, $iOidBic);

    $sInsertBic  = "insert into recadastroimobiliarioimoveisbic                            ";
    $sInsertBic .= "       (ie29_sequencial,                                               ";
    $sInsertBic .= "        ie29_iptubase,                                                 ";
    $sInsertBic .= "        ie29_arquivobic)                                               ";
    $sInsertBic .= "values (nextval('recadastroimobiliarioimoveisbic_ie29_sequencial_seq'),";
    $sInsertBic .= "        {$this->iMatricula},                                           ";
    $sInsertBic .= "        {$iOidBic})                                                    ";

    if (!db_query($sInsertBic) || !$lSalvou) {

      $sMensagem = "Erro ao salvar arquivo de BIC para a matrícula {$this->iMatricula}.";

      RecadastroImobiliarioImoveisArquivo::$oLog->escreverLog($sMensagem, DBLog::LOG_ERROR);

      throw new Exception($sMensagem);

    }

    if ( file_exists($this->sNomeArquivo) ) {
      unlink($this->sNomeArquivo);
    }

    return true;
  }

  /**
   * Impime cabecalhos fixos do relatorio
   *
   * @param Object  type $pdf
   * @param Integer type $iAlt
   * @param Boolean type $lImprime
   * @param String  type $sDescricao
   */
  public function imprimirCabecalhoFixo($pdf, $iAlt, $lImprime, $sDescricao) {

    if ( $pdf->gety() > $pdf->h - 10 || $lImprime ) {

      if ( !$lImprime ) {
        $pdf->addpage("P");
      }

      $pdf->setfont('arial','B',7);
      $pdf->cell(117, $iAlt, ''                                                                   ,'B',0,"C",0);
      $pdf->cell(76, $iAlt, $sDescricao                                                             ,1,1,"R",1);
    }

  }

}
?>