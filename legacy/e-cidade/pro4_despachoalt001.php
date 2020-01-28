<?php
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_protprocesso_classe.php");
require_once("classes/db_procvar_classe.php");
require_once("classes/db_proctipovar_classe.php");
require_once("classes/db_db_syscampo_classe.php");
require_once("dbforms/db_funcoes.php");

$clprotprocesso = new cl_protprocesso;
$rotulo = new rotulocampo();
$rotulo->label("p58_codproc");
$rotulo->label("p58_numero");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>
function js_processa(){

  if (document.form1.p58_numero.value!=""){
        js_OpenJanelaIframe('top.corpo','db_iframe_despint','func_procdespint.php?grupo=1&pesquisa_chave='+document.form1.p58_numero.value+'&funcao_js=parent.js_mudapagina&sCampoRetorno=p58_codproc','Pesquisa',false);
  }else{
    alert("Informe o Cod. do Processo!!");
    document.form1.p58_numero.focus();
  }
}

function js_mudapagina(iCodigoProcesso, iNumCgm, sNome, lErro){

  /**
   * Erro na consulta 
   */
  if ( lErro ) {

    alert("Digite um codigo de processo valido!!");
    document.form1.p58_numero.value="";
    document.form1.p58_numero.focus();
    return false;
  }

  location.href="pro4_despachoalt002.php?p58_codproc=" + iCodigoProcesso
}
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >

<center>
  <form method="post" name="form1" action="pro4_despachoalt002.php">
  
    <fieldset style="margin-top: 30px; width: 600px;">
    <legend>Alteração de Despacho</legend>
      <table>
	      <tr>
	        <td title="<?=$Tp58_codproc?>">
	           <? db_ancora("Processo:","js_pesquisa(true);",1); ?>
	        </td>
	        <td>
	           <?php db_input("p58_numero", 15, $Ip58_numero, true, "text", 2, "onchange='js_pesquisa(false);'"); ?>
             <?php db_input("p58_codproc", 30, 0, true, "hidden", 1); ?>
	        </td>
	        <td> 
	           <?db_input("p58_requer",40,$Ip58_codproc,true,"text",3);?>
	        </td>
        </tr>
      </table>
    </fieldset>  
    
    <div style="margin-top: 10px;">
      <input type="button" value="Processar" onclick="js_processa();">
    </div>    
    
  </form>
</center>

<?php db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit")); ?>

</body>
</html>
<script>
function js_pesquisa(mostra) {

  if( mostra == true) {
    js_OpenJanelaIframe('top.corpo','db_iframe_despint','func_procdespint.php?grupo=1&funcao_js=parent.js_mostra1|dl_cod_processo|dl_processo|dl_nome_ou_Razão_social','Pesquisa',true);
  } else {

     if (document.form1.p58_numero.value != '') { 

        js_OpenJanelaIframe('top.corpo','db_iframe_despint',
          'func_procdespint.php?grupo=1&pesquisa_chave='+document.form1.p58_numero.value+'&funcao_js=parent.js_mostra&sCampoRetorno=p58_codproc',
          'Pesquisa',false);
     }
  }
}
 
function js_mostra(iCodigoProcesso, iNumCgm, sNome, lErro) {
 
  document.form1.p58_requer.value = sNome;

  if ( lErro ) { 

    document.form1.p58_numero.focus(); 
    document.form1.p58_numero.value = '';     
    document.form1.p58_requer.value = iNumCgm;
    return false;
  }
  
  document.form1.p58_codproc.value = iCodigoProcesso;
  document.form1.p58_requer.value  = sNome;

  return true;
}

function js_mostra1(iCodigoProcesso, iNumeroProecesso, sNome) {

  document.form1.p58_codproc.value = iCodigoProcesso;
  document.form1.p58_numero.value  = iNumeroProecesso;
  document.form1.p58_requer.value  = sNome;
  db_iframe_despint.hide();
}
</script>