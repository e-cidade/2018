<?php
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

$clrescisao->rotulo->label();

$clrotulo = new rotulocampo;
$clrotulo->label("rh115_descricao");
$clrotulo->label("rh115_sigla");

$r59_anousu = db_anofolha();
$r59_mesusu = db_mesfolha();
?>
<br />
<form name="form1" method="post" action="">
<center>

<fieldset style="width:650px;">
	<legend>
		<strong>Cadastro de Causas de Rescisão</strong>
	</legend>
	<table border="0">
	  <tr>
	    <td nowrap title="<?=@$Tr59_regime?>">
	      <?=@$Lr59_regime?>
	    </td>
	    <td colspan=2> 
	      <?
	      $result_regime = $clrhcadregime->sql_record($clrhcadregime->sql_query_file(null));
	      db_selectrecord("r59_regime",$result_regime,true,($db_opcao!=1?3:1));
	      db_input('r59_anousu',4,$Ir59_anousu,true,'hidden',3);
	      db_input('r59_mesusu',2,$Ir59_mesusu,true,'hidden',3);
	      ?>
	    </td>
	  </tr>
	  <tr>
	    <td nowrap title="<?=@$Tr59_causa?>">
	      <?
	      db_ancora(@$Lr59_causa,"js_pesquisar59_causa(true,false);",( ( $db_opcao != 1 ) ? 3 : 1 ));
	      ?>
	    </td>
	    <td colspan=2>
	      <?
	      db_input('r59_causa',6,$Ir59_causa,true,'text',( ( $db_opcao != 1 ) ? 3 : 1 ), "onchange='js_pesquisar59_causa(true,true)'")
	      ?>
	      <?
	      db_input('r59_descr',43,$Ir59_descr,true,'text',$db_opcao,"")
	      ?>
	    </td>
	  </tr>
	  
	  <tr>
	    <td nowrap title="<?=@$Tr59_caub?>">
	      <?
	      db_ancora(@$Lr59_caub,"",3);
	      ?>
	    </td>
	    <td colspan=2>
	      <?
	      db_input('r59_caub',6,$Ir59_caub,true,'text',( ( $db_opcao != 1 ) ? 3 : 1 ), "")
	      ?>
	      <?
	      db_input('r59_descr1',43,$Ir59_descr1,true,'text',(($db_opcao != 1 && ((isset($r59_caub) && trim($r59_caub) == "") || !isset( $r59_caub ) ) ) ? 3 : $db_opcao),"")
	      ?>
	    </td>
	  </tr>
	  
	  <tr>
	    <td nowrap title="<?php echo $Tr59_causaafastamento; ?>">
	       <?php db_ancora(@$Lr59_causaafastamento,"js_pesquisarCausaAfastamento(true);",$db_opcao); ?>
	    </td>
	    <td colspan=2> 
	    <?php 
	    	db_input('rh115_sigla', 6, $Irh115_sigla, true, 'text', $db_opcao, " onchange='js_pesquisarCausaAfastamento(false);'");
	    	db_input('rh115_descricao', 43, $Irh115_descricao, true, 'text', 3, '');
	    	db_input('r59_causaafastamento', 4, $Irh115_descricao, true, 'hidden', 3, '');
	     ?>
	    </td>
	  </tr> 
	  
	  <tr>
	    <td nowrap title="<?php echo $Tr59_menos1; ?>">
	       <?php echo $Lr59_menos1; ?>
	    </td>
	    <td colspan=2> 
	      <?php
	      $arr_SorN = Array('N'=>'Não','S'=>'Sim');
	      db_select("r59_menos1",$arr_SorN,true,($db_opcao != 1?3:1));
	      ?>
	    </td>
	  </tr>
	  <tr>
	    <td nowrap title="<?=@$Tr59_movsef?>">
	       <?=@$Lr59_movsef?>
	    </td>
	    <td colspan=2> 
	      <?
	      $sDbWhere      = " r66_tipo = 'D' ";
	      $sDbWhere     .= " and r66_anousu={$r59_anousu} and r66_mesusu = {$r59_mesusu}"; 
	      $result_movsef = $clcodmovsefip->sql_record($clcodmovsefip->sql_query_file(null, null, null,
	                                                                 "rtrim(r66_codigo) as r66_codigo,r66_descr","r66_descr",$sDbWhere));
	      db_selectrecord("r59_movsef",$result_movsef,true,$db_opcao);
	      ?>
	    </td>
	  </tr>
	  <tr>
	    <td nowrap title="<?=@$Tr59_aviso?>">
	      <?=@$Lr59_aviso?>
	    </td>
	    <td> 
	      <?
	      $arr_SorN = Array('f'=>'Não','t'=>'Sim');
	      db_select('r59_aviso',$arr_SorN,true,$db_opcao,"");
	      ?>
	    </td>
	    <!--INÍCIO INCIDÊNCIAS-->
	    <td rowspan=9 valign="top">
	      <fieldset>
	        <legend><b>INCIDÊNCIAS</b></legend>
	        <table width="100%">
		  <tr>
		    <td width="37%" align="center"></td>
		    <td width="21%" align="center"><b>Previdência</b></td>
		    <td width="21%" align="center"><b>FGTS</b></td>
		    <td width="21%" align="center"><b>IRRF</b></td>
		  </tr>
		  <tr>
		    <td width="37%" align="right"><b>Férias:</b></td>
		    <td width="21%" align="center">
	              <?
	              db_select('r59_finss',$arr_SorN,true,$db_opcao,"");
	              ?>
		    </td>
		    <td width="21%" align="center">
	              <?
	              db_select('r59_ffgts',$arr_SorN,true,$db_opcao,"");
	              ?>
		    </td>
		    <td width="21%" align="center">
	              <?
	              db_select('r59_firrf',$arr_SorN,true,$db_opcao,"");
	              ?>
		    </td>
		  </tr>
		  <tr>
		    <td width="37%" align="right"><b>13o. Salário:</b></td>
		    <td width="21%" align="center">
	              <?
	              db_select('r59_13inss',$arr_SorN,true,$db_opcao,"");
	              ?>
		    </td>
		    <td width="21%" align="center">
	              <?
	              db_select('r59_13fgts',$arr_SorN,true,$db_opcao,"");
	              ?>
		    </td>
		    <td width="21%" align="center">
	              <?
	              db_select('r59_13irrf',$arr_SorN,true,$db_opcao,"");
	              ?>
		    </td>
		  </tr>
		  <tr>
		    <td width="37%" align="right"><b>Aviso Inden.:</b></td>
		    <td width="21%" align="center">
	              <?
	              db_select('r59_rinss',$arr_SorN,true,$db_opcao,"");
	              ?>
		    </td>
		    <td width="21%" align="center">
	              <?
	              db_select('r59_rfgts',$arr_SorN,true,$db_opcao,"");
	              ?>
		    </td>
		    <td width="21%" align="center">
	              <?
	              db_select('r59_rirrf',$arr_SorN,true,$db_opcao,"");
	              ?>
		    </td>
		  </tr>
	        </table>
	      </fieldset>
	    </td>
	    <!--FIM INCIDÊNCIAS-->
	  </tr>
	  <tr>
	    <td nowrap title="<?=@$Tr59_13sal?>">
	      <?=@$Lr59_13sal?>
	    </td>
	    <td> 
	      <?
	      db_select('r59_13sal',$arr_SorN,true,$db_opcao,"");
	      ?>
	    </td>
	  </tr>
	  <tr>
	    <td nowrap title="<?=@$Tr59_fprop?>">
	      <?=@$Lr59_fprop?>
	    </td>
	    <td> 
	      <?
	      db_select('r59_fprop',$arr_SorN,true,$db_opcao,"");
	      ?>
	    </td>
	  </tr>
	  <tr>
	    <td nowrap title="<?=@$Tr59_fvenc?>">
	      <?=@$Lr59_fvenc?>
	    </td>
	    <td> 
	      <?
	      db_select('r59_fvenc',$arr_SorN,true,$db_opcao,"");
	      ?>
	    </td>
	  </tr>
	  <tr>
	    <td nowrap title="<?=@$Tr59_tercof?>">
	      <?=@$Lr59_tercof?>
	    </td>
	    <td> 
	      <?
	      db_input('r59_tercof',6,$Ir59_tercof,true,'text',$db_opcao,"")
	      ?>
	    </td>
	  </tr>
	  <tr>
	    <td nowrap title="<?=@$Tr59_codsaq?>">
	      <?=@$Lr59_codsaq?>
	    </td>
	    <td> 
	      <?
	      db_input('r59_codsaq',6,$Ir59_codsaq,true,'text',$db_opcao,"")
	      ?>
	    </td>
	  </tr>
	  <tr>
	    <td nowrap title="<?=@$Tr59_mfgts?>">
	      <?=@$Lr59_mfgts?>
	    </td>
	    <td> 
	      <?
	      db_input('r59_mfgts',6,$Ir59_mfgts,true,'text',$db_opcao,"")
	      ?>
	    </td>
	  </tr>
	  <tr>
	    <td nowrap title="<?=@$Tr59_grfp?>">
	      <?=@$Lr59_grfp?>
	    </td>
	    <td> 
	      <?
	      db_select('r59_grfp',$arr_SorN,true,$db_opcao,"");
	      ?>
	    </td>
	  </tr>
	  <tr>
	    <td nowrap title="<?=@$Tr59_479clt?>">
	      <?=@$Lr59_479clt?>
	    </td>
	    <td> 
	      <?
	      db_select('r59_479clt',$arr_SorN,true,$db_opcao,"");
	      ?>
	    </td>
	  </tr>
	</table>
	</fieldset>
</center>
<br />	
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisar59_causa(mostra,dig){
  if(mostra==true){
    qry = "";
    ok = false;
    if(document.form1.r59_causa.value != "" && dig == true && document.form1.r59_descr.value == "" && document.form1.r59_caub.value == "" && document.form1.r59_descr1.value == ""){
      qry = "&chave_r59_causa="+document.form1.r59_causa.value;
      ok = true;
    }else if(dig == false){
      ok = true;
    }
    if(ok == true){
      js_OpenJanelaIframe('top.corpo','db_iframe_rescisao','func_rescisaoalt.php?funcao_js=parent.js_mostrarescisao1|r59_causa|r59_descr|r59_caub|r59_descr1&chave_r59_anousu=<?=$r59_anousu?>&chave_r59_mesusu=<?=$r59_mesusu?>&chave_r59_regime='+document.form1.r59_regime.value+qry,'Pesquisa',true);
    }
  }else{
    if(document.form1.r59_causa.value != ''){ 
      js_OpenJanelaIframe('top.corpo','db_iframe_rescisao','func_rescisaoalt.php?pesquisa_chave='+document.form1.r59_causa.value+'&funcao_js=parent.js_mostrarescisao&ano=<?=$r59_anousu?>&mes=<?=$r59_mesusu?>&chave_r59_regime='+document.form1.r59_regime.value,'pesquisa',false);
    }else{
      document.form1.r59_caub.value  = '';
      document.form1.r59_descr.value  = '';
      document.form1.r59_descr1.value = '';
    }
  }
}
function js_mostrarescisao(chave,chave2,chave3,erro){
  document.form1.r59_descr.value = chave; 
  if(erro==true){ 
    document.form1.r59_causa.focus(); 
    document.form1.r59_causa.value = ''; 
    document.form1.r59_caub.value  = '';
    document.form1.r59_descr1.value = '';
  }else{
    document.form1.r59_caub.value   = chave2;
    document.form1.r59_descr1.value  = chave3;
  }
}
function js_mostrarescisao1(chave1,chave2,chave3,chave4){
  document.form1.r59_causa.value = chave1;
  document.form1.r59_descr.value  = chave2;
  document.form1.r59_caub.value  = chave3;
  document.form1.r59_descr1.value = chave4;
  db_iframe_rescisao.hide();
}


function js_pesquisarCausaAfastamento(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_causaafastamento','func_causaafastamento.php?funcao_js=parent.js_retornoCausaAfastamentoAncora|rh115_sigla|rh115_descricao|rh115_sequencial','Pesquisa',true);
  }else{
     if(document.form1.rh115_sigla.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_causaafastamento','func_causaafastamento.php?sSigla='+document.form1.rh115_sigla.value+'&funcao_js=parent.js_retornoCausaAfastamentoInput','Pesquisa',false);
     }else{
       document.form1.rh115_descricao.value = ''; 
     }
  }
}

function js_retornoCausaAfastamentoInput(iSequencial, sDescricao, lErro) {

  if( lErro ){
     
    document.form1.rh115_sigla.focus(); 
    document.form1.rh115_sigla.value = ''; 
    return;
  }
  
  document.form1.rh115_descricao.value      = sDescricao; 
  document.form1.r59_causaafastamento.value = iSequencial; 
}

function js_retornoCausaAfastamentoAncora(sSigla,sDescricao, iSequencial) {
  
  document.form1.rh115_sigla.value          = sSigla;
  document.form1.rh115_descricao.value      = sDescricao;
  document.form1.r59_causaafastamento.value = iSequencial;
  db_iframe_causaafastamento.hide();
}

function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_rescisao','func_rescisao.php?funcao_js=parent.js_preenchepesquisa|r59_anousu|r59_mesusu|r59_regime|r59_causa|r59_caub|r59_menos1','Pesquisa',true);
}
function js_preenchepesquisa(chave,chave1,chave2,chave3,chave4,chave5){
  db_iframe_rescisao.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave+'&chavepesquisa1='+chave1+'&chavepesquisa2='+chave2+'&chavepesquisa3='+chave3+'&chavepesquisa4='+chave4+'&chavepesquisa5='+chave5";
  }
  ?>
}
</script>
<?
if ($db_opcao==2){
  if(isset($r59_movsef)&&$r59_movsef!=""){
 //   db_msgbox($r59_movsef );
    echo "<script>document.form1.r59_movsef.value = '{$r59_movsef}';</script>";    
 //   echo "<script>alert(document.form1.r59_movsef.value);</script>";    
  }
}