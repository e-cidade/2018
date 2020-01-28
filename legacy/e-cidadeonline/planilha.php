<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

session_start();
include("libs/db_conecta.php");
include("libs/db_stdlib.php");
include("libs/db_sql.php");
include("classes/db_issplan_classe.php");
include("classes/db_issplanit_classe.php");
include("classes/db_issplaninscr_classe.php");
include("classes/db_issplanitinscr_classe.php");

parse_str(base64_decode($HTTP_SERVER_VARS["QUERY_STRING"]));
$result = db_query("SELECT distinct m_publico,m_arquivo,m_descricao
                   FROM db_menupref
                   WHERE m_arquivo = 'digitaissqn.php'
                   ORDER BY m_descricao
                  ");
									
db_fieldsmemory($result,0);
if($m_publico != 't'){
  if(!session_is_registered("DB_acesso"))
    echo"<script>location.href='index.php?".base64_encode("erroscripts=3")."'</script>";
}

mens_help();
db_logs("","",0,"Digita Codigo da Inscricao.");
db_mensagem("alvara_cab","alvara_rod");

postmemory($HTTP_POST_VARS);
$clquery = new cl_query;
$alterando=false;
$matri= array("1"=>"janeiro","2"=>"Fevereiro","3"=>"Março","4"=>"Abril","5"=>"Maio","6"=>"Junho","7"=>"Julho","8"=>"Agosto","9"=>"Setembro","10"=>"Outubro","11"=>"Novembro","12"=>"Dezembro");
$mesx= $matri[$mes];
$clquery->sql_query("issplan","q20_nomecontri,q20_planilha, q20_numcgm, q20_ano, q20_mes,q20_numpre","q20_mes","q20_ano = $ano and q20_mes=$mes and q20_numcgm= $numcgm or q20_ano = $ano and q20_numpre is null and q20_numcgm= $numcgm");
//die("$clquery->sql");  // traz todos do ano e mes selecionado ou ano e numpre = nada pelo cgc.
$clquery->sql_record($clquery->sql);
$numrows=$clquery->numrows;
$result=$clquery->result;
for($x=0; $x < $numrows; $x++){
  $alterar="alterar_".$x;
  if(isset($$alterar)){ 
    $alterando=true;
    $numer=$x;
    break;
  }
}
if($alterando==true){
  $clquery->sql_result($result,$numer,0);
  $codigopla = $clquery->resultado;
  $clquery->sql_result($result,$numer,5);
  db_redireciona("opcoesissqn.php?".base64_encode("modificando=true&alter=true&nomecontri=".$nomecontri."&fonecontri=".$fonecontri."&mes=".$mes."&ano=".$ano."&numcgm=".$numcgm."&nomes=".$nomes."&inscricaow=".$inscricaow."&planilha=".$codigopla));
  exit;
} 
$anocorreto = $ano;
$mescorreto = $mes;
$clquery->sql_query("db_confplan"," * ","","");
$clquery->sql_record($clquery->sql);
if(pg_numrows($clquery->result)==0){
  $w10_valor = 0;
}
db_fieldsmemory($clquery->result,0);

//#######################    Excluir planilha  #########################

     
$cl_issplan = new cl_issplan;
$cl_issplanit = new cl_issplanit;
$cl_issplaninscr = new cl_issplaninscr;
$cl_issplanitinscr = new cl_issplanitinscr;
/*
         if (isset($excluir)){
         	
         	//select q21_sequencial,q21_planilha from issplanit where q21_planilha = 788;
         	
         	//$sql = "select q21_sequencial,q21_planilha,q31_sequencial,q31_inscr from issplanit inner join issplanitinscr on q21_sequencial=q31_issplanit where q21_planilha =$plan";
         	$clquery->sql_query("issplanit left join issplanitinscr on q21_sequencial=q31_issplanit","*","","q21_planilha =$plan");
            	Echo "<br>kkkkkkkkkkkkkkkkkkkk <br>";
							$xx = $clquery->sql;
							echo "<br>".$xx;
						//	exit;
            	$clquery->sql_record($clquery->sql);
           		$result = $clquery->result;
           		$linhas=$clquery->numrows;
            	for($xp=0; $xp < $linhas; $xp++){ 
            	 	db_fieldsmemory($result,$xp);
            	 	if (isset($q31_sequencial)){
            	 		$cl_issplanitinscr-> q31_sequencial = $q31_sequencial;
            	 		$cl_issplanitinscr->excluir($q31_sequencial);
            	 	}
								$cl_issplanit->q21_sequencial = $q21_sequencial;
            		$cl_issplanit->excluir($q21_sequencial);
         	      echo "pk ins =  $q31_sequencial...pknit = $q21_sequencial"; 	
            	}
         	
         	    $clquery->sql_query("issplaninscr","*","","q24_planilha =$plan");
            	
							//die($clquery->sql);
            	$clquery->sql_record($clquery->sql);
           		$result = $clquery->result;
           		$linhas = $clquery->numrows;
           		if ($linhas!= 0){
           			db_fieldsmemory($result,0);
           		  //echo "com inscriçao";  
								$cl_issplaninscr->q24_sequencial = $q24_sequencial;     			
	         	  	$cl_issplaninscr->excluir($q24_sequencial);
	         		
           		}
							$cl_issplan->q20_planilha = $plan;
            	$cl_issplan->excluir($plan);
         		
         
         }

*/
//#########


?>
<html>
<head>
<title><?=$w01_titulo?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="scripts/db_script.js"></script>

<style type="text/css">
<?db_estilosite();?>
small{
    font-size: 10px;
    }
</style>
<link href="config/estilos.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="<?=$w01_corbody?>" onLoad="" <? mens_OnHelp() ?>>
<?mens_div();?>
<center>
 <table width="100%" border="0" cellpadding="0" cellspacing="0" class="texto">
  <tr>
  	<?
     $sqlcgc = "Select z01_cgccpf from cgm where z01_numcgm = $numcgm ";
	 $resultcgc = db_query($sqlcgc);
	 $linhascgc = pg_num_rows($resultcgc);
	 if($linhascgc >0){
	 	db_fieldsmemory($resultcgc,0);
		$cgc = $z01_cgccpf;
	 }
	 
	?>
   <td align="center" valign="center">
     <form name="form1" method="post" target="">
     <table width="90%" height="150" border="0" cellpadding="0" cellspacing="0" background="imagens/azul_ceu_O.jpg" class="texto">
       <input name="ano" type="hidden" value="<?=$ano?>">
       <input name="mes" type="hidden" value="<?=$mes?>">
  	   <input name="cgc" type="hidden" value="<?=$cgc?>">
       <input name="numcgm" type="hidden" value="<?=$numcgm?>">
       <input name="inscricaow" type="hidden" value="<?=$inscricaow?>">
       <input name="nomes" type="hidden" value="<?=$nomes?>">
       <input name="nomecontri" type="hidden" value="<?=@$nomecontri?>">
       <input name="fonecontri" type="hidden" value="<?=$fonecontri?>">
       <input name="plan" type="hidden" value="">
       <tr>
         <td align="center">
         <b>Planilha de <?=$mesx?> de <?=$ano?>! </b>
         </td>
       </tr>
       <tr><td>&nbsp;</td></tr>
       <tr>
         <td align="center">
         <b>Para cadastrar uma nova planilha para esta mesma data, clique no botão abaixo! </b>
         </td>
       </tr>
       <tr>
         <td align="center">
           <input type="button" class="botao" value="Nova Planilha" onclick="novaplan()">
           &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Buscar planilhas
           <select name="mostra"  onchange= "js_mostra();">
                 <? 
								 echo "<option value=\"5\"".($mostra==5?" selected":"").">Em digitação</option>		
							         <option value=\"1\"".($mostra==1?" selected":"").">Abertos</option>				 
							         <option value=\"2\"".($mostra==2?" selected":"").">Todos</option>
			                 <option value=\"3\"".($mostra==3?" selected":"").">Pagos</option>
			                 <option value=\"4\"".($mostra==4?" selected":"").">Cancelados</option>
											 <option value=\"6\"".($mostra==6?" selected":"").">Anulados</option>
								       <option value=\"7\"".($mostra==7?" selected":"").">Suspensos</option>";
								 
								 ?>
           </select>    
         
         </td>
       </tr>
       <tr><td>&nbsp;</td></tr>
       <tr>
        <td>
          <?
    	
    	if (isset($mostra)){
    		
    		if($mostra==5){
    		//	echo "Em digitação";
    		//select * from issplan where q20_ano = 2006 and q20_mes=1 and q20_numcgm= 278626 and (q20_numpre=0 or q20_numpre is null);   
    		
    		 $clquery->sql_query("issplan inner join issplanit on q20_planilha=q21_planilha","distinct issplan.*","","q20_ano = $ano and q20_mes=$mes and q20_numcgm= $numcgm and (q20_numpre=0 or q20_numpre is null) and q20_situacao<> 5 ");
             $clquery->sql_record($clquery->sql);
             $result = $clquery->result;
             $numrows=$clquery->numrows;
            }
    		
    		if($mostra==1){
    			//echo "abertos";
    			//select * from issplan inner join arrecad on q20_numpre=k00_numpre  where q20_ano = 2006 and q20_mes=1 and q20_numcgm= 278626;
    		 	$clquery->sql_query("issplan inner join arrecad on q20_numpre=k00_numpre inner join issplanit on q20_planilha=q21_planilha","distinct issplan.*","","q20_ano = $ano and q20_mes=$mes and q20_numcgm= $numcgm and q20_situacao<> 5");
            	$clquery->sql_record($clquery->sql);
            	$result = $clquery->result;
             	$numrows=$clquery->numrows;
    			
    			
    		 	
    		}
    		if($mostra==2){
    			//echo "todos";
    		    		
	    		$sql1= "select distinct issplan.q20_numpre,q20_planilha,q20_ano,q20_mes,q20_nomecontri,q20_numpre as issplan, arrecad.k00_numpre as arrecad, arrecant.k00_numpre as arrecant, arrepaga.k00_numpre as arrepaga,
							case when arrecad.k00_numpre is not null and q20_situacao<> 5 then 'ABERTO' else
								case when arrepaga.k00_numpre is not null then 'PAGO' else
	 								case when arrecant.k00_numpre is not null and arrepaga.k00_numpre is null and q20_situacao<> 5  then 'CANCELADO' else
										case when q20_numpre is  null or q20_numpre = 0 and q20_situacao<> 5 then 'DIGITANDO' else
										  case when q76_planilha is not null and q20_situacao= 5 then 'ANULADO' 
										  end
										end
									end
								end 
							end as situacao
							from issplan
							left join arrecad      on arrecad.k00_numpre  = q20_numpre
							left join arrecant     on arrecant.k00_numpre = q20_numpre
							left join arrepaga     on arrepaga.k00_numpre = q20_numpre
							inner join issplanit   on q20_planilha        = q21_planilha
							left join issplananula on q76_planilha        = q20_planilha
							where q20_ano = $ano and q20_mes=$mes and q20_numcgm= $numcgm order by q20_mes";
				$result = db_query($sql1);
				$numrows = pg_numrows($result);
   		
    	}
    		if($mostra==3){
    			//echo "pagos";
    			$clquery->sql_query("issplan inner join arrepaga on q20_numpre=k00_numpre","*","","q20_ano = $ano and q20_mes=$mes and q20_numcgm= $numcgm");
            	//die($clquery->sql);
            	$clquery->sql_record($clquery->sql);
           		$result = $clquery->result;
            	$numrows=$clquery->numrows;
    			    			
    		}
    		if($mostra==4){
    			//echo "cancelados";
    			$clquery->sql_query("issplan inner join arrecant on q20_numpre=arrecant.k00_numpre left join arrepaga on q20_numpre=arrepaga.k00_numpre","*","","q20_ano = $ano and q20_mes=$mes and q20_numcgm= $numcgm and arrepaga.k00_numpre is null and q20_situacao<> 5");
            	//die($clquery->sql);
            	$clquery->sql_record($clquery->sql);
           		$result = $clquery->result;
            	$numrows=$clquery->numrows;
    			
    		}
				if($mostra==6){
    			//echo "anulados";
    			$clquery->sql_query("issplan inner join issplananula on q20_planilha = q76_planilha ","distinct on(q20_planilha)  *","","q20_ano = $ano and q20_mes=$mes and q20_numcgm= $numcgm ");
//        die($clquery->sql);
          $clquery->sql_record($clquery->sql);
          $result = $clquery->result;
          $numrows=$clquery->numrows;
    			
    		}
				
    	   if($mostra==7){
    	   
          //echo "suspensos";
          $sSql  = " issplan                                                           "; 
          $sSql .= " inner join arresusp  on arresusp.k00_numpre       = q20_numpre    ";
          $sSql .= " inner join suspensao on suspensao.ar18_sequencial = k00_suspensao "; 
    	   
          $sWhere  = "     ar18_situacao = 1       "; 
					$sWhere .= " and q20_ano       = $ano    "; 
					$sWhere .= " and q20_mes       = $mes    ";
					$sWhere .= " and q20_numcgm    = $numcgm ";
          
          $clquery->sql_query($sSql,"distinct on(q20_planilha)  *","",$sWhere);
          $clquery->sql_record($clquery->sql);
          $result = $clquery->result;
          $numrows=$clquery->numrows;
          
        }				
    		
    		
    	}
    	
    	
          if(isset($planilha) && $planilha != ""){ // entra aki quando clico no botão comprovante.....................
             $clquery->sql_query("issplanit  left join issplanitinscr on q21_sequencial=q31_issplanit"," distinct q21_cnpj,q21_nome,q21_servico,q31_inscr,sum(q21_valor) as q21_valor",""," q21_planilha = $planilha  and q21_status = 1 group by q21_cnpj,q21_nome,q21_servico,q31_inscr");
            // die ("$clquery->sql");
             $clquery->sql_record($clquery->sql);
             $result = $clquery->result;
             $numrows=$clquery->numrows;
             $numfields=$clquery->numfields;
             
             
             echo "<table width=\"100%\"  class=\"tab\">";
             echo "  <tr>";
             echo "    <th align=\"center\" bgcolor=\"#00436E\" ><b><font color=\"#FFFFFF\">CNPJ</font></b></th> ";
             echo "    <th align=\"center\" bgcolor=\"#00436E\" ><b><font color=\"#FFFFFF\">INSCRIÇÃO</font></b></th> ";
             echo "    <th align=\"center\" bgcolor=\"#00436E\" ><b><font color=\"#FFFFFF\">NOME/RAZÃO SOCIAL</font></b></th> ";
             echo "    <th align=\"center\" bgcolor=\"#00436E\" ><b><font color=\"#FFFFFF\">SERVIÇO PRESTADO</font></b></th> ";
             echo "    <th align=\"center\" bgcolor=\"#00436E\" ><b><font color=\"#FFFFFF\">TOTAL</font></b></th> ";
             echo "    <th align=\"center\" bgcolor=\"#00436E\" ><b><font color=\"#FFFFFF\">EMITE</font></b></th> ";
             echo "  </tr>";
             echo "<tbody id='corpoplanilha'>";
             for($x=0; $x < $numrows; $x++) {
             
               echo "  <tr>";
               db_fieldsmemory($result,$x);
               echo "    <td align=\"left\" ><b>$q21_cnpj</b></td> ";
               echo "    <td align=\"left\" ><b>$q31_inscr</b></td> ";
               echo "    <td align=\"left\" ><b>$q21_nome</b></td> ";
               echo "    <td align=\"left\" ><b>$q21_servico</b></td> ";
               echo "    <td align=\"right\" ><b>".db_formatar($q21_valor,"f")."</b></td> ";
               echo "    <td width=\"15%\" nowrap align=\"center\" valign=\"top\" ><input class=\"botao\" type=\"button\" name=\"tiras_$x\" value=\"Comprovante\" onclick=\"js_tiras('$planilha','$q31_inscr','$q21_cnpj')\" ></td>";
               echo "</tr>";
               
             }
             echo "</tbody>";
             ?><input type="button" value="Voltar" onclick="history.back()"><?
          }else{ 
          	
          // ################## entra aki qd vem da opcoesissqn antes de clicar nos botoes ##################
            
             // ############ traz a planilha o ano e o mes #################
            /* $clquery->sql_query("issplan","*","q20_mes","q20_ano = $ano and q20_mes=$mes and q20_numcgm= $numcgm and q20_numcgm= $numcgm");
            // die("$clquery->sql");
             $clquery->sql_record($clquery->sql);
             $result = $clquery->result;
             $numrows=$clquery->numrows;
             $numfields=$clquery->numfields;*/
            
                 echo "<table width=\"100%\"  class=\"tab\">";
               echo "  <tr>";
               echo "    <th align=\"center\" bgcolor=\"#00436E\" ><b><font color=\"#FFFFFF\">Planilha</font></b></th> ";
               echo "    <th align=\"center\" bgcolor=\"#00436E\" ><b><font color=\"#FFFFFF\">Ano</font></b></th> ";
               echo "    <th align=\"center\" bgcolor=\"#00436E\" ><b><font color=\"#FFFFFF\">Mês</font></b></th> ";
               echo "    <th align=\"center\" bgcolor=\"#00436E\" ><b><font color=\"#FFFFFF\">Emite</font></b></th> ";
               echo "    <th align=\"center\" bgcolor=\"#00436E\" ><b><font color=\"#FFFFFF\">Contato</font></b></th> ";
               echo "    <th align=\"center\" bgcolor=\"#00436E\" ><b><font color=\"#FFFFFF\">Opções</font></b></th> ";
               echo "  </tr>"; 
               echo "<input name=\"planilha\" value=\"\" type=\"hidden\">";
               echo "<tbody id='corpoplanilha'>";
                    
	             for($x=0; $x < $numrows; $x++){ //linhas
	               $clquery->sql_result($result,$x,0);
	               $planilha = $clquery->resultado;
	              //######### traz o numpre da planilha ##########
	              // $res=db_query("select q20_numpre,q20_nomecontri from issplan where q20_planilha=$planilha and q20_planilha is not null");
	              // $numpr=pg_result($re,0,0);
	               db_fieldsmemory($result,$x);
	              // echo "numpre=$q20_numpre, nome =$q20_nomecontri ";
	               echo "<tr>";
	           // ######### monta a tabela #####################    
	               echo "<td align=\"center\" ><b><font >$q20_planilha</font></b></td> ";
	               echo "<td align=\"center\" ><b><font >$q20_ano</font></b></td> ";
	               echo "<td align=\"center\" ><b><font >$q20_mes</font></b></td> ";
	               echo "<td width=\"200px\" nowrap align=\"center\" valign=\"top\" >
								         <input class=\"botao\" type=\"button\" name=\"planilha_$x\" value=\"Planilha\" onclick=\"js_planilha($q20_planilha)\" >
												 ";
								if(($mostra!=6) and ($mostra == 2 and $situacao!="ANULADO") ){
									//db_msgbox("mostra = $mostra situacao = $situacao");
									echo "<input class=\"botao\" type=\"button\" name=\"tiras_$x\" value=\"Comprovante\" onclick=\"document.form1.planilha.value = '$q20_planilha';document.form1.submit()\" >";
								}		 
								 echo "	 </td>";
	               echo "<td align=\"center\" ><b><font > $q20_nomecontri </font></b></td> ";
	             
	               // se o mes que escolhi for maior que o mes atual-2 ou se o ano q escolhi for maior que o ano atual
	               // ele mostra um botão de alterar
	               
	               //determina o botão.... se tiver numpre = 0 botão reemite recibo senão emite recibo
	               //botão reemite recibo chama a função js_recibo e passa o numpre que vai chamar recibopdf.php
	               //botão emite recibo chama a função 7 e passa $planilha,$q20_ano,$q20_mes,$numcgm que vai chamar opcoesissqn001
				  
				   if (isset($mostra)){	              
		               if ($mostra==5 || $mostra==1){ //......... se for aberto ou em diditação 
		               
			               $botvalor = $q20_numpre!=0?"Reemite Recibo":("Emite Recibo");
			               $evento = $q20_numpre!=0?'onclick="js_recibo('.$q20_numpre.','.$planilha.')"':('onclick="js_recibo1('.$planilha.','.$q20_ano.','.$q20_mes.','.$numcgm.',this)"');
			               echo "<td width=\"250px\" valign=\"top\"><input class=\"botao\" type=\"button\" name=\"alterar_$x\" value=\"$botvalor\"   $evento   >&nbsp;";
			               
			               //alterar abertos, digitados 
			               echo "<input class=\"botao\" type=\"button\" name=\"alterar\" value=\"Alterar \" onClick=\"js_alterar($q20_planilha)\"   >&nbsp;";
		                 
		              
		                   // excluir digitados 
			               if ($q20_numpre==0 ||$q20_numpre==""){
                        echo "<input class=\"botao\" type=\"button\" name=\"anular\" value=\"Anular\" onClick=\"js_anula($q20_planilha)\">";
//			              	echo "<input class=\"botao\" type=\"submit\" name=\"excluir\" value=\"Excluir \" onClick=\"js_excluir($q20_planilha)\">";
			                 
										 }else{
										 	 
												 echo "<input class=\"botao\" type=\"button\" name=\"anular\" value=\"Anular\" onClick=\"js_anula($q20_planilha)\">";
										 }
			               echo"</td>";
		               }
				   }
				   if (isset($mostra)){	     
		           if ($mostra==2){// todos................
		               	if($situacao=="DIGITANDO" || $situacao== "ABERTO"){
		               	   	$botvalor = $q20_numpre!=0?"Reemite Recibo":("Emite Recibo");
			              	  $evento = $q20_numpre!=0?'onclick="js_recibo('.$q20_numpre.','.$q20_planilha.')"':('onclick="js_recibo1('.$q20_planilha.','.$q20_ano.','.$q20_mes.','.$numcgm.',this)"');
			               	  echo "<td width=\"250px\" valign=\"top\"><input class=\"botao\" type=\"button\" name=\"alterar_$x\" value=\"$botvalor\"   $evento   >&nbsp;";
		               	   	echo "<input class=\"botao\" type=\"button\" name=\"alterar\" value=\"Alterar \" onClick=\"js_alterar($q20_planilha)\">&nbsp;";
		               	  	if ($q20_numpre==0 ||$q20_numpre==""){
			              	  	 echo "<input class=\"botao\" type=\"button\" name=\"anular\" value=\"Anular\" onClick=\"js_anula($q20_planilha)\">";
//											   echo "<input class=\"botao\" type=\"submit\" name=\"excluir\" value=\"Excluir \" onClick=\"js_excluir($q20_planilha)\">";
												
			                	}else{
										 	    echo "<input class=\"botao\" type=\"button\" name=\"anular\" value=\"Anular\" onClick=\"js_anula($q20_planilha)\">";
										    }
		               	    echo"</td>";
		               	}
		               	if($situacao=="PAGO"){
		               		echo "<td > PAGO</td>";
		               	}
		               	if($situacao=="CANCELADO"){
		               		echo "<td > CANCELADO</td>";
		               	}
										if($situacao=="ANULADO"){
		               		echo "<td > ANULADO</td>";
		               	}
		            
		              }
				   }
	               	                            
	               echo "</tr>";
	               echo "</tbody>";
	             //  }
	             }
           
         ?>
           </td>
         </tr>
         <tr>
         <td align="center" colspan="7">Data de Pagamento:
             <?
            
             $mescorreto += 1; 
             if($mescorreto>12){
               $mescorreto = 1;
               $anocorreto += 1;
             }
             if(($anocorreto < date("Y") ) || ($anocorreto==date("Y") && $mescorreto < date("m"))){

               $mescorreto = date("m");
               $anocorreto = date("Y");
               if($w10_dia<date("d"))
                 $w10_dia = date("d");

             }else{
               if( ($anocorreto==date("Y") && $mescorreto == date("m") &&  $w10_dia<date("d"))){
                 $w10_dia = date("d");
               }
             }

             db_data("dtvenc",db_formatar($w10_dia,'s','0',2),db_formatar($mescorreto,'s','0',2),$anocorreto);
             ?>
         </td>
        </tr>
         <?
         }
    
         
         
         ?>
 </table>
</form>
</center>
</body>
</html>
<script>
function js_planilha(planilha){
  window.open('relatoriopdf.php?planilha='+planilha+'&contato='+document.form1.nomecontri.value+'&telcontato='+document.form1.fonecontri.value,'','toolbar=no,menubar=no,scrollbars=yes,resizable=yes,location=no,directories=no,status=no');
}  
function js_tiras(planilha,inscr,cnpj){
  window.open('tiras.php?planilha='+planilha+'&cnpjprestador='+cnpj+'&q21_inscr='+inscr+'&contato='+document.form1.nomecontri.value+'&telcontato='+document.form1.fonecontri.value,'','toolbar=no,menubar=no,scrollbars=yes,resizable=yes,location=no,directories=no,status=no');
}  



function js_excluir(plan){
		var obj = document.form1;
		obj.plan.value  = plan;
		
}
function js_alterar(plan){
	mes        = document.form1.mes.value;
 	ano        = document.form1.ano.value;
 	numcgm     = document.form1.numcgm.value;
 	inscricaow = document.form1.inscricaow.value;
  cgc        = document.form1.cgc.value;

  location.href = "opcoesissqn.php?planilha="+plan+"&mes="+mes+"&ano="+ano+"&numcgm="+numcgm+"&cgc="+cgc+"&inscricaow="+inscricaow+"&nova="+1;
	
}
function js_anula(plan){

	mes        = document.form1.mes.value;
 	ano        = document.form1.ano.value;
 	numcgm     = document.form1.numcgm.value;
	inscricaow = document.form1.inscricaow.value;
	location.href ="anulaplanilha.php?planilha="+plan+"&mes="+mes+"&ano="+ano+"&numcgm="+numcgm+"&inscricaow="+inscricaow;
}

function js_mostra(){
	document.form1.submit(); 
}


function novaplan(){
 mes        =document.form1.mes.value;
 ano        =document.form1.ano.value;
 numcgm     =document.form1.numcgm.value;
 cgc        =document.form1.cgc.value;
 inscricaow =document.form1.inscricaow.value;
 nomecontri =document.form1.nomecontri.value;
 fonecontri =document.form1.fonecontri.value;
 location.href="opcoesissqn.php?fonecontri="+fonecontri+"&nomecontri="+nomecontri+"&mes="+mes+"&ano="+ano+"&numcgm="+numcgm+"&inscricaow="+inscricaow+"&cgc="+cgc;
}

function js_recibo1(planilha,ano,mes,cgm,obj){
	
 obj.disabled = true;
  	
 inscricaow = document.form1.inscricaow.value;
 var dthoje = new Date('<?=date("Y")?>','<?=date("m")?>','<?=date("d")?>','24');
  var dt = new Date(document.form1.dtvenc_ano.value,document.form1.dtvenc_mes.value,document.form1.dtvenc_dia.value,'24');

  if(isNaN(dt)){

    alert('Data Inválida. Verifique');
    document.form1.dtvenc_dia.select();
    document.form1.dtvenc_dia.focus();
  
  } else {

    var dti = new Number(dt.getTime());
    var dtf = new Number(dthoje.getTime());
   
    if( dti < dtf ){
    
      alert('Data de Pagamento Inválida. Deverá ser data de hoje ou maior que hoje.');
    
    } else {
    
      var retorno = confirm('Confirma emissão do recibo?');
      if(retorno==true){
        jan = window.open('recibopdf.php?planilha='+planilha+'&dtpaga='+document.form1.dtvenc_ano.value+"-"+document.form1.dtvenc_mes.value+"-"+document.form1.dtvenc_dia.value,'','toolbar=no,menubar=no,scrollbars=yes,resizable=yes,location=no,directories=no,status=no');
        jan.focus();
        document.form1.mostra.value = 1;
        document.form1.submit();
        
      } else {
      	obj.disabled = false;
      }
    }
  }
    
 // location.href='opcoesissqn001.php?ano='+ano+'&mes='+mes+'&numcgm='+cgm+'&planilha='+planilha+'&inscricao='+inscricaow;
 
}
function js_recibo(numpre,planilha){
  
  var dthoje = new Date('<?=date("Y")?>','<?=date("m")?>','<?=date("d")?>','24');
  var dt = new Date(document.form1.dtvenc_ano.value,document.form1.dtvenc_mes.value,document.form1.dtvenc_dia.value,'24');

  if(isNaN(dt)){

    alert('Data Inválida. Verifique');
    document.form1.dtvenc_dia.select();
    document.form1.dtvenc_dia.focus();
  
  } else {

    var dti = new Number(dt.getTime());
    var dtf = new Number(dthoje.getTime());
	if( dti < dtf ){
    
      alert('Data de Pagamento Inválida. Deverá ser data de hoje ou maior que hoje.');

      document.form1.dtvenc_dia.select();
      document.form1.dtvenc_dia.focus();

    }else{
      var retorno = confirm('Confirma e Emissão?');
      if(retorno==true){
        window.open('recibopdf.php?dados_recibo=true&q24_inscr=<?=$inscricaow?>&q20_numcgm=<?=$numcgm?>&q20_numpre='+numpre+'&planilha='+planilha+'&dtpaga='+document.form1.dtvenc_ano.value+"-"+document.form1.dtvenc_mes.value+"-"+document.form1.dtvenc_dia.value,'','toolbar=no,menubar=no,scrollbars=yes,resizable=yes,location=no,directories=no,status=no');
      }
    }
  }
}
</script>