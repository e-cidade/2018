<?
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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_itbi_classe.php"));
require_once(modification("classes/db_itbirural_classe.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));

db_postmemory($HTTP_POST_VARS);

$clitbi      = new cl_itbi;
$clitbirural = new cl_itbirural;
$clrotulo    = new rotulocampo;

$clitbi->rotulo->label();
$clitbirural->rotulo->label();
$clrotulo->label('DBtxt23');
$clrotulo->label('DBtxt25');
$clrotulo->label('DBtxt27');
$clrotulo->label('DBtxt28');

$clrotulo->label("j34_setor");
$clrotulo->label("j34_quadra");
$clrotulo->label("j34_lote");

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
  db_app::load('scripts.js');
  db_app::load('prototype.js');
  db_app::load('datagrid.widget.js');
  db_app::load('strings.js');
  db_app::load('grid.style.css');
  db_app::load('estilos.css');
  db_app::load('/widgets/dbautocomplete.widget.js');
?>
<script>
function js_emite(){

  var dtIni       = $('dtIni').value;
  var dtFim       = $('dtFim').value;
  var sOrdem      = $('ordem').value;
  var sTipo       = $('tipo').value;
  var sSituacao   = $('situacao').value;
  var sLiberadas  = $('liberadas').value;
  var guiaIni     = $('it01_guia_ini').value;
  var guiaFim     = $('it01_guia_fim').value;
  var sLogradouro = $('it18_nomelograd').value;
  var sSetor      = $('j34_setor').value;
  var sQuadra     = $('j34_quadra').value;
  var sLote       = $('j34_lote').value;

  aObjModo = document.getElementsByName('modoOrdem');
  for ( var iInd=0; iInd < aObjModo.length; iInd++ ) {
     if ( aObjModo[iInd].checked ) {
       var sModo = aObjModo[iInd].value;
     }
  }

  aObjModoImp = document.getElementsByName('modoImp');
  for ( var iInd=0; iInd < aObjModoImp.length; iInd++ ) {
     if ( aObjModoImp[iInd].checked ) {
       var sModoImp = aObjModoImp[iInd].value;
     }
  }

  if (dtFim != "" && dtFim != "") {
	  if (dtIni > dtFim) {
	    alert("Data Inválida. Verifique!");
	    return false;
	  }
  }

  var sQuery  = '?ordem='+sOrdem;
  	  sQuery +=	'&modo='+sModo;
  	  sQuery += '&modoimp='+sModoImp;
  	  sQuery += '&dtini='+dtIni;
  	  sQuery +=	'&dtfim='+dtFim;
  	  sQuery +=	'&tipo='+sTipo;
  	  sQuery +=	'&situacao='+sSituacao;
  	  sQuery +=	'&liberadas='+sLiberadas;
  	  sQuery += '&guiaini='+guiaIni;
  	  sQuery += '&guiafim='+guiaFim;
      sQuery += '&sLogradouro='+sLogradouro;
      sQuery += '&sSetor='+sSetor;
      sQuery += '&sQuadra='+sQuadra;
      sQuery += '&sLote='+sLote;

  var sUrl    = 'itb2_relresumoitbi002.php'+sQuery;

  var sParam  = 'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ';
  var jan     = window.open(sUrl,'',sParam);
      jan.moveTo(0,0);
}

</script>
<link href='estilos.css' rel='stylesheet' type='text/css'>
</head>
<body bgcolor=#CCCCCC leftmargin='0' topmargin='0' marginwidth='0' marginheight='0' onLoad='a=1' bgcolor='#cccccc'>
<form name='form1' method='post' action='' >
  <table align='center' border='0' width='100%'>
    <tr>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>
        <table align='center' cellpadding='0' cellspacing='0'>
          <tr>
            <td>
			        <fieldset>
			          <legend>
			            <b>Resumo de ITBI</b>
			          </legend>
			          <table border='0'>
			            <tr>
			              <td align='right'>
					            <?
					              db_ancora('<b>Guia :</b>','js_pesquisait01_guia(true);',1);
					            ?>
			              </td>
			              <td width='20'>
					            <?
					              db_input('it01_guia_ini',15,$Iit01_guia,true,'text',1," onchange='js_pesquisait01_guia(false);'");
					            ?>
			              </td>
			              <td align='center'>
			                <b> a </b>
			              </td>
			              <td>
					            <?
					              db_input('it01_guia_fim',15,$Iit01_guia,true,'text',1,'');
					            ?>
			              </td>
			            </tr>
                  <tr>
                    <td align='right'>
                      <b>Periodo de :</b>
                    </td>
                    <td width='20'>
						          <?
						            db_inputdata('dtIni', '', '', '', true, 'text', 1, '');
						          ?>
                    </td>
                    <td align='center'>
                      <b> a </b>
                    </td>
                    <td>
						          <?
						            db_inputdata('dtFim', '', '', '', true, 'text', 1, '');
						          ?>
                    </td>
                  </tr>
                  <tr>
                    <td align='right'>
					            <b>Logradouro :</b>
                    </td>
                    <td colspan='3'>
					            <?
					              db_input('logradouroid',40,'',true,'hidden',3);
					              db_input('it18_nomelograd',40,$Iit18_nomelograd,true,'text',1);
					            ?>
                    </td>
                  </tr>
                  <tr>
                    <td align='right'>
                      <b>Ordem :</b>
                    </td>
                    <td colspan='3'>
                      <?
                        $aOrdem = array( 'g'=>'Guia',
                                         'v'=>'Valor' );
                        db_select('ordem',$aOrdem,true,2," style='width:275px;'");
                      ?>
                    </td>
                  </tr>
                  <tr>
                    <td></td>
                  </tr>
                  <tr>
                    <td align='right'>
                      <b>Modo :</b>
                    </td>
                    <td colspan='3'>
                      <table border='0' width='100%'>
                        <tr>
		                    <td width='50%'>
		                      <input type='radio' name='modoOrdem' value='asc' checked>Ascendente</input><br>
		                      <input type='radio' name='modoOrdem' value='desc'>Descendente</input>
		                    </td>
		                    <td width='50%'>
		                      <input type='radio' name='modoImp' value='anal' checked>Análitico</input><br>
		                      <input type='radio' name='modoImp' value='sint'>Sintético</input>
		                    </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                  <tr>
                    <td align='right'>
                      <b>Tipo :</b>
                    </td>
                    <td colspan='3'>
					            <?
					              $aTipo = array( 't'=>'Todos',
					                              'u'=>'Urbano',
					                              'r'=>'Rural' );

					              db_select('tipo',$aTipo,true,2," style='width:275px;'");
					             ?>
                    </td>
                  </tr>
                  <tr>
                    <td align='right'>
                      <b>Situaçao:</b>
                    </td>
                    <td colspan='3'>
				              <?
				                $aSituacao = array( '1'=>'Todos',
				                                    '2'=>'Aberto',
				                                    '3'=>'Pago',
				                                    '4'=>'Cancelado');
				                db_select('situacao',$aSituacao,true,2," style='width:275px;'");
				              ?>
                    </td>
                  </tr>
                  <tr>
                    <td align='right'>
                      <b>Liberadas:</b>
                    </td>
                    <td colspan='3'>
                      <?
                        $aLiberadas = array( 't'=>'Todos',
                                             's'=>'Sim',
                                             'n'=>'Não');
                        db_select('liberadas',$aLiberadas,true,2," style='width:275px;'");
                      ?>
                    </td>
                  </tr>
                  <tr>
								    <td align='right' nowrap title="<?=@$Tj34_setor?>">
								       <?=$Lj34_setor?>
								    </td>
								    <td colspan='3'>
								    <?
								     db_input('j34_setor',10,$Ij34_setor,true,'text',1);
								     ?>
								     <?=$Lj34_quadra?>
								     <?
								       db_input('j34_quadra',10,$Ij34_quadra,true,'text',1);
								     ?>

								     <?=$Lj34_lote?>
								     <?
								       db_input('j34_lote',10,$Ij34_lote,true,'text',1);
								     ?>
								    </td>
								  </tr>
			          </table>
			        </fieldset>
            </td>
          </tr>
          <tr>
            <td>
              <table align='center'>
			          <tr>
			            <td>&nbsp;</td>
			          </tr>
                <tr>
                  <td>
                    <input  name='emitir' id='emitir' type='button' value='Processar' onclick='js_validar();'>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
   	  </td>
    </tr>
  </table>
</form>
<?
  db_menu(db_getsession('DB_id_usuario'),db_getsession('DB_modulo'),db_getsession('DB_anousu'),db_getsession('DB_instit'));
?>
</body>
</html>
<script>

  $('it18_nomelograd').style.width = '275px';

  oAutoComplete = new dbAutoComplete($('it18_nomelograd'),'itb2_pesquisalogradouro.RPC.php');
  oAutoComplete.setTxtFieldId($('logradouroid'));
  oAutoComplete.show();

function js_validar(){
  js_emite();
}

function js_pesquisait01_guia(mostra){

  var sUrl1  = 'func_itbi.php?funcao_js=parent.js_mostrarit01_guia|it01_guia';
  var sUrl2  = 'func_itbi.php?pesquisa_chave='+$F('it01_guia_ini')+'&funcao_js=parent.js_mostrarit01_guia1';

  if ( mostra == true ) {
    js_OpenJanelaIframe('','db_iframe_itbi',sUrl1,'Pesquisa',true);
  } else {

     if ( $F('it01_guia_ini') != ''){
       js_OpenJanelaIframe('','db_iframe_itbi',sUrl2,'Pesquisa',false);
     } else {
       $('it01_guia_ini').value = '';
     }
  }
}

function js_mostrarit01_guia1(chave,erro){

  if ( erro == true ) {

    alert(chave);
    $('it01_guia_ini').value = '';
    $('it01_guia_ini').focus();

  } else {
    $('it01_guia_ini').value = chave;
  }
}

function js_mostrarit01_guia(chave){

  $('it01_guia_ini').value = chave;
  db_iframe_itbi.hide();

}
</script>
