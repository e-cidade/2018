<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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
$clcgs_cartaosus->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_v_nome");
?>

<form name="form1" method="post" >
  <fieldset >
    <legend><b>Cartão SUS</b></legend>
    <table class="form-container">
      <tr>
        <td nowrap="nowrap" title="<?=@$Ts115_i_codigo?>">
           <?=@$Ls115_i_codigo?>
        </td>
        <td nowrap="nowrap">
          <?php
            db_input('s115_i_codigo',10,$Is115_i_codigo,true,'text',3,"");
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap='nowrap' title="<?=@$Ts115_i_cgs?>">
          <?php
            db_ancora(@$Ls115_i_cgs,"js_pesquisas115_i_cgs(true);",$db_opcao);
          ?>
        </td>
        <td nowrap='nowrap' >
          <?php
            db_input('s115_i_cgs',10,$Is115_i_cgs,true,'text',$db_opcao," onchange='js_pesquisas115_i_cgs(false);'");
            db_input( 'z01_v_nome', 40, $Iz01_v_nome, true, 'text', 3 );
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap='nowrap' title="<?=@$Ts115_c_cartaosus?>">
           <?=@$Ls115_c_cartaosus?>
        </td>
        <td nowrap='nowrap'>
          <?php
            db_input( 's115_c_cartaosus', 15, '', true, 'text', $db_opcao );
            $s115_i_entrada = 1;
            db_input( 's115_i_entrada', 2, $Is115_i_entrada, true, 'hidden', $db_opcao );
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap='nowrap' title="<?=@$Ts115_c_tipo?>">
           <?=@$Ls115_c_tipo?>
        </td>
        <td nowrap='nowrap'>
          <?php
          $x = array( 'D' => 'Definitivo', 'P' => 'Provisório' );
          db_select( 's115_c_tipo', $x, true, $db_opcao );
          ?>
        </td>
    </table>
  </fieldset>
  <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao"
         value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?>
  <input type="button" name="sinc" id="sinc" value="Atualizar" onclick="js_atualizar();">
<br>
<?
$chavepri = array( "s115_i_codigo" => @$s115_i_codigo );
$cliframe_alterar_excluir->chavepri = $chavepri;

if( isset( $s115_i_cgs ) && @$s115_i_cgs != "" ) {

  $sCampos = 's115_i_codigo, s115_c_cartaosus, s115_c_tipo, s115_i_entrada, z01_v_nome';
  $cliframe_alterar_excluir->sql = $clcgs_cartaosus->sql_query( null, $sCampos, null, "s115_i_cgs = {$s115_i_cgs}" );
}

$cliframe_alterar_excluir->legenda       = "Registros Cartão SUS";
$cliframe_alterar_excluir->campos        = "s115_c_cartaosus,s115_c_tipo";
$cliframe_alterar_excluir->msg_vazio     = "Não foi encontrado nenhum registro.";
$cliframe_alterar_excluir->textocabec    = "darkblue";
$cliframe_alterar_excluir->textocorpo    = "black";
$cliframe_alterar_excluir->fundocabec    = "#aacccc";
$cliframe_alterar_excluir->fundocorpo    = "#ccddcc";
$cliframe_alterar_excluir->iframe_width  = "100%";
$cliframe_alterar_excluir->iframe_height = "130";
$cliframe_alterar_excluir->opcoes        = 1;
$cliframe_alterar_excluir->iframe_alterar_excluir(1);
?>
</form>
<script>
function js_pesquisas115_i_cgs( mostra ) {

  if( mostra == true ) {
    js_OpenJanelaIframe(
                         'top.corpo',
                         'db_iframe_cgs',
                         'func_cgs_und.php?funcao_js=parent.js_mostracgs1|z01_i_cgsund|z01_v_nome',
                         'Pesquisa',
                         true
                       );
  } else {

    if( document.form1.s115_i_cgs.value != '' ) {
      js_OpenJanelaIframe(
                           'top.corpo',
                           'db_iframe_cgs',
                           'func_cgs_und.php?pesquisa_chave='+document.form1.s115_i_cgs.value+'&funcao_js=parent.js_mostracgs',
                           'Pesquisa',
                           false
                         );
    } else {
      document.form1.z01_v_nome.value = '';
    }
  }
}

function js_mostracgs( chave, erro ) {

  document.form1.z01_v_nome.value = chave; 
  if( erro == true ) {

    document.form1.s115_i_cgs.focus(); 
    document.form1.s115_i_cgs.value = ''; 
  }

  location.href = 'sau1_cgs_cartaosus001.php?s115_i_cgs='+document.form1.s115_i_cgs.value+'&z01_v_nome='+chave;
}

function js_mostracgs1( chave1, chave2 ) {

  document.form1.s115_i_cgs.value = chave1;
  document.form1.z01_v_nome.value = chave2;
  db_iframe_cgs.hide();
  location.href = 'sau1_cgs_cartaosus001.php?s115_i_cgs='+chave1+'&z01_v_nome='+chave2;
}

function js_pesquisa() {
  js_OpenJanelaIframe(
                       'top.corpo',
                       'db_iframe_cgs_cartaosus',
                       'func_cgs_cartaosus.php?funcao_js=parent.js_preenchepesquisa|s115_i_codigo',
                       'Pesquisa',
                       true
                     );
}

function js_preenchepesquisa( chave ) {

  db_iframe_cgs_cartaosus.hide();
  <?php
  if( $db_opcao != 1 ) {
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}

function js_atualizar() {

  sau_ajax = new ws_ajax( 'sau1_cgs_cartaosusRPC.php' );

  if( document.form1.s115_i_cgs.value != '' ) {

    sau_ajax.add( 'cod_cgs', document.form1.s115_i_cgs.value );
    sau_ajax.execute( 'sincronizar', 'js_retorno_atualizar' );
  } else {
    alert('Entre com um CGS!');
  }
}

function js_retorno_atualizar( objAjax ) {

  var objRetorno = eval("("+objAjax.responseText+")");

  if( objRetorno.status == 1 ) {

    alert( 'Operação concluida com sucesso!' );
    location.href = 'sau1_cgs_cartaosus001.php?s115_i_cgs='+$F('s115_i_cgs')+'&z01_v_nome='+$F('z01_v_nome');
  } else {

    if( objRetorno.status == 2 ) {
      alert('Falha configurações não encontradas!');
    } else {

      if( objRetorno.status == 3 ) {
        alert('Falha ao conectar no banco CADSUS');
      } else {

        if( objRetorno.status == 4 ) {
          alert('O CGS não encontrado!');
        } else {
          alert('Erro deconhecido.');
        }
      }
    }
  }
}

$('s115_c_cartaosus').onkeyup = function() {
  this.value = this.value.somenteNumeros();
};

$('s115_c_cartaosus').onkeydown = function() {
  this.value = this.value.somenteNumeros();
};
</script>