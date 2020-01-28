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

//MODULO: Escola
include(modification("dbforms/db_classesgenericas.php"));

$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clturmaacmatricula->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed268_i_numvagas");
$clrotulo->label("ed268_i_nummatr");
$clrotulo->label("ed269_aluno");
$clrotulo->label("ed268_c_descr");
$clrotulo->label("ed47_v_nome");

$data_valor  = "";
$dataEntrada = "";

if( isset( $ed269_d_data ) && !empty( $ed269_d_data ) ) {

  $ed269_d_data_dia = substr( $ed269_d_data, 0, 2 );
  $ed269_d_data_mes = substr( $ed269_d_data, 3, 2 );
  $ed269_d_data_ano = substr( $ed269_d_data, 6, 4 );

  $data_valor = $ed269_d_data_ano .'-'. $ed269_d_data_mes .'-'. $ed269_d_data_dia;

  $fano = substr( $data_valor, 0, 4 );
  $fmes = substr( $data_valor, 5, -3 );
  $fdia = substr( $data_valor, 8, 9 );

  $dataEntrada = $fano . '-' . $fmes . '-' . $fdia;
}

$ano =  date('Y');
$mes =  5;

for( $i = 31; $i >= 20; $i-- ) {

  $diasemana = date("w", mktime(0,0,0,$mes,$i,$ano) );

  switch($diasemana) {

    case "0": $diasemana = "Domingo";       break;
    case "1": $diasemana = "Segunda-Feira"; break;
    case "2": $diasemana = "Terça-Feira";   break;
    case "3": $diasemana = "Quarta-Feira";  break;
    case "4": $diasemana = "Quinta-Feira";  break;
    case "5": $diasemana = "Sexta-Feira";   break;
    case "6": $diasemana = "Sábado";        break;
  }

  if($diasemana == 'Quarta-Feira') {

    $dataCenso =  $ano.'-'.'0'.$mes.'-'.$i;
    break;
  }
}

$db_botao1 = false;

if( isset( $opcao ) && $opcao == "alterar" ) {

  $ed269_d_data_dia = substr( $ed269_d_data, 0, 2 );
  $ed269_d_data_mes = substr( $ed269_d_data, 3, 2 );
  $ed269_d_data_ano = substr( $ed269_d_data, 6, 4 );

  echo $ed269_d_data_dia;

  $db_opcao  = 2;
  $db_botao1 = true;
} else if( isset($opcao) && $opcao == "excluir" || isset($db_opcao) && $db_opcao == 3 ) {

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

$no_vagas    = false;
$sSqlTurmaAc = $clturmaac->sql_query_file( "", "ed268_i_numvagas, ed268_i_nummatr", "", "ed268_i_codigo = {$ed269_i_turmaac}" );
$result4     = $clturmaac->sql_record( $sSqlTurmaAc );

if ($clturmaac->numrows > 0) {

  db_fieldsmemory($result4,0);

  if ($ed268_i_nummatr >= $ed268_i_numvagas && $db_opcao == 1) {

   $db_botao = false;
   $no_vagas = true;
  }
}
?>
<form id="form1" name="form1" method="post" action="" class="container">
  <fieldset>
    <table class="form-container">
      <tr>
        <td nowrap title="<?=$Ted269_i_codigo?>">
          <?=$Led269_i_codigo?>
        </td>
        <td>
          <?php
          db_input( 'ed269_i_codigo', 10, $Ied269_i_codigo, true, 'text', 3 );
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=$Ted269_i_turmaac?>">
          <?php
          db_ancora( $Led269_i_turmaac, "", 3 );
          ?>
        </td>
        <td>
          <?php
          db_input( 'ed269_i_turmaac', 10, $Ied269_i_turmaac, true, 'text', 3 );
          db_input( 'ed268_c_descr',   50, $Ied268_c_descr,   true, 'text', 3 );
          ?>
        </td>
      </tr>
      <tr>
        <td>
          <?=$Led268_i_numvagas?>
        </td>
        <td>
          <?php
          db_input( 'ed268_i_numvagas', 10, $Ied268_i_numvagas, true, 'text', 3 );
          echo $Led268_i_nummatr;
          db_input( 'ed268_i_nummatr', 10, $Ied268_i_nummatr, true, 'text', 3 );
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=$Ted269_aluno?>">
          <?php
          db_ancora( "<b>Código do Aluno:</b>", "js_pesquisaed269_i_matricula(true);", $db_opcao);
          ?>
        </td>
        <td>
          <?php
          $sChange = " onchange='js_pesquisaed269_i_matricula(false);'";
          db_input( 'ed269_aluno', 10, $Ied269_aluno, true, 'text', $db_opcao, $sChange );
          db_input( 'ed47_v_nome', 50, $Ied47_v_nome, true, 'text', 3 );
          ?>
        </td>
      </tr>
      <tr>
        <td>
          <?=$Led269_d_data?>
        </td>
        <td>
          <?php
          db_inputdata(
                        'ed269_d_data',
                        $ed269_d_data_dia,
                        $ed269_d_data_mes,
                        $ed269_d_data_ano,
                        true,
                        'text',
                        $db_opcao
                      );
          ?>
        </td>
      </tr>
    </table>
  </fieldset>
  <input name="<?=( $db_opcao == 1 ? "incluir" : ( $db_opcao == 2 || $db_opcao == 22 ? "alterar" : "excluir" ) )?>"
         onclick ="return js_confirm(this);"  
         type="submit"
         id="db_opcao" 
         value="<?=( $db_opcao == 1 ? "Incluir" : ( $db_opcao == 2 || $db_opcao == 22 ? "Alterar" : "Excluir" ) )?>"
         <?=( $db_botao == false ? "disabled" : "" )?> >
  <input name="cancelar" type="submit" value="Cancelar" <?=( $db_botao1 == false ? "disabled" : "" )?> >
  </form>
  <table style="width:100%">
    <tr>
      <td valign="top">
        <?
        $campos1  = "distinct turmaacmatricula.ed269_aluno, turmaacmatricula.ed269_i_codigo";
        $campos1 .= ", turmaacmatricula.ed269_i_turmaac, turmaacmatricula.ed269_d_data, turmaac.ed268_c_descr";
        $campos1 .= ", aluno.ed47_v_nome";

        $chavepri = array(
                          "ed269_aluno"     => $ed269_aluno,
                          "ed269_i_codigo"  => $ed269_i_codigo,
                          "ed269_i_turmaac" => $ed269_i_turmaac,
                          "ed269_d_data"    => $ed269_d_data,
                          "ed268_c_descr"   => $ed268_c_descr,
                          "ed47_v_nome"     => $ed47_v_nome
                        );

        $sWhereTurma = "ed269_i_turmaac = {$ed269_i_turmaac}";

        $cliframe_alterar_excluir->chavepri = $chavepri;
        $cliframe_alterar_excluir->sql      = $clturmaacmatricula->sql_query( "", $campos1, "ed47_v_nome", $sWhereTurma );

        $sCampos = "ed269_aluno, ed269_i_codigo, ed269_i_turmaac, ed269_d_data, ed268_c_descr, ed47_v_nome";

        $cliframe_alterar_excluir->campos        = $sCampos;
        $cliframe_alterar_excluir->legenda       = "Registros";
        $cliframe_alterar_excluir->msg_vazio     = "Não foi encontrado nenhum registro.";
        $cliframe_alterar_excluir->textocabec    = "#DEB887";
        $cliframe_alterar_excluir->textocorpo    = "#444444";
        $cliframe_alterar_excluir->fundocabec    = "#444444";
        $cliframe_alterar_excluir->fundocorpo    = "#eaeaea";
        $cliframe_alterar_excluir->iframe_height = "200";
        $cliframe_alterar_excluir->iframe_width  = "100%";
        $cliframe_alterar_excluir->tamfontecabec = 9;
        $cliframe_alterar_excluir->tamfontecorpo = 9;
        $cliframe_alterar_excluir->formulario    = false;
        $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
        ?>
      </td>
    </tr>
  </table>
<script>
function js_confirm( form1 ) {

  var data  = document.form1.ed269_d_data_ano.value;
      data += '-' + document.form1.ed269_d_data_mes.value;
      data += '-' + document.form1.ed269_d_data_dia.value;

  var data_venc   = new Date(data);
  var data_limite = new Date("<?=$dataCenso?>"); 

  if(document.form1.ed269_d_data.value == '') {
    
    alert("Preencha a data");
    document.form1.ed269_d_data.focus();
    return false;
  } else {

    if( data_venc.setHours( 0, 0, 0, 0 ) > data_limite.setHours( 0, 0, 0, 0 ) ) {

      var sMensagem  = "Data informada é maior que a data limite do censo. Ao gerar o arquivo do Censo, o sistema não";
          sMensagem += " informará o vínculo do aluno com turma Ativ complementar/AEE. Deseja incluir assim mesmo?";
      var confirma = confirm( sMensagem );

      if( confirma == true ) {
        return true;
      } else {
        return false;
      }
    } else {
      return true;
    }
  }
}

function js_pesquisaed269_i_matricula(mostra) {

  if (mostra) {

    js_OpenJanelaIframe('',
                        'db_iframe_matricula',
                        'func_matriculaturmaac.php?ed268_i_tipoatend=<?=$ed268_i_tipoatend?>'
                                                +'&calendario=<?=$ed268_i_calendario?>'
                                                +'&codigo_turma='+$F('ed269_i_turmaac')
                                                +'&funcao_js=parent.js_mostramatricula1|ed47_i_codigo|ed47_v_nome',
                        'Pesquisa alunos',
                        true);
  } else {

    if(document.form1.ed269_aluno.value != ''){

      js_OpenJanelaIframe('',
                          'db_iframe_matricula',
                          'func_matriculaturmaac.php?ed268_i_tipoatend=<?=$ed268_i_tipoatend?>'
                                                  +'&calendario=<?=$ed268_i_calendario?>'
                                                  +'&codigo_turma='+$F('ed269_i_turmaac')
                                                  +'&pesquisa_chave='+document.form1.ed269_aluno.value
                                                  +'&funcao_js=parent.js_mostramatricula',
                          'Pesquisa alunos',
                          false);
    }else{
      document.form1.ed47_v_nome.value = '';
    }
  }
}

function js_mostramatricula(nome, codigo, situacao, erro, matricula) {

  document.form1.ed47_v_nome.value = nome;
  document.form1.ed269_aluno.value = codigo;
  if (erro) {

    document.form1.ed269_aluno.focus();
    document.form1.ed269_aluno.value = '';
    document.form1.ed269_aluno.value = '';
  }
}
  
function js_mostramatricula1(chave1,chave2, matricula) {

  document.form1.ed269_aluno.value = chave1;
  document.form1.ed269_aluno.value = chave1;
  document.form1.ed47_v_nome.value = chave2;
  db_iframe_matricula.hide();
}
  
<?php
if( $no_vagas == true ) {
?>
  alert("Turma sem vagas disponíveis!");
<?php
}
?>
</script>