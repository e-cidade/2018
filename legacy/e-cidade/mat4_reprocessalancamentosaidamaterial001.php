<?php
/*
 *     E-cidade Software Público para Gestão Municipal                
 *  Copyright (C) 2014  DBseller Serviços de Informática             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa é software livre; você pode redistribuí-lo e/ou     
 *  modificá-lo sob os termos da Licença Pública Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versão 2 da      
 *  Licença como (a seu critério) qualquer versão mais nova.          
 *                                                                    
 *  Este programa e distribuído na expectativa de ser útil, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implícita de              
 *  COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM           
 *  PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Você deve ter recebido uma cópia da Licença Pública Geral GNU     
 *  junto com este programa; se não, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Cópia da licença no diretório licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

require_once 'libs/db_stdlib.php';
require_once 'libs/db_utils.php';
require_once 'libs/db_app.utils.php';
require_once 'libs/db_conecta.php';
require_once 'libs/db_sessoes.php';
require_once 'dbforms/db_funcoes.php';
require_once 'std/db_stdClass.php';
require_once 'dbforms/db_funcoes.php';
require_once 'std/DBDate.php';
require_once 'libs/db_liborcamento.php';
require_once 'model/contabilidade/planoconta/ContaPlano.model.php';
require_once 'model/contabilidade/planoconta/ContaOrcamento.model.php';
require_once 'model/contabilidade/planoconta/ClassificacaoConta.model.php';
require_once 'model/contabilidade/planoconta/SistemaConta.model.php';
require_once 'model/contabilidade/planoconta/SubSistemaConta.model.php';
require_once 'classes/materialestoque.model.php';
require_once 'classes/requisicaoMaterial.model.php';
require_once 'libs/db_libpostgres.php';

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

if (!USE_PCASP) {

  echo "<h1> Cliente nao utiliza pcasp</h1>";
  exit;
}

$sCaminhoArquivoLog = "tmp/log_alteracao_lancamento_movimentacao_estoque.txt";

$aTables = array("c70_codlan"     => "conlancam",
                 "c69_codlan"     => "conlancamval",
                 "c76_codlan"     => "conlancamcgm",
                 "c72_codlan"     => "conlancamcompl",
                 "c71_codlan"     => "conlancamdoc",
                 "c103_conlancam" => "conlancammatestoqueinimei");


if (isset($_POST["processar"])) {


  if (!isset($_POST["sData"]) || $_POST["sData"] == "") {

    db_msgbox("Preencha a data inicial");
    exit;
  }

  if (!isset($_POST["sDataFinal"]) || $_POST["sDataFinal"] == "") {

    db_msgbox("Preencha a data final");
    exit;
  }

  $sDataInicialTabela = str_replace('/', '_', $_POST["sData"]);
  $sDataFinalTabela   = str_replace('/', '_', $_POST["sDataFinal"]);
  $sPeriodoTabela     = "{$sDataInicialTabela}_{$sDataFinalTabela}";
  if (PostgreSQLUtils::isTableExists("material_conlancam_{$sPeriodoTabela}")) {

    echo "<h1>Processamento para o período {$sDataInicialTabela} a {$sDataFinalTabela} já executado. Limpe as tabelas de backup.</h1>";
    exit;
  }
  $oData   = new DBDate($_POST["sData"]);
  $sData   = $oData->convertTo(DBDate::DATA_EN);

  $oDataFinal = new DBDate($_POST["sDataFinal"]);
  $sDataFinal = $oDataFinal->convertTo(DBDate::DATA_EN);
  $rstrigger = db_query("alter table conlancamval disable trigger all;");

  $oInstit = db_stdClass::getDadosInstit();

    $rsErros = fopen($sCaminhoArquivoLog, "w");
    $sSqlMovimentacaoEstoque = "SELECT m80_codigo, m80_data,
                                       m70_codmatmater,
                                       m81_codtipo,
                                       sum(m89_valorfinanceiro) as valor,
                                       m80_obs,
                                       m82_codigo
                                  FROM matestoqueini
                                       inner join db_depart        on m80_coddepto = coddepto
                                       inner JOIN matestoqueinimei ON m80_codigo = m82_matestoqueini
                                       INNER JOIN matestoqueitem ON m82_matestoqueitem = m71_codlanc
                                       INNER JOIN matestoque ON m71_codmatestoque = m70_codigo
                                       INNER JOIN matestoquetipo ON m81_codtipo = m80_codtipo
                                       INNER JOIN matestoqueinimeipm ON m89_matestoqueinimei = m82_codigo
                                 WHERE m80_data between '{$sData}' and '{$sDataFinal}'
                                 and m80_codtipo in (1, 3, 2, 4, 5, 17, 18)
                                 and instit = {$oInstit->codigo}

                                 GROUP BY m80_codigo,
                                          m70_codmatmater,
                                          m81_codtipo,
                                          m80_data,
                                          m80_obs,
                                          m82_codigo
                                 order by m80_codigo,m80_data";
    $rsBuscaMovimentacaoEstoque = db_query($sSqlMovimentacaoEstoque);
    $iTotalMovimentacoes        = pg_num_rows($rsBuscaMovimentacaoEstoque);
    if ($iTotalMovimentacoes == 0) {
      throw new Exception("Nenhuma movimentação encontrada no período informado.");
    }

    $_SESSION["DB_desativar_account"] = true;
    foreach ($aTables as $sTabela) {

      $sSqlAjusteDesempenho = "alter table {$sTabela} set (autovacuum_enabled = false, toast.autovacuum_enabled = false)";
      $rsAjusteDesempenho   = db_query($sSqlAjusteDesempenho);
    }
    criarBackupLancamentos($aTables, $sData, $sDataFinal, $sPeriodoTabela, $oInstit->codigo);
    deletarLancamentos($sData, $sDataFinal, $oInstit->codigo);
    for ($i = 0; $i < $iTotalMovimentacoes; $i++) {

      $oDados = db_utils::fieldsMemory($rsBuscaMovimentacaoEstoque, $i);
      if ($oDados->valor < 0.01) {
        continue;
      }
      db_inicio_transacao();

      /**
       * 1 - Deletar lancamentos
       * 2 - Descobrir o tipo da transacao(atendimento, saida manual, entrada) e instanciar classe certa
       */

      $oMaterial = new materialEstoque($oDados->m70_codmatmater);
      switch ($oDados->m81_codtipo) {

        case 1:

          try {

            $oDadosEntrada                       = new stdClass();
            $oDadosEntrada->iMovimentoEstoque    = $oDados->m82_codigo;
            $oDadosEntrada->sObservacaoHistorico = $oDados->m80_obs;
            $oDadosEntrada->nValorLancamento     = $oDados->valor;
            $oDadosEntrada->iContaPCASP          = $oMaterial->getGrupo()->getConta();
            $oDadosEntrada->iCodigoMaterial      = $oDados->m70_codmatmater;

            $oAlmoxarifado = new Almoxarifado(db_getsession('DB_coddepto'));
            $oAlmoxarifado->implantacaoEstoque($oDadosEntrada, $oDados->m80_data);

          } catch (Exception $eErro) {

            $sMsg = "Material {$oDados->m70_codmatmater}: Matestoqueini: {$oDados->m82_codigo} - Cod Tipo: {$oDados->m81_codtipo} - Erro {$eErro->getMessage()}";
            fputs($rsErros, $sMsg."\n");
          }
        break;
        case 3:

          try {

            $oDadosEntrada                       = new stdClass();
            $oDadosEntrada->iMovimentoEstoque    = $oDados->m82_codigo;
            $oDadosEntrada->sObservacaoHistorico = $oDados->m80_obs;
            $oDadosEntrada->nValorLancamento     = $oDados->valor;
            $oDadosEntrada->iContaPCASP          = $oMaterial->getGrupo()->getConta();
            $oDadosEntrada->iCodigoMaterial      = $oMaterial->getcodMater();

            $oAlmoxarifado = new Almoxarifado(db_getsession('DB_coddepto'));
            $oAlmoxarifado->entradaManual($oDadosEntrada, $oDados->m80_data);

          } catch (Exception $eErro) {

            $sMsg = "Material {$oDados->m70_codmatmater}: Matestoqueini: {$oDados->m82_codigo} - Cod Tipo: {$oDados->m81_codtipo} - Erro {$eErro->getMessage()}";
            fputs($rsErros, $sMsg."\n");
          }
          break;

        case 2:
        case 4:
        case 5:

          try {
            $oMaterial->processarLancamento($oDados->m82_codigo,
                                            $oDados->valor,
                                            $oDados->m80_obs,
                                            $oDados->m80_data
                                           );
          } catch (Exception $eErro) {

            $sMsg = "Material {$oDados->m70_codmatmater}: Matestoqueini: {$oDados->m82_codigo} - Cod Tipo: {$oDados->m81_codtipo} - Erro {$eErro->getMessage()}";
            fputs($rsErros, $sMsg."\n");
          }

          break;

        case 17:

          try {
            $sSqlBuscaRequisicao = "select m41_codmatrequi
                                      from matestoqueinimeiari
                                           inner join atendrequiitem on m49_codatendrequiitem = m43_codigo
                                           inner join matrequiitem on m41_codigo = m43_codmatrequiitem
                                     where m49_codmatestoqueinimei = {$oDados->m82_codigo}";
            $rsBuscaRequisicao   = db_query($sSqlBuscaRequisicao);
            if (!$rsBuscaRequisicao || pg_num_rows($rsBuscaRequisicao) == 0) {
              throw new Exception("Não foi possível localizar a requisição {$oDados->m82_codigo} do material para efetuar o lancamento de atendimento.");
            }
            $oRequisicaoMaterial = new RequisicaoMaterial(db_utils::fieldsMemory($rsBuscaRequisicao, 0)->m41_codmatrequi);
            $oRequisicaoMaterial->processarLancamento($oMaterial, $oDados->m82_codigo, $oDados->valor, $oDados->m80_data);

          } catch (Exception $eErro) {
            $sMsg = "Material {$oDados->m70_codmatmater}: Matestoqueini: {$oDados->m82_codigo} - Cod Tipo: {$oDados->m81_codtipo} - Erro {$eErro->getMessage()}";
            fputs($rsErros, $sMsg."\n");
          }
          break;

        case 18:

          try {
            $sSqlBuscaRequisicao = "select m41_codmatrequi
                                      from matestoqueinimeimdi
                                           inner join matestoquedevitem on m50_codmatestoquedevitem = m46_codigo
                                           inner join matrequiitem      on m46_codmatrequiitem      = m41_codigo
                                     where m50_codmatestoqueinimei = {$oDados->m82_codigo}";
            $rsBuscaRequisicao   = db_query($sSqlBuscaRequisicao);
            if (!$rsBuscaRequisicao || pg_num_rows($rsBuscaRequisicao) == 0) {
              throw new Exception("Não foi possível localizar a requisição {$oDados->m82_codigo} do material para efetuar o lancamento de devolução.");
            }
            $oRequisicaoMaterial = new RequisicaoMaterial(db_utils::fieldsMemory($rsBuscaRequisicao, 0)->m41_codmatrequi);
            $oRequisicaoMaterial->estornarLancamento($oMaterial, $oDados->m82_codigo, $oDados->valor, $oDados->m80_data);

          } catch (Exception $eErro) {

            $sMsg = "Material {$oDados->m70_codmatmater}: Matestoqueini: {$oDados->m82_codigo} - Cod Tipo: {$oDados->m81_codtipo} - Erro {$eErro->getMessage()}";
            fputs($rsErros, $sMsg."\n");
          }

          break;
      }
      
      db_fim_transacao(false);
    }
    
    $rstrigger = db_query("alter table conlancamval enable trigger all;");
    
    db_inicio_transacao();
    recriarConplanoExeSaldo();
    db_fim_transacao(false);
    db_msgbox('Processamento Efetuado com sucesso');
//   } catch (Exception $eErro) {

//     $sMsg = @str_replace("\n", "\\n", $eErro->getMessage());
//     db_msgbox($sMsg);
//     db_fim_transacao(true);
//   }
  unset($_SESSION["DB_desativar_account"]);
  foreach ($aTables as $sTabela) {

    $sSqlAjusteDesempenho = "alter table {$sTabela} set (autovacuum_enabled = true, toast.autovacuum_enabled = true)";
    $rsAjusteDesempenho   = db_query($sSqlAjusteDesempenho);
  }
}
$_SESSION["DB_desativar_account"] = false;
unset($_SESSION["DB_desativar_account"]);
?>
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
  <div style="display: table">
    <form action="" method="post">
    <fieldset>
      <legend>
        <b>Reprocessamento dos lançamentos dos Materiais</b>
      </legend>
      <table>
         <tr>
            <td><b>Data para inicio do processamento:</b></td>
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
      </table>
    </fieldset>
    <p align="center">
      <input type="submit" value='Processar' name='processar' onclick='return confirm("Confirma o processamento dos dados a partir data informada?")'>
    </p>

    <?php
      if (isset($_POST["processar"])) {

        echo "<a href='{$sCaminhoArquivoLog}' title='Download do arquivo de log'>Download do Arquivo {$sCaminhoArquivoLog}</a>";
      }
    ?>
  </form>
  </div>
  </center>
</body>
<?php
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
 ?>
</html>

<?php

  function criarBackupLancamentos($aTables, $sData, $sDataFinal, $sPeriodoTabela, $sInstituicao) {

    $sSqlMovimentacaoEstoque = "SELECT distinct c103_conlancam
                                  FROM matestoqueini
                                       inner join db_depart                 on m80_coddepto          = coddepto
                                       inner join matestoqueinimei          on m82_matestoqueini     = m80_codigo
                                       inner join conlancammatestoqueinimei on c103_matestoqueinimei = m82_codigo
                                 WHERE m80_data between '{$sData}' and '{$sDataFinal}'
                                   and instit = {$sInstituicao}
                                   and m80_codtipo in (1, 3, 2, 4, 5, 17, 18) ";
    foreach ($aTables as $sCampo => $sNomeTabela) {

      if ( !PostgreSQLUtils::isTableExists("material_{$sNomeTabela}_{$sPeriodoTabela}_instit{$sInstituicao}") ) {

        $rsCriarBackup = db_query("create table material_{$sNomeTabela}_{$sPeriodoTabela}_instit{$sInstituicao} as select *
                                                                             from {$sNomeTabela}
                                                                            where {$sCampo} in({$sSqlMovimentacaoEstoque})");
      } else {

        $rsCriarBackup = db_query("insert into material_{$sNomeTabela}_{$sPeriodoTabela}_instit{$sInstituicao} select *
                                                                         from {$sNomeTabela}
                                                                        where {$sCampo} in({$sSqlMovimentacaoEstoque})");
      }
      if (!$rsCriarBackup) {
        throw new Exception("Não foi possível criar/inserir na tabela [{$sNomeTabela}_{$sPeriodoTabela}_instit{$sInstituicao}] de backup para o lançamento [{$iCodigoLancamento}].");
      }
    }
    return true;
  }

  function deletarLancamentos($sData, $sDataFinal, $sInstituicao) {

    $sSqlMovimentacaoEstoque = "SELECT distinct c103_conlancam
                                  FROM matestoqueini
                                       inner join db_depart                 on m80_coddepto          = coddepto
                                       inner join matestoqueinimei          on m82_matestoqueini     = m80_codigo
                                       inner join conlancammatestoqueinimei on c103_matestoqueinimei = m82_codigo
                                  WHERE m80_data between '{$sData}' and '{$sDataFinal}'
                                    and instit = {$sInstituicao}
                                    and m80_codtipo in (1, 3, 2, 4, 5, 17, 18) ";
    db_query("drop table if exists w_conlancam");
    $rsCriaTabelaLancamento = db_query("create table w_conlancam as select * from conlancam where c70_codlan in({$sSqlMovimentacaoEstoque})");
    if (!$rsCriaTabelaLancamento) {
      throw new Exception('Não foi possivel criar a tabela com os lançamentos contábeis');
    }

    $rsDeleteConlancamCgm = db_query("delete from conlancamcgm  where c76_codlan in (select c70_codlan from w_conlancam)");
    if (!$rsDeleteConlancamCgm) {
      throw new Exception('Não foi possivel excluir dados da tabela conlancamcgm');
    }
    $rsDeleteConlancamGrupo = db_query("delete from conlancamcorgrupocorrente
                                       where c23_conlancam in (select c70_codlan from w_conlancam)");
    if (!$rsDeleteConlancamGrupo) {
      throw new Exception('Não foi possivel excluir dados da tabela conlancamGrupo');
    }
    $rsDeleteConlancamCorrente = db_query("delete from conlancamcorrente
                                          where c86_conlancam in (select c70_codlan from w_conlancam)");
    if (!$rsDeleteConlancamCorrente) {
      throw new Exception("Não foi possivel excluir dados da tabela conlancamcorrente\n".pg_last_error());
    }

    $rsDeleteConlancamRec = db_query("delete from conlancamrec
                                     where c74_codlan in (select c70_codlan from w_conlancam)");
    if (!$rsDeleteConlancamRec)  {
      throw new Exception('Não foi possivel excluir dados da tabela conlancamrec');
    }

    $rsDeleteConlancamCompl = db_query("delete from conlancamcompl
                                       where c72_codlan in (select c70_codlan from w_conlancam)");
    if (!$rsDeleteConlancamCompl)  {
      throw new Exception('Não foi possivel excluir dados da tabela conlancamcompl');
    }

    $rsDeleteConlancamPag = db_query("delete from conlancampag
                                     where c82_codlan in (select c70_codlan from w_conlancam)");
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
                                      where c08_codlan in (select c70_codlan from w_conlancam)");
    if (!$rsDeleteConlancamCP)  {
      throw new Exception('Não foi possivel excluir dados da tabela conlancamconcarpeculiar');
    }

    $rsDeleteConlancamMatEstoqueInimei = db_query("delete from conlancammatestoqueinimei
                                               where c103_conlancam in (select c70_codlan from w_conlancam)");
    if (!$rsDeleteConlancamMatEstoqueInimei)  {
      throw new Exception('Não foi possivel excluir dados da tabela conlancammatestoqueinimei');
    }

    $rsDeleteConlancam = db_query("delete from conlancam where c70_codlan in (select c70_codlan from w_conlancam)");
    if (!$rsDeleteConlancam)  {
      throw new Exception('Não foi possivel excluir dados da tabela conlancam');
    }

    return true;
  }
  
  
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

?>