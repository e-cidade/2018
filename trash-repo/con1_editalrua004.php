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
include("dbforms/db_funcoes.php");
include("classes/db_edital_classe.php");
include("classes/db_projmelhorias_classe.php");
include("classes/db_projmelhoriasmatric_classe.php");
$clprojmelhorias = new cl_projmelhorias;
$clprojmelhoriasmatric = new cl_projmelhoriasmatric;
$clprojmelhoriasmatric->rotulo->label();
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">

<script>


function js_incluirlinha(quant,vlrcal,texto,tipos,vlrval,mult,forma,vlrobra){

  novalinha    = document.getElementById('id_tabela').insertRow(document.getElementById('id_tabela').rows.length);
  novalinha.id = "linha_"+tipos;
  novalinha.setAttribute('align','center');

  novacoluna           = novalinha.insertCell(0);
  novacoluna.innerHTML = "<input type='checkbox' name='CHECK_"+tipos+"' checked>";
  novacoluna.setAttribute('class','linhagrid');
  
  novacoluna           = novalinha.insertCell(1);
  novacoluna.innerHTML = tipos;
  novacoluna.setAttribute('id',"tipos_"+tipos);
  novacoluna.setAttribute('class','linhagrid');

  novacoluna           = novalinha.insertCell(2);
  novacoluna.innerHTML = texto;
  novacoluna.setAttribute('id',"texto_"+tipos);
  novacoluna.setAttribute('class','linhagrid');

  novacoluna           = novalinha.insertCell(3);
  novacoluna.innerHTML = quant;
  novacoluna.setAttribute('id',"quant_"+tipos);
  novacoluna.setAttribute('class','linhagrid');

  novacoluna           = novalinha.insertCell(4);
  novacoluna.innerHTML = vlrcal;
  novacoluna.setAttribute('id',"vlrcal_"+tipos);
  novacoluna.setAttribute('class','linhagrid');

  novacoluna           = novalinha.insertCell(5);
  novacoluna.innerHTML = vlrval;
  novacoluna.setAttribute('id',"vlrval_"+tipos);
  novacoluna.setAttribute('class','linhagrid');
  
  novacoluna           = novalinha.insertCell(6);
  novacoluna.innerHTML = mult;
  novacoluna.setAttribute('id',"mult_"+tipos);
  novacoluna.setAttribute('class','linhagrid');

  novacoluna           = novalinha.insertCell(7);
  novacoluna.innerHTML = forma;
  novacoluna.setAttribute('id',"forma_"+tipos);
  novacoluna.setAttribute('class','linhagrid');

  novacoluna           = novalinha.insertCell(8);
  novacoluna.innerHTML = vlrobra;
  novacoluna.setAttribute('id',"vlrobra_"+tipos);
  novacoluna.setAttribute('class','linhagrid');

}

function js_marca(obj){ 

   var OBJ = document.form1;

   for(i=0;i<OBJ.length;i++){

     if(OBJ.elements[i].type == 'checkbox'){
       OBJ.elements[i].checked = !(OBJ.elements[i].checked == true); 
     }

   }

   return false;

}
</script>

<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >

  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr width="100%"> 
      <td width="100%" align="center" valign="top" bgcolor="#CCCCCC">
        <form name="form1" method="post" action="">

          <table id='id_tabela' border="0" width="100%" cellspacing='0' style='background-color:#FFFFFF' >           

            <tr>
              <td nowrap class='table_header' align='center'><a title='Inverte Marcação' href='#' <?=($db_opcao==3||$db_opcao==22 ?'':'onclick="return js_marca(this);return false;"')?> >M</a></td>
              <td nowrap class='table_header' align="center"><b> Código              </b> </td>
              <td nowrap class='table_header' align="center"><b> Serviço             </b> </td>
              <td nowrap class='table_header' align="center"><b> Quant.              </b> </td>
              <td nowrap class='table_header' align="center"><b> Valor p/calculo     </b> </td>
              <td nowrap class='table_header' align="center"><b> Valor p/valorização </b> </td>
              <td nowrap class='table_header' align="center"><b> Multiplicador       </b> </td>
              <td nowrap class='table_header' align="center"><b> Forma               </b> </td>
              <td nowrap class='table_header' align="center"><b> Valor Obra          </b> </td>
            </tr>
    
            <?
            if(isset($dados)){
    
              $ma = split("XX",$dados);

              for($k=0; $k<sizeof($ma); $k++){
        
                if($ma[$k]!=""){
        
                  $dad = split("-",$ma[$k]);
                  echo "<tr id='linha_".$dad[0]."'>";
                  echo "  <td align='center' > ";
                  echo "    <input type='checkbox' name='CHECK_".$dad[0]."' checked ".($db_opcao==3||$db_opcao==33||$db_opcao==22?'disabled':'')."> ";
                  echo "  </td> ";	
                  echo "  <td align='center' id='tipos_".$dad[0]."'>".$dad[0]."  </td>";	
                  echo "  <td align='center' id='texto_".$dad[0]."'>".$dad[3]."  </td>";	
                  echo "  <td align='center' id='quant_".$dad[0]."'>".$dad[1]."  </td>";	
                  echo "  <td align='center' id='vlrcal_".$dad[0]."'>".$dad[2]." </td>";	
                  echo "  <td align='center' id='vlrval_".$dad[0]."'>".$dad[4]." </td>";
                  echo "  <td align='center' id='mult_".$dad[0]."'>".$dad[5]."   </td>";	
                  echo "  <td align='center' id='forma_".$dad[0]."'>".$dad[6]."  </td>";	
                  echo "  <td align='center' id='vlrobra_".$dad[0]."'>".$dad[7]."  </td>";	
                  echo "</tr>";
        
                }  
        
              } 
        
            }
            ?>
            <tbody>
          </table>  
        </form>
      </td>
    </tr>
  </table>
</body>
</html>