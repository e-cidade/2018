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

//MODULO: Merenda
$clmer_consumoescola->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("me32_i_codigo");
$clrotulo->label("me21_i_codigo");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tme38_i_codigo?>">
       <?=@$Lme38_i_codigo?>
    </td>
    <td> 
     <?db_input('me38_i_codigo',10,$Ime38_i_codigo,true,'text',3,"")?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tme38_i_cardapioescola?>">
     <?db_ancora(@$Lme38_i_cardapioescola,"js_pesquisame38_i_cardapioescola(true);",$db_opcao);?>
    </td>
    <td> 
     <?db_input('me38_i_cardapioescola',10,$Ime38_i_cardapioescola,true,'text',$db_opcao,
                " onchange='js_pesquisame38_i_cardapioescola(false);'"
               )
     ?>
     <?db_input('me32_i_codigo',10,$Ime32_i_codigo,true,'text',3,'')?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tme38_i_cardapiotipo?>">
       <?
       db_ancora(@$Lme38_i_cardapiotipo,"js_pesquisame38_i_cardapiotipo(true);",$db_opcao);
       ?>
    </td>
    <td> 
     <?db_input('me38_i_cardapiotipo',10,$Ime38_i_cardapiotipo,true,'text',$db_opcao,
                " onchange='js_pesquisame38_i_cardapiotipo(false);'"
               )
     ?>
     <?db_input('me21_i_codigo',4,$Ime21_i_codigo,true,'text',3,'')?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tme38_i_ordem?>">
       <?=@$Lme38_i_ordem?>
    </td>
    <td> 
     <?db_input('me38_i_ordem',10,$Ime38_i_ordem,true,'text',$db_opcao,"")?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>"
       type="submit" id="db_opcao"
       value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>"
        <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisame38_i_cardapioescola(mostra) {

  if (mostra == true) {

    js_OpenJanelaIframe('top.corpo','db_iframe_mer_cardapioescola',
                        'func_mer_cardapioescola.php?funcao_js=parent.js_mostramer_cardapioescola1|me32_i_codigo|me32_i_codigo',
                        'Pesquisa',true
                       );

  } else {

     if (document.form1.me38_i_cardapioescola.value != '') { 

        js_OpenJanelaIframe('top.corpo','db_iframe_mer_cardapioescola',
                             'func_mer_cardapioescola.php?pesquisa_chave='+document.form1.me38_i_cardapioescola.value+
                             '&funcao_js=parent.js_mostramer_cardapioescola',
                             'Pesquisa',false
                           )
     } else {
       document.form1.me32_i_codigo.value = ''; 
     }
  }
}

function js_mostramer_cardapioescola(chave,erro) {

  document.form1.me32_i_codigo.value = chave; 
  if (erro == true) { 

    document.form1.me38_i_cardapioescola.focus(); 
    document.form1.me38_i_cardapioescola.value = ''; 

  }

}

function js_mostramer_cardapioescola1(chave1,chave2) {

  document.form1.me38_i_cardapioescola.value = chave1;
  document.form1.me32_i_codigo.value         = chave2;
  db_iframe_mer_cardapioescola.hide();

}

function js_pesquisame38_i_cardapiotipo(mostra) {

  if (mostra == true) {

    js_OpenJanelaIframe('top.corpo','db_iframe_mer_cardapiotipo',
                        'func_mer_cardapiotipo.php?funcao_js=parent.js_mostramer_cardapiotipo1|me21_i_codigo|me21_i_codigo',
                        'Pesquisa',true
                       );

  } else {

     if (document.form1.me38_i_cardapiotipo.value != '') { 

        js_OpenJanelaIframe('top.corpo','db_iframe_mer_cardapiotipo',
                             'func_mer_cardapiotipo.php?pesquisa_chave='+document.form1.me38_i_cardapiotipo.value+
                             '&funcao_js=parent.js_mostramer_cardapiotipo',
                             'Pesquisa',false
                           )
     } else {
       document.form1.me21_i_codigo.value = ''; 
     }
  }
}

function js_mostramer_cardapiotipo(chave,erro) {

  document.form1.me21_i_codigo.value = chave; 
  if (erro == true) { 

    document.form1.me38_i_cardapiotipo.focus(); 
    document.form1.me38_i_cardapiotipo.value = ''; 

  }

}

function js_mostramer_cardapiotipo1(chave1,chave2) {

  document.form1.me38_i_cardapiotipo.value = chave1;
  document.form1.me21_i_codigo.value   = chave2;
  db_iframe_mer_cardapiotipo.hide();

}

function js_pesquisa() {

  js_OpenJanelaIframe('top.corpo','db_iframe_mer_consumoescola',
                       'func_mer_consumoescola.php?funcao_js=parent.js_preenchepesquisa|me38_i_codigo','Pesquisa',true);

}

function js_preenchepesquisa(chave) {

  db_iframe_mer_consumoescola.hide();
  <?
  if ($db_opcao != 1) {

    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>