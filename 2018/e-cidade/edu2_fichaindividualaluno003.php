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

require_once(modification("libs/db_stdlibwebseller.php"));
require_once(modification("fpdf151/pdfwebseller.php"));
require_once(modification("libs/db_utils.php"));

$resultedu           = eduparametros(db_getsession("DB_coddepto"));
$permitenotaembranco = VerParametroNota(db_getsession("DB_coddepto"));
$escola              = db_getsession("DB_coddepto");
$oGet                = db_utils::postMemory( $_GET );
$obs1                = base64_decode($obs1);

$clmatricula       = new cl_matricula;
$claluno           = new cl_aluno;
$clturma           = new cl_turma;
$clEscola          = new cl_escola();
$cldiarioavaliacao = new cl_diarioavaliacao;
$cldiarioresultado = new cl_diarioresultado;
$clregenteconselho = new cl_regenteconselho;
$clregencia        = new cl_regencia;
$clrotulo          = new rotulocampo;
$oDaoEscolaDiretor = new cl_escoladiretor();
$oDaoTipoSanguineo = new cl_tiposanguineo();

$claluno->rotulo->label();
$clrotulo->label("ed76_i_escola");
$clrotulo->label("ed76_d_data");

/**
 * Busca o município da escola
 */
$sSqlDadosEscola = $clEscola->sql_query( "", "ed261_c_nome as mun_escola", "", "ed18_i_codigo = {$escola}" );
$rsDadosEscola   = db_query( $sSqlDadosEscola );
$oDadosEscola    = db_utils::fieldsMemory( $rsDadosEscola, 0 );
$mun_escola      = $oDadosEscola->mun_escola;

/**
 * Campos a serem retornados em relação ao diretor da escola
 */
$sCamposDiretor    = " 'DIRETOR' as funcao, ";
$sCamposDiretor   .= "          case when ed20_i_tiposervidor = 1 then ";
$sCamposDiretor   .= "                  cgmrh.z01_nome ";
$sCamposDiretor   .= "               else cgmcgm.z01_nome ";
$sCamposDiretor   .= "            end as nome,";
$sCamposDiretor   .= " ed83_c_descr||' n°: '||ed05_c_numero::varchar as descricao,'D' as tipo";
$sWhereDiretor     = " ed254_i_escola = ".$escola." AND ed254_c_tipo = 'A' AND ed01_i_funcaoadmin = 2 limit 1 ";
$sSqlDiretor       = $oDaoEscolaDiretor->sql_query_resultadofinal("", $sCamposDiretor, "", $sWhereDiretor);
$rsDiretor         = $oDaoEscolaDiretor->sql_record($sSqlDiretor);
$iLinhasDiretor    = $oDaoEscolaDiretor->numrows;

if ( $iLinhasDiretor > 0 ) {

  db_fieldsmemory( $result, 0 );
  $nome = trim( db_utils::fieldsmemory( $rsDiretor, 0 )->nome );
} else {
  $nome= "";
}

/**
 * Campos para impressão dos dados do aluno
 */
$camp  = " ed60_d_datasaida as datasaida, ed10_i_codigo as ensino, ";
$camp .= " case ";
$camp .= "   when ed60_c_situacao = 'TRANSFERIDO REDE' then ";
$camp .= "    (select escoladestino.ed18_c_nome from transfescolarede ";
$camp .= "      inner join atestvaga  on  atestvaga.ed102_i_codigo = transfescolarede.ed103_i_atestvaga ";
$camp .= "      inner join escola  as escoladestino on  escoladestino.ed18_i_codigo = atestvaga.ed102_i_escola ";
$camp .= "     where ed103_i_matricula = ed60_i_codigo order by ed103_d_data desc limit 1) ";
$camp .= "   when ed60_c_situacao = 'TRANSFERIDO FORA' then ";
$camp .= "    (select escolaproc1.ed82_c_nome from transfescolafora ";
$camp .= "     inner join escolaproc as escolaproc1 on  escolaproc1.ed82_i_codigo = transfescolafora.ed104_i_escoladestino ";
$camp .= "     where ed104_i_matricula = ed60_i_codigo order by ed104_d_data desc limit 1) ";
$camp .= "    else null ";
$camp .= "  end as destinosaida, ";
$camp .= "  matricula.*, ";
$camp .= "  turma.ed57_c_descr, ";
$camp .= "  turma.ed57_i_codigo, ";
$camp .= "  turmaserieregimemat.ed220_i_procedimento, ";
$camp .= "  turma.ed57_c_medfreq, ";
$camp .= "  calendario.ed52_c_descr, ";
$camp .= "  calendario.ed52_i_ano, ";
$camp .= "  case when turma.ed57_i_tipoturma = 2 then ";
$camp .= "   fc_nomeetapaturma(ed60_i_turma) else ";
$camp .= "   serie.ed11_c_descr ";
$camp .= "  end as ed11_c_descr, ";
$camp .= "  serie.ed11_i_codigo, ";
$camp .= "  escola.ed18_c_nome, ";
$camp .= "  turno.ed15_c_nome, ";
$camp .= "  aluno.ed47_v_nome, ";
$camp .= "  alunoprimat.ed76_i_codigo, ";
$camp .= "  alunoprimat.ed76_i_escola, ";
$camp .= "  alunoprimat.ed76_d_data, ";
$camp .= "  alunoprimat.ed76_c_tipo, ";
$camp .= "  case when ed76_c_tipo = 'M' ";
$camp .= "   then escolaprimat.ed18_c_nome else escolaproc.ed82_c_nome end as nomeescola, ";
$camp .= "   aluno.*  ";

$sSqlMatricula = $clmatricula->sql_query( "", $camp, "ed60_d_datamatricula desc", " ed60_i_codigo in ($alunos)" );
$result1       = $clmatricula->sql_record( $sSqlMatricula );

$sSqlTipoSanguineo = $oDaoTipoSanguineo->sql_query_file("", "*", "sd100_sequencial", "");
$rsTipoSanguineo   = $oDaoTipoSanguineo->sql_record($sSqlTipoSanguineo);
$iLinhas           = $oDaoTipoSanguineo->numrows;

$aTiposSanguineos = array();

if ( isset( $rsTipoSanguineo ) && $iLinhas > 0) {

  for ( $iContador = 0; $iContador < $iLinhas; $iContador++ ) {

    $oDados = db_utils::fieldsMemory($rsTipoSanguineo, $iContador);
    $aTiposSanguineos[$oDados->sd100_sequencial] = $oDados->sd100_tipo;
  }
}

/**
 * Caso não encontre registros da matrícula do aluno, apresenta a mensagem de nenhum registro encontrado
 */
if ( $clmatricula->numrows == 0 ) {
  db_redireciona( "db_erros.php?fechar=true&db_erro=Nenhum registro encontrado." );
}

/**
 * Início da impressão do PDF
 */
$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->ln(5);

db_fieldsmemory( $result1, 0 );

/**
 * Cabeçalho da ficha do aluno
 */
$head1 = "FICHA DO ALUNO";
$head2 = "{$ed47_i_codigo} - {$ed47_v_nome}";

$pdf->Addpage('P');
$pdf->setfillcolor(223);

$u       = 0;
$iCodigo = 0;

/**
 * Percorre as matrículas encontradas
 */
for ( $ww = 0; $ww < $clmatricula->numrows; $ww ++ ) {

  db_fieldsmemory( $result1, $ww );

  $data         = date( "Y-m-d",DB_getsession("DB_datausu") );
  $dia          = date( "d" );
  $mes          = date( "m" );
  $ano          = date( "Y" );
  $mes_extenso  = array(
                         "01" => "janeiro",
                         "02" => "fevereiro",
                         "03" => "março",
                         "04" => "abril",
                         "05" => "maio",
                         "06" => "junho",
                         "07" => "julho",
                         "08" => "agosto",
                         "09" => "setembro",
                         "10" => "outubro",
                         "11" => "novembro",
                         "12" => "dezembro"
                       );
  $data_extenso = $mun_escola.", ".$dia." de ".$mes_extenso[$mes]." de ".$ano.".";

  /**
   * Cabeçalho da ficha do aluno
   */
  $head1        = "FICHA DO ALUNO";
  $head2        = "{$ed47_i_codigo} - {$ed47_v_nome}";

  if ( $iCodigo != $ed60_i_codigo ) {

    if ( $ww != 0 ) {

   	  $pdf->ln(5);
      $pdf->Addpage('P');
    }

    $iCodigo = $ed60_i_codigo;
  }

  /**
   * ****************************
   * Impressão dos DADOS PESSOAIS
   * ****************************
   */
  $pdf->setfont( 'arial', 'b', 7 );
  $pdf->cell( 190, 4, "DADOS PESSOAIS", "LBT", 1, "C", 1 );
  $pdf->cell( 3,   4, "",               "L",   0, "C", 0 );

  $pdf->setfont( 'arial', '', 7 );
  $pdf->cell( 35, 4, strip_tags( $Led47_v_nome ), 0, 0, "L", 0 );

  $pdf->setfont( 'arial', 'b', 7 );
  $pdf->cell( 120, 4, $ed47_v_nome, 0,   1, "L", 0 );
  $pdf->cell( 3,   4, "",           "L", 0, "C", 0 );

  $pdf->setfont( 'arial', '', 7 );
  $pdf->cell( 35, 4, strip_tags( $Led47_i_codigo ), 0, 0, "L", 0 );

  $pdf->setfont( 'arial', 'b', 7 );
  $pdf->cell( 20, 4, $ed47_i_codigo, 0, 0, "L", 0 );

  $pdf->setfont( 'arial', '', 7 );
  $pdf->cell( 30, 4, strip_tags( $Led47_c_codigoinep ), 0, 0, "R", 0 );

  $pdf->setfont( 'arial', 'b', 7 );
  $pdf->cell( 20, 4, $ed47_c_codigoinep, 0, 0, "L", 0 );

  $pdf->setfont( 'arial', '', 7 );
  $pdf->cell( 25, 4, strip_tags( $Led47_c_nis ), 0, 0, "R", 0 );

  $pdf->setfont( 'arial', 'b', 7 );
  $pdf->cell( 25, 4, $ed47_c_nis, 0,   1, "L", 0 );
  $pdf->cell( 3,  4, "",          "L", 0, "C", 0 );

  $pdf->setfont( 'arial', '', 7 );
  $pdf->cell( 35, 4, strip_tags( $Led47_d_nasc ), 0, 0, "L", 0 );

  $pdf->setfont( 'arial', 'b', 7 );
  $pdf->cell( 20, 4, db_formatar( $ed47_d_nasc, 'd' ), 0, 0, "L", 0 );

  $pdf->setfont( 'arial', '', 7 );
  $pdf->cell( 30, 4, strip_tags( $Led47_v_sexo ), 0, 0, "R", 0 );

  $pdf->setfont( 'arial', 'b', 7 );
  $pdf->cell( 20, 4, $ed47_v_sexo == "M" ? "MASCULINO" : "FEMININO", 0, 0, "L", 0 );

  $pdf->setfont( 'arial', '', 7 );
  $pdf->cell( 25, 4, strip_tags( $Led47_i_estciv ), 0, 0, "R", 0 );

  if ( $ed47_i_estciv == 1 ) {
    $ed47_i_estciv = "SOLTEIRO";
  } else if ( $ed47_i_estciv == 2 ) {
    $ed47_i_estciv = "CASADO";
  } else if ( $ed47_i_estciv == 3 ) {
    $estciv = "VIÚVO";
  } else if ( $ed47_i_estciv == 4 ) {
    $ed47_i_estciv = "DIVORCIADO";
  }

  $pdf->setfont( 'arial', 'b', 7 );
  $pdf->cell( 25, 4, $ed47_i_estciv, 0,   1, "L", 0 );
  $pdf->cell( 3,  4, "",             "L", 0, "C", 0 );

  $pdf->setfont( 'arial', '', 7 );
  $pdf->cell( 35, 4, strip_tags( $Led47_tiposanguineo ), 0, 0, "L", 0 );

  $pdf->setfont( 'arial', 'b', 7 );
  $pdf->cell( 65, 4, $ed47_tiposanguineo == "" ? "NÃO INFORMADO" : $aTiposSanguineos[$ed47_tiposanguineo], 0, 0, "L", 0 );

  $pdf->setfont( 'arial', '', 7 );
  $pdf->cell( 30, 4, strip_tags( $Led47_c_raca ), 0, 0, "R", 0 );

  $pdf->setfont( 'arial', 'b', 7 );
  $pdf->cell( 25, 4, $ed47_c_raca, 0,   1, "L", 0 );
  $pdf->cell( 3,  4, "",           "L", 0, "C", 0 );

  $pdf->setfont( 'arial', '', 7 );
  $pdf->cell( 35, 4, strip_tags( $Led47_i_filiacao ), 0, 0, "L", 0 );

  $pdf->setfont( 'arial', 'b', 7 );
  $pdf->cell( 120, 4, $ed47_i_filiacao == "0" ? "NÃO DECLARADO / IGNORADO" : "PAI E/OU MÃE", 0, 1, "L", 0 );
  $pdf->cell( 3,  4, "", "L", 0, "C", 0 );

  $pdf->setfont( 'arial', '', 7 );
  $pdf->cell( 35, 4, strip_tags( $Led47_v_pai ), 0, 0, "L", 0 );

  $pdf->setfont( 'arial', 'b', 7 );
  $pdf->cell( 120, 4, $ed47_v_pai, 0, 1, "L", 0 );
  $pdf->cell( 3, 4, "", "L", 0, "C", 0 );

  $pdf->setfont( 'arial', '', 7 );
  $pdf->cell( 35, 4, strip_tags( $Led47_v_mae ), 0, 0, "L", 0 );

  $pdf->setfont( 'arial', 'b', 7 );
  $pdf->cell( 120, 4, $ed47_v_mae, 0, 1, "L", 0 );
  $pdf->cell( 3, 4, "", "L", 0, "C", 0 );

  $pdf->setfont( 'arial', '', 7 );
  $pdf->cell( 35, 4, strip_tags( $Led47_c_nomeresp ), 0, 0, "L", 0 );

  $pdf->setfont( 'arial', 'b', 7 );
  $pdf->cell( 120, 4, $ed47_c_nomeresp, 0, 1, "L", 0 );
  $pdf->cell( 3, 4, "", "L", 0, "C", 0 );

  $pdf->setfont( 'arial', '', 7 );
  $pdf->cell( 35, 4, strip_tags( $Led47_c_emailresp ), 0, 0, "L", 0 );

  $pdf->setfont( 'arial', 'b', 7 );
  $pdf->cell( 120, 4, $ed47_c_emailresp, 0, 1, "L", 0 );

  $pdf->line( 200, 35, 200, 75 );
  $pdf->line( 10,  75, 200, 75 );

  $altini = $pdf->getY()+5;
  $pdf->setY( $altini );

  /**
   * ********************************
   * Impressão dos DADOS DA MATRÍCULA
   * ********************************
   */
  if ( $clmatricula->numrows > 0 ) {

    $contador = 0;
    db_fieldsmemory( $result1, $ww );

    $oTurma     = TurmaRepository::getTurmaByCodigo( $ed57_i_codigo );
    
    
    $clObsFichaIndAluno = new cl_obsfichaindaluno;
    $iEscola = $oTurma->getEscola()->getCodigo();
    $sSql = $clObsFichaIndAluno->sql_query("","ed286_t_obs",""," ed286_i_escola = $iEscola");
    
    $resultobs = $clObsFichaIndAluno->sql_record($sSql);
    if ($clObsFichaIndAluno->numrows > 0) {
    
    	$oDadosObs = db_utils::fieldsmemory($resultobs,0);
    	$obs1 = $oDadosObs->ed286_t_obs;
    }
    
    $oMatricula = MatriculaRepository::getMatriculaByCodigo( $ed60_i_codigo );

    $pdf->setfont( 'arial', '', 7 );
    $pdf->cell( 35, 4, "Matrícula N°:", "LT", 0, "L", 1 );

    $pdf->setfont( 'arial', 'b', 7 );
    $pdf->cell( 40, 4, $ed60_i_codigo, "T", 0, "L", 1 );

    $pdf->setfont( 'arial', '', 7 );
    $pdf->cell( 30, 4, "Escola:", "T", 0, "L", 1 );

    $pdf->setfont( 'arial', 'b', 7 );
    $pdf->cell( 85, 4, $ed18_c_nome, "RT", 1, "L", 1 );

    $pdf->setfont( 'arial', '', 7 );
    $pdf->cell( 35, 4, "Data da Matrícula:", "L", 0, "L", 1 );

    $pdf->setfont( 'arial', 'b', 7 );
    $pdf->cell( 40, 4, db_formatar( $ed60_d_datamatricula, 'd' ), 0, 0, "L", 1 );

    $pdf->setfont( 'arial', '', 7 );
    $pdf->cell( 30, 4, "Situação:", 0, 0, "L", 1 );

    $pdf->setfont( 'arial', 'b', 7 );

    if ( trim( $ed60_c_situacao ) == "AVANÇADO" || trim( $ed60_c_situacao ) == "CLASSIFICADO") {
      $sitt = trim( $ed60_c_situacao );
    } else {

      if ( $ed60_c_concluida == "S" ) {
        $sitt = Situacao( $ed60_c_situacao, $ed60_i_codigo )."(CONCLUÍDA)";
      } else {
        $sitt = Situacao( $ed60_c_situacao, $ed60_i_codigo );
      }
    }

    $pdf->cell( 85, 4, $sitt, "R", 1, "L", 1 );

    if (    trim( Situacao( $ed60_c_situacao, $ed60_i_codigo ) ) != "MATRICULADO"
         && trim( Situacao( $ed60_c_situacao, $ed60_i_codigo ) ) != "REMATRICULADO" ) {

      $pdf->setfont( 'arial', '', 7 );
      $pdf->cell( 35, 4, "Data Saída:", "L", 0, "L", 1 );

      $pdf->setfont( 'arial', 'b', 7 );
      $sDataSaida = !empty( $datasaida ) ? db_formatar( $datasaida, 'd' ) : "";
      $pdf->cell( 40, 4, $sDataSaida, 0, 0, "L", 1 );

      $pdf->setfont( 'arial', '', 7 );
      $pdf->cell( 30, 4, "Destino Saída:", 0, 0, "L", 1 );

      $pdf->setfont( 'arial', 'b', 7 );
      $pdf->cell( 85, 4, $destinosaida, "R", 1, "L", 1 );
    }

    $pdf->setfont( 'arial', '', 7 );
    $pdf->cell( 35, 4, "Nome da Turma:", "L", 0, "L", 1 );

    $pdf->setfont( 'arial', 'b', 7 );
    $pdf->cell( 40, 4, $ed57_c_descr, 0, 0, "L", 1 );

    $pdf->setfont( 'arial', '', 7 );
    $pdf->cell( 30, 4, "Etapa:", 0, 0, "L", 1 );

    $pdf->setfont( 'arial', 'b', 7 );
    $pdf->cell( 85, 4, $ed11_c_descr, "R", 1, "L", 1 );

    $pdf->setfont( 'arial', '', 7 );
    $pdf->cell( 35, 4, "Turno:", "L", 0, "L", 1 );

    /**
     * Verifica se a turma é do tipo Integral e Infantil, alterando a forma como é apresentada a descrição do
     * turno.
     * Por padrão, mostra somente a descrição do Turno (Ex.: MANHÃ)
     * No caso de turno Integral e Infantil, mostra também o turno referente o qual a matrícula está vinculada
     * Ex.: INTEGRAL - MANHÃ / TARDE
     */
    $oMatricula = MatriculaRepository::getMatriculaByCodigo( $ed60_i_codigo );
    if (    $oMatricula->getTurma()->getTurno()->isIntegral()
      && $oMatricula->getTurma()->getBaseCurricular()->getCurso()->getEnsino()->isInfantil()
    ) {

      $aDescricaoTurno = array();
      $aTurnoReferente = array( 1 => 'MANHÃ', 2 => 'TARDE', 3 => 'NOITE' );

      foreach ( $oMatricula->getTurnosVinculados() as $oTurnoReferente ) {
        $aDescricaoTurno[] = $aTurnoReferente[ $oTurnoReferente->ed336_turnoreferente ];
      }

      $ed15_c_nome = "INTEGRAL - " . implode( " / ", $aDescricaoTurno );
    }

    $oEtapa = EtapaRepository::getEtapaByCodigo( $ed11_i_codigo );

    $pdf->setfont( 'arial', 'b', 7 );
    $pdf->cell( 40, 4, $ed15_c_nome, 0, 0, "L", 1 );

    $pdf->setfont( 'arial', '', 7 );
    $pdf->cell( 30, 4, "Calendário:", 0, 0, "L", 1 );

    $pdf->setfont( 'arial', 'b', 7 );
    $pdf->cell( 85, 4, $ed52_c_descr." / ".$ed52_i_ano, "R", 1, "L", 1 );

    $pdf->setfont( 'arial', '', 7 );
    $pdf->cell( 35, 4, "Carga Horária Total:", "L", 0, "L", 1 );

    $pdf->setfont( 'arial', 'b', 7 );
    $pdf->cell( 40, 4, $oTurma->getCargaHoraria( $oEtapa ), 0, 0, "L", 1 );

    $pdf->setfont( 'arial', '', 7 );
    $pdf->cell( 30, 4, "Dias Letivos:", 0, 0, "L", 1 );

    $pdf->setfont( 'arial', 'b', 7 );
    $pdf->cell( 85, 4, $oTurma->getCalendario()->getDiasLetivos(), "R", 1, "L", 1 );

    $pdf->setfont( 'arial', '', 7 );
    $pdf->cell( 35, 4, "Classificação:", "L", 0, "L", 1 );

    $pdf->setfont( 'arial', 'b', 7 );
    $pdf->cell( 155, 4, $oMatricula->getNumeroOrdemAluno(), "R", 1, "L", 1 );
  }

  $cont_geral   = 0;
  $sSqlRegencia = $clregencia->sql_query( "", "ed59_i_codigo as regencia", "", "ed59_c_freqglob = 'FA' and ed59_i_turma = {$ed60_i_turma}" );
  $result11     = $clregencia->sql_record( $sSqlRegencia );

  if ( $clregencia->numrows > 0 && $punico == "yes" ) {

  	db_fieldsmemory($result11,0);
  	$condicao = "and ed59_i_codigo = {$regencia}";
  } else {
  	$condicao = "AND ed95_i_regencia in({$disciplinas})";
  }

  /**
   * Busca informações referentes a regência, pareceres, amparos e observações da matrícula
   */
  $campos  = "ed95_i_regencia, ed232_c_descr, ed72_t_obs, ed72_i_codigo as codaval, ed72_t_parecer as parecer, ed09_c_descr";
  $campos .= ", ed72_c_amparo as amparoum, ed81_c_todoperiodo as amparo, ed06_c_descr as justificativa, ed72_i_numfaltas, ed09_i_codigo";
  $campos .= ", ed81_i_justificativa, ed81_i_convencaoamp, ed250_c_abrev, ed74_t_obs, ed78_i_aulasdadas";
  $sql     = " SELECT {$campos} ";
  $sql    .= "   FROM diarioavaliacao ";
  $sql    .= "        inner join diario           on ed95_i_codigo                        = ed72_i_diario ";
  $sql    .= "        inner join regencia         on ed59_i_codigo                        = ed95_i_regencia ";
  $sql    .= "        inner join disciplina       on ed12_i_codigo                        = ed59_i_disciplina ";
  $sql    .= "        inner join caddisciplina    on ed232_i_codigo                       = ed12_i_caddisciplina ";
  $sql    .= "        left  join amparo           on ed81_i_diario                        = ed95_i_codigo ";
  $sql    .= "        left  join justificativa    on ed06_i_codigo                        = ed81_i_justificativa ";
  $sql    .= "        left  join convencaoamp     on ed250_i_codigo                       = ed81_i_convencaoamp ";
  $sql    .= "        inner join procavaliacao    on procavaliacao.ed41_i_codigo          = diarioavaliacao.ed72_i_procavaliacao";
  $sql    .= "        inner join periodoavaliacao on periodoavaliacao.ed09_i_codigo       = procavaliacao.ed41_i_periodoavaliacao";
  $sql    .= "        inner join regenciaperiodo  on regenciaperiodo.ed78_i_procavaliacao = procavaliacao.ed41_i_codigo ";
  $sql    .= "                                   and regenciaperiodo.ed78_i_regencia      = regencia.ed59_i_codigo";
  $sql    .= "        inner join diariofinal      on diariofinal.ed74_i_diario            = diario.ed95_i_codigo";
  $sql    .= "  WHERE ed95_i_aluno = {$ed60_i_aluno} ";
  $sql    .= "        {$condicao}";
  $sql    .= "    AND ed59_c_condicao = 'OB' and (trim(ed72_t_obs) !='' or trim(ed72_t_parecer) !='')";
  $sql    .= "  ORDER BY ed41_i_sequencia,ed59_i_ordenacao ";

  $result  = db_query( $sql );
  $linhas0 = pg_num_rows( $result );

  /**
   * Percorre as informações adicionais retornadas da matrícula
   */
  for ( $r = 0; $r < $linhas0; $r++ ) {

    db_fieldsmemory( $result, $r );
    $pdf->setfont( 'arial', 'b', 7 );

    if ( $punico == "yes" ) {

  	  $pdf->cell( 190, 4, "Período de Avaliação: ".$ed09_c_descr,              1, 1, "C",  0 );
      $pdf->cell( 190, 4, $punico == "yes" ? "PARECER ÚNICO" : $ed232_c_descr, 1, 1, "LR", 0 );
      $pdf->cell( 100, 4, "Faltas :".$ed72_i_numfaltas,                        1, 0, "L",  0 );
  	  $pdf->cell( 90,  4, "Aulas Dadas: ".$ed78_i_aulasdadas,                  1, 1, "R",  0 );
    } else {

      if ( $u != $ed09_i_codigo ) {

        $pdf->cell( 190, 4, "Período de Avaliação: ".$ed09_c_descr, 1, 1, "C", 1 );
        $u = $ed09_i_codigo;
      }

      $pdf->cell( 190, 4, "Aulas Dadas: ".$ed78_i_aulasdadas, 1, 1, "L", 0 );
      $pdf->cell( 100, 4, "Disciplina: $ed232_c_descr ",      1, 0, "L", 0 );
      $pdf->cell( 90,  4, "Faltas :".$ed72_i_numfaltas,       1, 1, "L", 0 );
    }

    /**
     * Imprime as informações de parecer
     */
    if ( $parecer != "" ) {

      $pdf->cell( 190, 4, "Parecer", 1, 1, "C", 1 );
      $pdf->setfont( 'arial', '', 7 );
      $pdf->multicell( 190, 3, "  ".trim( $parecer ), "LRB", "J", 0, 0 );
    }

    /**
     * Imprime observações referentes a matrícula
     */
    if ( $ed72_t_obs != "" ) {

      $pdf->setfont( 'arial', 'b', 7 );
      $pdf->cell( 190, 4, "Observações:", 1, 1, "L", 1 );

      $pdf->setfont( 'arial', '', 7 );
      $pdf->multicell( 190, 4, ( $ed72_t_obs != "" ? $ed72_t_obs."\n" : ""), 1, "L", 0, 0 );
    }

    $pdf->cell( 190, 4, "", 0, 1, "C", 0 );
  }

  /**
   * Retorna o parecer final da matrícula
   */
  $sWhere              = "ed95_i_aluno = {$ed60_i_aluno} AND ed95_i_regencia in({$disciplinas}) AND ed59_c_condicao = 'OB'";
  $sSqlDiarioResultado = $cldiarioresultado->sql_query( "", "ed73_t_parecer", "", $sWhere );
  $result66            = $cldiarioresultado->sql_record( $sSqlDiarioResultado );

  for ($e = 0; $e < $cldiarioresultado->numrows; $e++) {

  	db_fieldsmemory( $result66, $e );
    $pdf->setfont( 'arial', '', 7 );

    if ( $ed73_t_parecer != "" ) {

      $pdf->setfont( 'arial', 'b', 7 );
      $pdf->cell( 190, 4, "Parecer Final", 1, 1, "C", 1 );

      $pdf->setfont( 'arial', '', 7 );
      $pdf->multicell( 190, 3, "  ".trim( $ed73_t_parecer ), "LRB", "J", 0, 0 );
      $pdf->cell( 190, 4, "", 0, 1, "C", 0) ;
    }
  }

  /**
   * Retorna o resultado final do aluno
   */
  $sResultadoFinal = ResultadoFinal( $ed60_i_codigo, $ed60_i_aluno, $ed60_i_turma, trim( $ed60_c_situacao ), trim( $ed60_c_concluida), $ensino );
  $pdf->setfont( 'arial', 'b', 7 );
  $pdf->cell( 190, 4, "Resultado Final : ". $sResultadoFinal, 1, 1, "L", 1 );
  $pdf->cell( 190, 4, "",                                     0, 1, "C", 0 );

  if ( $linhas0 > 0 ) {

    db_fieldsmemory( $result, 0 );
    if ($ed74_t_obs != "") {

      $pdf->setfont( 'arial', 'b', 7 );
      $pdf->cell( 190, 4, "Observações Diário final:", 1, 1, "L", 1 );

      $pdf->setfont( 'arial', '', 7 );
      $pdf->multicell( 190, 4, mb_strtoupper( $ed74_t_obs ), 1, "L", 0, 0 );
    }

  }

  if ( $obs1 != "" ) {

    $pdf->setfont( 'arial', 'b', 7 );
    $pdf->cell( 190, 4, "",                   0, 1, "L", 0 );
    $pdf->cell( 190, 4, "Observações Geral:", 1, 1, "L", 1 );

    $pdf->setfont( 'arial', '', 7 );
    $pdf->multicell( 190, 4, mb_strtoupper($obs1), 1, "L" , 0, 0 );
  }

  $final = $pdf->getY();
  $pdf->setY( $final + 5 );
  $pdf->cell( 25, 4, $data_extenso, 0, 1, "L", 0 );

  $sCampos             = "case when ed20_i_tiposervidor = 1 then cgmrh.z01_nome else cgmcgm.z01_nome end as regente";
  $sSqlRegenteConselho = $clregenteconselho->sql_query( "", $sCampos, "", " ed235_i_turma = {$ed57_i_codigo}" );
  $result5             = $clregenteconselho->sql_record( $sSqlRegenteConselho );

  if ($clregenteconselho->numrows > 0) {
    db_fieldsmemory( $result5, 0 );
  } else {
    $regente = "";
  }

  if ($pdf->getY() >= $pdf->h - 30 ) {
    $pdf->Addpage('P');
  }

  /**
   * Variáveis para controle da posição X das opções possíveis de serem impressas
   */
  $iPosicaoXProfessor = 10;
  $iPosicaoXAdicional = 10;
  $iPosicaoXDiretor   = 10;

  /**
   * Variáveis para controle da posição Y das opções possíveis de serem impressas
   */
  $iPosicaoYDiretor   = $pdf->GetY();
  $iPosicaoYProfessor = $pdf->GetY();

  /**
   * Variáveis para controle de exibição da assinatura adicional e do professor
   */
  $lExibirAdicional = false;
  $lExibirProfessor = false;

  if ( isset( $oGet->iAssinaturaAdicional ) && !empty( $oGet->iAssinaturaAdicional ) ) {
    $lExibirAdicional = true;
  }

  if ( isset( $oGet->lExibeAssinaturaProfessor ) && $oGet->lExibeAssinaturaProfessor == "S" ) {
    $lExibirProfessor = true;
  }

  /**
   * Imprime a assinatura adicional caso informada
   */
  if ( $lExibirAdicional ) {

    $iPosicaoXDiretor = 120;
    $oDocente         = DocenteRepository::getDocenteByCodigoRecursosHumano( $oGet->iAssinaturaAdicional );
    $sNomeDocente     = $oDocente->getNome();
    $sFuncaoDocente   = '';

    foreach ( $oDocente->getAtividades( $oTurma->getEscola() ) as $oAtividades ) {

      if( isset( $oGet->iAtividade ) && $oAtividades->getAtividade()->getCodigo() == $oGet->iAtividade ) {
        $sFuncaoDocente = $oAtividades->getAtividade()->getDescricao();
      }
    }

    $pdf->setX( $iPosicaoXAdicional );
    $pdf->cell( 45, 5, "______________________________________________", 0, 1, "L", 0 );

    $pdf->setX( $iPosicaoXAdicional );
    $pdf->multicell( 70, 4, $sNomeDocente, 0, "L", 0, 0 );
    $pdf->setX( $iPosicaoXAdicional );
    $pdf->cell( 45, 3, $sFuncaoDocente, 0, 1, "L", 0 );

    /**
     * Caso tenha sido selecionado para exibir a assinatura do professor, setamos a posição Y do mesmo
     */
    if ( $lExibirProfessor ) {
      $iPosicaoYProfessor = $pdf->GetY() + 8;
    }
  }

  /**
   * Imprime a assinatura do professor caso checado
   */
  if ( $lExibirProfessor ) {

    $iPosicaoXDiretor = 120;

    $pdf->setY( $iPosicaoYProfessor );
    $pdf->setX( $iPosicaoXProfessor );
    $pdf->cell( 45, 5, "______________________________________________", 0, 1, "L", 0 );

    $pdf->setX( $iPosicaoXProfessor );
    $pdf->multicell( 70, 4, "",    0, "L", 0, 0 );
    $pdf->setX( $iPosicaoXProfessor );
    $pdf->cell( 45, 3, "Professor",   0, 1, "L", 0 );
  }

  /**
   * Imprime a assinatura do diretor
   */
  $pdf->setY( $iPosicaoYDiretor );
  $pdf->setX( $iPosicaoXDiretor );
  $pdf->cell( 45, 5, "______________________________________________", 0, 1, "L", 0 );

  $pdf->setX( $iPosicaoXDiretor );
  $pdf->multicell( 70, 4, $nome,    0, "L", 0, 0 );
  $pdf->setX( $iPosicaoXDiretor );
  $pdf->cell( 45, 3, "Diretor",   0, 1, "L", 0 );
}

$pdf->Output();
?>