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
require_once("classes/db_solicita_classe.php");
db_postmemory($HTTP_POST_VARS);

$clsolicita = new cl_solicita;
$clrotulo   = new rotulocampo;
$clsolicita->rotulo->label();

?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

<script>

function js_emite(){
	
    var codigo       = document.form1.pc10_numero.value;
    var separador    = document.form1.separador.value;
    var delimitador  = document.form1.delimitador.value;
    var layout       = document.form1.layout.value;
    
    var sQuery       = 'pc10_numero=' + codigo; 
        sQuery      += '&separador='  + separador; 
        sQuery      += '&delimitador='+ delimitador; 
        sQuery      += '&layout='     + layout;

    if (codigo == '' || codigo == null) {

      alert("Selecione uma Solicitação.");
      return false;
    }    
    
    jan = window.open('com2_solicitacaoTxt002.php?' + sQuery, '', 'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    jan.moveTo(0,0);
    document.form1.pc10_numero.value='';

}
</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>

<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">

<center>

  <form name="form1" method="post" action="">
  
    <fieldset style="margin-top: 50px; width: 400px;">
    
      <legend><strong>Gerar TXT com Itens da Solicitação</strong></legend>
      
      <table  align="center" border ='0'>
      
        <tr>
          <td align="left" nowrap title="<?=$Tpc10_numero?>"> 
            <? db_ancora(@$Lpc10_numero,"js_pesquisapc10_numero(true);",1);?>
          </td>
          <td  align="left" nowrap title="<?=$Tl20_codigo?>">
            <b>
              <?php 
                db_input('pc10_numero',8,$Ipc10_numero,true,"text",1,"onchange='js_pesquisapc10_numero(false);'");
              ?>
            </b> 
          </td>
        </tr>
      
        <tr>
          <td align="left" nowrap><b>Separador Colunas :</b></td>
          <td align="left" nowrap>
            <?
              if(!isset($separador)) {
                $separador = ";";
              }
              db_input("separador", 1, 3, true, "text", 1, "");
            ?>
          </td>
        </tr>
        
        <tr>
          <td align="left" nowrap><b>Delimitador de Campos :</b></td>
          <td align="left" nowrap>
            <?
              $aDelimitador = array("1" => "Aspas Duplas",
                                    "2" => "Aspas Simples");
              db_select('delimitador', $aDelimitador, true, 1, "");
            ?>
          </td>
        </tr>
        
        <tr style="display: none;">
          <td align="left" nowrap><b>Layout:</b></td>
          <td align="left" nowrap>
            <?
              $aLayout= array("1" => "Layout 1",
                                    "2" => "Layout 2 (sem seq item)");
              db_select('layout', $aLayout, true, 1, "");
            ?>
          </td>
        </tr>
         
      </table>

    </fieldset>  
    <div style="margin-top: 10px;">
      <input  name="emite2" id="emite2" type="button" value="Processar" onclick="js_emite();" >
    </div>
      
  </form>
</center>
</body>

<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</html>
<script>
function js_pesquisapc10_numero(mostra){
	  qry = "&nada=true";
	  if(mostra==true){
	    js_OpenJanelaIframe('top.corpo','db_iframe_solicita','func_solicita.php?funcao_js=parent.js_mostrapcorcamitem1|pc10_numero'+qry,'Pesquisa Solicitação',true);
	  }else{
	    if(document.form1.pc10_numero.value!=""){
	      js_OpenJanelaIframe('top.corpo','db_iframe_solicita','func_solicita.php?funcao_js=parent.js_mostrapcorcamitem&pesquisa_chave='+document.form1.pc10_numero.value+qry,'Pesquisa',false);
	    }else{
	      document.form1.pc10_numero.value = "";
	    }
	  }
	}
	function js_mostrapcorcamitem1(chave1,chave2){
	  document.form1.pc10_numero.value = chave1;
	  db_iframe_solicita.hide();
	}
	function js_mostrapcorcamitem(chave1,erro){
	  if(erro==true){
	    document.form1.pc10_numero.value = "";
	  }
	}

</script>