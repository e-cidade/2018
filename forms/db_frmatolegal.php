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

//MODULO: Educação
$oDaoAtoLegal->rotulo->label();
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
 <tr>
  <td nowrap title="<?=@$Ted05_i_codigo?>">
   <?=@$Led05_i_codigo?>
  </td>
  <td>
   <?db_input('ed05_i_codigo',15,$Ied05_i_codigo,true,'text',3,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted05_c_numero?>">
   <?=@$Led05_c_numero?>
  </td>
  <td>
   <?db_input('ed05_c_numero',10,$Ied05_c_numero,true,'text',$db_opcao,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted05_c_finalidade?>">
   <?=@$Led05_c_finalidade?>
  </td>
  <td>
   <?db_input('ed05_c_finalidade',50,$Ied05_c_finalidade,true,'text',$db_opcao,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted05_i_tipoato?>">
   <?db_ancora(@$Led05_i_tipoato," js_pesquisaed05_i_tipoato(true); ",$db_opcao);?>
  </td>
  <td>
   <?db_input('ed05_i_tipoato',15,@$Ied07_i_ato,true,'text',
              $db_opcao," onchange='js_pesquisaed05_i_tipoato(false)'; "
             )
   ?>
   <?db_input('ed83_c_descr',30,@$Ied83_c_descr,true,'text',3,'')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted05_c_competencia?>">
   <?=@$Led05_c_competencia?>
  </td>
  <td>
   <?
   $x = array('M'=>'Municipal','E'=>'Estadual','F'=>'Federal');
   db_select('ed05_c_competencia',$x,true,$db_opcao,"");
   ?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted05_i_ano?>">
   <?=@$Led05_i_ano?>
  </td>
  <td>
   <?db_input('ed05_i_ano',4,$Ied05_i_ano,true,'text',$db_opcao,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted05_c_orgao?>">
   <?=@$Led05_c_orgao?>
  </td>
  <td>
   <?db_input('ed05_c_orgao',50,$Ied05_c_orgao,true,'text',$db_opcao,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted05_d_vigora?>">
   <?=@$Led05_d_vigora?>
  </td>
  <td>
   <?db_inputdata('ed05_d_vigora',@$ed05_d_vigora_dia,@$ed05_d_vigora_mes,
                  @$ed05_d_vigora_ano,true,'text',$db_opcao,""
                 )
   ?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted05_d_aprovado?>">
   <?=@$Led05_d_aprovado?>
  </td>
  <td>
   <?db_inputdata('ed05_d_aprovado',@$ed05_d_aprovado_dia,@$ed05_d_aprovado_mes,
                  @$ed05_d_aprovado_ano,true,'text',$db_opcao,""
                 )
   ?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted05_d_publicado?>">
   <?=@$Led05_d_publicado?>
  </td>
  <td>
   <?db_inputdata('ed05_d_publicado',@$ed05_d_publicado_dia,@$ed05_d_publicado_mes,
                  @$ed05_d_publicado_ano,true,'text',$db_opcao,""
                 )
   ?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=$Ted05_i_aparecehistorico?>">
   <?=$Led05_i_aparecehistorico?>
  </td>
  <td>

    <?
    $aX = array('2'=>'NÃO', '1'=>'SIM');
    db_select('ed05_i_aparecehistorico', $aX, true, $db_opcao);
    ?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted05_t_texto?>" valign="top">
   <?=@$Led05_t_texto?>
  </td>
  <td>
   <?db_textarea('ed05_t_texto',4,50,$Ied05_t_texto,true,'text',$db_opcao,"")?>
  </td>
 </tr>
</table>
</center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" 
       type="submit" id="db_opcao" 
       value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" 
             <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();"
</form>
<script>
function js_pesquisaed05_i_tipoato(mostra) {
	
  if (mostra == true) {
	  
    js_OpenJanelaIframe('','db_iframe_tipoato','func_tipoato.php?funcao_js=parent.js_mostratipoato1|'+
    	                'ed83_i_codigo|ed83_c_descr','Pesquisa de Tipos Atos Legais',true
    	               );
    
  } else {
	  
    if (document.form1.ed05_i_tipoato.value != '') {
        
      js_OpenJanelaIframe('','db_iframe_tipoato','func_tipoato.php?pesquisa_chave='+document.form1.ed05_i_tipoato.value+
    	                  '&funcao_js=parent.js_mostratipoato','Pesquisa Atos Legais',false
    	                 );
      
    } else {
      document.form1.ed83_c_descr.value = '';
    }
    
  }
  
}

function js_mostratipoato(chave1,erro) {
	
  document.form1.ed83_c_descr.value = chave1;
  if (erro == true) {
	  
    document.form1.ed05_i_tipoato.value = '';
    document.form1.ed05_i_tipoato.focus();
    
  }
  
}

function js_mostratipoato1(chave1,chave2) {
	
  document.form1.ed05_i_tipoato.value = chave1;
  document.form1.ed83_c_descr.value   = chave2;
  db_iframe_tipoato.hide();
  
}

function js_pesquisa() {
	
  js_OpenJanelaIframe('','db_iframe_atolegal','func_atolegal.php?funcao_js=parent.js_preenchepesquisa|ed05_i_codigo',
		              'Pesquisa de Atos Legais',true
		             );
  
}

function js_preenchepesquisa(chave) {
	
  db_iframe_atolegal.hide();
  <?
  if ($db_opcao != 1) {
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
  
}

function js_novo() {
  parent.location.href="edu1_atolegalabas001.php";
}
</script>