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

//MODULO: educação
$oDaoProcedimento->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed37_i_codigo");
?>
<form name="form1" method="post" action="">
 <table border="0" align="left" width="100%">
  <tr>
   <td valign="top">
    <table border="0" align="left" width="100%">
     <tr>
      <td nowrap title="<?=@$Ted40_i_codigo?>" width="25%">
       <?=@$Led40_i_codigo?>
      </td>
      <td>
       <?db_input('ed40_i_codigo',20,$Ied40_i_codigo,true,'text',3,"")?>
      </td>
     </tr>
     <tr>
      <td nowrap title="<?=@$Ted40_i_formaavaliacao?>">
       <?db_ancora(@$Led40_i_formaavaliacao,"js_pesquisaed40_i_formaavaliacao(true);",$db_opcao1);?>
      </td>
      <td>
       <?db_input('ed40_i_formaavaliacao',20,$Ied40_i_formaavaliacao,true,'text',$db_opcao1,
                  " onchange='js_pesquisaed40_i_formaavaliacao(false);'"
                 )
       ?>
       <?db_input('ed37_c_descr',40,@$Ied37_c_descr,true,'text',3,'')?>
      </td>
     </tr>
     <tr>
      <td nowrap title="<?=@$Ted40_c_descr?>">
       <?=@$Led40_c_descr?>
      </td>
      <td>
       <?db_input('ed40_c_descr',40,$Ied40_c_descr,true,'text',$db_opcao,"")?>
      </td>
     </tr>
     <tr>
      <td nowrap title="<?=@$Ted40_i_percfreq?>">
       <b>Freq. Mínima p/ Aprovação:</b>
      </td>
      <td>
       <?db_input('ed40_i_percfreq',10,$Ied40_i_percfreq,true,'text',$db_opcao,
                  " onchange='js_verificaperc(this.value);' "
                 )?> % (0-100)
      </td>
     </tr>
     <tr>
      <td nowrap title="<?=@$Ted40_i_calcfreq?>">
       <?=@$Led40_i_calcfreq?>
      </td>
      <td>
       <?
         $xx = array(""=>"","1"=>"Por Disciplina","2"=>"Por Carga Horária Total");
         db_select('ed40_i_calcfreq',$xx,true,$db_opcao,"");
       ?>
      </td>
     </tr>
     <tr>
      <td colspan="2">
       <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" 
              type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" 
              <?=($db_botao==false?"disabled":"")?> >
       <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();">
       <input name="novo" type="button" id="novo" value="Novo Registro" 
              onclick="js_novo()" <?=$db_opcao==1?"disabled":""?>>
      </td>
     </tr>
    </table>
   </td>
   <td valign="top">
    <iframe src="" name="iframe_aval" id="iframe_aval" width="170" height="100" frameborder="0"></iframe>
   </td>
  </tr>
  <tr>
   <td nowrap title="<?=@$Ted40_c_contrfreqmpd?>" colspan="2">
    <?
      $ed40_c_contrfreqmpd = "I";
      db_input('ed40_c_contrfreqmpd',10,$Ied40_c_contrfreqmpd,true,'hidden',$db_opcao,"")
    ?>
   </td>
  </tr>
 </table>
</form>
<script>
function js_pesquisaed40_i_formaavaliacao(mostra) {
	
  if (mostra == true) {
	  
    js_OpenJanelaIframe('','db_iframe_formaavaliacao','func_formaavaliacao.php?funcao_js=parent.js_mostraformaavaliacao1'+
    	                '|ed37_i_codigo|ed37_c_descr','Pesquisa de Formas de Avaliação',true
    	               );
    
  } else {
	  
    if (document.form1.ed40_i_formaavaliacao.value != '') {
        
      js_OpenJanelaIframe('','db_iframe_formaavaliacao',
    	                  'func_formaavaliacao.php?pesquisa_chave='+document.form1.ed40_i_formaavaliacao.value+
    	                  '&funcao_js=parent.js_mostraformaavaliacao','Pesquisa',false
    	                 );
      
    } else {
      document.form1.ed37_c_descr.value = '';
    }
    
  }
  
}

function js_mostraformaavaliacao(chave, erro) {
	
  document.form1.ed37_c_descr.value = chave;
  if (erro == true) {
	  
    document.form1.ed40_i_formaavaliacao.focus();
    document.form1.ed40_i_formaavaliacao.value = '';
    iframe_aval.location.href = "edu1_procedimento004.php";
    
  } else {
    iframe_aval.location.href = "edu1_procedimento004.php?codigo="+document.form1.ed40_i_formaavaliacao.value;
  }
  
}

function js_mostraformaavaliacao1(chave1, chave2) {
	
  document.form1.ed40_i_formaavaliacao.value = chave1;
  document.form1.ed37_c_descr.value          = chave2;
  iframe_aval.location.href                  = "edu1_procedimento004.php?codigo="+chave1;
  db_iframe_formaavaliacao.hide();
  
}

function js_pesquisa() {
	
  js_OpenJanelaIframe('','db_iframe_procedimento','func_procedimento.php?funcao_js=parent.js_preenchepesquisa|'+
		              'ed40_i_codigo','Pesquisa de Procedimentos de Avaliação',true
		             );
  
}

function js_preenchepesquisa(chave) {
	
  db_iframe_procedimento.hide();
  <?
    if ($db_opcao != 1) {
      echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
    }
  ?>
  
}

function js_verificaperc(valor) {
	
  if (valor != "") {
	  
    if (parseFloat(valor) < 0 || parseFloat(valor) > 100) {
        
      alert("Percentual deve estar entre 0 e 100!");
      document.form1.ed40_i_percfreq.value = "";
      
    }
    
  }
  
}

function js_novo() {
  parent.location.href="edu1_procedimentoabas001.php";
}
</script>