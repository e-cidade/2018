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
require_once ("classes/db_lote_classe.php");
require_once ("dbforms/db_funcoes.php");
require_once ("dbforms/db_classesgenericas.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($_POST);
$cllote             = new cl_lote;
$cliframe_seleciona = new cl_iframe_seleciona;
$cllote->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
if (isset($j37_quadra) && $j37_quadra != "") {
  
  $quadra = split(",",$j37_quadra);
  $vir    = "";
  $qua    = "";
  for ($i = 0; $i < count($quadra); $i++) {
    
    $qua .= $vir."'".$quadra[$i]."'";
    $vir  = ",";
  }
}
if (isset($j37_setor) && $j37_setor != "") {
  
  $setor     = split(",",$j37_setor);
  $vir       = "";
  $qua1      = "";
  $setor_old = "";
  for ($i = 0; $i < count($setor); $i++) {
    
    if ($setor[$i] != $setor_old) {
      $qua1 .= $vir."'".$setor[$i]."'";
    }
    $setor_old = $setor[$i];
    $vir       = ",";
  }
}
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class='body-default' onLoad="a=1" >
  
  <div class="container">
    
    <form name="form1" method="post" action="cad2_iptuconstr002.php" target="rel">
      <table class='form-container'>
        <tr>
          <td >
            <strong>Opções:</strong>
            <select id="ver" name="ver" onChange="parent.iframe_g1.document.form1.temruas.value = this.value">
              <option name="cruas" value="t">Com as ruas selecionadas</option>
              <option name="cruas" value="f">Sem as ruas selecionadas</option>
            </select>
          </td>
        </tr>
        <tr>
          <td nowrap >
            <?php
              $aux                 = new cl_arquivo_auxiliar;
              $aux->cabecalho      = "<strong>RUAS</strong>";
              $aux->codigo         = "j14_codigo";
              $aux->descr          = "j14_nome";
              $aux->nomeobjeto     = 'ruas';
              $aux->funcao_js      = 'js_mostra';
              $aux->funcao_js_hide = 'js_mostra1';
              $aux->sql_exec       = "";
              $aux->func_arquivo   = "func_ruas.php";
              $aux->nomeiframe     = "iframa_ruas";
              $aux->localjan       = "";
              $aux->onclick        = "";
              $aux->db_opcao       = 2;
              $aux->tipo           = 2;
              $aux->top            = 0;
              $aux->linhas         = 10;
              $aux->vwhidth        = 400;
              $aux->funcao_gera_formulario();
            ?>    
            <script>
              parent.iframe_g1.document.form1.temruas.value = 't';
              
              function js_ver_rua() {
                
                for (i = 0; i < parent.iframe_g6.document.form1.length; i++) {
                  
                  if (parent.iframe_g6.document.form1.elements[i].name == "ruas1[]") {
                    
                    for (x = 0; x < parent.iframe_g6.document.form1.elements[i].length; x++) {
                      
                      if(parent.iframe_g6.document.form1.elements[i].options[x].value == document.form1.j14_codigo.value) {
                        
                        alert('Rua já selecionada para não constar no relatório')
                        document.form1.j14_codigo.value = '';
                      }
                    }
                  }
                }
              }
            </script>
          </td>
        </tr>
      </table>
    </form>
  </div>
</body>
</html>
<script>

$("ver").setAttribute("rel","ignore-css");
$("ver").addClassName("field-size5");
$("j14_codigo").addClassName("field-size2");
$("j14_nome").addClassName("field-size7");
$("ruas").setAttribute("rel", "ignore-css");
$("ruas").style.width = "100%";

function js_limpacampos() {
  
  for (i = 0; i < document.form1.length; i++) {
    
    if (document.form1.elements[i].type == 'text') {
      document.form1.elements[i].value = '';
    }
  }
}
</script>