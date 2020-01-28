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
include("classes/db_apolitem_classe.php");
include("classes/db_apolice_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clapolitem = new cl_apolitem;
$clapolice = new cl_apolice;
$db_opcao = 22;
$db_botao = false;
if(isset($alterar) || isset($excluir) || isset($incluir)|| isset($verificado)){
  $sqlerro = false;
  $clapolitem->t82_codapo = $t82_codapo;
  $clapolitem->t82_codbem = $t82_codbem;
}
if(isset($incluir) || isset($verificado)){
  if($sqlerro==false){
    db_inicio_transacao();
    if(empty($verificado) && $sqlerro==false){
      $result =  $clapolitem->sql_record($clapolitem->sql_query_apolice(null,null,"t81_codapo,t81_apolice","","t82_codbem=$t82_codbem and t81_venc >='".date("Y-m-d",db_getsession("DB_datausu"))."'"));
      $numrows = $clapolitem->numrows;
      if($numrows > 0){
	$cods = "";
	$fim = "";
	if($numrows>6){
	  $numrows=($numrows-5);
	  $oParms = new stdClass;
	  $oParms->total_apolices = $numrows;
	  
	  $fim = _M("patrimonial.patrimonio.pat1_apolitem001.bem_ja_incluido", $oParms);
	  $numrows = 5;
	}
	$alerta = true;
	for($i=0;$i<$numrows;$i++){
	  db_fieldsmemory($result,$i);
	  if($t81_codapo!=$t82_codapo){
	    /*  	 
	     *  $oParms = new stdClass;
	     *  $oParms-> codigo_apolice = $t81_codapo;
	     *  $oParms-> apolice = $t81_apolice;
	     *  
	     *  $cods.= _M("patrimonial.patrimonio.pat1_apolitem001.codigo_apolice", $oParms);
	     */
	    $cods.= "\\n".$t81_codapo." --- $t81_apolice";
	  }else{
	    $alerta = false;
	    break;
	  }
	}
	if($alerta==true){
	  $cods.= $fim;
          $sqlerro=true;
          unset($incluir);
	}
      }
    }  
    $clapolitem->incluir($t82_codapo,$t82_codbem);
    $erro_msg = $clapolitem->erro_msg;
    if($clapolitem->erro_status==0){
      $sqlerro=true;
    }
  db_fim_transacao($sqlerro);
  }
}else if(isset($excluir)){
  if($sqlerro==false){
    db_inicio_transacao();
    $clapolitem->excluir($t82_codapo,$t82_codbem);
    $erro_msg = $clapolitem->erro_msg;
    if($clapolitem->erro_status==0){
      $sqlerro=true;
    }
    db_fim_transacao($sqlerro);
  }
}else if(isset($opcao)){
   $result = $clapolitem->sql_record($clapolitem->sql_query($t82_codapo,$t82_codbem));
   if($result!=false && $clapolitem->numrows>0){
     db_fieldsmemory($result,0);
   }
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC>
	<?
	include("forms/db_frmapolitem.php");
	?>
</body>
</html>
<?
if(isset($cods) && $alerta == true){
echo "
      <script>
        if(confirm('Este bem já esta incluído na(s) apólice(s): $cods \\n\\n Deseja incluir novamente?')){
	      obj=document.createElement('input');
	      obj.setAttribute('name','verificado');
	      obj.setAttribute('type','hidden');
      	      obj.setAttribute('value','true');
	      document.form1.appendChild(obj);
	      document.form1.submit();
	}
      </script>
";
}
if(isset($alterar) || isset($excluir) || isset($incluir) || isset($verificado)){
    db_msgbox($erro_msg);
    if($clapolice->erro_campo!=""){
        echo "<script> document.form1.".$clapolice->erro_campo.".style.backgroundColor='#99A9AE';</script>";
        echo "<script> document.form1.".$clapolice->erro_campo.".focus();</script>";
    }
}
?>