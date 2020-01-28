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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_usuariosonline.php");
require_once ("classes/db_caracter_classe.php");
require_once ("dbforms/db_funcoes.php");
require_once ("dbforms/db_classesgenericas.php");

parse_str( $_SERVER["QUERY_STRING"] );
db_postmemory( $_POST );

$clcaracter         = new cl_caracter;
$cliframe_seleciona = new cl_iframe_seleciona;
$clcaracter->rotulo->label();
$clrotulo = new rotulocampo;
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
<body class='body-default' onLoad="a=1" >
  
  <div class ='container'>
    <form name="form1" method="post" action="cad2_iptuconstr002.php" target="rel">

      <table >
        <tr>
          <td >
            <?php
              $cliframe_seleciona->campos        = "j31_codigo, j31_descr, j32_descr";
              $cliframe_seleciona->legenda       ="QUE NÃO CONTENHAM ESTAS CARACTERÍSTICAS DE FACE";
              $cliframe_seleciona->sql           = $clcaracter->sql_query( "", " * ", "j32_descr", "j32_tipo = 'F'");
              $cliframe_seleciona->textocabec    = "darkblue";
              $cliframe_seleciona->textocorpo    = "black";
              $cliframe_seleciona->fundocabec    = "#aacccc";
              $cliframe_seleciona->fundocorpo    = "#ccddcc";
              $cliframe_seleciona->iframe_height = "250";
              $cliframe_seleciona->iframe_width  = "700";
              $cliframe_seleciona->iframe_nome   = "ncaracteristicas";
              $cliframe_seleciona->chaves        = "j31_codigo,j31_descr";
              $cliframe_seleciona->dbscript      = "onClick='parent.js_nome(this)'";
              $cliframe_seleciona->marcador      = true;
              $cliframe_seleciona->iframe_seleciona(@$db_opcao);    
            ?>
          </td>
        </tr>
        <script>
        function js_nome(obj) {

          if (obj.checked == true) {
            eval('parent.iframe_g1.caracteristicas.document.form1.'+obj.name+'.disabled = true');
          } else {

            eval('parent.iframe_g1.caracteristicas.document.form1.'+obj.name+'.disabled = false');
            removeItem( obj );
          }

          if (obj.checked == true) {

            if (parent.iframe_g1.document.form1.chaves_caract.value == "") {

              var valor = obj.value.split("_");
              parent.iframe_g1.document.form1.chaves_caract.value = valor[0];
            } else {

              var valor = obj.value.split("_");
              parent.iframe_g1.document.form1.chaves_caract.value += ',' + valor[0];
            }
          }
        }

        function removeItem( oObjeto ) {

          var aSelecionados      = parent.iframe_g1.document.form1.chaves_caract.value.split(',');
          var aNovosSelecionados = new Array();

          for( var iContador = 0; iContador < aSelecionados.length; iContador++ ) {

            var aValor = oObjeto.value.split( '_' );

            if( aSelecionados[iContador] != aValor[0] ) {
              aNovosSelecionados.push( aSelecionados[iContador] );
            }
          }

          parent.iframe_g1.document.form1.chaves_caract.value = aNovosSelecionados.join( ',' );
        }
        </script>
      </table>
    </form>
  </div>  

</body>
</html>