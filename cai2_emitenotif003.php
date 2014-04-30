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
include("classes/db_listanotifica_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$instit  = db_getsession("DB_instit");
$cllistanotifica = new cl_listanotifica;
$cllistanotifica->rotulo->label("k63_notifica");
//$clarretipo->rotulo->label("k00_descr");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <tr> 
    <td height="63" align="center" valign="top">
        <table width="35%" border="0" align="center" cellspacing="0">
	     <form name="form2" method="post" action="" >
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tk63_notifica?>">
	    <?=$Lk63_notifica?>
              
            </td>
            <td width="96%" align="left" nowrap> 
              <?
	       db_input("k63_notifica",6,$Ik63_notifica,true,"text",4,"","chave_k63_notifica");
              ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="Código">Código
              
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("codigo",4,'',true,"text",4,"","chave_codigo");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="Nome">Nome
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("descr",40,'',true,"text",4,"","chave_descr");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      if ($lista == ''){
         db_redireciona('db_erros.php?fechar=true&db_erro=Não Existe Lista Selecionada');
      }
      $sqllista = "select * from lista where k60_codigo = $lista and k60_instit= $instit";
      $resultlista = pg_exec($sqllista);
      db_fieldsmemory($resultlista,0);
      if ($k60_tipo == 'M'){
	 $xcodigo  = 'matric';
	 $xcodigo1 = 'j01_matric';
	 $xxmatric = ' inner join notimatric on matric = k55_matric ';
      }elseif($k60_tipo == 'I'){
	 $xcodigo  = 'inscr';
	 $xcodigo1 = 'q02_inscr';
	 $xxmatric = ' inner join notiinscr on inscr = k56_inscr ';
      }elseif($k60_tipo == 'N'){
	 $xcodigo  = 'numcgm';
	 $xcodigo1 = 'j01_numcgm';
	 $xxmatric = ' inner join notinumcgm on numcgm = k57_numcgm ';
      }
	
      if(!isset($pesquisa_chave)){
        if(isset($chave_codigo) && (trim($chave_k63_notifica)!="") ){
	   $sql = "select k63_notifica, $xcodigo1,z01_numcgm,z01_nome,sum(valor_vencidos) as xvalor
                         from
                  (select distinct k63_notifica, $xcodigo as $xcodigo1,
	                  z01_numcgm,
			  z01_nome,
			  valor_vencidos 
	   	   from (select distinct k61_numpre,k61_codigo from listadeb) as a 
		        inner join devedores b on a.k61_numpre = b.numpre 
		                             and b.data = '$k60_datadeb'
		        inner join listanotifica on k63_numpre = b.numpre and k63_codigo = $lista
			$xxmatric
			inner join cgm on z01_numcgm = b.numcgm 
		   where k61_codigo = $lista and k63_notifica = $chave_k63_notifica) as y
		   group by k63_notifica, $xcodigo1,z01_numcgm,z01_nome"; 
        }elseif(isset($chave_codigo) && (trim($chave_codigo)!="") ){
	   $sql = "select k63_notifica, $xcodigo1,z01_numcgm,z01_nome,sum(valor_vencidos) as xvalor
                         from
                  (select distinct k63_notifica, $xcodigo as $xcodigo1,
	                  z01_numcgm,
			  z01_nome,
			  valor_vencidos 
	   	   from (select distinct k61_numpre,k61_codigo from listadeb) as a 
		        inner join devedores b on a.k61_numpre = b.numpre 
		                             and b.data = '$k60_datadeb'
		        inner join listanotifica on k63_numpre = b.numpre and k63_codigo = $lista
			$xxmatric
			inner join cgm on z01_numcgm = b.numcgm 
		   where k61_codigo = $lista and $xcodigo = $chave_codigo) as y
		   group by k63_notifica, $xcodigo1,z01_numcgm,z01_nome"; 
        }else if(isset($chave_descr) && (trim($chave_descr)!="") ){
	         $sql = "select k63_notifica, $xcodigo1,z01_numcgm,z01_nome,sum(valor_vencidos) as xvalor
                         from
                        (select distinct k63_notifica,$xcodigo as $xcodigo1,
		                z01_numcgm,
			        z01_nome,
			        valor_vencidos
			 from (select distinct k61_numpre,k61_codigo from listadeb) as a
			      inner join devedores b on a.k61_numpre = b.numpre
		                             and b.data = '$k60_datadeb'
		              inner join listanotifica on k63_numpre = b.numpre and k63_codigo = $lista
			      $xxmatric
			      inner join cgm on z01_numcgm = b.numcgm
			 where k61_codigo = $lista and z01_nome like upper('$chave_descr%')) as y 
			 group by k63_notifica, $xcodigo1,z01_numcgm,z01_nome";
        }else{
           $sql ="select k63_notifica, $xcodigo1,z01_numcgm,z01_nome,sum(valor_vencidos) as xvalor
                         from
                  (select distinct k63_notifica, $xcodigo as $xcodigo1,
                         z01_numcgm,
                         z01_nome,
                         valor_vencidos
                  from (select distinct k61_numpre,k61_codigo from listadeb) as a
                       inner join devedores b on a.k61_numpre = b.numpre
		                             and b.data = '$k60_datadeb'
		       inner join listanotifica on k63_numpre = b.numpre and k63_codigo = $lista
		       $xxmatric
                       inner join cgm on z01_numcgm = b.numcgm
                  where k61_codigo = $lista) as y
                  group by k63_notifica, $xcodigo1,z01_numcgm,z01_nome";
        }
        db_lovrot($sql,15,"()","",$funcao_js);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
	   $sql = "select k63_notifica, $xcodigo1,z01_numcgm,z01_nome,sum(valor_vencidos) as xvalor
                         from
                   (select distinct k63_notifica, $xcodigo as $xcodigo1,
	                  z01_numcgm,
			  z01_nome,
			  valor_vencidos 
	   	   from listadeb a 
		        inner join devedores b on a.k61_numpre = b.numpre 
		                             and b.data = '$k60_datadeb'
		        inner join listanotifica on k63_numpre = b.numpre and k63_codigo = $lista
			$xxmatric
			inner join cgm on z01_numcgm = b.numcgm 
		   where k61_codigo = $lista and matric = $pesquisa_chave) as y
		   group by k63_notifica, $xcodigo1,z01_numcgm,z01_nome"; 
//		   echo $sql;exit;
          $result = pg_exec($sql);
	  
          if(pg_numrows($result) !=0 ){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$z01_nome',false);</script>";
          }else{
	         echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
          }
        }else{
	       echo "<script>".$funcao_js."('',false);</script>";
        }
      }
      ?>
     </td>
   </tr>
</table>
</body>
</html>
<?
if(!isset($pesquisa_chave)){
  ?>
  <script>
  </script>
  <?
}
?>