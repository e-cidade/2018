<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_medicos_classe.php");
$cl_medicos = new cl_medicos;
$clrotulo = new rotulocampo;
$clrotulo->label("sd03_i_codigo");
$clrotulo->label("sd03_c_nome");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" >
<table width="790" height='18' border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table valign="top" marginwidth="0" width="790" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td height="430" align="center" valign="top" bgcolor="#CCCCCC">
    <form name='form1'>
    <table>
     <tr>
      <td>
      Período </td><td><?db_inputdata('data1',@$data1_dia,@$data1_mes,@$data1_ano,true,'text',$db_opcao,"")?> A <?db_inputdata('data2',@$data2_dia,@$data2_mes,@$data2_ano,true,'text',$db_opcao,"")?>
      </td>
     </tr>
<!--     <tr>
      <td>Faixa de Idade </td><td><?db_input('id1',3,$id1,true,'text',$db_opcao,'')?> A <?db_input('id2',3,$id2,true,'text',$db_opcao,'')?></td>
     </tr>-->
     <tr><td>Buscar por</td><td>
     <select name="tp" onchange="submit()">
      <option value=0 >Selecione</option>
      <option value=1 <?if($tp == 1){ echo "selected"; }?> >Cid</option>
      <option value=2 <?if($tp == 2){ echo "selected"; }?> >Bairro</option>
     </select>
     </td></tr>
     <?if($tp == 1){?>
     <tr><td nowrap>
       <?db_ancora('Cid',"js_pesquisa_cid();",$db_opcao);?>
      </td>
      <td>
       <?db_input('extra',10,$extra,true,'text',3,"")?>
       <?db_input('descr',40,"",true,'text',3,'')?>
      </td>
     </tr>
     <?}elseif($tp == 2){?>
     <tr><td>
      <?db_ancora('Bairro',"js_pesquisa_bairro();",$db_opcao);?>
     </td>
     <td>
      <?db_input('bairro',40,"",true,'text',3,'')?>
     </td>
     </tr>
     <?}?>
     <tr>
      <td colspan='6' align='center' >
       <input name='Processar' type='button' value='Processar' onclick="EnviaForm()">
       <input name='Cancelar' type='button' value='Cancelar' onclick="Cancela()">
      </td>
     </tr>
    </table>
    </form>
    <script>
    function js_pesquisa_cid(){
       js_OpenJanelaIframe('top.corpo','db_iframe_cids','func_cids.php?funcao_js=parent.js_mostracids|sd22_c_codigo|sd22_c_descr','Pesquisa',true);
    }
    function js_mostracids(chave1,chave2){
      document.form1.extra.value = chave1;
      document.form1.descr.value = chave2;
      db_iframe_cids.hide();
    }
    
    function js_pesquisa_bairro(){
     js_OpenJanelaIframe('top.corpo','db_iframe_cids','func_bairro.php?funcao_js=parent.js_mostrabairro|j13_codi|j13_descr','Pesquisa',true);
    }
    function js_mostrabairro(chave1,chave2){
      document.form1.bairro.value = chave2;
      db_iframe_cids.hide();
    }

     function EnviaForm(){
     //validacao dos campos
      obj = document.form1;
      if(obj.data1_dia.value=="" || obj.data1_mes.value=="" || obj.data1_ano.value=="" || obj.data2_dia.value=="" || obj.data2_mes.value=="" || obj.data2_ano.value==""){
       alert("Preencha o Período Corretamente");
       obj.data1_dia.focus();
       return false;
      }
      /*if(obj.id1.value=="" || obj.id2.value==""){
       alert("Preencha a Faixa de Idade");
       obj.id1.focus();
       return false;
      } */
      <?if($tp == 1){?>
      if(obj.extra.value == ""){
       alert("Preencha o Cid");
       return false;
      }
      <?}elseif($tp == 2){?>
      if(obj.bairro.value == ""){
       alert("Preencha o Bairro");
       return false;
      }
      <?}?>
      else{
       query = <?if($tp == 1){?>"extra="+obj.extra.value+<?}elseif($tp == 2){?>"bairro="+obj.bairro.value+<?}?>"&data1="+obj.data1_ano.value+"X"+obj.data1_mes.value+"X"+obj.data1_dia.value+"&data2="+obj.data2_ano.value+"X"+obj.data2_mes.value+"X"+obj.data2_dia.value;//+"&id1="+obj.id1.value+"&id2="+obj.id2.value;
       }
       jan = window.open('sau2_grafsaude002.php?'+query,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
       jan.moveTo(0,0);
      }

     function Cancela(){
      location.href = "<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>";
     }
    </script>
  </td>
 </tr>
</table>
<?
 db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>