<?
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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("libs/db_liborcamento.php");
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
variavel = 1;
function js_emite(){
   valor_nivel = new Number(document.form1.orgaos.value);
   sel_instit  = new Number(document.form1.db_selinstit.value);

   if(sel_instit == 0){
      alert('Você não escolheu nenhuma Instituição. Verifique!');
      return false;
   } else {
    jan = window.open('','safo' + variavel,'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    document.form1.target = 'safo' + variavel++;
    document.form1.filtra_despesa.value = parent.iframe_filtro.js_atualiza_variavel_retorno();
    var obj      = document.form1.nivel;
    var virgula  = "";
    var vernivel = "";

    var flag_elemento = false;
    var flag_grupo    = false;
    for (var i = 0; i < obj.options.length; i++){
          if (obj.options[i].selected == true){
//               if (obj.options[i].value == "7B" && flag_grupo == false){
                    vernivel += virgula+obj.options[i].value;
                    virgula   = ",";
//                    flag_elemento = true;
//               }
/*
               if (obj.options[i].value == "9B" && flag_elemento == false){
                    vernivel += virgula+obj.options[i].value;
                    virgula   = ",";
                    flag_grupo = true;
               }

               if (obj.options[i].value != "7B" && obj.options[i].value != "9B"){
                    vernivel += virgula+obj.options[i].value;
                    virgula   = ",";
               }
*/               
          }
    }

    document.form1.vernivel.value = vernivel;
    //setTimeout("document.form1.submit()",1000);
    return true;
    }
}
</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
 <form name="form1" method="post" action="con2_balancdespsinteticocsv002.php">
      <table  align="center">
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td colspan="2">
          <?
            db_selinstit("",400,150);
          ?>
          </td>
        </tr>
      </table>

      <table border="0" align="center" width="400">
       <tr>
         <td colspan="2">
           <fieldset>
             <table border="0">
               <tr>
                 <td align="right" ><strong>Nível :</strong></td>
                 <td>
                 <?
                   $xy = array('1B'=>'Órgão','2A'=>'Unidade','3B'=>'Função','4B'=>'Subfunção','5B'=>'Programa','6B'=>'Proj/Ativ','7B'=>'Elemento','8B'=>'Recurso');
              	   db_select('nivel',$xy,true,2,"");
	               ?>
                 </td>
               </tr>
             </table>
           </fieldset>
         </td>
       </tr> 
      </table>

      <table border="0" align="center" width="400">
       <tr>
         <td colspan="2">
           <fieldset>
             <table border="0">
               <tr>
                 <td nowrap align="right" title="<?=@$TDBtxt21?>"><?=@$LDBtxt21?></td>
                 <td>
                 <?
                   $DBtxt21_ano = db_getsession("DB_anousu");
                   $DBtxt21_mes = '01';
                   $DBtxt21_dia = '01';
                   db_inputdata('DBtxt21',$DBtxt21_dia,$DBtxt21_mes,$DBtxt21_ano ,true,'text',4)
                 ?>
                 </td>
               </tr>
               <tr>
                 <td nowrap align="right" title="<?=@$TDBtxt22?>"><?=@$LDBtxt22?></td>
                 <td>
                 <?
                   $DBtxt22_ano = date("Y",db_getsession("DB_datausu"));
                   $DBtxt22_mes = date("m",db_getsession("DB_datausu"));
                   $DBtxt22_dia = date("d",db_getsession("DB_datausu"));
                   db_inputdata('DBtxt22',$DBtxt22_dia,$DBtxt22_mes,$DBtxt22_ano ,true,'text',4)
                 ?>
                 </td>
               </tr>
             </table>  
           </fieldset>
         </td>
       </tr> 
      </table>

      <table align="center">
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td colspan="2" align = "center"> 
            <input  name="emite2" id="emite2" type="submit" value="Processar" onclick="return js_emite();">
            <input  name="orgaos" id="orgaos" type="hidden" value="">
            <input  name="vernivel" id="vernivel" type="hidden" value="">
            <input  name="filtra_despesa" id="filtra_despesa" type="hidden" value="">
          </td>
        </tr>
      </table>

 </form>
</body>
</html>
<script>
   document.forms[0].nivel.multiple = true;
</script>