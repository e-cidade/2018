<?php
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_setor_classe.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");
require_once("classes/db_sanitario_classe.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clsetor            = new cl_setor;
$cliframe_seleciona = new cl_iframe_seleciona;
$clsetor->rotulo->label();
$clrotulo           = new rotulocampo;
$clrotulo->label("z01_nome");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class="body-default">

  <div class="container">

    <form name="form1" method="post">

    <table border="0">
      <tr>
        <td align="top" colspan="1">
           <?php

              $cliframe_seleciona->campos        = "j30_codi,j30_descr";
              $cliframe_seleciona->legenda       = "SETOR";
              $cliframe_seleciona->sql           = $clsetor->sql_query(""," * ","");
              $cliframe_seleciona->textocabec    = "darkblue";
              $cliframe_seleciona->textocorpo    = "black";
              $cliframe_seleciona->fundocabec    = "#aacccc";
              $cliframe_seleciona->fundocorpo    = "#ccddcc";
              $cliframe_seleciona->iframe_height = "150";
              $cliframe_seleciona->iframe_width  = "600";
              $cliframe_seleciona->iframe_nome   = "setor";
              $cliframe_seleciona->chaves        = "j30_codi, j30_descr";
              $cliframe_seleciona->dbscript      = "onClick='parent.js_nome(this)'";
              $cliframe_seleciona->marcador      = true;
              $cliframe_seleciona->js_marcador   = "parent.js_nome(this)";
              $cliframe_seleciona->iframe_seleciona(@$db_opcao);

              db_input('setor',"",0,true,'hidden',3,"");
          ?>
        </td>
      </tr>

      <tr>
       <td align="center" >
        <input type="submit" name="relatorio1" value="Gerar relatório" onClick="return js_imprime();">
       </td>
      </tr>
    </table>

   </form>

</div>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>

<script type="text/javascript">
function js_imprime(){

  if(document.form1.setor.value!=""){

    jan = window.open('','rel','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
    jan.moveTo(0,0);
    document.form1.action = "cad2_quadrasetor002.php";
    document.form1.target = "rel";
    document.form1.submit();
  }else{

    alert("Selecione os setores que deverão constar no relatório.");
    return false;
  }
}

function js_nome(obj){

  j34_setor = "";
  vir       = "";
  x         = 0;

  for(i=0;i<setor.document.form1.length;i++){

   if(setor.document.form1.elements[i].type == "checkbox"){

     if(setor.document.form1.elements[i].checked == true){

       valor      = setor.document.form1.elements[i].value.split("_")
       j34_setor += vir + valor[0];
       vir        = ",";
       x         += 1;
     }
   }
  }

  document.form1.setor.value = j34_setor;
  if(j34_setor == ""){
    document.form1.setor.value = '';
  }
}
</script>
</body>
</html>