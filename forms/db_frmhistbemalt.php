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

//MODULO: patrim
$clhistbem->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("t52_descr");
$clrotulo->label("descrdepto");
$clrotulo->label("t70_descr");
?>
<form class="container" name="form1" method="post" action="">
  <fieldset>
    <legend>Procedimentos - Alterar Situação</legend>
    <table class="form-contianer">
      <tr>
        <td nowrap title="<?=@$Tt56_codbem?>">
          <?
            db_ancora(@$Lt56_codbem,"js_pesquisat56_codbem(true);",3);
          ?>
        </td>
        <td> 
          <?
            db_input('t56_codbem',8,$It56_codbem,true,'text',3," onchange='js_pesquisat56_codbem(false);'")
          ?>
          <?
            db_input('t52_descr',60,$It52_descr,true,'text',3,'')
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tt56_data?>">
          <?=@$Lt56_data?>
        </td>
        <td> 
          <?
            $t56_data_dia = date('d',db_getsession("DB_datausu"));
            $t56_data_mes= date('m',db_getsession("DB_datausu"));
            $t56_data_ano= date('Y',db_getsession("DB_datausu"));
            
            db_inputdata('t56_data',@$t56_data_dia,@$t56_data_mes,@$t56_data_ano,true,'text',3,"");
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tt56_depart?>">
          <?
            db_ancora(@$Lt56_depart,"js_pesquisat56_depart(true);",3);
          ?>
        </td>
        <td> 
          <?
            db_input('t56_depart',8,$It56_depart,true,'text',3," onchange='js_pesquisat56_depart(false);'")
          ?>
          <?
            db_input('descrdepto',60,$Idescrdepto,true,'text',3,'')
          ?>
        </td>
      </tr>
      <?if (isset($t52_depart)&&$t52_depart!=""){?>
      <tr>
        <td nowrap title="Divisão do Depart.">
          Divisão:   
        </td>
        <td>    
          <select name='t33_divisao'>
    	      <option value=''>Nenhuma</option>
          	<?
            	$result=$cldepartdiv->sql_record($cldepartdiv->sql_query_file(null,"t30_codigo,t30_descr",null,"t30_depto=$t52_depart"));
            	for($y=0;$y<$cldepartdiv->numrows;$y++){
             	  db_fieldsmemory($result,$y);
           	?>
    	      <option value=<?=@$t30_codigo?> <?=(isset($t33_divisao)&&$t33_divisao==$t30_codigo?"selected":"")?> > <?=@$t30_descr?></option>
       	    <?}?>
          </select> 
        </td>
      <?}?>
      <tr>
        <td nowrap title="<?=@$Tt56_situac?>">
          <?
            db_ancora(@$Lt56_situac,"js_pesquisat56_situac(true);",$db_opcao);
          ?>
        </td>
        <td> 
          <?
            db_input('t56_situac',8,$It56_situac,true,'text',$db_opcao," onchange='js_pesquisat56_situac(false);'")
          ?>
          <?
            db_input('t70_descr',60,$It70_descr,true,'text',3,'')
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tt56_histor?>" colspan="2">
          <fieldset class="separator">
            <legend>Justificativa:</legend>
            <?
              db_textarea('t56_histor',0,50,$It56_histor,true,'text',$db_opcao,"")
            ?>
          </fieldset>
        </td>
      </tr>
    </table>
  </fieldset>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>"  onclick="return js_hist();"  <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_hist(){
	if (document.form1.t56_histor.value==""){
		alert(_M("patrimonial.patrimonio.db_frmhistbemalt.justificativa_obrigatoria"));
		document.form1.t56_histor.focus();
		return false;
	}else{
		return true;
	}
}
function js_pesquisat56_codbem(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_bens','func_bens.php?funcao_js=parent.js_mostrabens1|t52_bem|t52_descr','Pesquisa',true);
  }else{
     if(document.form1.t56_codbem.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_bens','func_bens.php?pesquisa_chave='+document.form1.t56_codbem.value+'&funcao_js=parent.js_mostrabens','Pesquisa',false);
     }else{
       document.form1.t52_descr.value = ''; 
     }
  }
}
function js_mostrabens(chave,erro){
  document.form1.t52_descr.value = chave; 
  if(erro==true){ 
    document.form1.t56_codbem.focus(); 
    document.form1.t56_codbem.value = ''; 
  }
}
function js_mostrabens1(chave1,chave2){
  document.form1.t56_codbem.value = chave1;
  document.form1.t52_descr.value = chave2;
  db_iframe_bens.hide();
}
function js_pesquisat56_depart(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_depart','func_db_depart.php?funcao_js=parent.js_mostradb_depart1|coddepto|descrdepto','Pesquisa',true);
  }else{
     if(document.form1.t56_depart.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_db_depart','func_db_depart.php?pesquisa_chave='+document.form1.t56_depart.value+'&funcao_js=parent.js_mostradb_depart','Pesquisa',false);
     }else{
       document.form1.descrdepto.value = ''; 
     }
  }
}
function js_mostradb_depart(chave,erro){
  document.form1.descrdepto.value = chave; 
  if(erro==true){ 
    document.form1.t56_depart.focus(); 
    document.form1.t56_depart.value = ''; 
  }
}
function js_mostradb_depart1(chave1,chave2){
  document.form1.t56_depart.value = chave1;
  document.form1.descrdepto.value = chave2;
  db_iframe_db_depart.hide();
}
function js_pesquisat56_situac(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_situabens','func_situabens.php?funcao_js=parent.js_mostrasituabens1|t70_situac|t70_descr','Pesquisa',true);
  }else{
     if(document.form1.t56_situac.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_situabens','func_situabens.php?pesquisa_chave='+document.form1.t56_situac.value+'&funcao_js=parent.js_mostrasituabens','Pesquisa',false);
     }else{
       document.form1.t70_descr.value = ''; 
     }
  }
}
function js_mostrasituabens(chave,erro){
  document.form1.t70_descr.value = chave; 
  if(erro==true){ 
    document.form1.t56_situac.focus(); 
    document.form1.t56_situac.value = ''; 
  }
}
function js_mostrasituabens1(chave1,chave2){
  document.form1.t56_situac.value = chave1;
  document.form1.t70_descr.value = chave2;
  db_iframe_situabens.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_bens','func_bens.php?funcao_js=parent.js_preenchepesquisa|t52_bem','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_bens.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>
<script>

$("t56_codbem").addClassName("field-size2");
$("t52_descr").addClassName("field-size7");
$("t56_data").addClassName("field-size2");
$("t56_depart").addClassName("field-size2");
$("descrdepto").addClassName("field-size7");
$("t56_situac").addClassName("field-size2");
$("t70_descr").addClassName("field-size7");
$("t56_histor").setAttribute("rel","ignore-css");
$("t56_histor").style.width = "100%";
</script>