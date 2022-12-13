<?
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once ("libs/JSON.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<center>
<fieldset style="width: 700px;">
  <legend>Formulário</legend>
  <table border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">


      <script type="text/javascript">

        var PosMouseY, PosMoudeX;

        function js_comparaDatasname_data(dia,mes,ano){
          var objData        = document.getElementById('name_data');
      		objData.value      = dia+"/"+mes+'/'+ano;
        }

        var x = JSON.parse(parent.campos.value);
        var iLength = x.length;
        var html = '';
        for (var i = 0; i < iLength; i++ ) {

          var oCampo = x[i];
          html += '<tr>';
          html += '<td>';
          html += '<b>'+oCampo.label_form+'</b>';
          html += '</td>';

          html += '<td>';

          /**
           * Monta campo <select>
           */
          if (oCampo.valores_default.length > 0) {

            html += '<select name="'+oCampo.nome_campo+'">';

            for (var j = 0; j < oCampo.valores_default.length; j++) {

              aValorDefault = oCampo.valores_default[j].split('#&');
              sSelecionado = '';
              if (aValorDefault[0] == oCampo.default) {
                sSelecionado = 'selected';
              }

              html += '<option value="'+aValorDefault[0]+'" '+sSelecionado+'>'+aValorDefault[1]+'</option>';
            }
            html += '</select>';
          }

         /**
          * Monta campo <input type="text">
          */
          if (oCampo.valores_default.length == 0 && (oCampo.tipo_campo == 'varchar' ||
                                                     oCampo.tipo_campo == 'int4'    ||
                                                     oCampo.tipo_campo == 'int8'    ||
                                                     oCampo.tipo_campo == 'float4'  ||
                                                     oCampo.tipo_campo == 'int8'    ||
                                                     oCampo.tipo_campo == 'char'
                                                     )) {

            html += '<input type="text" name="'+oCampo.nome_campo+'" value="'+oCampo.default+'" size="'+oCampo.tamanho+'" maxlength="'+oCampo.tamanho+'">';
          }

         /**
          * Monta campo <textarea>
          */
         if (oCampo.tipo_campo == 'text') {
           html += '<textarea name="'+oCampo.nome_campo+'">'+oCampo.default+'</textarea>';
         }

         /**
          * Monta campo <date>
          */
          if (oCampo.tipo_campo == 'date') {

            html += '<input name="name_data" type="text" id="name_data"  value="" size="10" maxlength="10" autocomplete="off" onBlur="js_validaDbData(this);" onKeyUp="return js_mascaraData(this,event)" onFocus="js_validaEntrada(this);">';
            html += '<input name="name_data_dia"   type="hidden" title="" id="name_data_dia" value="" size="2"  maxlength="2" >';
            html += '<input name="name_data_mes"   type="hidden" title="" id="name_data_mes" value="" size="2"  maxlength="2" >';
            html += '<input name="name_data_ano"   type="hidden" title="" id="name_data_ano" value="" size="4"  maxlength="4" >';

            html += '<input value="D" type="button" name="dtjs_name_data" onclick="pegaPosMouse(event);show_calendar(\'name_data\',\'none\')"  >';
          }

          html += '</td>';
          html += '</tr>';
        }

        document.write(html);

      </script>

  </table>
</fieldset>
</center>
</body>
</html>

<script>
</script>