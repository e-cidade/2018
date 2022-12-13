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


//MODULO: contabilidade
$clsaltes->rotulo->label();
$clorcreceita->rotulo->label();
$clrotulocampo = new rotulocampo;
$clrotulocampo->label("c70_valor");
$clrotulocampo->label("c70_data");
db_postmemory($HTTP_POST_VARS);
?>
  <form name="form1" method="post" action="" >
  <center>
  <table border="0">
    <tr>
      <td colspan"2" align="right" nowrap title="<?=@$Tc70_data?>">
	 <?=@$Lc70_data?>
      <?


if (!isset ($c70_data_dia)) {
	$c70_data_dia = date("d", db_getsession("DB_datausu"));
	$c70_data_mes = date("m", db_getsession("DB_datausu"));
	$c70_data_ano = date("Y", db_getsession("DB_datausu"));
}
db_inputdata('c70_data', @ $c70_data_dia, @ $c70_data_mes, @ $c70_data_ano, true, 'text', $db_opcao, "")
?>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
	<input name="pesquisa" value="Pesquisa" type="submit">
      </td>
    </tr>
  <? 
 if (isset ($pesquisa)) {

	$data = $c70_data_ano."-".$c70_data_mes."-".$c70_data_dia;
	$clboletim = new cl_boletim;
	$result = $clboletim->sql_record($clboletim->sql_query($data, db_getsession("DB_instit")));
	$processar = false;
	if ($clboletim->numrows == 0) {
		db_msgbox('Boletim não gerado para esta data. ('.$c70_data_dia."/".$c70_data_mes."/".$c70_data_ano.')');
		db_redireciona('con4_boletim003.php');
	} else {
		db_fieldsmemory($result, 0);
		if ($k11_libera == 'f' || $k11_lanca == 'f' || db_getsession("DB_anousu") != $c70_data_ano) {
			if ($k11_libera == 'f') {
				db_msgbox('Boletim não liberado para a Contabilidade.');
			} else
				if ($k11_lanca == 'f') {
					db_msgbox('Boletim não processado pela Contabilidade.');
				} else {
					db_msgbox('Exercício Inválido. Permitido: '.db_getsession("DB_anousu"));
				}
			db_redireciona('con4_boletim003.php');
		} else {
			$processar = true;

			echo "<script>
				      function js_mostra(tipo){
			                js_OpenJanelaIframe('top.corpo','db_iframeboletim','forms/db_frmboletim002.php?tipo='+tipo+'&data=$data&boletim=$k11_numbol','Saldoa',true);
				      }
				      </script>";
			echo "<br>";
			echo "<tr>";
			echo "<td align=\"center\"  colspan=\"2\" >";
			echo "</td>";
			echo "</tr>";
			echo "<tr>";
			echo "<td align=\"center\"  colspan=\"2\" >";
			echo "</td>";
			echo "</tr>";

			echo "<tr>";
			echo "<td align=\"left\"  width='70%' >Transferências Caixa/Bancos </td><td><a  onclick=\"js_mostra('trans');return false;\" href='#'> Consulta</a>";
			echo "</td>";
			echo "</tr>";
			echo "<tr>";
			echo "<td align=\"center\"  colspan=\"2\" >";
			echo "</td>";
			echo "</tr>";
			echo "<tr>";
			echo "<td align=\"left\"  width='70%' >Receita Orçamentária</td><td><a  onclick=\"js_mostra('recorc');return false;\" href='#'> Consulta</a>";
			echo "</td>";
			echo "</tr>";
			echo "<tr>";
			echo "<td align=\"center\"  colspan=\"2\" >";
			echo "</td>";
			echo "</tr>";

			echo "<tr>";
			echo "<td align=\"left\"  width='70%' >Receita Extra-Orçamentária</td><td><a  onclick=\"js_mostra('recextra');return false;\" href='#'> Consulta</a>";
			echo "</td>";
			echo "</tr>";
			echo "<tr>";
			echo "<td align=\"center\"  colspan=\"2\" >";
			echo "</td>";
			echo "</tr>";

			echo "<tr>";
			echo "<td align=\"left\"  width='70%' >Despesa Extra-Orçamentária</td><td><a  onclick=\"js_mostra('despextra');return false;\" href='#'> Consulta</a>";
			echo "</td>";

			echo "</tr>";

			//echo "<tr>";
			//echo "<td align=\"center\" colspan=\"2\">";
			//echo '<iframe name="iframe_receita" frameborder="0" leftmargin="0" topmargin="0" 
			//    src="forms/db_frmboletim002.php?data='.$data.'&boletim='.$k11_numbol.'" height="300" width="770"></iframe>';
			//echo "</td>";
			//echo "</tr>";
		}
	}
?>
      <tr>
      <td align="center" colspan="2"> 
     <input name="data" value="<?=$data?>" type="hidden">
	 <input name="processar" value="Estornar Boletim" type="submit" >
      </td>
      </tr>
    <?


}
?>
</table>
</center>
</form>