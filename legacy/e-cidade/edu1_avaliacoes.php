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

require_once(modification("libs/db_stdlibwebseller.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("dbforms/db_classesgenericas.php"));
require_once(modification("classes/db_procavaliacao_classe.php"));

$sBtnAtualizarDiario = " disabled='disabled' ";
if ( isModuloEscola() ) {
  $sBtnAtualizarDiario = "";
}

$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clprocavaliacao          = new cl_procavaliacao;

$sPossuiTurmasEncerradas = 'N';
if ( isset($_GET['possuiTurmasEncerradas']) ) {
  $sPossuiTurmasEncerradas = $_GET['possuiTurmasEncerradas'];
}

if ( isset($ordenar) ) {

  $tam = sizeof($ordenacao);
  for ( $i = 0 ; $i < $tam; $i++) {

    if ($ordenacaotipo[$i]=="A") {

      $sql   = "UPDATE procavaliacao SET ed41_i_sequencia = " . ($i + 1) ." WHERE ed41_i_codigo = {$ordenacao[$i]} ";
      $query = db_query($sql);
    } else {

      $sql   = "UPDATE procresultado SET ed43_i_sequencia = ".($i+1)." WHERE ed43_i_codigo = {$ordenacao[$i]} ";
      $query = db_query($sql);
    }
  }
  ?>
  <script>location.href="edu1_avaliacoes.php?procedimento=<?=$procedimento?>&ed40_c_descr=<?=$ed40_c_descr?>&forma=<?=$forma?>&possuiTurmasEncerradas=<?=$sPossuiTurmasEncerradas?>";</script>
<?php
}

if (isset($opcao) && $opcao=="alterar") {
  $tarefa = "alterar";
} elseif(isset($opcao) && $opcao=="excluir" || isset($db_opcao) && $db_opcao==3) {
  $tarefa = "excluir";
}
$result_d = $clprocavaliacao->sql_record($clprocavaliacao->sql_query("","ed41_i_codigo",""," ed41_i_procedimento = $procedimento"));

?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<form name="form1" method="post" action="">
<table width="100%" border="0" cellspacing="2" cellpading="0">
  <tr>
    <td valign="top" style="border:2px outset #d1d1d1;" width="75%">
      <table border="0" cellspacing="0" cellpading="0">
        <tr >
          <td style="white-space: nowrap;">
            <input id='tipo_avaliacao' name="tipo" type="radio" value="A" checked />
          </td>
          <td style="white-space: nowrap;">
            <label for="tipo_avaliacao">Avaliações Periódicas</label>
          </td>
          <td style="white-space: nowrap;">
            <input id='tipo_resultado' name="tipo" type="radio" value="R" <?=$clprocavaliacao->numrows==0?"disabled":""?> />
          </td>
          <td style="white-space: nowrap;">
            <label for="tipo_resultado">Resultados</label>
          </td>
          <td style="white-space: nowrap;">
            <input type="submit" id="btn_adicionar" name="adicionar" value="Adicionar" />
          </td>
          <td style="width: 80%; text-align: right;">
            <input type="button" id="btnAtualizarDiario" name="atualizar" value="Atualizar Diário" <?=$sBtnAtualizarDiario?> />
          </td>
        </tr>
      </table>
    </td>
    <td rowspan="2" style="border:2px outset #d1d1d1;">
      <table border="0" cellspacing="0" cellpading="0" width="220">
        <tr>
          <td colspan="2" align="center" width="5" style="font-size:10px" cellpading="0">
            A-Avaliações R-Resultados
          </td>
        </tr>
        <tr>
          <td align="center">
            <?AvalResult("ordenacao",$procedimento,8,"multiple","asc","","no","","");?>
      <input name="ordenar" type="submit" id="btn_ordenar" value="Ordenar" onclick="js_selecionar()">
          </td>
          <td>
            <img style="cursor:hand" onClick="js_sobe();return false;" src="skins/img.php?file=Controles/seta_up.png" />
            <br/><br/>
            <img style="cursor:hand" onClick="js_desce()" src="skins/img.php?file=Controles/seta_down.png" />
          </td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td style="border:2px outset #d1d1d1;">
      <?php
        $sql = "SELECT ed41_i_codigo,
                      ed09_c_descr,
                      case
                       when ed41_i_codigo>0 then 'AVALIAÇÃO PERÍODICA' end as ed15_c_nome,
                      ed37_c_tipo,
                      ed41_i_sequencia
               FROM procavaliacao
                inner join periodoavaliacao on periodoavaliacao.ed09_i_codigo = procavaliacao.ed41_i_periodoavaliacao
                inner join formaavaliacao on formaavaliacao.ed37_i_codigo = procavaliacao.ed41_i_formaavaliacao
               WHERE ed41_i_procedimento = $procedimento
               UNION
               SELECT ed43_i_codigo,
                      ed42_c_descr,
                      case
                       when ed43_i_codigo>0 then 'RESULTADO' end as ed15_c_nome,
                      ed37_c_tipo,
                      ed43_i_sequencia
               FROM procresultado
                inner join resultado on resultado.ed42_i_codigo = procresultado.ed43_i_resultado
                inner join formaavaliacao on formaavaliacao.ed37_i_codigo = procresultado.ed43_i_formaavaliacao
               WHERE ed43_i_procedimento = $procedimento
               ORDER BY ed41_i_sequencia
               ";
        $chavepri= array("ed41_i_codigo"=>@$ed41_i_codigo,"ed15_c_nome"=>@$ed15_c_nome);
        $cliframe_alterar_excluir->chavepri=$chavepri;
        @$cliframe_alterar_excluir->sql = $sql;
        $cliframe_alterar_excluir->campos  ="ed09_c_descr,ed37_c_tipo,ed15_c_nome";
        $cliframe_alterar_excluir->labels  ="ed67_i_procavaliacao,ed41_i_formaavaliacao,ed15_c_nome";
        $cliframe_alterar_excluir->legenda="Avaliações Periódicas e Resultados do Procedimento $ed40_c_descr";
        $cliframe_alterar_excluir->msg_vazio ="Não foi encontrado nenhum registro.";
        $cliframe_alterar_excluir->textocabec ="#DEB887";
        $cliframe_alterar_excluir->textocorpo ="#444444";
        $cliframe_alterar_excluir->fundocabec ="#444444";
        $cliframe_alterar_excluir->fundocorpo ="#eaeaea";
        $cliframe_alterar_excluir->iframe_height ="80";
        $cliframe_alterar_excluir->iframe_width ="100%";
        $cliframe_alterar_excluir->tamfontecabec = 9;
        $cliframe_alterar_excluir->tamfontecorpo = 9;
        $cliframe_alterar_excluir->formulario = false;
        $cliframe_alterar_excluir->iframe_alterar_excluir(1);
        $result3 = db_query($sql);
        $linhas3 = pg_num_rows($result3);
        if ($linhas3==0) {
         ?>
         <script>
          document.form1.ordenar.disabled = true;
         </script>
         <?
        }
      ?>
    </td>
  </tr>
  <tr>
    <td colspan="2">
      <?php
        if( isset( $adicionar ) ) {

          $ed15_c_nome = "";
          if($tipo=="A"){
       ?>
         <iframe name="iframe_avaliacoes"
                 id="iframe_avaliacoes"
                 src="edu1_procavaliacao001.php?ed41_i_procedimento=<?=$procedimento?>&ed40_c_descr=<?=$ed40_c_descr?>&forma=<?=$forma?>&possuiTurmasEncerradas=<?=$sPossuiTurmasEncerradas?>"
                 height="320"
                 width="99%"
                 frameborder="0"
                 scrolling="no"
                 style="border: 3px inset #d1d1d1;background-color:#dbdbdb;">
        </iframe>
      <?php
          } else {
      ?>
        <iframe name="iframe_avaliacoes"
                id="iframe_avaliacoes"
                src="edu1_procresultadoabas.php?tarefa=incluir&ed43_i_procedimento=<?=$procedimento?>&ed40_c_descr=<?=$ed40_c_descr?>&forma=<?=$forma?>&possuiTurmasEncerradas=<?=$sPossuiTurmasEncerradas?>"
                height="320"
                width="99%"
                frameborder="0"
                scrolling="no"
                style="border: 3px inset #d1d1d1;background-color:#dbdbdb;">
        </iframe>
      <?php
          }
        }
      ?>
      <?php if(isset($ed15_c_nome) && trim($ed15_c_nome) == "AVALIAÇÃO PERÍODICA") { ?>
        <iframe  name="iframe_avaliacoes"
                 id="iframe_avaliacoes"
                 src="edu1_procavaliacao001.php?chavepesquisa=<?=$ed41_i_codigo?>&tarefa=<?=$tarefa?>&ed41_i_procedimento=<?=$procedimento?>&forma=<?=$forma?>&possuiTurmasEncerradas=<?=$sPossuiTurmasEncerradas?>"
                 height="320"
                 width="99%"
                 frameborder="0"
                 scrolling="no"
                 style="border: 3px inset #d1d1d1;background-color:#dbdbdb;">
        </iframe>
      <?php } elseif (isset($ed15_c_nome) && trim($ed15_c_nome) == "RESULTADO") { ?>
        <iframe name="iframe_avaliacoes"
                id="iframe_avaliacoes"
                src="edu1_procresultadoabas.php?chavepesquisa=<?=$ed41_i_codigo?>&tarefa=<?=$tarefa?>&ed43_i_procedimento=<?=$procedimento?>&forma=<?=$forma?>&possuiTurmasEncerradas=<?=$sPossuiTurmasEncerradas?>"
                height="320"
                width="99%"
                frameborder="0"
                scrolling="no"
                style="border: 3px inset #d1d1d1;background-color:#dbdbdb;">
        </iframe>
      <?php }?>
    </td>
  </tr>
</table>
</form>
</body>
</html>
<script>
js_tabulacaoforms("form1","adicionar",true,1,"adicionar",true);
</script>
<script>

var oGet = js_urlToObject();

if ( oGet.possuiTurmasEncerradas && oGet.possuiTurmasEncerradas == 'S' ) {

  $('btn_adicionar').setAttribute('disabled', 'disabled');
  $('btn_ordenar').setAttribute('disabled', 'disabled');
  $('btnAtualizarDiario').setAttribute('disabled', 'disabled');
}

function js_sobe(){
 var F = document.getElementById("ordenacao");
 var G = document.getElementById("ordenacaotipo");
 if(F.selectedIndex != -1 && F.selectedIndex > 0 || G.selectedIndex != -1 && G.selectedIndex > 0) {
  var SI = F.selectedIndex - 1;
  var SI2 = G.selectedIndex - 1;
  var auxText = F.options[SI].text;
  var auxText2 = G.options[SI2].text;
  var auxValue = F.options[SI].value;
  var auxValue2 = G.options[SI2].value;
  F.options[SI] = new Option(F.options[SI + 1].text,F.options[SI + 1].value);
  G.options[SI2] = new Option(G.options[SI2 + 1].text,G.options[SI2 + 1].value);
  F.options[SI + 1] = new Option(auxText,auxValue);
  G.options[SI2 + 1] = new Option(auxText2,auxValue2);
  F.options[SI].selected = true;
  G.options[SI2].selected = true;
 }
}
function js_desce() {

 var F = document.getElementById("ordenacao");
 var G = document.getElementById("ordenacaotipo");
 if(F.selectedIndex != -1 && F.selectedIndex < (F.length - 1) || G.selectedIndex != -1 && G.selectedIndex < (G.length - 1)) {
  var SI = F.selectedIndex + 1;
  var SI2 = G.selectedIndex + 1;
  var auxText = F.options[SI].text;
  var auxText2 = G.options[SI2].text;
  var auxValue = F.options[SI].value;
  var auxValue2 = G.options[SI2].value;
  F.options[SI] = new Option(F.options[SI - 1].text,F.options[SI - 1].value);
  G.options[SI2] = new Option(G.options[SI2 - 1].text,G.options[SI2 - 1].value);
  F.options[SI - 1] = new Option(auxText,auxValue);
  G.options[SI2 - 1] = new Option(auxText2,auxValue2);
  F.options[SI].selected = true;
  G.options[SI2].selected = true;
 }
}
function js_selecionar(){
 var F = document.getElementById("ordenacao").options;
 var G = document.getElementById("ordenacaotipo").options;
 for(var i = 0;i < F.length;i++) {
   F[i].selected = true;
 }
 for(var i = 0;i < G.length;i++) {
   G[i].selected = true;
 }
 return true;
}
function js_selectum(nome){
 var F = document.getElementById(nome);
 var G = document.getElementById(nome+"tipo");
 for(var i = 0;i < G.options.length;i++){
  if(G.selectedIndex == i){
   F.options[i].selected = true;
  }else{
   F.options[i].selected = false;
  }
 }
}
function js_selectdois(nome){
 var F = document.getElementById(nome);
 var G = document.getElementById(nome+"tipo");
 for(var i = 0;i < F.options.length;i++){
  if(F.selectedIndex == i){
   G.options[i].selected = true;
  }else{
   G.options[i].selected = false;
  }
 }
}
</script>
<?php
function AvalResult($nome,$procedimento,$tamanho,$tipo,$ordem,$disabled,$linhabranco,$where1,$where2){
 $sql = "SELECT ed41_i_codigo as codigo,
                ed09_c_descr as avaliacao,
                case
                 when ed41_i_codigo>0 then 'A' end as tipo,
                ed41_i_sequencia
         FROM procavaliacao
          inner join periodoavaliacao on periodoavaliacao.ed09_i_codigo = procavaliacao.ed41_i_periodoavaliacao
         WHERE ed41_i_procedimento = $procedimento
         $where1
         UNION
         SELECT ed43_i_codigo as codigo,
                ed42_c_descr as resultado,
                case
                 when ed43_i_codigo>0 then 'R' end as tipo,
                ed43_i_sequencia
         FROM procresultado
          inner join resultado on resultado.ed42_i_codigo = procresultado.ed43_i_resultado
         WHERE ed43_i_procedimento = $procedimento
         $where2
         ORDER BY ed41_i_sequencia $ordem
        ";
 $query = db_query($sql);
 $query1 = db_query($sql);
 $linhas = pg_num_rows($query);
 ?>
 <table cellspacing="0" cellpading="0" >
  <tr>
   <td>
    <select name="<?=$nome?>tipo[]" id="<?=$nome?>tipo" size="<?=$tamanho?>" style="font-size:9px;width:40px;" <?=$tipo?> onclick="js_selectum('<?=$nome?>')" <?=$disabled!=""?"$disabled":""?>>
     <?php
     if($linhabranco=="yes"){
      echo "<option value=''></option>";
     }
     for($i=0;$i<$linhas;$i++){
     $dados = pg_fetch_array($query);
      echo "<option value=\"".trim($dados["tipo"])."\">".trim($dados["tipo"])."</option>\n";
     }
     ?>
    </select>
   </td>
   <td>
    <select name="<?=$nome?>[]" id="<?=$nome?>" size="<?=$tamanho?>" style="font-size:9px;width:150px" <?=$tipo?> onclick="js_selectdois('<?=$nome?>')" <?=$disabled!=""?"$disabled":""?>>
     <?php
     if($linhabranco=="yes"){
      echo "<option value=''></option>";
     }
     for($i=0;$i<$linhas;$i++){
     $dados1 = pg_fetch_array($query1);
      echo "<option value=\"".$dados1["codigo"]."\">".trim($dados1["avaliacao"])."</option>\n";
     }
     ?>
    </select>
   </td>
  </tr>
 </table>
 <?php
}
?>
<script type="text/javascript">

var oGet = js_urlToObject();
$('btnAtualizarDiario').observe('click', function() {

  var sMsg  = 'O diário de classe dos alunos será atualizado conforme ordenação dos elementos de avaliação.\n';
      sMsg += 'Confirma a ordenação atual?';

  if ( !confirm(sMsg) ) {
    return;
  }

  var oParametros  = {"exec": 'atualizarDiario', "iProcedimento": oGet.procedimento};
  var oAjaxRequest = new AjaxRequest('edu4_procedimentoavaliacao.RPC.php', oParametros, function (oRetorno, lErro) {

    if ( lErro || !oRetorno.lUtilizaProporcionalidade) {

      alert(oRetorno.sMessage.urlDecode());
      return;
    }


    if (oRetorno.lUtilizaProporcionalidade) {

      var sMsg  = oRetorno.sMessage.urlDecode() + '\n';
          sMsg += "Procedimento utiliza proporcionalidade. A configuração da proporcionalidade nos diários dos alunos ";
          sMsg += "deve ser salva novamente.\nDeseja imprimir um relatorio com os alunos afetados?";
      if (confirm(sMsg)) {

        var sUrl = 'edu2_logatualizacaodiario.php?sArquivo='+oRetorno.sNomeArquivoLog.urlDecode();
         window.open(sUrl,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
      }
    }
  });
  oAjaxRequest.setMessage('Atualizando diários de classe. Essa rotina pode demorar alguns minutos. Aguarde...');
  oAjaxRequest.execute();

});
</script>