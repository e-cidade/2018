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
  
require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once ("dbforms/db_funcoes.php");
require_once("classes/db_parprojetos_classe.php");

$clrotulo = new rotulocampo;  
$clrotulo->label("ob09_codhab");
$clrotulo->label("ob01_nomeobra");
$oDaoParProjetos = new cl_parprojetos();

$sSqlParametros  = $oDaoParProjetos->sql_query_pesquisaParametros( db_getsession('DB_anousu') ); $rsParametros    = $oDaoParProjetos->sql_record($sSqlParametros);
$db_opcao        = 1;

if ($oDaoParProjetos->erro_status != "0") {
  $oParametros   = db_utils::fieldsMemory($rsParametros, 0);
  $db_opcao      = 3;
} else {
 db_msgbox(_M('tributario.projetos.pro2_cartahabite001.parametros_nao_configurados'));
} 

$iTipoRelatorio = $oParametros->ob21_tipocartahabite;
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
<body bgcolor=#CCCCCC onLoad="document.form1.ob09_codhab.focus()" >
  <form class="container" name="form1" method="post" >
    <fieldset>
      <legend>Emissão de carta de habite-se</legend>
      <table class="form-contianer">
        <tr> 
          <td nowrap title="<?=@$Tob09_codhab?>">
            <?
              db_ancora(@$Lob09_codhab, "js_pesquisaob09_codhab(true);", 4);
            ?>
          </td>
          <td> 
          <?
            db_input('ob09_codhab', 10, $Iob09_codhab, true, 'text', 4, " onchange='js_pesquisaob09_codhab(false);'");
            db_input('ob01_nomeobra', 40, $Iob01_nomeobra, true, 'text', 3);                                   
          ?>
          </td>
        </tr>
      </table>
    </fieldset>
    <input name="exibir_relatorio" type="button" id="exibir_relatorio" value="Exibir relat&oacute;rio" onClick="js_AbreJanelaRelatorio(<?=$iTipoRelatorio; ?>);"></td>
  </form>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
function js_AbreJanelaRelatorio(iTipoRelatorio) { 

  /**
   * Verifica qual relatório abrir, 0 pdf, 1 office
   */   
  if(iTipoRelatorio == 0) {
    sTipoArquivoRelatorio = "pro2_cartahabite002.php";
  } else {
    sTipoArquivoRelatorio = "pro2_cartahabite003.php";
  }

  if(document.form1.ob09_codhab.value != '') {

    jan = window.open(sTipoArquivoRelatorio + '?codigo=' + document.form1.ob09_codhab.value, '', 'width=' + (screen.availWidth-5) + 
      ',height=' + (screen.availHeight - 40) + ', scrollbars=1,location=0');
    jan.moveTo(0, 0);    
  }else{
    alert(_M('tributario.projetos.pro2_cartahabite001.digite_habitacao'));
  }    

}

function js_pesquisaob09_codhab(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo', 'db_iframe_obrashabite', 'func_obrashabite.php?funcao_js=parent.js_mostratermohabite1|ob09_codhab|ob01_nomeobra', 'Pesquisa', true);
  }else{
    if(document.form1.ob09_codhab.value != ''){ 
      js_OpenJanelaIframe('top.corpo', 'db_iframe_obrashabite', 'func_obrashabite.php?pesquisa_chave='+document.form1.ob09_codhab.value+'&funcao_js=parent.js_mostratermohabite', 
        'Pesquisa', false);
    }
  }
}

function js_mostratermohabite(chave, erro){

  if(erro == true){ 

    document.form1.ob09_codhab.focus(); 
    document.form1.ob09_codhab.value = ''; 
  }

  document.form1.ob01_nomeobra.value = chave; 
}

function js_mostratermohabite1(iCodigoHabite, sNomeObra){

  document.form1.ob09_codhab.value   = iCodigoHabite;
  document.form1.ob01_nomeobra.value = sNomeObra;
  db_iframe_obrashabite.hide();
}
</script>
<script>

$("ob09_codhab").addClassName("field-size2");
$("ob01_nomeobra").addClassName("field-size7");

</script>