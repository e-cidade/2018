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
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("classes/db_procandam_classe.php");
require_once ("classes/db_proctransfer_classe.php");
require_once ("classes/db_protprocesso_classe.php");
require_once ("classes/db_proctransand_classe.php");
require_once ("dbforms/db_funcoes.php");

$db_opcao = 1;
db_postmemory($HTTP_SERVER_VARS);
db_postmemory($_POST);
$clprocandam    = new cl_procandam;
$clproctransfer = new cl_proctransfer;
$clprotprocesso = new cl_protprocesso;
$clproctransand = new cl_proctransand;
$rotulo         = new rotulocampo();
$rotulo->label("p58_codproc");
$rotulo->label("p58_requer");
$rotulo->label("p58_numcgm");
$rotulo->label("p58_id_usuario");
$rotulo->label("p58_coddepto");
$rotulo->label("p58_numero");
$rotulo->label("z01_nome");
$rotulo->label("numeroProcesso");

 if (!isset($grupo)) {
 	 $grupo = 1;
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<center>
<div style="margin-top: 25px; width: 570px;">
<form method="post" action="" name="form1">
  <fieldset>
    <legend>
      <b>Consulta de Processos</b>
    </legend>
    <table>
    
		  <tr>
		    <td nowrap title="<?=@$Tp58_numero?>">
		       <?
		         db_ancora(@$Lp58_numero,"js_pesquisap58_codproc(true);",$db_opcao);
		       ?>
		    </td>
		    <td> 
					<?php
					  db_input('p58_numero',10,$Ip58_numero,true,'text',$db_opcao," onchange='js_pesquisap58_codproc(false);'");
					  db_input('p58_requer',40,$Ip58_requer,true,'text',3,'');
					?>
		    </td>
		  </tr>

			<?php if ($grupo == 1) : ?>
        <tr>
          <td title="<?=$Tp58_numcgm;?>">
            <?
              db_ancora(@$Lp58_numcgm,"js_pesquisap58_numcgm(true);",1);
            ?>
          </td>
          <td>
            <?
              db_input("p58_numcgm",10,"",true,"text", $db_opcao, "onchange='js_pesquisap58_numcgm(false);'");
              db_input('z01_nome',40,$Iz01_nome,true,'text',3,'');
            ?>
          </td>      
        </tr>  
      <?php endif; ?>

      </table>
    </fieldset>
  <input type="button" name="db_opcao" value="Consultar" onclick="js_consultaProcesso();">
  <input type="reset" value="Limpar">
</form>
</div>
</center>

<?php db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit")); ?>

</body>
</html>
<script type="text/javascript">

function js_consultaProcesso() {

  var iNumeroProcesso = $F('p58_numero');
  var iNumeroCgm = $F('p58_numcgm');
  var sUrl = 'pro3_consultaprocesso003.php?numeroprocesso=' + iNumeroProcesso + '&cgm=' + iNumeroCgm;

  if (iNumeroProcesso == "" && iNumeroCgm == "") {

    alert("Preencha um dos campos.");
    return false;
  }
      
  js_OpenJanelaIframe('top.corpo', 'db_iframe', sUrl, 'Pesquisa de Processos', true);
} 

var sGrupo = <?=$grupo?>;

/**
 * Valida formatacao do campo numero do processo
 * - aceita somente numeros e o caracter /
 *
 * @param string $sNumero
 * @access public
 * @return bool
 */
function js_validarNumero(sNumero) {

  var lCaracteresValidos = new RegExp(/^[0-9\/]+$/).test(sNumero);
  var iPosicaoBarra      = sNumero.indexOf('/');
  var iQuantidadeBarras  = iPosicaoBarra > 0 ? sNumero.match(/\//g).length : 0;

  /**
   * Contem caracter difernete de 0-9 e / 
   */
  if ( !lCaracteresValidos ) {
    return false;
  }

  /**
   * Informou primeiro caracter / 
   */
  if ( iPosicaoBarra == 0 ) {
    return false;
  }

  /**
   * Informou mais de uma barra
   */
  if ( iQuantidadeBarras > 1 ) {
    return false;
  }

  /**
   * Não informou nenhum numero apos a barra
   */
  if ( iPosicaoBarra > 0 && empty(sNumero.split('/')[1]) ) {
    return false;
  }

  return true;
}

function js_pesquisap58_codproc(mostra) {

  var sUrl = 'func_protprocesso_protocolo.php?grupo='+sGrupo;
  
  if(mostra) {

    sUrl += '&funcao_js=parent.js_mostraprotprocesso1|p58_numero|dl_nome_ou_razão_social';
    js_OpenJanelaIframe('', 'db_iframe_cgm', sUrl, 'Pesquisa de Processos', true);

  } else {

    /**
     * Valida formatacao do campo numero processo 
     */
    if ( !empty($('p58_numero').value) && !js_validarNumero($('p58_numero').value) ) {

      var sMensagemErro  = "Formatação do campo inválida\n";
          sMensagemErro += "Informe número do processo / ano"; 

      alert(sMensagemErro);
      $('p58_numero').value = '';
      return false;
    } 

    sUrl += '&pesquisa_chave='+$F('p58_numero')+'&funcao_js=parent.js_mostraprotprocesso';
    js_OpenJanelaIframe('','db_iframe_cgm', sUrl, 'Pesquisa', false);
  }
}

function js_mostraprotprocesso(chave, chave1, erro) {
  
  document.form1.p58_requer.value = chave1; 
  document.form1.p58_numero.value = chave;
  
  if (erro) { 
    document.form1.p58_numero.focus(); 
    document.form1.p58_numero.value = ''; 
  }
}

function js_mostraprotprocesso1(sNumero, sNome) {
  
  document.getElementById('p58_numero').value = sNumero;
  document.getElementById('p58_requer').value = sNome;
  db_iframe_cgm.hide();
}

function js_pesquisa() {
  
  db_iframe.jan.location.href = 'func_procarquiv.php?funcao_js=parent.js_preenchepesquisa|0';
  db_iframe.mostraMsg();
  db_iframe.show();
  db_iframe.focus();
}

function js_preenchepesquisa(chave) {
  
  db_iframe.hide();
  location.href = '<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>'+"?chavepesquisa="+chave;
}

function js_pesquisap58_numcgm(mostra) {

  var sUrl = 'func_nome.php?';  
  if (mostra) {

    sUrl += 'funcao_js=parent.js_mostracgm1|0|1'; 
    js_OpenJanelaIframe('','db_iframe_c',sUrl,'Pesquisa',true);
  } else {
    if ($F('p58_numcgm') != "") {
      sUrl += 'pesquisa_chave='+$F('p58_numcgm')+'&funcao_js=parent.js_mostracgm';
      js_OpenJanelaIframe('','db_iframe_c',sUrl,'Pesquisa',false);
    } else {
      $('p58_numcgm').value = "";
      $('z01_nome').value = "";
    }
  }
}

function js_mostracgm(erro, chave) {
  
  document.form1.z01_nome.value = chave; 
  if (erro) {
     
    document.form1.p58_numcgm.focus(); 
    document.form1.p58_numcgm.value = ''; 
  }
}

function js_mostracgm1(chave1, chave2) {
  
  document.form1.p58_numcgm.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_c.hide();
}

onLoad=document.form1.p58_numero.select();
onLoad=document.form1.p58_numero.focus();

</script>
