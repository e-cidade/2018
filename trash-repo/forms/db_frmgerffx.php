<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

//MODULO: pessoal
include ("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clrotulo = new rotulocampo;
$clgerfsal->rotulo->label();
$clrotulo->label("z01_nome");
$clrotulo->label("rh27_descr");
$clrotulo->label("rh27_form");
$clrotulo->label("r20_tpp");
$clrotulo->label("r70_descr");
$clrotulo->label("DBtxt23");
$clrotulo->label("DBtxt25");

if($gerf == "fs"){
  $dfolha = " Folha de salário";
}else if($gerf == "com"){
  $dfolha = " Folha complementar";
}else if($gerf == "f13"){
  $dfolha = " Folha de décimo terceiro";
}else if($gerf == "fr"){
  $dfolha = " Folha de rescisão";
}

?>
<form name="form1" method="post" action="">
<center>
<table border="0" width="100%" height="90%">
  <tr>
    <td colspan="2" align="center">
      <strong>
      <?
      echo $dfolha;
      ?>
      </strong>
    </td>
  </tr>
  <tr>
    <td align="right" nowrap title="Digite o Ano / Mes de competência" >
      <strong>Ano / Mês :&nbsp;&nbsp;</strong> 
    </td>
    <td>
      <?
      db_input('DBtxt23', 4, $IDBtxt23, true, 'text', $db_opcao == 1 ? 1 : 3, "onchange='document.form1.submit();'", 'r14_anousu');
      ?>
      &nbsp;/&nbsp;
      <?
      db_input('DBtxt25', 2, $IDBtxt25, true, 'text', $db_opcao == 1 ? 1 : 3, "onchange='document.form1.submit();'", 'r14_mesusu');
      db_input('gerf', 15, 0, true, 'hidden', 3, "");
      db_input('data_de_admissao', 15, 0, true, 'hidden', 3, "");
      db_input('rh27_form', 15, $Irh27_form, true, 'hidden', 3, "");
      ?>
    </td>
  </tr>
  <tr>
    <td align="right" nowrap title="<?=@$Tr14_regist?>">
      <?
      db_ancora(@ $Lr14_regist, "js_pesquisar14_regist(true);", $db_opcao);
      ?>
    </td>
    <td> 
      <?
      db_input('r14_regist', 8, $Ir14_regist, true, 'text', $db_opcao, " onchange='js_pesquisar14_regist(false);'")
      ?>
      <?
      db_input('z01_nome', 60, $Iz01_nome, true, 'text', 3, '');
      ?>
    </td>
  </tr>
  <tr>
    <td align="right" nowrap title="<?=@$Tr14_lotac?>">
      <?
      db_ancora(@ $Lr14_lotac, "js_pesquisar14_lotac(true);", 3);
      ?>
    </td>
    <td> 
      <?
      db_input('r14_lotac', 8, $Ir14_lotac, true, 'text', 3, " onchange='js_pesquisar14_lotac(false);'")
      ?>
      <?
      db_input('r70_descr', 60, $Ir70_descr, true, 'text', 3, '');
      ?>
    </td>
  </tr>  
  <tr>
    <td align="center" colspan="2" width="80%">
      <fieldset>
      <table border="0">
        <tr>
          <td align="left" nowrap title="<?=@$Tr14_rubric?>">
            <?
            db_ancora(@ $Lr14_rubric, "js_pesquisar14_rubric(true);", (($db_opcao==1)?"1":"3"));
            ?>
          </td>
            <?
            //Se for folha de rescisão, colocará o LABEL da TPP
            if($gerf == "fr"){
              echo "<td align='left' nowrap title='$Tr20_tpp'>
                      $Lr20_tpp
                    </td>";
            }else if($gerf == "fs" || $gerf == "com"){
              echo "<td align='left' nowrap title='$Tr20_tpp'>
                      $Lr14_pd
                    </td>";
	    }
            ?>
          <td align="left" nowrap title="<?=@$Tr14_quant?>">
            <?=@$Lr14_quant?>
          </td>
          <td align="left" nowrap title="<?=@$Tr14_valor?>">
            <?=@$Lr14_valor?>
          </td>
        </tr>
        <tr>
          <td> 
            <?
            db_input('r14_rubric', 8, $Ir14_rubric, true, 'text', (($db_opcao==1)?"1":"3"), " onchange='js_pesquisar14_rubric(false);'")
            ?>
            <?
            db_input('rh27_descr', 30, $Irh27_descr, true, 'text', 3, '');
            ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          </td>
            <?
            // Se for folha de rescisão, colocará o CAMPO da TPP
            if($gerf == "fr"){
              echo "<td>";
	            $arr_tpp = Array("V"=>"Vencida","P"=>"Proporcional","S"=>"Saldo");
              db_select("r20_tpp",$arr_tpp,true,(($db_opcao==1)?"1":"3"));
              echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
              echo "</td>";
            }else if($gerf == "fs" || $gerf == "com"){
              echo "<td>";
	            $arr_pd = Array("1"=>"Provento",
	                            "2"=>"Desconto",
	                            "3"=>"Base");
              db_select("r14_pd",$arr_pd,true,(($db_opcao==1)?"1":"3"));
              echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
              echo "</td>";
            }
            ?>
          </td>
          <td>
            <?
            if(!isset($r14_quant) || (isset($r14_quant) && trim($r14_quant)=="")){
              $r14_quant = '0';
            }
            db_input('r14_quant', 15, $Ir14_quant, true, 'text', $db_opcao, "' ");
            ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          </td>
          <td>
            <?
            if(!isset($r14_valor) || (isset($r14_valor) && trim($r14_valor)=="")){
              $r14_valor = '0';
            }
            db_input('r14_valor', 15, $Ir14_valor, true, 'text', $db_opcao, "");
            ?>
          </td>
        </tr>
      </table>
      </fieldset>
    </td>
  </tr>   
  <tr>
    <td colspan="2" align="center" height="5%">
      <br>
      <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?>  onclick="return js_vercampos();" onblur="if(document.form1.novo.style.visibility=='hidden')js_campos('regist');">
      <input name="novo" type="button" id="cancelar" value="Novo" onclick="js_submita();" <?=(($db_opcao==1)?"style='visibility:hidden;'":"")?> onblur='js_campos("regist");'>
      <br>
    </td>
  </tr>
  <tr>
    <td colspan="2" width="100%" height="60%" valign="top"  align="center">  
      <?
      // $sigla - É a sigla a ser utilizada no select.
      // $campoextra - É para quando as tabelas tiverem campos TPP
      // $mostracamp - É para quando o TPP for apresentado no Select ser mostrado no IFRAME_SELECIONA

      // $whereextra - Como o campo TPP é PK juntamente com o REGISTRO, ANOUSU e MESUSU em algumas tabelas, essas tabelas
      // poderão retornar o mesmo registro, mesmo anousu e mesmo mesusu com diferentes TPP, assim, quando o usuário clicar
      // em A ('alteração') ou E ('exclusão'), a linha q foi clicada, não deverá mais aparecer e as outras com diferentes
      // TPP devem continuar aparecendo... $whereextra controla isso.

      $campoextra = "";
      $mostracamp = "";
      if($gerf == "fs"){
        $sigla = "r14_";
        $campoextra = ", case 
                           when r14_pd = 1 then 'Provento' 
                           when r14_pd = 2 then 'Desconto'
                           else 'Base' 
                         end as r14_pd ";
        
        $mostracamp = ",r14_pd";
      }else if($gerf == "fr"){
        $sigla = "r20_";
        $campoextra = ", r20_tpp";
        $mostracamp = ",r20_tpp";
        $whereextra222 = true;
      }else if($gerf == "f13"){
        $sigla = "r35_";
      }else if($gerf == "com"){
        $sigla = "r48_";
        $campoextra = ", case 
                            when r48_pd = 1 then 'Provento' 
                            when r48_pd = 2 then 'Desconto'
                            else 'Base' 
                         end as r14_pd ";
        
        $mostracamp = ",r14_pd";
      }
      $dbwhere = "      ".$sigla."regist = ".@$r14_regist ;
      $dbwhere .= " and ".$sigla."anousu = $r14_anousu ";
      $dbwhere .= " and ".$sigla."mesusu = $r14_mesusu ";
      // Para controlar a INSTITUIÇÃO
      $dbwhere .= " and ".$sigla."instit = ".db_getsession("DB_instit");

      if(isset ($r14_rubric) && trim($r14_rubric) != "" && !isset($incluir) && !isset($alterar)){
        if(!isset($whereextra222)){
          $dbwhere .= " and ".$sigla."rubric <> '$r14_rubric' ";
	}else if(isset($r20_tpp) && trim($r20_tpp)!=""){
      	  $dbwhere .= " and ".$sigla."tpp||".$sigla."rubric <> '".$r20_tpp.$r14_rubric."' ";
	}
      }
      
      $campos = $sigla."anousu as r14_anousu,".  $sigla."mesusu  as r14_mesusu,". $sigla."regist  as r14_regist,". $sigla."rubric as r14_rubric,
                z01_numcgm, z01_nome".$campoextra.", rh27_descr ,".  $sigla."lotac as r14_lotac,
                r70_descr,". $sigla."quant as r14_quant,".  $sigla."valor as r14_valor";
      $orderby= $sigla."regist,". $sigla."rubric";

      $chavepri = array ("r14_anousu" => @ $r14_anousu, "r14_mesusu" => @ $r14_mesusu, "r14_regist" => @ $r14_regist, "r14_rubric" => @ $r14_rubric);
      
      // Seta TPP como chave primária.
      if($gerf == "fr"){
      	$chavepri["r20_tpp"] = @$r20_tpp;
      }
      $cliframe_alterar_excluir->chavepri = $chavepri;
      if($gerf == "fs"){
      $cliframe_alterar_excluir->sql = $clgerfsal->sql_query_seleciona(
                                                   null,
                                                   null,
                                                   null,
                                                   null,
                                                   $campos,
                                                   $orderby,
                                                   $dbwhere);
      }else if($gerf == "fr"){
      $cliframe_alterar_excluir->sql = $clgerfres->sql_query_seleciona(
                                                   null,
                                                   null,
                                                   null,
                                                   null,
                                                   null,
                                                   $campos,
                                                   $orderby,
                                                   $dbwhere);
      }else if($gerf == "f13"){
      $cliframe_alterar_excluir->sql = $clgerfs13->sql_query_seleciona(
                                                   null,
                                                   null,
                                                   null,
                                                   null,
                                                   $campos,
                                                   $orderby,
                                                   $dbwhere);
      }else if($gerf == "com"){
      $cliframe_alterar_excluir->sql = $clgerfcom->sql_query_seleciona(
                                                   null,
                                                   null,
                                                   null,
                                                   null,
                                                   $campos,
                                                   $orderby,
                                                   $dbwhere);
      }
      // echo $cliframe_alterar_excluir->sql;
      $cliframe_alterar_excluir->campos   = "r14_rubric,rh27_descr".$mostracamp.",r14_quant,r14_valor";
      $cliframe_alterar_excluir->opcoes   = 3;
      $cliframe_alterar_excluir->legenda  = "";
      $cliframe_alterar_excluir->iframe_height = "70%";
      $cliframe_alterar_excluir->iframe_width  = "95%";
      $cliframe_alterar_excluir->opcoes   = 1;
      $cliframe_alterar_excluir->fieldset = false;
      $cliframe_alterar_excluir->iframe_alterar_excluir(1);
      ?>
    </td>
  </tr>
  <tr>
    <td colspan="2" width="100%" valign="top" align="center" id="caixa_de_texto" height="15%" valign="top"></td>
  </tr>
 </table>
  </center>
</form>
<script>
function js_campos(opcao){
  <?
  if(!isset($opcao) && !isset($r14_regist)){
    echo "js_tabulacaoforms('form1','r14_regist',true,1,'r14_regist',true);";
  }else if(isset($opcao) && $opcao == "alterar"){
    echo "js_tabulacaoforms('form1','r14_quant',true,1,'r14_quant',true);";
  }else if(isset($opcao) && $opcao == "excluir"){
    echo "js_tabulacaoforms('form1','excluir',true,1,'excluir',true);";
  }else if(isset($r14_regist)){
    echo "js_tabulacaoforms('form1','r14_rubric',true,1,'r14_rubric',true);";
  }
  ?>
}
function js_vercampos(){
  // Verificar se algum campo ficou em branco
  <?
  if($db_opcao != 3 && $db_opcao != 33){
    echo '
          erro = 0;
          if(document.form1.r14_regist.value == ""){
            alert("Código do funcionário não informado");
            document.form1.r14_regist.focus();
            erro++;
          }else if(document.form1.r14_lotac.value == ""){
            alert("Lotação do funcionário não informada");
            document.form1.r14_lotac.focus();
            erro++;
          }else if(document.form1.r14_rubric.value == ""){
            alert("Rubrica não informada");
            document.form1.r14_rubric.focus();
            erro++;  
          }else if((document.form1.r14_quant.value == "" || document.form1.r14_quant.value == "0") && (document.form1.rh27_form.value == "T" || document.form1.rh27_form.value == "t")){
            alert("Quantidade não informada");
            document.form1.r14_quant.select();
            document.form1.r14_quant.focus();
            erro++;
          }else if((document.form1.r14_valor.value == "" || document.form1.r14_valor.value == "0") && (document.form1.rh27_form.value == "F" || document.form1.rh27_form.value == "f")){
            alert("Valor não informado");
            document.form1.r14_valor.select();
            document.form1.r14_valor.focus();
            erro++;
          }

          if(erro > 0){
            return false;
          }else{
            if(document.form1.r14_quant.value == ""){
              document.form1.r14_quant.value = 0;
            }
            if(document.form1.r14_valor.value == ""){
              document.form1.r14_valor.value = 0;
            }
            return true;
          }
         ';
  }else{
    echo "return true;";
  }
  ?>
}
function js_submita(){
  location.href = "pes1_gerffx001.php?r14_anousu="+document.form1.r14_anousu.value+"&r14_mesusu="+document.form1.r14_mesusu.value+"&r14_regist="+document.form1.r14_regist.value+"&gerf="+document.form1.gerf.value;
}
function js_pesquisar14_regist(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_rhpessoal','func_rhpessoal.php?testarescisao=<?=($gerf == "fs" ? "ar" : ($gerf == "fr" ? "af" : "r"))?>&funcao_js=parent.js_mostrapessoal1|rh01_regist|z01_nome&instit=<?=(db_getsession("DB_instit"))?>&chave_r01_mesusu='+document.form1.r14_mesusu.value+'&chave_r01_anousu'+document.form1.r14_anousu.value,'Pesquisa',true);
  }else{
    if(document.form1.r14_regist.value != ''){ 
       js_OpenJanelaIframe('top.corpo','db_iframe_rhpessoal','func_rhpessoal.php?testarescisao=<?=($gerf == "fs" ? "ar" : ($gerf == "fr" ? "af" : "r"))?>&pesquisa_chave='+document.form1.r14_regist.value+'&funcao_js=parent.js_mostrapessoal&instit=<?=(db_getsession("DB_instit"))?>','Pesquisa',false);
    }else{
      document.form1.z01_nome.value = '';
      location.href = "pes1_gerffx001.php?gerf="+document.form1.gerf.value; 
    }
  }
}
function js_mostrapessoal(chave,erro){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.r14_regist.focus(); 
    document.form1.r14_regist.value = ''; 
  }else{
    js_submita();
  }
}
function js_mostrapessoal1(chave1,chave2){
  document.form1.r14_regist.value = chave1;
  document.form1.z01_nome.value   = chave2;
  db_iframe_rhpessoal.hide();
  js_submita();
}

function js_pesquisar14_rubric(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_rhrubricas','func_rhrubricasnovo.php?funcao_js=parent.js_mostrarubricas1|rh27_rubric|rh27_descr|rh27_limdat|formula|rh27_obs|rh27_pd&instit=<?=(db_getsession("DB_instit"))?>','Pesquisa',true);
  }else{
    if(document.form1.r14_rubric.value != ''){     	
      quantcaracteres = document.form1.r14_rubric.value.length;
      for(i=quantcaracteres;i<4;i++){
        document.form1.r14_rubric.value = "0"+document.form1.r14_rubric.value;        
      }
      js_OpenJanelaIframe('top.corpo','db_iframe_rhrubricas','func_rhrubricasnovo.php?ret=pd&pesquisa_chave='+document.form1.r14_rubric.value+'&funcao_js=parent.js_mostrarubricas&instit=<?=(db_getsession("DB_instit"))?>','Pesquisa',false);
    }else{
      document.form1.rh27_descr.value = '';
      document.form1.rh27_form.value  = '';
      document.form1.r14_rubric.value  = '';
      document.getElementById('caixa_de_texto').innerHTML = "";
    }
  }
}
function js_mostrarubricas(chave,chave2,chave3,chave4,chave5,erro){
  document.form1.rh27_descr.value  = chave;
  if(erro==true){
    document.getElementById('caixa_de_texto').innerHTML = "";
    document.form1.rh27_form.value = '';
    document.form1.r14_rubric.value = '';
    document.form1.r14_rubric.focus();
  }else{
    document.getElementById('caixa_de_texto').innerHTML = "<font color='red'><b>"+chave4+"</b></font>";
    document.form1.rh27_form.value  = chave3;
    if(document.form1.r14_pd){
      valor = 0;
      if( chave5 == 2 ){
        valor = 1;
      }
      document.form1.r14_pd.options[valor].selected = true;
    }
  }
}
function js_mostrarubricas1(chave1,chave2,chave3,chave4,chave5,chave6){
  document.form1.r14_rubric.value  = chave1;
  document.form1.rh27_descr.value  = chave2;
  document.form1.rh27_form.value   = chave4;
  document.getElementById('caixa_de_texto').innerHTML = "<font color='red'><b>"+chave5+"</b></font>";

  if(document.form1.r14_pd){
    valor = 0;
    if(chave6 == 2){
      valor = 1;
    }
    document.form1.r14_pd.options[valor].selected = true;
  }
  db_iframe_rhrubricas.hide();
}

function js_pesquisar14_lotac(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframelotacao','func_lotacao.php?funcao_js=parent.js_mostralotacao1|r70_codigo|r70_descr&instit=<?=(db_getsession("DB_instit"))?>&chave_r70_mesusu='+document.form1.r14_mesusu.value+'&chave_r70_anousu'+document.form1.r14_anousu.value,'Pesquisa',true);
  }else{
    if(document.form1.r14_lotac.value != ''){ 
      js_OpenJanelaIframe('top.corpo','db_iframelotacao','func_lotacao.php?pesquisa_chave='+document.form1.r14_lotac.value+'&funcao_js=parent.js_mostralotacao&instit=<?=(db_getsession("DB_instit"))?>&chave_r70_mesusu='+document.form1.r14_mesusu.value+'&chave_r70_anousu'+document.form1.r14_anousu.value,'Pesquisa',false);
    }else{
      document.form1.r70_descr.value = ''; 
    }
  }
}
function js_mostralotacao(chave,erro){
  document.form1.r70_descr.value = chave; 
  if(erro==true){ 
    document.form1.r14_lotac.focus(); 
    document.form1.r14_lotac.value = ''; 
  }
}
function js_mostralotacao1(chave1,chave2){
  document.form1.r14_lotac.value = chave1;
  document.form1.r70_descr.value = chave2;
  db_iframelotacao.hide();
}
</script>