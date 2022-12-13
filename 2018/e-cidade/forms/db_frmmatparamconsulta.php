<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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

//MODULO: material
$clmatparamconsulta->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("nomeinst");
?>

<fieldset style="width:760px; margin-top: 50px;">
<legend>Manutenção de Parâmetros - Consultas</legend>
<form name="form1" method="post" action="">
<table border="0" align="left">
	
  <tr>
    <td> 
			<?
				//db_input('m38_instit',10,$Im38_instit,true,'text',$db_opcao," onchange='js_pesquisam38_instit(false);'");
      ?>
    </td>
  </tr>
	
  <tr>
    <td nowrap title="<?=@$Tm38_visualizacaoitens?>">
       <?=@$Lm38_visualizacaoitens?>
    </td>
    <td> 
		<?
			$x = array('1'=>'Todas as instituições visualizam tudo na consulta de material','2'=>'Visualizar apenas itens com movimentação na própria instituição');
			db_select('m38_visualizacaoitens',$x,true,$db_opcao,"");
		?>
    </td>
  </tr>
	
  <tr>
    <td nowrap title="<?=@$Tm38_visualizacaomatestoque?>">
       <?=@$Lm38_visualizacaomatestoque?>
    </td>
    <td> 
		<?
			$x = array('f'=>'Não','t'=>'Sim');
			db_select('m38_visualizacaomatestoque',$x,true,$db_opcao,"");
		?>
    </td>
  </tr>
	
  </table>
	</fieldset>
	<center>
		<input name="alterar" type="submit" id="db_opcao" value="Alterar" style="margin-top: 10px;" />
	</center>
</form>
<script>
function js_pesquisam38_instit(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_config','func_db_config.php?funcao_js=parent.js_mostradb_config1|codigo|nomeinst','Pesquisa',true);
  }else{
     if(document.form1.m38_instit.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_db_config','func_db_config.php?pesquisa_chave='+document.form1.m38_instit.value+'&funcao_js=parent.js_mostradb_config','Pesquisa',false);
     }else{
       document.form1.nomeinst.value = ''; 
     }
  }
}
function js_mostradb_config(chave,erro){
  document.form1.nomeinst.value = chave; 
  if(erro==true){ 
    document.form1.m38_instit.focus(); 
    document.form1.m38_instit.value = ''; 
  }
}
function js_mostradb_config1(chave1,chave2){
  document.form1.m38_instit.value = chave1;
  document.form1.nomeinst.value = chave2;
  db_iframe_db_config.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_matparamconsulta','func_matparamconsulta.php?funcao_js=parent.js_preenchepesquisa|m38_instit','Pesquisa',false);
}
function js_preenchepesquisa(chave){
  db_iframe_matparamconsulta.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>