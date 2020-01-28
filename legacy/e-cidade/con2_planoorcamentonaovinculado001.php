<?php
/*
 *     E-cidade Software Público para Gestão Municipal                
 *  Copyright (C) 2014  DBseller Serviços de Informática             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa é software livre; você pode redistribuí-lo e/ou     
 *  modificá-lo sob os termos da Licença Pública Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versão 2 da      
 *  Licença como (a seu critério) qualquer versão mais nova.          
 *                                                                    
 *  Este programa e distribuído na expectativa de ser útil, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implícita de              
 *  COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM           
 *  PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Você deve ter recebido uma cópia da Licença Pública Geral GNU     
 *  junto com este programa; se não, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Cópia da licença no diretório licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_app.utils.php");
require_once ("dbforms/db_funcoes.php");
require_once ("libs/db_liborcamento.php");
require_once ("libs/db_usuariosonline.php");
require_once ("libs/db_libcontabilidade.php");
require_once ("classes/db_conparametro_classe.php");

$clestrutura_sistema = new cl_estrutura_sistema;

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">

<?
db_app::load("scripts.js, prototype.js, strings.js, dbautocomplete.widget.js");
db_app::load("estilos.css");
?>

<script>

function js_emite(){

  var exp = /\.|\-/g;
  var estrutural = document.form1.c90_estrutcontabil.value;
      estrutural = estrutural.replace(exp,"");
  jan = window.open('con2_planoorcamentonaovinculado002.php?estrutual='+estrutural,
                                                           '','width='+(screen.availWidth-5)+
                                                           ',height='+(screen.availHeight-40)+
                                                           ',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}
</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <div style='margin-top: 40px;'></div>
  <center>
  <fieldset style='width: 250px;'>
  <legend><b>Plano Orçamentário não Vínculado PCASP</b></legend>
  <table  align="center">
    <form name="form1" method="post" action="" >
      <tr>
      <?php 
        $clestrutura_sistema->autocompletar = false;
        $clestrutura_sistema->size          = 30;
        $clestrutura_sistema->botao         = false;
        $clestrutura_sistema->reload        = true ;
        $clestrutura_sistema->estrutura_sistema('c90_estrutcontabil');
      ?>
      </tr>
      <tr>
        <td colspan="2" align = "center"> 
          <input  name="emite" id="emite" type="button" value="Processar" onclick="js_emite();" >
        </td>
      </tr>
    </form>
  </table>
  </fieldset>
  </center>
  <?
    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
  ?>
</body>
</html>