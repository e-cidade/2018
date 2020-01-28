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
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");

$clrotulo = new rotulocampo;
$clrotulo->label("rh01_regist");
$clrotulo->label("z01_nome");
$db_opcao = 1;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
</head>
<body bgcolor="#CCCCCC">
  <form name="form1" method="get" action="">
    <fieldset style="width: 480px; margin: 25px auto 10px auto; "> 
      <legend><strong>Avaliação Prêvia de Estágio:</strong></legend>
      <table align="center">
        <tr>
          <td title="<?=@$Trh01_regist?>">
          <?
            db_ancora(@$Lrh01_regist,"js_pesquisarh01_regist(true);",$db_opcao);
          ?>
          </td>
          <td> 
          <?
            db_input('rh01_regist',10,$Irh01_regist,true,'text',$db_opcao," onchange='js_pesquisarh01_regist(false);'"); 
            db_input('z01_nome',40,$Iz01_nome,true,'text',3,'');
          ?>
          </td>
        </tr>
        <tr>
          <td>
            <strong>Período:</strong>
          </td>
          <td>
          <?
            db_inputdata('h64_dataini',null,null,null,true,'text',$db_opcao,"");
          ?>
            <strong>até</strong>
          <?php 
            db_inputdata('h64_datafim',null,null,null,true,'text',$db_opcao,"");
          ?>
          </td>
        </tr>
      </table>
    </fieldset>
    <center> 
      <input name="consultar" type="button" id="consultar" value="Consultar" onclick='js_montaQuery()';>
    </center>
  </form>
</body>
</html>
<?php
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
<script>
function js_pesquisarh01_regist(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_rhpessoal','func_rhpessoal.php?funcao_js=parent.js_mostrarhpessoal1|rh01_regist|z01_nome','Consulta Matrícula',true);
  }else{
     if(document.form1.rh01_regist.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_rhpessoal','func_rhpessoal.php?pesquisa_chave='+document.form1.rh01_regist.value+'&funcao_js=parent.js_mostrarhpessoal','Consulta Matrícula',false);
     }else{
       document.form1.z01_nome.value = ''; 
     }
  }
}
function js_mostrarhpessoal(chave,erro){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.rh01_regist.focus(); 
    document.form1.rh01_regist.value = ''; 
  }
}
function js_mostrarhpessoal1(chave1,chave2){
  document.form1.rh01_regist.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_rhpessoal.hide();
}

function js_montaQuery(){

  sQuery  = "dDataInicial="+$F('h64_dataini')+'&dDataFinal='+$F('h64_datafim');
  sQuery += "&iMatricula="+$F('rh01_regist');

  window.open('rec2_relatorioassentamentos002.php?'+sQuery,'','location=0');
}
</script>