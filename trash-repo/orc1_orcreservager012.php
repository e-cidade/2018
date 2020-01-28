<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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


require ("libs/db_stdlib.php");
require ("libs/db_conecta.php");
include ("libs/db_sessoes.php");
include ("libs/db_usuariosonline.php");
include ("classes/db_orcreservager_classe.php");
include ("classes/db_orcreserprev_classe.php");
include ("dbforms/db_funcoes.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$erro = false;
$oRotulo = new rotulocampo();
$oRotulo->label("o80_codres");
$clorcreserprev = new cl_orcreserprev;

if (isset ($atualiza)) {

	db_inicio_transacao();
	$erro = false;
	reset($HTTP_POST_VARS);

	for ($i = 0; $i < count($HTTP_POST_VARS); $i ++) {
		if (substr(key($HTTP_POST_VARS), 0, 13) == 'dotacao_dimi_') {
			if ($HTTP_POST_VARS[key($HTTP_POST_VARS)] > 0) {
				$mt = split("\_", key($HTTP_POST_VARS));

				$sql = " select o80_valor,o80_coddot
			               from orcreserva where o80_codres = ".$mt[2];
				$result = pg_query($sql);
				if ($result == false) {
					$erro = true;
					$msg_erro = "Erro ao alterar reserva automática (reduzir).";
					break;
				}
				$saldo_reserva = pg_result($result, 0, 0);
				$coddot = pg_result($result, 0, 1);
				if ($saldo_reserva > 0) {
					$sql = "update orcreserva set o80_valor = o80_valor - ".$HTTP_POST_VARS[key($HTTP_POST_VARS)]." where o80_codres = ".$mt[2];
					$res = pg_query($sql);
					if ($res == false) {
						$erro = true;
						break;
					}
				} else {
					$erro = true;
					$msg_erro = "Dotacao ($coddot) sem reserva de saldo para remanejar.(Reserva zerada).";
				}
			}
		}

		if (substr(key($HTTP_POST_VARS), 0, 13) == 'dotacao_soma_') {
			$mt = split("\_", key($HTTP_POST_VARS));
			if ($HTTP_POST_VARS[key($HTTP_POST_VARS)] > 0) {
				$sql = " select  substr(fc_dotacaosaldo,133,12)::float8 as atual_menos_reservado,o80_coddot
			                 from ( 
			                      select o80_coddot,fc_dotacaosaldo(".db_getsession("DB_anousu").",o80_coddot,4,'".date("Y-m-d", db_getsession("DB_datausu"))."','".db_getsession("DB_anousu")."-12-31')
			                      from orcreserva where o80_codres = ".$mt[2]."
			                      ) as x ";
				$result = pg_query($sql);
				if ($result == false) {
					$erro = true;
					$msg_erro = "Erro ao alterar reserva automática (reduzir).";
					break;
				}
				$saldo_atual = pg_result($result, 0, 0);
				$coddot = pg_result($result, 0, 1);
				if ($saldo_atual >= $HTTP_POST_VARS[key($HTTP_POST_VARS)]) {
					$sql = "update orcreserva set o80_valor = o80_valor + ".$HTTP_POST_VARS[key($HTTP_POST_VARS)]." where o80_codres = ".$mt[2];
					$res = pg_query($sql);
					if ($res == false) {
						$erro = true;
						$msg_erro = "Erro ao alterar reserva automática (somar).";
						break;
					}
				} else {
					$erro = true;
					$msg_erro = "Dotacao ($coddot) sem saldo para remanejar.";
				}
			}
		}
		next($HTTP_POST_VARS);
	}
	db_fim_transacao($erro);
}

$sql = "select fc_estruturaldotacao(".db_getsession("DB_anousu").",o58_coddot) as estrutural,
               o58_coddot, o80_valor, o80_codres, o58_codigo,o15_descr,o56_descr,
               substr(fc_dotacaosaldo,133,12)::float8 as atual_menos_reservado
        from (
          select fc_estruturaldotacao(".db_getsession("DB_anousu").",o58_coddot) as estrutural,
               o58_coddot, o80_valor, o80_codres,o58_codigo,o15_descr,o56_descr,
              fc_dotacaosaldo(".db_getsession("DB_anousu").",o58_coddot,4,'".date("Y-m-d", db_getsession("DB_datausu"))."','".db_getsession("DB_anousu")."-12-31')
         from orcreservager           
             inner join orcreserva on o84_codres = o80_codres
             inner join orcdotacao on o58_anousu = o80_anousu and o58_coddot = o80_coddot
             inner join orcelemento on o58_codele = o56_codele and o56_anousu = o58_anousu
             inner join orctiporec on o58_codigo = o15_codigo
         where o58_projativ = $ativid and o58_codigo = $recurso
         order by o58_orgao,o58_unidade,o58_funcao,o58_subfuncao,o58_programa,o58_projativ,o58_codigo,o56_elemento 
        ) as x";

$clorcreservager = new cl_orcreservager;
$result = $clorcreservager->sql_record($sql);
if ($clorcreservager->numrows == 0) {
	$msg_erro = "Não existem programação para esta atividade..";
	$erro = true;
}

$clrotulo = new rotulocampo;

$clrotulo->label("o58_coddot");
$clrotulo->label("o80_valor");
$clrotulo->label("o56_elemento");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>
function js_verifica_valores(){
  elem = document.form1;
  soma = 0;
  dimi = 0;
  for(i=0;i<elem.length;i++){
    if(elem[i].name.substr(0,13)=='dotacao_soma_')
      soma = soma + Number(elem[i].value);  
    if(elem[i].name.substr(0,13)=='dotacao_dimi_')
      dimi = dimi + Number(elem[i].value);  
  }
  if(soma != dimi)
    alert('Valores a serem remanejados não conferem \n Reduzir:'+dimi+' \nAumentar:'+soma+' \nDiferença:'+(dimi-soma));
  else
    document.form1.submit();
}

function js_verifica_valor_reduz(objeto,vlrreduz,objsoma){
  if(objsoma.value > 0 ){
  	alert('Náo poderá reduzir pois possui valor a somar.');
      objeto.value = '';
      objeto.focus();
  }else{
    if( objeto.value > vlrreduz ){
      alert('Valor a reduzir maior que valor liberado.');
      objeto.value = '';
      objeto.focus();
    }
  }
}
function js_verifica_valor_soma(objeto,objreduz){
  if(objreduz.value > 0 ){
  	alert('Náo poderá somar pois possui valor a reduzir.');
    objeto.value = '';
    objeto.focus();
  }else{
    elem = document.form1;
    soma = 0;
    dimi = 0;
    for(i=0;i<elem.length;i++){
      if(elem[i].name.substr(0,13)=='dotacao_soma_')
        soma = soma + Number(elem[i].value);  
      if(elem[i].name.substr(0,13)=='dotacao_dimi_')
        dimi = dimi + Number(elem[i].value);  
    }
    if(soma > dimi){
      alert('Valor a somar maior que a reduzir.');
      objeto.value = '';
      objeto.focus();
    }
  }
    
}

</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<form name='form1' method='post' >
<table width="100%" border="1" cellspacing="0" cellpadding="0">
  <?


if ($erro == false || isset ($atualiza)) {
	echo "<tr><td title='Estrutural da despesa' >";
	echo "Estrutural";
	echo "</td><td>";
	echo $Lo56_elemento;
	echo "</td><td>";
	echo $Lo58_coddot;
	echo "</td><td>";
	echo $Lo80_codres;
	echo "</td><td>";
	echo "Saldo Atual";
	echo "</td><td>";
	echo "Verba Bloqueada";
	echo "</td><td>";
	echo "Reduzir Verba";
	echo "</td><td>";
	echo "Aumentar Verba";
	echo "</td></tr>";
	$codigo = 0;
	for ($i = 0; $i < $clorcreservager->numrows; $i ++) {
		db_fieldsmemory($result, $i);
		if ($codigo != $o58_codigo) {
			$codigo = $o58_codigo;
			echo "<tr><td colspan=7>";
			echo "<strong>Recurso : $o58_codigo - $o15_descr</strong>";
			echo "</td></tr>";
		}
		echo "<tr><td title='Estrutural'>";
		echo $estrutural;
		echo "<td title='$o56_descr'>";
		echo substr($o56_descr,0,20);
		echo "</td><td align='right'>";
		echo "<a href='' onclick='js_JanelaAutomatica(\"orcdotacao\",\"$o58_coddot\",\"".db_getsession("DB_anousu")."\");return false;'>$o58_coddot</a>";
		echo "</td><td>";
		echo $Lo80_codres;
		echo "</td><td align='right'>";
		echo db_formatar($atual_menos_reservado, 'f');
		echo "</td><td align='right'>";
		echo db_formatar($o80_valor, 'f');
		echo "</td><td align='left'>";
		db_input("dotacao_dimi_".$o80_codres, 20, 0, true, 'text', 2, " onchange='js_verifica_valor_reduz(this,$o80_valor,dotacao_soma_$o80_codres)'");
		echo "</td><td align='left'>";
		db_input("dotacao_soma_".$o80_codres, 20, 0, true, 'text', 2, " onchange='js_verifica_valor_soma(this,dotacao_dimi_$o80_codres)'");
		echo "</td></tr>";
	}
}
?>
  <input type='hidden' name='atualiza' value='atualiza'>
</table>
</form>
</body>
</html>
<?


if ($erro == true)
	db_msgbox($msg_erro);
?>