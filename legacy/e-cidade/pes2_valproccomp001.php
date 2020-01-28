<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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
require_once("libs/db_app.utils.php");
require_once("libs/db_utils.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");

$oRotulo   = new rotulocampo();
$oRotulo->label('rh90_anousu');
$oRotulo->label('rh90_mesusu');

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
  db_app::load("scripts.js");
  db_app::load("strings.js");
  db_app::load("prototype.js");
  db_app::load("estilos.css");
?>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<form name="form1" method="post" action="">
<table align="center" style="padding-top: 25px;">
  <tr> 
    <td>
      <fieldset>
        <legend>
          <b>Valores Processados por Compet�ncia</b>
        </legend>
        <table>
          <tr>
            <td>
              <b>Compet�ncia ( M�s / Ano ) :</b>
            </td>
            <td>
              <?php
              
                 $anousu = db_anofolha();
                 $mesusu = db_mesfolha();
               
                 db_input('mesusu',2,true,$Irh90_anousu,'text',1);
                 echo "/";
                 db_input('anousu',4,true,$Irh90_mesusu,'text',1);                 
              ?>
            </td>
          </tr>
        </table>
      </fieldset>
    </td>
  </tr>
  <tr>
    <td align="center">
      <input type="button" id="imprimir" value="Imprimir" onClick="js_imprimir();">
    </td>
  </tr>          
</table>
</form>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
<script>
   
  function js_imprimir() {
    
    var sAnoUsu = new String($F('anousu'));
    var sMesUsu = new String($F('mesusu'));
      
    if ( sAnoUsu.trim() == '' || sMesUsu.trim() == '' ) {
      alert('Compet�ncia n�o informada!');
      return false;
    }
      
    if ( sMesUsu < 1 || sMesUsu > 12  ) {
      alert('M�s inv�lido!');
      return false; 
    }
      
	  var sQuery  ="?iAnoCompetencia="+sAnoUsu
	              +"&iMesCompetencia="+sMesUsu;

	  var sUrl    = 'pes2_valproccomp002.php'+sQuery;
	  var sParam  = 'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ';
	  var jan     = window.open(sUrl,'',sParam);
	      jan.moveTo(0,0);  

  }
     
</script>
</html>