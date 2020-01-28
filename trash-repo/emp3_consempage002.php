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

include("classes/db_empage_classe.php");
$clempage = new cl_empage;

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$dbwhere =" 1=1 and e80_instit = " . db_getsession("DB_instit");

if(isset($e80_codage) && $e80_codage != ""){
  $dbwhere ="  e80_codage =$e80_codage ";
}else{
   if(isset($e50_codord) && $e50_codord != '' ){
        $dbwhere = " e82_codord = $e50_codord ";
   }
   if(isset($e60_codemp) && $e60_codemp!="" ){
     $arr = split("/",$e60_codemp);
     $dbwhere_ano = "";
     if(count($arr) == 2  && isset($arr[1]) && $arr[1] != '' ){
        $dbwhere_ano = " and e60_anousu = ".$arr[1];
     }
      $dbwhere .=  " and  e60_codemp =  '".$arr[0]."' $dbwhere_ano";
   }   
   
   if(isset($cheque) && $cheque != '' ){
        $dbwhere .= "  and e91_cheque = $cheque or e86_cheque = $cheque";
   }
   if(isset($k17_codigo) && $k17_codigo != '' ){
        $dbwhere .= "  and e89_codigo = $k17_codigo ";
   }
   if(isset($e81_codmov) && $e81_codmov != '' ){
        $dbwhere .= "  and e81_codmov = $e81_codmov ";
   }
   if(isset($e60_numemp) && $e60_numemp != '' ){
        $dbwhere .= "  and e60_numemp = $e60_numemp ";
   }
   if(isset($z01_numcgm) && $z01_numcgm != '' ){
        $dbwhere .= " and  (a.z01_numcgm = $z01_numcgm or cgm.z01_numcgm=$z01_numcgm)";
   }
   if(isset($e80_data) && $e80_data !=''){
      $dbwhere .= " and  e80_data = '".str_replace("X","-",$e80_data)."' ";
   }
   if(isset($valor) && $valor != '' ){
      $dbwhere .= "  and e81_valor = $valor or k17_valor = $valor";
   }
   
   if(!isset($k17_codigo) && !isset($e80_data)){
    $dbwhere .= "and (e53_vlranu < e53_valor)";
   }
   
}



$sql = $clempage->sql_query_cons(null,"e60_anousu,
                                       case when a.z01_numcgm is null then cgm.z01_numcgm else a.z01_numcgm end as z01_numcgm,
                                       e80_codage,
	     			                   e80_data,
					                   e81_codmov,
					                   e60_codemp,
					                   e60_numemp,
                                       e82_codord,
					                   case when a.z01_numcgm is null then cgm.z01_nome else a.z01_nome end as z01_nome,
					                   e86_codmov,
						               e89_codmov,
					                   e86_cheque,
					                   e81_valor,
                                       slip.k17_codigo,
					                   slip.k17_debito,
					                   slip.k17_credito","",$dbwhere);
							   
$sql = "select distinct e60_anousu as db_e60_anousu,
               e80_codage,
               e80_data,
               e81_codmov,
     	       (case when e89_codmov is null then e60_codemp||'/'||e60_anousu else 'Slip'||k17_codigo end)::varchar(15) as e60_codemp,
       	       case when e89_codmov is null then e60_numemp end as db_numemp,
	           case when e89_codmov is null then e82_codord else k17_debito end,
	           case when e89_codmov is null then z01_numcgm else k17_credito end,
               e86_cheque, 
               z01_nome,
 	       case when e86_codmov is null then 'NÃO' else 'SIM' end as db_m_Emitido,
	       case when e86_codmov is null then '0.00' else e81_valor end as e81_valor
        from ($sql) as x";
//die($sql);
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>
function js_consultar(){
    js_OpenJanelaIframe('top.corpo','db_iframe_consultar','emp3_consempage002.php','Pesquisa',true);
}
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
      <table>
       <tr>
	 <td colspan="2" align="center">
	   <br>
	   <input name="fechar" type="button" value="Fechar" onclick="parent.db_iframe_consultar.hide();"> 
	 </td>	
       </tr>
       <tr>
	 <td align='left'>  
	 <?
        db_lovrot($sql,15,"","",'');
	 ?>
	 </td>
       </tr>
      </table>  
    </td>
  </tr>
</table>
</body>
</html>