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

require_once ("libs/db_stdlibwebseller.php");
require_once ("fpdf151/scpdf.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_libdocumento.php");
require_once ("libs/db_libparagrafo.php");
require_once ('model/educacao/ArredondamentoNota.model.php');

$resultedu          = eduparametros(db_getsession("DB_coddepto"));
$oDaoEduParametros  = new cl_edu_parametros();
$oDaoHistorico      = new cl_historico();
$oDaoAluno          = new cl_aluno();
$oDaoEscola         = new cl_escola();
$oDaoTelEscola      = new cl_telefoneescola();
$oDaoAprovConselho  = new cl_aprovconselho();
$oDaoTrocaSerie     = new cl_trocaserie();
$oDaoEduRelatModel  = new cl_edu_relatmodel();
$oDaoCursoAto       = new cl_cursoato();
$oDaoDbConfig       = new cl_db_config();
$oDaoDisc           = new cl_histmpsdisc();
$oDaoDiscFora       = new cl_histmpsdiscfora();
$oDaoSerie          = new cl_historicomps();
$oDaoSerieFora      = new cl_historicompsfora();
$oDaoAtoLegal       = new cl_atolegal();

$sCamposTelEscola   = " ed26_i_ddd, ed26_i_numero, ed26_i_ramal ";
$sWhereTelEscola    = " ed26_i_escola = $iEscola LIMIT 1 ";
$sSqlTelEscola      = $oDaoTelEscola->sql_query("", $sCamposTelEscola, "", $sWhereTelEscola);
$rsTelEscola        = $oDaoTelEscola->sql_record($sSqlTelEscola);

if ($oDaoTelEscola->numrows > 0) {

  $oDadosTelEscola = db_utils::fieldsmemory($rsTelEscola, 0);

  $sTelefoneEscola = "- Fone: ($oDadosTelEscola->ed26_i_ddd) $oDadosTelEscola->ed26_i_numero ".
                              ($oDadosTelEscola->ed26_i_ramal != "" ? " Ramal: $oDadosTelEscola->ed26_i_ramal":"");

} else {
  $sTelefoneEscola = "";
}

$sCamposEscola      = " ed18_c_nome as nome_escola, j14_nome as rua_escola, ed18_c_cep as cep_escola, ed18_codigoreferencia, ";
$sCamposEscola     .= " ed18_i_numero as num_escola, ed261_c_nome as mun_escola, ed260_c_sigla as uf_escola";
$sSqlEscola         = $oDaoEscola->sql_query("", $sCamposEscola, "", "ed18_i_codigo = $iEscola");
$rsEscola           = $oDaoEscola->sql_record($sSqlEscola);
$oDadosEscola       = db_utils::fieldsmemory($rsEscola, 0);

$sNomeEscola = $oDadosEscola->nome_escola;

if ( $oDadosEscola->ed18_codigoreferencia != null ) {
  $sNomeEscola = "{$oDadosEscola->ed18_codigoreferencia} - {$sNomeEscola}";
}

$sCamposRelatModel  = " ed217_t_cabecalho, ed217_t_rodape, ed217_t_obs, ";
$sCamposRelatModel .= " ed217_orientacao, case when ed217_gradenotas = 1 then '6' ";
$sCamposRelatModel .= " when ed217_gradenotas = 2 then '8' when ed217_gradenotas = 3 ";
$sCamposRelatModel .= " then '10' when ed217_gradenotas = 4 then '12' end as gradenota , ";
$sCamposRelatModel .= " case when ed217_gradeetapas = 1 then '6' when ed217_gradeetapas = 2 then '8' ";
$sCamposRelatModel .= " when ed217_gradeetapas = 3 then '10' when ed217_gradeetapas = 4 ";
$sCamposRelatModel .= " then '12' end as gradeetapa, ";
$sCamposRelatModel .= " case when ed217_observacao = 1 then '6' when ed217_observacao = 2 then '8' ";
$sCamposRelatModel .= " when ed217_observacao = 3 then '10' when ed217_observacao = 4 ";
$sCamposRelatModel .= " then '12' end as observacao";
$sSqlRelatModel     = $oDaoEduRelatModel->sql_query("", $sCamposRelatModel, "", "ed217_i_codigo = $iTipoRelatorio");
$rsRelatModel       = $oDaoEduRelatModel->sql_record($sSqlRelatModel);

if ($oDaoEduRelatModel->numrows > 0) {
  $oDadosRelatModel = db_utils::fieldsmemory($rsRelatModel, 0);
}

if ($sDiretor != "") {

  $aDiretor       = explode("-", $sDiretor);
  $sNomeDiretor   = $aDiretor[1];
  $sFuncaoDiretor = $aDiretor[0].(trim($aDiretor[2]) != "" ? " ($aDiretor[2])":"");

} else {

  $sNomeDiretor   = "Diretor(a)";
  $sFuncaoDiretor = "";

}

if ($sSecretario != "") {

  $aSecretario       = explode("-", $sSecretario);
  $sNomeSecretario   = $aSecretario[1];
  $sFuncaoSecretario = $aSecretario[0].(trim($aSecretario[2]) != "" ? " ($aSecretario[2])":"");

} else {

  $sNomeSecretario   = "Secretário(a)";
  $sFuncaoSecretario = "";

}

$sCamposAluno  = " aluno.*,  censoufident.ed260_c_sigla as ufident, censoufnat.ed260_c_sigla as ufnat,  ";
$sCamposAluno .= " censoufcert.ed260_c_sigla as ufcert, censoufend.ed260_c_sigla as ufend, ";
$sCamposAluno .= " censomunicnat.ed261_c_nome as municnat, censomuniccert.ed261_c_nome as municcert, ";
$sCamposAluno .= " censomunicend.ed261_c_nome as municend, censoorgemissrg.ed132_c_descr as orgemissrg";
$sSqlAluno     = $oDaoAluno->sql_query("", "$sCamposAluno", "ed47_v_nome", " ed47_i_codigo in ($alunos)");
$rsAluno       = $oDaoAluno->sql_record($sSqlAluno);

if ($oDaoAluno->numrows == 0) {

  echo " <table width='100%'>";
  echo "  <tr>";
  echo "   <td align='center'>";
  echo "    <font color='#FF0000' face='arial'>";
  echo "     <b>Nenhum histórico para o(s) aluno(s) selecionados<br>";
  echo "     <input type='button' value='Fechar' onclick='window.close()'></b>";
  echo "    </font>";
  echo "   </td>";
  echo "  </tr>";
  echo " </table>";
  exit;

}

$oFpdf = new fpdf();
$oFpdf->Open();
$oFpdf->AliasNbPages();
$oFpdf->SetAutoPageBreak(false);
for ($iContAluno = 0; $iContAluno < $oDaoAluno->numrows; $iContAluno++) {

  $oDadosAluno   = db_utils::fieldsmemory($rsAluno, $iContAluno);

  $sCamposHist   = "*";
  $sWhereHist    = " ed61_i_aluno in ($oDadosAluno->ed47_i_codigo)";
  $sOrderHist    = " ed47_v_nome ";
  $sSqlHist      = $oDaoHistorico->sql_query("", $sCamposHist, $sOrderHist, $sWhereHist);
  $rsHist        = $oDaoHistorico->sql_record($sSqlHist);
  $iLinhasHist   = $oDaoHistorico->numrows;
  $iCodigoHist   = "";
  $iCodigoCursos = "";
  $sCurso        = "";
  $sSep                 = "";
  $sObservacaoHistorico = '';
  for ($iContHist = 0; $iContHist < $iLinhasHist; $iContHist++) {

    $oDadosHist     = db_utils::fieldsmemory($rsHist, $iContHist);
    $iCodigoHist   .= $sSep.$oDadosHist->ed61_i_codigo;
    $iCodigoCursos .= $sSep.$oDadosHist->ed61_i_curso;
    $sCurso        = $oDadosHist->ed29_c_descr;
    $sObservacaoHistorico .= "{$oDadosHist->ed61_t_obs}\n";
    $sSep           = ", ";

  }

  $sCamposAno  = "max(ed62_i_anoref) as ultimoanorede, max(ed99_i_anoref) as ultimoanofora";
  $sSqlAnoHist = $oDaoHistorico->sql_query_historico("", $sCamposAno, "", " ed61_i_codigo in ($iCodigoHist)");
  $rsAnoHist   = $oDaoHistorico->sql_record($sSqlAnoHist);
  $iLinhasAno  = $oDaoHistorico->numrows;

  if ($iLinhasAno > 0) {

    $oDadosAno = db_utils::fieldsmemory($rsAnoHist, 0);
    if (trim($oDadosAno->ultimoanorede) == "" && trim($oDadosAno->ultimoanofora) == "") {
      $iUltimoAno = date("Y", db_getsession("DB_datausu"));
    } else if (trim($oDadosAno->ultimoanorede) == "" && trim($oDadosAno->ultimoanofora) != "") {
      $iUltimoAno = $oDadosAno->ultimoanofora;
    } else if (trim($oDadosAno->ultimoanorede) != "" && trim($oDadosAno->ultimoanofora) == "") {
      $iUltimoAno = $oDadosAno->ultimoanorede;
    } else if (trim($oDadosAno->ultimoanorede) != "" && trim($oDadosAno->ultimoanofora) != "") {

      if (trim($oDadosAno->ultimoanorede) > trim($oDadosAno->ultimoanofora)) {
        $iUltimoAno = $oDadosAno->ultimoanorede;
      } else {
        $iUltimoAno = $oDadosAno->ultimoanofora;
      }

    }

  } else {
    $iUltimoAno = date("Y", db_getsession("DB_datausu"));;
  }

  $sWhereCarga   = " ed61_i_codigo in ($iCodigoHist) AND ed62_c_resultadofinal in('A', 'D') AND ed62_i_anoref <= $iUltimoAno ";
  $sSqlCargaHist = $oDaoHistorico->sql_query_historicomps("", "sum(ed62_i_qtdch) as chtotalrede", "", $sWhereCarga);
  $rsCargaHist   = $oDaoHistorico->sql_record($sSqlCargaHist);
  $iLinhasCarga  = $oDaoHistorico->numrows;
  $oDadosCarga   = db_utils::fieldsmemory($rsCargaHist, 0);

  //Sql Carga Fora
  $sCamposCargaFora = " sum(ed99_i_qtdch) as chtotalfora ";
  $sWhereCargaFora  = " ed61_i_codigo in ($iCodigoHist) AND ed99_c_resultadofinal in('A', 'D') AND ed99_i_anoref <= $iUltimoAno ";
  $sSqlCargaFora    = $oDaoHistorico->sql_query_historicompsfora("", $sCamposCargaFora, "", $sWhereCargaFora);
  $rsCargaFora      = $oDaoHistorico->sql_record($sSqlCargaFora);
  $iLinhasCargaFora = $oDaoHistorico->numrows;
  $oDadosCargaFora  = db_utils::fieldsmemory($rsCargaFora, 0);


  if ($iTipoRegistro == "A") { //registros aprovados

    $sCondicaoHistMps     = " AND ed62_i_anoref <= {$iUltimoAno} AND ed62_c_resultadofinal in('A', 'D')";
    $sCondicaoHistMpsFora = " AND ed99_i_anoref <= {$iUltimoAno} AND ed99_c_resultadofinal in('A', 'D')";

  } else if ($iTipoRegistro == "U") { //somente o ultimo registro

    $sCondicaoHistMps      = " AND ed62_i_anoref <= {$iUltimoAno} ";
    $sCondicaoHistMps     .= " AND (ed62_c_resultadofinal in('A', 'D') OR ed62_i_anoref = {$iUltimoAno})";
    $sCondicaoHistMpsFora  = " AND ed99_i_anoref <= {$iUltimoAno} ";
    $sCondicaoHistMpsFora .= "AND (ed99_c_resultadofinal in('A', 'D') OR ed99_i_anoref = {$iUltimoAno})";

  }

  if ($sDisposicao == 1) {

    $sConvencoes  = " convenções: ch = carga horária rf = resultado final pl = período letivo";
    $sConvencoes .= " esc = escola dl = dias letivos aprov. = aproveitamento";
    $oFpdf->setfillcolor(223);
    $oFpdf->addpage('p');
    $oFpdf->image('imagens/brasaohistoricoescolar.jpeg', 10, 10, 25, 25, '');
    $oFpdf->setfont('arial', 'b', 6);
    $oFpdf->setX(35);
    $oFpdf->multicell(80, 4, $oDadosRelatModel->ed217_t_cabecalho, 0, "C", 0, 0);
    //$oFpdf->setxy(125, 10);

    $sCamposMantenedora = " nomeinst ";
    $sWhereMantenedora  = " codigo = ".db_getsession("DB_instit");
    $sSqlMantenedora    = $oDaoDbConfig->sql_query_file("", $sCamposMantenedora,"", $sWhereMantenedora);
    $rsMantenedora      = $oDaoDbConfig->sql_record($sSqlMantenedora);

    //sql ato
    $sCamposCursoAto = " ed05_c_finalidade, ed05_c_numero, ed05_d_vigora, ed05_d_publicado,ed29_c_descr ";
    $sWhereCursoAto  = " ed29_i_codigo in ($iCodigoCursos) and ed18_i_codigo = $iEscola and ed05_i_aparecehistorico = 1 ";
    $sSqlCursoAto    = $oDaoCursoAto->sql_query("", $sCamposCursoAto, "", $sWhereCursoAto);
    $rsCursoAto      = $oDaoCursoAto->sql_record($sSqlCursoAto);

    if ($oDaoCursoAto->numrows > 0) {

      $mAtoEscola = "";
      $sSepEscola = "";

      for ($iContAto = 0; $iContAto < $oDaoCursoAto->numrows; $iContAto++) {

        $oDadosAto   = db_utils::fieldsmemory($rsCursoAto, $iContAto);
        $mAtoEscola .= $sSepEscola."$oDadosAto->ed05_c_finalidade n° $oDadosAto->ed05_c_numero data: ".
                       db_formatar($oDadosAto->ed05_d_vigora, 'd');
        $mAtoEscola .= " d.o.: ".db_formatar($oDadosAto->ed05_d_publicado, 'd');
        $sSepEscola  = "\n";

      }

    } else {
      $mAtoEscola = "";
    }

    $mCabecalhoEscola  = "$sNomeEscola\nMantenedora: ";
    $mCabecalhoEscola .=  $oDadosMantenedora = db_utils::fieldsmemory($rsMantenedora, 0)->nomeinst;
    $mCabecalhoEscola .= " \nEndereço: $oDadosEscola->rua_escola , ";
    $mCabecalhoEscola .= " $oDadosEscola->num_escola \nCEP: $oDadosEscola->cep_escola - $oDadosEscola->mun_escola / ";
    $mCabecalhoEscola .=   $oDadosEscola->uf_escola." ".$sTelefoneEscola;
    $oFpdf->setXY(115, 10);
    $oFpdf->setfont('arial', '', 6);
    $oFpdf->multicell(110, 3, $mCabecalhoEscola, 0, "L", 0, 0);
    $oFpdf->setX(125);
    $oFpdf->multicell(110, 2, "", "", "L", 0, 0);
    $oFpdf->setX(115);
    $oFpdf->setfont('arial', '', 6);
    $oFpdf->multicell(100, 3, $mAtoEscola, "", "L", 0, 0);
    $oFpdf->setXY(100,36);
    $oFpdf->setfont('arial', 'b', 7);
    $oFpdf->multicell(120, 2, "Histórico Escolar", "", "L", 0, 0);
    $oFpdf->setXY(80,40);
    $oFpdf->multicell(120, 2, $sCurso." Lei 9.394/96", "", "L", 0, 0);
    $oFpdf->sety(45);
    $oFpdf->setfont('arial', '', 7);
    $oFpdf->cell(10, 4, "Nome:", 0, 0, "L", 0);
    $oFpdf->setfont('arial', 'b', 9);
    $oFpdf->cell(93, 4, $oDadosAluno->ed47_v_nome, 0, 1, "L", 0);
    $oFpdf->setfont('arial', '', 7);
    $anacionalidade = array("1" => "brasileiro",
                            "2" => "estrangeiro",
                            "3" => "brasileiro nascido no exterior ou naturalizado"
                           );
    $oFpdf->cell(18, 4, "Nacionalidade:", 0, 0, "L", 0);
    $oFpdf->cell(43, 4, $anacionalidade[$oDadosAluno->ed47_i_nacion], 0, 0, "L", 0);
    $oFpdf->cell(13, 4, "Identidade: ", 0, 0, "L", 0);
    $oFpdf->cell(5, 4, $oDadosAluno->ed47_v_ident, 0, 1, "L", 0);


    if (trim($oDadosAluno->ed47_v_pai) == "" && trim($oDadosAluno->ed47_v_mae) == "") {
      $sFiliacao = "";
    } else if (trim($oDadosAluno->ed47_v_pai) == "" && trim($oDadosAluno->ed47_v_mae) != "") {
      $sFiliacao = $oDadosAluno->ed47_v_mae;
    } else if (trim($oDadosAluno->ed47_v_pai) != "" && trim($oDadosAluno->ed47_v_mae) == "") {
      $sFiliacao = $oDadosAluno->ed47_v_pai;
    } else if (trim($oDadosAluno->ed47_v_pai) !== "" && trim($oDadosAluno->ed47_v_mae) != "") {
      $sFiliacao = "$oDadosAluno->ed47_v_pai e de $oDadosAluno->ed47_v_mae";
    }
    $oFpdf->cell(13, 4, "Filho(a) de: ", 0, 0, "l", 0);
    $oFpdf->cell(90, 4, $sFiliacao, 0, 1, "L", 0);
    $oFpdf->cell(18, 4, "Nascido(a) em:", 0, 0, "L", 0);
    $oFpdf->cell(80, 4, db_formatar($oDadosAluno->ed47_d_nasc, 'd').
                        " em  $oDadosAluno->municnat / $oDadosAluno->ufnat", 0, 0, "L", 0
                );
    $oFpdf->cell(11, 4, "ID INEP: ", 0, 0, "L", 0);
    $oFpdf->cell(5, 4, $oDadosAluno->ed47_c_codigoinep, 0, 1, "L", 0);
    $oFpdf->setY(65);


  } else {

    //quando for sDisposicao == 2
    //$sConvencoes  = " convenções: ch = carga horária rf = resultado final pl = período letivo";
    //$sConvencoes .= " esc = escola dl = dias letivos aprov. = aproveitamento";
    $oFpdf->setfillcolor(223);
    $oFpdf->addpage('p');
    $oFpdf->image('imagens/brasaohistoricoescolar.jpeg', 10, 10, 25, 25, '');
    $oFpdf->setfont('arial', 'b', 6);
    $oFpdf->setX(60);
    $oFpdf->multicell(100, 4, $oDadosRelatModel->ed217_t_cabecalho, 0, "C", 0, 0);

    $sCamposMantenedora = " nomeinst ";
    $sWhereMantenedora  = " codigo = ".db_getsession("DB_instit");
    $sSqlMantenedora    = $oDaoDbConfig->sql_query_file("", $sCamposMantenedora,"", $sWhereMantenedora);
    $rsMantenedora      = $oDaoDbConfig->sql_record($sSqlMantenedora);

    //sql ato
    $sCamposCursoAto = " ed05_c_finalidade, ed05_c_numero, ed05_d_vigora, ed05_d_publicado,ed29_c_descr ";
    $sWhereCursoAto  = " ed29_i_codigo in ($iCodigoCursos) and ed18_i_codigo = $iEscola and ed05_i_aparecehistorico = 1 ";
    $sSqlCursoAto    = $oDaoCursoAto->sql_query("", $sCamposCursoAto, "", $sWhereCursoAto);
    $rsCursoAto      = $oDaoCursoAto->sql_record($sSqlCursoAto);

    $mAtoEscola = "";
    if ($oDaoCursoAto->numrows > 0 and false ) {

      $mAtoEscola = "";
      $sSepEscola = "";

      for ($iContAto = 0; $iContAto < $oDaoCursoAto->numrows; $iContAto++) {

        $oDadosAto   = db_utils::fieldsmemory($rsCursoAto, $iContAto);
        $mAtoEscola .= $sSepEscola."$oDadosAto->ed05_c_finalidade n° $oDadosAto->ed05_c_numero data: ".
                       db_formatar($oDadosAto->ed05_d_vigora, 'd');
        $mAtoEscola .= " d.o.: ".db_formatar($oDadosAto->ed05_d_publicado, 'd');
        $sSepEscola  = "\n";

      }

    }

    $sCamposAtoLegal  = " ed05_i_codigo, ed05_c_numero, ed05_d_aprovado, ed05_d_publicado,";
    $sCamposAtoLegal .= " ed05_c_finalidade, ed83_c_descr as dl_tipo, case when ed05_c_competencia='F' ";
    $sCamposAtoLegal .= " then 'FEDERAL' when ed05_c_competencia='E' then 'ESTADUAL' ";
    $sCamposAtoLegal .= " else 'MUNICIPAL' end as ed05_c_competencia, ed05_i_ano ";
    $sWhereAtoLegal   = " ed19_i_escola = $iEscola and ed05_i_aparecehistorico = 1 ";
    $sSqlAtoLegal     = $oDaoAtoLegal->sql_query("", $sCamposAtoLegal, "ed05_i_codigo", $sWhereAtoLegal);
    $rsAtoLegal       = $oDaoAtoLegal->sql_record($sSqlAtoLegal);

    if ($oDaoAtoLegal->numrows > 0 ) {

      $sSepEscola = "";

      for ($iContAtoLegal = 0; $iContAtoLegal < $oDaoAtoLegal->numrows; $iContAtoLegal++) {

        $oDadosAtoLegal = db_utils::fieldsmemory($rsAtoLegal, $iContAtoLegal);
        $mAtoEscola    .= $sSepEscola."$oDadosAtoLegal->ed05_c_finalidade $oDadosAtoLegal->ed05_c_numero - Data: ";
        $mAtoEscola    .= db_formatar($oDadosAtoLegal->ed05_d_aprovado, 'd') . " - D.O.: ";
        $mAtoEscola    .= db_formatar($oDadosAtoLegal->ed05_d_publicado,'d');
        $sSepEscola  = "\n";

      }

    }

    $mCabecalhoEscola  = "Mantenedora: ";
    $mCabecalhoEscola .=  $oDadosMantenedora = db_utils::fieldsmemory($rsMantenedora, 0)->nomeinst;
    $oFpdf->setfont('arial', 'b', 8);
    $oFpdf->cell(0, 5, strtoupper($sNomeEscola), 0, 1, "C", 0);
    $oFpdf->setfont('arial', 'b', 7);
    $oFpdf->cell(0, 4, "HISTÓRICO ESCOLAR", 0, 1, "C", 0);
//    $oFpdf->cell(0, 4, $sCurso." Lei 9.394/96 e Lei 11.274/06", 0, 1, "C");
    $oFpdf->ln();
    $oFpdf->setfont('arial', '', 6);
    $oFpdf->multicell(110, 3, $mCabecalhoEscola, 0, "L", 0, 0);
    $oFpdf->setfont('arial', '', 6);
    if ($mAtoEscola != "") {
      $oFpdf->multicell(110, 4, $mAtoEscola, "", "L", 0, 0);
    }
    $oFpdf->cell(105, 5, "Endereço: ".$oDadosEscola->rua_escola ,0, 0, "L", 0);
    $oFpdf->cell(160, 5, "CEP: ".$oDadosEscola->cep_escola." - ".
                         $oDadosEscola->mun_escola." / ".
                         $oDadosEscola->uf_escola." ".$sTelefoneEscola ,0, 1, "L", 0);
    if ($mAtoEscola != "") {
//      $oFpdf->ln();
    }
    $oFpdf->setfont('arial', '', 6);
    $oFpdf->cell(10, 4, "Nome:", 0, 0, "L", 0);
    $oFpdf->setfont('arial', 'b', 8);
    $oFpdf->cell(95, 4, $oDadosAluno->ed47_i_codigo . " - " . $oDadosAluno->ed47_v_nome, 0, 0, "L", 0);

    $oFpdf->ln();
    $oFpdf->setfont('arial', '', 6);
    $oFpdf->cell(12, 4, "Filho(a) de:", 0, 0, "l", 0);

    if (trim($oDadosAluno->ed47_v_pai) == "" && trim($oDadosAluno->ed47_v_mae) == "") {
      $sfiliacao = "";
    } else if (trim($oDadosAluno->ed47_v_pai) == "" && trim($oDadosAluno->ed47_v_mae) != "") {
      $sfiliacao = $oDadosAluno->ed47_v_mae;
    } else if (trim($oDadosAluno->ed47_v_pai) != "" && trim($oDadosAluno->ed47_v_mae) == "") {
      $sfiliacao = $oDadosAluno->ed47_v_pai;
    } else if (trim($oDadosAluno->ed47_v_pai) !== "" && trim($oDadosAluno->ed47_v_mae) != "") {
      $sfiliacao = "$oDadosAluno->ed47_v_pai e de $oDadosAluno->ed47_v_mae";
    }

    $oFpdf->cell(93, 4, $sfiliacao, 0, 0, "L", 0);
    $oFpdf->ln();



    $anacionalidade = array("1"=>"brasileiro", "2"=>"estrangeiro", "3"=>"brasileiro nascido no exterior ou naturalizado");
    $oFpdf->cell(15, 4, "Nacionalidade:", 0, 0, "L", 0);
    $oFpdf->cell(25, 4, $anacionalidade[$oDadosAluno->ed47_i_nacion], 0, 0, "L", 0);
    $oFpdf->cell(12, 4, "Identidade: ", 0, 0, "L", 0);
    $oFpdf->cell(30, 4, $oDadosAluno->ed47_v_ident, 0, 0, "L", 0);



    $oFpdf->cell(15, 4, "Nascido(a) em:", 0, 0, "L", 0);
    $oFpdf->cell(80, 4, db_formatar($oDadosAluno->ed47_d_nasc, 'd').
                       " em  $oDadosAluno->municnat / $oDadosAluno->ufnat", 0, 0, "L", 0
                );
    $oFpdf->cell(12, 4, "ID INEP: ", 0, 0, "L", 0);
    $oFpdf->cell(5, 4, $oDadosAluno->ed47_c_codigoinep, 0, 0, "L", 0);
    $oFpdf->ln();

  }

  $oFpdf->setfont('arial', 'b', $oDadosRelatModel->gradenota);

  $sCamposEtapa = " distinct ed11_i_sequencia,ed11_i_codigo,ed61_i_curso,ed11_c_abrev, ano";
  $sWhereEtapa  = " ed61_i_aluno in ($oDadosAluno->ed47_i_codigo)";
  $sWhereEtapa .=  str_replace(array("ed62_i_anoref", "ed62_c_resultadofinal"),
                               array('ano', 'resfinal'),
                               $sCondicaoHistMps
                              );

   $sWhereEtapa .=  str_replace(array("ed99_i_anoref", "ed99_c_resultadofinal"),
                               array('ano', 'resfinal'),
                               $sCondicaoHistMpsFora
                              );

  /**
   *
   */
  $sSqlEtapa    = $oDaoHistorico->sql_query_union("", $sCamposEtapa, "ano, ed11_i_sequencia", $sWhereEtapa);
  $rsEtapa      = $oDaoHistorico->sql_record($sSqlEtapa);
  $iLinhasEtapa = $oDaoHistorico->numrows;
  $iQuebraEtapa = 0;
  $lCor         = false;

  $iQuebraEtapa = 0;
  $iNumColunas  = 9;//numero máximo de colunas de etapas

  $oFpdf->cell(60, 4, "Componentes Curriculares", 1, 0, "C", 0);

  $aOrdemEtapa     = array();
  for ($iContEtapa = 0; $iContEtapa < $iLinhasEtapa; $iContEtapa++) {

    $oDadosEtapa = db_utils::fieldsmemory($rsEtapa, $iContEtapa);

    if ($iContEtapa < ($iLinhasEtapa-1) || $iLinhasEtapa < $iNumColunas) {
      $iQuebraEtapa = 0;
    } else {
      $iQuebraEtapa = 1;
    }
    $aOrdemEtapa[] = $oDadosEtapa;
    $oFpdf->cell(15, 4, $oDadosEtapa->ed11_c_abrev, 1, $iQuebraEtapa, "C", 0);
  }
  //após o término das etapas, vai o for abaixo
  for ($iContRestantes = $iLinhasEtapa; $iContRestantes < $iNumColunas; $iContRestantes++) {

    if ($iContRestantes < $iNumColunas -1) {
      $iQuebraEtapa = 0;
    } else {
      $iQuebraEtapa = 1;
    }

    $oFpdf->cell(15, 4, "", 1, $iQuebraEtapa, "C", 0);

  }



//select das disciplinas
  $sCamposDisc     = " distinct ed232_c_descrcompleta, ed12_i_codigo";
  $sWhereDisc      = " ed61_i_codigo in ($iCodigoHist) ";
  $sSqlDisc        = $oDaoDisc->sql_query_certconclusao("", $sCamposDisc, "", $sWhereDisc);
  $sCamposDiscFora = " distinct ed232_c_descrcompleta,ed12_i_codigo ";
  $sWhereDiscFora  = " ed61_i_codigo in ($iCodigoHist) ";
  $sOrderDiscFora  = " ed232_c_descrcompleta ";
  $sSqlDiscFora    = $oDaoDiscFora->sql_query_certconclusao("", $sCamposDiscFora, $sOrderDiscFora, $sWhereDiscFora);

  $sSqlDisciplinas  = $sSqlDisc;
  $sSqlDisciplinas .= " UNION ";
  $sSqlDisciplinas .= $sSqlDiscFora;

  /**
   * agrupamos as disciplinas em,
   */
  $sSqlDisciplinasAgrupada  = "select ed232_c_descrcompleta, array_to_string(array_accum(ed12_i_codigo), ',') as ed12_i_codigo ";
  $sSqlDisciplinasAgrupada .= "  from ({$sSqlDisciplinas}) as x";
  $sSqlDisciplinasAgrupada .= " group  by ed232_c_descrcompleta ";
  $rsDisciplina    = $oDaoDisc->sql_record($sSqlDisciplinasAgrupada);
  $iLinhasDisc     = $oDaoDisc->numrows;

  $iCont           = 0;
  $iLimite         = $iLinhasDisc - 1;
  $iLimitePag      = $iLinhasDisc - 1;
  $iProxPagina     = 0;
  $iAltura         = 48;
  $iPagAtual       = 0;
  $iQuebra         = 0;

  for ($iContDisc = 0; $iContDisc < $iLinhasDisc; $iContDisc++) { //for disciplinas

    $oDadosDisc = db_utils::fieldsmemory($rsDisciplina, $iContDisc);

    if ($lCor) {
      $lCor = false;
    } else {
      $lCor = true;
    }

    $iAlturaPosicaoCursor = $oFpdf->GetY();
    $nTamanhoFonte        = $oFpdf->GetStringWidth($oDadosDisc->ed232_c_descrcompleta) + 3;
    $nTotalLinhas         = ceil($nTamanhoFonte / 60);
    $oFpdf->setfont('arial', 'b', $oDadosRelatModel->gradenota);
    $oFpdf->multicell(60, 4, $oDadosDisc->ed232_c_descrcompleta, 1, "L", $lCor);

    $nAlturaLinhaDisciplina = $nTotalLinhas * 4;
    $oFpdf->SetXY(70, $iAlturaPosicaoCursor);
    for ($iContEtapa = 0; $iContEtapa < $iLinhasEtapa; $iContEtapa++) {

      if ($iContEtapa < $iLinhasEtapa -1 || $iLinhasEtapa < $iNumColunas) {

        $iQuebra = 0;
      } else {
        $iQuebra = 1;
      }

      $oDadosEtapa = $aOrdemEtapa[$iContEtapa];
      //Sql que traz as notas
      $sCamposRes      = " distinct ed11_i_sequencia, ed62_i_anoref, ed62_i_escola,ed11_i_codigo, ";
      $sCamposRes     .= " ed65_i_disciplina, ed65_i_justificativa, ed65_i_qtdch, ed65_c_resultadofinal, ";
      $sCamposRes     .= " ed65_c_situacao as situacao, ed65_c_tiporesultado, ed65_t_resultobtido as resultado, ";
      $sCamposRes     .= " ed65_c_termofinal as termofinal, ed62_i_anoref as ano ";
      $sWhereRes       = " ed65_i_disciplina in ({$oDadosDisc->ed12_i_codigo}) ";
      $sWhereRes      .= " and ed61_i_aluno  = {$oDadosAluno->ed47_i_codigo} ";
      $sWhereRes      .= " and ed11_i_codigo = $oDadosEtapa->ed11_i_codigo";
      $sSqlRes         = $oDaoDisc->sql_query_certconclusao("", $sCamposRes, "", $sWhereRes);
      $sCamposResFora  = " distinct ed11_i_sequencia, ed99_i_anoref, ed99_i_escolaproc, ed11_i_codigo, ";
      $sCamposResFora .= " ed100_i_disciplina, ed100_i_justificativa, ed100_i_qtdch, ed100_c_resultadofinal , ";
      $sCamposResFora .= " ed100_c_situacao as situacao, ed100_c_tiporesultado,ed100_t_resultobtido as resultado, ";
      $sCamposResFora .= " ed100_c_termofinal as termofinal, ed99_i_anoref as ano  ";
      $sWhereResFora   = " ed100_i_disciplina  in ({$oDadosDisc->ed12_i_codigo}) ";
      $sWhereResFora  .= " and ed11_i_codigo = {$oDadosEtapa->ed11_i_codigo} ";
      $sWhereResFora  .= " and ed61_i_aluno  = {$oDadosAluno->ed47_i_codigo} ";
      $sOrderResFora   = " ed62_i_anoref asc ";
      $sSqlResFora     = $oDaoDiscFora->sql_query_certconclusao("", $sCamposResFora,$sOrderResFora, $sWhereResFora);
      $sSqlUnionRes    = $sSqlRes;
      $sSqlUnionRes   .= " UNION ";
      $sSqlUnionRes   .= $sSqlResFora;
      $rsUnionRes      = $oDaoSerie->sql_record($sSqlUnionRes);
      $iLinhasUnionRes = $oDaoSerie->numrows;

      if ($iLinhasUnionRes > 0) {

        $oDadosResultado = db_utils::fieldsmemory($rsUnionRes, 0);
        $oDadosResultado->resultado = $oDadosResultado->resultado;

        /**
         * Verificamos a descricao que sera apresentada no aproveitamento (Ap.)
         */
        $sResultadoFinal = "";
        if ($oDadosResultado->situacao != "CONCLUÍDO" && $oDadosResultado->situacao != "NÃO OPTANTE") {
          $sResultadoFinal = "Amparo";
        } else if ($oDadosResultado->situacao == "NÃO OPTANTE") {
          $sResultadoFinal = $oDadosResultado->resultado;
        } else if ($oDadosResultado->resultado != "") {
          $sResultadoFinal = $oDadosResultado->resultado;
        }

        $oFpdf->setfont('arial', '', $oDadosRelatModel->gradeetapa);
        $oFpdf->cell(15, $nAlturaLinhaDisciplina, $sResultadoFinal, 1, $iQuebra, "C", $lCor);

      } else {
        $oFpdf->cell(15, $nAlturaLinhaDisciplina, "", 1, $iQuebra, "C", $lCor);
      }
    }//fecha for etapas


     //após o término das etapas, vai o for abaixo
    for ($iContRestantes = $iLinhasEtapa; $iContRestantes < $iNumColunas; $iContRestantes++) {

      if ($iContRestantes < $iNumColunas -1) {
        $iQuebraEtapa = 0;
      } else {
        $iQuebraEtapa = 1;
      }

      $oFpdf->cell(15, $nAlturaLinhaDisciplina, "", 1, $iQuebraEtapa, "C", $lCor);

    }

    if ($iCont == $iLimite) {

      $iLimite     += 20;
      $iProxPagina += 1;
      $iPagAtual    = ($iProxPagina == 1 || $iProxPagina == 2) ? -4 : 0;
      $iAltura      = 48;
    }


    ///rodape  NAO ALTERAR
    if ($iCont == $iLimitePag ) { //series do historico

      $iAlturaSerie = $oFpdf->getY();
      $oFpdf->setfont('arial', 'b', $oDadosRelatModel->gradeetapa);
      $oFpdf->cell(15,5,"",0,1,"C",0);
      $oFpdf->cell(15, 5, "Etapa", 1, 0, "C", 0);
      $oFpdf->cell(10, 5, "Ano", 1, 0, "C", 0);
      $oFpdf->cell(10, 5, "Dias", 1, 0, "C", 0);
      $oFpdf->cell(10, 5, "CH", 1, 0, "C", 0);
      $oFpdf->cell(15, 5, "Resultado", 1, 0, "C", 0);
//      $oFpdf->cell(20, 5, "Mínimo", 1, 0, "C", 0);
      $oFpdf->cell(80, 5, "Escola", 1, 0, "C", 0);
      $oFpdf->cell(45, 5, "Cidade", 1, 0, "C", 0);
      $oFpdf->cell(10, 5, "UF", 1, 1, "C", 0);

      $sCamposSerie      = " ed11_i_sequencia, ed11_c_abrev, ed62_i_anoref, ed62_i_qtdch, ed62_i_diasletivos, ";
      $sCamposSerie     .= " ed62_c_resultadofinal, ed62_i_escola, ed62_i_turma, ed260_c_sigla, ed261_c_nome, ";
      $sCamposSerie     .= " ed18_i_codigo,ed18_c_nome, ed62_c_minimo, ed62_c_termofinal as termofinaletapa, ";
      $sCamposSerie     .= " ed62_lancamentoautomatico, ed62_c_situacao as situacao";
      $sWhereSerie       = " ed62_i_historico in ($iCodigoHist) $sCondicaoHistMps";
      $sSqlSerie         = $oDaoSerie->sql_query_certconclusao("", $sCamposSerie, "", $sWhereSerie);

      $sCamposSerieFora  = " ed11_i_sequencia, ed11_c_abrev, ed99_i_anoref, ed99_i_qtdch, ed99_i_diasletivos,";
      $sCamposSerieFora .= " ed99_c_resultadofinal, ed99_i_escolaproc, ed99_c_turma, ed260_c_sigla, ed261_c_nome, ";
      $sCamposSerieFora .= " ed82_i_codigo, ed82_c_nome, ed99_c_minimo, ed99_c_termofinal as termofinaletapa, ";
      $sCamposSerieFora .= " false as ed62_lancamentoautomatico, ed99_c_situacao as situacao";
      $sWhereSerieFora   = " ed99_i_historico in ($iCodigoHist) $sCondicaoHistMpsFora";
      $sOrderSerieFora   = " ed62_i_anoref ASC ";
      $sSqlSerieFora     = $oDaoSerieFora->sql_query_certconclusao("", $sCamposSerieFora, $sOrderSerieFora, $sWhereSerieFora);


      $sSqlUnion         = $sSqlSerie;
      $sSqlUnion        .= " UNION ";
      $sSqlUnion        .= $sSqlSerieFora;

      $rsSerie           = $oDaoSerie->sql_record($sSqlUnion);
      $iLinhasSerie      = $oDaoSerie->numrows;
      $iContSerie        = 0;

      $iAlturaFinal                  = $oFpdf->getY();
      $iAlturaInicialQuadroEscolas   = $oFpdf->getY();
      $lPossuiAprovacaoComProgressao = false;
      for ($iContador = 0; $iContador < $iLinhasSerie; $iContador++) {

        $iAlturaAnterior = $oFpdf->getY();
        if ($iAlturaFinal > $iAlturaAnterior) {
          $iAlturaAnterior = $iAlturaFinal;
        }
        $oDadosSerie     = db_utils::fieldsmemory($rsSerie, $iContador);
        switch (trim($oDadosSerie->ed62_c_resultadofinal)) {

          case 'A':

            $sSituacaoFinal = 'APR';
            break;

          case 'D':

            $lPossuiAprovacaoComProgressao = true;
            $sSituacaoFinal                = 'APR*';
            break;

          case 'R':

            $sSituacaoFinal = 'REP';
            break;

          default:

            $sSituacaoFinal = "REP";
            if ($oDadosResultado->situacao != "CONCLUÍDO") {
              $sSituacaoFinal = 'APR';
            }
            break;
        }

        /**
         * Verificamos a carga horaria a ser apresentada
         */
        $iCargaHoraria = '';
        if ($oDadosSerie->ed62_i_qtdch != "") {
          $iCargaHoraria = $oDadosSerie->ed62_i_qtdch;
        }

        /**
         * Caso tenha sido informado um termo final, este substituira o resultado final
         */
        if (!empty($oDadosSerie->termofinaletapa)) {
          $sSituacaoFinal = $oDadosSerie->termofinaletapa;
        }

        /**
         * Caso o histórico tenha sido lancado como transferencia, o resultado recebe TR
         */
        if (!empty($oDadosSerie->situacao) && $oDadosSerie->situacao == 'TRANSFERIDO') {
          $sSituacaoFinal = 'TR';
        }
        
        $oFpdf->setY($iAlturaAnterior);
        $oFpdf->setfont('arial', '', $oDadosRelatModel->gradeetapa);
        $oFpdf->cell(15, 5, $oDadosSerie->ed11_c_abrev,       "LTR", 0, "C", 0);
        $oFpdf->cell(10, 5, $oDadosSerie->ed62_i_anoref,      "LTR", 0, "C", 0);
        $oFpdf->cell(10, 5, $oDadosSerie->ed62_i_diasletivos, "LTR", 0, "C", 0);
        $oFpdf->cell(10, 5, $iCargaHoraria,                   "LTR", 0, "C", 0);
        $oFpdf->cell(15, 5, $sSituacaoFinal,                  "LTR", 0, "C", 0);
        $iLarguraAnterior = $oFpdf->getX();
        $oFpdf->multicell(80, 5, $oDadosSerie->ed18_c_nome, "LTR", "L", 0);
        $iAlturaFinalEscola = $oFpdf->getY();

        $oFpdf->setY($iAlturaAnterior);
        $oFpdf->SetX($iLarguraAnterior + 80);
        $iLarguraAnterior = $oFpdf->getX();
        $oFpdf->multicell(45, 5, $oDadosSerie->ed261_c_nome, "LTR", "L", 0);

        $iAlturaFinalMunicipio = $oFpdf->getY();
        $oFpdf->setY($iAlturaAnterior);
        $oFpdf->SetX($iLarguraAnterior + 45);
        $oFpdf->cell(10, 5, $oDadosSerie->ed260_c_sigla, "LTR", 1, "L", 0);
        $iContSerie++;
        $iAlturaFinal = $iAlturaFinalEscola;
        if ($iAlturaFinalMunicipio > $iAlturaFinalEscola) {
          $iAlturaFinal = $iAlturaFinalMunicipio;
        }
      }
      $oFpdf->setY($iAlturaFinal);

      /**
      * Fechamos o quadro das etapas cursadas
      */
      $oFpdf->line(10, $oFpdf->getY(), 205, $oFpdf->GetY());
      $iLimiteObs = 1500-($iLinhasSerie*100);
      $oFpdf->setfont('arial', '', 6, $oDadosRelatModel->gradeetapa);
      $iPosY = $oFpdf->getY();
      $sObsConselho          = "";
      $sCamposAprovConselho  = " cgmrh.z01_nome, ed253_i_data,ed232_c_descrcompleta as disc_conselho, ed253_t_obs, ed47_v_nome,";
      $sCamposAprovConselho .= " ed11_c_descr as serie_conselho, ed59_i_ordenacao, ed253_aprovconselhotipo, ed52_i_ano ";
      $sWhereAprovConselho   = "ed95_i_aluno = $oDadosHist->ed61_i_aluno AND ed31_i_curso = $oDadosHist->ed61_i_curso";
      $sSqlAprovConselho     = $oDaoAprovConselho->sql_query("", $sCamposAprovConselho,
                                                             "ed59_i_ordenacao", $sWhereAprovConselho
                                                            );

      $rsAprovConselho          = $oDaoAprovConselho->sql_record($sSqlAprovConselho);
      $aAprovadoBaixaFrequencia = array();
      if ($oDaoAprovConselho->numrows > 0) {

        $sSepObs = "";
        for ($iContAprov = 0; $iContAprov < $oDaoAprovConselho->numrows; $iContAprov++) {

          $oDadosAprovConselho = db_utils::fieldsmemory($rsAprovConselho, $iContAprov);
          $sTipoAprovacao = "foi aprovado pelo Conselho de Classe. ";
          if ($oDadosAprovConselho->ed253_aprovconselhotipo == 2) {

      	    $sHashSerieAno = $oDadosAprovConselho->serie_conselho.$oDadosAprovConselho->ed52_i_ano;
      	    if (!isset($aAprovadoBaixaFrequencia[$sHashSerieAno])) {
              $aAprovadoBaixaFrequencia[$sHashSerieAno] = $oDadosAprovConselho;
      	    }
            continue;
          }
          $sObsConselho .= $sSepObs."-Disciplina $oDadosAprovConselho->disc_conselho na etapa";
          $sObsConselho .= " $oDadosAprovConselho->serie_conselho {$sTipoAprovacao}";
          $sObsConselho .= "Justificativa: $oDadosAprovConselho->ed253_t_obs";
          $sSepObs       = "\n";

        }
      }
      if (count($aAprovadoBaixaFrequencia) > 0) {


        $oDocumento             = new libdocumento(5005);
        foreach ($aAprovadoBaixaFrequencia as $oBaixaFrequencia) {

          $oDocumento->nome_aluno = $oDadosHist->ed47_v_nome;
          $oDocumento->ano        = $oBaixaFrequencia->ed52_i_ano;
          $oDocumento->nome_etapa = $oBaixaFrequencia->serie_conselho;
          $aParagrafos            = $oDocumento->getDocParagrafos();
          if (isset($aParagrafos[1])) {
            $sObsConselho .= "\n{$aParagrafos[1]->oParag->db02_texto}";
          }
        }
      }
      $sCamposTrocaSerie  = " distinct trocaserie.ed101_i_codigo, serieorig.ed11_c_descr as ed11_c_origem, ";
      $sCamposTrocaSerie .= " seriedest.ed11_c_descr as ed11_c_destino, trocaserie.ed101_d_data, ";
      $sCamposTrocaSerie .= " trocaserie.ed101_c_tipo ";
      $sWhereTrocaSerie   = " ed101_i_aluno = $oDadosHist->ed61_i_aluno ";
      $sOrderTrocaSerie   = " ed101_d_data ";
      $sSqlTrocaSerie     = $oDaoTrocaSerie->sql_query_certificado_conclusao("", $sCamposTrocaSerie, $sOrderTrocaSerie, $sWhereTrocaSerie);
      $rsTrocaSerie       = $oDaoTrocaSerie->sql_record($sSqlTrocaSerie);
      $sObsTroca          = "";
      $sSepTroca          = "";

      if ($oDaoTrocaSerie->numrows > 0) {

        for ($iContTrocaSerie = 0; $iContTrocaSerie < $oDaoTrocaSerie->numrows; $iContTrocaSerie++) {

          $oDadosTrocaSerie  = db_utils::fieldsmemory($rsTrocaSerie, $iContTrocaSerie) ;
          $sObsTroca        .= $sSepTroca."-".($oDadosTrocaSerie->ed101_c_tipo == "A" ?
                                               "AVANÇADO" : "CLASSIFICADO")."(A) DA ETAPA ".
                                               (trim($oDadosTrocaSerie->ed11_c_origem))." PARA ETAPA ".
                                               (trim($oDadosTrocaSerie->ed11_c_destino))." EM ".
                                                substr($oDadosTrocaSerie->ed101_d_data, 8, 2)."/".substr
                                               ($oDadosTrocaSerie->ed101_d_data, 5, 2)."/".substr
                                               ($oDadosTrocaSerie->ed101_d_data, 0, 4);
          $sObsTroca        .= ",  CONFORME LEI FEDERAL N° 9394/96 - ARTIGO 23,  § 1o ,  ";
          $sObsTroca        .= " PARECER CEED N° 740/99 E REGIMENTO ESCOLAR";
          $sSepTroca         = "\n";

        }

      }

      $sSqlHistorico = $oDaoHistorico->sql_query("", "ed61_t_obs", "", " ed61_i_aluno in ($oDadosAluno->ed47_i_codigo)");
      $rsHistorico   = $oDaoHistorico->sql_record($sSqlHistorico);
      $sObsHist      = "";
      $sSep          = "";

      for ($iContObs = 0; $iContObs < $oDaoHistorico->numrows; $iContObs++) {

        $oDadosHistorico = db_utils::fieldsmemory($rsHistorico, $iCont);
        $sObsHist .= $sSep.$oDadosHistorico->ed61_t_obs;
        $sSep = "\n";

      }

      $sObservacaoHistorico .= $sObsConselho;
      /**
       *  Ajustamos o que vai ser impresso ao final do relatorio
       */
      $sObservacao          = (!empty($oDadosRelatModel->ed217_t_obs) ? $oDadosRelatModel->ed217_t_obs."\n" : "");
      $sObservacaoHistorico = (!empty($sObservacaoHistorico) ? $sObservacaoHistorico."\n" : "");
      $sObsTroca            = (!empty($sObsTroca) ? $sObsTroca."\n" : "");
      $iYInicialObservacoes = $oFpdf->getY() + 3;

      $sConvencoes = "";
      if (isset($sDisposicao) && $sDisposicao == 1) {

        $sConvencoes          = " Convenções: CH = Carga Horária RF = Resultado Final PL = Período Letivo ";
        $sConvencoes         .= " ESC = Escola DL = Dias Letivos Aprov. = Aproveitamento";
      }
      if ($lPossuiAprovacaoComProgressao) {
        $sConvencoes .= " * = Aprovado com progressão parcial / Dependência";
      }
      $sConteudoMultiCell   = "{$sObservacao} {$sConvencoes}\n {$sObservacaoHistorico} {$sObsTroca}";
      $oFpdf->setfont('arial','',$oDadosRelatModel->observacao);
      $oFpdf->setY($oFpdf->getY() + 4);
      $oFpdf->multicell(30, 5, "Observações:", 0, 10, "LR", "J", 0, 0);
			$oFpdf->multicell(190, 3, "{$sConteudoMultiCell}", 0, 10, "LR", "J", 0, 0);

      $iYFinalObservacoes = $oFpdf->getY();
      $oFpdf->Rect(10, $iYInicialObservacoes, 195, ($oFpdf->h - 35) - $iYInicialObservacoes); //retangulo da observacao
      $oFpdf->setfont('arial', '',$oDadosRelatModel->observacao);
      $oFpdf->setXY(60, $oFpdf->h - 25);
      $oFpdf->cell(90, 5, $oDadosEscola->mun_escola.",  ".date("d", db_getsession("DB_datausu")).
                   " de ".db_mes(date("m", db_getsession("DB_datausu")), 1).
                   " de ".date("Y", db_getsession("DB_datausu")).".", 0, 0, "C", 0
                  );
      $oFpdf->setfont('arial', '', 6);
      $oFpdf->setXY(5, $oFpdf->h - 15);
      if ( $sSecretario <> "" ) {
        $oFpdf->cell(102, 5, "______________________________________________________________________", 0, 0, "C", 0);
      }
      if ( $sDiretor <> "" ) {
        $oFpdf->cell(102, 5, "______________________________________________________________________", 0, 0, "C", 0);
      }
      $oFpdf->ln();

      $oFpdf->cell(95, 5, $sSecretario, 0, 0, "C", 0);
      $oFpdf->cell(95, 5, $sDiretor, 0, 1, "C", 0);

    }

    $iCont++;
    $iPagAtual += 4;


  }//fecha for disciplinas

}
$oFpdf->Output();
?>