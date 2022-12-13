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
include("dbforms/db_classesgenericas.php");
$aux = new cl_arquivo_auxiliar;
$clrotulo = new rotulocampo;
$clrotulo->label('DBtxt21');
$clrotulo->label('DBtxt22');
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

<script>
 function js_abre(opcao){
   sel_instit  = new Number(document.form1.db_selinstit.value);
   if(sel_instit == 0){
      alert('Voc� n�o escolheu nenhuma Institui��o. Verifique!');
      return false;
   }
 }
variavel = 1;
 function js_emite(){
   //verifica datas
   obj = document.form1;
   dt1 = new Date(obj.DBtxt21_ano.value,obj.DBtxt21_mes.value,obj.DBtxt21_dia.value,0,0,0);
   dt2 = new Date(obj.DBtxt22_ano.value,obj.DBtxt22_mes.value,obj.DBtxt22_dia.value,0,0,0);
   if (dt1 > dt2 ){
      alert('Data inicial n�o pode ser maior que a Data final ! ');
      return false;
   } 
  for(i=0;i<document.form1.length;i++){
    if(document.form1.elements[i].name == "lista[]"){
      for(x=0;x< document.form1.elements[i].length;x++){
        document.form1.elements[i].options[x].selected = true;
      }
    }
  }
//   jan = window.open('con2_conlancamval002.php?DBtxt21_dia='+document.form1.DBtxt21_dia.value+'&DBtxt21_mes='+document.form1.DBtxt21_mes.value+'&DBtxt21_ano='+document.form1.DBtxt21_ano.value+'&DBtxt22_dia='+document.form1.DBtxt22_dia.value+'&DBtxt22_mes='+document.form1.DBtxt22_mes.value+'&DBtxt22_ano='+document.form1.DBtxt22_ano.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
//   jan.moveTo(0,0);
  jan = window.open('','safo' + variavel,'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
  document.form1.target = 'safo' + variavel++;
  setTimeout("document.form1.submit()",1000);
  return true;
  }
</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>

  <table  align="center" >
    <form name="form1" method="post" action="con2_conlancamval002.php">
      <tr>
        <td align="center" colspan="2">
         <?
          db_selinstit();
         ?>
        </td>
      </tr>
      <tr>
      <td align="center" colspan="2">
       <table border="0"  align="center">
       <tr>
          <td align="center" >
               <?
                 // $aux = new cl_arquivo_auxiliar;
                 $aux->cabecalho = "<strong>Hist�ricos</strong>";
                 $aux->codigo = "c50_codhist"; //chave de retorno da func
                 $aux->descr  = "c50_descr";   //chave de retorno
                 $aux->nomeobjeto = 'lista';
                 $aux->funcao_js = 'js_mostra';
                 $aux->funcao_js_hide = 'js_mostra1';
                 $aux->sql_exec  = "";
             //  $aux->func_arquivo = "func_conplanoreduz001.php";  //func a executar
	         $aux->func_arquivo = "func_conhist.php";
                 $aux->nomeiframe = "db_iframe_conhist";
                 $aux->localjan = "";
                 $aux->onclick = "";
                 $aux->db_opcao = 2;
                 $aux->tipo = 2;
                 $aux->top = 0;
                 $aux->linhas = 7;
                 $aux->vwhidth = 400;
                 $aux->funcao_gera_formulario();
              ?>    
          </td>
       </tr>
       </table>
      </td>
      </tr>
     <tr>
       <td nowrap align="right" title="<?=@$TDBtxt21?>">
        <?=@$LDBtxt21?>
         <?
          $DBtxt21_ano = db_getsession("DB_anousu");
          $DBtxt21_mes = '01';
          $DBtxt21_dia = '01';
          db_inputdata('DBtxt21',$DBtxt21_dia,$DBtxt21_mes,$DBtxt21_ano ,true,'text',4)
         ?>
       </td>
       <td nowrap align="right" title="<?=@$TDBtxt22?>">
        <?=@$LDBtxt22?>
         <?
          $DBtxt22_ano = db_getsession("DB_anousu"); 
          $DBtxt22_mes = date("m",db_getsession("DB_datausu"));
          $DBtxt22_dia = date("d",db_getsession("DB_datausu"));
          db_inputdata('DBtxt22',$DBtxt22_dia,$DBtxt22_mes,$DBtxt22_ano ,true,'text',4)
         ?>
       </td>
     </tr>
      <tr>
        <td >&nbsp;</td>
        <td >&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2" align = "center"> 
          <input  name="emite2" id="emite2" type="button" value="Processar" onclick="js_emite();" >
        </td>
      </tr>

  </form>
    </table>
<? db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
</body>
</html>