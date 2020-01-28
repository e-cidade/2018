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

$cllab_entrega->rotulo->label();
?>
<form name="form1" id='form1' method="post" action="" class="form-container">
  <fieldset style='width: 75%;'>
    <legend>Entrega de Resultado</legend>
    <table border="0">
      <tr>
        <td nowrap title="<?=@$Tla31_i_codigo?>">
           <?=@$Lla31_i_codigo?>
        </td>
        <td>
          <?php
          db_input( 'la31_i_codigo', 10, $Ila31_i_codigo, true, 'text', 3 );
          ?>
        </td>
      </tr>
      <tr>
       <td nowrap>
         <?php
         db_ancora( @$Lla31_i_cgs, "js_pesquisala31_i_cgs(true);", $db_opcao );
         ?>
       </td>
       <td nowrap>
         <?php
         db_input( 'la31_i_cgs', 10, $Ila31_i_cgs,  true, 'text', $db_opcao, "onchange='js_pesquisala31_i_cgs(false);'" );
         db_input( 'z01_v_nome', 50, @$Iz01_v_nome, true, 'text', 3 );
         ?>
       </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tla22_i_codigo?>">
           <?
        db_ancora( '<b>Requisição</b>', "js_pesquisala22_i_codigo(true);", "" );
         ?>
        </td>
        <td>
          <?php
          db_input( 'la22_i_codigo', 10, @$Ila22_i_codigo, true, 'text', "", "onchange='js_pesquisala22_i_codigo(false);'" );
          db_input( 'z01_v_nome2',   50, @$Iz01_v_nome,    true, 'text', 3 );
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="requiitem">
          <?php
          db_ancora( "<b>Exame</b>", "js_pesquisala21_i_codigo(true);", "" );
          ?>
        </td>
        <td>
          <?php
          db_input( 'la21_i_codigo', 10, @$Ila21_i_codigo, true, 'text', "", "onchange='js_pesquisala21_i_codigo(false);'" );
          db_input( 'la08_c_descr',  50, @$Ila08_c_descr,  true, 'text',  3 );
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tla31_i_tipodocumento?>">
          <?php
          db_ancora( @$Lla31_i_tipodocumento, "js_pesquisala31_i_tipodocumento(true);", "" );
          ?>
        </td>
        <td>
          <?php
          db_input(
                    'la31_i_tipodocumento',
                    10,
                    @$Ila31_i_tipodocumento,
                    true,
                    'text',
                    "",
                    "onchange='js_pesquisala31_i_tipodocumento(false);'"
                  );
          db_input( 'la33_c_descr', 50, @$Ila33_c_descr, true, 'text', 3 );
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tla31_c_documento?>">
          <?=@$Lla31_c_documento?>
        </td>
        <td>
          <?php
          db_input( 'la31_c_documento', 20, $Ila31_c_documento, true, 'text', $db_opcao );
          ?>
        </td>
      </tr>
    </table>
  </fieldset>
  <input name="<?=( $db_opcao == 1 ? "incluir" : ( $db_opcao == 2 || $db_opcao == 22 ? "alterar" : "excluir" ) )?>"
         type="submit"
         id="db_opcao"
         value="<?=( $db_opcao == 1 ? "Incluir" : ( $db_opcao == 2 || $db_opcao == 22 ? "Alterar" : "Excluir" ) )?>"
    <?=( $db_botao == false ? "disabled" : "" )?> >
</form>
<script>
var oGet = js_urlToObject();
if( oGet.lRedirecionamento && oGet.lRedirecionamento == 'true' ) {

  if( !empty( oGet.iPaciente ) ) {

    $('la31_i_cgs').value = oGet.iPaciente;
    js_pesquisala31_i_cgs( false );
  }

  if( !empty( oGet.iRequisicao ) ) {

    $('la22_i_codigo').value = oGet.iRequisicao;
    js_pesquisala22_i_codigo( false );
  }
}

function js_limpaCamposTrocaReq() {

  document.form1.la21_i_codigo.value = '';
  document.form1.la08_c_descr.value  = '';
}

function js_pesquisala22_i_codigo(mostra) {

  var sCgs = '';
  if( !empty( $F('la31_i_cgs') ) ) {
    sCgs = '&iCgs=' + $F('la31_i_cgs');
  }

  if( mostra == true ) {

    js_OpenJanelaIframe(
                          '',
                          'db_iframe_lab_requisicao',
                          'func_lab_requisicao.php?iLaboratorioLogado=<?=$iLaboratorioLogado?>'
                                                +'&autoriza=2'
                                                +'&funcao_js=parent.js_mostrarequisicao1|la22_i_codigo|z01_v_nome'
                                                + sCgs,
                          'Pesquisa',
                          true
                        );
  } else {

    if( document.form1.la22_i_codigo.value != '' ) {

      js_OpenJanelaIframe(
                           '',
                           'db_iframe_lab_requisicao',
                           'func_lab_requisicao.php?iLaboratorioLogado=<?=$iLaboratorioLogado?>'
                                                 +'&autoriza=2'
                                                 +'&pesquisa_chave='+document.form1.la22_i_codigo.value
                                                 +'&funcao_js=parent.js_mostrarequisicao'
                                                 + sCgs,
                           'Pesquisa',
                           false
                         );
    } else {
      document.form1.z01_v_nome2.value = '';
    }
  }
}

function js_mostrarequisicao( chave, erro ) {

  document.form1.z01_v_nome2.value = chave;
  if( erro == true ) {

    document.form1.la22_i_codigo.focus();
    document.form1.la22_i_codigo.value = '';
  }

  js_limpaCamposTrocaReq();
}

function js_mostrarequisicao1( chave1, chave2 ) {

  document.form1.la22_i_codigo.value = chave1;
  document.form1.z01_v_nome2.value   = chave2;
  db_iframe_lab_requisicao.hide();
  js_limpaCamposTrocaReq();
}

function js_pesquisala21_i_codigo( mostra ) {

  if( document.form1.la22_i_codigo.value == '' ) {

    alert('Escolha uma requisição primeiro.');
    js_limpaCamposTrocaReq();
    return false;
  }

  sPesq = 'la21_i_requisicao='+document.form1.la22_i_codigo.value+'&sSituacao=7 - Conferido&';

  if( mostra == true ) {
    js_OpenJanelaIframe(
                         '',
                         'db_iframe_requiitem',
                         'func_lab_requiitem.php?'+sPesq
                                                  +'funcao_js=parent.js_mostrarequiitem1|la21_i_codigo|la08_c_descr',
                         'Pesquisa',
                         true
                       );
  } else {

    if( document.form1.la21_i_codigo.value != '' ) {
      js_OpenJanelaIframe(
                           '',
                           'db_iframe_requiitem',
                           'func_lab_requiitem.php?'+sPesq
                                                    +'pesquisa_chave='+document.form1.la21_i_codigo.value
                                                    +'&funcao_js=parent.js_mostrarequiitem',
                           'Pesquisa',
                           false
                         );
    } else {
      document.form1.la08_c_descr.value = '';
    }
  }
}

function js_mostrarequiitem( chave, erro ) {

  document.form1.la08_c_descr.value = chave;

  if( erro == true ) {

    document.form1.la21_i_codigo.focus();
    document.form1.la21_i_codigo.value = '';
  }
}

function js_mostrarequiitem1( chave1, chave2 ) {

  document.form1.la21_i_codigo.value = chave1;
  document.form1.la08_c_descr.value  = chave2;
  db_iframe_requiitem.hide();
}

function js_pesquisala31_i_tipodocumento( mostra ) {

  if( mostra == true ) {
    js_OpenJanelaIframe(
                         '',
                         'db_iframe_tipodocumento',
                         'func_lab_tipodocumento.php?&funcao_js=parent.js_mostratipodocumento1|la33_i_codigo|la33_c_descr',
                         'Pesquisa',
                         true
                       );
  } else {

    if( document.form1.la31_i_tipodocumento.value != '' ) {
      js_OpenJanelaIframe(
                           '',
                           'db_iframe_tipodocumento',
                           'func_lab_tipodocumento.php?&pesquisa_chave='+document.form1.la31_i_tipodocumento.value
                                                     +'&funcao_js=parent.js_mostratipodocumento',
                           'Pesquisa',
                           false
                         );
    } else {
      document.form1.la33_c_descr.value = '';
    }
  }
}

function js_mostratipodocumento( chave, erro ) {

  document.form1.la33_c_descr.value = chave;
  if( erro == true ) {

    document.form1.la31_i_tipodocumento.focus();
    document.form1.la31_i_tipodocumento.value = '';
  }
}

function js_mostratipodocumento1( chave1, chave2 ) {

  document.form1.la31_i_tipodocumento.value = chave1;
  document.form1.la33_c_descr.value         = chave2;
  db_iframe_tipodocumento.hide();
}

function js_pesquisala31_i_cgs( mostra ) {

  if( mostra == true ) {
    js_OpenJanelaIframe(
                         '',
                         'db_iframe_cgs_und',
                         'func_cgs_und.php?funcao_js=parent.js_mostracgs_und1|z01_i_cgsund|z01_v_nome',
                         'Pesquisa',
                         true
                       );
  } else {

    if( document.form1.la31_i_cgs.value != '' ) {
      js_OpenJanelaIframe(
                           '',
                           'db_iframe_cgs_und',
                           'func_cgs_und.php?pesquisa_chave='+document.form1.la31_i_cgs.value
                                          +'&funcao_js=parent.js_mostracgs_und',
                           'Pesquisa',
                           false
                         );
    } else {
     document.form1.z01_v_nome.value = '';
    }
  }
}

function js_mostracgs_und( chave, erro ) {

  document.form1.z01_v_nome.value = chave;

  if( erro == true ) {

    document.form1.la31_i_cgs.focus();
    document.form1.la31_i_cgs.value = '';
  }
}

function js_mostracgs_und1( chave1, chave2 ) {

  document.form1.la31_i_cgs.value = chave1;
  document.form1.z01_v_nome.value = chave2;
  db_iframe_cgs_und.hide();
}

function js_pesquisa() {

  js_OpenJanelaIframe(
                       '',
                       'db_iframe_lab_tipodocumento',
                       'func_lab_tipodocumento.php?funcao_js=parent.js_preenchepesquisa|la33_i_codigo',
                       'Pesquisa',
                       true
                     );
}

function js_preenchepesquisa( chave ) {

  db_iframe_lab_tipodocumento.hide();
  <?php
  if( $db_opcao != 1 ) {
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>