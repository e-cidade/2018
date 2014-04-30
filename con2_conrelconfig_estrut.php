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


require ("libs/db_stdlib.php");
require ("libs/db_conecta.php");
include ("libs/db_sessoes.php");
include ("libs/db_usuariosonline.php");
include ("dbforms/db_funcoes.php");
include ("libs/db_liborcamento.php");
include ("classes/db_conrelinfo_classe.php");
include ("classes/db_conrelvalor_classe.php");
include ("classes/db_orcparamrel_classe.php");
include ("classes/db_orcparamseq_classe.php");
include ("classes/db_orcparamelemento_classe.php");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_POST_VARS);

$clconrelinfo = new cl_conrelinfo;
$clconrelvalor = new cl_conrelvalor;
$clorcparamrel = new cl_orcparamrel;
$clorcparamseq = new cl_orcparamseq;
$clorcparamelemento = new cl_orcparamelemento;

$clrotulo = new rotulocampo;
$clrotulo->label('c83_codrel');
$clrotulo->label('o42_descrrel');

$db_opcao = 1;
$db_botao = true;

$res = $clorcparamrel->sql_record($clorcparamrel->sql_query($c83_codrel));
if ($clorcparamrel->numrows > 0) {
	db_fieldsmemory($res, 0);
}
if (isset ($atualizar) && $atualizar == "atualizar") {
	db_inicio_transacao();
	$erro = false;
	$msg = "";
	$instit = db_getsession("DB_instit");
	$clorcparamelemento->o44_instit = $instit;
	$clorcparamelemento->sql_record("select * from orcparamelemento 
                                   where o44_codparrel=$c83_codrel and 
				         o44_sequencia=$c69_codseq and 
					 o44_instit=$instit ");
	if ($clorcparamelemento->numrows > 0) {
		$clorcparamelemento->excluir(null, null, null,
		                             null,
					     "o44_codparrel=$c83_codrel and
					      o44_sequencia=$c69_codseq and
					      o44_instit=$instit");
		if ($clorcparamelemento->erro_status == 0) {
			$erro = true;
			$msg = $clorcparamelemento->erro_msg;
		}
	}
	$matriz = explode("#", $lista); //gera matriz com as chaves
	for ($i = 0; $i < sizeof($matriz); $i ++) {
		// o teste abaixo e necessario porque quando desmerca todos os itens na tela, o expode acima gera 1 vazio
		if ($matriz[$i] != "") {
			// db_msgbox($matriz[$i]);
			$clorcparamelemento->incluir(db_getsession("DB_anousu"), $c83_codrel, $c69_codseq, $matriz[$i], $instit);
			if ($clorcparamelemento->erro_status == 0) {
				$erro = true;
				$msg = $clorcparamelemento->erro_msg;
			}
		}
	}
	db_fim_transacao($erro);
	if ($erro == true) {
		db_msgbox($msg);
	}

}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>
function js_refresh(){
 obj = document.form1;
 location.href = 'con2_conrelparametros.php?c83_codrel='+obj.c83_codrel.value+'&c69_codseq='+obj.c69_codseq.value;
}  
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">

<form name="form1" method="post" action="" >
 <table  align="center" border=0>
 <tr><td align=left width=20%><? db_ancora(@$Lc83_codrel,"js_pesquisac60_codcla(true);",2);?></td>
     <td><? db_input('c83_codrel',5,$Ic83_codrel,true,'text',3,"")?>
         <? db_input('o42_descrrel',40,$Io42_descrrel,true,'text',3,"")?></td>
 </tr>
 <tr>
   <td>Linha/Parâmetro : </td>
   <td> <? 


$record = $clorcparamseq->sql_record($clorcparamseq->sql_query($c83_codrel, $sequen, "o69_codseq,o69_descr"));
$matriz = array ();
if ($clorcparamseq->numrows > 0) {
	for ($x = 0; $x < $clorcparamseq->numrows; $x ++) {
		db_fieldsmemory($record, $x);
		$matriz["$o69_codseq"] = $o69_descr;
	}
}
db_select("c69_codseq", $matriz, false, 1, "onchange='js_refresh();'");
?>
   </td> 

 </tr>
  <td colspan=2 align="left">
  <? 
include ("forms/db_frmorcparamseqrel.php");
?>
  </td>
 </tr>
 </table>
</form>
</body>
</html>
<?


echo "<script>
   
    //           js_refresh();  


          </script> ";
?>