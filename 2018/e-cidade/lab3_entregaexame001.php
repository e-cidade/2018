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
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$cllab_entrega = new cl_lab_entrega;
$clrotulo      = new rotulocampo;

$clrotulo->label("la09_i_exame");
$clrotulo->label("la24_i_setor");
$clrotulo->label("la02_i_codigo");
$clrotulo->label("la24_c_descr");
$clrotulo->label("la22_i_codigo");
$clrotulo->label("z01_v_nome");

@$dia1 = substr($data1,0,2);
@$mes1 = substr($data1,3,2);
@$ano1 = substr($data1,6,4);
@$dia2 = substr($data2,0,2);
@$mes2 = substr($data2,3,2);
@$ano2 = substr($data2,6,4);
?>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
</head>
<body bgcolor="#CCCCCC">
  <div class="container">
    <form name="form1" method="post" action="" class="form-container">
      <fieldset style="width:67%">
        <legend>Consulta de Exames</legend>
        <table>
          <tr>
            <td>
              <label class="bold">Período:</label>
            </td>
            <td colspan="2">
              <?php
              db_inputdata( 'dataini', @$dia1, @$mes1, @$ano1, true, 'text', 1 );
              echo " A ";
              db_inputdata( 'datafim', @$dia2, @$mes2, @$ano2, true, 'text', 1 );
              ?>
            </td>
          </tr>
          <tr>
            <td>
              <?php
              db_ancora( '<b>Laboratório:</b>', "js_pesquisala02_i_laboratorio(true);", "" );
              ?>
            </td>
            <td>
              <?php
              db_input( 'la02_i_codigo', 10, $Ila02_i_codigo, true, 'text', "", "onchange='js_pesquisala02_i_laboratorio(false);'");
              ?>
            </td>
            <td>
              <?php
              db_input( 'la02_c_descr',  50, @$Ila02_c_descr, true, 'text', 3 );
              ?>
            </td>
          </tr>
          <tr>
            <td>
              <?php
              db_ancora( @$Lla24_i_setor, "js_pesquisala24_i_setor(true);", "" );
              ?>
            </td>
           <td>
              <?php
              db_input( 'la24_i_setor',  10, $Ila24_i_setor,  true, 'text', "", "onchange='js_pesquisala24_i_setor(false);'");
              ?>
            </td>
            <td>
              <?php
              db_input( 'la24_i_codigo', 10, '',              true, 'hidden', '' );
              db_input( 'la23_c_descr',  38, @$Ila23_c_descr, true, 'text', 3 );
              ?>
            </td>
          </tr>
          <tr>
            <td>
              <?php
              db_ancora( @$Lla09_i_exame, "js_pesquisala09_i_exame(true);", "" );
              ?>
            </td>
            <td>
              <?php
              db_input( 'la09_i_exame', 10, @$Ila09_i_exame, true, 'text',"", "onchange='js_pesquisala09_i_exame(false);'" );
              ?>
            </td>
            <td>
              <?php
              db_input( 'la08_c_descr', 50, @$Ila08_c_descr, true, 'text', 3 );
              ?>
            </td>
          </tr>
          <tr>
            <td>
              <?php
              db_ancora( '<b>Requisição:</b>', "js_pesquisala22_i_codigo(true);", "" );
              ?>
            </td>
            <td>
              <?php
              db_input( 'la22_i_codigo', 10, @$Ila22_i_codigo, true, 'text', "", "onchange='js_pesquisala22_i_codigo(false);'" );
              ?>
            </td>
            <td>
              <?php
              db_input( 'z01_v_nome',    50, @$Iz01_v_nome,    true, 'text',  3 );
              ?>
            </td>
          </tr>
        </table>
      </fieldset>
      <input name="consultar" type="button" id="consultar" value="Consultar" onClick= 'js_botao();'>
      <input name="cancelar" type="button" id="cancelar" value="Cancelar" onClick="location.href='lab3_entregaexame001.php'">
    </form>
  </div>
  <div class="center">
    <iframe id="frame" name="frame"  src="lab3_entregaexame002.php" width="70%" height="50%" scrolling="yes"></iframe>
  </div>
</body>
</html>
<?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
<script>
function js_limpaCamposTrocaLab() {
	 
  document.form1.la24_i_setor.value  = '';
  document.form1.la24_i_codigo.value = '';
  document.form1.la23_c_descr.value  = '';
  js_limpaCamposTrocaSetor();
}

function js_limpaCamposTrocaSetor() {

  document.form1.la09_i_exame.value = '';
  document.form1.la08_c_descr.value = '';
}
	
function js_pesquisala02_i_laboratorio( mostra ) {

  if( mostra == true ) {

    js_OpenJanelaIframe(
                         '',
                         'db_iframe_lab_laboratorio',
                         'func_lab_laboratorio.php?checkLaboratorio=true'
                                                +'&funcao_js=parent.js_mostralaboratorio1|la02_i_codigo|la02_c_descr',
                         'Pesquisa',
                         true
                       );
  } else {

    if( document.form1.la02_i_codigo.value != '' ) {

      js_OpenJanelaIframe(
                           '',
                           'db_iframe_lab_laboratorio',
                           'func_lab_laboratorio.php?checkLaboratorio=true'
                                                  +'&pesquisa_chave='+document.form1.la02_i_codigo.value
                                                  +'&funcao_js=parent.js_mostralaboratorio',
                           'Pesquisa',
                           false
                         );
    } else {

      document.form1.la02_c_descr.value = '';
      js_limpaCamposTrocaLab();
    }
  }
}

function js_mostralaboratorio( chave, erro ) {

  document.form1.la02_c_descr.value = chave;

	if( erro == true ) {

	  document.form1.la02_i_codigo.focus(); 
	  document.form1.la02_i_codigo.value = ''; 
	}

  validaCampo( $('la02_i_codigo') );
}

function js_mostralaboratorio1( chave1, chave2 ) {

  document.form1.la02_i_codigo.value = chave1;
  document.form1.la02_c_descr.value  = chave2;
  db_iframe_lab_laboratorio.hide();

  validaCampo( $('la02_i_codigo') );
}

function js_pesquisala24_i_setor( mostra ) {

  if( empty( $F('la02_i_codigo') ) ) {

    alert( 'É necessário informar um laboratório.' );
    $('la24_i_setor').value = '';
    return;
  }

  sPesq = 'la24_i_laboratorio='+document.form1.la02_i_codigo.value+'&';

  if( mostra == true ) {

    js_OpenJanelaIframe(
                         '',
                         'db_iframe_lab_labsetor',
                         'func_lab_labsetor.php?'+sPesq+'funcao_js=parent.js_mostralab_labsetor1|la24_i_setor'
                                                                                              +'|la23_c_descr|la24_i_codigo',
                         'Pesquisa',
                         true
                       );
  } else {

    if( document.form1.la24_i_setor.value != '' ) {

      js_OpenJanelaIframe(
                           '',
                           'db_iframe_lab_labsetor',
                           'func_lab_labsetor.php?'+sPesq
                                                   +'pesquisa_chave='+document.form1.la24_i_setor.value
                                                   +'&funcao_js=parent.js_mostralab_labsetor',
                           'Pesquisa',
                           false
                         );
    } else {

      $('la24_i_codigo').value          = '';
      document.form1.la23_c_descr.value = '';
    }
  }
}

function js_mostralab_labsetor( chave, erro, chave2 ) {

  document.form1.la23_c_descr.value  = chave;
  document.form1.la24_i_codigo.value = chave2;

  if( erro == true ) {

    document.form1.la24_i_setor.focus();
    document.form1.la24_i_setor.value  = '';
    document.form1.la24_i_codigo.value = '';
  }

  validaCampo( $('la24_i_setor') );
}

function js_mostralab_labsetor1( chave1, chave2, chave3 ) {

  document.form1.la24_i_setor.value  = chave1;
  document.form1.la24_i_codigo.value = chave3;
  document.form1.la23_c_descr.value  = chave2;
  db_iframe_lab_labsetor.hide();

  validaCampo( $('la24_i_setor') );
}

function js_pesquisala09_i_exame( mostra ) {

  if( empty( $F('la24_i_codigo') ) ) {

    alert( 'É necessário informar um setor.' );
    return;
  }

  sPesq = 'la24_i_codigo='+document.form1.la24_i_codigo.value+'&';

  if( mostra == true ) {

    js_OpenJanelaIframe(
                         '',
                         'db_iframe_lab_setorexame',
                         'func_lab_setorexame.php?'+sPesq
                                                   +'&la24_i_codigo='+document.form1.la24_i_codigo.value
                                                   +'&funcao_js=parent.js_mostralab_exame1|la08_i_codigo|la08_c_descr',
                         'Pesquisa',
                         true
                       );
  } else {

    if( document.form1.la09_i_exame.value != '' ) {

      js_OpenJanelaIframe(
                           '',
                           'db_iframe_lab_setorexame',
                           'func_lab_setorexame.php?'+sPesq
                                                     +'pesquisa_chave='+document.form1.la09_i_exame.value
                                                     +'&funcao_js=parent.js_mostralab_exame',
                           'Pesquisa',
                           false
                         );
    } else {
      document.form1.la08_c_descr.value = '';
    }
  }
}

function js_mostralab_exame( chave, erro ) {

  document.form1.la08_c_descr.value = chave;

  if( erro == true ) {

    document.form1.la09_i_exame.focus(); 
    document.form1.la09_i_exame.value = ''; 
  }

  validaCampo( $('la09_i_exame') );
}

function js_mostralab_exame1( chave1, chave2 ) {

  document.form1.la09_i_exame.value = chave1;
  document.form1.la08_c_descr.value = chave2;
  db_iframe_lab_setorexame.hide();

  validaCampo( $('la09_i_exame') );
}

function js_mandaDados() {
 
  oF           = document.form1;
  sDataini     = 'dataini='+oF.data1.value;
  sDatafim     = '&datafim='+oF.data2.value;
  iLaboratorio = '&laboratorio='+oF.la02_i_codigo.value;
  iLabsetor    = '&labsetor='+oF.la24_i_codigo.value;
  iExame       = '&exame='+oF.la09_i_exame.value;
  iRequisicao  = '&requisicao='+oF.la22_i_codigo.value;
  jan          = window.open(
                              'lab2_exame002.php?'+sDataini+sDatafim+iLaboratorio+iLabsetor+iExame+iRequisicao,
                              '',
                              'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 '
                            );
  jan.moveTo(0,0);
}

function js_validadata() {

  if( !empty( $F('la22_i_codigo') ) ) {
    return true;
  }

  if( document.form1.dataini.value != ''  && document.form1.datafim.value != '' ) {

    aIni = document.form1.dataini.value.split('/');
    aFim = document.form1.datafim.value.split('/');
    dIni = new Date(aIni[2], aIni[1], aIni[0]);
    dFim = new Date(aFim[2], aFim[1], aFim[0]);

  	if(dFim < dIni) {
		
      alert("Data final nao pode ser menor que a data inicial.");
	    document.form1.datafim.value = '';
      return false;
	  }

	  return true;
  } else {

    alert('Preencha o período.');
    return false
  }
}

function js_pesquisala22_i_codigo( mostra ) {

  if( mostra == true ) {

    js_OpenJanelaIframe(
                         '',
                         'db_iframe_lab_requisicao',
                         'func_lab_requisicao.php?&funcao_js=parent.js_mostrarequisicao1|la22_i_codigo|z01_v_nome',
                         'Pesquisa',
                         true
                       );
  } else {

    if( document.form1.la22_i_codigo.value != '' ) {

      js_OpenJanelaIframe(
                           '',
                           'db_iframe_lab_requisicao',
                           'func_lab_requisicao.php?&pesquisa_chave='+document.form1.la22_i_codigo.value
                                                  +'&funcao_js=parent.js_mostrarequisicao',
                           'Pesquisa',
                           false
                         );
    } else {
      document.form1.z01_v_nome.value = '';
    }
  }
}

function js_mostrarequisicao( chave, erro ) {

  document.form1.z01_v_nome.value = chave;

  if( erro == true ) {

    document.form1.la22_i_codigo.focus();
    document.form1.la22_i_codigo.value = '';
  }

  validaCampo( $('la22_i_codigo') );
}

function js_mostrarequisicao1( chave1, chave2 ) {

  document.form1.la22_i_codigo.value = chave1;
  document.form1.z01_v_nome.value    = chave2;
  db_iframe_lab_requisicao.hide();

  validaCampo( $('la22_i_codigo') );
}

function validaCampo( oElemento ) {

  if( oElemento.id == 'la22_i_codigo' ) {

    $('dataini').value       = '';
    $('datafim').value       = '';
    $('la02_i_codigo').value = '';
    $('la02_c_descr').value  = '';
    $('la09_i_exame').value  = '';
    $('la08_c_descr').value  = '';

    js_limpaCamposTrocaLab();
  } else {

    $('la22_i_codigo').value = '';
    $('z01_v_nome').value    = '';
  }
}

function js_botao() {

	if( js_validadata() ) {

    if( document.form1.la02_i_codigo.value == "" && document.form1.la22_i_codigo.value == "" ) {

      alert("Pesquise um laboratório ou requisição");
      document.form1.la02_i_codigo.focus();
      return false;
    }

    x  = "lab3_entregaexame002.php";
    x += "?consultar";
    x += "&laboratorio="+document.form1.la02_i_codigo.value;
    x += "&setor="+document.form1.la24_i_codigo.value;
    x += "&dataini="+document.form1.dataini.value;
    x += "&datafim="+document.form1.datafim.value;
    x += "&exame="+document.form1.la09_i_exame.value;
    x += "&requisicao="+document.form1.la22_i_codigo.value;
    this.frame.location.href = x;
	}
}

$('dataini').onchange = function() {
  validaPeriodoRequisicao();
}

$('datafim').onchange = function() {
  validaPeriodoRequisicao();
}

function validaPeriodoRequisicao() {

  if( !empty( $F('la22_i_codigo') ) ) {

    $('la22_i_codigo').value = '';
    $('z01_v_nome').value    = '';
  }
}

$('dataini').className       = 'field-size2';
$('datafim').className       = 'field-size2';
$('la02_i_codigo').className = 'field-size2';
$('la02_c_descr').className  = 'field-size7';
$('la24_i_setor').className  = 'field-size2';
$('la24_i_codigo').className = 'field-size2';
$('la23_c_descr').className  = 'field-size7';
$('la09_i_exame').className  = 'field-size2';
$('la08_c_descr').className  = 'field-size7';
$('la22_i_codigo').className = 'field-size2';
$('z01_v_nome').className    = 'field-size7';

</script>