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
?>
<html>
<head>
<title>Documento sem t&iacute;tulo</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script>
function js_removelinha(linha,linha1) {
  //alert(linha);
  //alert(linha1);
  var tab = document.getElementById('tab');
  for(i=0;i<tab.rows.length;i++){
    if(linha == tab.rows[i].id){
      var totalapagar  = new Number(parent.document.form1.apagar.value);
      var totalapagar1 = new Number(document.getElementById(linha1).value);
      totalapagar = totalapagar - totalapagar1;
      parent.document.form1.apagar.value = totalapagar.toFixed(2);
      tab.deleteRow(i);
      break;
    }
  }
}
</script>
<style type="text/css">
.cancelapagto{
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	height: 20px;
	width: 100px;
	background-color: #AAAF96;
}
</style>
</head>
<body bgcolor=#CCCCCC bgcolor="#AAAF96" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<center>
  <table width="695" id="tab" border="1" cellspacing="0" cellpadding="0">
    <tr bgcolor="#BDC6BD">
      <th width="42" align="left" nowrap style="font-size:12px">Tipo</th>
      <th width="300" align="left" nowrap style="font-size:12px">Origem</th>
      <th width="80" nowrap style="font-size:12px">Valor Histórico</th>
      <th width="97" nowrap bgcolor="#BDC6BD" style="font-size:12px">Valor Corrigido</th>
      <th width="80" nowrap bgcolor="#BDC6BD" style="font-size:12px">Valor dos Juros</th>
      <th width="80" nowrap style="font-size:12px">Valor da Multa</th>
      <th width="50" nowrap style="font-size:12px">Valor do Desconto</th>
      <th width="83" nowrap style="font-size:12px">Valor à Pagar</th>
      <th width="120" nowrap style="font-size:12px">Cancela Pagto.</th>
    </tr>
  </table>
</center>			
</body>
</html>