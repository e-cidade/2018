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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_utils.php");

$aExercicios  = array();

for ($iIndice = db_getsession('DB_anousu'); $iIndice >= (db_getsession('DB_anousu') - 10); $iIndice--) {
  
  $aExercicios[$iIndice] = $iIndice;
  
}

?>

<html>
<head>
<?php 
  db_app::load("estilos.css, scripts.js, strings.js, prototype.js");
?>
<script type="text/javascript">

function js_imprimir() {

  sUrl = 'arr2_lancamentostributarios002.php?iAnoCalculo=' + $F('iAnoCalculo');
  
  oJanela = window.open(sUrl, '', 'width=' + (screen.availWidth - 5) + ',height=' + (screen.availHeight - 40) + ', scrollbars=1, location=0 ');

  oJanela.moveTo(0,0);

  return true;
  
}

</script>
</head>

<body bgcolor="#CCCCCC">
<fieldset style="margin: 25px auto 10px auto; width: 450px; text-align: center;">
  <legend><strong>Exercício do Lançamento</strong></legend>
    <strong>Exercício:</strong>  
		<?php
		  db_select('iAnoCalculo', $aExercicios, true, 1, "style='width: 100px'");
		?>
</fieldset>

<center>
  <input type="button" name="imprimir" id="imprimir" value="Imprimir" onclick="js_imprimir()"/>
</center>

<?php 
  db_menu(db_getsession('DB_id_usuario'), db_getsession('DB_modulo'), db_getsession('DB_anousu'), db_getsession('DB_instit'));
?>
</body>
</html>