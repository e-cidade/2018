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

//WebSeller

function db_inputdatasaude( $intEspecmed,
                            $nome,
                            $dia = "",
                            $mes = "",
                            $ano = "",
                            $dbcadastro = true,
                            $dbtype = 'text',
                            $db_opcao = 3,
                            $js_script = "",
                            $nomevar = "",
                            $bgcolor = "",
                            $shutdown_function="none",
                            $onclickBT="",
                            $onfocus="",
                            $jsRetornoCal="",
                            $exame=false,
                            $lTodosDias = false,
                            $iUpsSolicitante = "",
                            $iUpsPrestadora = ""
) {

  //#00#//db_inputdata
  //#10#//Função para montar um objeto tipo data. Serão três objetos input na tela mais um objeto input tipo button para
  //#10#//acessar o calendário do sistema
  //#15#//db_inputdata($nome,$dia="",$mes="",$ano="",$dbcadastro=true,$dbtype='text',$db_opcao=3,$js_script="",$nomevar="",$bgcolor="",$shutdown_funcion="none",$onclickBT="",$onfocus"");
  //#20#//Nome            : Nome do campo da documentacao do sistema ou do arquivo
  //#20#//Dia             : Valor para o objeto |db_input| do dia
  //#20#//Mês             : Valor para o objeto |db_input| do mês
  //#20#//Ano             : Valor para o objeto |db_input| do ano
  //#20#//Cadastro        : True se cadastro ou false se nao cadastro Padrão: true
  //#20#//Type            : Tipo a ser incluido para a data Padrão: text
  //#20#//Opcao           : *db_opcao* do programa a ser executado neste objeto input, inclusão(1) alteração(2) exclusão(3)
  //#20#//Script          : JAVASCRIPT  a ser executado juntamento com o objeto, indicando os métodos
  //#20#//Nome Secundário : Nome do input que será gerado, assumindo somente as características do campo Nome
  //#20#//Cor Background  : Cor de fundo da tela, no caso de *db_opcao*=3 será "#DEB887"
  //#20#//shutdown_funcion : função que será executada apos o retorno do calendário
  //#20#//onclickBT       : Função que será executada ao clicar no botão que abre o calendário
  //#20#//onfocus         : Função que será executada ao focar os campos
  //#99#//Quando o parâmetro Opção for de alteração (Opcao = 22) ou exclusão (Opção = 33) o sistema
  //#99#//colocará a sem acesso ao calendário
  //#99#//Para *db_opcao* 3 e 5 o sistema colocará sem o calendário e com readonly
  //#99#//
  //#99#//Os três input gerados para a data terão o nome do campo acrescido do [Nome]_dia, [Nome]_mes e
  //#99#//[Nome]_ano os quais serão acessados pela classe com estes nome.
  //#99#//
  //#99#//O sistema gerá para a primeira data incluída um formulário, um objeto de JanelaIframe do nosso
  //#99#//sistema para que sejá mostrado o calendário.

  global $DataJavaScript;
  if ($db_opcao == 3 || $db_opcao == 22) {
    $bgcolor = "style='background-color:#DEB887'";
  }

  if (isset($dia) && $dia != "" && isset($mes) && $mes != '' && isset($ano) && $ano != "") {
    $diamesano = $dia."/".$mes."/".$ano;
    $anomesdia = $ano."/".$mes."/".$dia;
  }

  $sButtonType = "button";

  ?>
  <input name="<?=($nomevar==""?$nome:$nomevar).""?>" <?=$bgcolor?>
         type="<?=$dbtype?>"
         id="<?=($nomevar==""?$nome:$nomevar).""?>"
    <?=($db_opcao==3 || $db_opcao==22 ?'readonly':($db_opcao==5?'disabled':''))?>
         value="<?=@$diamesano?>" size="10" maxlength="10" autocomplete="off"
         onBlur='js_validaDbData(this);'
         onKeyUp="return js_mascaraData(this,event);"
         onFocus="js_validaEntrada(this);" <?=$js_script?>
         readonly="readonly" class="readonly" />

  <input name="<?=($nomevar==""?$nome:$nomevar)."_dia"?>"   type="hidden" title="" id="<?=($nomevar==""?$nome:$nomevar)."_dia"?>" value="<?=@$dia?>" size="2"  maxlength="2" >
  <input name="<?=($nomevar==""?$nome:$nomevar)."_mes"?>"   type="hidden" title="" id="<?=($nomevar==""?$nome:$nomevar)."_mes"?>" value="<?=@$mes?>" size="2"  maxlength="2" >
  <input name="<?=($nomevar==""?$nome:$nomevar)."_ano"?>"   type="hidden" title="" id="<?=($nomevar==""?$nome:$nomevar)."_ano"?>" value="<?=@$ano?>" size="4"  maxlength="4" >
  <?
  if (($db_opcao < 3) || ($db_opcao == 4)) {
    ?>
    <script>
      var PosMouseY, PosMoudeX;

      function js_comparaDatas<?=($nomevar==""?$nome:$nomevar).""?>(dia,mes,ano) {
        var objData        = document.getElementById('<?=($nomevar==""?$nome:$nomevar).""?>');
        objData.value      = dia+"/"+mes+'/'+ano;
        <?=$jsRetornoCal?>
      }
    </script>
    <?
    if (isset($dbtype) && strtolower($dbtype) == strtolower('hidden')) {
      $sButtonType = "hidden";
    }

    if ( $exame == true ) {
      ?>
      <input value="D"
             type="<?=$sButtonType?>"
             name="dtjs_<?=($nomevar==""?$nome:$nomevar)?>"
             onclick="<?=$onclickBT?>pegaPosMouse(event);show_calendarexames('<?=($nomevar==""?$nome:$nomevar)?>','<?=$shutdown_function?>',<?=$intEspecmed ?>)"  >
      <?
    } else {

      if ( !$lTodosDias ) {

        ?>
        <input value="D"
               type="<?=$sButtonType?>"
               name="dtjs_<?=($nomevar==""?$nome:$nomevar)?>"
               onclick="<?=$onclickBT?>pegaPosMouse(event);show_calendarsaude('<?=($nomevar==""?$nome:$nomevar)?>','<?=$shutdown_function?>',<?=$intEspecmed ?>, <?=$iUpsSolicitante?>, <?=$iUpsPrestadora?>)"  >
        <?
      } else {

        ?>
        <input value="D"
               type="<?=$sButtonType?>"
               name="dtjs_<?=($nomevar==""?$nome:$nomevar)?>"
               onclick="<?=$onclickBT?>pegaPosMouse(event);showCalendarioSaudeTodosDias('<?=($nomevar==""?$nome:$nomevar)?>','<?=$shutdown_function?>',<?=$intEspecmed ?>)"  >
        <?
      }
    }
  }
} //fim function


//função para mostrar mensagens de aviso ao usuário

function MsgAviso($codescola,$tabela,$arquivo=null,$where=null) {
  require_once modification("classes/db_".trim($tabela)."_classe.php");
  $instancia = "cl_".$tabela;
  $cltabela = new $instancia;
  if (trim($tabela)=="escola") {
    $result = $cltabela->sql_record($cltabela->sql_query("","*",""," ed18_i_codigo = $codescola"));
  }else{
    $result = $cltabela->sql_record($cltabela->sql_query("","*","","$where"));
  }
  if ($cltabela->numrows==0) {
    $where = $arquivo!=null?"AND ed90_c_arquivo = '$arquivo'":"";
    $sql = "SELECT * FROM msgaviso
          WHERE trim(ed90_c_tabela) = '$tabela'
          $where";
    $result1 = db_query($sql);
    $dados = pg_fetch_array($result1);
    $arquivo = trim($dados['ed90_c_arqdestino']);
    ?>
    <br>
    <center>
      <fieldset style="width:90%"><legend><b>Aviso Importante:</b></legend>
        <?=$dados["ed90_t_msg"]?><br><br>
        <a href="javascript:location.href='<?=$arquivo?>'" title="<?=$dados['ed90_c_titulolink']?>"><?=$dados["ed90_c_descrlink"]?></a>
      </fieldset>
    </center>
    <?
    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
    exit;
  }
}
function DiasLetivos($data_inicio,$data_fim,$sabado,$calendario,$retorno) {
  $data_in = mktime(0,0,0,substr($data_inicio,5,2),substr($data_inicio,8,2),substr($data_inicio,0,4));
  $data_out = mktime(0,0,0,substr($data_fim,5,2),substr($data_fim,8,2),substr($data_fim,0,4));
  #pega a data de saida em UNIX_TIMESTAMP e diminui da data de entrada UNIX_TIMESTAMP
  $data_entre = $data_out - $data_in;
  #divide a diferenca das datas pelo numero de segundos de um dia e arredonda, para saber o numero de dias inteiro que tem
  $dias = ceil($data_entre/86400);
  $dias2 = $dias;
  $day = 0;
  $nao_util = 0;
  #pega dia, mes e ano da data de entrada
  $mes_inicial = date('m', $data_in);
  $d = date('d', $data_in);
  $m = date('m', $data_in);
  $y = date('Y', $data_in);
  #pega mes e ano da data de saida
  $m2 = date('m', $data_out);
  $y2 = date('Y', $data_out);
  #conta o numero de dias do mes de entrada
  $days_month = date("t", $data_in);
  $mi = date('m', $data_in);
  $semanas = 1;
  #se o dia da entrada + total de dias for menor que total de dias do mes, ou seja, se não passar do mesmo mês.
  if ($dias+$d <= $days_month) {
    for ($i = 0; $i < $dias+1; $i++) {
      $letivo = true;
      $day++;
      #checa o dia da semana para cada dia do mês, se for igual a 0 (domingo) ou 6 (sabado) ele adiciona 1 no dia não útil
      if ($sabado=="N") {
        if (date("w", mktime (0,0,0,$m,$d+$i,$y)) == 0 || date("w", mktime (0,0,0,$m,$d+$i,$y)) == 6) {
          #pesquisa no banco os feriados cadastrados se retornar aquele dia ele adiciona 1 no dia não útil
          $res = db_query("SELECT * FROM feriado WHERE extract(month from ed54_d_data)=$m AND extract(day from ed54_d_data)=$d+$i AND ed54_i_calendario=$calendario");
          if (pg_num_rows($res)==0) {
            $nao_util++;
            $letivo = false;
          }else{
            if (pg_result($res,0,'ed54_c_dialetivo')=="N") {
              $nao_util++;
              $letivo = false;
            }
          }
        }else{
          #pesquisa no banco os feriados cadastrados se retornar aquele dia ele adiciona 1 no dia não útil
          $res = db_query("SELECT * FROM feriado WHERE extract(month from ed54_d_data)=$m AND extract(day from ed54_d_data)=$d+$i AND ed54_i_calendario=$calendario AND ed54_c_dialetivo = 'N' ");
          if ($row = pg_fetch_assoc($res)) {
            $nao_util++;
            $letivo = false;
          }
        }
      }else{
        if (date("w", mktime (0,0,0,$m,$d+$i,$y)) == 0 ) {
          #pesquisa no banco os feriados cadastrados se retornar aquele dia ele adiciona 1 no dia não útil
          $res = db_query("SELECT * FROM feriado WHERE extract(month from ed54_d_data)=$m AND extract(day from ed54_d_data)=$d+$i AND ed54_i_calendario=$calendario");
          if (pg_num_rows($res)==0) {
            $nao_util++;
            $letivo = false;
          }else{
            if (pg_result($res,0,'ed54_c_dialetivo')=="N") {
              $nao_util++;
              $letivo = false;
            }
          }
        }else{
          #pesquisa no banco os feriados cadastrados se retornar aquele dia ele adiciona 1 no dia não útil
          $res = db_query("SELECT * FROM feriado WHERE extract(month from ed54_d_data)=$m AND extract(day from ed54_d_data)=$d+$i AND ed54_i_calendario=$calendario AND ed54_c_dialetivo = 'N' ");
          if ($row = pg_fetch_assoc($res)) {
            $nao_util++;
            $letivo = false;
          }
        }
      }
      if ($letivo==true) {
        $dia_mes_letivo[] = (strlen($d+$i)==1?"0".($d+$i):($d+$i))."-".(strlen($m)==1?"0".$m:$m);
      }
    }
    #se o dia da entrada + total de dias for maior que total de dias do mes, ou seja, se passar do mesmo mês.
  }else{
    #enquanto o mês de entrada for diferente do mês de saida ou ano de entrada for diferente do ano de saida.
    while($m != $m2 || $y != $y2) {
      #pega total de dias do mes de entrada
      if ($m==$mi) {
        $days_month = date("t", mktime (0,0,0,$m,$d,$y))-$d+1;
      }else{
        $days_month = date("t", mktime (0,0,0,$m,$d,$y));
      }
      for ($i = 0; $i < $days_month; $i++) {
        $letivo = true;
        $day++;
        #checa o dia da semana para cada dia do mês, se for igual a 0 (domingo) ou 6 (sabado) ele adiciona 1 no dia não útil
        if ($sabado=="N") {
          if (date("w", mktime (0,0,0,$m,$d+$i,$y)) == 0 || date("w", mktime (0,0,0,$m,$d+$i,$y)) == 6) {
            #pesquisa no banco os feriados cadastrados se retornar aquele dia ele adiciona 1 no dia não útil
            $res = db_query("SELECT * FROM feriado WHERE extract(month from ed54_d_data)=$m AND extract(day from ed54_d_data)=$d+$i AND ed54_i_calendario=$calendario");
            if (pg_num_rows($res)==0) {
              $nao_util++;
              $letivo = false;
            }else{
              if (pg_result($res,0,'ed54_c_dialetivo')=="N") {
                $nao_util++;
                $letivo = false;
              }
            }
          }else{
            #pesquisa no banco os feriados cadastrados se retornar aquele dia ele adiciona 1 no dia não útil
            $res = db_query("SELECT * FROM feriado WHERE extract(month from ed54_d_data)=$m AND extract(day from ed54_d_data)=$d+$i AND ed54_i_calendario=$calendario AND ed54_c_dialetivo = 'N' ");
            if ($row = pg_fetch_assoc($res)) {
              $nao_util++;
              $letivo = false;
            }
          }
        }else{
          if (date("w", mktime (0,0,0,$m,$d+$i,$y)) == 0 ) {
            #pesquisa no banco os feriados cadastrados se retornar aquele dia ele adiciona 1 no dia não útil
            $res = db_query("SELECT * FROM feriado WHERE extract(month from ed54_d_data)=$m AND extract(day from ed54_d_data)=$d+$i AND ed54_i_calendario=$calendario");
            if (pg_num_rows($res)==0) {
              $nao_util++;
              $letivo = false;
            }else{
              if (pg_result($res,0,'ed54_c_dialetivo')=="N") {
                $nao_util++;
                $letivo = false;
              }
            }
          }else{
            #pesquisa no banco os feriados cadastrados se retornar aquele dia ele adiciona 1 no dia não útil
            $res = db_query("SELECT * FROM feriado WHERE extract(month from ed54_d_data)=$m AND extract(day from ed54_d_data)=$d+$i AND ed54_i_calendario=$calendario AND ed54_c_dialetivo = 'N' ");
            if ($row = pg_fetch_assoc($res)) {
              $nao_util++;
              $letivo = false;
            }
          }
        }
        if ($letivo==true) {
          $dia_mes_letivo[] = (strlen($d+$i)==1?"0".($d+$i):($d+$i))."-".(strlen($m)==1?"0".$m:$m);
        }
      }
      #se o mes for igual a 12 (dezembro), mes recebe 1 (janeiro) e ano recebe +1 (próximo ano)
      if ($m == 12) {
        $m = 1;
        $y++;
        #mês recebe mais 1 para fazer o mesmo processo do próximo mês
      }else{
        $m++;
      }
      $d = 1;
      //$dias2 = $dias2 - $day;
      if ($m==$m2) {
        $d3 = date('d', $data_out);
        $m3 = date('m', $data_out);
        $y3 = date('Y', $data_out);
        for ($i = 0; $i < $d3; $i++) {
          $letivo = true;
          $day++;
          #checa o dia da semana para cada dia do mês, se for igual a 0 (domingo) ou 6 (sabado) ele adiciona 1 no dia não útil
          if ($sabado=="N") {
            if (date("w", mktime (0,0,0,$m3,$d+$i,$y3)) == 0 || date("w", mktime (0,0,0,$m3,$d+$i,$y3)) == 6) {
              #pesquisa no banco os feriados cadastrados se retornar aquele dia ele adiciona 1 no dia não útil
              $res = db_query("SELECT * FROM feriado WHERE extract(month from ed54_d_data)=$m3 AND extract(day from ed54_d_data)=$d+$i AND ed54_i_calendario=$calendario");
              if (pg_num_rows($res)==0) {
                $nao_util++;
                $letivo = false;
              }else{
                if (pg_result($res,0,'ed54_c_dialetivo')=="N") {
                  $nao_util++;
                  $letivo = false;
                }
              }
            }else{
              #pesquisa no banco os feriados cadastrados se retornar aquele dia ele adiciona 1 no dia não útil
              $res = db_query("SELECT * FROM feriado WHERE extract(month from ed54_d_data)=$m3 AND extract(day from ed54_d_data)=$d+$i AND ed54_i_calendario=$calendario AND ed54_c_dialetivo = 'N' ");
              if ($row = pg_fetch_assoc($res)) {
                $nao_util++;
                $letivo = false;
              }
            }
          }else{
            if (date("w", mktime (0,0,0,$m3,$d+$i,$y3)) == 0 ) {
              #pesquisa no banco os feriados cadastrados se retornar aquele dia ele adiciona 1 no dia não útil
              $res = db_query("SELECT * FROM feriado WHERE extract(month from ed54_d_data)=$m3 AND extract(day from ed54_d_data)=$d+$i AND ed54_i_calendario=$calendario");
              if (pg_num_rows($res)==0) {
                $nao_util++;
                $letivo = false;
              }else{
                if (pg_result($res,0,'ed54_c_dialetivo')=="N") {
                  $nao_util++;
                  $letivo = false;
                }
              }
            }else{
              #pesquisa no banco os feriados cadastrados se retornar aquele dia ele adiciona 1 no dia não útil
              $res = db_query("SELECT * FROM feriado WHERE extract(month from ed54_d_data)=$m3 AND extract(day from ed54_d_data)=$d+$i AND ed54_i_calendario=$calendario AND ed54_c_dialetivo = 'N' ");
              if ($row = pg_fetch_assoc($res)) {
                $nao_util++;
                $letivo = false;
              }
            }
          }
          if ($letivo==true) {
            $dia_mes_letivo[] = (strlen($d+$i)==1?"0".($d+$i):($d+$i))."-".(strlen($m3)==1?"0".$m3:$m3);
          }
        }
      }
    }
  }
  $diasletivos = $day-$nao_util;
  $cont = 0;
  for($r=0;$r<count($dia_mes_letivo);$r++) {
    $array_data = explode("-",$dia_mes_letivo[$r]);
    if (trim($array_data[1])!=$mes_inicial || $r==(count($dia_mes_letivo)-1)) {
      $mes_qtdias[] = $mes_inicial.",".($r==(count($dia_mes_letivo)-1)?$cont+1:$cont);
      $mes_inicial = $array_data[1];
      $cont = 0;
    }
    $cont++;
  }
  if ($retorno==1) {
    return $diasletivos;
  }elseif ($retorno==2) {
    return $dia_mes_letivo;
  }elseif ($retorno==3) {
    return $mes_qtdias;
  }
}
function Situacao($situacao,$matricula) {
  if (trim($situacao)=="MATRICULADO") {
    $sql = "SELECT ed60_c_tipo
          FROM matricula
          WHERE ed60_i_codigo = $matricula
         ";
    $result = db_query($sql);
    $tipo = pg_result($result,0,0);
    if ($tipo=="N") {
      $retorno = "MATRICULADO";
    }else{
      $retorno = "REMATRICULADO";
    }
  }else{
    $retorno = $situacao;
  }
  return $retorno;
}

function eduparametros($escola) {
  $sql2 = "SELECT *
          FROM edu_parametros
          WHERE ed233_i_escola = $escola
         ";
  $result2 = db_query($sql2);
  if (pg_num_rows($result2)>0) {
    $retorno = pg_result($result2,0,"ed233_c_decimais");
  }else{
    $retorno = null;
  }
  return $retorno;
}

function TiraAcento($sString, $lMaiusculo = true) {

  $aAcentos = array('á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú', 'à', 'À', 'Â', 'â', 'Ê', 'ê',
    'ô', 'Ô', 'ü', 'Ü', 'ï', 'Ï', 'ö', 'Ö', 'ñ', 'Ñ', 'ã', 'Ã', 'õ', 'Õ', 'ç', 'Ç',
    'ª', 'º', 'ä', 'Ä', '\\'
  );

  if ($lMaiusculo) {

    $aLetras  = array('A', 'E', 'I', 'O', 'U', 'A', 'E', 'I', 'O', 'U', 'A', 'A', 'A', 'A', 'E', 'E',
      'O', 'O', 'U', 'U', 'I', 'I', 'O', 'O', 'N', 'N', 'A', 'A', 'O', 'O', 'C', 'C',
      'A', 'O', 'A', 'A', ' '
    );

  } else {

    $aLetras  = array('a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U', 'a', 'A', 'A', 'a', 'E', 'e',
      'o', 'O', 'u', 'U', 'i', 'I', 'o', 'O', 'n', 'N', 'a', 'A', 'o', 'O', 'c', 'C',
      'a', 'o', 'a', 'A', ' '
    );
  }

  return str_replace($aAcentos, $aLetras, $sString);

}

function VerParametroNota($escola) {
  $sql2 = "SELECT *
          FROM edu_parametros
          WHERE ed233_i_escola = $escola
         ";
  $result2 = db_query($sql2);
  if (pg_num_rows($result2)>0) {
    $retorno = pg_result($result2,0,"ed233_c_notabranca");
  }else{
    $retorno = "N";
  }
  return $retorno;
}
function calcage($dd,$mm,$yy,$dd2,$mm2,$yy2,$iFormato = 1) {
  $yy  = $yy * 1;
  $yy2 = $yy2 * 1;

  if ($yy < 100 && $yy < 20) {$yy = $yy + 2000;}
  if ($yy2 < 100 && $yy2 > 20) {$yy2 = $yy2 + 1900;}
  if ($yy2 < 100 && $yy2 < 20) {$yy2 = $yy2 + 2000;}

  //firstdate = new Date(mm+'/'+ dd +'/'+ yy)
  $mm = $mm + 1;

  //seconddate = new Date(mm2+'/'+ dd2 +'/'+ yy2)
  $mm2 = $mm2 + 1;

  $ageyears = $yy2 - $yy;

  if ($mm2 == $mm) {
    if ($dd2 < $dd) {
      $mm2 = $mm2 + 12;
      $ageyears = $ageyears - 1;
    }
  }

  if ($mm2 < $mm) {
    $mm2 = $mm2 + 12;
    $ageyears = $ageyears - 1;
    $agemonths = $mm2 - $mm;
  }

  $agemonths = $mm2 - $mm;

  if ($dd2 < $dd) {
    $agemonths = $agemonths - 1;
    $dd2 = $dd2 + 30;
    if ($mm2 == $mm) {
      $agemonths = 0;
      $ageyears = $ageyears - 1;
    }
  }
  $agedays = $dd2 - $dd;

  if ($iFormato == 1) {
    return $totalage =  $ageyears . ' anos, '. $agemonths .' meses e '. $agedays .' dias';
  } else {
    return $ageyears.' || '.$agemonths.' || '.$agedays;
  }

}

function ResultadoFinal($ed60_i_codigo,$ed60_i_aluno,$ed60_i_turma,$ed60_c_situacao,$ed60_c_concluida, $iCodigoEnsino = null) {

  if ($ed60_c_situacao == 'EVADIDO') {
    return $ed60_c_situacao;
  }

  $oTurma         = TurmaRepository::getTurmaByCodigo( $ed60_i_turma );
  if ( !$oTurma instanceof Turma ) {
    throw new BusinessException("Houve um erro ao tentar buscar os dados da turma.");
  }
  $iAnoCalendario = $oTurma->getCalendario()->getAnoExecucao();

  $resultado = "";
  $sql_param = "SELECT ed29_i_codigo,ed29_i_avalparcial,ed57_i_escola
                  FROM turma
                 inner join base on ed31_i_codigo = ed57_i_base
                 inner join cursoedu on ed29_i_codigo = ed31_i_curso
                 WHERE ed57_i_codigo = $ed60_i_turma
                 ";
  $result_param = db_query($sql_param);
  $ed29_i_codigo = pg_result($result_param,0,0);
  $ed29_i_avalparcial = pg_result($result_param,0,1);
  $codescola = pg_result($result_param,0,2);
  $sql_etp = "SELECT ed221_i_serie,ed60_c_situacao
              FROM matriculaserie
               inner join matricula on ed60_i_codigo = ed221_i_matricula
              WHERE ed221_c_origem = 'S'
              AND ed60_i_codigo = $ed60_i_codigo
             ";
  $result_etp = db_query($sql_etp);
  $ed221_i_serie = pg_result($result_etp,0,0);
  $sSqlReg  = " SELECT ed61_i_codigo
                FROM histmpsdisc
                 inner join historicomps on ed62_i_codigo = ed65_i_historicomps
                 inner join historico on ed61_i_codigo = ed62_i_historico
                WHERE ed61_i_aluno  = $ed60_i_aluno
                AND ed61_i_curso = $ed29_i_codigo
                AND ed62_i_serie = $ed221_i_serie
                AND ed62_c_resultadofinal = 'P'
                AND exists(select * from regencia
                           where ed59_i_turma = $ed60_i_turma
                           and ed59_i_serie = $ed221_i_serie
                           and ed59_i_disciplina = ed65_i_disciplina)";
  $ResultReg = db_query($sSqlReg);
  $iLinhasReg = pg_num_rows($ResultReg);
  if ( in_array( trim($ed60_c_situacao), array("CLASSIFICADO", "AVANÇADO", "RECLASSIFICADO") ) ) {
    $resultado = trim($ed60_c_situacao);
  } elseif (trim($ed60_c_situacao)=="TRANSFERIDO FORA" || trim($ed60_c_situacao)=="TRANSFERIDO REDE") {

    if ($ed60_c_concluida=="S") {
      $resultado = Situacao($ed60_c_situacao,$ed60_i_codigo);
    } else {
      $resultado = "EM ANDAMENTO";
    }
  } else {

    $sql4 = "SELECT ed95_c_encerrado
            FROM diario
             inner join aluno on ed47_i_codigo = ed95_i_aluno
             inner join diariofinal on ed74_i_diario = ed95_i_codigo
             inner join regencia on ed59_i_codigo = ed95_i_regencia
            WHERE ed95_i_aluno = $ed60_i_aluno
            AND ed95_c_encerrado = 'S'
            AND ed95_i_regencia in (select ed59_i_codigo
                                    from regencia
                                    where ed59_i_turma = $ed60_i_turma
                                    and ed59_i_serie = $ed221_i_serie
                                    and ed59_c_condicao = 'OB')
           ";

    $result4 = db_query($sql4);
    $linhas4 = pg_num_rows($result4);
    if ($linhas4 == 0) {

      if ($ed60_c_concluida=="S") {
        $resultado = Situacao($ed60_c_situacao, $ed60_i_codigo);
      } else {

        $resultado = "EM ANDAMENTO";

        $sSqlValidaRecuperacao  = "SELECT 1 ";
        $sSqlValidaRecuperacao .= "  FROM diario ";
        $sSqlValidaRecuperacao .= " INNER JOIN diarioresultado on diarioresultado.ed73_i_diario = diario.ed95_i_codigo ";
        $sSqlValidaRecuperacao .= " INNER JOIN diarioresultadorecuperacao on diarioresultadorecuperacao.ed116_diarioresultado = diarioresultado.ed73_i_codigo ";
        $sSqlValidaRecuperacao .= " INNER JOIN regencia    ON ed59_i_codigo = ed95_i_regencia ";
        $sSqlValidaRecuperacao .= " WHERE ed95_i_aluno = {$ed60_i_aluno} ";
        $sSqlValidaRecuperacao .= "   AND ed95_i_regencia IN (SELECT ed59_i_codigo ";
        $sSqlValidaRecuperacao .= "                             FROM regencia ";
        $sSqlValidaRecuperacao .= "                            WHERE ed59_i_turma = {$ed60_i_turma} ";
        $sSqlValidaRecuperacao .= "                              AND ed59_i_serie = {$ed221_i_serie} ";
        $sSqlValidaRecuperacao .= "                              AND ed59_c_condicao = 'OB') ";

        $rsValidaRecuperacao = db_query($sSqlValidaRecuperacao);

        if ($rsValidaRecuperacao && pg_num_rows($rsValidaRecuperacao) > 0) {
          $resultado = "EM RECUPERAÇÃO";
        }


      }
    } else {

      $sql41 = "SELECT ed74_c_resultadofinal,ed74_c_resultadofreq,
               exists(select 1
                        from progressaoparcialaluno
                             inner join progressaoparcialalunodiariofinalorigem on ed107_progressaoparcialaluno = ed114_sequencial
                       where ed74_i_codigo = ed107_diariofinal
                       and   ed114_situacaoeducacao <> ".ProgressaoParcialAluno::INATIVA."
                     ) as disciplina_com_progressao
                FROM diario
                     inner join aluno       on ed47_i_codigo = ed95_i_aluno
                     inner join diariofinal on ed74_i_diario = ed95_i_codigo
               WHERE ed95_i_aluno = $ed60_i_aluno
                 AND ed95_c_encerrado = 'S'
                 AND ed95_i_regencia in (select ed59_i_codigo
                                           from regencia
                                          where ed59_i_turma = $ed60_i_turma
                                            and ed59_i_serie = $ed221_i_serie
                                            and ed59_c_condicao = 'OB')";
      $result41 = db_query($sql41);
      $linhas41 = pg_num_rows($result41);
      $res_final = "";
      $sep = "";
      for($f = 0; $f < $linhas4; $f++) {

        $ed74_c_resultadofinal = pg_result($result41,$f,'ed74_c_resultadofinal')==""?" ":pg_result($result41,$f,'ed74_c_resultadofinal');
        $lAprovacaoParcial     = pg_result($result41,$f,'disciplina_com_progressao') == "t"? true: false;
        if ($lAprovacaoParcial) {
          $ed74_c_resultadofinal = 'D';
        }
        $res_final .= $sep.$ed74_c_resultadofinal;
        $sep = ",";

      }
      if (strstr($res_final," ")) {

        if ($ed60_c_concluida=="S") {

          if (strstr($res_final,"R")) {

            $resultado = "REPROVADO";
            if (!empty($iCodigoEnsino)) {
              $aDadosTermo = DBEducacaoTermo::getTermoEncerramento($iCodigoEnsino, 'R', $iAnoCalendario);
              if (isset($aDadosTermo[0])) {
                $resultado = $aDadosTermo[0]->sDescricao;
              }
            }
          } else if  (strstr($res_final,"D")) {
            $resultado = "APROVADO COM PROGRESSAO PARCIAL /DEPENDÊNCIA";
          } else {
            $resultado = Situacao($ed60_c_situacao,$ed60_i_codigo);
          }
        } else {
          $resultado = "EM ANDAMENTO";
        }
      } elseif (strstr($res_final,"R")) {

        $resultado = "REPROVADO";
        if (!empty($iCodigoEnsino)) {
          $aDadosTermo = DBEducacaoTermo::getTermoEncerramento($iCodigoEnsino, 'R', $iAnoCalendario);
          if (isset($aDadosTermo[0])) {
            $resultado = $aDadosTermo[0]->sDescricao;
          }
        }
      } else if  (strstr($res_final,"D")) {
        $resultado = "APROVADO COM PROGRESSAO PARCIAL /DEPENDÊNCIA";
      } else {

        if ($ed29_i_avalparcial==2 && $iLinhasReg > 0) {
          $resultado = "APROVADO PARCIAL";
          if (!empty($iCodigoEnsino)) {

            $aDadosTermo = DBEducacaoTermo::getTermoEncerramento($iCodigoEnsino, "P", $iAnoCalendario);
            if (isset($aDadosTermo[0])) {
              $resultado = $aDadosTermo[0]->sDescricao;
            }
          }
        } else {

          $resultado = "APROVADO";
          if (!empty($iCodigoEnsino)) {

            $aDadosTermo = DBEducacaoTermo::getTermoEncerramento($iCodigoEnsino, "A", $iAnoCalendario);



            if (isset($aDadosTermo[0])) {
              $resultado = $aDadosTermo[0]->sDescricao;
            }
          }
        }
      }
    }
  }
  return $resultado;
}

function LimpaResultadoFinal($matricula) {
  $result = db_query("SELECT ed60_i_turma,ed60_i_aluno,ed221_i_serie
                     FROM matricula
                      inner join matriculaserie on ed221_i_matricula = ed60_i_codigo
                     WHERE ed60_i_codigo = $matricula
                     AND ed221_c_origem = 'S'
                    ");
  if (pg_num_rows($result)>0) {
    $ed60_i_turma = pg_result($result,0,0);
    $ed60_i_aluno = pg_result($result,0,1);
    $ed221_i_serie = pg_result($result,0,2);
    $result1 = db_query("UPDATE diariofinal SET
                       ed74_i_procresultadoaprov = null,
                       ed74_c_valoraprov = '',
                       ed74_c_resultadoaprov = '',
                       ed74_i_procresultadofreq = null,
                       ed74_i_percfreq = null,
                       ed74_c_resultadofreq = '',
                       ed74_c_resultadofinal = ''
                      WHERE ed74_i_diario in (select ed95_i_codigo
                                              from diario
                                              where ed95_i_aluno = $ed60_i_aluno
                                              and ed95_i_regencia in (select ed59_i_codigo
                                                                      from regencia
                                                                      where ed59_i_turma = $ed60_i_turma
                                                                      and ed59_i_serie = $ed221_i_serie
                                                                      )
                                              )
                     ");
  }
}

function MatriculaPosterior( $iMatricula ) {

  $lTemPosterior = false;

  $sql1    = "SELECT ed60_d_datasaida, ed60_d_datamatricula, ed60_i_aluno       ";
  $sql1   .= "  FROM turma                                                      ";
  $sql1   .= "       inner join calendario on ed52_i_codigo = ed57_i_calendario ";
  $sql1   .= "       inner join matricula  on ed57_i_codigo = ed60_i_turma      ";
  $sql1   .= " WHERE ed60_i_codigo = {$iMatricula}                              ";
  $sql1   .= " order by ed60_i_codigo desc                                      ";
  $result1 = db_query( $sql1 );

  if( $result1 && pg_num_rows( $result1 ) > 0 ) {

    $oRetornoMatricula  = db_utils::fieldsMemory( $result1, 0 );

    if( !empty( $oRetornoMatricula->ed60_d_datasaida ) ) {

      $sql2    = "SELECT 1 ";
      $sql2   .= "  FROM matricula ";
      $sql2   .= " WHERE ed60_i_aluno = {$oRetornoMatricula->ed60_i_aluno} ";
      $sql2   .= "   AND ed60_d_datamatricula >= '{$oRetornoMatricula->ed60_d_datasaida}' ";
      $sql2   .= "   AND ed60_c_situacao NOT IN ('TROCA DE MODALIDADE', 'CANCELADO', 'EVADIDO')";
      $sql2   .= "   AND ed60_i_codigo <> {$iMatricula}";
      $result2 = db_query( $sql2 );

      if( $result2 && pg_num_rows( $result2 ) > 0 ) {
        $lTemPosterior = true;
      }

    } else {

      $sql3    = "SELECT 1 ";
      $sql3   .= "  FROM matricula  ";
      $sql3   .= " WHERE ed60_i_aluno = {$oRetornoMatricula->ed60_i_aluno}                   ";
      $sql3   .= "   and ed60_d_datamatricula > '{$oRetornoMatricula->ed60_d_datamatricula}' ";
      $sql3   .= "   AND ed60_c_situacao NOT IN ('TROCA DE MODALIDADE', 'CANCELADO')         ";
      $result3 = db_query($sql3);

      if( $result3 && pg_num_rows( $result3 ) > 0 ) {
        $lTemPosterior = true;
      }
    }
  }

  return $lTemPosterior;
}

function RFanterior($matricula) {

  $sql = "SELECT ed57_i_calendario,ed221_i_serie,ed60_i_aluno
         FROM matricula
          inner join matriculaserie on ed221_i_matricula = ed60_i_codigo
          inner join turma on ed57_i_codigo = ed60_i_turma
         WHERE ed60_i_codigo = $matricula
         AND ed221_c_origem = 'S'
        ";
  $result = db_query($sql);
  $calendario = pg_result($result,0,0);
  $serie = pg_result($result,0,1);
  $aluno = pg_result($result,0,2);
  $sql1 = "SELECT ed60_i_codigo,ed57_i_codigo,ed57_c_descr,ed60_c_situacao,ed60_c_concluida
          FROM matricula
           inner join matriculaserie on ed221_i_matricula = ed60_i_codigo
           inner join turma on ed57_i_codigo = ed60_i_turma
          WHERE ed60_i_aluno = $aluno
          AND ed60_i_codigo not in ($matricula)
          AND (ed57_i_calendario not in ($calendario)
               OR
               (ed57_i_calendario = $calendario AND ed221_i_serie != $serie)
              )
          AND ed60_c_ativa = 'S'
          AND ed60_c_concluida = 'S'
          AND ed221_c_origem = 'S'
          ORDER BY ed60_d_datamatricula desc LIMIT 1
         ";

  $result1 = db_query($sql1);
  $linhas1 = pg_num_rows($result1);
  if ($linhas1>0) {
    $codigo = pg_result($result1,0,0);
    $turma = pg_result($result1,0,1);
    $descrturma = pg_result($result1,0,2);
    $situacao = trim(pg_result($result1,0,3));
    $concluida = trim(pg_result($result1,0,4));
    if ($situacao=="CLASSIFICADO" || $situacao=="AVANÇADO") {
      $rfanterior = "APROVADO";
    }elseif ($situacao=="MATRICULADO") {
      $rfanterior = ResultadoFinal($codigo,$aluno,$turma,$situacao,$concluida);
    }else{
      $rfanterior = $situacao;
    }
  }else{
    $descrturma = "";
    $rfanterior = "";
  }
  if ($rfanterior=="EM ANDAMENTO") {
    $descrturma = "";
    $rfanterior = "";
  }
  return trim($descrturma)."|".trim($rfanterior);
}




function data_farmacia($ano,$tipo='1T') {
  //#10#// retorna datas bimestrais, trimestrais,quadrimestras ou semestrais
  //#20#// tipo: [1B|2B|3B|4B|5B|6B|1T|2T|T3|T4|1Q|2Q|3Q|1S|2S|M1|M2|M3|M4|M5|M6|M7|M8|M9|M10|M11|M12|QUIN1|QUIN2]
  //#20#//
  $mes_ini = '';
  $mes_fin = '';
  $texto ='';
  $abrev ='';

  if ($tipo=='1T') { ////COMEÇA TRIMESTRE
    $mes_ini=1;  $mes_fin=3;
    $texto = 'PRIMEIRO TRIMESTRE';
    $abrev = 'Trimestre';
  } elseif ($tipo=='2T') {
    $mes_ini=4;  $mes_fin=6;
    $texto = 'SEGUNDO TRIMESTRE';
    $abrev = 'Trimestre';
  } elseif ($tipo=='3T') {
    $mes_ini=7;  $mes_fin=9;
    $texto = 'TERCEIRO TRIMESTRE';
    $abrev = 'Trimestre';
  } elseif ($tipo=='4T') {
    $mes_ini=10;  $mes_fin=12;
    $texto = 'QUARTO TRIMESTRE';
    $abrev = 'Trimestre';
  }  elseif ($tipo=='1M') { ///COMEÇA OS MESES X MESES
    $mes_ini=1;  $mes_fin=1;
    $texto = 'JANEIRO';
    $abrev = 'Janeiro';
  }  elseif ($tipo=='2M') {
    $mes_ini=2;  $mes_fin=2;
    $texto = 'FEVEREIRO';
    $abrev = 'Fevereiro';
  }  elseif ($tipo=='3M') {
    $mes_ini=3;  $mes_fin=3;
    $texto = 'MARÇO';
    $abrev = 'Março';
  }  elseif ($tipo=='4M') {
    $mes_ini=4;  $mes_fin=4;
    $texto = 'ABRIL';
    $abrev = 'Abril';
  }  elseif ($tipo=='5M') {
    $mes_ini=5;  $mes_fin=5;
    $texto = 'MAIO';
    $abrev = 'Maio';
  }  elseif ($tipo=='6M') {
    $mes_ini=6;  $mes_fin=6;
    $texto = 'JUNHO';
    $abrev = 'Junho';
  }  elseif ($tipo=='7M') {
    $mes_ini=7;  $mes_fin=7;
    $texto = 'JULHO';
    $abrev = 'Julho';
  }  elseif ($tipo=='8M') {
    $mes_ini=8;  $mes_fin=8;
    $texto = 'AGOSTO';
    $abrev = 'Agosto';
  }  elseif ($tipo=='9M') {
    $mes_ini=9;  $mes_fin=9;
    $texto = 'SETEMBRO';
    $abrev = 'Setembro';
  }  elseif ($tipo=='10M') {
    $mes_ini=10;  $mes_fin=10;
    $texto = 'OUTUBRO';
    $abrev = 'Outubro';
  }  elseif ($tipo=='11M') {
    $mes_ini=11;  $mes_fin=11;
    $texto = 'NOVEMBRO';
    $abrev = 'Novembro';
  }  elseif ($tipo=='12M') {
    $mes_ini=12;  $mes_fin=12;
    $texto = 'DEZEMBRO';
    $abrev = 'Dezembro';
  }  elseif ($tipo=='1A') {
    $mes_ini=1;  $mes_fin=12;
    $texto = 'ANO';
    $abrev = 'ano';
  }  else {
    echo "Datas inválidas tipo=$tipo ano=$ano ";
    exit;
  }
  $data_ini = $ano."-".$mes_ini."-01";
  $data_fin = $ano."-".$mes_fin."-".ultimo_dia_mes($mes_fin, $ano);
  $matriz[0] = $data_ini;
  $matriz[1] = $data_fin;
  $matriz['texto']=$texto;
  $matriz['periodo']=$abrev;

  return $matriz;
}



function retorna_dia($ano,$mes,$dia,$tipo='1D') {
  //#10#// retorna datas diárias
  //#20#// tipo: [D1|D2.....]
  //#20#//

  $dia_ini = '';
  $dia_fin = '';
  $texto ='';
  $abrev ='';

  $data_ini = $ano."-".$mes."-".$dia;
  $data_fin = $ano."-".$mes."-".$dia;
  $matriz[0] = $data_ini;
  $matriz[1] = $data_fin;

  return $matriz;

}


////////////////////////////////////////////////
// Retorna a data de inicio da ultima quinzena
function inicio_ultima_quinzena()
{
  $data_atual = getdate();

  if ($data_atual['mday'] <= 15)
  {
    $mes = $data_atual['mon'] - 1;
    $ano = $data_atual['year'];
    if ($mes == 0)
    {
      $mes = 12;
      $ano --;
    }
    return date("d/m/y",strtotime($ano . sprintf("%02d", $mes) . "16"));
  }
  else
  {
    return date("d/m/y",strtotime($data_atual['year'] .
      sprintf("%02d", $data_atual['mon']) . "01"));
  }
}

// Retorna a data de termino da ultima quinzena
function fim_ultima_quinzena()
{
  $data_atual = getdate();

  if ($data_atual['mday'] <= 15)
  {
    $mes = $data_atual['mon'] - 1;
    $ano = $data_atual['year'];
    if ($mes == 0)
    {
      $mes = 12;
      $ano --;
    }
    return date("d/m/y",strtotime("{$ano}-{$mes}-" .
      cal_days_in_month(CAL_GREGORIAN, $mes, $ano)));
  }
  else
  {
    return date("d/m/y",strtotime($data_atual['year'] .
      sprintf("%02d", $data_atual['mon']) . "15"));
  }
}



/*
 * Se ela estiver assim 0000-00-00, ela converte para 00/00/0000.
 * Se ela estiver assim 00/00/0000, ela converte para 0000-00-00.
 */
function converte_data($data) {
  if (strstr($data, "/")) {//verifica se tem a barra /
    $d = explode ("/", $data);//tira a barra
    $invert_data = "$d[2]-$d[1]-$d[0]";//separa as datas $d[2] = ano $d[1] = mês etc...
    return $invert_data;
  }
  elseif (strstr($data, "-")) {
    $d = explode ("-", $data);
    $invert_data = "$d[2]/$d[1]/$d[0]";
    return $invert_data;
  }
  else{
    return "Data invalida";
  }

}




function db_geraArquivoOidfarmacia ($arquivo,$arquivoAlt=null,$opcao=1,$conn) {
  /*
   * $arquivo    => o arquivo do type "file", o arquivo a ser gravado
   * $arquivoAlt => o arquivo ja existente no banco,
   *                a opção alterar a função altera do $arquivoAlt para o $arquivo
   *                na opção excluir a função exclui este arquivo
   * $opcao      => 1 = incluir, 2= alterar, 3 = excluir
   * $conn       => conexão com banco
   */

  if ($opcao==2) {
    pg_lo_unlink($conn, $arquivoAlt);
  }
  if ($opcao==3) {
    pg_lo_unlink($conn, $arquivoAlt);
    return "null";
  }

  $nomeArquivo        = $arquivo;
  $localRecebeArquivo = $arquivo;

  if ( trim($localRecebeArquivo) != "") {
    $arquivoGrava = fopen($localRecebeArquivo, "rb");
    if ($arquivoGrava == false) {
      echo "erro ao abrir o arquivo: $localRecebeArquivo";
      exit;
    }
    $dados = fread($arquivoGrava, filesize($localRecebeArquivo));
    if ($dados == false) {
      echo "erro fread";
      exit;
    }
    fclose($arquivoGrava);
    $oidgrava = pg_lo_create();
    if ($oidgrava == false) {
      echo "erro pg_lo_create";
      exit;
    }


    $objeto = pg_lo_open($conn, $oidgrava, "w");
    if ($objeto != false) {
      $erro = pg_lo_write($objeto, $dados);
      if ($erro == false) {
        echo "erro pg_lo_write";
        exit;
      }
      pg_lo_close($objeto);
    } else {
      $erro_msg ("Operação Cancelada!!");
      $sqlerro = true;
    }

    return $oidgrava;
  }

}

/**
 * 01/03/2013 - Adicionado o parâmetro $sGeraResultado, que controla o jon com a tabela procresultado
 *            - Antes estava fixo no fonte.
 * @param unknown_type $matricula
 * @param unknown_type $relatorio
 * @param unknown_type $iAno
 * @param String $sGeraResultado
 * @return string
 */
function GradeAproveitamentoSQL($matricula,$relatorio="N", $iAno, $sGeraResultado = "S") {
  #Campos de Retorno do sql:
  ## 01 - Descrição da Disciplina
  ## 02 - Array contendo informações de cada elemento(periodo de avaliação e/ou resultado)
  ##      do procedimento de avaliação, separados por "|"
  ##      [0] - sequencial
  ##      [1] - abreviatura da descrição do elemento
  ##      [2] - forma de avaliação do elemento
  ##      [3] - aproveitamento do aluno no elemento
  ##      [4] - n° de faltas do aluno no elemento
  ##      [5] - se o elemento é periodo de avaliação ou resultado: AVA->Periodo de Avaliação | RES->Resultado
  ##      [6] - ordenação do elemento no procedimento de avaliação
  ##      [7] - se aluno atingiu aproveitamento minimo no elemento: S->Sim | N->Não
  ##      [8] - descrição do elemento
  ##      [9] - se o elemento está amparado: S->Sim | N->Não
  ## 03 - Nota Parcial
  ## 04 - Total de Aulas Dadas ou Dias Letivos
  ## 05 - Percentual de Frequência
  ## 06 - Aproveitamento do aluno no Resultado Final
  ## 07 - Resultado Final da matrícula: APR->Aprovado | REP->Reprovado
  ## 08 - Total de Faltas
  ## 09 - Total de Faltas Abonadas
  ## 10 - Mínimo para Aprovação do Resultado que gera o Resultado Final
  ## 11 - Descrição dos Níveis da Forma de Avaliação, caso a turma tenha forma de avaliação NIVEL
  ## 12 - Se matricula está concluida: S->Sim | N->Não
  ## 13 - Situação da Matrícula
  ## 14 - Código do Aluno
  ## 15 - Código da Turma
  ## 16 - Descrição da Etapa
  ## 17 - Se parâmetro Calcular Média Final está habilitado: S->Sim | N->Não
  ## 18 - Se parâmetro Casas Decimais está habilitado: S->Sim | N->Não
  ## 19 - Medidor da Frequência: PERIODOS ou DIAS LETIVOS
  ## 20 - Forma de Avaliação do Resultado que gera o Resultado Final
  ## 21 - Convencoes de Amparo
  ## 22 - Forma de Obtenção do Resultado que gera o Resultado Final
  if ($relatorio=="S") {
    $restricao1 = " AND procavaliacao.ed41_c_boletim = 'S'";
    $restricao2 = " AND procresultado_res.ed43_c_boletim = 'S'";
    $restricao3 = "";
  }else{
    $restricao1 = "";
    $restricao2 = "";
    $restricao3 = "AND procresultado_res.ed43_c_geraresultado = 'N'";
  }
  require_once(modification("model/educacao/ArredondamentoNota.model.php"));
  $iCasasDecimais = ArredondamentoNota::getNumeroCasasDecimais($iAno);
  $sql="SELECT distinct caddisciplina.ed232_c_descrcompleta as disciplina,regencia.ed59_i_ordenacao,
 regencia.ed59_i_codigo as codigo_disciplina,
              (SELECT ARRAY ( SELECT coalesce(lpad(procavaliacao.ed41_i_sequencia,3,0)::varchar,'')||'|'||
                                     coalesce(periodoavaliacao.ed09_c_abrev::varchar,'')||'|'||
                                     coalesce(formaavaliacao.ed37_c_tipo::varchar,'')||'|'||
                                     coalesce(
                                      case when diarioavaliacao.ed72_c_amparo = 'S'
                                       then
                                        case when amparo.ed81_i_justificativa is not null
                                         then 'Amparo'
                                         else convencaoamp.ed250_c_abrev
                                        end::varchar
                                       else
                                       case when matricula.ed60_c_parecer = 'S'
                                        then
                                           case when (diarioavaliacao.ed72_i_escola != diario.ed95_i_escola) or diarioavaliacao.ed72_c_tipo = 'F'
                                            then '*PD'
                                            else 'PD'
                                         end ::varchar
                                        else
                                          case when formaavaliacao.ed37_c_tipo = 'NOTA'
                                           then
                                            case when (select edu_parametros.ed233_c_decimais from edu_parametros where edu_parametros.ed233_i_escola = diario.ed95_i_escola) = 'S'
                                             then
                                              case when (diarioavaliacao.ed72_i_escola != diario.ed95_i_escola) or diarioavaliacao.ed72_c_tipo = 'F'
                                               then '*'||trim(trunc(diarioavaliacao.ed72_i_valornota::numeric, {$iCasasDecimais})::varchar)
                                               else trunc(diarioavaliacao.ed72_i_valornota::numeric, {$iCasasDecimais})::varchar
                                              end
                                             else
                                              case when (diarioavaliacao.ed72_i_escola != diario.ed95_i_escola) or diarioavaliacao.ed72_c_tipo = 'F'
                                               then '*'||trim(trunc(diarioavaliacao.ed72_i_valornota::numeric,{$iCasasDecimais})::varchar)
                                               else trunc(diarioavaliacao.ed72_i_valornota::numeric,{$iCasasDecimais})::varchar
                                              end
                                             end::varchar
                                           when formaavaliacao.ed37_c_tipo = 'NIVEL'
                                            then
                                             case when (diarioavaliacao.ed72_i_escola != diario.ed95_i_escola) or diarioavaliacao.ed72_c_tipo = 'F'
                                              then '*'||trim(diarioavaliacao.ed72_c_valorconceito)
                                              else diarioavaliacao.ed72_c_valorconceito
                                             end::varchar
                                           when formaavaliacao.ed37_c_tipo = 'PARECER'
                                            then
                                               case when (diarioavaliacao.ed72_i_escola != diario.ed95_i_escola) or diarioavaliacao.ed72_c_tipo = 'F'
                                                then '*PD'
                                                else 'PD'
                                               end
                                            else null
                                           end
                                       end
                                     end::varchar,'')||'|'||
                                     coalesce(
                                      case when diarioavaliacao.ed72_c_amparo = 'N'
                                       then diarioavaliacao.ed72_i_numfaltas
                                       else null
                                      end::varchar,'')||'|'||'AVA'||'|'||
                                     procavaliacao.ed41_i_sequencia||'|'||
                                     diarioavaliacao.ed72_c_aprovmin||'|'||
                                     periodoavaliacao.ed09_c_descr||'|'||
                                     diarioavaliacao.ed72_c_amparo||'|'||
                                     periodoavaliacao.ed09_c_controlfreq ||'|'||
                                     cast(ed37_c_minimoaprov as varchar) as elemento
                              FROM diarioavaliacao
                               inner join procavaliacao on procavaliacao.ed41_i_codigo = diarioavaliacao.ed72_i_procavaliacao
                               inner join formaavaliacao on formaavaliacao.ed37_i_codigo = procavaliacao.ed41_i_formaavaliacao
                               inner join periodoavaliacao on periodoavaliacao.ed09_i_codigo = procavaliacao.ed41_i_periodoavaliacao
                              WHERE diarioavaliacao.ed72_i_diario = diario.ed95_i_codigo
                              $restricao1

                              UNION

                              SELECT coalesce(lpad(procresultado_res.ed43_i_sequencia,3,0)::varchar,'')||'|'||
                                     coalesce(resultado.ed42_c_abrev::varchar,'')||'|'||
                                     coalesce(formaavaliacao_res.ed37_c_tipo::varchar,'')||'|'||
                                     coalesce(
                                      case when diarioresultado_res.ed73_c_amparo = 'S'
                                       then
                                        case when amparo.ed81_i_justificativa is not null
                                         then 'Amparo'
                                         else convencaoamp.ed250_c_abrev
                                        end::varchar
                                       else
                                       case when matricula.ed60_c_parecer = 'S'
                                        then 'PD'
                                        else
                                          case when formaavaliacao_res.ed37_c_tipo = 'NOTA'
                                           then
                                            case when (select edu_parametros.ed233_c_decimais from edu_parametros where edu_parametros.ed233_i_escola = diario.ed95_i_escola) = 'S'
                                             then diarioresultado_res.ed73_valorreal::varchar
                                             else diarioresultado_res.ed73_valorreal::varchar
                                             end::varchar
                                           when formaavaliacao_res.ed37_c_tipo = 'NIVEL'
                                            then diarioresultado_res.ed73_c_valorconceito
                                           when formaavaliacao_res.ed37_c_tipo = 'PARECER'
                                            then 'PD'
                                            else null
                                         end
                                       end
                                     end::varchar,'')||'|'||''||'|'||'RES'||'|'||
                                     procresultado_res.ed43_i_sequencia||'|'||
                                     diarioresultado_res.ed73_c_aprovmin||'|'||
                                     resultado.ed42_c_descr||'|'||
                                     diarioresultado_res.ed73_c_amparo||'|N'||'|'||
                                     cast(ed37_c_minimoaprov as varchar) as elemento
                              FROM diarioresultado as diarioresultado_res
                               inner join procresultado as procresultado_res on procresultado_res.ed43_i_codigo = diarioresultado_res.ed73_i_procresultado
                               inner join formaavaliacao as formaavaliacao_res on formaavaliacao_res.ed37_i_codigo = procresultado_res.ed43_i_formaavaliacao
                               inner join resultado on resultado.ed42_i_codigo = procresultado_res.ed43_i_resultado
                              WHERE diarioresultado_res.ed73_i_diario = diario.ed95_i_codigo
                              $restricao2
                              $restricao3
                              ORDER BY elemento
                            )
              ) as periodos,
              case when formaavaliacaores.ed37_c_tipo = 'NOTA'
                        and matricula.ed60_c_situacao = 'MATRICULADO'
                        and matricula.ed60_c_concluida = 'N'
                        and (procresultado.ed43_c_obtencao = 'ME' OR procresultado.ed43_c_obtencao = 'MP' OR procresultado.ed43_c_obtencao = 'SO')
                        and (select edu_parametros.ed233_c_notabranca from edu_parametros where edu_parametros.ed233_i_escola = diario.ed95_i_escola) = 'S'
               then
                case

                 when procresultado.ed43_c_obtencao = 'ME' then
                      (select sum(coalesce(diariorescomp.ed73_valorreal,0)+coalesce(da3.ed72_i_valornota,0))
                          /case when count(dr3.ed73_valorreal) = 0
                            then count(da3.ed72_i_valornota)
                            else count(dr3.ed73_valorreal)
                           end
                   from diarioresultado as dr3
                    inner join procresultado as proc3 on proc3.ed43_i_codigo = dr3.ed73_i_procresultado
                    inner join diario as d3 on d3.ed95_i_codigo = dr3.ed73_i_diario
                    left join rescompoeres on rescompoeres.ed68_i_procresultado = proc3.ed43_i_codigo
                    left join avalcompoeres on avalcompoeres.ed44_i_procresultado = proc3.ed43_i_codigo
                    left join diarioresultado as diariorescomp on diariorescomp.ed73_i_procresultado = rescompoeres.ed68_i_procresultcomp
                                                              and diariorescomp.ed73_i_diario = dr3.ed73_i_diario
                    left join diarioavaliacao as da3 on da3.ed72_i_procavaliacao = avalcompoeres.ed44_i_procavaliacao
                                                    and da3.ed72_i_diario = dr3.ed73_i_diario
                   where dr3.ed73_i_codigo = diarioresultado.ed73_i_codigo
                   and (da3.ed72_c_amparo = 'N' OR diariorescomp.ed73_c_amparo = 'N')
                   and (da3.ed72_i_valornota is not null OR diariorescomp.ed73_valorreal is not null))
                 when procresultado.ed43_c_obtencao = 'MP' then
                  (select sum(coalesce(diariorescomp.ed73_valorreal*rescompoeres.ed68_i_peso,0)+coalesce(da3.ed72_i_valornota*avalcompoeres.ed44_i_peso,0))
                         /coalesce(sum(coalesce(avalcompoeres.ed44_i_peso,0)+coalesce(rescompoeres.ed68_i_peso,0)),1)
                   from diarioresultado as dr3
                    inner join procresultado as proc3 on proc3.ed43_i_codigo = dr3.ed73_i_procresultado
                    inner join diario as d3 on d3.ed95_i_codigo = dr3.ed73_i_diario
                    left join rescompoeres on rescompoeres.ed68_i_procresultado = proc3.ed43_i_codigo
                    left join avalcompoeres on avalcompoeres.ed44_i_procresultado = proc3.ed43_i_codigo
                    left join diarioresultado as diariorescomp on diariorescomp.ed73_i_procresultado = rescompoeres.ed68_i_procresultcomp
                                                              and diariorescomp.ed73_i_diario = dr3.ed73_i_diario
                    left join diarioavaliacao as da3 on da3.ed72_i_procavaliacao = avalcompoeres.ed44_i_procavaliacao
                                                    and da3.ed72_i_diario = dr3.ed73_i_diario
                   where dr3.ed73_i_codigo = diarioresultado.ed73_i_codigo
                   and (da3.ed72_c_amparo = 'N' OR diariorescomp.ed73_c_amparo = 'N')
                   and (da3.ed72_i_valornota is not null OR diariorescomp.ed73_valorreal is not null))
                 when procresultado.ed43_c_obtencao = 'SO' then
                  (select sum(coalesce(diariorescomp.ed73_valorreal,0)
                             +coalesce(da3.ed72_i_valornota,0))
                   from diarioresultado as dr3
                    inner join procresultado as proc3 on proc3.ed43_i_codigo = dr3.ed73_i_procresultado
                    inner join diario as d3 on d3.ed95_i_codigo = dr3.ed73_i_diario
                    left join rescompoeres on rescompoeres.ed68_i_procresultado = proc3.ed43_i_codigo
                    left join avalcompoeres on avalcompoeres.ed44_i_procresultado = proc3.ed43_i_codigo
                    left join diarioresultado as diariorescomp on diariorescomp.ed73_i_procresultado = rescompoeres.ed68_i_procresultcomp
                                                              and diariorescomp.ed73_i_diario = dr3.ed73_i_diario
                    left join diarioavaliacao as da3 on da3.ed72_i_procavaliacao = avalcompoeres.ed44_i_procavaliacao
                                                    and da3.ed72_i_diario = dr3.ed73_i_diario
                   where dr3.ed73_i_codigo = diarioresultado.ed73_i_codigo
                   and (da3.ed72_c_amparo = 'N' OR diariorescomp.ed73_c_amparo = 'N')
                   and (da3.ed72_i_valornota is not null OR diariorescomp.ed73_valorreal is not null))
                 when procresultado.ed43_c_obtencao = 'MN' then
                  (select case
                           when max(coalesce(diariorescomp.ed73_valorreal,0)) > max(coalesce(da3.ed72_i_valornota,0))
                            then max(coalesce(diariorescomp.ed73_valorreal,0))
                           when max(coalesce(da3.ed72_i_valornota,0)) > max(coalesce(diariorescomp.ed73_valorreal,0))
                            then max(coalesce(da3.ed72_i_valornota,0))
                           else max(coalesce(da3.ed72_i_valornota,0))
                          end
                   from diarioresultado as dr3
                    inner join procresultado as proc3 on proc3.ed43_i_codigo = dr3.ed73_i_procresultado
                    inner join diario as d3 on d3.ed95_i_codigo = dr3.ed73_i_diario
                    left join rescompoeres on rescompoeres.ed68_i_procresultado = proc3.ed43_i_codigo
                    left join avalcompoeres on avalcompoeres.ed44_i_procresultado = proc3.ed43_i_codigo
                    left join diarioresultado as diariorescomp on diariorescomp.ed73_i_procresultado = rescompoeres.ed68_i_procresultcomp
                                                              and diariorescomp.ed73_i_diario = dr3.ed73_i_diario
                    left join diarioavaliacao as da3 on da3.ed72_i_procavaliacao = avalcompoeres.ed44_i_procavaliacao
                                                    and da3.ed72_i_diario = dr3.ed73_i_diario
                   where dr3.ed73_i_codigo = diarioresultado.ed73_i_codigo
                   and (da3.ed72_c_amparo = 'N' OR diariorescomp.ed73_c_amparo = 'N')
                   and (da3.ed72_i_valornota is not null OR diariorescomp.ed73_valorreal is not null))
                 when procresultado.ed43_c_obtencao = 'UN' then
                  (select daval5.ed72_i_valornota
                   from diario as d5
                    inner join diarioavaliacao as daval5 on daval5.ed72_i_diario = d5.ed95_i_codigo
                    inner join procavaliacao as proc5 on proc5.ed41_i_codigo = daval5.ed72_i_procavaliacao
                   where d5.ed95_i_aluno = diario.ed95_i_aluno
                   and d5.ed95_i_regencia = diario.ed95_i_regencia
                   order by proc5.ed41_i_sequencia DESC LIMIT 1)
                 else null
                end
               else null
              end as notaparcial,
              (select sum(regenciaperiodo.ed78_i_aulasdadas)
               from regenciaperiodo
                inner join procavaliacao as proc6 on proc6.ed41_i_codigo = regenciaperiodo.ed78_i_procavaliacao
                inner join periodoavaliacao as per6 on per6.ed09_i_codigo = proc6.ed41_i_periodoavaliacao
               where regenciaperiodo.ed78_i_regencia = diario.ed95_i_regencia
               and per6.ed09_c_somach = 'S') as aulas,
              case when matricula.ed60_c_situacao = 'MATRICULADO' and ed60_c_concluida = 'N'
               then
                case when amparo.ed81_c_todoperiodo = 'S'
                 then ''
                 else
                  case when (select edu_parametros.ed233_c_decimais from edu_parametros where edu_parametros.ed233_i_escola = diario.ed95_i_escola) = 'S'
                   then
                    case when procedimento.ed40_i_calcfreq = 2
                     then
                       (SELECT round( ( ( coalesce(sum(rp1.ed78_i_aulasdadas),0)-coalesce(sum(da1.ed72_i_numfaltas),0)+coalesce(sum(ab1.ed80_i_numfaltas),0) )
                                          / coalesce(sum(rp1.ed78_i_aulasdadas),1)::float
                                      )*100,2)
                        FROM diarioavaliacao as da1
                         inner join procavaliacao as proc1 on proc1.ed41_i_codigo = da1.ed72_i_procavaliacao
                         inner join periodoavaliacao as per1 on per1.ed09_i_codigo = proc1.ed41_i_periodoavaliacao
                         inner join avalfreqres as ava1 on ava1.ed67_i_procavaliacao = proc1.ed41_i_codigo
                         inner join diario as d1 on d1.ed95_i_codigo = da1.ed72_i_diario
                         inner join regencia as reg1 on reg1.ed59_i_codigo = d1.ed95_i_regencia
                         inner join regenciaperiodo rp1 on rp1.ed78_i_procavaliacao = proc1.ed41_i_codigo
                                                    and rp1.ed78_i_regencia = d1.ed95_i_regencia
                         left join abonofalta as ab1 on ab1.ed80_i_diarioavaliacao = da1.ed72_i_codigo
                        WHERE ava1.ed67_i_procresultado = procresultado.ed43_i_codigo
                        AND d1.ed95_i_regencia in (select reg2.ed59_i_codigo from regencia as reg2 where reg2.ed59_i_turma = turma.ed57_i_codigo and reg2.ed59_c_condicao = 'OB')
                        AND d1.ed95_i_aluno = matricula.ed60_i_aluno
                        AND da1.ed72_c_amparo = 'N'
                        AND per1.ed09_c_somach = 'S')::varchar
                     else
                       (SELECT round( ( ( coalesce(sum(rp1.ed78_i_aulasdadas),0)-coalesce(sum(da1.ed72_i_numfaltas),0)+coalesce(sum(ab1.ed80_i_numfaltas),0) )
                                          / coalesce(sum(rp1.ed78_i_aulasdadas),1)::float
                                      )*100, 2)
                        FROM diarioavaliacao as da1
                         inner join procavaliacao as proc1 on proc1.ed41_i_codigo = da1.ed72_i_procavaliacao
                         inner join periodoavaliacao as per1 on per1.ed09_i_codigo = proc1.ed41_i_periodoavaliacao
                         inner join avalfreqres as ava1 on ava1.ed67_i_procavaliacao = proc1.ed41_i_codigo
                         inner join diario as d1 on d1.ed95_i_codigo = da1.ed72_i_diario
                         inner join regencia as reg1 on reg1.ed59_i_codigo = d1.ed95_i_regencia
                         inner join regenciaperiodo rp1 on rp1.ed78_i_procavaliacao = proc1.ed41_i_codigo
                                                    and rp1.ed78_i_regencia = d1.ed95_i_regencia
                         left join abonofalta as ab1 on ab1.ed80_i_diarioavaliacao = da1.ed72_i_codigo
                        WHERE ava1.ed67_i_procresultado = procresultado.ed43_i_codigo
                        AND rp1.ed78_i_regencia = diario.ed95_i_regencia
                        AND da1.ed72_i_diario = diario.ed95_i_codigo
                        AND da1.ed72_c_amparo = 'N')::varchar
                    end
                   else
                    case when procedimento.ed40_i_calcfreq = 2
                     then
                       (SELECT round( ( ( coalesce(sum(rp1.ed78_i_aulasdadas),0)-coalesce(sum(da1.ed72_i_numfaltas),0)+coalesce(sum(ab1.ed80_i_numfaltas),0) )
                                          / coalesce(sum(rp1.ed78_i_aulasdadas),1)::float
                                      )*100,0)
                        FROM diarioavaliacao as da1
                         inner join procavaliacao as proc1 on proc1.ed41_i_codigo = da1.ed72_i_procavaliacao
                         inner join periodoavaliacao as per1 on per1.ed09_i_codigo = proc1.ed41_i_periodoavaliacao
                         inner join avalfreqres as ava1 on ava1.ed67_i_procavaliacao = proc1.ed41_i_codigo
                         inner join diario as d1 on d1.ed95_i_codigo = da1.ed72_i_diario
                         inner join regencia as reg1 on reg1.ed59_i_codigo = d1.ed95_i_regencia
                         inner join regenciaperiodo rp1 on rp1.ed78_i_procavaliacao = proc1.ed41_i_codigo
                                                    and rp1.ed78_i_regencia = d1.ed95_i_regencia
                         left join abonofalta as ab1 on ab1.ed80_i_diarioavaliacao = da1.ed72_i_codigo
                        WHERE ava1.ed67_i_procresultado = procresultado.ed43_i_codigo
                        AND d1.ed95_i_regencia in (select reg2.ed59_i_codigo from regencia as reg2 where reg2.ed59_i_turma = turma.ed57_i_codigo and reg2.ed59_c_condicao = 'OB')
                        AND d1.ed95_i_aluno = matricula.ed60_i_aluno
                        AND da1.ed72_c_amparo = 'N'
                        AND per1.ed09_c_somach = 'S')::varchar
                     else
                       (SELECT round( ( ( coalesce(sum(rp1.ed78_i_aulasdadas),0)-coalesce(sum(da1.ed72_i_numfaltas),0)+coalesce(sum(ab1.ed80_i_numfaltas),0) )
                                          / coalesce(sum(rp1.ed78_i_aulasdadas),1)::float
                                      )*100,0)
                        FROM diarioavaliacao as da1
                         inner join procavaliacao as proc1 on proc1.ed41_i_codigo = da1.ed72_i_procavaliacao
                         inner join periodoavaliacao as per1 on per1.ed09_i_codigo = proc1.ed41_i_periodoavaliacao
                         inner join avalfreqres as ava1 on ava1.ed67_i_procavaliacao = proc1.ed41_i_codigo
                         inner join diario as d1 on d1.ed95_i_codigo = da1.ed72_i_diario
                         inner join regencia as reg1 on reg1.ed59_i_codigo = d1.ed95_i_regencia
                         inner join regenciaperiodo rp1 on rp1.ed78_i_procavaliacao = proc1.ed41_i_codigo
                                                    and rp1.ed78_i_regencia = d1.ed95_i_regencia
                         left join abonofalta as ab1 on ab1.ed80_i_diarioavaliacao = da1.ed72_i_codigo
                        WHERE ava1.ed67_i_procresultado = procresultado.ed43_i_codigo
                        AND rp1.ed78_i_regencia = diario.ed95_i_regencia
                        AND da1.ed72_i_diario = diario.ed95_i_codigo
                        AND da1.ed72_c_amparo = 'N')::varchar
                    end
                  end
                end
               else
                case
                 when matricula.ed60_c_situacao = 'AVANÇADO' or matricula.ed60_c_situacao = 'CLASSIFICADO'
                  then 'Apr'
                 when matricula.ed60_c_situacao != 'MATRICULADO'
                  then substr(matricula.ed60_c_situacao,1,5)
                 when matricula.ed60_c_situacao = 'MATRICULADO' aND ed60_c_concluida = 'S'
                  then
                   case when (select edu_parametros.ed233_c_decimais from edu_parametros where edu_parametros.ed233_i_escola = diario.ed95_i_escola) = 'S'
                    then round(ed74_i_percfreq,2)::varchar
                    else round(ed74_i_percfreq,0)::varchar
                   end
                end
              end as frequencia,
              case when diario.ed95_c_encerrado = 'S'
               then
                case when matricula.ed60_c_situacao = 'MATRICULADO'
                 then
                  case when amparo.ed81_c_todoperiodo = 'S'
                   then
                    case when amparo.ed81_i_justificativa is not null
                     then 'Amparo'
                     else convencaoamp.ed250_c_abrev
                    end
                   else
                    case
                     when formaavaliacaores.ed37_c_tipo = 'NOTA' then
                      case when diariofinal.ed74_c_valoraprov != '' AND diariofinal.ed74_c_valoraprov != 'Parecer'
                       then
                        case when (select edu_parametros.ed233_c_decimais from edu_parametros where edu_parametros.ed233_i_escola = diario.ed95_i_escola) = 'S'
                         then diariofinal.ed74_c_valoraprov::numeric
                         else diariofinal.ed74_c_valoraprov::numeric
                        end
                       else null
                      end::varchar
                     when formaavaliacaores.ed37_c_tipo = 'NIVEL' then
                      diariofinal.ed74_c_valoraprov::varchar
                     when formaavaliacaores.ed37_c_tipo = 'PARECER' then
                      diariofinal.ed74_c_valoraprov::varchar
                    end
                   end
                 else substr(matricula.ed60_c_situacao,1,5)
                end
               else
                case
                 when matricula.ed60_c_situacao = 'AVANÇADO' or matricula.ed60_c_situacao = 'CLASSIFICADO'
                  then 'Apr'
                 when matricula.ed60_c_situacao != 'MATRICULADO'
                  then substr(matricula.ed60_c_situacao,1,5)
                 else null
                end
              end as aprovfinal,
              case when diario.ed95_c_encerrado = 'S'
               then
                case when matricula.ed60_c_situacao = 'MATRICULADO'
                 then
                  case when diariofinal.ed74_c_resultadofinal = 'A'
                   then 'Apr'
                  when diariofinal.ed74_c_resultadofinal = 'R'
                   then 'Rep'
                  when diariofinal.ed74_c_resultadofinal = ''
                   then ''
                  end
                 else
                  case
                   when matricula.ed60_c_situacao = 'AVANÇADO' or matricula.ed60_c_situacao = 'CLASSIFICADO'
                    then 'Apr'
                    else substr(matricula.ed60_c_situacao,1,5)
                  end
                end
               else
                case
                 when matricula.ed60_c_situacao = 'AVANÇADO' or matricula.ed60_c_situacao = 'CLASSIFICADO'
                  then 'Apr'
                 when matricula.ed60_c_situacao != 'MATRICULADO'
                  then substr(matricula.ed60_c_situacao,1,5)
                 else null
                end
              end as resfinal,
              (select sum(diarioavaliacao.ed72_i_numfaltas)
               from diarioavaliacao
               where diarioavaliacao.ed72_i_diario = diario.ed95_i_codigo
               and diarioavaliacao.ed72_c_amparo = 'N') as tot_faltas,
              (select sum(abonofalta.ed80_i_numfaltas)
               from diarioavaliacao
                left join abonofalta on abonofalta.ed80_i_diarioavaliacao = diarioavaliacao.ed72_i_codigo
               where diarioavaliacao.ed72_i_diario = diario.ed95_i_codigo
               and diarioavaliacao.ed72_c_amparo = 'N') as tot_abonos,
              case
               when formaavaliacaores.ed37_c_tipo = 'NOTA'
                then
                 case when (select edu_parametros.ed233_c_decimais from edu_parametros where edu_parametros.ed233_i_escola = diario.ed95_i_escola) = 'S'
                  then trunc(formaavaliacaores.ed37_c_minimoaprov::numeric::numeric,{$iCasasDecimais})::varchar
                  else trunc(formaavaliacaores.ed37_c_minimoaprov::numeric::numeric,{$iCasasDecimais})::varchar
                 end
                else formaavaliacaores.ed37_c_minimoaprov
              end as minimoaprov,
              (select array(select distinct conceito.ed39_i_sequencia||'-'||conceito.ed39_c_conceito||':'||conceito.ed39_c_conceitodescr as descricao
                            from conceito
                             inner join formaavaliacao as forma1 on forma1.ed37_i_codigo = conceito.ed39_i_formaavaliacao
                             inner join procavaliacao as proc1 on proc1.ed41_i_formaavaliacao = forma1.ed37_i_codigo
                             inner join procedimento as proced1 on proced1.ed40_i_codigo = proc1.ed41_i_procedimento
                             inner join turmaserieregimemat as tsrmat1 on tsrmat1.ed220_i_procedimento = proced1.ed40_i_codigo
                             inner join serieregimemat as srmat1 on srmat1.ed223_i_codigo = tsrmat1.ed220_i_serieregimemat
                             inner join turma as turma1 on turma1.ed57_i_codigo = tsrmat1.ed220_i_turma
                            where turma1.ed57_i_codigo = turma.ed57_i_codigo
                            and srmat1.ed223_i_serie = serie.ed11_i_codigo
                            order by descricao)) as descr_niveis,
              matricula.ed60_c_concluida as concluida,
              matricula.ed60_c_situacao as situacao,
              matricula.ed60_i_aluno as aluno,
              matricula.ed60_i_turma as turma,
              serie.ed11_c_descr as serie,
              (select edu_parametros.ed233_c_notabranca from edu_parametros where edu_parametros.ed233_i_escola = diario.ed95_i_escola) as permitenotaembranco,
              (select edu_parametros.ed233_c_decimais from edu_parametros where edu_parametros.ed233_i_escola = diario.ed95_i_escola) as decimais,
              turma.ed57_c_medfreq as medfrequencia,
              formaavaliacaores.ed37_c_tipo as formafinal,
              (select array(select distinct convencaoamp.ed250_c_abrev||' - '||convencaoamp.ed250_c_descr
                            from convencaoamp as camp
                             inner join amparo as amp on amp.ed81_i_convencaoamp = camp.ed250_i_codigo
                            where amp.ed81_i_diario = diario.ed95_i_codigo)) as convencao,
              (select prs.ed43_c_obtencao
                 from procresultado prs
                where prs.ed43_i_procedimento = procedimento.ed40_i_codigo
                  and prs.ed43_c_geraresultado = 'S'
                  and prs.ed43_c_obtencao in('ME', 'MP', 'SO')
                  limit 1) as formaobtencao
       FROM matricula
        inner join turma on turma.ed57_i_codigo = matricula.ed60_i_turma
        inner join matriculaserie on matriculaserie.ed221_i_matricula = matricula.ed60_i_codigo
        inner join serie on serie.ed11_i_codigo = matriculaserie.ed221_i_serie
        inner join regencia on regencia.ed59_i_turma = turma.ed57_i_codigo
                            and regencia.ed59_i_turma = matricula.ed60_i_turma
                            and regencia.ed59_i_serie = serie.ed11_i_codigo
        left join diario on diario.ed95_i_aluno = matricula.ed60_i_aluno
                         and diario.ed95_i_regencia = regencia.ed59_i_codigo
        inner join disciplina on disciplina.ed12_i_codigo = regencia.ed59_i_disciplina
        inner join caddisciplina on caddisciplina.ed232_i_codigo = disciplina.ed12_i_caddisciplina
        inner join serieregimemat  on  serieregimemat.ed223_i_serie = serie.ed11_i_codigo
        inner join turmaserieregimemat  on  turmaserieregimemat.ed220_i_serieregimemat = serieregimemat.ed223_i_codigo
                                        and turmaserieregimemat.ed220_i_turma = regencia.ed59_i_turma
                                        and turmaserieregimemat.ed220_i_turma = matricula.ed60_i_turma
        inner join procedimento  on  procedimento.ed40_i_codigo = turmaserieregimemat.ed220_i_procedimento
        left join diariofinal on diariofinal.ed74_i_diario = diario.ed95_i_codigo
        left join procresultado on procresultado.ed43_i_codigo = diariofinal.ed74_i_procresultadoaprov
        left join formaavaliacao as formaavaliacaores on formaavaliacaores.ed37_i_codigo = procresultado.ed43_i_formaavaliacao
        left join amparo on amparo.ed81_i_diario = diario.ed95_i_codigo
        left join convencaoamp on convencaoamp.ed250_i_codigo = amparo.ed81_i_convencaoamp
        left join diarioresultado on diarioresultado.ed73_i_diario = diario.ed95_i_codigo
                                 and diarioresultado.ed73_i_procresultado = procresultado.ed43_i_codigo
       WHERE matricula.ed60_i_codigo = $matricula
       AND matriculaserie.ed221_c_origem = 'S'
       ORDER BY regencia.ed59_i_ordenacao
       ";
  return $sql;
}
function GradeAproveitamentoHTML($matricula, $mostraresfinal="S", $iAno) {
  ?>
  <style>
    .titulo{
      font-size: 11px;
      color: #DEB887;
      background-color:#444444;
      font-weight: bold;
      border: 1px solid #f3f3f3;
    }
    .cabec1{
      font-size: 11px;
      color: #000000;
      background-color:#999999;
      font-weight: bold;
    }
    .aluno{
      color: #000000;
      font-family : Tahoma;
      font-size: 10px;
      font-weight: bold;
    }
    .aluno1{
      color: #000000;
      font-family : Tahoma;
      font-weight: bold;
      text-align: center;
      font-size: 10px;
    }
    .aluno2{
      color: #000000;
      font-family : Verdana;
      font-size: 10px;
      font-weight: bold;
    }
  </style>
  <?
  $oDaoMatricula = db_utils::getDao("matricula");
  db_inicio_transacao();

  try {

    $oMatricula = new Matricula($matricula);
    $oDiario    = $oMatricula->getDiarioDeClasse();
  } catch (Exception $eErro) {
    db_msgbox($eErro);
  }
  db_fim_transacao(false);
  $sSqlMatricula = $oDaoMatricula->sql_query($matricula, "ed10_i_codigo");
  $iCodigoEnsino = '';
  $rsEnsino      = $oDaoMatricula->sql_record($sSqlMatricula);
  if ($oDaoMatricula->numrows > 0) {
    $iCodigoEnsino = db_utils::fieldsMemory($rsEnsino, 0)->ed10_i_codigo;
  }
  $sql = GradeAproveitamentoSQL($matricula, "N", $iAno);
  $result = db_query($sql);
  //db_criatabela($result);exit;
  if (pg_numrows($result)>0) {
    echo '<table width="100%" border="1" cellspacing="0" cellpadding="2">';
    $cor1 = "#f3f3f3";
    $cor2 = "#DBDBDB";
    $cor = "";
    $qtd_periodo = 0;
    for($y=0;$y<pg_num_rows($result);$y++) {

      db_fieldsmemory($result,$y);
      $disciplina          = pg_result($result,$y,'disciplina');
      $iCodigoDisciplina   = pg_result($result,$y,'codigo_disciplina');
      $periodos            = pg_result($result,$y,'periodos');
      $notaparcial         = pg_result($result,$y,'notaparcial');
      $aulas               = pg_result($result,$y,'aulas');
      $frequencia          = ArredondamentoFrequencia::arredondar(pg_result($result,$y,'frequencia'), $iAno);
      $aprovfinal          = pg_result($result,$y,'aprovfinal');
      $resfinal            = pg_result($result,$y,'resfinal');
      $concluida           = pg_result($result,$y,'concluida');
      $situacao            = pg_result($result,$y,'situacao');
      $decimais            = pg_result($result,$y,'decimais');
      $aluno               = pg_result($result,$y,'aluno');
      $turma               = pg_result($result,$y,'turma');
      $serie               = pg_result($result,$y,'serie');
      $permitenotaembranco = pg_result($result,$y,'permitenotaembranco');
      $medfrequencia       = pg_result($result,$y,'medfrequencia');
      $formaobtencao       = trim(pg_result($result,$y,'formaobtencao'));

      $oDisciplinasDiario  = $oDiario->getDisciplinasPorRegencia(RegenciaRepository::getRegenciaByCodigo($iCodigoDisciplina));
      if ($oDisciplinasDiario) {

        $frequencia = $oDisciplinasDiario->calcularPercentualFrequencia();

        if ( verificaReclassificadoBaixaFrequencia( $oDisciplinasDiario ) ) {
          $frequencia = '--';
        }

        if ( !empty($aprovfinal) ) {

          $mNotaConselho = verificaNotaFinalAprovadoConselho($oDisciplinasDiario);

          if ($mNotaConselho != null)  {
            $aprovfinal = $mNotaConselho;
          }
        }
      }

      if ($cor==$cor1) {
        $cor = $cor2;
      }else{
        $cor = $cor1;
      }
      if ($y==0) {

        echo '<tr align="center"><td class="cabec1">&nbsp;</td>';
        if (trim($periodos)=="") {
          echo '<td class="cabec1">&nbsp;</td></tr>';
        }else{
          $pri_periodos = str_replace("{","",$periodos);
          $pri_periodos = str_replace("}","",$pri_periodos);
          $pri_periodos = str_replace(chr(34),"",$pri_periodos);
          $pri_periodos = explode(",",$pri_periodos);
          for($x=0;$x<count($pri_periodos);$x++) {

            $arr_periodo = explode("|",$pri_periodos[$x]);
            echo '<td class="cabec1" '.(trim($arr_periodo[5])=="AVA"?"colspan=2":"").' >'.trim($arr_periodo[1]).'</td>';
          }
          if (trim($permitenotaembranco)=="S" && trim($situacao)=="MATRICULADO" && trim($concluida)=="N" && ($formaobtencao=="ME" || $formaobtencao=="MP" || $formaobtencao=="SO")) {

            $qtd_periodo++;
            echo '<td class="cabec1">Nota Parcial</td>';
          }
          echo '<td class="cabec1" colspan="2">Frequência</td>';
          echo '<td class="cabec1" colspan="2">Resultado Final</td>';
          echo '</tr>';
        }
        echo '<tr align="center">';
        echo '<td class="cabec1">Disciplina</td>';
        if (trim($periodos)=="") {
          echo '<td class="cabec1">&nbsp;</td></tr>';
        }else{

          for($x=0;$x<count($pri_periodos);$x++) {

            $arr_periodo = explode("|",$pri_periodos[$x]);
            echo '<td class="cabec1">AVAL.</td>';
            if (trim($arr_periodo[5])=="AVA") {
              echo '<td class="cabec1">Ft.</td>';
            }
          }
          if (trim($permitenotaembranco)=="S" && trim($situacao)=="MATRICULADO" && trim($concluida)=="N" && ($formaobtencao=="ME" || $formaobtencao=="MP" || $formaobtencao=="SO")) {
            echo '<td class="cabec1">&nbsp;</td>';
          }
          echo '<td class="cabec1">'.(trim($medfrequencia)=="PERÌODOS"?"Aulas":"Dias").'</td>';
          echo '<td class="cabec1" >% Freq</td>';
          echo '<td class="cabec1" >Aprov.</td>';
          echo '<td class="cabec1" >RF</td>';
          echo '</tr>';
        }
      }
      echo '<tr bgcolor="'.$cor.'"><td class="aluno">&nbsp;'.trim($disciplina).'</td>';
      if (trim($periodos)=="") {
        echo '<td align="center" class="aluno1">Nenhum registro para esta disciplina.</td></tr>';
      }else{

        $array_peraval = str_replace("{","",$periodos);
        $array_peraval = str_replace("}","",$array_peraval);
        $array_peraval = str_replace(chr(34),"",$array_peraval);
        $array_peraval = explode(",",$array_peraval);
        for($x=0;$x<count($array_peraval);$x++) {
          $arr_explode = explode("|",$array_peraval[$x]);

          if ($arr_explode[5] == "RES") {
            $arr_explode[3] = ArredondamentoNota::formatar($arr_explode[3], $iAno);
          }

          echo '<td class="aluno1">'.(trim($arr_explode[3])==""?"&nbsp;":trim($arr_explode[3])).'</td>';
          if (trim($arr_explode[5])=="AVA") {

            $iFaltas = trim($arr_explode[4]);
            if ( empty($iFaltas) ) {
              $iFaltas = "&nbsp";
            }

            echo '<td class="aluno1">'. $iFaltas .'</td>';
          }
        }
        if (trim($permitenotaembranco)=="S" && trim($situacao)=="MATRICULADO" && trim($concluida)=="N" && ($formaobtencao=="ME" || $formaobtencao=="MP" || $formaobtencao=="SO")) {

          $notaparcial = '';
          $oDisciplinasDiario->getElementoResultadoFinal();
          if ($oDisciplinasDiario != '') {

            $oElementoNota = $oDisciplinasDiario->getElementoResultadoFinal();
            if ($oElementoNota != '') {
              $notaparcial   = $oDisciplinasDiario->getNotaParcial($oElementoNota->getElementoAvaliacao());
            }
          }

          $sNotaParcial = ArredondamentoNota::formatar($notaparcial, $iAno);
          echo '<td class="aluno1">'.$sNotaParcial.'</td>';
        }

        if ($arr_periodo[2] == 'NOTA'  && $aprovfinal != "") {
          $aprovfinal = ArredondamentoNota::formatar($aprovfinal, $iAno);
        }

        switch (trim($situacao)) {

          case 'MATRICULA INDEVIDA':

            $frequencia = 'MATRICULA INDEVIDA';
            $aprovfinal = 'MATRICULA INDEVIDA';
            $resfinal   = 'MATRICULA INDEVIDA';
            break;

          case 'MATRICULA TRANCADA':

            $frequencia = 'MATRICULA TRANCADA';
            $aprovfinal = 'MATRICULA TRANCADA';
            $resfinal   = 'MATRICULA TRANCADA';
            break;

          case 'MATRICULA INDEFERIDA':

            $frequencia = 'MATRICULA INDEFERIDA';
            $aprovfinal = 'MATRICULA INDEFERIDA';
            $resfinal   = 'MATRICULA INDEFERIDA';
            break;
        }
        echo '<td class="aluno1">'.(trim($aulas)==""?"&nbsp;":trim($aulas)).'</td>';
        echo '<td class="aluno1">'.trim($frequencia).'</td>';
        echo '<td class="aluno1">'.(trim($aprovfinal)==""?"&nbsp;":($mostraresfinal=="S"?trim($aprovfinal):"")).'</td>';
        $corfonte = trim($resfinal) == "Apr" ? "green" : (trim($resfinal) == "Rep" ? "red" :"black");
        if (trim($situacao) == 'AVANÇADO') {
          $resfinal = 'AVANÇ.';
        } elseif (trim($situacao) == 'CLASSIFICADO') {
          $resfinal = 'CLASS.';
        }
        if (!empty($iCodigoEnsino) && ($resfinal == 'Apr' || $resfinal == 'Rep')) {

          $aDadosTermo = DBEducacaoTermo::getTermoEncerramento($iCodigoEnsino, substr($resfinal, 0, 1), $iAno );
          if (isset($aDadosTermo[0])) {
            $resfinal    = $aDadosTermo[0]->sAbreviatura;
          }
        }
        $oDisciplina = $oDiario->getDisciplinasPorRegencia(RegenciaRepository::getRegenciaByCodigo($iCodigoDisciplina));
        if ($oDisciplina->getResultadoFinal()->aprovadoPorProgressaoParcial()) {
          $resfinal = 'AP / DP';
        }
        echo '<td class="aluno1"><font color="'.$corfonte.'">'.(trim($resfinal)==""?"&nbsp;":($mostraresfinal=="S"?trim($resfinal):"")).'</font></td>';
        echo '</tr>';
      }
    }
    if (trim($periodos) != '' || trim($situacao) == 'AVANÇADO' || trim($situacao) == 'CLASSIFICADO') {
      $rfatual = ResultadoFinal($matricula,$aluno,$turma,trim($situacao),trim($concluida), $iCodigoEnsino);
      echo '<tr>
          <td colspan="'.((count(@$pri_periodos)*2)+5+@$qtd_periodo).'">
           <table width="100%">
            <tr>
             <td>
              <b>Resultado final em '.trim($serie).': '.trim($rfatual).'</b>&nbsp;&nbsp;
             </td>
             <td align="right">
               * <font size="2">Nota Externa</font>
             </td>
            </tr>
           </table>
          </td>
         </tr>';
    }
    echo '</table>';
  }
}

function GradeAproveitamentoPDF( $matricula, $largura, $pdf, $mostraresfinal = "S", $iAno ) {

  $pdf->setfont( 'arial', 'b', 7);

  $largura_disc = $largura * 20 / 100;
  $largura_meio = $largura * 60 / 100;
  $largura_rf   = $largura * 20 / 100;

  $sql    = GradeAproveitamentoSQL( $matricula, "S", $iAno );
  $result = db_query( $sql );

  $oMatricula = new Matricula($matricula);
  $oEtapa     = $oMatricula->getEtapaDeOrigem();

  db_inicio_transacao();
  $oDiario = $oMatricula->getDiarioDeClasse();
  db_inicio_transacao(false);

  $oProcedimentoAvaliacaoEtapa = $oDiario->getTurma()->getProcedimentoDeAvaliacaoDaEtapa( $oEtapa );
  $lEncontrouPeriodoValido     = false;
  $aFormasObtencao             = array( "ME", "MP", "SO" );

  if (pg_numrows($result) > 0) {

    $iQuantidadePeriodos = 0;

    /**
     * Percorre os dados retornados primeiramente para montar o cabeçalho correto, de acordo com o procedimento de
     * avaliação da turma
     */
    for ($y = 0; $y < pg_num_rows($result); $y++) {

      $oDadosGrade        = db_utils::fieldsMemory( $result, $y );
      $oRegencia          = RegenciaRepository::getRegenciaByCodigo( $oDadosGrade->codigo_disciplina );
      $oDisciplinasDiario = $oDiario->getDisciplinasPorRegencia( $oRegencia );

      /**
       * Caso o procedimento de avaliação da disciplina seja o mesmo da turma, imprime o cabeçalho com as colunas
       * corretas
       */
      if(    $oDisciplinasDiario->getRegencia()->getProcedimentoAvaliacao()->getFormaAvaliacao()->getTipo() ==
        $oProcedimentoAvaliacaoEtapa->getFormaAvaliacao()->getTipo()
        && !$lEncontrouPeriodoValido
      ) {

        $lEncontrouPeriodoValido = true;

        if( trim( $oDadosGrade->periodos ) != "" && trim( $oDadosGrade->periodos ) != "{}" ) {

          $pdf->cell( $largura_disc, 4, "", 0, 0, "C", 0 );

          $pri_periodos = str_replace( "{", "", $oDadosGrade->periodos );
          $pri_periodos = str_replace( "}", "", $pri_periodos );
          $pri_periodos = str_replace( chr(34), "", $pri_periodos );
          $pri_periodos = explode( ",", $pri_periodos );

          if(    trim($oDadosGrade->permitenotaembranco) == "S"
            && trim($oDadosGrade->situacao) == "MATRICULADO"
            && trim($oDadosGrade->concluida) == "N"
            && in_array( $oDadosGrade->formaobtencao, $aFormasObtencao )
          ) {
            $acrescecoluna = 1;
          } else {
            $acrescecoluna = 0;
          }

          $largura_periodo = $largura_meio / ( count( $pri_periodos ) + $acrescecoluna );

          $iQuantidadePeriodos = count($pri_periodos);
          for( $x = 0; $x < count($pri_periodos); $x++ ) {

            $arr_periodo = explode( "|", $pri_periodos[$x] );
            $pdf->cell( $largura_periodo, 4, trim( $arr_periodo[1] ), 1, 0, "C", 0 );
          }

          if(    trim($oDadosGrade->permitenotaembranco) == "S"
            && trim($oDadosGrade->situacao) == "MATRICULADO"
            && trim($oDadosGrade->concluida) == "N"
            && in_array( $oDadosGrade->formaobtencao, $aFormasObtencao )
          ) {
            $pdf->cell( $largura_periodo, 4, "Nota Parcial", 1, 0, "C", 0 );
          }

          $pdf->cell( $largura_rf / 2, 4, "Frequência",      1, 0, "C", 0 );
          $pdf->cell( $largura_rf / 2, 4, "Resultado Final", 1, 1, "C", 0 );
          $pdf->cell(   $largura_disc, 4, "Disciplina",      1, 0, "C", 0 );
        }

        if( trim( $oDadosGrade->periodos ) == "" || trim( $oDadosGrade->periodos ) == "{}" ) {

          $pdf->cell( $largura_disc,               4, "Disciplina",  1, 0, "C", 1 );
          $pdf->cell( $largura_meio + $largura_rf, 4, "Observações", 1, 1, "C", 1 );
        } else {

          $largura_aprov = $largura_periodo * 70 / 100;
          $largura_ft    = $largura_periodo * 30 / 100;

          for( $x = 0; $x < count( $pri_periodos ); $x++ ) {

            $arr_periodo = explode( "|", $pri_periodos[$x] );

            if( trim( $arr_periodo[5] ) == "AVA" ) {

              $pdf->cell( $largura_aprov, 4, "AVAL.", 1, 0, "C", 0 );
              $pdf->cell( $largura_ft,    4, "Ft",                  1, 0, "C", 0 );
            } else {
              $pdf->cell( $largura_periodo, 4, trim( $arr_periodo[2] ), 1, 0, "C", 0 );
            }
          }

          if(    trim($oDadosGrade->permitenotaembranco) == "S"
            && trim($oDadosGrade->situacao) == "MATRICULADO"
            && trim($oDadosGrade->concluida) == "N"
            && in_array( $oDadosGrade->formaobtencao, $aFormasObtencao )
          ) {
            $pdf->cell( $largura_periodo, 4, "", 1, 0, "C", 0 );
          }

          $largura_dias    = $largura_rf * 20 / 100;
          $largura_freq    = $largura_rf * 30 / 100;
          $largura_aprovrf = $largura_rf * 30 / 100;
          $largura_result  = $largura_rf * 20 / 100;

          $sFrequencia = trim( $oDadosGrade->medfrequencia ) == "PERÌODOS" ? "Aulas" : "Dias";
          $pdf->cell( $largura_dias,    4, $sFrequencia, 1, 0, "C", 0 );
          $pdf->cell( $largura_freq,    4, "% Freq",     1, 0, "C", 0 );
          $pdf->cell( $largura_aprovrf, 4, "Aprov.",     1, 0, "C", 0 );
          $pdf->cell( $largura_result,  4, "RF",         1, 1, "C", 0 );
        }
      }
    }

    /**
     * Após montado o cabeçalho, percorre novamente os dados, desta vez para montagem dos dados de cada disciplina
     */
    for ($y = 0; $y < pg_num_rows($result); $y++) {

      db_fieldsmemory( $result, $y );

      $disciplina          = pg_result( $result, $y, 'disciplina' );
      $periodos            = pg_result( $result, $y, 'periodos' );
      $notaparcial         = pg_result( $result, $y, 'notaparcial' );
      $aulas               = pg_result( $result, $y, 'aulas' );
      $frequencia          = ArredondamentoFrequencia::arredondar( pg_result( $result, $y, 'frequencia' ), $iAno );
      $aprovfinal          = pg_result( $result, $y, 'aprovfinal' );
      $resfinal            = pg_result( $result, $y, 'resfinal' );
      $concluida           = pg_result( $result, $y, 'concluida' );
      $situacao            = pg_result( $result, $y, 'situacao' );
      $decimais            = pg_result( $result, $y, 'decimais' );
      $permitenotaembranco = pg_result( $result, $y, 'permitenotaembranco' );
      $formaobtencao       = trim( pg_result( $result, $y, 'formaobtencao' ) );
      $iCodigoDisciplina   = pg_result( $result, $y, 'codigo_disciplina' );

      $oDisciplinasDiario  = $oDiario->getDisciplinasPorRegencia(RegenciaRepository::getRegenciaByCodigo($iCodigoDisciplina));
      if ($oDisciplinasDiario) {

        $frequencia = $oDisciplinasDiario->calcularPercentualFrequencia();

        if ( verificaReclassificadoBaixaFrequencia( $oDisciplinasDiario ) ) {
          $frequencia = '--';
        }
      }

      $aDados    = array();
      $aPosicao  = array();
      $aLarguras = array();

      $aDados[]    = trim($disciplina);
      $aPosicao[]  = "L";
      $aLarguras[] = $largura_disc;

      $iTamNome = strlen($disciplina);
      $iLinhas  = ceil($iTamNome / $largura_disc);

      if( trim( $periodos ) == "" || trim( $periodos ) == "{}" ) {

        $aDados[]    = "Nenhum registro para esta disciplina.";
        $aLarguras[] = $largura_meio + $largura_rf;
        $aPosicao[]  = "C";
      } else {

        $array_peraval = str_replace( "{", "", $periodos );
        $array_peraval = str_replace( "}", "", $array_peraval );
        $array_peraval = str_replace( chr(34), "", $array_peraval );
        $array_peraval = explode( ",", $array_peraval );

        $iDiminuiPeriodos = 0;
        if( count($array_peraval) > $iQuantidadePeriodos ) {
          $iDiminuiPeriodos = count($array_peraval) > $iQuantidadePeriodos;
        }

        for ($x = 0; $x < count($array_peraval) - $iDiminuiPeriodos; $x++) {

          $arr_explode = explode( "|", $array_peraval[$x] );

          if( trim( $arr_explode[5] ) == "AVA" ) {

            $aDados[]    = trim($arr_explode[3]);
            $aLarguras[] = $largura_aprov;
            $aPosicao[]  = "C";

            $iFaltas     = trim($arr_explode[4]);
            $aDados[]    = empty( $iFaltas ) ? '' : $iFaltas;
            $aLarguras[] = $largura_ft;
            $aPosicao[]  = "C";
          } else {

            $sNota  = trim(@$arr_explode[3]);

            if (trim(@$arr_explode[2]) == "NOTA") {
              $sNota = ArredondamentoNota::formatar($sNota, $iAno);
            }

            $aDados[]    = trim($sNota);
            $aLarguras[] = $largura_periodo;
            $aPosicao[]  = "C";
          }
        }

        if( count($array_peraval) < $iQuantidadePeriodos ) {

          $iColunasEmBranco = $iQuantidadePeriodos - count($array_peraval);

          for( $iContador = 0; $iContador < $iColunasEmBranco; $iContador++ ) {

            $aDados[]    = '';
            $aLarguras[] = $largura_aprov + $largura_ft;
            $aPosicao[]  = "C";
          }
        }

        if(    trim($permitenotaembranco) == "S"
          && trim($situacao) == "MATRICULADO"
          && trim($concluida) == "N"
          && in_array( $formaobtencao, $aFormasObtencao )
        ) {

          $aDados[]    = (trim($notaparcial) == "" ? "" : ($decimais == "S" ? number_format(trim($notaparcial),2,".",".") : number_format(trim($notaparcial))));
          $aLarguras[] = $largura_periodo;
          $aPosicao[]  = "C";
        }

        if( trim( $situacao ) == 'AVANÇADO' ) {
          $resfinal = 'AVANÇ.';
        } else if( trim( $situacao ) == 'CLASSIFICADO' ) {
          $resfinal = 'CLASS.';
        }

        $aDados[]    = trim($aulas);
        $aLarguras[] = $largura_dias;
        $aPosicao[]  = "C";

        $aDados[]    = $mostraresfinal == "S" ? trim($frequencia) : " ";
        $aLarguras[] = $largura_freq;
        $aPosicao[]  = "C";

        $aDados[]    = substr($mostraresfinal == "S" ? trim($aprovfinal) : " " ,0 ,3);
        $aLarguras[] = $largura_aprovrf;
        $aPosicao[]  = "C";

        $aDados[]    = substr($mostraresfinal == "S" ? trim($resfinal) : " ", 0, 3);
        $aLarguras[] = $largura_result;
        $aPosicao[]  = "C";
      }

      $pdf->SetWidths($aLarguras);
      $pdf->SetAligns($aPosicao);

      $iAltura        = 3.8;
      $lBorda         = true;
      $iEspaco        = 4;
      $iPreenche      = 0;
      $set_altura_row = $pdf->h - 32;

      $pdf->Row_multicell($aDados, $iAltura, $lBorda, $iEspaco, $iPreenche, false, true, 2, $set_altura_row, 0);
    } //for que percorre os resultados
  } //if numrows > 0
}

/**
 * 01/03/2013 - Adicionado o parâmetro $sGeraResultado, que é levado para a função GradeAproveitamentoSQL
 *
 * @param unknown_type $matricula
 * @param unknown_type $largura
 * @param unknown_type $pdf
 * @param unknown_type $niveis
 * @param unknown_type $orientacao
 * @param unknown_type $seqatual
 * @param unknown_type $ficha
 * @param String $sGeraResultado
 */
function GradeAproveitamentoBOLETIM($matricula, $largura, $pdf, $niveis, $orientacao, $seqatual, $ficha = "N", $sGeraResultado = "S") {

  $oDaoMatricula = db_utils::getDao("matricula");
  $sSqlMatricula = $oDaoMatricula->sql_query($matricula, "ed10_i_codigo, calendario.ed52_i_ano");

  $iCodigoEnsino = '';
  $iAno          = db_getsession("DB_anousu");
  $rsEnsino      = $oDaoMatricula->sql_record($sSqlMatricula);
  if ($oDaoMatricula->numrows > 0) {

    $iCodigoEnsino = db_utils::fieldsMemory($rsEnsino, 0)->ed10_i_codigo;
    $iAno          = db_utils::fieldsMemory($rsEnsino, 0)->ed52_i_ano;
  }
  $oMatricula = new Matricula($matricula);
  db_inicio_transacao();
  $oDiario    = $oMatricula->getDiarioDeClasse();
  db_inicio_transacao();
  if ($orientacao == "P") {

    $largura_disc = $largura*20/100;//coluna da descriçao da disciplina 23%
    $largura_meio = $largura*53/100;//coluna dos elementos da avaliaçao 50%

  } else {

    $largura_disc = $largura*18/100;//coluna da descriçao da disciplina 18%
    $largura_meio = $largura*55/100;//coluna dos elementos da avaliaçao 55%

  }
  $iTamanhoMaximoColuna = $largura_disc;
  $largura_rf = $largura*27/100;//coluna do resultado final (Aulas|Faltas|Abonos|Freq|Aprov|RF) 27%
  $sql        = GradeAproveitamentoSQL($matricula,"S", $iAno, $sGeraResultado);
  $result     = db_query($sql);

  $iTamanhoMaximoDisciplina = 0;
  if (pg_numrows($result) > 0) {

    for ($iDisc = 0; $iDisc < pg_num_rows($result); $iDisc++) {

      $qtd_periodo = 0;
      db_fieldsmemory($result, $iDisc);
      $disciplina = pg_result($result, $iDisc, 'disciplina');
      $iTamanhoDisciplina = $pdf->GetStringWidth(($disciplina)) + 3;
      if ($iTamanhoDisciplina > $iTamanhoMaximoDisciplina) {
        $iTamanhoMaximoDisciplina = $iTamanhoDisciplina;
      }
    }
    if ($iTamanhoMaximoDisciplina < $largura_disc) {

      $largura_meio += ($largura_disc - $iTamanhoMaximoDisciplina);
      $largura_disc  = $iTamanhoMaximoDisciplina;
    }

    global $boletim_periodos;
    global $boletim_convencao;
    $boletim_periodos  = pg_result($result, 0, 'periodos');
    $boletim_convencao = pg_result($result, 0, 'convencao');

    $aPeriodos = array();

    for ($iContador = 0; $iContador < pg_num_rows($result); $iContador++) {
      $aPeriodos[] = db_utils::fieldsMemory( $result, $iContador)->periodos;
    }

    $oEtapa                          = $oMatricula->getEtapaDeOrigem();
    $sTipoAvaliacao                  = $oMatricula->getTurma()->getProcedimentoDeAvaliacaoDaEtapa($oEtapa)->getFormaAvaliacao()->getTipo();
    $lMontaDadosCabecalho            = false;
    $lImprimeCabecalho               = true;
    $lTemMediaFinal                  = false;
    $lProcedimentoDiferenteAvaliacao = false;
    $aPeriodosCabecalho              = array();

    $largura_aprov    = 0;
    $largura_ft       = 0;
    $largura_dias     = 0;
    $largura_ttfaltas = 0;
    $largura_ttabonos = 0;
    $largura_freq     = 0;
    $largura_aprovrf  = 0;
    $largura_result   = 0;


    /**
     * Monta o cabeçalho da grid e define o tamanho das colunas a serem impressas de acordo com os perídos de avaliação.
     */
    for ($y = 0; $y < pg_num_rows($result); $y++) {

      $qtd_periodo = 0;
      db_fieldsmemory($result, $y);

      $disciplina          = pg_result($result, $y, 'disciplina');
      $periodos            = pg_result($result, $y, 'periodos');
      $medfrequencia       = pg_result($result, $y, 'medfrequencia');
      $permitenotaembranco = pg_result($result, $y, 'permitenotaembranco');
      $situacao            = pg_result($result, $y, 'situacao');
      $concluida           = pg_result($result, $y, 'concluida');
      $formaobtencao       = trim(pg_result($result, $y, 'formaobtencao'));

      $pdf->setfont('arial','b',7);

      $pri_periodos = str_replace("{", "", $periodos);
      $pri_periodos = str_replace("}", "", $pri_periodos);
      $pri_periodos = str_replace(chr(34), "", $pri_periodos);
      $pri_periodos = explode(",", $pri_periodos);

      $aPeriodos = array();
      for ($iPer = 0; $iPer < count($pri_periodos); $iPer++) {
        $aPeriodos  = explode("|", $pri_periodos[$iPer]);
      }

      if ($sTipoAvaliacao == $aPeriodos[2] && !$lMontaDadosCabecalho ) {

        $aPeriodosCabecalho   = $pri_periodos;
        $lMontaDadosCabecalho = true;
      }

      if ( count($aPeriodosCabecalho) > 0 && $lImprimeCabecalho) {

        $lImprimeCabecalho = false;
        $pdf->cell($largura_disc, 4, "", 1, 0, "C", 0);
        if (trim($periodos) == "") {
          $pdf->cell($largura_meio+$largura_rf, 4, "", 1, 1, "C", 0);
        } else {

          $pri_periodos = str_replace("{", "", $periodos);
          $pri_periodos = str_replace("}", "", $pri_periodos);
          $pri_periodos = str_replace(chr(34), "", $pri_periodos);
          $pri_periodos = explode(",", $pri_periodos);

          if (trim($permitenotaembranco) == "S"
            && trim($situacao) == "MATRICULADO"
            && trim($concluida) == "N"
            && ($formaobtencao == "ME" || $formaobtencao == "MP" || $formaobtencao == "SO")) {

            $lTemMediaFinal = true;
            $acrescecoluna  = 1;
          } else {
            $acrescecoluna = 0;
          }

          $largura_periodo        = $largura_meio/(count($pri_periodos)+$acrescecoluna);
          $largura_aprov          = ($largura_periodo*65)/100;
          $largura_ft             = ($largura_periodo*44)/100;
          $nTamanhoOriginal       = $largura_ft;
          $iTotalPeriodosComFalta = 0;
          $iTotalPeriodosSemFalta = 0;
          $nTamanhoAcrescentar    = 0;

          for ($iPer = 0; $iPer < count($pri_periodos); $iPer++) {

            $aPeriodos  = explode("|", $pri_periodos[$iPer]);

            if (isset($aPeriodos[5]) && trim($aPeriodos[5]) == "AVA" && $aPeriodos['10'] == "S") {

              $iTotalPeriodosComFalta++;
              $nTamanhoAcrescentar  += $largura_ft ;

            } else if (isset($aPeriodos[5]) && trim($aPeriodos[5]) == "AVA" && $aPeriodos['10'] == "N") {
              $iTotalPeriodosSemFalta++;
            }
          }

          if ($iTotalPeriodosSemFalta > 0) {

            $nTamanhoSobrando = (($nTamanhoAcrescentar)/$iTotalPeriodosComFalta);
            $largura_ft      += ($nTamanhoSobrando/2)/$iTotalPeriodosComFalta;
            $largura_aprov   += ($nTamanhoSobrando/2)/$iTotalPeriodosComFalta;
          }

          for ($x = 0; $x < count($aPeriodosCabecalho); $x++) {

            $arr_periodo     = explode("|", $aPeriodosCabecalho[$x]);
            $nLarguraPeriodo = $largura_periodo;

            if (isset($arr_periodo[5]) && trim(@$arr_periodo[5]) != "AVA" && @$arr_periodo[10] != "S") {
              $lProcedimentoDiferenteAvaliacao = true;
            }

            if (trim(@$arr_periodo[5]) == "AVA" && @$arr_periodo['10'] == "N") {
              $nLarguraPeriodo -= $nTamanhoOriginal;
            } else if (trim(@$arr_periodo[5]) == "AVA" && @$arr_periodo['10'] == "S") {
              $nLarguraPeriodo = $largura_ft + $largura_aprov;
            }

            $pdf->cell($nLarguraPeriodo, 4, trim(@$arr_periodo[1]), 1, 0, "C", 0);

          }

          if (trim($permitenotaembranco) == "S"
            && trim($situacao) == "MATRICULADO"
            && trim($concluida) == "N"
            && ($formaobtencao == "ME" || $formaobtencao == "MP" || $formaobtencao == "SO")) {

            $qtd_periodo++;
            $pdf->cell($largura_periodo - 1.1, 4, "NP", 1, 0, "C", 0);

          }

          $pdf->cell(($largura_rf*52/100), 4, "Frequência", 1, 0, "C", 0);
          $pdf->cell($largura_rf*37/100, 4, "Resultado Final", 1, 1, "C", 0);

        }
        $pdf->cell($largura_disc, 4, "Disciplina", 1, 0, "C", 0);
        if (trim($periodos) == "") {
          $pdf->cell($largura_meio+$largura_rf, 4, "", 1, 1, "C", 0);
        } else {

          for ($x = 0; $x < count($aPeriodosCabecalho); $x++) {

            $arr_periodo = explode("|", $aPeriodosCabecalho[$x]);
            if (trim(@$arr_periodo[5]) == "AVA" && @$arr_periodo['10'] == "S") {

              $pdf->cell($largura_aprov, 4, "AVAL.", 1, 0, "C", 0);
              $pdf->cell($largura_ft, 4, 'FT', 1, 0, "C", 0);

            } else {

              $nTamanhoColuna = $largura_periodo;
              if (trim(@$arr_periodo[5]) == "AVA") {
                $nTamanhoColuna -= $nTamanhoOriginal;
              }
              $pdf->cell($nTamanhoColuna, 4, "AVAL.", 1, 0, "C", 0);
            }

          }

          if (trim($permitenotaembranco) == "S"
            && trim($situacao) == "MATRICULADO"
            && trim($concluida) == "N"
            && ($formaobtencao == "ME" || $formaobtencao == "MP" || $formaobtencao == "SO")) {

            $pdf->cell($largura_periodo-1.1, 4, "", 1, 0, "C", 0);
          }

          $largura_dias     = $largura_rf*12/100;
          $largura_ttfaltas = $largura_rf*12/100;
          $largura_ttabonos = $largura_rf*12/100;
          $largura_freq     = $largura_rf*16/100;
          $largura_aprovrf  = $largura_rf*18/100;
          $largura_result   = $largura_rf*19/100;
          $pdf->cell($largura_dias, 4, (trim($medfrequencia) == "PERÌODOS" ? "AD" : "DL"), 1, 0, "C", 0);
          $pdf->cell($largura_ttfaltas, 4, "TF", 1, 0, "C", 0);
          $pdf->cell($largura_ttabonos, 4, "FA", 1, 0, "C", 0);
          $pdf->cell($largura_freq ,4, "Freq.", 1, 0, "C", 0);
          $pdf->cell($largura_aprovrf, 4, "Aprov.", 1, 0, "C", 0);
          $pdf->cell($largura_result, 4, "RF", 1, 1, "C", 0);
        }

      }
    }

    /**
     * Percorre as disciplinas e as imprime de acordo com o tamanho das colunas calculadas ao montar o cabeçalho
     */
    for ($y = 0; $y < pg_num_rows($result); $y++) {

      $qtd_periodo = 0;
      db_fieldsmemory($result, $y);

      $disciplina          = pg_result($result, $y, 'disciplina');
      $periodos            = pg_result($result, $y, 'periodos');
      $notaparcial         = pg_result($result, $y, 'notaparcial');
      $aulas               = pg_result($result, $y, 'aulas');
      $frequencia          = ArredondamentoFrequencia::arredondar(pg_result($result, $y, 'frequencia'), $iAno);
      $aprovfinal          = pg_result($result, $y, 'aprovfinal');
      $resfinal            = pg_result($result, $y, 'resfinal');
      $concluida           = pg_result($result, $y, 'concluida');
      $situacao            = pg_result($result, $y, 'situacao');
      $decimais            = pg_result($result, $y, 'decimais');
      $aluno               = pg_result($result, $y, 'aluno');
      $turma               = pg_result($result, $y, 'turma');
      $serie               = pg_result($result, $y, 'serie');
      $permitenotaembranco = pg_result($result, $y, 'permitenotaembranco');
      $tot_faltas          = pg_result($result, $y, 'tot_faltas');
      $tot_abonos          = pg_result($result, $y, 'tot_abonos');
      $minimoaprov         = pg_result($result, $y, 'minimoaprov');
      $formafinal          = pg_result($result, $y, 'formafinal');
      $descr_niveis        = pg_result($result, $y, 'descr_niveis');
      $codigo_disciplina   = pg_result($result, $y, 'codigo_disciplina');
      $formaobtencao       = trim(pg_result($result, $y, 'formaobtencao'));

      $oDisciplinasDiario  = $oDiario->getDisciplinasPorRegencia(RegenciaRepository::getRegenciaByCodigo($codigo_disciplina));
      $minimoaprov         = $oDiario->getMinimoAprovacao();

      if ($oDisciplinasDiario) {

        $frequencia = "{$oDisciplinasDiario->calcularPercentualFrequencia()}%";

        if ( verificaReclassificadoBaixaFrequencia( $oDisciplinasDiario ) ) {
          $frequencia = '--';
        }

        if ( !empty($aprovfinal) ) {

          $mNotaConselho = verificaNotaFinalAprovadoConselho($oDisciplinasDiario);

          if ($mNotaConselho != null)  {
            $aprovfinal = $mNotaConselho;
          }
        }
      }

      /* Defino os arrays para o Row_multicell */
      $aDados     = array();
      $aPosicao   = array();
      $aLarguras  = array();
      $aNegrito   = array();
      $lBold      = false;

      $pdf->setfont('arial', '', 7);

      $aDados[0]    = trim($disciplina);
      $aPosicao[0]  = "L";
      $aLarguras[0] = $largura_disc;
      $aNegrito[0]  = false;

      if (trim($periodos) == "") {

        $iInd             = count($aDados);
        $aDados[$iInd]    = "Nenhum registro para esta disciplina.";
        $aPosicao[$iInd]  = "C";
        $aLarguras[$iInd] = $largura_meio+$largura_rf;

      } else {

        $array_peraval = str_replace("{", "", $periodos);
        $array_peraval = str_replace("}", "", $array_peraval);
        $array_peraval = str_replace(chr(34), "", $array_peraval);
        $array_peraval = explode(",", $array_peraval);

        $lImprimiuLinha = false;

        for ($x = 0; $x < count($array_peraval); $x++) {

          $arr_explode = explode("|", $array_peraval[$x]);

          if ((trim(@$arr_explode[2]) == "NOTA" || trim(@$arr_explode[2]) == "NIVEL") && @$arr_explode[9] == "N") {

            if (trim($arr_explode[7]) == "N") {
              $pdf->setfont('arial', 'b', 10);
            } else {
              $pdf->setfont('arial', '', 9);
            }

          } else {
            $pdf->setfont('arial', '', 7);
          }

          if (isset($arr_explode[7]) && trim($arr_explode[7]) == "N" && trim($arr_explode[2]) != 'PARECER') {
            $lBold = true;
          } else {
            $lBold = false;
          }

          if (isset($arr_explode[5]) && trim(@$arr_explode[5]) == "AVA" && @$arr_explode[10] == "S") {

            $iInd             = count($aDados);
            $aDados[$iInd]    = ArredondamentoNota::formatar( $arr_explode[3], $iAno );
            $aPosicao[$iInd]  = "C";
            $aLarguras[$iInd] = $largura_aprov;
            $aNegrito[$iInd]  = $lBold;
            $pdf->setfont('arial', '', 8);

            /**
             * Alterado valor da avaliação quando = "Amparo" para informar somente "Amp"
             */
            if ($arr_explode[3] == "Amparo") {


              $aDados[$iInd] = "AMP";
              $oAmparo       = $oDisciplinasDiario->getAmparo();
              if ( !is_null($oAmparo) ) {

                if ( $oAmparo->getTipoAmparo() == AmparoDisciplina::AMPARO_JUSTIFICATIVA ) {
                  $aDados[$iInd] = $oAmparo->getJustificativa()->getAbreviatura();
                }
              }
            }

            $iInd             = count($aDados);
            $iFaltas          = trim($arr_explode[4]);
            $aDados[$iInd]    = empty( $iFaltas ) ? '' : $iFaltas;
            $aNegrito[$iInd]  = false;
            $aPosicao[$iInd]  = "C";
            $aLarguras[$iInd] = $largura_ft;

          } else {

            $iInd   = count($aDados);
            if ( $lProcedimentoDiferenteAvaliacao ) {

              $lImprimiuLinha = true;
              $iInd   = count($aDados);
              $sNota  = trim(@$arr_explode[3]);
              if (trim(@$arr_explode[2]) == "NOTA") {
                $sNota =  ArredondamentoNota::formatar($sNota, $iAno);
              }
              $nTamanhoColuna = $largura_periodo;
              if (trim(@$arr_explode[5]) == "AVA") {
                $nTamanhoColuna -=  $nTamanhoOriginal;
              }
              $aDados[$iInd]    = $sNota;
              $aPosicao[$iInd]  = "C";
              $aLarguras[$iInd] = $nTamanhoColuna;
              $aNegrito[$iInd]  = $lBold;
            }

            /**
             * Alterado valor da avaliação quando = "Amparo" para informar somente "Amp"
             */
            if ($arr_explode[3] == "Amparo") {


              $aDados[$iInd] = "AMP";
              $oAmparo       = $oDisciplinasDiario->getAmparo();
              if ( !is_null($oAmparo) ) {

                if ( $oAmparo->getTipoAmparo() == AmparoDisciplina::AMPARO_JUSTIFICATIVA ) {
                  $aDados[$iInd] = $oAmparo->getJustificativa()->getAbreviatura();
                }
              }
            }
          }
        }

        if ($lProcedimentoDiferenteAvaliacao && !$lImprimiuLinha ) {

          $iInd             = count($aDados);
          $nTamanhoColuna   = $largura_periodo;
          $aDados[$iInd]    = "";
          $aPosicao[$iInd]  = "C";
          $aLarguras[$iInd] = $nTamanhoColuna;
          $aNegrito[$iInd]  = $lBold;
        }


        if ( $lTemMediaFinal ) {

          if (trim($notaparcial) < $minimoaprov) {
            $lBold = true;
          } else {
            $lBold = false;
          }
          if ($arr_explode[2] == "NOTA" && $notaparcial != "" && $notaparcial != 'TRANS') {

            $notaparcial = '';
            $oDisciplinasDiario->getElementoResultadoFinal();
            if ($oDisciplinasDiario != '') {

              $oElementoNota = $oDisciplinasDiario->getElementoResultadoFinal();
              if ($oElementoNota != '') {
                $notaparcial   = $oDisciplinasDiario->getNotaParcial($oElementoNota->getElementoAvaliacao());
              }
            }

            $notaparcial = ArredondamentoNota::formatar($notaparcial, $iAno);
          }
          $iInd             = count($aDados);
          $aDados[$iInd]    = trim($notaparcial);
          $aPosicao[$iInd]  = "C";
          $aNegrito[$iInd]  = $lBold;
          $aLarguras[$iInd] = $largura_periodo-1.1;

        }
        $pdf->setfont('arial', '', 8);
        if (trim($situacao) != "MATRICULADO") {
          $pdf->setfont('arial', '', 7);
        }
        if (isset($arr_explode[2]) && $arr_explode[2] == "NOTA" && $aprovfinal != "" && $aprovfinal != 'TRANS') {
          $aprovfinal = ArredondamentoNota::formatar($aprovfinal, $iAno);
        }
        switch (trim($situacao)) {

          case 'MATRICULA TRANCADA' :

            $resfinal   = '';
            $frequencia = '';
            $aprovfinal = '';
            break;

          case 'MATRICULA INDEFERIDA' :

            $resfinal   = '';
            $frequencia = '';
            $aprovfinal = '';
            break;

          case 'MATRICULA INDEVIDA' :

            $resfinal   = '';
            $frequencia = '';
            $aprovfinal = '';
            break;

          case 'TRANSFERIDO REDE':

            $resfinal   = '';
            $frequencia = '';
            $aprovfinal = '';
            break;

          case 'TRANSFERIDO FORA':

            $resfinal   = '';
            $frequencia = '';
            $aprovfinal = '';
            break;

          case 'TROCA DE MODALIDADE':

            $resfinal   = '';
            $frequencia = '';
            $aprovfinal = '';
            break;

          case 'EVADIDO':

            $resfinal   = '';
            $frequencia = '';
            $aprovfinal = '';
            break;

          case 'CANCELADO':

            $resfinal   = '';
            $frequencia = '';
            $aprovfinal = '';
            break;

          case 'FALECIDO':

            $resfinal   = '';
            $frequencia = '';
            $aprovfinal = '';
            break;
        }

        $iInd             = count($aDados);
        $aDados[$iInd]    = trim($aulas);
        $aPosicao[$iInd]  = "C";
        $aLarguras[$iInd] = $largura_dias;
        $aNegrito[$iInd]  = false;

        $iInd             = count($aDados);
        $iTotalFaltas     = $oDisciplinasDiario->getTotalFaltas();
        $aDados[$iInd]    = empty($iTotalFaltas) ? '' : $iTotalFaltas;
        $aPosicao[$iInd]  = "C";
        $aLarguras[$iInd] = $largura_ttfaltas;
        $aNegrito[$iInd]  = false;

        $iInd             = count($aDados);
        $aDados[$iInd]    = $oDisciplinasDiario->getTotalFaltasAbonadas();
        $aPosicao[$iInd]  = "C";
        $aLarguras[$iInd] = $largura_ttabonos;
        $aNegrito[$iInd]  = false;

        $iInd             = count($aDados);
        $aDados[$iInd]    = trim($frequencia);
        $aPosicao[$iInd]  = "C";
        $aLarguras[$iInd] = $largura_freq;
        $aNegrito[$iInd]  = false;

        if (trim($formafinal) == "PARECER" || trim($aprovfinal) == "Amparo") {
          $pdf->setfont('arial', '', 7);
        } else {

          if (trim($formafinal)=="NIVEL") {

            if ($resfinal=="R") {
              $pdf->setfont('arial', 'b', 10);
            } else {
              $pdf->setfont('arial', '', 9);
            }

          } else {

            if (trim($aprovfinal) < $minimoaprov) {
              $lBold = true;
            } else {
              $lBold = false;
            }

          }

        }

        if (trim($situacao) != "MATRICULADO") {
          $pdf->setfont('arial', '', 7);
        }

        $iInd             = count($aDados);
        $aDados[$iInd]    = substr(trim($aprovfinal), 0, 3);
        $aPosicao[$iInd]  = "C";
        $aLarguras[$iInd] = $largura_aprovrf;
        $aNegrito[$iInd]  = $lBold;

        $pdf->setfont('arial', '', 7);
        if (trim($situacao) != "MATRICULADO") {
          $pdf->setfont('arial', '', 7);
        }
        if (trim($situacao) == 'AVANÇADO') {
          $resfinal = 'AVANÇ.';
        } elseif (trim($situacao) == 'CLASSIFICADO') {
          $resfinal = 'CLASS.';
        }

        $iInd             = count($aDados);
        $aDados[$iInd]    = trim($resfinal);
        $aPosicao[$iInd]  = "C";
        $aLarguras[$iInd] = $largura_result;
      }

      $pdf->SetWidths($aLarguras);
      $pdf->SetAligns($aPosicao);

      $iAltura        = 3.8;
      $lBorda         = true;
      $iEspaco        = 4;
      $iPreenche      = 0;
      $set_altura_row = $pdf->h - 32;

      $pdf->Row_multicell($aDados, $iAltura, $lBorda, $iEspaco, $iPreenche, false, true, 2, $set_altura_row, 0, $aNegrito);

    }
    if ($formafinal != "PARECER") {

      $pdf->setfont('arial', 'b', 8);
      $pdf->cell(43, 4, "Mínimo para Aprovação Anual:", "LT", 0, "L", 0);
      $pdf->cell(array_sum($aLarguras)-43, 4, trim($minimoaprov)=="0"?"":$minimoaprov, "RT", 1, "L", 0);

    }

    if ($concluida == "S" || $ficha == "S") {

      $pdf->setfont('arial', 'b', 8);
      $pdf->cell(35, 4, "Resultado Final: ", "BTL", 0, "L", 0);
      $pdf->cell(array_sum($aLarguras)-35,4,(ResultadoFinal($matricula, $aluno, $turma,
        trim($situacao), trim($concluida), $iCodigoEnsino)), "BTR", 1, "L", 0);

    }

    if ($niveis == "yes") {

      $pdf->setfont('arial', 'b', 8);
      $pdf->cell($largura, 4, "Níveis:", 1, 1, "L", 1);
      $pdf->setfont('arial', '', 7);
      $arr_niveis = str_replace("{", "", $descr_niveis);
      $arr_niveis = str_replace("}", "", $arr_niveis);
      $troca      = chr(34).",".chr(34);
      $arr_niveis = str_replace($troca, " | ", $arr_niveis);
      $arr_niveis = str_replace(chr(34), "", $arr_niveis);
      $pdf->multicell($largura, 4, $arr_niveis, "RL", "L", 0, 0);

    }
  }
  $oDadosRetorno = new stdClass();
  $oDadosRetorno->nLarguraGrade = array_sum($aLarguras);
  return $oDadosRetorno;
}

/**
 * Função para montar um INPUT do tipo COMBOBOX com os DIRETORES e SECRETÁRIOS.
 *
 * @param Integer $iEscola -> Código da escola.
 */
function Assinatura($iEscola) {

  $oDaoEscolaDiretor  = db_utils::getdao('escoladiretor');
  $sCamposDiretor     = " 'DIRETOR' as funcao, ";
  $sCamposDiretor    .= "         case when ed20_i_tiposervidor = 1 then ";
  $sCamposDiretor    .= "                 cgmrh.z01_nome ";
  $sCamposDiretor    .= "              else cgmcgm.z01_nome ";
  $sCamposDiretor    .= "         end as nome, ";
  $sCamposDiretor    .= " ed83_c_descr||' n°: '||ed05_c_numero::varchar as descricao,'D' as tipo ";
  $sWhereDiretor      = " ed254_i_escola = ".$iEscola." AND ed254_c_tipo = 'A' AND ed01_i_funcaoadmin = 2 ";
  $sSqlDiretor        = $oDaoEscolaDiretor->sql_query_resultadofinal("", $sCamposDiretor, "", $sWhereDiretor);

  $oDaoRechumanoAtiv  = db_utils::getdao('rechumanoativ');
  $sCamposSec         = " DISTINCT ed01_c_descr as funcao, ";
  $sCamposSec        .= "         case when ed20_i_tiposervidor = 1 then ";
  $sCamposSec        .= "                 cgmrh.z01_nome ";
  $sCamposSec        .= "              else cgmcgm.z01_nome ";
  $sCamposSec        .= "         end as nome,";
  $sCamposSec        .= " ed83_c_descr||' n°: '||ed05_c_numero::varchar as descricao,'O' as tipo ";
  $sWhereSec          = " ed75_i_escola = ".$iEscola." AND ed01_i_funcaoadmin = 3 ";
  $sSqlSec            = $oDaoRechumanoAtiv->sql_query_resultadofinal("", $sCamposSec, "", $sWhereSec);

  $sSqlUnion          = $sSqlDiretor;
  $sSqlUnion         .= " UNION ";
  $sSqlUnion         .= $sSqlSec;

  $rsAssinatura       = $oDaoEscolaDiretor->sql_record($sSqlUnion);
  $iLinhas            = $oDaoEscolaDiretor->numrows;

  if ($iLinhas > 0) {

    echo(' <select name="diretor" style="font-size:9px; width:330px;"> ');
    echo('      <option value="">Selecione na lista...</option> ');

    for ($iCont = 0; $iCont < $iLinhas; $iCont++) {

      $oDados  = db_utils::fieldsmemory($rsAssinatura, $iCont);

      $sValue   = $oDados->funcao."|".$oDados->nome."|".$oDados->descricao;
      $sString  = $oDados->funcao." - ".$oDados->nome;

      if (!empty($oDados->descricao)) {
        $sString .= " (".$oDados->descricao.") ";
      }

      echo('      <option value="'.$sValue.'">'.$sString.'</option> ');

    }

    echo(' </select> ');

  } else {

    echo(' <select name="diretor" style="font-size:9px; width:330px;"> ');
    echo('      <option value="">Nenhum registro encontrado.</option> ');
    echo(' </select> ');

  }

}

function NotaParcial() {
  $sql = "case

         when procresultado.ed43_c_obtencao = 'ME' then
          (select sum(coalesce(diariorescomp.ed73_i_valornota,0)+coalesce(da3.ed72_i_valornota,0))
                    / (count(diariorescomp.ed73_i_valornota) + count(da3.ed72_i_valornota))
          /*        /case when count(dr3.ed73_i_valornota) = 0
                    then count(da3.ed72_i_valornota)
                    else count(dr3.ed73_i_valornota)
                   end
          */
           from diarioresultado as dr3
            inner join procresultado as proc3 on proc3.ed43_i_codigo = dr3.ed73_i_procresultado
            inner join diario as d3 on d3.ed95_i_codigo = dr3.ed73_i_diario
            left join rescompoeres on rescompoeres.ed68_i_procresultado = proc3.ed43_i_codigo
            left join avalcompoeres on avalcompoeres.ed44_i_procresultado = proc3.ed43_i_codigo
            left join diarioresultado as diariorescomp on diariorescomp.ed73_i_procresultado = rescompoeres.ed68_i_procresultcomp
                                                      and diariorescomp.ed73_i_diario = dr3.ed73_i_diario
            left join diarioavaliacao as da3 on da3.ed72_i_procavaliacao = avalcompoeres.ed44_i_procavaliacao
                                            and da3.ed72_i_diario = dr3.ed73_i_diario
           where dr3.ed73_i_codigo = diarioresultado.ed73_i_codigo
           and (da3.ed72_c_amparo = 'N' OR diariorescomp.ed73_c_amparo = 'N')
           and (da3.ed72_i_valornota is not null OR diariorescomp.ed73_i_valornota is not null))
         when procresultado.ed43_c_obtencao = 'MP' then
          (select sum(coalesce(diariorescomp.ed73_i_valornota*rescompoeres.ed68_i_peso,0)+coalesce(da3.ed72_i_valornota*avalcompoeres.ed44_i_peso,0))
                 /coalesce(sum(coalesce(avalcompoeres.ed44_i_peso,0)+coalesce(rescompoeres.ed68_i_peso,0)),1)
           from diarioresultado as dr3
            inner join procresultado as proc3 on proc3.ed43_i_codigo = dr3.ed73_i_procresultado
            inner join diario as d3 on d3.ed95_i_codigo = dr3.ed73_i_diario
            left join rescompoeres on rescompoeres.ed68_i_procresultado = proc3.ed43_i_codigo
            left join avalcompoeres on avalcompoeres.ed44_i_procresultado = proc3.ed43_i_codigo
            left join diarioresultado as diariorescomp on diariorescomp.ed73_i_procresultado = rescompoeres.ed68_i_procresultcomp
                                                      and diariorescomp.ed73_i_diario = dr3.ed73_i_diario
            left join diarioavaliacao as da3 on da3.ed72_i_procavaliacao = avalcompoeres.ed44_i_procavaliacao
                                            and da3.ed72_i_diario = dr3.ed73_i_diario
           where dr3.ed73_i_codigo = diarioresultado.ed73_i_codigo
           and (da3.ed72_c_amparo = 'N' OR diariorescomp.ed73_c_amparo = 'N')
           and (da3.ed72_i_valornota is not null OR diariorescomp.ed73_i_valornota is not null))
         when procresultado.ed43_c_obtencao = 'SO' then
          (select sum(coalesce(diariorescomp.ed73_i_valornota,0)
                     +coalesce(da3.ed72_i_valornota,0))
           from diarioresultado as dr3
            inner join procresultado as proc3 on proc3.ed43_i_codigo = dr3.ed73_i_procresultado
            inner join diario as d3 on d3.ed95_i_codigo = dr3.ed73_i_diario
            left join rescompoeres on rescompoeres.ed68_i_procresultado = proc3.ed43_i_codigo
            left join avalcompoeres on avalcompoeres.ed44_i_procresultado = proc3.ed43_i_codigo
            left join diarioresultado as diariorescomp on diariorescomp.ed73_i_procresultado = rescompoeres.ed68_i_procresultcomp
                                                      and diariorescomp.ed73_i_diario = dr3.ed73_i_diario
            left join diarioavaliacao as da3 on da3.ed72_i_procavaliacao = avalcompoeres.ed44_i_procavaliacao
                                            and da3.ed72_i_diario = dr3.ed73_i_diario
           where dr3.ed73_i_codigo = diarioresultado.ed73_i_codigo
           and (da3.ed72_c_amparo = 'N' OR diariorescomp.ed73_c_amparo = 'N')
           and (da3.ed72_i_valornota is not null OR diariorescomp.ed73_i_valornota is not null))
         when procresultado.ed43_c_obtencao = 'MN' then
          (select case
                   when max(coalesce(diariorescomp.ed73_i_valornota,0)) > max(coalesce(da3.ed72_i_valornota,0))
                    then max(coalesce(diariorescomp.ed73_i_valornota,0))
                   when max(coalesce(da3.ed72_i_valornota,0)) > max(coalesce(diariorescomp.ed73_i_valornota,0))
                    then max(coalesce(da3.ed72_i_valornota,0))
                   else max(coalesce(da3.ed72_i_valornota,0))
                  end
           from diarioresultado as dr3
            inner join procresultado as proc3 on proc3.ed43_i_codigo = dr3.ed73_i_procresultado
            inner join diario as d3 on d3.ed95_i_codigo = dr3.ed73_i_diario
            left join rescompoeres on rescompoeres.ed68_i_procresultado = proc3.ed43_i_codigo
            left join avalcompoeres on avalcompoeres.ed44_i_procresultado = proc3.ed43_i_codigo
            left join diarioresultado as diariorescomp on diariorescomp.ed73_i_procresultado = rescompoeres.ed68_i_procresultcomp
                                                      and diariorescomp.ed73_i_diario = dr3.ed73_i_diario
            left join diarioavaliacao as da3 on da3.ed72_i_procavaliacao = avalcompoeres.ed44_i_procavaliacao
                                            and da3.ed72_i_diario = dr3.ed73_i_diario
           where dr3.ed73_i_codigo = diarioresultado.ed73_i_codigo
           and (da3.ed72_c_amparo = 'N' OR diariorescomp.ed73_c_amparo = 'N')
           and (da3.ed72_i_valornota is not null OR diariorescomp.ed73_i_valornota is not null))
         when procresultado.ed43_c_obtencao = 'UN' then
          (select daval5.ed72_i_valornota
           from diario as d5
            inner join diarioavaliacao as daval5 on daval5.ed72_i_diario = d5.ed95_i_codigo
            inner join procavaliacao as proc5 on proc5.ed41_i_codigo = daval5.ed72_i_procavaliacao
           where d5.ed95_i_aluno = diario.ed95_i_aluno
           and d5.ed95_i_regencia = diario.ed95_i_regencia
           order by proc5.ed41_i_sequencia DESC LIMIT 1)
         else null
        end";
  return $sql;
}
function db_inputdatamerenda($nome, $dia = "", $mes = "", $ano = "", $dbcadastro = true, $dbtype = 'text', $db_opcao = "", $js_script = "", $nomevar = "", $bgcolor = "",$shutdown_function="none",$onclickBT="", $onfocus="", $jsRetornoCal="") {
  //#00#//db_inputdata
  //#10#//Função para montar um objeto tipo data. Serão três objetos input na tela mais um objeto input tipo button para
  //#10#//acessar o calendário do sistema
  //#15#//db_inputdata($nome,$dia="",$mes="",$ano="",$dbcadastro=true,$dbtype='text',$db_opcao=3,$js_script="",$nomevar="",$bgcolor="",$shutdown_funcion="none",$onclickBT="",$onfocus"");
  //#20#//Nome            : Nome do campo da documentacao do sistema ou do arquivo
  //#20#//Dia             : Valor para o objeto |db_input| do dia
  //#20#//Mês             : Valor para o objeto |db_input| do mês
  //#20#//Ano             : Valor para o objeto |db_input| do ano
  //#20#//Cadastro        : True se cadastro ou false se nao cadastro Padrão: true
  //#20#//Type            : Tipo a ser incluido para a data Padrão: text
  //#20#//Opcao           : *db_opcao* do programa a ser executado neste objeto input, inclusão(1) alteração(2) exclusão(3)
  //#20#//Script          : JAVASCRIPT  a ser executado juntamento com o objeto, indicando os métodos
  //#20#//Nome Secundário : Nome do input que será gerado, assumindo somente as características do campo Nome
  //#20#//Cor Background  : Cor de fundo da tela, no caso de *db_opcao*=3 será "#DEB887"
  //#20#//shutdown_funcion : função que será executada apos o retorno do calendário
  //#20#//onclickBT       : Função que será executada ao clicar no botão que abre o calendário
  //#20#//onfocus         : Função que será executada ao focar os campos
  //#99#//Quando o parâmetro Opção for de alteração (Opcao = 22) ou exclusão (Opção = 33) o sistema
  //#99#//colocará a sem acesso ao calendário
  //#99#//Para *db_opcao* 3 e 5 o sistema colocará sem o calendário e com readonly
  //#99#//
  //#99#//Os três input gerados para a data terão o nome do campo acrescido do [Nome]_dia, [Nome]_mes e
  //#99#//[Nome]_ano os quais serão acessados pela classe com estes nome.
  //#99#//
  //#99#//O sistema gerá para a primeira data incluída um formulário, um objeto de JanelaIframe do nosso
  //#99#//sistema para que sejá mostrado o calendário.
  global $DataJavaScript;
  if ($db_opcao == 3 || $db_opcao == 22) {
    $bgcolor = "style='background-color:#DEB887'";
  }
  if ($bgcolor == "") {
    $bgcolor = @$GLOBALS['N'.$nome];
  }
  if (isset($dia) && $dia != "" && isset($mes) && $mes != '' && isset($ano) && $ano != "") {
    $diamesano = $dia."/".$mes."/".$ano;
    $anomesdia = $ano."/".$mes."/".$dia;
  }
  $sButtonType = "button";
  ?>
  <input name="<?=($nomevar==""?$nome:$nomevar).""?>" <?=$bgcolor?>   type="<?=$dbtype?>" id="<?=($nomevar==""?$nome:$nomevar).""?>" <?=($db_opcao==3 || $db_opcao==22 ?'readonly':($db_opcao==5?'disabled':''))?> value="<?=@$diamesano?>" size="10" maxlength="10" autocomplete="off" onBlur='js_validaDbData(this);' onKeyUp="return js_mascaraData(this,event)"  onFocus="js_validaEntrada(this);" <?=$js_script?> >
  <input name="<?=($nomevar==""?$nome:$nomevar)."_dia"?>"   type="hidden" title="" id="<?=($nomevar==""?$nome:$nomevar)."_dia"?>" value="<?=@$dia?>" size="2"  maxlength="2" >
  <input name="<?=($nomevar==""?$nome:$nomevar)."_mes"?>"   type="hidden" title="" id="<?=($nomevar==""?$nome:$nomevar)."_mes"?>" value="<?=@$mes?>" size="2"  maxlength="2" >
  <input name="<?=($nomevar==""?$nome:$nomevar)."_ano"?>"   type="hidden" title="" id="<?=($nomevar==""?$nome:$nomevar)."_ano"?>" value="<?=@$ano?>" size="4"  maxlength="4" >
  <?
  if (($db_opcao < 3) || ($db_opcao == 4)) {
    ?>
    <script>
      var PosMouseY, PosMoudeX;
      function js_comparaDatas<?=($nomevar==""?$nome:$nomevar).""?>(dia,mes,ano) {
        var objData        = document.getElementById('<?=($nomevar==""?$nome:$nomevar).""?>');
        objData.value      = dia+"/"+mes+'/'+ano;
        <?=$jsRetornoCal?>
      }
    </script>
    <?
    if (isset($dbtype) && strtolower($dbtype) == strtolower('hidden')) {
      $sButtonType = "hidden";
    }
    ?>
    <input value="D" type="<?=$sButtonType?>" name="dtjs_<?=($nomevar==""?$nome:$nomevar)?>" onclick="<?=$onclickBT?>pegaPosMouse(event);show_calendarmerenda('<?=($nomevar==""?$nome:$nomevar)?>','<?=$shutdown_function?>')"  >
    <?
  }
}
function VerUltimoRegHistorico($aluno,$etapaindicada,$etapasturma) {

  $restricao = false;
  global $msgequiv;
  $sql_hist = "SELECT ed11_i_codigo as ult_cod,
                     ed11_c_descr as ult_descr,
                     ed11_i_ensino as ult_ensino,
                     ed11_i_sequencia as ult_sequencia,
                     ed10_c_descr as ult_descrensino,
                     ed10_c_abrev as ult_abrevensino,
                     ed62_c_resultadofinal as ult_resfinal,
                     ed62_i_anoref as ult_ano
              FROM historicomps
               inner join historico on ed61_i_codigo = ed62_i_historico
               inner join serie on ed11_i_codigo = ed62_i_serie
               inner join ensino on ed10_i_codigo = ed11_i_ensino
              WHERE ed61_i_aluno = $aluno
              UNION
              SELECT ed11_i_codigo as ult_cod,
                     ed11_c_descr as ult_descr,
                     ed11_i_ensino as ult_ensino,
                     ed11_i_sequencia as ult_sequencia,
                     ed10_c_descr as ult_descrensino,
                     ed10_c_abrev as ult_abrevensino,
                     ed99_c_resultadofinal as ult_resfinal,
                     ed99_i_anoref as ult_ano
              FROM historicompsfora
               inner join historico on ed61_i_codigo = ed99_i_historico
               inner join serie on ed11_i_codigo = ed99_i_serie
               inner join ensino on ed10_i_codigo = ed11_i_ensino
              WHERE ed61_i_aluno = $aluno
              ORDER BY ult_ano desc,ult_sequencia desc,ult_resfinal asc
              LIMIT 1
             ";


  $result_hist = db_query($sql_hist);
  if (pg_num_rows($result_hist)>0) {

    $ult_cod = pg_result($result_hist,0,'ult_cod');
    $ult_resfinal = trim(pg_result($result_hist,0,'ult_resfinal'));
    $ult_descr = trim(pg_result($result_hist,0,'ult_descr'));
    $ult_descrensino = trim(pg_result($result_hist,0,'ult_descrensino'));
    $ult_abrevensino = trim(pg_result($result_hist,0,'ult_abrevensino'));
    $ult_ensino = trim(pg_result($result_hist,0,'ult_ensino'));
    $ult_sequencia = trim(pg_result($result_hist,0,'ult_sequencia'));
    if ($ult_resfinal=="R") {

      $descr_situacao = "REPROVADO";
      if ($ult_cod!=$etapaindicada) {

        $temequiv  = false;
        $msgequiv .= "\\nATENÇÃO: Aluno(a) $aluno tem a Etapa $ult_descr ($ult_descrensino - $ult_abrevensino) como a última cursada, na situação de $descr_situacao. Selecione uma turma que contenha alguma das etapas abaixo relacionadas:\\n";
        $sql_equiv = "SELECT ed234_i_serieequiv,ed11_c_descr as descr_equiv,ed10_c_descr as ensino_equiv,ed10_c_abrev as abrev_equiv
                      FROM serieequiv
                       inner join serie on ed11_i_codigo = ed234_i_serieequiv
                       inner join ensino on ed10_i_codigo = ed11_i_ensino
                      WHERE ed234_i_serie = $ult_cod order by ed11_i_sequencia";
        $result_equiv = db_query($sql_equiv);
        $msgequiv .= "-> ".$ult_descr." (".$ult_descrensino." - ".$ult_abrevensino.")\\n";
        for ($r=0;$r<pg_num_rows($result_equiv);$r++) {

          $codserie_equiv = pg_result($result_equiv,$r,'ed234_i_serieequiv');
          $descr_equiv = trim(pg_result($result_equiv,$r,'descr_equiv'));
          $ensino_equiv = trim(pg_result($result_equiv,$r,'ensino_equiv'));
          $abrev_equiv = trim(pg_result($result_equiv,$r,'abrev_equiv'));
          if ($codserie_equiv==$etapaindicada) {

            $temequiv = true;
            break;
          }else{
            $msgequiv .= "-> ".$descr_equiv." (".$ensino_equiv." - ".$abrev_equiv.")\\n";
          }
        }
        if ($temequiv==false) {
          $restricao = true;
        }
      }
    } else if($ult_resfinal=='A') {

      $descr_situacao = "APROVADO";
      $sql_prox = "SELECT ed11_i_codigo as cod_prox,ed11_c_descr as descr_prox,ed10_c_descr as ensino_prox,ed10_c_abrev as abrev_prox
                     FROM serie
                    inner join ensino on ed10_i_codigo = ed11_i_ensino
                   WHERE ed11_i_ensino = $ult_ensino
                   AND ed11_i_sequencia > $ult_sequencia order by ed11_i_sequencia";
      $result_prox = db_query($sql_prox);
      if (pg_num_rows($result_prox)>0) {

        $cod_prox = pg_result($result_prox,0,'cod_prox');
        $descr_prox = trim(pg_result($result_prox,0,'descr_prox'));
        $ensino_prox = trim(pg_result($result_prox,0,'ensino_prox'));
        $abrev_prox = trim(pg_result($result_prox,0,'abrev_prox'));

        if ($cod_prox!=$etapaindicada) {

          $temequiv = false;
          $msgequiv .= "\\nATENÇÃO: Aluno(a) $aluno tem a Etapa $ult_descr ($ult_descrensino - $ult_abrevensino)como a última cursada, na situação de $descr_situacao. Selecione uma turma que contenha alguma das etapas abaixo relacionadas:\\n";
          $sql_equiv = "SELECT ed234_i_serieequiv,ed11_c_descr as descr_equiv,ed10_c_descr as ensino_equiv,ed10_c_abrev as abrev_equiv
                        FROM serieequiv
                         inner join serie on ed11_i_codigo = ed234_i_serieequiv
                         inner join ensino on ed10_i_codigo = ed11_i_ensino
                        WHERE ed234_i_serie = $cod_prox order by ed11_i_sequencia";
          $result_equiv = db_query($sql_equiv);
          $msgequiv .= "-> ".$descr_prox." (".$ensino_prox." - ".$abrev_prox.")\\n";
          for($r=0;$r<pg_num_rows($result_equiv);$r++) {

            $codserie_equiv = pg_result($result_equiv,$r,'ed234_i_serieequiv');
            $descr_equiv = trim(pg_result($result_equiv,$r,'descr_equiv'));
            $ensino_equiv = trim(pg_result($result_equiv,$r,'ensino_equiv'));
            $abrev_equiv = trim(pg_result($result_equiv,$r,'abrev_equiv'));
            if ($codserie_equiv==$etapaindicada) {
              $temequiv = true;
              break;
            }else{
              $msgequiv .= "-> ".$descr_equiv." (".$ensino_equiv." - ".$abrev_equiv.")\\n";
            }
          }
          if ($temequiv==false) {
            $restricao = true;
          }
        }
      }//else{
      //$restricao = true;
      //$msgequiv .= "\\nATENÇÃO: Aluno(a) $aluno tem a Etapa $ult_descr ($ult_descrensino - $ult_abrevensino) como a última cursada, na situação de $descr_situacao. Não existem etapas após esta descrita acima.\\n";
      //}
    } elseif ($ult_resfinal=="P") {

      $descr_situacao = "APROVADO PARCIAL";
      if (!strstr($etapasturma,$ult_cod)) {

        $temequiv = false;
        $msgequiv .= "\\nATENÇÃO: Aluno(a) $aluno tem a Etapa $ult_descr ($ult_descrensino - $ult_abrevensino) como a última cursada, na situação de $descr_situacao. Selecione uma turma que contenha alguma das etapas abaixo relacionadas:\\n";
        $sql_equiv = "SELECT ed234_i_serieequiv,ed11_c_descr as descr_equiv,ed10_c_descr as ensino_equiv,ed10_c_abrev as abrev_equiv
                        FROM serieequiv
                         inner join serie on ed11_i_codigo = ed234_i_serieequiv
                         inner join ensino on ed10_i_codigo = ed11_i_ensino
                        WHERE ed234_i_serie = $ult_cod";
        $result_equiv = db_query($sql_equiv);
        $msgequiv .= "-> ".$ult_descr." (".$ult_descrensino." - ".$ult_abrevensino.")\\n";
        for($r=0;$r<pg_num_rows($result_equiv);$r++) {

          $codserie_equiv = pg_result($result_equiv,$r,'ed234_i_serieequiv');
          $descr_equiv = trim(pg_result($result_equiv,$r,'descr_equiv'));
          $ensino_equiv = trim(pg_result($result_equiv,$r,'ensino_equiv'));
          $abrev_equiv = trim(pg_result($result_equiv,$r,'abrev_equiv'));
          if ($codserie_equiv==$etapaindicada) {
            $temequiv = true;
            break;
          }else{
            $msgequiv .= "-> ".$descr_equiv." (".$ensino_equiv." - ".$abrev_equiv.")\\n";
          }
        }
        if ($temequiv==false) {
          $restricao = true;
        }
      }
    }
    return $restricao;
  }
}

/*
*  @param string $sStr string a ter a codificacao convertida
*  @param int $iTipo define o tipo de conversao da codificacao
*  @return string string convertida
*/
function converteCodificacao($sStr, $iTipo = 1) {

  switch($iTipo) {

    case 1:

      return mb_convert_encoding($sStr, "ISO-8859-1", "UTF-8");

    default:

      return $sStr;
  }
}


function validaCnsDefinitivo($cns) {

  if ( !is_numeric($cns) ) {
    return false;
  }

  if ( $cns == '000000000000000' ) {
    return false;
  }

  if ((strlen(trim($cns))) != 15) {
    return false;
  }
  $pis = substr($cns,0,11);
  $soma = (((substr($pis, 0,1)) * 15) +
    ((substr($pis, 1,1)) * 14) +
    ((substr($pis, 2,1)) * 13) +
    ((substr($pis, 3,1)) * 12) +
    ((substr($pis, 4,1)) * 11) +
    ((substr($pis, 5,1)) * 10) +
    ((substr($pis, 6,1)) * 9) +
    ((substr($pis, 7,1)) * 8) +
    ((substr($pis, 8,1)) * 7) +
    ((substr($pis, 9,1)) * 6) +
    ((substr($pis, 10,1)) * 5));
  $resto = fmod($soma, 11);
  $dv = 11  - $resto;
  if ($dv == 11) {
    $dv = 0;
  }
  if ($dv == 10) {
    $soma = ((((substr($pis, 0,1)) * 15) +
        ((substr($pis, 1,1)) * 14) +
        ((substr($pis, 2,1)) * 13) +
        ((substr($pis, 3,1)) * 12) +
        ((substr($pis, 4,1)) * 11) +
        ((substr($pis, 5,1)) * 10) +
        ((substr($pis, 6,1)) * 9) +
        ((substr($pis, 7,1)) * 8) +
        ((substr($pis, 8,1)) * 7) +
        ((substr($pis, 9,1)) * 6) +
        ((substr($pis, 10,1)) * 5)) + 2);
    $resto = fmod($soma, 11);
    $dv = 11  - $resto;
    $resultado = $pis."001".$dv;
  } else {
    $resultado = $pis."000".$dv;
  }
  if ($cns != $resultado) {
    return false;
  } else {
    return true;
  }
}

function validaCnsProvisorio($cns) {

  if ( !is_numeric($cns) ) {
    return false;
  }

  if ( $cns == '000000000000000' ) {
    return false;
  }

  if ((strlen(trim($cns))) != 15) {
    return false;
  }
  $soma = (((substr($cns,0,1)) * 15) +
    ((substr($cns,1,1)) * 14) +
    ((substr($cns,2,1)) * 13) +
    ((substr($cns,3,1)) * 12) +
    ((substr($cns,4,1)) * 11) +
    ((substr($cns,5,1)) * 10) +
    ((substr($cns,6,1)) * 9) +
    ((substr($cns,7,1)) * 8) +
    ((substr($cns,8,1)) * 7) +
    ((substr($cns,9,1)) * 6) +
    ((substr($cns,10,1)) * 5) +
    ((substr($cns,11,1)) * 4) +
    ((substr($cns,12,1)) * 3) +
    ((substr($cns,13,1)) * 2) +
    ((substr($cns,14,1)) * 1));
  $resto = fmod($soma,11);
  if ($resto != 0) {
    return false;
  } else {
    return true;
  }

}

/**
 * procura qualquer valor
 * @param array $array
 * @param mixed $valor
 */
function arr_search( $array, $valor ) {
  for( $x=0; $x < count($array); $x++) {
    if ( $array[$x] == $valor ) {
      return true;
    }
  }
  return false;
}

/**
 * Função que gerar arquivo do BPA
 * @param  object   $oDados
 * @param  resource $rsCabecalho
 * @param  resource $rsProducao
 * @param  booleano $lValidaCid
 * @param  string   $sArquivo
 * @return integer  -1 convenção para falha durante a geração, numeros maiore que 0(zero) representan numero de BPA's
 */
function geraArquivoBPA($oDados, $rsCabecalho, $rsProducao, $lValidaCid = true, $sArquivo = "/tmp/arquivobpa.txt") {

  require_once(modification("dbforms/db_layouttxt.php"));
  include(modification("classes/db_db_layoutcampos_classe.php"));

  /* Inicializa variaveis princiais */
  $cldb_layouttxt      = new db_layouttxt(85, $sArquivo, "");
  $sValida             = "/tmp/validacns.bpa";
  $pValidacns          = fopen($sValida, "w");
  $iPagina             = 1;
  $iLinhaPagina        = 1;
  $oCabecalho          = db_utils::fieldsMemory($rsCabecalho, 0);
  $oCabecalho->cbc_fim = "";
  $lErro               = false;
  $objValida           = new stdClass();
  $objValida->valida   = array(array(), array(), array());
  $iBpas               = 0;
  $iUpsAnt             = "";
  $iCnsMed             = "";

  /* Escreve no arquivo o cabeçalho */
  $cldb_layouttxt->setByLineOfDBUtils($oCabecalho, 1);

  /* Percorre todas as linhas da produção */
  for ($iIndice = 0; $iIndice < $oDados->iLinhas; $iIndice ++) {

    $oProducao = db_utils::fieldsMemory($rsProducao, $iIndice);

    /* Atualiza o Termometro */
    db_atutermometro ($iIndice, $oDados->iLinhas, 'termometro');

    /* Seta campos da produção */
    $oProducao->prd_fim = "";
    if ($oProducao->prd_ups != $iUpsAnt) {

      $iLinhaPagina = 1;
      $iPagina      = 1;
      $iUpsAnt      = $oProducao->prd_ups;
      $iBpas++;

    }
    if ($oDados->sTipo == "01") {

      $oProducao->prd_cmp    = $oDados->iCompano.str_pad ($oDados->iCompmes,2, "0", STR_PAD_LEFT);
      $oProducao->prd_cnspac = str_pad (' ', 15, ' ', STR_PAD_LEFT);
      $oProducao->prd_sexo   = str_pad (' ', 2, ' ', STR_PAD_LEFT);
      $oProducao->prd_ibge   = str_pad (' ', 6, ' ', STR_PAD_LEFT);
      $oProducao->prd_cid    = str_pad (' ', 4, ' ', STR_PAD_LEFT);
      $oProducao->prd_cnsmed = str_pad (' ', 15, ' ', STR_PAD_LEFT);
      $oProducao->prd_dtaten = str_pad (' ', 8, ' ', STR_PAD_LEFT);
      $oProducao->prd_caten  = str_pad (' ', 2, ' ', STR_PAD_LEFT);
      $oProducao->prd_nmpac  = str_pad (' ', 30, ' ', STR_PAD_LEFT);
      $oProducao->prd_dtnasc = str_pad (' ', 8, ' ', STR_PAD_LEFT);
      $oProducao->prd_raca   = "99";
      $oProducao->prd_naut   = str_pad (' ', 13, ' ', STR_PAD_LEFT );
      $oProducao->prd_org    = "BPA";

    } else {

      if ($oProducao->prd_cnsmed != $iCnsMed) {

        $iLinhaPagina = 1;
        $iPagina      = 1;
        $iCnsMed      = $oProducao->prd_cnsmed;
        $iBpas++;

      }
      $oProducao->prd_dtaten = str_replace('-', '', $oProducao->prd_dtaten);
      $oProducao->prd_dtnasc = str_replace('-', '', $oProducao->prd_dtnasc);

      /* Valida informações antes de gerar o arquivo */
      if ($oProducao->valida_cns_cgs == 'f') {

        if (( arr_search( $objValida->valida[0],
            "$oProducao->cod_pac, $oProducao->prd_nmpac, $oProducao->prd_cnspac" )
          == false) ) {

          $objValida->valida[0][] = "$oProducao->cod_pac, $oProducao->prd_nmpac, $oProducao->prd_cnspac";
          $lErro=true;

        }
      }
      if ($oProducao->valida_cns_med == 'f') {
        if (( arr_search( $objValida->valida[1],
            "$oProducao->cod_prof, $oProducao->nome_med , $oProducao->prd_cnsmed")
          == false) ) {

          $objValida->valida[1][] = "$oProducao->cod_prof, $oProducao->nome_med , $oProducao->prd_cnsmed";
          $lErro=true;

        }
      }
      if ($lValidaCid == true) {
        if ($oProducao->prd_cid == "" && $oProducao->proc_quant_cid > 0) {
          if (( arr_search( $objValida->valida[2],$oProducao->cod_faa." - ".$oProducao->prd_pa) == false)) {

            $objValida->valida[2][] = $oProducao->cod_faa." - ".$oProducao->prd_pa;
            $lErro=true;

          }
        }
      }
    }
    $oProducao->prd_flh = str_pad ($iPagina, 3, "0", STR_PAD_LEFT);
    $oProducao->prd_seq = str_pad ($iLinhaPagina, 2, "0", STR_PAD_LEFT);

    /* Escreve produção no cabeçalho */
    $cldb_layouttxt->setByLineOfDBUtils($oProducao, 3);

    /* quebra a pagina quando chega a 20 elementos */
    if ($iLinhaPagina == 20) {

      $iLinhaPagina = 0;
      $iPagina++;
      if ($oDados->sTipo == "01") {
        $iBpas++;
      }

    }
    $iLinhaPagina ++;

  }
  /* Fecha ponteiro do arquivo */
  $cldb_layouttxt->fechaArquivo();

  /* Se houve erro e tipo de BPA 02 individual */
  if (($lErro == true) && ($oDados->sTipo == "02")) {

    db_msgbox("Arquivo possui inconsistências. Verifique!");
    asort($objValida->valida[0]);
    for ($iX = 0; $iX < sizeof( $objValida->valida[0] ); $iX++) {

      fwrite( $pValidacns, "PACIENTES:".$objValida->valida[0][ $iX ]. "\n" );

    }
    asort($objValida->valida[1]);
    for ($iX = 0; $iX < sizeof( $objValida->valida[1] ); $iX++) {

      fwrite($pValidacns, "PROFISSIONAIS:".$objValida->valida[1][ $iX ]."\n" );

    }
    for ($iX = 0; $iX < sizeof( $objValida->valida[2] ); $iX++) {

      fwrite( $pValidacns, "FAA - Procedimento sem CID:".$objValida->valida[2][ $iX ]."\n" );

    }
    fclose( $pValidacns );
    ?>
    <script>
      jan = window.open('sau2_bpa001.php?bpa=<?=$sValida?>', '',
        'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
      jan.moveTo(0,0);
    </script>
    <?
    return -1;

  } else {

    /* Abre pop-up com link para download do arquivo */
    ?>
    <script>
      listagem = '<?=$sArquivo?>#Download arquivo TXT (BPA)|';
      js_montarlista(listagem, 'form1');
    </script>
    <?
    return $iBpas;

  }

}

/*
 * Função que soma dias, messes e ou anos a uma determinada data
 * @descr função que soma uma quantidade de dias, meses e ou anos à data passada nos parâmetros $iDia, $iMes e $iAno.
 * @param int $iDia dia da data base para o cálculo
 * @param int $iMes mes da data base para o cálculo
 * @param int $iAno ano da data base para o cálculo
 * @param int $iDiaSomar quantidade de dias a serem somados à data passada
 * @param int $iMesSomar quantidade de meses a serem somados à data passada
 * @param int $iAnoSomar quantidade de anos a serem somados à data passada
 * @param int $iFormato define o formato da data de retorno 1 - dd/mm/aaaa  2 - aaaa-mm-dd  3 - Timestamp
 * @return mixed data resultante da soma
 */
function somaDataDiaMesAno($iDia, $iMes, $iAno, $iDiaSomar, $iMesSomar, $iAnoSomar, $iFormato = 1) {

  require_once(modification("ext/php/adodb-time.inc.php"));
  $dTimestamp  = adodb_mktime(0, 0, 0, $iMes, $iDia, $iAno);
  $dTimestamp += $iAnoSomar * 31557600; //31536000
  $dTimestamp += $iMesSomar * 2629800;  //2628000
  $dTimestamp += $iDiaSomar * 86400;

  if ($iFormato == 1) {
    return adodb_date('d/m/Y', $dTimestamp);
  } else if ($iFormato == 2) {
    return adodb_date('Y-m-d', $dTimestamp);
  } else {
    return $dTimestamp;
  }

}
/**
 * Calcula a Qauntidade de dias entre as duas datas
 * @param $data1 Formato dd/mm/aaaa
 * @param $data2 Formato dd/mm/aaaa
 * @return inteiro quantidade de dias entre as duas datas
 */
function quantDias($data1, $data2) {
  $aVet1=explode("/",$data1);
  $aVet2=explode("/",$data2);
  return round((mktime(0,0,0,$aVet2[1],$aVet2[0],$aVet2[2])-
      mktime(0,0,0,$aVet1[1],$aVet1[0],$aVet1[2])) / (24 * 60 * 60), 0);
}
function ParamAvalAlternativa($escola) {

  $sql2    = "SELECT ed233_c_avalalternativa ";
  $sql2   .= "        FROM edu_parametros ";
  $sql2   .= "        WHERE ed233_i_escola = $escola ";
  $result2 = db_query($sql2);
  if (pg_num_rows($result2) > 0) {
    $retorno = pg_result($result2,0,0);
  } else {
    $retorno = "N";
  }

  return $retorno;

}

/**
 * Valida se o horario pode ser marcado
 * @param  string   $sTipoGrade
 * @param  inteiro  $sd23_i_undmedhor
 * @param  data     $sd23_d_consulta
 * @param  inteiro  $sd23_i_ficha
 * @return boolean  status true para grade liberada false para grade ja agendada
 */
function validaSaldo($sTipoGrade, $sd23_i_undmedhor, $sd23_d_consulta, $sd23_i_ficha) {

  $oDaoAgendamentos  = db_utils::getdao('agendamentos');
  $oDaoUndmedhorario = db_utils::getdao('undmedhorario');

  $sWhere            = " NOT EXISTS (select * from agendaconsultaanula where s114_i_agendaconsulta=sd23_i_codigo)";
  //verifica o tipo da grade
  if ($sTipoGrade == "P") {

    //grade por periodo verifica a quantidade de fichas

    $sSql               = $oDaoAgendamentos->sql_query("",
      "*",
      "",
      " sd23_i_undmedhor = $sd23_i_undmedhor ".
      " and sd23_d_consulta='$sd23_d_consulta' ".
      " and $sWhere "
    );
    $rsAgendamento      = $oDaoAgendamentos->sql_record($sSql);
    $iFichasMarcadas    = $oDaoAgendamentos->numrows;
    $sSql               = $oDaoUndmedhorario->sql_query("",
      "sd30_i_fichas,sd30_i_reservas",
      "",
      " sd30_i_codigo = $sd23_i_undmedhor"
    );
    $rsUndmedhorario    = $oDaoUndmedhorario->sql_record($sSql);
    if ($oDaoUndmedhorario->numrows > 0) {
      $oUndmedhorario   = db_utils::fieldsmemory($rsUndmedhorario, 0);
    } else {
      return false;
    }
    $iFichasdisponiveis = $oUndmedhorario->sd30_i_fichas+$oUndmedhorario->sd30_i_reservas;
    if ($iFichasMarcadas >= $iFichasdisponiveis) {
      return false;
    }

  } else {

    //verifica se o id da ficha ja esta agendade
    $sSql          = $oDaoAgendamentos->sql_query("",
      "*",
      "",
      " sd23_i_undmedhor    = $sd23_i_undmedhor ".
      " and sd23_i_ficha    = $sd23_i_ficha ".
      " and sd23_d_consulta = '$sd23_d_consulta' ".
      " and $sWhere "
    );
    $rsAgendamento = $oDaoAgendamentos->sql_record($sSql);
    if ($oDaoAgendamentos->numrows > 0) {
      return false;
    }

  }
  return true;

}


function geraArquivoHiperdia($iTipo, $sArquivo, $rsRegistros, $iLinhas) {

  require_once(modification('dbforms/db_layouttxt.php'));
  require_once(modification('classes/db_db_layoutcampos_classe.php'));

  $iCodLayout = null;
  switch($iTipo) {

    case 0:

      $iCodLayout = 90;
      break;

    case 1:

      $iCodLayout = 87;
      break;

    case 2:

      $iCodLayout = 88;
      break;

    case 3:

      $iCodLayout = 89;
      break;

    case 4:

      $iCodLayout = 91;
      break;

    default:

      $iCodLayout = -1;

  }

  $clDbLayoutTxt = new db_layouttxt($iCodLayout, $sArquivo, '');
  $aCgs          = array();
  $lErro         = false;

  if ($iTipo == 0 || $iTipo == 4) { // $rsRegistros já vem com os dados prontos

    $clDbLayoutTxt->setByLineOfDBUtils($rsRegistros, $iTipo + 1);
    $clDbLayoutTxt->fechaArquivo();

    return true;

  }

  for ($iIndice = 0; $iIndice < $iLinhas; $iIndice++) {

    $oRegistro = db_utils::fieldsMemory($rsRegistros, $iIndice)
    or die ('Erro na geração do Arquivo. Contate o administrador do sistema.');

    db_atutermometro ($iIndice, $iLinhas, 'termometro');
    if ($iTipo == 2) {

      if (empty($oRegistro->sexo)) {

        $aCgs[$oRegistro->z01_i_cgsund]['sexo'] = 'Sexo não informado';
        $lErro = true;

      }

      if (empty($oRegistro->data_nascimento)) {

        $aCgs[$oRegistro->z01_i_cgsund]['datanasc'] = 'Data de nascimento não informada';
        $lErro = true;

      }

      if (empty($oRegistro->nome_mae)) {

        $aCgs[$oRegistro->z01_i_cgsund]['datanasc'] = 'Nome da mãe não informado.';
        $lErro = true;

      }

    }

    $clDbLayoutTxt->setByLineOfDBUtils($oRegistro, 3);

  }

  $clDbLayoutTxt->fechaArquivo();
  if ($lErro == true) {

    $sLog = "/tmp/logErroHiperdia$iTipo.txt";
    $pLog = fopen($sLog, "wb");
    foreach ($aCgs as $iCgs => $aTipos) {

      foreach ($aTipos as $sTipos => $sValor) {

        fwrite($pLog, "CGS $iCgs : ".$sValor."\n" );

      }

    }
    fclose($pLog);

  }

  return !$lErro;

}

function unirArquivos($aEndArquivosUnir, $sEndArquivoGerar, $lDeletarOrigens = false) {


  $pArquivoDestino = fopen($sEndArquivoGerar, 'wb');
  if (!$pArquivoDestino) {
    return false;
  }

  $iTam = count($aEndArquivosUnir);
  for ($iCont = 0; $iCont < $iTam; $iCont++) {

    $pArquivoOrigem = fopen($aEndArquivosUnir[$iCont], 'rb');
    if (!$pArquivoOrigem) {

      fclose($pArquivoDestino);
      return false;

    }
    stream_copy_to_stream($pArquivoOrigem, $pArquivoDestino);
    fclose($pArquivoOrigem);

  }

  fclose($pArquivoDestino);

  if ($lDeletarOrigens) {

    for ($iCont = 0; $iCont < $iTam; $iCont++) {

      unlink($aEndArquivosUnir[$iCont]);

    }

  }


  return true;

}

/**
 * retorna o arquivo referente ao modelo de FAA selecionado na sau_config
 * @param $cod - codigo do modelo
 */
function modeloFaa($cod) {

  switch ($cod) {

    case 1:

      return "sau2_emitirfaa002.php";
      break;

    case 2:

      return "sau2_emitirfaa003.php";
      break;

    case 3:

      return "sau2_fichaatend005.php";
      break;

    case 4:

      return "sau2_fichaatend006.php";
      break;

    case 5:

      return "sau2_emitirfaa004.php";
      break;

    case 6:

      return "sau2_emitirfaa005.php";
      break;

    default:

      return "sau2_emitirfaa006.php";

  }
}

/**
 * Carrega a tabela de configuração de qualquer modulo
 * @param  string $sTabela - Nome da tabela no banco
 * @param  string $sCondicao - Condição (where) do select
 * @return object - Objeto com os campos da tabela
 */
function loadConfig($sTabela, $sCondicao = '') {

  $oDaoConfig = db_utils::getdao($sTabela);
  $sSql       = $oDaoConfig->sql_query_file(null, '*', '', $sCondicao);
  $rsConfig   = $oDaoConfig->sql_record($sSql);
  if ($oDaoConfig->numrows > 0) {
    return db_utils::fieldsmemory($rsConfig, 0);
  }
  return null;

}

function VerNutricionista($iCodUsuario) {

  $sql  = " select me31_i_codigo";
  $sql .= " from mer_nutricionistaescola";
  $sql .= "  inner join mer_nutricionista on  mer_nutricionista.me02_i_codigo = mer_nutricionistaescola.me31_i_nutricionista";
  $sql .= "  inner join db_usuacgm on  db_usuacgm.cgmlogin = mer_nutricionista.me02_i_cgm";
  $sql .= "  inner join db_usuarios on  db_usuarios.id_usuario = db_usuacgm.id_usuario";
  $sql .= " where db_usuarios.id_usuario = $iCodUsuario";
  $result = db_query($sql);
  if (pg_num_rows($result)>0) {
    return $iCodUsuario;
  } else {
    return null;
  }

}

/*
 * função par somar minutos numa hora (tem especificidades para ser utilizada nos horários do módulo agendamento
 * @author Cristian Tales
 * @revision Tony F. B. M. Ribeiro
 * @param string $inicio - hora
 * @param string $minutos - minutos a somar na hora
 * @return string
 */
function somaMinutosHoraAgendamento($sHoraIni, $iMinutosSomar) {

  $aHoraIni = explode(':', $sHoraIni);
  $iHoraIni = $aHoraIni[0];
  $iMinIni  = $aHoraIni[1];

  $iMinIni  = number_format($iMinIni + $iMinutosSomar, 2, '.', '');

  $aMin     = explode('.', $iMinIni);
  if ($aMin[1] >= 60) {

    $iMinIni  = $aMin[0] + 1;
    $iMinIni += ($aMin[1] - 60 ) / 100;

  }
  while ($iMinIni >= 60) {

    $iHoraIni++;
    if ($iHoraIni == 24) {
      $iHoraIni = 0;
    }
    $iMinIni -= 60;

  }
  if ($iMinIni < 10) {

    $iMinIni = '0'.$iMinIni;
  }

  return str_pad($iHoraIni, 2, 0, STR_PAD_LEFT).':'.$iMinIni;

}

/*
 * função par somar minutos numa hora
 * @author Tony F. B. M. Ribeiro
 * @param string $inicio - hora
 * @param string $minutos - minutos a somar na hora
 * @return string
 */
function somaMinutosHora($sHoraIni, $iMinutosSomar) {

  $aHoraIni = explode(':', $sHoraIni);
  $iHoraIni = $aHoraIni[0];
  $iMinIni  = $aHoraIni[1];

  $iMinIni += $iMinutosSomar;

  while ($iMinIni >= 60) {

    $iHoraIni++;
    if ($iHoraIni == 24) {
      $iHoraIni = 0;
    }
    $iMinIni -= 60;

  }

  return str_pad($iHoraIni, 2, 0, 'STR_PAD_LEFT').':'.str_pad($iMinIni, 2, 0, 'STR_PAD_LEFT');

}

/*
 * função retorno minutos  entre duas horas
 * @author Cristian Tales
 * @revision Tony F. B. M. Ribeiro
 * @param string $hora1 - hora inicial
 * @param string $hora2 - hora final
 * @return string
 */
function diferencaEmMinutos($sHoraIni, $sHoraFim) {

  $iHoraIni = substr($sHoraIni, 0, 2);
  $iMinIni  = substr($sHoraIni, 3, 2);
  $iHoraFim = substr($sHoraFim, 0, 2);
  $iMinFim  = substr($sHoraFim, 3, 2);

  // diferença em horas
  $iMinutosFim = $iMinFim + ($iHoraFim * 60);
  $iMinutosIni = $iMinIni + ($iHoraIni * 60);

  return $iMinutosFim - $iMinutosIni;

  /*  $iHorasTrabalhadas = ($minutosFim - $minutosIni) / 60;
		$iHorasTrabalhadas = $iHorasTrabalhadas > 20 ? $iHorasTrabalhadas - 20 : $iHorasTrabalhadas;
		$decimal           =  strstr($iHorasTrabalhadas, '.');

		if ($decimal != '') {

			$minutos_decimal = round($decimal * 60);
			$explode         = explode('.', $iHorasTrabalhadas);
			$horas_finais    = @str_pad($explode[0], 2, 0, str_pad_left).':'.@str_pad($minutos_decimal, 2, 0, str_pad_left);
			$minutos_finais  = $minutos_decimal + ($explode[0] * 60);

		} else {

			$horas_finais   = @str_pad($iHorasTrabalhadas,2,0,str_pad_left).":00";
			$minutos_finais = $iHorasTrabalhadas * 60;

		}

		$minutos_finais = $minutos_finais < 0 ? $minutos_finais * (-1) : $minutos_finais;
		return $minutos_finais;
	*/

}

/*
 * @author Tony Farney B. M. Ribeiro
 * @descr Função que verifica se algum dos agendamentos passados no array $aAgendamentos está alocado
          para o horário da grade indicado nos parâmetros iIdGrade, $sTipoGrade e $iIdFicha
 * @param int $iIdGrade -> id da grade (sd30_i_codigo)
 * @param string $sTipoGrade -> tipo da grade (sd30_c_tipograde)
 * @param int $iNumFicha -> numero da ficha
 * @param array $aAgendamentos -> vetor com as informacoes dos agendamentos.
                                  Nem todas as informações são necessárias
 * @return iIdAgendamento -> indice do vetor de agendamentos que está alocado para o horário da grade
                             ou -1 em caso de nenhum agendamento estar alocado
 */
function verificaAgendamentoHorarioByArray($iIdGrade, $sTipoGrade, $iNumFicha, $aAgendamentos) {

  if ($sTipoGrade == 'I') { // Pra grade do tipo intervalo, basta verificar se o número da ficha é igual

    foreach ($aAgendamentos as $iId => $oAgendamento) {

      if ($oAgendamento->sd23_i_ficha == $iNumFicha) {
        return $iId;
      }

    }

  } else { // Pra grade do tipo período, basta verificar se o código da grade é o mesmo

    foreach ($aAgendamentos as $iId => $oAgendamento) {

      if ($oAgendamento->sd23_i_undmedhor == $iIdGrade) {
        return $iId;
      }

    }

  }

  // Nenhum agendamento está alocado para o horário da grade
  return -1;

}

/*
 * @author Tony Farney B. M. Ribeiro
 * @descr Função que verifica se existe algum dos agendamento para o horario
          de $sHoraIni a $sHoraFim nas datas de $dIni a $dFim para a especmedico $iEspecMed
 * @param string $sHoraIni Horário de início para verificar (formato HH:MM)
 * @param string $sHoraFim Horário de fim para verificar (formato HH:MM)
 * @param string $sHoraIni Data de início para verificar (formato YYYY-MM-DD)
 * @param string $sHoraIni Data de fim para verificar (formato YYYY-MM-DD)
 * @param int $iEspecMedico Codigo da tabela especmedico
 * @return boolean Indica se existe ou não horário marcado para o horário indicado no período indicado
 */
function verificaAgendamentoHorario($sHoraIni, $sHoraFim, $dIni, $dFim, $iEspecMed) {

  $oDaoAgendamentos = db_utils::getdao('agendamentos_ext');
  $oDaoAusencias    = db_utils::getdao('ausencias');

  $sCampos          = ' sd30_i_codigo, sd30_c_tipograde, sd30_c_horaini, sd30_c_horafim, ';
  $sCampos         .= ' sd30_i_fichas, sd30_i_reservas, sd30_i_diasemana, sd23_d_consulta ';

  $sCamposPeriodo   = $sCampos.', count(*) as numagendamentos ';

  $sCamposIntervalo = $sCampos.', sd23_i_ficha ';

  $sWhere           = 'sd27_i_codigo = '.$iEspecMed;
  $sWhere          .= " and sd23_d_consulta between '$dIni' and '$dFim' ";

  $sWherePeriodo    = "$sWhere and sd30_c_tipograde = 'P' and s114_i_agendaconsulta is null group by $sCampos";

  $sWhereIntervalo  = "$sWhere and sd30_c_tipograde = 'I' ";

  $sSql             = $oDaoAgendamentos->sql_query_ext(null, $sCamposPeriodo, '', $sWherePeriodo, false);
  $rsPeriodo        = $oDaoAgendamentos->sql_record($sSql);
  $aGradesPeriodo   = db_utils::getCollectionByRecord($rsPeriodo);

  $sSql             = $oDaoAgendamentos->sql_query_ext(null, $sCamposIntervalo, '', $sWhereIntervalo);
  $rsIntervalo      = $oDaoAgendamentos->sql_record($sSql);
  $aGradesIntervalo = db_utils::getCollectionByRecord($rsIntervalo);

  $iTam             = count($aGradesPeriodo);
  if ($iTam > 0) {

    for ($iCont = 0; $iCont < $iTam; $iCont++) {

      $sWhere  = ' ausencias.sd06_i_undmedhorario is null ';
      $sWhere .= ' and ausencias.sd06_i_especmed = '.$iEspecMed;
      $sWhere .= " and '".$aGradesPeriodo[$iCont]->sd23_d_consulta;
      $sWhere .= "' between ausencias.sd06_d_inicio and ausencias.sd06_d_fim ";
      $sWhere .= " and (ausencias.sd06_c_horainicio between '".$aGradesPeriodo[$iCont]->sd30_c_horaini;
      $sWhere .= "' and '".$aGradesPeriodo[$iCont]->sd30_c_horafim."' ";
      $sWhere .= "      or ausencias.sd06_c_horafim between '".$aGradesPeriodo[$iCont]->sd30_c_horaini;
      $sWhere .= "' and '".$aGradesPeriodo[$iCont]->sd30_c_horafim."' ";
      $sWhere .= "      or (ausencias.sd06_c_horainicio <= '".$aGradesPeriodo[$iCont]->sd30_c_horaini;
      $sWhere .= "' and ausencias.sd06_c_horainicio >= '".$aGradesPeriodo[$iCont]->sd30_c_horafim."')) ";
      $sSql    = $oDaoAusencias->sql_query_especmedico(null, '*', 'sd06_c_horainicio', $sWhere);
      $rs      = $oDaoAusencias->sql_record($sSql);

      $iMinTrab           = diferencaEmMinutos($aGradesPeriodo[$iCont]->sd30_c_horaini,
        $aGradesPeriodo[$iCont]->sd30_c_horafim
      );
      $iNumFichas         = $aGradesPeriodo[$iCont]->sd30_i_fichas + $aGradesPeriodo[$iCont]->sd30_i_reservas;
      $iIntervalo         = number_format(($iMinTrab / $iNumFichas), 2, '.', '');

      /* Horário final que já estaria agendado (mas como pode haver ausencias neste período,
         os agendamentos podem acabar sendo movidos para horários mais adiante */
      $sHoraFimJaAgendado = somaMinutosHora($aGradesPeriodo[$iCont]->sd30_c_horaini,
        $iIntervalo * $aGradesPeriodo[$iCont]->numagendamentos
      );

      for ($iCont2 = 0; $iCont2 < $oDaoAusencias->numrows; $iCont2++) {

        $oAusencia = db_utils::fieldsmemory($rs, $iCont2);

        // Se a ausencia começar no período já agendado, tenho que aumentar o tempo de ausência ao período já ocupado
        if ($oAusencia->sd06_c_horainicio >= $aGradesPeriodo[$iCont]->sd30_c_horaini
          && $oAusencia->sd06_c_horainicio <= $sHoraFimJaAgendado) {

          $sHoraFimJaAgendado = somaMinutosHora($sHoraFimJaAgendado,
            diferencaEmMinutos($oAusencia->sd06_c_horainicio,
              $oAusencia->sd06_c_horafim
            )
          );

          /* Se a ausência terminar no período já agendado, mas começar antes dele,
						 tenho que aumentar o tempo do inicio da grade até o fim da ausência ao período já ocupado */
        } elseif ($oAusencia->sd06_c_horafim >= $aGradesPeriodo[$iCont]->sd30_c_horaini
          && $oAusencia->sd06_c_horafim <= $sHoraFimJaAgendado) {
          $sHoraFimJaAgendado = somaMinutosHora($sHoraFimJaAgendado,
            diferencaEmMinutos($aGradesPeriodo[$iCont]->sd30_c_horaini,
              $oAusencia->sd06_c_horafim
            )
          );
        }

      }

      // Se tiver mais de uma ausência, somo um para compensar a imprecisão
      if ($oDaoAusencias->numrows > 1) {
        $sHoraFimJaAgendado = somaMinutosHora($sHoraFimJaAgendado, 1);
      }
      /*
				 die ("($sHoraIni >= {$aGradesPeriodo[$iCont]->sd30_c_horaini}
								&& $sHoraIni <= $sHoraFimJaAgendado)
								|| ($sHoraFim <= $sHoraFimJaAgendado
								&& $sHoraFim >= {$aGradesPeriodo[$iCont]->sd30_c_horaini})
								|| ($sHoraIni <= {$aGradesPeriodo[$iCont]->sd30_c_horaini}
								&& $sHoraFim >= $sHoraFimJaAgendado)");*/
      if (($sHoraIni >= $aGradesPeriodo[$iCont]->sd30_c_horaini
          && $sHoraIni <= $sHoraFimJaAgendado)
        || ($sHoraFim <= $sHoraFimJaAgendado
          && $sHoraFim >= $aGradesPeriodo[$iCont]->sd30_c_horaini)
        || ($sHoraIni <= $aGradesPeriodo[$iCont]->sd30_c_horaini
          && $sHoraFim >= $sHoraFimJaAgendado)) {

        return true;

      }

    }

  }

  $iTam = count($aGradesIntervalo);
  // Ainda não encontrou nenhuma interseção entre o horário da ausência e o dos agendamentos
  if ($iTam > 0) {

    for ($iCont = 0; $iCont < $iTam; $iCont++) {

      $iMinTrab      = diferencaEmMinutos($aGradesIntervalo[$iCont]->sd30_c_horaini,
        $aGradesIntervalo[$iCont]->sd30_c_horafim
      );
      $iNumFichas    = $aGradesIntervalo[$iCont]->sd30_i_fichas + $aGradesIntervalo[$iCont]->sd30_i_reservas;
      $iIntervalo    = number_format(($iMinTrab / $iNumFichas), 2, '.', '');

      // Horário de início da ficha
      $sHoraIniFicha = somaMinutosHora($aGradesIntervalo[$iCont]->sd30_c_horaini,
        ($iIntervalo * ($aGradesIntervalo[$iCont]->sd23_i_ficha - 1)) + 1
      );
      // Horário de fim da ficha
      $sHoraFimFicha = somaMinutosHora($aGradesIntervalo[$iCont]->sd30_c_horaini,
        $iIntervalo * $aGradesIntervalo[$iCont]->sd23_i_ficha
      );
      if (($sHoraIni >= $sHoraIniFicha && $sHoraIni <= $sHoraFimFicha)
        || ($sHoraFim <= $sHoraFimFicha && $sHoraFim >= $sHoraIniFicha)
        || ($sHoraIni <= $sHoraIniFicha && $sHoraFim >= $sHoraFimFicha)) {

        return true;

      }

    }

  }

  return false;

}

/*
 * @author Tony Farney B. M. Ribeiro
 * @descr Função que calcula o IMC (Índice de Massa Corporal)
 * @param float $nPeso Peso em quilos
 * @param int $iAltura Altura em centímetros
 * @return array $aRetorono Primeira posição: float IMC; Segunda posição: Descrição do IMC segundo tabela.
    Retorna vazio se o cálculo não for possível de ser realizado.
 */
function calculaIMC($nPeso, $iAltura) {

  if ((int)$iAltura == 0 || empty($iAltura) || empty($nPeso)) {
    return '';
  }

  $nImc = $nPeso / (($iAltura * $iAltura) / 10000);

  $aRetorno    = array();
  $aRetorno[0] = $nImc;
  if ($nImc < 18.5) {
    $aRetorno[1] = 'ABAIXO DO PESO';
  } else if ($nImc < 25.0) {
    $aRetorno[1] = 'PESO NORMAL';
  } else if ($nImc < 30.0) {
    $aRetorno[1] = 'ACIMA DO PESO';
  } else {
    $aRetorno[1] = 'MUITO ACIMA DO PESO';
  }

  return $aRetorno;

}

/*
 * @author Tony Farney B. M. Ribeiro
 * @descr Função que gera o select dos modelos de FAA disponíveis.
 * @param int $iModeloSelecionar seleciona o modelo que for indicado
 * @return void
 */
function selectModelosFaa($iModeloSelecionar = null) {

  if (!empty($iModeloSelecionar)) {
    $GLOBALS['s103_i_modelofaa'] = $iModeloSelecionar;
  }
  $aX = array('1' => 'Modelo 1 Padrão',
    '2' => 'Modelo 2 Continuada',
    '3' => 'Modelo 3 ',
    '4' => 'Modelo 4 ',
    '5' => 'Modelo 1 Com 1 via',
    '6' => 'Modelo TXT - Alegrete',
    '7' => 'Modelo TXT - Bagé'
  );

  $aY = array('1' => 'sau2_emitirfaa002.php',
    '2' => 'sau2_emitirfaa003.php',
    '3' => 'sau2_fichaatend005.php',
    '4' => 'sau2_fichaatend006.php',
    '5' => 'sau2_emitirfaa004.php',
    '6' => 'sau2_emitirfaa005.php',
    '7' => 'sau2_emitirfaa006.php'
  );

  db_select('s103_i_modelofaa', $aX, true, 1);
  db_select('sArquivoFaa', $aY, true, 1, 'style="display: none;"');

}

/*
 * @author Adriano Quilião de Oliveira
 * @descr Função que retorna a quantidade de cotas para uma UPS solicitante e se o controle é feito por cotas ou não
 * @param int $iUpssolicitante    Unidade portadora das cotas
 * @param int $iUpsprstadora      unidade que está fornecendo as cotas
 * @param int $sRh70_estrutural   Código estrutural da especialidade
 * @param int $iAnocomp           Quando informado pega os registros apenas daquele ano
 * @param int $iMescomp           Quando informado pega os registros apenas daquele mes
 * @param int $iUndmedhorario     Código referente ao dia da grade de horário
 * @param int $iMedico            Médico a ter as cotas validadas
 * @return Array com dados das cotas do agendamento, quantidade em cotas, e uma mensagem.
 */

function getCotasAgendamento(
  $iUpssolicitante, $iUpsprestadora = null, $sRh70_estrutural = null, $iAnocomp = null,
  $iMescomp = null, $iUndmedhorario = 0, $iMedico = null
) {

  $oDaoCotasAgendamento = new cl_sau_cotasagendamento();
  $sCampos              = ' s163_i_codigo, s163_i_quantidade, s163_i_upsprestadora, rh70_estrutural, rh70_descr ';

  if ($iUndmedhorario != 0) {

    $sCampos             .= ' ,(select s164_quantidade from sau_cotasagendamentoprofissional ';
    $sCampos             .= '  inner join especmedico as x   on x.sd27_i_codigo = s164_especmedico ';
    $sCampos             .= '  inner join undmedhorario as y on y.sd30_i_undmed = x.sd27_i_codigo ';
    $sCampos             .= '  where s164_cotaagendamento = s163_i_codigo and y.sd30_i_codigo = '.$iUndmedhorario.' ';
    $sCampos             .= 'limit 1) as saldo_medico ';
  }

  $sWhere  = " s163_i_upssolicitante = '$iUpssolicitante' ";
  $sWhere .= isset($sRh70_estrutural) && !empty($sRh70_estrutural) ? " AND rh70_estrutural =  '$sRh70_estrutural' " : "";
  $sWhere .= isset($iAnocomp) && !empty($iAnocomp) ? " AND s163_i_anocomp =  '$iAnocomp'" : "";
  $sWhere .= isset($iMescomp) && !empty($iMescomp) ? " AND s163_i_mescomp =  '$iMescomp' " : "";
  $sWhere .= isset($iUpsprestadora) && !empty($iUpsprestadora) ? " AND s163_i_upsprestadora = '$iUpsprestadora'" : "";

  if( !is_null( $iMedico ) ) {
    $sWhere .= " AND sd04_i_medico = {$iMedico}";
  }

  $sSql = $oDaoCotasAgendamento->sql_query_cotas(null, $sCampos, null, $sWhere);
  $rs   = db_query( $sSql );

  $oResult                    = new stdClass();
  $oResult->lStatus           = 0;
  $oResult->sMensagem         = "Não encontrado o controle por cotas para a UPS solicitante.";
  $oResult->aCotasAgendamento = null;

  if( is_resource( $rs ) && pg_num_rows( $rs ) > 0 ) {

    $oResult->lStatus           = 1;
    $oResult->sMensagem         = "Agendamento realizado por cotas.";
    $oResult->aCotasAgendamento = db_utils::getCollectionByRecord($rs, false, false, true);
  }

  return $oResult;

}
/**
 * Soma os valores dos elementos do vetor em cada posição
 * @param array $a1 , array $a2 [, array $...]
 * @return array
 * @example $aX = array(1); $aY = array(2,2); $aR = arraySumValues($aX,$aY); //Resultado X = [0]=> 3 [1]=> 2
 */
function arraySumValues() {

  $aReturn = array();
  $iArgs   = func_num_args();
  $aArgs   = func_get_args();
  if ($iArgs < 1) {
    trigger_error('Warning: Wrong parameter count for arraySumValues()', E_USER_WARNING);
  }
  foreach ($aArgs as $aItem) {

    if (!is_array($aItem)) {
      trigger_error('Warning: Wrong parameter values for arraySumValues()', E_USER_WARNING);
    }
    foreach ($aItem as $iK => $iV) {

      if (!isset($aReturn[$iK])) {
        $aReturn[$iK] = 0;
      }
      $aReturn[$iK] += $iV;

    }

  }
  return $aReturn;

}

/**
 * Função que verifica a quantidade de matriculados em uma determinada turma.
 *
 * @param Integer $iTurma         -> Código da turma que se deseja verificar
 * @param Integer $iEscola        -> Código da escola da turma, caso seja null busca-se o departamento logado
 * @param Integer $iTipoRetorno   -> Caso o tipo seja 1 -> Retorna TRUE ou FALSE (Turma tem ou não vagas disponíveis)
 *                                -> Caso o tipo seja 2 -> Retorna a quantidade de alunos matriculados na turma
 *
 * if tipoRetorno == 1
 * @return Boolean TRUE           -> Caso a turma possua vagas para matricular alunos
 *                 FALSE          -> Caso a turma não possua mais vagas para matrícula
 * if tipoRetorno == 2 (Obs.: Usado quando existe chave_pesquisa)
 * @return Integer $iMatriculados -> Número de alunos matrículados na turma
 *
 * @author Thiago A. de Lima (thiago.lima@dbseller.com.br)
 */
function verificaMatriculados($iTurma, $iEscola = null, $iTipoRetorno = 1) {

  if ($iEscola == null) {
    $iEscola = db_getsession('DB_coddepto');
  }

  /* Busco a quantidade de vagas totais da turma */
  $oDaoTurma    = db_utils::getdao('turma');
  $sWhereTurma  = " ed57_i_codigo = ".$iTurma;
  $sWhereTurma .= " AND ed57_i_escola = ".$iEscola;
  $sSqlTurma    = $oDaoTurma->sql_query_file("", "*", "", $sWhereTurma);
  $rsTurma      = $oDaoTurma->sql_record($sSqlTurma);
  $iLinhasTurma = $oDaoTurma->numrows;

  if ($iLinhasTurma > 0) {

    $oDadosTurma   = db_utils::fieldsmemory($rsTurma, 0);

    /* Busco a quantidade de alunos que já estão matrículados na turma */
    $oDaoMatricula = db_utils::getdao('matricula');
    $sCamposMat    = " COUNT(*) AS nummatriculas ";
    $sWhereMat     = " ed60_i_turma = ".$iTurma;
    $sWhereMat    .= " AND ed60_c_situacao = 'MATRICULADO' ";
    $sSqlMatricula = $oDaoMatricula->sql_query_file("", $sCamposMat, "", $sWhereMat);
    $rsMatricula   = $oDaoMatricula->sql_record($sSqlMatricula);
    $iNumMat       = db_utils::fieldsmemory($rsMatricula, 0)->nummatriculas;

    $oDaoMatDepend = db_utils::getdao('matriculadependencia');
    $sCamposMatDep = " COUNT(*) AS nummatriculasdepend ";
    $sWhereMatDep  = " ed297_turma = ".$iTurma;
    $sSqlMatDepend = $oDaoMatDepend->sql_query_file("", $sCamposMatDep, "", $sWhereMatDep);
    $rsMatDepend   = $oDaoMatDepend->sql_record($sSqlMatDepend);
    $iNumMatDepend = db_utils::fieldsmemory($rsMatDepend, 0)->nummatriculasdepend;

    $iMatriculados = $iNumMat + $iNumMatDepend;

    if ($iTipoRetorno == 1) {

      if ($iMatriculados < $oDadosTurma->ed57_i_numvagas) {
        return true;
      } else {
        return false;
      }

    } elseif ($iTipoRetorno == 2) {

      return $iMatriculados;

    }

  } else {

    db_msgbox("Não foi possível localizar a turma informada.");
    return false;

  }

}

/**
 * Realiza a troca de turma de um aluno.
 * cria uma nova matricula para a matricula antiga
 * @param      $iSeqMatricula
 * @param      $iCodigoTurmaDestino
 * @param bool $lAproveitamento
 * @param null $iMatriculaOrigem
 * @param null $sTurno
 * @return bool
 * @throws Exception
 */
function trocaTurma($iSeqMatricula, $iCodigoTurmaDestino, $lAproveitamento = false, $iMatriculaOrigem = null, $sTurno = null, $lValidaEtapataOrigem = true) {

  if (!db_utils::inTransaction()) {
    throw new Exception('Não existe transação ativa com o banco de dados.');
  }

  /**
   * Verificamos se a turma de destino existe
   */
  $oDaoTurma     = new cl_turma();
  $sCamposBusca  = " distinct ed11_i_codigo, turma.*,";
  $sCamposBusca .=" (select max(ed60_i_numaluno) from matricula where ed60_i_turma = turma.ed57_i_codigo) as ultimo_numero";
  $sWhereBusca   = "     ed57_i_codigo = {$iCodigoTurmaDestino}";

  $sSqlSerieTurmaDestino = $oDaoTurma->sql_query_turmaserie(null, $sCamposBusca, null, $sWhereBusca);

  $rsSerieTurma = $oDaoTurma->sql_record($sSqlSerieTurmaDestino);
  if ($oDaoTurma->numrows == 0) {
    throw new Exception("Turma de destino sem séries vinculadas");
  }

  /**
   * Cancelamos o diario de classe da turma atual (matricula)
   */
  $oDaoMatricula      = new cl_matricula();
  $sCampos            = "ed60_i_turma, ed60_i_aluno, ed60_d_datamatricula ";
  $sWhere             = "ed60_i_codigo = {$iSeqMatricula}";
  $sSqlMatriculaAtual = $oDaoMatricula->sql_query_file(null, "*", null, $sWhere);
  $rsMatriculaAtual   = $oDaoMatricula->sql_record($sSqlMatriculaAtual);

  if ($oDaoMatricula->numrows == 0) {
    throw new Exception("Matricula {$iSeqMatricula} inválida.");
  }

  /**
   * Verificar quais diarios de classe o aluno possui.
   */
  $oMatriculaAtual = db_utils::fieldsMemory($rsMatriculaAtual, 0);

  if ($oMatriculaAtual->ed60_i_turma == $iCodigoTurmaDestino) {
    throw new Exception("Turma de destino não pode ser a mesma de origem.");
  }

  $oDadosTurma = db_utils::fieldsMemory($rsSerieTurma, 0);

  /**
   * Criar nova matricula.
   */
  $oDaoMatricula->ed60_c_ativa         = 'S';
  $oDaoMatricula->ed60_c_concluida     = 'N';
  $oDaoMatricula->ed60_c_parecer       = $oMatriculaAtual->ed60_c_parecer;
  $oDaoMatricula->ed60_c_rfanterior    = $oMatriculaAtual->ed60_c_rfanterior;
  $oDaoMatricula->ed60_c_tipo          = $oMatriculaAtual->ed60_c_tipo;
  $oDaoMatricula->ed60_d_datamatricula = $oMatriculaAtual->ed60_d_datamatricula;
  $oDaoMatricula->ed60_d_datamodif     = date("Y-m-d", db_getsession("DB_datausu"));
  $oDaoMatricula->ed60_d_datamodifant  = date("Y-m-d", db_getsession("DB_datausu"));
  $oDaoMatricula->ed60_i_aluno         = $oMatriculaAtual->ed60_i_aluno;
  $oDaoMatricula->ed60_i_turma         = $iCodigoTurmaDestino;
  $oDaoMatricula->ed60_i_turmaant      = $oMatriculaAtual->ed60_i_turma;
  $oDaoMatricula->ed60_c_situacao      = "MATRICULADO";
  $oDaoMatricula->ed60_matricula       = $iMatriculaOrigem;

  if ($oDadosTurma->ultimo_numero != '') {
    $oDaoMatricula->ed60_i_numaluno = $oDadosTurma->ultimo_numero + 1;
  }

  $oDaoMatricula->incluir(null);
  if ($oDaoMatricula->erro_status == 0) {

    throw new Exception("Erro ao trocar a turma do aluno.\n{$oDaoMatricula->erro_msg}");
  }
  $iTotalSeriesTurma     = $oDaoTurma->numrows;
  $oDaoMatriculaSerie    = new cl_matriculaserie();
  $iTotalMatriculasTurma = 0;

  $iSerieDestino = null;

  /**
   * Alterado lógica que identifica a etapa de origem para validar se etapa da turma de destino = etapa turma de origem
   * Como essa função é chamada somente quando ensino turma origem = ensino turma de destino o código das etapas serão iguais
   */
  $iCodigoEtapaOrigem = null;
  if ( !empty($iMatriculaOrigem) ) {

    $oMatriculaOrigem   = MatriculaRepository::getMatriculaByCodigo($iMatriculaOrigem);
    $iCodigoEtapaOrigem = $oMatriculaOrigem->getEtapaDeOrigem()->getCodigo();
  }

  for ($i = 0; $i < $iTotalSeriesTurma; $i++) {

    $oDadosSerie = db_utils::fieldsMemory($rsSerieTurma, $i);
    $sOrigem     = "N";
    if ($iCodigoEtapaOrigem == $oDadosSerie->ed11_i_codigo ) {
      $sOrigem = 'S';
    }

    /**
     * Lógica aplicada quando função chamada da rotina de Alterar Situação da Matricula e selecionado a
     * opção: MATRICULA INDEVIDA é possível selecionar uma turma de qualquer etapa do ensino (maior ou menor que etapa atual)
     *  sendo assim a Etapa de origem pode ser diferente da turma de destino.
     */
    if (!$lValidaEtapataOrigem) {

      $sOrigem = 'S';
      if ($i > 0) {
        $sOrigem = 'N';
      }
    }

    $iSerieDestino                         = $oDadosSerie->ed11_i_codigo;
    $oDaoMatriculaSerie->ed221_c_origem    = $sOrigem;
    $oDaoMatriculaSerie->ed221_i_matricula = $oDaoMatricula->ed60_i_codigo;
    $oDaoMatriculaSerie->ed221_i_serie     = $oDadosSerie->ed11_i_codigo;
    $oDaoMatriculaSerie->incluir(null);
  }

  /**
   * Atualizados o total de alunos na turma
   * devemos atualizar todos os dados da turma, para garantir que não havera
   * informações incorretas, pois pode haver variaves com o nome do campo no escopo global.
   * @todo Remover campos desnecessarios
   */
  $oDaoTurma->ed57_c_descr             = $oDadosTurma->ed57_c_descr;
  $oDaoTurma->ed57_c_medfreq           = $oDadosTurma->ed57_c_medfreq;
  $oDaoTurma->ed57_i_ativqtd           = $oDadosTurma->ed57_i_ativqtd;
  $oDaoTurma->ed57_i_base              = $oDadosTurma->ed57_i_base;
  $oDaoTurma->ed57_i_calendario        = $oDadosTurma->ed57_i_calendario;
  $oDaoTurma->ed57_i_censocursoprofiss = $oDadosTurma->ed57_i_censocursoprofiss;
  $oDaoTurma->ed57_i_censoetapa        = $oDadosTurma->ed57_i_censoetapa;
  $oDaoTurma->ed57_i_codigoinep        = $oDadosTurma->ed57_i_codigoinep;
  $oDaoTurma->ed57_i_escola            = $oDadosTurma->ed57_i_escola;
  $oDaoTurma->ed57_i_sala              = $oDadosTurma->ed57_i_sala;
  $oDaoTurma->ed57_i_tipoatend         = $oDadosTurma->ed57_i_tipoatend;
  $oDaoTurma->ed57_i_tipoturma         = $oDadosTurma->ed57_i_tipoturma;
  $oDaoTurma->ed57_i_turno             = $oDadosTurma->ed57_i_turno;
  $oDaoTurma->ed57_t_obs               = $oDadosTurma->ed57_t_obs;
  $oDaoTurma->ed57_i_codigo            = $iCodigoTurmaDestino;
  $oDaoTurma->alterar($iCodigoTurmaDestino);
  if ($oDaoTurma->erro_status == 0) {

    throw new Exception('Erro ao atualizar numero de matriculas na turma');
  }

  $oDaoDiarioClasse = new cl_diario();

  /**
   * Migrar os aproveitamentos;
   */
  $sWhere               .= "and ed95_i_aluno = {$oMatriculaAtual->ed60_i_aluno} ";
  $sWhere               .= "and ed59_i_turma = {$oMatriculaAtual->ed60_i_turma} ";
  $sSqlDiarioClasseAluno = $oDaoDiarioClasse->sql_query_diario_classe(null, "diario.*", null, $sWhere);

  $rsDiarioClasseAluno   = $oDaoDiarioClasse->sql_record($sSqlDiarioClasseAluno);
  $iTotalDiarios         = $oDaoDiarioClasse->numrows;
  for ($iDiario = 0; $iDiario < $iTotalDiarios; $iDiario++) {

    /**
     * Encerramos  os diarios da matricula atual.
     */
    $oDadosDiario                       = db_utils::fieldsMemory($rsDiarioClasseAluno, $iDiario);
    $oDaoDiarioClasse->ed95_i_codigo    = $oDadosDiario->ed95_i_codigo;
    $oDaoDiarioClasse->ed95_c_encerrado = "S";
    $oDaoDiarioClasse->alterar($oDadosDiario->ed95_i_codigo);
    if ($oDaoDiarioClasse->erro_status == 0) {
      throw new Exception("Erro ao cancelar diarios de avaliação do aluno\n{$oDaoDiarioClasse->erro_msg}");
    }
  }

  if( empty($sTurno) ) {
    throw new BusinessException('Nenhum turno foi selecionado.');
  }

  if ( !empty( $sTurno ) ) {

    /**
     * Busca os registros da turma na tabela turmaturnoreferente para inserir a matrícula em matriculaturnoreferente
     */
    $oDaoTurmaTurnoReferente     = new cl_turmaturnoreferente();
    $oDaoMatriculaTurnoReferente = new cl_matriculaturnoreferente();
    $sWhereTurmaTurnoReferente   = "ed336_turma = {$iCodigoTurmaDestino} AND ed336_turnoreferente in ( {$sTurno} )";
    $sSqlTurmaTurnoReferente     = $oDaoTurmaTurnoReferente->sql_query_file(
      null,
      "ed336_codigo",
      null,
      $sWhereTurmaTurnoReferente
    );
    $rsTurmaTurnoReferente     = db_query( $sSqlTurmaTurnoReferente );
    $iTotalTurmaTurnoReferente = pg_num_rows( $rsTurmaTurnoReferente );

    for( $iContador = 0; $iContador < $iTotalTurmaTurnoReferente; $iContador++ ) {

      $iCodigoTurmaTurnoReferente = db_utils::fieldsMemory( $rsTurmaTurnoReferente, $iContador )->ed336_codigo;
      $oDaoMatriculaTurnoReferente->ed337_matricula           = $oDaoMatricula->ed60_i_codigo;
      $oDaoMatriculaTurnoReferente->ed337_turmaturnoreferente = $iCodigoTurmaTurnoReferente;
      $oDaoMatriculaTurnoReferente->incluir( null );

      if ($oDaoMatriculaTurnoReferente->erro_status == 0) {
        throw new Exception("Erro ao incluir registro na tabela matriculaturnoreferente:\n{$oDaoMatriculaTurnoReferente->erro_msg}");
      }
    }
  }

  return true;
}

/**
 * Verifica se aluno foi Aprovado pelo Conselho de classe na disciplina e
 * caso selecionado para substituir a nota do aluno, altera a nota final do aluno.
 * @param DiarioAvaliacaoDisciplina $oDiarioDisciplina
 * @return null
 */
function verificaNotaFinalAprovadoConselho(DiarioAvaliacaoDisciplina $oDiarioDisciplina) {

  $oResultadoFinal = $oDiarioDisciplina->getResultadoFinal();

  if ($oResultadoFinal) {

    $oAprovadoConselho = $oResultadoFinal->getFormaAprovacaoConselho();
    if ($oAprovadoConselho &&
      $oResultadoFinal->getFormaAprovacaoConselho()->getFormaAprovacao() == 1 &&
      $oResultadoFinal->getFormaAprovacaoConselho()->getAlterarNotaFinal() == 2)  {

      return $oResultadoFinal->getFormaAprovacaoConselho()->getAvaliacaoConselho();

    }
  }
  return null;
}

/**
 * Verifica qual é a forma de calculo de frequencia. Se for globalizada, então verifica se há ao menos uma disciplina
 * com reclassificação por baixa frequência, se não valida por disciplina.
 * @param  DiarioAvaliacaoDisciplina $oDisciplinasDiario
 * @return boolean
 */
function verificaReclassificadoBaixaFrequencia( $oDisciplinasDiario ) {

  $lReclassificadoBaixaFrequencia = false;

  $oDiario       = $oDisciplinasDiario->getDiario();
  $iFormaCalculo = $oDiario->getProcedimentoDeAvaliacao()->getFormaCalculoFrequencia();

  if ( $iFormaCalculo == 1) {
    $lReclassificadoBaixaFrequencia = $oDisciplinasDiario->reclassificadoPorBaixaFrequencia();
  } else {
    $lReclassificadoBaixaFrequencia = $oDiario->reclassificadoPorBaixaFrequencia();
  }

  return $lReclassificadoBaixaFrequencia;
}
?>
