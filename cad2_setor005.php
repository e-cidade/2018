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
include("libs/db_usuariosonline.php");
include("classes/db_lote_classe.php");
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$cllote							= new cl_lote;
$cliframe_seleciona = new cl_iframe_seleciona;
$clrotulo						= new rotulocampo;

$cllote->rotulo->label();
$clrotulo->label("z01_nome");

if(isset($j34_zona) && $j34_zona != ""){
  $zona = split(",",$j34_zona);
  $vir = "";
  $zon = "";
  for($i=0;$i<count($zona);$i++){
    $zon .= $vir."'".$zona[$i]."'";
    $vir = ",";
  }
}
if(isset($j34_setor) && $j34_setor != ""){
  $setor = split(",",$j34_setor);
  $vir = "";
  $set = "";
  for($i=0;$i<count($setor);$i++){
    $set .= $vir."'".$setor[$i]."'";
    $vir = ",";
  }
}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<script>

function js_loadVars(iInicio,iFinal){
	
	var oColection = quadras.document.getElementsByTagName('input');
	
	for(var i = iInicio; i <  iFinal  ; i++ ){
    if(oColection[i].type == "checkbox"){
			if(oColection[i].checked == true){
				j34_quadra += vir +  quadras.document.form1.elements[i].value;
				vir = ",";
      }
    }
  }
	if (i == aCol.length){
		parent.iframe_g1.document.form1.j34_quadra.value = j34_quadra;
		js_removeObj("msgbox");
	}
}


function js_nome(obj){
    
  js_divCarregando('Aguarde, efetuando pesquisa ....','msgbox');
	j34_quadra      = "";
	vir			 	     = "";
	iQuantidade		 = 0;
  aCol					 = quadras.document.getElementsByTagName('input');
	iTamanhoVoltas = ( aCol.length / 10);
	iResto				 = ( aCol.length % 10);
	iTamanhoVoltas = Math.floor(iTamanhoVoltas);
	
	if ( iTamanhoVoltas > 500 ){ 
		
		for ( var ii = 0 ; ii < 10; ii++ ) {
			tmp = setTimeout("js_loadVars("+iQuantidade+","+( ii==9?eval(iQuantidade+iResto+iTamanhoVoltas):eval(iQuantidade+iTamanhoVoltas))+")",2000);
			iQuantidade = eval(iQuantidade+iTamanhoVoltas);
		}	
	
	}else{
    
		js_loadVars(0,aCol.length);
	
	}
}

</script>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
	<table width="790" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr> 
      <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
        <center>
					<form name="form1" method="post" action="cad2_setor005.php" target="">
						<center>
							<table border="0" align="center">
								<tr>
									<td align="top" colspan="2">
                    <?
                    
                    $sql = "select distinct j34_zona,
                														j34_quadra
          													from lote
                										inner join zonas on j50_zona = j34_zona
        													where j34_zona in ( $zon ) and j34_setor in ($set);
                       				";
                    if(isset($j34_setor)&& $j34_setor!=""){
										  $cliframe_seleciona->campos  = "j34_zona,j34_quadra";
										  $cliframe_seleciona->legenda="QUADRAS";
//										  $cliframe_seleciona->sql=$clface->sql_query("","distinct j37_quadra,j37_setor","j37_setor,j37_quadra","j37_setor in ($set) ");
										  $cliframe_seleciona->sql=$sql;
										  $cliframe_seleciona->textocabec ="darkblue";
										  $cliframe_seleciona->textocorpo ="black";
										  $cliframe_seleciona->fundocabec ="#aacccc";
										  $cliframe_seleciona->fundocorpo ="#ccddcc";
										  $cliframe_seleciona->iframe_height ="250";
										  $cliframe_seleciona->iframe_width ="700";
										  $cliframe_seleciona->iframe_nome ="quadras";
										  $cliframe_seleciona->chaves ="j34_quadra";
										  $cliframe_seleciona->dbscript ="onClick='parent.js_nome(this)'";
										  $cliframe_seleciona->js_marcador="parent.js_nome()";
										  $cliframe_seleciona->iframe_seleciona(@$db_opcao);   
                    }else{
										  echo "<br><strong>SELECIONE UMA ZONA PARA ESCOLHER A(S) QUADRAS(S)</strong>";
                      //echo "<script>parent.document.formaba.g3.disabled = true;</script>";
	                  }

									  ?>   
								  </td>
							  </tr>
								<tr>
									<td>
										<input type="hidden" name="j34_zona">
										<input type="hidden" name="j34_setor" value="<?@$j34_setor?>">
									</td>
							  </tr>
							</table>
						</center>
				  </form>
				</center>
			</td>
		</tr>
	</table>
</body>
</html>