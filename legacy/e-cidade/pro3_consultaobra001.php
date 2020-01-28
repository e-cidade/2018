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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#cccccc">
<div class="container">
  <fieldset>
    <legend>Consulta de Obras</legend>
    <table class="form-container">
      <tr>
        <td>Código da Obra:</td>
        <td>
          <?php db_input("ob01_codobra", 10, false, true, 'text', 1);?>
        </td>      
      </tr>
      <tr>
        <td>
	        <?php 
	          db_ancora("Matrícula", "js_pesquisaMatricula(true);", 1);
	        ?>          
        </td>
        <td>
          <?php 
            db_input("j01_matric", 10, false, true, 'text', 1, "onChange='js_pesquisaMatricula(false);'");
          ?>
        </td>      
      </tr>
      <tr>
        <td>
	        <?php 
	          db_ancora("Proprietário: ", "js_pesquisaProprietario(true);", 1);
	        ?>          
        </td>
        <td>
          <?php 
            db_input("ob03_numcgm", 10, false, true, 'text', 1, "js_pesquisaProprietario(false);");
            db_input("z01_nome"  , 50, false, true, 'text', 3);
          ?>
        </td>      
      </tr>
    </table>
  </fieldset> 
  <input type="button" onclick="js_pesquisa();" value="Pesquisar">
 </div>
 <?
   db_menu( db_getsession("DB_id_usuario"),
            db_getsession("DB_modulo"),
            db_getsession("DB_anousu"),
            db_getsession("DB_instit") );
 ?>
</body>
<script>

function js_pesquisa() {
  var sQueryString = "?funcao_js=parent.js_preenchepesquisa|ob01_codobra";
		  sQueryString+= "&ob01_codobra="    + $F('ob01_codobra');
		  sQueryString+= "&j01_matric="      + $F('j01_matric');
		  sQueryString+= "&ob03_numcgm="     + $F('ob03_numcgm');
      sQueryString+= "&lOrigemConsulta=true";
      
  js_OpenJanelaIframe('',
                      'db_iframe_obras',
                      'func_obras.php' + sQueryString,
                      'Pesquisa',
                      true);
}

function js_preenchepesquisa(iCodigoObra) {
  
   js_OpenJanelaIframe('',
                       'db_iframe_consultaobra', 
                       'pro3_consultaobra002.php?iCodigoObra='+iCodigoObra,
                       'Consulta Obras',
                       true);
}

function js_pesquisaMatricula(lMostra) {
  
  var sQueryString = 'func_iptubase.php?';
  if(lMostra) {
    sQueryString += 'funcao_js=parent.js_mostraMatricula|j01_matric|z01_nome';
  } 
  js_OpenJanelaIframe('top.corpo', 'db_iframe_iptubase', sQueryString, 'Pesquisa', lMostra, 20);
}

function js_mostraMatricula(iMatricula, sNome) {
  
  $('j01_matric').setValue(iMatricula);
  db_iframe_iptubase.hide();
}

function js_pesquisaProprietario(mostra) {
  
  if ( mostra == true ) {
    js_OpenJanelaIframe('','db_iframe_cgm','func_nome.php?testanome=true&funcao_js=parent.js_mostracgm1|0|1','Pesquisa',true);
  } else {
    
    if (document.form1.ob03_numcgm.value != '') { 
      js_OpenJanelaIframe('','db_iframe_cgm','func_nome.php?pesquisa_chave='+document.form1.ob03_numcgm.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
    } else {
      document.form1.z01_nome.value = ''; 
    }
  }
}

function js_mostracgm(erro,chave,erro){
  
  $('z01_nome').value = chave; 
  if ( erro == true ) { 
    
    $('ob03_numcgm').focus(); 
    $('ob03_numcgm').value = ''; 
  }
}

function js_mostracgm1(chave1,chave2) {
  
  $('ob03_numcgm').value = chave1;
  $('z01_nome').value = chave2;
  db_iframe_cgm.hide();
}
</script>
</html>
<script>

$("ob01_codobra").addClassName("field-size2");
$("j01_matric").addClassName("field-size2");
$("ob03_numcgm").addClassName("field-size2");
$("z01_nome").addClassName("field-size7");

</script>