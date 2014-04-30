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

$result = pg_exec("select o.codordem, 
                          to_char(o.dataordem,'DD/MM/YYYY') as dataordem, 
			  o.descricao, 
			  o.id_usuario, 
			  o.usureceb, 
			  o.coddepto,
		  	  p.descrdepto, 
			  u.nome, 
			  no.nome as nomeusureceb, 
			  to_char(o.dataprev,'DD/MM/YYYY') as dataprev, 
			  o.dtrecebe,
			  o.status
    	           from db_ordem o
			   inner join db_depusu d  on d.coddepto = o.coddepto
			   inner join db_depart p  on p.coddepto = o.coddepto
			   inner join db_usuarios u on u.id_usuario = o.id_usuario
			   left outer join db_usuarios no on no.id_usuario = o.usureceb
		   where o.codordem = $cod_ord_and
		   
		   "
		   
		   );
$sql02 = "
	        select nome_modulo
		from db_ordemmod 
		   inner join db_modulos on db_modulos.id_item = db_ordemmod.id_item
		where db_ordemmod.codordem= $cod_ord_and;
	   ";
$result02 = pg_query($sql02);
$numrows02 = pg_numrows($result02);
echo "     <br>
		<table width=80% border=1 align=\"center\" cellpadding=0 cellspacing=0>
		  <tr> 
			<td width=\"10%\"   align=\"center\" nowrap bgcolor=\"#CDCDFF\" style=\"font-size:13px\">ordem 
			  :<strong> ".pg_result($result, 0, "codordem")."</strong>
			</td>
			<td width=\"18%\"   align=\"center\" nowrap bgcolor=\"#CDCDFF\" style=\"font-size:13px\">Data 
			  <strong>: ".pg_result($result, 0, "dataordem")."</strong>
	       	        </td>
			<td width=\"22%\"   align=\"center\" nowrap bgcolor=\"#CDCDFF\" style=\"font-size:13px\">Previsão 
			  <strong>: ".pg_result($result, 0, "dataprev")."</strong>
			</td>
			<td width=\"50%\"   align=\"center\" nowrap bgcolor=\"#CDCDFF\" style=\"font-size:13px\">Destinat&aacute;rio 
			  <strong>:".pg_result($result, 0, "nomeusureceb")." </strong>
		        </td>
			<td>
			  <b>Módulo:</b>
			</td>
			  
		  </tr>
		  <tr> 
			<td colspan=\"3\" style=\"font-size:13px\"><strong>Cadastrado por: </strong>".pg_result($result, 0, "nome")."</td>
			<td style=\"font-size:13px\"><strong>Departamento: </strong>".pg_result($result, 0, "descrdepto")."</td>
		        <td>";
if ($numrows02 > 0) {
	for ($i = 0; $i < $numrows02; $i ++) {
		db_fieldsmemory($result02, $i);
		echo $nome_modulo."<br>";
	}
} else {
	echo "Todos";
}

@$status = pg_result($result,0,"status");

echo "</td>
       <form name=form1 method=post >

      <td>";  
       $mtr = array ();
       /* $mtr[1] = "Em Desenvolvimento";
       $mtr[2] = "Liberada P/Teste";  */
       $mtr[3] = "Retorno/Teste";
       $mtr[4] = "Aguardando Conclusão";     
       db_select('status',$mtr,false,1);
echo " </td>
      </tr></table>";

////////////////////
// formulario de entrada de dados
  $resultPesquisaNome = pg_exec("select nome from db_usuarios where id_usuario = $DB_id_usuario");
  $nomeUsuario = pg_result($resultPesquisaNome,0,0); 

  $departamentos = pg_exec("select * from db_depart");
  $numeroDepartamentos = pg_numrows($departamentos);

    echo "\n\n<script>\n";
    for ($i=0;$i<$numeroDepartamentos;$i++) {
	  $usu =  pg_exec("select us.id_usuario, us.nome 
	                   from db_usuarios us
        	           inner join db_depusu du 
		        	   on du.id_usuario = us.id_usuario
        			   where coddepto = ".pg_result($departamentos,$i,"coddepto"));
	  $numusu = pg_numrows($usu);
	  $aux= '';
	  $c = '';
 	  echo strtolower(str_replace(" ","_",pg_result($departamentos,$i,"descrdepto")))." = new Array(";
      for($j=0;$j<$numusu;$j++) {
        $aux .= "$c'".pg_result($usu,$j,"nome")."'";
		$c = ",";
	  }
	  echo $aux.");\n";
    }
    ?>
	function vai(obj) {
	  for(var i = 0;i < document.form1.usuarioescolhido.length;i++)
	    document.form1.usuarioescolhido.options[i] = null;
	
	  for(var i = 0;i < obj.length;i++)
	   document.form1.usuarioescolhido.options[i] = new Option(obj[i], obj[i], false, false);
	}
	<?
    echo "\n</script>\n\n";
  
  $dtrecebe = pg_result($result, 0, "dtrecebe");
?>
  <table width=200px  border="1" align="center" cellpadding="0" cellspacing="0" align="center">
    <tr>
      <td colspan="3" nowrap ><input name="codordem"  value="<? echo $cod_ord_and ?>" type="hidden" id="codordem2">
      </td>
    </tr>
    <tr> 
      <td colspan="3" nowrap bgcolor="#CDCDFF"  style="font-size:13px" align="center" >
      <div align="center">
        <strong>Inclusão de Andamento</strong></div></td>
    </tr>
    <tr> 
      <td colspan="3" nowrap >
          <table width="100%" height="100%" border="1" cellpadding="0" cellspacing="0">
          <tr> 
            <td width="12%" nowrap style="font-size:13px" >Data inicial:</td>
            <td width="34%" nowrap  style="font-size:13px"> 
             <? 
		  db_data("dtini",date("d"),date("m"),date("Y"));
		  ?>
            </td>
            <td width="15%" nowrap style="font-size:13px">Hor&aacute;rio inicial:</td>
            <td width="39%" nowrap style="font-size:13px">
	       <input name="hrini" type="text" id="hrini" size="10" maxlength="5">
	     </td>
          </tr>
          <tr> 
            <td nowrap  style="font-size:13px">Data final:</td>
            <td nowrap  style="font-size:13px"> 
              <? 
		  db_data("dtfim",date("d"),date("m"),date("Y"));
		  ?>
            </td>
            <td nowrap  style="font-size:13px">Horário final:</td>
	    
            <td><input name="hrfim" type="text" value="<?=db_hora(db_getsession('DB_datausu'))?>" id="hrfim" size="10" maxlength="5"></td>
          </tr>
          <tr> 
            <td colspan="4" nowrap style="font-size:13px"><table width="100%" border="1" cellspacing="0" cellpadding="0">
                <tr> 
                  <td colspan="2" nowrap style="font-size:13px" ><div align="center">Usu&aacute;rio que receber&aacute; 
                      a ordem servi&ccedil;o:</div></td>
                </tr>
                <tr> 
                  <td width="46%" nowrap style="font-size:13px" ><div align="right">Departamento:</div></td>
                  <td width="54%" nowrap style="font-size:13px" >
				  <select name="depto" id="depto" onChange="vai(eval(this.options[this.selectedIndex].value))">
				  <? 
				    $descratual = pg_result($result,0,"descrdepto");
					$listaDepartamentos = pg_exec("select * from db_depart where descrdepto not like '$descratual'");
					$numdep = pg_numrows($listaDepartamentos);
 				    echo "<option selected value=\"".strtolower(str_replace(" ","_",pg_result($result,0,"descrdepto")))."\">".pg_result($result,0,"descrdepto")."</option>";
					for ($i=0;$i<$numdep;$i++) {
					  echo "<option value=\"".strtolower(str_replace(" ","_",pg_result($listaDepartamentos,$i,"descrdepto")))."\">".pg_result($listaDepartamentos,$i,"descrdepto")."</option>";
					}
				  ?> 
                  </select>
				  </td>
                </tr>
                <tr> 
  <td nowrap style="font-size:13px" ><div align="right">Usuario:</div></td>
  <td nowrap style="font-size:13px" >
  <select name="usuarioescolhido" id="select">
  <? 
		
	$coddepartamento = pg_result($result,0,"coddepto");
	$nome = pg_result($result,0,"nomeusureceb");
	if ($nome=="") {$nome=$nomeUsuario;}
	$listanomes = pg_exec("select u.id_usuario , d.nome
                     from db_depusu u 
			 inner join db_usuarios d
			 on d.id_usuario = u.id_usuario
		 where u.coddepto = $coddepartamento
		 ");
	$numnomes = pg_numrows($listanomes); 
	for ($i=0;$i<$numnomes;$i++) {
  if (pg_result($listanomes,$i,"nome")==$nome) 
      {$estado="selected";
    echo "<option ".$estado." value=\"".pg_result($listanomes,$i,"nome")."\">".pg_result($listanomes,$i,"nome")."</option>";
     
      } else {$estado="";}
      
//   echo "<option ".$estado." value=\"".pg_result($listanomes,$i,"nome")."\">".pg_result($listanomes,$i,"nome")."</option>";
	}
?>                    
		
   </select></td>
                </tr>
              </table></td>
          </tr>
          <tr> 
            <td colspan="4" nowrap style="font-size:13px">Descri&ccedil;&atilde;o:</td>
          </tr>
	  <?
		  if($dtrecebe!=null){
	  ?>
          <tr> 
            <td colspan="4" nowrap style="font-size:13px">             
                <textarea name=descr cols=100 rows=5 id=descr></textarea>
            </td>
          </tr>
	   <?
	        }   
	   ?>
        </table>
        </td>
    </tr>
    <tr> 
      <td nowrap ><div align="center"> 
	      <?
		  if($dtrecebe!=null){
		  ?>
          <input name="incluir" type="submit" id="incluir" value="Incluir Andamento">
		  <?
		  }
		  ?>
        </div></td>
      <td width="232" nowrap ><div align="center"> 
	      <?
		  if($dtrecebe!=null){
		  ?>
          <input name="finaliza" type="submit" id="finaliza" value="Finalizar esta ordem" onClick="return confirm('Quer realmente finalizar esta ordem?')">
		  <?
		  }else{
		  ?>
          <input name="recebe" type="submit" id="recebe" value="Confirma Recebimento" >
		  <?
		  }
		  ?>
        </div></td>
      <td width="247" nowrap ><div align="center"> 
          <input name="cancela" type="submit" id="cancela" value="Cancelar">
        </div></td>
    </tr>
  </table>
  </form>

<?

////////////////////
// lista o primeiro andamento antes
  
  echo "<table border=1 align=center width=90%>   
	 	    <tr> 
		  	<td colspan=\"5\" style=\"font-size:13px\"><strong>Descrição:<br>
			   </strong>TEXTO DA ORDEM :     ".str_replace("\n", "<br> ", pg_result($result, 0, "descricao"))."</td>
		    </tr>		  		
       ";

// aqui trazer todos os andamentos da ordem em questao
$selecionaAndamento = pg_exec(
                       "select o.id_usuario, 
                               o.codandam, 
                               o.codordem, 
                               o.descricao,
		               to_char(o.dtini,'DD/MM/YYYY') as datainicial, 
		               to_char(o.dtfim,'DD/MM/YYYY') as datafinal,
                               u.nome as nome_andamento
                        from db_ordemandam o
			     inner join db_usuarios u on u.id_usuario = o.id_usuario
  			where o.codordem = $cod_ord_and
		        order by codandam
								");
  $numSelecionaAndamento = pg_numrows($selecionaAndamento);
  
  
  
  for ($i=0;$i<$numSelecionaAndamento;$i++) {
  	 $and     = pg_result($selecionaAndamento,$i,"codandam");
     $ordem = pg_result($selecionaAndamento,$i,"codordem");
     $nome_and = pg_result($selecionaAndamento,$i,"nome_andamento");
     $dt       =  pg_result($selecionaAndamento,$i,"datainicial");
     $descricao = str_replace("\n","<br>",pg_result($selecionaAndamento,$i,"descricao"));
	
     echo "
		  <tr> 
			<td colspan=5 style=font-size:13px><strong>Descrição :   Recebida em $dt   $nome_and <br>
			    </strong>$descricao
            </td>
		  </tr>		  
       ";
  }
     
  echo "</table>";
  
  
?>