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

 //MODULO: arrecadacao

 $clcadarrecadacao->rotulo->label();
 $clrotulo = new rotulocampo;
 $clrotulo->label("nomeinst");

?>
<center>
<form class="container" name="form1" method="post" action="">
    <fieldset>
      <legend>Configuração de Convenio Arrecadação</legend>
    <table class="form-container">
	  <tr>
	    <td nowrap title="<?=@$Tar16_instit?>">
	      <?=@$Lar16_instit?>
	    </td>
	    <td> 
		  <?
			db_input('ar16_sequencial',10,$Iar16_sequencial,true,'hidden',$db_opcao,"");
			db_input('ar16_instit',10,$Iar16_instit,true,'text',3," onchange='js_pesquisaar16_instit(false);'");
			db_input('nomeinst'	  ,40,$Inomeinst   ,true,'text',3,'');
	      ?>
	    </td>
	  </tr>
	  <tr>
	    <td nowrap title="<?=@$Tar16_convenio?>">
          <?=@$Lar16_convenio?>
	    </td>
	    <td> 
	 	  <?
			db_input('ar16_convenio',4,$Iar16_convenio,true,'text',$db_opcao,"");
		  ?>
	    </td>
	  </tr>
	  <tr>
	    <td nowrap title="<?=@$Tar16_segmento?>">
	      <?=@$Lar16_segmento?>
	    </td>
	    <td> 
		  <?
		    $aSegmento = array( "1"=>"1-Prefeituras",
		    				    "2"=>"2-Saneamento",
		    					"3"=>"3-Energia Elétrica e Gás",
		    					"4"=>"4-Telecomunicações",
		    					"5"=>"5-Órgãos Governamentais",
		    					"6"=>"6-Carnes e Assemelhados ou demais Empresas",
		    					"7"=>"7-Multas de trânsito",
		    					"9"=>"9-Uso exclusivo do banco");
		    
		    db_select("ar16_segmento",$aSegmento,true,$db_opcao,"");
		  ?>
	    </td>
	  </tr>
	  <tr>
	    <td nowrap title="<?=@$Tar16_formatovenc?>">
	     <?=@$Lar16_formatovenc?>
	    </td>
	    <td>
		 <?
 	       $aFormVenc = array("1"=>"1-aaaa/mm/dd","2"=>"2-dd/mm/aa");
 	       db_select("ar16_formatovenc",$aFormVenc,true,$db_opcao,"");
		 ?>
	    </td>
 	  </tr>
    </table>
    </fieldset>
    <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
</form>
  </center>
<script>
function js_pesquisaar16_instit(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_config','func_db_config.php?funcao_js=parent.js_mostradb_config1|codigo|nomeinst','Pesquisa',true);
  }else{
     if(document.form1.ar16_instit.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_db_config','func_db_config.php?pesquisa_chave='+document.form1.ar16_instit.value+'&funcao_js=parent.js_mostradb_config','Pesquisa',false);
     }else{
       document.form1.nomeinst.value = ''; 
     }
  }
}
function js_mostradb_config(chave,erro){
  document.form1.nomeinst.value = chave; 
  if(erro==true){ 
    document.form1.ar16_instit.focus(); 
    document.form1.ar16_instit.value = ''; 
  }
}
function js_mostradb_config1(chave1,chave2){
  document.form1.ar16_instit.value = chave1;
  document.form1.nomeinst.value = chave2;
  db_iframe_db_config.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_cadarrecadacao','func_cadarrecadacao.php?funcao_js=parent.js_preenchepesquisa|ar16_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_cadarrecadacao.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>
<script>

$("ar16_instit").addClassName("field-size2");
$("nomeinst").addClassName("field-size7");
$("ar16_convenio").addClassName("field-size2");

</script>