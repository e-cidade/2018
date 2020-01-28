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

$clrotulo = new rotulocampo;
$clrotulo->label("pc10_numero");
$clrotulo->label("pc10_data");
$clrotulo->label("pc10_resumo");
$clrotulo->label("descrdepto");
$val = false;
if(isset($pc10_numero) && trim($pc10_numero)!=""){
  $val = true;
  $cod = $pc10_numero;
}
?>
<form name="form1" method="post" action="">
<center>
<br><br><br>
<table border="0" height="100%">
  <tr  width="10%" height="01%">
    <td align="right" nowrap>
      <strong>Solicitação: </strong>
    </td>
    <td align="left" nowrap>
    <?
      $arr_numero = array();
      $arr_index  = array();
      $sql_solicita = $clsolicita->sql_record($clsolicita->sql_query_solicita(null,"distinct pc10_numero,pc10_data,pc10_resumo,descrdepto","pc10_numero"));
      for($i=0;$i<$clsolicita->numrows;$i++){
	db_fieldsmemory($sql_solicita,$i,true);
	$arr_numero[$pc10_numero] = $pc10_numero;
	$arr_index[$pc10_numero]  = $i;
	$arr_data = split("/",$pc10_data);
	$pc10_data_dia = $arr_data[0];
	$pc10_data_mes = $arr_data[1];
	$pc10_data_ano = $arr_data[2];
      }
      if($clsolicita->numrows>0){
	db_fieldsmemory($sql_solicita,0,true);
      }
      db_select('pc10_numero',$arr_numero,true,1,"onchange='js_mudasolicita();'");
      if($val==true){
	echo "<script>
                var_obj = document.getElementById('pc10_numero').length;
		for(i=0;i<var_obj;i++){
		  if(document.getElementById('pc10_numero').options[i].value==$cod){
		    document.getElementById('pc10_numero').options[i].selected = true;
		  }
		}
	      </script>";
	db_fieldsmemory($sql_solicita,$arr_index[$cod]);
	$arr_data = split("-",$pc10_data);
	$pc10_data_dia = $arr_data[2];
	$pc10_data_mes = $arr_data[1];
	$pc10_data_ano = $arr_data[0];
      }
    ?>
    </td>
    <td align="right" nowrap>
      <strong>&nbsp;&nbsp;&nbsp;Data: </strong>      
    </td>
    <td align="left" nowrap>
    <?
      db_inputdata('pc10_data',@$pc10_data_dia,@$pc10_data_mes,@$pc10_data_ano,true,'text',3);
    ?>
    </td>
    <td align="right" nowrap>
      <strong>&nbsp;&nbsp;&nbsp;Departamento: </strong>      
    </td>
    <td align="left" nowrap>
    <?
      db_input('descrdepto',40,$Idescrdepto,true,'text',3);
    ?>
    </td>
  </tr>
  <tr height="3%">
    <td align="right" nowrap>
      <strong>&nbsp;&nbsp;&nbsp;Resumo: </strong>      
    </td>
    <td colspan="5" nowrap>
    <?
      db_textarea('pc10_resumo',2,80,$Ipc10_resumo,true,'text',3,"")
    ?>
    </td>
  </tr>
  <tr height="3%" nowrap>
    <td colspan="6" nowrap><strong><br>Itens da solicitação:</strong></td>
  </tr>
  <tr align="center" width="90%" >
    <td colspan="6" width="100%" height="70%" nowrap>
      <iframe name="iframe_solicitem" id="solicitem" marginwidth="0" marginheight="0" frameborder="0" src="com1_gerasolicitem.php" width="95%" height="100%"></iframe>
    </td>
  </tr>
</table>
</center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> onclick='top.corpo.document.form1.submit();' >
<br><br><br>
</form>
<script>
function js_mudasolicita(){  
  parent.iframe_solicita.location.href = 'com1_pcproc004.php?pc10_numero='+document.form1.pc10_numero.value;  
}
if(document.form1.pc10_numero.value!=""){
  iframe_solicitem.location.href = 'com1_gerasolicitem.php?solicita='+document.form1.pc10_numero.value;
}
</script>