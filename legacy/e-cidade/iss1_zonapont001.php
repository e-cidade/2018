<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("classes/db_zonas_classe.php");
require_once("classes/db_zonapont_classe.php");

$oGet  = db_utils::postMemory($_GET);
$oPost = db_utils::postMemory($_POST);

$clZona     = new cl_zonas();
$clZonapont = new cl_zonapont();
$clZonapont->rotulo->label();

$clrotulo = new rotulocampo();
$clrotulo->label('j50_descr');

$lSqlErro = false;

if(isset($oPost->botao)) {
  
  if($oPost->botao == 'Incluir') {
       
    $rsZonapont = $clZonapont->sql_record($clZonapont->sql_query($oPost->q26_zona));
    
    if($clZonapont->numrows > 0) {
      
      $clZonapont->erro_msg = "Pontuação da zona, código $oPost->q26_zona já cadastrado. Inclusão abortada.";   
       
      $lSqlErro = true;      
      $db_opcao = 1;
      
    } else {
    
      $clZonapont->q26_zona      = $oPost->q26_zona;
      $clZonapont->q26_pontuacao = $oPost->q26_pontuacao;
  
      db_inicio_transacao();
      
      $clZonapont->incluir($oPost->q26_zona);
      
      if($clZonapont->erro_status == "0") {
        
        $lSqlErro = true;
        
      }
      
      db_fim_transacao($lSqlErro);
      
      $db_opcao = 3;
    }
     
  } else if ($oPost->botao == 'Alterar') {
    
    $clZonapont->q26_zona      = $oPost->q26_zona;
    $clZonapont->q26_pontuacao = $oPost->q26_pontuacao;
    
    db_inicio_transacao();

    $clZonapont->alterar($oPost->q26_zona);
    
    if($clZonapont->erro_status == "0") {
      
      $lSqlErro = true;
      
    }
    
    db_fim_transacao($lSqlErro);
    
    $db_opcao = 3;
    
  } else if ($oPost->botao == 'Excluir') {
    
    $clZonapont->q26_zona    = $oPost->q26_zona;
    
    db_inicio_transacao();
    
    $clZonapont->excluir($oPost->q26_zona);
    
    if($clZonapont->erro_status == "0") {
      
      $lSqlErro = true;
      
    }
    
    db_fim_transacao($lSqlErro);
    
    $db_opcao = 3;
    
  } 
  
} elseif(isset($oGet->zona) and ($oGet->zona != '')) {
  
  $rsZonapont = $clZonapont->sql_record($clZonapont->sql_query($oGet->zona));
  
  if($clZonapont->numrows > 0) {
    
    $oZonapont     = db_utils::fieldsMemory($rsZonapont, 0);
    $q26_zona      = $oZonapont->q26_zona;
    $q26_pontuacao = $oZonapont->q26_pontuacao;
    $j50_descr     = $oZonapont->j50_descr;
    
    $db_opcao      = 3;
    
  } else {
    
    $rsZona = $clZona->sql_record($clZona->sql_query_file($oGet->zona));
    
    if($clZona->numrows > 0) {
      
      $oZona         = db_utils::fieldsMemory($rsZona, 0);
      $q26_zona      = $oZona->j50_zona;
      $j50_descr     = $oZona->j50_descr;
      $q26_pontuacao = '';
      
      
      
    } else {
      $q26_zona      = '';
      $q26_descr     = "CHAVE($oGet->zona) NÃO ENCONTRADO";
      $q26_pontuacao = '';
    }
    
    $db_opcao      = 1;
    
  }
  
} else {
  
  $db_opcao      = 1;
  
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
  db_app::load('scripts.js');
  db_app::load('estilos.css');
?>
</head>
<body bgcolor="#CCCCCC">
<form name="form1" id="form1" method="post">
<fieldset style="margin: 30px auto; width: 600px; text-align: center">
  <legend><strong>Zona</strong></legend>
  
  <table align="center">
  <tr>
    <td>
    <?
      db_ancora($Lq26_zona, 'js_pesquisaZona(true)', 1);
     ?>
    </td>
    <td>
    <?
      db_input('q26_zona', 10, $Iq26_zona, true, 'text', $db_opcao, 'onchange="js_pesquisaZona(false)"');
      db_input('j50_descr', 40, $Ij50_descr, true, 'text', 3);
    ?>
    </td>
  </tr>
  
  <tr>
    <td>
    <?=$Lq26_pontuacao ?>
    </td>
    <td>
    <?
      db_input('q26_pontuacao', 10, $Iq26_pontuacao, true, 'text', 1);
    ?>
    </td>
  </tr>
  
  </table>
  
  <?
    if($db_opcao == 1) {
      echo "<input type='submit' name='botao' id='botao' value='Incluir'>";
    } else {
      echo "<input type='submit' name='botao' id='botao' value='Alterar'>";
      echo "&nbsp;";
      echo "<input type='submit' name='botao' id='botao' value='Excluir'>";
      echo "&nbsp;";
      echo "<input type='button' name='botao' id='botao' value='Novo' onclick='js_novoRegistro()'>";
    }
    echo "&nbsp;";
    echo "<input type='button' name='pesquisar' id='pesquisar' value='Pesquisar' onclick='js_pesquisa()'>";
  ?>
  
</fieldset>

<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</form>
<script type="text/javascript">
function js_pesquisa() {
  js_OpenJanelaIframe('top.corpo', 'db_iframe_zonapont', 'func_zonapont.php?funcao_js=parent.js_carregaZona|q26_zona|q26_pontuacao', 'Pesquisa', true);
}

function js_pesquisaZona(lMostra) {
  if(lMostra) {
    js_OpenJanelaIframe('top.corpo', 'db_iframe_zonapont', 'func_zonas.php?funcao_js=parent.js_carregaZona|j50_zona|j50_descr', 'Pesquisa', true);
  } else {
    js_OpenJanelaIframe('top.corpo', 'db_iframe_zonapont', 'func_zonas.php?pesquisa_chave='+document.form1.q26_zona.value+'&funcao_js=parent.js_carregaZonaHide', 'Pesquisa', false);
  }
}

function js_carregaZona(iZona, sDescr) {
  window.location = 'iss1_zonapont001.php?zona=' + iZona;
  db_iframe_zonapont.hide();
}

function js_carregaZonaHide(sDescr, lErro) {
  if(!lErro) {
    window.location = 'iss1_zonapont001.php?zona=' + document.form1.q26_zona.value;
  } else {
    document.form1.q26_zona.value = '';
    document.form1.j50_descr.value  = sDescr;
  }
}

function js_novoRegistro() {
  window.location = 'iss1_zonapont001.php';
}

</script>
</body>
</html>
<?
if(isset($oPost->botao)) {
  db_msgbox($clZonapont->erro_msg);
  db_redireciona('iss1_zonapont001.php');
}
?>