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

//MODULO: cadastro
$clcfiptu->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("i01_descr");
$clrotulo->label("nomefuncao");
$clrotulo->label("j45_descr");
$clrotulo->label("j17_descr2");
$clrotulo->label("j17_descr");
$clrotulo->label("v19_templateparcelamento");
$clrotulo->label("j18_templatecertidaoexitencia");
$clrotulo->label("db82_descricao");
?>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<form name="form1" method="post" action="">
<center>
  <table align=center style="margin-top: 25px;">
    <tr>
      <td>
        <fieldset>
          <legend>
            <strong>Parâmetros</strong>
          </legend>
					<table border="0">
					  <tr>
					    <td nowrap title="<?=@$Tj18_anousu?>">
					      <?=@$Lj18_anousu?>
					    </td>
					    <td> 
								<?
								$j18_anousu = db_getsession('DB_anousu');
								db_input('j18_anousu',4,$Ij18_anousu,true,'text',3,"")
								?>
					    </td>
					  </tr>
					  <tr>
					    <td nowrap title="<?=@$Tj18_vlrref?>">
					      <?=@$Lj18_vlrref?>
					    </td>
					    <td> 
								<?
								db_input('j18_vlrref',15,$Ij18_vlrref,true,'text',$db_opcao,"")
								?>
					    </td>
					  </tr>
					  <tr>
					    <td nowrap title="<?=@$Tj18_dtoper?>">
					      <?=@$Lj18_dtoper?>
					    </td>
					    <td> 
								<?
								db_inputdata('j18_dtoper',@$j18_dtoper_dia,@$j18_dtoper_mes,@$j18_dtoper_ano,true,'text',$db_opcao,"")
								?>
					    </td>
					  </tr>
					  <tr>
					    <td nowrap title="<?=@$Tj18_rterri?>">
					      <?=@$Lj18_rterri?>
					    </td>
					    <td> 
								<?
								db_input('j18_rterri',10,$Ij18_rterri,true,'text',$db_opcao,"")
								?>
					    </td>
					  </tr>
					  <tr>
					    <td nowrap title="<?=@$Tj18_rpredi?>">
					      <?=@$Lj18_rpredi?>
					    </td>
					    <td> 
								<?
								db_input('j18_rpredi',10,$Ij18_rpredi,true,'text',$db_opcao,"")
								?>
					    </td>
					  </tr>
					  <tr>
					    <td nowrap title="<?=@$Tj18_vencim?>">
					      <?=@$Lj18_vencim?>
					    </td>
					    <td> 
								<?
								db_input('j18_vencim',4,$Ij18_vencim,true,'text',$db_opcao,"")
								?>
					    </td>
					  </tr>
					  <tr>
					    <td nowrap title="<?=@$Tj18_logradauto?>">
					      <?=@$Lj18_logradauto?>
					    </td>
					    <td> 
								<?
								$x = array("f"=>"NAO","t"=>"SIM");
								db_select('j18_logradauto',$x,true,$db_opcao,"");
								?>
					    </td>
					  </tr>
					  <tr>
					    <td nowrap title="<?=@$Tj18_segundavia?>">
					      <?=@$Lj18_segundavia?>
					    </td>
					    <td> 
								<?
								$x = array('1'=>'Segunda Via','2'=>'Carne');
								db_select('j18_segundavia',$x,true,$db_opcao,"");
								?>
					    </td>
					  </tr>
					  <tr>
					    <td nowrap title="<?=@$Tj18_infla?>">
					       <?
					       db_ancora(@$Lj18_infla,"js_pesquisaj18_infla(true);",$db_opcao);
					       ?>
					    </td>
					    <td> 
								<?
								db_input('j18_infla',5,$Ij18_infla,true,'text',$db_opcao," onchange='js_pesquisaj18_infla(false);'")
								?>
					       <?
					         db_input('i01_descr',40,$Ii01_descr,true,'text',3,'')
					       ?>
					    </td>
					  </tr>
					  <tr>
					    <td nowrap title="<?=@$Tj18_utilizasetfisc?>">
					       <?=@$Lj18_utilizasetfisc?>
					    </td>
					    <td> 
								<?
								$x = array("f"=>"NAO","t"=>"SIM");
								db_select('j18_utilizasetfisc',$x,true,$db_opcao,"");
								?>
					    </td>
					  </tr>
					  <tr>
					    <td nowrap title="<?=@$Tj18_testadanumero?>">
					       <?=@$Lj18_testadanumero?>
					    </td>
					    <td> 
								<?
								$x = array("f"=>"NAO","t"=>"SIM");
								db_select('j18_testadanumero',$x,true,$db_opcao,"");
								?>
					    </td>
					  </tr>
					  <tr>
					    <td nowrap title="<?=@$Tj18_excconscalc?>">
					       <?=@$Lj18_excconscalc?>
					    </td>
					    <td> 
								<?
								$x = array('f'=>'Não','t'=>'Sim');
								db_select('j18_excconscalc',$x,true,$db_opcao,"");
								?>
					    </td>
					  </tr>
					  <tr>
					    <td nowrap title="<?=@$Tj18_textoprom?>">
					       <?=@$Lj18_textoprom?>
					    </td>
					    <td> 
								<?
								db_input('j18_textoprom',20,$Ij18_textoprom,true,'text',$db_opcao,"")
								?>
					    </td>
					  </tr>
					  <tr>
					    <td nowrap title="<?=@$Tj18_calcvenc?>">
					       <?=@$Lj18_calcvenc?>
					    </td>
					    <td> 
								<?
								db_input('j18_calcvenc',1,$Ij18_calcvenc,true,'text',$db_opcao,"")
								?>
					    </td>
					  </tr>
					  <tr>
					    <td nowrap title="<?=@$Tj18_utilizaloc?>">
					       <?=@$Lj18_utilizaloc?>
					    </td>
					    <td> 
								<?
								$x = array('f'=>'Não','t'=>'Sim');
								db_select('j18_utilizaloc',$x,true,$db_opcao,"");
								?>
					    </td>
					  </tr>
					  <tr>
					    <td nowrap title="<?=@$Tj18_permvenc?>">
					       <?=@$Lj18_permvenc?>
					    </td>
					    <td> 
								<?
								db_input('j18_permvenc',1,$Ij18_permvenc,true,'text',$db_opcao,"")
								?>
					    </td>
					  </tr>
            <tr>
              <td nowrap title="<?=@$Tj18_perccorrepadrao?>">
                 <?=@$Lj18_perccorrepadrao?>
              </td>
              <td> 
                <?
                  db_input('j18_perccorrepadrao',10,@$Ij18_perccorrepadrao,true,'text',$db_opcao,"");
                ?>
              </td>
            </tr>
					  <tr>
					    <td nowrap title="<?=@$Tj18_utidadosdiver?>">
					       <?=@$Lj18_utidadosdiver?>
					    </td>
					    <td> 
								<?
								$x = array("f"=>"NAO","t"=>"SIM");
								db_select('j18_utidadosdiver',$x,true,$db_opcao,"");
								?>
					    </td>
					  </tr>
					  <tr>
					    <td nowrap title="<?=@$Tj18_dadoscertisen?>">
					       <?=@$Lj18_dadoscertisen?>
					    </td>
					    <td> 
								<?
								$x = array('0'=>'Proprietario','1'=>'Promitente');
								db_select('j18_dadoscertisen',$x,true,$db_opcao,"");
								?>
					    </td>
					  </tr>
					  <tr>
					    <td nowrap title="<?=@$Tj18_formatsetor?>">
					       <?=@$Lj18_formatsetor?>
					    </td>
					    <td> 
								<?
								$x = array('0'=>'Somente Números','1'=>'Letras e Números');
								db_select('j18_formatsetor',$x,true,$db_opcao,"");
								?>
					    </td>
					  </tr>
					  <tr>
					    <td nowrap title="<?=@$Tj18_formatquadra?>">
					       <?=@$Lj18_formatquadra?>
					    </td>
					    <td> 
								<?
								$x = array('0'=>'Somente Números','1'=>'Letras e Números');
								db_select('j18_formatquadra',$x,true,$db_opcao,"");
								?>
					    </td>
					  </tr>
					  <tr>
					    <td nowrap title="<?=@$Tj18_formatlote?>">
					       <?=@$Lj18_formatlote?>
					    </td>
					    <td> 
								<?
								$x = array('0'=>'Somente Números','1'=>'Letras e Números');
								db_select('j18_formatlote',$x,true,$db_opcao,"");
								?>
					    </td>
					  </tr>
					  <tr>
					    <td nowrap title="<?=@$Tj18_utilpontos?>">
					       <?=@$Lj18_utilpontos?>
					    </td>
					    <td> 
								<?
								$x = array('0'=>'Não
								','1'=>'Sim');
								db_select('j18_utilpontos',$x,true,$db_opcao,"");
								?>
					    </td>
					  </tr>
					  <tr>
					    <td nowrap title="<?=@$Tj18_ordendent?>">
					       <?=@$Lj18_ordendent?>
					    </td>
					    <td> 
								<?
								$x = array(
								           '1' => 'imobiliaria, zona de entrega, endereco de entrega, endereco do cgm, endereco da construcao (predial)',
								           '2' => 'imobiliaria, zona de entrega, endereco de entrega, endereco da construcao (predial), endereco do cgm',
								           '3' => 'imobiliaria, zona de entrega, endereco de entrega, endereco da construcao (predial)',
								           '4' => 'imobiliaria, zona de entrega, endereco do cgm, endereco da construcao (predial)',
								           '5' => 'endereco de entrega, endereco da construcao (predial), endereco do cgm',
								           '6' => 'endereco de entrega, endereco do cgm, endereco da construcao (predial)',
								           '7' => 'Baldio = Endereço do Terreno, Predial = Endereço da Construção');
								db_select('j18_ordendent',$x,true,$db_opcao,"");
								?>
					    </td>
					  </tr>
					  <tr>
					    <td nowrap title="<?=@$Tj18_iptuhistisen?>">
					       <?
					        db_ancora("<b>Código do historico de isenção:</b>","js_pesquisaj08_histiseni(true);",$db_opcao);
					       ?>
					    </td>
					    <td> 
					      <?
					       db_input('j18_iptuhistisen',10,$Ij18_iptuhistisen,true,'text',$db_opcao,"onchange='js_pesquisaj08_histiseni(false);'");
					       //db_input('j08_histisen',10,$Ij08_histisen,true,'text',$db_opcao," onchange='js_pesquisaj08_histiseni(false);'");
					      ?>
					      <?
					       db_input('j17_descr2',40,$Ij17_descr,true,'text',3,'')
					      ?>
					    </td>
					  </tr>
					  <tr>
					    <td nowrap title="<?=@$Tj18_db_sysfuncoes?>">
					       <?
					       db_ancora(@$Lj18_db_sysfuncoes,"js_pesquisaj18_db_sysfuncoes(true);",$db_opcao);
					       ?>
					    </td>
					    <td> 
					      <?
					      db_input('j18_db_sysfuncoes',10,$Ij18_db_sysfuncoes,true,'text',$db_opcao," onchange='js_pesquisaj18_db_sysfuncoes(false);'")
					      ?>
					      <?
					       db_input('nomefuncao',40,$Inomefuncao,true,'text',3,'')
					      ?>
					    </td>
					  </tr>
					  <tr>
					    <td nowrap title="<?=@$Tj18_tipoisen?>">
					       <?
					       db_ancora(@$Lj18_tipoisen,"js_pesquisaj18_tipoisen(true);",$db_opcao);
					       ?>
					    </td>
					    <td> 
					      <?
					      db_input('j18_tipoisen',10,$Ij18_tipoisen,true,'text',$db_opcao," onchange='js_pesquisaj18_tipoisen(false);'")
					      ?>
					      <?
					      db_input('j45_descr',40,$Ij45_descr,true,'text',3,'')
					       ?>
					    </td>
					  </tr>
					  
            <tr >
    					<td nowrap="nowrap" title="<?=@$Tp90_db_documentotemplate?>">
    		        <?
    		          db_ancora("<B>Documento Template:</B>","js_pesquisaDocumento(true);",$db_opcao);
    		        ?>
    		      </td>
    		      <td nowrap="nowrap"> 
    		        <?
    		          db_input('j18_templatecertidaoexitencia',10,@$Ij18_templatecertidaoexitencia,true,'text',$db_opcao,'onchange="js_pesquisaDocumento(false);"');
    		          db_input('db82_descricao',50,$Idb82_descricao,true,'text',3,'','db82_descricao');
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
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();">
<?
  if ($db_opcao == 1) {
?>
<input name="importar"  type="submit" id="importar" value="Importar Parâmetros do Exercício Anterior">
<?
  }
?>
</form>
<script>



/**
 * Pesquisa dados via lookup ou digitação
 * @param object   oElemento Elemento HTML base para pesquisa
 * @param boolean  lMostra   Valida se mostra a lookup de pesquisa
 */
function js_pesquisaDocumento(lMostra) {
  
 if (lMostra) {

    sArquivoPesquisa    = 'func_db_documentotemplate.php?funcao_js=parent.js_mostraDocumentoLookUp|db82_sequencial|db82_descricao&tipo=18' ;
 } else {

  sArquivoPesquisa    = 'func_db_documentotemplate.php?pesquisa_chave=' + $F('j18_templatecertidaoexitencia') + '&funcao_js=parent.js_mostraDocumentoDigitacao&tipo=18';

 }

  /**
   * Abre a janela 
   */
  js_OpenJanelaIframe('top.corpo',
  	                  'db_iframe_db_documentotemplate',
  	                  sArquivoPesquisa,
  	                  'Pesquisa Documentos Template',
  	                  lMostra);
}

function js_mostraDocumentoDigitacao(sRetorno, lErro){

	$('db82_descricao').value = sRetorno;
		  
  if (lErro) { 
    $('db82_descricao').focus(); 
    $('db82_descricao').value = ''; 
  }
}

function js_mostraDocumentoLookUp(iCodigo, sRetorno) {

		$('j18_templatecertidaoexitencia').value = iCodigo;
		$('db82_descricao').value   = sRetorno;
    db_iframe_db_documentotemplate.hide();
}


function js_pesquisaj08_histiseni(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframeiptucalh','func_iptucalh.php?funcao_js=parent.js_mostraiptucalh1i|j17_codhis|j17_descr','Pesquisa',true,'20');
  }else{
     if(document.form1.j08_histisen.value != ''){ 
        js_OpenJanelaIframe('','db_iframeiptucalh','func_iptucalh.php?pesquisa_chave='+document.form1.j08_histisen.value+'&funcao_js=parent.js_mostraiptucalhi','Pesquisa',false);
     }else{
       document.form1.j17_descr2.value = ''; 
     }
  }
}
function js_mostraiptucalhi(chave,erro){
  document.form1.j17_descr2.value = chave; 
  if(erro==true){ 
    document.form1.j18_iptuhistisen.focus(); 
    document.form1.j18_iptuhistisen.value = ''; 
  }
}
function js_mostraiptucalh1i(chave1,chave2){
  document.form1.j18_iptuhistisen.value = chave1;
  document.form1.j17_descr2.value = chave2;
  db_iframeiptucalh.hide();
}


function js_pesquisaj18_infla(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_inflan','func_inflan.php?funcao_js=parent.js_mostrainflan1|i01_codigo|i01_descr','Pesquisa',true);
  }else{
     if(document.form1.j18_infla.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_inflan','func_inflan.php?pesquisa_chave='+document.form1.j18_infla.value+'&funcao_js=parent.js_mostrainflan','Pesquisa',false);
     }else{
       document.form1.i01_descr.value = ''; 
     }
  }
}
function js_mostrainflan(chave,erro){
  document.form1.i01_descr.value = chave; 
  if(erro==true){ 
    document.form1.j18_infla.focus(); 
    document.form1.j18_infla.value = ''; 
  }
}
function js_mostrainflan1(chave1,chave2){
  document.form1.j18_infla.value = chave1;
  document.form1.i01_descr.value = chave2;
  db_iframe_inflan.hide();
}
function js_pesquisaj18_db_sysfuncoes(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_sysfuncoes','func_db_sysfuncoes.php?funcao_js=parent.js_mostradb_sysfuncoes1|codfuncao|nomefuncao','Pesquisa',true);
  }else{
     if(document.form1.j18_db_sysfuncoes.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_db_sysfuncoes','func_db_sysfuncoes.php?pesquisa_chave='+document.form1.j18_db_sysfuncoes.value+'&funcao_js=parent.js_mostradb_sysfuncoes','Pesquisa',false);
     }else{
       document.form1.nomefuncao.value = ''; 
     }
  }
}
function js_mostradb_sysfuncoes(chave,erro){
  document.form1.nomefuncao.value = chave; 
  if(erro==true){ 
    document.form1.j18_db_sysfuncoes.focus(); 
    document.form1.j18_db_sysfuncoes.value = ''; 
  }
}
function js_mostradb_sysfuncoes1(chave1,chave2){
  document.form1.j18_db_sysfuncoes.value = chave1;
  document.form1.nomefuncao.value = chave2;
  db_iframe_db_sysfuncoes.hide();
}
function js_pesquisaj18_tipoisen(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_tipoisen','func_tipoisen.php?funcao_js=parent.js_mostratipoisen1|j45_tipo|j45_descr','Pesquisa',true);
  }else{
     if(document.form1.j18_tipoisen.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_tipoisen','func_tipoisen.php?pesquisa_chave='+document.form1.j18_tipoisen.value+'&funcao_js=parent.js_mostratipoisen','Pesquisa',false);
     }else{
       document.form1.j45_descr.value = ''; 
     }
  }
}
function js_mostratipoisen(chave,erro){
  document.form1.j45_descr.value = chave; 
  if(erro==true){ 
    document.form1.j18_tipoisen.focus(); 
    document.form1.j18_tipoisen.value = ''; 
  }
}
function js_mostratipoisen1(chave1,chave2){
  document.form1.j18_tipoisen.value = chave1;
  document.form1.j45_descr.value = chave2;
  db_iframe_tipoisen.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_cfiptu','func_cfiptu.php?funcao_js=parent.js_preenchepesquisa|j18_anousu','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_cfiptu.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>
  
<?php 

if (isset($j18_templatecertidaoexitencia)) {
  echo "<script>js_pesquisaDocumento(false)</script>";
}
?>