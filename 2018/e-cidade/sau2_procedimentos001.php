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
include("classes/db_unidades_classe.php");
$clunidades = new cl_unidades;
$clrotulo = new rotulocampo;
$clrotulo->label("sd29_i_procedimento");
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
<table width="790" height='18'  border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
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
    <table align="center" width="100%" border="0">
     <tr>
     <td align="right" width="30%">
       <b> Período:</b>
     </td>
     <td>
       <?db_inputdata('data1',@$dia1,@$mes1,@$ano1,true,'text',1,"")?>
        A
       <?db_inputdata('data2',@$dia2,@$mes2,@$ano2,true,'text',1,"")?>
     </td>
     </tr>
     <tr>
      <td nowrap align="right" >
       <?db_ancora("<b>Procedimento:</b>","js_pesquisa_procedimento(true);",1)?>
      </td>
      <td>
       <?db_input('sd63_c_procedimento',11,"",true,'text',1," onchange='js_pesquisa_procedimento(false);'")?>
       <?db_input('sd63_c_nome',70,"",true,'text',3,'')?>
      </td>
     </tr>
     <tr>
        <td align="right">
           <b>Agrupar:</b>
        </td>
        <td>
            <select name="group" id="group" >
                  <option value="1">Unidade</option>
                  <option value="2">Procedimento</option>
            </select>
        </td>
     </tr>
     <tr>
     <td colspan="2">
     <table border="1" cellpadding="0" cellspacing="0" width="100%">
     <?
      $result = $clunidades->sql_record($clunidades->sql_query());
      if($clunidades->numrows > 0){
     ?>
       <tr>
        <td bgcolor="#D0D0D0" width="30"><input type="button" value="M" name="marca" title="Marcar/Desmarcar" onclick="marcar(<?=$clunidades->numrows?>, this)"></td>
        <td colspan="5"><b>Selecione as Unidades</b></td>
       </tr>
      <?$bg = "#E8E8E8";
        echo "<tr bgcolor='#b0b0b0'>";
        for($u=0; $u< $clunidades->numrows; $u++){
         db_fieldsmemory($result,$u);
         echo "<td align='center' width='30'><input type='checkbox' value='$sd02_i_codigo' name='unidade'></td><td align='center' width='50'>".$sd02_i_codigo."</td><td width='400'>".$descrdepto."</td>";
          @$coluna = $coluna + 1;
          if ($coluna>1)
            {
             echo "<tr>";
             echo "<tr bgcolor='$bg'>";
             if($bg == "#E8E8E8"){
              $bg = "#B0B0B0";
             }else{
              $bg = "#E8E8E8";
             }
             $coluna = 0;
            }
        }
        }else{
         echo "<tr><td class='texto'>Unidades não cadastradas</td></tr>";
        }
      ?>
      </table>
      </td>
      </tr>
     <tr>
       <td colspan='6' align='center' >
         <input name='start' type='button' value='Gerar' onclick="valida(<?=$clunidades->numrows?>,this)">
       </td>
     </tr>
    </table>
    </form>
  </td>
 </tr>
</table>
    <?
      db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
    ?>
</body>
</html>
<script>
function js_pesquisa_procedimento(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_procedimento','func_sau_procedimento.php?funcao_js=parent.js_mostraprocedimento1|sd63_c_procedimento|sd63_c_nome','Pesquisa',true);
  }else{
     if(document.form1.sd63_c_procedimento.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_procedimento','func_sau_procedimento.php?pesquisa_chave='+document.form1.sd63_c_procedimento.value+'&funcao_js=parent.js_mostraprocedimento','Pesquisa',false);
     }else{
       document.form1.sd09_c_descr.value = '';
     }
  }
}
function js_mostraprocedimento(chave,erro){
  document.form1.sd63_c_nome.value = chave;
  if(erro==true){
    document.form1.sd63_c_procedimento.focus();
    document.form1.sd63_c_procedimento.value = '';
  }
}
function js_mostraprocedimento1(chave1,chave2){
  document.form1.sd63_c_procedimento.value = chave1;
  document.form1.sd63_c_nome.value = chave2;
  db_iframe_procedimento.hide();
}
function marcar(tudo,documento)
 {
  for(i=0;i<tudo;i++)
   {
    if(documento.value=="D")
     {
      document.form1.unidade[i].checked=false;
     }
    if(documento.value=="M")
     {
      document.form1.unidade[i].checked=true;
     }
   }
  if(document.form1.marca.value == "D")
   {
    document.form1.marca.value="M";
   }
  else
   {
    document.form1.marca.value="D";
   }
 }

function valida(tudo,documento){
   obj = document.form1;
   count = 0;
   query='';

    if((obj.data1_dia.value !='') && (obj.data1_mes.value !='') && (obj.data1_ano.value !='') && (obj.data1_dia.value !='') && (obj.data2_mes.value !='') && (obj.data2_ano.value !='')){
          query +="data1="+obj.data1_ano.value+"X"+obj.data1_mes.value+"X"+obj.data1_dia.value+"&data2="+obj.data2_ano.value+"X"+obj.data2_mes.value+"X"+obj.data2_dia.value;
      count += 1;
    }
    query +="&unidades=";
    sep = "";
    for(i=0;i<tudo;i++)
    {
     if(document.form1.unidade[i].checked == true)
      {
       query += sep+document.form1.unidade[i].value;
       sep = "X";
       count += 1;
      }    
    }
    if(obj.group.value==1){
        n='2';
    }else{
        n='3';
    }
    if(count<2){
      alert("Indique o período e selecione uma Unidade!");
    }else{
      query +="&procedimento="+obj.sd63_c_procedimento.value;
      jan = window.open('sau2_procedimentos00'+n+'.php?'+query,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
      jan.moveTo(0,0);
    }
}
</script>