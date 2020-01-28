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

$clrotulo = new rotulocampo;
$clrotulo->label("o39_codlei");
$clrotulo->label("o45_numlei");


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
function emite_lista(){
   obj = document.form1;

   sel_instit  = new Number(document.form1.db_selinstit.value);
   if(sel_instit == 0){
      alert('Você não escolheu nenhuma Instituição. Verifique!');
      return false;
   }
 
   var dt_ini = obj.data_ini_ano.value +'-'+obj.data_ini_mes.value+'-'+obj.data_ini_dia.value;
   var dt_fim = obj.data_fim_ano.value +'-'+obj.data_fim_mes.value+'-'+obj.data_fim_dia.value;

   if (obj.lista_modelo.value==1)
     jan = window.open('orc2_orcsuplem003.php?processados='+obj.processados.value+'&db_selinstit='+obj.db_selinstit.value+'&dt_ini='+dt_ini+'&dt_fim='+dt_fim+'&codlei='+obj.o39_codlei.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
   else  
     jan = window.open('orc2_orcsuplem004.php?db_selinstit='+obj.db_selinstit.value+'&dt_ini='+dt_ini+'&dt_fim='+dt_fim+'&codlei='+obj.o39_codlei.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
   jan.moveTo(0,0);
}


</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="100%" height="18">&nbsp;</td>    
  </tr>
</table>
 
 <br><br>
  <table  align="center" border="0" >
   <form name="form1" method="post" action="" >
   
    <tr>
      <td colspan=3 align=left><h3>Relatório de Projetos </h3></td>    
    </tr>


     <tr>
         <td align="center" colspan="3">
         <?
           db_selinstit('',300,150);
         ?>
         </td>
      </tr>



  <tr>
   <td nowrap><b>  Período inicial </b></td>
      <td colspan="2">
         <? 
	   $data_ini_dia = '01';
	   $data_ini_mes = '01';
           $data_ini_ano = db_getsession("DB_anousu"); 	 
	  db_inputdata('data_ini',@$data_ini_dia,@$data_ini_mes,@$data_ini_ano,true,'text',1);  ?>
      </td>
   </tr>
  <tr>
   <td nowrap><b>  Período final  </b> </td>
      <td colspan="2">
	  <? 
	   $data_fim_dia = date('d',db_getsession("DB_datausu"));
	   $data_fim_mes = date('m',db_getsession("DB_datausu"));
	   $data_fim_ano = db_getsession("DB_anousu");
	   db_inputdata('data_fim',@$data_fim_dia,@$data_fim_mes,@$data_fim_ano,true,'text',1);  ?>

      </td>
   </tr>
   <tr>
   
       <td nowrap align="right" colspan="1"><b> Listar Dotações : </b></td>
       <td colspan=2>  
        <select name=lista_modelo>
           <option value="1"> Não </option>
           <option value="2"> Sim </option>
        </select>
     </td>
   </tr>
   <tr>
      <td nowrap align="right" colspan="1"><b>Tipo: </b></td>    
      <td colspan=2>  
        <select name=processados>
           <option value="1"> Processados  </option>
           <option value="2"> Não Processados </option>
           <option value="3"> Todos </option>
        </select>
     </td>
   </tr>

   <tr>
      <td nowrap title="<?=@$To39_codlei?>"><?db_ancora(@$Lo39_codlei,"js_pesquisao39_codlei(true);",$db_opcao);?></td>
      <td> 
         <? db_input('o39_codlei',8,$Io39_codlei,true,'text',$db_opcao," onchange='js_pesquisao39_codlei(false);'")?>
         <? db_input('o45_numlei',30,$Io45_numlei,true,'text',3,'')     ?>
      </td>
   </tr>

   <tr>     
      <td nowrap align="center" colspan=3><input name="emitir_projetos"  type="button" value="Emitir" onclick="emite_lista();" >
   </td>

  </tr>
  </table>
 </form>

<?  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit")); ?>

</body>
</html>
<script>
function js_pesquisao39_codlei(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orclei','func_orclei.php?funcao_js=parent.js_mostraorclei1|o45_codlei|o45_numlei','Pesquisa',true);
  }else{
     if(document.form1.o39_codlei.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_orclei','func_orclei.php?pesquisa_chave='+document.form1.o39_codlei.value+'&funcao_js=parent.js_mostraorclei','Pesquisa',false);
     }else{
       document.form1.o45_numlei.value = ''; 
     }
  }
}
function js_mostraorclei(chave,erro){
  document.form1.o45_numlei.value = chave; 
  if(erro==true){ 
    document.form1.o39_codlei.focus(); 
    document.form1.o39_codlei.value = ''; 
  }
}
function js_mostraorclei1(chave1,chave2){
  document.form1.o39_codlei.value = chave1;
  document.form1.o45_numlei.value = chave2;
  db_iframe_orclei.hide();
}
</script>