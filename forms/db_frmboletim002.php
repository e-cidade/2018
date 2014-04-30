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

require("../libs/db_stdlib.php");
require("../libs/db_conecta.php");
include("../libs/db_sessoes.php");
include("../libs/db_usuariosonline.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="../scripts/scripts.js"></script>
<link href="../estilos.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >

<?
    $processou = false;
if($tipo=="recorc" || $tipo=="recextra"){

   $sql = "
     select k13_conta,k13_descr,k02_codigo,k02_tipo,k02_drecei,round(arrec,2)::float8 as arrec,round(estorno,2)::float8 as estorno from (
     select k12_conta,k12_receit,sum(k12_arrec) as arrec, sum(k12_estorno) as estorno
     from 
        (select corrente.k12_conta,cornump.k12_receit,case when k12_estorn = false then cornump.k12_valor else 0 end as k12_arrec, case when k12_estorn = true then cornump.k12_valor else 0 end as k12_estorno
         from corrente
	      inner join cornump   
               on corrente.k12_id     = cornump.k12_id 
	       and corrente.k12_data   = cornump.k12_data 
	       and corrente.k12_autent = cornump.k12_autent
       where corrente.k12_data = '$data' and k12_instit = " . db_getsession("DB_instit") . "
        ) as x
    group by k12_conta, k12_receit
    order by k12_conta,k12_receit
    ) as rec
         inner join saltes on k12_conta = k13_conta
	 inner join tabrec on k02_codigo = rec.k12_receit
    where k02_tipo = ".($tipo=="recorc"?"'O'":"'E'");



    
    $result = pg_query($sql);

    //db_criatabela($result);
    
    $clrotulo = new rotulocampo;
    $clrotulo->label("k13_conta");
    $clrotulo->label("k13_descr");
    $clrotulo->label("k02_codigo");
    $clrotulo->label("k02_drecei");
    

    if(pg_numrows($result)!=0){
      $processou = true;
      db_lovrot($sql,15,"()","","");
/* 
      echo "<table border=\"1\">";
      echo "<tr>";
      echo "<td>$Lk13_conta</td>";
      echo "<td align=\"left\">$Lk13_descr</td>";
      echo "<td>$Lk02_codigo</td>";
      echo "<td align=\"left\">$Lk02_drecei</td>";
      echo "<td><strong>Arrecadado</strong></td>";
      echo "<td><strong>Estornado</strong></td>";
      echo "<td><strong>Saldo</strong></td>";
      echo "</tr>";
      $totarre = 0;
      $totesto = 0;
      for($rec=0;$rec<pg_numrows($result);$rec++){
	db_fieldsmemory($result,$rec);
        echo "<tr>";
	echo "<td>$k13_conta</td>";
	echo "<td>$k13_descr</td>";
	echo "<td>$k02_codigo</td>";
	echo "<td>$k02_drecei</td>";
	echo "<td align=\"right\">$arrec</td>";
	echo "<td align=\"right\">$estorno</td>";
	echo "<td align=\"right\">".($arrec-($estorno*-1))."</td>";
      	echo "</tr>";
	$totarre = $totarre + $arrec;
	$totesto = $totesto + $estorno;
      }
      echo "<tr>";
      echo "<td align=\"right\" colspan=\"4\"><strong>Total:</strong></td>";
      echo "<td align=\"right\"><strong>$totarre</strong></td>";
      echo "<td align=\"right\"><strong>$totesto</strong></td>";
      echo "<td align=\"right\"><strong>".($totarre-($totesto*-1))."</strong></td>";
      echo "</tr>";
      echo "</table>";
      $processou = true;
     */
   }

}else if($tipo=='trans'){
    
  $sql = "
    select 
	       corrente as dl_Crébito,
	       descr_conta as dl_Descricao,
           case when k12_estorn =  'f' then valor else 0 end as dl_Valor,
	       case when k12_estorn <> 'f' then valor else 0 end as dl_Estorno,
	        corlanc as dl_Dédito,
	        descr_receita as dl_Descr
    from (
    select corrente.k12_id,
			   corrente.k12_data,
			   sum(corrente.k12_valor) as valor,
			   corrente.k12_conta as corrente,
			   p2.c60_descr as descr_conta,
			   c.k13_conta as corr_saltes,
			   b.k12_conta as corlanc,
			   p1.c60_descr as descr_receita,
			   coalesce(d.k13_conta,0) as corl_saltes,
			   k12_estorn
    from corrente
	     inner join corlanc b on corrente.k12_id = b.k12_id
		       	                      and corrente.k12_autent=b.k12_autent
			                          and corrente.k12_data = b.k12_data
	     inner join saltes c   on c.k13_conta = corrente.k12_conta
	     inner join saltes d   on d.k13_conta = b.k12_conta
	     inner join conplanoreduz r1 on b.k12_conta = r1.c61_reduz and r1.c61_anousu=".db_getsession("DB_anousu")."
	     inner join conplano      p1 on r1.c61_codcon = p1.c60_codcon and r1.c61_anousu = p1.c60_anousu 

	     inner join conplanoreduz r2 on corrente.k12_conta = r2.c61_reduz and r2.c61_anousu=".db_getsession("DB_anousu")."
	     inner join conplano      p2 on r2.c61_codcon = p2.c60_codcon and r2.c61_anousu = p2.c60_anousu  
    where corrente.k12_instit = " . db_getsession("DB_instit") . " and corrente.k12_data = '$data'
    group by corrente.k12_id,
				   corrente.k12_data,
				   corrente.k12_conta,
				   p2.c60_descr,
				   c.k13_conta,
				   b.k12_conta,
				   p1.c60_descr,
				   d.k13_conta,
				   k12_estorn
    order by corrente.k12_conta,
				   p2.c60_descr,
				   b.k12_conta,
				   p1.c60_descr
    ) as x " ;
    $result = pg_query($sql);
    $clrotulo = new rotulocampo;

    if(pg_numrows($result)!=0){

      $processou = true;
      db_lovrot($sql,15,"()","","");
    }

}else if($tipo=='despextra'){
   
  $sql = "
    select 
	       corrente as dl_Crédito ,
	       descr_conta as dl_Descricao,
           case when k12_estorn =  'f' then valor else 0 end as dl_Valor,
     	   case when k12_estorn <> 'f' then valor else 0 end as dl_Estorno,
	       corlanc as dl_Débito,
	       descr_receita as dl_Descr,
	       conta_lanc as conta_lanc
    from (
    select corrente.k12_id,
			   corrente.k12_data,
			   sum(corrente.k12_valor) as valor,
			   corrente.k12_conta as corrente,
			   p2.c60_descr as descr_conta,
			   c.k13_conta as corr_saltes,
			   b.k12_conta as corlanc,
			   p1.c60_descr as descr_receita,
			   coalesce(d.k13_conta,0) as corl_saltes,
			   k12_estorn,
			   d.k13_conta as conta_lanc
    from corrente
	          inner join corlanc b on corrente.k12_id = b.k12_id
			                               and corrente.k12_autent=b.k12_autent
			                               and corrente.k12_data = b.k12_data
			 left join saltes c   on c.k13_conta = corrente.k12_conta
			 left join saltes d   on d.k13_conta = b.k12_conta
			 inner join conplanoreduz r1 on b.k12_conta = r1.c61_reduz and r1.c61_anousu=".db_getsession("DB_anousu")."
			 inner join conplano      p1 on r1.c61_codcon = p1.c60_codcon and r1.c61_anousu = p1.c60_anousu 
			 inner join conplanoreduz r2 on corrente.k12_conta = r2.c61_reduz and r2.c61_anousu=".db_getsession("DB_anousu")."
			 inner join conplano      p2 on r2.c61_codcon = p2.c60_codcon and r2.c61_anousu = p2.c60_anousu 
	      	         left outer join coremp e on e.k12_data = corrente.k12_data and
	                                                    e.k12_id   = corrente.k12_id and
				                                        e.k12_autent = corrente.k12_autent
    where corrente.k12_instit = " . db_getsession("DB_instit") . " and 
          corrente.k12_data = '$data' and e.k12_empen is null
    group by corrente.k12_id,
	   corrente.k12_data,
	   corrente.k12_conta,
	   p2.c60_descr,
	   c.k13_conta,
	   b.k12_conta,
	   p1.c60_descr,
	   d.k13_conta,
	   k12_estorn
    order by corrente.k12_conta,
	     p2.c60_descr,
	     b.k12_conta,
	     p1.c60_descr
    ) as x where conta_lanc is null " ;
    $result = pg_query($sql);
    $clrotulo = new rotulocampo;

    if(pg_numrows($result)!=0){

      $processou = true;
      db_lovrot($sql,15,"()","","");
    }


   
 
}
if($processou==false){
  echo "<center>";
  echo "<strong>";
  echo "<table >
        <tr>
	<td>Nâo existem lançamentos nesta data.
	</td>
	</tr>
	<tr>
        <td>
	";
  if($tipo=="trans")
    echo "Transferncia Caixa/Bancos.";
  if($tipo=="recorc")
    echo "Receita Orçamentária";
  if($tipo=="recextra")
    echo "Receita Extra-Orçamentária";
  if($tipo=="despextra")
    echo "Despesa Extra-Orçamentária"; 
    
  echo 	"
	</td>
	</tr>
	</table>";
  echo "</strong>";
  echo "</center>";	

}
?>
</body>
</html>