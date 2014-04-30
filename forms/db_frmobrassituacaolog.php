<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

//MODULO: projetos
$clobrassituacaolog->rotulo->label();
$clobrassituacaolog->rotulo->tlabel();

$clrotulo = new rotulocampo;
$clrotulo->label("ob01_nomeobra");
$clrotulo->label("ob28_descricao");

$ob29_data_dia = date('d', db_getsession('DB_datausu'));
$ob29_data_mes = date('m', db_getsession('DB_datausu')); 
$ob29_data_ano = date('Y', db_getsession('DB_datausu')); 

?>
<form class="container" name="form1" method="post" action="">
<fieldset>
  <legend><?php echo $Lobrassituacaolog; ?></legend>
  <table class="form-container">
    <tr style="display:none;">
      <td nowrap title="<?=$Tob29_sequencial?>">
         <?=$Lob29_sequencial?>
      </td>
      <td> 
      <?
        db_input('ob29_sequencial',10,$Iob29_sequencial,true,'text',$db_opcao,"")
      ?>
      </td>
    </tr>
    <tr>
      <td nowrap title="<?=$Tob29_obras?>">
        <?
          db_ancora($Lob29_obras,"js_pesquisaob29_obras(true);",$db_opcao);
        ?>
      </td>
      <td> 
        <?
          db_input('ob29_obras',10,$Iob29_obras,true,'text',$db_opcao," onchange='js_pesquisaob29_obras(false);'");
          db_input('ob01_nomeobra',73,$Iob01_nomeobra,true,'text',3,'');
        ?>
      </td>
    </tr>
    <tr>
      <td nowrap title="<?=$Tob29_obrassituacao?>">
         <?
          db_ancora($Lob29_obrassituacao,"js_pesquisaob29_obrassituacao(true);",$db_opcao);
         ?>
      </td>
      <td> 
        <?
          db_input('ob29_obrassituacao',10,$Iob29_obrassituacao,true,'text',$db_opcao," onchange='js_pesquisaob29_obrassituacao(false);'");
          db_input('ob28_descricao',73,$Iob28_descricao,true,'text',3,'');
        ?>
      </td>
    </tr>
    <tr>
      <td nowrap title="<?=$Tob29_data?>">
         <?=$Lob29_data?>
      </td>
      <td> 
        <?
          db_inputdata('ob29_data',$ob29_data_dia,$ob29_data_mes,$ob29_data_ano,true,'text',$db_opcao,"");
        ?>
      </td>
    </tr>
    <tr>
      <td colspan="2" nowrap title="<?=$Tob29_obs?>">
        <fieldset class="separator">
          <legend><?php echo $Lob29_obs; ?></legend>
          <?php db_textarea('ob29_obs',10,50,$Iob29_obs,true,'text',$db_opcao,""); ?>
        </fieldset>
      </td>
    </tr>
  </table>
</fieldset>

  <input name="<?=($db_opcao == 1 ? "incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "alterar" : "excluir" ))?>" type="submit"
    id="db_opcao" value="<?=($db_opcao == 1 ? "Incluir" : ( $db_opcao == 2 || $db_opcao == 22 ? "Alterar" : "Excluir" ))?>"
    <?=($db_botao == false ? "disabled" : "")?> >
  <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >

</form>

<script>
function js_pesquisaob29_obras( mostra ) {

  if ( mostra == true ) {
    js_OpenJanelaIframe('top.corpo',
                        'db_iframe_obras',
                        'func_obras.php?funcao_js=parent.js_mostraobras1|ob01_codobra|ob01_nomeobra',
                        'Pesquisa',
                        true);
  } else {

    if ( document.form1.ob29_obras.value != '' ) { 
      js_OpenJanelaIframe('top.corpo',
                          'db_iframe_obras',
                          'func_obras.php?pesquisa_chave='+document.form1.ob29_obras.value+'&funcao_js=parent.js_mostraobras',
                          'Pesquisa',
                          false);
     }else{
       document.form1.ob01_nomeobra.value = ''; 
     }
  }
}

function js_mostraobras(chave, erro) {

  document.form1.ob01_nomeobra.value = chave; 

  if ( erro == true ) { 

    document.form1.ob29_obras.focus(); 
    document.form1.ob29_obras.value = ''; 
  }
}

function js_mostraobras1( chave1, chave2 ) {
  
  document.form1.ob29_obras.value    = chave1;
  document.form1.ob01_nomeobra.value = chave2;
  db_iframe_obras.hide();
}

function js_pesquisaob29_obrassituacao(mostra) {

  if ( mostra == true ) {

    js_OpenJanelaIframe('top.corpo',
                        'db_iframe_obrassituacao',
                        'func_obrassituacao.php?funcao_js=parent.js_mostraobrassituacao1|ob28_sequencial|ob28_descricao',
                        'Pesquisa',
                        true);
  } else {

    if(document.form1.ob29_obrassituacao.value != ''){ 
      js_OpenJanelaIframe('top.corpo',
                          'db_iframe_obrassituacao',
                          'func_obrassituacao.php?pesquisa_chave='+document.form1.ob29_obrassituacao.value+'&funcao_js=parent.js_mostraobrassituacao',
                          'Pesquisa',
                          false);
    }else{
      document.form1.ob28_descricao.value = ''; 
    }
  }
}

function js_mostraobrassituacao(chave, erro) {

  document.form1.ob28_descricao.value = chave; 

  if ( erro == true ) { 

    document.form1.ob29_obrassituacao.focus(); 
    document.form1.ob29_obrassituacao.value = ''; 
  }
}

function js_mostraobrassituacao1( chave1, chave2 ) {

  document.form1.ob29_obrassituacao.value = chave1;
  document.form1.ob28_descricao.value     = chave2;
  db_iframe_obrassituacao.hide();
}

function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo',
                      'db_iframe_obrassituacaolog',
                      'func_obrassituacaolog.php?funcao_js=parent.js_preenchepesquisa|ob29_sequencial',
                      'Pesquisa',
                      true);
}

function js_preenchepesquisa( chave ) {

  db_iframe_obrassituacaolog.hide();
  <?
  if($db_opcao != 1 ) {
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>
<script>

$("ob29_obras").addClassName("field-size2");
$("ob01_nomeobra").addClassName("field-size7");
$("ob29_obrassituacao").addClassName("field-size2");
$("ob28_descricao").addClassName("field-size7");
$("ob29_data").addClassName("field-size2");
$("ob29_obs").style.width = "100%";

</script>