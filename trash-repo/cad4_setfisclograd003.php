<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
include("classes/db_lote_classe.php");
include("classes/db_face_classe.php");
include("classes/db_testada_classe.php");
include("classes/db_lotesetorfiscal_classe.php");
include("dbforms/db_classesgenericas.php");
db_postmemory($HTTP_POST_VARS);
$cliframe_seleciona = new cl_iframe_seleciona;
$cllote = new cl_lote;
$clface = new cl_face;
$cltestada = new cl_testada;
$cllotesetorfiscal = new cl_lotesetorfiscal;
$clrotulo = new rotulocampo;
$clrotulo->label("j36_face");
$clrotulo->label("j34_setor");
$clrotulo->label("j34_lote");
$clrotulo->label("j34_quadra");
$clrotulo->label("j90_codigo");
$db_opcao=1;
$db_botao=true;		
if (isset($Incluir)){
	$arr_dados = split("#",$dados);
	$sqlerro=false;
	db_inicio_transacao();
	for($w=0;$w<count($arr_dados);$w++){
		$arr_info  = split("_",$arr_dados[$w]);
		$face      = $arr_info[0];
		$setor     = $arr_info[1];
		$var_zona  = "zonas_$face";
		$zona      = $$var_zona;		
		/*
		if ($setor=="0"){
			continue;
		}
		*/
		$result_idbql=$cltestada->sql_record($cltestada->sql_query_file(null,null,"distinct j36_idbql as idbql",null,"j36_face=$face"));
		for($i=0;$i<$cltestada->numrows;$i++){
			
			db_fieldsmemory($result_idbql,$i);
			if ($setor!="0"){
				
				if ($sqlerro==false){
					
					$result_exist = $cllotesetorfiscal->sql_record($cllotesetorfiscal->sql_query_file(null,"*",null,"j91_idbql=$idbql"));
					if ($cllotesetorfiscal->numrows>0){
						
						$cllotesetorfiscal->excluir(null,"j91_idbql=$idbql");
						if($cllotesetorfiscal->erro_status==0){
							
							$sqlerro=true;
							$erro_msg=$cllotesetorfiscal->erro_msg;
							break;
						}
					}
				}
				if ($sqlerro==false){
					
          $cllotesetorfiscal->j91_idbql=$idbql;
					$cllotesetorfiscal->j91_codigo=$setor;
					$cllotesetorfiscal->incluir($idbql);
					
					if($cllotesetorfiscal->erro_status==0){
						
						$sqlerro=true;
						$erro_msg=$cllotesetorfiscal->erro_msg;
						break;
					}
				}
			}
			if ($zona!="0"){
				
				if ($sqlerro==false){
					
					$cllote->j34_zona = $zona;
					$cllote->j34_idbql = $idbql;
					$cllote->alterar($idbql);
					if ($cllote->erro_status==0){
						$sqlerro=true;
						$erro_msg=$cllote->erro_msg;
					}					
				}
			}
		}
	}
	db_fim_transacao($sqlerro);
	if ($sqlerro==true){
		db_msgbox("Operação cancelada!!\\n $erro_msg");
	}else{
		db_msgbox("Operação efetuada com sucesso!!");
		echo "<script>location.href='cad4_setfisclograd001.php';</script>";
	}
}else if(isset($Alterar)){
	$setor=$unico_setor;
	$zona=$unico_zona;	
	$sqlerro=false;
	db_inicio_transacao();			
	$result_idbql=$cltestada->sql_record($cltestada->sql_query_file(null,null,"distinct j36_idbql as idbql",null,"j36_face in ($faces)"));
	for($i=0;$i<$cltestada->numrows;$i++){
		db_fieldsmemory($result_idbql,$i);
		if ($setor!=0){
			if ($sqlerro==false){
				$result_exist = $cllotesetorfiscal->sql_record($cllotesetorfiscal->sql_query_file(null,"*",null,"j91_idbql=$idbql"));
				if ($cllotesetorfiscal->numrows>0){
					$cllotesetorfiscal->excluir(null,"j91_idbql=$idbql");
					if($cllotesetorfiscal->erro_status==0){
						$sqlerro=true;
						$erro_msg=$cllotesetorfiscal->erro_msg;
						break;
					}
				}
			}
			if ($sqlerro==false){
				$cllotesetorfiscal->j91_idbql=$idbql;
				$cllotesetorfiscal->j91_codigo=$setor;
				$cllotesetorfiscal->incluir($idbql);
				if($cllotesetorfiscal->erro_status==0){
					$sqlerro=true;
					$erro_msg=$cllotesetorfiscal->erro_msg;
					break;
				}
			}
		}
		if ($zona!="0"){
			if ($sqlerro==false){
				$cllote->j34_zona = $zona;
				$cllote->j34_idbql = $idbql;
				$cllote->alterar($idbql);
				if ($cllote->erro_status==0){
					$sqlerro=true;
					$erro_msg=$cllote->erro_msg;
				}					
			}
		}
	}
	db_fim_transacao($sqlerro);
	if ($sqlerro==true){
		db_msgbox("Operação cancelada!!\\n$erro_msg");
	}else{
		db_msgbox("Operação efetuada com sucesso!!");
		echo "<script>location.href='cad4_setfisclograd001.php';</script>";
	}
	
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_dados(){
	
	obj= document.form1;
	valor="";
	arr_nome="";
	sep="";
	for (i=0;i<obj.elements.length;i++){
		if (obj.elements[i].name.substr(0,8)=="setfisc_"){
		  arr_nome=obj.elements[i].name.split("_");
		  face = arr_nome[1];
		  if (obj.elements[i].name.search("descr")==-1){	      
	      	valor+=sep+face+"_"+obj.elements[i].value;	      
	      	sep = "#";
	      }
	    }	    
	}
	document.form1.dados.value = valor;
	return true; 	
}
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style>
<?//$cor="#999999"?>
.bordas{
         border: 2px solid #cccccc;
         border-top-color: #999999;
         border-right-color: #999999;
         border-left-color: #999999;
         border-bottom-color: #999999;
         background-color: #999999;
/*         border: 2px solid #cccccc;
         border-top-color: <?=$cor?>;
         border-right-color: <?=$cor?>;
         border-bottom-color: <?=$cor?>;
         background-color: #999999;
*/	 
}
<?//$cor="999999"?>
.bordas_corp{
/*       border: 1px solid #cccccc;
         border-right-color: <?=$cor?>;
         border-bottom-color: <?=$cor?>;
*/
         border: 1px solid #cccccc;
         border-top-color: #999999;
         border-right-color: #999999;
         border-left-color: #999999;
         border-bottom-color: #999999;
         background-color: #cccccc;
       }
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="790" border="0" cellpadding="0" cellspacing="0" >
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<form name="form1" action="" method="post">
<table border="0" cellspacing="0" cellpadding="0" width="100%">
  <br>
  <br>
  <tr> 
    <td align="left" valign="top" bgcolor="#CCCCCC">     
    <center>
    <table >
    <tr>
    <td align="center">
        <b>Alterar Todas Faces Para o Setor Fiscal:</b>        
    	<?
    	$sql1="select 0 as j90_codigo, 'Selecione Setor Fiscal' as j90_descr
                  union all
                  select j90_codigo,j90_descr from setorfiscal
						";			 
		   $result1=pg_exec($sql1);	 
		   
		
		   db_selectrecord("unico_setor",$result1,true,"text");
        
    	?>
    	<b>e Zona:</b>
    	<?
    	$sql2="select 0 as j50_zona, 'Nenhum...' as j50_descr
                  union all
                  select j50_zona,j50_descr from zonas
						";			 
		   $result2=pg_exec($sql2);	 
		   
		
		   db_selectrecord("unico_zona",$result2,true,"text");
        
    	?>
    	<input name="Alterar" type="submit"  value="Alterar"  >
    </td>
  </tr>
    <tr>
    <td>
    <table>
    
    <? 
        db_input("chaves",10,"",true,"hidden",3);
        db_input("dados",10,"",true,"hidden",3);
        $faces = str_replace("#",",",$chaves);
        db_input("faces",10,"",true,"hidden",3);
        $result=$cltestada->sql_record($cltestada->sql_query(null,null,"distinct j36_face,j34_setor,j34_quadra","j36_face","j36_face in ($faces)"));
        $numrows=$cltestada->numrows;    	
		if ($numrows > 0) {
		echo "<tr class='bordas'>
					    <td class='bordas' align='center'><b><small>$RLj36_face</small></b></td>
						<td class='bordas' align='center'><b><small>$RLj34_setor</small></b></td>
						<td class='bordas' align='center'><b><small>$RLj34_quadra</small></b></td>
						<td class='bordas' align='center'><b><small>Setores Fiscais da Face</small></b></td>						
						<td class='bordas' align='center'><b><small>Novo Setor Fiscal</small></b></td>
						<td class='bordas' align='center'><b><small>Nova zona para lotes da face</small></b></td>
					     ";
		} else
			echo "<b>Nenhum registro encontrado...</b>";
		echo "</tr>";
	    for($w=0;$w<$numrows;$w++){
	    	db_fieldsmemory($result,$w);
           echo "<tr>";
		   echo "<td class='bordas_corp' align='center'><small>$j36_face</small></td>
                 <td class='bordas_corp' align='center'><small>$j34_setor</small></td>
				 <td class='bordas_corp' align='center'><small>$j34_quadra</small></td>				   				
				";
		   $setores=array();
		   $sep="";
		   $result_exist=pg_exec("select  distinct j90_codigo as cod,j90_descr as descr from testada
											inner join lotesetorfiscal on j91_idbql = j36_idbql
											inner join setorfiscal on j90_codigo = j91_codigo
		    				      where j36_face = $j36_face");
           for($i=0;$i<pg_numrows($result_exist);$i++){
           	db_fieldsmemory($result_exist,$i);
           	array_push($setores, $descr );           	
           }		    				                 							
           echo "<td class='bordas_corp' align='center'><small>";
           db_select("setorant",$setores,true,"text");
           echo "&nbsp HHHH</small></td>";		   
		   $sql1="select 0 as j90_codigo, 'Nenhum...' as j90_descr
                  union all
                  select j90_codigo,j90_descr from setorfiscal
						";			 
		   $result1=pg_exec($sql1);	 
		   
		   echo "<td class='bordas_corp' align='center'><small>";
		   db_selectrecord("setfisc_$j36_face",$result1,true,"text");
           echo "</small></td>";
           $sql2="select 0 as j50_zona, 'Nenhum...' as j50_descr
                  union all
                  select j50_zona,j50_descr from zonas
						";			 
		   $result2=pg_exec($sql2);	 
		   
		   echo "<td class='bordas_corp' align='center'><small>";
		   db_selectrecord("zonas_$j36_face",$result2,true,"text");
           echo "</small></td>";		   
		   		   
		   echo "</tr>";
	    }
    ?>
    </table>
    </td>
    </tr>
    </table>
    </center>
    </td>
  </tr>
  <tr>
    <td align="center">
    	<input name="Incluir" type="submit"  value="Incluir"  onclick="return js_dados();">
    </td>
  </tr>  
</table>
</form>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>