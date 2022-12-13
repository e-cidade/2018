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

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($_POST);

$classenta          = new cl_assenta;
$cltipoasse         = new cl_tipoasse;

$db_opcao = 22;
$db_botao = false;

/**
 * Alterar assentamento/afastamento
 */
if ( isset($alterar) ) {

  $sMensagens = "recursoshumanos.rh.rec1_assenta.";

  db_inicio_transacao();

  $db_botao = true;
  $db_opcao = 2;

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
     * Campo com codigo do afastamento nao informado
     */
    if ( empty($sCodigoAfastamento) ){
      throw new Exception("Campo assentamento/afastamento não informado.");
    }

    /*
     * Bloqueia alteração de assentamentos de tipo de reajuste salarial
     */
    $rsVerificaReajuste = $cltipoasse->sql_record( $cltipoasse->sql_query_file( $h16_assent, "h12_tiporeajuste") );

    if ($cltipoasse->numrows > 0) {
      $oTipoasse = db_utils::fieldsMemory($rsVerificaReajuste, 0);

      if ($oTipoasse->h12_tiporeajuste != 0) {
        throw new Exception( _M($sMensagens . "alterar_reajuste_salarial") );
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

    switch ($h12_natureza_novo_tipo) {

      case AssentamentoSubstituicao::NATUREZA_AUTORIZA_HORA_EXTRA:

        $h16_dtterm = $h16_dtconc;
        $oDataFinal = new DBDate($h16_dtterm);

        /**
         * Verifica se já existe um assentamento de autorização para a matrícula na data informada não sendo o assentamento atual
         */

        $sSqlVerificaExistenciaAssentamento = $classenta->sql_query_tipo(null,'*',null,"h16_codigo != {$h16_codigo} AND h16_regist={$h16_regist} AND h16_dtconc='{$dDataInicial}' AND h12_natureza=".Assentamento::NATUREZA_AUTORIZA_HORA_EXTRA."");
        $rsAssentamentoAutorizacao          = $classenta->sql_record($sSqlVerificaExistenciaAssentamento);

        if(!empty($rsAssentamentoAutorizacao) || pg_num_rows($rsAssentamentoAutorizacao) > 0){
          throw new Exception('Já existe um assentamento de autorização de hora extra para este servidor na data informada.\nRealize a alteração no assentamento existente.');
        }
        break;

      case AssentamentoSubstituicao::CODIGO_NATUREZA:
        
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
        break;

      case AssentamentoRRA::CODIGO_NATUREZA:
        /**
         * Verificamos se o assentamento é de rra, e se já foi realizado o lançamento do mesmo
         * no ponto.
         */
        $oAssentamentoRRA = new AssentamentoRRA($h16_codigo);
        $oLancamentoRRA   = LancamentoRRARepository::getInstanciasByAssentamento($oAssentamentoRRA);

        if ($oLancamentoRRA) {
          throw new BusinessException("Lançamento já realizado no ponto. Alteração não permitida.");
        }
        break;
        
      case Assentamento::NATUREZA_HE_MANUAL:

        if(empty($h16_dtterm)) {
          throw new BusinessException(_M($sMensagens . "data_fim_assentamentos_hora_extra_manual"));
        }

        $existeAssentamentoHoraExtraManual = false;
        if($h12_natureza != $h12_natureza_novo_tipo) {
          
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
        }

        if($existeAssentamentoHoraExtraManual) {
          throw new DBException("Já existe assentamento de horas extras para o servidor na data informada.");
        }
        break;
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
    $sWhereValidacao .= "            and h16_codigo != {$h16_codigo}                        ";
    $sWhereValidacao .= "            and ({$sWhereDatas})                                   ";
    $sWhereValidacao .= "          )                                                        ";
    $sWhereValidacao .= "     else false                                                    ";
    $sWhereValidacao .= " end                                                               ";
    $sSqlValidacao    = $classenta->sql_query(null, '*', 'h16_dtconc', $sWhereValidacao);
    $rsValidacao      = $classenta->sql_record($sSqlValidacao);

    /**
     * Econtrou afastamento para o periodo informado no formulario
     * Lanca excessao
     */ 
    if ( $classenta->numrows > 0 && $h12_vinculaperiodoaquisitivo == 'f') {

      $sMensagemErro  = "Servidor já possui assentamento cadastrado para este período.";
      $aAssentamentos = db_utils::getCollectionByRecord($rsValidacao);
      $lErroValidacaoAfastamentos = true;

      /**
       * Percorre assentamentos encontrados para montar mensagem de erro
       */
      foreach( $aAssentamentos as $oAssentamento ) {

        if($oAssentamento->h16_codigo == $h16_codigo) {
          $lErroValidacaoAfastamentos = false;
        }

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

      if($lErroValidacaoAfastamentos) {
        throw new Exception($sMensagemErro);
      }
    }   
 
    $oDaoConfiguracoesDatasEfetividade   = new cl_configuracoesdatasefetividade();
    $sWhereConfiguracoesDatasEfetividade = "rh186_processado is true AND rh186_instituicao = " . db_getsession("DB_instit");

    if (empty($h16_dtterm)) {                                                                                                                       
      $sWhereConfiguracoesDatasEfetividade .= " and '{$oDataInicial->getDate()}' between rh186_datainicioefetividade and rh186_datafechamentoefetividade";
    } else {
      $sWhereConfiguracoesDatasEfetividade .= " and (('{$oDataInicial->getDate()}' between rh186_datainicioefetividade and rh186_datafechamentoefetividade) or ('{$oDataFinal->getDate()}' between rh186_datainicioefetividade and rh186_datafechamentoefetividade))";
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
     * Altera Assentamento/afastamento
     */
    if(!isset($sOpcaoAssentamento)) {
      throw new BusinessException("Não foi possível determinar qual o tipo de assentamento que está sendo salvo.");
    }

    if($sOpcaoAssentamento == 1) {
      $oAssentamento = new Assentamento();
    } else {
      $oAssentamento = AssentamentoFuncionalRepository::getInstanciaPorCodigo($h16_codigo);
    }
    
    $oAssentamento->setCodigo($h16_codigo);
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

    if(isset($h16_dtterm) && !empty($h16_dtterm)) {
      $oAssentamento->setDataTermino(new DBDate($h16_dtterm));
    }

    if($sOpcaoAssentamento == 1) {
      $oAssentamentoSalvo = AssentamentoRepository::persist($oAssentamento);
    } else {
      $oAssentamentoSalvo = AssentamentoFuncionalRepository::persist($oAssentamento);
    }
    

    if(!$oAssentamentoSalvo instanceof Assentamento && !$oAssentamentoSalvo instanceof AssentamentoFuncional) {
      throw new BusinessException();
    }

    /**
     * Verificamos a configuração se há tipo de assentamentos do RH que geram afastamentos do pessoal
     * se existir necessário refletir alterações do RH no pessoal e inicilizar novamente o ponto
     */
    $aListaInformacoesExternas = InformacoesExternasTipoAssentamento::getTipoAssentamentoConfiguradosPorCompetencia(DBPessoal::getCompetenciaFolha());

    if(is_array($aListaInformacoesExternas)){

      $aTiposAssentamentoConfigurados = array();
      foreach ($aListaInformacoesExternas as $oInformacoesExternas) {
        $aTiposAssentamentoConfigurados[] = $oInformacoesExternas->getTipoAssentamento()->getSequencial();
      }

      if( in_array($oAssentamento->getTipoAssentamento(), $aTiposAssentamentoConfigurados) ) {

        $oServidor    = ServidorRepository::getInstanciaByCodigo($oAssentamento->getMatricula(),
                                                                 $oInformacoesExternas->getCompetencia()->getAno(),
                                                                 $oInformacoesExternas->getCompetencia()->getMes());

        /**
         * Busca o(s) afastamento(s) vinculado(s) ao assentamento em questão
         */
        $aAfastaAssenta = AfastaAssentaRepository::getAfastamentosPorAssentamento($oAssentamentoSalvo);

        if(!is_array($aAfastaAssenta)) {
          throw new BusinessException("Não foi possível buscar o vínculo entre assentamento e afastamento.");
        }

        /**
         * Pegamos o primeiro retornado pois essa deve ser uma relação de um para um,
         * embora a base suporte não deve existir a relação n para n
         */
        $oAfastamento = $aAfastaAssenta[0];

        $oAfastamento->setCodigoSituacao($oInformacoesExternas->getSituacaoAfastamento());
        $oAfastamento->setCodigoAfastamentoSefip($oInformacoesExternas->getSefip());
        $oAfastamento->setCodigoRetornoSefip($oInformacoesExternas->getCodigoRetorno());
        $oAfastamento->setDataAfastamento($oAssentamentoSalvo->getDataConcessao());
        $oAfastamento->setDataRetorno($oAssentamentoSalvo->getDataTermino());
        $oAfastamento->setDataLancamento($oAssentamentoSalvo->getDataLancamento());
        $oAfastamento->setObservacao($oAssentamentoSalvo->getHistorico());

        /**
         * Salva o afastamento
         */
        $oAfastamentoSalvo = AfastamentoRepository::persist($oAfastamento);

        if(!$oAfastamentoSalvo instanceof Afastamento) {
          throw new BusinessException("Erro ao salvar afastamento na base de dados.");
        }

        /**
         * Realiza a proporcionalização no ponto
         */
        $oProporcionalizacaoPontoSalario = new ProporcionalizacaoPontoSalario($oServidor->getPonto(Ponto::SALARIO), $oInformacoesExternas->getSituacaoAfastamento(), $oAssentamento->getDataTermino());
        $oProporcionalizacaoPontoSalario->processar();
      }
    }

    if ($h12_vinculaperiodoaquisitivo == 't') {
      $oPeriodoAquisitivoAssentamento = PeriodoAquisitivoAssentamento::getPeriodoAquisitivoAssentamento($oAssentamento);

      if ($oPeriodoAquisitivoAssentamento) {
        $oPeriodoAquisitivoAssentamento->excluir();
      } else {
        $oPeriodoAquisitivoAssentamento = new PeriodoAquisitivoAssentamento();
      }

      $oPeriodoAquisitivoAssentamento->setAssentamento(new Assentamento( $oAssentamento->getCodigo() ));
      $oPeriodoAquisitivoAssentamento->setPeriodoAquisitivo(new PeriodoAquisitivoFerias( $iPeriodoAquisitivo ));

      $oPeriodoAquisitivoAssentamento->salvar();
    }else{

      /**
       * Remove vinculo da rhferiasassenta quando existe
       */
      $oDaoRhFeriasAssenta = db_utils::getDao("rhferiasassenta");

      $sSqlRhFeriasAssenta = $oDaoRhFeriasAssenta->sql_query(null, 'rh131_sequencial', null, 'rh131_assenta = ' . $oAssentamento->getCodigo() );
      $rsRhFeriasAssenta   = $oDaoRhFeriasAssenta->sql_record($sSqlRhFeriasAssenta);

      if( !empty($rsRhFeriasAssenta) ){

        $oRhFeriasAssenta    = db_utils::fieldsMemory($rsRhFeriasAssenta, 0);
        $oDaoRhFeriasAssenta->rh131_assenta  = $oRhFeriasAssenta->rh131_sequencial;
        $oDaoRhFeriasAssenta->excluir( $oRhFeriasAssenta->rh131_sequencial );

        if ($oDaoRhFeriasAssenta->erro_status == "0") {

          $oMensagemErro = (object) array("sMensagem" => $oDaoRhFeriasAssenta->erro_banco);
          throw new DBException( _M(self::MENSAGENS . "erro_exclusao", $oMensagemErro) );
        }
      }
    }

    switch ($h12_natureza_novo_tipo) {

      case AssentamentoSubstituicao::CODIGO_NATUREZA:

        if($h12_natureza != AssentamentoSubstituicao::CODIGO_NATUREZA) {

          if($h12_natureza == AssentamentoRRA::CODIGO_NATUREZA) {
          
            $oAssentamentoRRA = new AssentamentoRRA($oAssentamento->getCodigo());
            $oAssentamentoRRA->excluir($lSomenteRRA = true);
          }
          
          if($h12_natureza == Assentamento::NATUREZA_JUSTIFICATIVA) {

            $oDaoAssentamentoJustificativa = new cl_assentamentojustificativaperiodo;
            $oDaoAssentamentoJustificativa->excluir(null, null, "rh206_codigo = {$oAssentamento->getCodigo()}");

            if($oDaoAssentamentoJustificativa->erro_status == '0') {
              throw new DBException($oDaoAssentamentoJustificativa->erro_msg);
            }
          }

          if($h12_natureza == Assentamento::NATUREZA_HE_MANUAL) {

            $oDaoAssentamentoHoraExtraManual = new cl_assentamentohoraextra;
            if(!$oDaoAssentamentoHoraExtraManual->excluir(null, "h17_assenta = {$oAssentamento->getCodigo()}")) {
              throw new DBException($oDaoAssentamentoHoraExtraManual->erro_msg);
            }
          }
        }

        $oAssentamentoSubstituicao = new AssentamentoSubstituicao($oAssentamento->getCodigo());
        $oAssentamentoSubstituicao->setSubstituido(ServidorRepository::getInstanciaByCodigo($rh161_regist, DBPessoal::getAnoFolha(), DBPessoal::getMesFolha()));

        $mResponsePersistAssentamentoSubstituicao = $oAssentamentoSubstituicao->persist();

        if($mResponsePersistAssentamentoSubstituicao !== true) {
          throw new BusinessException($mResponsePersistAssentamentoSubstituicao);
        }
        break;
      
      case AssentamentoRRA::CODIGO_NATUREZA:

        if($h12_natureza != AssentamentoRRA::CODIGO_NATUREZA) {

          if($h12_natureza == AssentamentoSubstituicao::CODIGO_NATUREZA) {

            $oDaoAssentamentoSubstituicao = new cl_assentamentosubstituicao();
            $rsAssentamentoSubstituicao   = $oDaoAssentamentoSubstituicao->sql_record($oDaoAssentamentoSubstituicao->sql_query_file(null,
                                                                                                                                    "*",
                                                                                                                                    null,
                                                                                                                                    " rh161_assentamento = {$oAssentamento->getCodigo()}"));

            if(is_resource($rsAssentamentoSubstituicao) && $oDaoAssentamentoSubstituicao->numrows > 0){
              $oDaoAssentamentoSubstituicao->excluir($oAssentamento->getCodigo());
            }
          }

          if($h12_natureza == Assentamento::NATUREZA_JUSTIFICATIVA) {

            $oDaoAssentamentoJustificativa = new cl_assentamentojustificativaperiodo;
            $oDaoAssentamentoJustificativa->excluir(null, null, "rh206_codigo = {$oAssentamento->getCodigo()}");

            if($oDaoAssentamentoJustificativa->erro_status == '0') {
              throw new DBException($oDaoAssentamentoJustificativa->erro_msg);
            }
          }

          if($h12_natureza == Assentamento::NATUREZA_HE_MANUAL) {

            $oDaoAssentamentoHoraExtraManual = new cl_assentamentohoraextra;
            if(!$oDaoAssentamentoHoraExtraManual->excluir(null, "h17_assenta = {$oAssentamento->getCodigo()}")) {
              throw new DBException($oDaoAssentamentoHoraExtraManual->erro_msg);
            }
          }
        }

        try {
          $oAssentamentoRRA = new AssentamentoRRA($oAssentamento->getCodigo());

        } catch (Exception $oErro) {

          if(strpos(strtolower($oErro->getMessage()), "erro")) {
            throw new DBException($oErro->getMessage());
          }

          $oAssentamentoRRA = new AssentamentoRRA();
          $oAssentamentoRRA->setCodigo($oAssentamento->getCodigo());
        }

        $oAssentamentoRRA->setValorTotalDevido($h83_valor);
        $oAssentamentoRRA->setNumeroDeMeses($h83_meses);
        $oAssentamentoRRA->setValorDosEncargosJudiciais($h83_encargos);

        $oAssentamentoRRA->persist($lSomenteRRA = true);

        break;

      case Assentamento::NATUREZA_JUSTIFICATIVA:
        
        if($h12_natureza != Assentamento::NATUREZA_JUSTIFICATIVA) {
          
          if($h12_natureza == AssentamentoRRA::CODIGO_NATUREZA) {
            
            $oAssentamentoRRA = new AssentamentoRRA($oAssentamento->getCodigo());
            $oAssentamentoRRA->excluir($lSomenteRRA = true);
          }

          if($h12_natureza == AssentamentoSubstituicao::CODIGO_NATUREZA) {

            $oDaoAssentamentoSubstituicao->excluir($oAssentamento->getCodigo());
            
            if($oDaoAssentamentoSubstituicao->erro_status == '0') {
              throw new DBException($oDaoAssentamentoSubstituicao->erro_msg);
            }
          }

          if($h12_natureza == Assentamento::NATUREZA_HE_MANUAL) {

            $oDaoAssentamentoHoraExtraManual = new cl_assentamentohoraextra;
            if(!$oDaoAssentamentoHoraExtraManual->excluir(null, "h17_assenta = {$oAssentamento->getCodigo()}")) {
              throw new DBException($oDaoAssentamentoHoraExtraManual->erro_msg);
            }
          }
        }

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

        if($h12_natureza != Assentamento::NATUREZA_HE_MANUAL) {
          
          if($h12_natureza == AssentamentoRRA::CODIGO_NATUREZA) {
            
            $oAssentamentoRRA = new AssentamentoRRA($oAssentamento->getCodigo());
            $oAssentamentoRRA->excluir($lSomenteRRA = true);
          }

          if($h12_natureza == AssentamentoSubstituicao::CODIGO_NATUREZA) {

            $oDaoAssentamentoSubstituicao = new cl_assentamentosubstituicao;
            $oDaoAssentamentoSubstituicao->excluir($oAssentamento->getCodigo());
            
            if($oDaoAssentamentoSubstituicao->erro_status == '0') {
              throw new DBException($oDaoAssentamentoSubstituicao->erro_msg);
            }
          }

          if($h12_natureza == Assentamento::NATUREZA_JUSTIFICATIVA) {

            $oDaoAssentamentoJustificativa = new cl_assentamentojustificativaperiodo;
            $oDaoAssentamentoJustificativa->excluir(null, null, "rh206_codigo = {$oAssentamento->getCodigo()}");

            if($oDaoAssentamentoJustificativa->erro_status == '0') {
              throw new DBException($oDaoAssentamentoJustificativa->erro_msg);
            }
          }
        }

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

    /**
     * Verificamos se o assentamento foi gerado a partir de um processamento de férias, caso seja e as
     * férias já foram pagas, o assentamento não pode ser alterado.
     */
    $oDaoRhFeriasPeriodosAssentamento   = new cl_rhferiasperiodoassentamento();
    $sWhereRhFeriasPeriodosAssentamento = "rh169_assenta = {$h16_codigo} and rh110_situacao <> 0";
    $sSqlRhFeriasPeriodosAssentamento   = $oDaoRhFeriasPeriodosAssentamento->sql_query (null,"*", null, $sWhereRhFeriasPeriodosAssentamento);
    $rsRhFeriasPeriodosAssentamento     = db_query($sSqlRhFeriasPeriodosAssentamento);

    if (!$rsRhFeriasPeriodosAssentamento) {
      throw new DBException("Ocorreu um erro ao verificar os periodos de assentamentos.");
    }

    if (pg_num_rows($rsRhFeriasPeriodosAssentamento) > 0) {
      throw new BusinessException("Assentamento vinculado a férias já processadas no pessoal. Alteração não permitida.");
    }

    /**
     * commit
     */
    db_fim_transacao();

    /**
     * Alert com mensagem de alteracao e atualizado tela
     */
    $oDaoAssentaAttr = new cl_assentadb_cadattdinamicovalorgrupo();
    $oDaoAssentaAttr->excluir(null, null, "h80_assenta = $h16_codigo" );
    $oDaoAssentaAttr->h80_assenta                     = $oAssentamento->getCodigo();
    $oDaoAssentaAttr->h80_db_cadattdinamicovalorgrupo = $h80_db_cadattdinamicovalorgrupo;
    $oDaoAssentaAttr->incluir($oAssentamento->getCodigo(), $h80_db_cadattdinamicovalorgrupo);
    // $classenta->erro(true, true);

    $msg = "Alteração Realizada com Sucesso.";
    if($sOpcaoAssentamento == 1) {
      db_redireciona("rec1_assenta002.php?msg={$msg}&iTipoFuncionamento=1");
    }
    db_redireciona("rec1_assenta002.php?msg=$msg");

  } catch(Exception $oErro) {

    /**
     * Exibe alert com mensagem de erro e da rollback
     */
    $msg = str_replace("\n", '\n', $oErro->getMessage());
    db_fim_transacao(true);
  }

} else if(isset($chavepesquisa)) {

  $db_opcao = 2;
  $result = $classenta->sql_record($classenta->sql_query($chavepesquisa));
  $classentamentofuncional = new cl_assentamentofuncional;
  $rsAssentamentoFuncional = db_query($classentamentofuncional->sql_query($chavepesquisa));
  $sOpcaoAssentamento      = 1;

  if($rsAssentamentoFuncional && pg_num_rows($rsAssentamentoFuncional) > 0) {
    $sOpcaoAssentamento    = 2;
  }
  db_fieldsmemory($result,0);

  $periodoJustificativa1 = null;
  $periodoJustificativa2 = null;
  $periodoJustificativa3 = null;
  
  switch ($h12_natureza) {

    case Assentamento::NATUREZA_JUSTIFICATIVA:
      $oDaoAssentamentoJustificativa = new cl_assentamentojustificativaperiodo;
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

  $oDaoAssentaAttr = new cl_assentadb_cadattdinamicovalorgrupo();
  $rsComplemento   = db_query($oDaoAssentaAttr->sql_query_file(null,null, "h80_db_cadattdinamicovalorgrupo", null, "h80_assenta = $h16_codigo"));

  if (pg_num_rows($rsComplemento) > 0) {
    db_fieldsmemory($rsComplemento,0);
  }

  $db_botao = true;
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
<script language="javascript" type="text/javascript" src="scripts/dates.js"></script>
<script language="javascript" type="text/javascript" src="scripts/widgets/DBInputHora.widget.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
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
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?php
if(isset($msg)) {
  db_msgbox($msg);
}
if($db_opcao==22){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
<script>
js_tabulacaoforms("form1","h16_regist",true,1,"h16_regist",true);
</script>
