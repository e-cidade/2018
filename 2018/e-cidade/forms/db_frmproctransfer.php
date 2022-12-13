<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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

include("classes/db_proctransferint_classe.php");
include("classes/db_proctransferintand_classe.php");
include("classes/db_proctransferintusu_classe.php");
include("classes/db_protprocesso_classe.php");
include("classes/db_protparam_classe.php");
include("classes/db_procandamint_classe.php");
include("classes/db_procandamintand_classe.php");
include("classes/db_andpadrao_classe.php");

$clprocandamint       = new cl_procandamint;
$clprocandamintand    = new cl_procandamintand;
$clproctransferint    = new cl_proctransferint;
$clproctransferintand = new cl_proctransferintand;
$clproctransferintusu = new cl_proctransferintusu;
$clprotprocesso       = new cl_protprocesso;
$clprotparam          = new cl_protparam;
$clandpadrao          = new cl_andpadrao;
$clrotulo             = new rotulocampo;

//MODULO: protocolo
$clproctransfer->rotulo->label();
$clrotulo->label("nome");
$clrotulo->label("descrdepto");


?>
<form name="form1" method="post" id="cria" action="" onsubmit="return valida(this)">
  <center>
    <table border="0">
      <tr>
        <td>
          <fieldset>
            <table>
              <tr>
                <td nowrap align="right" title="Usuário" width="10%">
                  <b>Usuário:</b>
                </td>
                <td>
                 <?
                   $sql = "select nome from db_usuarios where id_usuario = ".db_getsession("DB_id_usuario");
                   echo pg_result(db_query($sql),0,"nome");

                 ?>
                </td>
              </tr>
              <tr>
                <td nowrap align="right" title="Usuário" width="10%" >
                  <b>Departamento:</b>
                </td>
                <td>
                 <?
                   $sql = "select descrdepto from db_depart where coddepto = ".db_getsession("DB_coddepto");
                   echo pg_result(db_query($sql),0,"descrdepto");
                 ?>
                </td>
              </tr>
                <tr>
                  <td nowrap align="right" title="<?=@$Tp62_codtran?>" width="10%" >
                     <?=@$Lp62_codtran?>
                  </td>
                  <td>
                    <?
                    db_input('p62_codtran',10,$Ip62_codtran,true,'text',3,"")
                    ?>
                  </td>
                </tr>
                <tr>
                  <td nowrap align="right" title="<?=@$Tp62_dttran?>" width="10%">
                     <?=@$Lp62_dttran?>
                  </td>
                  <td>
                    <?
                    db_inputdata('p62_dttran',@$p62_dttran_dia,@$p62_dttran_mes,@$p62_dttran_ano,true,'text',3,"")
                    ?>
                  </td>
                </tr>
                <tr>
                  <td nowrap title="<?=@$Tp62_coddeptorec?>" >
                     <?
                     db_ancora(@$Lp62_coddeptorec,"js_pesquisap62_coddeptorec(true);",$db_opcao);
                     ?>
                  </td>
                  <td nowrap>
                    <?
                    db_input('p62_coddeptorec',10,$Ip62_coddeptorec,true,'text',$db_opcao," onchange='js_pesquisap62_coddeptorec(false);'");
                    db_input('descrdepto',60,$Idescrdepto,true,'text',3);
                     ?>
                  </td>
                </tr>
                <tr>
                  <td  nowrap title="<?=@$Tp62_id_usorec?>">
                     <?=@$Lp62_id_usorec; ?>
                  </td>
                  <td nowrap>
                  <?
                   $aUsuarios = array("0" => "Selecione o Usuário");
                   db_select("p62_id_usorec",$aUsuarios,true,$db_opcao);
                  ?>

                  </td>
                </tr>
            	  <tr>
            	    <td colspan="5" align="center">
            	      <input name="incluir" type="button"
            	             value="<?=($db_opcao==1?"Incluir":($db_opcao==2?"Alterar":"Excluir"))?>"
            	             <?=($db_botao==false?"disabled":"")?>
            	             onClick="js_validaProcessos();">
                    <input name="grupo"   type="hidden" id="grupo" value="<?=$grupo?> ">
                  </td>
            	  </tr>
              </table>
            </fieldset>
         </td>
       </tr>
       <tr>
          <td colspan=5>
    <fieldset><legend><b>Processos No Departamento</b></legend>
       <table border="0" width="100%" cellspacing =0 class="tab_sem_cor">
   <?

    if(!isset($ordem) || $ordem == ''){
      $ordem = " p58_codproc";
    }

    $iInstituicao    = db_getsession('DB_instit');

    $sqlParametro = "select p90_traminic from protparam where p90_instit = {$iInstituicao}";
    $rsParametro  = db_query($sqlParametro);
    $linhasParametro = pg_num_rows($rsParametro);
    if($linhasParametro > 0){
      db_fieldsmemory($rsParametro,0);
    }

    db_input('ordem',5,"",true,'hidden',3,"");
    db_input('usuario',5,"",true,'hidden',3,"");
    db_input('p90_traminic',5,"",true,'hidden',3,"");
    db_input('depart',5,"",true,'hidden',3,"");

    $sJoinValidaDespacho = " left join procandamint on p78_codandam = p58_codandam ";
    $result_param=$clprotparam->sql_record($clprotparam->sql_query());

    if ($clprotparam->numrows>0){
      db_fieldsmemory($result_param,0);
      if (isset($p90_despachoob)&&$p90_despachoob=='t'){
      	$sJoinValidaDespacho = " inner join procandamint on p78_codandam = p58_codandam ";
      }
    }
//die("AQUIIIII >>>>>>>    ".$sJoinValidaDespacho);
    $where = " and not exists ( select *
                                  from processosapensados
                                 where p30_procapensado  = p58_codproc limit 1)  ";


    //SQL:
    //update protprocesso set p58_numero = replace (p58_numero, '/2011/', '')  where p58_numero ilike '%/%';
    //update protprocesso set p58_numero = p58_codproc where p58_numero = '';

    $sql  = " select distinct 																																							";
    $sql .= "        p58_codproc,                                                                           ";
    $sql .= "        p58_dtproc, 	                                                                          ";
    $sql .= "        p58_requer,                                                                            ";
    $sql .= "        p58_codigo,                                                                            ";
    $sql .= "        z01_nome,                                                                              ";
    $sql .= "        cast( case                                                                             ";
    $sql .= "                when trim(p58_numero) = ''                                                     ";
    $sql .= "                  then '0'                                                                     ";
    $sql .= "                else p58_numero                                                                ";
    $sql .= "              end as integer) as p58_numero,                                                   ";
    $sql .= "        p58_ano,                                                                               ";
    $sql .= "        p51_descr,                                                                             ";
    $sql .= "        p61_id_usuario,                                                                        ";
    $sql .= "        p68_codproc,                                                                           ";
    $sql .= "        p61_codandam,                                                                          ";
    $sql .= "        p61_codproc,                                                                           ";
    $sql .= "        p61_coddepto,                                                                          ";
    $sql .= "        p78_codandam,                                                                          ";
    $sql .= "        p87_codandam,                                                                           ";
    $sql .= "				 p86_codandam, p53_codigo,                                                              ";
    $sql .= "				 (select a.p53_coddepto                                                                 ";
    $sql .= "						from andpadrao a                                                                    ";
    $sql .= "					 where a.p53_codigo   = protprocesso.p58_codigo                                       ";
    $sql .= "						 and a.p53_ordem    = (andpadrao.p53_ordem + 1)                                     ";
    $sql .= "					 limit 1 ) as proxdepto,                                                              ";
    $sql .= "				 (select b.descrdepto                                                                   ";
    $sql .= "						from andpadrao a                                                                    ";
    $sql .= "					       inner join db_depart b on b.coddepto   = a.p53_coddepto                        ";
    $sql .= "					 where a.p53_codigo   = protprocesso.p58_codigo                                       ";
    $sql .= "						 and a.p53_ordem    = (andpadrao.p53_ordem + 1)                                     ";
    $sql .= "					 limit 1 ) as proxdescr                                                               ";
    $sql .= "		from protprocesso                                                                           ";
    $sql .= "			   inner join cgm          on z01_numcgm   = p58_numcgm                                   ";
    $sql .= "				 inner join tipoproc     on p51_codigo   = p58_codigo                                   ";
    $sql .= "				                        and p51_tipoprocgrupo = {$grupo}                                ";
    $sql .= "				 inner join procandam    on p61_codandam = p58_codandam                                 ";
    $sql .= "				 left  join andpadrao    on p53_codigo   = p58_codigo                                   ";
    $sql .= "				 				            	  and p53_coddepto = p61_coddepto                                 ";
    $sql .= "                                      and p53_ordem    = (select count(*)                      ";
    $sql .= "                                                            from procandam pa                  ";
    $sql .= "                                                           where pa.p61_codproc = p58_codproc) ";
    $sql .= "				 left join arqproc      on p68_codproc  = protprocesso.p58_codproc                      ";
    $sql .= "				 $sJoinValidaDespacho                                                                   ";
    $sql .= "				 left join proctransferintand  on p87_codandam = p78_codandam                           ";
    $sql .= "				 left join procandamintand     on p86_codandam = p78_codandam                           ";
    $sql .= "																	and p86_codtrans = p87_codtransferint                         ";
    $sql .= "	 where p61_coddepto = ".db_getsession("DB_coddepto")."                                        ";
    $sql .= "		 and p68_codproc is null                                                                    ";
    $sql .= "    and not exists (select *                                                                   ";
    $sql .= "           from arqandam                                                                       ";
    $sql .= "           where p69_codandam = p61_codandam and p69_arquivado = 't')                          ";
    $sql .= "		 and (select p63_codtran                                                                    ";
    $sql .= "						from proctransferproc                                                               ";
    $sql .= "          			   inner join proctransand on p64_codtran = p63_codtran                         ";
    $sql .= "					 where p63_codproc = p58_codproc                                                      ";
    $sql .= "					 limit 1 ) is not null                                                                ";
    $sql .= "          {$where}                                                                             ";
    $sql .= "   order by $ordem desc                                                                        ";

    $rs = db_query($sql) or die($sql);
    $numrows = pg_num_rows($rs);

    if ($numrows > 0){  ?>
             <tr>
                <td colspan = 7 align='center' style="text-align: left; text-indent: 30px;"><b>Processos Existentes</b>

                </td>
             </tr>
             <tr>
                <td bgcolor='#999999'></td>
                <td align='center' bgcolor='#999999' style = "padding-right: 5px;"><b><a href='' onClick ='return js_ordena("p58_codproc");' >N.Controle</a></b></td>
                <td align='center' bgcolor='#999999' style = "padding-right: 5px;" ><b><a href='' onClick ='return js_ordena("p58_numero::integer");' >Processo    </a></b></td>
                <td align='center' bgcolor='#999999' style = "padding-right: 5px;" ><b><a href='' onClick ='return js_ordena("p58_dtproc") ;' >Data                </a></b></td>
                <td align='center' bgcolor='#999999' style = "padding-right: 5px;" ><b><a href='' onClick ='return js_ordena("z01_nome")   ;' >Titular             </a></b></td>
                <td align='center' bgcolor='#999999' style = "padding-right: 5px;" ><b><a href='' onClick ='return js_ordena("p51_descr")  ;' >Tipo                </a></b></td>
                <td align='center' bgcolor='#999999' style = "padding-right: 5px;" ><b><a href='' onClick ='return js_ordena("proxdepto")  ;' >Proximo Departamento</a></b></td>
             </tr>
             <tbody style='height:300;overflow:scroll;' id="listaProcesso">

             <?
                //<td align='center' bgcolor='#999999'><b>Depto padrão</b></td>


			$cont = 0;
      for ($i = 0;$i < $numrows;$i++){

				db_fieldsmemory($rs,$i);

				if ( $grupo == 2 ) {
	        $sSqlPrazoPrevisto = "select ov15_coddepto,
					                             descrdepto
					                        from processoouvidoriaprorrogacao
					                             inner join db_depart on coddepto = ov15_coddepto
					                       where ov15_protprocesso = {$p58_codproc}
					                         and ov15_ativo is true
					                       order by ov15_dtfim";

					$rsPrazoPrevisto      = db_query($sSqlPrazoPrevisto);
					$iLinhasPrazoPrevisto = pg_num_rows($rsPrazoPrevisto);
					$lProximoDepto        = false;

					if ( $iLinhasPrazoPrevisto > 0 ) {
					  for ( $iInd=0; $iInd < $iLinhasPrazoPrevisto; $iInd++ ) {
					    $oPrazo = db_utils::fieldsMemory($rsPrazoPrevisto,$iInd);
					    if ( $lProximoDepto ) {
					      $proxdepto = $oPrazo->ov15_coddepto;
					      $proxdescr = $oPrazo->descrdepto;
					      $lProximoDepto = false;
					    }
					    if ( $oPrazo->ov15_coddepto == db_getsession('DB_coddepto') ) {
					      $lProximoDepto = true;
					    }
					  }
					}
				}

				$p58_dtproc = db_formatar($p58_dtproc, "d");
				$passou=true;
				$sql_proc="select p63_codproc,
				                  p63_codtran
				             from proctransferproc
				            where p63_codproc=$p58_codproc";
				$result_proc=db_query($sql_proc) or die($sql_proc);
				if (pg_numrows($result_proc)!=0) {
					for ($yy=0; $yy<pg_numrows($result_proc); $yy++) {
						db_fieldsmemory($result_proc,$yy);
						$sql_and="select * from proctransand where p64_codtran=$p63_codtran";
						$result_and=db_query($sql_and) or die($sql_and);
						if (pg_numrows($result_and)==0) {
							$passou=false;
						}
					}
				}

				// echo "Processo : {$p58_codproc} passou : {$passou} <br>";

				if ($passou==true) {
          if ($p78_codandam != "") {
            if ($p87_codandam != "") {
              if ($p86_codandam != "") {
              	$cont ++;
								//           $setor = explode("#",$depto);
								$class = $p61_id_usuario == db_getsession("DB_id_usuario")?"class='dono'":null;
								$class = null;
								$cor = ($i%2 == 0?"#CCCCCC":"#FFFFFF");
								echo "<tr nowrap bgcolor='$cor'>
				                <td $class nowrap>
				                  <input type='checkbox' name='processos[]'  onclick=\"Envia_setor('".@$proxdepto."','".@$proxdescr."',this);\" value='".$p58_codproc."'>
				                </td>
				                <td $class nowrap style = 'padding-right: 7px;'>".$p58_codproc."</td>
				                <td $class nowrap style = 'padding-right: 7px;'>{$p58_numero}/{$p58_ano}</td>
				                <td $class nowrap style = 'padding-right: 7px'>".$p58_dtproc."</td>
				                <td $class nowrap style = 'padding-right: 7px;'>".$z01_nome."</td>
				                <td $class nowrap style = 'padding-right: 7px;'>".$p51_descr."</td>
				                <td $class nowrap style = 'padding-right: 7px;'>".@$proxdescr."</td>
				              </tr>";
							} else {
								$cont ++;
								//           $setor = explode("#",$depto);
								$class = $p61_id_usuario == db_getsession("DB_id_usuario")?"class='dono'":null;
								$class = null;
								$cor = ($i%2 == 0?"#CCCCCC":"#FFFFFF");
								echo "<tr nowrap bgcolor='$cor'>
				                <td $class nowrap>
				                  <input type='checkbox' name='processos[]' disabled  value='".$p58_codproc."'>
				                </td>
				                <td $class nowrap style = 'padding-right: 7px;'>".$p58_codproc."</td>
				                <td $class nowrap style = 'padding-right: 7px;'>{$p58_numero}/{$p58_ano}</td>
				                <td $class nowrap style = 'padding-right: 7px'>".$p58_dtproc."</td>
				                <td $class nowrap style = 'padding-right: 7px;'>".$z01_nome."</td>
				                <td $class nowrap style = 'padding-right: 7px;'>".$p51_descr."</td>
				                <td $class nowrap style = 'padding-right: 7px;'>".@$proxdescr."</td>
				              </tr>";
							}
						} else {
							$cont ++;
							//           $setor = explode("#",$depto);
							$class = $p61_id_usuario == db_getsession("DB_id_usuario")?"class='dono'":null;
							$class = null;
							//$cor = ($i%2 == 0?"#97b5e6":"#e796a4");
							$cor = ($i%2 == 0?"#CCCCCC":"#FFFFFF");
							echo "<tr nowrap bgcolor='$cor'>
				              <td $class nowrap>
				                <input type='checkbox' name='processos[]'  onclick=\"Envia_setor('".@$proxdepto."','".@$proxdescr."',this);\"  value='".$p58_codproc."'>
				              </td>
				              <td $class nowrap style = 'padding-right: 7px;'>".$p58_codproc."</td>
				              <td $class nowrap style = 'padding-right: 7px;'>{$p58_numero}/{$p58_ano}</td>
				              <td $class nowrap style = 'padding-right: 7px'>".$p58_dtproc."</td>
				              <td $class nowrap style = 'padding-right: 7px;'>".$z01_nome."</td>
				              <td $class nowrap style = 'padding-right: 7px;'>".$p51_descr."</td>
				              <td $class nowrap style = 'padding-right: 7px;'>".@$proxdescr."</td>
				            </tr>";
						}
					} else {
            if ($p87_codandam != "") {
            	            $cont ++;
							$class = $p61_id_usuario == db_getsession("DB_id_usuario")?"class='dono'":null;
							$class = null;
							$cor = ($i%2 == 0?"#CCCCCC":"#FFFFFF");
							echo "<tr nowrap bgcolor='$cor'>
				              <td $class nowrap>
				                <input type='checkbox' name='processos[]' disabled  value='".$p58_codproc."'>
				              </td>
				              <td $class nowrap style = 'padding-right: 7px;'>".$p58_codproc."</td>
				              <td $class nowrap style = 'padding-right: 7px;'>{$p58_numero}/{$p58_ano}</td>
				              <td $class nowrap style = 'padding-right: 7px;'>".$p58_dtproc."</td>
				              <td $class nowrap style = 'padding-right: 7px;'>".$z01_nome."</td>
				              <td $class nowrap style = 'padding-right: 7px;'>".$p51_descr."</td>
				              <td $class nowrap style = 'padding-right: 7px;'>".@$proxdescr."</td>
				            </tr>";
						} else {
							$cont ++;
							//           $setor = explode("#",$depto);
							$class = $p61_id_usuario == db_getsession("DB_id_usuario")?"class='dono'":null;
							$class = null;
							$cor = ($i%2 == 0?"#CCCCCC":"#FFFFFF");
							echo "<tr nowrap bgcolor='$cor'>
				              <td $class nowrap>
				                <input type='checkbox' name='processos[]'   onclick=\"Envia_setor('".@$proxdepto."','".@$proxdescr."',this);\"     value='".$p58_codproc."'>
				              </td>
				              <td $class nowrap style = 'padding-right: 7px;'>".$p58_codproc."</td>
				              <td $class nowrap style = 'padding-right: 7px;'>{$p58_numero}/{$p58_ano}</td>
				              <td $class nowrap style = 'padding-right: 7px;'>".$p58_dtproc."</td>
				              <td $class nowrap style = 'padding-right: 7px;'>".$z01_nome."</td>
				              <td $class nowrap style = 'padding-right: 7px;'>".$p51_descr."</td>
				              <td $class nowrap style = 'padding-right: 7px;'>".@$proxdescr."</td>
				            </tr>";
						}
					}
				}
      }
			echo "<label>Total de processos: <b>$cont</b></label>";
    } else {
      $db_botao = false;
      echo "<tr><td bgcolor='#999999'><b>Não existem processos</b></td></tr>";
    }

   ?>
              <td style='height:100%;'>&nbsp;</td>
            </tbody >
          </table>
          </fieldset>
        </td>
      </tr>
    </table>
  </form>
</center>
<script>


if(document.form1.p62_coddeptorec.value == 0){
  document.form1.p62_id_usorec.disabled = true;
}else{
  document.form1.p62_id_usorec.disabled = false;
}


sUrlRPC = "pro4_proctransf.RPC.php";

oDBGridProcessosDif = new DBGrid('difprocessos');
oDBGridProcessosDif.nameInstance = 'oDBGridProcessosDif';
oDBGridProcessosDif.setHeader(new Array('Processo','Requerente','Número Dias','Seguir no Andamento','obj'));
oDBGridProcessosDif.setHeight(290);
oDBGridProcessosDif.setCellWidth(new Array(60,180,100,110,10));
oDBGridProcessosDif.setCellAlign(new Array('center','left','center','center','center'));
oDBGridProcessosDif.aHeaders[4].lDisplayed = false;

function js_telaDiferencas(aListaDiferenca) {

  var sContent  = "<div id='msg' style='border-bottom: 2px groove white;padding:5px;background-color:white;vertical-align:bottom;font-weight:bold;width:98%;height:50px;text-align:left'> ";
      sContent += "Departamento escolhido difere do andamento padrão, favor digite o número de dias referente ao departamento escolhido.</div>                                              ";
      sContent += "<table width='100%' style='padding-top:20px;'> ";
      sContent += "  <tr>                             ";
      sContent += "    <td>                           ";
      sContent += "      <fieldset>                   ";
      sContent += "        <div id='listaDifProcesso'>";
      sContent += "        </div>                     ";
      sContent += "      </fieldset>                  ";
      sContent += "    </td>                          ";
      sContent += "  </tr>                            ";
      sContent += "  <tr align='center'>              ";
      sContent += "    <td>                           ";
      sContent += "      <input type='button' id='btnIncluir' value='Incluir'/>";
      sContent += "      <input type='button' id='btnFechar'  value='Fechar'/> ";
      sContent += "    </td>                          ";
      sContent += "  </tr>                            ";
      sContent += "</table>                           ";


  windowAuxiliarDias  = new windowAux('wnddias', 'Informe a quantidade de dias', 650, 500);
  windowAuxiliarDias.setContent(sContent);
  windowAuxiliarDias.show(100,300);

  $('btnFechar').observe("click",js_fecharJanela);
  $('btnIncluir').observe("click",js_validaCamposDif);
  $('window'+windowAuxiliarDias.idWindow+'_btnclose').observe("click",js_fecharJanela);

  oDBGridProcessosDif.show($('listaDifProcesso'));
  oDBGridProcessosDif.clearAll(true);

  aListaDiferenca.each(
    function (oProcesso,iInd){

      if ( oProcesso.lTemDepto ) {
        var sDisabled = '';
      } else {
        var sDisabled = 'disabled';
      }
      var sSelect  = "<select style='width:100%' id='segue"+oProcesso.p58_codproc+"' "+sDisabled+"> ";
          sSelect += "  <option value='false' >Não</option>                                         ";
          sSelect += "  <option value='true'  >Sim</option>                                         ";
          sSelect += "</select>                                                                     ";

      aRow = new Array();
      aRow[0] = oProcesso.p58_codproc;
      aRow[1] = oProcesso.p58_requer;
      aRow[2] = "<input style='width:100%' type='text' id='dia"+oProcesso.p58_codproc+"' value=''/>";
      aRow[3] = sSelect;
      aRow[4] = Object.toJSON(oProcesso);
      oDBGridProcessosDif.addRow(aRow);

    }
  );

  oDBGridProcessosDif.renderRows();

}


function js_fecharJanela(){
  windowAuxiliarDias.destroy();
}


function js_validaCamposDif(){

  var aCamposText  = $$("#listaDifProcesso input[type='text']");
  var lRetorno     = true;
  var aProcessoDif = new Array();

  if ( $F('p62_coddeptorec') == '' ) {
    alert('Departamento de recebimento não informado!');
    return false;
  }

  aCamposText.each(
    function ( eCampo, iInd ) {
      if ( eCampo.value.trim() == '') {
        alert('Número de Dias não informado!');
        lRetorno = false;
      } else if ( eCampo.value.trim() == '0' ) {
        alert('Número de Dias deve ser maior que zero!');
        lRetorno = false;
      }
    }
  );

  if ( !lRetorno ) {
    return false;
  }

 var oProcessoDif = new Array();

  oDBGridProcessosDif.aRows.each(
    function ( eRow, iInd ){

      var iCodProcesso = eRow.aCells[0].getValue();
      var iDias        = $('dia'+iCodProcesso).value;
      var lSegue       = false;

      var iNumOpt      = $('segue'+iCodProcesso).options.length;
      for ( var iIndOpt=0; iIndOpt < iNumOpt; iIndOpt++ ) {
        if ( $('segue'+iCodProcesso).options[iIndOpt].selected ) {
          lSegue = eval($('segue'+iCodProcesso).options[iIndOpt].value);
        }
      }

      oProcessoDif = new js_objProcesso(iCodProcesso,iDias,lSegue,true);
      aProcessoDif.push(oProcessoDif);

    }
  );

  var aListaChk    = js_getChkProcessos();
  var aProcessoSel = new Array();

  aListaChk.each(
    function ( eChk, iInd ) {
      if ( eChk.checked ) {
        var oProcesso = new js_objProcesso(eChk.value,0,false,false);
        aProcessoSel.push(oProcesso);
      }
    }
  );

  aProcessoSel.each(
    function ( oProcessoSel, iIndSel ){
      aProcessoDif.each(
        function ( oProcessoDif, iIndDif ){
          if ( oProcessoSel.iCodProc == oProcessoDif.iCodProc ) {
            oProcessoSel.iDias      = oProcessoDif.iDias;
            oProcessoSel.lSegue     = oProcessoDif.lSegue;
            oProcessoSel.lNovoDepto = oProcessoDif.lNovoDepto;
          }
        }
      );
    }
  );

  js_fecharJanela();
  js_incluirTransferencia(aProcessoSel);

}


function js_incluirTransferencia(aObjProcesso){

  js_divCarregando('Aguarde...','msgBox');

  var iNumOpt       = $('p62_id_usorec').options.length;
  var iIdUsuarioRec = '';

  if ( !$('p62_id_usorec').disabled ) {
    for ( var iIndOpt=0; iIndOpt < iNumOpt; iIndOpt++ ) {
      if ( $('p62_id_usorec').options[iIndOpt].selected ) {
        iIdUsuarioRec = $('p62_id_usorec').options[iIndOpt].value;
      }
    }
  }

  var sQuery  = 'sMethod=incluirTransferencia';
      sQuery += '&aObjProcesso='+Object.toJSON(aObjProcesso);
      sQuery += '&iCodDeptoRec='+$F('p62_coddeptorec');
      sQuery += '&iIdUsuarioRec='+iIdUsuarioRec;
      sQuery += '&iGrupo='+$F('grupo');

      document.form1.incluir.disabled = true;

  var oAjax   = new Ajax.Request( sUrlRPC, {
                                             method: 'post',
                                             parameters: sQuery,
                                             onComplete: js_retornoIncluirTransferencia
                                           }
                                );
}

function js_retornoIncluirTransferencia(oAjax){

  js_removeObj("msgBox");
  var aRetorno = eval("("+oAjax.responseText+")");
  var sExpReg  = new RegExp('\\\\n','g');

  alert(aRetorno.sMsg.urlDecode().replace(sExpReg,'\n'));

  document.form1.incluir.disabled = false;

  if ( aRetorno.lErro ) {
    return false;
  } else {
    url = "pro4_termorecebimento.php?codtran="+aRetorno.iCodTran;
    window.open(url,'','location=0');
    document.form1.submit();
  }

}



function js_getChkProcessos(){
  return $$('#listaProcesso input[type="checkbox"]');
}


function js_objProcesso(iCodProc,iDias,lSegue,lNovoDepto){

  this.iCodProc   = iCodProc;
  this.iDias      = iDias;
  this.lSegue     = lSegue;
  this.lNovoDepto = lNovoDepto;

}


function js_validaProcessos(){

  var aListaChk     = js_getChkProcessos();
  var aListaProc    = new Array();

  if ( $F('p62_coddeptorec') == '' ) {
    alert('Departamento de recebimento não informado!');
    return false;
  }

  aListaChk.each(
    function ( eChk,iInd ) {
      if ( eChk.checked ) {
        aListaProc.push(eChk.value);
      }
    }
  );

  if ( aListaProc.length == 0 ) {
    alert('Nenhum processo selecionado!');
    return false;
  }


  if ( $F('grupo') ==  2 ) {

    js_divCarregando('Aguarde...','msgBox');

    var sQuery  = 'sMethod=validaProximoDepto';
        sQuery += '&aListaProcesso='+Object.toJSON(aListaProc);
        sQuery += '&iCodDeptoRec='+$F('p62_coddeptorec');

    var oAjax   = new Ajax.Request( sUrlRPC, {
                                            method: 'post',
                                            parameters: sQuery,
                                            onComplete: js_retornoValidaProcesso
                                          }
                                  );
  } else {

    var aListaChk = js_getChkProcessos();
    var aProcesso = new Array();

    aListaChk.each(
      function ( eChk, iInd ) {
        if ( eChk.checked ) {
          var oProcesso = new js_objProcesso(eChk.value,0,false,false);
          aProcesso.push(oProcesso);
        }
      }
    );

    js_incluirTransferencia(aProcesso);

  }

}
function js_retornoValidaProcesso(oAjax){

  js_removeObj("msgBox");

  var aRetorno = eval("("+oAjax.responseText+")");

  if ( aRetorno.aListaDiferenca.length > 0 ) {

    js_telaDiferencas(aRetorno.aListaDiferenca);

  } else {

    var aListaChk = js_getChkProcessos();
    var aProcesso = new Array();

    aListaChk.each(
      function ( eChk, iInd ) {
        if ( eChk.checked ) {
          var oProcesso = new js_objProcesso(eChk.value,0,false,false);
          aProcesso.push(oProcesso);
        }
      }
    );

    js_incluirTransferencia(aProcesso);
  }

}




function js_ajaxRequest(iCoddepto) {
  if(iCoddepto!=""){
    var objUsuarios    = document.form1.p62_id_usorec;
    objUsuarios.disabled = true;

    js_divCarregando('Buscando usuarios','div_processando');

    var objUsuarios    = document.form1.p62_id_usorec;
    var url       = 'pro4_consusuariodeptoRPC.php';
    var parametro = "json={icoddepto:"+iCoddepto+"}";
    var objAjax   = new Ajax.Request (url,{
                                           method:'post',
                                           parameters:parametro,
                                           onComplete:carregaDadosSelect
                                         }
                                    );
  } else {

    $('p62_id_usorec').length   = 1;
    $('p62_id_usorec').disabled = true;

  }
}

function carregaDadosSelect(oResposta) {

  eval('var aUsuarios = '+oResposta.responseText);

  var objUsuarios    = document.form1.p62_id_usorec;
  objUsuarios.length = 0;

  for (var i = 0; i < aUsuarios.length; i++) {

    objUsuarios.options[i]       = new Option();
    objUsuarios.options[i].value = aUsuarios[i].id_usuario.urlDecode();
    objUsuarios.options[i].text  = aUsuarios[i].nome.urlDecode();

  }
  if(document.form1.usuario.value!=0 || document.form1.usuario.value!=""){
     document.form1.p62_id_usorec.value = document.form1.usuario.value;
  }

  objUsuarios.disabled = false;
  js_removeObj('div_processando');

}

function js_pesquisap62_id_usorec(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_tran','func_db_usuarios.php?funcao_js=parent.js_mostradb_usuarios1|0|1','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_tran','func_db_usuarios.php?pesquisa_chave='+document.form1.p62_id_usorec.value+'&funcao_js=parent.js_mostradb_usuarios','Pesquisa',false);
  }
}
function js_mostradb_usuarios(chave,erro){
  document.form1.nome.value = chave;
  if(erro==true){
    document.form1.p62_id_usorec.focus();
    document.form1.p62_id_usorec.value = '';
  }
}
function js_mostradb_usuarios1(chave1,chave2){
  document.form1.p62_id_usorec.value = chave1;
  document.form1.nome.value = chave2;
  db_iframe_tran.hide();
}
function js_pesquisap62_coddeptorec(mostra){
  var processa = true;
  var form = document.form1;
  var itens = form.elements.length;
  a = 0;
  for (i = 0; i < itens ;i++){
    if (form.elements[i].type=="checkbox"){
      if (form.elements[i].checked == true){
        a = a + 1;
      }
    }
  }
  if((a>=1) && (document.form1.depart.value !="") && (document.form1.depart.value != document.form1.p62_coddeptorec.value)){
     if(document.form1.p90_traminic.value == 2){
       alert('Departamento selecionado diferente do departamento padrão.');
       document.form1.p62_coddeptorec.value = document.form1.depart.value;
       processa = false;
     }
     if(document.form1.p90_traminic.value == 3){
       alert('Aviso...Departamento selecionado diferente do departamento padrão.');
       processa = true;
     }
  }else{
     processa = true;
  }
  if(processa == true){
    if(mostra==true){
      js_OpenJanelaIframe('top.corpo','db_iframe_tran','func_db_depart_transferencias.php?funcao_js=parent.js_mostradb_depart1|0|1&todasinstit=1','Pesquisa',true);
    }else{
      js_OpenJanelaIframe('top.corpo','db_iframe_tran','func_db_depart_transferencias.php?pesquisa_chave='+document.form1.p62_coddeptorec.value+'&funcao_js=parent.js_mostradb_depart&todasinstit=1&instituicao=0','Pesquisa',false);
    }
  }
}
function js_mostradb_depart(chave,erro){
  document.form1.descrdepto.value = chave;
  if(erro==true){
    document.form1.p62_coddeptorec.focus();
    document.form1.p62_coddeptorec.value = '';
  }
  //
  // funcao que processa uma requisicao ajax
  // para pesquisar os usuarios do departamento
  //
  js_ajaxRequest(document.form1.p62_coddeptorec.value);

}
function js_mostradb_depart1(chave1,chave2){
  document.form1.p62_coddeptorec.value = chave1;
  document.form1.descrdepto.value = chave2;
  //
  // funcao que processa uma requisicao ajax
  // para pesquisar os usuarios do departamento
  //
  js_ajaxRequest(chave1);
  db_iframe_tran.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_tran','func_proctransfer.php?funcao_js=parent.js_preenchepesquisa|0','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe.hide();
  location.href = '<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>'+"?chavepesquisa="+chave;
}

function js_ordena(ord){
  document.form1.ordem.value = ord;
  document.form1.usuario.value = document.form1.p62_id_usorec.value;
  document.form1.submit();
  return false;
}

     function cria_obj(){
        var usurec = document.createElement("INPUT");
        usurec.setAttribute("type","hidden");
        usurec.setAttribute("name","p62_id_usorec");
        usurec.setAttribute("value",campos.document.form1.p62_id_usorec.value);
        document.getElementById("cria").appendChild(usurec);

        var deptorec = document.createElement("INPUT");
        deptorec.setAttribute("type","hidden");
        deptorec.setAttribute("name","p62_coddeptorec");
        deptorec.setAttribute("value",campos.document.form1.p62_coddeptorec.value);
        document.getElementById("cria").appendChild(deptorec);

        var sbt = document.createElement("INPUT");
        sbt.setAttribute("type","hidden");
        sbt.setAttribute("name","db_opcao");
        sbt.setAttribute("value","Incluir");
        sbt.setAttribute("style","visibility:hidden");
        document.getElementById("cria").appendChild(sbt);
        document.getElementById("cria").submit();
       //alert(document.form1.p62_id_usorec.value+" "+document.form1.p62_coddeptorec.value);
    }
 function valida(form){

    itens = form.elements.length;
    var a = 0;
    for (i = 0; i < itens ;i++){
       if (form.elements[i].type=="checkbox"){
          if (form.elements[i].checked == true){
             a = a + 1;
          }
       }
   }
   if (a == 0){
      alert("Por favor escolha algum processo!");
      return false;
   }else{
     //alert("ok");
     return true;
   }


 }
  function Envia_setor(coddepto,descrdepto,objCheck){
    var copiaDepart = false;
    var form = document.form1;
    var itens = form.elements.length;
    a = 0;
    for (i = 0; i < itens ;i++){
       if (form.elements[i].type=="checkbox"){
          if (form.elements[i].checked == true){
             a = a + 1;
          }
       }
    }

   if(document.form1.p90_traminic.value == 2){
     // Não permitir escolher departamentos diferentes;
   if (a == 0) {
    document.form1.p62_coddeptorec.value = "";
    document.form1.descrdepto.value = "";
    document.form1.depart.value = "";
    copiaDepart = false;
    var objUsuarios = document.form1.p62_id_usorec;
    objUsuarios.disabled = true;
   }else if((a>1 || document.form1.p62_coddeptorec.value!="" )&& document.form1.p62_coddeptorec.value!=coddepto){
       // verificar se ja tem algum marcado
        if (objCheck.checked == true) {
      alert('Departamento padrão diferente do departamento selecionado.');
      objCheck.checked = false;
    }
     }else{

       copiaDepart = true;
     }


   }else if(document.form1.p90_traminic.value == 3){
     //permitir escolher departamentos diferentes, mas avisar o usuário
     if((a>1 || document.form1.p62_coddeptorec.value!="" )&& document.form1.p62_coddeptorec.value!=coddepto){
       // verificar se ja tem algum marcado
       if(objCheck.checked== true){
         alert('Aviso...Departamento padrão diferente do departamento selecionado.');
     copiaDepart = true;
       }
     }else if(a==0){
    document.form1.p62_coddeptorec.value = "";
        document.form1.descrdepto.value = "";
        document.form1.depart.value = "";
    copiaDepart = false;
    var objUsuarios    = document.form1.p62_id_usorec;
    objUsuarios.disabled = true;
   }else{
    copiaDepart = true;
   }

   }else{
     // parametro == 1 -- permitir escolher departamentos diferentes
     copiaDepart = true;
   }

    if(copiaDepart == true){
      if (coddepto!=""){
        document.form1.p62_coddeptorec.value = coddepto;
        document.form1.descrdepto.value = descrdepto;
        document.form1.depart.value = coddepto;
        js_ajaxRequest(coddepto);
        var objUsuarios    = document.form1.p62_id_usorec;
        objUsuarios.disabled = true;
      }
    }

  }

  function js_chamaajax(){
     if(document.form1.p62_coddeptorec.value!=0){
       js_ajaxRequest(document.form1.p62_coddeptorec.value);
     }
  }

</script>