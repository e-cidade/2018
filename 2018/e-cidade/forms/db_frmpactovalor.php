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

//MODULO: orcamento
$clpactovalor->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("o31_descricao");
$clrotulo->label("o79_descricao");
$clrotulo->label("o109_descricao");
$clrotulo->label("o74_descricao");
$clrotulo->label("o107_descricao");
$clrotulo->label("o104_descricao");
$clrotulo->label("o55_descr");
$o17_descricao = @$o54_descr;
?>
<form name="form1" method="post" action="">
<center>
<table>
  <tr>
    <td>
		  <fieldset>
		    <legend>
		      <b>Valores do Pacto</b>
		    </legend>
		    <table border="0">
				  <tr>
				    <td  width="110px;" nowrap title="<?=@$To87_sequencial?>">
				      <?=@$Lo87_sequencial?>
				    </td>
				    <td> 
							<?
							  db_input('o87_sequencial',10,$Io87_sequencial,true,'text',3,"");
							?>
				    </td>
				  </tr>
				  <tr>
				    <td nowrap title="<?=@$To87_pactoplano?>">
				      <?
				        db_ancora(@$Lo87_pactoplano,"js_pesquisao87_pactoplano(true);",$db_opcao);
				      ?>
				    </td>
				    <td> 
							<?
						  	db_input('o87_pactoplano',10,$Io87_pactoplano,true,'text',$db_opcao," onchange='js_pesquisao87_pactoplano(false);'");
				        db_input('o74_descricao' ,50,"",true,'text',3,'');
				      ?>
				    </td>
				  </tr>
				  <tr>
				    <td nowrap title="<?=@$To87_pactoprograma?>">
				      <?
				        db_ancora(@$Lo87_pactoprograma,"js_pesquisao87_pactoprograma(true);",$db_opcao);
				      ?>
				    </td>
				    <td> 
							<?
							  db_input('o87_pactoprograma',10,$Io87_pactoprograma,true,'text',$db_opcao," onchange='js_pesquisao87_pactoprograma(false);'");
				        db_input('o107_descricao',50,$Io107_descricao,true,'text',3,'');
				      ?>
				    </td>
				  </tr>
				  <tr>
				    <td nowrap title="<?=@$To87_orcprojativativprojeto?>">
				      <?
				        db_ancora(@$Lo87_orcprojativativprojeto,"js_pesquisao87_orcprojativativprojeto(true);",$db_opcao);
				      ?>
				    </td>
				    <td> 
							<?
							  db_input('o87_orcprojativativprojeto',10,$Io87_orcprojativativprojeto,true,'text',$db_opcao," onchange='js_pesquisao87_orcprojativativprojeto(false);'");
				        db_input('o55_descr',50,$Io55_descr,true,'text',3,'');
						  	db_input('o87_orcprojativanoprojeto',4,"",true,'hidden',$db_opcao);
				      ?>
				    </td>
				  </tr>
				  <tr>
				    <td nowrap title="<?=@$To87_pactoatividade?>">
				      <?
				        db_ancora(@$Lo87_pactoatividade,"js_pesquisao87_pactoatividade(true);",$db_opcao);
				      ?>
				    </td>
				    <td> 
							<?
						  	db_input('o87_pactoatividade',10,$Io87_pactoatividade,true,'text',$db_opcao," onchange='js_pesquisao87_pactoatividade(false);'");
				  			db_input('o104_descricao',50,$Io104_descricao,true,'text',3,'');
					    ?>
				    </td>
				  </tr>
				  <tr>
				    <td nowrap title="<?=@$To87_pactoacoes?>">
				      <?
				        db_ancora(@$Lo87_pactoacoes,"js_pesquisao87_pactoacoes(true);",$db_opcao);
				      ?>
				    </td>
				    <td> 
							<?
						  	db_input('o87_pactoacoes',10,$Io87_pactoacoes,true,'text',$db_opcao," onchange='js_pesquisao87_pactoacoes(false);'");
					  		db_input('o79_descricao',50,$Io79_descricao,true,'text',3,'');
					    ?>
				    </td>
				  </tr>
				  <tr>
				    <td nowrap title="<?=@$To87_categoriapacto?>">
				      <?
				        db_ancora(@$Lo87_categoriapacto,"js_pesquisao87_categoriapacto(true);",$db_opcao);
				      ?>
				    </td>
				    <td> 
							<?
							  db_input('o87_categoriapacto',10,$Io87_categoriapacto,true,'text',$db_opcao," onchange='js_pesquisao87_categoriapacto(false);'");
				  			db_input('o31_descricao',50,$Io31_descricao,true,'text',3,'');
					    ?>
				    </td>
				  </tr>
				  <tr>
				    <td nowrap title="<?=@$To87_pactoitem?>">
				      <?
				        db_ancora(@$Lo87_pactoitem,"js_pesquisao87_pactoitem(true);",$db_opcao);
				      ?>
				    </td>
				    <td> 
							<?
								db_input('o87_pactoitem',10,$Io87_pactoitem,true,'text',$db_opcao," onchange='js_pesquisao87_pactoitem(false);'");
								db_input('o109_descricao',50,$Io109_descricao,true,'text',3,'');
					    ?>
				    </td>
				  </tr>
				  <tr>
				    <td nowrap title="<?=@$To87_quantidade?>">
				      <?=@$Lo87_quantidade?>
				    </td>
				    <td> 
							<?
						  	db_input('o87_quantidade',10,$Io87_quantidade,true,'text',$db_opcao,"");
							?>
				    </td>
				  </tr>
				  <tr>
				    <td nowrap title="<?=@$To87_vlraproximado?>">
				      <?=@$Lo87_vlraproximado?>
				    </td>
				    <td> 
							<?
						  	db_input('o87_vlraproximado',10,$Io87_vlraproximado,true,'text',$db_opcao,"");
							?>
				    </td>
				  </tr>
		    </table>
		  </fieldset>
	  </td>
	</tr>
</table>        
</center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<? if ( $db_opcao != 1 ) { ?>
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa(); ">
<? } ?>
</form>
<script>


function js_pesquisao87_categoriapacto(mostra){

  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_categoriapacto','func_categoriapacto.php?funcao_js=parent.js_mostracategoriapacto1|o31_sequencial|o31_descricao','Pesquisa',true);
  }else{
     if(document.form1.o87_categoriapacto.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_categoriapacto','func_categoriapacto.php?pesquisa_chave='+document.form1.o87_categoriapacto.value+'&funcao_js=parent.js_mostracategoriapacto','Pesquisa',false);
     }else{
       document.form1.o31_descricao.value = ''; 
     }
  }
}
function js_mostracategoriapacto(chave,erro){
  document.form1.o31_descricao.value = chave; 
  if(erro==true){ 
    document.form1.o87_categoriapacto.focus(); 
    document.form1.o87_categoriapacto.value = ''; 
  }
}
function js_mostracategoriapacto1(chave1,chave2){
  document.form1.o87_categoriapacto.value = chave1;
  document.form1.o31_descricao.value = chave2;
  db_iframe_categoriapacto.hide();
}
function js_pesquisao87_pactoacoes(mostra){
  
  if ( document.form1.o87_pactoplano.value == "" ) {
    alert("Preecher código do plano!");
    document.form1.o87_pactoacoes.value = ''; 
    document.form1.o79_descricao.value  = '';
    return false;
  }
  
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_pactoacoes','func_pactoacoes.php?plano='+document.form1.o87_pactoplano.value+'&funcao_js=parent.js_mostrapactoacoes1|o79_sequencial|o79_descricao','Pesquisa',true);
  }else{
     if(document.form1.o87_pactoacoes.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_pactoacoes','func_pactoacoes.php?plano='+document.form1.o87_pactoplano.value+'&pesquisa_chave='+document.form1.o87_pactoacoes.value+'&funcao_js=parent.js_mostrapactoacoes','Pesquisa',false);
     }else{
       document.form1.o79_descricao.value = ''; 
     }
  }
}
function js_mostrapactoacoes(chave,erro){
  document.form1.o79_descricao.value = chave; 
  if(erro==true){ 
    document.form1.o87_pactoacoes.focus(); 
    document.form1.o87_pactoacoes.value = ''; 
  }
}
function js_mostrapactoacoes1(chave1,chave2){
  document.form1.o87_pactoacoes.value = chave1;
  document.form1.o79_descricao.value = chave2;
  db_iframe_pactoacoes.hide();
}
function js_pesquisao87_pactoitem(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_pactoitem','func_pactoitem.php?funcao_js=parent.js_mostrapactoitem1|o109_sequencial|o109_descricao','Pesquisa',true);
  }else{
     if(document.form1.o87_pactoitem.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_pactoitem','func_pactoitem.php?pesquisa_chave='+document.form1.o87_pactoitem.value+'&funcao_js=parent.js_mostrapactoitem','Pesquisa',false);
     }else{
       document.form1.o109_descricao.value = ''; 
     }
  }
}
function js_mostrapactoitem(chave,erro){
  document.form1.o109_descricao.value = chave; 
  if(erro==true){ 
    document.form1.o87_pactoitem.focus(); 
    document.form1.o87_pactoitem.value = ''; 
  }
}
function js_mostrapactoitem1(chave1,chave2){
  document.form1.o87_pactoitem.value = chave1;
  document.form1.o109_descricao.value = chave2;
  db_iframe_pactoitem.hide();
}
function js_pesquisao87_pactoplano(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_pactoplano','func_pactoplano.php?funcao_js=parent.js_mostrapactoplano1|o74_sequencial|o74_descricao','Pesquisa',true);
  }else{
     if(document.form1.o87_pactoplano.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_pactoplano','func_pactoplano.php?pesquisa_chave='+document.form1.o87_pactoplano.value+'&funcao_js=parent.js_mostrapactoplano','Pesquisa',false);
     }else{
       document.form1.o74_descricao.value = ''; 
     }
  }
}
function js_mostrapactoplano(chave,erro){
  document.form1.o74_descricao.value = chave; 
  if(erro==true){ 
    document.form1.o87_pactoplano.focus(); 
    document.form1.o87_pactoplano.value = ''; 
  }
}
function js_mostrapactoplano1(chave1,chave2){
  document.form1.o87_pactoplano.value = chave1;
  document.form1.o74_descricao.value = chave2;
  db_iframe_pactoplano.hide();
}
function js_pesquisao87_pactoprograma(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_pactoprograma',
                        'func_orcprograma.php?funcao_js=parent.js_mostrapactoprograma1|o54_programa|o54_descr',
                        'Pesquisa',true);
  }else{
     if(document.form1.o87_pactoprograma.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_pactoprograma',
                            'func_orcprograma.php?pesquisa_chave='+document.form1.o87_pactoprograma.value+
                            '&funcao_js=parent.js_mostrapactoprograma','Pesquisa',false);
     }else{
       document.form1.o107_descricao.value = ''; 
     }
  }
}
function js_mostrapactoprograma(chave,erro){
  document.form1.o107_descricao.value = chave; 
  if(erro==true){ 
    document.form1.o87_pactoprograma.focus(); 
    document.form1.o87_pactoprograma.value = ''; 
  }
}
function js_mostrapactoprograma1(chave1,chave2){
  document.form1.o87_pactoprograma.value = chave1;
  document.form1.o107_descricao.value = chave2;
  db_iframe_pactoprograma.hide();
}
function js_pesquisao87_pactoatividade(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_pactoatividade','func_pactoatividade.php?funcao_js=parent.js_mostrapactoatividade1|o104_sequencial|o104_descricao','Pesquisa',true);
  }else{
     if(document.form1.o87_pactoatividade.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_pactoatividade','func_pactoatividade.php?pesquisa_chave='+document.form1.o87_pactoatividade.value+'&funcao_js=parent.js_mostrapactoatividade','Pesquisa',false);
     }else{
       document.form1.o104_descricao.value = ''; 
     }
  }
}
function js_mostrapactoatividade(chave,erro){
  document.form1.o104_descricao.value = chave; 
  if(erro==true){ 
    document.form1.o87_pactoatividade.focus(); 
    document.form1.o87_pactoatividade.value = ''; 
  }
}
function js_mostrapactoatividade1(chave1,chave2){
  document.form1.o87_pactoatividade.value = chave1;
  document.form1.o104_descricao.value = chave2;
  db_iframe_pactoatividade.hide();
}
function js_pesquisao87_orcprojativativprojeto(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_orcprojativ','func_orcprojativano.php?o55_tipo=1&funcao_js=parent.js_mostraorcprojativ1|o55_projativ|o55_anousu|o55_descr','Pesquisa',true);
  }else{
     if(document.form1.o87_orcprojativativprojeto.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_orcprojativ','func_orcprojativano.php?o55_tipo=1&pesquisa_chave='+document.form1.o87_orcprojativativprojeto.value+'&funcao_js=parent.js_mostraorcprojativ','Pesquisa',false);
     }else{
       document.form1.o55_descr.value = ''; 
       document.form1.o87_orcprojativanoprojeto.value = '';
     }
  }
}
function js_mostraorcprojativ(chave,chave1,erro){
  document.form1.o55_descr.value                  = chave; 
  document.form1.o87_orcprojativanoprojeto.value  = chave1;
  if(erro==true){ 
    document.form1.o87_orcprojativativprojeto.focus(); 
    document.form1.o87_orcprojativativprojeto.value = ''; 
  }
}
function js_mostraorcprojativ1(chave1,chave2,chave3){
  document.form1.o87_orcprojativativprojeto.value = chave1;
  document.form1.o87_orcprojativanoprojeto.value  = chave2;
  document.form1.o55_descr.value                  = chave3;
  db_iframe_orcprojativ.hide();
}

function js_pesquisa(){
  js_OpenJanelaIframe('','db_iframe_pactovalor','func_pactovalor.php?funcao_js=parent.js_preenchepesquisa|o87_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_pactovalor.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>