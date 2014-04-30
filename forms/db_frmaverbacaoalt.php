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

//MODULO: cadastro
$claverbacao->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("j77_codproc");
$clrotulo->label("p58_requer");
$clrotulo->label("j78_protocolo");
$clrotulo->label("j78_matric");

?>
<script>
function js_submit(){
	document.form1.submit();
}
</script>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td align="right" nowrap title="<?=@$Tj75_codigo?>">
       <?=@$Lj75_codigo?>
    </td>
    <td> 
<?
db_input('j75_codigo',6,$Ij75_codigo,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td align="right" nowrap title="<?=@$Tj75_matric?>">
       <?
       db_ancora(@$Lj75_matric,"js_consulta_matric();",1);
       ?>
    </td>
    <td> 
<?
db_input('j75_matric',10,$Ij75_matric,true,'text',3," onchange='js_pesquisaj75_matric(false);'")
?>
       <?
db_input('z01_nome',50,$Iz01_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  
  <tr>
    <td nowrap title="<?=@$Tj75_data?>">
       <?=@$Lj75_data?>
    </td>
    <td> 
<?
db_inputdata('j75_data',@$j75_data_dia,@$j75_data_mes,@$j75_data_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  
  <tr>
    <td align="right" nowrap title="<?=@$Tj75_obs?>">
       <?=@$Lj75_obs?>
    </td>
    <td> 
<?
db_textarea('j75_obs',0,60,$Ij75_obs,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td align="right"  nowrap title="<?=@$Tj75_tipo?>">
       <?=@$Lj75_tipo?>
    </td>
    <td> 
<?
$x = array('1'=>'Escritura','2'=>'Registro de Imóveis','3'=>'Contrato');
db_select('j75_tipo',$x,true,$db_opcao,"onchange='js_submit();'");
?>
<?=@$Lj75_dttipo?>
<?
if ($db_opcao==1){
	$opc=1;
}else{
	$opc=3;
}
db_inputdata('j75_dttipo',@$j75_dttipo_dia,@$j75_dttipo_mes,@$j75_dttipo_ano,true,'text',$opc,"")
?>

    </td>
  </tr>
  
  <!--
  <tr>
    <td nowrap title="<?=@$Tj75_dttipo?>">
       <?=@$Lj75_dttipo?>
    </td>
    <td> 
<?
db_inputdata('j75_dttipo',@$j75_dttipo_dia,@$j75_dttipo_mes,@$j75_dttipo_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  -->
  <tr>
    <td align="right" nowrap title="<?=@$Tj77_codproc?>">
       <?
       db_ancora(@$Lj77_codproc,"js_pesquisaj77_codproc(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('j77_codproc',10,$Ij77_codproc,true,'text',$db_opcao," onchange='js_pesquisaj77_codproc(false);'")
?>
       <?
db_input('p58_requer',50,$Ip58_requer,true,'text',3,'')
       ?>
    </td>
  </tr>
  <?
    if (isset($j75_tipo)&&$j75_tipo==2){
    ?>
    <tr>
    <td align="right" nowrap title="<?=@$Tj78_matric?>">
       <?=@$Lj78_matric?>
    </td>
    <td> 
<?
db_input('j78_matric',30,$Ij78_matric,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
    <tr>
    <td align="right" nowrap title="<?=@$Tj78_protocolo?>">
       <?=@$Lj78_protocolo?>
    </td>
    <td> 
<?
db_input('j78_protocolo',30,$Ij78_protocolo,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
    <?
    }else if (isset($j75_tipo)&&$j75_tipo==1){   	 	
  	?>
    <tr>
    <td align="right" nowrap title="<?=@$Tj94_livro?>">
       <?=@$Lj94_livro?>
    </td>
    <td> 
<?
db_input('j94_livro',30,$Ij94_livro,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
    <tr>
    <td align="right" nowrap title="<?=@$Tj94_folha?>">
       <?=@$Lj94_folha?>
    </td>
    <td> 
<?
db_input('j94_folha',30,$Ij94_folha,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td align="right" nowrap title="<?=@$Tj94_numero?>">
       <?=@$Lj94_numero?>
    </td>
    <td> 
<?
db_input('j94_numero',30,$Ij94_numero,true,'text',$db_opcao,"")
?>
    </td>
  </tr>  	
   <tr>
    <td align="right" nowrap title="<?=@$Tj94_tabelionato?>">
       <?=@$Lj94_tabelionato?>
    </td>
    <td> 
<?
db_input('j94_tabelionato',30,$Ij94_tabelionato,true,'text',$db_opcao,"")
?>
    </td>
  </tr>  	  
    <?
    }
    ?>
    <tr>
    <td align="right"  nowrap title="<?=@$Tj75_situacao?>">
       <?=@$Lj75_situacao?>
    </td>
    <td> 
<?
if (!isset($j75_situacao)){
	$j75_situacao = 1;
}
$y = array('1'=>'Não Processada','2'=>'Processada');
db_select('j75_situacao',$y,true,3,"");

?></td>
  </tr>
    </table>
  </center>
  <br>
<input name="processar" type="submit" id="db_opcao" value="Processar" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_consulta_matric(){
	js_OpenJanelaIframe('top.corpo','db_iframe','cad3_conscadastro_002.php?cod_matricula='+document.form1.j75_matric.value,'Consulta Matrcula',true);
}
function js_pesquisaj77_codproc(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_cgm','func_protprocesso.php?funcao_js=parent.js_mostraprotprocesso1|p58_codproc|z01_nome','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_cgm','func_protprocesso.php?pesquisa_chave='+document.form1.j77_codproc.value+'&funcao_js=parent.js_mostraprotprocesso','Pesquisa',false);
  }
}
function js_mostraprotprocesso(chave,chave1,erro){
  document.form1.p58_requer.value = chave1; 
  if(erro==true){ 
    document.form1.j77_codproc.focus(); 
    document.form1.j77_codproc.value = ''; 
  }
}
function js_mostraprotprocesso1(chave1,chave2){
  document.form1.j77_codproc.value = chave1;
  document.form1.p58_requer.value = chave2;
  db_iframe_cgm.hide();
}
function js_pesquisaj75_matric(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_iptubase','func_iptubase.php?funcao_js=parent.js_mostraiptubase1|j01_matric|j01_numcgm','Pesquisa',true);
  }else{
     if(document.form1.j75_matric.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_iptubase','func_iptubase.php?pesquisa_chave='+document.form1.j75_matric.value+'&funcao_js=parent.js_mostraiptubase','Pesquisa',false);
     }else{
       document.form1.j01_numcgm.value = ''; 
     }
  }
}
function js_mostraiptubase(chave,erro){
  document.form1.j01_numcgm.value = chave; 
  if(erro==true){ 
    document.form1.j75_matric.focus(); 
    document.form1.j75_matric.value = ''; 
  }
}
function js_mostraiptubase1(chave1,chave2){
  document.form1.j75_matric.value = chave1;
  document.form1.j01_numcgm.value = chave2;
  db_iframe_iptubase.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_averbacao','func_averbacaoalt.php?funcao_js=parent.js_preenchepesquisa|j75_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_averbacao.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>