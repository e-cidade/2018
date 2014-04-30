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
include("libs/db_utils.php");

include("dbforms/db_funcoes.php");

include("classes/db_unidades_ext_classe.php");
include("classes/db_sau_fechamento_classe.php");
include("classes/db_sau_config_ext_classe.php");

$clunidades       = new cl_unidades_ext;
$clsau_fechamento = new cl_sau_fechamento;
$clrotulo         = new rotulocampo;
$clsau_config     = new cl_sau_config_ext;

$clrotulo->label("sd02_i_codigo");
$clrotulo->label("sd02_c_nome");

$db_opcao=1;
$desabilita="";

//Sau_Config
$resSau_config = $clsau_config->sql_record($clsau_config->sql_query_ext());
$objSau_config = db_utils::fieldsMemory($resSau_config,0 );

?>


<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" >
<table width="100%" height='18'  border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table valign="top" marginwidth="0" width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#CCCCCC">
 <tr>
  <td height="100%" align="center" valign="top" bgcolor="#CCCCCC">
    <form name='form1'>
    <table>
     <tr>
	     <td align="right" >
	       <b> Competência: Ano/Mês</b>
	     </td>
	     <td>
	     <?          
	      db_input('anocomp1',4,@$anocomp1,true,'text',$db_opcao,""); echo "/";
	      db_input('mescomp1',2,@$mescomp1,true,'text',$db_opcao,"Onchange= 'document.form1.submit()'");
		  if(isset($anocomp1)){
		     $result1 = $clsau_fechamento->sql_record($clsau_fechamento->sql_query("","sd97_c_tipo","","sd97_i_compmes = $mescomp1 and sd97_i_compano = $anocomp1"));
		   if($clsau_fechamento->numrows>0){
		      db_fieldsmemory($result1,0);
			if($sd97_c_tipo=="Aberta"){
			  $desabilita= "disabled";
			  db_msgbox("Competência está Aberta");			 	
			}else{
			  $desabilita="";	
			}	
		   }
		  }
	      ?>
	     </td>

	     <td align="right" >
	       <b>Agrupar:</b>
	     </td>
	     <td>
	     <?
			$x = array('0'=>'NENHUM','1'=>'UNIDADE');
			db_select('agrupar',$x,true,$db_opcao,"");
	     ?>
	     </td>     
	     <td align="right" >
	       <b>BPA</b>
	     </td>
	     <td>
	     <?
			$x = array('0'=>'Analítico','1'=>'Sintético');
			db_select('bpa',$x,true,$db_opcao,"");
	     ?>
	     </td>
	     <td align="right" >
	       <b>Tipo BPA</b>
	     </td>
       <td>
         <?
	       if (! isset ( $pab )) {
			     $pab = "3";
		  	 }
	  		 $arr_pab = array ("1" => "PAB", "2" => "NPAB", "3" => "TODOS");
  			 db_select('pab', $arr_pab, true, 4);
			   ?>
       </td>
     </tr>
     &nbsp;&nbsp;&nbsp;
      <table border="1" cellpadding="0" cellspacing="0" width="90%">
     <?
     $strWhere = $objSau_config->s103_i_departamentos == 1 ? "":"db_depusu.id_usuario = ".db_getsession("DB_id_usuario");
     $result = $clunidades->sql_record( $clunidades->sql_query_ext("","distinct unidades.sd02_i_codigo, db_depart.descrdepto", "descrdepto",$strWhere) );
     //$result = $clunidades->sql_record($clunidades->sql_query());
      //$result = $clunidades->sql_record("select * from unidades inner join db_depart on coddepto=sd02_i_codigo limit 1");
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
     <tr>
       <td colspan='6' align='center' >
         <input name='start' type='button' value='Gerar' <?=$desabilita?> onclick="valida(<?=$clunidades->numrows?>,this)">
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
   query  = "agrupar="+obj.agrupar.value;
   query += "&bpa="+obj.bpa.value;

    if((obj.anocomp1.value !='') && (obj.mescomp1.value !='')){
          query +="&anocomp1="+obj.anocomp1.value+"&mescomp1="+obj.mescomp1.value;
          count +=1;
    } else {
      alert('Preencha a competencia.');
      return;
    }
    query +="&unidades=";
    sep = "";

    for(i=0;i<tudo;i++)
    {
      if( tudo == 1){
        query += obj.unidade.value;
        count += 1;
      }else{
         if(obj.unidade[i].checked == true)
          {
           query += sep+obj.unidade[i].value;
           sep = "X";
           count += 1;
          }        
      }
    }
    query += '&pab='+obj.pab.value;
    if(count<2){
      alert("Preencha os Campos Corretamente!");
    }else{
      jan = window.open('sau2_boletim003.php?'+query,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
      jan.moveTo(0,0);
    }
}
s</script>