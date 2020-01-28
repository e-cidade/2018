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

  require_once(modification("libs/db_stdlib.php"));
  require_once(modification("libs/db_conecta.php"));
  require_once(modification("libs/db_sessoes.php"));
  require_once(modification("libs/db_usuariosonline.php"));
  require_once(modification("libs/db_utils.php"));
  require_once(modification("dbforms/db_funcoes.php"));
  
  $db_botao     = true;
  $clrhferias   = new cl_rhferias;
  $clrhferias->rotulo->label();
  $clrotulo = new rotulocampo;
  $clrotulo->label("z01_nome");

  $oGet     = db_utils::postMemory($_GET);
  $db_opcao = $oGet->db_opcao;

  $sUrlDestinoFormulario = "pes1_escalaferias003.php";
  if ( !isset($oGet->db_opcao) || $oGet->db_opcao == 1 ) { 
    $sUrlDestinoFormulario = "pes1_escalaferias002.php";
  }
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/dates.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/numbers.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>

  <body>
  
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="form-container">
      <tr>
        <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
          <form name="form1" method="get" action="<?php echo $sUrlDestinoFormulario; ?>" class="container">
              	<fieldset style="width:460px;">
              	
              	  <legend><strong>Seleção da Matrícula</strong></legend>
              		<table border="0">
              		  <tr>
              		    <td align="right" nowrap title="<?=@$Trh109_regist?>">
              		      <?php
              		      $mensagemlote='n';
              		      db_input('mensagemlote', 4, 0,'', 'hidden', 3);
              		      db_ancora($Lrh109_regist, "js_pesquisarh109_regist(true);", 1);
              		      ?>
              		    </td>
              		    <td> 
              		      <?php
              			      db_input('rh109_regist', 10, $Irh109_regist, true, 'text', 1, " onchange='js_pesquisarh109_regist(false);'");      
              			      db_input('z01_nome', 40, $Iz01_nome, true, 'text', 3);
              		      ?>
              		    </td>
              		  </tr>
              		</table>
              	</fieldset>
              <br />
              <input name="enviar" value="Pesquisar" type="submit" <?=($db_botao==false?"disabled":"")?> onblur="document.form1.rh109_regist.focus();" onclick="return js_processar(arguments[0])" >
            </form>
        </td>
      </tr>
    </table>
    <?php 
      db_menu();
    ?>
  </body>

</html>
<?php
  $iTipoQuery = ($db_opcao == 1 ? 2 : 3 );
?>
<script>

var MENSAGEM_SISTEMA = 'recursoshumanos/pessoal/pes1_escalaferias001.';

function js_pesquisarh109_regist(mostra){

  if (mostra == true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_rhpessoal','func_rhpessoal.php?funcao_js=parent.js_mostrapessoal1|rh01_regist|z01_nome|r45_dtafas|r45_dtreto&instit=<?=(db_getsession("DB_instit"))?>&condition=somenteAtivos&sAtivos=1','Pesquisa',true);
  } else{

    if (document.form1.rh109_regist.value != ''){ 
      js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_rhpessoal','func_rhpessoal.php?pesquisa_chave='+document.form1.rh109_regist.value.urlEncode()+'&funcao_js=parent.js_mostrapessoal&instit=<?=(db_getsession("DB_instit"))?>&condition=somenteAtivos&sAtivos=1','Pesquisa',false);
    } else{
      document.form1.z01_nome.value = '';
    }
  }
}

function js_mostrapessoal(chave, erro){
  
	mostrar = false;

  if (erro == true) {
    document.form1.rh109_regist.value = '';
  }

	if(!erro){
		mostrar = true;
  }
  
  document.form1.z01_nome.value = chave;
  
  if(mostrar == false){
    if(erro != true){
      document.form1.z01_nome.value   = '';
    }
    document.form1.rh109_regist.focus(); 
    document.form1.rh109_regist.value = '';
  }
}

function js_mostrapessoal1(chave1,chave2,chave3,chave4){
  
	mostrar = true;
	db_iframe_rhpessoal.hide();
  if(mostrar == true){
	  document.form1.rh109_regist.value = chave1;
	  document.form1.z01_nome.value   = chave2;
  }else{
    document.form1.rh109_regist.focus();
    document.form1.rh109_regist.value = '';
    document.form1.z01_nome.value   = '';
  }
}

/**
 * Verifica se o nome do servidor está no campo
 * Caso contrário não submete o formulário
 */
function js_processar(clickEvent) {

  if (!$F('rh109_regist')) {

    alert( _M(MENSAGEM_SISTEMA + 'campo_nao_pode_ficar_em_branco', {sCampo: 'Matrícula'}) );
    return false;
    
  } else {
    $('rh109_regist').onkeyup = clickEvent
  }

  if (!$F('z01_nome')) {

    alert( _M(MENSAGEM_SISTEMA + 'campo_nao_pode_ficar_em_branco', {sCampo: 'Matrícula'}) );
    return false;
  }



  return $F('rh109_regist') != '';
}
js_tabulacaoforms("form1","rh109_regist",true,1,"rh109_regist",true);
</script>
