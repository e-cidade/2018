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

//MODULO: Habitacao
$clHabitPrograma->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ht03_descricao");
$clrotulo->label("ht19_numcgm");

if( $db_opcao == 1 ) {
  $db_action ="hab1_habitprograma004.php";
} else if( $db_opcao == 2 || $db_opcao == 22 ){
 	$db_action ="hab1_habitprograma005.php";
} else if( $db_opcao == 3 || $db_opcao == 33 ){
  $db_action ="hab1_habitprograma006.php";
}  
?>
<form name="form1" method="post" action="<?=$db_action?>"  onSubmit="return js_validaSubmit();">
  <fieldset>
    <legend>
      <b>Dados Programa</b>
    </legend>
		<table border="0">
		  <tr>
		    <td nowrap title="<?=@$Tht01_sequencial?>">
		      <?=@$Lht01_sequencial?>
		    </td>
		    <td> 
					<?
					  db_input('ht01_sequencial',10,$Iht01_sequencial,true,'text',3,"");
					?>
		    </td>
		  </tr>
		  <tr>
		    <td nowrap title="<?=@$Tht01_habitgrupoprograma?>">
		      <?
		        db_ancora(@$Lht01_habitgrupoprograma,"js_pesquisaht01_habitgrupoprograma(true);",$db_opcao);
		      ?>
		    </td>
		    <td> 
					<?
						db_input('ht01_habitgrupoprograma',10,$Iht01_habitgrupoprograma,true,'text',$db_opcao," onchange='js_pesquisaht01_habitgrupoprograma(false);'");
						db_input('ht03_descricao',40,$Iht03_descricao,true,'text',3,'');
 	        ?>
		    </td>
		  </tr>
		  <tr>
		    <td nowrap title="<?=@$Tht01_descricao?>">
		      <?=@$Lht01_descricao?>
		    </td>
		    <td> 
					<?
					  db_input('ht01_descricao',53,$Iht01_descricao,true,'text',$db_opcao,"");
					?>
		    </td>
		  </tr>
		  <tr>
		    <td nowrap title="<?=@$Tht01_obs?>">
		      <?=@$Lht01_obs?>
		    </td>
		    <td> 
					<?
					  db_textarea('ht01_obs',2,51,$Iht01_obs,true,'text',$db_opcao,"");
					?>
		    </td>
		  </tr>
      <tr>
        <td>
          <b>
	          <?
	            db_ancora("Concedente:","js_pesquisaConcedente(true);",$db_opcao);
	          ?>
          </b>
        </td>
        <td> 
          <?
            db_input('ht19_numcgm',10,$Iht19_numcgm,true,'text',$db_opcao," onchange='js_pesquisaConcedente(false);'");
            db_input('z01_nome'   ,40,'',true,'text',3,'');
          ?>
        </td>
      </tr>		  
		  <tr>
		    <td nowrap title="<?=@$Tht01_controlemultpartcandidato?>">
		      <?=@$Lht01_controlemultpartcandidato?>
		    </td>
		    <td> 
					<?
						$x = array('1'=>'Sem Aviso e Sem Bloqueio',
						           '2'=>'Com Aviso e Sem bloqueio',
						           '3'=>'Com Aviso e Bloqueio');
						
						db_select('ht01_controlemultpartcandidato',$x,true,$db_opcao,"style='width:400px'");
					?>
		    </td>
		  </tr>
		  <tr>
		    <td nowrap title="<?=@$Tht01_controleqtd?>">
		      <?=@$Lht01_controleqtd?>
		    </td>
		    <td> 
					<?
						$x = array('1'=>'Sem Limite',
						           '2'=>'Máxima Determindada');
						db_select('ht01_controleqtd',$x,true,$db_opcao,"style='width:400px' onChange='js_verificaControleQtd()'");
					?>
		    </td>
		  </tr>
		  <?
        if (isset($ht01_controleqtd) && $ht01_controleqtd == 2) {
        	$sStyle = '';
        } else {
        	$sStyle = 'none';
        }
		  ?>
      <tr  id="qtdBenef" style="display:<?=$sStyle?>;">
        <td nowrap title="<?=@$Tht01_qtdbenef?>">
          <?=@$Lht01_qtdbenef?>
        </td>
        <td> 
          <?
            db_input('ht01_qtdbenef',10,$Iht01_qtdbenef,true,'text',$db_opcao,"");
          ?>
        </td>
      </tr>		  
		  <tr>
		    <td nowrap title="<?=@$Tht01_validadeini?>">
		      <b>Período Validade:</b>
		    </td>
		    <td> 
					<?
					  db_inputdata('ht01_validadeini',@$ht01_validadeini_dia,@$ht01_validadeini_mes,@$ht01_validadeini_ano,true,'text',$db_opcao,"");
					  echo "&nbsp;&nbsp;<b>Até<b>&nbsp;&nbsp;";
					  db_inputdata('ht01_validadefim',@$ht01_validadefim_dia,@$ht01_validadefim_mes,@$ht01_validadefim_ano,true,'text',$db_opcao,"");
					?>
		    </td>
		  </tr>
		  <tr>
		    <td nowrap title="<?=@$Tht01_descrcontrato?>">
		      <?=@$Lht01_descrcontrato?>
		    </td>
		    <td> 
					<?
					  db_textarea('ht01_descrcontrato',2,51,$Iht01_descrcontrato,true,'text',$db_opcao,"");
					?>
		    </td>
		  </tr>
		  <tr>
		    <td nowrap title="<?=@$Tht01_lei?>">
		      <?=@$Lht01_lei?>
		    </td>
		    <td> 
					<?
				  	db_input('ht01_lei',53,$Iht01_lei,true,'text',$db_opcao,"");
					?>
		    </td>
		  </tr>
		  <tr>
		    <td nowrap title="<?=@$Tht01_exigeassconcedente?>">
		      <?=@$Lht01_exigeassconcedente?>
		    </td>
		    <td> 
					<?
						$x = array("f"=>"NAO","t"=>"SIM");
						db_select('ht01_exigeassconcedente',$x,true,$db_opcao,"style='width:90px'");
	          
						echo "&nbsp;&nbsp;".@$Lht01_exigevalcpf."&nbsp;&nbsp;";					
            $x = array("f"=>"NAO","t"=>"SIM");
            db_select('ht01_exigevalcpf',$x,true,$db_opcao,"style='width:90px'");
					?>
		    </td>
		  </tr>
		  <tr>
		    <td nowrap title="<?=@$Tht01_qtdparcpagamento?>">
		      <?=@$Lht01_qtdparcpagamento?>
		    </td>
		    <td> 
					<?
					  db_input('ht01_qtdparcpagamento',10,$Iht01_qtdparcpagamento,true,'text',$db_opcao,"");
					?>
		    </td>
		  </tr>
		  <tr>
		    <td nowrap title="<?=@$Tht01_diapadraopagamento?>">
		      <?=@$Lht01_diapadraopagamento?>
		    </td>
		    <td> 
					<?
					  db_input('ht01_diapadraopagamento',10,$Iht01_diapadraopagamento,true,'text',$db_opcao,"");
					?>
		    </td>
		  </tr>
		  <tr>
		    <td nowrap title="<?=@$Tht01_receitapadraopagamento?>">
		      <?
		        db_ancora(@$Lht01_receitapadraopagamento,"js_pesquisaReceita(true);",$db_opcao);
	        ?>
		    </td>
		    <td> 
					<?
					  db_input('ht01_receitapadraopagamento',10,$Iht01_receitapadraopagamento,true,'text',$db_opcao,"onChange='js_pesquisaReceita(false);'");
					  db_input('k02_descr',40,'',true,'text',3,"");
					?>
		    </td>
		  </tr>
      <tr>
        <td nowrap title="<?=@$Tht01_workflow?>">
          <?
            db_ancora(@$Lht01_workflow,"js_pesquisaWorkFlow(true);",$db_opcao);
          ?>
        </td>
        <td> 
          <?
            db_input('ht01_workflow'  ,10,$Iht01_workflow,true,'text',$db_opcao,"onChange='js_pesquisaWorkFlow(false);'");
            db_input('db112_descricao',40,'',true,'text',3,"");
          ?>
        </td>
      </tr>      
	  </table>
  </fieldset>
  <center>
		<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
		<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
  </center>
</form>
<script>

function js_validaSubmit(){

  var iParamControle = document.form1.ht01_controleqtd.value;
  var iQtdBenef      = document.form1.ht01_qtdbenef.value;
  
  if (iParamControle == 2 && iQtdBenef == '' ) {
    alert('Quantidade de beneficiados não informado!');
    return false;  
  }
  
  return true;
  
}


function js_verificaControleQtd(){
  
  var iParamControle = document.form1.ht01_controleqtd.value;
  
  if (iParamControle == 2) {
    document.getElementById('qtdBenef').style.display = '';
  } else {
    document.getElementById('qtdBenef').style.display = 'none';
  }
}

function js_pesquisaht01_habitgrupoprograma(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_habitprograma','db_iframe_habitgrupoprograma','func_habitgrupoprograma.php?funcao_js=parent.js_mostrahabitgrupoprograma1|ht03_sequencial|ht03_descricao','Pesquisa',true);
  }else{
     if(document.form1.ht01_habitgrupoprograma.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_habitprograma',
                            'db_iframe_habitgrupoprograma',
                            'func_habitgrupoprograma.php?pesquisa_chave='+document.form1.ht01_habitgrupoprograma.value+'&funcao_js=parent.js_mostrahabitgrupoprograma',
                            'Pesquisa',false);
     }else{
       document.form1.ht03_descricao.value = ''; 
     }
  }
}
function js_mostrahabitgrupoprograma(chave,erro){
  document.form1.ht03_descricao.value = chave; 
  if(erro==true){ 
    document.form1.ht01_habitgrupoprograma.focus(); 
    document.form1.ht01_habitgrupoprograma.value = ''; 
  }
}
function js_mostrahabitgrupoprograma1(chave1,chave2){
  document.form1.ht01_habitgrupoprograma.value = chave1;
  document.form1.ht03_descricao.value = chave2;
  db_iframe_habitgrupoprograma.hide();
}

function js_pesquisaConcedente(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_habitprograma',
                        'db_iframe_habitprogramaconcedente',
                        'func_nome.php?funcao_js=parent.js_mostraConcedente1|z01_numcgm|z01_nome',
                        'Pesquisa',true);
  }else{
     if(document.form1.ht19_numcgm.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_habitprograma',
                            'db_iframe_habitprogramaconcedente',
                            'func_nome.php?pesquisa_chave='+document.form1.ht19_numcgm.value+'&funcao_js=parent.js_mostraConcedente',
                            'Pesquisa',false);
     }else{
       document.form1.z01_nome.value = ''; 
     }
  }
}
function js_mostraConcedente(erro,chave){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.ht19_numcgm.focus(); 
    document.form1.ht19_numcgm.value = ''; 
  }
}
function js_mostraConcedente1(chave1,chave2){
  document.form1.ht19_numcgm.value = chave1;
  document.form1.z01_nome.value    = chave2;
  db_iframe_habitprogramaconcedente.hide();
}


function js_pesquisaReceita(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_habitprograma',
                        'db_iframe_tabrec',
                        'func_tabrec.php?funcao_js=parent.js_mostraReceita1|k02_codigo|k02_descr',
                        'Pesquisa',true);
  }else{
     if(document.form1.ht01_receitapadraopagamento.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_habitprograma',
                            'db_iframe_tabrec',
                            'func_tabrec.php?pesquisa_chave='+document.form1.ht01_receitapadraopagamento.value+'&funcao_js=parent.js_mostraReceita',
                            'Pesquisa',false);
     }else{
       document.form1.k02_descr.value = ''; 
     }
  }
}

function js_mostraReceita(chave,erro){
  document.form1.k02_descr.value = chave; 
  if(erro==true){ 
    document.form1.ht01_receitapadraopagamento.focus(); 
    document.form1.ht01_receitapadraopagamento.value = ''; 
  }
}

function js_mostraReceita1(chave1,chave2){
  document.form1.ht01_receitapadraopagamento.value = chave1;
  document.form1.k02_descr.value    = chave2;
  db_iframe_tabrec.hide();
}

function js_pesquisaWorkFlow(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_habitprograma',
                        'db_iframe_workflow',
                        'func_workflow.php?funcao_js=parent.js_mostraWorkFlow1|db112_sequencial|db112_descricao',
                        'Pesquisa',true);
  }else{
     if(document.form1.ht01_workflow.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_habitprograma',
                            'db_iframe_workflow',
                            'func_workflow.php?pesquisa_chave='+document.form1.ht01_workflow.value+'&funcao_js=parent.js_mostraWorkFlow',
                            'Pesquisa',false);
     }else{
       document.form1.db112_descricao.value = ''; 
     }
  }
}

function js_mostraWorkFlow(chave,erro){
  document.form1.db112_descricao.value = chave; 
  if(erro==true){ 
    document.form1.ht01_workflow.focus(); 
    document.form1.ht01_workflow.value = ''; 
  }
}

function js_mostraWorkFlow1(chave1,chave2){
  document.form1.ht01_workflow.value   = chave1;
  document.form1.db112_descricao.value = chave2;
  db_iframe_workflow.hide();
}


function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo.iframe_habitprograma','db_iframe_habitprograma','func_habitprograma.php?funcao_js=parent.js_preenchepesquisa|ht01_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_habitprograma.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>