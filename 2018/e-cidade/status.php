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
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<style type="text/css">
<!--
.tab {
  font-family: Arial, Helvetica, sans-serif;
  font-size: 12px;
  color: #000000;
}
-->
</style>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>
semanas = new Array('Dom','Seg','Ter','Qua','Qui','Sex','S�b');
meses = new Array('jan','fev','mar','abr','mai','jun','jul','ago','set','out','nov','dez');

function js_adiciona_mensagem(mensagem,erro,modulo,acesso){
  alert(mensagens);
  //mensagens[1] = "Modulo: "+modulo+" Acesso: "+acesso+" Erro: "+erro+" Mensagem: "+mensagem;
}

function js_data() {
  data = new Date();
  var segundos = new String(data.getSeconds());
  if(segundos.length == 1)
    segundos = '0' + segundos;
  var minutos = new String(data.getMinutes());
  if(minutos.length == 1)
    minutos = '0' + minutos;
  
//  document.getElementById('dthr').innerHTML = semanas[data.getDay()] + ', ' +  data.getDate() + '/' + meses[data.getMonth()] + '/' + data.getFullYear() + '-' + data.getHours() + ':' + minutos ;
}

function js_mostra_log(tipo){
  if(tipo==true)
    alert(document.getElementById('logtext').innerHTML);

}

</script>
</head>

<body bgcolor=#CCCCCC bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="setInterval('js_data()',1000)">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="533" align="left" valign="middle" class="tab" id="st" ></td>
    <!--<td width="10" align="left" valign="middle" class="tab" id="log" onmouseover="js_mostra_log(true)" onmouseout="js_mostra_log(false)" ><strong>Logs</strong></td>-->
    <td width="30"  align="left" valign="middle" class="tab" ><strong>Data:&nbsp</strong></td>
    <td width="80"  align="center" valign="middle" class="tab" id="dtatual"></td>
    <td width="60"  align="left" valign="middle" class="tab" ><strong>Exerc�cio:&nbsp</strong></td>
    <td width="60"  align="center" valign="middle" class="tab" id="dtanousu"></td>
  </tr>
</table>
<div name='logtext' id='logtext' style='visibility:hidden'></div>
</body>
</html>