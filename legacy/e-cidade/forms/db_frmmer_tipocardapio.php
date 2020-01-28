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


//MODULO: merenda
$clmer_tipocardapio->rotulo->label();
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tme27_i_codigo?>">
      <?=@$Lme27_i_codigo?>
    </td>
    <td>
      <?db_input('me27_i_codigo',4,$Ime27_i_codigo,true,'text',3,"")?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tme27_c_nome?>">
      <?=@$Lme27_c_nome?>
    </td>
    <td>
      <?db_input('me27_c_nome',40,$Ime27_c_nome,true,'text',$db_opcao,"")?>
      <?=@$Lme27_f_versao?>
      <?db_input('me27_f_versao',5,$Ime27_f_versao,true,'text',3,"")?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tme27_d_inicio?>">
      <b>Data de Validade :</b>
    </td>
    <td>
      <?
      if (!isset($me27_d_inicio)) {
   	
        $me27_d_inicio=date("d/m/Y",db_getsession("DB_datausu"));
        $me27_d_inicio_dia=date("d",db_getsession("DB_datausu"));
        $me27_d_inicio_mes=date("m",db_getsession("DB_datausu"));
        $me27_d_inicio_ano=date("Y",db_getsession("DB_datausu"));
       
      }
      db_inputdata('me27_d_inicio',@$me27_d_inicio_dia,@$me27_d_inicio_mes,@$me27_d_inicio_ano,true,'text',$db_opcao,
                   "onchange=\"js_validadata();\"","",""," parent.js_validadata();");
      ?>
      <b>á</b>
      <?
      db_inputdata('me27_d_fim',@$me27_d_fim_dia,@$me27_d_fim_mes,@$me27_d_fim_ano,true,'text',$db_opcao,
                   "onchange=\"js_validadata();\"","",""," parent.js_validadata();");
      ?>   
    </td>
  </tr>
  <tr>
    <td>
      <?=@$Lme27_i_ano?>
    </td>
    <td>
      <select name="me27_i_ano">
      </select>
    </td>
  </tr>
</table>
</center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" 
       type="submit" id="db_opcao" 
       value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" 
              <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
<input name="novo" type="button" id="novo" value="Novo" 
       onclick="parent.location.href='mer1_mer_tipocardapio_aba001.php';" >
<?if ($naopode == true && $db_opcao != 3 && $db_opcao != 33) {?>
    <input name="newversao" id="newversao" value="Nova Versão" type="button" 
           onclick="js_NovaVersao(<?=$me27_i_codigo?>);">
<?}?>
<br>
<iframe name="iframe_newversao" src="" frameborder="0" width="0" heigth="0" style="visibility:hidden;"></iframe>
</form>
<script>
function js_pesquisa() {
	
  js_OpenJanelaIframe('',
	 	              'db_iframe_mer_tipocardapio',
		              'func_mer_tipocardapio.php?funcao_js=parent.js_preenchepesquisa|me27_i_codigo',
		              'Pesquisa',
		              true
		             );
  
}

function js_preenchepesquisa(chave) {
	
  db_iframe_mer_tipocardapio.hide();
  <?
   if ($db_opcao!=1) {
     echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
   }
 ?>
}

function js_validadata() {

  document.form1.me27_i_ano.length = null;	
  if (document.form1.me27_d_inicio.value=="") {
	  
    alert("Data inicial não informada!");
    document.form1.me27_d_inicio_dia.value = '';
    document.form1.me27_d_inicio_mes.value = '';
    document.form1.me27_d_inicio_ano.value = '';
    document.form1.me27_d_fim_dia.value = '';
    document.form1.me27_d_fim_mes.value = '';
    document.form1.me27_d_fim_ano.value = '';
    document.form1.me27_d_fim.value = '';
    document.form1.me27_d_inicio.focus();   
    return false;
      
  }
  d1 = document.form1.me27_d_inicio_dia.value;
  m1 = document.form1.me27_d_inicio_mes.value;
  a1 = document.form1.me27_d_inicio_ano.value;
  d2 = document.form1.me27_d_fim_dia.value;
  m2 = document.form1.me27_d_fim_mes.value;
  a2 = document.form1.me27_d_fim_ano.value;
  if (document.form1.me27_d_inicio.value!="" && document.form1.me27_d_fim.value=="") {
	  
   document.form1.me27_i_ano.options[document.form1.me27_i_ano.length] = new Option(a1,a1);
   for(t=1;t<=4;t++){
	   
	 prx_ano = parseInt(a1)+parseInt(t);
     document.form1.me27_i_ano.options[document.form1.me27_i_ano.length] = new Option(prx_ano,prx_ano);
     
   }
   
  } else if (document.form1.me27_d_inicio.value!="" && document.form1.me27_d_fim.value!="") {

    data1 = a1+m1+d1;
    data2 = a2+m2+d2;
    if (parseInt(data2)<parseInt(data1)) {

      alert("Data final deve ser maior ou igual a data inicial!");
      document.form1.me27_d_fim_dia.value = '';
      document.form1.me27_d_fim_mes.value = '';
      document.form1.me27_d_fim_ano.value = '';
      document.form1.me27_d_fim.value = '';
      document.form1.me27_d_fim.focus(); 
      if(document.form1.me27_d_inicio.value!=""){
    	js_validadata();
      }	
      
    }else{
        
	  if (a1!=a2) {

	    for(t=a1;t<=a2;t++){
          document.form1.me27_i_ano.options[document.form1.me27_i_ano.length] = new Option(t,t);			   
	    }

	  } else {
        document.form1.me27_i_ano.options[document.form1.me27_i_ano.length] = new Option(a1,a1);
	  }	 

    }
	  
  }
  
}
if(document.form1.me27_d_inicio.value!=""){
  js_validadata();
} 
function js_NovaVersao(cod_cardapio) {
    
  if (confirm("Confirmar criação de nova versão para este cardápio?")) {
    iframe_newversao.location.href='mer1_mer_tipocardapio004.php?cod_cardapio='+cod_cardapio;
  }
	  
}

<?if(isset($chavepesquisa)){?>
  document.form1.me27_i_ano.value = <?=$me27_i_ano?>;
<?}?>
</script>