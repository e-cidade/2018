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

//MODULO: cadastro
$clruas->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("j29_cep");

?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tj14_codigo?>">
       <?=@$Lj14_codigo?>
    </td>
    <td> 
		<?
			db_input('db_teste',7,'db_teste',true,'hidden',"","")
		?>
		<?
			db_input('j14_codigo',7,$Ij14_codigo,true,'text',$db_codopcao,"");
			if (isset($mostrabotao) && $mostrabotao == 't'){
				echo '<input name="db_procproximo" type="submit" id="db_proc" value="Procura Próximo">';
			}
			db_input('clicou',7,'',true,'hidden',"","");
		?>
		
   		 
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj14_nome?>">
       <?=@$Lj14_nome?>
    </td>
    <td> 
		<?
			db_input('j14_nome',40,$Ij14_nome,true,'text',$db_opcao,"")
		?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj14_tipo?>">
       <?=@$Lj14_tipo?>
    </td>
    <td> 
	<?

    $rsConsultaRuasTp = $clruastipo->sql_record($clruastipo->sql_query_file(null,"j88_codigo,j88_descricao"));
    db_selectrecord("j14_tipo",$rsConsultaRuasTp,$Ij14_tipo,$db_opcao,"");    
	
	?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj14_rural?>">
       <?=@$Lj14_rural?>
    </td>
    <td> 
		<?
			$x = array("f"=>"NAO","t"=>"SIM");
			db_select('j14_rural',$x,true,$db_opcao,"");
		?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj29_cep?>">
      <?=@$Lj29_cep?>
    </td>
    <td>
	<?
	  db_input('j29_cep',8,$Ij29_cep,true,'text',$db_opcao,"")
	?>
   </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj14_lei?>">
      <?=@$Lj14_lei?>
    </td>
    <td>
	<?
	  db_input('j14_lei',30,$Ij14_lei,true,'text',$db_opcao,"")
	?>
   </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj14_dtlei?>">
      <?=@$Lj14_dtlei?>
    </td>
    <td>
	<?
//	if (isset($j14_dtlei)&&$j14_dtlei!=""){
//		echo "ano $j14_dtlei <br> ";
//		$j14_dtlei_ano=substr($j14_dtlei,0,4);
//		echo "ano $j14_dtlei_ano <br> ";
//		$j14_dtlei_mes=substr($j14_dtlei,5,2);
//		echo "mes $j14_dtlei_mes <br> ";
//		$j14_dtlei_dia=substr($j14_dtlei,8,10);
//		echo "dia $j14_dtlei_dia <br> ";
//	}
	  db_inputdata('j14_dtlei',@$j14_dtlei_dia,@$j14_dtlei_mes,@$j14_dtlei_ano,$Ij14_dtlei,true,'text',$db_opcao,"")
	?>
   </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj14_obs?>">
      <?=@$Lj14_obs ?>
    </td>
    <td>
     <?
      db_textarea('j14_obs',5,60,$Ij14_obs,true,'text',$db_opcao,"");
     ?>
    </td>
  </tr>
  </table>
  </center>
<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >

<? if(($db_opcao == 3) or ($db_opcao == 33)){  
     echo '<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa1();" >';
 }else{ 
     echo ' <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >';
 } ?>
</form>
<script>
function js_pesquisa(){
  js_OpenJanelaIframe('parent.iframe_g1','db_iframe_ruas','func_ruas.php?rural=1&funcao_js=parent.js_preenchepesquisa|j14_codigo','Pesquisa',true,0);
}
function js_preenchepesquisa(chave){
  db_iframe_ruas.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave;";
  }
  ?>
   
}

function js_pesquisa1(){
    js_OpenJanelaIframe('top.corpo','db_iframe_ruas','func_ruas.php?rural=1&funcao_js=parent.js_preenchepesquisa1|j14_codigo','Pesquisa',true);
    
}
function js_preenchepesquisa1(chave){
    db_iframe_ruas.hide();
      <?
        if($db_opcao!=1){
	      echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave;";
	        }
		  ?>
}

</script>