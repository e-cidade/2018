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
include("classes/db_testada_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$cltestada = new cl_testada;
$clrotulo = new rotulocampo;
$clrotulo->label("j34_idbql");
$clrotulo->label("j34_setor");
$clrotulo->label("j34_quadra");
$clrotulo->label("j34_lote");
$clrotulo->label("j36_testad");
$clrotulo->label("d41_eixo");
$clrotulo->label("j34_zona");
$clrotulo->label("d02_contri");
$result = $cltestada->sql_record($cltestada->sql_query($j34_idbql));
if($result==false || $cltestada->numrows == 0 ){
  $cltestada->erro(true,false);
  exit;
}
db_fieldsmemory($result,0);
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_atualiza(){
   testadas="";
  obj = document.form1;
  for(i=0;i<obj.length;i++){
    if(obj.elements[i].type=='text'){
      val = obj.elements[i].name.substr(0,10);
      if(val=="j36_testad"){
	testadas+=obj.elements[i].value+"XX";
      }
    }
   } 
<?   
echo "parent.js_lotecontri2(document.form1.j34_idbql.value,document.form1.j34_setor.value,document.form1.j34_quadra.value,document.form1.j34_lote.value,document.form1.j34_zona.value,document.form1.dad.value,testadas,".$j36_testad.");";
?> 
}
function js_label(liga,evt,descr,quant,vlr){
  evt= (evt)?evt:(window.event)?window.event:"";
  if(liga){
    document.getElementById('descr').innerHTML=descr;
    document.getElementById('quant').innerHTML=quant;
    document.getElementById('vlr').innerHTML=vlr;
    document.getElementById('divlabel').style.visibility='visible';
  }else{
     document.getElementById('divlabel').style.visibility='hidden';
  }
}

</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="100%" height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
 <div align="left" id="divlabel" style="position:absolute; z-index:1; top:35; left:100; visibility: hidden; border: 1px none #000000; background-color: #CCCCCC; background-color:#999999; font-weight:bold;">
     <span id="descr"></span><br>
      Quant: <span id="quant"></span><br>
       Valor R$:<span id="vlr"></span><br>
 </div>
			
 <form name="form1" method="post" action="" >
  <tr> 
    <td height="63" align="center" valign="top">
      <center>
        <table border="0" align="center" cellspacing="0">
          <tr> 
            <td width="34%" align="left" nowrap title="<?=$Td02_contri?>">
              <?=$Ld02_contri?>
            </td>
            <td width="66%" align="left" nowrap> 
              <?
              db_input("d02_contri",8,$Id02_contri,true,"text",3,"");
	      ?>
            </td>
          </tr>
          <tr> 
            <td width="34%" align="left" nowrap title="<?=$Tj34_idbql?>">
              <?=$Lj34_idbql?>
            </td>
            <td width="66%" align="left" nowrap> 
              <?
              db_input("j34_idbql",8,$Ij34_idbql,true,"text",3,"");
	      ?>
            </td>
          </tr>
          <tr> 
            <td width="34%" align="left" nowrap title="<?=$Tj34_setor?>">
            <?=$Lj34_setor?>
            </td>
            <td width="66%" align="left" nowrap> 
            <?
	    db_input("j34_setor",4,$Ij34_setor,true,'text',3)
	    ?>
            </td>
          </tr>
           <tr> 
            <td width="34%" align="left" nowrap title="<?=$Tj34_quadra?>">
            <?=$Lj34_quadra?>
            </td>
            <td width="66%" align="left" nowrap> 
            <?
	    db_input("j34_quadra",4,$Ij34_quadra,true,'text',3)
	    ?>
            </td>
          </tr>
            <tr> 
            <td width="34%" align="left" nowrap title="<?=$Tj34_lote?>">
            <?=$Lj34_lote?>
            </td>
            <td width="66%" align="left" nowrap> 
            <?
	    db_input("j34_lote",4,$Ij34_lote,true,'text',3)
	    ?>
            </td>
          </tr>
         
	  <tr> 
            <td width="34%" align="left" nowrap title="<?=$Tj34_zona?>">
            <?=$Lj34_zona?>
            </td>
            <td width="66%" align="left" nowrap> 
            <?
	    db_input("j34_zona",4,$Ij34_zona,true,'text',3)
	    ?>
            </td>
          </tr>
        <?         
	  $re=pg_query("select d03_tipos,d03_descr,d04_quant,d04_vlrcal,d04_vlrval,d04_mult from editalserv inner join editaltipo on d03_tipos=d04_tipos where d04_contri=$d02_contri");
          $numlinhas= pg_numrows($re);
	    $dad="";
 	    for($f=0; $f<$numlinhas; $f++){
	       db_fieldsmemory($re,$f);
	       $d03_tipos=$GLOBALS["d03_tipos"];
	       $d03_descr=$GLOBALS["d03_descr"];
	       $d04_quant=$GLOBALS["d04_quant"];
	       $d04_vlrcal=$GLOBALS["d04_vlrcal"];
	       $d04_vlrval=$GLOBALS["d04_vlrval"];
	       $d04_mult=$GLOBALS["d04_mult"];
               echo "<tr><td  style='cursor:help; font-weight:bold;' onMouseOut='js_label(false);' onMouseOver='js_label(true,event,\"$d03_descr\",$d04_quant,$d04_vlrcal,$d04_vlrval);'>Serviço ".($f+1)."</td>";
	       $x= "j36_testad_".$f;
               $$x=$j36_testad;
	       echo "<td style='cursor:help; font-weight:bold;' onMouseOut='js_label(false);' onMouseOver='js_label(true,event,\"$d03_descr\",$d04_quant,$d04_vlrcal);'>";
               db_input("j36_testad",4,$Ij36_testad,true,'text',1,'',"j36_testad_".$f);
	       echo "</td></tr>";
	       $dad.= $d03_descr."-".$d04_quant."-".$d04_vlrcal."-".$d03_tipos."-".$d04_vlrval."-".$d04_mult."XX";
	    }
	    echo "<input name='dad' type='hidden' value='$dad'>";
          ?>  
	  <tr> 
            <td align="center" colspan="2" >
	    <input name="confirma" value="Confirma" onclick="js_atualiza()" type="button">
            </td>
          </tr>
	  
        </table>
      </center>
      </td>
    </tr>
   </form>
</table>
</body>
</html>