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
 
  db_getsession(); 
  $resultPesquisaNome = pg_exec("select nome from db_usuarios where id_usuario = $DB_id_usuario");
  $nomeUsuario = pg_result($resultPesquisaNome,0,0);
  
  //seleciona todos os departamentos e verifica o total de registros encontrados
  $departamentos = pg_exec("select * from db_depart");
  $numeroDepartamentos = pg_numrows($departamentos);

  //para cada departamento encontrado verifica quais sao seus usuarios e separa em arrays
  //se tiver ao menos um usuario encontrado na pesquisa anterior

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
  
?>
  <form name="form1" method="post">
  <table width="100%" height="100%" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td colspan="3" nowrap ><input name="codordem"  value="<? echo $cod_ord_and ?>" type="hidden" id="codordem2">
      </td>
    </tr>
    <tr> 
      <td colspan="3" width="8%" nowrap bgcolor="#CDCDFF"  style="font-size:13px" align="center" ><div align="center"><strong>Inclus&atilde;o 
          de Andamento</strong></div></td>
    </tr>
    <tr> 
      <td colspan="3" nowrap ><table width="100%" height="100%" border="1" cellpadding="0" cellspacing="0">
          <tr> 
            <td width="12%" nowrap style="font-size:13px" >Data inicial:</td>
            <td width="34%" nowrap  style="font-size:13px"> 
             <? include ("dbforms/db_funcoes.php") ;
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
            <td nowrap  style="font-size:13px">Hor&aacute;rio final:</td>
	    
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
                  <td nowrap style="font-size:13px" ><select name="usuarioescolhido" id="select">
                    <? 
					
					$coddepartamento = pg_result($result,0,"coddepto");
					$nome = pg_result($result,0,"nomeusureceb");
					if ($nome=="") {$nome=$nomeUsuario;}
					$listanomes = pg_exec("select u.id_usuario , d.nome
				                     from db_depusu u 
									 inner join db_usuarios d
									 on d.id_usuario = u.id_usuario
									 where u.coddepto = $coddepartamento");
					$numnomes = pg_numrows($listanomes); 
					for ($i=0;$i<$numnomes;$i++) {
					  if (pg_result($listanomes,$i,"nome")==$nome) {$estado="selected";} else {$estado="";}
					  echo "<option ".$estado." value=\"".pg_result($listanomes,$i,"nome")."\">".pg_result($listanomes,$i,"nome")."</option>";
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
            <td colspan="4" nowrap style="font-size:13px"><div align="center"> 
                <textarea name="descr" cols="80" rows="5" id="descr"></textarea>
              </div></td>
          </tr>
	   <?
	            }   
	   ?>
        </table></td>
    </tr>
    <tr> 
      <td width="249" nowrap ><div align="center"> 
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