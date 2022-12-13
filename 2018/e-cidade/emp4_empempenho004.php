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

use ECidade\Patrimonial\Acordo\RegimeCompetencia\Repository\RegimeCompetencia;

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_liborcamento.php"));
require_once(modification("libs/db_libcontabilidade.php"));
require_once(modification("classes/db_pagordem_classe.php"));
require_once(modification("classes/db_pagordemnota_classe.php"));
require_once(modification("classes/db_pagordemele_classe.php"));
require_once(modification("classes/db_empnota_classe.php"));
require_once(modification("classes/db_empnotaele_classe.php"));

//////////////////////////////Controle Andamento da SOlicitação de Compras/////////////////////
require_once(modification("classes/db_pcparam_classe.php"));
require_once(modification("classes/db_protprocesso_classe.php"));
require_once(modification("classes/db_proctransfer_classe.php"));
require_once(modification("classes/db_proctransand_classe.php"));
require_once(modification("classes/db_proctransferproc_classe.php"));
require_once(modification("classes/db_solicitemprot_classe.php"));
require_once(modification("classes/db_solandam_classe.php"));
require_once(modification("classes/db_solandamand_classe.php"));
require_once(modification("classes/db_solandpadraodepto_classe.php"));
require_once(modification("classes/db_solordemtransf_classe.php"));
require_once(modification("classes/db_procandam_classe.php"));
require_once(modification("classes/db_empempenhonl_classe.php"));
require_once(modification("libs/db_sql.php"));

$clpcparam = new cl_pcparam;

//////////////////////////////////////////////////////////////////////////////////////////////

$clpagordem     = new cl_pagordem;
$clpagordemele  = new cl_pagordemele;
$clpagordemnota = new cl_pagordemnota;
$clempnota = new cl_empnota;
$clempnotaele = new cl_empnotaele;

$lAutorizacaoAcordo    = false;

require_once(modification("classes/db_empautpresta_classe.php"));
require_once(modification("classes/db_empempenho_classe.php"));
require_once(modification("classes/db_empprestatip_classe.php"));
require_once(modification("classes/db_emppresta_classe.php"));
require_once(modification("classes/db_empelemento_classe.php"));
require_once(modification("classes/db_emphist_classe.php"));
require_once(modification("classes/db_empemphist_classe.php"));
require_once(modification("classes/db_empempaut_classe.php"));
require_once(modification("classes/db_empempitem_classe.php"));
require_once(modification("classes/db_empautitem_classe.php"));
require_once(modification("classes/db_empautoriza_classe.php"));
require_once(modification("classes/db_empauthist_classe.php"));
require_once(modification("classes/db_empautidot_classe.php"));
require_once(modification("classes/db_emptipo_classe.php"));
require_once(modification("classes/db_empparametro_classe.php"));
require_once(modification("classes/db_cflicita_classe.php"));
require_once(modification("classes/db_db_depusu_classe.php"));
require_once(modification("classes/db_pctipocompra_classe.php"));
require_once(modification("classes/db_conplanoreduz_classe.php"));
require_once(modification("classes/db_empparamnum_classe.php"));
require_once(modification("classes/db_concarpeculiar_classe.php"));
require_once modification("libs/db_app.utils.php");

require_once(modification("model/configuracao/Instituicao.model.php"));
require_once(modification("model/contabilidade/contacorrente/ContaCorrenteFactory.model.php"));
require_once(modification("model/contabilidade/contacorrente/ContaCorrenteBase.model.php"));
require_once(modification("model/financeiro/ContaBancaria.model.php"));
require_once(modification("model/contabilidade/planoconta/SistemaConta.model.php"));
require_once(modification("model/contabilidade/planoconta/SubSistemaConta.model.php"));
require_once(modification("model/contabilidade/planoconta/SistemaContaCompensado.model.php"));
require_once(modification("model/contabilidade/planoconta/SistemaContaFinanceiroBanco.model.php"));
require_once(modification("model/contabilidade/planoconta/SistemaContaFinanceiroCaixa.model.php"));
require_once(modification("model/contabilidade/planoconta/SistemaContaFinanceiroExtraOrcamentaria.model.php"));
require_once(modification("model/contabilidade/planoconta/SistemaContaFinanceiro.model.php"));
require_once(modification("model/contabilidade/planoconta/SistemaContaPatrimonial.model.php"));
require_once(modification("model/contabilidade/planoconta/SistemaContaOrcamentario.model.php"));
require_once(modification("model/contabilidade/planoconta/SistemaContaNaoAplicado.model.php"));
require_once(modification("model/contabilidade/planoconta/ContaPlano.model.php"));
require_once(modification("model/contabilidade/planoconta/ClassificacaoConta.model.php"));
require_once(modification("model/contabilidade/planoconta/ContaCorrente.model.php"));
require_once(modification("model/contabilidade/planoconta/ContaOrcamento.model.php"));
require_once(modification("model/contabilidade/planoconta/ContaPlanoPCASP.model.php"));


db_app::import("Acordo");
db_app::import("financeiro.*");
db_app::import("orcamento.*");
db_app::import("AcordoComissao");
db_app::import("AcordoComissaoMembro");
db_app::import("CgmFactory");
db_app::import("financeiro.*");
db_app::import("contabilidade.*");
db_app::import("contabilidade.lancamento.*");
db_app::import("Dotacao");


db_app::import("contabilidade.contacorrente.*");
db_app::import("contabilidade.contacorrente.AC.*");
$clempautpresta   = new cl_empautpresta;
$clempempenho	  	= new cl_empempenho;
$clconplanoreduz  = new cl_conplanoreduz;
$clempprestatip	  = new cl_empprestatip;
$clemppresta	  	= new cl_emppresta;
$clempelemento	  = new cl_empelemento;
$clempempaut	  	=	new cl_empempaut;
$clempempitem	  	= new cl_empempitem;
$clempautoriza	  = new cl_empautoriza;
$clemphist	      = new cl_emphist;
$clempauthist	  	= new cl_empauthist;
$clempemphist	  	= new cl_empemphist;
$clemptipo	      = new cl_emptipo;
$clempautitem	  	= new cl_empautitem;
$clempautidot	  	= new cl_empautidot;
$clempparametro	  = new cl_empparametro;
$clcflicita	      = new cl_cflicita;
$clempparamnum	  = new cl_empparamnum;
$clconcarpeculiar = new cl_concarpeculiar;
$oDaoEmpenhoNl    = new cl_empempenhonl;
$cldb_depusu	  	= new cl_db_depusu;
$clpctipocompra	  = new cl_pctipocompra;

//retorna os arrays de lancamento...
$cltranslan       = new cl_translan;

require_once(modification("classes/db_orcelemento_classe.php"));
require_once(modification("classes/db_orcdotacao_classe.php"));
require_once(modification("classes/db_orcreservaaut_classe.php"));
require_once(modification("classes/db_orcdotacaoval_classe.php"));
require_once(modification("classes/db_orcreserva_classe.php"));

$clorcreserva	  	= new cl_orcreserva;
$clorcdotacao	  	= new cl_orcdotacao;
$clorcreservaaut  = new cl_orcreservaaut;
$clorcelemento    = new cl_orcelemento;

require_once(modification("classes/db_conlancam_classe.php"));
require_once(modification("classes/db_conlancamele_classe.php"));
require_once(modification("classes/db_conlancamlr_classe.php"));
require_once(modification("classes/db_conlancamcgm_classe.php"));
require_once(modification("classes/db_conlancamemp_classe.php"));
require_once(modification("classes/db_conlancamval_classe.php"));
require_once(modification("classes/db_conlancamdot_classe.php"));
require_once(modification("classes/db_conlancamdoc_classe.php"));
require_once(modification("classes/db_conlancamcompl_classe.php"));
require_once(modification("classes/db_conlancamnota_classe.php"));

$clconlancam	  	= new cl_conlancam;
$clconlancamele	  = new cl_conlancamele;
$clconlancamlr	  = new cl_conlancamlr;
$clconlancamcgm	  = new cl_conlancamcgm;
$clconlancamemp	  = new cl_conlancamemp;
$clconlancamval	  = new cl_conlancamval;
$clconlancamdot	  = new cl_conlancamdot;
$clconlancamdoc	  = new cl_conlancamdoc;
$clconlancamcompl = new cl_conlancamcompl;
$clconlancamnota  = new cl_conlancamnota;

// Retenções
require_once(modification("classes/db_empautret_classe.php"));
require_once(modification("classes/db_empempret_classe.php"));
require_once(modification("classes/db_empretencao_classe.php"));

// lançamentos contábeis
require_once(modification("classes/empenho.php"));

db_app::import("exceptions.*");
db_app::import("configuracao.*");
require_once(modification("model/CgmFactory.model.php"));
require_once(modification("model/CgmBase.model.php"));
require_once(modification("model/CgmJuridico.model.php"));
require_once(modification("model/CgmFisico.model.php"));
require_once(modification("model/Dotacao.model.php"));


$clempautret	  	= new cl_empautret;
$clempempret	  	= new cl_empempret;
$clempretencao	  = new cl_empretencao;


parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

if (isset($e54_concarpeculiar) && trim(@$e54_concarpeculiar) != ""){
  $concarpeculiar       = $e54_concarpeculiar;
  $descr_concarpeculiar = @$c58_descr;
}

$anousu           = db_getsession("DB_anousu");
$iInstituicao     = db_getsession("DB_instit");
$alertar_retencao = false;
$lControlePacto   = false;
$aParametrosOrcamento = db_stdClass::getParametro("orcparametro",array(db_getsession("DB_anousu")));
if (count($aParametrosOrcamento) > 0) {
  if ( isset($aParametrosOrcamento[0]->o50_utilizapacto) ) {
    $lControlePacto = $aParametrosOrcamento[0]->o50_utilizapacto=="t"?true:false;
  }
}
if(isset($tipocompra)){
  $db_opcao = 1;
  $db_botao = true;
}else{
  $db_opcao = 33;
  $db_botao = false;
}

$lLiquidar               = "";
$lLiquidaMaterialConsumo = 'false';
$sGrupoDesdobramento = null;

if (!empty($iElemento)) {

  if (USE_PCASP) {

    $oGrupoContaOrcamento = GrupoContaOrcamento::getGrupoConta($iElemento, db_getsession("DB_anousu"));

    if ($oGrupoContaOrcamento instanceof GrupoContaOrcamento) {

      if (in_array($oGrupoContaOrcamento->getCodigo(), array(7,8,9))) {

        $sGrupoDesdobramento = $oGrupoContaOrcamento->getDescricao();
        if ( $oGrupoContaOrcamento->getCodigo() == 9 ) {

          $sMensagem = "O desdobramento deste empenho está no grupo {$sGrupoDesdobramento} ";
          $lLiquidar = "disabled='disabled'";
          db_msgbox("$sMensagem. não será possível liquidar o empenho diretamente");
        } else {
          $lLiquidaMaterialConsumo = 'true';
        }
      }
    }
  }
}

if (isset($incluir)) {
  $sqlerro = false;
  db_inicio_transacao();

  try {

    /* [Extensão] Programação Financeira */

    //////////////////////////////Controle Andamento da SOlicitação de Compras/////////////////////
    //----------------------------REcebe processo se existe tranferencia -------------
    $result_pcparam = $clpcparam->sql_record($clpcparam->sql_query_file(db_getsession("DB_instit"),
                                                                        "pc30_gerareserva,pc30_contrandsol"
    )
    );
    db_fieldsmemory($result_pcparam, 0);
    if ($pc30_contrandsol == 't') {

      $sqltran     = "select distinct x.p62_codtran

			from ( select distinct p62_codtran,
                             p62_dttran,
                             p63_codproc,
                             descrdepto,
                             p62_hora,
                             login,
                             pc11_numero,
			                       pc11_codigo,
                             pc81_codproc,
                             e55_autori,
                             e54_autori,
			                       e54_anulad
		                    from proctransferproc

                             inner join solicitemprot        on pc49_protprocesso                   = proctransferproc.p63_codproc
                             inner join solicitem            on pc49_solicitem                      = pc11_codigo
                             inner join proctransfer         on p63_codtran                         = p62_codtran
						                 inner join db_depart            on coddepto                            = p62_coddepto
						                 inner join db_usuarios          on id_usuario                          = p62_id_usuario
						                 inner join pcprocitem           on pcprocitem.pc81_solicitem           = solicitem.pc11_codigo
                             inner join empautitempcprocitem on empautitempcprocitem.e73_pcprocitem = pcprocitem.pc81_codprocitem
                             inner join empautitem           on empautitem.e55_autori               = empautitempcprocitem.e73_autori
                                                            and empautitem.e55_sequen               = empautitempcprocitem.e73_sequen
						                 inner join empautoriza          on empautoriza.e54_autori              = empautitem.e55_autori
             			     where p62_coddeptorec = " . db_getsession("DB_coddepto") . "
                 ) as x
				 left join proctransand 	on p64_codtran = x.p62_codtran
				 left join arqproc 	on p68_codproc = x.p63_codproc
			where p64_codtran is null and p68_codproc is null and x.e54_autori = {$e54_autori}";
      $result_tran = db_query($sqltran);
      if (pg_numrows($result_tran) != 0) {
        for ($w = 0; $w < pg_numrows($result_tran); $w++) {
          db_fieldsmemory($result_tran, $w);
          $recebetransf = recprocandsol($p62_codtran);
          if ($recebetransf == true) {
            $sqlerro = true;
            break;
          }
        }
      }
    }

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    $sql = "update empparametro set e39_anousu = e39_anousu where e39_anousu = " . db_getsession("DB_anousu");
    $res = db_query($sql);

    $clempempaut->sql_record($clempempaut->sql_query(null, "*", "", "e61_autori = $e54_autori"));
    if ($clempempaut->numrows > 0) {
      $erro_msg = "Autorização empenhada!";
      $sqlerro  = true;
    }

    $resaut = $clempautoriza->sql_record($clempautoriza->sql_query_file($e54_autori, "e54_anousu", "", ""));
    if ($clempautoriza->numrows > 0) {
      db_fieldsmemory($resaut, 0);
      if ($e54_anousu != db_getsession("DB_anousu")) {
        $erro_msg = "Autorização de outro exercício.! ($e54_anousu)";
        $sqlerro  = true;
      }
    } else {
      $erro_msg = "Autorização não encontrada!";
      $sqlerro  = true;
    }

    //verifica o saldo dos itens com o saldo total da autorização
    $resdiftot = $clempempaut->sql_record("select e54_autori,
                                    e54_valor,
									sum(e55_vltot) as e55_vltot
							   from empautoriza
							  inner join empautitem on e54_autori = e55_autori
							  where e54_autori = $e54_autori
							  group by e54_autori, e54_valor
							  having cast(sum(round(e55_vltot,2)) as numeric) <> cast(round(e54_valor,2) as numeric)
							  "
    );

    if ($clempempaut->numrows > 0) {
      db_fieldsmemory($resdiftot, 0);
      $erro_msg = "Valor total dos itens diferente do valor total da autorização. Vlr. da Autorização: $e54_valor - Vlr. Total dos Itens: $e55_vltot ";
      $sqlerro  = true;
    }

    if ($sqlerro == false) {
      // chama função de critica para empenhos
      $sql = "select fc_verifica_lancamento(" . $e54_autori . ",'" . date("Y-m-d", db_getsession("DB_datausu"))
             . "',1,00.00)";
      $result_erro = db_query($sql) or die($sql);
      $erro_msg = pg_result($result_erro, 0, 0);
      if (substr($erro_msg, 0, 2) > 0) {
        $erro_msg = substr($erro_msg, 3);
        $sqlerro  = true;
      }
    }


    /*inicio-conlancamval*/

    $cltranslan->db_trans_empenho($e54_codcom, db_getsession("DB_anousu"));
    $arr_debito     = $cltranslan->arr_debito;
    $arr_credito    = $cltranslan->arr_credito;
    $arr_histori    = $cltranslan->arr_histori;
    $arr_seqtranslr = $cltranslan->arr_seqtranslr;

    if (count($arr_credito) == 0) {
      $sqlerro  = true;
      $erro_msg = "Não existem transações cadastradas para esta instituição.";
    }


    /*rotina de incluir  na tabela empempenho*/
    if ($sqlerro == false) {
      //$clempempenho->e60_numemp  = $e60_numemp;
      $e60_numemp = '';

      /*
       *  NÃO COMENTAR A LINHA ABAIXO,
      *  ELA SERVER PARA NUMERAR OS EMPENHOS EM BAGE, ONDE EXISTE O EMPENHO 1 NA PREFEITURA,1 NO DAEBE E 1 NA CAMARA
      *
      */
      $result = $clempparamnum->sql_record($clempparamnum->sql_query_file($anousu,
                                                                          db_getsession("DB_instit"),
                                                                          " (e29_codemp + 1) as e60_codemp"
      )
      );

      if ($clempparamnum->numrows == 0) {

        $result = $clempparametro->sql_record($clempparametro->sql_query_file($anousu,
                                                                              "(e30_codemp+1) as e60_codemp,e30_notaliquidacao"
        )
        );
        if ($clempparametro->numrows > 0) {
          db_fieldsmemory($result, 0);
          $clempempenho->e60_codemp = $e60_codemp;

          /*rotina que atualiza a tabela empparametro*/
          $clempparametro->e39_anousu         = $anousu;
          $clempparametro->e30_codemp         = $e60_codemp;
          $clempparametro->e30_notaliquidacao = $e30_notaliquidacao;
          $clempparametro->alterar($anousu);
          if ($clempparametro->erro_status == 0) {
            $sqlerro = true;
          }
          /*final*/
        } else {
          $erro_msg = "Preencha os parametros da tabela empparametro para o exercicio $anousu!";
          $sqlerro  = true;
        }

      } else {

        db_fieldsmemory($result, 0);
        $clempempenho->e60_codemp = $e60_codemp;

        /*rotina que atualiza a tabela empparametro*/
        $clempparamnum->e29_anousu = $anousu;
        $clempparamnum->e29_instit = db_getsession('DB_instit');
        $clempparamnum->e29_codemp = $e60_codemp;
        $clempparamnum->alterar($anousu, db_getsession('DB_instit'));
        if ($clempparamnum->erro_status == 0) {
          $erro_msg = "Tabela de parametros por instituicao para o exercicio $anousu nao criada!";
          $sqlerro  = true;
        }
        /*final*/


      }
      /**
       * Verificamos se existe o empenho cadastrado com o numero na base de dados:
       */
      if (!$sqlerro) {

        $sWhereVerificacaoEmpenho = "e60_codemp = '{$e60_codemp}' ";
        $sWhereVerificacaoEmpenho .= "and e60_anousu = {$anousu} and e60_instit = {$iInstituicao}";
        $sSqlEmpenhoExistente = $clempempenho->sql_query_file(null, "e60_numemp", null, $sWhereVerificacaoEmpenho);
        $rsVerificacaoEmpenho = $clempempenho->sql_record($sSqlEmpenhoExistente);
        if ($clempempenho->numrows > 0) {

          $sqlerro  = true;
          $erro_msg = "Já existe um empenho de número {$e60_codemp}/{$anousu} lançado na instituição!" . pg_last_error(
            );
        }
      }

      if ($sqlerro == false) {

        $result = $clempautidot->sql_record($clempautidot->sql_query_file($e54_autori, "e56_anousu,e56_coddot"));
        db_fieldsmemory($result, 0);
        $clempempenho->e60_anousu = $e56_anousu;
        $clempempenho->e60_coddot = $e56_coddot;
        $clempempenho->e60_numcgm = $e54_numcgm;
        $clempempenho->e60_emiss  = date("Y-m-d", db_getsession("DB_datausu"));
        $clempempenho->e60_vencim = date("Y-m-d", db_getsession("DB_datausu"));

        $result = db_dotacaosaldo(8, 2, 2, "true", "o58_coddot=$e56_coddot", db_getsession("DB_anousu"));
        db_fieldsmemory($result, 0);
        $clempempenho->e60_vlrorc = $dot_ini;  //valor disponivel

        $result = $clempautitem->sql_record($clempautitem->sql_query_file($e54_autori,
                                                                          null,
                                                                          "sum(e55_vltot) as e60_vlremp"
        )
        );
        db_fieldsmemory($result, 0);
        $e60_vlremp                       = number_format($e60_vlremp, "2", '.', "");
        $clempempenho->e60_vlremp         = $e60_vlremp;      //valor dos itens
        $clempempenho->e60_salant         = "$atual";
        $clempempenho->e60_vlrliq         = '0';
        $clempempenho->e60_vlrpag         = '0';
        $clempempenho->e60_vlranu         = '0';
        $clempempenho->e60_codcom         = $e54_codcom;
        $clempempenho->e60_tipol          = $e54_tipol;
        $clempempenho->e60_numerol        = $e54_numerl;
        $clempempenho->e60_destin         = $e54_destin;
        $clempempenho->e60_codtipo        = $e54_codtipo;
        $clempempenho->e60_resumo         = $e54_resumo;
        $clempempenho->e60_instit         = db_getsession("DB_instit");
        $clempempenho->e60_concarpeculiar = $e54_concarpeculiar;

        $clempempenho->incluir($e60_numemp);
        if ($clempempenho->erro_status == 0) {
          $sqlerro = true;
        }

        $erro_msg   = $clempempenho->erro_msg;
        $ok_msg     = $clempempenho->erro_msg;
        $e60_numemp = $clempempenho->e60_numemp;

          // <!-- ContratosPADRS: despesa funcionario inserir -->
      }
    }

    /**
     * Verificamos se foi vinculado algum contrato com o empenho
     */
    $lEmpenhoVinculadoContrato = false;
    if (!empty($ac16_sequencial) && !$sqlerro) {

      $oDaoEmpempenhoContrato              = db_utils::getDao("empempenhocontrato");
      $oDaoEmpempenhoContrato->e100_acordo = $ac16_sequencial;
      $oDaoEmpempenhoContrato->e100_numemp = $e60_numemp;
      $oDaoEmpempenhoContrato->incluir(null);
      if ($oDaoEmpempenhoContrato->erro_status == 0) {

        $erro_msg = $oDaoEmpempenhoContrato->erro_msg;
        $sqlerro  = true;
      }
      $lEmpenhoVinculadoContrato = true;
    }
    /*fim rotina empempenho*/
    /*rotina que inclui no emppresta*/
    /**
     * Variavel para verificar se o empenho eh uma prestacao de contas.
     * Caso seja uma prestacao de contas devemos seta-la para true
     *
     * @var $isPrestacaoContas
     */
    $isPrestacaoContas = false;
    if (isset($e44_tipo) && $e44_tipo != '' and $sqlerro == false) {

      $result = $clempprestatip->sql_record($clempprestatip->sql_query_file($e44_tipo, "e44_obriga"));
      db_fieldsmemory($result, 0);

      if ($e44_obriga != 0) {

        $clemppresta->e45_numemp = $e60_numemp;
        $clemppresta->e45_data   = date("Y-m-d", db_getsession("DB_datausu"));;
        $clemppresta->e45_tipo = $e44_tipo;
        $clemppresta->incluir(null);
        if ($clemppresta->erro_status == 0) {
          $sqlerro = true;
        }
        $isPrestacaoContas = true;
        $erro_msg          = $clemppresta->erro_msg;
      }
    }
    /*final*/

    //rotina para pegar o elemento da dotação
    if ($sqlerro == false) {

      $result09  = $clorcdotacao->sql_record($clorcdotacao->sql_query_ele(db_getsession("DB_anousu"),
                                                                          $e56_coddot,
                                                                          "o56_elemento as elemento_emp"
      )
      );
      $numrows09 = $clorcdotacao->numrows;
      if ($numrows09 > 0) {
        db_fieldsmemory($result09, 0);
      } else {

        $sqlerro  = true;
        $erro_msg = "Não existe elemento para dotação $e56_coddot";
      }
    }
    //final

    /*rotina que inclui na tabela empempitem*/
    if ($sqlerro == false) {

      $result  = $clempautitem->sql_record($clempautitem->sql_query_file($e54_autori));
      $numrows = $clempautitem->numrows;
      for ($i = 0; $i < $numrows; $i++) {

        db_fieldsmemory($result, $i);
        //rotina para pegar o elemento da dotação
        $result09  = $clorcelemento->sql_record($clorcelemento->sql_query_file($e55_codele,
                                                                               db_getsession("DB_anousu"),
                                                                               "o56_elemento as elemento_item"
        )
        );
        $numrows09 = $clorcelemento->numrows;
        if ($numrows09 > 0) {
          db_fieldsmemory($result09, 0);
        } else {

          $sqlerro  = true;
          $erro_msg = "Não existe elemento para o iten $e55_item";
        }
        //final

        //rotina que compara os elementos da dotação do empenho com a dotação dos itens
        if (substr($elemento_emp, 0, 6) != substr($elemento_item, 0, 6)) {
          $erro_msg = "Subelemento do item diferente da dotação. Verifique!";
          $sqlerro  = true;
        }
        if ($sqlerro == false) {

          $clempempitem->e62_numemp            = $e60_numemp;
          $clempempitem->e62_item              = $e55_item;
          $clempempitem->e62_sequen            = $e55_sequen;
          $clempempitem->e62_quant             = $e55_quant;
          $clempempitem->e62_vltot             = $e55_vltot;
          $clempempitem->e62_vlrun             = $e55_vlrun;
          $clempempitem->e62_servicoquantidade = $e55_servicoquantidade == "f" ? "false" : "true";
          $e55_descr                           = AddSlashes($e55_descr);
          $clempempitem->e62_descr             = $e55_descr;
          $clempempitem->e62_codele            = $e56_codele;
          $clempempitem->incluir($e60_numemp, $e55_sequen);
          $erro_msg = $clempempitem->erro_msg;

          if ($clempempitem->erro_status == 0) {
            $sqlerro = true;
            break;
          }

          /*
           * Verificamos se o item está vinculado a uma autorização de um pacto sem solicitação
          * Se a autorização foi gerada sem solicitaçao, controla o saldo do pacto, do contrário esse controle foi realizado
          * no momento da inclusão da solicitação
          *
          */
          $sSqlEmpAutorizaSol = "select *
          from empautoriza
          inner join empautitem on empautitem.e55_autori       = empautoriza.e54_autori
          left join pcprocitem on pcprocitem.pc81_codprocitem = empautitem.e55_sequen
          left join solicitem  on solicitem.pc11_codigo       = pcprocitem.pc81_solicitem
          where solicitem.pc11_numero is null
          and e54_autori = $e54_autori ";
          $rsEmpAutorizaSol   = db_query($sSqlEmpAutorizaSol);

          if (!$sqlerro && $lControlePacto && pg_numrows($rsEmpAutorizaSol) > 0) {

            $sSqlItemPacto = $clempautitem->sql_query_item_pacto($e54_autori, $e55_sequen);
            $rsItemPacto   = $clempautitem->sql_record($sSqlItemPacto);
            if ($clempautitem->numrows > 0) {

              $oItemPactoSol = db_utils::fieldsMemory($rsItemPacto, 0);
              $oEmpenho      = new empenho();
              try {
                $oEmpenho->baixarSaldoPacto($clempempitem->e62_sequencial,
                                            $oItemPactoSol->o88_pactovalor,
                                            $e55_quant,
                                            $e55_vltot
                );

              } catch (Exception $eEmpenho) {

                $sqlerro  = true;
                $erro_msg = $eEmpenho->getMessage();
              }
            }
          }
        }
      }
    }
    /*final rotina */

    /*rotina de incluir  na tabela empelemento*/
    if ($sqlerro == false) {
      $result                    = $clempautitem->sql_record($clempautitem->sql_query_elemento($e54_autori));
      $numrows                   = $clempautitem->numrows;
      $clempelemento->e64_numemp = $e60_numemp;
      for ($i = 0; $i < $numrows; $i++) {
        db_fieldsmemory($result, $i);
        $clempelemento->e64_codele = $e56_codele;
        $clempelemento->e64_vlremp = number_format($e55_vltot, "2", '.', "");      //valor dos itens
        $clempelemento->e64_vlrliq = '0';
        $clempelemento->e64_vlrpag = '0';
        $clempelemento->e64_vlranu = '0';
        $clempelemento->incluir($e60_numemp, $e56_codele);
        $erro_msg = $clempelemento->erro_msg;
        if ($clempelemento->erro_status == 0) {
          $sqlerro = true;
        }
        $clempelemento->e64_codele = null;
        $clempelemento->e64_vlremp = null;      //valor dos itens
        $clempelemento->e64_vlrliq = null;
        $clempelemento->e64_vlrpag = null;
        $clempelemento->e64_vlranu = null;
      }
    }


    /*rotina que inclui na tabela empemphist*/
    if ($sqlerro == false && $e57_codhist != "Nenhum") {
      $clempemphist->e63_numemp  = $e60_numemp;
      $clempemphist->e63_codhist = $e57_codhist;
      $clempemphist->incluir($e60_numemp);
      $erro_msg = $clempemphist->erro_msg;
      if ($clempemphist->erro_status == 0) {
        $sqlerro = true;
      }
    }
    /*final rotina que inclui em empemphist*/

    /*rotina que inclui na tabela empempaut*/
    if ($sqlerro == false) {
      $clempempaut->e61_numemp = $e60_numemp;
      $clempempaut->e61_autori = $e54_autori;
      $clempempaut->incluir($e60_numemp);
      $erro_msg = $clempempaut->erro_msg;
      if ($clempempaut->erro_status == 0) {
        $sqlerro = true;
      }
    }
    /*final da rotina*/

    /**
     * Insere na tabela empempenhofinalidadepagamentofundeb
     */
    if (!$sqlerro) {

      try {

        $oEmpenhoFinanceiro = new EmpenhoFinanceiro($e60_numemp);
        $iRecursoDotacao    = $oEmpenhoFinanceiro->getDotacao()->getRecurso();

        if ($iRecursoDotacao == ParametroCaixa::getCodigoRecursoFUNDEB(db_getsession('DB_instit'))) {

          $oEmpenhoFinanceiro->setFinalidadePagamentoFundeb(FinalidadePagamentoFundeb::getInstanciaPorCodigo($e151_codigo
          )
          );
          $oEmpenhoFinanceiro->salvarFinalidadePagamentoFundeb();
        }


      } catch (Exception $eErro) {

        $sqlerro  = true;
        $erro_msg = $eErro->getMessage();
      }
    }

    /*rotina que exclui orcreserva e  aut e sol*/


    if ($sqlerro == false) {
      $result = $clorcreservaaut->sql_record($clorcreservaaut->sql_query(null,
                                                                         "o83_codres",
                                                                         "",
                                                                         "o83_autori=$e54_autori"
      )
      );
      db_fieldsmemory($result, 0);

      $clorcreservaaut->o83_codres = $o83_codres;
      $clorcreservaaut->excluir($o83_codres);
      $erro_msg = $clorcreservaaut->erro_msg;
      if ($clorcreservaaut->erro_status == 0) {
        $sqlerro = true;
      }
    }

    if ($sqlerro == false) {
      $clorcreserva->o80_codres = $o83_codres;
      $clorcreserva->excluir($o83_codres);
      $erro_msg = $clorcreserva->erro_msg;
      if ($clorcreserva->erro_status == 0) {
        $sqlerro = true;
      }
    }
    /*final rotina que exclui do orcreserva e aut*/

    if (!$sqlerro) {

      $oDaoEmpenhoNl->e68_numemp = $e60_numemp;
      $oDaoEmpenhoNl->e68_data   = date("Y-m-d", db_getsession("DB_datausu"));
      $oDaoEmpenhoNl->incluir(null);
      if ($oDaoEmpenhoNl->erro_status == 0) {

        $erro_msg = "Erro ao incluir empenho como nota de liquidação;";
        $sqlerro  = true;

      }
    }

    /** [Extensao ProgramacaoFinanceira] - Bloqueio de Empenho por SALDO */

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //////////////////////////LANÇAMENTO CONTÁBIL//////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    if ($sqlerro == false) {
      $result09  = $clempelemento->sql_record($clempelemento->sql_query($e60_numemp,
                                                                        null,
                                                                        "e64_codele,o56_elemento,e64_vlremp"
      )
      );
      $numrows09 = $clempelemento->numrows;
    }

    $anousu     = db_getsession("DB_anousu");
    $datausu    = date("Y-m-d", db_getsession("DB_datausu"));
    $c71_coddoc = '1';

    $oEmpenhoFinanceiro = null;
    try {
      $oEmpenhoFinanceiro = new EmpenhoFinanceiro($e60_numemp);
    } catch (Exception $eErro) {

      $erro_msg = $eErro->getMessage();
      $sqlerro  = false;
    }

    if ($sqlerro == false && !empty($oEmpenhoFinanceiro)) {

      if (USE_PCASP) {

        if ($isPrestacaoContas) {
          $c71_coddoc = 410;
        }

        $isProvisaoFerias         = $oEmpenhoFinanceiro->isProvisaoFerias();
        $isProvisaoDecimoTerceiro = $oEmpenhoFinanceiro->isProvisaoDecimoTerceiro();
        $isAmortizacaoDivida      = $oEmpenhoFinanceiro->isAmortizacaoDivida();
        $isPrecatoria             = $oEmpenhoFinanceiro->isPrecatoria();

        if ($isProvisaoFerias) {
          $c71_coddoc = 304;
        }

        if ($isProvisaoDecimoTerceiro) {
          $c71_coddoc = 308;
        }

        if ($isAmortizacaoDivida) {
          $c71_coddoc = 504; // EMPENHO AMORT. DA DÍVIDA
        }

        if ($isPrecatoria) {
          $c71_coddoc = 500; // EMPENHO DE PRECATÓRIOS
        }
      }


      for ($i = 0; $i < $numrows09; $i++) {

        db_fieldsmemory($result09, $i);

        $iCodigoDotacao = $e56_coddot;
        $sComplemento   = "Lançamento do Empenho " . $oEmpenhoFinanceiro->getNumero() . "/" . $oEmpenhoFinanceiro->getAno();

        if (isset($e54_resumo) && !empty($e54_resumo)) {
          $sComplemento .= "  {$e54_resumo}";
        }

        try {

          $oDotacao              = new Dotacao($iCodigoDotacao, $oEmpenhoFinanceiro->getAno());
          $oContaCorrenteDetalhe = new ContaCorrenteDetalhe();
          $oContaCorrenteDetalhe->setDotacao($oDotacao);
          $oContaCorrenteDetalhe->setEmpenho($oEmpenhoFinanceiro);
          $oContaCorrenteDetalhe->setRecurso($oDotacao->getDadosRecurso());

          /**
           * Valida parametro de integracao da contabilidade com contratos
           */
          $oEventoContabil     = new EventoContabil($c71_coddoc, $anousu);
          $oLancamentoAuxiliar = new LancamentoAuxiliarEmpenho();
          $oLancamentoAuxiliar->setCaracteristicaPeculiar($clempempenho->e60_concarpeculiar);
          $oLancamentoAuxiliar->setCodigoElemento($e56_codele);
          $oLancamentoAuxiliar->setFavorecido($oEmpenhoFinanceiro->getCgm()->getCodigo());
          $oLancamentoAuxiliar->setNumeroEmpenho($e60_numemp);
          $oLancamentoAuxiliar->setValorTotal($oEmpenhoFinanceiro->getValorEmpenho());
          $oLancamentoAuxiliar->setObservacaoHistorico($sComplemento);
          $oLancamentoAuxiliar->setEmpenhoFinanceiro($oEmpenhoFinanceiro);
          $oLancamentoAuxiliar->setCodigoDotacao($iCodigoDotacao);
          $oLancamentoAuxiliar->setContaCorrenteDetalhe($oContaCorrenteDetalhe);
          $oEventoContabil->executaLancamento($oLancamentoAuxiliar);

          /**
           * Pesquisa contrato do empenho
           * - caso exista gera lancamento
           */
          $oDataImplantacao = new DBDate(date("Y-m-d", db_getsession('DB_datausu')));
          $oInstituicao     = InstituicaoRepository::getInstituicaoByCodigo(db_getsession('DB_instit'));
          if (ParametroIntegracaoPatrimonial::possuiIntegracaoContrato($oDataImplantacao, $oInstituicao)) {

            $oDaoEmpenhoContrato = db_utils::getDao("empempenhocontrato");
            $sSqlContrato        = $oDaoEmpenhoContrato->sql_query_file(null,
                                                                        "e100_acordo",
                                                                        null,
                                                                        "e100_numemp = {$e60_numemp}");

            $rsContrato = $oDaoEmpenhoContrato->sql_record($sSqlContrato);

            if ($oDaoEmpenhoContrato->numrows > 0) {

              $iCodigoAcordo         = db_utils::fieldsMemory($rsContrato, 0)->e100_acordo;
              $oAcordo               = new Acordo($iCodigoAcordo);
              $oEventoContabilAcordo = new EventoContabil(900, $anousu);

              $oLancamentoAuxiliarAcordo = new LancamentoAuxiliarAcordo();
              $oLancamentoAuxiliarAcordo->setEmpenho($oEmpenhoFinanceiro);
              $oLancamentoAuxiliarAcordo->setAcordo($oAcordo);
              $oLancamentoAuxiliarAcordo->setValorTotal($oEmpenhoFinanceiro->getValorEmpenho());

              $oContaCorrente = new ContaCorrenteDetalhe();
              $oContaCorrente->setAcordo($oAcordo);
              $oLancamentoAuxiliarAcordo->setContaCorrenteDetalhe($oContaCorrente);
              $oEventoContabilAcordo->executaLancamento($oLancamentoAuxiliarAcordo);
            }
          }

        } catch (Exception $eErro) {

          $erro_msg = $eErro->getMessage();
          $sqlerro  = true;
          break;
        }

      }

    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //FINAL LANÇAMENTO CONTÁBEIS////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // LIQUIDAÇÃO////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


    //rotina que pega os valores dos elementos e coloca na variavel $dados
    if ($sqlerro == false) {
      $result03  = $clempelemento->sql_record($clempelemento->sql_query($e60_numemp, null, "e64_vlremp,e64_codele"));
      $numrows03 = $clempelemento->numrows;
      $dados     = '';
      $sep       = '';
      for ($e = 0; $e < $numrows03; $e++) {
        db_fieldsmemory($result03, $e);
        $dados .= $sep . $e64_codele . "-" . $e64_vlremp;
        $sep = '#';
      }
    }
    //**********************************************************/

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //ARQUVO DE LIQUIDAÇÃO////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    if ($sqlerro == false) {
      if ($opc == 1) {
        //$dados tem todos os elementos e seus valores
        //variáveis necessárias//
        //$dados =  $elemento-$valorliquidar#$elemento-$valorliquidar#elemen...
        //$e60_numemp =  $e60_numemp;
        //$vlrliq     =  $vlrliq;
        $vlrliq = $e60_vlremp;
        require_once(modification("emp1_empliquidaarq.php"));
      }
    }
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //FINAL DE LIQUIDAÇÃO////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //ARQUVO DE ORDEM DE PAGAMENTO////////////////////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    if ($opc == 5 && $sqlerro == false) {
      //variaveis
      //$e50_numemp = $e50_numemp;
      //$e50_obs    = $e50_obs;
      //$dados = $elemento-$valor#elemento-valor#elem..
      //$chaves, é setado quando tiver notas

      $e50_numemp = $e60_numemp;
      $e50_obs    = "Ordem de pagamento";
      require_once(modification("emp1_pagordemarq.php"));
    }
    //FINAL ORDEM DE PAGAMENTO//

    if ($sqlerro == false) {

      /**
       * Classificação de Credores
       * Cria vínculo entre empenho e lista de classificação
       */
      try {

        if (!isset($classificacao_credor_combo)) {
          $classificacao_credor_combo = 0;
        }

        $oEmpenho             = new EmpenhoFinanceiro($e60_numemp);
        $lDispensaManual      = $classificacao_credor_combo == 1;
        $oClassificacaoCredor = ListaClassificacaoCredorRepository::getPorCodigo(ListaClassificacaoCredor::DISPENSA_MANUAL);
        if (!$lDispensaManual) {
          $oClassificacaoCredor = ListaClassificacaoCredorRepository::getPorEmpenho($oEmpenho);
        }
        if (!empty($oClassificacaoCredor)) {
          $oClassificacaoCredor->vincularEmpenhoEmClassificacao($oEmpenho, $lDispensaManual, $cc31_justificativa);
        }

      } catch (Exception $oErro) {

        $sqlerro  = true;
        $erro_msg = $oErro->getMessage();
      }
    }

    // INICIO LIQUIDAÇÃO + NOTA DE LIQUIDAÇÃO
    if ($opc == 2 && $sqlerro == false) {

      /**
       * esta opção deve :
       * - lançar ordem de compra ( modulo compras )
       * - dar entrada dos ítens na ordem de compra com nota ( modulo almoxarifado )
       * - liquidar o empenho com nota , obrigatorio usar nota (modulo contabilidade)
       *
       * // ordem de compra
       *
       * - matordem :  lança 1 registro, ordem de compra
       * - matordemitem : ítens da ordem de compra
       *
       * // nota do fornecedor
       *
       * - empnota : nota
       * - empnotaord : ligação da nota com a ordem de compra
       * - empnotaele : elementos de empenho ligados a nota
       *
       * // entrada no estoque
       *
       * - matestoqueini : estoque
       * - matmater : materiais/itens, cadastro de materiais ( 1:pcmatar ------ N:matmater )
       * - matestoque : uma tabela que sintetiza valores para relatorios do modulo  almox/estoque
       * - matestoqueitem : representa os ítens que estao no almox
       * - matestoqueitemunid : representa como as quantidades são representadas dos ítens
       * - matestoqueitemoc : itens ligados a ordem de com pra
       * - matestoqueitemnota : itens ligados a nota ( empnota )
       *
       */
      try {

        /** [Extensao ContratosPADRS] valida serie nota */

        $oDataNota        = new DBDate($e69_dtnota);
        $oDataRecebimento = new DBDate($e69_dtrecebe);
        $oDataVencimento  = empty($e69_dtvencimento) ? null : new DBDate($e69_dtvencimento);
        $e69_dtnota       = $oDataNota->getDate();
        $e69_dtrecebe     = $oDataRecebimento->getDate();
        $e69_dtvencimento = $oDataVencimento ? $oDataVencimento->getDate() : null;

        $oListaClassificacaoCredor = $oEmpenho->getListaClassificacaoCredor();
        if (!empty($oListaClassificacaoCredor)) {

          $oEmpenho->getListaClassificacaoCredor()->validarParametros($e69_dtnota,
            $e69_dtrecebe,
            $e69_dtvencimento,
            $e69_localrecebimento);

          $oEmpenho->getListaClassificacaoCredor()->validarDatas($oDataNota, $oDataRecebimento, $oDataVencimento);
        }

        require_once(modification("mat1_matestoque_arqnota.php"));

        if ($clempnota->erro_status == 0) {
          throw new DBException('Não foi possível salvar a Nota de Liquidação.');
        }

        /** [Extensao ContratosPADRS] nota liquidacao */

        // liquidação contábil
        $clempenho = new empenho();
        $clempenho->setCompetenciaLiquidacao($_POST["competencia_regime"]);
        $clempenho->liquidar($e60_numemp, $e64_codele, $clempnota->e69_codnota, $e60_vlremp, $e54_resumo);
        $e50_codord = $clempenho->iPagOrdem;
        if ($clempenho->erro_status == '0') {
          $sqlerro  = true;
          $erro_msg = $clempenho->erro_msg;
          db_msgbox($erro_msg);
        }
      } catch (Exception $oErro) {

        $sqlerro  = true;
        $erro_msg = $oErro->getMessage();
      }
    }

    //ARQUVO DE RETENÇÕES///
    if ($sqlerro == false) {
      /**
       * seleciona as retenções lançadas na autorização e
       * lança para o empenho (duplica-as)
       */
      $result_retencao  = $clempautret->sql_record($clempautret->sql_query($e54_autori, null, "empretencao.*"));
      $numrows_retencao = $clempautret->numrows;
      for ($i = 0; $i < $numrows_retencao; $i++) {
        db_fieldsmemory($result_retencao, $i);
        $clempretencao->e65_seq      = null;
        $clempretencao->e65_receita  = $e65_receita;
        $clempretencao->e65_aliquota = $e65_aliquota;
        $clempretencao->e65_valor    = $e65_valor;
        $clempretencao->incluir($clempretencao->e65_seq);
        if ($clempretencao->erro_status == 0) {
          $erro_msg = $clempretencao->erro_msg;
          $sqlerro  = true;
          break;
        }
        if ($sqlerro == false) {
          $clempempret->e67_numemp      = $e60_numemp;
          $clempempret->e67_seqretencao = $clempretencao->e65_seq;
          $clempempret->incluir($e60_numemp, $clempretencao->e65_seq);
          if ($clempempret->erro_status == 0) {
            $erro_msg = $clempempret->erro_msg;
            $sqlerro  = true;
            break;
          }
        }
      }// end loop
    }
    //FINAL DE RETENÇÕES//

    //     $sqlerro = true;
    // db_msgbox("Registro não incluido , transação comentada no script emp1_empempenho004");
    /**[Extensao Ordenador Despesa] inclusao_ordenador*/


  } catch (Exception $e) {

    $sqlerro  = true;
    $erro_msg = $e->getMessage();
  }

  db_fim_transacao($sqlerro);

  $db_opcao = 1;
  $db_botao = true;

} else if (isset($chavepesquisa)) {
  $db_opcao = 1;

  $result = $clempautoriza->sql_record($clempautoriza->sql_query($chavepesquisa));
  db_fieldsmemory($result,0);

  if(mktime(0,0,0,substr($e54_emiss,5,2),substr($e54_emiss,8,2),substr($e54_emiss,0,4)) > db_getsession("DB_datausu")){
    db_msgbox("Data da autorização (".db_formatar($e54_emiss,"d").") maior que data do empenho.");
  }

  $result=$clempauthist->sql_record($clempauthist->sql_query_file($e54_autori));
  if($clempauthist->numrows>0){
    db_fieldsmemory($result,0);
  }

  $result = $clempautpresta->sql_record($clempautpresta->sql_query_file(null,"*","e58_autori","e58_autori=$e54_autori"));
  if($clempautpresta->numrows>0) {
    db_fieldsmemory($result,0);
    $e44_tipo = $e58_tipo;
  }
  $db_botao = true;

  $result_empretencao = $clempautret->sql_record($clempautret->sql_query_file($e54_autori,null,"*"));
  if($clempautret->numrows){
    $alertar_retencao = true;
  }

  $oDaoAcordoEmpautoriza = db_utils::getDao("acordoempautoriza");
  $sSqlAcordoAutorizacao = $oDaoAcordoEmpautoriza->sql_queryAutorizacaoAcordo(null, "ac16_resumoobjeto,ac45_acordo", null, "ac45_empautoriza = {$chavepesquisa}");
  $rsAcordoAutorizacao   = $oDaoAcordoEmpautoriza->sql_record($sSqlAcordoAutorizacao);
  $lMostrarCompetencia   = false;
  $ac16_sequencial       = '';
  if ($oDaoAcordoEmpautoriza->numrows > 0) {

    $oDadosAcordo       = db_utils::fieldsMemory($rsAcordoAutorizacao, 0);
    $ac16_sequencial    = $oDadosAcordo->ac45_acordo;
    $ac16_resumoobjeto  = $oDadosAcordo->ac16_resumoobjeto;
    $lAutorizacaoAcordo = true;


  }

}
$lMostrarCompetencia   = false;
if (!empty($ac16_sequencial)) {

  $oContrato = AcordoRepository::getByCodigo($ac16_sequencial);
  $oRegimeCompetenciaRepository = new RegimeCompetencia();
  $oRegime                      = $oRegimeCompetenciaRepository->getByAcordo($oContrato);
  $lMostrarCompetencia          = (!empty($oRegime) && !$oRegime->isDespesaAntecipada());
}
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/classes/empenho/ViewCotasMensais.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/dbtextField.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/windowAux.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/dbmessageBoard.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/dbtextField.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="ext/javascript/prototype.maskedinput.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body style="background-color:#CCCCCC; margin-top:30px;" >
<center>
  <?php
  require_once(modification(Modification::getFile("forms/db_frmempempenhonota.php")));
  ?>
</center>
</body>
</html>

<script>
  function js_emiteEmpenho(iNumEmp) {

    jan = window.open('emp2_emitenotaemp002.php?e60_numemp='+iNumEmp, '','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
    jan.moveTo(0,0);

    document.form1.incluir.disabled = true;
    document.form1.op.disabled = true;
  }

  function js_naoImprimir() {

    document.form1.incluir.disabled = true;
    document.form1.op.disabled = true;
  }
</script>

<?
if($alertar_retencao == true){
  db_msgbox("Autorização $e54_autori com retenções!");
}
//rotina que alerta se o usuario não tem permissão
if(isset($erro_perm)){
  db_msgbox($erro_perm);
  db_redireciona("emp4_empempenho004.php");
}
if(isset($incluir)){

  if($sqlerro == true){
    db_msgbox($erro_msg);

    if ($clempempenho->erro_campo!="") {
      echo "<script> document.form1.".$clempempenho->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clempempenho->erro_campo.".focus();</script>";
    }

  } else {

    $ord ='';
    if(isset($e50_codord) && $e50_codord != ''){
      $ord = "Nota de Liquidação: $e50_codord";
    }

    $ok_msg = "Inclusão efetuada com sucesso! \\n Empenho: $e60_codemp\/".db_getsession("DB_anousu")." \\n $ord" ;
    db_msgbox($ok_msg);

    if ( empty($naoimprimir) ) {

      echo "<script>
	 	          js_emiteEmpenho({$e60_numemp});
	 	        </script>";

    } else {


      if (isset($lanc_emp)&&$lanc_emp==true){

        exit;

      } else {
        echo "<script>js_naoImprimir()</script>";
      }
    }
  }
}

if (isset($e54_autori)) {
  echo "
       <script>
          parent.document.formaba.empempitem.disabled = false;\n
          parent.document.formaba.empempdot.disabled  = false;\n
          parent.document.formaba.empprazos.disabled  = false;\n
         (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_empempitem.location.href = 'emp1_empempitem001.php?db_opcaoal=3&e55_autori=$e54_autori';\n
         (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_empempdot.location.href  = 'emp1_empempdot001.php?e56_autori=$e54_autori';\n
         (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_empprazos.location.href  = 'emp1_empempenho007.php?chavepesquisa=$e54_autori';\n

       </script>
   ";
}

if($db_opcao==33){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
