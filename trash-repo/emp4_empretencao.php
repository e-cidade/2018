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

include ("classes/db_pagordemtiporec_classe.php");

$clpagordemtiporec = new cl_pagordemtiporec;

$clrotulo = new rotulocampo;
$clrotulo->label('c83_variavel');
$clrotulo->label('e53_valor');

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$op = 1;
$db_opcao = 1;
$db_botao = true;
$instit = db_getsession("DB_instit");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style type="text/css">
.table_header{
    border: 1px solid #cccccc;
    border-top-color: #999999;
    border-right-color: #999999;
    border-left-color: #999999;
    border-bottom-color: #999999;
    background-color: #999999;
    font-size: 10px;
}
.tr_tab{
  background-color:white;
  font-size: 8px;
  height : 8px;
}
</style>

<script>

function setValorNota(valor){	
	var vl = new Number(parseFloat(valor));
	document.form1.valor_nota.value=vl.valueOf().toFixed(2);
}



</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">

<form name="form1" method="post" action="" >


<table border=1 width=80%>
<tr>
     <td colspan=6><b>RETENÇÕES</b></td>
          
    </tr>
   <tr>
     <td colspan=6>
      <!-- lista possiveis deduções -->
      <table id="tb_ret" border=0 style="border:1px solid #999999" width=100%>
      <tr>
       <td colspan=3>&nbsp;</td>
       <td width=10% nowrap> <b>Valor da Nota</td>
       <td align=right width=10%><?db_input('valor_nota', 15, '', true, 'text',3, '','','','text-align:right') ?></td> 
      
      </tr>
      <tr bgcolor="#BDC6BD">
        <td> &nbsp; </td>
        <td><b> REGRA     </b></td>
        <td><b> DESCRIÇÃO </b></td>
        <td><b> ALÍQUOTA </b></td>
        <td align=center><b> VALOR</b></td>
      </tr>
      <?

 // cria uma lista com as receitas ja existentes nessa liquidação       
$retencoes = array ();
/*
if (isset($e50_codord) && $e50_codord!=""){
  $res = $clpagordemrec->sql_record($clpagordemrec->sql_query($e50_codord,null,"e52_receit,e52_valor"));
  if ($clpagordemrec->numrows>0){
for($x=0;$x<$clpagordemrec->numrows;$x++){
   db_fieldsmemory($res,$x);
       $retencoes[$e52_receit] = $e52_valor;
}
}  
} 
*/

$res = $clpagordemtiporec->sql_record($clpagordemtiporec->sql_query(null, "*", null, ""));
if ($clpagordemtiporec->numrows > 0) {
	$cont = 1;
	for ($x = 0; $x < $clpagordemtiporec->numrows; $x ++) {
		db_fieldsmemory($res, $x);

		$marca = false;
		if (array_key_exists($e59_codrec, $retencoes)) {
			$marca = true;
			$v = "tb_rec_valor_$cont";
			$$v = $retencoes[$e59_codrec];
		}
?>
              <tr id="ret_<?=$x?>" class="tr_tab">
                <td><input id="chk_<?=$cont?>" type=checkbox name=regra <?=($marca==true?"checked":""); ?>></td>
                <td><?=$e59_codrec ?></td>
                <td><?=$k02_drecei ?></td>
                <td align=right><?=$e59_aliquota ?></td>
                <td align=right><? db_input('tb_rec_valor_'.$cont, 15, $Ie53_valor, true, 'text',$op, '','','','text-align:right')?></td>     
	      </tr>
	    <?


		$cont ++;
	}
}
?>
      </table>
    </td>
   </tr>
</table>



</form>
</body>
</html>
<script>
setValorNota(150.45);
</script>