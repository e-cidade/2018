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
require_once "libs/db_libcontabilidade.php";
require_once "libs/db_liborcamento.php";
require_once "std/db_stdClass.php";
require_once "std/DBDate.php";
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
require_once "model/caixa/PlanilhaArrecadacao.model.php";
require_once "model/caixa/AutenticacaoPlanilha.model.php";
require_once "model/orcamento/ReceitaContabil.model.php";

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

    $rsSessaoAnoUsu  = db_query("select fc_putsession('DB_use_pcasp', (select c90_usapcasp::text from conparametro limit 1))");

    /**
     * Percorremos as instituicoes cadastradas fazendo o estorno e arrecadação dos slips gerados
     */
    foreach ($aInstituicoes as $oInstituicao) {


      $rsSessaoInstituicao = db_query("select fc_putsession('DB_instit', '{$oInstituicao->codigo}')");
      $rsSessaoAnoUsu      = db_query("select fc_putsession('DB_anousu', '2013')");

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

      echo "\n\nPROCESSANDO SLIPS DA INSTITUICAO {$oInstituicao->codigo} - {$oInstituicao->nomeinst}\n\n";

      $sSqlBuscaPlanilha  = " select distinct placaixa.*, db_itensmenu.descricao, fc_montamenu(db_itensmenu.id_item) ";
      $sSqlBuscaPlanilha .= "   from placaixa";
      $sSqlBuscaPlanilha .= "        inner join db_acount       on db_acount.contatu         = placaixa.k80_codpla::varchar";
      $sSqlBuscaPlanilha .= "        inner join db_acountacesso on db_acountacesso.id_acount = db_acount.id_acount";
      $sSqlBuscaPlanilha .= "        inner join db_logsacessa   on db_logsacessa.codsequen   = db_acountacesso.codsequen";
      $sSqlBuscaPlanilha .= "        inner join db_itensmenu    on db_itensmenu.id_item      = db_logsacessa.id_item";
      $sSqlBuscaPlanilha .= "  where db_logsacessa.id_item   = 3973";
      $sSqlBuscaPlanilha .= "    and db_logsacessa.id_modulo = 39";
      $sSqlBuscaPlanilha .= "    and placaixa.k80_data >= '2013-01-01'";
      $sSqlBuscaPlanilha .= "    and placaixa.k80_dtaut is not null";
      $sSqlBuscaPlanilha .= "    and placaixa.k80_instit = {$oInstituicao->codigo}";

      $rsBuscaPlanilha = db_query($sSqlBuscaPlanilha);
      if (!$rsBuscaPlanilha) {
        throw new Exception("Nao foi possível buscar as planilhas criadas pelo menu antigo do sistema.\n\n");
      }

      $iTotalPlanilhas = pg_num_rows($rsBuscaPlanilha);
      if ($iTotalPlanilhas == 0) {
        throw new Exception("Nenhuma planilha encontrada para a instituicao {$oInstituicao->codigo} - {$oInstituicao->nomeinst}\n\n");
      }

      for ($iRowPlanilha = 0; $iRowPlanilha < $iTotalPlanilhas; $iRowPlanilha++) {

        $oStdPlanilha = db_utils::fieldsMemory($rsBuscaPlanilha, $iRowPlanilha);
        echo " => Processando Planilha {$oStdPlanilha->k80_codpla} - {$oStdPlanilha->k80_data}\n";

        $oPlanilha = new PlanilhaArrecadacao($oStdPlanilha->k80_codpla);
        list($iAno, $iMes, $iDia) = explode("-", $oStdPlanilha->k80_dtaut);
        db_putsession("DB_datausu", mktime(0, 0, 0, $iMes, $iDia, $iAno));
        $oPlanilha->estornar();
        $oPlanilha->autenticar();
      }

      echo " ########################################################\n";
      echo " Total de Planilhas Configuradas: {$iRowPlanilha}\n";
      echo " ########################################################\n\n";

    }
    db_fim_transacao(false);

  } catch (Exception $eErro) {

    db_fim_transacao(true);
    echo "\n\n{$eErro->getMessage()}\n\n";
  }