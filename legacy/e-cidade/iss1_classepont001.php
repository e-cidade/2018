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
require_once("classes/db_classe_classe.php");
require_once("classes/db_classepont_classe.php");

$oGet  = db_utils::postMemory($_GET);
$oPost = db_utils::postMemory($_POST);

$clClasse     = new cl_classe();
$clClassepont = new cl_classepont();
$clClassepont->rotulo->label();

$clrotulo = new rotulocampo();
$clrotulo->label('q12_descr');

$lSqlErro = false;

if(isset($oPost->botao)) {
	
  if($oPost->botao == 'Incluir') {
  	   
    $rsClassepont = $clClassepont->sql_record($clClassepont->sql_query($oPost->q25_classe));
    
    if($clClassepont->numrows > 0) {
      
      $clClassepont->erro_msg = "Pontuação da classe, código $oPost->q25_classe já cadastrado. Inclusão abortada.";    
      $lSqlErro = true;      
      $db_opcao = 1;
      
    } else {
    
	    $clClassepont->q25_classe    = $oPost->q25_classe;
	    $clClassepont->q25_pontuacao = $oPost->q25_pontuacao;
	
	    db_inicio_transacao();
	    
	    $clClassepont->incluir($oPost->q25_classe);
	    
	    if($clClassepont->erro_status == "0") {
	      
	      $lSqlErro = true;
	      
	    }
	    
	    db_fim_transacao($lSqlErro);
	    
	    $db_opcao = 3;
    }
     
  } else if ($oPost->botao == 'Alterar') {
  	
    $clClassepont->q25_classe    = $oPost->q25_classe;
    $clClassepont->q25_pontuacao = $oPost->q25_pontuacao;
    
    db_inicio_transacao();

    $clClassepont->alterar($oPost->q25_classe);
    
    if($clClassepont->erro_status == "0") {
      
      $lSqlErro = true;
      
    }
    
    db_fim_transacao($lSqlErro);
    
    $db_opcao = 3;
    
  } else if ($oPost->botao == 'Excluir') {
    
    $clClassepont->q25_classe    = $oPost->q25_classe;
    
    db_inicio_transacao();
    
    $clClassepont->excluir($oPost->q25_classe);
    
    if($clClassepont->erro_status == "0") {
      
      $lSqlErro = true;
      
    }
    
    db_fim_transacao($lSqlErro);
    
    $db_opcao = 3;
    
  }	
  
} elseif(isset($oGet->classe) and ($oGet->classe != '')) {
	
  $rsClassepont = $clClassepont->sql_record($clClassepont->sql_query($oGet->classe));
  
  if($clClassepont->numrows > 0) {
    
    $oClassepont   = db_utils::fieldsMemory($rsClassepont, 0);
    $q25_classe    = $oClassepont->q25_classe;
    $q25_pontuacao = $oClassepont->q25_pontuacao;
    $q12_descr     = $oClassepont->q12_descr;
    
    $db_opcao      = 3;
    
  } else {
    
  	$rsClasse = $clClasse->sql_record($clClasse->sql_query_file($oGet->classe));
  	
  	if($clClasse->numrows > 0) {
  		
  		$oClasse       = db_utils::fieldsMemory($rsClasse, 0);
  		$q25_classe    = $oClasse->q12_classe;
  		$q12_descr     = $oClasse->q12_descr;
  		$q25_pontuacao = '';
  		
  		
  		
  	} else {
  		$q25_classe    = '';
  		$q25_descr     = "CHAVE($oGet->classe) NÃO ENCONTRADO";
  		$q25_pontuacao = '';
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
  <legend><strong>Classe</strong></legend>
  
  <table align="center">
  <tr>
    <td>
    <?
      db_ancora($Lq25_classe, 'js_pesquisaClasse(true)', 1);
     ?>
    </td>
    <td>
    <?
      db_input('q25_classe', 10, $Iq25_classe, true, 'text', $db_opcao, 'onchange="js_pesquisaClasse(false)"');
      db_input('q12_descr', 40, $Iq12_descr, true, 'text', 3);
    ?>
    </td>
  </tr>
  
  <tr>
    <td>
    <?=$Lq25_pontuacao ?>
    </td>
    <td>
    <?
      db_input('q25_pontuacao', 10, $Iq25_pontuacao, true, 'text', 1);
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
  js_OpenJanelaIframe('top.corpo', 'db_iframe_classepont', 'func_classepont.php?funcao_js=parent.js_carregaClasse|q25_classe|q25_pontuacao', 'Pesquisa', true);
}

function js_pesquisaClasse(lMostra) {
  if(lMostra) {
    js_OpenJanelaIframe('top.corpo', 'db_iframe_classepont', 'func_classe.php?funcao_js=parent.js_carregaClasse|q12_classe|q12_descr', 'Pesquisa', true);
  } else {
    js_OpenJanelaIframe('top.corpo', 'db_iframe_classepont', 'func_classe.php?pesquisa_chave='+document.form1.q25_classe.value+'&funcao_js=parent.js_carregaClasseHide', 'Pesquisa', false);
  }
}

function js_carregaClasse(iClasse, sDescr) {
  window.location = 'iss1_classepont001.php?classe=' + iClasse;
  db_iframe_classepont.hide();
}

function js_carregaClasseHide(sDescr, lErro) {
  if(!lErro) {
    window.location = 'iss1_classepont001.php?classe=' + document.form1.q25_classe.value;
  } else {
    document.form1.q25_classe.value = '';
    document.form1.q12_descr.value  = sDescr;
  }
}

function js_novoRegistro() {
  window.location = 'iss1_classepont001.php';
}

</script>
</body>
</html>
<?
if(isset($oPost->botao)) {
	db_msgbox($clClassepont->erro_msg);
	db_redireciona('iss1_classepont001.php');
}
?>