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
  require_once("libs/db_utils.php");
  require_once("dbforms/db_funcoes.php");
  
  $db_botao     = true;
  $clrhferias   = new cl_rhferias;
  $clrhferias->rotulo->label();
  $clrotulo = new rotulocampo;
  $clrotulo->label("z01_nome");

  $oGet = db_utils::postMemory($_GET);
  $db_opcao     = $oGet->db_opcao;

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

  <body bgcolor="#cccccc" style="margin-top:30px;" onLoad="a=1;" >
  
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
          <center>
            
          <form name="form1" method="get" action="<?php echo $sUrlDestinoFormulario; ?>">

              <center>
              
              	<fieldset style="width:600px;">
              	
              	  <legend><strong>Seleção de matrícula</strong></legend>	
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
              </center>
              <br />
              <input name="enviar" value="Pesquisar" type="submit" <?=($db_botao==false?"disabled":"")?> onblur="document.form1.rh109_regist.focus();" onclick="return js_processar(arguments[0])" >
            </form>
            
          </center>
        </td>
      </tr>
    </table>
    <?php 
      db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
    ?>
    
  </body>

</html>
<?php
  $iTipoQuery = ($db_opcao == 1 ? 2 : 3 );
?>
<script>

var MENSAGEM_SISTEMA = 'recursoshumanos/pessoal/pes1_escalaferias001.';

function js_pesquisarh109_regist(mostra){

  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_rhpessoal','func_rhpessoal.php?sQuery=<?php echo $iTipoQuery; ?>&funcao_js=parent.js_mostrapessoal1|rh01_regist|z01_nome|r45_dtafas|r45_dtreto&instit=<?=(db_getsession("DB_instit"))?>','Pesquisa',true);
  }else{

    if(document.form1.rh109_regist.value != ''){ 
      js_OpenJanelaIframe('top.corpo','db_iframe_rhpessoal','func_rhpessoal.php?sQuery=<?php echo $iTipoQuery; ?>&pesquisa_chave='+document.form1.rh109_regist.value.urlEncode()+'&funcao_js=parent.js_mostrapessoal&instit=<?=(db_getsession("DB_instit"))?>','Pesquisa',false);
    }else{
      document.form1.z01_nome.value = '';
    }
  }
}

function js_mostrapessoal(chave,chave2,chave3,erro){

	mostrar = false;
  
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
  db_iframe_rhpessoal.hide();
	mostrar = true;
	
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
    $('rh109_regist').onkeyup(clickEvent)
  }

  return $F('rh109_regist') != '';
}

js_tabulacaoforms("form1","rh109_regist",true,1,"rh109_regist",true);
</script>