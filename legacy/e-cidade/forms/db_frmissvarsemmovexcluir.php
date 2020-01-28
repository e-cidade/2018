<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2015  DBseller Servicos de Informatica
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

require_once(modification('dbforms/db_classesgenericas.php'));

$cliframe_seleciona = new cl_iframe_seleciona;
$clrotulo           = new rotulocampo;
$clrotulo->label('q07_inscr');
$clrotulo->label('z01_nome');
?>

<form name="form1" method="post" action="">
  <fieldset>
    <legend>Cancelamento ISSQN Variável - Exclusão</legend>
    <table border="0" align="center">
      <tr>
        <td title="<?php echo $Tq07_inscr ?>">
          <?php db_ancora($Lq07_inscr, ' js_inscr(true); ', 1); ?>
        </td>
        <td title="<?=$Tq07_inscr?>" colspan="4">
          <?php
          db_input('q07_inscr', 6, $Iq07_inscr, true, 'text', 1, 'onchange="js_inscr(false)"');
          isset($q07_inscr) ? $inscricao=$q07_inscr : '';
          db_input('inscricao', 6, $Iq07_inscr, true, 'hidden', 1);
          db_input('z01_nome', 83, 0, true, 'text', 3);
          ?>
        </td>
      </tr>
    </table>
    <table border="0">
      <tr>
        <td>
          <?php
            $cliframe_seleciona->legenda      = "Lançamentos";
            $cliframe_seleciona->alignlegenda = "left";

            if (isset($q07_inscr) && $q07_inscr != '') {

              $cliframe_seleciona->sql = "select issvar.*, arreinscr.*
                                            from arreinscr
                                                 inner join issvar             on q05_numpre          = arreinscr.k00_numpre
                                                 inner join arrecant           on arrecant.k00_numpre = q05_numpre
                                                                              and k00_numpar          = q05_numpar
                                                 inner join cancdebitosreg     on k21_numpre          = q05_numpre
                                                                              and k21_numpar          = q05_numpar
                                                 inner join cancdebitosprocreg on k24_cancdebitosreg  = k21_sequencia
                                          where arrecant.k00_valor = '0'
                                            and k00_inscr = {$q07_inscr}
                                            and q05_valor = 0
                                       order by q05_ano, q05_mes";
            }

            $cliframe_seleciona->campos        = 'q05_numpre,q05_numpar,q05_mes,q05_ano,q05_valor,q05_histor,';
            $cliframe_seleciona->campos       .= 'q05_aliq,q05_bruto,q05_vlrinf';
            $cliframe_seleciona->textocabec    = 'darkblue';
            $cliframe_seleciona->textocorpo    = 'black';
            $cliframe_seleciona->fundocabec    = '#aacccc';
            $cliframe_seleciona->fundocorpo    = '#ccddcc';
            $cliframe_seleciona->iframe_height = '250';
            $cliframe_seleciona->iframe_width  = '715';
            $cliframe_seleciona->iframe_nome   = 'atividades';
            $cliframe_seleciona->chaves        = 'q05_numpre,q05_numpar';
            $cliframe_seleciona->iframe_seleciona($db_opcao);
          ?>
        </td>
      </tr>
    </table>
  </fieldset>
  <br>
  <center>
    <input name="cancelar" type="submit" onclick="return js_verifica();" id="db_opcao" value="Cancelar" disabled <?php echo ($db_botao == false ? 'disabled' : '') ?>>
  </center>
</form>

<script type="text/javascript">
  function js_verifica(){
    inscr = new Number(document.form1.q07_inscr.value);

    if (inscr == '' || inscr == '0' || isNaN(inscr) == true) {
      alert('Verifique a inscrição');
      return false;
    }

    if (inscr != document.form1.inscricao.value) {
      return false;
    }

    obj = atividades.document.getElementsByTagName('INPUT');

    var marcado = false;

    for (var i = 0; i < obj.length; i++) {
      if (obj[i].type == 'checkbox') {
        if (obj[i].checked == true) {
          id      = obj[i].id.substr(6);
          marcado = true;
        }
      }
    }

    if (!marcado) {
      alert('Selecione um lançamento!');
      return false;
    }

    return js_gera_chaves();
  }

  function js_inscr(mostra){
    var inscr=document.form1.q07_inscr.value;

    if (mostra == true) {
      js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_inscr','func_issbase.php?funcao_js=parent.js_mostrainscr|q02_inscr|z01_nome','Pesquisa',true);
    } else {
      if (inscr != '') {
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_inscr','func_issbase.php?pesquisa_chave='+inscr+'&funcao_js=parent.js_mostrainscr1','Pesquisa',false);
      } else {
        document.form1.z01_nome.value="";
        document.form1.submit();
      }
    }
  }

  function js_mostrainscr(chave1,chave2) {
    document.form1.q07_inscr.value = chave1;
    document.form1.z01_nome.value  = chave2;
    atividades.location.href       = "iss1_tabativbaixaiframe.php?q07_inscr=" +chave1+ "&z01_nome=" +chave2;
    document.form1.submit();
    db_iframe_inscr.hide();
  }

  function js_mostrainscr1(chave,erro) {
    document.form1.z01_nome.value = chave;

    if (erro == true) {
      document.form1.q07_inscr.focus();
      document.form1.q07_inscr.value = '';
    } else {
      document.form1.submit();
    }
  }

  <?php
  if (isset($q07_inscr) && $q07_inscr != '') {
    ?>document.form1.cancelar.disabled = false;<?php
  } else {
    ?>document.form1.cancelar.disabled = true;<?php
  }
  ?>
</script>