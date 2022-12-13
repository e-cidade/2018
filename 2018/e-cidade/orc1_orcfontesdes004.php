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
include("classes/db_orcfontes_classe.php");
include("classes/db_orcfontesdes_classe.php");
include("dbforms/db_funcoes.php");
require("libs/db_liborcamento.php");
$clrotulo = new rotulocampo;
$clrotulo->label("o57_fonte");
$clrotulo->label("o57_descr");
$clrotulo->label("o60_perc");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$clorcfontes = new cl_orcfontes;
$clorcfontesdes = new cl_orcfontesdes;

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>
<?
  if(isset($chavepesquisa)){
    $fonte_full=str_replace(".","",$o50_estrutreceita);
    $mami=db_le_mae($fonte_full,false);
    echo "parent.document.form1.o50_estrutreceita.value='".db_formatar($mami,'receita')."'";
  }
?>  
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="parent.js_soma();" >
<form name='form1'>
<center>
 <table width="80%" border ='0' > 
    <tr>
       <td>&nbsp;</td>
       <td nowrap title="<?=@$To57_fonte?>" align='center'>
         <b><?=@$RLo57_fonte?></b>
       </td>
      <td nowrap title="<?=@$To57_descr?>" align='center'>
         <b><?=@$RLo57_descr?></b>
       </td>

       <td nowrap title="<?=@$To60_perc?>" align='center'>
         <b><?=@$RLo60_perc?>(%)</b>
       </td>
    </tr> 
<?
  $matriz= split("\.",$o50_estrutreceita);
  $inicia=false;//variavel que indica que o nivel não tem mais filhos
  $tam=(count($matriz)-1);
  $codigos='';
  for($i=$tam; $i>=0; $i--){
    $codigo='';//monta os codigos para a pesquisa
    if($matriz[$i]!="0" || $inicia==true){
      $inicia=true;
      for($x=$i; $x>=0; $x--){
	  $codigo=$matriz[$x].$codigo;
      } 	 
    }
    if($inicia==true){
      break;
    }  
  }
      
  $taman=strlen($codigo);
  $fonte_full=str_replace(".","",$o50_estrutreceita);
  $nivel=db_le_mae($fonte_full,true);
  if(isset($chavepesquisa)){
    switch($nivel){
      case 1:
            $dbwhere = "o57_fonte ='$codigo'  ";
            break;
      case 2:
            $dbwhere = " substr(o57_fonte,1,1)='".substr($fonte_full,0,1)."' and substr(o57_fonte,2,13)<>'000000000000' ";
            break;
      case 3:
            $dbwhere = " substr(o57_fonte,1,2)='".substr($fonte_full,0,2)."' and substr(o57_fonte,3,13)<>'00000000000' ";
            break;
      case 4:
            $dbwhere = " substr(o57_fonte,1,3)='".substr($fonte_full,0,3)."' and substr(o57_fonte,4,13)<>'0000000000' ";
            break;
      case 5:
            $dbwhere = " substr(o57_fonte,1,4)='".substr($fonte_full,0,4)."' and substr(o57_fonte,5,13)<>'000000000' ";
            break;
      case 6:
            $dbwhere = " substr(o57_fonte,1,5)='".substr($fonte_full,0,5)."' and substr(o57_fonte,6,13)<>'00000000' ";
            break;
      case 7:
            $dbwhere = " substr(o57_fonte,1,7)='".substr($fonte_full,0,7)."' and substr(o57_fonte,8,13)<>'000000' ";
            break;
      case 8:
            $dbwhere = " substr(o57_fonte,1,9)='".substr($fonte_full,0,9)."' and substr(o57_fonte,10,13)<>'0000' ";
            break;
      case 9:
            $dbwhere = " substr(o57_fonte,1,11)='".substr($fonte_full,0,11)."' and substr(o57_fonte,12,13)<>'00' ";
            break;
    }
  }else{
     $dbwhere = "substr(o57_fonte,1,$taman)='$codigo' and o57_fonte<>'$fonte_full' ";
  }
  
  $dbwhere .= " and o57_anousu = ".db_getsession("DB_anousu");
  
  $result=$clorcfontes->sql_record($clorcfontes->sql_query(null,null,"o57_fonte,o57_codfon,o57_descr",'o57_fonte',"$dbwhere"));
  $numrows = $clorcfontes->numrows;
  for($i=0; $i<$numrows; $i++){
      db_fieldsmemory($result,$i);
      $nomefon="o60_codfon_$o57_codfon";
      $$nomefon=db_formatar($o57_fonte,"receita");
      $nomeperc="o60_perc_$o57_codfon";
      $descrfon="o57_descr_$o57_codfon";
      $$descrfon=$o57_descr;

      if(isset($chavepesquisa)){
         $result15=$clorcfontesdes->sql_record($clorcfontesdes->sql_query_file(null,null,"o60_perc",'',"o60_anousu=".db_getsession('DB_anousu')." and o60_codfon=$o57_codfon "));
	 if($clorcfontesdes->numrows>0){
           db_fieldsmemory($result15,0);      	
	 }else{
	   continue;
	 }
         $$nomeperc=$o60_perc;
      }else{
            $$nomeperc='0';
      }  	    
      echo "
	   <tr>
	    <td>&nbsp;</td>
	    <td align='center'>";
	      db_input('o60_fonte',23,$Io60_perc,true,'text',3,"",$nomefon);
      echo "  	      
	     </td>
	    <td align='center'>";
	      db_input('o57_descr',50,$Io57_descr,true,'text',3,"",$descrfon);
      echo "  	      
	     </td>
	     <td align='center'>";
	     db_input('o60_perc',4,$Io60_perc,true,'text',$db_opcao,"onchange='parent.js_totaliza(this);'",$nomeperc);
      echo " 	     
	     </td>
	  </tr> 
            ";
  }
   
?>
    </form>
   </table>
</body>
</html>