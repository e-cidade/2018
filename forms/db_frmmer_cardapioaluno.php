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
$clmer_cardapioaluno->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("me12_i_codigo");
$clrotulo->label("ed60_i_codigo");
$clrotulo->label("nome");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tme11_i_codigo?>">
       <?=@$Lme11_i_codigo?>
    </td>
    <td> 
    <?db_input('me11_i_codigo',5,$Ime11_i_codigo,true,'text',$db_opcao,"")?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tme11_i_cardapiodia?>">
     <?db_ancora(@$Lme11_i_cardapiodia,"js_pesquisame11_i_cardapiodia(true);",$db_opcao);?>
    </td>
    <td> 
     <?db_input('me11_i_cardapiodia',5,$Ime11_i_cardapiodia,true,'text',$db_opcao,
                " onchange='js_pesquisame11_i_cardapiodia(false);'"
               )
     ?>
     <?db_input('me12_i_codigo',5,$Ime12_i_codigo,true,'text',3,'')?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tme11_i_matricula?>">
     <?db_ancora(@$Lme11_i_matricula,"js_pesquisame11_i_matricula(true);",$db_opcao);?>
    </td>
    <td> 
     <?db_input('me11_i_matricula',5,$Ime11_i_matricula,true,'text',$db_opcao,
                " onchange='js_pesquisame11_i_matricula(false);'"
               )
     ?>
     <?db_input('ed60_i_codigo',20,$Ied60_i_codigo,true,'text',3,'')?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tme11_d_data?>">
       <?=@$Lme11_d_data?>
    </td>
    <td> 
     <?db_inputdata('me11_d_data',@$me11_d_data_dia,@$me11_d_data_mes,@$me11_d_data_ano,true,'text',$db_opcao,"")?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tme11_i_usuario?>">
     <?db_ancora(@$Lme11_i_usuario,"js_pesquisame11_i_usuario(true);",$db_opcao);?>
    </td>
    <td> 
     <?db_input('me11_i_usuario',10,$Ime11_i_usuario,true,'text',$db_opcao,
                " onchange='js_pesquisame11_i_usuario(false);'"
               )
     ?>
     <?db_input('nome',40,$Inome,true,'text',3,'')?>
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
function js_pesquisame11_i_cardapiodia(mostra) {

  if (mostra == true) {

    js_OpenJanelaIframe('top.corpo','db_iframe_mer_cardapiodia',
                        'func_mer_cardapiodia.php?funcao_js=parent.js_mostramer_cardapiodia1|me12_i_codigo|me12_i_codigo',
                        'Pesquisa',true
                       );

  } else {

     if (document.form1.me11_i_cardapiodia.value != '') { 

        js_OpenJanelaIframe('top.corpo','db_iframe_mer_cardapiodia',
                             'func_mer_cardapiodia.php?pesquisa_chave='+document.form1.me11_i_cardapiodia.value+
                             '&funcao_js=parent.js_mostramer_cardapiodia',
                             'Pesquisa',false
                           )
     } else {
       document.form1.me12_i_codigo.value = ''; 
     }
  }
}

function js_mostramer_cardapiodia(chave,erro) {

  document.form1.me12_i_codigo.value = chave; 
  if (erro == true) { 

    document.form1.me11_i_cardapiodia.focus(); 
    document.form1.me11_i_cardapiodia.value = ''; 

  }

}

function js_mostramer_cardapiodia1(chave1,chave2) {

  document.form1.me11_i_cardapiodia.value = chave1;
  document.form1.me12_i_codigo.value      = chave2;
  db_iframe_mer_cardapiodia.hide();

}

function js_pesquisame11_i_matricula(mostra) {

  if (mostra == true) {

    js_OpenJanelaIframe('top.corpo','db_iframe_matricula',
                        'func_matricula.php?funcao_js=parent.js_mostramatricula1|ed60_i_codigo|ed60_i_codigo',
                        'Pesquisa',true
                       );

  } else {

     if (document.form1.me11_i_matricula.value != '') { 

        js_OpenJanelaIframe('top.corpo','db_iframe_matricula',
                             'func_matricula.php?pesquisa_chave='+document.form1.me11_i_matricula.value+
                             '&funcao_js=parent.js_mostramatricula',
                             'Pesquisa',false
                           )
     } else {
       document.form1.ed60_i_codigo.value = ''; 
     }
  }
}

function js_mostramatricula(chave,erro) {

  document.form1.ed60_i_codigo.value = chave; 
  if (erro == true) { 

    document.form1.me11_i_matricula.focus(); 
    document.form1.me11_i_matricula.value = ''; 

  }

}

function js_mostramatricula1(chave1,chave2) {

  document.form1.me11_i_matricula.value = chave1;
  document.form1.ed60_i_codigo.value    = chave2;
  db_iframe_matricula.hide();

}

function js_pesquisame11_i_usuario(mostra) {

  if (mostra == true) {

    js_OpenJanelaIframe('top.corpo','db_iframe_db_usuarios',
                        'func_db_usuarios.php?funcao_js=parent.js_mostradb_usuarios1|id_usuario|nome','Pesquisa',true);

  } else {

     if (document.form1.me11_i_usuario.value != '') { 

        js_OpenJanelaIframe('top.corpo','db_iframe_db_usuarios',
                             'func_db_usuarios.php?pesquisa_chave='+document.form1.me11_i_usuario.value+
                             '&funcao_js=parent.js_mostradb_usuarios',
                             'Pesquisa',false
                           )
     } else {
       document.form1.nome.value = ''; 
     }
  }
}

function js_mostradb_usuarios(chave,erro) {

  document.form1.nome.value = chave; 
  if (erro == true) { 

    document.form1.me11_i_usuario.focus(); 
    document.form1.me11_i_usuario.value = ''; 

  }

}

function js_mostradb_usuarios1(chave1,chave2) {

  document.form1.me11_i_usuario.value = chave1;
  document.form1.nome.value           = chave2;
  db_iframe_db_usuarios.hide();

}

function js_pesquisa() {

  js_OpenJanelaIframe('top.corpo','db_iframe_mer_cardapioaluno',
                       'func_mer_cardapioaluno.php?funcao_js=parent.js_preenchepesquisa|me11_i_codigo','Pesquisa',true);

}

function js_preenchepesquisa(chave) {

  db_iframe_mer_cardapioaluno.hide();
  <?
  if ($db_opcao != 1) {

    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>