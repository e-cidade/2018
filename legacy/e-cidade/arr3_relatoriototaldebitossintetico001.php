<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2015  DBseller Servicos de Informatica
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

set_time_limit(0);
require_once("libs/db_sql.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("fpdf151/pdf.php");
require_once("std/DBDate.php");
require_once("std/DBNumber.php");
require_once("libs/db_libtributario.php");

db_app::import("juridico.ProcessoForo");
db_app::import("CgmFactory");

try {

  $oDaoProcessoForoPartilhaCusta = db_utils::getDao("processoforopartilhacusta");
  $oDaoConvenio                  = db_utils::getDao('cadconvenio');
  $oDaoParjuridico               = db_utils::getDao("parjuridico");

  $oGet                          = db_utils::postMemory($_GET);
  $aTiposDebitosSelecionados     = explode(",", $oGet->tipos);
  $oData                         = new DBDate($db_datausu);
  $oDataSessao                   = new DBDate(date('Y-m-d', db_getsession("DB_datausu")));
  $DB_DATACALC                   = $oDataSessao->getTimeStamp();

  /**
   * Buscando parametros do Juridico para utilização de partilha
   */
  $oParametrosJuridico  = $oDaoParjuridico->getParametrosJuridico(db_getsession("DB_instit"), db_getsession("DB_anousu"));
  $oParametrosJuridico  = $oParametrosJuridico[0];

  if ( isset ($oGet->db_datausu) ) {

    if ( !checkdate( $oData->getMes(), $oData->getDia(), $oData->getAno() ) ) {

      $sMensagem  = "Data para Cálculo Inválida. <br><br>";
      $sMensagem .= "Data deverá ser superior a : " . $oDataSessao->getDate();
      throw new BusinessException( $sMensagem );
    }

    if ( $oData->getTimeStamp() < $oDataSessao->getTimeStamp() ) {

      $sMensagem  = "Data no permitida para cálculo. <br><br>";
      $sMensagem .= "Data deverá ser superior a : " . $oDataSessao->getDate();
      throw new BusinessException( $sMensagem );
    }
    $DB_DATACALC = $oData->getTimeStamp();
  }

  $aTiposDebitoProcessoForo = array(12,13,18);
  $sDadosExercicio          = "";
  $sDadosPeriodo            = "";
  $sOutrosDados1            = "";
  $sOutrosDados2            = "";
  $sOutrosDados3            = "";
  $sOutrosDados4            = "";

  $iAnoUsu                  = db_getsession("DB_anousu");

  /**
   * Configurações para o pdf
   */
  $iAlturaLinha             = 5;
  $iCorPreenchimento        = 235;

  $sTipoBusca               = isset($oGet->matric) ? "M" :
                                ( isset($oGet->inscr) ? "I" :
                                  ( isset($oGet->numcgm) ? "C" :
                                    ( isset($oGet->numpre) ? "N" : null) ) );

  $sCampos          = "db21_regracgmiss, db21_regracgmiptu";
  $oDaoInstituicao  = new cl_db_config();
  $sSqlConfiguracao = $oDaoInstituicao->sql_query_file(db_getsession("DB_instit"), $sCampos);
  $rsConfiguracao   = $oDaoInstituicao->sql_record($sSqlConfiguracao);

  $oDadoConfigTributario = null;
  if ($oDaoInstituicao->numrows == 0) {
    throw new Exception("Não foi encontrado regra para definir o cgm do Iptu.");
  }
  $oDadoConfigTributario = db_utils::fieldsMemory($rsConfiguracao, 0);

  switch ($sTipoBusca) {

    /**
     * Matrícula
     */
    case "M":

      $sFuncaoDebitos        = "debitos_matricula";
      $sChavePesquisa        = $matric;
      $sSqlDadosProprietario = "select * from proprietario where j01_matric = $sChavePesquisa limit 1";
      $rsDadosProprietario   = db_query($sSqlDadosProprietario);

      $sSqlEnvolvido    = "select * ";
      $sSqlEnvolvido   .= "  from fc_busca_envolvidos(false, {$oDadoConfigTributario->db21_regracgmiptu}, 'M', $sChavePesquisa)";

      $rsEnvolvidoRegra = db_query($sSqlEnvolvido);
      $oEnvolvidoRegra  = CgmFactory::getInstanceByCgm(db_utils::fieldsMemory($rsEnvolvidoRegra, 0)->rinumcgm);

      if ( !$rsDadosProprietario ) {
        throw new DBException("Erro ao Processar Pesquisa do Proprietário:".pg_last_error());
      }
      if ( pg_num_rows($rsDadosProprietario) == 0 ) {
        throw new BusinessException("Não Foi possivel emitir o Relatório pois não existe proprietário vinculado a Matrícula:".$sChavePesquisa);
      }

      $oDadosPesquisa = db_utils::fieldsMemory($rsDadosProprietario, 0);

      $sNome          = $oEnvolvidoRegra->getNome();
      $sEndereco      = $oDadosPesquisa->tipopri    . ' '  .
                        $oDadosPesquisa->nomepri    . ', ' .
                        $oDadosPesquisa->j39_numero . ' '  .
                        $oDadosPesquisa->j39_compl;

      $sOutrosDados1 = 'REF. ANTER.';
      $sOutrosDados2 = $oDadosPesquisa->j40_refant;
      $sOutrosDados3 = 'MATRÍCULA';
      $sOutrosDados4 = "Setor: "     . $oDadosPesquisa->j34_setor  .
                       "   Quadra: " . $oDadosPesquisa->j34_quadra .
                       "   Lote: "   . $oDadosPesquisa->j34_lote;
    break;

    /**
     * Inscrição
     */
    case "I":

      $sFuncaoDebitos        = "debitos_inscricao";
      $sChavePesquisa        = $inscr;
      $sSqlDadosProprietario =  "select * from empresa where q02_inscr = $sChavePesquisa";
      $rsDadosEmpresa        = db_query($sSqlDadosProprietario);

      if ( !$rsDadosEmpresa ) {
        throw new DBException("Erro ao Buscar Pesquisa da Empresa:".pg_last_error());
      }
      if ( pg_num_rows($rsDadosEmpresa) == 0 ) {
        throw new BusinessException("Não Foi possivel emitir o Relatório pois a Empresa não existe:".$sChavePesquisa);
      }

      $oDadosPesquisa = db_utils::fieldsMemory($rsDadosEmpresa, 0);
      $sNome          = $oDadosPesquisa->z01_nome;
      $sEndereco      = $oDadosPesquisa->j14_tipo   . '  ' .
                        $oDadosPesquisa->z01_ender  . ', ' .
                        $oDadosPesquisa->z01_numero . '  ' .
                        $oDadosPesquisa->z01_compl;

      $sOutrosDados1  = 'ATIVIDADE';
      $sOutrosDados2  = $oDadosPesquisa->q03_descr;
      $sOutrosDados3  = 'INSCRIÇÃO';
      $sOutrosDados4  = "";

    break;

  /**
   * Cgm
   */
  case "C":

    $sFuncaoDebitos = "debitos_numcgm";
    $sChavePesquisa = $numcgm;
    $oCgm           = CgmFactory::getInstanceByCgm($numcgm);

    $sNome          = $oCgm->getNome();
    $sEndereco      = $oCgm->getLogradouro() . ', ' .
                      $oCgm->getNumero()     . ' '  .
                      $oCgm->getComplemento();
    $sOutrosDados1  = '';
    $sOutrosDados2  = '';
    $sOutrosDados3  = 'CGM';
    $sOutrosDados4  = "";

  break;

  /**
   * Numpre
   */
  case "N":

    $sFuncaoDebitos   = "debitos_numpre";
    $sChavePesquisa   = $numpre;
    $sSqlEnvolvido    = "select * ";
    $sSqlEnvolvido   .= "  from fc_socio_promitente( {$sChavePesquisa}, 'true',{$oDadoConfigTributario->db21_regracgmiptu}, {$oDadoConfigTributario->db21_regracgmiss} )";
    $rsEnvolvidoRegra = db_query($sSqlEnvolvido);

    if ( !$rsEnvolvidoRegra ) {
      throw new DBException( "Erro ao Buscar dados do Débito: ".pg_last_error() );
    }

    if ( pg_num_rows($rsEnvolvidoRegra) == 0 ) {
      throw new BusinessException( "Não Foi possivel emitir o Relatório pois o debito({$sChavePesquisa}) não existe" );
    }

    $oCgm  = CgmFactory::getInstanceByCgm(db_utils::fieldsMemory($rsEnvolvidoRegra, 0)->rinumcgm);

    $sNome          = $oCgm->getNome();
    $sEndereco      = $oCgm->getLogradouro() . ', ' .
                      $oCgm->getNumero()     . ' '  .
                      $oCgm->getComplemento();

    $sOutrosDados1  = '';
    $sOutrosDados2  = '';
    $sOutrosDados3  = 'NUMPRE';
    $sOutrosDados4  = "";
  break;

  /**
   * Erro de Parâmetro
   */
  default:
    throw new ParameterException("Tipo de Busca de Dados Não Informado ou Inválido");
  break;
}

$aTipoDebitos = DBTributario::getTiposDebitoByOrigem($sTipoBusca, $sChavePesquisa);
$aWhere       = array();
$where        = "";
$and          = " and ";

if ( $oGet->parReceit != '' ) {
  $aWhere[] = " y.k00_receit in($parReceit)";
}

if ( $oGet->dtini != "--" && $oGet->dtfim != "--" ) {

	$aWhere[]             = "k00_dtoper  between '{$oGet->dtini}' and '{$oGet->dtfim}'";
	$sDataInicio          = db_formatar($oGet->dtini, "d");
	$sDataFim             = db_formatar($oGet->dtfim, "d");
	$sDadosPeriodo        = "De $sDataInicio até $sDataFim.";

} else if ($dtini != "--") {

	$aWhere[]             = " k00_dtoper >= '{$oGet->dtini}'  ";
	$sDataInicio          = db_formatar($oGet->dtini, "d");
  $sDataFim             = "";
	$sDadosPeriodo        = "Apartir de $sDataInicio.";
} else if ($dtfim != "--") {

  $aWhere[]             = " k00_dtoper <= '{$oGet->dtfim}'   ";
  $sDataInicio          = "";
  $sDataFim             = db_formatar($oGet->dtfim, "d");
  $sDadosPeriodo        = "Até $sDataFim.";
}

if ( !empty($oGet->exercini) && !empty($oGet->exercfim) ) {

	$aWhere[]              = "fc_arrecexerc(y.k00_numpre,y.k00_numpar)  between '{$oGet->exercini}' and '{$oGet->exercfim}'  ";
	$sDadosExercicio       = "Do exercício {$oGet->exercini} até {$oGet->exercfim}.";
} else if ( !empty($oGet->exercini) ) {

	$aWhere[]              = "fc_arrecexerc(y.k00_numpre,y.k00_numpar) >= '{$oGet->exercini}'  ";
	$sDadosExercicio       = "Apartir do exercício {$oGet->exercini}.";
} else if ( !empty($oGet->exercfim) ) {

	$aWhere[]              = "fc_arrecexerc(y.k00_numpre,y.k00_numpar) <= '{$oGet->exercfim}'   ";
	$sDadosExercicio       = "Até o exercício {$oGet->exercfim}.";
}

/**
 * Percorre os Tipos de Débitos encontrados e retorna os seus dados
 * para serem pre-processado para o relatorio
 */
$oDadosRelatorio              = new stdClass();
$oDadosRelatorio->aDebitos    = array();
$aTiposDebitosDetalhe         = array();
$oDadosRelatorio->aSuspensoes = array();
$aValoresTipoDebito           = $oDadosRelatorio->aDebitos;

foreach ( $aTipoDebitos as $oTipoDebito ) {

  if (!in_array($oTipoDebito->k00_tipo, $aTiposDebitosSelecionados) ) {
    continue;
  }
  /**
   * Parametros da Funcao
   */
  $aParametros     = array();
  $aParametros[]   = $sChavePesquisa;           // Valor do Tipo de Pesquisa Ex.: Numero do CGM, MATRICULA...
  $aParametros[]   = 0;                         // Limite de Registros
  $aParametros[]   = $oTipoDebito->k00_tipo;    // Tipo de Debito
  $aParametros[]   = $DB_DATACALC;              // Data Base para Calculo
  $aParametros[]   = db_getsession("DB_anousu");// Ano da Sessao

  /**
   * Adicionamos uma posicao a mais no array para quando por pesquisa por Numpre
   */
  if( $sTipoBusca == 'N' ){
    $aParametros[] = "";                        // Numpar
  }
  $aParametros[]   = "";                        // Totaliza
  $aParametros[]   = "";                        // Ordem Totalizacao
  $aParametros[]   = count($aWhere) > 0 ? "and " . implode(" and ", $aWhere) : ""; // Filtros para a Pesquisa
  $aParametros[]   = "";                        // Justific
  $aParametros[]   = "";                        // Instit

  /**
   * Chama a Função de débitos conforme o tipo de Origem selecionada
   * - debitos_matricula() - debitos_matricula($matricula, $limite, $tipo, $datausu, $anousu, $totaliza="", $totalizaordem="", $db_where="",      $justific=false, $instit=null )
   * - debitos_inscricao() - debitos_inscricao($inscricao, $limite, $tipo, $datausu, $anousu, $totaliza="", $totalizaordem="", $db_where="",      $justific=false, $instit=null)
   * - debitos_numcgm()    - debitos_numcgm   ($numcgm,    $limite, $tipo, $datausu, $anousu, $totaliza="", $totalizaordem="", $db_where="",      $justific=false, $instit=null )
   * - debitos_numpre()    - debitos_numpre   ($numpre,    $limite, $tipo, $datausu, $anousu, $numpar=0,    $totaliza="",      $totalizaordem="", $db_where="",    $justific=false, $instit=null )
   */
  $rsDebitos        = call_user_func_array($sFuncaoDebitos, $aParametros);

  if ( !is_resource($rsDebitos) ) {
    throw new DBException( "Não existem debitos({$oTipoDebito->k00_descr}) para o Exercicio/Periodo informado.");
  }
  $aNumpreDebito    = array();

  for ( $iIndiceDebitos = 0; $iIndiceDebitos < pg_num_rows($rsDebitos); $iIndiceDebitos++ ) {

    $oDebitos                                   = db_utils::fieldsMemory($rsDebitos, $iIndiceDebitos);
    $oValorBase                                 = new stdClass();
    $oValorBase->nValorHistorico                = 0;
    $oValorBase->nValorCorrigido                = 0;
    $oValorBase->nValorJuros                    = 0;
    $oValorBase->nValorMulta                    = 0;
    $oValorBase->nValorDesconto                 = 0;
    $oValorBase->nValorAcrescimos               = 0;
    $oValorBase->nValorTotal                    = 0;

    if ( isset($oDadosRelatorio->aDebitos[$oTipoDebito->k00_tipo]) ) {
      $oValorBase = $oDadosRelatorio->aDebitos[$oTipoDebito->k00_tipo];
    }

    $oDebitosRelatorio                          = new stdClass();
    $oDebitosRelatorio->iTipoDebito             = $oTipoDebito->k00_tipo; $oDebitosRelatorio->sDescricaoTipoDebito    = $oTipoDebito->k00_descr;
    $oDebitosRelatorio->nValorHistorico         = $oValorBase->nValorHistorico  + $oDebitos->vlrhis;
    $oDebitosRelatorio->nValorCorrigido         = $oValorBase->nValorCorrigido  + $oDebitos->vlrcor;
    $oDebitosRelatorio->nValorJuros             = $oValorBase->nValorJuros      + $oDebitos->vlrjuros;
    $oDebitosRelatorio->nValorMulta             = $oValorBase->nValorMulta      + $oDebitos->vlrmulta;
    $oDebitosRelatorio->nValorDesconto          = $oValorBase->nValorDesconto   + $oDebitos->vlrdesconto;
    $oDebitosRelatorio->nValorAcrescimos        = $oValorBase->nValorAcrescimos; //Acrescimos serão calculados depois
    $oDebitosRelatorio->nValorTotal             = $oValorBase->nValorTotal      + $oDebitos->total;


    $oDadosRelatorio->aDebitos[$oTipoDebito->k00_tipo] = $oDebitosRelatorio;

    /*
     * Caso o parametro para cobranca de custas esteja ativo, buscamos os processos do foro vinculados aos numpres
     */
    if ( $oParametrosJuridico->v19_partilha == "t") {

       if (in_array($oTipoDebito->k03_tipo, $aTiposDebitoProcessoForo)) {
         $oProcessoForo = ProcessoForo::getInstanceByNumpre($oDebitos->k00_numpre);
         if ( $oProcessoForo ) {
           $aProcessosForo[$oProcessoForo->getCodigoProcesso()] = $oProcessoForo->getCodigoProcesso();
         }
       }
    }

  }

  /*
   * Caso o parametro para cobranca de custas esteja ativo, verificamos se o tipo de debito esta nos tipos que cobram custas
   * Buscamos as custas calculadas e caso não existam, calculamos as custas e somamos ao total do tipo de débito.
   *
   */
  if ( $oParametrosJuridico->v19_partilha == "t") {

    if ( in_array($oTipoDebito->k03_tipo, $aTiposDebitoProcessoForo) ) {

      $aValorProcesso    = array();

      foreach ( $aProcessosForo as $iProcesso  ) {


        $nValorTotalCustas = 0;
        $sSqlProcessoforo  = " select v76_sequencial,v76_tipolancamento, v76_dtpagamento,                                 ";
        $sSqlProcessoforo .= "       coalesce(sum(v77_valor), 0) as v77_valor_soma                                        ";
        $sSqlProcessoforo .= "  from processoforopartilha                                                                 ";
        $sSqlProcessoforo .= "       left join processoforopartilhacusta on v77_processoforopartilha = v76_sequencial     ";
        $sSqlProcessoforo .= " where v76_sequencial in (select max(v76_sequencial)                                        ";
        $sSqlProcessoforo .= "                            from processoforopartilha                                       ";
        $sSqlProcessoforo .= "                           where v76_processoforo = {$iProcesso}                            ";
        $sSqlProcessoforo .= "                         )                                                                  ";
        $sSqlProcessoforo .= " group by v76_tipolancamento, v76_sequencial, v76_dtpagamento                               ";

        $rsProcessoForo    = db_query($sSqlProcessoforo);

        if (!$rsProcessoForo) {
          throw new DBException( "Erro ao buscar dados das custas: " . pg_last_error() );
        }

        $lCalculaEmisaoCustas = true;
        $nValorTotalCustas    = 0;

        if (pg_num_rows($rsProcessoForo) > 0) {

          $oDadosCustas = db_utils::fieldsMemory($rsProcessoForo, 0);

          if ($oDadosCustas->v76_tipolancamento == 1 && $oDadosCustas->v76_dtpagamento == "") {
            $nValorTotalCustas  = $oDadosCustas->v77_valor_soma;
          }

          $lCalculaEmisaoCustas = false;
        }

        if ( $lCalculaEmisaoCustas ) {

          $sSqlTaxas                     = $oDaoConvenio->sql_queryTaxasConvenio();
          $rsSqlTaxas                    = db_query($sSqlTaxas);
          $aTaxas                        = db_utils::getCollectionByRecord($rsSqlTaxas);

          $nValorProcessos               = $oDaoProcessoForoPartilhaCusta->getCustasProcesso(null,
                                                                                             $iProcesso,
                                                                                             date('Y-m-d', $DB_DATACALC),
                                                                                             $oTipoDebito->k03_tipo);

          foreach ($aTaxas as $oTaxa) {

            if ($oTaxa->ar36_perc == 0) {
              $nValorCusta = $oTaxa->ar36_valor;
            } else {

              $oValor               = new DBNumber();
              $nVlrPercentualDebito = $oValor->truncate($nValorProcessos * ($oTaxa->ar36_perc / 100), 2);

              /**
               * Verifica se valor do percentual do débito é maior que maximo ou minimo permitido
               * caso ele ultrapasse um dos limites o valor da taxa será o limite
               * caso contrario sera o resultado da operaçao
               */
              if ($nVlrPercentualDebito > $oTaxa->ar36_valormax) {
                $nValorCusta = $oTaxa->ar36_valormax;
              } elseif ($nVlrPercentualDebito < $oTaxa->ar36_valormin) {
                $nValorCusta = $oTaxa->ar36_valormin;
              } else {
                $nValorCusta = $nVlrPercentualDebito;
              }
            }
            $nValorTotalCustas += $nValorCusta;
          }
        }

        $sSqlDadosCustas                                     = $oDaoProcessoForoPartilhaCusta->sql_query();
        $aValorProcesso[$oProcessoForo->getCodigoProcesso()] = $nValorTotalCustas;
      }

      $oAcrescimoRelatorio                   = $oDadosRelatorio->aDebitos[$oTipoDebito->k00_tipo];
      $oAcrescimoRelatorio->nValorAcrescimos = array_sum($aValorProcesso);
      $oAcrescimoRelatorio->nValorTotal = $oAcrescimoRelatorio->nValorTotal + $oAcrescimoRelatorio->nValorAcrescimos;
    }

  }

   /**
    * Busca as suspensões para os tipos de Débito selecionados.
    */

  $oDaoArresusp   = db_utils::getDao('arresusp');
  $sSqlSuspensoes = $oDaoArresusp->sql_query_debitosSuspensos($sTipoBusca, $sChavePesquisa, explode(",", $parReceit), array($oTipoDebito->k00_tipo));

  $rsSuspensoes   = db_query($sSqlSuspensoes);

  if ( !$rsSuspensoes ) {
    throw new DBException("Erro ao Buscar dados das Suspensões: " . pg_last_error() );
  }

  if (pg_num_rows($rsSuspensoes) > 0) {

    $oSuspensoes    = db_utils::fieldsMemory($rsSuspensoes, 0);
    $oDadosRelatorio->aSuspensoes[$oTipoDebito->k00_tipo] = $oSuspensoes;
  }
}

  $oPDF = new pdf();
  $oPDF->Open();
  $oPDF->AliasNbPages();

  $head2      = "SECRETARIA DA FAZENDA";
  $head4      = "Relatório do Total dos Débitos Sintético";
  $head5      = $sDadosPeriodo;
  $head6      = $sDadosExercicio;

  $oPDF->AddPage();

  $oPDF->SetFillColor($iCorPreenchimento);
  $oPDF->SetLineWidth(0.5);
  $oPDF->Ln(3);
  $oPDF->Cell(191, 2           , ''                                         ,"T", 1, "R", 0);
  $oPDF->SetFont('Arial', 'B', 8);
  $oPDF->Cell(25, $iAlturaLinha, $sOutrosDados3                             ,  0, 0, "L", 0);
  $oPDF->SetFont('Arial', 'I', 8);
  $oPDF->Cell(80, $iAlturaLinha, ': '.$sChavePesquisa. '  ' . $sOutrosDados4,  0, 1, "L", 0);
  $oPDF->SetFont('Arial', 'B', 8);
  $oPDF->Cell(25, $iAlturaLinha, "NOME"                                     ,  0, 0, "L", 0);
  $oPDF->SetFont('Arial', 'I', 8);
  $oPDF->Cell(80, $iAlturaLinha, ': ' . $sNome                              ,  0, 1, "L", 0);
  $oPDF->SetFont('Arial', 'B', 8);
  $oPDF->Cell(25, $iAlturaLinha, "ENDEREÇO"                                 ,  0, 0, "L", 0);
  $oPDF->SetFont('Arial', 'I', 8);
  $oPDF->Cell(80, $iAlturaLinha, ': ' . $sEndereco                          ,  0, 1, "L", 0);

  if ( $sOutrosDados1 != '' ) {

    $oPDF->SetFont('Arial', 'B', 8);
    $oPDF->Cell(25, $iAlturaLinha,        $sOutrosDados1, 0, 0, "L", 0);
    $oPDF->SetFont('Arial', 'I', 8);
    $oPDF->Cell(80, $iAlturaLinha, ': ' . $sOutrosDados2, 0, 1, "L", 0);
  }

  $oPDF->SetFont('Arial', 'BI', 12);
  $oPDF->Cell(191, 2, '', "B", 1, "R", 0);
  $oPDF->MultiCell(0, 20, "Valores Válidos Até a Data : " . db_formatar(date('Y-m-d'), 'd'), 0, "C", 0);
  $oPDF->SetLineWidth(0.2);
  $oPDF->SetFont('Arial', 'B', 8);

  $oPDF->Cell(07, $iAlturaLinha, "Tipo"           , 1, 0, "C", 1);
  $oPDF->Cell(60, $iAlturaLinha, "Descrição"      , 1, 0, "C", 1);
  $oPDF->Cell(18, $iAlturaLinha, "Vlr Histórico"  , 1, 0, "C", 1);
  $oPDF->Cell(18, $iAlturaLinha, "Vlr Corrigido"  , 1, 0, "C", 1);
  $oPDF->Cell(18, $iAlturaLinha, "Vlr Juros"      , 1, 0, "C", 1);
  $oPDF->Cell(18, $iAlturaLinha, "Vlr Multa"      , 1, 0, "C", 1);
  $oPDF->Cell(18, $iAlturaLinha, "Descontos"      , 1, 0, "C", 1);
  $oPDF->Cell(18, $iAlturaLinha, "Acréscimos"     , 1, 0, "C", 1);
  $oPDF->Cell(18, $iAlturaLinha, "Total"          , 1, 1, "C", 1);

  $aTotalDebito["nValorHistorico"] = 0;
  $aTotalDebito["nValorCorrigido"] = 0;
  $aTotalDebito["nValorJuros"]     = 0;
  $aTotalDebito["nValorMulta"]     = 0;
  $aTotalDebito["nValorDesconto"]  = 0;
  $aTotalDebito["nValorAcrescimo"] = 0;
  $aTotalDebito["nValorTotal"]     = 0;


  foreach ( $oDadosRelatorio->aDebitos as $oValoresDebitos) {

   $oPDF->SetFont('Arial', '', 8);
   $oPDF->Cell(7,  $iAlturaLinha, $oValoresDebitos->iTipoDebito                       , 1, 0, "L", 0);
   $oPDF->Cell(60, $iAlturaLinha, $oValoresDebitos->sDescricaoTipoDebito              , 1, 0, "L", 0);
   $oPDF->Cell(18, $iAlturaLinha, db_formatar($oValoresDebitos->nValorHistorico , 'f'), 1, 0, "R", 0);
   $oPDF->Cell(18, $iAlturaLinha, db_formatar($oValoresDebitos->nValorCorrigido , 'f'), 1, 0, "R", 0);
   $oPDF->Cell(18, $iAlturaLinha, db_formatar($oValoresDebitos->nValorJuros     , 'f'), 1, 0, "R", 0);
   $oPDF->Cell(18, $iAlturaLinha, db_formatar($oValoresDebitos->nValorMulta     , 'f'), 1, 0, "R", 0);
   $oPDF->Cell(18, $iAlturaLinha, db_formatar($oValoresDebitos->nValorDesconto  , 'f'), 1, 0, "R", 0);
   $oPDF->Cell(18, $iAlturaLinha, db_formatar($oValoresDebitos->nValorAcrescimos, 'f'), 1, 0, "R", 0);
   $oPDF->Cell(18, $iAlturaLinha, db_formatar($oValoresDebitos->nValorTotal     , 'f'), 1, 1, "R", 0);

   $aTotalDebito["nValorHistorico"] += $oValoresDebitos->nValorHistorico;
   $aTotalDebito["nValorCorrigido"] += $oValoresDebitos->nValorCorrigido;
   $aTotalDebito["nValorJuros"]     += $oValoresDebitos->nValorJuros;
   $aTotalDebito["nValorMulta"]     += $oValoresDebitos->nValorMulta;
   $aTotalDebito["nValorDesconto"]  += $oValoresDebitos->nValorDesconto;
   $aTotalDebito["nValorAcrescimo"] += $oValoresDebitos->nValorAcrescimos;
   $aTotalDebito["nValorTotal"]     += $oValoresDebitos->nValorTotal;

  }

  $oPDF->SetFont('Arial', 'B', 8);
  $oPDF->Cell(67, $iAlturaLinha, "Total"                                            , 1, 0, "C", 0);
  $oPDF->Cell(18, $iAlturaLinha, db_formatar($aTotalDebito["nValorHistorico"] , 'f'), 1, 0, "R", 0);
  $oPDF->Cell(18, $iAlturaLinha, db_formatar($aTotalDebito["nValorCorrigido"] , 'f'), 1, 0, "R", 0);
  $oPDF->Cell(18, $iAlturaLinha, db_formatar($aTotalDebito["nValorJuros"]     , 'f'), 1, 0, "R", 0);
  $oPDF->Cell(18, $iAlturaLinha, db_formatar($aTotalDebito["nValorMulta"]     , 'f'), 1, 0, "R", 0);
  $oPDF->Cell(18, $iAlturaLinha, db_formatar($aTotalDebito["nValorDesconto"]  , 'f'), 1, 0, "R", 0);
  $oPDF->Cell(18, $iAlturaLinha, db_formatar($aTotalDebito["nValorAcrescimo"] , 'f'), 1, 0, "R", 0);
  $oPDF->Cell(18, $iAlturaLinha, db_formatar($aTotalDebito["nValorTotal"]     , 'f'), 1, 1, "R", 0);
  $oPDF->SetFont('Arial', '', 8);

  if (!empty($oGet->parReceit)) {
    $oPDF->multiCell(190,4,"Receitas Selecionadas: {$oGet->parReceit}");
    $oPDF->Ln();
  }

  if ( count($oDadosRelatorio->aSuspensoes) > 0 ) {

    $oPDF->Ln(4);
    $oPDF->SetFont('Arial', 'BI', 12);
    $oPDF->Cell(0,5,'Débitos Suspensos',0,1,"C",0);
    $oPDF->Ln();

    $oPDF->SetFont('Arial', 'B', 8);
    $oPDF->Cell(7 , $iAlturaLinha, "Tipo"		       ,1,0,"C",1);
    $oPDF->Cell(60, $iAlturaLinha, "Descrição"	   ,1,0,"C",1);
    $oPDF->Cell(21, $iAlturaLinha, "Vlr Histórico"  ,1,0,"C",1);
    $oPDF->Cell(21, $iAlturaLinha, "Vlr Corrigido" ,1,0,"C",1);
    $oPDF->Cell(21, $iAlturaLinha, "Vlr Juros"	   ,1,0,"C",1);
    $oPDF->Cell(21, $iAlturaLinha, "Vlr Multa"     ,1,0,"C",1);
    $oPDF->Cell(21, $iAlturaLinha, "Vlr Desconto"  ,1,0,"C",1);
    $oPDF->Cell(21, $iAlturaLinha, "Vlr Total"	   ,1,1,"C",1);

    $oPDF->SetFont('Arial', '', 8);

    $aTotalSuspensao['vlrhis'] = 0;
    $aTotalSuspensao['vlrcor'] = 0;
    $aTotalSuspensao['vlrjur'] = 0;
    $aTotalSuspensao['vlrmul'] = 0;
    $aTotalSuspensao['vlrdes'] = 0;
    $aTotalSuspensao['vlrtot'] = 0;


    foreach ($oDadosRelatorio->aSuspensoes as $oValoresSuspencoes  ) {

      $oPDF->Cell(7 , $iAlturaLinha, $oValoresSuspencoes->k00_tipo                              , 1, 0, "C", 0);
      $oPDF->Cell(60, $iAlturaLinha, substr($oValoresSuspencoes->k00_descr, 0, 35)	            , 1, 0, "L", 0);
      $oPDF->Cell(21, $iAlturaLinha, db_formatar($oValoresSuspencoes->valor_historico  , 'f')   , 1, 0, "R", 0);
      $oPDF->Cell(21, $iAlturaLinha, db_formatar($oValoresSuspencoes->valor_corrigido  , 'f')   , 1, 0, "R", 0);
      $oPDF->Cell(21, $iAlturaLinha, db_formatar($oValoresSuspencoes->valor_juros      , 'f')   , 1, 0, "R", 0);
      $oPDF->Cell(21, $iAlturaLinha, db_formatar($oValoresSuspencoes->valor_multas     , 'f')   , 1, 0, "R", 0);
      $oPDF->Cell(21, $iAlturaLinha, db_formatar($oValoresSuspencoes->valor_descontos  , 'f')   , 1, 0, "R", 0);
      $oPDF->Cell(21, $iAlturaLinha, db_formatar($oValoresSuspencoes->valor_total      ,'f')    , 1, 1, "R", 0);

      $aTotalSuspensao['vlrhis'] += $oValoresSuspencoes->valor_historico;
      $aTotalSuspensao['vlrcor'] += $oValoresSuspencoes->valor_corrigido;
      $aTotalSuspensao['vlrjur'] += $oValoresSuspencoes->valor_juros;
      $aTotalSuspensao['vlrmul'] += $oValoresSuspencoes->valor_multas;
      $aTotalSuspensao['vlrdes'] += $oValoresSuspencoes->valor_descontos;
      $aTotalSuspensao['vlrtot'] += $oValoresSuspencoes->valor_total;

    }

    $oPDF->SetFont('Arial', 'B', 8);
    $oPDF->Cell(67, $iAlturaLinha, 'Total'		                                					  , 1, 0, "C", 0);
    $oPDF->Cell(21, $iAlturaLinha, db_formatar($aTotalSuspensao['vlrhis'], 'f') , 1, 0, "R", 0);
    $oPDF->Cell(21, $iAlturaLinha, db_formatar($aTotalSuspensao['vlrcor'], 'f') , 1, 0, "R", 0);
    $oPDF->Cell(21, $iAlturaLinha, db_formatar($aTotalSuspensao['vlrjur'], 'f') , 1, 0, "R", 0);
    $oPDF->Cell(21, $iAlturaLinha, db_formatar($aTotalSuspensao['vlrmul'], 'f') , 1, 0, "R", 0);
    $oPDF->Cell(21, $iAlturaLinha, db_formatar($aTotalSuspensao['vlrdes'], 'f') , 1, 0, "R", 0);
    $oPDF->Cell(21, $iAlturaLinha, db_formatar($aTotalSuspensao['vlrtot'], 'f') , 1, 1, "R", 0);
    $oPDF->SetFont('Arial', '', 8);

  }

  $oPDF->Output();
} catch (Exception $eErro) {

	db_redireciona("db_erros.php?fechar=true&db_erro=[1] - {$eErro->getMessage()}");
}