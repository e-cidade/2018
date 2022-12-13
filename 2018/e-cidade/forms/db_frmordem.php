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
?>
<script>
function js_ProtegeTextoEsc() {
  var evt = (evt) ? evt : (window.event) ? window.event : "";
  if(evt.keyCode == 27)
    return false;
}
</script>
<? 
  db_getsession(); 
$clrotulo = new rotulocampo;
$clrotulo->label("or10_codatend");
  $resultPesquisaNome = pg_exec("select nome from db_usuarios where id_usuario = $DB_id_usuario");
  $nomeUsuario = pg_result($resultPesquisaNome,0,0);
  
  //Implementação da troca dos items do menu departamento e usuário através de javaScript
  //seleciona todos os departamentos e verifica o total de registros encontrados
  $departamentos = pg_exec("select * from db_depart");
  $numeroDepartamentos = pg_numrows($departamentos);

  //para cada departamento encontrado verifica quais sao seus usuarios e separa em arrays
  //se tiver ao menos um usuario encontrado na pesquisa anterior

    echo "\n\n<script>\n";
	// Carrega a array com a lista de nomes de usuarios em cada array com  nome do setor
    for ($i=0;$i<$numeroDepartamentos;$i++) {
	  $usu =  pg_exec("select us.id_usuario, us.nome 
	                   from db_usuarios us
        	           inner join db_depusu du 
		        	   on du.id_usuario = us.id_usuario
        			   where coddepto = ".pg_result($departamentos,$i,"coddepto"));
	  $numusu = pg_numrows($usu);
	  $aux= '';
	  $c = '';
 	  echo "a".pg_result($departamentos,$i,"coddepto")." = new Array(";
      for($j=0;$j<$numusu;$j++) {
        $aux .= "$c'".pg_result($usu,$j,"nome")."'";
		$c = ",";
	  }
	  echo $aux.");\n";
	}

	// Carrega a array com a lista de id_usuario de cada usuario em cada array com  nome do setor mais o sufixo "1"
    for ($i=0;$i<$numeroDepartamentos;$i++) {
	  $usu =  pg_exec("select us.id_usuario, us.nome 
	                   from db_usuarios us
        	           inner join db_depusu du 
		        	   on du.id_usuario = us.id_usuario
        			   where coddepto = ".pg_result($departamentos,$i,"coddepto"));
	  $numusu = pg_numrows($usu);
	  $aux= '';
	  $c = '';
 	  echo "a".pg_result($departamentos,$i,"coddepto")."1 = new Array(";
      for($j=0;$j<$numusu;$j++) {
        $aux .= "$c'".pg_result($usu,$j,"id_usuario")."'";
		$c = ",";
	  }
	  echo $aux.");\n";


    }
	?>
	function vai(obj,obj1) {
	  for(var i = 0;i < document.form1.usuarioreceb.length;i++)
	    document.form1.usuarioreceb.options[i] = null;
	
	  for(var i = 0;i < obj.length;i++)
	   document.form1.usuarioreceb.options[i] = new Option(obj[i], obj1[i], false, false);
	   
	  document.form1.usuarioreceb.options[i] = new Option("Enviar para todo o departamento", "", false, false);

	  js_trocacordeselect();
	}
	<?
    echo "\n</script>\n\n";
  
  
  
?>
<center>
  <form method="post" enctype="multipart/form-data" name="form1">
    <table width="780" align="center" cellpadding="0" cellspacing="1">
      <tr> 
        <td colspan="2" nowrap style="font-size:13px" align="center" ><div align="center"><strong>Inclus&atilde;o 
            de ordem de servi&ccedil;o</strong></div><br></td>
      </tr>
      <br>
      <tr align="left" valign="middle"> 
        <td width="365" nowrap ><strong>Usuario : <? echo $nomeUsuario ?> 
          </strong> <div align="left"></div></td>
        <td nowrap ><div align="left"></div>
          <strong>Data :</strong> 
          <? include ("dbforms/db_funcoes.php") ;
		    db_data("dataordem",date("d"),date("m"),date("Y"));
		  ?>
        </td>
        <? 
		  //Gera numero negativo temporario para gravação dos arquivos anexado
		  $numTemp = rand(-1000,-1);
		?>
      </tr>
      <tr align="left" valign="middle"> 
        <td align="left" nowrap > <table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
            <tr> 
              <td align="left" nowrap valign="middle"><strong>Departamento 
                : </strong> <select name="depto" id="select3" onChange="vai(eval('a' + this.options[this.selectedIndex].value),eval('a' + this.options[this.selectedIndex].value + '1'))">
                  <?
				    // Identifica o departamento atual da pessoa que está acessando
					$result = pg_exec("select d.id_usuario, d.coddepto, p.descrdepto
					                   from db_depusu d
									   inner join db_depart p on d.coddepto = p.coddepto
									   where d.id_usuario = $DB_id_usuario limit 1
									  ");
					$descratual = pg_result($result,0,"descrdepto");
					$listaDepartamentos = pg_exec("select * from db_depart where descrdepto not like '$descratual'");
					$numdep = pg_numrows($listaDepartamentos);
					// Deixa selecionado o departamento da pessoa que está acessando
 				    echo "<option selected value=\"".pg_result($result,0,"coddepto")."\">".pg_result($result,0,"descrdepto")."</option>\n";
					//carrega a lista com o resto dos departamentos cadastrados. isto é usado somente na primeira vez que esta janela aparece.
					//depois a troca desses items é feita pela função "vai" javaScript.
					for ($i=0;$i<$numdep;$i++) {
					  echo "<option value=\"".pg_result($listaDepartamentos,$i,"coddepto")."\">".pg_result($listaDepartamentos,$i,"descrdepto")."</option>\n";
					}
				?>
                </select></td>
            </tr>
          </table></td>
        <td align="center" nowrap style="font-size:13px" ><table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
            <tr> 
              <td align="left" nowrap valign="middle"><strong>Usuario 
                destino : 
                <select name="usuarioreceb" id="select2" onChange="document.form1.usuold.value = this.value" >
                  <option value="">Enviar para todo o departamento</option>
                  <? 
						// Carrega a lista de usuarios do departamento selecionado na rotina acima
						// e deixa selecionado o nome do usuario que está acessando
    					$coddepartamento = pg_result($result,0,"coddepto");
		    			$listanomes = pg_exec("select u.id_usuario , d.nome
			    	                           from db_depusu u 
				     					       inner join db_usuarios d
					    				       on d.id_usuario = u.id_usuario
					    				       where u.coddepto = $coddepartamento");
    					$numnomes = pg_numrows($listanomes); 
	    				for ($i=0;$i<$numnomes;$i++) {
			    		  if (pg_result($listanomes,$i,"nome")==$nomeUsuario) {$estado="selected";} else {$estado="";}
		      			  echo "<option ".$estado." value=\"".pg_result($listanomes,$i,"id_usuario")."\">".pg_result($listanomes,$i,"nome")."</option>\n";
				    	}
					?>
                </select>
	<?
	if(isset($usuold) && $usuold != ""){
	  echo "<script>
	        for(var i = 0;i < document.form1.usuarioreceb.length;i++){
	          if(document.form1.usuarioreceb.options[i].value == '$usuold'){
	            document.form1.usuarioreceb.options[i].selected = true;
		  }
		}  
		</script>"; 
	}
	?>
	<input name="usuold" type="hidden" value="<?=@$usuold?>">
                </strong></td>
            </tr>
          </table></td>
      </tr>
      <tr> 
        <td colspan="2" align="center" valign="middle" nowrap  style="font-size:13px"> 
          <iframe src="con6_andamentoanexar.php" align="middle"  scrolling="no" hspace="0" name="iframe" width="100%" height="30" frameborder="0" marginwidth="0" marginheight="0"> 
          </iframe> </td>
      </tr>
      <tr> 
        <td align="center" valign="middle" nowrap  style="font-size:13px"><table width="100%" height="100%" border="0" align="center" cellpadding="0" cellspacing="0">
            <tr align="center" valign="middle"> 
              <td width="40%" height="17" align="right" style="font-size:13px">Desmarcar 
                todos 
                <input name="desmarcar" type="button" id="desmarcar" value=" - " onClick="js_desmarcar()"> 
              </td>
              <td width="20%" ><strong>&nbsp;M&oacute;dulos:</strong> 
              </td>
              <td width="40%" height="17" align="left" style="font-size:13px"><input name="marcar" type="button" id="marcar17" value="+" onClick="js_marcar()">
                Marcar todos </td>
            </tr>
            <tr> 
              <td colspan="3" align="center" valign="middle" style="font-size:13px"> 
                <select name="modulos[]" size="5" multiple id="modulos[]">
                  <?
		  // Carrega a lista de modulos
		  $listaDeModulos = pg_exec("Select id_item, nome_modulo from db_modulos");
		  $numListaDeModulos = pg_numrows($listaDeModulos);
          for ($i=0;$i<$numListaDeModulos;$i++) {
		    echo "
			<option value=\"".pg_result($listaDeModulos,$i,"id_item")."\">".pg_result($listaDeModulos,$i,"nome_modulo")."</option>
			/n";
		  }
		?>
                </select></td>
            </tr>
          </table></td>
        <td align="center" valign="bottom" nowrap  style="font-size:13px"><table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
            <tr> 
              <td align="center" ><strong>Arquivos anexados 
                :</strong></td>
            </tr>
            <tr> 
              <td align="center" ><input name="removerAnexado" type="button" id="removerAnexado" value=" - " onClick="javascript:removeItemAnexado()">
                Remover item selecionado da lista</td>
            </tr>
            <tr> 
              <td align="center" valign="bottom"><select name="arquivos[]" size="4" multiple id="arquivos[]">
                </select></td>
            </tr>
          </table></td>
      </tr>
      <tr align="center" valign="middle"> 
        <td height="11" colspan="2" nowrap ><div align="left"></div>
          
       <?
       db_ancora(@$Lor10_codatend,"js_pesquisaor10_codatend(true);",1);

       ?>
       <br>
          <strong>Descri&ccedil;&atilde;o : </strong> 
	</td>
      </tr>
      <tr align="center"> 
        <td height="11" colspan="2" nowrap valign="center"> 
	  <textarea name="descr" cols="80" rows="6" id="descr" onKeyDown="return js_ProtegeTextoEsc()"></textarea>
          </td>
        <input name="or10_seq" type="hidden" value="">
        <input name="or10_codatend" type="hidden" value="">
        <input name="data_dia" type="hidden" value="<?=@$data_dia?>">
        <input name="data_mes" type="hidden" value="<?=@$data_mes?>">
        <input name="data_ano" type="hidden" value="<?=@$data_ano?>">
      </tr>
      <tr align="center">
        <td colspan="2" nowrap ><strong>Previs&atilde;o </strong>: 
          <? 
		    db_data("dataprev");
		    if(isset($data_dia) && $data_dia != ""){
		      echo "<script>document.form1.dataprev_dia.value = '$data_dia'</script>";
		    }
		    if(isset($data_mes) && $data_mes != ""){
		      echo "<script>document.form1.dataprev_mes.value = '$data_mes'</script>";
		    }
		    if(isset($data_ano) && $data_ano != ""){
		      echo "<script>document.form1.dataprev_ano.value = '$data_ano'</script>";
		    }
		  echo "
		  <script>
		  function js_guardadata(){
		    if(document.form1.dataprev_dia.value != ''){
		      document.form1.data_dia.value = document.form1.dataprev_dia.value;
		    }
		    if(document.form1.dataprev_mes.value != ''){
		      document.form1.data_mes.value = document.form1.dataprev_mes.value;
		    }
		    if(document.form1.dataprev_ano.value != ''){
		      document.form1.data_ano.value = document.form1.dataprev_ano.value;
		    }
		    return true;
		  }
		  </script>
		  ";
		  ?>
        </td>
      </tr>
      <tr> 
        <td colspan="2" nowrap ><div align="center"> 
            <input name="incluir" type="submit" id="incluir" value="Incluir" onClick="return js_guardadata();javascript:js_marcarAnexados()">
          </div></td>
      </tr>
    </table>
  </form>
</center>
  
<script>
function js_pesquisaor10_codatend(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_atenditem','func_atenditemordem.php?funcao_js=parent.js_mostraatenditem1|at05_seq|at05_codatend|at05_solicitado|at05_data','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_atenditem','func_atenditemordem.php?pesquisa_chave='+document.form1.or10_codatend.value+'&funcao_js=parent.js_mostraatenditem','Pesquisa',false);
  }
}
function js_mostraatenditem(chave,erro){
  document.form1.at05_solicitado.value = chave; 
  if(erro==true){ 
    document.form1.or10_codatend.focus(); 
    document.form1.or10_codatend.value = ''; 
  }
}
function js_mostraatenditem1(chave1,chave2,chave3,chave4){
  document.form1.or10_seq.value = chave1;
  document.form1.or10_codatend.value = chave2;
  document.form1.descr.value = chave3;
  dia = chave4.substr(8,2);
  mes = chave4.substr(5,2);
  ano = chave4.substr(0,4);
  document.form1.dataprev_dia.value = dia;
  document.form1.dataprev_mes.value = mes;
  document.form1.dataprev_ano.value = ano;
  db_iframe_atenditem.hide();
}
	function js_desmarcar() {
	  var F = document.form1.elements['modulos[]'];
	  if(F.selectedIndex != -1) {
		for(i = 0;i < F.length;i++) {
		  F.options[i] = new Option(F.options[i].text,F.options[i].value);
		}
		js_trocacordeselect();
	  }
	}

	function js_marcar() {
	  var F = document.form1.elements['modulos[]'];
	  for(i = 0;i < F.length;i++) {
		F.options[i].selected = true;
	  }
	  js_trocacordeselect();
	}

	function removeItemAnexado() {
	  var F = document.form1.elements['arquivos[]'];
	  if(F.selectedIndex != -1) {
		F.options[F.selectedIndex] = null;
	  }
	  js_trocacordeselect();
	}

	function js_marcarAnexados() {
	  var F = document.form1.elements['arquivos[]'];
	  for(i = 0;i < F.length;i++) {
		F.options[i].selected = true;
	  }
	  js_trocacordeselect();
	}

	js_trocacordeselect();
</script>
<script>
js_Ipassacampo();
document.forms[0].elements[0].focus();
</script>