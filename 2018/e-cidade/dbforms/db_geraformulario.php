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

// CLASSE PARA GERAR FORM PARA RELATÓRIOS CONFIGURÁVEIS
class cl_formulario_relcampos {

  var $sqltabelas = ""; // SQL com quais tabelas devem aparecer no SELECT
  var $urlproxarq = ""; // Arquivo que receberá URL
  var $varcodigo = "";  // Nome da variável que será o value do SELECT
  var $vardescri = "";  // Nome da variável que será a descrição do SELECT
  var $nomecampo = "seleciona";  // Nome do campo SELECT
  var $arr_alter = Array();      // Array com tabelas para quando o usuário for alterar algum relatório.
  var $cam_alter = "";           // String com tabelas para quando o usuário for alterar algum relatório.

  function cl_formulario_rel_pes(){
  	$this->rotulo = new rotulocampo;
  }

	function gera_arquivo(){

	  $valcam = $this->nomecampo;
		$codigo = $this->varcodigo;
		$descri = $this->vardescri;
   	global $$codigo,$$descri,$$valcam;
   	if(!isset($$valcam)){
   		$$valcam = 0;
   	}

    if(trim($this->sqltabelas) != "" || count($this->arr_alter) > 0){
    	if(count($this->arr_alter) <= 0){
	    	$result_tabelas = @pg_exec($this->sqltabelas);
    	}else{
    		$result_tabelas = @pg_exec("select db_sysarquivo.codarq,rotulo from db_sysarquivo where codarq in (".$this->cam_alter.")");
   		}

    	if($result_tabelas == false){
    		db_msgbox("Nenhum arquivo encontrado.");
    		exit;
    	}

      $arr_tabless = Array();
    	$arr_tabelas = Array("0"=>"Selecione um arquivo");
	    $colunas = pg_num_fields($result_tabelas);

	    for($i=0; $i<pg_numrows($result_tabelas); $i++){
	    	db_fieldsmemory($result_tabelas, $i);
	    	$arr_tabless[$$codigo] = $$codigo;
	   	  $arr_tabelas[$$codigo] = $$descri;
	    }
     
      if(count($arr_tabelas) == 2){
      	$$valcam = $$codigo;
      }

      echo "
            <center>
						<table width='90%' border='0' cellspacing='0' cellpadding='0'>
     				  <form name='form1' method='post'>
						  <tr><td colspan='2'>&nbsp;</td></tr>
						  <tr><td colspan='2'>&nbsp;</td></tr>
	            <tr>
	              <td width='35%' align='right' nowrap title='Selecione a tabela para visualizar seus campos'>
	                <strong>Tabelas:</strong>
	              </td>
	              <td align='left' nowrap>
           ";
		  db_select($this->nomecampo,$arr_tabelas,true,1,"onChange='js_submita(true);'");
      echo "
	              </td>
	            </tr>
           ";

      if($$valcam != 0){
      	$sql_camposTABLES = "select distinct codarq as codigodatabela, sigla as sigladoarquivo from db_sysarquivo where db_sysarquivo.codarq = ".$$valcam;
      	$result_camposTABLES = @pg_exec($sql_camposTABLES);
        if($result_camposTABLES == false){
        	db_msgbox("Erro ao buscar campos da tabela escolhida");
        	exit;
        }
        db_fieldsmemory($result_camposTABLES, 0);
        global $codigodatabela, $sigladoarquivo; 

      	$sql_camposFKPRIN = "select ".$codigodatabela." as arquivo, '".$sigladoarquivo."' as siglaarq, codarq, sigla
                             from db_sysarquivo
                                  inner join (
								                              select distinct
								                                     db_sysforkey.referen as arquivo
								                              from   db_sysarquivo
								                                     left  join db_sysforkey   on  db_sysforkey.codarq = db_sysarquivo.codarq
								                              where     trim(rotulo) <> ''
								                                    and rotulo is not null
								                                    and db_sysarquivo.codarq = ".$$valcam."
                                             ) x on x.arquivo = codarq
                             where     trim(rotulo) <> ''
                                   and rotulo is not null
                            ";
        $result_camposFKPRIN = @pg_exec($sql_camposFKPRIN);
        
        if($result_camposFKPRIN == false){
        	db_msgbox("Erro ao buscar campos referentes à tabela selecionada.");
        	exit;
        }

        $sql_camposFKSECN = "select ".$codigodatabela." as arquivo, '".$sigladoarquivo."' as siglaarq, codarq, sigla
		                              from db_sysarquivo
		                                   inner join (
										                               select distinct
										                                      db_sysarquivo.codarq as arquivo
										                               from   db_sysforkey
										                                      left  join  db_sysarquivo  on  db_sysarquivo.codarq = db_sysforkey.referen
										                               where      trim(rotulo) <> ''
										                                      and rotulo is not null
				      			                                      and db_sysforkey.referen = ".$$valcam."
		                                              ) x on x.arquivo = codarq
		                              where     trim(rotulo) <> ''
	 	                                    and rotulo is not null
 		                            ";
        $result_camposFKSECN = @pg_exec($sql_camposFKSECN);
        
        if($result_camposFKSECN == false){
        	db_msgbox("Erro ao buscar campos referentes à tabela selecionada.");
        	exit;
        }

        $arr_camposTIPO = Array();
        $arr_camposUSAR = Array();

        $arr_camposUSARSoma = Array();

        $arr_camposSSEL = Array();
        $arr_camposNSEL = Array();

        $arr_tables = Array();
        $arr_campos = Array();
        $arr_camposSEL = Array();
        global $codcam, $rotulo, $conteudo, $arquivo, $siglaarq;
        global $campo_auxilio_codigorel, $campo_auxilio_tabelasel, $campo_auxilio_nselecion, $campo_auxilio_sselecion, $campo_seleciona_filtros, $campo_tipodados_filtros, $campo_camposfks_filtros;
        global $filtro1, $filtro2, $filtro3, $campo_camporecb_filtro1, $campo_camporecb_filtro2, $campo_camporecb_filtro3, $qbratod, $qbrapag, $gerafon;
        global $campo_camporecb_cabecal, $campo_camporecb_comple1, $campo_camporecb_comple2, $campo_camporecb_comple3, $campo_camporecb_comple4, $campo_camporecb_comple5, $campo_camporecb_comple6;
        for($i=0; $i<pg_numrows($result_camposFKPRIN); $i++){
        	db_fieldsmemory($result_camposFKPRIN, $i);
        	$where = "";
        	if(isset($campo_auxilio_sselecion) && trim($campo_auxilio_sselecion) != ""){
        		$where = " and db_syscampo.codcam not in (".$campo_auxilio_sselecion.")";
        	}
        	if(!isset($arr_tables[$arquivo])){
        		$result_campos = @pg_exec("select db_syscampo.codcam, rotulo, conteudo from db_syscampo inner join db_sysarqcamp on db_sysarqcamp.codcam = db_syscampo.codcam where codarq = ".$arquivo.$where);
        		for($ii=0; $ii<pg_numrows($result_campos); $ii++){
        			db_fieldsmemory($result_campos, $ii);
        			$arr_campos[$codcam] = $rotulo;
              $arr_camposNSEL[$codcam] = $codcam;
        			if($conteudo == "int4" || $conteudo == "int8" || $conteudo == "date"){
                $arr_camposUSAR[$codcam] = $codcam;
                $arr_camposTIPO[$codcam] = $conteudo;
        			}else if($conteudo == "float4" || $conteudo == "float8"){
                $arr_camposUSARSoma[$codcam] = $codcam;
        			}
        		}
        		$arr_tables[$arquivo] = $arquivo;
        	}
        	if(!isset($arr_tables[$codarq])){
        		$result_campos = @pg_exec("select db_syscampo.codcam, rotulo, conteudo from db_syscampo inner join db_sysarqcamp on db_sysarqcamp.codcam = db_syscampo.codcam where codarq = ".$codarq.$where);
        		for($ii=0; $ii<pg_numrows($result_campos); $ii++){
        			db_fieldsmemory($result_campos, $ii);
        			$arr_campos[$codcam] = $rotulo;
              $arr_camposNSEL[$codcam] = $codcam;
        			if($conteudo == "int4" || $conteudo == "int8" || $conteudo == "date"){
                $arr_camposUSAR[$codcam] = $codcam;
                $arr_camposTIPO[$codcam] = $conteudo;
        			}else if($conteudo == "float4" || $conteudo == "float8"){
                $arr_camposUSARSoma[$codcam] = $codcam;
        			}
        		}
        		$arr_tables[$codarq] = $codarq;
        	}
        }
        for($i=0; $i<pg_numrows($result_camposFKSECN); $i++){
        	db_fieldsmemory($result_camposFKSECN, $i);
        	$where = "";
        	if(isset($campo_auxilio_sselecion) && trim($campo_auxilio_sselecion) != ""){
        		$where = " and db_syscampo.codcam not in (".$campo_auxilio_sselecion.")";
        	}
        	if(!isset($arr_tables[$arquivo])){
        		$result_campos = @pg_exec("select db_syscampo.codcam, rotulo, conteudo from db_syscampo inner join db_sysarqcamp on db_sysarqcamp.codcam = db_syscampo.codcam where codarq = ".$arquivo.$where);
        		for($ii=0; $ii<pg_numrows($result_campos); $ii++){
        			db_fieldsmemory($result_campos, $ii);
        			$arr_campos[$codcam] = $rotulo;
              $arr_camposNSEL[$codcam] = $codcam;
        			if($conteudo == "int4" || $conteudo == "int8" || $conteudo == "date"){
                $arr_camposUSAR[$codcam] = $codcam;
                $arr_camposTIPO[$codcam] = $conteudo;
        			}else if($conteudo == "float4" || $conteudo == "float8"){
                $arr_camposUSARSoma[$codcam] = $codcam;
        			}
        		}
        		$arr_tables[$arquivo] = $arquivo;
        	}
        	if(!isset($arr_tables[$codarq])){
        		$result_campos = @pg_exec("select db_syscampo.codcam, rotulo, conteudo from db_syscampo inner join db_sysarqcamp on db_sysarqcamp.codcam = db_syscampo.codcam where codarq = ".$codarq.$where);
        		for($ii=0; $ii<pg_numrows($result_campos); $ii++){
        			db_fieldsmemory($result_campos, $ii);
        			$arr_campos[$codcam] = $rotulo;
              $arr_camposNSEL[$codcam] = $codcam;
        			if($conteudo == "int4" || $conteudo == "int8" || $conteudo == "date"){
                $arr_camposUSAR[$codcam] = $codcam;
                $arr_camposTIPO[$codcam] = $conteudo;
        			}else if($conteudo == "float4" || $conteudo == "float8"){
                $arr_camposUSARSoma[$codcam] = $codcam;
        			}
        		}
        		$arr_tables[$codarq] = $codarq;
        	}
        }
        if(isset($campo_auxilio_sselecion) && $campo_auxilio_sselecion != ""){
          $arr_selecionados = split(",",$campo_auxilio_sselecion);
          for($i=0; $i<count($arr_selecionados); $i++){
        		$result_campos = @pg_exec("select db_syscampo.codcam, rotulo, conteudo from db_syscampo where codcam in (".$campo_auxilio_sselecion.") ");
        		if($result_campos != false){
	        		for($ii=0; $ii<pg_numrows($result_campos); $ii++){
	        			db_fieldsmemory($result_campos, $ii);
	        			$arr_camposSEL[$codcam] = $rotulo;
                $arr_camposSSEL[$codcam] = $codcam;
	        			if($conteudo == "int4" || $conteudo == "int8" || $conteudo == "date"){
	                $arr_camposUSAR[$codcam] = $codcam;
	                $arr_camposTIPO[$codcam] = $conteudo;
	        			}else if($conteudo == "float4" || $conteudo == "float8"){
	                $arr_camposUSARSoma[$codcam] = $codcam;
	        			}
	        		}
        		}
          }
        }

      	$arr_camposFORK = Array();
        if(trim(implode(",",$arr_camposUSAR)) != ""){
        	$result_camposfiltro = @pg_exec("select distinct db_syscampo.codcam, conteudo from db_syscampo left join db_sysforkey on db_sysforkey.codcam = db_syscampo.codcam left join db_sysprikey on db_sysprikey.codcam = db_syscampo.codcam where (db_sysforkey.codcam is not null or db_sysprikey.codcam is not null) and db_syscampo.codcam in (".implode(",",$arr_camposUSAR).")");
        	if($result_camposfiltro != false){
	        	for($ifil=0; $ifil<pg_numrows($result_camposfiltro); $ifil++){
	        		db_fieldsmemory($result_camposfiltro, $ifil);
	            $arr_camposFORK[$codcam] = $codcam;
        		}
        	}
        }

        $campo_seleciona_filtros = implode(",",$arr_camposUSAR);
        $campo_tipodados_filtros = implode(",",$arr_camposTIPO);
        $campo_camposfks_filtros = implode(",",$arr_camposFORK);

	      echo "
		            <tr>
		              <td align='center' nowrap colspan='2'>
	           ";
        db_multiploselect("valor","descr", "objetosel1", "objetosel2", $arr_campos, $arr_camposSEL, 20, 300, "Campos a selecionar", "Campos selecionados", false, "js_inserirfiltros();");

        $campo_auxilio_sselecion = implode(",",$arr_camposSSEL);
        $campo_auxilio_nselecion = implode(",",$arr_camposNSEL);
        $campo_auxilio_tabelasel = implode(",",$arr_tabless);

	      $arr_filtro = $arr_camposSEL;
	      $arr_filtro[0] = "Nenhum";
	      ksort($arr_filtro);

        db_input("campo_auxilio_codigorel",40,0,true,"hidden",3);//echo "<BR>";
        db_input("campo_auxilio_tabelasel",40,0,true,"hidden",3);//echo "<BR>";
        db_input("campo_auxilio_nselecion",40,0,true,"hidden",3);//echo "<BR>";
        db_input("campo_auxilio_sselecion",40,0,true,"hidden",3);//echo "<BR>";
        db_input("campo_seleciona_filtros",40,0,true,"hidden",3);//echo "<BR>";
        db_input("campo_tipodados_filtros",40,0,true,"hidden",3);//echo "<BR>";
        db_input("campo_camposfks_filtros",40,0,true,"hidden",3);//echo "<BR>";
        db_input("campo_camporecb_filtro1",40,0,true,"hidden",3);//echo "<BR>";
        db_input("campo_camporecb_filtro2",40,0,true,"hidden",3);//echo "<BR>";
        db_input("campo_camporecb_filtro3",40,0,true,"hidden",3);//echo "<BR>";
        db_input("campo_camporecb_qbrapor",40,0,true,"hidden",3);//echo "<BR>";
        db_input("campo_camporecb_totaliz",40,0,true,"hidden",3);//echo "<BR>";
        db_input("campo_camporecb_somator",40,0,true,"hidden",3);//echo "<BR>";
        db_input("campo_camporecb_cabecal",40,0,true,"hidden",3);//echo "<BR>";
        db_input("campo_camporecb_comple1",40,0,true,"hidden",3);//echo "<BR>";
        db_input("campo_camporecb_comple2",40,0,true,"hidden",3);//echo "<BR>";
        db_input("campo_camporecb_comple3",40,0,true,"hidden",3);//echo "<BR>";
        db_input("campo_camporecb_comple4",40,0,true,"hidden",3);//echo "<BR>";
        db_input("campo_camporecb_comple5",40,0,true,"hidden",3);//echo "<BR>";
        db_input("campo_camporecb_comple6",40,0,true,"hidden",3);//echo "<BR>";
        db_input("campo_camporecb_nomearq",40,0,true,"hidden",3);

	      echo "
		              </td>
		            </tr>
		            <tr>
                  <td colspan='2' align='center'>
                    <table width='83.5%'>
                      <tr>
					              <td align='center' nowrap title='Campos para filtro'>
			                    <fieldset>
			                    <legend align='left'>
			                      <b>Campos para filtro</b>
			                    </legend>
			                    <table width='100%'>
			                      <tr>
								              <td align='center' nowrap width='33%'>
							           ";
				      if(!isset($filtro1)){
				      	$filtro1 = 0;
				      }
				      if(!isset($filtro2)){
				      	$filtro2 = 0;
				      }
				      if(!isset($filtro3)){
				      	$filtro3 = 0;
				      }
							echo "<input type='button' name='mudar1' value='A' style='visibility:hidden;' onclick='js_abrearquivofil(document.form1.filtro1.value,1,2);'>";
									  db_select('filtro1',$arr_filtro,true,1,"onChange='js_abrearquivofil(this.value,1,1);'");
						  echo "
								              </td>
								              <td align='center' nowrap width='33%'>
							           ";
							echo "<input type='button' name='mudar2' value='A' style='visibility:hidden;' onclick='js_abrearquivofil(document.form1.filtro2.value,2,2);'>";
									  db_select('filtro2',$arr_filtro,true,1,"onChange='js_abrearquivofil(this.value,2,1);'");
						  echo "
								              </td>
								              <td align='center' nowrap width='33%'>
							           ";
							echo "<input type='button' name='mudar3' value='A' style='visibility:hidden;' onclick='js_abrearquivofil(document.form1.filtro3.value,3,2);'>";
									  db_select('filtro3',$arr_filtro,true,1,"onChange='js_abrearquivofil(this.value,3,1);'");
							$checkar = "";
							$disable = " disabled ";
							if(isset($qbrapag)){
								$checkar = " checked ";
								$disable = "";
							}
							$checkarT = "";
							$disableT = " disabled ";
							if(isset($qbratod)){
								$checkarT = " checked ";
								$disableT = "";
							}
							$disableN = " disabled ";
							$checkarF = "";
							if(isset($gerafon)){
								$disableN = "";
								$checkarF = " checked ";
							}
						  echo "
								              </td>
			                      </tr>
			                    </table>
			                    </legend>
			                    </fieldset>
					              </td>
                      </tr>
                    </table>
                  </td>
		            </tr>
  						  <tr>
							    <td colspan='2' align='center'>
                    <table width='83.5%'>
                      <tr>
					              <td align='center' nowrap title='Outras opções'>
			                    <fieldset>
			                    <legend align='left'>
			                      <b>Outras opções</b>
			                    </legend>
			                    <table width='100%' border='0'>
			                      <tr>
								              <td align='center' nowrap width='75%'>
                                <table align='center' nowrap>
						                      <tr>
											              <td align='center' nowrap width='33%'>
			                                <table>
									                      <tr>
														              <td>
										      						      <input type='button' name='salvarrel' value='Gerar/Salvar dados' style='width:140px;' onclick='js_actionform(true);'>
														              </td>
									                      </tr>
			                                </table>
											              </td>
											              <td align='center' nowrap width='33%'>
			                                <table>
									                      <tr>
														              <td>
										      						      <input type='button' name='outrosrel' value='Outros relatórios' style='width:140px;' onclick='js_outrosrel();'>
														              </td>
									                      </tr>
			                                </table>
											              </td>
											              <td align='center' nowrap width='33%'>
			                                <table>
									                      <tr>
														              <td>
										      						      <input type='button' name='cabecalho' value='Cabeçalho' style='width:140px;' onclick='js_abrecabec();'>
														              </td>
									                      </tr>
			                                </table>
											              </td>
						                      </tr>
						                      <tr>
											              <td align='center' nowrap width='33%'>
			                                <table>
									                      <tr>
														              <td>
										      						      <input type='button' name='somatorio' value='Inserir somas' style='width:140px;' onclick='js_abrearquivosom();'>
														              </td>
									                      </tr>
			                                </table>
											              </td>
											              <td align='center' nowrap width='33%'>
			                                <table>
									                      <tr>
														              <td>
										      						      <input type='button' name='quebrapor' value='Inserir quebras' style='width:140px;' onclick='js_abrearquivoqbr();'>
														              </td>
									                      </tr>
			                                </table>
											              </td>
											              <td align='center' nowrap width='33%'>
			                                <table>
									                      <tr>
														              <td>
										      						      <input type='button' name='nomearqui' value='Nome do arquivo' style='width:140px;' ".$disableN." onclick='js_abrenomearq();'>
														              </td>
									                      </tr>
			                                </table>
											              </td>
						                      </tr>
                                </table>
								              </td>
								              <td align='center' nowrap width='25%'>
                                <table>
						                      <tr>
											              <td>
										      						<input type='checkbox' name='qbrapag' value='qbrarpagina' ".$disable." ".$checkar." onclick='js_testaqbras(true);'>
			                                <b>Quebrar página</b>
											              </td>
						                      </tr>
						                      <tr>
											              <td>
										      						<input type='checkbox' name='qbratod' value='qbrartodos' ".$disableT." ".$checkarT." onclick='js_testaqbras(false);'>
			                                <b>Quebrar por todos</b>
											              </td>
						                      </tr>
						                      <tr>
											              <td>
										      						<input type='checkbox' name='gerafon' value='geracodfon' ".$checkarF."  onclick='js_nomearquivo();'>
			                                <b>Gerar código fonte</b>
											              </td>
						                      </tr>
                                </table>
								              </td>
			                      </tr>
			                    </table>
			                    </legend>
			                    </fieldset>
					              </td>
                      </tr>
                    </table>
							    </td>
							  </tr>
              ";
      }else{
      	echo "
              <tr>
                <td></td>
                <td align='left'>
                  <BR>
                  <input type='button' name='outrosrel' value='Outros relatórios' style='width:140px;' onclick='js_outrosrel();'>
                </td>
              </tr>
             ";
      }
    	  echo "
						  </form>
						</table>
						</center>
            <script>
							var recebelistagem = '';
              function js_outrosrel(){
                js_OpenJanelaIframe('top.corpo','db_iframe_db_relat','func_db_relat.php?funcao_js=parent.js_preenchepesquisa|db91_codrel','Pesquisa',true,'20');
              }
							function js_preenchepesquisa(chave){
							  db_iframe_db_relat.hide();
							  location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave;
							}
              function js_nomearquivo(){
                if(document.form1.gerafon.checked == true){
                  document.form1.nomearqui.disabled = false;
                }else{
                  document.form1.campo_camporecb_nomearq.value = '';
                  document.form1.nomearqui.disabled = true;
                }
              }
              function js_testaqbras(campo){
                if(campo == true){
                  if(document.form1.qbrapag.checked == false){
                    document.form1.qbratod.checked = false;
                  }
                }else{
                  if(document.form1.qbratod.checked == true){
                    document.form1.qbrapag.checked = true;
                  }
                }
              }
              function js_abrearquivofil(valor,campo,mudar){
                if(mudar == 1){
                  eval('document.form1.campo_camporecb_filtro'+campo+'.value = \"\"');
                  eval('document.form1.mudar'+campo+'.style.visibility = \"hidden\"');
                }
                if(valor != 0){
	                arr = document.form1.campo_camposfks_filtros.value.split(',');
		              val = js_search(arr,valor,'f');
	                qry = '?param='+valor+'&campo='+campo;
                  if(mudar == 2){
                    variavel = eval('document.form1.campo_camporecb_filtro'+campo+'.value');
                    for(i=0;i<2;i++){
                      variavel = variavel.replace(\"#\",\"|\");
                    }
                    qry += '&valorvariavel='+variavel;
                  }
		              if(val != 'false'){
		                qry += '&valor='+arr[val];
		              }
	                js_OpenJanelaIframe('top.corpo','db_iframe_interval','func_interval.php'+qry,'Informar filtro',true,20,0,600,150);
                }
              }
              function js_abrearquivoqbr(){
                js_OpenJanelaIframe('top.corpo','db_iframe_quebrapag','func_quebrapag.php','Informar quebras',true,20);
              }
              function js_abrecabec(){
                js_OpenJanelaIframe('top.corpo','db_iframe_cabecalho','func_cabecalho.php','Informar dados do cabeçalho',true,20);
              }
              function js_abrenomearq(){
                js_OpenJanelaIframe('top.corpo','db_iframe_nomearqui','func_nomearqui.php?arquivo='+document.form1.campo_camporecb_nomearq.value,'Informar filtro',true,20,0,600,150);
              }
              function js_abrearquivosom(){
                x = document.form1;
                quantidad = 0;
                campousar = '';
                virguusar = '';
                if(x.objetosel2.length > 0){
                  arr_search = new Array();
                  for(i=0;i<x.objetosel2.length;i++){
                    arr_search.push(x.objetosel2.options[i].value);
                  }
             ";
        if(isset($arr_camposUSARSoma)){
	        reset($arr_camposUSARSoma);
	        for($i=0; $i<count($arr_camposUSARSoma); $i++){
	          $campousar = key($arr_camposUSARSoma);
	          echo "
	                val = js_search(arr_search,'".$campousar."','f');
	                if(val != 'false'){
                    quantidad++;
		                campousar+= virguusar+'".$campousar."';
		                virguusar = ',';
	                }
	               ";
	          next($arr_camposUSARSoma);
	        }
        }
        echo "
                }
                if(campousar != ''){
                  altura = (60+(33*quantidad));
                  js_OpenJanelaIframe('top.corpo','db_iframe_somatorio','func_somatorio.php?sel='+document.form1.campo_camporecb_somator.value+'&campousar='+campousar,'Informar somas',true,20,0,300,altura);
                }else{
                  alert('Nenhum campo selecionado ou campos selecionados não podem ser somados.');
                }
              }
              function js_setarvalor(campo, valor){
								x = eval('document.form1.'+campo);
								y = eval('document.form1.campo_camporecb_'+campo);
                erro = 0;
								for(iin=0; iin<x.length; iin++){
								  if(x.options[iin].value == valor){
							      x.options[iin].selected=true;
                    erro++;
								    break;
                  }
								}
                if(erro == 0){
                  y.value = '';
                  if(campo == 'filtro1'){
                    document.form1.mudar1.style.visibility = 'hidden';
                  }else if(campo == 'filtro2'){
                    document.form1.mudar2.style.visibility = 'hidden';
                  }else if(campo == 'filtro3'){
                    document.form1.mudar3.style.visibility = 'hidden';
                  }
                }
              }
              function js_inserirfiltros(){
                x = document.form1;
                index = 1;

                valorf1 = x.filtro1.value;
                valorf2 = x.filtro2.value;
                valorf3 = x.filtro3.value;

                q0 = x.filtro1.length;
                for(ites=q0; ites>0; ites--){
	                x.filtro1.options[ites] = null;
	                x.filtro2.options[ites] = null;
	                x.filtro3.options[ites] = null;
                }

                /*
                q1 = x.objetosel1.length;
                for(iins=0; iins<q1; iins++){
	                x.filtro1.options[index] = new Option(x.objetosel1.options[iins].text,x.objetosel1.options[iins].value);
	                x.filtro2.options[index] = new Option(x.objetosel1.options[iins].text,x.objetosel1.options[iins].value);
	                x.filtro3.options[index] = new Option(x.objetosel1.options[iins].text,x.objetosel1.options[iins].value);
	                index++;
                }
                */

                q2 = x.objetosel2.length;
                for(iins=0; iins<q2; iins++){
	                x.filtro1.options[index] = new Option(x.objetosel2.options[iins].text,x.objetosel2.options[iins].value);
	                x.filtro2.options[index] = new Option(x.objetosel2.options[iins].text,x.objetosel2.options[iins].value);
	                x.filtro3.options[index] = new Option(x.objetosel2.options[iins].text,x.objetosel2.options[iins].value);
	                index++;
                }


                if(valorf1 != 0){
                  js_setarvalor('filtro1', valorf1);
                }else{
                  document.form1.campo_camporecb_filtro1.value = '';
                  document.form1.mudar1.style.visibility = 'hidden';
                }
                if(valorf2 != 0){
                  js_setarvalor('filtro2', valorf2);
                }else{
                  document.form1.campo_camporecb_filtro2.value = '';
                  document.form1.mudar2.style.visibility = 'hidden';
                }
                if(valorf3 != 0){
                  js_setarvalor('filtro3', valorf3);
                }else{
                  document.form1.campo_camporecb_filtro3.value = '';
                  document.form1.mudar3.style.visibility = 'hidden';
                }

                js_trocacordeselect();
              }

              function js_actionform(salvar){
                x = document.form1;
                if(x.objetosel2.length > 0){
                  if(salvar == false){
                    x.action = 'db_selecionafiltro.php';
                  }else{
				            obj=document.createElement('input');
				            obj.setAttribute('name','salvarrelatorio');
				            obj.setAttribute('type','hidden');
				            obj.setAttribute('value','salvarrelatorio');
				            x.appendChild(obj);
                  }
	                js_submita(true);
                }else{
                  alert('Selecione um campo para prosseguir.');
                }
              }
              function js_search(arr,val,TorF){
                for(i2=0; i2<arr.length; i2++){
                  if(arr[i2] == val){
                    return i2;
                  }
                }
                return 'false';
              }
              function js_submita(submitar){
                x = document.form1;
                retorno = true;
                if(x.campo_auxilio_nselecion && x.campo_auxilio_sselecion){
	                arr1 = x.campo_auxilio_nselecion.value.split(',');
	                arr2 = x.campo_auxilio_sselecion.value.split(',');
	                for(i=0; i<x.objetosel1.length; i++){
	                  valor1 = js_search(arr1,x.objetosel1.options[i].value,'f');
	                  if(valor1 == 'false'){
	                    arr1.push(x.objetosel1.options[i].value);
	                  }
	                  valor2 = js_search(arr2,x.objetosel1.options[i].value,'f');
	                  if(valor2 != 'false'){
	                    arr2.splice(valor2,1);
	                  }
	                }
	                for(i=0; i<x.objetosel2.length; i++){
	                  valor1 = js_search(arr1,x.objetosel2.options[i].value,'f');
	                  if(valor1 != 'false'){
	                    arr1.splice(valor1,1);
	                  }
	                  valor2 = js_search(arr2,x.objetosel2.options[i].value,'f');
	                  if(valor2 == 'false'){
	                    arr2.push(x.objetosel2.options[i].value);
	                  }
	                }
	                selecionados1 = arr1.toString();
	                posic1 = selecionados1.search(',');
                  if(selecionados1.slice(0,(posic1+1)) == ','){
	                  selecionados1 = selecionados1.slice(posic1+1);
                  }
	
	                selecionados2 = arr2.toString();
	                posic2 = selecionados2.search(',');
                  if(selecionados2.slice(0,(posic2+1)) == ','){
	                  selecionados2 = selecionados2.slice(posic2+1);
                  }

                  arr_substitui = new Array();
	                for(i=0; i<x.objetosel2.length; i++){
                    arr_substitui.push(x.objetosel2.options[i].value);
	                }
                  selecionados2 = arr_substitui.toString();

	                x.campo_auxilio_nselecion.value = selecionados1;
	                x.campo_auxilio_sselecion.value = selecionados2;
	                if(x.".$this->nomecampo.".value == '0' && selecionados2 != ''){
	                  retorno = false;
	                  if(confirm('Campos selecionados serão perdidos, deseja continuar?')){
	                    retorno = true;
	                  }
	                }
                }
                if(retorno == true && submitar == true){
                  x.submit();
                }
              }
              if(document.form1.campo_camporecb_totaliz){
						    if(document.form1.campo_camporecb_totaliz.value == ''){
						      document.form1.qbrapag.disabled = true;
						      document.form1.qbrapag.checked  = false;
						      document.form1.qbratod.disabled = true;
						      document.form1.qbratod.checked  = false;
						    }else{
						      document.form1.qbrapag.disabled = false;
						      document.form1.qbratod.disabled = false;
						    }
              }
							if(document.form1.campo_camporecb_filtro1 && document.form1.campo_camporecb_filtro1.value != ''){
								document.form1.mudar1.style.visibility = 'visible';
							}
							if(document.form1.campo_camporecb_filtro2 && document.form1.campo_camporecb_filtro2.value != ''){
								document.form1.mudar2.style.visibility = 'visible';
							}
							if(document.form1.campo_camporecb_filtro3 && document.form1.campo_camporecb_filtro3.value != ''){
								document.form1.mudar3.style.visibility = 'visible';
							}
            </script>
           ";
    }
	}
}
?>