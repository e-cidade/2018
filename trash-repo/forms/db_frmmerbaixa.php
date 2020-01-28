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

//MODULO: merenda
$clmer_cardapio->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("me03_i_codigo");
$clrotulo->label("ed15_i_codigo");
$clrotulo->label("me14_i_codigo");
$clrotulo->label("ed18_i_codigo");
?>
<form name="form1" method="post" action="">
<br><br>
<center>
<fieldset style="width:65%"><legend>Horario de Cardápios</legend>
<center>
<table border="0" align="center">
  <tr> 
     <td>  
       <?
       $sqlturn="select me03_c_tipo,me03_i_orden,me03_i_codigo,ed15_c_nome from mer_tprefeicao inner join turno on ed15_i_codigo=me03_i_turno order by me03_i_orden";
       $resultturn=pg_query($sqlturn);
       $linhasturn=pg_num_rows($resultturn);
       ?>  
            <table cellspacing="0" cellpading="0" border="1" bordercolor="#000000">
               <tr><td colspan="6"></td></tr>  
       <?
       for($x=0;$x<$linhasturn;$x++){
  	        db_fieldsmemory($resultturn,$x);
            $resultdias = $cldiasemana->sql_record($cldiasemana->sql_query_rh("","*","ed32_i_codigo"," ed04_c_letivo = 'S' AND ed04_i_escola = $escola"));
            ?>
               <tr>
				  <td colspan="<?=$cldiasemana->numrows+1?>" bgcolor=""><b></b></td>
			   </tr>			   
			   <tr bgcolor="#444444">
                  <td align="center" width="30" style="font-weight: bold; color: #DEB887;"><?=$ed15_c_nome?></td>
            <?
            if($cldiasemana->numrows==0){
             ?>			
			   <tr>
				  <td><a href="javascript:parent.location.href='edu1_diasemanaabas001.php'"><b>Informe
				  os dias lelivos desta escola</b></a></td>
			   </tr>
			   <tr> 
			<?}
			for($y=0;$y<$cldiasemana->numrows;$y++){
              db_fieldsmemory($resultdias,$y);?> 
                 <td>
			        <table cellspacing="0" cellpading="0">
				       <tr>
					      <td width="50" style="font-weight: bold; color: #DEB887;">
					         <div align="center"><?=$ed32_c_abrev?></div>
					      </td>
				       </tr>
			        </table>
			     </td>
            <?}?>
               </tr>
			   <tr>
			       <td align="center" width="120" style="font-weight: bold; background-color: #f3f3f3;">
                   <?=$me03_c_tipo?>
                   </td><?
                   for($y=0;$y<$cldiasemana->numrows;$y++){
                   $quadro = "Q".$x.$y;       
                   db_fieldsmemory($resultdias,$y);
                   $sql2= "select * from mer_cardapiodia 
	               inner join mer_cardapio on me01_i_codigo=me12_i_cardapio
	               inner join mer_tprefeicao on me03_i_codigo=me12_i_tprefeicao
	               where me12_i_diasemana=".$ed32_i_codigo." and me03_i_codigo=".$me03_i_codigo;
                   $result2 = pg_query($sql2);
                   $linhas2 = pg_num_rows($result2);
                   if($linhas2>0){
                       db_fieldsmemory($result2,0);                                          
                          $nomecardapio = $marcar = $me01_c_nome;        
                          $percepita = $me01_i_percapita;
                          $codcar = $me12_i_cardapio;
                          $estado  = 'OK';
                   }else{
                          $marcar = "";
                          $percapita = "";
                          $codcar= "";
                          $estado = "Sem Cardapio";
                   }
                   
                   ?>
                   <td>
			          <table cellspacing="0" cellpading="0" marginwidth="0">
				         <tr>
					       <td>
					          <table class="texto" bgcolor="#cccccc" id="<?=$quadro?>"
						         cellspacing="0" cellpading="0"
					 	         style="border: 2px outset #f3f3f3; border-bottom-color: #999999; border-right-color: #999999;">
						         <tr>
							       <td align="center"
								      width="50" height="15" onmouseover="InSet('<?=$quadro?>')"
								      onmouseout="OutSet('<?=$quadro?>')">
								      <input type="text"
								             id="text<?=$quadro?>" name="text<?=$quadro?>"
								             value="<?=$marcar?>" size="7"
								             style="border: 0px; background: #cccccc; text-align: center; font-weight: bold;"
								             readonly> 
							        </td>
						        </tr>
					          </table>
					          <div name="dados<?=$quadro?>" id="dados<?=$quadro?>"
						      style="visibility: hidden; position: absolute;">
					          <table bgcolor="#f3f3f3" style="border: 2px outset #999999;">
						         <tr>
							        <td style="font-size: 9px;">Dados do Cardapio</td>
						         </tr>
						         <tr>
							        <td height="1" bgcolor="#999999"></td>
						         </tr>
						         <tr>
							        <td style="font-size: 9px;">
                                       Nome:<?=$marcar?><br>
                                       TURNO: <?=$ed15_c_nome?><br>
                                       <?=$ed32_c_descr?><br> 
                                       Percapita: <?=$percapita?><br>
                                       Codigo do cardapio: <?=$codcar?><br>
							           Estado: <span id="estado<?=$quadro?>"><font color="#FF0000"><?=$estado?></font></span>
							        </td>
						         </tr>
					          </table>
					          </div>
					      </td>
				       </tr>
			        </table>
			     </td>
	  <?}?>	
		    <tr>
      <?}?>
            </tr>
	       </table>
		 </td>		 
      </tr>
</table>  
</fieldset>
</center>
<br><br>
<?

$sql="select me07_i_codigo,me07_f_quantidade,me07_i_codmater,me07_i_cardapio from mer_cardapioitem
        inner join mer_cardapio on me07_i_cardapio=me01_i_codigo
        inner join mer_cardapiodia on me01_i_codigo=me12_i_cardapio";
$result=pg_query($sql);
$linhas = pg_num_rows($result);
for($x=0;$x<$linhas;$x++){
   db_fieldsmemory($result,$x);
   $add=false;
   $vetcod[0]=0;
   $vetitem[0]=0;
   $vetquant[0]=0;
   $limit=count($vetcod);
   for($y=0;$y<$limit;$y++){
      if($vetitem[$y]==$me07_i_codmater){      	   
      	   $vetquant[$y]=$vetquant[$y]+$me07_f_quantidade;
           $add=true;
      }
   }
   if($add==false){
     $vetcod[$limit]=$me07_i_codigo;
     $vetitem[$limit]=$me07_i_codmater;
     $vetquant[$limit]=$me07_f_quantidade;
   }
}
$listacod="";
$listaitem="";
$listaquant="";
$sep="";
if(isset($vetcod)){
$limit=count($vetcod);
for($y=1;$y<$limit;$y++){
   $listacod=$listacod.$sep.$vetcod[$y];
   $listaitem=$listaitem.$sep.$vetitem[$y];
   $listaquant=$listaquant.$sep.$vetquant[$y];     
   $sep=",";
}
}

$sql="select me07_i_codmater,m60_descr,(sum(me07_f_quantidade)) as me07_f_quantidade from mer_cardapioitem
       inner join mer_cardapiodia on me07_i_cardapio=me12_i_cardapio
       inner join matmater on me07_i_codmater=m60_codmater
group by me07_i_codmater,m60_descr";
$result=pg_query($sql);
$linhas=pg_num_rows($result);
?>
<center>
<?db_lovrot($sql,"5","","")?>
<br>
<?if($linhas>0){?>
<input type="button" name="Incluir" value="Baixar Estoque" onclick="js_incluir('<?=$listacod?>','<?=$listaitem?>','<?=$listaquant?>')">
<?}?>
</center>

</form>

<script>
function InSet(id){
  T = document.getElementById(id);
  D = document.getElementById("dados"+id);
  T.style.border = "2px inset #f3f3f3";
  D.style.visibility = "visible";
}
function OutSet(id){
  T = document.getElementById(id);
  D = document.getElementById("dados"+id);
  T.style.border = "2px outset #f3f3f3";
  T.style.borderBottomColor = "#999999";
  T.style.borderRightColor = "#999999";
  T.style.fontSize = "11px;";
  D.style.visibility = "hidden";
}
function js_incluir(listacod,listaitem,listaquant){  
   if(confirm('Tem certeza que deja dar baixa nesses itens?')){     
     location.href = 'mer4_mer_baixasemanal001.php?cod='+listacod+'&item='+listaitem+'&quant='+listaquant+'&incluir=1';
   }
}
function js_reload(){
     location.href = 'mer4_mer_baixasemanal001.php';
}
</script>