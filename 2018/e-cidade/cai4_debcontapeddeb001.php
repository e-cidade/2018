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

require ("libs/db_stdlib.php");
require ("libs/db_conecta.php");
include ("libs/db_usuariosonline.php");
include ("dbforms/db_funcoes.php");
include ("dbforms/db_classesgenericas.php");
//include ("classes/db_debcontapedidonumpre_classe.php");
include ("classes/db_debcontapedidotiponumpre_classe.php");
include ("classes/db_debcontapedidotipo_classe.php");
include ("classes/db_debcontapedidopref_classe.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
//$cldebcontapedidonumpre = new cl_debcontapedidonumpre;
$cldebcontapedidotiponumpre = new cl_debcontapedidotiponumpre;
$cldebcontapedidotipo = new cl_debcontapedidotipo;
$cldebcontapedidopref = new cl_debcontapedidopref;
$cliframe_seleciona = new cl_iframe_seleciona;
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
if (isset ($incluir) && $incluir == true) {
	db_inicio_transacao();
	$erro_msg = "Inclusão efetuada com sucesso!!";
	$sqlerro = false;
	if ($sqlerro == false) {
		$cldebcontapedidotiponumpre->excluir(null, "d67_codigo=$codigo");
		if ($cldebcontapedidotiponumpre->erro_status == 0) {
			$sqlerro = true;
			$erro_msg = $cldebcontapedidotiponumpre->erro_msg;

		}
	}
	if ($sqlerro == false) {
		$cldebcontapedidotipo->excluir(null, "d66_codigo=$codigo");
		if ($cldebcontapedidotipo->erro_status == 0) {
			$sqlerro = true;
			$erro_msg = $cldebcontapedidotipo->erro_msg;

		}
	}
	/*
	if ($sqlerro == false) {
	$cldebcontapedidonumpre->excluir($codigo);
	if ($cldebcontapedidonumpre->erro_status == 0) {
		$sqlerro = true;
		$erro_msg = $cldebcontapedidonumpre->erro_msg;
		
	}
	}
	*/
	if ($sqlerro == false) {
		$cldebcontapedidopref->excluir($codigo);
		if ($cldebcontapedidopref->erro_status == 0) {
			$sqlerro = true;
			$erro_msg = $cldebcontapedidopref->erro_msg;

		}
	}
	$arr_dados = split("#", $chaves);
	for ($i = 0; $i < count($arr_dados); $i ++) {
		$info = split("-", $arr_dados[$i]);
		$numpre = $info[0];
		$numpar = $info[1];
		$tipo = $info[2];
		if ($sqlerro == false) {
			$cldebcontapedidotiponumpre->d67_numpar = $numpar;
			$cldebcontapedidotiponumpre->d67_numpre = $numpre;
			$cldebcontapedidotiponumpre->d67_codigo = $codigo;
			$cldebcontapedidotiponumpre->incluir(null);
			if ($cldebcontapedidotiponumpre->erro_status == 0) {
				$sqlerro = true;
				$erro_msg = $cldebcontapedidotiponumpre->erro_msg;
			}
		}
		if ($sqlerro == false) {
			$result_arretipo = $cldebcontapedidotipo->sql_record($cldebcontapedidotipo->sql_query_file(null, "*", null, "d66_arretipo=$tipo and d66_codigo=$codigo"));
			if ($cldebcontapedidotipo->numrows == 0) {
				$cldebcontapedidotipo->d66_arretipo = $tipo;
				$cldebcontapedidotipo->d66_codigo = $codigo;
				$cldebcontapedidotipo->incluir(null);
				if ($cldebcontapedidotipo->erro_status == 0) {
					$sqlerro = true;
					$erro_msg = $cldebcontapedidotipo->erro_msg;
				}
			}
		} /*
				if ($sqlerro == false) {
					$result_numpre=$cldebcontapedidonumpre->sql_record($cldebcontapedidonumpre->sql_query_file(null,"*",null,"d71_numpre=$numpre and d71_codigo=$codigo"));
					if ($cldebcontapedidonumpre->numrows==0){
					$cldebcontapedidonumpre->d71_numpre = $numpre;
					$cldebcontapedidonumpre->d71_codigo = $codigo;
					$cldebcontapedidonumpre->incluir($codigo);
					if ($cldebcontapedidonumpre->erro_status == 0) {
						$sqlerro = true;
						$erro_msg = $cldebcontapedidonumpre->erro_msg;
					}
					}
				}*/

	}
	if ($sqlerro == false) {
		$cldebcontapedidopref->d65_usuario = db_getsession("DB_id_usuario");
		$cldebcontapedidopref->d65_codigo = $codigo;
		$cldebcontapedidopref->incluir($codigo);
		if ($cldebcontapedidopref->erro_status == 0) {
			$sqlerro = true;
			$erro_msg = $cldebcontapedidopref->erro_msg;
		}
	}
	db_msgbox($erro_msg);
	db_fim_transacao($sqlerro);
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
function js_submit(){
   chaves="";	
   sust="";
   obj= itens.document.form1;
   for (i=0;i<obj.elements.length;i++){
     if (obj.elements[i].name.substr(0,6)=="CHECK_"){
       cheke=obj.elements[i].name.split("_");
       if (eval("obj.CHECK_"+cheke[1]+"_"+cheke[2]+"_"+cheke[3]+".checked")==true){	 
	 	   chaves+=sust+cheke[1]+"-"+cheke[2]+"-"+cheke[3]
	 	   sust="#";
	   }     
     }  
   }
  
  document.form1.chaves.value=chaves;
	document.form1.tipo_info.value=document.form1.tipo.value;
	document.form1.incluir.value=true;	
	//js_gera_chaves()	
	document.form1.submit();
}
</script>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table  border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td valign="top" bgcolor="#CCCCCC"> 
    <center>
      <form name="form1" method="post">
      <center>
      <table border="0" >
      <tr>
        <br>
        <br>
          <td align="center" colspan="4">
          
        <input name="processar" type="button" value="Processar" onclick="js_submit();" >
        </td>
        </tr>
        <tr>
        
          <td align="center" colspan="4">
          <br>
        <br>        
   <? 
$incluir = false;
if (isset ($tipo_info) && $tipo_info != "") {
	$tipo = $tipo_info;
}
db_input('tipo', 10, "", true, 'hidden', 3, "");
db_input('tipo_info', 10, "", true, 'hidden', 3, "");
db_input('incluir', 10, "", true, 'hidden', 3, "");
db_input('codtipo', 10, "", true, 'hidden', 3, "");
db_input('codigo', 10, "", true, 'hidden', 3, "");
db_input('chaves', 10, "", true, 'hidden', 3, "");
$tab = "";
$where = "";
if (isset ($tipo) && $tipo == "CGM") {
	$tab = "arrenumcgm";
	$where = " arrenumcgm.k00_numcgm=$codtipo ";
} else	if (isset ($tipo) && $tipo == "MATRIC") {
	$tab = "arrematric";
	$where = " arrematric.k00_matric=$codtipo ";
} else	if (isset ($tipo) && $tipo == "INSCR") {
	$tab = "arreinscr";
	$where = " arreinscr.k00_inscr=$codtipo ";
}
echo           "<iframe name='itens' id='itens' src='cai4_debcontaped_iframe.php?tab=$tab&where=$where&codigo=$codigo' width='720' height='350' marginwidth='0' marginheight='0' frameborder='0'>
	  </iframe>";
/*
$sql = "select arrecad.k00_numpre,k00_numpar,k00_dtvenc,sum(k00_valor)as k00_valor,arrecad.k00_tipo,k00_descr
	              from $tab 
	                inner join arrecad on arrecad.k00_numpre = $tab.k00_numpre
	                inner join arretipo on arrecad.k00_tipo = arretipo.k00_tipo 
	              where $where 
	              group by arrecad.k00_numpre,k00_numpar,k00_dtvenc,arrecad.k00_tipo,k00_descr
				  order by arrecad.k00_numpre,arrecad.k00_numpar
                  ";
$cliframe_seleciona->campos = "k00_numpre,k00_numpar,k00_dtvenc,k00_valor,k00_tipo,k00_descr";
$cliframe_seleciona->legenda = "Débitos";
$cliframe_seleciona->sql = $sql;
$sql_marca = "select arrecad.k00_numpre,k00_numpar,arrecad.k00_tipo 
              from debcontapedidotiponumpre 
                   inner join arrecad on k00_numpre=d67_numpre and k00_numpar=d67_numpar
                   inner join arretipo on arrecad.k00_tipo = arretipo.k00_tipo where d67_codigo=$codigo ";
//die($sql_marca);
$cliframe_seleciona->sql_marca = $sql_marca;
$cliframe_seleciona->textocabec = "darkblue";
$cliframe_seleciona->textocorpo = "black";
$cliframe_seleciona->fundocabec = "#aacccc";
$cliframe_seleciona->fundocorpo = "#ccddcc";
$cliframe_seleciona->iframe_height = "400";
$cliframe_seleciona->iframe_width = "600";
$cliframe_seleciona->iframe_nome = "setor";
$cliframe_seleciona->chaves = "k00_numpre,k00_numpar,k00_tipo";
$cliframe_seleciona->dbscript = "";
//$cliframe_seleciona->marcador =false;
$cliframe_seleciona->iframe_seleciona(@ $db_opcao);
*/
?>
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