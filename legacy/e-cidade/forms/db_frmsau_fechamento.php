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

//MODULO: saude
$oDaoSauFechamento->rotulo->label();
?>
<form class="container" name="form1" method="post" action="">
  <center>
  <fieldset style="width:95%"><legend><b>Fechamento de Competência:</b></legend>
    <?
      db_input('sd97_i_codigo',5,$Isd97_i_codigo,true,'hidden',$db_opcao,"");
      db_input('sd97_i_login',5,$Isd97_i_login,true,'hidden',$db_opcao,"");
    ?>
    <table border="0" align="left">
      <tr>
        <td nowrap title="<?=@$Tsd97_i_compmes?>">
          <b>Competência Mês/Ano:</b>
        </td> 
        <td> 
          <? db_input('sd97_i_compmes',2,$Isd97_i_compmes,true,'text',$db_opcao,'onchange="js_descr()"');?>
        /
          <? db_input('sd97_i_compano',4,$Isd97_i_compano,true,'text',$db_opcao,'onchange="js_descr()"');?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tsd97_d_dataini?>">
          <b>Período de Fechamento :</b>
        </td>
        <td> 
          <?
            db_inputdata('sd97_d_dataini', @$sd97_d_dataini_dia, @$sd97_d_dataini_mes, @$sd97_d_dataini_ano, true, 
                         'text', $db_opcao, "");
          ?>
        À
          <? 
            db_inputdata('sd97_d_datafim', @$sd97_d_datafim_dia, @$sd97_d_datafim_mes, @$sd97_d_datafim_ano, true, 
                         'text', $db_opcao, "onchange=\"js_troca();\"","","","parent.js_troca();");
          ?>
        </td>
      </tr>
      <tr>
        <td><b>Tipo Financiamento:</b></td>
        <td colspan="3">
          <?
            $x = array();
            $sWhere = "sd65_i_anocomp=(select max(sd65_i_anocomp) from sau_financiamento) and sd65_i_mescomp=( 
                       select max(sd65_i_mescomp) from sau_financiamento where sd65_i_anocomp=(
                       select max(sd65_i_anocomp) from sau_financiamento))";
            $sSql    = $oDaoSauFinanciamento->sql_query_file(null,
                                                             "sd65_i_codigo,sd65_c_financiamento||' - '||".
                                                             "sd65_c_nome as descr",
                                                             "",
                                                             $sWhere
                                                            );
            $rsDados = $oDaoSauFinanciamento->sql_record($sSql);
            $x[0]    = 'Todos';
            for ($iX = 0; $iX < $oDaoSauFinanciamento->numrows; $iX++) {

              $oDados                    = db_utils::fieldsmemory($rsDados,$iX);
              $x[$oDados->sd65_i_codigo] = $oDados->descr;

            }
            db_select('sd97_i_financiamento', $x, true, $db_opcao, "");
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tsd97_d_data?>">
          <?=@$Lsd97_d_data?>
        </td>
        <td colspan="3"> 
          <?
            db_inputdata('sd97_d_data',@$sd97_d_data_dia,@$sd97_d_data_mes,@$sd97_d_data_ano,true,'text',3,"");
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tsd97_c_descricao?>">
          <?=@$Lsd97_c_descricao?>
        </td>
        <td colspan="3"> 
          <?
            db_input('sd97_c_descricao',58,$Isd97_c_descricao,true,'text',$db_opcao,"");
          ?>
        </td>
      </tr>
    </table>
  </fieldset>
  <table>  
    <tr>
      <td  width="30%">
        <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" 
               id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" 
               <?=($db_botao==false?"disabled":"")?> >
      </td>
      <td width="30%">
        <input name="cancelar" type="button" id="cancelar" value="Cancelar" 
               <?=($db_opcao==1||isset($incluir)?"disabled":"")?> onClick='location.href="sau4_fechamento001.php"'>
      </td>
    </tr>
  </table>
  <table>
    <tr>
      <td valign="top">
        <?
          $chavepri                     = array("sd97_i_codigo"=>@$sd97_i_codigo);
          $oIframeAltExc->chavepri      = $chavepri;
          $sCampos                      = "sd97_i_compmes||'/'||sd97_i_compano as sd97_i_compmes,";
          $sCampos                     .= "sd97_d_dataini,sd65_c_nome,";
          $sCampos                     .= "sd97_d_datafim,sd97_c_descricao, sd97_c_tipo,sd97_i_codigo,";
          $sCampos                     .= "nome as sd97_i_login,";
          $sCampos                     .= "sd97_d_data,substr(sd97_c_hora,1,5) as sd97_c_hora, sd97_i_compmes as mes";
          $oIframeAltExc->sql           = $oDaoSauFechamento->sql_query("", $sCampos,
                                                                        "sd97_i_compano desc,".
                                                                        "mes desc,".
                                                                        "sd97_i_codigo desc");
          $oIframeAltExc->campos        = "sd97_i_compmes,sd97_d_dataini, sd97_d_datafim,sd65_c_nome,";
          $oIframeAltExc->campos       .= "sd97_c_descricao,sd97_d_data,sd97_c_hora,sd97_i_login";
          $oIframeAltExc->legenda       = "Registros:";
          $oIframeAltExc->msg_vazio     = "Não foi encontrado nenhum registro.";
          $oIframeAltExc->textocabec    = "#DEB887";
          $oIframeAltExc->textocorpo    = "#444444";
          $oIframeAltExc->fundocabec    = "#444444";
          $oIframeAltExc->fundocorpo    = "#eaeaea";
          $oIframeAltExc->tamfontecabec = 9;
          $oIframeAltExc->tamfontecorpo = 9;
          $oIframeAltExc->formulario    = false;
          $oIframeAltExc->iframe_width  = "630";
          $oIframeAltExc->iframe_height = "130";
          $oIframeAltExc->opcoes        = 2;
          $oIframeAltExc->iframe_alterar_excluir($db_opcao);
        ?>
      </td>
    </tr>
  </table>
  </center>
</form>
<script>
  <?
    if (!isset($la54_c_descr)) {
  ?>
      js_descr();
  <?
    }
  ?>

function js_descr() {

  F = document.form1;
  if ((F.sd97_i_compmes.value != '') && (F.sd97_i_compano.value != '')) {

    if (parseInt(F.sd97_i_compmes.value, 10) > 12) {

      alert('Mês invalido!');
      F.sd97_c_descr.value   ='';
      F.sd97_i_compmes.value ='';
      F.sd97_i_compmes.focus();
      return false

    }
    aMes = new Array('JAN', 'FEV', 'MAR', 'ABR', 'MAI', 'JUN', 'JUL', 'AGO', 'SET', 'OUT', 'NOV', 'DEZ');
    /* Retorna a descrição */
    F.sd97_c_descricao.value = 'COMP '+aMes[parseInt(F.sd97_i_compmes.value,10)-1]+'/'+F.sd97_i_compano.value;

  }
}

function js_pesquisasd97_i_login(mostra) {

  if (mostra == true) {

    js_OpenJanelaIframe('top.corpo', 'db_iframe_db_usuarios',
                        'func_db_usuarios.php?funcao_js=parent.js_mostradb_usuarios1|id_usuario|nome', 'Pesquisa',
                        true);

  } else {

     if (document.form1.sd97_i_login.value != '') {

        js_OpenJanelaIframe('top.corpo','db_iframe_db_usuarios',
                            'func_db_usuarios.php?pesquisa_chave='+document.form1.sd97_i_login.value+
                            '&funcao_js=parent.js_mostradb_usuarios', 'Pesquisa', false);

     } else {
       document.form1.nome.value = ''; 
     }

  }
}
function js_mostradb_usuarios(chave, erro) {

  document.form1.nome.value = chave; 
  if (erro == true) {

    document.form1.sd97_i_login.focus(); 
    document.form1.sd97_i_login.value = '';

  }

}
function js_mostradb_usuarios1(chave1, chave2) {

  document.form1.sd97_i_login.value = chave1;
  document.form1.nome.value         = chave2;
  db_iframe_db_usuarios.hide();

}
function js_pesquisa() {

  js_OpenJanelaIframe('top.corpo', 'db_iframe_sau_fechamento',
                      'func_sau_fechamento.php?funcao_js=parent.js_preenchepesquisa|sd97_i_codigo', 'Pesquisa' ,true);

}
function js_preenchepesquisa(chave) {

  db_iframe_sau_fechamento.hide();
  <?
  if ($db_opcao != 1) {
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
function js_troca() {

  datafim = document.form1.sd97_d_datafim.value.split('/').reverse().join();
  dataini = document.form1.sd97_d_dataini.value.split('/').reverse().join();
  if (datafim < dataini) {

    alert("Data final menor que a data inicial");
    document.form1.sd97_d_datafim.value     = "";
    document.form1.sd97_d_datafim_dia.value = "";
    document.form1.sd97_d_datafim_mes.value = "";
    document.form1.sd97_d_datafim_ano.value = "";

  }

}
</script>