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
    jan = window.open('div2_certid002.php?ordem='+document.form1.ordem.value+'&rela='+document.form1.rela.value+'&data='+document.form1.data_ano.value+'-'+document.form1.data_mes.value+'-'+document.form1.data_dia.value+'&data1='+document.form1.data1_ano.value+'-'+document.form1.data1_mes.value+'-'+document.form1.data1_dia.value+'&iniciais='+document.form1.inicial.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    jan.moveTo(0,0);
  }
  </script>  
  <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body bgcolor=#CCCCCC onLoad="a=1">
  
<form class="container" name="form1" method="post" action="">
  <fieldset>
  	<legend>Certidão</legend>
    <table  class="form-container">
      <tr>
	    <td nowrap title="Ordem Todas/Dívida Ativa/Parceladas">
	      Certidões:
	    </td>
	    <td>
	    <?
          $tipo_ordem = array("t"=>"Todas","d"=>"Dívida Ativa","p"=>"Parceladas");
          db_select("ordem",$tipo_ordem,true,2);
        ?>
	    </td>
	  </tr>
      <tr>
        <td nowrap title="Data Inicial da Emissão de Certidão">
          De: 
        </td>      
        <td>
          <?db_inputdata("data","","","","true","text",2)?>
        </td>
      </tr>
      <tr>
        <td nowrap title="Data Final da Emissão de Certidão">
          Até :
        </td>      
        <td>
          <?db_inputdata("data1","","","","true","text",2)?>
        </td>
      </tr>
      <tr>
	    <td nowrap title="Relátorio Resumido/Completo" >
	      Relátorio :
	    </td>
	    <td>
	      <? 
	        $tipo_ordem1 = array("r"=>"Resumido","c"=>"Completo");
	        db_select("rela",$tipo_ordem1,true,2); 
	      ?>
	    </td>
	  </tr>
      <tr>
	    <td nowrap title="" >
	    Iniciais :
	    </td>
	    <td>
	      <?
            $tipo_inicial1 = array("0"=>"Todas","1"=>"Com Inicial Emitida", "2"=>"Sem Inicial Emitida");
	        db_select("inicial",$tipo_inicial1,true,2);
          ?>
	    </td>
	  </tr>  
    </table>
  </fieldset>
  <input  name="emite2" id="emite2" type="button" value="Emitir Relátorio" onclick="js_emite();" >  
</form>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
function js_pesquisatabdesc(mostra){
     if(mostra==true){
       db_iframe.jan.location.href = 'func_tabdesc.php?funcao_js=parent.js_mostratabdesc1|0|2';
       db_iframe.mostraMsg();
       db_iframe.show();
       db_iframe.focus();
     }else{
       db_iframe.jan.location.href = 'func_tabdesc.php?pesquisa_chave='+document.form1.codsubrec.value+'&funcao_js=parent.js_mostratabdesc';
     }
}
function js_mostratabdesc(chave,erro){
  document.form1.k07_descr.value = chave;
  if(erro==true){
     document.form1.codsubrec.focus();
     document.form1.codsubrec.value = '';
  }
}
function js_mostratabdesc1(chave1,chave2){
     document.form1.codsubrec.value = chave1;
     document.form1.k07_descr.value = chave2;
     db_iframe.hide();
}
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
$("ordem").setAttribute("rel","ignore-css");
$("ordem").addClassName("field-size4");
$("rela").setAttribute("rel","ignore-css");
$("rela").addClassName("field-size4");
$("inicial").setAttribute("rel","ignore-css");
$("inicial").addClassName("field-size4");

</script>