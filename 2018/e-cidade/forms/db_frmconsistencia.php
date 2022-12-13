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
<form name="form1" method="post" action="">
<center>
<br>
<strong>Arquivos do:</strong>
<? 

$periodo = array("1"=> " 1 - Janeiro          ",
"2"=> " 2 - Fevereiro (1 Bim)",
"3"=> " 3 - Março            ",
"4"=> " 4 - Abril     (2 Bim)",
"5"=> " 5 - Maio             ",
"6"=> " 6 - Junho     (3 Bim)",
"7"=> " 7 - Julho            ", 
"8"=> " 8 - Agosto    (4 Bim)",
"9"=> " 9 - Setembro         ",
"10"=>"10 - Outubro   (5 Bim)",
"11"=>"11 - Novembro         ",
"12"=>"12 - Dezembro  (6 Bim)");
global $periodopad;
$periodopad = date("m",db_getsession("DB_datausu"))-1;
if(db_getsession("DB_anousu") != date("Y",db_getsession("DB_datausu"))){
  $periodopad = 12;
}else{
  if($periodopad == 0)
  $periodopad = 1;
}
db_select("periodopad",$periodo,true,2);
?>

<br>
<table border="1">
<tr>
<td valign=top rowspan=2>
<table border="0">
<tr><td colspan=2> <b>ESCOLHA</b> </td></tr>
<tr><td><input type=checkbox name="docempenho">  </td><td>Documento nao configurado</td></tr>
</table>
</td>
</tr>

<tr auto>
<td colspan="2">

<iframe name="iframe_processapad" src="con4_processaconsistencia.php" scrolling="auto" height="450" width="800" >

</iframe>
</td>
</tr>

</table>

<br>
<input name="todos" type="button" value="Todos" onclick="js_marcatodos();" >
<input name="limpa" type="button" value="Limpa" onclick="js_limpatodos();" >
<input name="processar" type="button" value="Processar" onclick="js_seleciona();">

</form>