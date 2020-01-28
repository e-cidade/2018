<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_app.utils.php");
require_once ("dbforms/db_classesgenericas.php");
require_once ("dbforms/db_funcoes.php");
require_once ("classes/db_solicita_classe.php");

$clrotulo = new rotulocampo();
$clrotulo->label("z10_numcgm");
$clrotulo->label("z11_numcgm");
$clrotulo->label("z11_nome");
$clrotulo->label("z01_nome");
$clrotulo->label("DBtxt_cgmcorreto");
$clrotulo->label("DBtxt_cgmerrado");
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

<style>
td {
	white-space: nowrap
}

.fildset-principal table td:first-child {
	width: 90px;
	white-space: nowrap
}
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0"
	marginheight="0" onload="js_limpacampos();">
<table align="center" width="30%">
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>
		<form name="form1" method="post" action=""
			onsubmit="js_limpacampos();">
		<fieldset class="fildset-principal"><legend> <b>Consulta CGM duplo
		processado</b> </legend>
		<table align="left" border="0" class="table-campos">
			<tr>
				<td nowrap align="left"><b>
                  <?
                  db_ancora($LDBtxt_cgmcorreto, "js_pesquisaz10_numcgm(true);", 1);
                  ?>
                </b></td>
				<td>
                  <?
                  db_input("z10_numcgm", 10, $Iz10_numcgm, true, "text", 1, "onchange='js_pesquisaz10_numcgm(false);'");
                  db_input("z01_nome", 45, $Iz01_nome, TRUE, "text", 3);
                  ?>  
                </td>


				<td align="left" nowrap>
                 <?
                
                ?>
                </td>
			</tr>
			<tr>
				<td nowrap align="left"><b>
                    <?
                    db_ancora($LDBtxt_cgmerrado, "js_pesquisaz11_numcgm(true);", 1);
                    ?>   
                  </b></td>
				<td align="left" nowrap>
                 <?
                db_input("z11_numcgm", 10, $Iz11_numcgm, true, "text", 1, "onchange='js_pesquisaz11_numcgm(false);'");
                db_input("z11_nome", 45, $Iz11_nome, TRUE, "text", 3);
                ?>
                </td>
			</tr>
			<tr>
				<td nowrap align="left"><b>Data do Processamento</b></td>
				<td>
              <?php
              db_inputdata('dtinivlrg', @$dia, @$mes, @$ano, true, 'text', 1, "");
              echo " <b>até:</b> ";
              db_inputdata('dtfimvlrg', @$dia2, @$mes2, @$ano2, true, 'text', 1, "");
              ?>
              </td>
			
			
			<tr>
				<td colspan="2">
				<table align="center" border="0">
					<tr>
						<td>
                         <?
                        $cl_cgm = new cl_arquivo_auxiliar();
                        $cl_cgm->nome_botao = "db_lanca_cgm";
                        $cl_cgm->cabecalho = "<strong>Usuários selecionados</strong>";
                        $cl_cgm->codigo = "id_usuario";
                        $cl_cgm->descr = "nome";
                        $cl_cgm->nomeobjeto = 'db_usuario';
                        $cl_cgm->funcao_js = 'js_mostra';
                        $cl_cgm->funcao_js_hide = 'js_mostra1';
                        $cl_cgm->sql_exec = "";
                        $cl_cgm->func_arquivo = "func_db_usuarios.php";
                        $cl_cgm->nomeiframe = "db_iframe_itens_pcmater";
                        $cl_cgm->localjan = "";
                        $cl_cgm->onclick = "";
                        $cl_cgm->db_opcao = 2;
                        $cl_cgm->tipo = 2;
                        $cl_cgm->top = 0;
                        $cl_cgm->linhas = 5;
                        $cl_cgm->vwidth = 400;
                        $cl_cgm->funcao_gera_formulario();
                        ?>
                      </td>
					</tr>
				</table>
				</td>
			</tr>
		</table>
		</fieldset>
		<table align="center">
			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td colspan="2" align="center">
				<input name="pequisar" id="pesquisar" type="button" value="Pesquisar" onclick="js_pesquisar();"> 
				<input name="limpar" id="limpar" type="button" value="Limpar" onclick="js_limpar();">
				</td>
			</tr>
		
		</table>
		</form>
		
		</td>
	</tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
?>
</body>
</html>
<script>

function js_pesquisaz10_numcgm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','func_cgmcorreto','func_cgmcorretoconsulta.php?funcao_js=parent.js_mostranumcgmcorreto|z10_numcgm|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.z10_numcgm.value != ''){
        js_OpenJanelaIframe('top.corpo','func_cgmcorreto','func_cgmcorretoconsulta.php?pesquisa_chave='+document.form1.z10_numcgm.value+'&funcao_js=parent.js_mostranumcgmcorretoerro','Pesquisa',false);
      }
     else{
       document.form1.z01_nome.value = "";
     }
  }
}

function js_pesquisaz11_numcgm(mostra){
 
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','func_cgmerrado','func_cgmerradoconsulta.php?funcao_js=parent.js_mostranumcgmerrado|z11_numcgm|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.z11_numcgm.value != ''){
        js_OpenJanelaIframe('top.corpo','func_cgmerrado','func_cgmerradoconsulta.php?pesquisa_chave='+document.form1.z11_numcgm.value+'&funcao_js=parent.js_mostranumcgmerradoerro','Pesquisa',false);
      }
     else{
       document.form1.z11_nome.value = "";
     }
  }
}

function js_pesquisar(){

			  var vrg    = '';
			  var iUsuarios = $('db_usuario').options.length;
			  var sUsuarios = '';
			  for (i = 0; i < iUsuarios; i++) {
			  
			    sUsuarios = sUsuarios+vrg+$('db_usuario').options[i].value;
			    vrg =',';
			  
			  }
			  
			      js_OpenJanelaIframe('top.corpo', 
			                          'func_cgmduplofiltroconsulta',
			                          'func_cgmduplofiltroconsulta.php?cgmcorreto='+document.form1.z10_numcgm.value+
			                                                         '&cgmerrado='+document.form1.z11_numcgm.value+
			                                                         '&dtinivlrg='+document.form1.dtinivlrg.value+
			                                                         '&dtfimvlrg='+document.form1.dtfimvlrg.value+
			                                                         '&iUsuarios='+sUsuarios+       
			                           '&funcao_js=parent.js_mostracgmduplo|dl_cgmcorreto|dl_nomecorreto|z10_data|z10_hora|usuario|z10_codigo',
			                           'Pesquisa',true);
			                        
			}
      
//Função que retorna a pesquisa para o formulario com os dois campos NUMCGM e NOME.
//Caso a função js_pesquisaz10_numcgm tenha sido FALSE.
//Se a função não encontrar um NUMCGM digitado retorna um erro para o formulario.
function js_mostranumcgmcorretoerro(chave,erro){
  document.form1.z01_nome.value = chave;
  if(erro==true)
  { 
    document.form1.z10_numcgm.value = ''; 
    document.form1.z10_numcgm.focus(); 
  }
}

//Função que retorna a pesquisa para o formulario com os dois campos NUMCGM e NOME.
//Caso a função js_pesquisaz11_numcgm tenha sido FALSE.
//Se a função não encontrar um NUMCGM digitado retorna um erro para o formulario.
function js_mostranumcgmerradoerro(chave,erro){
  document.form1.z11_nome.value = chave;
  if(erro==true)
  { 
    document.form1.z11_numcgm.value = ''; 
    document.form1.z11_numcgm.focus(); 
  }
}

//Função que retorna a pesquisa para o formulario com os dois campos NUMCGM e NOME
//Caso a função js_pesquisaz10_numcgm tenha sido TRUE.
function js_mostranumcgmcorreto(chave1,chave2){
  document.form1.z10_numcgm.value = chave1;
  document.form1.z01_nome.value   = chave2;
  func_cgmcorreto.hide();
}

//Função que retorna a pesquisa para o formulario com os dois campos NUMCGM e NOME
//Caso a função js_pesquisaz11_numcgm tenha sido TRUE.
function js_mostranumcgmerrado(chave1,chave2){
  document.form1.z11_numcgm.value = chave1;
  document.form1.z11_nome.value   = chave2;
  func_cgmerrado.hide();
}

//funcao que envia por get os paramentros de pequisa para o iframe 
function js_mostracgmduplo(chave1, chave2, chave3, chave4, chave5, chave6){
  
   js_OpenJanelaIframe('top.corpo','func_cgmduploconsulta','func_cgmduploconsulta.php?z10_numcgm='+chave1+
                                                                                      '&z01_nome='+chave2+
                                                                                      '&z10_data='+chave3+
                                                                                      '&z10_hora='+chave4+
                                                                                      '&usuario='+chave5+
                                                                                      '&z10_codigo='+chave6);
}

//funcao para limpar os campos do form
function js_limpar(){
  $(form1).reset();
  $('db_usuario').options.length=0;
  
}

</script>