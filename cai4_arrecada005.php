<?
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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
?>
<html>
<head>
<title>Documento sem t&iacute;tulo</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">

<script>
function js_autenticar() {
  parent.js_divCarregando("Aguarde, executando baixa de banco...", "msgBox");
  document.form1.action = 'cai4_arrecada007.php?system=linux';
  document.form1.submit();
}

function js_calculacodcla(codcla){
  parent.recibos.document.location.href = 'cai4_arrecada006.php?codcla='+codcla;
}
function js_baixacaixa(){
   document.location.href = 'cai4_arrecada002.php';
   parent.recibos.document.location.href = 'cai4_arrecada004.php';
}
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC bgcolor="#AAB7D5" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onload="";>
<form name="form1" method="post" >
<?

//$result_conta = db_query("select c01_reduz,k13_descr as c01_descr,c01_estrut 
//	                 from saltes s
//	                      inner join saltesplan n on n.k13_conta = s.k13_conta 
//			 order by n.c01_estrut");
$sql_conta = "
	select k13_reduz as c01_reduz, 
	       k13_descr as c01_descr, 
	       c60_estrut as c01_estrut
	from   saltes
	inner join conplanoexe   on k13_reduz  = c62_reduz  and c62_anousu = " . db_getsession("DB_anousu") . "
	inner join conplanoreduz on c61_anousu = c62_anousu and c61_reduz  = c62_reduz and c61_instit = " . db_getsession("DB_instit") . "
	inner join conplano      on c60_anousu = c61_anousu and c60_codcon = c61_codcon ;";

//						select k13_reduz as c01_reduz,k13_descr as c01_descr,c60_estrut as c01_estrut 
//	          from  saltes 
//			      inner join conplanoexe   on k13_reduz = c62_reduz and c62_anousu = " . db_getsession("DB_anousu") . "
//			      inner join conplanoreduz on c61_anousu=".db_getsession("DB_anousu")." and   c62_reduz = c61_reduz and c61_instit = " . db_getsession("DB_instit") . "
//			      inner join conplano      on c62_reduz = c60_codcon and c62_anousu = c60_anousu <=== AQUI TAVA ERRADO
//	                 order by c60_estrut";
//die($sql_conta);									 
$result_conta = db_query($sql_conta); 
if(pg_numrows($result_conta) == 0){
  echo "<script>parent.alert('Sem Contas Cadastradas.');</script>";
  exit;
}

$iInstit = db_getsession("DB_instit");
$result = db_query("select codcla,k15_codbco,k15_codage,k00_conta,k13_descr
		           from discla
					    inner join disarq on disarq.codret = discla.codret 
					    inner join saltes on disarq.k00_conta = saltes.k13_conta 
				   where dtaute is null and discla.instit = {$iInstit}
	  			   order by codcla");
if(pg_numrows($result) == 0){
  echo "<script>parent.alert('Não Existe Classificacao para ser Autenticada.');js_baixacaixa();</script>";
  exit;
}
?>
  <table width="99%">
    <tr>
      <td align="left" valign="middle">&nbsp; </td>
      <td align="right" valign="middle">&nbsp;</td>
      <td align="right" valign="middle">&nbsp;</td>
    </tr>
    <tr> 
      <td width="69%" align="center" valign="middle"><b>Classificação:&nbsp;</b><select  onChange="js_calculacodcla(this.value);" name="codcla">
          <?
		for($i=0;$i<pg_numrows($result);$i++){
		  db_fieldsmemory($result,$i);
		  echo '<option value="'.$codcla.'">'.$codcla.'-'.$k15_codbco.'-'.$k15_codage.'-'.$k00_conta.'-'.$k13_descr.'</option>';
		}
		?>
        </select> </td>
      <td width="15%" align="right" valign="middle"><input name="autenticar" type="button" id="autenticar4" onClick="js_autenticar()" value="Autenticar"></td>
      <td width="16%" align="right" valign="middle"><input name="caixa"  type="button" id="caixa4"  onClick="js_baixacaixa();" value="Baixa Caixa"></td>
    </tr>
  </table>
</form>
</body>
</html>
<script>
js_calculacodcla(document.form1.codcla.value);
</script>