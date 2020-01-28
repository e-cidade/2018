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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);

$clrotulo = new rotulocampo;
$clrotulo->label('k13_conta');
$clrotulo->label('ob09_area');



?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>

<script>
function js_emite(){  
    jan = window.open('pro2_relhabite002.php?areaini='+document.form1.ob09_areaINI.value+'&areafin='+document.form1.ob09_areaFIN.value+'&ordem='+document.form1.ordem.value+'&dt='+document.form1.data_ano.value+'-'+document.form1.data_mes.value+'-'+document.form1.data_dia.value+'&dt1='+document.form1.data1_ano.value+'-'+document.form1.data1_mes.value+'-'+document.form1.data1_dia.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    jan.moveTo(0,0);
  
  
}
</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC>
<form class="container" name="form1" method="post" action="">
  <fieldset>
    <legend>Relatórios - Construções e Habite-se</legend>
    <table class="form-container">
      <tr>
	      <td>De:</td>
	      <td>
	        <?db_inputdata("data","","","","true","text",2) ?> 
	        <b>Até:</b>
	        <?db_inputdata("data1","","","","true","text",2)?> 
	      </td>
	    </tr>
      <tr>
        <td title="Habite-se" >Habite-se:</td>
        <td>
          <?
            $xx = array("t"=>"Todos","p"=>"Parcial","g"=>"Total");
            db_select('ordem',$xx,true,4,"");
          ?>
        </td>
      </tr>
      <tr>
        <td>Area de:</td>
  	    <td>
  	      <?
            db_input('ob09_area',6,$Iob09_area,true,'text',1,"","ob09_areaINI","");             
          ?>
  	      <b>Até:</b>
          <?
            db_input('ob09_area',6,$Iob09_area,true,'text',1,"","ob09_areaFIN","");             
          ?>
  	    </td>
      </tr>
    </table>
  </fieldset>
  <input  name="emite2" id="emite2" type="button" value="Emitir Relatório" onclick="js_emite();" >
</form>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
</script>


<?
if(isset($ordem)){
  echo "<script>
       js_emite();
       </script>";  
}
$func_iframe = new janela('db_iframe','');
$func_iframe->posX=1;
$func_iframe->posY=20;
$func_iframe->largura=780;
$func_iframe->altura=430;
$func_iframe->titulo='Pesquisa';
$func_iframe->iniciarVisivel = false;
$func_iframe->mostrar();

?>

<script>

$("data").addClassName("field-size2");
$("data1").addClassName("field-size2");
$("ob09_areaINI").addClassName("field-size3");
$("ob09_areaFIN").addClassName("field-size3");

</script>