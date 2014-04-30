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
$clmer_requi->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed18_i_codigo");
$clrotulo->label("nome");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tme16_i_codigo?>">
       <?=@$Lme16_i_codigo?>
    </td>
    <td> 
    <?db_input('me16_i_codigo',5,$Ime16_i_codigo,true,'text',$db_opcao,"")?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tme16_t_obs?>">
       <?=@$Lme16_t_obs?>
    </td>
    <td> 
    <?db_textarea('me16_t_obs',0,0,$Ime16_t_obs,true,'text',$db_opcao,"") ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tme16_d_data?>">
       <?=@$Lme16_d_data?>
    </td>
    <td> 
     <?db_inputdata('me16_d_data',@$me16_d_data_dia,@$me16_d_data_mes,@$me16_d_data_ano,true,'text',$db_opcao,"")?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tme16_c_hora?>">
       <?=@$Lme16_c_hora?>
    </td>
    <td> 
     <?db_input('me16_c_hora',5,$Ime16_c_hora,true,'text',$db_opcao,"")?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tme16_c_tiposaida?>">
       <?=@$Lme16_c_tiposaida?>
    </td>
    <td> 
     <?db_input('me16_c_tiposaida',2,$Ime16_c_tiposaida,true,'text',$db_opcao,"")?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tme16_i_escola?>">
     <?db_ancora(@$Lme16_i_escola,"js_pesquisame16_i_escola(true);",$db_opcao);?>
    </td>
    <td> 
    <?db_input('me16_i_escola',5,$Ime16_i_escola,true,'text',$db_opcao," onchange='js_pesquisame16_i_escola(false);'")?>
    <?db_input('ed18_i_codigo',20,$Ied18_i_codigo,true,'text',3,'')?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tme16_i_dbusuario?>">
     <?db_ancora(@$Lme16_i_dbusuario,"js_pesquisame16_i_dbusuario(true);",$db_opcao);?>
    </td>
    <td> 
    <?db_input('me16_i_dbusuario',5,$Ime16_i_dbusuario,true,'text',$db_opcao,
                " onchange='js_pesquisame16_i_dbusuario(false);'"
              )
    ?>
    <?db_input('nome',40,$Inome,true,'text',3,'')?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" 
       type="submit" 
       id="db_opcao" 
       value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" 
       <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisame16_i_escola(mostra) {
	
  if (mostra==true) {
	  
    js_OpenJanelaIframe('top.corpo','db_iframe_escola',
    	                'func_escola.php?funcao_js=parent.js_mostraescola1|ed18_i_codigo|ed18_i_codigo','Pesquisa',true
    	               );
    
  } else {
	  
     if (document.form1.me16_i_escola.value != '') {
          
        js_OpenJanelaIframe('top.corpo','db_iframe_escola',
                            'func_escola.php?pesquisa_chave='+document.form1.me16_i_escola.value+
                            '&funcao_js=parent.js_mostraescola','Pesquisa',false
                           );
        
     } else {
       document.form1.ed18_i_codigo.value = ''; 
     }
  }
}

function js_mostraescola(chave,erro) {
	
  document.form1.ed18_i_codigo.value = chave; 
  if (erro==true) {
	   
    document.form1.me16_i_escola.focus(); 
    document.form1.me16_i_escola.value = '';
     
  }
}

function js_mostraescola1(chave1,chave2) {
	
  document.form1.me16_i_escola.value = chave1;
  document.form1.ed18_i_codigo.value = chave2;
  db_iframe_escola.hide();
  
}

function js_pesquisame16_i_dbusuario(mostra) {
	
  if (mostra==true) {
	  
    js_OpenJanelaIframe('top.corpo','db_iframe_db_usuarios',
    	                'func_db_usuarios.php?funcao_js=parent.js_mostradb_usuarios1|id_usuario|nome','Pesquisa',true
    	               );
    
  }else{
	  
     if(document.form1.me16_i_dbusuario.value != ''){
          
        js_OpenJanelaIframe('top.corpo','db_iframe_db_usuarios',
                            'func_db_usuarios.php?pesquisa_chave='+document.form1.me16_i_dbusuario.value+
                             '&funcao_js=parent.js_mostradb_usuarios','Pesquisa',false
                           );
        
     }else{
       document.form1.nome.value = ''; 
     }
  }
}

function js_mostradb_usuarios(chave,erro) {
	
  document.form1.nome.value = chave; 
  if (erro==true) {
	   
    document.form1.me16_i_dbusuario.focus(); 
    document.form1.me16_i_dbusuario.value = '';
     
  }
}

function js_mostradb_usuarios1(chave1,chave2) {
	
  document.form1.me16_i_dbusuario.value = chave1;
  document.form1.nome.value = chave2;
  db_iframe_db_usuarios.hide();
  
}

function js_pesquisa() {
	
  js_OpenJanelaIframe('top.corpo','db_iframe_mer_requi',
		              'func_mer_requi.php?funcao_js=parent.js_preenchepesquisa|me16_i_codigo','Pesquisa',true
		             );
  
}
function js_preenchepesquisa(chave) {
	
  db_iframe_mer_requi.hide();
  <?
  if ($db_opcao!=1) {
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>