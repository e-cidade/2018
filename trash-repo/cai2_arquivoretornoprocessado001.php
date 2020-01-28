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
require_once("dbforms/db_classesgenericas.php");

//global empgegera e87
$oRotuloEmpagegera = new rotulo("empagegera");
$oRotuloEmpagegera->label();
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

<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" style="margin-top: 30px;" >
<center>
  <form name="form1">
  
  <fieldset  style="position:relative;  width:600px; ">
  <legend><b>Arquivo de Retorno Processado</b></legend>
    <table border="0" width="100%">
    
    <!-- input código do arquivo gerado -->
    <tr> 
      <td  align="left" nowrap title="<?=$Te87_codgera?>">
        <? db_ancora(@$Le87_codgera,"js_pesquisaCodigo(true);",1);?>  
      </td>
      <td align="left" nowrap>
        <?
        db_input("e87_codgera",8,$Ie87_codgera,true,"text",4,"onchange='js_pesquisaCodigo(false);'"); 
        db_input("e87_descgera",60,$Ie87_descgera,true,"text",3);
        ?>
      </td>
    </tr>
       
    </table>
  </fieldset>
  
  <br />
  <input type="button" name="btnProcessar" id="btnProcessar" value="Processar" onclick="js_redireciona();" />
  </form>
</center>

<?php
	db_menu(db_getsession("DB_id_usuario"),
          db_getsession("DB_modulo"),
          db_getsession("DB_anousu"),
          db_getsession("DB_instit"));
?>

<script>

/*
 * Funções que respectivamente pesquisam, preenchem e completam 
 */
function js_pesquisaCodigo(lMostra){
  var sUrlArquivo = '';

  if(lMostra){
    sUrlArquivo = 'func_empagegera.php?funcao_js=parent.js_preencheCodigo|e87_codgera|e87_descgera';
  }else{
    sUrlArquivo = 'func_empagegera.php?pesquisa_chave='+$F('e87_codgera')+'&funcao_js=parent.js_completaCodigo';
  }
  js_OpenJanelaIframe("", 'db_iframe_empagegera', sUrlArquivo, "Pesquisa Arquivo", lMostra);
  
}

function js_preencheCodigo(iCodigo, sDescricao) {
  $("e87_codgera").value    = iCodigo;
  $("e87_descgera").value   = sDescricao;
  db_iframe_empagegera.hide();
}

function js_completaCodigo(sDescricao,lErro){  
  $("e87_descgera").value = sDescricao;
  if (lErro) {
    $("e87_codgera").value = "";
  }
}

function js_redireciona(){
  if($F('e87_codgera') != ""){
    
    var sArquivo = "cai2_inconsistenciaagenda002.php?lCancelado=0&iCodigoGeracao="+$F('e87_codgera');
    var jan = window.open(sArquivo,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');

  }
}
</script>
</body>
</html>