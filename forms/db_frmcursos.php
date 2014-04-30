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
$clcurso->rotulo->label();
$clrotulo = new rotulocampo;
$db_botao = false;
?>
<form name="form1" method="post" action="">
 <center>
  <table border="0">
   <tr>
    <td nowrap title="<?=@$Ted29_i_codigo?>">
     <?=@$Led29_i_codigo?>
    </td>
    <td>
     <?db_input('ed29_i_codigo',10,$Ied29_i_codigo,true,'text',3,"")?>
    </td>
   </tr>
   <tr>
    <td nowrap title="<?=@$Ted29_c_descr?>">
     <?=@$Led29_c_descr?>
    </td>
    <td>
     <?db_input('ed29_c_descr',40,$Ied29_c_descr,true,'text',3,"")?>
    </td>
   </tr>
   <tr>
    <td nowrap title="<?=@$Ted29_i_ensino?>">
     <?db_ancora(@$Led29_i_ensino,"js_pesquisaed29_i_ensino(true);",3);?>
    </td>
    <td>
     <?db_input('ed29_i_ensino',10,$Ied29_i_ensino,true,'text',3," onchange='js_pesquisaed29_i_ensino(false);'")?>
     <?db_input('ed10_c_descr',30,@$Ied10_c_descr,true,'text',3,'')?>
    </td>
   </tr>
   <tr>
    <td nowrap title="<?=@$Ted29_c_historico?>">
     <?=@$Led29_c_historico?>
    </td>
    <td>
     <?
      $x = array('S'=>'SIM','N'=>'NÃO');
      db_select('ed29_c_historico',$x,true,3,"");
     ?>
    </td>
   </tr>
   </table>
 </center>
 <input type="hidden" name="ed71_i_codigo" value="<?=@$ed71_i_codigo?>"
 <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" 
        type="submit" id="db_opcao" 
        value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" 
        <?=($db_botao==false?"disabled":"")?> >
 <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();">
</form>
<script>
function js_pesquisaed29_i_ensino(mostra) {

  if (mostra == true) {
  
    js_OpenJanelaIframe('','db_iframe_ensino',
                        'func_ensino.php?funcao_js=parent.js_mostraensino1|ed10_i_codigo|ed10_c_descr',
                        'Pesquisa de Ensinos',true
                       );
                       
  } else {
  
    if (document.form1.ed29_i_ensino.value != '') {
    
      js_OpenJanelaIframe('','db_iframe_ensino','func_ensino.php?pesquisa_chave='+document.form1.ed29_i_ensino.value+
                          '&funcao_js=parent.js_mostraensino','Pesquisa Ensinos',false
                         );
                         
    } else {
      document.form1.ed10_c_descr.value = '';
    }
    
  }
  
}

function js_mostraensino(chave, erro) {

  document.form1.ed10_c_descr.value = chave;
  if (erro == true) {
  
    document.form1.ed29_i_ensino.focus();
    document.form1.ed29_i_ensino.value = '';
    
  }
  
}

function js_mostraensino1(chave1, chave2) {

  document.form1.ed29_i_ensino.value = chave1;
  document.form1.ed10_c_descr.value  = chave2;
  db_iframe_ensino.hide();
  
}

function js_pesquisa() {

  js_OpenJanelaIframe('','db_iframe_curso','func_cursoedu.php?funcao_js=parent.js_preenchepesquisa|ed29_i_codigo',
                      'Pesquisa de Cursos',true
                     );
                     
}

function js_preenchepesquisa(chave) {

  db_iframe_curso.hide();
  <?
   if ($db_opcao != 1) {
     echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
   }
 ?>
 
}
</script>