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
include("classes/db_procandam_classe.php");
include("classes/db_procandamint_classe.php");
include("classes/db_proctransfer_classe.php");
include("classes/db_protprocesso_classe.php");
include("classes/db_proctransand_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);

$clprocandamint = new cl_procandamint;

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>
function js_imprime(){
   obj= document.form1;
   jan = window.open('pro2_relproc002.php?&processo='+obj.processo.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
   jan.moveTo(0,0);

}
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<form name=form1 action="">
   <center>
    <table bgcolor="#cccccc">
     <tr>
        <td>
        <? if (isset($codproc)){
              $sqlandam = "select p61_codandam as db_p61_codandam,
                                  p61_codproc,
                                  p61_dtandam,
				  p61_hora,
                                  nome,
                                  descrdepto,
				  coddepto,
                                  p61_Despacho
                           from   procandam inner join db_usuarios
                                  on p61_id_usuario = id_usuario
                                  inner join db_depart on p61_coddepto = coddepto 
             	           where p61_codproc = $codproc 
                           union 
                           select '0' as db_p61_codandam,
                                   p58_codproc, 
                                   p58_dtproc,
				   '' as hora,
                                   nome,
                                   descrdepto,
				   coddepto as coddepto,
                                   ' ' as p61_despacho
                            from   protprocesso inner join db_usuarios
                                   on p58_id_usuario = id_usuario
                                   inner join db_depart on p58_coddepto = coddepto
                           where   p58_codproc = $codproc order by db_p61_codandam";

         ?>
         <table border=1 cellspacing=0 style="border:1px solid black">
         <?
             
             // db_lovrot($sqlandam,10,"","","");
            $rs = pg_exec($sqlandam);  
            $j = 0;
            for ($i = 0;$i < pg_num_rows($rs);$i++){
	      $arquiv = false;
                if ($j % 2 == 0 ){
                    $cor = "bgcolor='#CCCCCC'";
                }else{
                    $cor = "bgcolor='#FFFFFF'";
                }
                                     
                db_fieldsmemory($rs,$i);
		if($i == 0){
		  $sqlrequer = "select  p58_requer,
		  			p58_obs,
		  		       	p58_dtproc,
					p58_hora,
					z01_nome,
					p51_descr,
					p58_despacho,
					nome
				from protprocesso
				inner join cgm on p58_numcgm = z01_numcgm
				inner join tipoproc on p51_codigo = p58_codigo 
				inner join db_usuarios on p58_id_usuario = id_usuario
				where p58_codproc = $p61_codproc";
		  $resrequer = pg_query($sqlrequer);
                  if(pg_numrows($resrequer) > 0){
		    db_fieldsmemory($resrequer,0);
		    echo "<tr>
			    <td nowrap><b>PROCESSO: </b></td>
			    <td nowrap>$p61_codproc 
			          <input type=button name=imprime  value=impressao onclick=js_imprime();>
			          <input type=hidden name=processo value=$p61_codproc >
			    </td>
			    <td ><b>NOME:</b> </td><td colspan='2' nowrap>$z01_nome</td>
			  </tr>
			  <tr>  
			      <td ><b>DATA:</b> </td><td nowrap>".db_formatar($p58_dtproc,'d')."</td>
			    <td ><b>HORA:</b> </td><td colspan='2' nowrap>$p58_hora&nbsp;</td>
			  </tr>
			  <tr>  
			    <td ><b>TIPO:</b> </td><td  nowrap>$p51_descr</td>
			    <td ><b>ATENDENTE:</b> </td><td colspan='2' nowrap>$nome</td>
			  </tr>
			  <tr>  
			    <td ><b>REQUERENTE:</b> </td><td colspan='5' nowrap>$p58_requer</td>
			  </tr>
			  <tr> 
			    <td  ><b>OBSERVAÇÃO:</b> </td><td colspan='5' nowrap>".($p58_obs == ""?"&nbsp;":nl2br($p58_obs))."</td>
			  </tr>  
			  ";
		    echo "
		    <tr><td colspan=7></td>
		    <tr>
		     <td colspan='7' align='center'><b>ANDAMENTO(S)</b></td>
		   </tr>
		   <tr>
		   <td colspan=7>
		   <table>
		   
		   <tr> 
		     <td bgcolor=\"#999999\" align=center><b>Data</b></td>
		     <td bgcolor=\"#999999\" align=center width=15><b>Hora</b></td>
		     <td bgcolor=\"#999999\" align=center width=25><b>Depto</b></td>
		     <td bgcolor=\"#999999\" align=center width=125><b>Setor</b></td>
		     <td bgcolor=\"#999999\" align=center width=40><b>Situação</b></td>
		     <td bgcolor=\"#999999\" align=center width=40><b>Tipo</b></td>
		     </tr>
		     
		     ";
		  }
		}
		$situacao = "<b>NORMAL</b>";
		if($i ==  (pg_num_rows($rs) - 1)){
		  $sql = "select a.descrdepto as deptoatual,
				 b.descrdepto as deptovai,
				 p63_codtran 
			  from proctransfer 
				 inner join db_depart a on p62_coddepto = a.coddepto 
				 inner join db_depart b on p62_coddeptorec = b.coddepto
				 inner join proctransferproc on p63_codtran = p62_codtran
				 where p63_codproc = $codproc and p63_codtran not in(select p64_codtran
				                                       from   proctransand order by p64_codtran)";
		  $res = pg_query($sql);
		  if(pg_numrows($res) > 0){
		    db_fieldsmemory($res,0);
		    //$situacao = "<b>EM TRANSFERÊNCIA PARA $deptovai - CODTRANS: $p63_codtran</b>";
		    $situacao = "<b>EM TRANSFERÊNCIA </b>";
		  }else{
		    $situacao = "<b>NORMAL</b>";
		  }
		  $sql = "select * from procarquiv 
		  	  inner join arqproc on p68_codarquiv = p67_codarquiv
			  inner join db_usuarios on p67_id_usuario = id_usuario 
			  inner join db_depart on p67_coddepto = coddepto 
			  where p67_codproc = $p61_codproc";
		  $res = pg_query($sql);
		  if(pg_numrows($res) > 0){
		    db_fieldsmemory($res,0);
		    $situacao = "<b>ARQUIVADO</b>";
		    $arquiv = true;
		  }
	        }
		if($p61_despacho!=""){
                  echo "<tr>";
  		  echo "<td colspan='5'>"; 
                  echo $p61_despacho;
                  echo "</td>";
		  echo "</tr>"; 
		}
                echo "<tr>";
                echo "<td $cor nowrap>".db_formatar($p61_dtandam,"d")."</td>";    
                echo "<td $cor nowrap>".$p61_hora."</td>";    
                echo "<td $cor nowrap>".$coddepto."</td>"; 
                echo "<td $cor nowrap>".$descrdepto."</td>"; 
                echo "<td $cor nowrap>".$situacao."</td>"; 
                echo "<td $cor nowrap>Normal</td>"; 
	       echo "</tr>"; 
		if($arquiv == true){
                  echo "<tr>";
  		  echo "<td colspan='5'>"; 
                  echo "Código do arquivamento: ".$p67_codarquiv; 
                  echo "</td>";
		  echo "</tr>"; 
		}
		$result_andamint=$clprocandamint->sql_record($clprocandamint->sql_query_file(null,"*",null,"p78_codandam=$db_p61_codandam"));
		for($y=0;$y<$clprocandamint->numrows;$y++){
		
		    echo "<tr>";
		    echo "<td $cor nowrap>".db_formatar($p78_data,"d")."</td>";    
		    echo "<td $cor nowrap>".$p78_hora."</td>";    
		    echo "<td $cor nowrap>".$coddepto."</td>"; 
		    echo "<td $cor nowrap>".$descrdepto."</td>"; 
		    echo "<td $cor nowrap></td>"; 
		    echo "<td $cor nowrap>INTERNO</td>"; 
		    echo "</tr>"; 
		    
		
		}
                $j++;
          }
                if(@$deptovai!=""){
                  echo "<tr>";
	  	  echo "<td><b> Destino:</b> </td><td>".$deptovai."</td><td><b>Transferência:</b></td><td colspan='2'>".$p63_codtran."</td>";
                  echo "</td>";
                  echo "</tr>";
		}
	  
                echo "<tr>
		        <td cgcolor='#CCCCCC' colspan='5'><b>DESPACHO: </b>
                          ".nl2br($p58_despacho)."
                        </td>
		      </tr>";
	  $sql = "select r.k00_numpre as recibo,
	  		 rp.k00_numpre as arrepaga,
			 sum(r.k00_valor) as k00_valor,
			 rp.k00_dtpaga,r.k00_dtvenc,r.k00_dtoper 
		  from arreproc 
		  	 inner join recibo r on r.k00_numpre = k80_numpre 
			 left join arrepaga rp on rp.k00_numpre = k80_numpre 
		  where k80_codproc = $codproc group by r.k00_numpre, rp.k00_numpre,rp.k00_dtpaga,r.k00_dtvenc,r.k00_dtoper";
	  $res = pg_query($sql);
	  if(pg_numrows($res) > 0){
	    echo"
	          </table>
	        </td>
	      </tr>
	     <tr>
	       <td colspan='7' align='center'><br><b>RECIBO(S)</b></td>
	     </tr>
	     <tr> 
	       <td bgcolor=\"#999999\" align=center><b>Situação</b></td>
	       <td bgcolor=\"#999999\" align=center><b>Numpre</b></td>
	       <td bgcolor=\"#999999\" align=center width=125><b>Valor</b></td>
	       <td bgcolor=\"#999999\" align=center width=125><b>Data de Operação</b></td>
	       <td bgcolor=\"#999999\" align=center width=125><b>Data de Vencimento</b></td>
	       <td bgcolor=\"#999999\" align=center width=\"125\"><b>Data de Pagamento</b></td>
	     </tr>
	     ";
            for ($i = 0;$i < pg_num_rows($res);$i++){
	      db_fieldsmemory($res,$i);
	      echo "
	       <tr> 
		 <td bgcolor=\"#CCCCCC\" align=center nowrap><b>".($arrepaga == ""?"RECIBO À PAGAR":"RECIBO PAGO")."</b></td>
		 <td bgcolor=\"#CCCCCC\" align=center><b>$recibo</b></td>
		 <td bgcolor=\"#CCCCCC\" align=center width=125><b>".db_formatar($k00_valor,'f')."</b></td>
		 <td bgcolor=\"#CCCCCC\" align=center width=125><b>".db_formatar($k00_dtoper,'d')."</b></td>
		 <td bgcolor=\"#CCCCCC\" align=center width=125><b>".db_formatar($k00_dtvenc,'d')."</b></td>
		 <td bgcolor=\"#CCCCCC\" align=center width=125><b>".($k00_dtpaga != ""?db_formatar($k00_dtpaga,'d'):"&nbsp;")."</b></td>
	       </tr>
	       ";
	    }
	  }
         }
        ?>
       </table>
        </td>
     </tr>
    </table>
    </center>
  </form>  
</body>
</html>