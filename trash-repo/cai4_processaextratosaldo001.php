<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
require("libs/db_utils.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clrotulo = new rotulocampo;
$clrotulo->label("k86_contabancaria");
$clrotulo->label("k86_data");
$clrotulo->label("db83_descricao");

$sqlerro  = false;
$erro_msg = "Processamento concluído com sucesso!"; 
if (isset($processar)) {
	
	if ($Conta == "") {
		$sqlerro  = true;
		$erro_msg = "Nenhuma conta informada";
	}
	
	$sDtSaldoFinal = $sDtSaldoFinal_ano."-".$sDtSaldoFinal_mes."-".$sDtSaldoFinal_dia;
	
  $sTableName    = "w_backup_extratosaldo_".date('Ymdhis');
  db_query("drop table {$sTableName}");

  db_inicio_transacao();

  $rsCriaTable = db_query("create table {$sTableName} as select * from extratosaldo");
  if (!$rsCriaTable) {
  	$sqlerro  = true;
  	$erro_msg = "Erro processando backup dos dados. Processamento cancelado";
  }

  if ($iSaldo != "") {
    $sSqlSeqSaldoAnterior = "select k97_sequencial 
                            from extratosaldo 
                           where k97_contabancaria = {$Conta} 
                             and k97_dtsaldofinal < '{$sDtSaldoFinal}' 
                           order by k97_dtsaldofinal desc limit 1";

    $rsSeqSaldoAnterior = db_query($sSqlSeqSaldoAnterior);                    
    if (pg_numrows($rsSeqSaldoAnterior) == 0) { 
    	$sqlerro = true;
      $erro_msg = "Erro atualizando o saldo anterior!\\nNão foi encontrado Extrato Saldo para data anterior!";         
    } else {
      $oSeqSaldoAnterior  = db_utils::fieldsMemory($rsSeqSaldoAnterior,0);
    
      $sSqlSaldoAnterior = "update extratosaldo set k97_saldofinal = {$iSaldo} where k97_sequencial = {$oSeqSaldoAnterior->k97_sequencial}";
      $rsSaldoAnterior   = db_query($sSqlSaldoAnterior);
      if (!$rsSaldoAnterior) {
        $sqlerro = true;
        $erro_msg = "Erro atualizando o saldo anterior";
      }
    }  
    
  }

  
  $sSqlContas = "select distinct k97_contabancaria from extratosaldo where k97_contabancaria = $Conta";
  $rsContas   = pg_query($sSqlContas);
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

    $rsSaldo     = pg_query($sSql);
    if (!$rsSaldo) {
    	$sqlerro = true;
    	$erro_msg = "Erro buscando registros da Extrato Saldo";
    }
    
    $iQtdNumRows = pg_num_rows($rsSaldo);

    for ($i=0;$i<$iQtdNumRows; $i++) {
    
      $oPesquisa = db_utils::fieldsMemory($rsSaldo,$i);

      $sSqlSaldoAnterior = "select round(k97_saldofinal,2) as saldoanterior 
                              from extratosaldo 
                             where k97_contabancaria = {$oPesquisa->k97_contabancaria} 
                               and k97_dtsaldofinal < '{$oPesquisa->k97_dtsaldofinal}' 
                             order by k97_dtsaldofinal desc limit 1";
      $rsSaldoAnterior = pg_query($sSqlSaldoAnterior);
      
      $oSaldoAnterior  = db_utils::fieldsMemory($rsSaldoAnterior,0);
    
      $s = "({$oSaldoAnterior->saldoanterior} + {$oPesquisa->k97_valorcredito} - {$oPesquisa->k97_valordebito} ) = ".($oSaldoAnterior->saldoanterior+$oPesquisa->k97_valorcredito-$oPesquisa->k97_valordebito);

      $sSqlUpdate = "update extratosaldo 
                        set k97_saldofinal = round( ({$oSaldoAnterior->saldoanterior} + {$oPesquisa->k97_valorcredito} - {$oPesquisa->k97_valordebito} ),2)
                      where k97_contabancaria = {$oPesquisa->k97_contabancaria} 
                        and k97_dtsaldofinal  = '{$oPesquisa->k97_dtsaldofinal}'";
      $rsUpdate = pg_query($sSqlUpdate);
      if(!$rsUpdate){
      	$sqlerro  = true;
      	$erro_msg = "Erro reprocessando saldo do extrato";
      }
    
    }

  }

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

<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellspacing="0" cellpadding="0">
 <tr> 
  <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center> <br><br><br>
     <form name="form1" method="post" action="">
      <center>
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
          <? db_inputdata('sDtSaldoFinal',@$k86_data_dia,@$k86_data_mes,@$k86_data_ano,true,'text',1,"") ?>
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
        <tr> 
         <td colspan="2" align="center" height="50">
           <input name="processar"  type="submit" value="Processar">
         </td>
        </tr> 
       </table>
      </center>
     </form>    
    </center>
  </td>
 </tr>
</table>
</body>

</html>

<? 
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>

<script>

function js_pesquisak86_contabancaria(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_contabancaria','func_contabancaria.php?funcao_js=parent.js_mostracontabancaria1|db83_sequencial|db83_descricao','Pesquisa',true,'20');
  }else{
     if(document.form1.k86_contabancaria.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_contabancaria','func_contabancaria.php?tp=1&pesquisa_chave='+document.form1.k86_contabancaria.value+'&funcao_js=parent.js_mostracontabancaria','Pesquisa',false);     
     }else{
       document.form1.db83_descricao.value = ''; 
     }
  }
}
function js_mostracontabancaria(erro,chave){
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