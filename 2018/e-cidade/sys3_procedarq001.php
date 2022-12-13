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
include("classes/db_solicitem_classe.php");
include("classes/db_solicitemprot_classe.php");
include("classes/db_solicita_classe.php");
include("classes/db_pcparam_classe.php");
include("classes/db_pcorcamitemsol_classe.php");
include("classes/db_solandam_classe.php");
include("classes/db_solandpadraodepto_classe.php");
db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_GET_VARS);
$clsolicitem = new cl_solicitem;
$clsolicitemprot = new cl_solicitemprot;
$clsolicita = new cl_solicita;
$clpcparam = new cl_pcparam;
$clpcorcamitemsol = new cl_pcorcamitemsol;
$clsolandam = new cl_solandam;
$clsolandpadraodepto = new cl_solandpadraodepto;
$db_opcao=1;
$db_botao=true;
if(isset($solicita) && trim($solicita)!=""){
  $where_liberado = "";
  $selecionalibera = $clpcparam->sql_record($clpcparam->sql_query_file(null,"pc30_liberado,pc30_contrandsol"));
  if($clpcparam->numrows>0){
    db_fieldsmemory($selecionalibera,0);
    if($pc30_liberado=='f'){
      $where_liberado = " and pc11_liberado='t' ";
    }
  }
//  $select_itens = $clsolicitem->sql_record($clsolicitem->sql_query_pcmater(null,"pc11_numero,pc11_codigo,pc11_quant,pc11_seq,pc11_vlrun,pc11_resum,pc01_codmater,pc01_descrmater","pc11_codigo","pc11_numero=$solicita and pc11_codigo not in (select distinct pc81_solicitem from pcprocitem) $where_liberado "));
  $select_itens = $clsolicitem->sql_record($clsolicitem->sql_query_pcmater(null,"pc11_numero,pc11_codigo,pc11_quant,pc11_seq,pc11_vlrun,pc11_resum,pc01_codmater,pc01_descrmater,pc01_servico,pc17_unid,pc17_quant,m61_descr,m61_usaquant","pc11_codigo","pc11_numero=$solicita and pc11_codigo not in (select distinct pc81_solicitem from pcprocitem) $where_liberado "));
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_setornoset(campo,SN){
  cont = 0;
  if(SN==true){
    for(i=0;i<top.corpo.arr_dados.length;i++){
      if(top.corpo.arr_dados[i]==campo){
	cont++;
	break;
      }
    }
  }else{
    cont = SN;
  }

  if(eval('document.form1.'+campo+'.checked')==true){
    if(cont==0){
      top.corpo.arr_dados.push(campo);
    }
  }else{
    if(cont>0){
      top.corpo.arr_dados.splice(i,1);
    }
  }
  top.corpo.document.form1.valores.value = top.corpo.arr_dados.valueOf();
}
function js_setornosetimp(campo,SN){
  cont = 0;
  if(SN==true){
    for(i=0;i<top.corpo.arr_impor.length;i++){
      if(top.corpo.arr_impor[i]==campo){
	cont++;
	break;
      }
    }
  }else{
    cont = 0;
  }

  if(eval('document.form1.'+campo+'.checked')==true){
    if(cont==0){
      top.corpo.arr_impor.push(campo);
    }
  }else{
    if(cont>0){
      top.corpo.arr_impor.splice(i,1);
    }
  }
  top.corpo.document.form1.importa.value = top.corpo.arr_impor.valueOf();
}
function js_marcacampos(){
  erro=0;
  campo = "";
  for(i=0;i<document.form1.length;i++){
    if(document.form1.elements[i].type == 'checkbox' && document.form1.elements[i].name.search('imp')==-1){
      if(top.corpo.document.form1.valores.value.search(document.form1.elements[i].name)!=-1){
        document.form1.elements[i].checked = true;
	campo = document.form1.elements[i].name;
	for(ii=0;ii<top.corpo.arr_dados.length;ii++){
	  if(top.corpo.arr_dados[ii]==campo){
	    erro++;
	    break;
	  }
	}
	if(erro==0 && campo!=""){
	  top.corpo.arr_dados.push(campo);
	}
      }
    }
  } 
}
function js_marcacamposimp(){
  for(i=0;i<document.form1.length;i++){
    if(document.form1.elements[i].type == 'checkbox'){
      if(top.corpo.document.form1.importa.value.search(document.form1.elements[i].name)!=-1){
        document.form1.elements[i].checked = true;
      }
    }
  } 
}
function js_marcar(){
  for(i=0;i<document.form1.length;i++){
    cont = 0;
    if(document.form1.elements[i].type == 'checkbox' && document.form1.elements[i].disabled==false && document.form1.elements[i].name.search('imp')==-1){
      for(ii=0;ii<top.corpo.arr_dados.length;ii++){
	if(top.corpo.arr_dados[ii]==document.form1.elements[i].name){
	  cont++;
	  break;
	}
      }
      if(document.form1.elements[i].checked == true){
        document.form1.elements[i].checked = false;
	eval('js_setornoset("'+document.form1.elements[i].name+'",'+cont+');');
      }else{
        document.form1.elements[i].checked = true;	
	eval('js_setornoset("'+document.form1.elements[i].name+'",'+cont+');');
      }
    }
  } 
}
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style>
.bordas02{
         border: 2px solid #cccccc;
         border-top-color: #999999;
         border-right-color: #999999;
         border-left-color: #999999;
         border-bottom-color: #999999;
         background-color: #999999;
}
.bordas{
         border: 1px solid #cccccc;
         border-top-color: #999999;
         border-right-color: #999999;
         border-left-color: #999999;
         border-bottom-color: #999999;
         background-color: #cccccc;
}
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<form name="form1">
<table border="0" cellspacing="0" cellpadding="0" width="100%">
  <tr> 
    <td align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
    <?
    if($clsolicitem->numrows==0){
      echo "<strong>N�o existem itens para esta solicita��o.</strong>\n";
    }else{
      echo "<center>";
      echo "<table border='1' align='center'>\n";
      echo "<tr>";
      echo "  <td colspan='11' nowrap><strong><font size='3'>Itens da solicita��o</font></strong></td>";
      echo "</tr>";
      echo "<tr bgcolor=''>\n";
      echo "  <td nowrap class='bordas02' align='center'><strong>";db_ancora('M','js_marcar();',1);echo"</strong></td>\n";
      echo "  <td nowrap class='bordas02' align='center'><strong>Sequencial</strong></td>\n";
      echo "  <td nowrap class='bordas02' align='center'><strong>Item</strong></td>\n";
      echo "  <td nowrap class='bordas02' align='center'><strong>Quantidade</strong></td>\n";
      echo "  <td nowrap class='bordas02' align='center'><strong>Valor Unit.</strong></td>\n";
      echo "  <td nowrap class='bordas02' align='center'><strong>Valor Tot.</strong></td>\n";
      echo "  <td nowrap class='bordas02' align='center'><strong>Refer�ncia</strong></td>\n";      
      echo "  <td nowrap class='bordas02' align='center'><strong>Codigo</strong></td>\n";
      echo "  <td nowrap class='bordas02' align='center'><strong>Material</strong></td>\n";
      echo "  <td nowrap class='bordas02' align='center'><strong>Resumo</strong></td>\n";
      echo "  <td nowrap class='bordas02' align='center' title='Gerar, automaticamente, or�amento do processo de compras para este item, com base no or�amento de solicita��o.'><strong>Imp</strong></td>\n";
      echo "</tr>\n";
      $readonly="";     
      for($i=0;$i<$clsolicitem->numrows;$i++){	
	if($i%2==0){$bgcolor = '#ccddcc';}else{$bgcolor = '#aacccc';}
	db_fieldsmemory($select_itens,$i,true);
	$readonly="";

	
	
	//---------------------------------------Controla Andamento da solicita��o---------------------------------
	$result_conand = $clpcparam->sql_record($clpcparam->sql_query_file(db_getsession("DB_instit"),"pc30_contrandsol"));
	db_fieldsmemory($result_conand,0);
	if (isset($pc30_contrandsol)&&$pc30_contrandsol=='t'){
		$result_prot = $clsolicitemprot->sql_record($clsolicitemprot->sql_query_file(null,"*",null,"pc49_solicitem = $pc11_codigo"));
		if ($clsolicitemprot->numrows>0){
		$result_andam=$clsolandam->sql_record($clsolandam->sql_query_file(null,"*","pc43_ordem desc limit 1","pc43_solicitem=$pc11_codigo"));
      	if ($clsolandam->numrows>0){
      		db_fieldsmemory($result_andam,0);
      	    $result_tipo=$clsolandpadraodepto->sql_record($clsolandpadraodepto->sql_query(null,"*",null,"pc47_solicitem=$pc11_codigo and pc47_ordem=$pc43_ordem"));
      	    if($clsolandpadraodepto->numrows>0){
      	    	db_fieldsmemory($result_tipo,0);
      	      	if ($pc47_pctipoandam!=3||$pc48_depto!=db_getsession("DB_coddepto")){
      	      		 $sqltran = "select distinct x.p62_codtran,                   
      x.pc11_numero,
x.pc11_codigo,
                            x.p62_dttran, 
                            x.p62_hora, 
                			x.descrdepto, 
							x.login
			from ( select distinct p62_codtran, 
                          p62_dttran, 
                          p63_codproc,                          
                          descrdepto, 
                          p62_hora, 
                          login,
                          pc11_numero,
							pc11_codigo,
                          pc81_codproc,
                          e55_autori,
							e54_anulad 
		           from proctransferproc
                         
                        inner join solicitemprot on pc49_protprocesso = proctransferproc.p63_codproc
                        inner join solicitem on pc49_solicitem = pc11_codigo
                        inner join proctransfer on p63_codtran = p62_codtran
						inner join db_depart on coddepto = p62_coddepto
						inner join db_usuarios on id_usuario = p62_id_usuario
						left join pcprocitem on pcprocitem.pc81_solicitem = solicitem.pc11_codigo
						left join empautitem on empautitem.e55_sequen = pcprocitem.pc81_codprocitem
						left join empautoriza on empautoriza.e54_autori= empautitem.e55_autori  
             			where  p62_coddeptorec = ".db_getsession("DB_coddepto")."
                 ) as x
				 left join proctransand 	on p64_codtran = x.p62_codtran
				 left join arqproc 	on p68_codproc = x.p63_codproc
			where p64_codtran is null and p68_codproc is null and x.pc11_codigo = $pc11_codigo";
			
			$result_tran=pg_exec($sqltran);
			if(pg_numrows($result_tran)==0){
      	        	$readonly = "disabled";
			}
        	    }
      	    }  	
      	}
      	$result_=$clsolicita->sql_record($clsolicita->sql_query_andsol("distinct pc11_numero,pc11_codigo,pc11_quant,pc11_seq,pc11_vlrun,pc11_resum,pc01_codmater,pc01_descrmater,pc01_servico,pc17_unid,pc17_quant,m61_descr,m61_usaquant","where pc11_codigo=$pc11_codigo and  p64_codtran is not null  and y.pc43_depto=".db_getsession("DB_coddepto")));
		if ($clsolicita->numrows==0){
			 $sqltran = "select distinct x.p62_codtran,                   
      x.pc11_numero,
x.pc11_codigo,
                            x.p62_dttran, 
                            x.p62_hora, 
                			x.descrdepto, 
							x.login
			from ( select distinct p62_codtran, 
                          p62_dttran, 
                          p63_codproc,                          
                          descrdepto, 
                          p62_hora, 
                          login,
                          pc11_numero,
							pc11_codigo,
                          pc81_codproc,
                          e55_autori,
							e54_anulad 
		           from proctransferproc
                         
                        inner join solicitemprot on pc49_protprocesso = proctransferproc.p63_codproc
                        inner join solicitem on pc49_solicitem = pc11_codigo
                        inner join proctransfer on p63_codtran = p62_codtran
						inner join db_depart on coddepto = p62_coddepto
						inner join db_usuarios on id_usuario = p62_id_usuario
						left join pcprocitem on pcprocitem.pc81_solicitem = solicitem.pc11_codigo
						left join empautitem on empautitem.e55_sequen = pcprocitem.pc81_codprocitem
						left join empautoriza on empautoriza.e54_autori= empautitem.e55_autori  
             			where  p62_coddeptorec = ".db_getsession("DB_coddepto")."
                 ) as x
				 left join proctransand 	on p64_codtran = x.p62_codtran
				 left join arqproc 	on p68_codproc = x.p63_codproc
			where p64_codtran is null and p68_codproc is null and x.pc11_codigo = $pc11_codigo";
			
			$result_tran=pg_exec($sqltran);
			if(pg_numrows($result_tran)==0){
			$readonly="disabled";
			}
		}
	  	$result_transf = $clsolicitemprot->sql_record($clsolicitemprot->sql_query_transf(null,"*",null,"pc49_solicitem = $pc11_codigo and p64_codtran is null"));
    	if ($clsolicitemprot->numrows>0){
    		 $sqltran = "select distinct x.p62_codtran,                   
      x.pc11_numero,
x.pc11_codigo,
                            x.p62_dttran, 
                            x.p62_hora, 
                			x.descrdepto, 
							x.login
			from ( select distinct p62_codtran, 
                          p62_dttran, 
                          p63_codproc,                          
                          descrdepto, 
                          p62_hora, 
                          login,
                          pc11_numero,
							pc11_codigo,
                          pc81_codproc,
                          e55_autori,
							e54_anulad 
		           from proctransferproc
                         
                        inner join solicitemprot on pc49_protprocesso = proctransferproc.p63_codproc
                        inner join solicitem on pc49_solicitem = pc11_codigo
                        inner join proctransfer on p63_codtran = p62_codtran
						inner join db_depart on coddepto = p62_coddepto
						inner join db_usuarios on id_usuario = p62_id_usuario
						left join pcprocitem on pcprocitem.pc81_solicitem = solicitem.pc11_codigo
						left join empautitem on empautitem.e55_sequen = pcprocitem.pc81_codprocitem
						left join empautoriza on empautoriza.e54_autori= empautitem.e55_autori  
             			where  p62_coddeptorec = ".db_getsession("DB_coddepto")."
                 ) as x
				 left join proctransand 	on p64_codtran = x.p62_codtran
				 left join arqproc 	on p68_codproc = x.p63_codproc
			where p64_codtran is null and p68_codproc is null and x.pc11_codigo = $pc11_codigo";
			
			$result_tran=pg_exec($sqltran);
			if(pg_numrows($result_tran)==0){
        	$readonly = " disabled ";
			}
        }      	
		}
	}	
	//--------------------------------------------------------------------------------------------------------------------------------------
	
	
	
        echo "<tr>\n";
	echo "  <td nowrap class='bordas'><input type='checkbox' $readonly  name='item_".$pc11_numero."_".$pc11_codigo."' onclick='js_setornoset(this.name,true);'></td>\n";
        echo "  <td nowrap class='bordas' align='center'>$pc11_seq</td>\n";
	echo "  <td nowrap class='bordas' align='center'>$pc11_codigo</td>\n";
	echo "  <td nowrap class='bordas' align='center'>$pc11_quant</td>\n";
	echo "  <td nowrap class='bordas' align='right'>R$ ".db_formatar($pc11_vlrun,"f")."</td>\n";
	echo "  <td nowrap class='bordas' align='right'>R$ ".db_formatar(($pc11_vlrun*$pc11_quant),"f")."</td>\n";

        if((isset($pc01_servico) && (trim($pc01_servico)=="f" || trim($pc01_servico)=="")) || !isset($pc01_servico)){
          $unid = trim(substr($m61_descr,0,10));
          if($m61_usaquant=="t"){
            $unid .= " <BR>($pc17_quant UNIDADES)";
          }
        }else{
          $unid = "SERVI�O";
        }

        echo "  <td nowrap class='bordas' align='center'>$unid</td>\n";
	echo "  <td nowrap class='bordas' align='center'>$pc01_codmater</td>\n";
	echo "  <td class='bordas' align='left'>  ".ucfirst(strtolower($pc01_descrmater))."</td>\n";
        if(isset($pc11_resum) && trim($pc11_resum)==""){
          $pc11_resum="&nbsp;";
        }
	echo "  <td class='bordas' bgcolor='$bgcolor' align='left'>".substr($pc11_resum,0,40)."</td>\n";
	$result_orcamitemsol = $clpcorcamitemsol->sql_record($clpcorcamitemsol->sql_query_solicitem(null,null,"pc22_codorc,pc22_orcamitem",""," pc29_solicitem=$pc11_codigo order by pc22_codorc desc")); 
	if($clpcorcamitemsol->numrows>0){
	  db_fieldsmemory($result_orcamitemsol,0);
	  $orcamitemsol = "<input type='checkbox' name='imp_".$pc22_codorc."_".$pc11_codigo."_".$pc22_orcamitem."' checked onclick='js_setornosetimp(this.name,true);'>";
	  if(trim($pc22_codorc)==""){
	    $orcamitemsol = "<strong>N�o</strong>";
	  }
	}else{
	  $orcamitemsol = "<strong>N�o</strong>";
	}
	echo "  <td nowrap class='bordas'>$orcamitemsol</td>\n";
        if($clpcorcamitemsol->numrows>0){
	  if(trim($pc22_codorc)!=""){
	    echo "<script>js_setornosetimp('imp_".$pc22_codorc."_".$pc11_codigo."_".$pc22_orcamitem."',false);</script>";
	  }
	}
        echo "</tr>\n";
      }
      echo "</table>\n";
      echo "</center>"; 
    }
    ?>
    </center>
    </td>
  </tr>
</table>
</form>
<script>
js_marcacampos();
js_marcacamposimp();
</script>
</body>
</html>