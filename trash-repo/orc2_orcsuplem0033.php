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
include("libs/db_liborcamento.php");
require("classes/db_orcsuplem_classe.php");  // declaração da classe orcreserva

$clorcsuplem = new cl_orcsuplem ; // instancia classe orcsuplem
$clorcsuplem->rotulo->label();

db_postmemory($HTTP_POST_VARS);
$db_opcao=1;
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

<script>
function js_projeto(){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcprojeto','func_orcprojeto001.php?funcao_js=parent.js_mostra|o39_codproj','Pesquisa',true);
}
function js_mostra(chave,erro){
  document.form1.o46_codlei.value = chave; 
  db_iframe_orcprojeto.hide();
  if(erro==true){ 
    document.form1.o46_codlei.focus(); 
    document.form1.o46_codlei.value = ''; 
  }
}

function emite(){
   if (document.form1.o46_codlei.value ==''){
      alert('Preencha o Numero do Projeto ');
   } else {  
      if (document.form1.modelo.value=='normal'){  
          jan = window.open('orc2_orcprojeto002.php?o46_codlei='+document.form1.o46_codlei.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
          jan.moveTo(0,0);
      } else {
	  jan = window.open('orc2_orcprojeto003.php?o46_codlei='+document.form1.o46_codlei.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
          jan.moveTo(0,0);
      }
   }  
}
function emite_lista(){
   obj = document.form1;
   var dt_ini = obj.data_ini_ano.value +'-'+obj.data_ini_mes.value+'-'+obj.data_ini_dia.value;
   var dt_fim = obj.data_fim_ano.value +'-'+obj.data_fim_mes.value+'-'+obj.data_fim_dia.value;
   if (obj.lista_modelo.value==1)
     jan = window.open('orc2_orcsuplem003.php?o46_codlei='+obj.o46_codlei.value+'&dt_ini='+dt_ini+'&dt_fim='+dt_fim,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
   else  
     jan = window.open('orc2_orcsuplem004.php?o46_codlei='+obj.o46_codlei.value+'&dt_ini='+dt_ini+'&dt_fim='+dt_fim,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
   jan.moveTo(0,0);
}


</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">  &nbsp;</td>
    <td width="25">   &nbsp;</td>
    <td width="140">  &nbsp;</td>
  </tr>
</table>
 
 <br><br>
  <table  align="center" border="0" >
   <form name="form1" method="post" action="" >
   
   <tr>
      <td nowrap title="<?=@$To46_codlei?>"><? db_ancora(@$Lo46_codlei,"js_projeto();",$db_opcao);  ?> </td>
      <td nowrap> <? db_input('o46_codlei',8,$Io46_codlei,true,'text',$db_opcao,"") ?>  </td>
      <td nowrap>
        <select name=modelo>
           <option value="normal"> Modelo 1  </option>
           <option value="compacto">Modelo 2 </option>
        </select>
     </td>
   </tr>
   <tr>
      <td> &nbsp;  </td>
      <td colspan="2"  nowrap align="center"> <input name="emitir" type="button" value="Emitir Projeto" onclick="emite();" ></td>
   </tr>
   <tr>
      <td> &nbsp;  </td>
      <td> &nbsp;  </td>
      <td> &nbsp;  </td>
   </tr>
 
  <tr>
   <td nowrap>  Período inicial  </td>
      <td colspan="2">
         <? db_inputdata('data_ini',@$data_ini_dia,@$data_ini_mes,@$data_ini_ano,true,'text',1);  ?>
      </td>
   </tr>
  <tr>
   <td nowrap>  Período final  </td>
      <td colspan="2">
	  <? db_inputdata('data_fim',@$data_fim_dia,@$data_fim_mes,@$data_fim_ano,true,'text',1);  ?>

      </td>
   </tr>
   <tr>
   
       <td nowrap align="right" colspan="3">
         <i>Modelo da lista </i>
        <select name=lista_modelo>
           <option value="1"> geral  </option>
           <option value="2"> Analitica </option>
        </select>
     </td>
   </tr>
   <tr>
      <td nowrap align="right" colspan="3">
         <b>Tipo: </b>
        <select name=processados>
           <option value="1"> Processados  </option>
           <option value="2"> Não Processados </option>
           <option value="3"> Todos </option>
        </select>
     </td>
   </tr>
 

   
   <tr>
   <td colspan=3>
    <table>	
     <tr>
      <td nowrap align="center"> <input name="emitir_projetos"  type="button" value="Lista de Projetos" onclick="emite_lista();" ></td>
      </tr>
    </table>  
   </td>
  </tr>
  </table>
 </form>
<?  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit")); ?>
</body>
</html>