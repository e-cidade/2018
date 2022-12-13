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
require_once(modification( "libs/db_stdlibwebseller.php" ));

$clcgs_und->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("s115_c_cartaosus");
$clrotulo->label("s113_c_encaminhamento");

//Procedimento
$clrotulo->label ("s125_i_procedimento");
$clrotulo->label ( "sd63_c_procedimento" );
$clrotulo->label ( "sd63_c_nome" );

//CBO
$clrotulo->label ( "rh70_sequencial" );
$clrotulo->label ( "rh70_estrutural" );
$clrotulo->label ( "rh70_descr" );

//sau_prontprograma
$clrotulo->label ( "s141_i_acaoprog" );

$clrotulo->label ( "z01_v_telcel" );

$objSau_Config = loadConfig( "sau_config" );

$sDisabled = "";
$sName     = "excluir";
$sValue    = "Excluir";

switch ($db_opcao) {
  case 1:

    $sDisabled = "disabled='disabled'";
    $sName     = "incluir";
    $sValue    = "Incluir";
    break;
  case 2:
  case 22:
    $sDisabled = "disabled='disabled'";
    $sName     = "alterar";
    $sValue    = "Alterar";
    break;

}
$sDisplay = 'none';
if ($tipo == 'M') {
  
  $sDisplay = '';
  $s113_c_hora = '00:00';
}
?>
<div class="container" style="margin: 10px auto 0px;">
<form name="form3" method="post" action="">
  <fieldset>
    <legend><b>Paciente</b></legend>
	  <table border="0">
	    <?php
      if( $objSau_Config->s103_c_agendaprog == "S" ) {
      ?>
      <!-- Acão Programatica -->
      <tr>
        <td nowrap title="<?=$Ts141_i_acaoprog?>">
          <?=$Ls141_i_acaoprog?>
        </td>
        <td>
        <?php
        $result_programa = db_query( "select fa12_i_codigo,fa12_c_descricao from far_programa" );
        db_selectrecord( "fa12_i_codigo", $result_programa, $Isd141_i_acaoprog, $db_opcao, "", "fa12_c_descricao","","","",1 );
        echo"<script>
               document.form3.fa12_c_descricao.add(new Option(\"NENHUM\", \"0\"),  null);
             </script>";
        ?>
        </td>
      </tr>
			  <?php
        }
        ?>
			  <!--  CGS / Nome -->
			  <tr>
			    <td nowrap title="<?=$Ts115_c_cartaosus?>">
            <?=$Ls115_c_cartaosus?>
			    </td>
			    <td>
			      <?php
            db_input(
                      's115_c_cartaosus',
                      10,
                      $Is115_c_cartaosus,
                      true,
                      'text',
                      $db_opcao,
                      "onchange='js_pesquisas115_c_cartaosus(false);'  onFocus=\"nextfield='z01_i_cgsund'\" "
                    );
			      ?>
			    </td>
			  </tr>
			  <tr>
			    <td nowrap title="<?=$Tz01_i_cgsund?>">
			      <?php
			      db_ancora( $Lz01_i_cgsund, "js_pesquisaz01_i_cgsund(true);", $db_opcao );
			      ?>
			    </td>
			    <td nowrap>
			      <?php
            $sJavaScript = " onchange='js_pesquisaz01_i_cgsund(false);' onFocus=\"nextfield='db_opcao'\" ";
			      db_input( 'z01_i_cgsund', 10, $Iz01_i_cgsund, true, 'text', $db_opcao, $sJavaScript );
			      db_input( 'z01_v_nome',   40, $Iz01_v_nome,   true, 'text', $db_opcao, "onchange='js_pesquisaz01_v_nome()'" );
			      ?>
			    </td>
			  </tr>
        <tr>
          <td nowrap title="<?=$Tz01_v_telcel?>">
            <?=$Lz01_v_telcel?>
          </td>
          <td>
            <?php
            db_input( 'z01_v_telcel', 20, $Iz01_v_telcel, true, 'text',   $db_opcao );
            ?>
          </td>
        </tr>
			  <tr>
			    <td nowrap title="<?=$Ts113_c_encaminhamento?>">
            <?=$Ls113_c_encaminhamento?>
			    </td>
			    <td>
			      <?php
			      db_input( 's113_c_encaminhamento', 10, $Is113_c_encaminhamento, true, 'text',   $db_opcao );
					  db_input( 's125_i_procedimento',   10, $Is125_i_procedimento,   true, 'hidden', $db_opcao );
			      ?>
			    </td>
			  </tr>
        <tr style="display:<?=$sDisplay;?>">
          <td>
            <label for="hora"><b>Hora:</b></label>
          </td>
          <td>
            
            <input type="text" id="hora" name="hora" value="<?=$s113_c_hora;?>" autocomplete="off">
          </td>
        </tr> 
	</table>
</fieldset>
<input name="<?=$sName?>" type="submit" <?=$sDisabled?> id='incluirAgendamento'
       value="<?=$sValue?>" onFocus="nextfield='done'" onclick="return validaCampos();" />
<input name="fechar" type="submit" id="fechar" value="Fechar" onclick="js_fechar();">
</form>
</div>
<script type="text/javascript">

mascaraTelefone( $("z01_v_telcel") );

$('s115_c_cartaosus').onkeyup   = validaCartaoSUS;
$('s115_c_cartaosus').onkeydown = validaCartaoSUS;

/**
 * Valida campo Cartão SUS para aceitar apenas números
 */
function validaCartaoSUS (){
  $('s115_c_cartaosus').value = $F('s115_c_cartaosus').somenteNumeros();
}

function js_fechar(){
  parent.db_iframe_agendamento.hide();
}

//CGS
function js_pesquisaz01_i_cgsund( mostra ) {

  $('incluirAgendamento').disabled = true;

  if( mostra == true ) {

    js_OpenJanelaIframe(
                         'CurrentWindow.corpo',
                         'db_iframe_cgs_und',
                         'func_cgs_und.php?funcao_js=parent.IFdb_iframe_agendamento.js_mostracgs1|z01_i_cgsund'
                                                                                               +'|z01_v_nome'
                                                                                               +'|z01_d_nasc'
                                                                                               +'|z01_v_telcel'
                                                                                               +'|s115_c_cartaosus'
                                        +'&retornacgs=p.p.IFdb_iframe_agendamento.document.form3.z01_i_cgsund.value'
                                        +'&retornanome=p.p.IFdb_iframe_agendamento.document.form3.z01_v_nome.value'
                                        +'&lValidaCGS=true',
                         'Pesquisa CGS',
                         mostra
                       );
  } else {

    if( document.form3.z01_i_cgsund.value != '' ) {

      js_OpenJanelaIframe(
                           'CurrentWindow.corpo',
                           'db_iframe_cgs_und',
                           'func_cgs_und.php?chave_z01_i_cgsund='+document.form3.z01_i_cgsund.value
                                          +'&funcao_js=parent.IFdb_iframe_agendamento.js_mostracgs1|z01_i_cgsund'
                                                                                                 +'|z01_v_nome'
                                                                                                 +'|z01_d_nasc'
                                                                                                 +'|z01_v_telcel'
                                                                                                 +'|s115_c_cartaosus'
                                          +'&retornacgs=p.p.IFdb_iframe_agendamento.document.form3.z01_i_cgsund.value'
                                          +'&retornanome=p.p.IFdb_iframe_agendamento.document.form3.z01_v_nome.value'
                                          +'&lValidaCGS=true',
                           'Pesquisa CGS',
                           mostra
                         );
    } else {

      $('z01_v_nome').value            = '';
      $('z01_v_telcel').value          = '';
      $('s115_c_cartaosus').value      = '';
      $('s113_c_encaminhamento').value = '';
    }
  }
}

//Cartão Sus
function js_pesquisas115_c_cartaosus( mostra ) {

	var strParam  = 'func_cgs_und.php';
	    strParam += '?funcao_js=parent.IFdb_iframe_agendamento.js_mostracgs1|z01_i_cgsund|z01_v_nome|z01_d_nasc|z01_v_telcel|s115_c_cartaosus';
	    strParam += '&retornacgs=p.p.IFdb_iframe_agendamento.document.form3.z01_i_cgsund.value';
	    strParam += '&retornanome=p.p.IFdb_iframe_agendamento.document.form3.z01_v_nome.value';

  $('incluirAgendamento').disabled = true;

	if( mostra == true ) {
		js_OpenJanelaIframe( 'CurrentWindow.corpo', 'db_iframe_cgs_und', strParam, 'Pesquisa CGS', true );
	} else {

		if( document.form3.s115_c_cartaosus.value != '' ) {

			strParam += '&chave_s115_c_cartaosus='+document.form3.s115_c_cartaosus.value;
			js_OpenJanelaIframe( 'CurrentWindow.corpo', 'db_iframe_cgs_und', strParam, 'Pesquisa CGS', true );
		} else {

			document.form3.z01_v_nome.value   = '';
      document.form3.z01_v_telcel.value = '';
		}
	}
}

//Nome
function js_pesquisaz01_v_nome() {

  if( document.form3.z01_v_nome.value != '' ) {

    js_OpenJanelaIframe(
                         'CurrentWindow.corpo',
                         'db_iframe_cgs_und',
                         'func_cgs_und.php?chave_z01_v_nome='+document.form3.z01_v_nome.value
                                        +'&funcao_js=parent.IFdb_iframe_agendamento.js_mostracgs1|z01_i_cgsund'
                                                                                               +'|z01_v_nome'
                                                                                               +'|z01_d_nasc'
                                                                                               +'|z01_v_telcel'
                                                                                               +'|s115_c_cartaosus'
                                        +'&retornacgs=p.p.IFdb_iframe_agendamento.document.form3.z01_i_cgsund.value'
                                        +'&retornanome=p.p.IFdb_iframe_agendamento.document.form3.z01_v_nome.value',
                         'Pesquisa',
                         true
                       );
  }
}

function js_mostracgs( chave, erro ) {

  document.form3.z01_v_nome.value = chave;

  if( erro == true ) {

    document.form3.z01_i_cgsund.focus();
    document.form3.z01_v_nome.value   = '';
    document.form3.z01_v_telcel.value = '';
  }
}

function validaTamanhoTelefone() {

  if ( $F('z01_v_telcel') != '' ) {

    var iCelular = $F('z01_v_telcel').replace(/[^0-9]/g,"");
    if ( iCelular.length < 10) {
      return false;
    }
  }

  return true;
}


function js_mostracgs1( chave1, chave2, chave3, chave4, chave5 ) {

	if( chave3 != ""  ) {

    $('incluirAgendamento').disabled = false;

    if(!confirm( "Confirma paciente: \n\n "+chave1+" "+chave2 ) ) {
      return false;
    }

    document.form3.z01_i_cgsund.value     = chave1;
    document.form3.z01_v_nome.value       = chave2;
    document.form3.z01_v_telcel.value     = chave4;
    document.form3.s115_c_cartaosus.value = chave5;
    parent.db_iframe_cgs_und.hide();

    $('incluirAgendamento').removeAttribute("disabled");

	} else {
		alert("Paciente sem Data de Nascimento, por favor atualize o Cadastro.");
  }
}

function validaCampos() {

  if( empty( $F('z01_i_cgsund') ) ) {

    alert( 'Código do CGS não informado.' );
    return false;
  }

  if( !validaTelefone() ) {
    return false;
  }

  return true;
}

function validaTelefone() {

  /*ATENÇÃO: Codigo utilizado pelo plugin SMSAgendamento*/

  if ( !validaTamanhoTelefone() ) {

    alert("O formato do telefone deve iniciar com o DDD seguido de 8 ou 9 digitos." )
    return false;
  }

  return true;
}

$('z01_i_cgsund').className          = 'field-size2';
$('s115_c_cartaosus').className      = 'field-size3';
$('z01_v_telcel').className          = 'field-size3';
$('s113_c_encaminhamento').className = 'field-size3';
$('hora').className = 'field-size3';
var oInputHora = new DBInputHora($('hora'));
</script>