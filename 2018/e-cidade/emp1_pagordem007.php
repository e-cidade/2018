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


include("classes/db_pagordemrec_classe.php");
include("classes/db_pagordemele_classe.php");
$clpagordemrec = new cl_pagordemrec;
$clpagordemele = new cl_pagordemele;

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<form name='form1'>
<table>
  <tr> 

<?
        $ver=true;
       //rotina que verifica... os valores das receitas
       $result = $clpagordemrec->sql_record($clpagordemrec->sql_query($e50_codord,null,"sum(e52_valor) as tot_receit")); 
       db_fieldsmemory($result,0);

          $result  = $clpagordemele->sql_record($clpagordemele->sql_query_file($e50_codord,"","(sum(e53_valor)-sum(e53_vlranu)-sum(e53_vlrpag)) as tot_dis")); 
	  db_fieldsmemory($result,0);
	
          if($tot_receit!="" && $tot_receit!=0){
	      if($tot_receit > ($tot_dis-$vlranu)){
        	$ver=false;
	    }
        }
	if($ver==false){
	    echo "
	        <script> 
		  parent.js_confere(false);
	        </script> 
	    ";
	}else{
	    echo "
	        <script> 
		  parent.js_confere(true);
	        </script> 
	    ";
	
	}    
?>
    </td>
  </tr>
</table>
</form>
</body>
</html>