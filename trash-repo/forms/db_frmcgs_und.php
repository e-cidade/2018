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

//MODULO: saude
$clcgs_und->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("j14_codigo");
$clrotulo->label("j13_cod");
$clrotulo->label("j13_codi");
$clrotulo->label("DBtxt1");
$clrotulo->label("DBtxt5");
$clrotulo->label("s115_c_cartaosus");
$clrotulo->label("s115_c_tipo");
/*
if($db_opcao!=1 && @$z01_i_cgsund!=""){
 $sql = "SELECT ed56_i_escola as cod_escola FROM alunocurso WHERE ed56_i_aluno = $z01_i_cgsund";
 $query = pg_query($sql);
 $linhas4 = pg_num_rows($query);
 if($linhas4==0){
  $db_botao = true;
 }elseif(db_getsession("DB_coddepto")!=pg_result($query,0,0)){
  $db_botao = false;
 }else{
  $db_botao = true;
 }
}
*/
$municipio="S";
?>
<form name="form1" method="post" action="">
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
 <fieldset style="width:70%"><legend><b>Dados Pessoais</b></legend>
<table border="0" width="90%">
   <tr valign="top">
    <td>
      <tr>
      <td nowrap title="<?=@$Tz01_v_cgccpf?>">
       <?=$Lz01_i_cgsund?>&nbsp;
      </td>
      <td>
       <?db_input('z01_i_cgsund',15,$Iz01_i_cgsund,true,'text',3);?>
      </td>
	  <td align="right" title="<?=$Lz01_i_cgsund?>">
	   <strong>CGS do Município:</strong>&nbsp;
	  </td>
	  <td align="left">
	    <?
		  $x = array("S"=>"SIM","N"=>"NÃO");
		  //db_select('municipio',$x,true,$db_opcao,'onChange="document.form1.submit()"');
	    	db_select('municipio',$x,true,$db_opcao,'onChange="js_zerac(this.value);"');
		?>
    </tr>
     <tr>
        <td width="15%" title='<?=$Tz01_i_cgsund?>' nowrap>
          <?=@$Lz01_v_cgccpf?>&nbsp;
        </td>
        <td nowrap width="33%">
         <?db_input('z01_v_cgccpf',15,@$Iz01_v_cgccpf,true,'text',$db_opcao,"onBlur='js_verificaCGCCPF(this);js_testanome(\"\",this.value,\"\")'");?>
         </td>
         <td width="30%"  align="right">
            <?=@$Lz01_v_ident?>&nbsp;
         </td>
         <td width="21%">
            <?db_input('z01_v_ident',15,$Iz01_v_ident,true,'text',$db_opcao);?>
         </td>
     </tr>
      <tr>
        <td nowrap title=<?=@$Tz01_v_nome?>>
         <?=@$Lz01_v_nome?>&nbsp;
        </td>
        <td nowrap title="<?=@$Tz01_v_nome?>">
         <?db_input('z01_v_nome',52,$Iz01_v_nome,true,'text',$db_opcao,"");?>
        </td>
        </td>
        <td nowrap align="right" title="<?=$Tz01_d_nasc?>">
         <?=$Lz01_d_nasc?>&nbsp;
        </td>
        <td nowrap title="<?=$Tz01_d_nasc?>">
         <?db_inputdata('z01_d_nasc',@$z01_d_nasc_dia,@$z01_d_nasc_mes,@$z01_d_nasc_ano,true,'text',$db_opcao);?>
        </td>
      </tr>
       <tr>
        <td nowrap title=<?=@$Tz01_v_pai?>>
         <?=@$Lz01_v_pai?>&nbsp;
        </td>
        <td nowrap title="<?=@$Tz01_v_pai?>">
         <?db_input('z01_v_pai',52,$Iz01_v_pai,true,'text',$db_opcao,"");?>
        </td>
        <td nowrap align="right" title="<?=$Tz01_i_estciv?>">
         <?=$Lz01_i_estciv?>&nbsp;
         </td>
         <td>
          <?
          $x = array("1"=>"Solteiro",
                     "2"=>"Casado",
                     "3"=>"Viúvo",
                     "4"=>"Separado ",
                     "5"=>"União C.",
                     "9"=>"Ignorado");
          db_select('z01_i_estciv',$x,true,$db_opcao);
          ?>
          </td>
        <tr>
         <td nowrap title=<?=@$Tz01_v_mae?>>
         <?=@$Lz01_v_mae?>&nbsp;
         </td>
         <td nowrap title="<?=@$Tz01_v_mae?>">
         <?db_input('z01_v_mae',52,$Iz01_v_mae,true,'text',$db_opcao,"");?>
         </td>
          <td nowrap align="right">
          <?=$Lz01_v_sexo?>&nbsp;
          </td>
          <td>
          <?
          $sex = array("M"=>"Masculino","F"=>"Feminino");
          db_select('z01_v_sexo',$sex,true,$db_opcao);
          ?>

         <td width="30%"  align="right">
        </tr>

       <tr>
          <td nowrap >
             <b>Cartao SUS</b>&nbsp;
          </td>
          <td>
            <?db_input('s115_i_codigo',15,@$Is115_i_codigo,true,'hidden',$db_opcao);?>
           	<?db_input('s115_c_cartaosus',17,@$Is115_c_cartaosus,true,'text',$db_opcao);?>
         </td>
            
          <td width="30%"  align="right">
             <b>Tipo Cartao SUS</b>&nbsp;
          </td>
          <td>
            <?
		        $x = array("D"=>"Definitivo","P"=>"Provisório");
	        	db_select('s115_c_tipo',$x,true,$db_opcao);
	          ?>
         </td>
       </tr>
           
    </td>
   </tr>
    <tr>
       <tr>     
        <td nowrap title="<?=@$Tz01_v_cep?>">
         <?
	       db_ancora(@$Lz01_v_cep,"js_cepcon(true);",$db_opcao);
	       ?>&nbsp;
        </td>
        <td nowrap>
         <?db_input('z01_v_cep',8,$Iz01_v_cep,true,'text',$db_opcao);?>
         <input type="button" name="buscacep" value="Pesquisar" onClick="js_cepcon(false);">
        </td>
     </tr>
        <td nowrap title="<?=@$Tz01_v_ender?>">
         <?db_ancora(@$Lz01_v_ender,"js_ruas();",$db_opcao);?>&nbsp;
         </td>
         <td colspan="3" nowrap>
          <?db_input('z01_v_ender',52,$Iz01_v_ender,true,'text',$db_opcao);?>
         </td>
        </tr>
        <tr>
        <td width="10%" nowrap title="<?=@$Tz01_i_numero?>">
         <?=@$Lz01_i_numero?>&nbsp;
        </td>
        <td width="47%" nowrap>
         <a name="AN3">
         <?db_input('z01_i_numero',8,$Iz01_i_numero,true,'text',$db_opcao);?>
         </td>
         <td width="20%"  align="right">
         <?=@$Lz01_v_compl?>&nbsp;
         </td>
         <td width="28%">
         <?db_input('z01_v_compl',15,$Iz01_v_compl,true,'text',$db_opcao);?>
         </a>
        </td>
       </tr>
       	<tr>
        <td nowrap title="<?=$Tz01_v_bairro?>">
         <?db_ancora(@$Lz01_v_bairro,"js_bairro();",$db_opcao);?>&nbsp;
        </td>
        <td nowrap>
         <?db_input('j13_codi',10,$Ij13_codi,true,'hidden',$db_opcao);?>
         <?db_input('z01_v_bairro',52,$Iz01_v_bairro,true,'text',3);?>
        </td>
        <td nowrap align="right" title="<?=@$Tz01_d_cadast?>">
         <?=@$Lz01_d_cadast?>&nbsp;
        </td>
        <td nowrap>
         <?db_inputdata('z01_d_cadast',@$z01_d_cadast_dia,@$z01_d_cadast_mes,@$z01_d_cadast_ano,true,'text',$db_opcao);?>
     </td>
       </tr>
       <tr>
        <td nowrap title="<?=@$Tz01_v_munic?>">
         <?=@$Lz01_v_munic?>&nbsp;
        </td>
        <td nowrap>
         <?db_input('z01_v_munic',30,$Iz01_v_munic,true,'text',$db_opcao);?>
        </td>
        <td nowrap align="right" title="<?=@$Tz01_v_uf?>">
         <?=@$Lz01_v_uf?>&nbsp;
         </td>
         <td>
         <?db_input('z01_v_uf',2,$Iz01_v_uf,true,'text',$db_opcao);?>
        </td>
       </tr>
       <tr>
        <td nowrap title="<?=@$Tz01_v_email?>">
         <?=@$Lz01_v_email?>&nbsp;
        </td>
        <td nowrap>
         <?db_input('z01_v_email',30,$Iz01_v_email,true,'text',$db_opcao);?>
        </td>
        <td nowrap align="right" title="<?=@$Tz01_v_telef?>">
         <?=@$Lz01_v_telef?>&nbsp;
        </td>
        <td nowrap>
         <?db_input('z01_v_telef',15,$Iz01_v_telef,true,'text',$db_opcao);?>
        </td>
       </tr>
      <tr>      
        <td nowrap title="<?=@$Tz01_v_cxpostal?>">
         <?=@$Lz01_v_cxpostal?>&nbsp;
        </td>
        <td nowrap>
         <?db_input('z01_v_cxpostal',15,$Iz01_v_cxpostal,true,'text',$db_opcao);?>
        </td>
        <td nowrap  align="right" title="<?=@$Tz01_v_telcel?>">
         <?=@$Lz01_v_telcel?>&nbsp;
        </td>
        <td nowrap>
         <?db_input('z01_v_telcel',15,$Iz01_v_telcel,true,'text',$db_opcao);?>
        </td>
       </tr>
   </table>
  </fieldset>
  <table>
       <tr align="center" valign="middle">
       <td height="30" colspan="2" nowrap>
      <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
      <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" <?=$db_opcao==1?"disabled":""?>>
      <input name="novo" type="button" id="novo" value="Novo Registro" onclick="js_novo()" <?=$db_opcao==1?"disabled":""?>>
		  <?
	      if( isset( $retornacgs ) ){
			  echo '<input name="fechar" type="submit" value="Fechar" onclick="parent.db_iframe_cgs_und.hide();">';
		  } elseif ( isset( $funcao_js ) ){
		    
			  echo '<input name="fechar" id="fechar" type="button" value="Fechar" onclick="parent.db_iframe_cgs_und.hide();">';
		    echo '<input name="funcaojs" id="funcaojs" type="hidden" value="'.$funcao_js.'">';
			  
		  }
		  ?>  
     </td>
    </tr>
   </table>
</form>
<script>
 function js_ruas(){
  js_OpenJanelaIframe('','db_iframe_ruas','func_ruas.php?rural=1&funcao_js=parent.js_preenchepesquisaruas|j14_codigo|j14_nome','Pesquisa',true);
 }
 function js_preenchepesquisaruas(chave,chave1){
   document.form1.z01_v_ender.value = chave1;
   db_iframe_ruas.hide();
 }
 function js_bairro(){
  js_OpenJanelaIframe('','db_iframe_bairro','func_bairro.php?rural=1&funcao_js=parent.js_preenchebairro|j13_codi|j13_descr','Pesquisa',true);
 }
 function js_preenchebairro(chave,chave1){
  document.form1.j13_codi.value = chave;
  document.form1.z01_v_bairro.value = chave1;
  db_iframe_bairro.hide();
 }
 function js_ruas1(){
  js_OpenJanelaIframe('','db_iframe_ruas1','func_ruas.php?rural=1&funcao_js=parent.js_preenchepesquisaruas1|j14_codigo|j14_nome','Pesquisa',true);
 }
 function js_preenchepesquisaruas1(chave,chave1){
   document.form1.z01_v_endcon.value = chave1;
   db_iframe_ruas1.hide();
 }
 function js_bairro1(){
  js_OpenJanelaIframe('','db_iframe_bairro1','func_bairro.php?rural=1&funcao_js=parent.js_preenchebairro1|j13_codi|j13_descr','Pesquisa',true);
 }
 function js_preenchebairro1(chave,chave1){
  document.form1.z01_v_baicon.value = chave1;
  db_iframe_bairro1.hide();
 }
 function js_pesquisa(){
 	
  js_OpenJanelaIframe('','db_iframe_cgs_und','func_cgs_und.php?funcao_js=parent.js_preenchepesquisa|z01_i_cgsund&redireciona=parent.parent.js_preenchepesquisa(document.form1.z01_i_cgsund.value)','Pesquisa CGS',true);
 }
 function js_preenchepesquisa(chave){
 	//alert(chave);
  db_iframe_cgs_und.hide();
  <? //echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave+'&retornacgs=$retornacgs&retornanome=$retornanome'";?>
  location.href = "<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>?chavepesquisa="+chave+"&retornacgs=<?=$retornacgs?>&retornanome=<?=$retornanome?>";
  
 }
 function js_novo(){
  parent.location="sau1_cgs_und000.php?id=1";
 }
 
 function js_zerac(municipio){
 	if(document.form1.municipio.value=="N"){
     document.form1.z01_v_cep.value    = "";
     document.form1.z01_v_ender.value  = "";
     document.form1.z01_v_munic.value  = "";
     document.form1.z01_v_uf.value	   = "";
     document.form1.z01_v_bairro.value = "";
     document.form1.buscacep.disabled=false;
     document.form1.z01_v_bairro.readOnly = false;
     document.form1.z01_v_bairro.style.background = "#FFFFFF";
     document.links[0].style.color = "#000000";
     document.links[0].style.textDecoration = "none";
     document.links[0].href = "";
     document.links[1].style.color = "#000000";
     document.links[1].style.textDecoration = "none";
     document.links[1].href = "";
     document.links[2].style.color = "#000000";
     document.links[2].style.textDecoration = "none";
     document.links[2].href = "";
 	}else if(document.form1.municipio.value=="S") { 
 	 document.form1.z01_v_cep.value    = "";
     document.form1.z01_v_ender.value  = "";
     document.form1.z01_v_munic.value  = "";
     document.form1.z01_v_uf.value	   = "";
     document.form1.z01_v_bairro.value = "";
     document.form1.z01_v_bairro.readOnly = false;
     document.form1.z01_v_bairro.style.background = "#FFFFFF";     
     document.links[0].style.color = "blue";
     document.links[0].style.textDecoration = "underline";	
     document.links[0].href = "";
     document.links[1].style.color = "blue";
     document.links[1].style.textDecoration = "underline";	
     document.links[1].href = "";
     document.links[2].style.color = "blue";
     document.links[2].style.textDecoration = "underline";	
     document.links[2].href = "";
 	 document.form1.submit();	
 	} 
   }
   
  if(document.form1.municipio.value=='S'){
   document.form1.buscacep.disabled=true;		
  }
   
 
 function js_cepcon(abre){
  //if(document.form1.z01_v_cep.value != "")
    //document.getElementById('teste').style.visibility = 'visible';
  if(abre == true){
    js_OpenJanelaIframe('','db_iframe_cep','func_cep.php?funcao_js=parent.js_preenchecepcon|cep|cp06_logradouro|cp05_localidades|cp05_sigla|cp01_bairro|z01_v_cep','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_cep','func_cep.php?pesquisa_chave='+document.form1.z01_v_cep.value+'&funcao_js=parent.js_preenchecepcon|cep|cp06_logradouro|cp05_localidades|cp05_sigla|cp01_bairro|z01_v_cep','Pesquisa',false);
  }
}


function js_preenchecepcon(chave,chave1,chave2,chave3,chave4){
//  setInterval("document.getElementById('teste').style.visibility = 'hidden'",2000);
  document.form1.z01_v_cep.value = chave;
  document.form1.z01_v_ender.value = chave1;
  document.form1.z01_v_munic.value = chave2;
  document.form1.z01_v_uf.value = chave3;
  document.form1.z01_v_bairro.value = chave4;
//document.form1.j13_codi.value = chave5;
 
  db_iframe_cep.hide();
}
function js_preenchecepcon1(chave,chave1,chave2,chave3,chave4){
//  setInterval("document.getElementById('teste').style.visibility = 'hidden'",2000);
  if(chave=="" && chave1 == "" && chave2 == "" && chave3=="" && chave4=="" && chave4==""){
    alert('CEP não encontrado!');
    document.form1.z01_v_cep.focus();
  }
  document.form1.z01_v_cep.value = chave;
  document.form1.z01_v_ender.value = chave1;
  document.form1.z01_v_munic.value = chave2;
  document.form1.z01_v_uf.value = chave3;
  document.form1.z01_v_bairro.value = chave4;

}

</script>