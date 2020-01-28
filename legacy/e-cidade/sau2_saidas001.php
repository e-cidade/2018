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
$clrotulo->label("sd27_i_prontuario");
$clrotulo->label("sd28_i_cgm");
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
<table valign="top" marginwidth="0" width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td height="430" align="center" valign="top" bgcolor="#CCCCCC">
    <form name='form1'>
    <table>
     <tr>
      <td>
      Período </td><td><?db_inputdata('data1',@$data1_dia,@$data1_mes,@$data1_ano,true,'text',$db_opcao,"")?> A <?db_inputdata('data2',@$data2_dia,@$data2_mes,@$data2_ano,true,'text',$db_opcao,"")?>
      </td>
     </tr>
     <tr><td>Buscar por</td><td>
     <select name="tp" onchange="submit()">
      <option value=0 >Selecione</option>
      <option value=1 <?if($tp == 1){ echo "selected"; }?> >CGM</option>
      <option value=2 <?if($tp == 2){ echo "selected"; }?> >Prontuario</option>
     </select>
     </td></tr>
     <?if($tp == 1){?>
     <tr><td nowrap>
       <?db_ancora('Cgm',"js_pesquisa_cgm();",$db_opcao);?>
      </td>
      <td>
       <?db_input('sd28_i_cgm',10,$sd28_i_cgm,true,'text',3,"")?>
       <?db_input('z01_nome',40,$z01_nome,true,'text',3,'')?>
      </td>
     </tr>
     <?}elseif($tp == 2){?>
     <tr><td>
      <?db_ancora('Prontuario',"js_pesquisa_prontuario();",$db_opcao);?>
     </td>
      <td>
       <?db_input('sd27_i_prontuario',10,$sd27_i_prontuario,true,'text',3,"")?>
       <?db_input('pront_nome',40,$pront_nome,true,'text',3,'')?>
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
    function js_pesquisa_cgm(){
       js_OpenJanelaIframe('top.corpo','db_iframe_cgm','func_cgm.php?funcao_js=parent.js_mostracgm|z01_numcgm|z01_nome','Pesquisa',true);
    }
    function js_mostracgm(chave1,chave2){
      document.form1.sd28_i_cgm.value = chave1;
      document.form1.z01_nome.value = chave2;
      db_iframe_cgm.hide();
    }

    function js_pesquisa_prontuario(){
     js_OpenJanelaIframe('top.corpo','db_iframe_pront','func_prontuarios.php?funcao_js=parent.js_mostrapront|sd24_i_id|sd24_c_atendimento','Pesquisa',true);
    }
    function js_mostrapront(chave1,chave2){
      document.form1.sd27_i_prontuario.value = chave1;
      document.form1.pront_nome.value = chave2;
      db_iframe_pront.hide();
    }

     function EnviaForm(){
     //validacao dos campos
      obj = document.form1;
      if(obj.data1_dia.value=="" || obj.data1_mes.value=="" || obj.data1_ano.value=="" || obj.data2_dia.value=="" || obj.data2_mes.value=="" || obj.data2_ano.value==""){
       alert("Preencha o Período Corretamente");
       obj.data1_dia.focus();
       return false;
      }
      <?if($tp == 1){?>
      if(obj.sd28_i_cgm.value == ""){
       alert("Preencha o CGM");
       return false;
      }
      <?}elseif($tp == 2){?>
      if(obj.sd27_i_prontuario.value == ""){
       alert("Preencha o Prontuario");
       return false;
      }
      <?}?>
      else{
       query = <?if($tp == 1){?>"cgm="+obj.sd28_i_cgm.value+<?}elseif($tp == 2){?>"pront="+obj.sd27_i_prontuario.value+<?}?>"&data1="+obj.data1_ano.value+"X"+obj.data1_mes.value+"X"+obj.data1_dia.value+"&data2="+obj.data2_ano.value+"X"+obj.data2_mes.value+"X"+obj.data2_dia.value;
       }
       jan = window.open('sau2_saidas002.php?'+query,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
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