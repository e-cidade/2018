<?php
/**
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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("classes/db_assenta_classe.php"));
include(modification("classes/db_tipoasse_classe.php"));
include(modification("dbforms/db_funcoes.php"));

use ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Horas\BaseHora;

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$classenta  = new cl_assenta;
$cltipoasse = new cl_tipoasse;
$classentamentofuncional         = new cl_assentamentofuncional;
$oDaoAssentamentoJustificativa   = new cl_assentamentojustificativaperiodo;
$oDaoAssentamentoHoraExtraManual = new cl_assentamentohoraextra;

$db_botao = false;
$db_opcao = 33;

if(isset($excluir)){


  try {

    /**
     * Verificamos se o assentamento é de rra, e se já foi realizado o lançamento do mesmo
     * no ponto.
     */
    /**
     * Valida datas para assentamento de substituição
     */
    $rsBuscaTipoAssentamento = $cltipoasse->sql_record($cltipoasse->sql_query_file( null, "*", null, "h12_assent = '{$h12_assent}'"));

    if(!$rsBuscaTipoAssentamento || pg_num_rows($rsBuscaTipoAssentamento) <=0 ) {
      throw new Exception("Não foi possível pesquisar o tipo de assentamento.");
    }

    $oTipoasse = db_utils::fieldsMemory($rsBuscaTipoAssentamento, 0);
    if ($oTipoasse->h12_natureza == AssentamentoRRA::CODIGO_NATUREZA) {


      $oAssentamentoRRA = new AssentamentoRRA($h16_codigo);
      $oLancamentoRRA   = LancamentoRRARepository::getInstanciasByAssentamento($oAssentamentoRRA);

      /**
       * Verificamos se o assentamento já não esta vinculado com um lote de registros de ponto
       * se estiver, não permite a exclusão.
       */
      $oDaoAssentaLoteRegistroPonto = new cl_assentaloteregistroponto();
      $sSqlAssentaLoteRegistroPonto = $oDaoAssentaLoteRegistroPonto->sql_query_file(null, "rh160_sequencial", null, "rh160_assentamento = {$h16_codigo}");
      
      
      $rsAssentaLoteRegistroPonto = db_query($sSqlAssentaLoteRegistroPonto);

      if (pg_num_rows($rsAssentaLoteRegistroPonto) > 0) {
        throw new BusinessException("Assentamento já possuí evento financeiro, exclusão não permitida.");
      }
    }
    db_inicio_transacao();
    $db_opcao = 3;

    $oPeriodoAquisitivoAssentamento = PeriodoAquisitivoAssentamento::getPeriodoAquisitivoAssentamento(new Assentamento( $h16_codigo ));

    if ($oPeriodoAquisitivoAssentamento) {
      $oPeriodoAquisitivoAssentamento->excluir();
    }
    
    /**
     * Tratamento para exclusão de assentamentos de substituição
     */
    $oDaoAssentamentoSubstituicao = new cl_assentamentosubstituicao();
    $rsAssentamentoSubstituicao   = $oDaoAssentamentoSubstituicao->sql_record($oDaoAssentamentoSubstituicao->sql_query_file($h16_codigo));

    if($rsAssentamentoSubstituicao && $oDaoAssentamentoSubstituicao->numrows > 0){
      $oDaoAssentamentoSubstituicao->excluir($h16_codigo);
    }

    /**
     * Tratamento para exclusão de assentamentos com atributos dinamicos
     */
    // Exclui os valores dos atributos dinâmicos vinculados ao assentamento
    $sWhereAtributoDinamicoValor  = " db110_cadattdinamicovalorgrupo in (select h80_db_cadattdinamicovalorgrupo";
    $sWhereAtributoDinamicoValor .= "                                      from assentadb_cadattdinamicovalorgrupo";
    $sWhereAtributoDinamicoValor .= "                                     where  h80_assenta = {$h16_codigo})";
    $oDaoAtributoDinamicoValor = new cl_db_cadattdinamicoatributosvalor();
    $oDaoAtributoDinamicoValor->excluir(null, $sWhereAtributoDinamicoValor);

    if($oDaoAtributoDinamicoValor->erro_status == '0') {
      throw new DBException("Ocorreu um erro ao excluir os valores dos atributos dinâmicos vinculados ao assentamento.");
    }


    // Exclui vínculo entre o valor dos atributos dinâmicos com o assentamento
    $oDaoAssentaAttr = new cl_assentadb_cadattdinamicovalorgrupo();
    $oDaoAssentaAttr->excluir(null,null, "h80_assenta = $h16_codigo" );

    if($oDaoAssentaAttr->erro_status == '0') {
      throw new DBException("Ocorreu um erro ao excluir o vínculo entre os valores dos atributos dinâmicos e o assentamento.");
    }

    /**
     * Verificamos a configuração se há tipo de assentamentos do RH que geram afastamentos do pessoal
     * se houver excluímos o afastamento vinculado
     */
    $oAssentamento  = AssentamentoRepository::getInstanceByCodigo($h16_codigo);
    $aListaInformacoesExternas = InformacoesExternasTipoAssentamento::getTipoAssentamentoConfiguradosPorCompetencia(DBPessoal::getCompetenciaFolha());

    if(is_array($aListaInformacoesExternas)){

      $aTiposAssentamentoConfigurados = array();
      foreach ($aListaInformacoesExternas as $oInformacoesExternas) {
        $aTiposAssentamentoConfigurados[] = $oInformacoesExternas->getTipoAssentamento()->getCodigo();
      }

      if( in_array($oAssentamento->getTipoAssentamento(), $aTiposAssentamentoConfigurados) ) {

        $aAfastaAssenta = AfastaAssentaRepository::getAfastamentosPorAssentamento($oAssentamento);

        if(!is_array($aAfastaAssenta)) {
          throw new BusinessException("Não foi possível buscar o vínculo entre assentamento e afastamento.");
        }

        $oAfastamento   = $aAfastaAssenta[0];
        $oAfastaAssenta = new AfastaAssenta($oAssentamento, $oAfastamento);

        /**
         * Excluímos o vínculo entre assentamentos e afastamentos
         */
        if(!$oAfastaAssenta->remove()) {
          throw new BusinessException("Erro ao excluir o vínculo entre o assentamento e afastamento.");
        }

        /**
         * Excluímos o afastamento que foi originado a partir do assentamento
         */
        if(!AfastamentoRepository::remove($oAfastamento)) {
          throw new BusinessException("Erro ao excluir o afastamento.");
        }
      }
    }

    /**
     * Tratamento para exclusão de assentamentos de RRA
     */
    $oDaoAssentamentoRRA = new cl_assentamentorra();
    $rsAssentamentoRRA   = $oDaoAssentamentoRRA->sql_record($oDaoAssentamentoRRA->sql_query_file(null, "*", null, " h83_assenta=".$h16_codigo));

    if($rsAssentamentoRRA && $oDaoAssentamentoRRA->numrows > 0){

      RRARepository::excluirLancamentosPorCodigoAssentamento($h16_codigo);
      $oDaoAssentamentoRRA->excluir(null, " h83_assenta=".$h16_codigo);
    }

    /**
     * Tratamento para exclusão de assentamentos de justificativa
     */
    $oDaoAssentamentoJustificativa = new cl_assentamentojustificativaperiodo;
    $rsAssentamentoJustificativa   = $oDaoAssentamentoJustificativa->sql_record($oDaoAssentamentoJustificativa->sql_query_file($h16_codigo));
    
    if($rsAssentamentoJustificativa && $oDaoAssentamentoJustificativa->numrows > 0){

      $oDaoAssentamentoJustificativa->excluir(null, null, "rh206_codigo = {$h16_codigo}");

      if($oDaoAssentamentoJustificativa->erro_status == '0') {
        throw new DBException($oDaoAssentamentoJustificativa->erro_msg);
      }
    }

    /**
     * Verificamos se o assentamento foi gerado a partir de um processamento de férias, caso seja e as
     * férias já foram pagas, o assentamento não pode ser excluido.
     */
    $oDaoRhFeriasPeriodosAssentamento   = new cl_rhferiasperiodoassentamento();
    $sWhereRhFeriasPeriodosAssentamento = "rh169_assenta = {$h16_codigo} and rh110_situacao <> 0";
    $sSqlRhFeriasPeriodosAssentamento   = $oDaoRhFeriasPeriodosAssentamento->sql_query (null,"*", null, $sWhereRhFeriasPeriodosAssentamento);
    $rsRhFeriasPeriodosAssentamento     = db_query($sSqlRhFeriasPeriodosAssentamento);

    if (!$rsRhFeriasPeriodosAssentamento) {
      throw new DBException("Ocorreu um erro ao verificar os periodos de assentamentos.");
    }

    if (pg_num_rows($rsRhFeriasPeriodosAssentamento) > 0) {
      throw new BusinessException("Assentamento vinculado a férias já processadas no pessoal.");
    }
    /**
     * Alteramos as datas em que o ponto é utilizado.
     */    
    $oDaoPontoEletronicoData = new cl_pontoeletronicoarquivodata();
    $sSqlDadosPonto = $oDaoPontoEletronicoData->sql_query_file(null, "*", null, "rh197_afastamento = {$h16_codigo}");
    $rsDadosPonto   = db_query($sSqlDadosPonto);
    if (!$rsDadosPonto) {
      throw new BusinessException("Erro ao pesquiser batidas do ponto eletrônico.");
    }
    db_utils::makeCollectionFromRecord($rsDadosPonto, function($dados) use ($oDaoPontoEletronicoData) {

      $oDaoPontoEletronicoData->rh197_sequencial             = $dados->rh197_sequencial;
      $oDaoPontoEletronicoData->rh197_pontoeletronicoarquivo = $dados->rh197_pontoeletronicoarquivo;
      $oDaoPontoEletronicoData->rh197_data                   = $dados->rh197_data;
      $oDaoPontoEletronicoData->rh197_matricula              = $dados->rh197_matricula;
      $oDaoPontoEletronicoData->rh197_horas_falta            = $dados->rh197_horas_falta;
      $oDaoPontoEletronicoData->rh197_horas_trabalhadas      = $dados->rh197_horas_trabalhadas;  
      $oDaoPontoEletronicoData->rh197_horas_extras_50_d      = $dados->rh197_horas_extras_50_d;
      $oDaoPontoEletronicoData->rh197_horas_extras_75_d      = $dados->rh197_horas_extras_75_d;
      $oDaoPontoEletronicoData->rh197_horas_extras_100_d     = $dados->rh197_horas_extras_100_d;
      $oDaoPontoEletronicoData->rh197_horas_adicinal_noturno = $dados->rh197_horas_adicinal_noturno;
      $oDaoPontoEletronicoData->rh197_pis                    = $dados->rh197_pis;
      $oDaoPontoEletronicoData->rh197_horas_extras_50_n      = $dados->rh197_horas_extras_50_n;
      $oDaoPontoEletronicoData->rh197_horas_extras_75_n      = $dados->rh197_horas_extras_75_n;
      $oDaoPontoEletronicoData->rh197_horas_extras_100_n     = $dados->rh197_horas_extras_100_n;
      $oDaoPontoEletronicoData->rh197_afastamento = 'null';
      $oDaoPontoEletronicoData->alterar($dados->rh197_sequencial);
      if ($oDaoPontoEletronicoData->erro_status == 0) {
        throw new BusinessException("Erro ao salvar dados do dia de trabalho.");
      }
    });
    $oDaoRhFeriasPeriodosAssentamento->excluir(null, "rh169_assenta = {$h16_codigo}");

    if ($oTipoasse->h12_natureza == AssentamentoRRA::NATUREZA_HE_MANUAL) {
      if(!$oDaoAssentamentoHoraExtraManual->excluir(null, "h17_assenta = {$h16_codigo}")) {
        throw new DBException($$oDaoAssentamentoHoraExtraManual->erro_msg);
        
      }
    }
    
    /**
     * Exclui da tabela assenta.
     */
    $oDaoConfiguracoesDatasEfetividade   = new cl_configuracoesdatasefetividade();
    $sWhereConfiguracoesDatasEfetividade = "rh186_processado is true AND rh186_instituicao = " . db_getsession("DB_instit");

    if (empty($h16_dtterm)) {
      $sWhereConfiguracoesDatasEfetividade .= " and '{$h16_dtconc}' between rh186_datainicioefetividade and rh186_datafechamentoefetividade";
    } else {
      $sWhereConfiguracoesDatasEfetividade .= " and (('{$h16_dtconc}' between rh186_datainicioefetividade and rh186_datafechamentoefetividade) or ('{$h16_dtterm}' between rh186_datainicioefetividade and rh186_datafechamentoefetividade))";
    }

    $sSqlConfiguracoesDatasEfetividade  = $oDaoConfiguracoesDatasEfetividade->sql_query_file(null, "*", null, $sWhereConfiguracoesDatasEfetividade);
    $rsSqlConfiguracoesDatasEfetividade = db_query($sSqlConfiguracoesDatasEfetividade);
    if (pg_num_rows($rsSqlConfiguracoesDatasEfetividade) > 0) {

     $dadosEfetividade =  db_utils::fieldsMemory($rsSqlConfiguracoesDatasEfetividade, 0);
     $periodo          = trim(db_formatar($dadosEfetividade->rh186_datainicioefetividade, 'd')) ." a ". trim(db_formatar($dadosEfetividade->rh186_datafechamentoefetividade, 'd'));
     $sMensagem        = "O período de efetividade {$periodo} já foi processado.\\nPara realizar manutenções em assentamentos nesse período, ";
     $sMensagem       .= "reabra o período de efetividade em Procedimentos > Efetividade > Reabrir Período.";
     $classenta->erro_msg    = $sMensagem;
     $classenta->erro_status = '1';

    } else {

      $classentamentofuncional->excluir($h16_codigo);
      if($classentamentofuncional->erro_status =='0') {
        throw new DBException($classentamentofuncional->erro_msg);
      }

      $classenta->excluir($h16_codigo);
      if($classenta->erro_status =='0') {
        throw new DBException($classenta->erro_msg);
      }

    }


  } catch (Exception $oException) {

    db_fim_transacao(true);
    $paginaRedirecionamento = $_SERVER['PHP_SELF'];
    $paginaRedirecionamento .= "?";

    if(!empty($_SERVER["QUERY_STRING"])) {
      $paginaRedirecionamento .= $_SERVER['QUERY_STRING'];
      $paginaRedirecionamento .= "&";
    }

    $paginaRedirecionamento .= "=".$oException->getMessage();
    $classenta->erro_status  = "1";
    $msg                     = $oException->getMessage();
  }

  db_fim_transacao();

  if ($classenta->erro_status != "0") {
    $h12_codigo = $h12_assent = $h16_assent = $h12_natureza = '';
  }
}else if(isset($chavepesquisa)){
  $db_opcao = 3;
  $result = $classenta->sql_record($classenta->sql_query($chavepesquisa));
  $classentamentofuncional = new cl_assentamentofuncional;
  $rsAssentamentoFuncional = db_query($classentamentofuncional->sql_query($chavepesquisa));
  $sOpcaoAssentamento      = 1;

  if($rsAssentamentoFuncional && pg_num_rows($rsAssentamentoFuncional) > 0) {
    $sOpcaoAssentamento    = 2;
  }
  db_fieldsmemory($result,0);
  $db_botao = true;

  switch ($h12_natureza) {

    case Assentamento::NATUREZA_JUSTIFICATIVA:
      $rsAssentamentoJustificativa   = $oDaoAssentamentoJustificativa->sql_record($oDaoAssentamentoJustificativa->sql_query_file($h16_codigo));
      
      if($rsAssentamentoJustificativa && $oDaoAssentamentoJustificativa->numrows > 0) {

        db_utils::makeCollectionFromRecord($rsAssentamentoJustificativa, function($oRetornoJustificativasPeriodo) use (&$periodoJustificativa1, &$periodoJustificativa2, &$periodoJustificativa3) {

          switch ($oRetornoJustificativasPeriodo->rh206_periodo) {
            case 1:
              $periodoJustificativa1 = $oRetornoJustificativasPeriodo->rh206_periodo;
              break;

            case 2:
              $periodoJustificativa2 = $oRetornoJustificativasPeriodo->rh206_periodo;
              break;

            case 3:
              $periodoJustificativa3 = $oRetornoJustificativasPeriodo->rh206_periodo;
              break;
          }
        });
      }
      break;
     
    case Assentamento::NATUREZA_HE_MANUAL:
      $oDaoAssentamentoHoraExtraManual  = new cl_assentamentohoraextra;
      $whereAssentamentoHoraExtraManual = "h17_assenta = {$chavepesquisa}";
      $sqlAssentamentoHoraExtraManual   = $oDaoAssentamentoHoraExtraManual->sql_query_file(null, "*", 'h17_tipo', $whereAssentamentoHoraExtraManual);;
      $rsAssentamentoHoraExtraManual    = db_query($sqlAssentamentoHoraExtraManual);
      
      if($rsAssentamentoHoraExtraManual && pg_num_rows($rsAssentamentoHoraExtraManual) > 0) {
        db_utils::makeCollectionFromRecord($rsAssentamentoHoraExtraManual, function($oRetornoHorasExtrasManuais) use (
          &$horaExtraManual50Diurna,
          &$horaExtraManual50Noturna,
          &$horaExtraManual75Diurna,
          &$horaExtraManual75Noturna,
          &$horaExtraManual100Diurna,
          &$horaExtraManual100Noturna
        ){
          switch ($oRetornoHorasExtrasManuais->h17_tipo) {
            case BaseHora::HORAS_EXTRA50:
              $horaExtraManual50Diurna  = $oRetornoHorasExtrasManuais->h17_hora;
              break;

            case BaseHora::HORAS_EXTRA75:
              $horaExtraManual75Diurna  = $oRetornoHorasExtrasManuais->h17_hora;
              break;

            case BaseHora::HORAS_EXTRA100:
              $horaExtraManual100Diurna  = $oRetornoHorasExtrasManuais->h17_hora;
              break;

            case BaseHora::HORAS_EXTRA50_NOTURNA:
              $horaExtraManual50Noturna = $oRetornoHorasExtrasManuais->h17_hora;
              break;

            case BaseHora::HORAS_EXTRA75_NOTURNA:
              $horaExtraManual75Noturna  = $oRetornoHorasExtrasManuais->h17_hora;
              break;

            case BaseHora::HORAS_EXTRA100_NOTURNA:
              $horaExtraManual100Noturna = $oRetornoHorasExtrasManuais->h17_hora;
              break;

          }
        });
      }
      break;
  }
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/dates.js"></script>
<script language="javascript" type="text/javascript" src="scripts/widgets/DBInputHora.widget.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body>
  <center>
    <?php	include(modification("forms/db_frmassenta.php")); ?>
  </center>
  <?php db_menu(); ?>
  <?php
    switch ($h12_natureza) {
      case Assentamento::NATUREZA_HE_MANUAL:
  ?>
  <script type="text/javascript">
    $$('.hora-extra-manual')[0].style.display = 'table-row';
  </script>
  <?php 
      break;
    }
  ?>
</body>
</html>
<?
if(isset($excluir)){
  if($classenta->erro_status=="0"){
    $classenta->erro(true,false);
  }else{
    $classenta->erro(true,false);
    unset($msg);
    if($sOpcaoAssentamento == 1) {
      db_redireciona("rec1_assenta003.php?iTipoFuncionamento=1");
    }
    db_redireciona("rec1_assenta003.php");
  }
}
if(isset($msg)){
  db_msgbox($msg);
}
if($db_opcao==33){
  echo "<script>document.form1.pesquisar.click();</script>";
}

?>
<script>
js_tabulacaoforms("form1","excluir",true,1,"excluir",true);
</script>
