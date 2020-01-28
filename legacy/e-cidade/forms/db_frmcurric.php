<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

//MODULO: recursoshumanos
$clcurric->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("h01_descr");
?>
<form name="form1" method="post" action="">

<fieldset style="margin-top: 10px;">
	<legend>
	  <strong>
	    Manutenção de Currículos
	  </strong>
	</legend>

<table border="0">
  <tr>
    <td nowrap title="<?=@$Th03_seq?>">
       <?=@$Lh03_seq?>
    </td>
    <td> 
				<?
				  db_input('h03_seq',10,$Ih03_seq,true,'text',3,"")
				?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Th03_numcgm?>">
       <?
        db_ancora(@$Lh03_numcgm,"js_pesquisah03_numcgm(true);",$db_opcao);
       ?>
    </td>
    <td> 
				<?
				  db_input('h03_numcgm',10,$Ih03_numcgm,true,'text',$db_opcao," onchange='js_pesquisah03_numcgm(false);'")
				?>
       <?
          db_input('z01_nome',50,$Iz01_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Th03_data?>">
       <?=@$Lh03_data?>
    </td>
    <td> 
				<?
				  db_inputdata('h03_data',@$h03_data_dia,@$h03_data_mes,@$h03_data_ano,true,'text',$db_opcao,"")
				?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Th03_codigo?>">
       <?
        db_ancora(@$Lh03_codigo,"js_pesquisah03_codigo(true);",$db_opcao);
       ?>
    </td>
    <td> 
				<?
				  db_input('h03_codigo',10,$Ih03_codigo,true,'text',$db_opcao," onchange='js_pesquisah03_codigo(false);'")
				?>
       <?
        db_input('h01_descr',50,$Ih01_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Th03_descr?>">
       <?=@$Lh03_descr?>
    </td>
    <td> 
			<?
			db_input('h03_descr',62,$Ih03_descr,true,'text',$db_opcao,"")
			?>
    </td>
  </tr>
  
  <tr>
    <td><B>Carga Horária :</B></td>
    <td>
      <? db_input('h03_cargahoraria',10,'Carga horária',true,'text',$db_opcao,""); ?>
    </td>
  </tr>  
  
  
  <tr>
    <td nowrap title="<?=@$Th03_tipopartic?>">
       <?=@$Lh03_tipopartic?>
    </td>
    <td> 
			<?

			     $oRhtipoparticipacaocurso    = new cl_rhtipoparticipacaocurso;
			     $sSqlRhtipoparticipacaocurso = $oRhtipoparticipacaocurso->sql_query(null, " h67_sequencial, h67_descricao ", 'h67_sequencial', '');
			     $rsRhtipoparticipacaocurso   = $oRhtipoparticipacaocurso->sql_record($sSqlRhtipoparticipacaocurso);
			     db_selectrecord('h03_tipopartic', $rsRhtipoparticipacaocurso, true, $db_opcao,"","","");
			?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Th03_detalh?>">
       <?=@$Lh03_detalh?>
    </td>
    <td> 
			<?
			db_textarea('h03_detalh', 5, 60,$Ih03_detalh,true,'text',$db_opcao,"")
			?>
    </td>
  </tr>
  

  
  
  
  
  </table>
  
</fieldset>
  
  
  <br>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisah03_numcgm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_cgm','func_cgm.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.h03_numcgm.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_cgm','func_cgm.php?pesquisa_chave='+document.form1.h03_numcgm.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
     }else{
       document.form1.z01_nome.value = ''; 
     }
  }
}
function js_mostracgm(erro,chave){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.h03_numcgm.focus(); 
    document.form1.h03_numcgm.value = ''; 
  }
}
function js_mostracgm1(chave1,chave2){
//alert('entrou 2');
  document.form1.h03_numcgm.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_cgm.hide();
}
function js_pesquisah03_codigo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_tabcurri','func_tabcurri.php?funcao_js=parent.js_mostratabcurri1|h01_codigo|h01_descr|h01_cargahor','Pesquisa',true);
  }else{
     if(document.form1.h03_codigo.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_tabcurri','func_tabcurri.php?pesquisa_chave='+document.form1.h03_codigo.value+'&funcao_js=parent.js_mostratabcurri','Pesquisa',false);
     }else{
       document.form1.h01_descr.value = ''; 
     }
  }
}
function js_mostratabcurri(chave,erro, chave2){
  document.form1.h01_descr.value = chave; 
  if(erro==true){ 
    document.form1.h03_codigo.focus(); 
    document.form1.h03_codigo.value = ''; 
    document.form1.h03_cargahoraria.value = "";
    document.form1.h03_descr.value = "";
  }
  if (erro  == false) {
    document.form1.h03_descr.value = document.form1.h01_descr.value;
    document.form1.h03_cargahoraria.value = chave2;
  }
}
function js_mostratabcurri1(chave1,chave2, chave3){

  document.form1.h03_codigo.value       = chave1;
  document.form1.h01_descr.value        = chave2;
  document.form1.h03_cargahoraria.value = chave3;
  db_iframe_tabcurri.hide();
  if(document.form1.h03_descr.value == ''){
    document.form1.h03_descr.value = document.form1.h01_descr.value;
  }
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_curric','func_curric.php?funcao_js=parent.js_preenchepesquisa|h03_seq','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_curric.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>