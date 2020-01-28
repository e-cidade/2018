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
include("classes/db_ativprinc_classe.php");
include("dbforms/db_funcoes.php");
$clrotulo = new rotulocampo;
$clativprinc = new cl_ativprinc;
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
if(isset($seq)){
     db_inicio_transacao();
       $clativprinc->sql_record($clativprinc->sql_query_file($q07_inscr));	
       if($clativprinc->numrows>0){
	 $clativprinc->q88_inscr=$q07_inscr;
         $clativprinc->excluir($q07_inscr);
         //$clativprinc->erro(true,false); 
         if($clativprinc->erro_status==0){
            $erromsg=$clativprinc->erro_msg;
            $sqlerro=true;
         }
       }
       $clativprinc->q88_inscr=$q07_inscr;
       $clativprinc->q88_seq=$seq;
       $clativprinc->incluir($q07_inscr);
       //$clativprinc->erro(true,false); 
       if($clativprinc->erro_status==0){
          $sqlerro=true;
       }
   db_fim_transacao($sqlerro);

}
?>
<html>
<head>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
  <form name="form1" method="post" action="iss1_tabativbaixaiframe02.php">
  <center>
  <table border="0" cellspacing="0" cellpadding="0" bgcolor="#cccccc">
  <tr>
    <td  valign="top">
      <input type="text" name="seq" value="<?=@$seq?>">  
      <input type="text" name="q07_inscr" value="<?=@$q07_inscr?>">  
    </td>
  </tr>  
  </table>
  </center>
  </form>
</body>  
</html>