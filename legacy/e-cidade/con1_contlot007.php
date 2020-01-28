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
include("classes/db_contrib_classe.php");
include("classes/db_editalrua_classe.php");
include("classes/db_editalproj_classe.php");
include("classes/db_editalruaproj_classe.php");
include("classes/db_contlot_classe.php");
include("classes/db_contlotv_classe.php");
include("classes/db_testada_classe.php");
include("dbforms/db_funcoes.php");
$cleditalrua = new cl_editalrua;
$cleditalproj = new cl_editalproj;
$cleditalruaproj = new cl_editalruaproj;
$cltestada = new cl_testada;
$clcontlot = new cl_contlot;
$clcontlotv = new cl_contlotv;
$clcontrib = new cl_contrib;
global $desabilita;
$GLOBALS["desabilita"]="false";

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_POST_VARS);
$clcontrib->sql_record($clcontrib->sql_query($contri,"","d07_contri" ));
if($clcontrib->numrows>0){
  $GLOBALS["desabilita"]="true";
}
if(isset($confirma) && $confirma=="ok"){
db_inicio_transacao();
   $clcontlotv->d06_contri=$contri;
   $clcontlotv->excluir($contri);
   if($clcontlotv->erro_status=='0'){
     $sqlerro = true;
   }
   $clcontlot->d05_contri=$contri;
   $clcontlot->excluir($contri);
   if($clcontlot->erro_status=='0'){
     $sqlerro = true;
   }
  $sqlerro=false;
  $vt=$HTTP_POST_VARS;
  $vt2=$HTTP_POST_VARS;
  $ta=sizeof($vt);
  reset($vt);

  for($i=0; $i<$ta; $i++){
    $chave=key($vt);
    if(substr($chave,0,5)=="CHECK"){
      $dados=split("_",$chave); 
      $idbql=$dados[1];
      $testada=$dados[2];
      $length=strlen($idbql);
      reset($vt2);
      
      /*$clcontlot->d05_contri=$contri;
      $clcontlot->d05_idbql=$idbql;
      $clcontlot->d05_testad=$testada;
      $clcontlot->incluir($contri,$idbql);
      if($clcontlot->erro_status=='0'){
         $sqlerro = true;
         break;
      }*/
       /* for($x=0; $x<$ta; $x++){
          $chave2=key($vt2);
	  if(substr($chave2,0,(10+$length))=="j36testad_".$idbql){ 
   	     $dados2=split("_",$chave2); 
	     $tipo=$dados2[2];
             $testad=$vt2[$chave2]."\n";
	     $valcal=split("XX",$vt2["quant_$tipo"]);
	     $quant=$valcal[0];
	     $vlr=$valcal[1];
             $clcontlotv->d06_contri=$contri;
             $clcontlotv->d06_idbql=$idbql;
             $clcontlotv->d06_tipos=$tipo;
             $clcontlotv->d06_fracao=$testad;
             $clcontlotv->d06_valor=($testad*$vlr)/$quant; 
             $clcontlotv->incluir($contri,$idbql,$tipo);
             if($clcontlotv->erro_status=='0'){
               $erroinclu=$clcontlotv->erro_sql;
               $sqlerro = true;
               break;
	       }
	    }
	    $proximo2=next($vt2);
	  }*/
      }
      $proximo=next($vt);
    }  
   db_fim_transacao($sqlerro);
  }  


  class cl_fate extends cl_testada {
    function facetesta($sql,$numcontri){
	$this->rotulo->label();
	  $result2=$this->sql_record($sql,$numcontri);
	$numrows=$this->numrows;
	if($numrows>0){
	  static $pri=true;
	  $re=pg_query("select d03_tipos,d03_descr,d04_quant,d04_vlrcal,d04_vlrval from editalserv inner join editaltipo on d03_tipos=d04_tipos where d04_contri=$numcontri");  
	  $numlinhas= pg_numrows($re);
	  if($pri){
	    echo "
	     <tr>
	      <td align='center'><a  title='Inverte Marcação' href='#'  ".($GLOBALS["desabilita"]=="true"?"disabled":"onclick='return js_marca(this);return false;'")." >M</a></td>
	      <td style='font-weight:bold;'>Setor</td>
	      <td style='font-weight:bold;'>Quadra</td>
	      <td style='font-weight:bold;'>Lote</td>
	      <td style='font-weight:bold;'>Zona</td>";
	      for($f=0; $f<$numlinhas; $f++){
		db_fieldsmemory($re,$f);
		$d03_descr=$GLOBALS["d03_descr"];
		$d04_quant=$GLOBALS["d04_quant"];
		$d04_vlrcal=$GLOBALS["d04_vlrcal"];
		$d04_vlrval=$GLOBALS["d04_vlrval"];
		echo "<td  style='cursor:help; font-weight:bold;' onMouseOut='parent.js_label(false);' onMouseOver='parent.js_label(true,event,\"$d03_descr\",$d04_quant,$d04_vlrcal,$d04_vlrval);'>Serviço ".($f+1)."</td>";
	      }    
	      echo "</tr>";
	     $pri=false;
	     echo "<input type='hidden' name='numtest' value='$numlinhas' >";
	  } 
	  for($r=0; $r<$numrows; $r++){
	    db_fieldsmemory($result2,$r);
	    $j34_idbql=$GLOBALS["j34_idbql"];
	    $j34_setor=$GLOBALS["j34_setor"];
	    $j34_quadra=$GLOBALS["j34_quadra"];
	    $j34_lote=$GLOBALS["j34_lote"];
	    $j34_zona=$GLOBALS["j34_zona"];
	    $d05_testad=$GLOBALS["d05_testad"];
	    $Ij36_testad=$GLOBALS["Ij36_testad"];
	    echo "
		 <tr>
		   <input name='j34_idbql_$j34_idbql' type='hidden' value='$j34_idbql'>
		   <td align='left'><input id='CHECK_".$j34_idbql."' name='CHECK_".$j34_idbql."_".$d05_testad."' type='checkbox' checked ".($GLOBALS["desabilita"]=="true"?"disabled":"")."></td>
		   <td id='td'>".$j34_setor."</td> 
		   <td id='td'>".$j34_quadra."</td> 
		   <td id='td'>".$j34_lote."</td>
		   <td id='td'>".$j34_zona."</td>"; 
	    for($f=0; $f<$numlinhas; $f++){
	      db_fieldsmemory($re,$f);
	      $d03_descr=$GLOBALS["d03_descr"];
	      $d03_tipos=$GLOBALS["d03_tipos"];
	      $d04_quant=$GLOBALS["d04_quant"];
	      $d04_vlrcal=$GLOBALS["d04_vlrcal"];
	      $d04_vlrval=$GLOBALS["d04_vlrval"];
	      $d03_descr=$GLOBALS["d03_descr"];
	      
	      $resultis=pg_exec("select d06_fracao from contlotv where d06_contri=$numcontri and d06_idbql=$j34_idbql and d06_tipos=$d03_tipos;");   
	      db_fieldsmemory($resultis,0);
	      $d06_fracao=$GLOBALS["d06_fracao"];

	      
	      $x= "j36_testad_".$f;
	      GLOBAL $$x;
	      $$x=$d05_testad;
	      echo "<td style='cursor:help' onMouseOut='parent.js_label(false);' onMouseOver='parent.js_label(true,event,\"$d03_descr\",$d04_quant,$d04_vlrcal,$d04_vlrval);'>";
	      echo "<input ".($GLOBALS["desabilita"]=="true"?"readonly":"")."  type=\"text\" size=\"4\" id=\"j36testad_".$j34_idbql."\" value=\"$d06_fracao\" name=\"j36testad_".$j34_idbql."_".$d03_tipos."\" title=\"Testadas\"onKeyUp=\"js_ValidaCampos(this,4,'Constribuicao','f','f',event);\"  onKeyDown=\"return js_controla_tecla_enter(this,event);\">";
	      echo "<input ".($GLOBALS["desabilita"]=="true"?"readonly":"")." type='hidden' name='quant_$d03_tipos' value='".$d04_quant."XX".$d04_vlrcal,$d04_vlrval."'>";
	      echo "</td>";
	    }  
	    echo "</tr>";
	  }
	}	  
   
   }
    
    
  }

  ?>
  <html>
  <head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script>
<?
   echo "parent.document.form1.conface.style.visibility='hidden';\n";
  if($desabilita=="true"){
     echo" parent.document.form1.confirma.disabled=true;
           parent.document.form1.lotecontri.disabled=true;";
  }else{
     echo" parent.document.form1.confirma.disabled=false;
           parent.document.form1.lotecontri.disabled=false;";
    }	    
   ?>     
  function js_confirma(){
    document.form1.confirma.value="ok";
    document.form1.submit();  
    
  }
  function js_incluirlinha(idbql,setor,quadra,lote,zona,dad,testadas,testada){
    OBJ = document.form1;
    for(i=0;i<OBJ.length;i++){
       if(OBJ.elements[i].type=='checkbox'){
	 if(OBJ.elements[i].name.substr(6) == idbql){
	   alert('Lote já informado. Verifique.');
	   return;
	 }
       }
    }
    novalinha  = document.getElementById('id_tabela').insertRow(document.getElementById('id_tabela').rows.length);
    novacoluna = novalinha.insertCell(0);
    novacoluna.innerHTML = "<input type='checkbox' name='CHECK_"+idbql+"_"+testada+"' checked>";
    novacoluna = novalinha.insertCell(1);
    novacoluna.innerHTML = setor;
    novacoluna = novalinha.insertCell(2);
    novacoluna.innerHTML = quadra;
    novacoluna = novalinha.insertCell(3);
    novacoluna.innerHTML = lote;
    novacoluna = novalinha.insertCell(4);
    novacoluna.innerHTML = zona;
    var dados=dad.split("XX"); 
    var testad=testadas.split("XX"); 
    for(l=0; l< dados.length; l++){
      if(dados[l]!=""){
	 var dad=dados[l].split("-"); 
	 novacoluna = novalinha.insertCell(5+l);
	 novacoluna.style.cursor='help';
	 novacoluna.innerHTML = "<input title=\"\" name=\"j36testad_"+idbql+"_"+dad[3]+"\" size=\"4\" onMouseOver='parent.js_label(true,event,\""+dad[0]+"\","+dad[1]+","+dad[2]+")' onMouseOut='parent.js_label(false);'   type=\"text\" id=\"j36_testad_"+idbql+"\"  value=\""+testad[l]+"\"  size=\"10\" maxlength=\"\" onKeyUp=\"js_ValidaCampos(this,0,'','','',event);\"  onKeyDown=\"return js_controla_tecla_enter(this,event);\" autocomplete=''>";
      }  
    }
  }
  function js_conface(){
     obj = document.form1;
     var face="";
     for(i=0;i<obj.length;i++){
       if(obj.elements[i].type=='checkbox' && obj.elements[i].checked ){
	 face += "XX"+obj.elements[i].name.substr(6);
       }
     }
     document.form1.face.value=face;
     document.form1.submit();
     parent.document.form1.confirma.style.visibility='visible';
     parent.document.form1.lotecontri.style.visibility='visible';
     parent.document.form1.conface.style.visibility='hidden';
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
  <style>
  .nomes {
	  font-weight:bold;
	  background-color:999999 ;
	  }
  </style>
  </head>
  <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
  <table width="745" height="326" border="0" cellspacing="0" cellpadding="0">
    <tr> 
     <td height="100%" align="center" valign="top" bgcolor="#CCCCCC"> 
  <form name="form1" method="post" action="con1_contlot007.php?numcontri=<?=@$contri?>">
  <input name="face" type="hidden">
  <input name="contri" type="hidden" value="<?=(isset($contri)?$contri:$numcontri)?>">
  <input name="confirma" type="hidden">
  <table border="0">
<?
           if($GLOBALS["desabilita"]=="true"){
			echo "  <b>*<small> As matrículas dos lotes já foram processadas, portanto não será possível alteração.</small></b>";
	     }
?>	     
    <table id='id_tabela' cellpadding="0" cellspacing="0" border="1" >
  <?
  if(isset($contri)){
   $result07=$cleditalrua->sql_record($cleditalrua->sql_query_file($contri,"d02_codedi as codiedi"));    
   db_fieldsmemory($result07,0); 
   $result08=$cleditalruaproj->sql_record($cleditalruaproj->sql_query($contri,"","d11_codproj as d10_codig"));
   if($cleditalruaproj->numrows>0){
     db_fieldsmemory($result08,0); 
     echo "
            <center><b>OBS:</b><font color='darkblue'>Esta contribuição foi baseada na lista $d10_codig, portanto quando ela foi incluida os lotes foram selecionados automaticamente.</font></center><br>
          "; 	 
   }   	  
    
    $clfate = new cl_fate;
    $sql=$clcontlot->sql_query($contri); 
    $clfate->facetesta($sql,$contri);
  }
  echo "<script>;
         parent.document.form1.confirma.style.visibility='visible';
         parent.document.form1.lotecontri.style.visibility='visible';
        </script> ";
   ?>
    </table>  
  </form>
	  </td>
    </tr>
  </table>
  </body>
</html>
<?
if(isset($confirma) && $confirma=="ok"){
  if($clcontlot->erro_status=="0"||$clcontlotv->erro_status=="0"){
     $clcontlot->erro(true,false);
  }else{
     $clcontlot->erro(true,false);
     echo "<script>parent.location='con1_contlot004.php'</script>";
  }
}

?>