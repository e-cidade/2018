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
require("libs/db_utils.php");
require("libs/db_app.utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_divida_classe.php");
include("classes/db_certidlivro_classe.php");
include("dbforms/db_funcoes.php");

$clrotulo         = new rotulocampo;
$cldivida         = new cl_divida;
$oDaoCertidLivro  = new cl_certidlivro;
$sSqlproximoLivro = "select coalesce(max(v25_numero),0) as livro from certidlivro";
$rsProximoLivro   = $oDaoCertidLivro->sql_record($sSqlproximoLivro);
$livro            = db_utils::fieldsMemory($rsProximoLivro, 0)->livro; 
$pagina           = 1;
$clrotulo->label("v01_livro");
$clrotulo->label("v01_dtoper");
$clrotulo->label("v14_certid");
$db_opcao = 1;
db_postmemory($HTTP_POST_VARS);
$dia = date("d", db_getsession("DB_datausu"));
$mes = date("m", db_getsession("DB_datausu"));
$ano = date("Y", db_getsession("DB_datausu"));
?>
<html>
 <head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <?
   db_app::load("scripts.js, estilos.css, prototype.js, strings.js");
  ?>
 </head>
 <body bgcolor=#CCCCCC onLoad="if(document.form1) document.form1.elements[0].focus()" >
<form class="container" name='form1' method="post">
  <fieldset>
    <legend> Reemissão do Livro das CDA's </legend>         
    <table class="form-container">
      <tr>
        <td>
          Tipo:
        </td>
        <td>
          <?
            $aTipos = array( 
                            1 => "Completo",
                            2 => "Resumido",
                            );            
            db_select('tipo',$aTipos,true,4,"style='width:95px' onchange='js_setTipo(this.value)'");                 
          ?>
        </td>
      </tr>
      <tr>
        <td>
          Data Correção: 
        </td>
        <td>
          <?=db_inputdata('datacorrecao', $dia,$mes,$ano,true,'text',4)?>
        </td>
      </tr>
      <tr>
        <td>
          Livro:
        </td> 
        <td>
          <?
            db_input("livro",10,4,true,"text",1);
          ?>
        </td>
      </tr>
      <tr>
        <td>
          Imprimir Origem da CDA:
        </td>
        <td>
          <?
            $aTipos = array( 
                            1 => "Sim",
                            2 => "Não",
                            );           
            db_select('imprimirorigem',$aTipos,true,4,"");                            
          ?>
        </td>
      </tr>
    </table>          
  </fieldset>
  <input type='button' value='Processar Livro' id='processar' onclick='js_emitirLivro()'>
</form>

  <? 
   db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
  ?>
 </body>
</html>
<script>
function js_setTipo(valor) {
  
  switch(valor) {
  
    case '1': 
      
      $('imprimirorigem').disabled = false;
      break;
   
   case '2':
     
     $('imprimirorigem').value    = 2;
     $('imprimirorigem').disabled = true;
     break;    
  }
}

function js_emitirLivro() {

  if ($F('livro') == '') {
  
    alert('Informe o número do livro.');
    $('livro').focus();
    return false;
  }
   var sUrl  = 'div2_emitelivrocda002.php?';
       sUrl += 'livro='+$F('livro');
       sUrl += '&tipo='+$F('tipo');
       sUrl += '&imprimirorigem='+$F('imprimirorigem');
       
  var jan = window.open(sUrl,'livro',
                        'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
  return true;   
  
}
</script>
<script>

$("datacorrecao").addClassName("field-size2");
$("livro").addClassName("field-size2");
$("tipo").setAttribute("rel","ignore-css");
$("tipo").style.width = "100%";
$("imprimirorigem").setAttribute("rel","ignore-css");
$("imprimirorigem").addClassName("field-size2");


</script>