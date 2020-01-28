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
include("classes/db_projmelhorias_classe.php");
include("classes/db_projmelhoriasmatric_classe.php");
include("classes/db_editalproj_classe.php");
include("classes/db_editalruaproj_classe.php");
include("classes/db_testada_classe.php");
include("classes/db_face_classe.php");
include("dbforms/db_funcoes.php");
$clprojmelhorias = new cl_projmelhorias;
$cleditalproj = new cl_editalproj;
$cleditalruaproj = new cl_editalruaproj;
$clprojmelhoriasmatric = new cl_projmelhoriasmatric;
$clprojmelhoriasmatric->rotulo->label();
$cltestada = new cl_testada;
$clface = new cl_face;

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
if(isset($codproj)){
  $sql = "select j01_matric,j40_refant,z01_nome,j34_setor,j34_quadra,j34_lote,j34_zona,projmelhoriasmatric.* 
          from projmelhoriasmatric
	       inner join iptubase on j01_matric = d41_matric
	       left  join iptuant on j40_matric = j01_matric
	       inner join lote on j34_idbql = j01_idbql
	       inner join cgm on j01_numcgm = z01_numcgm
          where d41_codigo = $codproj and j01_baixa is null  
	  order by j34_quadra,j34_lote";
   $result01=$cleditalruaproj->sql_record($cleditalruaproj->sql_query_file("",$codproj,"d11_contri"));
   $numrows01=$cleditalruaproj->numrows;
   if($numrows01>0){
     $xx="";
     $str="";
     for($iii=0; $iii<$numrows01; $iii++){
       db_fieldsmemory($result01,$iii);
       $str=$xx.$d11_contri;
       $xx=", ";
     }  
     $disab="ok";
     echo "
        <script>
          function js_sds(){ 
            parent.document.form1.confirma.disabled=true;
            if(parent.document.form1.matricontri){
                parent.document.form1.matricontri.disabled=true;
      	    }
          }	
	  js_sds();
        </script>
       ";
   }
   $resulttes = $cltestada->sql_record($sql);
}else if(isset($d40_codlog) && empty($faces)){
  $result02=$clface->sql_record($clface->sql_query_file("","j37_face,j37_setor,j37_quadra","j37_quadra","j37_codigo=$d40_codlog"));
}else if(isset($setores)){
  $face=str_replace("XX",",",$faces);
  
  $sql = "select j01_matric,j40_refant,z01_nome,j34_setor,j34_quadra,j34_lote,j34_zona,
                 round ( j36_testad * ( select round(rnFracao,2) from fc_iptu_fracionalote(j01_matric,2008,false,false) ) / 100 ,2 ) as j36_testad
            from testada
	               inner join lote on j34_idbql = j36_idbql
        	       inner join iptubase on j01_idbql = j36_idbql
        	       inner join cgm on j01_numcgm = z01_numcgm
                 left  join iptuant on j40_matric = j01_matric
           where j36_codigo = $d40_codlog and j01_baixa is null
         	   and j36_face in ($face)
           order by j34_quadra,j34_lote";

$resulttes = $cltestada->sql_record($sql);

}



?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>


function js_incluirlinha(matri,refant,nome,setor,quadra,lote,zona,test,eixo,obs) {

  OBJ = document.form1;
  for(i=0;i<OBJ.length;i++){
     if(OBJ.elements[i].type=='checkbox'){
       if(OBJ.elements[i].name.substr(6) == matri){
      	 alert('Matricula já Informada. Verifique.');
      	 return;
       }
     }
  }
   
  novalinha  = document.getElementById('id_tabela').insertRow(document.getElementById('id_tabela').rows.length);
  novacoluna = novalinha.insertCell(0);
  novacoluna.innerHTML = matri;
  novacoluna = novalinha.insertCell(1);
  novacoluna.innerHTML = refant;
  novacoluna = novalinha.insertCell(2);
  novacoluna.innerHTML = nome;
  novacoluna = novalinha.insertCell(3);
  novacoluna.innerHTML = setor;
  novacoluna = novalinha.insertCell(4);
  novacoluna.innerHTML = quadra;
  novacoluna = novalinha.insertCell(5);
  novacoluna.innerHTML = lote;
  novacoluna = novalinha.insertCell(6);
  novacoluna.innerHTML = zona;
  
  novacoluna = novalinha.insertCell(7);
  novacoluna.innerHTML = "<input type='checkbox' name='CHECK_"+matri+"' checked>";

  novacoluna = novalinha.insertCell(8);
  novacoluna.align='center';
  novacoluna.innerHTML = "<input name=\"d41_pgtopref_"+matri+"\" value='true' type='checkbox' onchange=\"js_trocatot(this);\" >";


  novacoluna = novalinha.insertCell(9);
  novacoluna.innerHTML = "<input title=\"\" name=\"d41_testada_"+matri+"\"  type=\"text\" id=\"d41_testada_"+matri+"\"  value=\""+test+"\"  size=\"10\" maxlength=\"\" onKeyUp=\"js_ValidaCampos(this,0,'','','',event);\"  onKeyDown=\"return js_controla_tecla_enter(this,event);\" onchange=\"js_trocatot(this);\"  autocomplete=''>";

test;
  novacoluna = novalinha.insertCell(10);
  novacoluna.innerHTML = "<input title=\"\" name=\"d41_eixo_"+matri+"\"  type=\"text\" id=\"d41_eixo_"+matri+"\"  value=\""+eixo+"\"  size=\"10\" onchange=\"js_trocatot(this);\" maxlength=\"\" onblur=\"js_ValidaMaiusculo(this,'',event);\" onKeyUp=\"js_ValidaCampos(this,0,'','','',event);\"  onKeyDown=\"return js_controla_tecla_enter(this,event);\" autocomplete=''>";
  
  total=new Number(test)+ new Number(eixo);

  novacoluna = novalinha.insertCell(11);
  novacoluna.innerHTML = "<input title=\"\" name=\"total_"+matri+"\"  type=\"text\" id=\"total_"+matri+"\"  value=\""+total+"\"  size=\"10\" maxlength=\"\" onblur=\"js_ValidaMaiusculo(this,'',event);\" onKeyUp=\"js_ValidaCampos(this,0,'','','',event);\"  onKeyDown=\"return js_controla_tecla_enter(this,event);\" autocomplete=''>";

  novacoluna = novalinha.insertCell(12);
  novacoluna.innerHTML = "<input title=\"\" name=\"d41_obs_"+matri+"\"  type=\"text\" id=\"d41_obs_"+matri+"\"  value=\""+obs+"\"  size=\"20\" maxlength=\"\" onblur=\"js_ValidaMaiusculo(this,'',event);\" onKeyUp=\"js_ValidaCampos(this,0,'','','',event);\"  onKeyDown=\"return js_controla_tecla_enter(this,event);\" autocomplete=''>";
  
}

function js_marca(obj,sTipo){ 

   var OBJ = document.form1;

   if (sTipo == 'matric') {     

     for(i=0;i<OBJ.length;i++){
       name=OBJ.elements[i].name;
       if(OBJ.elements[i].type == 'checkbox' && name.substr(0,5) =="CHECK") { 
         OBJ.elements[i].click();
       }
     }   
     
   }else{
     
     for(i=0;i<OBJ.length;i++){
       name=OBJ.elements[i].name;
       if(OBJ.elements[i].type == 'checkbox' && name.substr(0,13) == "d41_pgtopref_") {                                                                  
         OBJ.elements[i].click();
       }
     }     
     
   }
   
   return false;
   
}

function js_trocatot(obj){
  if(obj.name.substr(0,8)=="d41_eixo"){
    matric=obj.name.substr(9);
    testad= new Number(document.getElementById("d41_testada_"+matric).value);
    val=new Number(obj.value);
    if(obj.value!=""){
      total=val+testad;
      eval("document.form1.total_"+matric+".value="+total);
      if(!isNaN(total)){
         eval("document.form1.total_"+matric+".value="+total);
      }else{
            alert("Verifique o valor digitado.");	
	    eval("document.form1.total_"+matric+".value="+testad);
      }	 
    }else{
      eval("document.form1.total_"+matric+".value="+testad);
    }
  }else{
      matric=obj.name.substr(12);
      eixo = new Number(document.getElementById("d41_eixo_"+matric).value);
      val=new Number(obj.value);
      total=val+eixo;
      if(!isNaN(total)){
         eval("document.form1.total_"+matric+".value="+total);
      }else{
            alert("Verifique o valor digitado.");	
      }	 
  }  
}
function js_conface(){
    obj = document.form1;
    setor="";
    quadra="";
    face="";
    xx="";
    for(i=0;i<obj.length;i++){
      if(obj.elements[i].type=='checkbox' && obj.elements[i].checked ){
       	matriz = obj.elements[i].name.split("_");
        setor+=xx+matriz[1]; 
        quadra+=xx+matriz[2]; 
        face+=xx+matriz[3]; 
        xx="XX";
      }
    }
   document.form1.setores.value=setor;
   document.form1.quadras.value=quadra;
   document.form1.faces.value=face;
   document.form1.submit();
}
function js_mudacor(matric){
  if(document.getElementById('lin_'+matric).style.backgroundColor==""){ 
    document.getElementById('lin_'+matric).style.color='darkblue'; 
    document.getElementById('lin_'+matric).style.backgroundColor='#ccddcc'; 
  }else{
    document.getElementById('lin_'+matric).style.color=''; 
    document.getElementById('lin_'+matric).style.backgroundColor=''; 
  }  
}
</script>

<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="center" valign="top" bgcolor="#CCCCCC"> 

<form name="form1" method="post" action="con1_projmelhorias004.php">
  <table id='id_tabela' cellpadding="0" cellspacing="0" border="1" >
<?
if(isset($d40_codlog) && empty($faces) && empty($codproj)){
  echo"
     <input name='d40_codlog' type='hidden' value='$d40_codlog' >
     <input name='quadras' type='hidden' value='' >
     <input name='setores' type='hidden' value='' >
     <input name='faces' type='hidden' value='' >
     <tr>
       <td align='center'>
         <a title='Inverte Marcação' href='' onclick='return js_marca(this,\"matric\");') > M </a>
       </td>
       <td><b>Setor</b></td>
       <td><b>Quadra</b></td>
      </tr>";
    for($i=0; $i<$clface->numrows; $i++){
      db_fieldsmemory($result02,$i);
      echo"
         <tr>
	         <td align='left'>
             <input name='CHECK_".$j37_setor."_".$j37_quadra."_".$j37_face."' checked  type='checkbox' >
           </td>
           <td align='center'>$j37_setor</td>
           <td align='center'>$j37_quadra</td>
         </tr>
	 ";
    }   
        
}else if(isset($resulttes) || isset($codproj)){    
    if($cltestada->numrows>0){
      ?>
      <tr>
        <td nowrap> <b> Matricula </b> </td>
        <td nowrap> <b> Ref. Ant. </b> </td>
        <td nowrap> <b> Nome      </b> </td>
        <td nowrap> <b> Setor     </b> </td>
        <td nowrap> <b> Quadra    </b> </td>
        <td nowrap> <b> Lote      </b> </td>
        <td nowrap> <b> Zona      </b> </td>
        <td nowrap align='center'>
          <a title='Inverte Marcação'  <?=(isset($disab)?"onclick='return false;'":"onclick='return js_marca(this,'matric');return false;'")?> >M</a>
        </td>
      <? if(isset($codproj)){ ?>
          <td nowrap> <b> Pgto Prefeitura </b> 
            <a onclick="return js_marca(this,'pagto');" > M </a>     
          </td>          
      <?}?>
        <td nowrap><b>Testada</b></td>
        <td nowrap><b>Eixo</b></td>
        <td nowrap><b>Total</b></td>
        <td nowrap><b>Observação</b></td>
      </tr>
      <? 
    
      $matric_sem_testadas="";
      $vir="";
      $vezes=0;
      for($i=0;$i<$cltestada->numrows;$i++){
        db_fieldsmemory($resulttes,$i);
	if(isset($j36_testad) && ($j36_testad=="" || $j36_testad==0)){
          $matric_sem_testadas.=$vir.$j01_matric;
	  $vir=" ,";
	  $vezes++;
	  continue;
	}
  $color="";
  if(isset($codproj)){
    $x = array("false"=>"NAO","true"=>"SIM");
 	  $nomem="d41_pgtopref_".$j01_matric;
 	  global $$nomem;
	  if(isset($d41_pgtopref)){
	    if($d41_pgtopref=="t"){
	      $chec="checked";
    		$color="background-color:#ccddcc; color:darkblue;";
	    }else{
	      $chec="";
      }  
	  }
	}  
        echo "
        <tr style='$color' id='lin_$j01_matric'>
        <td>$j01_matric</td>
        <td>&nbsp;$j40_refant</td>
        <td><small>".substr($z01_nome,0,20)."<small></td>
        <td>$j34_setor</td>
        <td>$j34_quadra</td>
        <td>$j34_lote</td>
        <td>$j34_zona</td>
	<td align='left'><input name='CHECK_".$j01_matric."' type='checkbox' ".(!isset($d41_testada) || @$d41_testada!=null?"checked":"")." ".(isset($disab)?"disabled":"").">
	</td>";
        if(isset($codproj)){
   	  echo "<td align='center'>";
  	  echo "<input name='$nomem' value='true' type='checkbox'  $chec ".(isset($disab)?"disabled":"onclick='js_mudacor(\"$j01_matric\");'").">";
          echo "</td>";
        }	
        echo "<td>";
	if(isset($d41_testada) && !empty($d41_testada)){
	  $variat = "d41_testada_".$j01_matric;
	  $$variat = $d41_testada;
        }else{
	  $variat = "d41_testada_".$j01_matric;
	  $$variat = $j36_testad;
	}
        db_input("d41_testada",10,$Id41_testada,true,'text',(isset($disab)?"3":""),'onchange="js_trocatot(this);"',"d41_testada_".$j01_matric);
	echo "</td>";
        echo "<td>";
       	$varia = "d41_eixo_".$j01_matric;
	$$varia = @$d41_eixo;
	db_input("d41_eixo_",10,$Id41_eixo,true,'text',(isset($disab)?"3":""),'onchange="js_trocatot(this);"',"d41_eixo_".$j01_matric);
	echo "</td>";
        echo "<td>";
       	$varia = "total_".$j01_matric;
	$$varia = @$d41_eixo+$$variat;
	db_input("total_".$j01_matric,10,'',true,'text',3);
	echo "</td>";
        echo "<td>";
       	$varia = "d41_obs_".$j01_matric;
	$$varia = @$d41_obs;
     	db_input("d41_obs_",20,$Id41_obs,true,'text',(isset($disab)?"3":""),'','d41_obs_'.$j01_matric);
	echo "</td>";
        echo "</tr>";
      }
    }
}   
    ?>
  </table>  
</form>
	</td>
  </tr>
</table>
</body>
</html>
<?
if(isset($str) && $str!=""){
  if($iii==1){
    db_msgbox('Alteração não permitida, a lista esta sendo usada na contribuição '.$str);
  }else{
    db_msgbox('Alteração não permitida, a lista esta sendo usada nas contribuições '.$str);
  }
  echo "
  	<script>
	parent.document.form1.d40_profun.style.backgroundColor='#DEB887';
	parent.document.form1.d40_profun.style.color='#000000';
	parent.document.form1.d40_profun.disabled=true;
  	</script>
  ";
}
if(isset($resulttes) && $cltestada->numrows>0){
  echo "
    <script>
      if(parent.document.form1.conface){
        parent.document.form1.conface.style.visibility='hidden';
      }
      parent.document.form1.confirma.style.visibility='visible';
    </script>
  ";
}
if(isset($matric_sem_testadas) && $matric_sem_testadas!=""){
   if($vezes>1){
     db_msgbox("Matrículas $matric_sem_testadas não foram incluidas porque estavam sem testada. Verifique no cadastro imobiliário.");
   }else{
     db_msgbox("Matrícula $matric_sem_testadas não foi incluida porque estava sem testada. Verifique no cadastro imobiliário.");
   }      
}
?>