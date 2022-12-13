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

//MODULO: agua
include("dbforms/db_classesgenericas.php");

$clrotulo = new rotulocampo;
$clrotulo->label("x01_codrua");
$clrotulo->label("j14_nome");
?>
<form name="form1" method="post" action="">
 <?
   if(isset($processa)){
	 
	   db_criatermometro('termo_alteracao_serie','Concluido...','blue',0); 
   }
	   echo "<div id='filtro' style='visibility:visible'>";
?>

<center>
<table border="0">


  <tr>
    <td nowrap title="<?=@$Tx01_codrua?>">
       <?
       db_ancora(@$Lx01_codrua,"js_pesquisax01_codrua(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('x01_codrua',7,$Ix01_codrua,true,'text',$db_opcao," onchange='js_pesquisax01_codrua(false);'")
?>
       <?
db_input('j14_nome',40,$Ij14_nome,true,'text',3,'')
       ?>
    </td>
  </tr>

  <tr>
    <td nowrap title="Numero Inicial">
	   <strong>Numero Inicial:</strong>
    </td>
    <td> 
<?
db_input('x99_numero_inicial',5,0,true,'text',$db_opcao,"")
?>
    </td>
  </tr>

  <tr>
    <td nowrap title="Numero Final">
	   <strong>Numero Final:</strong>
    </td>
    <td> 
<?
db_input('x99_numero_final',5,0,true,'text',$db_opcao,"")
?>
    </td>
  </tr>

   <tr>
    <td nowrap title="Ligacao de Agua">
	   <strong>Agua:</strong>
    </td>
    <td> 
<?

$sql = $clcaracter->sql_query(null, "j31_codigo, j31_descr", null, "j32_grupo=83");
$result = $clcaracter->sql_record($sql);

db_selectrecord("x99_caragua",$result,true,$db_opcao,"","","","");
?>
    </td>
  </tr>

  <tr>
    <td nowrap title="Ligacao de Esgoto">
	   <strong>Esgoto:</strong>
    </td>
    <td> 
<?

$sql = $clcaracter->sql_query(null, "j31_codigo, j31_descr", null, "j32_grupo=82");
$result = $clcaracter->sql_record($sql);

db_selectrecord("x99_caresgoto",$result,true,$db_opcao,"","","","");
?>
    </td>
  </tr>
			 
   	<!--
  <tr> 
 <td></td>
    <td>

      <input name="termometro" style='background: transparent' id="termometro" type="text" value="" size=80>
		-->
	
<!--    </td>-->
  </tr> 
  <tr> 
    <td height="25">&nbsp;</td>
      <td height="25"> <input name="processa"  type="submit" id="processa" value="Processa" onclick="" > 
    </td>
  </tr>


 </table>
  </center>
</form>

<script>

function js_termo(msg){
	document.getElementById('termometro').innerHTML=msg;
}

function js_cancelar(){
  var opcao = document.createElement("input");
  opcao.setAttribute("type","hidden");
  opcao.setAttribute("name","novo");
  opcao.setAttribute("value","true");
  document.form1.appendChild(opcao);
  document.form1.submit();
}

function js_pesquisax01_codrua(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_ruas','func_ruas.php?funcao_js=parent.js_mostraruas1|j14_codigo|j14_nome','Pesquisa',true,'20','1','775','390');
  }else{
     if(document.form1.x01_codrua.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_ruas','func_ruas.php?pesquisa_chave='+document.form1.x01_codrua.value+'&funcao_js=parent.js_mostraruas','Pesquisa',false,'20','1','775','390');
     }else{
       document.form1.j14_nome.value = ''; 
     }
  }
}
function js_mostraruas(chave,erro){
  document.form1.j14_nome.value = chave; 
  if(erro==true){ 
    document.form1.x01_codrua.focus(); 
    document.form1.x01_codrua.value = ''; 
  }
}
function js_mostraruas1(chave1,chave2){
  document.form1.x01_codrua.value = chave1;
  document.form1.j14_nome.value = chave2;
  db_iframe_ruas.hide();
}

</script>