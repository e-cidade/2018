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
<form name="form1" method="post" action="">
<center>
<br>
<strong>Arquivos do :</strong>
<? 
// $data1_dia = date("d",db_getsession("DB_datausu"));
// $data1_mes = date("m",db_getsession("DB_datausu"));
// $data1_ano = date("Y",db_getsession("DB_datausu"));
// $data2_dia = date("d",db_getsession("DB_datausu"));
// $data2_mes = date("m",db_getsession("DB_datausu"));
// $data2_ano = date("Y",db_getsession("DB_datausu"));

// db_inputdata('data_ini',@$data1_dia,@$data1_mes,@$data1_ano,true,'text',1);  
// db_inputdata('data_fim',@$data2_dia,@$data2_mes,@$data2_ano,true,'text',1);  
  
if ($iTipo == "pad") {
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
} else if ($iTipo == "mgs") {
  $periodo = array(
                   "1"=> " 1 - Janeiro   ",
                   "2"=> " 2 - Fevereiro ",
            		   "3"=> " 3 - Março     ",
            		   "4"=> " 4 - Abril     (1 Quadrimestre)",
             		   "5"=> " 5 - Maio      ",
            		   "6"=> " 6 - Junho     ",
             		   "7"=> " 7 - Julho     ", 
             		   "8"=> " 8 - Agosto    (2 Quadrimestre)",
             		   "9"=> " 9 - Setembro  ",
            		   "10"=>"10 - Outubro   ",
            		   "11"=>"11 - Novembro  ",
             		   "12"=>"12 - Dezembro  (3 Quadrimestre)");
}
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
   <tr><td colspan=2> ARQUIVOS PRINCIPAIS   </td></tr>
   <tr><td><input type=checkbox name="empenho">  </td><td>Empenhos   </td></tr>
   <tr><td><input type=checkbox name="pagament"> </td><td>Pagamento  </td></tr>
   <tr><td><input type=checkbox name="bal_rec"> </td><td>Balancete de Receita  </td></tr>
   <tr><td><input type=checkbox name="receita"> </td><td>Receita  </td></tr>
 </table>
</td>
<td valign=top rowspan=2>
 <table border="0">
   <tr><td colspan=2> ARQUIVOS AUXILIARES   </td></tr>
   <tr><td><input type=checkbox name="rubrica"> </td><td>Rubricas  </td></tr>
   <tr><td><input type=checkbox name="credor"> </td><td>Credor </td></tr>
 </table>
</td>
<td valign=top height=50%>
 <table border="0">
   <tr><td colspan=2> DO EXERCICIO    </td></tr>
   <tr><td><input type=checkbox name="cta_disp">  </td><td>Disponibilidades </td></tr>
 </table>
</td>
</tr>
<tr>
  <td colspan="2">

  <iframe name="iframe_processapad" src="con4_processapad.php" scrolling="auto"  >
  
  </iframe>
  </td>
</tr>

</table>

<br>
 <input name="todos" type="button" value="Todos" onclick="js_marcatodos();" >
 <input name="limpa" type="button" value="Limpa" onclick="js_limpatodos();" >
 <input name="processar" type="button" value="Processar" onclick="js_seleciona('<?=$iTipo;?>');">

</form>