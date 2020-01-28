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
/**
* esta função é usada para configurar o valor maximo das retenções 
*
*/
function setValorNota(valor){	
	var vl = new Number(parseFloat(valor));
	document.form1.valor_nota.value=vl.valueOf().toFixed(2);
	js_calculaRetencao();
}
/**
* retencoes() devolve uma lista separada por ´underline´
* ret_:codreceita_:aliquota_:valor
* exemplo:   
*    ret_3_1.2_5.40     ( receita 3, aliquota 1.2, valor $ 5.40)
*    ret_8_0.75_5.80    ( receita 8, aliquota 0.75, valor $ 5.80)    
*
*   para pegar o conteudo selecionado use como exemplo a função teste() logo abaixo 
*/
function retencoes(){
	var str_lista='';
	var sep = '';	
    obj =  document.form1;
	qtd = obj.length;
	//totalRetencao=0;
	for(linha=0;linha < qtd; linha++){
	    if (obj[linha].type=='checkbox'){	           
	          objeto = obj[linha];
		      if (objeto.checked==true){		      
	   	           id = objeto.id; // pega o nome do objeto.
	   	           _receita  = 'receita_'+id;
	   	           _aliquota = 'aliquota_'+id; // captura o objeto aliquota
   	    	       _valor     = 'valor_'+id;   // captura o objeto valor
   	    	       receita = eval('document.form1.'+_receita+'.value');
   	    	       aliquota = eval('document.form1.'+_aliquota+'.value');
   	    	       valor     = eval('document.form1.'+_valor+'.value');
	   	           
   	    	       // alert(receita+' > '+ aliquota +'>'+valor);
   	    	       str_lista += sep+'ret_'+receita+'_'+aliquota+'_'+valor; 
   	    	       // totalRetencao = totalRetencao + parseFloat(valor.value) ;
   	    	       sep='|';   	    	       
    	      }
	    }
    }   
    return str_lista;
    // alert(str_lista);
}
function teste(){
   var lista = retencoes(); // invora a funcao retencoes e pega o retorno
   		  						     // retorno:  ret_:codreceita_:aliquota_:valor separados por | pipe
   alert(lista);   
}
//  inicio - funções específicas desta aba 
/**
* pega todos os objetos checkbox marcados e calcula valor da retenção
* os objetos desmarcados serão zerados
*/
function js_calculaRetencao(){
	valor_nota = document.form1.valor_nota.value;
	obj =  document.form1;
	qtd = obj.length;
	for(linha=0;linha < qtd; linha++){
	    if (obj[linha].type=='checkbox'){	           
	          objeto = obj[linha];
		      if (objeto.checked==true){
	   	           id = objeto.id; // peta o nome do objeto.
	   	           _aliquota = 'aliquota_'+id; // captura o objeto aliquota
   	    	       _valor = 'valor_'+id;   // captura o objeto valor
   	    	       aliquota = eval('document.form1.'+_aliquota);
   	    	       valor     = eval('document.form1.'+_valor);
   	    	       valor.value = (valor_nota * aliquota.value / 100).toFixed(2);  	
    	      }else {
  	    	       id = objeto.id;
   	    	       obj_valor = 'valor_'+id;  
   	    	       objeto_nota = eval('document.form1.'+obj_valor);
   	    	       objeto_nota.value = '0.00';  	   	
    	      }
	    }
    }
}
/**
* verifica totas as retenções e ferifica se não passou do total 
*/
function js_testaRetencao(){
	valor_nota = document.form1.valor_nota.value;
	obj =  document.form1;
	qtd = obj.length;
	totalRetencao=0;
	for(linha=0;linha < qtd; linha++){
	    if (obj[linha].type=='checkbox'){	           
	          objeto = obj[linha];
		      if (objeto.checked==true){
	   	           id = objeto.id; // peta o nome do objeto.	   	           
   	    	       _valor = 'valor_'+id;   // captura o objeto valor
   	    	       valor     = eval('document.form1.'+_valor);
   	    	       totalRetencao = totalRetencao + parseFloat(valor.value) ;   	    	       
    	      }
	    }
    }    
    totalRetencao = new Number(parseFloat(totalRetencao));
    valor_nota       = new Number(parseFloat(valor_nota));
    if (totalRetencao.valueOf() > valor_nota.valueOf() ){
    	alert('retenção não deve ser maior que o valor da nota ! >> '+totalRetencao.valueOf()+' >'+valor_nota.valueOf());
    }
}
//  #fim  funções específicas desta aba 


</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">

<form name="form1" method="post" action="" >

<!--
<input type=button  value=teste onclick="teste();">
-->

<table border=0 style="border-top:1px solid" width=80%>
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
        /*
		
		if (array_key_exists($e59_codrec, $retencoes)) {
			$marca = true;
			$v = "tb_rec_valor_$cont";
			$$v = $retencoes[$e59_codrec];
		}
		*/
?>
              <tr id="ret_<?=$x?>" class="tr_tab">
                <td>
                	<input id="chk_<?=$cont?>" type=checkbox name=regra <?=($marca==true?"checked":""); ?> onChange="js_calculaRetencao();"></td>
                <td>
                  <? 
                     $v = 'receita_chk_'.$cont;
                     $$v   = $e59_codrec;
                     global $$v;                  
                     db_input('receita_chk_'.$cont, 10, '', true, 'text',3);
                
                  ?></td>
                <td><?=$k02_drecei ?></td>
                <td align=right>
                <?
                       $v = 'aliquota_chk_'.$cont;
                       $$v   = $e59_aliquota;
                       global $$v;
                       db_input('aliquota_chk_'.$cont, 15, $Ie53_valor, true, 'text',3, '','','','text-align:right')
                ?>
                </td>
                <td align=right>
                <? 
                      db_input('valor_chk_'.$cont, 15, $Ie53_valor, true, 'text',$op, ' onchange=js_testaRetencao();','','','text-align:right')
                ?></td>     
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
   setValorNota(0.00);
</script>