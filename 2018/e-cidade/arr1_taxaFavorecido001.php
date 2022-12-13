<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_taxa_classe.php"));
require_once(modification("classes/db_favorecidotaxa_classe.php"));
require_once(modification("classes/db_favorecido_classe.php"));
$cltaxa           = new cl_taxa;
$clFavorecidoTaxa = new cl_favorecidotaxa;
$clFavorecido     = new cl_favorecido;
$lFavorecido      = false;
$oRotuloCampos    = new rotulocampo;
$oGet             = db_utils::postMemory($_GET);
$oPost            = db_utils::postMemory($_POST);
$oRotuloCampos->label("z01_nome");
$oRotuloCampos->label("z01_numcgm");

if (isset($oGet->lAlteracao)){
	
	db_msgbox("Favorecido Vinculado à Taxa com Sucesso.");
}

if (isset($oGet->ar36_sequencial)) {
	
	$rsTaxa = $cltaxa->sql_record($cltaxa->sql_query($oGet->ar36_sequencial,"*","",""));
	db_fieldsmemory($rsTaxa,0); 
	
	// verificamos se ja existe favorecido para a taxa
	$sCamposFavorecidoTaxa = "v87_favorecido,v87_sequencial";
	$sSqlFavorecidoTaxa    = $clFavorecidoTaxa->sql_query(null,$sCamposFavorecidoTaxa,"","v87_taxa = {$ar36_sequencial}");
	$rsFavorecidoTaxa      = $clFavorecidoTaxa->sql_record($sSqlFavorecidoTaxa);
	if ($clFavorecidoTaxa->numrows > 0) {
		$lFavorecido = true;
		$db_opcao = 2;
  	db_fieldsmemory($rsFavorecidoTaxa,0);
  	
  	// buscamos os dados do favorecido
  	$sSqlFavorecido = $clFavorecido->sql_query(null,"v86_sequencial,z01_numcgm,z01_nome",null,"v86_sequencial = ".@$v87_favorecido);
  	$rsFavorecido   = $clFavorecido->sql_record($sSqlFavorecido);
  	db_fieldsmemory($rsFavorecido,0);
  	
	} else {
		
		$db_opcao = 1;
	}
}



if (isset($incluir)) {
	
	db_inicio_transacao();
	
	try {
		
		$clFavorecidoTaxa->v87_favorecido = $v86_sequencial;
		$clFavorecidoTaxa->v87_taxa       = $ar36_sequencial;
		
		if ($lFavorecido == false) {
			
	  	$clFavorecidoTaxa->incluir(null);
	  
		} else {
			
			$clFavorecidoTaxa->alterar($ar36_sequencial);
			
		}
		if($clFavorecidoTaxa->erro_status == '0'){
			throw new Exception($clFavorecidoTaxa->erro_msg);
		}
		db_msgbox("Favorecido Vinculado à Taxa com Sucesso.");
		db_fim_transacao(false);
		$db_opcao = 2;
		
	} catch (Exception $eException) {
		
		db_msgbox($eException->getMessage());
		db_fim_transacao(true);
	}
}
if (isset($alterar)) {
  
  db_inicio_transacao();
  
  try {
  	
  	$clFavorecidoTaxa->v87_sequencial = $v87_sequencial;
    $clFavorecidoTaxa->v87_favorecido = $oPost->v86_sequencial;
    $clFavorecidoTaxa->v87_taxa       = $ar36_sequencial;
    $clFavorecidoTaxa->alterar($clFavorecidoTaxa->v87_sequencial);
    if($clFavorecidoTaxa->erro_status == '0'){
      throw new Exception($clFavorecidoTaxa->erro_msg);
    }
    db_fim_transacao(false);
    $db_opcao = 2;
    db_redireciona("arr1_taxaFavorecido001.php?lAlteracao=1&ar36_sequencial=".$ar36_sequencial);
  } catch (Exception $eException) {
    
    db_msgbox($eException->getMessage());
    db_fim_transacao(true);
  }
}
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?
      db_app::load("scripts.js, prototype.js, widgets/windowAux.widget.js,strings.js,widgets/dbtextField.widget.js,
                   dbmessageBoard.widget.js,dbautocomplete.widget.js,dbcomboBox.widget.js,
                   datagrid.widget.js");
      db_app::load("estilos.css,grid.style.css");
    ?>
  </head>
  <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
		<table width="790" border="0" cellpadding="0" cellspacing="0" >
		  <tr> 
		    <td width="360" height="18">&nbsp;</td>
		    <td width="263">&nbsp;</td>
		    <td width="25">&nbsp;</td>
		    <td width="140">&nbsp;</td>
		  </tr>
		</table>  
    <center>
      <form name='form1' method="post">
        <table>
          <tr>
            <td>
              <fieldset>
              <legend><b>Cadastro de Favorecidos</b></legend>
               <table>
                 <tr>
                   <td>
                     <b>Taxa</b>
                   </td>
                   <td>
                     <?
                     db_input("ar36_sequencial", 10, '', true, "text", 3, ""); 
                     db_input("ar36_descricao", 40, '', true, "text", 3, "");
                     ?>
                   </td>
                 </tr>
                 <tr>
                   <td>
                     <b><?db_ancora("Favorecido", "js_pesquisacgm(true)", 1)?></b>
                   </td>
                   <td>
                     <?
                      db_input("z01_numcgm", 10, $Iz01_numcgm, true, "text", 1, "onchange='js_pesquisacgm(false);situacaoCpf();'");
                      db_input("z01_nome", 40, $Iz01_nome, true, "text", 3);
                      db_input("v86_sequencial", 10, '', true, "hidden", 3);
                     ?>
                        <input type='button' value='Novo' name='novo' id='novo' onclick="js_novoFavorecido();" >
                        <input type='button' value='alterar' name='alterar' id='alterar' onclick="js_alterarFavorecido(z01_numcgm.value);" >
                   </td>
                 </tr>
               </table>
             </fieldset>
            </td>
          </tr>
           <tr>
	           <td colspan="2" align="center">
	              <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="incluir" name='incluir' value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>"  >
	           </td>
           </tr>
        </table>
      </form>
    </center>
  </body>
</html>
<script>
function js_pesquisacgm(mostra){

  if (mostra) {
     js_OpenJanelaIframe('', 
                         'db_iframe_favorecidos', 
                         'func_favorecido.php?funcao_js=parent.js_mostracgm1|z01_nome|z01_numcgm|Sequencial&filtro=1',
                         'Pesquisar CGM',
                         true,'0');
  } else {
    if(document.form1.z01_numcgm.value != ''){ 
       js_OpenJanelaIframe('',
                           'db_iframe_favorecidos',
                           'func_favorecido.php?sCgm=1&pesquisa_chave='+$F('z01_numcgm')+
                           '&funcao_js=parent.js_mostracgm',
                           'Pesquisa',
                           false,
                           '0');
    } else {
      document.form1.z01_numcgm.value = ''; 
    }
  }
}

function js_mostracgm(erro, chave, chave2, chave3){

  document.form1.z01_nome.value = chave2;
  document.form1.v86_sequencial.value = chave3; 
  
  
  if(chave == 'true' || chave == true) { 
    document.form1.z01_numcgm.focus(); 
    document.form1.z01_numcgm.value = ''; 
    document.form1.z01_nome.value = erro; 
    document.form1.v86_sequencial.value = "";
  } 
}

function js_mostracgm1(chave1, chave2, chave3) {

  $('z01_numcgm').value = chave2;
  $('z01_nome').value  = chave1;
  document.form1.v86_sequencial.value = chave3; 
  //alert(" chave : "+chave1+"\n chave2 : "+chave2 + "\n chave3 : "+chave3);
  db_iframe_favorecidos.hide();
  //parent.db_iframe_favorecidos.adicionaPrincipal(chave2, chave1);
}


function js_novoFavorecido() {
  js_OpenJanelaIframe('', 
                      'db_iframe_novoFavorecido', 
                      'jur1_favorecido004.php?lMenu=false&lFisico=true&funcaoRetorno=parent.CurrentWindow.corpo.iframe_grupoprograma.retornoCgm',
                      'Novo CGM',
                      true,
                      '0');
}

function js_alterarFavorecido(iCgm) {

  if (iCgm != "") {
    js_OpenJanelaIframe('', 
                        'db_iframe_novoFavorecido', 
                        'jur1_favorecido004.php?chavepesquisa='+iCgm+
                        '&lMenu=false&lCpf=true&funcaoRetorno=parent.CurrentWindow.corpo.iframe_grupoprograma.retornoCgm',
                        'Novo CGM',
                        true,
                        '0');
  }
}


function retornoCgm(iCgm) {
  
  db_iframe_novocgm.hide();
  $('z01_numcgm').value = iCgm;
  js_pesquisacgm(false); 
}
</script>
