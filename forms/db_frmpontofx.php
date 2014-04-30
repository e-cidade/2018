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
require_once ("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clpontofx->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("rh27_limdat");
$clrotulo->label("rh27_descr");
$clrotulo->label("rh27_form");
$clrotulo->label("r29_tpp");
$clrotulo->label("r70_descr");
$clrotulo->label("DBtxt23");
$clrotulo->label("DBtxt25");
$iPonto = "";

if($ponto == "fx"){
  $dponto = " Ponto fixo";
  $iPonto = 10;
}else if($ponto == "fs"){
  $dponto = " Ponto de Salário";
  $iPonto = 1;
}else if($ponto == "fa"){
  $dponto = " Ponto de Adiantamento";
  $iPonto = 2;
}else if($ponto == "com"){
  $dponto = " Ponto Complementar";
  $iPonto = 8;
}else if($ponto == "f13"){
  $dponto = " Ponto de Décimo Terceiro";
  $iPonto = 5;
}else if($ponto == "fe"){
  $dponto = " Ponto de Férias";
  $iPonto = 3;
}else if($ponto == "fr"){
  $dponto = " Ponto de Rescisão";
  $iPonto = 4;
}

?>

<style>
  #z01_nome, #r70_descr{
    width: 638px;
  }

  #r90_rubric, #rh27_descr, #r90_datlim, #r90_quant{
    margin-right: 8px;
  }
</style>

<script>

function js_getDadosPadroes() {
  
  var sUrl         = 'pes1_rhrubricas.RPC.php';
  var oParametros  = new Object();
  var msgDiv       = "Pesquisando dados padrão da rubrica. Aguarde...";
  
  oParametros.sExecucao      = 'BuscaPadroesRubrica';  
  oParametros.sCodigoRubrica = $F('r90_rubric');
  
  js_divCarregando(msgDiv,'msgBox');
   
  var oAjax = new Ajax.Request(
    sUrl, 
    {
      method     : 'post',
      parameters : 'json=' + Object.toJSON(oParametros),
      onComplete : js_retornoDadosPadroes
    }
  );   
}

function js_retornoDadosPadroes(oAjax) {

  js_removeObj('msgBox');

  var oRetorno         = eval("("+oAjax.responseText+")");

  $('r90_quant').value = oRetorno.nQuantidadePadrao;
  $('r90_valor').value = oRetorno.nValorPadrao;

}

function js_calcular(iMatricula, iPonto){

   /*
       REQUISITOS PARA CALCULO de FOLHA:
       - tipo de folha  = iPonto
       - tipo de resumo = m (matricula)
       - tipo de filtro = s (selecionados)
       - db_debug       = 'false'
   */
   
   if ( document.getElementById('r90_regist').value == null || document.getElementById('r90_regist').value == '') {
   
     alert('Selecione uma Matricula');
     return false;
   } else {
     
     js_OpenJanelaIframe('top.corpo','db_iframe_ponto','pes4_gerafolha002.php?lAutomatico=1&iMatricula='+iMatricula+'&iPonto='+iPonto+'','Cálculo Financeiro',true);
     
     //setTimeout(parent.document.getElementById('pesquisar').click(), 2000);
     
     
   } 

}

function js_consultar(iMatricula){
   
   //pes3_gerfinanc001.php
   if ( document.getElementById('r90_regist').value == null || document.getElementById('r90_regist').value == '') {
   
     alert('Selecione uma Matricula');
     return false;
   } else {
     document.location.href='pes3_gerfinanc001.php?lConsulta=1&iMatricula='+iMatricula;
   }  
}

</script> 

<form name="form1" method="post" action="">
<center>
<fieldset style="width: 800px; margin-top: 20px;">
<legend><b><?php echo $dponto; ?></b></legend>
<table border="0" width="100%" height="90%">
  <tr>
    <td title="Digite o Ano / Mes de competência" >
      <strong>Ano / Mês :&nbsp;&nbsp;</strong> 
      
    </td>
    <td>
      <?
      db_input('DBtxt23', 4, $IDBtxt23, true, 'text', 3, "onchange='js_submita();'", 'r90_anousu');
      ?>
      &nbsp;/&nbsp;
      <?
      db_input('DBtxt25', 2, $IDBtxt25, true, 'text', 3, "onchange='js_submita();'", 'r90_mesusu');
      db_input('ponto', 15, 0, true, 'hidden', 3, "");
      db_input('data_de_admissao', 15, 0, true, 'hidden', 3, "");
      db_input('rh27_form', 15, $Irh27_form, true, 'hidden', 3, "");
      ?>
      <input type='button' id='calcular' value='Calcular' name='calcular' onclick="js_calcular(r90_regist.value ,<?=$iPonto ?>);" />
    </td>
  </tr>
  <tr>
    <td title="<?=@$Tr90_regist?>">
      <?
      db_ancora(@ $Lr90_regist, "js_pesquisar90_regist(true);", $iDbOpcao);
      ?>
    </td>
    <td> 
      <?
      db_input('r90_regist', 8, $Ir90_regist, true, 'text', $iDbOpcao, " onchange='js_pesquisar90_regist(false);' tabIndex=1 ")
      ?>
      <?
      db_input('z01_nome', 60, $Iz01_nome, true, 'text', 3, '');
      ?>
    </td>
  </tr>
  <tr>
    <td title="<?=@$Tr90_lotac?>">
      <?
      db_ancora(@ $Lr90_lotac, "js_pesquisar90_lotac(true);", 3);
      ?>
    </td>
    <td> 
      <?
      db_input('r90_lotac', 8, $Ir90_lotac, true, 'text', 3, " onchange='js_pesquisar90_lotac(false);'")
      ?>
      <?
      db_input('r70_descr', 60, $Ir70_descr, true, 'text', 3, '');
      ?>
    </td>
  </tr>  
  <tr>
    <td>&nbsp</td>
  </tr>
  <tr>
    <td align="center" colspan="2">  
      <fieldset>
        <legend>Rubrica</legend>
        <table border="0">
          <tr>
            <td align="left" nowrap title="<?=@$Tr90_rubric?>">
              <?
              db_ancora(@ $Lr90_rubric, "js_pesquisar90_rubric(true);", (($db_opcao==1)?"1":"3"));
              ?>
            </td>
              <?
              // Se for ponto fixo ou for ponto de salário, colocará o LABEL do ano/mês limite...
              if($ponto == "fx" || $ponto == "fs"){
                echo "<td align='left' nowrap title='$Tr90_datlim'>
                        $Lr90_datlim
                      </td>";
              // Caso contrário, se for ponto de férias ou de rescisão, colocará o LABEL da TPP
              }else if($ponto == "fe" || $ponto == "fr"){
                echo "<td align='left' nowrap title='$Tr29_tpp'>
                        $Lr29_tpp
                      </td>";
              }
              ?>
            <td align="left" nowrap title="<?=@$Tr90_quant?>">
              <?=@$Lr90_quant?>
            </td>
            <td align="left" nowrap title="<?=@$Tr90_valor?>">
              <?=@$Lr90_valor?>
            </td>
          </tr>
          <tr>
            <td> 
              <?
              db_input('r90_rubric', 8, $Ir90_rubric, true, 'text', (($db_opcao==1)?"1":"3"), " onchange='js_pesquisar90_rubric(false);' tabIndex=2 ")
              ?>
              <?
              db_input('rh27_descr', 30, $Irh27_descr, true, 'text', 3, '');
              ?>
            </td>
              <?
              // Se for ponto fixo ou for ponto de salário, colocará o CAMPO do ano/mês limite...
              $tabulacao = 4;
              if($ponto == "fx" || $ponto == "fs"){
                $tabulacao++;
                echo "<td>";
                db_input('r90_datlim', 15, $Ir90_datlim, true, 'text', 3, "onChange='js_calculaQuant(this.value);' onKeyUp='js_mascaradata(this.value);' tabIndex=3 ");
                db_input('rh27_limdat', 15, $Irh27_limdat, true, 'hidden', 3, "");
                echo "</td>";
              // Caso contrário, se for ponto de férias ou de rescisão, colocará o CAMPO da TPP
              }else if($ponto == "fe" || $ponto == "fr"){
                $tabulacao++;
                echo "<td>";
                db_input('r29_tpp', 5, $Ir29_tpp, true, 'text', $db_opcao, " tabIndex=3 ");
                echo "</td>";
              }
              ?>
            </td>
            <td>
              <?
              if(!isset($r90_quant) || (isset($r90_quant) && trim($r90_quant)=="")){
                $r90_quant = '0';
              }
              db_input('r90_quant', 15, $Ir90_quant, true, 'text', $db_opcao, "onchange='js_calculaDataLimit();' tabIndex=$tabulacao ");
              $tabulacao++;
              
              db_input('rh27_presta',10,'',true,'hidden',3);
              
              ?>
            </td>
            <td>
              <?
              if(!isset($r90_valor) || (isset($r90_valor) && trim($r90_valor)=="")){
                $r90_valor = '0';
              }
              db_input('r90_valor', 15, $Ir90_valor, true, 'text', $db_opcao, " tabIndex=$tabulacao ");
              $tabulacao++;
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
      
      
      <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?>  onclick="return js_vercampos();" tabIndex=<?=$tabulacao?> <?=(($db_opcao==1)?"onblur='document.form1.r90_regist.select();document.form1.r90_regist.focus();'":"onblur='document.form1.novo.focus();'")?>>
      
      
      
      <? if (!isset($_GET['lConsulta'])){ ?>
      <input type='button' id='consultar' value='Consultar' name='consultar' onclick="js_consultar(<?=$r90_regist ?>)" />
      <? } ?>
     
      
      <?
      $tabulacao++;
      ?>
      <input name="novo" type="button" id="cancelar" value="Novo" onclick="js_submita();" <?=(($db_opcao==1)?"style='visibility:hidden;'":"")?>  tabIndex=<?=$tabulacao?> onblur='document.form1.r90_regist.select();document.form1.r90_regist.focus();'>
      <br>
    </td>
  </tr>
  <tr>
    <td colspan="2" width="100%" height="60%" valign="top"  align="center">  
      <?
      // $sigla - É a sigla a ser utilizada no select.
      // $campoextra - É para quando as tabelas tiverem campos como o DATLIM ou o TPP
      // $mostracamp - É para quando o DATLIM ou o TPP forem apresentados no Select, serem mostrados no IFRAME_SELECIONA

      // $whereextra - Como o campo TPP é PK juntamente com o REGISTRO, ANOUSU e MESUSU em algumas tabelas, essas tabelas
      // poderão retornar o mesmo registro, mesmo anousu e mesmo mesusu com diferentes TPP, assim, quando o usuário clicar
      // em A ('alteração') ou E ('exclusão'), a linha q foi clicada, não deverá mais aparecer e as outras com diferentes
      // TPP devem continuar aparecendo... $whereextra controla isso.

      if($ponto == "fx"){
        $sigla = "r90_";
        $campoextra = ", r90_datlim as r90_datlim ";
        $mostracamp = ", r90_datlim";
      }else if($ponto == "fs"){
        $sigla = "r10_";
        $campoextra = ", r10_datlim as r90_datlim ";
        $mostracamp = ", r90_datlim";
      }else if($ponto == "fa"){
        $sigla = "r21_";
        $campoextra = "";
        $mostracamp = "";
      }else if($ponto == "fe"){
        $sigla = "r29_";
        $campoextra = ", r29_tpp";
        $mostracamp = ",r29_tpp";
        $whereextra222 = true;
      }else if($ponto == "fr"){
        $sigla = "r19_";
        $campoextra = ", r19_tpp as r29_tpp";
        $mostracamp = ",r29_tpp";
        $whereextra222 = true;
      }else if($ponto == "f13"){
        $sigla = "r34_";
        $campoextra = "";
        $mostracamp = "";
      }else if($ponto == "com"){
        $sigla = "r47_";
        $campoextra = "";
        $mostracamp = "";
      }
      $dbwhere = "      ".$sigla."regist = ".@$r90_regist ;
      $dbwhere .= " and ".$sigla."anousu = $r90_anousu ";
      $dbwhere .= " and ".$sigla."mesusu = $r90_mesusu ";
      // Para controlar a INSTITUIÇÃO
      $dbwhere .= " and ".$sigla."instit = ".db_getsession("DB_instit");

      if(isset ($r90_rubric) && trim($r90_rubric) != "" && !isset($incluir) && !isset($alterar)){
	    if(!isset($whereextra222)){
          $dbwhere .= " and ".$sigla."rubric <> '$r90_rubric' ";
	    }else if(isset($r29_tpp) && trim($r29_tpp)!=""){
      	  $dbwhere .= " and ".$sigla."tpp||".$sigla."rubric <> '".$r29_tpp.$r90_rubric."' ";
	    }
      }
      
      $campos = $sigla."anousu as r90_anousu,".  $sigla."mesusu  as r90_mesusu,". $sigla."regist  as r90_regist,". $sigla."rubric as r90_rubric,
                z01_numcgm, z01_nome".$campoextra.", rh27_descr ,".  $sigla."lotac as r90_lotac,
                r70_descr,". $sigla."quant as r90_quant,".  $sigla."valor as r90_valor";
      $orderby= $sigla."regist,". $sigla."rubric";

      $chavepri = array ("r90_anousu" => @ $r90_anousu, "r90_mesusu" => @ $r90_mesusu, "r90_regist" => @ $r90_regist, "r90_rubric" => @ $r90_rubric);
      
      // Seta TPP como chave primária.
      if($ponto == "fe" || $ponto == "fr"){
      	$chavepri["r29_tpp"] = @$r29_tpp;
      }
      
      $cliframe_alterar_excluir->chavepri = $chavepri;
      if($ponto == "fx"){
      $cliframe_alterar_excluir->sql = $clpontofx->sql_query_seleciona(
                                                   null,
                                                   null,
                                                   null,
                                                   null,
																									 " distinct ".$campos,
                                                   $orderby,
                                                   $dbwhere);
      }else if($ponto == "fs"){
      $cliframe_alterar_excluir->sql = $clpontofs->sql_query_seleciona(
                                                   null,
                                                   null,
                                                   null,
                                                   null,
																									 " distinct ".$campos,
                                                   $orderby,
                                                   $dbwhere);
      }else if($ponto == "fa"){
      $cliframe_alterar_excluir->sql = $clpontofa->sql_query_seleciona(
                                                   null,
                                                   null,
                                                   null,
                                                   null,
																									 " distinct ".$campos,
                                                   $orderby,
                                                   $dbwhere);
      }else if($ponto == "fe"){
      $cliframe_alterar_excluir->sql = $clpontofe->sql_query_seleciona(
                                                   null,
                                                   null,
                                                   null,
                                                   null,
                                                   null,
																									 " distinct ".$campos,
                                                   $orderby,
                                                   $dbwhere);
      }else if($ponto == "fr"){
      $cliframe_alterar_excluir->sql = $clpontofr->sql_query_seleciona(
                                                   null,
                                                   null,
                                                   null,
                                                   null,
                                                   null,
																									 " distinct ".$campos,
                                                   $orderby,
                                                   $dbwhere);
      }else if($ponto == "f13"){
      $cliframe_alterar_excluir->sql = $clpontof13->sql_query_seleciona(
                                                   null,
                                                   null,
                                                   null,
                                                   null,
																									 " distinct ".$campos,
                                                   $orderby,
                                                   $dbwhere);
      }else if($ponto == "com"){
      $cliframe_alterar_excluir->sql = $clpontocom->sql_query_seleciona(
                                                   null,
                                                   null,
                                                   null,
                                                   null,
																									 " distinct ".$campos,
                                                   $orderby,
                                                   $dbwhere);
      }
      // echo $cliframe_alterar_excluir->sql;
      $cliframe_alterar_excluir->campos   = "r90_rubric,rh27_descr".$mostracamp.",r90_quant,r90_valor";
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
    <td colspan="2" width="100%" valign="top" align="center" id="caixa_de_texto" height="15%" valign="top">
    </td>
  </tr>
 </table>
</fieldset> 
  </center>
</form>
<script>

function js_calculaDataLimit(){

  var doc       = document.form1;
	var iQuant    = new Number(doc.r90_quant.value);
  
  if ( doc.rh27_presta.value == 't' && doc.rh27_limdat.value == 't' ) {
  
	  var iMesAtu   = new Number(doc.r90_mesusu.value);
	  var iAnoLimit = new Number(doc.r90_anousu.value);
	  var iMesLimit = iMesAtu + (iQuant-1);
	  
	  while ( iMesLimit > 12  ) {
	    iMesLimit -= 12;
	    iAnoLimit++;
	  }
	  
	  if ( iMesLimit.toString().length < 2 ) {
	    iMesLimit = "0"+iMesLimit;
	  }
	  
	  doc.r90_datlim.value = iAnoLimit+'/'+iMesLimit;
	   
  }

   
  doc.r90_valor.select();
  doc.r90_valor.focus();

}

function js_calculaQuant(sDataLimit){
  
	var doc        = document.form1;
	var aDataLimit = sDataLimit.split('/');
	var iAnoLimit  = new Number(aDataLimit[0]);
	var iMesLimit  = new Number(aDataLimit[1]);
	var iAnoAtu    = new Number(doc.r90_anousu.value);
	var iMesAtu    = new Number(doc.r90_mesusu.value);
	  
  if ( doc.rh27_presta.value == 't' && doc.rh27_limdat.value == 't' ) {
	  
	  var iQuant     = new Number(0);
	  
	  if ( iAnoLimit > iAnoAtu ) {
	  
	    while ( iAnoLimit > (iAnoAtu+1)  ) {
	      iQuant += 12;
	      --iAnoLimit;
	    }
	    
		  var iMesRest  = new Number(12 - iMesAtu);
		  
	    iQuant += iMesRest + iMesLimit;
	    
	  } else {
	    iQuant += iMesLimit - iMesAtu;
	  }
	  
	  doc.r90_quant.value = iQuant+1;
	  
	}  
}


function js_vercampos(){
  // Verificar se algum campo ficou em branco
<?
  if($db_opcao != 3 && $db_opcao != 33){
    echo '
          erro = 0;
          if(document.form1.r90_regist.value == ""){
            alert("Código do funcionário não informado");
            document.form1.r90_regist.focus();
            erro++;
          }else if(document.form1.r90_lotac.value == ""){
            alert("Lotação do funcionário não informada");
            document.form1.r90_lotac.focus();
            erro++;
          }else if(document.form1.r90_rubric.value == ""){
            alert("Rubrica não informada");
            document.form1.r90_rubric.focus();
            erro++;  
          }else if((document.form1.r90_quant.value == "" || document.form1.r90_quant.value == "0") && (document.form1.rh27_form.value == "T" || document.form1.rh27_form.value == "t")){
            alert("Quantidade não informada");
            document.form1.r90_quant.select();
            document.form1.r90_quant.focus();
            erro++;
          }else if(document.form1.r29_tpp && document.form1.r29_tpp.value == ""){
  	        alert("Tipo não informado");
            document.form1.r29_tpp.focus();
  	        erro++
          }else if((document.form1.r90_valor.value == "" || document.form1.r90_valor.value == "0") && (document.form1.rh27_form.value == "F" || document.form1.rh27_form.value == "f")){
            alert("Valor não informado");
            document.form1.r90_valor.select();
            document.form1.r90_valor.focus();
            erro++;
          }

          if(erro > 0){
            return false;
          }else{

            if ( document.form1.ponto.value == "fx" || document.form1.ponto.value == "fs" ) {
              return js_verificaposicoes(document.form1.r90_datlim.value,"true");
            }

            if(document.form1.r90_quant.value == ""){
              document.form1.r90_quant.value = 0;
            }
            if(document.form1.r90_valor.value == ""){
              document.form1.r90_valor.value = 0;
            }
            return js_testarRegraPonto();
          }
         ';
  } 
  ?>

}

function js_submita(){
  location.href = "pes1_pontofx001.php?r90_anousu="+document.form1.r90_anousu.value+"&r90_mesusu="+document.form1.r90_mesusu.value+"&r90_regist="+document.form1.r90_regist.value+"&ponto="+document.form1.ponto.value;
}
// Função para tornar ou não o campo datlim READONLY.
function js_desabilita(TrueORFalse){
  opcaoextra = "<?=($db_opcao)?>";
  <?
  // Se ponto for salário ou fixo, a função irá executar caso contrário, a função
  // não fará nada
  if($ponto == "fx" || $ponto == "fs"){
    echo '
    if(document.form1.r90_regist.value != ""){
      if(TrueORFalse==true || opcaoextra=="3"){
        if(opcaoextra!="3"){
          document.form1.r90_datlim.value                 = "";
        }
        document.form1.r90_datlim.readOnly              = true;
        document.form1.r90_datlim.style.backgroundColor = "#DEB887";
        if(document.form1.r90_rubric.value != ""){
          document.form1.r90_quant.select();
          document.form1.r90_quant.focus();
        }else{
          document.form1.r90_rubric.select();
          document.form1.r90_rubric.focus();
        }
      }else{
        if(document.form1.r90_rubric.value != ""){
          document.form1.r90_datlim.readOnly              = false;
          document.form1.r90_datlim.style.backgroundColor ="";
          document.form1.r90_datlim.select();
          document.form1.r90_datlim.focus();
        }
      }
    }else{
      document.form1.r90_regist.select();
      document.form1.r90_regist.focus();
    }
    ';
  }else if($ponto == "fe" || $ponto == "fr"){
  	echo '
    if(document.form1.r90_regist.value != ""){
      if(document.form1.r90_rubric.value != ""){
        document.form1.r29_tpp.select();
        document.form1.r29_tpp.focus();
      }else{
        document.form1.r90_rubric.select();
        document.form1.r90_rubric.focus();
      }
    }else{
      document.form1.r90_regist.select();
      document.form1.r90_regist.focus();
    }
    ';
  }
  ?>
}

/**
 * Variavel global para armazenar o status da rubrica
 * - true: pode ser inserida no ponto.
 * - false: não pode ser inserida no ponto.
 *
 * @var boolean lTestarRegraPonto
 * @access public
 */
var lTestarRegraPonto;

/**
 * Realiza uma consulta no RPC para cada vez que uma Rubrica é adicionada, 
 * para verificar se a mesma possui alguma regra de lançamento.
 * - lTestarRegraPonto recebe true quando a rubrica pode ser adicionada e false quando não pode ser adicionada
 *
 * @access public
 * @return boolean lTestarRegraPonto.
 */
function js_testarRegraPonto() {

  var aRubricas  = [$F('r90_rubric')];
  var sTabela    = "<?=$ponto?>";
  var iMatricula = $F('r90_regist');

  var sUrl   = 'pes1_rhrubricas.RPC.php';

  var oParametros = Object();
      oParametros.sExecucao  = 'testarRegistroPonto';
      oParametros.aRubricas  = aRubricas;
      oParametros.sTipoPonto = sTabela;
      oParametros.iMatricula = iMatricula;   

  var oAjax  = new Ajax.Request( sUrl, {
                                         method: 'post', 
                                         asynchronous: false,
                                         parameters : 'json=' + Object.toJSON(oParametros),
                                         onComplete: js_retornoTestarRegraPonto
                                        }
                                );

  return lTestarRegraPonto;
}

/**
 * Trata o retorno da função js_testarRegraPonto
 * - se for somente aviso, exibe um alert com a mensagem solicitando se deseja adicionar a rubrica ou não
 * - se for bloqueio não permite adicionar a rubrica
 * - lTestarRegraPonto recebe true quando a rubrica pode ser adicionada e false quando não pode ser adicionada ao ponto
 *
 * @param object oRetorno.
 * @access public
 */
function js_retornoTestarRegraPonto(oRetorno) {

  lTestarRegraPonto = true;

  var oRetorno = eval("("+oRetorno.responseText+")");
  var sMensagem = oRetorno.sMensagem.urlDecode().replace(/\\n/g, "\n");

  /**
   * Erro no RPC
   */
  if ( oRetorno.iStatus > 1 ) {

    alert(sMensagem);
    return false;
  }

  /**
   * Se haver uma mensagem de bloqueio, exibe a mensagem para o usuario a mensgem e lTestarRegraPonto 
   * recebe o valor false, para a rubrica não ser adicionada ao ponto
   */
  if ( oRetorno.sMensagensBloqueio != '' ) {

    lTestarRegraPonto = false;
    alert( oRetorno.sMensagensBloqueio.urlDecode().replace(/\n/g, "\n") );
    return false;
  }

  /**
   * Se haver uma mensagem de aviso, exibe a mensagem para o usuario perguntando 
   * se a rubrica deve ser adicionada ao ponto ou não.
   * - lTestarRegraPonto recebe false se o usuario clicar em cancelar
   */
  if ( oRetorno.sMensagensAviso != '' ) {

    lConfirmarAviso = confirm( oRetorno.sMensagensAviso.urlDecode().replace(/\n/g, "\n") );

    /**
     * Clicou em cancelar
     * - Nao permite adicionar ao ponto
     */
    if ( !lConfirmarAviso ) {
      lTestarRegraPonto = false;
    }
  }
}

function js_verificaposicoes(valor,TorF){

  var expr = new RegExp("[^0-9]+");
  localbarra = valor.search("/");
  erro = 0;
  errm = "";
  if(localbarra == -1){
   	if(valor.match(expr)){
      erro ++;
  	}else if(TorF == "true" && document.form1.r90_datlim.readOnly == false){
  	  erro ++;
  	}
  }else{
    ano = valor.substr(0,4);
    mes = valor.substr(5,2);
    anoi = new Number(ano);
    mesi = new Number(mes);
    anot = new Number(document.form1.r90_anousu.value);
    mest = new Number(document.form1.r90_mesusu.value);
    
   	if(ano.match(expr)){
      erro ++;
  	}else if(mes.match(expr)){
      erro ++;
  	}else if(anoi < anot || (anoi <= anot && mesi < mest)){
  	  if(mesi > 1 || anoi < anot || TorF == 'true'){
        errm = "\nAno e mês devem ser maior ou igual ao corrente da folha.";
        erro ++;
      }
  	}else if(mesi > 12){
      errm = "\nMês inexistente.";
      erro ++;
  	}else if(TorF == 'true' && mes == 0){
      errm = "\nMês não informado.";
      erro ++;
  	}
  }

  if( erro > 0 || (document.form1.rh27_limdat.value == 't' && document.form1.r90_datlim.value == "")){
	alert("Campo Ano/mês deve ser preenchido com números e uma '/' no seguinte formato (aaaa/mm)! " + errm);
    document.form1.r90_datlim.select();
    document.form1.r90_datlim.focus();
    return false;
  }

//   return false;
  return js_testarRegraPonto();

}
function js_mascaradata(valor){

  total = valor.length;
  if(total > 0){
    digit = valor.substr(total-1,1);
    if(digit != "/"){
      if(total == 4){
        valor += "/";
  	  }
    }
  }
  
  document.form1.r90_datlim.value = valor;
  return js_verificaposicoes(valor,'false');

}
function js_pesquisar90_regist(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_rhpessoal','func_rhpessoal.php?testarescisao=<?=($ponto == "fs" ? "raf" : ($ponto == "fr" ? "fa" : "ra"))?>&funcao_js=parent.js_mostrapessoal1|rh01_regist|z01_nome&instit=<?=(db_getsession("DB_instit"))?>&chave_r01_mesusu='+document.form1.r90_mesusu.value+'&chave_r01_anousu'+document.form1.r90_anousu.value,'Pesquisa',true);
  }else{
     if(document.form1.r90_regist.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_rhpessoal','func_rhpessoal.php?testarescisao=<?=($ponto == "fs" ? "raf" : ($ponto == "fr" ? "fa" : "ra"))?>&pesquisa_chave='+document.form1.r90_regist.value+'&funcao_js=parent.js_mostrapessoal&instit=<?=(db_getsession("DB_instit"))?>','Pesquisa',false);
     }else{
       document.form1.z01_nome.value = '';
       location.href = "pes1_pontofx001.php?ponto="+document.form1.ponto.value; 
     }
  }
}
function js_mostrapessoal(chave,erro){

  document.form1.z01_nome.value = chave; 

  if(erro==true){ 
    document.form1.r90_regist.focus(); 
    document.form1.r90_regist.value = ''; 
  }else{
    js_submita();
  }
}
function js_mostrapessoal1(chave1,chave2){

  document.form1.r90_regist.value = chave1;
  document.form1.z01_nome.value   = chave2;
  db_iframe_rhpessoal.hide();
  js_submita();
}

function js_pesquisar90_rubric(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_rhrubricas','func_rhrubricasponto.php?funcao_js=parent.js_mostrarubricas1|rh27_rubric|rh27_descr|rh27_limdat|formula|rh27_obs|rh27_presta&instit=<?=(db_getsession("DB_instit"))?>','Pesquisa',true);
  }else{
     if(document.form1.r90_rubric.value != ''){     	
       quantcaracteres = document.form1.r90_rubric.value.length;
       for(i=quantcaracteres;i<4;i++){
         document.form1.r90_rubric.value = "0"+document.form1.r90_rubric.value;        
       }
       js_OpenJanelaIframe('','db_iframe_rhrubricas','func_rhrubricasponto.php?pesquisa_chave='+document.form1.r90_rubric.value+'&funcao_js=parent.js_mostrarubricas&instit=<?=(db_getsession("DB_instit"))?>','Pesquisa',false);
     }else{
       document.form1.rh27_descr.value = '';
       document.form1.rh27_form.value  = '';
       document.form1.r90_rubric.value  = '';
       document.form1.r90_valor.value = '0';
       document.form1.r90_quant.value = '0';
       document.getElementById('caixa_de_texto').innerHTML = "";
       js_desabilita(true); 
     }
  }
}
function js_mostrarubricas(chave,chave2,chave3,chave4,chave5,erro){
  document.form1.rh27_descr.value  = chave;
  <?
  if($ponto == "fx" || $ponto == "fs"){
    echo "document.form1.rh27_limdat.value = chave2;\n";
  }
  ?>
  if(erro==true){
    document.getElementById('caixa_de_texto').innerHTML = "";
    document.form1.rh27_form.value = '';
    document.form1.r90_rubric.value = '';
    document.form1.r90_rubric.focus();
  }else{
    document.form1.rh27_presta.value = chave5;
    document.getElementById('caixa_de_texto').innerHTML = "<font color='red'><b>"+chave4+"</b></font>";
    document.form1.rh27_form.value  = chave3;
  }

  js_getDadosPadroes();

  if(chave2 == 'f' || chave2 == ''){
    js_desabilita(true);
  }else{
    js_desabilita(false);
  }
}
function js_mostrarubricas1(chave1,chave2,chave3,chave4,chave5,chave6){
  document.form1.r90_rubric.value  = chave1;
  document.form1.rh27_descr.value  = chave2;
  document.form1.rh27_form.value   = chave4;
  document.form1.rh27_presta.value = chave6;
  document.getElementById('caixa_de_texto').innerHTML = "<font color='red'><b>"+chave5+"</b></font>";
  <?
  if($ponto == "fx" || $ponto == "fs"){
    echo "document.form1.rh27_limdat.value = chave3;";
  }
  ?>
  if(chave3 == 'f' || chave3 == ""){
    js_desabilita(true);
  }else{
    js_desabilita(false);
  }

  js_getDadosPadroes();

  db_iframe_rhrubricas.hide();
}

function js_pesquisar90_lotac(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframelotacao','func_lotacao.php?funcao_js=parent.js_mostralotacao1|r70_codigo|r70_descr&instit=<?=(db_getsession("DB_instit"))?>&chave_r70_mesusu='+document.form1.r90_mesusu.value+'&chave_r70_anousu'+document.form1.r90_anousu.value,'Pesquisa',true);
  }else{
     if(document.form1.r90_lotac.value != ''){ 
       js_OpenJanelaIframe('top.corpo','db_iframelotacao','func_lotacao.php?pesquisa_chave='+document.form1.r90_lotac.value+'&funcao_js=parent.js_mostralotacao&instit=<?=(db_getsession("DB_instit"))?>&chave_r70_mesusu='+document.form1.r90_mesusu.value+'&chave_r70_anousu'+document.form1.r90_anousu.value,'Pesquisa',false);
     }else{
       document.form1.r70_descr.value = ''; 
     }
  }
}
function js_mostralotacao(chave,erro){
  document.form1.r70_descr.value = chave; 
  if(erro==true){ 
    document.form1.r90_lotac.focus(); 
    document.form1.r90_lotac.value = ''; 
  }
}
function js_mostralotacao1(chave1,chave2){
  document.form1.r90_lotac.value = chave1;
  document.form1.r70_descr.value = chave2;
  db_iframelotacao.hide();
}
<?
$TrueORFalse = "true";
if(isset($rh27_limdat)){
  if($rh27_limdat=="t"){
	$TrueORFalse = "false";
  }
}
echo "js_desabilita($TrueORFalse);";
?>
</script>