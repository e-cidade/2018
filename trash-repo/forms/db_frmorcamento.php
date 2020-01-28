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

$clpcorcam->rotulo->label();
?>
<form name="form1" method="post" action="">
<center>
<table border="0" >
  <tr>
    <td nowrap title="<?=@$Tpc20_codorc?>">
      <?=@$Lpc20_codorc?>
    </td>
    <td> 
	  <?
  		db_input('pc20_codorc',8,$Ipc20_codorc,true,'text',3);
  		db_input('pc10_numero',8,0,true,'hidden',3);
	  ?>
	</td>
    <td>
   	  <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();">
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tpc20_dtate?>">
      <?=@$Lpc20_dtate?>
    </td>
    <td colspan="2"> 
	  <?
	   if(!isset($pc20_dtate_dia) && !isset($pc20_dtate_mes) && !isset($pc20_dtate_ano) && !isset($pc20_dtate)){
		 
	   	 $somadata = $clpcparam->sql_record($clpcparam->sql_query_file(db_getsession("DB_instit"),"pc30_dias,pc30_horas as pc20_hrate"));
	   	 
		 if($clpcparam->numrows>0){
		    db_fieldsmemory($somadata,0);
		    $diadefault = $clpcparam->sql_record("select date_part('year','".date("Y-m-d",db_getsession("DB_datausu"))."'::date + '$pc30_dias days'::interval) as pc20_dtate_ano,date_part('months','".date("Y-m-d",db_getsession("DB_datausu"))."'::date + '$pc30_dias days'::interval) as pc20_dtate_mes,date_part('day','".date("Y-m-d",db_getsession("DB_datausu"))."'::date + '$pc30_dias days'::interval) as pc20_dtate_dia");
		    db_fieldsmemory($diadefault,0);
		    if($pc20_dtate_dia<10){
		      $pc20_dtate_dia = "0".$pc20_dtate_dia;
		    }
		    if($pc20_dtate_mes<10){
		      $pc20_dtate_mes = "0".$pc20_dtate_mes;
		    }
		  }
		}
		db_inputdata('pc20_dtate',@$pc20_dtate_dia,@$pc20_dtate_mes,@$pc20_dtate_ano,true,'text',$db_opcao,'',"","");
	  ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tpc20_hrate?>">
      <?=@$Lpc20_hrate?>
    </td>
    <td> 
	  <?
	   if(!isset($pc20_hrate)){
	     $pc20_hrate = db_hora();
	   }
	    
	   db_input('pc20_hrate',8,$Ipc20_hrate,true,'text',$db_opcao,"onchange='js_verifica_hora(this.value,this.name);'")
	  ?>
    </td>
    <td align="left">
     <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?>>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=$Tpc20_obs?>">
    <?=$Lpc20_obs?>
    </td>
    <td colspan="2">
    <?
      @$pc20_obs = stripslashes($pc20_obs);
      db_textarea("pc20_obs",10,81,"",true,"text",$db_opcao,"onKeydown='js_validaTamanhoObs(this,event)' onKeyUp='js_incrementaCaracDig(this.value.length)'");
    ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tpc20_prazoentrega?>">
     <?=@$Lpc20_prazoentrega?>
    </td>
    <td>
     <?
       db_input('pc20_prazoentrega',10,$Ipc20_prazoentrega,true,'text',$db_opcao);
      ?>
    </td>
    <td align="right">
      <b>  Caracteres Digitados : </b>
      <input type="text" name="obsdig" id="obsdig" size="3" value="0" disabled>
      <b> - Limite 800  </b>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tpc20_validadeorcamento?>">
     <?=@$Lpc20_validadeorcamento?>
    </td>
    <td colspan="2">
    <?
    db_input('pc20_validadeorcamento',10,$Ipc20_validadeorcamento,true,'text',$db_opcao);
    ?>
    </td>
  </tr>




</table>
</center>
<hr>
<center>
<table width="100%">
  <tr>
    <td>
      <center>
        <iframe name='iframe_itens' id='iframe_itens'  marginwidth='0' marginheight='0' frameborder='0' src='com1_itens001.php?pc22_codorc=<?=@$pc20_codorc?>&pc10_numero=<?=@$pc10_numero?>&db_opcaoal=<?=@$db_opcaoal?>&db_chama=<?=@$db_chama?>' width='95%' height='350'></iframe>
      </center>
    </td>
  </tr>
</table>
</center>
</form>
<script>

function js_validaTamanhoObs(elem,event){
  
  var sValorCampo   = new String(elem.value);
  var iTamanhoCampo = sValorCampo.length;
  var iLimite		= 800;
  
  if (event.keyCode != 8 && event.keyCode != 16 && event.keyCode != 20 && event.keyCode != 18){ 
    if ( iTamanhoCampo >= iLimite ) {
      elem.value = sValorCampo.substr(0,iLimite);
      js_incrementaCaracDig(elem.value.length);
      alert('Máximo '+iLimite+' caracteres!');
      return false;
    } 
  }
  
  js_incrementaCaracDig(iTamanhoCampo);
  
}

function js_incrementaCaracDig(iTamanhoCampo){
  document.getElementById('obsdig').value = iTamanhoCampo;
}

function js_verifica_hora(valor,campo){
  erro= 0;
  <?
  $hora = "00";
  $minu = "00";
  if(isset($pc30_horas)){
    $arr_horas = split(":",$pc30_horas);    
    $hora = $arr_horas[0];
    $minu = $arr_horas[1];
  }
  ?>
  hora= "<?$hora?>" ;
  minu= "<?$minu?>";
  
  ms  = "";
  hs  = "";
  
  tam = "";
  pos = "";
  tam = valor.length;
  pos = valor.indexOf(":");  
  if(pos!=-1){
    if(pos==0 || pos>2){
      erro++;
    }else{
      if(pos==1){
	hs = "0"+valor.substr(0,1);
	ms = valor.substr(pos+1,2);
      }else if(pos==2){
        hs = valor.substr(0,2);
        ms = valor.substr(pos+1,2);
      }
      if(ms==""){
	ms = "00";
      }
    }
  }else{
    if(tam>=4){
      hs = valor.substr(0,2);
      ms = valor.substr(2,2);
    }else if(tam==3){
      hs = "0"+valor.substr(0,1);
      ms = valor.substr(1,2);
    }else if(tam==2){
      hs = valor;
      ms = "00";
    }else if(tam==1){
      hs = "0"+valor;
      ms = "00";
    }
  }
  if(ms!="" && hs!=""){
    if(hs>24 || hs<0 || ms>60 || ms<0){
      erro++
    }else{
      if(ms==60){
	ms = "59";
      }
      if(hs==24){
	hs = "00";
      }
      hora = hs;
      minu = ms;
    }    
  }

  if(erro>0){
    alert("Informe uma hora válida.");
  }
  if(valor!=""){    
    eval("document.form1."+campo+".focus();");
    eval("document.form1."+campo+".value='"+hora+":"+minu+"';");
  }
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo.iframe_orcam','db_iframe_pcorcam','func_orcsolicita.php?sol=true&funcao_js=parent.js_preenchepesquisa|pc20_codorc&departamento=<?=db_getsession("DB_coddepto")?>','Pesquisa',true,'0');
}
function js_preenchepesquisa(chave){
  db_iframe_pcorcam.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}

 js_incrementaCaracDig(document.form1.pc20_obs.value.length);
 
</script>