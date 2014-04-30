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
include("dbforms/db_funcoes.php");
include("classes/db_caitransfseq_classe.php");
include("classes/db_saltes_classe.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$db_opcao = 1;
$db_botao = false;

$clcaitransfseq = new cl_caitransfseq;
$clsaltes = new cl_saltes;
$clrotulo = new rotulocampo;
// $clrotulo->label("k17_codigo");



/* contas de retenção para os repasses */
$retencao = $clsaltes->sql_record(
            $clsaltes->sql_query_anousu(
                       null,
                      "k13_conta,k13_descr","k13_descr",
	 	      "k13_limite is null or k13_limite >='".date('Y-m-d',db_getsession("DB_datausu"))."'"));
$matriz_retencao = array();
$matriz_retencao[0] = '** Selecione ';
for ($x=0;$x<$clsaltes->numrows;$x++){
  db_fieldsmemory($retencao,$x);
  $matriz_retencao[$k13_conta]=$k13_descr;
}  

////////////////////////////////////////////////////////////////////////////////////////////////////////////

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style type='text/css'>
.tit {
  background: #5786B2;
  font-size : 15px;
}  
.centro {
  background: #f7F6FF;
  font-size : 14px;
}  
</style>
<script>
function js_marca(obj){ 
   var OBJ = document.form1;
   for(i=0;i<OBJ.length;i++){
     if(OBJ.elements[i].type == 'checkbox' && OBJ.elements[i].disabled==false){
       OBJ.elements[i].checked = !(OBJ.elements[i].checked == true);            
     }
   }
   return false;
}
function js_padrao(val){

   var OBJ = document.form1;
   for(i=0;i<OBJ.length;i++){
     nome = OBJ.elements[i].name;
     tipo = OBJ.elements[i].type;
     if( tipo.substr(0,6) == 'select' && nome.substr(0,11)=='e83_codtipo'){
       ord = nome.substr(12);
       checa = eval("document.form1.CHECK_"+ord+".checked");
       if(checa==false){
	 continue;
       } 
      for(q=0; q<OBJ.elements[i].options.length; q++){
	if(OBJ.elements[i].options[q].value==val){
	   OBJ.elements[i].options[q].selected=true;
	   break;
	 }
      }
    }
   }
}
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0">
<tr> 
<td height="100%" align="left" valign="top" bgcolor="#CCCCCC"> 
  <form name="form1" method="POST" action="">        
    <table border=1 width=100%>
     <tr>
     <th colspan=8 align=left>Repasses à Autenticar</th>
     </tr>
     <tr>
       <td class=tit align='right' width='5px'> &nbsp; </td>
       <td class=tit align='left'>Cod/Seq </td>
       <td class=tit align='left'>Descrição</td>
       <td class=tit align='left'>Destino   </td>
       <td class=tit align='left'>Data  </td>
       <td class=tit align='left'>Usuário  </td>
       <td class=tit align='left'>Valor  </td>
       <td class=tit align='left'>Conta de Retenção </td>
     </tr>    
     <?
      // exibir todas os repasses
      $result =  $clcaitransfseq->sql_record(
                 $clcaitransfseq->sql_query_efetuadas(
		      null,"k94_seqtransf,k91_descr,c2.nomeinst as destino,k94_data,db_usuarios.nome,k94_valor",
		      null,"caitransf.k91_instit = ".db_getsession("DB_instit")."
		            and caitransf.k91_tipo ='R'"));          
      //    db_criatabela($result);     
      for($i=0; $i<$clcaitransfseq->numrows ; $i++){
           db_fieldsmemory($result,$i,true);  
      ?>
      <tr>
       <td align='right' width=5px><input type=checkbox name="repasses" value='<?=$k94_seqtransf?>'>
       <td class=centro align='right'><small > <?=$k94_seqtransf?></small></td>
       <td class=centro align='left'><small > <?=$k91_descr ?></small></td>
       <td class=centro align='left'><small > <?=$destino ?></small></td>
       <td class=centro align='right'><small > <?=$k94_data ?></small></td>
       <td class=centro align='left'><small > <?=$nome ?></small></td>
       <td class=centro align='right'><small > <?=$k94_valor ?></small></td>

       <td class=centro align='right' nowrap><small> 
       <?
        // seleciona as contas do saltes
	$k13_conta=0;
        db_select('k13_conta',$matriz_retencao,'false',1);       
       
       
       ?></small></td>

       
      </tr>
      <?  } ?> 





      


     <tr>
     <th colspan=7 align=left>Interferências à Autenticar</th>
     </tr>
     <tr>
       <td class=tit align='right' width='5px'> &nbsp; </td>
       <td class=tit align='left'>Cod/Seq </td>
       <td class=tit align='left'>Descrição</td>
       <td class=tit align='left'>Destino   </td>
       <td class=tit align='left'>Data  </td>
       <td class=tit align='left'>Usuário  </td>
       <td class=tit align='left'>Valor  </td>
     </tr>    
     <?
      // exibir todas os repasses
      $result =  $clcaitransfseq->sql_record(
                 $clcaitransfseq->sql_query_efetuadas(
		      null,"k94_seqtransf,k91_descr,c2.nomeinst as destino,k94_data,db_usuarios.nome,k94_valor",
		      null,"caitransf.k91_instit = ".db_getsession("DB_instit")."
		            and caitransf.k91_tipo ='I'"));          
      //    db_criatabela($result);     
      for($i=0; $i<$clcaitransfseq->numrows ; $i++){
           db_fieldsmemory($result,$i,true);  
      ?>
      <tr>
       <td align='right' width=5px><input type=checkbox name="repasses" value='<?=$k94_seqtransf?>'>
       <td class=centro align='right'><small > <?=$k94_seqtransf?></small></td>
       <td class=centro align='left'><small > <?=$k91_descr ?></small></td>
       <td class=centro align='left'><small > <?=$destino ?></small></td>
       <td class=centro align='right'><small > <?=$k94_data ?></small></td>
       <td class=centro align='left'><small > <?=$nome ?></small></td>
       <td class=centro align='right'><small > <?=$k94_valor ?></small></td>
      </tr>
      <?  } ?>  
 




      
   </table>
  </form>

   
  
  </td>
 </tr>
</table>
</body>
</html>