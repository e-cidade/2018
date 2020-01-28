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

//MODULO: merenda
$clmer_estoque->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("me10_i_codigo");
$clrotulo->label("ed18_i_codigo");
$clrotulo->label("m40_codigo");
$clrotulo->label("ed52_i_codigo");
$clrotulo->label("me19_i_matrequi");
?>
<form name="form1" method="post" action="">
<br><br><br>
<center>
<fieldset style="width:45%"><legend><b> Entrada no Estoque com Requisição </b></legend>
<table border="0">
  <tr>
    <td>
       <b>Calendario:</b>
    </td>
    <td>
     <? 
      $sql  = " select * from calendarioescola "; 
      $sql .= " inner join calendario on ed52_i_codigo=ed38_i_calendario where ed38_i_escola=".$escola; 
      $result=pg_query($sql);
	  $linhas=pg_num_rows($result);
	  ?><select name="calendario" value=""><?
	  for ($y=0;$y<$linhas;$y++) {
	  	
	  	db_fieldsmemory($result,$y);
	  	 ?><option value=<?=$ed52_i_codigo?>><?=$ed52_c_descr?></option>
	  	 
	  <?}?>
	  </select>
	</td>   
  </tr>
  <tr>
    <td nowrap title="<?=@$Tme19_i_matrequi?>">
       <?db_ancora(@$Lme19_i_matrequi,"js_pesquisame19_i_matrequi(true);",1);?>
    </td>
    <td> 
       <?db_input('me19_i_matrequi',5,$Ime19_i_matrequi,true,'text',1,
                  " onchange='js_pesquisame19_i_matrequi(false);'"
                 )
       ?>
       <?db_input('m40_codigo',10,$Im40_codigo,true,'hidden',3,'')?>
    </td>
    <td>
       <input type="button" name="Listar" value="Listar" onclick="js_listar(document.form1.me19_i_matrequi.value)">
    </td>
  </tr>
</table> 
<?if (isset($codrequi)) {
	
	$sql = $cl_matrequiitem->sql_query("","*","","m41_codmatrequi=".$codrequi);
	$result=pg_query($sql);
	$linhas=pg_num_rows($result);
	?>
	  <table border="3">
	    <tr>
	       <b>
	       <td>
	           Cod
	       </td>
	       <td>
	           Descrição
	       </td>
	       <td>
	           Quantidade
	       </td>
	       <td>
	           Confirmação
	       </td>
	       </b>
	    </tr>
	  	<?
	$tquant=0;
	for ($x=0;$x<$linhas;$x++) {
	  db_fieldsmemory($result,$x);
     ?>	  	   
	 <tr>
	  <td>
	   <?=$m41_codmatmater?>
	  </td>
	  <td>
	   <?=$m60_descr?>
	  </td>
	  <td>
	   <center>
	    <?=$m41_quant?>
	    <?$tquant = $tquant+$m41_quant ?>
	   </center>
	  </td>
	  <td>
	   <input type="checkbox" name="item" checked>Entregue
	  </td>
	 </tr>    
  <?}?>  
	 <tr>
	   <td>	           
	   </td>
	   <td>	          
	   </td>
	   <td>
	     Total quant:<?=$tquant?>
	   </td>
	   <td>
	     Total Items:<?=$linhas?>
	   </td>
	  </tr>
	  </table>
    <center>
     <input type="button" name="incluir" value="Incluir" onclick="js_incluir(<?=$codrequi?>,<?=$linhas?>)">
     <input type="button" name="calcelar" value="Cancelar">
    </center>
	<?
  }
?>
</fieldset> 
</center>
</form>
<script>
function js_pesquisame19_i_matrequi(mostra) {
	
  if(mostra==true){
	  
    js_OpenJanelaIframe('top.corpo','db_iframe_matrequi',
    	                'func_matrequi_mer.php?funcao_js=parent.js_mostramatrequi1|m40_codigo|m40_codigo',
    	                'Pesquisa',true
    	               );
    
  } else{
	  
    if (document.form1.me19_i_matrequi.value != '') {
         
      js_OpenJanelaIframe('top.corpo','db_iframe_matrequi',
    	                  'func_matrequi_mer.php?pesquisa_chave='+document.form1.me19_i_matrequi.value+
    	                  '&funcao_js=parent.js_mostramatrequi','Pesquisa',false
    	                 );
      
    } else {
      document.form1.m40_codigo.value = ''; 
    }
  }
}

function js_mostramatrequi(chave,erro){
  document.form1.m40_codigo.value = chave; 
  if(erro==true){ 
    document.form1.me19_i_matrequi.focus(); 
    document.form1.me19_i_matrequi.value = ''; 
  }
}
function js_mostramatrequi1(chave1,chave2){
  document.form1.me19_i_matrequi.value = chave1;
  document.form1.m40_codigo.value      = chave2;
  db_iframe_matrequi.hide();
}
function js_listar(codrequi){
  if(codrequi==''){
     alert('Selecione uma Requisição');     
  }else{
     location.href = 'mer1_mer_estoque002.php?codrequi='+codrequi;
  }
}
function js_incluir(codrequi,quant){
  if(confirm("Tem certeza que deseja incluir estes Items?!")){  
    sep='';
    lista='';
    if(quant>1){  
      for(x=0;x<quant;x++){      
         if(document.form1.item[x].checked==true){
            lista=lista+sep+x;
            sep=',';
         }
      }
    }else{
      lista='0';
    }    
    calendario=document.form1.calendario.value;
    location.href = 'mer1_mer_estoque002.php?codrequi='+codrequi+'&lista='+lista+'&calendario='+calendario+'&incluir='+1;
  }
}
</script>