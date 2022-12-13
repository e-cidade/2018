<?php
/**
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


require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("libs/db_usuariosonline.php");
require_once modification("dbforms/db_funcoes.php");
require_once modification("classes/db_cgs_und_ext_classe.php");

db_postmemory($HTTP_POST_VARS);

if (!isset($pesquisar) && isset($alterar_cgs)) {

  parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
  ?>
    <script>
    location.href ="sau1_cgs_und002.php?chavepesquisa=<?=$chave_z01_i_cgsund?>";
  </script>
  <?php
}

$clcgs_und = new cl_cgs_und_ext;
$clrotulo = new rotulocampo;
$clcgs_und->rotulo->label("z01_i_cgsund");
$clcgs_und->rotulo->label("z01_v_nome");
$clcgs_und->rotulo->label("z01_v_cgccpf");
$clcgs_und->rotulo->label("z01_v_ident");
$clrotulo->label("DBtxt30");
$clrotulo->label("DBtxt31");
$clrotulo->label("s115_c_cartaosus");

$aFuncaoParent  = explode('|', $funcao_js);
$funcaoParent   = $aFuncaoParent[0];

unset($aFuncaoParent[0]);
?>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
    <script>

      team = [];
      <?php

      /** Seleciona todos os calendários */
      $sql1  = "SELECT sd34_i_codigo,sd34_v_descricao ";
      $sql1 .= "  FROM microarea ";
      $sql1 .= " ORDER BY sd34_v_descricao";

      $sql_result = db_query($sql1);
      $num        = pg_num_rows($sql_result);
      $conta      = "";

      $aArrayPai = array();
      while ( $row = pg_fetch_array($sql_result)) {

        $conta       = $conta+1;
        $cod_micro   = $row["sd34_i_codigo"];
        $aArrayFilho = array();

        $sub_sql  = "SELECT sd35_i_codigo,sd33_v_descricao ";
        $sub_sql .= "  FROM familiamicroarea ";
        $sub_sql .= "       inner join familia on sd33_i_codigo = sd35_i_familia ";
        $sub_sql .= " WHERE sd35_i_microarea = '{$cod_micro}' ";
        $sub_sql .= " ORDER BY sd33_v_descricao ";

        $sub_result = db_query($sub_sql);
        $num_sub    = pg_num_rows($sub_result);
        if ($num_sub >= 1) {

          $aArrayFilho[] = array('', '');
          $conta_sub     = "";

          while ($rowx = pg_fetch_array($sub_result)) {

            $codigo_fam = $rowx["sd35_i_codigo"];
            $nome_fam   = $rowx["sd33_v_descricao"];
            $conta_sub  = $conta_sub+1;

            if ($conta_sub == $num_sub){

              $aArrayFilho[] = array(urlencode($nome_fam), $codigo_fam);
              $conta_sub     = "";
            } else {
              $aArrayFilho[] = array(urlencode($nome_fam), $codigo_fam);
            }
          }
        } else {
          $aArrayFilho[] = array("Microarea sem famílias cadastradas.", '');
        }
        $aArrayPai[] = $aArrayFilho ;
      }

      $sArrayJson = JSON::create()->stringify($aArrayPai);
      ?>
      team = <?=$sArrayJson?>;

      //Inicio da função JS

      function fillSelectFromArray(selectCtrl, itemArray, goodPrompt, badPrompt, defaultItem) {

        var i, j;
        var prompt;
        // empty existing items
        for (i = selectCtrl.options.length; i >= 0; i--) {
          selectCtrl.options[i] = null;
        }
        prompt = (itemArray != null) ? goodPrompt : badPrompt;
        if (prompt == null) {

          selectCtrl.options[0] = new Option('','');
          j = 0;
        }else{
          selectCtrl.options[0] = new Option(prompt);
          j = 1;
        }

        if (itemArray != null) {
          // add new items
          for (i = 0; i < itemArray.length; i++){
            selectCtrl.options[j] = new Option(itemArray[i][0].urlDecode());
            if (itemArray[i][1] != null){
              selectCtrl.options[j].value = itemArray[i][1];
            }
            <?if(isset($chave_z01_i_familiamicroarea)&&$chave_z01_i_familiamicroarea!=""){?>
              if(<?=$chave_z01_i_familiamicroarea?>==itemArray[i][1]){
                indice = i;
              }
              <?}?>
              j++;
          }
          <?if(isset($chave_z01_i_familiamicroarea)&&$chave_z01_i_familiamicroarea!=""){?>
            selectCtrl.options[indice].selected = true;
            <?}else{?>
            selectCtrl.options[0].selected = true;
            <?}?>
        }
      }

      function validaCgs(iCgs, parametros) {

        var oParametros = {'sExecucao': 'validarCGS', 'cgs': iCgs, 'asynchronous': false};

        AjaxRequest.create('sau4_cgs.RPC.php', oParametros, function(oRetorno, lErro) {

          if(lErro) {

            alert(oRetorno.sMessage);
            return;
          }

          if(    oRetorno.valido === false
              && confirm('O CGS está desatualizado. Gostaria de atualizá-lo?')) {

            manuntencaoCgs(iCgs,parametros);
            return;
          }

          funcaoAnterior.apply(null, parametros);
        }).setMessage('Aguarde, validando CGS...')
          .execute();
      }
    </script>
  </head>
  <body>
    <form name="form2" method="post" action="" class="container">
      <fieldset>
        <legend>Filtros da Pesquisa</legend>
        <table class="form-container">
          <tr>
            <td>
              <label for="chave_z01_i_cgsund">CGS:</label>
            </td>
            <td colspan="3">
              <?php

                db_input('z01_i_cgsund',10,$Iz01_i_cgsund,true,'text',4,"","chave_z01_i_cgsund", null, null, 15);
              ?>
            </td>
          </tr>
          <tr>
            <td>
              <label for="chave_z01_v_nome">Nome:</label>
            </td>
            <td colspan="3">
              <?php
              db_input('z01_v_nome',30,$Iz01_v_nome,true,'text',4,"class='field-size-max'",'chave_z01_v_nome');
              ?>
            </td>
          </tr>
          <tr>
            <td>
              <label for="chave_z01_d_nasc">Data de Nascimento:</label>
            </td>
            <td colspan="3">
              <?php
              $z01_d_nasc_dia = !empty($z01_d_nasc_dia) ? $z01_d_nasc_dia: "";
              $z01_d_nasc_mes = !empty($z01_d_nasc_mes) ? $z01_d_nasc_mes: "";
              $z01_d_nasc_ano = !empty($z01_d_nasc_ano) ? $z01_d_nasc_ano: "";
              db_inputdata('z01_d_nasc', $z01_d_nasc_dia, $z01_d_nasc_mes, $z01_d_nasc_ano, true, 'text', 4, "", 'chave_z01_d_nasc');
              ?>
            </td>
          </tr>
          <tr>
            <td>
              <label for="chave_z01_v_ident">Identidade:</label>
            </td>
            <td>
              <?php
              db_input('z01_v_ident',15,$Iz01_v_ident,true,'text',1,"class='field-size-max'","chave_z01_v_ident");
              ?>
            </td>
            <td>
              <label for="chave_s115_c_cartaosus">Cartão SUS:</label>
            </td>
            <td>
              <?php
              db_input('s115_c_cartaosus',15,$Is115_c_cartaosus,true,'text',4,"class='field-size-max'",'chave_s115_c_cartaosus');
              ?>
            </td>
          </tr>
          <tr>
              <td>
                <label for="chave_z01_v_micro">Microárea:</label>
              </td>
              <td>
                <select id="chave_z01_v_micro"
                        name="chave_z01_v_micro"
                        onChange="fillSelectFromArray(this.form.chave_z01_i_familiamicroarea, ((this.selectedIndex == -1) ? null : team[this.selectedIndex-1]));"
                        style="font-size:9px;width:200px;height:18px;">
                  <option></option>
                <?php

                  $sql1       = "SELECT sd34_i_codigo,sd34_v_descricao FROM microarea ORDER BY sd34_v_descricao";
                  $sql_result = db_query($sql1);

                  while ($row = pg_fetch_array($sql_result)) {

                    $cod_micro  = $row["sd34_i_codigo"];
                    $desc_micro = $row["sd34_v_descricao"];

                    echo "<option value='{$cod_micro}'".($cod_micro==@$chave_z01_v_micro?"selected":"") .">";
                    echo   $desc_micro;
                    echo "</option>";
                  }
                ?>
              </select>
              </td>
              <td>
                <label for="chave_z01_i_familiamicroarea">Família:</label>
              </td>
              <td>
                <select id="chave_z01_i_familiamicroarea"
                        name="chave_z01_i_familiamicroarea"
                        style="font-size:9px;width:200px;height:18px;"
                        onchange="if(this.value=='')document.form2.chave_z01_v_micro.value='';">
                  <option value=""></option>
                </select>
                <?php
                if((isset($chave_z01_i_familiamicroarea)&&$chave_z01_i_familiamicroarea!="")||(isset($chave_z01_v_micro)&&$chave_z01_v_micro!="")) {
                ?>
                  <script>fillSelectFromArray(document.form2.chave_z01_i_familiamicroarea, team[document.form2.chave_z01_v_micro.selectedIndex-1]);</script>
                <?php
                }
                ?>
              </td>
          </tr>
        </table>
      </fieldset>
      <input name="pesquisar2" type="submit" id="pesquisar2" value="Pesquisar">
      <input name="limpar" type="button"  id="limpar" value="Limpar" onClick="js_limpar();">
      <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="js_fechar('<?=@$campoFoco?>');">
      <?php

      $disabled = "disabled";

      if(isset($retornacgs) || !empty($redireciona)) {
        $disabled = "";
      } else if(!isset($retornacgs) && empty($redireciona)) {
        $disabled = "";
      }

      if(!isset($lDesabilitaCgs)) {

        ?>
        <input id="manutencaoCgs" type="button" value="Manutenção CGS"
               onclick="manuntencaoCgs($F('chave_z01_i_cgsund'));"/>
        <?php
      }
      ?>
    </form>

    <?php
    if(isset($lValidaCGS)) {

      echo <<<HTML
      <script>
        var js_validaCGS = function() {
          validaCgs(arguments[0], arguments);
        };
        var funcaoAnterior = {$funcaoParent};
      </script>
HTML;

      $funcao_js = "js_validaCGS|".implode("|", $aFuncaoParent);
    }

    if(!isset($pesquisa_chave)) {

      if(isset($campos) == false) {

        if(file_exists("funcoes/db_func_cgs_und_ext.php")==true) {
          include(modification("funcoes/db_func_cgs_und_ext.php"));
        } else {

          $campos  = "z01_i_cgsund, ";
          $campos .= " z01_v_nome, ";
          $campos .= " (case when s115_c_cartaosus is not null ";
          $campos .= "       then s115_c_cartaosus ";
          $campos .= "       else (select s115_c_cartaosus ";
          $campos .= "               from cgs_cartaosus as cartaop ";
          $campos .= "              where cartaop.s115_i_cgs = cgs_und.z01_i_cgsund ";
          $campos .= "                and s115_c_tipo = 'P' ";
          $campos .= "              order by s115_i_codigo desc ";
          $campos .= "              limit 1) ";
          $campos .= "   end ) as s115_c_cartaosus, ";
          $campos .= " z01_d_nasc, ";
          $campos .= " z01_v_sexo, ";
          $campos .= " z01_v_ender, ";
          $campos .= " z01_i_numero, ";
          $campos .= " z01_v_bairro, ";
          $campos .= " z01_v_ident, ";
          $campos .= " z01_v_mae, ";
          $campos .= " z01_v_telcel";
        }
      }

      if(!isset($chave_profissional) || empty($chave_profissional) || !isset($chave_unidade) || empty($chave_unidade)) {

        if(isset($chave_z01_i_cgsund) && (trim($chave_z01_i_cgsund)!="") ){
          $sql = $clcgs_und->sql_query($chave_z01_i_cgsund,$campos,"z01_i_cgsund");
        }else if(isset($chave_z01_v_nome) && (trim($chave_z01_v_nome)!="") ){
          $sql = $clcgs_und->sql_query("",$campos,"z01_v_nome"," to_ascii(z01_v_nome) like to_ascii('$chave_z01_v_nome%') ");
        }else if(isset($chave_z01_v_ident) && (trim($chave_z01_v_ident)!="") ){
          $sql = $clcgs_und->sql_query("",$campos,"z01_v_nome"," z01_v_ident = '$chave_z01_v_ident' ");
        }else if(isset($chave_z01_d_nasc) && (trim($chave_z01_d_nasc)!="") ){
          $chave_z01_d_nasc = substr($chave_z01_d_nasc,6,4)."-".substr($chave_z01_d_nasc,3,2)."-".substr($chave_z01_d_nasc,0,2);
          $sql = $clcgs_und->sql_query("",$campos,"z01_v_nome"," z01_d_nasc = '$chave_z01_d_nasc' ");
        }else if(isset($chave_s115_c_cartaosus) && (trim($chave_s115_c_cartaosus)!="") ){
          $sql = $clcgs_und->sql_query_ext("",$campos,"z01_v_nome"," s115_c_cartaosus = '$chave_s115_c_cartaosus' ");
        }else if(isset($chave_z01_i_familiamicroarea) && (trim($chave_z01_i_familiamicroarea)!="") ){
          $sql = $clcgs_und->sql_query("",$campos,"z01_v_nome"," z01_i_familiamicroarea = '$chave_z01_i_familiamicroarea' ");
        }else if(isset($chave_z01_v_micro) && (trim($chave_z01_v_micro)!="") ){
          $sql = $clcgs_und->sql_query("",$campos,"z01_v_nome"," familiamicroarea.sd35_i_microarea = $chave_z01_v_micro ");
        }
      } else { // Traz todos os CGSs que sao pacientes do profissional indicado na variavel $chave_profissional

        if(isset($chave_z01_i_cgsund) && (trim($chave_z01_i_cgsund)!="") ){
          $sql = $clcgs_und->sql_query_cgs_profissional($chave_z01_i_cgsund,$chave_profissional,$chave_unidade,$campos,"z01_i_cgsund");
        }else if(isset($chave_z01_v_nome) && (trim($chave_z01_v_nome)!="") ){
          $sql = $clcgs_und->sql_query_cgs_profissional("",$chave_profissional,$chave_unidade,$campos,"z01_v_nome"," to_ascii(z01_v_nome) like to_ascii('$chave_z01_v_nome%') ");
        }else if(isset($chave_z01_v_ident) && (trim($chave_z01_v_ident)!="") ){
          $sql = $clcgs_und->sql_query_cgs_profissional("",$chave_profissional,$chave_unidade,$campos,"z01_v_nome"," z01_v_ident = '$chave_z01_v_ident' ");
        }else if(isset($chave_z01_d_nasc) && (trim($chave_z01_d_nasc)!="") ){
          $chave_z01_d_nasc = substr($chave_z01_d_nasc,6,4)."-".substr($chave_z01_d_nasc,3,2)."-".substr($chave_z01_d_nasc,0,2);
          $sql = $clcgs_und->sql_query_cgs_profissional("",$chave_profissional,$chave_unidade,$campos,"z01_v_nome"," z01_d_nasc = '$chave_z01_d_nasc' ");
        }else if(isset($chave_s115_c_cartaosus) && (trim($chave_s115_c_cartaosus)!="") ){
          $sql = $clcgs_und->sql_query_cgs_profissional("",$chave_profissional,$chave_unidade,$campos,"z01_v_nome"," s115_c_cartaosus = '$chave_s115_c_cartaosus' ");
        }else if(isset($chave_z01_i_familiamicroarea) && (trim($chave_z01_i_familiamicroarea)!="") ){
          $sql = $clcgs_und->sql_query_cgs_profissional("",$chave_profissional,$chave_unidade,$campos,"z01_v_nome"," z01_i_familiamicroarea = '$chave_z01_i_familiamicroarea' ");
        }else if(isset($chave_z01_v_micro) && (trim($chave_z01_v_micro)!="") ){
          $sql = $clcgs_und->sql_query_cgs_profissional("",$chave_profissional,$chave_unidade,$campos,"z01_v_nome"," familiamicroarea.sd35_i_microarea = $chave_z01_v_micro ");
        }
      }

      if(isset($nao_mostra)) {

        $sSep    = '';
        $aFuncao = explode('|', $funcao_js);
        $rs      = $clcgs_und->sql_record($sql);

        if($clcgs_und->numrows == 0) {
          die('<script>'.$aFuncao[0]."('','Chave(".$chave_z01_i_cgsund.") não Encontrado');</script>");
        } else {

          db_fieldsmemory($rs, 0);
          $sFuncao = $aFuncao[0].'(';
          for($iCont = 1; $iCont < count($aFuncao); $iCont++) {

            $sFuncao .= $sSep.'"'.eval('return @$'.$aFuncao[$iCont].';').'"';
            $sSep     = ', ';
          }

          $sFuncao  = substr($sFuncao, 0, strlen($sFuncao));
          $sFuncao .= ');';
          die("<script>".$sFuncao.'</script>');
        }
      }

      $repassa = array();
      if(isset($chave_z01_i_cgsund)) {

        $repassa = array(
          "chave_z01_i_cgsund"           => $chave_z01_i_cgsund,
          "chave_z01_v_nome"             => !empty($chave_z01_v_nome) ? $chave_z01_v_nome : '',
          "chave_z01_v_ident"            => !empty($chave_z01_v_ident) ? $chave_z01_v_ident : '',
          "chave_z01_d_nasc"             => !empty($chave_z01_d_nasc) ? $chave_z01_d_nasc : '',
          "chave_z01_c_cartaosus"        => !empty($chave_s115_c_cartaosus) ? $chave_s115_c_cartaosus : '',
          "chave_z01_i_familiamicroarea" => !empty($chave_z01_i_familiamicroarea) ? $chave_z01_i_familiamicroarea : ''
        );
      }

      if( isset($sql) ) {

        echo '<div class="container">';
        echo '  <fieldset>';
        echo '    <legend>Resultado da Pesquisa</legend>';
        db_lovrot( $sql,15,"()","",$funcao_js,"","NoMe",$repassa);
        echo '  </fieldset>';
        echo '</div>';
      }
    } else {

      if($pesquisa_chave!=null && $pesquisa_chave!=""){

        if(!isset($chave_profissional) || empty($chave_profissional) || !isset($chave_unidade) || empty($chave_unidade)) {
          $result = $clcgs_und->sql_record($clcgs_und->sql_query($pesquisa_chave));
        } else {

          $sql = $clcgs_und->sql_query_cgs_profissional($pesquisa_chave,$chave_profissional,$chave_unidade);
          $clcgs_und->sql_record($sql);
        }

        if($clcgs_und->numrows != 0) {

          db_fieldsmemory($result,0);
          echo "<script>{$funcao_js}('$z01_v_nome',false,'$z01_v_sexo','$z01_v_telcel');</script>";
        } else {
          echo "<script>{$funcao_js}('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
        }
      } else {
        echo "<script>{$funcao_js}('',false);</script>";
      }
      exit;
    }
    ?>
  </body>
</html>
<script>
/**
 * Botoão Fechar
 * campoFoco = foco de retorno quando fechar
 */
  function js_fechar( campoFoco ) {

    if( campoFoco != undefined && campoFoco != '' ){

      eval( "parent.document.getElementById('"+campoFoco+"').focus(); " );
      eval( "parent.document.getElementById('"+campoFoco+"').select(); " );
    }
    parent.db_iframe_cgs_und.hide();
  }

  function js_limpar(){

    document.form2.chave_z01_v_nome.value             = "";
    document.form2.chave_z01_i_cgsund.value           = "";
    document.form2.chave_z01_v_ident.value            = "";
    document.form2.chave_z01_d_nasc.value             = "";
    document.form2.chave_s115_c_cartaosus.value       = "";
    document.form2.chave_z01_v_micro.value            = "";
    document.form2.chave_z01_i_familiamicroarea.value = "";
  }

  document.form2.chave_z01_v_nome.focus();

  function manuntencaoCgs(iCgs, parametros) {

    var sUrl  = 'sau1_manutencaocgs001.php?lBloqueiaBotoes&lBloqueiaMenu';
        sUrl += iCgs != '' ? '&cgs=' + iCgs : '';

    var janela = js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_cgs', sUrl, 'Manutenção de CGS', true, 0,0 );
    janela.moldura.style.zIndex = 1500;
    janela.setLargura("calc(100% - 25px)");
    janela.setAltura("calc(100% - 25px)");
    janela.janFrame.contentDocument.forms[0].addEventListener("submit", function() {

      janela.hide();
      funcaoAnterior.apply(null, parametros);
    });
  }

  (function() {
    var query = frameElement.getAttribute('name').replace('IF', ''), input = document.querySelector('input[value="Fechar"]');
    input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
  })();
</script>
