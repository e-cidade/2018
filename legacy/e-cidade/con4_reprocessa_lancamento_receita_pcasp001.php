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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("std/DBDate.php"));
require_once modification("libs/db_liborcamento.php");
require_once modification("model/contabilidade/planoconta/ContaPlano.model.php");
require_once modification("model/contabilidade/planoconta/ContaOrcamento.model.php");
require_once modification("model/contabilidade/planoconta/ClassificacaoConta.model.php");
require_once modification("model/contabilidade/planoconta/SistemaConta.model.php");
require_once modification("model/contabilidade/planoconta/SubSistemaConta.model.php");
require_once modification("model/CgmFactory.model.php");
require_once modification("model/caixa/AutenticacaoArrecadacao.model.php");
require_once modification("model/caixa/LancamentoContabilAjusteBaixaBanco.model.php");
require_once modification("model/caixa/AutenticacaoPlanilha.model.php");
require_once modification("model/caixa/PlanilhaArrecadacao.model.php");

db_app::import("exceptions.*");
db_app::import("configuracao.Instituicao");
db_app::import("configuracao.DBEstrutura");
db_app::import("CgmFactory");
db_app::import("contaTesouraria");
db_app::import("contabilidade.*");
db_app::import("orcamento.*");
db_app::import("configuracao.*");
db_app::import("contabilidade.lancamento.*");
db_app::import("contabilidade.planoconta.*");
db_app::import("financeiro.*");
db_app::import("contabilidade.contacorrente.*");
db_app::import("exceptions.*");
echo "<div style=\"display: block;\">";
if (!USE_PCASP) {

  echo "<h1> Cliente nao utiliza pcasp</h1>";
  exit;

}

$aTables = array("cornump",
                 "corrente",
                 "corcla",
                 "corplacaixa",
                 "corplacaixa",
                 "cornumpdesconto",
                 "conlancam",
                 "conlancamval",
                 "conlancamcgm",
                 "conlancamcompl",
                 "conlancamrec",
                 "conlancampag",
                 "conlancamslip",
                 "conlancamdoc",
                 "conlancamcorgrupocorrente",
                 "conlancamcorrente",
                 "conlancamordem",
                 "conlancaminstit",
                 "contacorrentedetalheconlancamval",
                 "conlancamconcarpeculiar"
       );
if (isset($_POST["processar"])) {

  foreach ($aTables as $sTabela) {

    $sSqlAjusteDesempenho = "alter table {$sTabela} set (autovacuum_enabled = false, toast.autovacuum_enabled = false)";
    $rsAjusteDesempenho   = db_query($sSqlAjusteDesempenho);
  }

  $_SESSION["DB_desativar_account"] = true;
  $oData   = new DBDate($_POST["sData"]);
  $sData   = $oData->convertTo(DBDate::DATA_EN);

  $oDataFinal = new DBDate($_POST["sDataFinal"]);
  $sDataFinal = $oDataFinal->convertTo(DBDate::DATA_EN);

  $rsErros = fopen('tmp/erros_correcao_lancamentos.txt', 'w');

  try {

    if ($_POST["reprocessar_descontos_tesouraria"]) {

      db_inicio_transacao();
      reprocessarDescontoTesouraria($sData, $sDataFinal);
      db_fim_transacao(false);
    }
    db_inicio_transacao();
    echo "<br><br>Limpando lançamentos contabeis de receita<br>";
    flush();

    $rstrigger = db_query("alter table conlancamval disable trigger all;");
    $rstrigger = db_query("alter table conlancamval disable trigger all;");

    $rsTabelaLancamentos = db_query("create temp table w_conlancam as
                                      select distinct conlancam.*
                                        From conlancam inner join conlancamdoc  on c71_codlan = c70_codlan
                                                       inner join conhistdoc    on c53_coddoc   = c71_coddoc
                                                       inner join conlancamval  on c69_codlan = c70_codlan
                                                       inner join conplanoreduz on c61_reduz  = c69_debito
                                                                               and c69_anousu = c61_anousu
                                       where c70_data  between '{$sData}' and '{$sDataFinal}'
                                         and c53_tipo in(100, 101, 160, 162)
                                         and c61_instit = ".db_getsession("DB_instit")."
                                         and not exists(select 1
                                                         from conlancamslip where c84_conlancam = c70_codlan) 
                                         and (not exists(select 1
                                                           from conlancamemp 
                                                          where c75_codlan = c70_codlan)
                                               or exists( select 1 
                                                            from conlancamemp 
                                                                 inner join conlancamdoc on c75_codlan = c71_codlan 
                                                           where c75_codlan = c70_codlan 
                                                             and c71_coddoc = 416 )
                                             ) order by 1;"
    );

    if (!$rsTabelaLancamentos) {
      throw new Exception('Não foi possivel criar tabela para exclusão de lançamentos');
    }

    $rsDeleteConlancamCgm = db_query("delete from conlancamcgm  where c76_codlan in (select c70_codlan from w_conlancam)");
    if (!$rsDeleteConlancamCgm) {
      throw new Exception('Não foi possivel excluir dados da tabela conlancamcgm');
    }
    $rsDeleteConlancamGrupo = db_query("delete from conlancamcorgrupocorrente
                                         where c23_conlancam in (select c70_codlan from w_conlancam)"
                                      );
    if (!$rsDeleteConlancamGrupo) {
      throw new Exception('Não foi possivel excluir dados da tabela conlancamGrupo');
    }
    $rsDeleteConlancamCorrente = db_query("delete from conlancamcorrente
                                            where c86_conlancam in (select c70_codlan from w_conlancam)"
    );
    if (!$rsDeleteConlancamCorrente) {
      throw new Exception("Não foi possivel excluir dados da tabela conlancamcorrente\n".pg_last_error());
    }

    $rsDeleteConlancamRec = db_query("delete from conlancamrec
                                       where c74_codlan in (select c70_codlan from w_conlancam)"
                                    );
    if (!$rsDeleteConlancamRec)  {
      throw new Exception('Não foi possivel excluir dados da tabela conlancamrec');
    }

    $rsDeleteConlancamCompl = db_query("delete from conlancamcompl
                                       where c72_codlan in (select c70_codlan from w_conlancam)"
                                    );
    if (!$rsDeleteConlancamCompl)  {
      throw new Exception('Não foi possivel excluir dados da tabela conlancamcompl');
    }

    $rsDeleteConlancamPag = db_query("delete from conlancampag
                                       where c82_codlan in (select c70_codlan from w_conlancam)"
    );
    if (!$rsDeleteConlancamPag)  {
      throw new Exception('Não foi possivel excluir dados da tabela conlancampag');
    }

    $rsDeleteConlancamDoc = db_query("delete from conlancamdoc
                                       where c71_codlan in (select c70_codlan from w_conlancam)"
                                    );
    if (!$rsDeleteConlancamDoc)  {
      throw new Exception('Não foi possivel excluir dados da tabela conlancamdoc');
    }

    $rsDeleteConlancamCC = db_query("delete from contacorrentedetalheconlancamval
                                       where c28_conlancamval in (select c69_sequen
                                                                    from conlancamval
                                                                   where c69_codlan in (select c70_codlan
                                                                                         from w_conlancam)
                                                                   )"
                                   );
    if (!$rsDeleteConlancamCC)  {
      throw new Exception('Não foi possivel excluir dados da tabela contacorrentedetalheconlancamval');
    }

    $rsDeleteConlancamVal = db_query(" delete from conlancamval
                                        where c69_codlan in (select c70_codlan from w_conlancam)"
                                    );
    if (!$rsDeleteConlancamVal)  {
      throw new Exception('Não foi possivel excluir dados da tabela conlancamval');
    }

    $rsDeleteConlancamCP = db_query("delete from conlancamconcarpeculiar
                                      where c08_codlan in (select c70_codlan from w_conlancam)"
    );
    if (!$rsDeleteConlancamCP)  {
      throw new Exception('Não foi possivel excluir dados da tabela conlancamconcarpeculiar');
    }

    $rsDeleteConlancamOrdem = db_query( "delete from conlancamordem where c03_codlan in (select c70_codlan from w_conlancam)" );

    if (!$rsDeleteConlancamOrdem) {
      throw new Exception('Não foi possivel excluir dados da tabela conlancamordem');
    }

    $rsDeleteConlancamInstit = db_query( "delete from conlancaminstit where c02_codlan in (select c70_codlan from w_conlancam)" );

    if (!$rsDeleteConlancamInstit) {
      throw new Exception('Não foi possivel excluir dados da tabela conlancaminstit');
    }

    $rsDeleteConlancamOrdem = db_query( "delete from conlancamordem where c03_codlan in (select c70_codlan from w_conlancam)" );

    if (!$rsDeleteConlancamOrdem) {
      throw new Exception('Não foi possivel excluir dados da tabela conlancamordem');
    }

    $rsDeleteConlancamemp = db_query("delete from conlancamemp where c75_codlan in(select c70_codlan from w_conlancam)");

    if (!$rsDeleteConlancamemp) {
      throw new Exception('Não foi possivel excluir dados da tabela conlancamemp');
    }

    $rsDeleteConlancam = db_query("delete from conlancam where c70_codlan in (select c70_codlan from w_conlancam)");
    if (!$rsDeleteConlancam)  {
      throw new Exception('Não foi possivel excluir dados da tabela conlancam');
    }

    echo "Iniciando Reprocessando da arrecadacao de Receita<br>";
    flush();
    db_fim_transacao(false);

    $sSqlReceita = "select distinct k12_conta, corrente.k12_id as id,
                           corrente.k12_autent as codautent,
                           corrente.k12_data as data,
                           corrente.k12_estorn
                     From corrente
                          inner join cornump on corrente.k12_data  = cornump.k12_data
                                            and corrente.k12_id    = cornump.k12_id
                                           and corrente.k12_autent = cornump.k12_autent
                          left join corplacaixa on k82_id     = corrente.k12_id
                                               and k82_data   = corrente.k12_data
                                               and k82_autent = corrente.k12_autent
                          left join corcla on corcla.k12_id       = corrente.k12_id
                                          and corcla.k12_data     = corrente.k12_data
                                          and corrente.k12_autent = corcla.k12_autent

                   where corrente.k12_data  between '{$sData}' and '{$sDataFinal}'
                     and k82_seqpla is null
                     and corcla.k12_autent is null
                     and corrente.k12_instit = ".db_getsession("DB_instit")."
                   order by corrente.k12_data, corrente.k12_id, corrente.k12_autent;";

    $rsReceitas       = db_query($sSqlReceita);
    $iTotalLinhas = pg_num_rows($rsReceitas);
    for ($i = 0; $i < $iTotalLinhas; $i++) {

       db_inicio_transacao();

       $oDadosAutenticacao = db_utils::fieldsMemory($rsReceitas, $i);
       $lEstorno =  $oDadosAutenticacao->k12_estorn == 't' ? true : false;
       echo "processando {$oDadosAutenticacao->data} - AUT:{$oDadosAutenticacao->codautent} ID:{$oDadosAutenticacao->id}<br>";
       flush();
       $oAutenticacao    = new AutenticacaoArrecadacao(1,
                                                       null,
                                                       $oDadosAutenticacao->k12_conta
                                                      );
       $lReceitaContabil = $oAutenticacao->efetuarLancamentos($oDadosAutenticacao->data,
                                                              $oDadosAutenticacao->id,
                                                              $oDadosAutenticacao->codautent,
                                                              $oDadosAutenticacao->k12_conta,
                                                              $lEstorno
                                                             );

       if ($lReceitaContabil) {

         $oAutenticacao->efetuarLancamentos($oDadosAutenticacao->data,
                                            $oDadosAutenticacao->id,
                                            $oDadosAutenticacao->codautent,
                                            $oDadosAutenticacao->k12_conta,
                                            $lEstorno,
                                            true, true
                                           );

         $oAutenticacao->efetuarLancamentos($oDadosAutenticacao->data,
                                            $oDadosAutenticacao->id,
                                            $oDadosAutenticacao->codautent,
                                            $oDadosAutenticacao->k12_conta,
                                            $lEstorno,
                                            true
                                           );
        }

        $lReceitaExtra = $oAutenticacao->efetuarLancamentosReceitaExtra($lEstorno,
                                                                        $oDadosAutenticacao->data,
                                                                        $oDadosAutenticacao->codautent,
                                                                        $oDadosAutenticacao->id
                                                                       );

        if (!$lReceitaContabil && !$lReceitaExtra) {
           fputs($rsErros, "Arrecadacao {$oDadosAutenticacao->k12_numpre} - {$oDadosAutenticacao->data} - AUT:{$oDadosAutenticacao->codautent} ID:{$oDadosAutenticacao->id} não foi efetuado lançamento contábil.\n");
        }

       db_fim_transacao(false);

    }
    echo "Termino do  Reprocessamento da arrecadacao de Receita<br>";
    flush();
    $rstrigger = db_query("alter table conlancamval enable trigger all;");
    $rstrigger = db_query("alter table conlancamval enable trigger all;");
    db_fim_transacao(false);


    echo "Iniciando Reprocessando da baixa de banco<br>";
    flush();
   // db_inicio_transacao();
    $sSqlReceita = "select distinct k12_codcla
                     From corrente
                          inner join cornump on corrente.k12_data  = cornump.k12_data
                                            and corrente.k12_id    = cornump.k12_id
                                           and corrente.k12_autent = cornump.k12_autent
                          inner join corcla on corcla.k12_id       = corrente.k12_id
                                          and corcla.k12_data     = corrente.k12_data
                                          and corrente.k12_autent = corcla.k12_autent
                   where corrente.k12_data  between '{$sData}' and '{$sDataFinal}'
                     and corrente.k12_instit = ".db_getsession("DB_instit");

    $rsReceitas       = db_query($sSqlReceita);
    $iTotalLinhas = pg_num_rows($rsReceitas);
    for ($i = 0; $i < $iTotalLinhas; $i++) {

      db_inicio_transacao();
      $oDadosAutenticacao = db_utils::fieldsMemory($rsReceitas, $i);

      $oAutenticacao      = new LancamentoContabilAjusteBaixaBanco($oDadosAutenticacao->k12_codcla);
      $oAutenticacao->autenticar();

      db_fim_transacao(false);

    }
//    db_fim_transacao(false);
    echo "termino do  Reprocessando da baixa de banco<Br>";
    flush();


    echo "Iniciando Processamento das Planilhas de Arrecadacao<br>";
    flush();
    $sSqlReceita = "select  distinct k12_conta, corrente.k12_id as id,
                           corrente.k12_autent as codautent,
                           corrente.k12_data as data,
                           corrente.k12_autent,
                           corrente.k12_data ,
                           corrente.k12_id,
                           corrente.k12_estorn,
                           k82_seqpla,
                           k81_codpla,
         case when  k02_codigo is null then  corrente.k12_estorn
                                when substr(k02_estorc, 1, 1) = '9' and k12_estorn is true then false
                                when substr(k02_estorc, 1, 1) = '4' and k12_estorn is false then false

                            else true end as estorno
                     From corrente
                          inner join cornump on corrente.k12_data  = cornump.k12_data
                                            and corrente.k12_id    = cornump.k12_id
                                           and corrente.k12_autent = cornump.k12_autent
                          inner join corplacaixa on k82_id     = corrente.k12_id
                                                and k82_data   = corrente.k12_data
                                                and k82_autent = corrente.k12_autent
                   inner join placaixarec on k82_seqpla  = k81_seqpla
                   left  join taborc      on k02_codigo  = k81_receita and k02_anousu = 2014
                   inner join placaixa   on k80_codpla  = k81_codpla
                   where corrente.k12_data between '{$sData}' and '{$sDataFinal}'
                     and corrente.k12_instit = ".db_getsession("DB_instit")."
                  order by corrente.k12_data, corrente.k12_id,corrente.k12_autent;";

    $rsReceitas   = db_query($sSqlReceita);
    $iTotalLinhas = pg_num_rows($rsReceitas);

    for ($i = 0; $i < $iTotalLinhas; $i++) {

    db_inicio_transacao();
      $oDadosAutenticacao           = db_utils::fieldsMemory($rsReceitas, $i);
      $oDadosAutenticacao->estorno  = $oDadosAutenticacao->estorno == 't' ? true : false;
      echo "processando Planilha {$oDadosAutenticacao->k81_codpla} - {$oDadosAutenticacao->data} - AUT:{$oDadosAutenticacao->codautent} ID:{$oDadosAutenticacao->id}\n";
      flush();
      $oPlanilhaArrecadacao = new AutenticacaoPlanilha(new PlanilhaArrecadacao($oDadosAutenticacao->k81_codpla));

      $lReceita      = $oPlanilhaArrecadacao->executarLancamentoContabeis($oDadosAutenticacao->codautent,
                                                          $oDadosAutenticacao->estorno,
                                                          $oDadosAutenticacao
                                                         );
      $lReceitaExtra = $oPlanilhaArrecadacao->executarLancamentosReceitaExtraOrcamentaria($oDadosAutenticacao->codautent,
                                                                                 $oDadosAutenticacao->estorno,
                                                                                 $oDadosAutenticacao
                                                                                );




      if (!$lReceita && !$lReceitaExtra) {
        fputs($rsErros, "planilha {$oDadosAutenticacao->k81_codpla}  - {$oDadosAutenticacao->data} - AUT:{$oDadosAutenticacao->codautent} ID:{$oDadosAutenticacao->id} não foi efetuado lançamento contábil.\n");
      }
    db_fim_transacao(false);

    }

    echo "Fim do processamento das planilhas de arrecadacao <br>";
    if ($_POST["reprocessar_saldo_contabil"] == 's') {

      db_inicio_transacao();
      recriarConplanoExeSaldo();
      db_fim_transacao(false);
    }

    db_msgbox('Processamento Efetuado com sucesso');
  } catch (Exception $eErro) {

    $sMensagem =  $eErro->getMessage() . " | [ERRO] - " . pg_last_error();
    $sMsg = @str_replace("\n", "\\n", $sMensagem);
    db_msgbox($sMsg);
    db_fim_transacao(true);
  }
  unset($_SESSION["DB_desativar_account"]);
  foreach ($aTables as $sTabela) {

    $sSqlAjusteDesempenho = "alter table {$sTabela} set (autovacuum_enabled = true, toast.autovacuum_enabled = true)";
    $rsAjusteDesempenho   = db_query($sSqlAjusteDesempenho);
  }
}
$_SESSION["DB_desativar_account"] = false;
unset($_SESSION["DB_desativar_account"]);
?>
</div>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body style="margin-top: 25px; background-color: #cccccc" >
  <center>
  <div class="container">
    <form action="" method="post">
    <fieldset>
      <legend>
        <b>Reprocessamento dos lançamentos Receita do PCASP</b>
      </legend>
      <table>
         <tr>
            <td><b>Data para Processamento:</b></td>
            <td>
              <?php
               db_inputdata('sData', null, null. null, null,true, 'text', 1);
              ?>
              <b>à</b>
              <?php
               db_inputdata('sDataFinal', null, null. null, null,true, 'text', 1);
              ?>
            </td>
         </tr>

         <tr>
           <td>
             <b>Reprocessar Saldo do Exercício:</b>
           </td>
           <td>
             <select name='reprocessar_saldo_contabil' style="width: 100%">
               <option value='n' selected>Não</option>
               <option value='s'>Sim</option>
             </select>
           </td>
         </tr>

         <tr>
           <td>
             <b>Reprocessar Descontos Tesouraria:</b>
           </td>
           <td>
             <select name='reprocessar_descontos_tesouraria' style="width: 100%">
               <option value='n' selected>Não</option>
               <option value='s'>Sim</option>
             </select>
           </td>
         </tr>
      </table>
    </fieldset>
    <input type="submit" value='Processar' name='processar' onclick='return confirm("Confirma o processamento dos dados a partir data informada?")'>
  </form>
  </div>
  </center>
</body>
<?php
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));



function recriarConplanoExeSaldo() {

  $rsDeleteConplanoExe = db_query("delete from conplanoexesaldo");
  if (!$rsDeleteConplanoExe) {
    throw new Exception('Não foi possivel excluir conplanoexesaldo');
  }
  $rsCreateTableSaldoDebito = db_query("create table landeb as
                                         select c69_anousu,c69_debito,to_char(c69_data,'MM')::integer,
                                                sum(round(c69_valor,2)),0::float8
                                           from conlancamval
                                          group by c69_anousu,c69_debito,to_char(c69_data,'MM')::integer;");

  if (!$rsCreateTableSaldoDebito) {
    throw new Exception('Não foi possivel criar tabela temporaria para saldo a debito');
  }
  $rsCreateTableSaldoCredito = db_query("
                                        create table lancre as
                                        select c69_anousu,c69_credito,to_char(c69_data,'MM')::integer,0::float8,sum(round(c69_valor,2))
                                        from conlancamval
                                        group by c69_anousu,c69_credito,to_char(c69_data,'MM')::integer;"
                                       );

  if (!$rsCreateTableSaldoCredito) {
    throw new Exception('Não foi possivel criar tabela temporaria para saldo a credito');
  }
  $rsInsertDebito = db_query("insert into conplanoexesaldo select * from landeb");
  if (!$rsInsertDebito) {
    throw new Exception('Não foi possivel incluir saldo a debito');
  }
  $rsUpdateDebito = db_query("
                              update conplanoexesaldo
                              set c68_credito = lancre.sum
                              from lancre
                              where c68_anousu = lancre.c69_anousu
                              and c68_reduz = lancre.c69_credito
                              and c68_mes = lancre.to_char;"
                            );

  if (!$rsUpdateDebito) {
    throw new Exception('Não foi possivel atualizar saldo a debito');
  }
  $rsDeleteCredito = db_query("
                              delete from lancre
                              using conplanoexesaldo
                              where lancre.c69_anousu = conplanoexesaldo.c68_anousu
                              and conplanoexesaldo.c68_reduz = lancre.c69_credito
                              and conplanoexesaldo.c68_mes = lancre.to_char;"
                             );
  if (!$rsDeleteCredito) {
    throw new Exception('Não foi possivel excluir contas a credito');
  }

  $rsInsertCredito = db_query("insert into conplanoexesaldo select * from lancre");
  if (!$rsInsertCredito) {
    throw new Exception('Não foi possivel insert contas a credito');
  }
  $rsDropTemTables = db_query("drop table landeb;
                               drop table lancre;
                              ");

}

function reprocessarDescontoTesouraria ($sDataInicial, $sDataFinal) {

  $sSqlDatas = "select data::date from generate_series('{$sDataInicial}'::timestamp,
                                                        '{$sDataFinal}'::timestamp, '1 day') as data;";
  $rsDatas = db_query($sSqlDatas);
  if (!$rsDatas) {
    throw new Exception('Não foi possivel criar intervalo de Datas');
  }
  $aDatas = db_utils::getCollectionByRecord($rsDatas);
  foreach ($aDatas as $oData) {

    $sSqlCreateTabelaCornump = "create temp table w_copia_cornump as
                                select *
                                  from caixa.cornump
                                 where k12_data = '{$oData->data}'";
    $rsCreateTempTable = db_query($sSqlCreateTabelaCornump);
    if (!$rsCreateTempTable) {
      throw new Exception("Não foi possivel criar tabela com os dados da cornump do dia {$oData->data}");
    }

    $rsDeleteCornumpDesconto = db_query("delete from contabilidade.cornumpdesconto
                                         where k12_data = '{$oData->data}'"
                                       );
    if (!$rsDeleteCornumpDesconto) {
      throw new Exception("Não foi possivel delete com os dados da cornumpdesconto do dia {$oData->data}");
    }

     $rsDelete = db_query("delete from caixa.cornump
                           where k12_data = '{$oData->data}'"
                        );
     if (!$rsDelete) {
        throw new Exception("Não foi possivel delete com os dados da cornump do dia {$oData->data}");
     }

     $rsInsert = db_query("insert into caixa.cornump select * from w_copia_cornump;");
     if (!$rsInsert) {
       throw new Exception("Não foi possivel inserir dados da cornump do dia {$oData->data}");
     }
     $rsCreateTempTable = db_query("drop table w_copia_cornump");
   }
}
 ?>
</html>
