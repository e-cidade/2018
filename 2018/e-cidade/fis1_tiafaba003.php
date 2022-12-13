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
include("classes/db_tiaf_classe.php");
include("classes/db_tiafprazo_classe.php");
include("classes/db_tiafcgm_classe.php");
include("classes/db_tiafinscr_classe.php");
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");
//db_postmemory($HTTP_SERVER_VARS,2);

$cltiaf      = new cl_tiaf;
$cltiafcgm   = new cl_tiafcgm;
$cltiafinscr = new cl_tiafinscr;
$cltiafprazo = new cl_tiafprazo;
$clrotulo    = new rotulocampo;

$cltiaf->rotulo->label();
$cltiafprazo->rotulo->label();
$clrotulo->label("z01_nome");
$clrotulo->label("q03_descr");
$clrotulo->label("z01_numcgm");
$clrotulo->label("j01_matric");
$clrotulo->label("q02_inscr");
$sqlerro = false;
$valida == false;

/////////////// VARIAVEIS DO FORMULARIO /////////////

$tipobotao = "Alterar";                 //tipo do botao
echo "<script> passa = 'n'; </script>"; //variavel js para a func saber se passa para proxima ab ou naun
$tipoancora = "1";
$db_opcao = "1";

///////////// ALTERAÇÃO DO TIAF ////////////////////////

if (isset($incluir) && $incluir == "Alterar"){
	db_inicio_transacao();
	$prazo = $y96_prazo_ano.$y96_prazo_mes.$y96_prazo_dia; 
	$data  = $y90_data_ano.$y90_data_mes.$y90_data_dia;
	if (isset($prazo) && $prazo <= $data){
		db_msgbox("A data do prazo não pode ser menor que data do TIAF !");
		$sqlerro=true;
		$valida == false;
	}
	
	if (isset($z01_numcgm) && $z01_numcgm != ""){
		$cltiafcgm->y95_numcgm = $z01_numcgm;
		$cltiafcgm->y95_codtiaf = $y90_codtiaf;
		$cltiafcgm->alterar($y90_codtiaf,"");
		if ($cltiafcgm->erro_status==0){
    	   $sqlerro=true;
	    }
	    else{
	        $erro_msg= $cltiaftiafcgm->erro_msg;
	    }
	}
	if (isset($q02_inscr) && $q02_inscr != ""){
		$cltiafinscr->y94_inscr = $q02_inscr;
		$cltiafinscr->y94_codtiaf = $y90_codtiaf;
		$cltiafinscr->alterar($y90_codtiaf);
		if ($cltiafinscr->erro_status==0){
    		$sqlerro=true;
	    }
	    else{
	        $erro_msg= $cltiaftiafinscr->erro_msg;
	    }
	}  
    $cltiafprazo->y96_prazo = $y96_prazo_ano."-".$y96_prazo_mes."-".$y96_prazo_dia;
	//$cltiafprazo->y96_codtiaf = $y90_codtiaf;
	$rsResult = pg_exec("select y96_codigo as codigo from tiafprazo where y96_codtiaf = $y90_codtiaf");
	db_fieldsmemory($rsResult,0);
	$cltiafprazo->y96_codigo = $codigo;
	$cltiafprazo->alterar($codigo);
	if ($cltiafprazo->erro_status==0){
    	$sqlerro=true;
    }
    else{
        $erro_msg= $cltiafprazo->erro_msg;
    }
        
    $cltiaf->y90_data = $y90_data_ano."-".$y90_data_mes."-".$y90_data_dia;
	$cltiaf->y90_atend = "f";
	//$cltiaf->y90_codtiaf = $y90_codtiaf;
	$cltiaf->alterar($y90_codtiaf);
	if ($cltiaf->erro_status==0){
    	$sqlerro=true;
    }
    else{
        $erro_msg= $cltiaf->erro_msg;
        $limpa = true;
        
    }
    /*
    if($sqlerro==true){
    	db_msgbox("true");
    }else{
    	db_msgbox("false");
    }*/
	db_fim_transacao($sqlerro);
} 

////////////////////////////////////////////////////////////////


?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#CCCCCC">
 
</table>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
	    <?
		    if(isset($y90_codtiaf) && $y90_codtiaf != ""){  
//			    echo($cltiaf->sql_querytiaf("","*","","y90_codtiaf = $y90_codtiaf"));
			    $rsResult = $cltiaf->sql_record($cltiaf->sql_querytiaf("","*","","y90_codtiaf = $y90_codtiaf"));
				if ($cltiaf->numrows > 0){
			    	db_fieldsmemory($rsResult,0);
			    	list($y90_data_ano,$y90_data_mes,$y90_data_dia) = split ("-", $y90_data);
			   		list($y96_prazo_ano,$y96_prazo_mes,$y96_prazo_dia) = split ("-", $y96_prazo);
			   		if ($tipo == "cgm"){
						$z01_numcgm = $numero;
						$z01_nomecgm = $z01_nome;
					}
					if ($tipo == "inscricao"){
						$q02_inscr = $numero;
						$z01_nomeinscr = $z01_nome;
						$z01_numcgm = "";
						
					}
			    }
		    }
		    include("forms/db_frmtiaf001.php");
		    /*if ($limpa == true){
       			echo "<script>js_limpacampos()</script>";
		    }*/
	    ?> 
	</td>
  </tr>
</table>
</body>
</html>

<?
if(isset($incluir)){
  if($cltiaf->erro_status=="0"){
    $cltiaf->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>";
    if($cltiaf->erro_campo!=""){
      echo "<script> document.form1.".$cltiaf->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$cltiaf->erro_campo.".focus();</script>";
    };
  }else{
    db_msgbox($erro_msg);
    /*echo "<script>
               parent.iframe_g2.location.href='fis1_tiafaba002.php?y90_codtiaf=".$cltiaf->y90_codtiaf."';\n
               parent.iframe_g1.location.href='fis1_tiafaba001.php?chavepesquisa=".@$codigo."';\n
               parent.mo_camada('g2');
               parent.document.formaba.matrequiitem.disabled = false;\n
	 </script>";*/
  };
};
?>