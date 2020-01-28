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

require("libs/db_stdlibwebseller.php");
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_db_depart_classe.php");
include("classes/db_escolaestrutura_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$cldb_depart = new cl_db_depart;
$cl_escolaestrutura = new cl_escolaestrutura;
$departamento = db_getsession("DB_coddepto");
$db_opcao = 1;
$db_botao = true;
$result = pg_query("SELECT ed18_i_censouf FROM escola where ed18_i_codigo = $departamento");
db_fieldsmemory($result,0);
function PegaValores($array,$tamanho){
 $retorno = "";
 for($x=1;$x<=$tamanho;$x++){
  $tem = false;
  for($y=0;$y<count($array);$y++){
   if($array[$y]==$x){
    $retorno .= "1";
    $tem = true;
    break;
   }
  }
  if($tem==false){
   $retorno .= "0";
  }
 }
 return $retorno;
}
if(isset($incluir)){
 db_inicio_transacao();
 $dependencias = PegaValores($ed255_c_dependencias,18);
 $localizacao = PegaValores($ed255_c_localizacao,8);
 $equipamentos = PegaValores(@$ed255_c_equipamentos,7);
 $abastagua = PegaValores($ed255_c_abastagua,5);
 $abastenergia = PegaValores($ed255_c_abastenergia,4);
 $esgotosanitario = PegaValores($ed255_c_esgotosanitario,3);
 $destinolixo = PegaValores($ed255_c_destinolixo,6);
 $materdidatico = PegaValores($ed255_c_materdidatico,3);
 $cl_escolaestrutura->ed255_c_dependencias = $dependencias;
 $cl_escolaestrutura->ed255_c_localizacao = $localizacao;
 $cl_escolaestrutura->ed255_c_equipamentos = $equipamentos;
 $cl_escolaestrutura->ed255_c_abastagua = $abastagua;
 $cl_escolaestrutura->ed255_c_abastenergia = $abastenergia;
 $cl_escolaestrutura->ed255_c_esgotosanitario = $esgotosanitario;
 $cl_escolaestrutura->ed255_c_destinolixo = $destinolixo;
 $cl_escolaestrutura->ed255_c_materdidatico = $materdidatico;
 $cl_escolaestrutura->ed255_i_escola=$departamento;
 $cl_escolaestrutura->incluir(null);
 db_fim_transacao();
 if($cl_escolaestrutura->erro_status=="0"){
  $cl_escolaestrutura->erro(true,false);
 }else{
  $cl_escolaestrutura->erro(true,true);
 }
}elseif(isset($alterar)){
 db_inicio_transacao();
 $db_opcao = 2;
 $dependencias = PegaValores($ed255_c_dependencias,18);
 $localizacao = PegaValores($ed255_c_localizacao,8);
 $equipamentos = PegaValores(@$ed255_c_equipamentos,7);
 $abastagua = PegaValores($ed255_c_abastagua,5);
 $abastenergia = PegaValores($ed255_c_abastenergia,4);
 $esgotosanitario = PegaValores($ed255_c_esgotosanitario,3);
 $destinolixo = PegaValores($ed255_c_destinolixo,6);
 $materdidatico = PegaValores($ed255_c_materdidatico,3);
 $cl_escolaestrutura->ed255_c_dependencias = $dependencias;
 $cl_escolaestrutura->ed255_c_localizacao = $localizacao;
 $cl_escolaestrutura->ed255_c_equipamentos = $equipamentos;
 $cl_escolaestrutura->ed255_c_abastagua = $abastagua;
 $cl_escolaestrutura->ed255_c_abastenergia = $abastenergia;
 $cl_escolaestrutura->ed255_c_esgotosanitario = $esgotosanitario;
 $cl_escolaestrutura->ed255_c_destinolixo = $destinolixo;
 $cl_escolaestrutura->ed255_c_materdidatico = $materdidatico;
 $cl_escolaestrutura->ed255_i_escola=$departamento;
 $cl_escolaestrutura->alterar($ed255_i_codigo);
 db_fim_transacao();
 if($cl_escolaestrutura->erro_status=="0"){
  $cl_escolaestrutura->erro(true,false);
 }else{
  $cl_escolaestrutura->erro(true,true);
 }
}else{
 $result = $cl_escolaestrutura->sql_record($cl_escolaestrutura->sql_query("","*",""," ed255_i_escola = $departamento"));
 if($cl_escolaestrutura->numrows!=0){
  db_fieldsmemory($result,0);
  $db_opcao = 2;
 }else{
  $db_opcao = 1;
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
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
   <br>
   <fieldset style="width:98%;padding:1px;"><legend><b>Infraestrutura da Escola</b></legend>
    <?include("forms/db_frmescolaestrutura.php");?>
   </fieldset>
  </td>
 </tr>
</table>
</body>
</html>