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
include("classes/db_procfiscal_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clprocfiscal = new cl_procfiscal;
$clprocfiscal->rotulo->label("y100_sequencial");
$clprocfiscal->rotulo->label("y100_coddepto");

//echo "tipo = $tipo valor = $valor";
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <tr> 
    <td height="63" align="center" valign="top">
        <table width="35%" border="0" align="center" cellspacing="0">
	     <form name="form2" method="post" action="" >
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Ty100_sequencial?>">
              <?=$Ly100_sequencial?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		          db_input("y100_sequencial",10,$Iy100_sequencial,true,"text",4,"","chave_y100_sequencial");
		          ?>
            </td>
          </tr>
          <tr>
          	<td align="right" nowrap ><b>Trazer apenas registros ligados a origem:</b></td>
					  <td>
					  	<?
					  	  $arr_origem = array("S"=>"Sim","N"=>"Não");
	              db_select("origem",$arr_origem,true,2); 
							?>
					  </td>
					</tr>
					 <tr>
					 	<td align="right" ><b>Considerar:</b></td>
					  <td>
					  	<?
					  	  $arr_cons = array("A"=>"Aberto","E"=>"Encerrado","T"=>"Todos");
	              db_select("considerar",$arr_cons,true,2); 
							?>
					  </td>
					</tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_procfiscal.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
			$where=" where 1=1 ";
      if(!isset($pesquisa_chave)){
        if((isset($origem) and $origem=="S") or (!isset($origem) and (isset($tipo)) )){
	        $where .= " and $tipo = $valor ";
        }
				if((isset($considerar) and $considerar=="A") or  (!isset($considerar))){
					 $where .= " and aberto is null ";
				}
				if(isset($considerar) and $considerar=="E"){
					 $where .= " and aberto >1 ";
				}
				
        if(isset($chave_y100_sequencial) && (trim($chave_y100_sequencial)!="") ){
        	 $where .= " and y100_sequencial = $chave_y100_sequencial  ";
	        
        }
       				
				$sql = "
				
select y100_sequencial,y100_dtinicial, y101_numcgm,z01_nome ,y103_inscr,y102_matric,y104_codsani,
       depart_protocolo as db_depart_protocolo,descr_depart as db_descr_depart ,y100_coddepto as db_depart_atual ,
       case when aberto >1 then 'Encerrado'
            else 'Aberto'
       end as dl_situacao
   from( select distinct 
                y100_sequencial,
                y100_dtinicial,
                y101_numcgm,
								z01_nome,
                y103_inscr,
                y102_matric,
                y104_codsani,
								y100_coddepto,
                (select p61_coddepto 
                   from protprocesso
                        inner join procandam  on p58_codandam = p61_codandam
                        inner join procfiscalprot on y105_protprocesso = procandam.p61_codproc
                  where procfiscalprot.y105_procfiscal = procfiscal.y100_sequencial
                   
                ) as depart_protocolo,
								 (select descrdepto
                   from protprocesso
                        inner join procandam  on p58_codandam = p61_codandam
                        inner join procfiscalprot on y105_protprocesso = procandam.p61_codproc
                        inner join db_depart      on coddepto          = p61_coddepto
                  where procfiscalprot.y105_procfiscal = procfiscal.y100_sequencial
                ) as descr_depart,
                (select count(*)
                   from procfiscalfases as qtd
                  where qtd.y108_procfiscal = procfiscalfases.y108_procfiscal
                  group by y108_procfiscal having count(*) > 1 ) as aberto
           from procfiscal
		left  join procfiscalfases  on y108_procfiscal = y100_sequencial 
		inner join procfiscalcgm    on y101_procfiscal = y100_sequencial 
		inner join cgm              on y101_numcgm     = z01_numcgm
		left  join procfiscalmatric on y102_procfiscal = y100_sequencial 
		left  join procfiscalinscr  on y103_procfiscal = y100_sequencial
		left  join procfiscalsani   on y104_procfiscal = y100_sequencial
          where y100_coddepto = ".db_getsession("DB_coddepto")." 
					  and y100_instit   =  ".db_getsession("DB_instit")." ) as x
					$where	
		           ";
							
//			echo "$sql";				
				 $repassa = array();
        if(isset($chave_y100_coddepto)){
          $repassa = array("chave_y100_sequencial"=>$chave_y100_sequencial,"chave_y100_coddepto"=>$chave_y100_coddepto);
        }
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa,false);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
        	
						$sql = "
			select y100_sequencial,z01_nome ,depart_protocolo as db_depart_protocolo,descr_depart as db_descr_depart ,y100_coddepto as db_depart_atual 
			      
			   from( select distinct 
			                y100_sequencial,
			               	z01_nome,
			                y103_inscr,
			                y100_coddepto,
			                (select p61_coddepto 
			                   from protprocesso
			                        inner join procandam  on p58_codandam = p61_codandam
			                        inner join procfiscalprot on y105_protprocesso = procandam.p61_codproc
			                  where procfiscalprot.y105_procfiscal = procfiscal.y100_sequencial
			                   
			                ) as depart_protocolo,
											 (select descrdepto
			                   from protprocesso
			                        inner join procandam  on p58_codandam = p61_codandam
			                        inner join procfiscalprot on y105_protprocesso = procandam.p61_codproc
			                        inner join db_depart      on coddepto          = p61_coddepto
			                  where procfiscalprot.y105_procfiscal = procfiscal.y100_sequencial
			                ) as descr_depart

			           from procfiscal
									left  join procfiscalfases  on y108_procfiscal = y100_sequencial 
									inner join procfiscalcgm    on y101_procfiscal = y100_sequencial 
									inner join cgm              on y101_numcgm     = z01_numcgm
									left  join procfiscalmatric on y102_procfiscal = y100_sequencial 
									left  join procfiscalinscr  on y103_procfiscal = y100_sequencial
									left  join procfiscalsani   on y104_procfiscal = y100_sequencial
							          where y100_coddepto = ".db_getsession("DB_coddepto")." 
												  and y100_instit   =  ".db_getsession("DB_instit")."
													and y100_sequencial = $pesquisa_chave ) as x
								order by y100_sequencial";
//						echo "$sql"	;
							
					$result = pg_query($sql);
					$linhas = pg_num_rows($result);
					
					
          if($linhas!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$z01_nome',false,$db_depart_protocolo,'$db_descr_depart',$db_depart_atual  );</script>";
          }else{
	         echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
          }
        }else{
	       echo "<script>".$funcao_js."('',false);</script>";
        }
      }
      ?>
     </td>
   </tr>
</table>
</body>
</html>
<?
if(!isset($pesquisa_chave)){
  ?>
  <script>
  </script>
  <?
}
?>
<script>
js_tabulacaoforms("form2","chave_y100_coddepto",true,1,"chave_y100_coddepto",true);
</script>