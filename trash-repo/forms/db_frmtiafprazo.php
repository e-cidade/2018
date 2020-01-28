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

//MODULO: fiscal
$cltiafprazo->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("y90_codtiaf");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Ty96_codigo?>">
       <?=@$Ly96_codigo?>
    </td>
    <td> 
		<?
			db_input('y96_codigo',10,$Iy96_codigo,true,'text',$db_opcao,"")
		?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty96_codtiaf?>">
       <?
    	   db_ancora(@$Ly96_codtiaf,"js_pesquisay96_codtiaf(true);",$db_opcao);
       ?>
    </td>
    <td> 
		<?
			db_input('y96_codtiaf',10,$Iy96_codtiaf,true,'text',$db_opcao," onchange='js_pesquisay96_codtiaf(false);'")
		?>
       <?
			db_input('y90_codtiaf',10,$Iy90_codtiaf,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty96_prazo?>">
       <?=@$Ly96_prazo?>
    </td>
	<td> 
	<?
		db_inputdata('y96_prazo',@$y96_prazo_dia,@$y96_prazo_mes,@$y96_prazo_ano,true,'text',$db_opcao,"")
	?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisay96_codtiaf(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_tiaf','func_tiaf.php?funcao_js=parent.js_mostratiaf1|y90_codtiaf|y90_codtiaf','Pesquisa',true);
  }else{
     if(document.form1.y96_codtiaf.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_tiaf','func_tiaf.php?pesquisa_chave='+document.form1.y96_codtiaf.value+'&funcao_js=parent.js_mostratiaf','Pesquisa',false);
     }else{
       document.form1.y90_codtiaf.value = ''; 
     }
  }
}
function js_mostratiaf(chave,erro){
  document.form1.y90_codtiaf.value = chave; 
  if(erro==true){ 
    document.form1.y96_codtiaf.focus(); 
    document.form1.y96_codtiaf.value = ''; 
  }
}
function js_mostratiaf1(chave1,chave2){
  document.form1.y96_codtiaf.value = chave1;
  document.form1.y90_codtiaf.value = chave2;
  db_iframe_tiaf.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_tiafprazo','func_tiafprazo.php?funcao_js=parent.js_preenchepesquisa|y96_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_tiafprazo.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>