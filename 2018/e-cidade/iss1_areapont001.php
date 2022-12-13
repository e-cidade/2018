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
require_once("classes/db_areapont_classe.php");

$oGet  = db_utils::postMemory($_GET);
$oPost = db_utils::postMemory($_POST);

$clAreapont = new cl_areapont();
$clAreapont->rotulo->label();

$clrotulo = new rotulocampo();

$lSqlErro  = false;

$lConflito = false;

$iNumrows = 0;

if(isset($oPost->botao)) {
	
  if(isset($oPost->q28_quantini) && $oPost->q28_quantfim != '' &&
     isset($oPost->q28_quantfim) && $oPost->q28_quantini != '' &&
     $oPost->botao != 'Excluir') { 
      
    //valida se a qtde informada nao está em nenhum intervalo ja cadastrado       
    $rsValida = $clAreapont->sql_record($clAreapont->sql_query_valida_qtde($oPost->q28_sequencia, 
                                                                           $oPost->q28_quantini, 
                                                                           $oPost->q28_quantfim));

    $iQuantidade = db_utils::fieldsMemory($rsValida, 0)->quantidade;
    
    if($iQuantidade > 0) {
      $lConflito = true;
      $lSqlErro  = true;   
      $clAreapont->erro_msg = "Já existe um intervalo com a quantidade informada.";   
      
      $db_opcao = $oPost->botao == 'Incluir' ? 1 : 3;
    }
    
  }
  
  if($oPost->botao == 'Incluir' && !$lConflito) {
    
    if($oPost->q28_sequencia != '') {
      $rsAreapont = $clAreapont->sql_record($clAreapont->sql_query($oPost->q28_sequencia));
      $iNumrows     = $clAreapont->numrows;
    }
    
    if($iNumrows > 0) {
      
      $clAreapont->erro_msg = "Pontuação da area, código $oPost->q28_sequencia já cadastrado. Inclusão abortada.";   
       
      $lSqlErro = true;      
      $db_opcao = 1;
      
    } else {
    
      $clAreapont->q28_sequencia = $oPost->q28_sequencia;
      $clAreapont->q28_quantini  = $oPost->q28_quantini;
      $clAreapont->q28_quantfim  = $oPost->q28_quantfim;
      $clAreapont->q28_pontuacao = $oPost->q28_pontuacao;
  
      db_inicio_transacao();
      
      $clAreapont->incluir(null);
      
      $db_opcao = 3;
      
      if($clAreapont->erro_status == "0") {

        $lSqlErro = true;
        
        $db_opcao = 1;
        
      }
      
      db_fim_transacao($lSqlErro);
      
    }
     
  } else if ($oPost->botao == 'Alterar' && !$lConflito) {
    
    $clAreapont->q28_sequencia = $oPost->q28_sequencia;
    $clAreapont->q28_quantini  = $oPost->q28_quantini;
    $clAreapont->q28_quantfim  = $oPost->q28_quantfim;
    $clAreapont->q28_pontuacao = $oPost->q28_pontuacao;
    
    db_inicio_transacao();

    $clAreapont->alterar($oPost->q28_sequencia);
    
    if($clAreapont->erro_status == "0") {
      
      $lSqlErro = true;
      
    }
    
    db_fim_transacao($lSqlErro);
    
    $db_opcao = 3;
    
  } else if ($oPost->botao == 'Excluir') {
    
    $clAreapont->q28_sequencia    = $oPost->q28_sequencia;
    
    db_inicio_transacao();
    
    $clAreapont->excluir($oPost->q28_sequencia);
    
    if($clAreapont->erro_status == "0") {
      
      $lSqlErro = true;
      
    }
    
    db_fim_transacao($lSqlErro);
    
    $db_opcao = 3;
    
  } 
  
} elseif(isset($oGet->codigo) and ($oGet->codigo != '')) {
  
  $rsAreapont = $clAreapont->sql_record($clAreapont->sql_query($oGet->codigo));
  
  if($clAreapont->numrows > 0) {
    
    $oAreapont   = db_utils::fieldsMemory($rsAreapont, 0);
    $q28_sequencia = $oAreapont->q28_sequencia;
    $q28_quantini  = $oAreapont->q28_quantini;
    $q28_quantfim  = $oAreapont->q28_quantfim;
    $q28_pontuacao = $oAreapont->q28_pontuacao;
    
    $db_opcao      = 3;
    
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
<fieldset style="margin: 30px auto; width: 400px; text-align: center">
  <legend><strong>&Aacute;rea</strong></legend>
  
  <table align="center">
  <tr title="<?=$Tq28_sequencia?>">
    <td>
    <?=$Lq28_sequencia?>
    </td>
    <td>
    <?
      db_input('q28_sequencia', 10, $Iq28_sequencia, true, 'text', 3);
    ?>
    </td>
  </tr>
  
  <tr title="<?=$Tq28_quantini ?>">
    <td>
    <?=$Lq28_quantini?>
    </td>
    <td>
    <?
      db_input('q28_quantini', 10, $Iq28_quantini, true, 'text', 1);
    ?>
    </td>
  </tr>
  
  <tr title="<?=$Tq28_quantfim ?>">
    <td>
    <?=$Lq28_quantfim?>
    </td>
    <td>
    <?
      db_input('q28_quantfim', 10, $Iq28_quantfim, true, 'text', 1);
    ?>
    </td>
  </tr>  
  
  <tr title="<?=$Tq28_pontuacao ?>">
    <td>
    <?=$Lq28_pontuacao ?>
    </td>
    <td>
    <?
      db_input('q28_pontuacao', 10, $Iq28_pontuacao, true, 'text', 1);
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
  js_OpenJanelaIframe('top.corpo', 'db_iframe_areapont', 'func_areapont.php?funcao_js=parent.js_carregaArea|q28_sequencia', 'Pesquisa', true);
}

function js_carregaArea(iArea) {
  window.location = 'iss1_areapont001.php?codigo=' + iArea;
  db_iframe_areapont.hide();
}

function js_carregaAreaHide(sDescr, lErro) {
  if(!lErro) {
    window.location = 'iss1_areapont001.php?codigo=' + document.form1.q28_classe.value;
  } else {
    document.form1.q28_classe.value = '';
    document.form1.q12_descr.value  = sDescr;
  }
}

function js_novoRegistro() {
  window.location = 'iss1_areapont001.php';
}

</script>
</body>
</html>
<?
if(isset($oPost->botao)) {
  db_msgbox($clAreapont->erro_msg);
  
  if(!$lSqlErro){
    db_redireciona('iss1_areapont001.php');
  }
}
?>