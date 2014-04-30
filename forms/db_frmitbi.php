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

//MODULO: itbI
include("classes/db_caracter_classe.php");
$clcaracter = new cl_caracter;
$clitbi->rotulo->label();
$clitburbano->rotulo->label();
$clitbirural->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("it04_descr");
$clrotulo->label("it07_descr");
$clrotulo->label("j31_codigo");
$clrotulo->label("it19_codigo");
$clrotulo->label("it19_valor");
?>
<form name="form1" method="post" action="">
<input type="hidden" name="tipo" value="<?=@$tipo?>">
<input type="hidden" name="j01_matric" value="<?=@$j01_matric?>">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tit01_guia?>">
       <?=@$Lit01_guia?>
    </td>
    <td> 
	  <?
	  
		db_input('it01_guia',15,$Iit01_areaterreno,true,'text',3,"");
		
		if(isset($j01_matric) && $j01_matric != ""){
			
		  include("classes/db_iptubase_classe.php");
		  $cliptubase = new cl_iptubase;
		  $result = $cliptubase->sql_record($cliptubase->sql_query($j01_matric));
		  
		  if($cliptubase->numrows > 0){
		    db_fieldsmemory($result,0);
		    include("classes/db_cgm_classe.php");
		    $clcgm = new cl_cgm;
		    $result = $clcgm->sql_record($clcgm->sql_query($j01_numcgm));
		    if($clcgm->numrows > 0){
		      db_fieldsmemory($result,0);
		    }
		  }else{
		    echo "<script>
		           alert('Matrícula $j01_matric inválida');
		           parent.iframe_itbi.location.href = 'itb1_itbi001.php?abas=1&tipo=$tipo';       
			  </script>";
		  }
		  echo "<a style='text-decoration:none;color:#6699cc;background-color:yellow' onMouseOver='this.style.color=\"blue\"' onMouseOut='this.style.color=\"#6699cc\"' onClick=\"js_abre('cad3_conscadastro_002.php?cod_matricula=$j01_matric');return false\" href=''>matrícula: ".$j01_matric." &nbsp;|&nbsp;".@$z01_nome."</a>";
		}
	  ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tit01_tipotransacao?>">
      <?
        db_ancora(@$Lit01_tipotransacao,"js_pesquisait01_tipotransacao(true);",$db_opcao);
        db_input('it01_guia',40,$Iit04_descr,true,'hidden',3,'');
      ?>
    </td>
    <td> 
	   <?
		 db_input('it01_tipotransacao',15,$Iit01_tipotransacao,true,'text',$db_opcao," onchange='js_pesquisait01_tipotransacao(false);'");
		 db_input('it04_descr',40,$Iit04_descr,true,'text',3,'');
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tit01_areaterreno?>">
       <?=@$Lit01_areaterreno?>
    </td>
    <td> 
	  <?
		db_input('it01_areaterreno',15,$Iit01_areaterreno,true,'text',$db_opcao," onChange='document.form1.it01_areatrans.value=this.value'");
	  ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tit01_areaedificada?>">
      <?=@$Lit01_areaedificada?>
    </td>
    <td> 
	  <?
		if (!(isset($it01_areaedificada))) {
			$it01_areaedificada = 0;
		}
		db_input('it01_areaedificada',15,$Iit01_areaedificada,true,'text',$db_opcao," onChange='parent.iframe_constr.document.form1.areatot.value = this.value'");
	  ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tit01_obs?>">
      <?=@$Lit01_obs?>
    </td>
    <td> 
	  <?
		db_textarea('it01_obs',10,120,$Iit01_obs,true,'text',$db_opcao,"");
	  ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tit01_valortransacao?>">
      <?=@$Lit01_valortransacao?>
    </td>
    <td> 
	  <?
		db_input('it01_valortransacao',15,$Iit01_valortransacao,true,'text',$db_opcao,"");
	  ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tit01_areatrans?>">
       <?=@$Lit01_areatrans?>
    </td>
    <td> 
	  <?
		db_input('it01_areatrans',15,$Iit01_areatrans,true,'text',$db_opcao,"");
	  ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tit01_mail?>">
      <?=@$Lit01_mail?>
    </td>
    <td> 
	  <?
		db_input('it01_mail',50,$Iit01_mail,true,'text',$db_opcao,"");
	  ?>
    </td>
  </tr>
  <?
  if($tipo == "urbano"){
  ?>
  <tr>
    <td nowrap title="<?=@$Tit05_frente?>">
      <?=@$Lit05_frente?>
    </td>
    <td> 
	  <?
		db_input('it05_frente',15,$Iit05_frente,true,'text',$db_opcao,"");
	  ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tit05_fundos?>">
      <?=@$Lit05_fundos?>
    </td>
    <td> 
	  <?
		db_input('it05_fundos',15,$Iit05_fundos,true,'text',$db_opcao,"");
	  ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tit05_direito?>">
      <?=@$Lit05_direito?>
    </td>
    <td> 
	  <?
		db_input('it05_direito',15,$Iit05_direito,true,'text',$db_opcao,"");
	  ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tit05_esquerdo?>">
      <?=@$Lit05_esquerdo?>
    </td>
    <td> 
	  <?
		db_input('it05_esquerdo',15,$Iit05_esquerdo,true,'text',$db_opcao,"");
	  ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tit05_itbisituacao?>">
      <?
        db_ancora(@$Lit05_itbisituacao,"js_pesquisait05_itbisituacao(true);",$db_opcao);
      ?>
    </td>
    <td> 
	  <?
		db_input('it05_itbisituacao',15,$Iit05_itbisituacao,true,'text',$db_opcao," onchange='js_pesquisait05_itbisituacao(false);'");
		db_input('it07_descr',40,$Iit07_descr,true,'text',3,'');
      ?>
    </td>
  </tr>
  <?
  } else if ($tipo == "rural") {
  ?>
  <tr>
    <td>
  	 <?
		db_input('it18_guia',10,$Iit18_guia,true,'hidden',$db_opcao," onchange='js_pesquisait18_guia(false);'");
  	 ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tit18_frente?>">
      <?=@$Lit18_frente?>
    </td>
    <td> 
	  <?
		db_input('it18_frente',20,$Iit18_frente,true,'text',$db_opcao,"");
	  ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tit18_fundos?>">
      <?=@$Lit18_fundos?>
    </td>
    <td> 
	  <?
		db_input('it18_fundos',20,$Iit18_fundos,true,'text',$db_opcao,"")
	  ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tit18_prof?>">
      <?=@$Lit18_prof?>
    </td>
    <td> 
	  <?
		db_input('it18_prof',20,$Iit18_prof,true,'text',$db_opcao,"")
	  ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tit19_codigo?>" colspan="2">
       <?
       db_ancora(@$Lj31_codigo,"js_caract();",$db_opcao);
       db_input('codigo',20,@$Icodigo,true,'hidden',$db_opcao,"");
       db_input('valor',20,$Iit19_valor,true,'hidden',$db_opcao,"");
       ?>
    </td>
  </tr>
  <?
  }
  ?>
  </table>
  </center>
<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> onclick="return testacaract()" >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function testacaract(){
  if(document.form1.valor.value == "" || document.form1.codigo.value == ""){
    alert('Escolha as características antes de prosseguir!')
    js_caract()
    return false;
  }else{
    return true;
  }
return false;  
}
function js_abre(pagina){
  js_OpenJanelaIframe('','db_iframe_consulta',pagina,'Pesquisa',true,0);
}
function js_caract(){
  js_OpenJanelaIframe('','db_iframe_caract','itb1_itbiruralcaract002.php?guia=<?=@$it01_guia?>','Pesquisa',true,0);
}
function js_fecha(){
  db_iframe_caract.hide();
}
function js_pesquisait05_itbisituacao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_itbi','db_iframe_itbisituacao','func_itbisituacao.php?funcao_js=parent.js_mostraitbisituacao1|it07_codigo|it07_descr','Pesquisa',true);
  }else{
     if(document.form1.it05_itbisituacao.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_itbi','db_iframe_itbisituacao','func_itbisituacao.php?pesquisa_chave='+document.form1.it05_itbisituacao.value+'&funcao_js=parent.js_mostraitbisituacao','Pesquisa',false);
     }else{
       document.form1.it07_descr.value = ''; 
     }
  }
}
function js_mostraitbisituacao(chave,erro){
  document.form1.it07_descr.value = chave; 
  if(erro==true){ 
    document.form1.it05_itbisituacao.focus(); 
    document.form1.it05_itbisituacao.value = ''; 
  }
}
function js_mostraitbisituacao1(chave1,chave2){
  document.form1.it05_itbisituacao.value = chave1;
  document.form1.it07_descr.value = chave2;
  db_iframe_itbisituacao.hide();
}
function js_pesquisait01_tipotransacao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_itbi','db_iframe_itbitransacao','func_itbitransacao.php?funcao_js=parent.js_mostraitbitransacao1|it04_codigo|it04_descr','Pesquisa',true);
  }else{
     if(document.form1.it01_tipotransacao.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_itbi','db_iframe_itbitransacao','func_itbitransacao.php?pesquisa_chave='+document.form1.it01_tipotransacao.value+'&funcao_js=parent.js_mostraitbitransacao','Pesquisa',false);
     }else{
       document.form1.it04_descr.value = ''; 
     }
  }
}
function js_mostraitbitransacao(chave,erro){
  document.form1.it04_descr.value = chave; 
  if(erro==true){ 
    document.form1.it01_tipotransacao.focus(); 
    document.form1.it01_tipotransacao.value = ''; 
  }
}
function js_mostraitbitransacao1(chave1,chave2){
  document.form1.it01_tipotransacao.value = chave1;
  document.form1.it04_descr.value = chave2;
  db_iframe_itbitransacao.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo.iframe_itbi','db_iframe_itbi','func_itbinaocancelado.php?funcao_js=parent.js_preenchepesquisa|it01_guia','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_itbi.hide();
  <?
    if($db_opcao == 2 || $db_opcao == 22){
      echo " location.href = 'itb1_itbi002.php?abas=1&chavepesquisa='+chave+'&tipo=$tipo';";
    }elseif($db_opcao == 33 || $db_opcao == 3){
      echo " location.href = 'itb1_itbi003.php?abas=1&chavepesquisa='+chave+'&tipo=$tipo';";
    }
  ?>
}
<?
if(isset($it01_guia)){
?>
document.form1.it18_guia.value = '<?=@$it01_guia?>';
<?
}
?>
</script>