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

//MODULO: ISSQN
$clissbaselog->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("q103_descricao");

if (isset($db_opcao) && $db_opcao == 1) {
	
  $q102_data_dia = date("d",db_getsession("DB_datausu"));
  $q102_data_mes = date("m",db_getsession("DB_datausu"));
  $q102_data_ano = date("Y",db_getsession("DB_datausu"));
  $q102_hora     = db_hora();
}
?>
<form name="form1" method="post" action="">
<fieldset>
  <legend><b>Controle de Alteração Cadastral</b></legend>
<table border="0" align="center">
  <tr>
    <td nowrap title="<?=@$Tq102_sequencial?>">
       <?=@$Lq102_sequencial?>
    </td>
    <td> 
			<?
			  db_input('q102_sequencial',10,$Iq102_sequencial,true,'text',3,"")
			?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq102_inscr?>">
      <?
        db_ancora(@$Lq102_inscr,"js_pesquisaq102_inscr(true);",$db_opcao);
      ?>
    </td>
    <td> 
			<?
			  db_input('q102_inscr',10,$Iq102_inscr,true,'text',$db_opcao," onchange='js_pesquisaq102_inscr(false);'")
			?>
      <?
        db_input('z01_nome',40,$Iz01_nome,true,'text',3,'')
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq102_issbaselogtipo?>">
      <?
        db_ancora(@$Lq102_issbaselogtipo,"js_pesquisaq102_issbaselogtipo(true);",$db_opcao);
      ?>
    </td>
    <td> 
		  <?
		    db_input('q102_issbaselogtipo',10,$Iq102_issbaselogtipo,true,'text',$db_opcao,
		             " onchange='js_pesquisaq102_issbaselogtipo(false);'");
		  ?>
      <?
        db_input('q103_descricao',40,$Iq103_descricao,true,'text',3,'');
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq102_data?>">
       <?=@$Lq102_data?>
    </td>
    <td> 
			<?
			  db_inputdata('q102_data',@$q102_data_dia,@$q102_data_mes,@$q102_data_ano,true,'text',$db_opcao,"")
			?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq102_hora?>">
       <?=@$Lq102_hora?>
    </td>
    <td> 
			<?
			  db_input('q102_hora',10,$Iq102_hora,true,'text',
			            $db_opcao,"onchange='js_formatValidaHora(this.value,this.name,24)'; 
			                       onkeypress='return js_mask(event,\"0-9|:\");'");
			?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq102_obs?>">
       <?=@$Lq102_obs?>
    </td>
    <td> 
			<?
			  db_textarea('q102_obs',5,51,$Iq102_obs,true,'text',$db_opcao,"")
			?>
    </td>
  </tr>
  </table>
</fieldset>
<table cellpadding="0" cellspacing="0" align="center">
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>
      <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" 
             type="submit" id="db_opcao" 
             value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" 
             <?=($db_botao==false?"disabled":"")?>>
    </td>
    <td>
      <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();"
             <?=($db_opcao==1?"disabled":($db_opcao!=1||$db_opcao==22?"":""))?>>
    </td>
  </tr>
</table>
</form>
<script>
function js_formatValidaHora(sString,sCampo,iTipo) {

  var iTamanho = sString.length;
  var iPosicao = sString.indexOf(":"); 

  var lErro    = false;
  var iHora    = "";
  var iMinuto  = "";
  var sMsgErro = "";

  if (iTipo != 12 && iTipo != 24) {
    lErro = true;
  }
  
  if (iTipo === 12) {
    
	  if (iPosicao != -1) {
	  
	    if(iPosicao == 0 || iPosicao > 2){
	    
	      lErro    = true;
	    } else {
	    
	      if (iPosicao == 1) {
	      
	        iHora   = "0"+sString.substr(0,1);
	        iMinuto = sString.substr(iPosicao+1,2);
	      } else if (iPosicao == 2) {
	      
	        iHora   = sString.substr(0,2);
	        iMinuto = sString.substr(iPosicao+1,2);
	      }
	      
	      if (iMinuto == "") {
	        iMinuto = "00";
	      }
	    }
	  } else {
	  
	    if (iTamanho >= 4) {
	    
	      iHora   = sString.substr(0,2);
	      iMinuto = sString.substr(2,2);
	    } else if (iTamanho == 3) {
	    
	      iHora   = '0'+sString.substr(0,1);
	      iMinuto = sString.substr(1,2);
	    } else if (iTamanho == 2) {
	    
	      iHora   = sString;
	      iMinuto = '00';
	    } else if (iTamanho == 1) {
	    
	      iHora   = '0'+sString;
	      iMinuto = '00';
	    }
	  }
	  
	  if (iMinuto != "" && iHora != "") {
	  
	    if (iHora > 12 || iHora < 0 || iMinuto > 60 || iMinuto < 0) {
	    
	      lErro    = true;
	    } else {
	    
	      if (iMinuto == 60) {
	        iMinuto = '59';
	      }
	    }    
	  }
	  
	  if (lErro) {
	  
	    alert(sMsgErro);
	    eval("document.form1."+sCampo+".focus();");
	    eval("document.form1."+sCampo+".value='';");
	    return false;
	  }
            
    if (sString != "") {    
      eval("document.form1."+sCampo+".value='"+iHora+":"+iMinuto+"';");
    }
  } else if (iTipo === 24) {
    
    if (iPosicao != -1) {
    
      if (iPosicao == 0 || iPosicao > 2) {
      
        lErro    = true;
      } else {
      
        if (iPosicao == 1) {
        
          iHora   = "0"+sString.substr(0,1);
          iMinuto = sString.substr(iPosicao+1,2);
        } else if (iPosicao == 2) {
        
          iHora   = sString.substr(0,2);
          iMinuto = sString.substr(iPosicao+1,2);
        }
        
        if (iMinuto == "") {
          iMinuto = "00";
        }
      }
    } else {
    
      if (iTamanho >= 4) {
      
        iHora   = sString.substr(0,2);
        iMinuto = sString.substr(2,2);
      } else if (iTamanho == 3) {
      
        iHora   = "0"+sString.substr(0,1);
        iMinuto = sString.substr(1,2);
      } else if (iTamanho == 2) {
      
        iHora   = sString;
        iMinuto = "00";
      } else if (iTamanho == 1) {
      
        iHora   = "0"+sString;
        iMinuto = "00";
      }
    }
    
    if (iMinuto != "" && iHora != "") {
    
      if (iHora > 24 || iHora < 0 || iMinuto > 60 || iMinuto < 0) {
      
        lErro    = true;
      } else {
      
        if (iMinuto == 60) {
          iMinuto = "59";
        }
        
        if (iHora == 24) {
          iHora = "00";
        }
      }    
    }
    
    if (lErro) {
    
      eval("document.form1."+sCampo+".focus();");
      eval("document.form1."+sCampo+".value='';");
      return false;
    }
            
    if (sString != "") {    
      eval("document.form1."+sCampo+".value='"+iHora+":"+iMinuto+"';");
    }
  }
} 

function js_pesquisaq102_inscr(mostra){
  var sUrl1 = 'func_issbase.php?funcao_js=parent.js_mostraissbase1|q02_inscr|z01_nome';
  var sUrl2 = 'func_issbase.php?pesquisa_chave='+document.form1.q102_inscr.value+'&funcao_js=parent.js_mostraissbase';
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_issbase',sUrl1,'Pesquisa',true);
  }else{
     if(document.form1.q102_inscr.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_issbase',sUrl2,'Pesquisa',false);
     }else{
       document.form1.z01_nome.value = ''; 
     }
  }
}
function js_mostraissbase(chave,erro,chave2){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.q102_inscr.focus(); 
    document.form1.q102_inscr.value = ''; 
  }
}
function js_mostraissbase1(chave1,chave2){
  document.form1.q102_inscr.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_issbase.hide();
}

function js_pesquisaq102_issbaselogtipo(mostra){

  var q102_issbaselogtipo = document.form1.q102_issbaselogtipo.value;
  var sParam              = '&lPeriodo=true&lAtivos=true';
  var sFunc1              = 'funcao_js=parent.js_mostraissbaselogtipo1|q103_sequencial|q103_descricao';
  var sUrl1               = 'func_issbaselogtipo.php?'+sFunc1+sParam;
  var sFunc2              =  '&funcao_js=parent.js_mostraissbaselogtipo';
  var sUrl2               = 'func_issbaselogtipo.php?pesquisa_chave='+q102_issbaselogtipo+sFunc2+sParam;

  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_issbaselogtipo',sUrl1,'Pesquisa',true);
  } else {
     if(document.form1.q102_issbaselogtipo.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_issbaselogtipo',sUrl2,'Pesquisa',false);
     } else {
       document.form1.q103_descricao.value = ''; 
     }
  }
}
function js_mostraissbaselogtipo(chave,erro){
  document.form1.q103_descricao.value = chave; 
  if(erro==true){ 
    document.form1.q102_issbaselogtipo.focus(); 
    document.form1.q102_issbaselogtipo.value = ''; 
  }
}
function js_mostraissbaselogtipo1(chave1,chave2){
  document.form1.q102_issbaselogtipo.value = chave1;
  document.form1.q103_descricao.value = chave2;
  db_iframe_issbaselogtipo.hide();
}

function js_pesquisa(){
  var sUrl = 'func_issbaselog.php?funcao_js=parent.js_preenchepesquisa|q102_sequencial';
  js_OpenJanelaIframe('top.corpo','db_iframe_issbaselog',sUrl,'Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_issbaselog.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>