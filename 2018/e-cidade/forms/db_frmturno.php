<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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
include("dbforms/db_classesgenericas.php");

$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;

$oDaoTurno->rotulo->label();
$oDaoTurnoReferente->rotulo->label();

$db_botao1 = false;

if (isset($opcao) && $opcao == "alterar") {

  $db_opcao  = 2;
  $db_botao1 = true;

  $sSql1     = $oDaoTurnoReferente->sql_query("",
                                              " ed231_i_referencia as ed231_i_referencia1 ",
                                              "",
                                              " ed231_i_turno = $ed15_i_codigo AND ed231_i_referencia = 1 "
                                             );
  $result1   = $oDaoTurnoReferente->sql_record($sSql1);

  $sSql2     = $oDaoTurnoReferente->sql_query("",
                                              " ed231_i_referencia as ed231_i_referencia2 ",
                                              "",
                                              " ed231_i_turno = $ed15_i_codigo AND ed231_i_referencia = 2 "
                                             );
  $result2   = $oDaoTurnoReferente->sql_record($sSql2);

  $sSql3     = $oDaoTurnoReferente->sql_query("",
                                              " ed231_i_referencia as ed231_i_referencia3 ",
                                              "",
                                              " ed231_i_turno = $ed15_i_codigo AND ed231_i_referencia = 3 "
                                             );
  $result3   = $oDaoTurnoReferente->sql_record($sSql3);

} elseif (isset($opcao) && $opcao == "excluir"
          || isset($db_opcao) && $db_opcao == 3) {

  $db_botao1 = true;
  $db_opcao  = 3;

} else {

  if (isset($alterar)) {

    $db_opcao  = 2;
    $db_botao1 = true;

  } else {
    $db_opcao = 1;
  }

}

if (isset($atualizar)) {

  $tam = sizeof($campos);

  for ($iCont = 0; $iCont < $tam; $iCont++) {

    $sql = "UPDATE turno SET
              ed15_i_sequencia = ".($iCont + 1)."
            WHERE ed15_i_codigo = $campos[$iCont]
           ";
    $query = db_query($sql);

  }

  echo "<script>location.href='".$oDaoTurno->pagina_retorno."'</script>";

}

?>
<form name="form1" method="post" action="">
  <center>
    <table border="0" width="70%">
      <tr>
        <td>
          <table border="0">
            <tr>
              <td nowrap title="<?=@$Ted15_i_codigo?>">
                <?=@$Led15_i_codigo?>
              </td>
              <td>
                <?
                  db_input('ed15_i_codigo', 10, $Ied15_i_codigo, true, 'text', 3, "")
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?=@$Ted15_c_nome?>">
                <?=@$Led15_c_nome?>
              </td>
              <td>
                <?
                  db_input('ed15_c_nome', 20, $Ied15_c_nome, true, 'text', $db_opcao, "")
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?=@$Ted231_i_referencia?>">
                <?=@$Led231_i_referencia?>
              </td>
              <td>
                <input type="checkbox" name="turnos[]" id="turnos"
                       value="1" <?=@pg_num_rows($result1)>0?"checked":""?>
                       <?=$db_opcao==3?"disabled style='background-color:#DEB887;'":""?>> MANHÃ

                <input type="checkbox" name="turnos[]" id="turnos"
                       value="2" <?=@pg_num_rows($result2)>0?"checked":""?>
                       <?=$db_opcao==3?"disabled style='background-color:#DEB887;'":""?>> TARDE

                <input type="checkbox" name="turnos[]" id="turnos"
                       value="3" <?=@pg_num_rows($result3)>0?"checked":""?>
                       <?=$db_opcao==3?"disabled style='background-color:#DEB887;'":""?>> NOITE
              </td>
            </tr>
            <tr>
              <td colspan="2">
                <input name="<?=($db_opcao == 1 ? "incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "alterar" : "excluir"))?>"
                       type="submit" id="db_opcao" value="<?=($db_opcao == 1 ? "Incluir" : ($db_opcao == 2 ||
                       $db_opcao == 22 ? "Alterar" : "Excluir"))?>" <?=($db_botao == false ? "disabled" : "")?>
                       <?=$db_opcao != 3 ? "onclick='return js_verificar();'" : ""?> >

                <input name="cancelar" type="submit" value="Cancelar" <?=($db_botao1 == false ? "disabled" : "")?> >
              </td>
            </tr>
          </table>
        </td>
        <td>
          <table border="0">
            <tr>
              <td>
                <b>Ordenar Turnos:</b><br>
                <select name="campos[]" id="campos" size="4" style="width:250px" multiple>
                <?
                  $sql = "SELECT ed15_i_codigo,ed15_c_nome from turno order by ed15_i_sequencia";
                  $query = db_query($sql);
                  $linhas = pg_num_rows($query);
                  if ($linhas > 0) {
                    for ($i = 0; $i < $linhas; $i++) {
                      $dados = pg_fetch_array($query);
                      echo "<option value=\"".$dados["ed15_i_codigo"]."\">".trim($dados["ed15_c_nome"])."</option>\n";
                    }
                  }
                ?>
                </select>
              </td>
              <td valign="top">
                <br/>
                <img style="cursor:hand" onClick="js_sobe();return false;" src="skins/img.php?file=Controles/seta_up.png" />
                <br/>
                <img style="cursor:hand" onClick="js_desce()" src="skins/img.php?file=Controles/seta_down.png" />
                <br/>
                <input name="atualizar" type="submit" value="Atualizar" onclick="js_selecionar()"/>
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
    <table width="100%">
      <tr>
        <td valign="top"><br>
          <?
            $campos = "ed15_i_codigo,
                       ed15_c_nome,
                       (select array(select case
                                            when ed231_i_referencia = 1
                                              then 'MANHÃ'
                                            when ed231_i_referencia = 2
                                              then 'TARDE'
                                            else 'NOITE' end
                                     from turnoreferente
                                       where ed15_i_codigo = ed231_i_turno
                                    )
                       ) as ed231_i_referencia
                      ";
            $chavepri= array("ed15_i_codigo"=>@$ed15_i_codigo,"ed15_c_nome"=>@$ed15_c_nome);
            $cliframe_alterar_excluir->chavepri=$chavepri;
            $cliframe_alterar_excluir->sql = $oDaoTurno->sql_query("",$campos,"ed15_i_sequencia","");
            $cliframe_alterar_excluir->campos  ="ed15_i_codigo,ed15_c_nome,ed231_i_referencia";
            $cliframe_alterar_excluir->legenda="Registros";
            $cliframe_alterar_excluir->msg_vazio ="Não foi encontrado nenhum registro.";
            $cliframe_alterar_excluir->textocabec ="#DEB887";
            $cliframe_alterar_excluir->textocorpo ="#444444";
            $cliframe_alterar_excluir->fundocabec ="#444444";
            $cliframe_alterar_excluir->fundocorpo ="#eaeaea";
            $cliframe_alterar_excluir->iframe_height ="200";
            $cliframe_alterar_excluir->iframe_width ="100%";
            $cliframe_alterar_excluir->tamfontecabec = 9;
            $cliframe_alterar_excluir->tamfontecorpo = 9;
            $cliframe_alterar_excluir->formulario = false;
            $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
          ?>
        </td>
      </tr>
    </table>
  </center>
</form>

<script>

function js_sobe() {

  var F = document.getElementById("campos");

  if (F.selectedIndex != -1 && F.selectedIndex > 0) {

    var SI                 = F.selectedIndex - 1;
    var auxText            = F.options[SI].text;
    var auxValue           = F.options[SI].value;

    F.options[SI]          = new Option(F.options[SI + 1].text,F.options[SI + 1].value);
    F.options[SI + 1]      = new Option(auxText,auxValue);
    F.options[SI].selected = true;

 }

}

function js_desce() {

  var F = document.getElementById("campos");

  if(F.selectedIndex != -1 && F.selectedIndex < (F.length - 1)) {

    var SI                 = F.selectedIndex + 1;
    var auxText            = F.options[SI].text;
    var auxValue           = F.options[SI].value;

    F.options[SI]          = new Option(F.options[SI - 1].text,F.options[SI - 1].value);
    F.options[SI - 1]      = new Option(auxText,auxValue);
    F.options[SI].selected = true;

  }

}

function js_selecionar() {

  var F = document.getElementById("campos").options;

  for(var i = 0; i < F.length; i++) {
    F[i].selected = true;
  }

  return true;

}

function js_verificar() {

  tam  = document.form1.turnos.length;
  cont = 0;

  for(i = 0; i < tam; i++) {

    if(document.form1.turnos[i].checked == true) {
      cont++;
    }

  }

  if(cont == 0) {

    alert("Informe a referência deste turno!");
    return false;

  }

  return true;

}
</script>