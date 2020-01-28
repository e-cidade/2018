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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("dbforms/db_funcoes.php"));
parse_str($_SERVER["QUERY_STRING"]);
db_postmemory($_POST);

$clrotulo = new rotulocampo;
$clrotulo->label("k86_contabancaria");
$clrotulo->label("k86_data");
$clrotulo->label("db83_descricao");

$sqlerro  = false;
$erro_msg = "Processamento concluído com sucesso!";
if (isset($processar)) {

  db_inicio_transacao();
  try {

    if ($Conta == "") {
      throw new Exception("Nenhuma conta informada.");
    }

    $sDtSaldoFinal = $sDtSaldoFinal_ano."-".$sDtSaldoFinal_mes."-".$sDtSaldoFinal_dia;

    $sTableName    = "w_backup_extratosaldo_".date('Ymdhis');
    db_query("drop table if exists {$sTableName}");

    $rsCriaTable = db_query("create table {$sTableName} as select * from extratosaldo");
    if (!$rsCriaTable) {
      throw new Exception("Erro processando backup dos dados. Processamento cancelado");
    }

    if ( !empty($iSaldo) ) {

      $sSqlSeqSaldoAnterior = "select k97_sequencial
                                 from extratosaldo
                                where k97_contabancaria = {$Conta}
                                  and k97_dtsaldofinal < '{$sDtSaldoFinal}'
                                order by k97_dtsaldofinal desc limit 1";

      $rsSeqSaldoAnterior = db_query($sSqlSeqSaldoAnterior);
      if (pg_numrows($rsSeqSaldoAnterior) == 0) {
        throw new Exception("Erro atualizando o saldo anterior!\\nNão foi encontrado Extrato Saldo para data anterior!");
      }

      $oSeqSaldoAnterior  = db_utils::fieldsMemory($rsSeqSaldoAnterior,0);
      $sSqlSaldoAnterior = "update extratosaldo set k97_saldofinal = {$iSaldo} where k97_sequencial = {$oSeqSaldoAnterior->k97_sequencial}";
      $rsSaldoAnterior   = db_query($sSqlSaldoAnterior);
      if (!$rsSaldoAnterior) {
        throw new Exception("Erro atualizando o saldo anterior");
      }

    }

    $sSqlContas = "select distinct k97_contabancaria from extratosaldo where k97_contabancaria = {$Conta}";
    $rsContas   = db_query($sSqlContas);
    if (!$rsContas) {
      throw new Exception("Não foi possível buscar o saldo do extrato da conta bancária.");
    }
    $iNumRowsContas = pg_num_rows($rsContas);

    for ($iContas = 0 ; $iContas < $iNumRowsContas; $iContas++) {

      $oConta = db_utils::fieldsMemory($rsContas,$iContas);

      $sSql  = " select k97_contabancaria, ";
      $sSql .= "        k97_dtsaldofinal, ";
      $sSql .= "        round(k97_valorcredito,2) as k97_valorcredito, ";
      $sSql .= "        round(k97_valordebito,2) as k97_valordebito ";
      $sSql .= "   from extratosaldo  ";
      $sSql .= "  where k97_contabancaria = {$oConta->k97_contabancaria}  ";
      if (!empty($sDtSaldoFinal)) {
        $sSql .= "    and k97_dtsaldofinal >= '{$sDtSaldoFinal}' ";
      }
      $sSql .= "  order by k97_contabancaria, ";
      $sSql .= "           k97_dtsaldofinal";

      $rsSaldo     = db_query($sSql);
      if (!$rsSaldo) {
        throw new Exception("Erro buscando registros da Extrato Saldo");
      }

      $iQtdNumRows = pg_num_rows($rsSaldo);
      for ($i = 0; $i < $iQtdNumRows; $i++) {

        $oPesquisa = db_utils::fieldsMemory($rsSaldo,$i);

        $sSqlSaldoAnterior = "select round(k97_saldofinal,2) as saldoanterior
                              from extratosaldo
                             where k97_contabancaria = {$oPesquisa->k97_contabancaria}
                               and k97_dtsaldofinal < '{$oPesquisa->k97_dtsaldofinal}'
                             order by k97_dtsaldofinal desc limit 1";
        $rsSaldoAnterior = db_query($sSqlSaldoAnterior);
        if (!$rsSaldoAnterior) {
          throw new Exception("Não foi possível buscar o saldo anterior da conta bancária.");
        }

        $oSaldoAnterior  = db_utils::fieldsMemory($rsSaldoAnterior,0);

        $sSqlUpdate = "update extratosaldo
                        set k97_saldofinal = round( ({$oSaldoAnterior->saldoanterior} + {$oPesquisa->k97_valorcredito} - {$oPesquisa->k97_valordebito} ),2)
                      where k97_contabancaria = {$oPesquisa->k97_contabancaria}
                        and k97_dtsaldofinal  = '{$oPesquisa->k97_dtsaldofinal}'";
        $rsUpdate = db_query($sSqlUpdate);
        if(!$rsUpdate){
          throw new Exception("Erro reprocessando saldo do extrato");
        }

      }
    }

    db_fim_transacao(false);

  } catch (Exception $eErro) {


    db_fim_transacao(true);
    $sqlerro  = true;
    $erro_msg = $eErro->getMessage();
  }

  $sqlerro = true;
  db_fim_transacao($sqlerro);
  db_msgbox($erro_msg);
}
?>
<html>

<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>

<body >

<form name="form1" method="post" action="">
  <div class="container">
    <fieldset>
      <legend class="bold">Processar Saldo do Extrato</legend>

      <table border="0">
        <tr>
          <td nowrap title="<?=@$Tk86_contabancaria?>">
            <? db_ancora(@$Lk86_contabancaria,"js_pesquisak86_contabancaria(true);",1); ?>
          </td>
          <td nowrap>
            <? db_input('Conta',10,$Ik86_contabancaria,true,'text',1," onchange='js_pesquisak86_contabancaria(false);'") ?>
            <? db_input('db83_descricao',50,$Idb83_descricao,true,'text',3,'') ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Tk86_data?>">
            <?=@$Lk86_data?>
          </td>
          <td>
            <?php
            $k86_data_dia = "";
            $k86_data_mes = "";
            $k86_data_ano= "";
            db_inputdata('sDtSaldoFinal',@$k86_data_dia,@$k86_data_mes,@$k86_data_ano,true,'text',1,"");
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap>
            <strong>Saldo Anterior: </strong>
          </td>
          <td>
            <? db_input('iSaldo',10,4,true,'text',1) ?>
          </td>
        </tr>
      </table>

    </fieldset>
    <p><input name="processar"  type="submit" value="Processar" /></p>
  </div>
</form>

</body>

</html>

<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>

<script>

  function js_pesquisak86_contabancaria(mostra){
    if(mostra==true){
      js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_contabancaria','func_contabancaria.php?funcao_js=parent.js_mostracontabancaria1|db83_sequencial|db83_descricao','Pesquisa',true,'20');
    }else{
      if(document.form1.Conta.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_contabancaria','func_contabancaria.php?lImplantacao=1&tp=1&pesquisa_chave='+document.form1.Conta.value+'&funcao_js=parent.js_mostracontabancaria','Pesquisa',false);
      }else{
        document.form1.db83_descricao.value = '';
      }
    }
  }
  function js_mostracontabancaria(chave,erro){

    document.form1.db83_descricao.value = chave;
    if(erro==true){
      document.form1.Conta.focus();
      document.form1.Conta.value = '';
    }
  }
  function js_mostracontabancaria1(chave1,chave2){
    document.form1.Conta.value = chave1;
    document.form1.db83_descricao.value = chave2;
    db_iframe_contabancaria.hide();
  }
</script>