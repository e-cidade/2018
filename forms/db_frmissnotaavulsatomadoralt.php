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

//MODULO: issqn
$clissnotaavulsatomador->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("q51_numnota");
$clrotulo->label("q51_dtemiss");
$clrotulo->label("q54_inscr");
$clrotulo->label("q02_inscr");
$clrotulo->label("z01_nome");
$clrotulo->label("z01_cgccpf");
$clrotulo->label("z01_bairro");
$clrotulo->label("z01_munic");
$clrotulo->label("z01_numcgm");
$clrotulo->label("z01_ender");
$clrotulo->label("z01_numero");
$clrotulo->label("z01_cep");
$clrotulo->label("z01_uf");
$clrotulo->label("z01_telef");
$clrotulo->label("z01_email");
?>
<form name="form1" method="post" action="">
<center>
<table border="0" >
 <tr>
   <td>
   <fieldset><b><Legend>NOTA FISCAL DE SERVIÇO AVULSA - inclusão</legend> 
   <table border="0">
   <tr>
     <td nowrap title="<?=@$Tq51_numnota?>">
       <?=@$Lq51_numnota?>
     </td>
    <td> 
     <?
     db_input('q51_numnota',10,$Iq51_numnota,true,'text',3,"");
     db_input('q53_sequencial',10,'',true,'hidden',3,"");
     ?>
   </td>
    <td nowrap title="<?=@$Tq51_dtemiss?>">
     <?= @$Lq51_dtemiss;
     ?> 
		 </td>
     <td> 
     <?
    db_inputdata('q51_dtemiss',@$q51_dtemiss_dia,@$q51_dtemiss_mes,@$q51_dtemiss_ano,true,'text',3,"");
     ?>
     </td>
  </tr>
	</table>
	</fieldset></td></tr>
	 <tr>
	 <td>
	 <fieldset><legend><b>Prestador do Serviço</b></legend>
	 <table>
	 <tr>
    <td nowrap title="<?=@$Tq02_inscr?>">
       <?=@$Lq02_inscr?>
    </td>
    <td> 
      <?
        db_input('q02_inscr',10,$Iq02_inscr,true,'text',3,"");
      ?>
    </td>
    <td nowrap title="CPF/CGC">
	   	<b>CPF/CGC:</b>
    </td>
    <td> 
      <?
      db_input('z01_cpfcgc',14,'',true,'text',3,"");
      ?>
     </td>
    </tr>
	 <tr>
    <td nowrap title="<?=@$Tz01_nome?>">
       <?=@$Lz01_nome?>
    </td>
    <td nowrap colspan='3'> 
      <?
        db_input('z01_nome',70,$Iz01_nome,true,'text',3,"");
      ?>
    </td>
		</tr>
	 <tr>
    <td nowrap title="Endereco">
       <b>Endereço:</b>
    </td>
    <td nowrap colspan='3'> 
      <?
        db_input('z01_endereco',50,'',true,'text',3,"");
      ?>
			<b>&nbsp;Nº:</b>
      <?
        db_input('z01_numero',10,'',true,'text',3,"");
      ?>
    </td>
		</tr>
	 </table>
	 </fieldset>
	 </td>
	 </tr>
	 <tr>
	 <td>
	 <fieldset><legend><b>Tomador do Serviço</b></legend>
	 <table border='0'>
   <tr>
    <td nowrap title="<?=@$Tz01_cgccpf?>">
		<?
      db_ancora(@$Lz01_cgccpf,"",$db_opcao,"","cpfAncora");?>
    </td>
    <td colspan='2'> 
      <?
       db_input('q53_cgccpf',17,$Iz01_cgccpf,true,'text',3);
      ?>
     </td>
		 </tr>
		 <tr>
     <td nowrap title="<?=@$Tq54_inscr?>">
       <?
       db_ancora(@$Lq54_inscr,"",$db_opcao,"","inscrAncora");
       ?>
    </td>
    <td> 
      <?
       db_input('q54_inscr',10,$Iq54_inscr,true,'text',$db_opcao," onchange='js_pesquisaq52_inscr(false);'");
      ?>
    </td>
		</tr>
   <tr>
    <td nowrap title="<?=@$Tz01_nome?>">
     <?=@$Lz01_nome?>
    </td>
    <td colspan='5'> 
   <?
   db_input('q61_numcgm',70,'',true,'hidden',3,"");
   db_input('q53_nome',70,$Iz01_nome,true,'text',3,"")
   ?>
    </td>
    </tr>
  <tr>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tz01_ender?>">
       <?=@$Lz01_ender?>
    </td>
    <td colspan='2'> 
    <?
     db_input('q53_endereco',50,$Iz01_ender,true,'text',3,"")
    ?>
    </td>
    <td nowrap title="<?=@$Tz01_numero?>">
       <?=@$Lz01_numero?>
    </td>
    <td> 
    <? 
     db_input('q53_numero',15,$Iz01_numero,true,'text',3,"")
     ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tz01_bairro?>">
       <?=@$Lz01_bairro?>
    </td>
    <td colspan='4'> 
       <?
        db_input('q53_bairro',50,$Iz01_bairro,true,'text',3,"")
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tz01_munic?>">
       <?=@$Lz01_munic?>
    </td>
    <td colspan='4'> 
       <?
        db_input('q53_municipio',50,$Iz01_munic,true,'text',3,"")
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tz01_uf?>">
       <?=@$Lz01_uf?>
    </td>
    <td colspan='2'> 
<?
db_input('q53_uf',2,$Iz01_uf,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tz01_cep?>">
       <?=@$Lz01_cep?>
    </td>
    <td> 
    <?
     db_input('q53_cep',8,$Iz01_cep,true,'text',3,"")
   ?>
    </td>
    <td nowrap title="<?=@$Tz01_email?>">
       <?=@$Lz01_email?>
    </td>
    <td colspan='2'> 
    <?
     db_input('q53_email',60,$Iz01_email,true,'text',3,"")
    ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tz01_telef?>">
       <?=@$Lz01_telef?>
    </td>
    <td> 
     <?
      db_input('q53_fone',15,$Iz01_telef,true,'text',3,"")
     ?>
    </td>
    <td nowrap title="<?=@$Tq53_dtservico?>">
       <?=@$Lq53_dtservico?>
    </td>
    <td colspan='3'> 
    <?
    db_inputdata('q53_dtservico',@$q53_dtservico_dia,@$q53_dtservico_mes,@$q53_dtservico_ano,true,'text',$db_opcao,"")
    ?>
    </td>
   </tr>
  </table>
	</fieldset>
	<td></tr>
	</table>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
</form>
<script>
function js_pesquisaq53_issnotaavulsa(mostra){
  if(mostra==true){
  }else{
     if(document.form1.q53_issnotaavulsa.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_issnotaavulsa','db_iframe_issnotaavulsa','func_issnotaavulsa.php?pesquisa_chave='+document.form1.q53_issnotaavulsa.value+'&funcao_js=parent.js_mostraissnotaavulsa','Pesquisa',false);
     }else{
       document.form1.q51_numnota.value = ''; 
     }
  }
}
function js_mostraissnotaavulsa(chave,erro){
  document.form1.q51_numnota.value = chave; 
  if(erro==true){ 
    document.form1.q53_issnotaavulsa.focus(); 
    document.form1.q53_issnotaavulsa.value = ''; 
  }
}
function js_mostraissnotaavulsa1(chave1,chave2){
  document.form1.q53_issnotaavulsa.value = chave1;
  document.form1.q51_numnota.value       = chave2;
	document.form1.submit();
  db_iframe_issnotaavulsa.hide();
}
function js_pesquisaq52_inscr(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_issnotaavulsatomador','db_iframe_issbase','func_issbase.php?funcao_js=parent.js_mostraissbase1|q02_inscr|z01_nome','Pesquisa',true,'0');
  }else{
     if(document.form1.q54_inscr.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_issnotaavulsatomador','db_iframe_issbase','func_issbase.php?pesquisa_chave='+document.form1.q54_inscr.value+'&funcao_js=parent.js_mostraissbase','Pesquisa',false);
     }
  }
}
function js_mostraissbase(chave,erro){
	document.form1.q53_nome.value   =  chave;
  document.form1.q61_numcgm.value = '';
  if(erro==true){ 
    document.form1.q54_inscr.focus(); 
    document.form1.q54_inscr.value = ''; 
  }else{
       
		 document.form1.submit();
    
	}
}
function js_mostraissbase1(chave1,chave2){
  document.form1.q54_inscr.value  = chave1;
  document.form1.q53_nome.value   = chave2;
  document.form1.q61_numcgm.value = '';
  document.form1.submit();
  db_iframe_issbase.hide();
}
function js_pesquisa_tomador(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_issnotaavulsatomador','db_iframe_cgm','func_nome.php?testanome=true&funcao_js=parent.js_mostratomador1|z01_numcgm|z01_nome|z01_cgccpf','Pesquisa',true);
  } 
}
function js_mostratomador1(chave1,chave2,chave3){

  if (js_validaCgm(chave3)){
     document.form1.q61_numcgm.value = chave1;
     document.form1.q53_nome.value   = chave2;
     document.form1.q54_inscr.value  = '';
     document.form1.submit();
     db_iframe_cgm.hide();
  }else{

     alert('CGM com CPF/CNPJ inconsistente. Verifique');
  }
}

function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo.iframe_issnotaavulsa','db_iframe_issnotaavulsatomador','func_issnotaavulsatomador.php?funcao_js=parent.js_preenchepesquisa|q53_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_issnotaavulsatomador.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo.iframe_issnotaavulsa','db_iframe_issnotaavulsatomador','func_issnotaavulsatomador.php?funcao_js=parent.js_preenchepesquisa|q53_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_issnotaavulsatomador.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}

function js_validaCgm(cgccpf){

    if (cgccpf.length == 11){

       return js_chkcic(cgccpf);
     }else if(cgccpf.length == 14){
        
        return js_chkcnpj(cgccpf);

     }else {

       return false;
     }

}
function js_chkcic(vcic){
expr  = new
RegExp("0{11}|1{11}|2{11}|3{11}|4{11}|5{11}|6{11}|7{11}|8{11}|9{11}");
      if (vcic.match(expr)){
         return false;
      }
      if (isNaN(vcic) || vcic.length != 11){
         return false;
      }
      for (var vdigpos = 10; vdigpos < 12; vdigpos++ ){
         var vdig = 0;
         var vpos = 0;
         for (var vfator = vdigpos;vfator >= 2; vfator-- ){
            vdig = eval(vdig + vcic.substr(vpos,1) * vfator);
            vpos++;
         }
         vdig  = eval(11 -(vdig % 11)) < 10 ? eval(11 - vdig % 11) : 0;
         if (vdig != eval(vcic.substr(vdigpos-1,1))) {
           return false;
         }
      }
   return true;
}
//validação de cnpj
function js_chkcnpj(vcnpj){
     if (isNaN(vcnpj) || vcnpj.length != 14){
         return false;
      }
      for (var vdigpos = 13; vdigpos < 15; vdigpos++ ){
         var vdig = 0;
         var vpos = 0;
         for (var vfator = vdigpos - 8 ;vfator >= 2; vfator-- ){
            vdig = eval(vdig + vcnpj.substr(vpos,1) * vfator);
            vpos++;
         }
         for (var vfator = 9 ;vfator >= 2; vfator-- ){
            vdig = eval(vdig + vcnpj.substr(vpos,1) * vfator);
            vpos++;
         }
         vdig  = eval(11 -(vdig % 11)) < 10 ? eval(11 - vdig % 11) : 0;
         if (vdig != eval(vcnpj.substr(vdigpos-1,1))) {
           return false;
         }
      }
   return true;
}

function js_controlaAncora(lHabilita){

	if (lHabilita) {
	
	  document.getElementById('inscrAncora').onclick = function(event) {js_pesquisaq52_inscr(true);};
	  document.getElementById('cpfAncora').onclick   = function(event) {js_pesquisa_tomador(true);};
	  
	} else {
	
	  document.getElementById('inscrAncora').onclick  = "return false;";
	  document.getElementById('cpfAncora').onclick 	  = "return false;";
	  
	  document.getElementById('q54_inscr').style.backgroundColor = "#DEB887";
	  document.getElementById('q54_inscr').readOnly 			 = true;
	  
	}

}
 
 js_controlaAncora(true);

</script>