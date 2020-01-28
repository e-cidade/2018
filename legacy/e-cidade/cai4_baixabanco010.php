<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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
require_once("libs/db_app.utils.php");

$clrotulo = new rotulocampo;
$clrotulo->label('autent');

db_postmemory($HTTP_SERVER_VARS);
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
  db_app::load("scripts.js, strings.js, prototype.js");
  db_app::load("estilos.css, grid.style.css");
?>
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class="body-default" onLoad="a=1" >

<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="100%" align="left" valign="top" bgcolor="#CCCCCC">
	   <center>
		 <fieldset style="width: 450px; margin-top: 50px;">
		  <legend><strong>Movimentações Manuais</strong></legend>
        <table width="60%" border="0" cellspacing="0">
          <tr>
            <form name="form1" method="post" action="">

				      <tr>
								<td align="left" width="50%"><strong>Agência :&nbsp;&nbsp;</strong>
								</td>
				        <td align="left">

											   <?
									          $result = db_query("select *
                                                  from cadban
															                         inner join cgm on k15_numcgm = z01_numcgm
													                       where k15_codbco = $codbco
														                       and k15_codage = '$codage'");
											     db_fieldsmemory($result,0);
			                   ?>
			                   <?=$k15_codage?>
				        </td>
				      </tr>
				      <tr>
							<td align="left"><strong>Banco:&nbsp;&nbsp;</strong>
							</td>
				        <td align="left">
			                   <?=$k15_codbco.' - '.$z01_nome?>
				        </td>
				      </tr>
               <tr>
                 <td align="left" nowrap><strong>Lançamento:&nbsp;&nbsp;</strong>

                 </td>
                 <td  nowrap>
		               <?
                     $xdata = date('d-m-Y',db_getsession("DB_datausu"));
					           db_inputdata("datai",substr($xdata,0,2),substr($xdata,3,2),substr($xdata,6,4),true,'text',1);
                    ?>
                 </td>
               </tr>
               <tr>
                 <td align="left" nowrap title="<?=$Tautent?>" >
                    <?=$Lautent?>
                 </td>
                 <td align="left">
                    <?
		                  $xx = array('f'=>'SIM','t'=>'NÃO');
                      db_select('autent',$xx,true,4,'');
                    ?>
                 </td>
               </tr>
              <tr>
                <td align="left"><strong>Arquivo: </strong></td>
                <td >
                   <?
                      $aArquivoExistente = array('nao'=>'NÃO','sim'=>'SIM');
                      db_select('arqexistente',$aArquivoExistente,true,4,"onchange='js_exibe_codarquivo();'");
                    ?>
                </td>
              </tr>
              <tr id="arquivoexistente" style="display: none;">
                <td align="left" >
                  <?
                   db_ancora("Nome Arquivo: ","js_pesquisaarquivos(true);",1,'font-weight:bold;');
                  ?>
                </td>
                <td >
                  <?
                    db_input('codret',10,"",true,'text',3,'')
                  ?>
                   <input id='cod_agencia' style="width: 25px;" type="hidden" value="<?=$k15_codage?>">
                   <input id='cod_banco'   style="width: 25px;" type="hidden" value="<?=$k15_codbco?>">
                 </td>
	              </tr>
	    	        <tr>
	    	          <td >&nbsp;</td>
	                <td >&nbsp;</td>
	              </tr>
            </table>
         </fieldset>
            <table width="60%" border="0" cellspacing="0" style="width: 450px; margin-top: 10px;">
               <tr>
                 <td colspan="2" align="center">
                   <input name="novo" type="button" id="novo" value="Incluir" onClick="return js_incluivalores();">
	               </td>
               </tr>
            </tr>

          </table>

        </center>

	   </td>

  </tr>

</table>
</body>
</html>
<?
      $disbanco = new janela("disbanco","");
      $disbanco->iniciarVisivel = false;
      $disbanco->largura = "470";
      $disbanco->altura = "350";
      $disbanco->mostrar();
?>
<script type="text/javascript">

function js_exibe_codarquivo() {

  var sHabilita = $('arqexistente').value;
  if (sHabilita == "sim") {
    $('arquivoexistente').show();
  } else {

    $('arquivoexistente').hide();
    $('codret').value = '';
  }
}

function js_pesquisaarquivos(mostra){

  if(mostra==true){

	  var iBanco   = $F('cod_banco');
	  var iAgencia = $F('cod_agencia');
	  var sQuery   = 'iBanco='+iBanco+'&iAgencia='+iAgencia;

    js_OpenJanelaIframe('','db_iframe_arquivos','func_disarq.php?'+sQuery+'&funcao_js=parent.js_preenche_arquivos|codret','Pesquisa',true,"10","10",screen.availWidth-60,500);

  } else {

    if(document.form1.codret.value != ''){
      js_OpenJanelaIframe('top.corpo','db_iframe_arquivos','func_disarq.php?'+sQuery+'pesquisa_chave='+document.form1.codret.value+'&funcao_js=parent.js_preenche_arquivos1','Pesquisa',true);
    }
  }
}

function js_preenche_arquivos(chave1){

  $('codret').value = chave1;
  db_iframe_arquivos.hide();
}

function js_acerta(){

  var acertabanco;
  acertabanco = confirm('Confirma acerto do banco?');
  if (acertabanco == true){
    location.href='cai4_baixabanco004.php?codret=1&acertabanco=1';
  }
}

function js_incluivalores(){

  var sArquivo = $F('codret');
  var sOpcao   = $F('arqexistente');

  if ((sOpcao == 'sim') && (sArquivo == null || sArquivo == '' )) {

      alert('Opção de Arquivo Selecionada \n Favor Inserir um Arquivo');
      js_pesquisaarquivos(true);
      return false;
  } else {

      disbanco.jan.location.href='cai4_baixabanco005.php?autent='+document.form1.autent.value+'&opcao=5&conta=<?=$conta?>&codbco=<?=$codbco?>&codage=<?=$codage?>&dia='+document.form1.datai_dia.value+'&mes='+document.form1.datai_mes.value+'&ano='+document.form1.datai_ano.value+'&arquivocodret='+sArquivo;
      disbanco.show();
      disbanco.focus();
      return true;
  }
}

function js_imprime(){
  window.open('cai4_baixabanco008.php?codret=1&opcao=5','','width=790,height=530,scrollbars=1,location=0');
}
</script>