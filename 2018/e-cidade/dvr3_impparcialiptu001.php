<?
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
require_once ("libs/db_utils.php");
require_once ("libs/db_conecta.php");
require_once ("dbforms/db_funcoes.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("libs/db_app.utils.php");

$clrotulo = new rotulocampo();
$clrotulo->label('j01_matric');
$clrotulo->label('z01_nome');

/** $oParametros->prefeitura      SE PREFEITURA      ('t','f')       **/
/** $oParametros->db21_usasisagua SE USA AGUA        ('t','f')       **/
/** $oGet->sTipo                  TIPO DE IMPORTACAO ('agua','iptu') **/

$oGet            = new _db_fields();
$oGet            = db_utils::postMemory($HTTP_GET_VARS);
$sTituloFieldset = '';

if ($oGet->sTipo == 'iptu'){
	$sTituloFieldset = ' (IPTU)';
} else if ($oGet->sTipo == 'agua'){
	$sTituloFieldset = ' (Água)';
}

$oDaoDBConfig = db_utils::getDao('db_config');
$oParametros  = $oDaoDBConfig->getParametrosInstituicao(db_getsession("DB_instit"));

/** permite funcionalidade somente se | prefeitura e acessar importacao de iptu | agua e acessar importacao de agua **/
if ( !($oParametros->prefeitura == 't' && $oGet->sTipo == 'iptu') && !($oParametros->db21_usasisagua == 't' && $oGet->sTipo == 'agua')) {
	$db_opcao = 33;
}else{
	$db_opcao = 1;
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<? 
db_app::load('scripts.js, prototype.js, strings.js, datagrid.widget.js, windowAux.widget.js, DBViewImportacaoDiversos.classe.js, dbmessageBoard.widget.js');
db_app::load('estilos.css, grid.style.css');
?>
</head>
<body bgcolor=#CCCCCC>
  <form class="container" name="form1" id="form1">
        <?php if ( $db_opcao == 1 ) { ?>
        <fieldset>
        <legend> Importação de Débitos<?php echo $sTituloFieldset ?>:</legend>
        <table class="form-container">
        <tr>
          <td title="<?=$Tj01_matric?>">
          <?
          db_ancora($Lj01_matric, "js_pesquisaMatricula(true);", 1);
          ?>
          </td>
          <td>
          <?php 
          db_input("j01_matric", 10, $Ij01_matric, true, 'text', 1, "onchange='js_pesquisaMatricula(false)'");
          db_input("z01_nome"  , 40, $Iz01_nome  , true, 'text', 3);
          ?>
          </td>
        </tr>
        </table>
        </fieldset>
        <input type="button" name="pesquisar" id="pesquisar" value="Visualizar Débitos" onclick="js_pesquisaDebitos()" />
        <?php } else { ?>
        <fieldset>
        <legend> Importação de Débitos<?php echo $sTituloFieldset ?>:</legend>
        <table class="form-container">
        <tr>
          <td align="center"><br/> <span>Esta rotina não está disponível para esta Instituição.</span>
          </td>
        </tr>
        </table>
        </fieldset>
        <?php } ?>
  </form>

  <?php
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
  ?>

  <script type="text/javascript">

  function js_pesquisaDebitos() {
    
    oImportacao = new DBViewImportacaoDiversos('oImportacao', 'importacao');
    oImportacao.setTipoPesquisa(2); //matricula

    oImportacao.setCallBackFunction( function(){ window.location.reload(); } );        
    
    var aChavesPesquisa = new Array();
    aChavesPesquisa.push($F('j01_matric'));   
    oImportacao.setChavePesquisa(aChavesPesquisa);
       
    oImportacao.show();
  }

  function js_pesquisaMatricula(lMostra){

  	if (lMostra == true){
  	  
  		js_OpenJanelaIframe('top.corpo','db_iframe_matric', 'func_iptubase.php?funcao_js=parent.js_mostraMatricula|j01_matric|z01_nome','Pesquisa',true);
      
    } else {
  	  
      js_OpenJanelaIframe('top.corpo','db_iframe_matric', 'func_iptubase.php?pesquisa_chave='+document.form1.j01_matric.value+'&funcao_js=parent.js_mostraMatriculaHidden','Pesquisa',false);
      
    }
  }

  function js_mostraMatricula(iMatricula, sNome) {

  	document.form1.j01_matric.value = iMatricula;
  	document.form1.z01_nome.value   = sNome;
  	
  	db_iframe_matric.hide();
  	
  }

  function js_mostraMatriculaHidden(sNome, lErro) {

  	if(lErro == true) {
  		document.form1.j01_matric.value = "";
  		document.form1.z01_nome.value   = sNome;
  	} else {
  		document.form1.z01_nome.value   = sNome;
  	}

  }
  
  </script>
</body>
</html>
<script>
<?php if ( $db_opcao == 1 ) { ?>
  $("j01_matric").addClassName("field-size2");
  $("z01_nome").addClassName("field-size7");
<?php }?>
</script>