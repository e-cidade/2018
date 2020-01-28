<?
/*
 *     E-cidade Software Público para Gestão Municipal                
 *  Copyright (C) 2014  DBseller Serviços de Informática             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa é software livre; você pode redistribuí-lo e/ou     
 *  modificá-lo sob os termos da Licença Pública Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versão 2 da      
 *  Licença como (a seu critério) qualquer versão mais nova.          
 *                                                                    
 *  Este programa e distribuído na expectativa de ser útil, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implícita de              
 *  COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM           
 *  PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Você deve ter recebido uma cópia da Licença Pública Geral GNU     
 *  junto com este programa; se não, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Cópia da licença no diretório licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

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

//MODULO: educação
require_once("dbforms/db_classesgenericas.php");
require_once("libs/db_jsplibwebseller.php");
$cltrocaserie->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed47_i_codigo");
$clrotulo->label("ed57_i_codigo");
$clrotulo->label("ed57_i_codigo");
$clrotulo->label("ed60_d_datamatricula");
?>
<form class="container" name="form1" method="post" action="">
  <fieldset style="width: 1200px">
    <legend>Avanço de Aluno</legend>
    <table class="form-container">
      <tr>
        <td nowrap title="<?=@$Ted101_i_codigo?>">
          <?=@$Led101_i_codigo?>
        </td>
        <td>
          <?php
            db_input( 'ed101_i_codigo', 15, $Ied101_i_codigo, true, 'text',  3 );
            db_input( 'iMatricula',     10, '',               true, 'hidden',3 );
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Ted101_i_aluno?>">
          <?db_ancora( @$Led101_i_aluno, "js_pesquisaed101_i_aluno(true);", $db_opcao );?>
        </td>
        <td>
          <?php
            db_input( 'ed101_i_aluno', 15, $Ied101_i_aluno, true, 'text', $db_opcao," onchange='js_pesquisaed101_i_aluno(false);'" );
            db_input( 'ed47_v_nome',   50, @$Ied47_v_nome,  true, 'text', 3 );
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Ted101_i_turmaorig?>">
          <?db_ancora( @$Led101_i_turmaorig, "", 3 );?>
        </td>
        <td>
          <?php
            db_input( 'ed101_i_turmaorig', 15, @$Ied101_i_turmaorig, true, 'text',   3 );
            db_input( 'ed57_c_descrorig',  30, @$Ied57_c_descrorig,  true, 'text',   3 );
            db_input( 'ed11_c_origem',     30, @$Ied11_c_origem,     true, 'text',   3 );
            db_input( 'ed11_i_codorigem',  30, @$Ied11_i_codorigem,  true, 'hidden', 3 );
          ?>
        </td>
      </tr>
      <tr>
        <td>
          <?=@$Led60_d_datamatricula?>
        </td>
        <td>
          <?php
            db_input( 'ed60_d_datamatricula', 10, @$Ied60_d_datamatricula, true, 'text',   3 );
            db_input( 'ed52_d_inicio',        10, @$Ied52_d_inicio,        true, 'hidden', 3 );
            db_input( 'ed52_d_fim',           10, @$Ied52_d_fim,           true, 'hidden', 3 );
          ?>
        </td>
      </tr>
      <tr id = "linhaTurmaDestino">
        <td nowrap title="<?=@$Ted101_i_turmadest?>">
          <?db_ancora( @$Led101_i_turmadest, "js_pesquisaed101_i_turmadest(true);", $db_opcao );?>
        </td>
        <td>
          <?php
            db_input( 'ed101_i_turmadest', 15, @$Ied101_i_turmadest, true, 'text',   3 );
            db_input( 'ed57_c_descrdest',  30, @$Ied57_c_descrdest,  true, 'text',   3 );
            db_input( 'ed11_c_destino',    30, @$Ied11_c_destino,    true, 'text',   3 );
            db_input( 'ed11_i_coddestino', 30, @$Ied11_i_coddestino, true, 'hidden', 3 );
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Ted101_t_obs?>" colspan="2">
          <fieldset class="separator">
            <legend><?=@$Led101_t_obs?></legend>
            <?db_textarea( 'ed101_t_obs', 4, 70, @$Ied101_t_obs, true, 'text', $db_opcao );?>
          </fieldset>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Ted101_d_data?>">
          <?=@$Led101_d_data?>
        </td>
        <td>
          <?db_inputdata( 'ed101_d_data', @$ed101_d_data_dia, @$ed101_d_data_mes, @$ed101_d_data_ano, true, 'text', $db_opcao );?>
        </td>
      </tr>
    </table>
  </fieldset>
  <div>
    <iframe id="iframeImportacao" name="iframeImportacao" src="" width="100%" frameborder="0" style="display: none;">
    </iframe>
  </div>
<br>
</form>
<script>
var oTurmaTurno                = null;
var aTurnosSelecionados        = new Array();
const CAMINHO_MENSAGENS_AVANCO = 'educacao.escola.db_frmtrocaserieav.';

function js_submit() {

  if ( document.form1.ed101_d_data.value == "" ) {

    alert( _M( CAMINHO_MENSAGENS_AVANCO + "informe_data_avanco" ) );
    document.form1.ed101_d_data.focus();
    document.form1.ed101_d_data.style.backgroundColor = '#99A9AE';
    return false;
  } else {

    datamat = document.form1.ed60_d_datamatricula.value;
    if ( document.form1.ed52_d_inicio.value != "" ) {

      dataav  = document.form1.ed101_d_data_ano.value + "-" + document.form1.ed101_d_data_mes.value + "-" + document.form1.ed101_d_data_dia.value;
      dataini = document.form1.ed52_d_inicio.value;
      datafim = document.form1.ed52_d_fim.value;
      check   = js_validata(dataav, dataini, datafim);
      if (check==false) {

        data_ini = dataini.substr(8, 2) + "/" + dataini.substr(5, 2) + "/" + dataini.substr(0, 4);
        data_fim = datafim.substr(8, 2) + "/" + datafim.substr(5, 2) + "/" + datafim.substr(0, 4);

        var oParametros              = new Object();
            oParametros.sDataInicial = data_ini;
            oParametros.sDataFinal   = data_fim;

        alert( _M( CAMINHO_MENSAGENS_AVANCO + "data_avanco_fora_periodo", oParametros ) );
        document.form1.ed101_d_data.focus();
        document.form1.ed101_d_data.style.backgroundColor = '#99A9AE';
        return false;
      }
    }

    if ( datamat != "" ) {

      datamat = datamat.substr(6, 4) + '' + datamat.substr(3, 2) + '' + datamat.substr(0, 2);
      dataav  = dataav.substr(0, 4) + '' + dataav.substr(5, 2) + '' + dataav.substr(8, 2);

      if (parseInt(datamat)>parseInt(dataav)) {

        alert( _M( CAMINHO_MENSAGENS_AVANCO + "data_avanco_menor" ) );
        document.form1.ed101_d_data.focus();
        document.form1.ed101_d_data.style.backgroundColor = '#99A9AE';
        return false;
      }
    }
  }
  return true;
}

function js_pesquisaed101_i_aluno(mostra) {

  if (mostra == true) {

    js_OpenJanelaIframe(
                         'top.corpo',
                         'db_iframe_aluno',
                         'func_alunoavancoav.php?funcao_js=parent.js_mostraaluno1|ed60_i_aluno|ed47_v_nome'
                                                                                            +'|ed60_i_turma|ed57_c_descr'
                                                                                            +'|ed11_c_descr|ed11_i_codigo'
                                                                                            +'|ed60_d_datamatricula'
                                                                                            +'|ed52_d_inicio|ed52_d_fim'
                                                                                            +'|db_ed60_i_codigo',
                         'Pesquisa',
                         true
                       );
  } else {

    if (document.form1.ed101_i_aluno.value != '') {

      js_OpenJanelaIframe(
                           'top.corpo',
                           'db_iframe_aluno',
                           'func_alunoavancoav.php?pesquisa_chave=' + document.form1.ed101_i_aluno.value
                                                +'&funcao_js=parent.js_mostraaluno',
                           'Pesquisa',
                           false
                         );
    } else {

      document.form1.ed101_i_aluno.value        = '';
      document.form1.ed101_i_turmaorig.value    = '';
      document.form1.ed47_v_nome.value          = '';
      document.form1.ed57_c_descrorig.value     = '';
      document.form1.ed11_c_origem.value        = '';
      document.form1.ed11_i_codorigem.value     = '';
      document.form1.ed60_d_datamatricula.value = '';
      js_limparTurmaDest();
    }
  }
}

function js_mostraaluno(chave1, chave2, chave3, chave4, chave5, chave6, chave7, chave8, iMatricula, erro) {

  document.form1.ed47_v_nome.value          = chave1
  document.form1.ed101_i_turmaorig.value    = chave2;
  document.form1.ed57_c_descrorig.value     = chave3;
  document.form1.ed11_c_origem.value        = chave4;
  document.form1.ed11_i_codorigem.value     = chave5;
  document.form1.ed60_d_datamatricula.value = chave6.substr(8, 2) + "/" + chave6.substr(5, 2) + "/" + chave6.substr(0, 4);
  document.form1.ed52_d_inicio.value        = chave7;
  document.form1.ed52_d_fim.value           = chave8;
  document.form1.iMatricula.value           = iMatricula;
  document.form1.ed101_i_turmadest.value    = '';
  document.form1.ed57_c_descrdest.value     = '';
  document.form1.ed11_c_destino.value       = '';
  document.form1.ed11_i_coddestino.value    = '';

  if (erro == true) {

    document.form1.ed101_i_aluno.focus();
    document.form1.ed101_i_aluno.value = '';
  }

  js_limparTurmaDest();
}

function js_mostraaluno1(chave1, chave2, chave3, chave4, chave5, chave6, chave7, chave8, chave9, iMatricula) {

  document.form1.ed101_i_aluno.value        = chave1;
  document.form1.ed47_v_nome.value          = chave2;
  document.form1.ed101_i_turmaorig.value    = chave3;
  document.form1.ed57_c_descrorig.value     = chave4;
  document.form1.ed11_c_origem.value        = chave5;
  document.form1.ed11_i_codorigem.value     = chave6;
  document.form1.ed60_d_datamatricula.value = chave7.substr(8,2) + "/" + chave7.substr(5,2) + "/" + chave7.substr(0,4);;
  document.form1.ed52_d_inicio.value        = chave8;
  document.form1.ed52_d_fim.value           = chave9;
  document.form1.iMatricula.value           = iMatricula;
  document.form1.ed101_i_turmadest.value    = '';
  document.form1.ed57_c_descrdest.value     = '';
  document.form1.ed11_c_destino.value       = '';
  document.form1.ed11_i_coddestino.value    = '';
  db_iframe_aluno.hide();
  js_limparTurmaDest();
}

function js_limparTurmaDest() {

  document.form1.ed101_i_turmadest.value = '';
  document.form1.ed57_c_descrdest.value  = '';
  document.form1.ed11_c_destino.value    = '';
  document.form1.ed11_i_coddestino.value = '';
}

function js_pesquisaed101_i_turmadest(mostra) {

  if (document.form1.ed101_i_aluno.value == '') {

    alert( _M( CAMINHO_MENSAGENS_AVANCO + "informe_aluno" ) );
    document.form1.ed101_i_turmadest.value             = '';
    document.form1.ed101_i_aluno.style.backgroundColor = '#99A9AE';
    document.form1.ed101_i_aluno.focus();
  } else {

    js_OpenJanelaIframe(
                         'top.corpo',
                         'db_iframe_turma',
                         'func_turmaavanco.php?aluno='+document.form1.ed47_v_nome.value
                                            +'&codaluno='+document.form1.ed101_i_aluno.value
                                            +'&turma='+document.form1.ed101_i_turmaorig.value
                                            +'&sTipoValidacaoEtapa=avanco'
                                            +'&funcao_js=parent.js_mostraturma1|ed57_i_codigo|ed57_c_descr'
                                                                             +'|ed11_i_codigo|ed11_c_descr',
                         'Pesquisa de Turma de Destino',
                         true
                       );
  }
}

function js_mostraturma1( chave1, chave2, chave3, chave4 ) {

  document.form1.ed101_i_turmadest.value = chave1;
  document.form1.ed57_c_descrdest.value  = chave2;
  document.form1.ed11_i_coddestino.value = chave3;
  document.form1.ed11_c_destino.value    = chave4;

  db_iframe_turma.hide();

  if ( !empty( oTurmaTurno ) ) {
    oTurmaTurno.limpaLinhasCriadas();
  }

  oTurmaTurno = new DBViewFormularioEducacao.TurmaTurnoReferente( $('linhaTurmaDestino'), $('ed101_i_turmadest' ).value );
  oTurmaTurno.show();

  dadosImportacao();
}

function dadosImportacao() {

  if ( !oTurmaTurno.temTurnoSelecionado() ) {

    document.getElementById('iframeImportacao').style.display = 'none';
    alert(_M( CAMINHO_MENSAGENS_AVANCO + 'sem_turno_selecionada' ));
    return;
  }

  var sGet  = '';
      sGet += 'matricula='+document.form1.iMatricula.value;
      sGet += '&turmaorigem='+document.form1.ed101_i_turmaorig.value+'&turmadestino=';
      sGet += document.form1.ed101_i_turmadest.value+'&codetapaorigem=';
      sGet += document.form1.ed11_i_codorigem.value;
      sGet += '&sTipo=A'; // Avanço

  aTurnosSelecionados = new Array();
  for ( var iContador = 1; iContador <= 3; iContador++ ) {

    if ( $('check_turno' + iContador ) ) {

      if ( oTurmaTurno.getVagasDisponiveis( iContador ).length == 0 && $('check_turno' + iContador ).checked ) {

        $('check_turno' + iContador ).checked  = false;
        $('check_turno' + iContador ).readOnly = true;
      }

      if ( $('check_turno' + iContador ).checked ) {
        aTurnosSelecionados.push( iContador );
      }

      $('check_turno' + iContador ).setAttribute( "onclick", "dadosImportacao();" );
    }
  }

  if ( !oTurmaTurno.temVagasDisponiveis() ) {

    document.getElementById('iframeImportacao').style.display = 'none';
    alert( _M( CAMINHO_MENSAGENS_AVANCO + 'turma_sem_vagas' ) );
    return;
  }

  sGet += '&sTurno=' + aTurnosSelecionados.join( "," );

  document.getElementById('iframeImportacao').src           = 'edu4_trocaserieimportacao001.php?'+sGet;
  document.getElementById('iframeImportacao').style.display = ''; // Habilito a visualização
}

$("ed101_i_codigo").addClassName("field-size2");
$("ed101_i_aluno").addClassName("field-size2");
$("ed47_v_nome").addClassName("field-size9");
$("ed101_i_turmaorig").addClassName("field-size2");
$("ed57_c_descrorig").addClassName("field-size7");
$("ed60_d_datamatricula").addClassName("field-size2");
$("ed101_i_turmadest").addClassName("field-size2");
$("ed57_c_descrdest").addClassName("field-size7");
$("ed101_d_data").addClassName("field-size2");

</script>