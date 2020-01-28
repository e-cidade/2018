<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

require_once "libs/db_conn.php";
require_once "libs/db_utils.php";
require_once "libs/db_app.utils.php";
require_once "libs/db_stdlib.php";
require_once "std/db_stdClass.php";
require_once "dbforms/db_funcoes.php";
require_once "model/slip.model.php";
require_once "model/caixa/slip/Transferencia.model.php";
require_once "model/caixa/slip/TransferenciaFactory.model.php";
require_once "model/agendaPagamento.model.php";
require_once "model/CgmFactory.model.php";
require_once "model/contabilidade/planoconta/ContaPlano.model.php";
require_once "model/contabilidade/planoconta/ContaPlanoPCASP.model.php";
require_once "model/contabilidade/planoconta/ContaOrcamento.model.php";
require_once "model/contabilidade/planoconta/SistemaConta.model.php";

db_app::import('contabilidade.*');
db_app::import('contabilidade.lancamento.*');
db_app::import('contabilidade.contacorrente.*');
db_app::import('contabilidade.planoconta.*');
db_app::import('exceptions.*');

$DB_SERVIDOR = "inf01.dbseller";
$DB_PORTA    = 5441;
$DB_USUARIO  = "dbportal";
$DB_SENHA    = 'db#rp7';
$DB_BASE     = "ontem_20130220_1900";

  try {

    $conn = pg_connect("host=$DB_SERVIDOR dbname=$DB_BASE port=$DB_PORTA user=$DB_USUARIO password=$DB_SENHA");
    if ( !$conn) {
      throw new Exception("[ERRO 001] Impossivel conectar a base de dados: {$DB_SERVIDOR}:{$DB_PORTA}");
    }

    $rsStartSession = db_query("select fc_startsession()");
    db_inicio_transacao();

    list($iAnoServidor, $iMesServidor, $iDiaServidor) = explode("-", date("Y-m-d"));

    /**
     * Buscamos as instituicoes cadastradas para o sistema.
     */
    $rsBuscaInstituicao = db_query("select codigo, nomeinst from db_config");
    if (!$rsBuscaInstituicao) {
      throw new Exception("Não foi possivel localizar as instituicoes cadastradas no sistema.");
    }
    $aInstituicoes = db_utils::getCollectionByRecord($rsBuscaInstituicao) ;

    $HTTP_SERVER_VARS = null;
    db_putsession("DB_anousu",     "{$iAnoServidor}");
    db_putsession("DB_id_usuario", "1");
    db_putsession("DB_acessado",   "0");


    /**
     * Percorremos as instituicoes cadastradas fazendo o estorno e arrecadação dos slips gerados
     */
    foreach ($aInstituicoes as $oInstituicao) {

      /**
       * Buscamos uma autenticadora com a configuração de "Autentica e Nao Imprime"
       */
      $rsBuscaTerminal = db_query("select k11_ipterm from cfautent where k11_instit = {$oInstituicao->codigo} and k11_tipautent = 2 limit 1");
      if (pg_num_rows($rsBuscaTerminal) == 0) {
        throw new Exception("Nenhum terminal cadastrado com a configuracao 'Autentica e Nao Imprime'");
      }
      $sIpTerminal = db_utils::fieldsMemory($rsBuscaTerminal, 0)->k11_ipterm;

      db_putsession("DB_instit", $oInstituicao->codigo);
      db_putsession("DB_ip",     $sIpTerminal);
      db_putsession("DB_datausu", mktime());

      echo "\n\nPROCESSANDO SLIPS DA INSTITUICAO {$oInstituicao->codigo} - {$oInstituicao->nomeinst}\n\n";

      $sSqlBuscaSlip  = " select slip.*";
      $sSqlBuscaSlip .= "   from slip";
      $sSqlBuscaSlip .= "        left join sliptipooperacaovinculo on slip.k17_codigo = sliptipooperacaovinculo.k153_slip";
      $sSqlBuscaSlip .= "  where sliptipooperacaovinculo.k153_slip is null";
      $sSqlBuscaSlip .= "    and slip.k17_data >= '2013-01-01'";
      $sSqlBuscaSlip .= "    and slip.k17_dtaut is not null";
      $sSqlBuscaSlip .= "    and slip.k17_instit = {$oInstituicao->codigo}";
      $sSqlBuscaSlip .= "    and slip.k17_situacao = 2";

      $rsBuscaSlip = db_query($sSqlBuscaSlip);
      if ( !$rsBuscaSlip) {
        throw new Exception("[ERRO 002] Nao foi possível buscar os dados dos Slips.");
      }

      $iTotalRegistroSlip = pg_num_rows($rsBuscaSlip);
      $aSlipsEncontrados  = array();
      for ($iRowSlip = 0; $iRowSlip < $iTotalRegistroSlip; $iRowSlip++) {

        $oStdDadoSlip = db_utils::fieldsMemory($rsBuscaSlip, $iRowSlip);
        list($iAnoAutent, $iMesAutent, $iDiaAutent) = explode("-", $oStdDadoSlip->k17_dtaut);

        db_putsession("DB_datausu", mktime(0, 0, 0, $iMesAutent, $iDiaAutent, $iAnoAutent));

        $oSlip = new slip($oStdDadoSlip->k17_codigo);
        $oSlip->estornar(true);
        print " => Estornando SLIP {$oStdDadoSlip->k17_codigo} | {$oStdDadoSlip->k17_data}\n";
        unset($oSlip);

        /**
         * Descobrimos o código do tipo do slip que este passará a ser
         */
        $sSqlBuscaSaltes       = " select * ";
        $sSqlBuscaSaltes      .= "   from saltes ";
        $sSqlBuscaSaltes      .= "  where k13_conta in ({$oStdDadoSlip->k17_debito}, {$oStdDadoSlip->k17_credito})";
        $rsBuscaSaltes         = db_query($sSqlBuscaSaltes);
        $iTotalRegistrosSaltes = pg_num_rows($rsBuscaSaltes);
        $iCodigoVinculo        = 13;
        $sDescricaoVinculo     = "Dep. Diversas Origens - Pagamento";
        if ($iTotalRegistrosSaltes == 2) {

          $iCodigoVinculo    = 5;
          $sDescricaoVinculo = "Transf. Bancaria";
        }

        $oDaoSlipTipoOperacaoVinculo = db_utils::getDao("sliptipooperacaovinculo");
        $oDaoSlipTipoOperacaoVinculo->k153_slip             = $oStdDadoSlip->k17_codigo;
        $oDaoSlipTipoOperacaoVinculo->k153_slipoperacaotipo = $iCodigoVinculo;
        $oDaoSlipTipoOperacaoVinculo->incluir($oStdDadoSlip->k17_codigo);
        print "  => Slip {$oStdDadoSlip->k17_codigo} Vinculado ao tipo {$iCodigoVinculo} - {$sDescricaoVinculo}\n";
        if ($oDaoSlipTipoOperacaoVinculo->erro_status == "0") {
          throw new Exception("[ERRO 003] Impossivel vincular o SLIP {$oStdDadoSlip->k17_codigo} a Operacao {$iCodigoVinculo} - {$sDescricaoVinculo}.");
        }

        /**
         * Validamos e vinculamos a caracteristica peculiar
         */
        $oDaoSlipConCarPeculiar = db_utils::getDao('slipconcarpeculiar');
        $sSqlBuscaCPSlip        = $oDaoSlipConCarPeculiar->sql_query_file(null, "*", null, "k131_slip = {$oStdDadoSlip->k17_codigo}");
        $rsBuscaCPSlip          = $oDaoSlipConCarPeculiar->sql_record($sSqlBuscaCPSlip);

        if ($oDaoSlipConCarPeculiar->numrows > 0) {

          if ($oDaoSlipConCarPeculiar->numrows == 2) {
            continue;
          }

          for ($iRowCP = 0; $iRowCP < $oDaoSlipConCarPeculiar->numrows; $iRowCP++) {

            $oStdCPSLip = db_utils::fieldsMemory($rsBuscaCPSlip, $iRowCP);
            $iTipoConta = 1;
            if ($oStdCPSLip->k131_tipo == 1) {
              $iTipoConta = 2;
            }

            $oDaoIncluirSlipCP = db_utils::getDao("slipconcarpeculiar");
            $oDaoIncluirSlipCP->k131_sequencial     = null;
            $oDaoIncluirSlipCP->k131_slip           = $oStdDadoSlip->k17_codigo;
            $oDaoIncluirSlipCP->k131_tipo           = $iTipoConta;
            $oDaoIncluirSlipCP->k131_concarpeculiar = "000";
            $oDaoIncluirSlipCP->incluir(null);
            print "  => Vinculando SLIP {$oStdDadoSlip->k17_codigo} CP/CA: 000\n";
            if ($oDaoIncluirSlipCP->erro_status == "0") {
              throw new Exception("Não foi possível vincular a CP/CA ao slip {$oStdDadoSlip->k17_codigo}.");
            }
            unset($oDaoIncluirSlipCP);
          }
        } else {

          $oDaoIncluirSlipCPCredito = db_utils::getDao("slipconcarpeculiar");
          $oDaoIncluirSlipCPCredito->k131_sequencial     = null;
          $oDaoIncluirSlipCPCredito->k131_slip           = $oStdDadoSlip->k17_codigo;
          $oDaoIncluirSlipCPCredito->k131_tipo           = 2;
          $oDaoIncluirSlipCPCredito->k131_concarpeculiar = "000";
          $oDaoIncluirSlipCPCredito->incluir(null);
          print "  => Incluindo CP/CA Credito: SLIP {$oStdDadoSlip->k17_codigo} CP/CA: 000\n";
          if ($oDaoIncluirSlipCPCredito->erro_status == "0") {
            throw new Exception("Não foi possível vincular a CP/CA ao slip {$oStdDadoSlip->k17_codigo}.");
          }
          unset($oDaoIncluirSlipCPCredito);

          $oDaoIncluirSlipCPDebito = db_utils::getDao("slipconcarpeculiar");
          $oDaoIncluirSlipCPDebito->k131_sequencial     = null;
          $oDaoIncluirSlipCPDebito->k131_slip           = $oStdDadoSlip->k17_codigo;
          $oDaoIncluirSlipCPDebito->k131_tipo           = 1;
          $oDaoIncluirSlipCPDebito->k131_concarpeculiar = "000";
          $oDaoIncluirSlipCPDebito->incluir(null);
          print "  => Incluindo CP/CA Debito: SLIP {$oStdDadoSlip->k17_codigo} CP/CA: 000\n";
          if ($oDaoIncluirSlipCPDebito->erro_status == "0") {
            throw new Exception("Não foi possível vincular a CP/CA ao slip {$oStdDadoSlip->k17_codigo}.");
          }
          unset($oDaoIncluirSlipCPDebito);

        }

        $oTransferencia = TransferenciaFactory::getInstance($iCodigoVinculo, $oStdDadoSlip->k17_codigo);
        list($iAno, $iMes, $iDia) = explode("-", $oStdDadoSlip->k17_dtaut);
        db_putsession("DB_datausu", mktime(0, 0, 0, $iMes, $iDia, $iAno));
        $oTransferencia->executaAutenticacao();
        $oTransferencia->executarLancamentoContabil();
        echo "  => Lancamento Contabil {$oTransferencia->getCodigoLancamento()}\n\n";

      }

      echo " ########################################################\n";
      echo " Total de Slips Configurados: {$iTotalRegistroSlip}\n";
      echo " ########################################################\n\n";

    }
    db_fim_transacao(false);

  } catch (Exception $eErro) {

    db_fim_transacao(true);
    echo "\n\n{$eErro->getMessage()}\n\n";
  }