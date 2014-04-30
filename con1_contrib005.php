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
include("classes/db_editalrua_classe.php");
include("classes/db_contrib_classe.php");
include("classes/db_contlot_classe.php");
include("dbforms/db_funcoes.php");
$cleditalrua = new cl_editalrua;
$clcontrib = new cl_contrib;
$clcontlot = new cl_contlot;
$clcontrib->rotulo->label();
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_POST_VARS);
if(isset($confirma) && $confirma=="ok"){
  db_inicio_transacao();
  $sqlerro=false;
  $vt=$HTTP_POST_VARS;
  $ta=sizeof($vt);
  reset($vt);
  for($i=0; $i<$ta; $i++){
    $chave=key($vt);
    if(substr($chave,0,5)=="CHECK"){
      $dados=split("_",$chave); 
      $matric=$dados[1];
      $valor=$vt["d07_valor_".$matric];
      $vlrdes=$vt["d07_vlrdes_".$matric];
      $idbql=$vt["j34_idbql_".$matric];
      $nops=$dados[2];
      if($nops=="nops"){
        $testada=$vt["d05_testad_".$matric];
        $clcontlot->d05_contri = $contri;
        $clcontlot->d05_idbql  = $idbql;
        $clcontlot->d05_testad = $testada;
        $clcontlot->incluir($contri,$idbql);
        if($clcontlot->erro_status=='0'){
           $sqlerro = true;
        }
      }
      $hoje = date("Ymd",db_getsession("DB_datausu"));
      $ano  = date("Y",db_getsession("DB_datausu"));
      $sql  = "select sum(case when j22_valor is null then 0 else j22_valor end+j23_vlrter) as j23_vlrter
           	   	 from ( select ( select sum(j22_valor)
                             		   from iptucale 
                            		  where j22_anousu = $ano 
                                    and j22_matric = $matric ) as j22_valor, 
                 		           ( select j23_vlrter 
                                   from iptucalc
                                  where j23_anousu = $ano  
                                    and j23_matric = $matric ) as j23_vlrter ) as j23_vlrter ";
      $result04 = pg_query($sql);	 
      db_fieldsmemory($result04,0);

      $clcontrib->d07_venal  = $j23_vlrter;
      $clcontrib->d07_contri = $contri;
      $clcontrib->d07_matric = $matric;
      $clcontrib->d07_idbql  = $idbql;
      $clcontrib->d07_vlrdes = "'".number_format($vlrdes,2,'.','')."'";
      $clcontrib->d07_valor  = number_format($valor,2,'.','');
      $clcontrib->d07_data   = $hoje;
      $clcontrib->incluir($contri,$matric);
      if($clcontrib->erro_status=='0') {
         $sqlerro = true;
      	if(empty($j23_vlrter) || $j23_vlrter==''){
      	  echo "Matrícula $matric sem valor venal... Contate técnico responsável!";
      	  die();
      	}
         break;
      }
    }
    $proximo=next($vt);
  }  
 db_fim_transacao($sqlerro);
}  
class cl_fate extends cl_contrib {
  function facetesta($sql,$numcontri){
      $this->rotulo->label();
      $result2=$this->sql_record($sql,$numcontri);
      $numrows=$this->numrows;
      if($numrows>0){
	static $pri=true;
	if($pri){
          echo "
           <tr>
            <td align='center'><a  title='Inverte Marcação' href='' onclick='return js_marca(this);return false;'>M</a></td>
            <td style='font-weight:bold;'>Matricula</td>
            <td align='center' style='font-weight:bold;'>Ref. Ant.</td>
            <td align='center' style='font-weight:bold;'>Nome</td>
            <td style='font-weight:bold;'>Setor</td>
            <td style='font-weight:bold;'>Quadra</td>
            <td style='font-weight:bold;'>Lote</td>
            <td style='font-weight:bold;'>Zona</td>
            <td style='font-weight:bold;'>Testada</td>
            <td style='font-weight:bold;'>Fração(%)</td>
            <td align='center'  style='font-weight:bold;'>Total R$</td>
	    <td align='center'  style='font-weight:bold;'>Desconto R$</td>
            <td align='center'  style='font-weight:bold;'>À Pagar R$</td>
           </tr>";
	   $pri=false;
	} 
        for($r=0; $r<$numrows; $r++){
          db_fieldsmemory($result2,$r);
	  $j34_idbql=$GLOBALS["j34_idbql"];
	  $j34_setor=$GLOBALS["j34_setor"];
	  $j34_quadra=$GLOBALS["j34_quadra"];
	  $d05_testad=$GLOBALS["d05_testad"];
	  $j34_lote=$GLOBALS["j34_lote"];
	  $j34_zona=$GLOBALS["j34_zona"];
//	  $d05_testad=$GLOBALS["d05_testad"];
	  $d05_testad=$GLOBALS["d41_testada"];
	  $j01_matric=$GLOBALS["j01_matric"];
	  $j40_refant=$GLOBALS["j40_refant"];
	  $d01_perc=$GLOBALS["d01_perc"];
	  $d02_profun=$GLOBALS["d02_profun"];
	  $z01_nome=$GLOBALS["z01_nome"];
	  $Id07_valor=$GLOBALS["Id07_valor"];
	  $Id07_vlrdes=$GLOBALS["Id07_vlrdes"];

     $resultas=pg_query("select fc_fracao($j34_idbql,".db_getsession('DB_datausu').",$j01_matric)");
	   db_fieldsmemory($resultas,0);
	   $fc_fracao=$GLOBALS["fc_fracao"];
	   //$fc_fracao=100;
     $resultad=pg_query("select d06_valor from contlotv where d06_contri=$numcontri and d06_idbql=$j34_idbql");
	   $nu=pg_numrows($resultad);
	   
	   if($nu>0){
	     $total="";
	     for($q=0; $q<$nu; $q++){
         db_fieldsmemory($resultad,$q);
	       $d06_valor=$GLOBALS["d06_valor"]/$GLOBALS["d05_testad"]*$d05_testad;
	       //$d06_fracao=$GLOBALS["d06_fracao"];
	       $total += $d06_valor;  
	     } 
	     if($fc_fracao==0){
	       $d07_valor=0;
	       $total=0;
	     }else{
	       ///d07_valor é igual a soma do contlotv conforme sua fração do iptubase
//				 echo "matric: $j01_matric - bql: $j34_idbql - fracao: $fc_fracao - total: $total<br>";
	       $valparc=$total; //*$fc_fracao/100;
	       $d07_valor=$valparc;
	     }
	   }
	   
           $y="d07_vlrdes_$j01_matric";
           global $$y;
           if($d01_perc!="" && $d01_perc!=0){
	   //mudança evandro  
          //    $d07_vlrdes=($d07_valor*$d01_perc)/100;
	       $d07_vlrdes = "0.00";
	  
              $d07_valor=($d07_valor-$d07_vlrdes);
	      $$y=number_format($d07_vlrdes,2,'.','');
	   }else{
	    $$y="0.00";    
	   }
	   $x="d07_valor_$j01_matric";
	   global $$x;
	   $$x=number_format($d07_valor,2,'.','');
	   
	  echo "
   	       <tr>
                 <input name='j34_idbql_$j01_matric' id='j34_idbql_$j01_matric' type='hidden' value='$j34_idbql'>
		   <td align='left'><input  id='CHECK_".$j01_matric."_ok' name='CHECK_".$j01_matric."_ok' type='checkbox'></td>
		   <td>$j01_matric</td>
		   <td>&nbsp;$j40_refant</td>
		   <td>".substr($z01_nome,0,20)."</td>
		   <td id='td'>".$j34_setor."</td> 
		   <td id='td'>".$j34_quadra."</td> 
		   <td id='td'>".$j34_lote."</td>
		   <td id='td'>".$j34_zona."</td>
		   <td id='td'>".$d05_testad."</td>
		   <td id='td'>".number_format($fc_fracao,2,".","")."</td> 
		   <td id='val_$j01_matric'>".number_format($valparc,2,".","")."</td>";
		   echo "<td align='left'>";
		   db_input('d07_vlrdes',7,$Id07_vlrdes,true,'text',1,"onchange='js_trocaval(this);'","d07_vlrdes_$j01_matric");
		 echo "</td>";
		 echo "<td align='left'>";
		 db_input('d07_valor',7,$Id07_valor,true,'text',3,"","d07_valor_$j01_matric");
		 echo "</td>";
          echo "</tr>";
 	}
	exit;
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
function js_trocaval(obj){
  matric=obj.name.substr(11);
  tot=document.getElementById("val_"+matric).innerHTML;  
  valor=new Number(tot)-new Number(obj.value);
  valor=new Number(valor);
  valor02=valor.toFixed(2);   
  eval("document.form1.d07_valor_"+matric+".value="+valor02);
}
function js(){
  parent.document.form1.confirma.disabled=false;
}
js();
function js_confirma(){
  document.form1.confirma.value="ok";
  document.form1.submit();
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
function js_incluirlinha(matri,refant,nome,setor,quadra,lote,zona,total,desconto,idbql,testada){
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
  novacoluna.innerHTML = "<input type='checkbox' name='CHECK_"+matri+"_nops' checked> <input name='j34_idbql_"+matri+"' id='j34_idbql_"+matri+"' type='hidden' value='"+idbql+"'>";
  novacoluna = novalinha.insertCell(1);
  novacoluna.innerHTML = matri;
  novacoluna = novalinha.insertCell(2);
  novacoluna.innerHTML = refant;
  novacoluna = novalinha.insertCell(3);
  novacoluna.innerHTML = nome;
  novacoluna = novalinha.insertCell(4);
  novacoluna.innerHTML = setor;
  novacoluna = novalinha.insertCell(5);
  novacoluna.innerHTML = quadra;
  novacoluna = novalinha.insertCell(6);
  novacoluna.innerHTML = lote;
  novacoluna = novalinha.insertCell(7);
  novacoluna.innerHTML = zona;
  novacoluna = novalinha.insertCell(8);
  novacoluna.innerHTML = testada;
  novacoluna = novalinha.insertCell(9);
  novacoluna.innerHTML ="#";
  novacoluna = novalinha.insertCell(10);
  novacoluna.id="val_"+matri;
  novacoluna.innerHTML = total;
  apagar=new Number(total)- new Number(desconto);
  novacoluna = novalinha.insertCell(11);
  novacoluna.innerHTML = "<input title=\"\" onchange='js_trocaval(this);' name=\"d07_vlrdes_"+matri+"\"  type=\"text\" id=\"d07_vlrdes_"+matri+"\"  value=\""+desconto+"\"  size=\"7\" maxlength=\"\" onblur=\"js_ValidaMaiusculo(this,'',event);\" onKeyUp=\"js_ValidaCampos(this,0,'','','',event);\"  onKeyDown=\"return js_controla_tecla_enter(this,event);\" autocomplete=''>";
  novacoluna = novalinha.insertCell(12);
  novacoluna.innerHTML = "<input title=\"\" name=\"d07_valor_"+matri+"\"  type=\"text\" id=\"d07_valor_"+matri+"\"  value=\""+apagar+"\"  size=\"7\" maxlength=\"\" onblur=\"js_ValidaMaiusculo(this,'',event);\" onKeyUp=\"js_ValidaCampos(this,0,'','','',event);\"  onKeyDown=\"return js_controla_tecla_enter(this,event);\" autocomplete=''><input name='d05_testad_"+matri+"' id='d05_testad_"+matri+"' type='hidden' value='"+testada+"'> ";
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
<table width="746" height="336" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="100%" align="left" valign="top" bgcolor="#CCCCCC"> 
  <form name="form1" method="post" action="">
  <input name="contri" type="hidden" value="<?=(isset($contri)?$contri:$numcontri)?>">
  <input name="confirma" type="hidden">
  <table border="0">
    <table id='id_tabela' cellpadding="0" cellspacing="0" border="1" >
  <?
  if(isset($contri)){
    $cleditalrua->sql_record($cleditalrua->sql_query_file($contri));
    if(!$cleditalrua->numrows>0){
        echo "<script>;
             parent.document.form1.confirma.style.visibility='hidden';
             parent.document.form1.matricontri.style.visibility='hidden';
             parent.alert(\"Contribuição inválida.\");
            </script> ";
			 exit;   
    } 
    $clcontlot->sql_record($clcontlot->sql_query_file($contri,'','d05_contri'));
    if(!$clcontlot->numrows>0){
        echo "<script>;
             parent.document.form1.confirma.style.visibility='hidden';
             parent.document.form1.matricontri.style.visibility='hidden';
             parent.alert(\"Contribuição não possui lotes selecionados..\");
            </script> ";
				exit;     
    }
    $clcontrib->sql_record($clcontrib->sql_query($contri,"","d07_contri"));
    $prox=false;
    if($clcontrib->numrows>0){
       $prox=true;  
    }else{
      $clfate = new cl_fate;
       $sql = "select d02_profun,d01_perc,j01_matric,j40_refant,z01_nome,j34_setor,j34_quadra,j34_lote,j34_zona,d05_testad,j34_idbql, case when j36_testle is null or j36_testle = 0 then j36_testad else j36_testle end as j36_testad, d41_testada+d41_eixo as d41_testada
               from contlot
               inner join lote on j34_idbql = d05_idbql
							 inner join testpri on j49_idbql = j34_idbql
							 inner join testada on j49_idbql = j36_idbql and j49_face = j36_face and j49_codigo = j36_codigo
							 inner join iptubase on j01_idbql = d05_idbql
							 left  join iptuant on j40_matric = j01_matric
							 inner join cgm on j01_numcgm = z01_numcgm
							 inner join editalrua on d02_contri = d05_contri
							 inner join edital on d02_codedi = d01_codedi
               inner join editalruaproj on d11_contri=d05_contri
               inner join projmelhoriasmatric on d41_codigo = d11_codproj and j01_matric = d41_matric
	    where d05_contri=$contri
	    order by j01_matric";
      $clfate->facetesta($sql,$contri);
      echo "<script>;
             parent.document.form1.confirma.style.visibility='visible';
            </script> ";
    }
      
  }  
  ?>
    </table>  
  </form>
	  </td>
    </tr>
  </body>
</html>
<?

if(isset($confirma) && $confirma=="ok"){
  if($clcontrib->erro_status=="0"){
     $clcontrib->erro(true,false);
  }else{
     $clcontrib->erro(true,false);
      echo "
          <script>
            parent.location.href='con1_contrib004.php';
          </script>
        "; 	  
  }
}

if($prox==true){
  db_redireciona("con1_contrib007.php?contri=$contri");
}     
?>