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

$clbens = new cl_bens;

$clrotulo = new rotulocampo;
$clrotulo->label("t52_bem");
$clbens->rotulo->label();

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

if(isset($pesquisa) && ($pesquisa == "true")){
  $clrotulo->label("t52_codcla");
  $clrotulo->label("t52_codmat");
  $clrotulo->label("t52_numcgm");
  $clrotulo->label("t52_valaqu");
  $clrotulo->label("t52_dtaqu");
  $clrotulo->label("t52_ident");
  $clrotulo->label("t52_descr");
  $clrotulo->label("t52_obs");
  $clrotulo->label("t52_depart");

  $clrotulo->label("t64_descr");
  $clrotulo->label("pc01_descmater");
  $clrotulo->label("t52_codcla");
  $clrotulo->label("t52_codcla");
  $clrotulo->label("t52_codcla");
  $clrotulo->label("t52_codcla");
  
  $result = $clbens->sql_record($clbens->sql_query($t52_bem));
  $numrows = $clbens->numrows;
  
  if($numrows>0){
    echo "<BR><BR><BR>";
    db_fieldsmemory($result,0,'',true);
  }
  $pesq = "true";
}

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table border="1"  align="center" cellspacing="0" bgcolor="#CCCCCC">

  <tr> 
    <td  align="left" nowrap title="<?=$Tt52_codcla?>"> <? db_ancora(@$Lt52_codcla,"",3);?>  </td>
    <td align="left" nowrap>
      <?
         db_input("t52_codcla",8,$It52_codcla,true,"text",4,""); 
//         db_input("t52_descr",40,"$It52_descr",true,"text",3);  
        ?></td>
            </tr>
            <tr> 
              <td  align="left" nowrap title="<?=$Tt52_codmat?>"> <? db_ancora(@$Lt52_codmat,"",3);?>  </td>
              <td align="left" nowrap>
       <?
         db_input("t52_codmat",8,$It52_codmat,true,"text",4,""); 
//         db_input("t52_descr",40,"$It52_descr",true,"text",3);  
        ?></td>
  </tr>
  <tr> 
    <td  align="left" nowrap title="<?=$Tt52_numcgm?>"> <? db_ancora(@$Lt52_numcgm,"",3);?>  </td>
    <td align="left" nowrap>
      <?
         db_input("t52_numcgm",8,$It52_numcgm,true,"text",4,""); 
//         db_input("t52_descr",40,"$It52_descr",true,"text",3);  
        ?></td>
  </tr>
  <tr> 
    <td  align="left" nowrap title="<?=$Tt52_valaqu?>"> <? db_ancora(@$Lt52_valaqu,"",3);?>  </td>
    <td align="left" nowrap>
      <?
         db_input("t52_valaqu",8,$It52_valaqu,true,"text",4,""); 
//         db_input("t52_descr",40,"$It52_descr",true,"text",3);  
        ?></td>
  </tr>
   <tr> 
    <td  align="left" nowrap title="<?=$Tt52_dtaqu?>"> <? db_ancora(@$Lt52_dtaqu,"",3);?>  </td>
    <td align="left" nowrap>
      <?
         db_input("t52_dtaqu",8,$It52_dtaqu,true,"text",4,""); 
//         db_input("t52_descr",40,"$It52_descr",true,"text",3);  
        ?></td>
  </tr> 
  <tr>   
    <td  align="left" nowrap title="<?=$Tt52_ident?>"> <? db_ancora(@$Lt52_ident,"",3);?>  </td>
    <td align="left" nowrap>
      <?
         db_input("t52_ident",8,$It52_ident,true,"text",4,""); 
//         db_input("t52_descr",40,"$It52_descr",true,"text",3);  
        ?></td>
  </tr>
  <tr> 
    <td  align="left" nowrap title="<?=$Tt52_obs?>"> <? db_ancora(@$Lt52_obs,"",3);?>  </td>
    <td align="left" nowrap>
      <?
         db_input("t52_obs",8,$It52_obs,true,"text",4,""); 
//         db_input("t52_obs",40,"$It52_descr",true,"text",3);  
        ?></td>
  </tr>
  <tr> 
    <td  align="left" nowrap title="<?=$Tt52_depart?>"> <? db_ancora(@$Lt52_depart,"",3);?>  </td>
    <td align="left" nowrap>
      <?
         db_input("t52_depart",8,$It52_depart,true,"text",4,""); 
//         db_input("t52_depart",40,"$It52_depart",true,"text",3);  
        ?></td>
  </tr>

</table>
</body>
</html>