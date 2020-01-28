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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("classes/db_assenta_classe.php"));
require_once(modification("classes/db_tipoasse_classe.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("std/DBDate.php"));

use ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Horas\BaseHora;
use ECidade\RecursosHumanos\RH\Assentamento\AssentamentoHoraExtraManual;

db_postmemory($_POST);

$classenta  = new cl_assenta;
$cltipoasse = new cl_tipoasse;
$db_opcao   = 1;
$db_botao   = true;

if( !isset($h12_tipo) ) {

  $h12_tipo   = "";
  $h12_tipefe = "";
}

/**
 * Inclui assentamento/afastamento
 */
if ( isset($incluir) ) {

  $sMensagens = "recursoshumanos.rh.rec1_assenta.";

  db_inicio_transacao();

  try {

    if(!isset($h16_perc)) {
      $h16_perc = '0';
    }
    
    if(!isset($h16_hora)) {
      $h16_hora = '0:00';
    }

    /**
     * h12_assent, no formulario esta como h12_codigo
     */
    $sCodigoAfastamento = trim($h12_codigo);

    /**
     * Validações quando o tipo de assentamento for de reajuste salarial
     */
    $rsVerificaReajuste = $cltipoasse->sql_record( $cltipoasse->sql_query_file( $h16_assent, "h12_tiporeajuste") );

    if ($cltipoasse->numrows > 0) {
      $oTipoasse = db_utils::fieldsMemory($rsVerificaReajuste, 0);

      if ($oTipoasse->h12_tiporeajuste != 0) {

        /**
         * Não permite incusão para servidores ativos ou rescindidos
         */
        require_once(modification("model/pessoal/Servidor.model.php"));

        $oServidor = new Servidor($h16_regist);

        if ($oServidor->isAtivo()) {
          /**
           * Limpa o campo tipoasse
           */
          $h12_codigo = '';
          throw new Exception( _M($sMensagens . "reajuste_servidor_ativo") );
        }

        if ($oServidor->isRescindido()) {
          /**
           * Limpa o campo tipoasse
           */
          $h12_codigo = '';
          throw new Exception( _M($sMensagens . "reajuste_servidor_rescindido") );
        }

        /**
         * Verifica se já foi lançado ountro assentamento com o campo tipo de rescisão informado no tipoasse
         */
        $sWhere  = "";

        $sWhere  = " h12_tiporeajuste is not null and h12_tiporeajuste <> 0 ";
        $sWhere .= " and h16_regist = {$h16_regist}";

        $classenta->sql_record( $classenta->sql_query( null, "h16_codigo", null, $sWhere) );

        if ($classenta->numrows > 0) {

          /**
           * Limpa o campo tipoasse
           */
          $h12_codigo = '';
          throw new Exception( _M($sMensagens . "ja_possui_reajuste_salarial") );
        }
      }
    }

    $oDataInicial = new DBDate($h16_dtconc);
    $dDataInicial = $oDataInicial->getDate();

    /**
     * Campo data final no formulario vazia
     * - procura afastamentos com data inicial maior ou igual e com data final menor ou igual
     * - ou com data final vazia(afastamento em aberto)
     * - ou com data inicial do formulario menor ou igual a do banco (afastamento com data posterior ja cadastrado)
     */
    $sWhereDatas  = " (                                                                                      ";
    $sWhereDatas .= "     ('{$dDataInicial}'::date >= h16_dtconc and '{$dDataInicial}'::date <= h16_dtterm ) ";
    $sWhereDatas .= "  or (h16_dtterm is null)                                                               ";
    $sWhereDatas .= "  or ( '{$dDataInicial}'::date <= h16_dtconc )                                          ";
    $sWhereDatas .= " )                                                                                      ";

    /**
     * Caso campo com data final nao estiver vazio procura afastamento entre data inicial e final
     * ou com data final vazia(afastamento em aberto)
     */
    if ( !empty($h16_dtterm) ) {

      $oDataFinal  = new DBDate($h16_dtterm);
      $dDataFinal  = $oDataFinal->getDate();
      $sWhereDatas = " (h16_dtconc, case when h16_dtterm is null then '3000-12-31'::date else h16_dtterm+1 end) overlaps ('{$dDataInicial}'::date, '{$dDataFinal}'::date) ";
    }

    /**
     * Valida datas para assentamento de substituição
     */
    $rsBuscaTipoAssentamento = $cltipoasse->sql_record($cltipoasse->sql_query_file( null, "*", null, "h12_assent = '{$h12_assentdescr}'"));

    if(!$rsBuscaTipoAssentamento || pg_num_rows($rsBuscaTipoAssentamento) <=0 ) {
      throw new Exception("Não foi possível pesquisar o tipo de assentamento.");
    } else {
      $oTipoasse = db_utils::fieldsMemory($rsBuscaTipoAssentamento, 0);
    }

    if($oTipoasse->h12_natureza == Assentamento::NATUREZA_AUTORIZA_HORA_EXTRA){

      $h16_dtterm = $h16_dtconc;

      /**
       * Verifica se já existe um assentamento de autorização para a matrícula na data informada
       */

      $sSqlVerificaExistenciaAssentamento = $classenta->sql_query_tipo(null,'*',null,"h16_regist={$h16_regist} AND h16_dtconc='{$dDataInicial}' AND h12_natureza=".Assentamento::NATUREZA_AUTORIZA_HORA_EXTRA."");
      $rsAssentamentoAutorizacao          = $classenta->sql_record($sSqlVerificaExistenciaAssentamento);

      if(!empty($rsAssentamentoAutorizacao) || pg_num_rows($rsAssentamentoAutorizacao) > 0){
        throw new Exception('Já existe um assentamento de autorização de hora extra para este servidor na data informada.\nRealize a alteração no assentamento existente.');
      }

    }
    elseif($oTipoasse->h12_natureza == AssentamentoSubstituicao::CODIGO_NATUREZA) {

      /**
       * Valida se data final está em branco
       */
      if (empty($h16_dtterm)) {
        throw new Exception("A data final deve ser preenchida.");
      }

      /**
       * Valida se data final é menor que data inicial
       */
      if ( str_replace("-", "", $dDataFinal) < str_replace("-", "", $dDataInicial) ) {
        throw new Exception("A data final não pode ser menor que a data inicial.");
      }

      /**
       * Busca o mês e ano das data inicial e final para compará-las
       */
      $sMesAnoDataInicial = preg_replace("/(\d{4}\-\d{2}).*/", "$1", $dDataInicial);
      $sMesAnoDataFinal   = preg_replace("/(\d{4}\-\d{2}).*/", "$1", $dDataFinal);

      if($sMesAnoDataFinal != $sMesAnoDataInicial) {
        throw new Exception("A data final deve estar no mesmo mês e ano da data inicial.");
      }
    }

    /**
     * Antes de alterar verifica se já nao tem afastamento cadastrado para o servidor no mesmo periodo
     *
     * h16_dtterm - data de termino
     * h16_dtconc - data concessao
     */
    $sWhereValidacao  = " case                                                              ";
    $sWhereValidacao .= "   when exists ( select 1                                          ";
    $sWhereValidacao .= "                   from tipoasse                                   ";
    $sWhereValidacao .= "                  where h12_codigo = '{$sCodigoAfastamento}'       ";
    $sWhereValidacao .= "                  and h12_tipo = 'A'                               ";
    $sWhereValidacao .= "               )                                                   ";
    $sWhereValidacao .= "     then (     h16_regist  = {$h16_regist}                        ";
    $sWhereValidacao .= "            and h12_tipo    = 'A'                                  ";
    $sWhereValidacao .= "            and ({$sWhereDatas})                                   ";
    $sWhereValidacao .= "          )                                                        ";
    $sWhereValidacao .= "     else false                                                    ";
    $sWhereValidacao .= " end                                                               ";

    if ( isset($sOpcaoAssentamento) &&  $sOpcaoAssentamento == 2) {

      $sWhereValidacao .= " and h16_codigo in (select rh193_assentamento_funcional         ";
      $sWhereValidacao .= "                      from assentamentofuncional) ";
    }

    $sSqlValidacao    = $classenta->sql_query(null, '*', 'h16_dtconc', $sWhereValidacao);
    $rsValidacao      = $classenta->sql_record($sSqlValidacao);

    /**
     * Econtrou afastamento para o periodo informado no formulario
     * Lanca excessao
     */
    if ( $classenta->numrows > 0 && $h12_vinculaperiodoaquisitivo == 'f') {

      $sMensagemErro  = "Servidor já possui assentamento cadastrado para este período.";
      $aAssentamentos = db_utils::getCollectionByRecord($rsValidacao);

      /**
       * Percorre assentamentos encontrados para montar mensagem de erro
       */
      foreach( $aAssentamentos as $oAssentamento ) {

        /**
         * Encontrou afastamento em aberto
         */
        if ( $oAssentamento->h16_dtterm == '' ) {
          $sMensagemErro = "Servidor com afastamento em aberto.";
        }

        $oDataInicial = new DBDate($oAssentamento->h16_dtconc);
        $sDataInicial = $oDataInicial->getDate(DBDate::DATA_PTBR);

        $sDataFinal   = null;

        if ( !empty($oAssentamento->h16_dtterm) ) {

          $oDataFinal = new DBDate($oAssentamento->h16_dtterm);
          $sDataFinal = $oDataFinal->getDate(DBDate::DATA_PTBR);
        }

        $sMensagemErro .= "\n\nAfastamento encontrado: {$oAssentamento->h12_assent}";
        $sMensagemErro .= "\nData inicial: {$sDataInicial}";
        $sMensagemErro .= "\nData final  : {$sDataFinal}";
      }

      throw new Exception($sMensagemErro);
    }

    $oDaoConfiguracoesDatasEfetividade   = new cl_configuracoesdatasefetividade();
    $sWhereConfiguracoesDatasEfetividade = "rh186_processado is true AND rh186_instituicao = " . db_getsession("DB_instit");

    if (empty($h16_dtterm)) {
      $sWhereConfiguracoesDatasEfetividade .= " and '{$h16_dtconc}' between rh186_datainicioefetividade and rh186_datafechamentoefetividade";
    } else {
      $sWhereConfiguracoesDatasEfetividade .= " and (('{$h16_dtconc}' between rh186_datainicioefetividade and rh186_datafechamentoefetividade) or ('{$h16_dtterm}' between rh186_datainicioefetividade and rh186_datafechamentoefetividade))";
    }

    $sSqlConfiguracoesDatasEfetividade  = $oDaoConfiguracoesDatasEfetividade->sql_query_file(null, "*", null, $sWhereConfiguracoesDatasEfetividade);
    $rsSqlConfiguracoesDatasEfetividade = db_query($sSqlConfiguracoesDatasEfetividade);
    if (pg_num_rows($rsSqlConfiguracoesDatasEfetividade) > 0 && !isset($lAssentamentoFuncional)) {

      $dadosEfetividade =  db_utils::fieldsMemory($rsSqlConfiguracoesDatasEfetividade, 0);
      $periodo          = trim(db_formatar($dadosEfetividade->rh186_datainicioefetividade, 'd')) ." a ". trim(db_formatar($dadosEfetividade->rh186_datafechamentoefetividade, 'd'));
      $sMensagem        = "O período de efetividade {$periodo} já foi processado.\nPara realizar manutenções em assentamentos nesse período, ";
      $sMensagem       .= "reabra o período de efetividade em Procedimentos > Efetividade > Reabrir Período.";
      throw new BusinessException($sMensagem);
    }

    /**
     * Inclui assentamento/afastamento
     */
    $oAssentamento = new Assentamento();

    switch ($oTipoasse->h12_natureza) {

      /**
       * Verifica se já tem assentamento de justificativa para
       * o período, caso exista irá alterar o existente
       */
      case Assentamento::NATUREZA_JUSTIFICATIVA:

        $existenciaAssentamentoJustificativa = false;

        $oAssentamento = AssentamentoRepository::getAssentamentoJustificativaPorTipoServidorPeriodo(
          $oTipoasse->h12_codigo,
          $h16_regist,
          new DBDate($h16_dtconc),
          (!empty($h16_dtterm) ? new DBDate($h16_dtterm) : null)
        );

        if(!empty($oAssentamento)) {
          $existenciaAssentamentoJustificativa = true;
        }

        if(empty($oAssentamento)) {
          $oAssentamento = new Assentamento();
        }

        break;

      case Assentamento::NATUREZA_HE_MANUAL:

        if(empty($h16_dtterm)) {
          throw new BusinessException(_M($sMensagens . "data_fim_assentamentos_hora_extra_manual"));
        }

        if ( isset($sOpcaoAssentamento) &&  $sOpcaoAssentamento == 2) {
          $existeAssentamentoHoraExtraManual = AssentamentoHoraExtraManual::existeAssentamentoHoraExtraHistoricoFuncionalNaData(
            new DBDate($h16_dtconc),
            ServidorRepository::getInstanciaByCodigo($h16_regist)
          );
        } else {
          $existeAssentamentoHoraExtraManual = AssentamentoHoraExtraManual::existeAssentamentoHoraExtraEfetividadeNaData(
            new DBDate($h16_dtconc),
            ServidorRepository::getInstanciaByCodigo($h16_regist)
          );
        }

        if($existeAssentamentoHoraExtraManual) {
          throw new DBException("Já existe assentamento para o servidor na data informada.");
        }
        break;

      default:
        $existenciaAssentamentoJustificativa = false;
        break;
    }

    if(isset($existenciaAssentamentoJustificativa) && $existenciaAssentamentoJustificativa === false) {

      /**
       * Se for utilizado a rotina Importar Assentamentos para vida fucional, irá salvar os dados do novo assentamento
       * na tabela assentamentofuncional.
       */
      if ( isset($sOpcaoAssentamento) &&  $sOpcaoAssentamento == 2) {

        $oAssentamento = new AssentamentoFuncional();
        if(isset($lAssentamentoFuncional)) {
          $oAssentamento->setAssentamentoEfetividade(new Assentamento($iCodigoEfetividade));
        }
      }
    }

    $oAssentamento->setMatricula($h16_regist);
    $oAssentamento->setTipoAssentamento($h16_assent);
    $oAssentamento->setDataConcessao(new DBDate($h16_dtconc));
    $oAssentamento->setHistorico($h16_histor);
    $oAssentamento->setCodigoPortaria($h16_nrport);
    $oAssentamento->setDescricaoAto($h16_atofic);
    $oAssentamento->setDias($h16_quant);
    $oAssentamento->setPercentual($h16_perc);
    $oAssentamento->setSegundoHistorico('');
    $oAssentamento->setLoginUsuario(db_getsession("DB_id_usuario"));
    $oAssentamento->setDataLancamento(date("Y-m-d",db_getsession("DB_datausu")));
    $oAssentamento->setConvertido("false");
    $oAssentamento->setAnoPortaria($h16_anoato);
    $oAssentamento->setHora($h16_hora);

    $oAssentamento->setDataTermino(null);
    if(isset($h16_dtterm) && !empty($h16_dtterm)) {
      $oAssentamento->setDataTermino(new DBDate($h16_dtterm));
    }

    if ( isset($sOpcaoAssentamento) &&  $sOpcaoAssentamento == 2) {

      if($oAssentamento instanceof \ECidade\RecursosHumanos\RH\Assentamento\AssentamentoJustificativa) {

        $oAssentamentoSalvar = new AssentamentoFuncional();

        $oAssentamentoSalvar->setCodigo($oAssentamento->getCodigo());
        $oAssentamentoSalvar->setMatricula($oAssentamento->getMatricula());
        $oAssentamentoSalvar->setTipoAssentamento($oAssentamento->getTipoAssentamento());
        $oAssentamentoSalvar->setDataConcessao($oAssentamento->getDataConcessao());
        $oAssentamentoSalvar->setHistorico($oAssentamento->getHistorico());
        $oAssentamentoSalvar->setCodigoPortaria($oAssentamento->getCodigoPortaria());
        $oAssentamentoSalvar->setDescricaoAto($oAssentamento->getDescricaoAto());
        $oAssentamentoSalvar->setDias($oAssentamento->getDias());
        $oAssentamentoSalvar->setPercentual($oAssentamento->getPercentual());
        $oAssentamentoSalvar->setSegundoHistorico($oAssentamento->getSegundoHistorico());
        $oAssentamentoSalvar->setLoginUsuario($oAssentamento->getLoginUsuario());
        $oAssentamentoSalvar->setDataLancamento($oAssentamento->getDataLancamento());
        $oAssentamentoSalvar->setConvertido($oAssentamento->isConvertido());
        $oAssentamentoSalvar->setAnoPortaria($oAssentamento->getAnoPortaria());
        $oAssentamentoSalvar->setDataTermino($oAssentamento->getDataTermino());

        $oAssentamento = $oAssentamentoSalvar;

        if(isset($lAssentamentoFuncional)) {
          $oAssentamentoSalvar->setCodigo(null);
          $oAssentamento->setAssentamentoEfetividade(new Assentamento($iCodigoEfetividade));
        }
      }

      $oAssentamentoSalvo = AssentamentoFuncionalRepository::persist($oAssentamento->persist());

    } else {
      $oAssentamentoSalvo = AssentamentoRepository::persist($oAssentamento->persist());
    }

    if(!$oAssentamentoSalvo instanceof Assentamento) {
      throw new BusinessException($oAssentamentoSalvo);
    }

    /**
     * Incluimos na tabela assenta e criamos uma relação entre os assentamentos do pessoal e do rh
     * incluendo as chaves na tabela afastaassenta
     */
    $aListaInformacoesExternas = InformacoesExternasTipoAssentamento::getTipoAssentamentoConfiguradosPorCompetencia(DBPessoal::getCompetenciaFolha());

    if(is_array($aListaInformacoesExternas)){

      $aTiposAssentamentoConfigurados = array();
      foreach ($aListaInformacoesExternas as $oInformacoesExternas) {
        $aTiposAssentamentoConfigurados[] = $oInformacoesExternas->getTipoAssentamento()->getCodigo();
      }

      if( in_array($oAssentamento->getTipoAssentamento(), $aTiposAssentamentoConfigurados) ) {

        $oServidor    = ServidorRepository::getInstanciaByCodigo($oAssentamento->getMatricula(),
          $oInformacoesExternas->getCompetencia()->getAno(),
          $oInformacoesExternas->getCompetencia()->getMes());

        $oAfastamento = new Afastamento();

        $oAfastamento->setCompetencia($oInformacoesExternas->getCompetencia());
        $oAfastamento->setServidor($oServidor);
        $oAfastamento->setDataAfastamento($oAssentamento->getDataConcessao());
        $oAfastamento->setDataRetorno($oAssentamento->getDataTermino());
        $oAfastamento->setCodigoSituacao($oInformacoesExternas->getSituacaoAfastamento());
        $oAfastamento->setDataLancamento($oAssentamento->getDataLancamento());
        $oAfastamento->setCodigoAfastamentoSefip($oInformacoesExternas->getSefip());
        $oAfastamento->setCodigoRetornoSefip($oInformacoesExternas->getCodigoRetorno());
        $oAfastamento->setObservacao($oAssentamento->getHistorico());

        $oAfastamentoSalvo = AfastamentoRepository::persist($oAfastamento);

        if(!$oAfastamentoSalvo instanceof Afastamento) {
          throw new BusinessException("Erro ao salvar afastamento na base de dados.");
        }

        $oAfastaAssenta      = new AfastaAssenta($oAssentamento, $oAfastamento);
        $oAfastaAssentaSalvo = $oAfastaAssenta->persist();

        if(!$oAfastaAssentaSalvo instanceof AfastaAssenta) {
          throw new BusinessException("Erro ao salvar vínculo entre assentamento e afastamento.");
        }

        /**
         * Realiza a proporcionalização no ponto
         */
        $oProporcionalizacaoPontoSalario = new ProporcionalizacaoPontoSalario($oServidor->getPonto(Ponto::SALARIO), $oInformacoesExternas->getSituacaoAfastamento(), $oAssentamento->getDataTermino());
        $oProporcionalizacaoPontoSalario->processar();
      }
    }

    if (!empty($h80_db_cadattdinamicovalorgrupo)) {

      $oDaoAssentaAttr = new cl_assentadb_cadattdinamicovalorgrupo();
      $oDaoAssentaAttr->h80_assenta                     = $oAssentamento->getCodigo();
      $oDaoAssentaAttr->h80_db_cadattdinamicovalorgrupo = $h80_db_cadattdinamicovalorgrupo;
      $oDaoAssentaAttr->incluir($oAssentamento->getCodigo(), $h80_db_cadattdinamicovalorgrupo);

      if ($oDaoAssentaAttr->erro_status == "0") {
        throw new Exception($oDaoAssentaAttr->erro_msg);
      }
    }

    if ($h12_vinculaperiodoaquisitivo == 't') {

      $oPeriodoAquisitivoAssentamento = new PeriodoAquisitivoAssentamento();
      $oPeriodoAquisitivoAssentamento->setAssentamento(new Assentamento($oAssentamento->getCodigo()));
      $oPeriodoAquisitivoAssentamento->setPeriodoAquisitivo(new PeriodoAquisitivoFerias($iPeriodoAquisitivo));
      $oPeriodoAquisitivoAssentamento->salvar();
    }

    switch ($oTipoasse->h12_natureza) {

      case AssentamentoSubstituicao::CODIGO_NATUREZA:

        if(empty($rh161_regist)) {
          throw new BusinessException("É necessário informar o servidor a substituir.");
        }

        $oAssentamentoSubstituicao = new AssentamentoSubstituicao($oAssentamento->getCodigo());
        $oAssentamentoSubstituicao->setSubstituido(ServidorRepository::getInstanciaByCodigo($rh161_regist, DBPessoal::getAnoFolha(), DBPessoal::getMesFolha()));

        $mResponsePersistAssentamentoSubstituicao = $oAssentamentoSubstituicao->persist();

        if($mResponsePersistAssentamentoSubstituicao !== true) {
          throw new BusinessException($mResponsePersistAssentamentoSubstituicao);
        }
        break;

      case AssentamentoRRA::CODIGO_NATUREZA:

        if(empty($h83_valor)) {
          throw new BusinessException("O campo Valor Total Devido é de preenchimento obrigatório");
        }

        if(empty($h83_meses)) {
          throw new BusinessException("O campo Número de Meses é de preenchimento obrigatório");
        }

        $oAssentamentoRRA = new AssentamentoRRA();
        $oAssentamentoRRA->setCodigo($oAssentamento->getCodigo());
        $oAssentamentoRRA->setValorTotalDevido($h83_valor);
        $oAssentamentoRRA->setNumeroDeMeses($h83_meses);
        $oAssentamentoRRA->setValorDosEncargosJudiciais($h83_encargos);

        $oAssentamentoRRA->persist($lSomenteRRA = true);
        break;

      case Assentamento::NATUREZA_JUSTIFICATIVA:

        $oDaoAssentamentoJustificativa = new cl_assentamentojustificativaperiodo;
        $oDaoAssentamentoJustificativa->excluir(null, null, "rh206_codigo = {$oAssentamento->getCodigo()}");

        if($oDaoAssentamentoJustificativa->erro_status == '0') {
          throw new DBException($oDaoAssentamentoJustificativa->erro_msg);
        }

        $periodosJustificativa = array(
          !empty($periodoJustificativa1) ? $periodoJustificativa1 : null,
          !empty($periodoJustificativa2) ? $periodoJustificativa2 : null,
          !empty($periodoJustificativa3) ? $periodoJustificativa3 : null
        );

        foreach ($periodosJustificativa as $periodo) {

          if(empty($periodo)) {
            continue;
          }

          $oDaoAssentamentoJustificativa = new cl_assentamentojustificativaperiodo;
          $oDaoAssentamentoJustificativa->rh206_codigo  = $oAssentamento->getCodigo();
          $oDaoAssentamentoJustificativa->rh206_periodo = $periodo;

          $oDaoAssentamentoJustificativa->incluir();

          if($oDaoAssentamentoJustificativa->erro_status == '0') {
            throw new DBException($oDaoAssentamentoJustificativa->erro_msg);
          }
        }

        break;
        
      case Assentamento::NATUREZA_HE_MANUAL:
        
        $oDaoAssentamentoHoraExtraManual = new cl_assentamentohoraextra;
        if(!$oDaoAssentamentoHoraExtraManual->excluir(null, "h17_assenta = {$oAssentamento->getCodigo()}")){
          throw new DBException($oDaoAssentamentoHoraExtraManual->erro_msg);
        }

        $horasExtras = array(
          BaseHora::HORAS_EXTRA50          => !empty($horaExtraManual50Diurna)   ? $horaExtraManual50Diurna   : null,
          BaseHora::HORAS_EXTRA50_NOTURNA  => !empty($horaExtraManual50Noturna)  ? $horaExtraManual50Noturna  : null,
          BaseHora::HORAS_EXTRA75          => !empty($horaExtraManual75Diurna)   ? $horaExtraManual75Diurna   : null,
          BaseHora::HORAS_EXTRA75_NOTURNA  => !empty($horaExtraManual75Noturna)  ? $horaExtraManual75Noturna  : null,
          BaseHora::HORAS_EXTRA100         => !empty($horaExtraManual100Diurna)  ? $horaExtraManual100Diurna  : null,
          BaseHora::HORAS_EXTRA100_NOTURNA => !empty($horaExtraManual100Noturna) ? $horaExtraManual100Noturna : null
        );

        foreach ($horasExtras as $tipo => $hora) {

          if(empty($hora)) {
            continue;
          }

          $oDaoAssentamentoHoraExtraManual = new cl_assentamentohoraextra;
          $oDaoAssentamentoHoraExtraManual->h17_assenta = $oAssentamento->getCodigo();
          $oDaoAssentamentoHoraExtraManual->h17_hora    = $hora;
          $oDaoAssentamentoHoraExtraManual->h17_tipo    = $tipo;

          if(!$oDaoAssentamentoHoraExtraManual->incluir()) {
            throw new DBException($oDaoAssentamentoHoraExtraManual->erro_msg);
          }
        }

        break;
    }

    db_fim_transacao();

    $msg  = "Cadastro Realizado com Sucesso.";
    $sUrl = "rec1_assenta001.php?msg={$msg}";

    if(isset($lAssentamentoFuncional)) {
      $sUrl .= "&lAssentamentoFuncional=true&iCodigoEfetividade={$iCodigoEfetividade}&h16_regist={$h16_regist}";
    }

    if ( !isset($lAssentamentoFuncional) ) {

      if (isset($iTipoFuncionamento) )  {
        $sUrl .= "&iTipoFuncionamento=1";
      }

      db_redireciona($sUrl);
    } else {
      db_redireciona($sUrl);
    }

  } catch(Exception $oErro) {

    /**
     * Exibe alert com mensagem de erro e da rollback
     */
    $msg = str_replace("\n", '\n', $oErro->getMessage());

    if ( isset($lAssentamentoFuncional) ) {

      $sQueryString  = "lAssentamentoFuncional=true";
      $sQueryString .= "&iCodigoEfetividade={$iCodigoEfetividade}";
      $sQueryString .= "&h16_regist={$h16_regist}";
      db_redireciona("rec1_assenta001.php?{$sQueryString}");
    }

  }

}

if( isset($h16_assent) && trim($h16_assent) != "" ) {

  $result_assent = $cltipoasse->sql_record($cltipoasse->sql_query_file($h16_assent, "h12_tipo, h12_tipefe, h12_vinculaperiodoaquisitivo"));

  if($cltipoasse->numrows > 0){
    db_fieldsmemory($result_assent, 0);
  }

  $h80_db_cadattdinamicovalorgrupo = "";
}
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="javascript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="javascript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="javascript" type="text/javascript" src="scripts/strings.js"></script>
  <script language="javascript" type="text/javascript" src="scripts/dates.js"></script>
  <script language="javascript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
  <script language="javascript" type="text/javascript" src="scripts/widgets/DBInputHora.widget.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<?php
if ( isset($incluir) && isset($lAssentamentoFuncional) ) { ?>
  <script>
    window.parent.assentamentofuncional.hide();
    window.parent.retornaAssentamentosFuncionais(<?=$oAssentamento->getCodigo()?>);
  </script>
<?php } ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
      <center>
        <?
        include(modification("forms/db_frmassenta.php"));
        ?>
      </center>
    </td>
  </tr>
</table>
<?php
if(!isset($lAssentamentoFuncional)) {db_menu();}
?>
</body>
</html>
<script>
  js_tabulacaoforms("form1","h16_regist",true,1,"h16_regist",true);
  js_limpaPeriodoAquisitivo();
</script>
<?
if(isset($msg)) {
  db_msgbox($msg);
}
?>
