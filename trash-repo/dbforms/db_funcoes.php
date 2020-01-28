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

//#00#//documentacao
//#10#//Como documentar ou funcao ou classe
//#99#////#10#//
//#99#//Descri��o da fun��o ou m�todo gerada no *//#00#//*
//#99#////#15#//
//#99#//Sintaxe da fun��o ou m�todo
//#99#////#20#//
//#99#//Par�metros fornecidos para a fun��o ou m�todo, como mostra a sintaxe *//#10#//*
//#99#////#30#//
//#99#//Propriedades ou variaveis de fun��o
//#99#////#40#//
//#99#//Retorno da fun��o ou m�todo
//#99#////#99#//
//#99#//Observa��o sobre a fun��o ou m�todo

//#00#//db_opcao
//#10#//Op��o do sistema para inclus�o, altera��o ou exclus�o em formul�rios
//#99#// 1 - Inclus�o
//#99#//22 - Inicio do formul�rio antes de selecionar um �tem para alterar
//#99#// 3 - Exclus�o
//#99#//33 - Inicio do formul�rio antes de selecionar um �tem para excluir
//#99#// 5 - Objeto desabilitado no formul�rio ( disabled )

function db_inicio_transacao() {
	//#00#//db_inicio_transacao
	//#10#//fun��o para abrir uma transa��o
	//#15#//db_inicio_transacao();
	//#99#//Uma transa��o � um conjunto de execu��es no banco de dados que dever�o ser gravadas somente
	//#99#//se todas as execu��es tiverem sucesso, caso contr�rio, nenhuma das execu��es dever� ser
	//#99#//confirmada
	db_query('BEGIN');
  return;
}
function db_fim_transacao($erro = false) {
		//#00#//db_fim_transacao
		//#10#//fun��o para finalizar uma transa��o
		//#20#//false : Finaliza transa��o com sucesso (commit)
		//#20#//true  : Transa��o com erro, desfaz os procedimentos executados (rollback)
	if ($erro == true) {
		db_query('ROLLBACK');
	} else {
		db_query('COMMIT');
	}
  return;
}

// Parametros do $tipo
// 1  Bota as contas do plano que n�o existem no saltes
// 2  Bota as contas do saltes
// 3  Bota as contas do plano
function db_contas($nome, $valor = "", $tipo = 1) {
	if ($tipo == 1) {
		//echo    $sql_redu = "select c62_reduz,
		//	                c60_descr
		//		 from conplano
		//		       inner join conplanoreduz on c61_codcon = c60_codcon
		//		       inner join conplanoexe   on c62_reduz  = c61_reduz
		//		                               and c62_anousu = ".db_getsession("DB_anousu")."
		//		 where substr(c60_estrut,1,3) in ('111') order by c60_estrut";
		$sql_desc = "select c62_reduz,
					                    c60_descr
						 from conplano
						       inner join conplanoreduz on c61_codcon = c60_codcon and
                                                                            c61_anousu=c60_anousu and
						       			                                    c61_instit = ".db_getsession("DB_instit")."
						       inner join conplanoexe   on c62_reduz  = c61_reduz
						                               and c62_anousu = ".db_getsession("DB_anousu")."
						 where substr(c60_estrut,1,3) in ('111')
                                  and c60_anousu=".db_getsession("DB_anousu")."
                         order by c60_estrut";

		//  $sql_desc = "select c01_reduz,c01_descr
		//            from plano
		//		 where c01_reduz <> 0 and substr(c01_estrut,1,3) in ('111','112') and c01_anousu = ".db_getsession("DB_anousu")."
		//		 order by c01_descr";
	} else
		if ($tipo == 2) {
			$sql_redu = "select p.k13_conta,l.c01_descr
							             from saltes p
										      inner join saltesplan s on s.k13_conta = p.k13_conta and s.c01_anousu = ".db_getsession("DB_anousu")."
										      inner join conplanoexe on c62_reduz = k13_reduz and c62_anousu = ".db_getsession("DB_anousu")."
										      inner join conplanoreduz on c62_reduz = c61_reduz and c61_instit = ".db_getsession("DB_instit")."
                         															and c61_anousu=c62_anousu
											  inner join plano l on l.c01_anousu = ".db_getsession("DB_anousu")." and l.c01_reduz = s.c01_reduz
									     order by p.k13_conta";
			$sql_desc = "select p.k13_conta,l.c01_descr
							             from saltes p
										      inner join saltesplan s on s.k13_conta = p.k13_conta  and s.c01_anousu = ".db_getsession("DB_anousu")."
											  inner join plano l on l.c01_anousu = ".db_getsession("DB_anousu")." and l.c01_reduz = s.c01_reduz
										 order by c01_descr";
		} else
			if ($tipo == 3) {
				$sql_redu = "select c01_reduz,c01_descr
									             from plano
												 where c01_anousu = ".db_getsession("DB_anousu")." and c01_reduz <> 0 order by c01_reduz";
				$sql_desc = "select c01_reduz,c01_descr
									             from plano
												 where c01_anousu = ".db_getsession("DB_anousu")." and c01_reduz <> 0 order by c01_descr";
			}
?>
  <table border="0" cellpadding="0" cellspacing="0">
  <tr><td nowrap>
  <select name="<?=$nome?>" onChange="js_ProcCod('<?=$nome?>','<?=$nome."descr"?>')">
    <?



	$result_redu = db_query($sql_redu);
	$numrows = pg_numrows($result_redu);
	for ($i = 0; $i < $numrows; $i ++) {
		echo "<option value=\"".pg_result($result_redu, $i, 0)."\" >".pg_result($result_redu, $i, 0)."</option>\n";
	}
?>
  </select>&nbsp;&nbsp;
  <select name="<?=$nome."descr"?>" onChange="js_ProcCod('<?=$nome."descr"?>','<?=$nome?>')">
    <?



	$result_desc = db_query($sql_desc);
	for ($i = 0; $i < $numrows; $i ++) {
		echo "<option value=\"".pg_result($result_desc, $i, 0)."\">".pg_result($result_desc, $i, 1)."</option>\n";
	}
?>
  </select>
  </td></tr>
  </table>
  <script>
  function js_ProcCod(proc,res) {
    var sel1 = document.form1.elements[proc];
    var sel2 = document.form1.elements[res];
	for(var i = 0;i < sel1.options.length;i++) {
	  if(sel1.options[sel1.selectedIndex].value == sel2.options[i].value)
	    sel2.options[i].selected = true;
	}
  }
  document.form1.elements['<?=$nome?>'].options[0].selected = true;
  js_ProcCod('<?=$nome?>','<?=$nome."descr"?>');
  </script>
  <?



}
//////////////////////////////////////
/**
 * Monta um input na tela, utilizando a documenta��o do sistema
 * @example db_input($nome,$dbsize,$dbvalidatipo,$dbcadastro,$dbhidden='text',$db_opcao=3,$js_script="",$nomevar="",$bgcolor="");
 * @param string  $nome          Nome            : Nome do campo da documentacao do sistema ou do arquivo
 * @param integer $dbsize        Tamanho         : Tamanho do objeto na tela (default tamanho na documenta��o)
 * @param integer $dbvalidatipo  Valid���o       : Tipo de valida��o JAVASCRIPT para o campo, retirado da documenta��o
 * @param boolean $dbcadastro    Cadastro        : True se cadastro ou false se nao cadastro Padr�o: true )
 * @param string  $dbhidden      Type            : Tipo do objeto INPUT a ser mostrado na tela (text,hidden,file,submit,button,...) Padr�o: text
 * @param number  $db_opcao      Opcao           : *db_opcao* do programa a ser executado neste objeto input, inclus�o(1) altera��o(2) exclus�o(3)
 * @param string  $js_script     Script          : JAVASCRIPT  a ser executado juntamento com o objeto, indicando os m�todos
 * @param string  $nomevar       Nome Secund�rio : Nome do input que ser� gerado, assumindo somente as caracter�sticas do campo Nome
 * @param string  $bgcolor       Cor Background  : Cor de fundo da tela, no caso de *db_opcao*=3 ser� "#DEB887"
 * @param string  $css           Estilo CSS      : Css Personalidado do Componente
 * @param string  $iMaxLen       MaxLenght       : Maximo de Caractereres
 * @return void
 */
function db_input($nome, $dbsize, $dbvalidatipo, $dbcadastro, $dbhidden = 'text', $db_opcao = 3, $js_script = "", $nomevar = "", $bgcolor = "", $css="",$iMaxLen=null) {

	if ($iMaxLen != null && !empty($iMaxLen)){
		$iMax = $iMaxLen;
	} else {
		$iMax = @$GLOBALS['M'.$nome];
	}
?>

  <input title="<?=@$GLOBALS['T'.$nome]?>" name="<?=($nomevar==""?$nome:$nomevar)?>"  type="<?=$dbhidden?>" <?=($dbhidden=="checkbox"?(@$GLOBALS[($nomevar==""?$nome:$nomevar)]=="t"?"checked":""):"")?>
    id="<?=($nomevar==""?$nome:$nomevar)?>"  value="<?=@$GLOBALS[($nomevar==""?$nome:$nomevar)]?>"  size="<?=$dbsize?>"
	maxlength="<?=@$iMax?>"
  <?

	echo $js_script;
	if ($dbcadastro == true) {

		if ($db_opcao == 3 || $db_opcao == 22 || $db_opcao == 33 || $db_opcao == 11) {
			echo " readonly ";
			if ($bgcolor == "")
				$bgcolor = "#DEB887";
		}
		if ($db_opcao == 5) {
			echo " disabled ";
		}
	}
	$db_style = '';
	if ($bgcolor == "") {
		echo " ".@ $GLOBALS['N'.$nome]." ";
	} else {
		$db_style .= "background-color:$bgcolor;";
	}

	if (isset ($GLOBALS['G'.$nome]) && $GLOBALS['G'.$nome] == 't') {
		$db_style .= "text-transform:uppercase;";
	}

	if ($db_style != '') {
	  if ($css!=""){
		   echo " style=\"$db_style;$css\" ";
     }else{
      echo " style=\"$db_style\" ";
		}
	} else {
	  if ($css != ""){
		 echo " style=\"$css\" ";
		}
	}


	if (($db_opcao != 3) && ($db_opcao != 5)) {
?>
    onblur="js_ValidaMaiusculo(this,'<?=@$GLOBALS['G'.$nome]?>',event);"
    onKeyUp="js_ValidaCampos(this,<?=($dbvalidatipo==''?0:$dbvalidatipo)?>,'<?=@$GLOBALS['S'.$nome]?>','<?=($db_opcao==4?"t":@$GLOBALS['U'.$nome])?>','<?=@$GLOBALS['G'.$nome]?>',event);"
    onKeyDown="return js_controla_tecla_enter(this,event);"
  <?



	}
?>
    autocomplete='<?=@$GLOBALS['A'.$nome]?>'>
  <?



}
/*************************************/
/**
 * Fun��o para montar um textarea na tela do programa
 *
 * @example db_textarea($nome,$dbsizelinha=1,$dbsizecoluna=1,$dbvalidatipo,$dbcadastro=true,$dbhidden='text',$db_opcao=3,$js_script="",$nomevar="",$bgcolor="");
 *
 * @param $nome          - Nome            : Nome do campo da documentacao do sistema ou do arquivo
 * @param $dbsizelinha   - Numero Linhas   : N�mero de linhas do objeto textarea
 * @param $dbsizecoluna  - Numero Colunas  : N�mero de Coluna do objeto textarea
 * @param $dbvalidatipo  - Valid���o       : Tipo de valida��o JAVASCRIPT para o campo, retirado da documenta��o
 * @param $dbcadastro    - Cadastro        : True se cadastro ou false se nao cadastro Padr�o: true )
 * @param $dbhidden      - Type            : Tipo do objeto INPUT a ser mostrado na tela (text,hidden,type,submit,...) Padr�o: text
 * @param $db_opcao      - Opcao           : *db_opcao* do programa a ser executado neste objeto input, inclus�o(1) altera��o(2) exclus�o(3)
 * @param $js_script     - Script          : JAVASCRIPT  a ser executado juntamento com o objeto, indicando os m�todos
 * @param $nomevar       - Nome Secund�rio : Nome do input que ser� gerado, assumindo somente as caracter�sticas do campo Nome
 * @param $bgcolor       - Cor Background  : Cor de fundo da tela, no caso de *db_opcao*=3 ser� "#DEB887"
 * @param $maxlength     - Maxlenght       : Tamanho m�ximo permitido para escrita no textarea
 */
function db_textarea($nome, $dbsizelinha = 1, $dbsizecoluna = 1, $dbvalidatipo, $dbcadastro = true, $dbhidden = 'text', $db_opcao = 3, $js_script = "", $nomevar = "", $bgcolor = "", $maxlength = "") {
  
  $sOnKeyDown = "";
?>
  <textarea title="<?=@$GLOBALS['T'.$nome]?>" name="<?=($nomevar==""?$nome:$nomevar)?>"  type="<?=$dbhidden?>"
    id="<?=($nomevar==""?$nome:$nomevar)?>" rows="<?=$dbsizelinha?>" cols="<?=$dbsizecoluna?>"
  <?
	echo $js_script;
	if ($dbcadastro == true) {

		/*
		     if ($db_opcao==3 || $db_opcao==22){s� coloquei a opcao 11...  dia 28-10-2004
		*/
		if ($db_opcao == 3 || $db_opcao == 22 || $db_opcao == 11 || $db_opcao == 33) {
			echo " readonly ";
			if ($bgcolor == "")
				$bgcolor = "#DEB887";
		}
		if ($db_opcao == 5) {
			echo " disabled ";
		}
	}
	//come�a a colocas CSS
	$db_style = '';
	if ($bgcolor != "") {
		$db_style = 'background-color:'.$bgcolor.';';
	}

	if (isset ($GLOBALS['G'.$nome]) && $GLOBALS['G'.$nome] == 't') {
		$db_style .= "text-transform:uppercase;";
	}

	if ($db_style != '') {
		echo " style=\"$db_style\" ";
	}

	$OnBlur  = " js_ValidaMaiusculo(this,'".@$GLOBALS['G'.$nome]."',event); ";
	$OnKeyUp = " js_ValidaCampos(this,".($dbvalidatipo==''?0:$dbvalidatipo).",'".@$GLOBALS['S'.$nome]."','".@$GLOBALS['U'.$nome]."','".@$GLOBALS['G'.$nome]."',event); ";

	if ($maxlength != "") {
	  $sOnKeyDown = " js_maxlenghttextarea(this,event,".$maxlength."); ";
	  $OnKeyUp   .= $sOnKeyDown;
	}
?>
    onblur    = "<?=$OnBlur?>"
    onKeyUp   = "<?=$OnKeyUp?>"
    onKeyDown = "<?php echo $sOnKeyDown; ?>"

    <?=@$GLOBALS['N'.$nome]?>

    autocomplete='<?=@$GLOBALS['A'.$nome]?>'><?=(!isset($GLOBALS[$nome])?"":stripslashes($GLOBALS[($nomevar==""?$nome:$nomevar)]))?></textarea>
<?
  if ( $maxlength != "") {
	echo "<br>";
	echo "<div align='right'>";
	echo "<span style='float:left;color:red;font-weight:bold' id='{$nome}errobar'></span>";
	echo " <b> Caracteres Digitados : </b> ";
	echo "  <input type='text' name='{$nome}obsdig' id='{$nome}obsdig' size='3' value='0' style='color: #000;' disabled> ";
	echo " <b> - Limite ".$maxlength." </b> ";
    echo "</div> ";
  }

}

function db_ancora($nome, $js_script, $db_opcao, $style = "", $varnome="") {
  //#00#//db_ancora
  //#10#//Coloca uma �ncora no Label do campo e executa uma fun��o JAVASCRIPT para pesquisa do arquivo em referencia
  //#15#//db_ancora($nome,$js_script,$db_opcao,$style="");
  //#20#//Nome : Nome do campo da documenta��o do sistema ou do arquivo
  //#20#//Script : Fun��o JAVASCRIPT que ser� executado no onclik do objeto label
  //#20#//Opcao : *db_opcao* do programa a ser executado neste objeto input, inclus�o(1) altera��o(2) exclus�o(3)
  //#20#//Style : Op��o para programador mudar o estilo da �ncora
  if (($db_opcao < 3) || ($db_opcao == 4)) {
    if($varnome	!=""){
  ?>
    <a href='#' id="<?=$varnome?>" class='dbancora' style='text-decoration:underline;<?=trim($style) != "" ? ";$style" : ""?>' onclick="<?=$js_script?>"><?=$nome?></a>
  <?
	  }else{
	  	 ?>
         <a href='#' class='dbancora'  style='text-decoration:underline;<?=trim($style) != "" ? ";$style" : ""?>' onclick="<?=$js_script?>"><?=$nome?></a>
       <?
	  }
  } else {
    echo $nome;
  }
}
/*************************************/

function db_multiploselect($valueobj,$descrobj, $objnsel="", $objsel="", $recordnsel, $recordsel, $nlinhas=10, $width=250, $descrnsel="", $descrsel="", $ordenarselect=true, $jsincluir=""){
  // Fun��o para montar dois objetos select multiple na tela, recebendo dados de recordset distintos. Selects para sele��o em que ficam passando as informa��es de um para o outro.
	// valueobj   : Campo que ser� o value dos objetos.
	// descrobj   : Campo que ser� a descri��o nos objetos.
	// objnsel    : Nome do objeto dos valores a selecionar.
	// objsel     : Nome do objeto dos valores j� selecionados.
	// recordnsel : Recordset ou array com os valores a selecionar.
	// recordsel  : Recordset ou array com os valores j� selecionados.
	// nlinhas    : N�mero de linhas que os objetos ter�o. Valor default � 10.
	// width      : Largura dos objetos. Valor default � 250.
	// descrsel   : Descri��o que aparecer� no FIELDSET dos itens a selecionar
	// descrnsel  : Descri��o que aparecer� no FIELDSET dos itens j� selecionados
	// ordenarselect : True se programador desejar ordenar os values dentro dos selects ao mudar os itens de lugar.
	// jsincluir  : Fun��o JavaScript chamada ao passar campos de um select para o outro
	if(trim($descrnsel) == ""){
		$descrnsel = "A selecionar";
	}
	if(trim($descrsel) == ""){
		$descrsel  = "Selecionados";
	}
	if(trim($objnsel) == ""){
		$objnsel = "objeto1";
	}
	if(trim($objsel) == ""){
		$objsel  = "objeto2";
	}
	?>
    <table>
      <tr>
        <td>
          <fieldset>
            <Legend align="left">
              <b><?=$descrnsel?></b>
            </Legend>
            <select name="<?=$objnsel?>[]" id="<?=$objnsel?>" size="<?=$nlinhas?>" style="width:<?=$width?>px" multiple onDblClick="js_db_multiploselect_incluir_item(this,document.form1.<?=$objsel?>);">
	          <?
	          if(gettype($recordnsel) == "resource"){
	          	$numrows_recnsel = pg_numrows($recordnsel);
	          	for($i=0; $i<$numrows_recnsel; $i++){
	          		db_fieldsmemory($recordnsel, $i);
	          		global $$valueobj;
	          		global $$descrobj;
	          		echo "<option value='".$$valueobj."'>".$$descrobj."</option>\n";
	          	}
	          }else if(gettype($recordnsel) == "array"){
	          	$numrows_recnsel = count($recordnsel);
	          	reset($recordnsel);
	          	for($i=0; $i<$numrows_recnsel; $i++){
	          	  $$valueobj = key($recordnsel);
	          	  $$descrobj = $recordnsel[$$valueobj];
	          		echo "<option value='".$$valueobj."'>".$$descrobj."</option>\n";
	          		next($recordnsel);
	          	}
	          }
            ?>
            </select>
          </fieldset>
        </td>
        <td width='10%' align='center'>
          <table>
            <tr>
              <td align='center'><input type='button' name='selecionD' title='Enviar selecionados para direita' value='&nbsp;>&nbsp;' onclick='js_db_multiploselect_incluir_item(document.form1.<?=$objnsel?>,document.form1.<?=$objsel?>);'></td>
            </tr>
            <tr>
              <td align='center'><input type='button' name='seltodosD' title='Enviar todos para direita' value='>>' onclick='js_db_multiposelect_incluir_todos(document.form1.<?=$objnsel?>,document.form1.<?=$objsel?>);'></td>
            </tr>
            <tr>
              <td align='center'><input type='button' name='selecionE' title='Enviar selecionados para esquerda' value='&nbsp;<&nbsp;' onclick='js_db_multiploselect_incluir_item(document.form1.<?=$objsel?>,document.form1.<?=$objnsel?>);'></td>
            </tr>
            <tr>
              <td align='center'><input type='button' name='seltodosE' title='Enviar todos para esquerda' value='<<' onclick='js_db_multiposelect_incluir_todos(document.form1.<?=$objsel?>,document.form1.<?=$objnsel?>);'></td>
            </tr>
          </table>
        </td>
        <td nowrap>
          <fieldset>
            <Legend align="left">
              <b><?=$descrsel?></b>
            </Legend>
            <select name="<?=$objsel?>[]" id="<?=$objsel?>" size="<?=$nlinhas?>" style="width:<?=$width?>px" multiple onDblClick="js_db_multiploselect_incluir_item(this,document.form1.<?=$objnsel?>);">
	          <?
	          if(gettype($recordsel) == "resource"){
	          	$numrows_recsel = pg_numrows($recordsel);
	          	for($i=0; $i<$numrows_recsel; $i++){
	          		db_fieldsmemory($recordsel, $i);
	          		global $$valueobj;
	          		global $$descrobj;
	          		echo "<option value='".$$valueobj."'>".$$descrobj."</option>\n";
	          	}
	          }else if(gettype($recordsel) == "array"){
	          	$numrows_recsel = count($recordsel);
	          	reset($recordsel);
	          	for($i=0; $i<$numrows_recsel; $i++){
	          	  $$valueobj = key($recordsel);
	          	  $$descrobj = $recordsel[$$valueobj];
	          		echo "<option value='".$$valueobj."'>".$$descrobj."</option>\n";
	          		next($recordsel);
	          	}
	          }
            ?>
            </select>
          </fieldset>
        </td>
        <td nowrap valign="center">
	        <?
	        if($ordenarselect == false){
	        	echo "
	        	      <img onClick='js_sobe();return false' src='imagens/seta_up.gif' width='20' height='20' border='0'>
	                <br><br>
	                <img onClick='js_desce();' src='imagens/seta_down.gif' width='20' height='20' border='0'>
	               ";
	        }
	        ?>
        </td>
      </tr>
      <tr>
        <td colspan="3" align='center'>
          <b>Dois Clicks para Movimentar os Itens</b>
        </td>
      </tr>
    </table>
<script>
function js_sobe() {
  var F = document.form1.<?=$objsel?>;
  if(F.selectedIndex != -1 && F.selectedIndex > 0) {
    var SI = F.selectedIndex - 1;
    var auxText = F.options[SI].text;
   	var auxValue = F.options[SI].value;
	  F.options[SI] = new Option(F.options[SI + 1].text,F.options[SI + 1].value);
	  F.options[SI + 1] = new Option(auxText,auxValue);
	  js_trocacordeselect();
	  F.options[SI].selected = true;
  }
}
function js_desce() {
  var F = document.form1.<?=$objsel?>;
  if(F.selectedIndex != -1 && F.selectedIndex < (F.length - 1)) {
    var SI = F.selectedIndex + 1;
    var auxText = F.options[SI].text;
	  var auxValue = F.options[SI].value;
	  F.options[SI] = new Option(F.options[SI - 1].text,F.options[SI - 1].value);
	  F.options[SI - 1] = new Option(auxText,auxValue);
	  js_trocacordeselect();
	  F.options[SI].selected = true;
  }
}

// Retorna apenas campos selecionados
// variavel = return js_db_multiploselect_retornaselecionados();
function js_db_multiploselect_retornaselecionados(){
  txt11 = "";
  vir11 = "";

  for(i=0;i<document.form1.<?=$objsel?>.length;i++){
    txt11 += vir11+document.form1.<?=$objsel?>.options[i].value;
    vir11 = ",";
  }
  stringretorno = txt11;
  return stringretorno;
}

// Retorna apenas campos n�o selecionados
// variavel = return js_db_multiploselect_retornanaoselecionados();
function js_db_multiploselect_retornanaoselecionados(){
  txt11 = "";
  vir11 = "";

  for(i=0;i<document.form1.<?=$objnsel?>.length;i++){
    txt11 += vir11+document.form1.<?=$objnsel?>.options[i].value;
    vir11 = ",";
  }
  stringretorno = txt11;
  return stringretorno;
}

// Retorna apenas campos selecionadosn e n�o selecionados
// variavel = return js_db_multiploselect_retornacampos();
function js_db_multiploselect_retornacampos(){
  txt22 = "";
  vir22 = "";

  txt11 = "";
  vir11 = "";

  for(i=0;i<document.form1.<?=$objnsel?>.length;i++){
    txt22 += vir22+document.form1.<?=$objnsel?>.options[i].value;
    vir22 = ",";
  }

  for(i=0;i<document.form1.<?=$objsel?>.length;i++){
    txt11 += vir11+document.form1.<?=$objsel?>.options[i].value;
    vir11 = ",";
  }
  stringretorno = txt22+"#"+txt11;
  return stringretorno;
}

// Fun��o para incluir todos os elementos do SELECT MULTIPLE escolhido no outro
// Esta fun��o selecionar� todos os elementos do SELECT e chamar� a fun��o js_db_multiploselect_incluir_item para enviar os itens
// para o SELECT desejado. Quando retornar da fun��o js_db_multiploselect_incluir_item, ela limpar� o select remetente
function js_db_multiposelect_incluir_todos(obj1,obj2){
  for(i=0;i<obj1.length;i++){
    obj1.options[i].selected = true;
  }
  linhasoption = obj2.length;
  js_db_multiploselect_incluir_item(obj1,obj2);
  obj1.length = 0;
  if(linhasoption == 0){
    for(i=0;i<obj2.length;i++){
      obj2.options[i].selected = false;
    }
  }
}

// Esta fun��o serve para passar os itens de um SELECT para o outro.
function js_db_multiploselect_incluir_item(obj1,obj2){
  var erro = 0;

  // Tirar o foco de todos os itens do select RECEPTOR
  for(i=0;i<obj2.length;i++){
    obj2.options[i].selected = false;
  }

  // Verifica a quantidade de itens no SELECT EMISSOR
  for(i=0;i<obj1.length;i++){

    // Testa se o item corrente esta selecionado
    if(obj1.options[i].selected){

      // Seta o valor defaul do novo item do SELECT RECEPTOR
      x = obj2.length;

      // Se for para ordenar os itens dentro dos selects ao serem mudados de local
      ordenaritens = true;
      <?
      if($ordenarselect == false){
      	echo "ordenaritens = false;\n";
      }
      ?>

      // Se a quantidade de itens do SELECT RECEPTOR for maior que zero, testa se encontra algum item que o valor
      // seja maior que o item corrente do SELECT EMISSOR
      if(obj2.length > 0 && ordenaritens == true){
        for(x=0;x<obj2.length;x++){
          if(obj1.options[i].value < obj2.options[x].value){
            break;
          }
        }

        // Repete no SELECT RECEPTOR o seu �ltimo item
        obj2.options[obj2.length] = new Option(obj2.options[obj2.length-1].text,obj2.options[obj2.length-1].value);

        // Busca todos os itens que o valor � menor que o �ltimo item e reorganiza os dados dentro do SELECT
        for(y=obj2.length-1;x<y;y--){
          obj2.options[y] = new Option(obj2.options[y-1].text,obj2.options[y-1].value)
        }
      }

      // Inclui o item que esta vindo do select EMISSOR
      obj2.options[x] = new Option(obj1.options[i].text,obj1.options[i].value);
      obj2.options[x].selected = true;
      erro ++;
    }
  }
  if(erro > 0){
  	// Tira a sele��o dos itens do SELECT EMISSOR
    for(i=0;i<obj1.length;i++){
      if(obj1.options[i].selected){
        obj1.options[i] = null;
        i = -1;
      }
    }
  }else{
    alert("Selecione um item");
  }
  <?=$jsincluir?>
  js_trocacordeselect();
}
js_trocacordeselect();
</script>
  <?
}


function db_selectrecord($nome, $record, $dbcadastro, $db_opcao = 3, $js_script = "", $nomevar = "", $bgcolor = "", $todos = "", $onchange = "", $numcol = 2) {
		//#00#//db_selectrecord
		//#10#//Fun��o para montar um ou dois objetos select na tela, recebendo dados de um recordset
		//#15#//db_selectrecord($nome,$record,$dbcadastro,$db_opcao=3,$js_script="",$nomevar="",$bgcolor="",$todos="",$onchange="",$numcol=2);
		//#20#//Nome            : Nome do ca po da documentacao do sistema ou do arquivo
		//#20#//Record Set      : Recordset que gerar� os objetos select, sendo o primeiro campo do recordset o campo chave
		//#20#//                  e o segundo campo a descricao.
		//#20#//Cadastro        : True se cadastro ou false se nao cadastro Padr�o: true )
		//#20#//Opcao           : *db_opcao* do programa a ser executado neste objeto input, inclus�o(1) altera��o(2) exclus�o(3)
		//#20#//Script          : JAVASCRIPT  a ser executado juntamento com o objeto, indicando os m�todos
		//#20#//Nome Secund�rio : Nome do input que ser� gerado, assumindo somente as caracter�sticas do campo Nome
		//#20#//Cor Background  : Cor de fundo da tela, no caso de *db_opcao*=3 ser� "#DEB887"
		//#20#//Todos           : Indica de ser� colocado um �tem inicial com op��o de todos "Todos ..." com valor zero (0)
		//#20#//OnChange        : Fun��o que ser� inclu�da no m�todo onchange dos objetos select, al�m das fun��e ja inclu�das
		//#20#//                  que servem para movimentar os select. Sempre que alterar um deles, o sistema altera o outro
		//#20#//Numero Select   : N�mero de select que ser�o mostrados na tela. O padr�o � dois, caso seja indicado este
		//#20#//                  par�metro, o sistema mostrar� somente o select do segundo campo (descri��o) e retornar� o
		//#20#//                  c�digo do �tem, o valor do primeiro campo
		//#99#//Quando o par�metro *db_opcao* for de altera��o (Opcao = 22) ou exclus�o (Op��o = 33) o sistema
		//#99#//n�o mostrar� os objetos desta fun��o e sim executar� o objeto INPUT com as op��es deste
		//#99#//objeto. Isto faz com que o usu�rio n�o movimente um select enquanto n�o selecionar um
		//#99#//c�digo de registro para alterar ou excluir
		//#99#//
		//#99#//O tamanho do objeto na tela depender� do tamanho do campo inserido no select
		//#99#//
		//#99#//Ap�s montar o select, sistema executa uma fun��o javascript para selecionar o elemento
		//#99#//do select que possui o mesmo valor do campo indicado na vari�vel Nome
	if ($db_opcao != 3 && $db_opcao != 5 && $db_opcao != 22 && $db_opcao != 33 && $db_opcao != 11) {
		if ($nomevar != "") {
			$nome = $nomevar;
			$nomedescr = $nomevar."descr";
		} else {
			$nomedescr = $nome."descr";
		}
		if ($numcol == 2) {
?>
    <select name="<?=$nome?>" id="<?=$nome?>"
    <?



			if ($numcol == 2)
				echo "onchange=\"js_ProcCod_$nome('$nome','$nomedescr');$onchange\"";
			else
				echo "onchange=\"$onchange\"";
			if ($dbcadastro == true) {
				if ($db_opcao == 3 || $db_opcao == 22 || $db_opcao == 11) {
					echo " readonly ";
					if ($bgcolor == "")
						$bgcolor = "#DEB887";
				}
				if ($db_opcao == 5) {
					echo " disabled ";
				}
			}
			echo $js_script;
?>
     >
    <?



			if ($todos != "") {
				if (strpos($todos, "-") > 0)
					$todos = split("-", $todos);
				else
					$todos = array ("0" => $todos, "1" => "Todos ...");
?>
      <option value="<?=$todos[0]?>" ><?=$todos[0]?></option>
      <?



			}
			for ($sqli = 0; $sqli < pg_numrows($record); $sqli ++) {
				$sqlv = pg_result($record, $sqli, 0);
?>
      <option value="<?=$sqlv?>" <?=(@$GLOBALS[$nome]==$sqlv?"selected":"")?>><?=$sqlv?></option>
      <?



			}
?>
    </select>
    <?



		} else {
			$nomedescr = $nome;
		}
		if ($record != false && pg_numrows($record) > 0 && pg_numfields($record) > 0) {
?>
      <select name="<?=$nomedescr?>" id="<?=$nomedescr?>"
	  onchange="js_ProcCod_<?=$nome?>('<?=$nomedescr?>','<?=$nome?>');<?=$onchange?>"
      <?


			if ($dbcadastro == true) {
				if ($db_opcao == 3 || $db_opcao == 22) {
					echo " readonly ";
					if ($bgcolor == "")
						$bgcolor = "#DEB887";

				}
				if ($db_opcao == 5) {
					echo " disabled ";
				}
			}
			echo $js_script;
?>
       >
      <?



			if (is_array($todos) || $todos != "") {
?>
	  <option value="<?=$todos[0]?>" ><?=$todos[1]?></option>
	  <?



			}
			for ($sqli = 0; $sqli < pg_numrows($record); $sqli ++) {
				$sqlv = pg_result($record, $sqli, 0);
				$sqlv1 = pg_result($record, $sqli, 1);
?>
      <option value="<?=$sqlv?>" ><?=$sqlv1?></option>
        <?



			}
?>
      </select>
      <script>
      function js_ProcCod_<?=$nome?>(proc,res) {
       var sel1 = document.forms[0].elements[proc];
       var sel2 = document.forms[0].elements[res];
       for(var i = 0;i < sel1.options.length;i++) {
	 if(sel1.options[sel1.selectedIndex].value == sel2.options[i].value)
	   sel2.options[i].selected = true;
	 }
       }
      <?



			if (isset ($GLOBALS[$nome])) {
				if ($GLOBALS[$nome] != "") {
					echo "var sel1 = document.form1.$nome;\n";
					echo "for(var i = 0;i < sel1.options.length;i++) {\n";
					echo "  if(sel1.options[i].value == '".$GLOBALS[$nome]."')\n";
					echo "  sel1.options[i].selected = true;\n";
					echo "}\n";
				} else {
					echo "document.forms[0].".$nome.".options[0].selected = true;";
				}
			} else {
				echo "document.forms[0].".$nome.".options[0].selected = true;";
			}
?>
      js_ProcCod_<?=$nome?>('<?=$nome?>','<?=$nomedescr?>');
      </script>
      <?



		} else {
?>
      <script>
      function js_ProcCod_<?=$nome?>(){
      }
      </script>
      <?
		}
	} else {
		$clrot = new rotulocampo;
		$clrot->label("$nome");
		$tamm = "M$nome";
		db_input($nome, $GLOBALS[$tamm], '', true, 'text', 3, "", $nomevar, "");
		$nomec = "";
		if ($nomevar != "") {
			$nome = $nomevar;
			$nomedescr = $nomevar."descr";
		} else {
			$nomedescr = $nome."descr";
		}

		for ($sqli = 0; $sqli < pg_numrows($record); $sqli ++) {
			if (pg_result($record, $sqli, 0) == @ $GLOBALS[$nome]) {
				$nomec = pg_fieldname($record, 1);
				global $$nomec;
				$$nomec = pg_result($record, $sqli, 1);
				$clrot->label($nomec);
				$tamm = "M".trim($nomec);
				break;
			}
		}
		if (!empty ($nomec)) {
			if ($GLOBALS[$tamm] > 40)
				$GLOBALS[$tamm] = 60;
			db_input($nomec, $GLOBALS[$tamm], '', true, 'text', 3, "");
		}
	}
}
//////////////////////////////////////

function db_selectmultiple($nome, $record, $size, $db_opcao = 3, $js_script = "", $nomevar = "", $bgcolor = "", $record_select = "", $onchange = "",$compltags="") {
		//#00#//db_selectmultiple
		//#10#//Fun��o para montar um objeto select do tipo multiple (multiplas linhas) na tela, recebendo dados de um recordset
		//#15#//db_selectmultiple($nome,$record,$size,$db_opcao=3,$js_script="",$nomevar="",$bgcolor="",$record_select="",$onchange="");
		//#20#//Nome            : Nome do ca po da documentacao do sistema ou do arquivo
		//#20#//Record Set      : Recordset ou Array que gera o objeto select, sendo o primeiro campo do recordset o campo chave
		//#20#//                  e o segundo campo a descricao que aparecer� na tela
		//#20#//Tamanho         : N�mero de linhas que o objeto ocupar� na tela
		//#20#//Opcao           : *db_opcao* do programa a ser executado neste objeto input, inclus�o(1) altera��o(2) exclus�o(3)
		//#20#//Script          : JAVASCRIPT  a ser executado juntamento com o objeto, indicando os m�todos
		//#20#//Nome Secund�rio : Nome do input que ser� gerado, assumindo somente as caracter�sticas do campo Nome
		//#20#//Cor Background  : Cor de fundo da tela, no caso de *db_opcao*=3 ser� "#DEB887"
		//#20#//Record Set      : Este recordset enviado para a fun��o ter� os valores que ser�o habilitados no objeto select
		//#20#//                  multiple, colocandos-os com a propriedade selected habilidata
		//#20#//OnChange        : Fun��o ou fun��es que ser�o inclu�das no m�todo onchange dos objetos select.
		//#20#//compltags       : Complementos da tag do Select como por exemplo: onclick, onblur, etc...
		//#99#//Quando o par�metro Op��o for de altera��o (Opcao = 22) ou exclus�o (Op��o = 33) o sistema
		//#99#//n�o mostrar� os objetos desta fun��o e sim executar� o objeto SELECT com as op��es do
		//#99#//segundo recordset, mostrando somente os dados cadastrados no c�digo de registro para alterar
		//#99#//ou excluir
	if ($nomevar != "") {
		$nome = $nomevar;
	}
	if ($db_opcao != 3 && $db_opcao != 5 && $db_opcao != 33 && $db_opcao != 22) {
		/*change="js_ProcCod_<?=$nome?>('<?=$nome?>','<?=$nome?>');<?=$onchange?>"tava assim dae eu mudei pra : (ze)*/
?>
    <select class="DBSelectMultiplo" multiple name="<?=$nome?>[]" size="<?=$size?>" id="<?=$nome?>"
	  onchange="<?=$js_script?>" <?=$compltags?>
    <?


		if ($db_opcao == 3 || $db_opcao == 22) {
			echo " readonly ";
			if ($bgcolor == "")
				$bgcolor = "#DEB887";
		}
		if ($db_opcao == 5) {
			echo " disabled ";
		}
		echo $js_script;
?>
     >
    <?
    if(gettype($record) == "resource"){
			for ($sqli = 0; $sqli < pg_numrows($record); $sqli ++) {
				if ($sqli % 2 == 0) {
					$color = "#D7CC06";
				} else {
					$color = "#F8EC07";
				}
				$sqlv = pg_result($record, $sqli, 0);
				$sqlv1 = pg_result($record, $sqli, 1);
				$esta_selecionado = "";
				if ($db_opcao != 1 && $db_opcao != 22) {
					for ($sqls = 0; $sqls < pg_numrows($record_select); $sqls ++) {
						$sqlsv = pg_result($record_select, $sqls, 0);
						if ($sqlsv == $sqlv)
							$esta_selecionado = " selected ";
					}
				}
	      /** <option value="<?=$sqlv?>" style="background-color:<?=$color?>" <?=$esta_selecionado?>><?=$sqlv1?></option> */
	      ?>
	      <option value="<?=$sqlv?>" <?=$esta_selecionado?>><?=$sqlv1?></option>
	      <?
			}
    }else if(gettype($record) == "array"){
    	$numrows_recsel = count($record);
    	reset($record);
    	for($sqli=0; $sqli<$numrows_recsel; $sqli++){
				if ($sqli % 2 == 0) {
					$color = "#D7CC06";
				} else {
					$color = "#F8EC07";
				}
    	  $valueobj = key($record);
    	  $descrobj = $record[$valueobj];
        $esta_selecionado = "";
				if ($db_opcao != 1 && $db_opcao != 22) {
					reset($record_select);
					for ($sqls = 0; $sqls < count($record_select); $sqls ++) {
						$sqlsv = key($record_select);
						if ($sqlsv == $valueobj){
							$esta_selecionado = " selected ";
						}
						next($record_select);
					}
				}
				/**option value="<?=$valueobj?>" style="background-color:<?=$color?>" <?=$esta_selecionado?>><?=$descrobj?></option>*/
	      ?>
	      <option value="<?=$valueobj?>" <?=$esta_selecionado?>><?=$descrobj?></option>
	      <?
    		next($record);
    	}
    }
    ?>
    </select>
    <?



	} else {
		if (!is_int($record_select) && $record_select != false) {
			if(gettype($record) == "resource"){
				if (pg_numrows($record_select) > 0) {
					db_selectrecord($nome, $record_select, true, ($db_opcao == 3 ? 2 : $db_opcao), "", $nomevar = "", $bgcolor = "", $todos = "", $onchange = "");
				}
      }else if(gettype($record) == "array"){
      	if(count($record_select) > 0){
      		db_select($nome, $record_select, true, ($db_opcao == 3 ? 2 : $db_opcao), "","", "");
      	}
			}
		} else {
			db_input($nome, 5, '', true, 'text', 3, "");
		}
	}
}

/*************************************/

function db_select($nome, $db_matriz, $dbcadastro, $db_opcao = 3, $js_script = "", $nomevar = "", $bgcolor = "") {
		//#00#//db_select
		//#10#//Fun��o para montar um objeto select na tela, recebendo dados de uma matriz
		//#15#//db_select($nome,$db_matriz,$dbcadastro,$db_opcao=3,$js_script="",$nomevar="",$bgcolor="");
		//#20#//Nome            : Nome do campo da documentacao do sistema ou do arquivo
		//#20#//Matriz          : Matriz com os dados a serem colocados no select, sendo a chave (key) da matriz o valor
		//#20#//                  a ser retornado e o conte�do da matriz o valor a ser mostrado na tela
		//#20#//                  ex: $x = array("1"=>"um") 1=key e um=conte�do;
		//#20#//Cadastro        : True se cadastro ou false se nao cadastro Padr�o: true )
		//#20#//Opcao           : *db_opcao* do programa a ser executado neste objeto input, inclus�o(1) altera��o(2) exclus�o(3)
		//#20#//Script          : JAVASCRIPT  a ser executado juntamento com o objeto, indicando os m�todos
		//#20#//Nome Secund�rio : Nome do input que ser� gerado, assumindo somente as caracter�sticas do campo Nome
		//#20#//Cor Background  : Cor de fundo da tela, no caso de *db_opcao*=3 ser� "#DEB887"
		//#99#//Quando o par�metro Op��o for de altera��o (Opcao = 22) ou exclus�o (Op��o = 33) o sistema
		//#99#//n�o mostrar� o objeto desta fun��o e sim executar� o objeto INPUT e colocar� o valor do
		//#99#//conte�do para este bjeto
		//#99#//
		//#99#//O sistema verifica o valor do campo Nome (conte�do do campo) e verifica se algum dos
		//#99#//campos key da matriz � igual a ele, ent�o coloca a propriedade SELECTED habilitada
		//#99#//para este elemento, deixando-o selecionado na tela
	if ($db_opcao != 3 && $db_opcao != 5 && $db_opcao != 22 && $db_opcao != 33) {
?>
    <select name="<?=$nome?>" id="<?=$nome?>"
    <?


		if ($dbcadastro == true) {
			if ($db_opcao == 3 || $db_opcao == 22) {
				echo " readonly ";
				if ($bgcolor == "")
					$bgcolor = "#DEB887";
			}
			if ($db_opcao == 5) {
				echo " disabled ";
			}
		}
		echo $js_script;
?>
     >
    <?


		//x = array("a"=>"1","2")
		reset($db_matriz);
		for ($i = 0; $i < sizeof($db_matriz); $i ++) {
?>
      <option value="<?=key($db_matriz)?>" <?=(@$GLOBALS[$nome]==key($db_matriz)?"selected":"")?>><?=$db_matriz[key($db_matriz)]?></option>
      <?



			next($db_matriz);
		}
?>
    </select>
    <?



	} else {
		$nome_select_descr = $nome."_select_descr";
		global $$nome_select_descr, $$nome;
		$$nome = $GLOBALS[$nome];

		reset($db_matriz);
		for ($matsel = 0; $matsel < sizeof($db_matriz); $matsel ++) {
			if (key($db_matriz) == $$nome) {
				$$nome_select_descr = $db_matriz[key($db_matriz)];
				$$nome = key($db_matriz);
			}
			next($db_matriz);
		}
		if (strlen($$nome_select_descr) > 8) {
			if (strlen($$nome_select_descr) > 40) {
				$tamanho = 60;
			} else {
				$tamanho = strlen($$nome_select_descr);
			}
		} else {
			$tamanho = strlen($$nome_select_descr);
		}
		$Mtam = "M$nome";
		global $$Mtam;
		$$Mtam = $tamanho;
		db_input($nome_select_descr, $tamanho +4, '', $dbcadastro, 'text', 3, "", "", "");
		db_input($nome, $tamanho +4, '', $dbcadastro, 'hidden', 3, "", "", "");
	}
}
//////////////////////////////////////

function db_inputdata($nome, $dia = "", $mes = "", $ano = "", $dbcadastro = true, $dbtype = 'text', $db_opcao = 3, $js_script = "", $nomevar = "", $bgcolor = "",$shutdown_function="none",$onclickBT="", $onfocus="", $jsRetornoCal=""){
		//#00#//db_inputdata
		//#10#//Fun��o para montar um objeto tipo data. Ser�o tr�s objetos input na tela mais um objeto input tipo button para
		//#10#//acessar o calend�rio do sistema
		//#15#//db_inputdata($nome,$dia="",$mes="",$ano="",$dbcadastro=true,$dbtype='text',$db_opcao=3,$js_script="",$nomevar="",$bgcolor="",$shutdown_funcion="none",$onclickBT="",$onfocus"");
		//#20#//Nome            : Nome do campo da documentacao do sistema ou do arquivo
		//#20#//Dia             : Valor para o objeto |db_input| do dia
		//#20#//M�s             : Valor para o objeto |db_input| do m�s
		//#20#//Ano             : Valor para o objeto |db_input| do ano
		//#20#//Cadastro        : True se cadastro ou false se nao cadastro Padr�o: true
		//#20#//Type            : Tipo a ser incluido para a data Padr�o: text
		//#20#//Opcao           : *db_opcao* do programa a ser executado neste objeto input, inclus�o(1) altera��o(2) exclus�o(3)
		//#20#//Script          : JAVASCRIPT  a ser executado juntamento com o objeto, indicando os m�todos
		//#20#//Nome Secund�rio : Nome do input que ser� gerado, assumindo somente as caracter�sticas do campo Nome
		//#20#//Cor Background  : Cor de fundo da tela, no caso de *db_opcao*=3 ser� "#DEB887"
		//#20#//shutdown_funcion : fun��o que ser� executada apos o retorno do calend�rio
		//#20#//onclickBT       : Fun��o que ser� executada ao clicar no bot�o que abre o calend�rio
		//#20#//onfocus         : Fun��o que ser� executada ao focar os campos
		//#99#//Quando o par�metro Op��o for de altera��o (Opcao = 22) ou exclus�o (Op��o = 33) o sistema
		//#99#//colocar� a sem acesso ao calend�rio
		//#99#//Para *db_opcao* 3 e 5 o sistema colocar� sem o calend�rio e com readonly
		//#99#//
		//#99#//Os tr�s input gerados para a data ter�o o nome do campo acrescido do [Nome]_dia, [Nome]_mes e
		//#99#//[Nome]_ano os quais ser�o acessados pela classe com estes nome.
		//#99#//
		//#99#//O sistema ger� para a primeira data inclu�da um formul�rio, um objeto de JanelaIframe do nosso
		//#99#//sistema para que sej� mostrado o calend�rio.

	global $DataJavaScript;

	if ($db_opcao == 3 || $db_opcao == 22 || $db_opcao == 33) {
		$bgcolor = "style='background-color:#DEB887'";
	}

  if ($bgcolor == "") {
    $bgcolor = @$GLOBALS['N'.$nome];
  }


  if(isset($dia) && $dia != "" && isset($mes) && $mes != '' && isset($ano) && $ano != ""){
    $diamesano = $dia."/".$mes."/".$ano;
    $anomesdia = $ano."/".$mes."/".$dia;
	}

  $sButtonType = "button";

?>

  <input name="<?=($nomevar==""?$nome:$nomevar).""?>" <?=$bgcolor?>   type="<?=$dbtype?>" id="<?=($nomevar==""?$nome:$nomevar).""?>" <?=($db_opcao==3 || $db_opcao==33 || $db_opcao==22 ?'readonly':($db_opcao==5?'disabled':''))?> value="<?=@$diamesano?>" size="10" maxlength="10" autocomplete="off" onBlur='js_validaDbData(this);' onKeyUp="return js_mascaraData(this,event)"  onFocus="js_validaEntrada(this);" <?=$js_script?> >
	<input name="<?=($nomevar==""?$nome:$nomevar)."_dia"?>"   type="hidden" title="" id="<?=($nomevar==""?$nome:$nomevar)."_dia"?>" value="<?=@$dia?>" size="2"  maxlength="2" >
  <input name="<?=($nomevar==""?$nome:$nomevar)."_mes"?>"   type="hidden" title="" id="<?=($nomevar==""?$nome:$nomevar)."_mes"?>" value="<?=@$mes?>" size="2"  maxlength="2" >
  <input name="<?=($nomevar==""?$nome:$nomevar)."_ano"?>"   type="hidden" title="" id="<?=($nomevar==""?$nome:$nomevar)."_ano"?>" value="<?=@$ano?>" size="4"  maxlength="4" >
  <?
	if (($db_opcao < 3) || ($db_opcao == 4)) {
  ?>
  <script>
  var PosMouseY, PosMoudeX;

  function js_comparaDatas<?=($nomevar==""?$nome:$nomevar).""?>(dia,mes,ano){
    var objData        = document.getElementById('<?=($nomevar==""?$nome:$nomevar).""?>');
		objData.value      = dia+"/"+mes+'/'+ano;
    <?=$jsRetornoCal?>
	}

  </script>
  <?
   if (isset($dbtype) && strtolower($dbtype) == strtolower('hidden')) {
     $sButtonType = "hidden";
   }

  ?>

  <input value="D" type="<?=$sButtonType?>" name="dtjs_<?=($nomevar==""?$nome:$nomevar)?>" onclick="<?=$onclickBT?>pegaPosMouse(event);show_calendar('<?=($nomevar==""?$nome:$nomevar)?>','<?=$shutdown_function?>')"  >

  <?

	}

}
/*************************************/

//////////////////////////////////////
function db_data($nome, $dia = "", $mes = "", $ano = "") {
	global $DataJavaScript;
	if (!isset ($DataJavaScript)) {
		$DataJavaScript = new janela("DataJavaScript", "");
		$DataJavaScript->posX = 1;
		$DataJavaScript->posY = 1;
		$DataJavaScript->largura = 140;
		$DataJavaScript->altura = 210;
		$DataJavaScript->titulo = "Calend�rio";
		$DataJavaScript->iniciarVisivel = false;
		$DataJavaScript->scrollbar = "no";
		$DataJavaScript->janBotoes = "001";
		$DataJavaScript->mostrar();
	}
?>
  <input name="<?=$nome."_dia"?>" onFocus="ContrlDigitos=0" onKeyUp="js_Passa(this.name,<?=date("j")?>,<?=(date("n") - 1)?>,<?=date("Y")?>)" type="text" id="<?=$nome."_dia"?>" value="<?=$dia?>" size="2" maxlength="2" autocomplete="off"><strong>/</strong>
  <input name="<?=$nome."_mes"?>" onFocus="ContrlDigitos=0" onKeyUp="js_Passa(this.name,<?=date("j")?>,<?=(date("n") - 1)?>,<?=date("Y")?>)" type="text" id="<?=$nome."_mes"?>" value="<?=$mes?>" size="2" maxlength="2" autocomplete="off"><strong>/</strong>
  <input name="<?=$nome."_ano"?>" onFocus="ContrlDigitos=0" onKeyUp="js_Passa(this.name,<?=date("j")?>,<?=(date("n") - 1)?>,<?=date("Y")?>)" type="text" id="<?=$nome."_ano"?>" value="<?=$ano?>" size="4" maxlength="4" autocomplete="off">
  <input value="D" type="button" name="acessadatajavascript" onclick="pegaPosMouse(event);show_calendar('form1.<?=$nome?>')">
  <?



}
/*************************************/
function db_label_blur($tab, $label, $campo = "", $campoaux = "") {

	$campo = ($campo == "") ? $label : $campo;
?>
  <strong>
  <label for="db_<?=$campo?>">
  <a href="" class="rotulos" onClick="js_lista_blur('dbforms/db_<?=$tab?>.php',document.form1.db_<?=$campo?>.value,'<?=$campo?>',100,50,600,420,document.form1.db_<?=$campoaux?>.value,'<?=$campoaux?>');return false">
    <?=ucwords($label)?>:
  </a>
  </label>
  </strong>
<?



}

function db_text_blur($tab, $campo, $campoaux, $tamanho, $max, $db_nome = "", $dbh_nome = "") {
?>
  <input name="db_<?=$campo?>" id="db_<?=$campo?>" <?=@$read_only?> value="<?=$db_nome?>" type="text" size="<?=$tamanho?>" maxlength="<?=$max?>" onChange="if(this.value!='') js_lista_blur('dbforms/db_<?=$tab?>.php','db_<?=$campo?>' + '==' + document.form1.db_<?=$campo?>.value,'<?=$campo?>',100,50,600,420,'db_<?=$campoaux?>' + '==' + document.form1.db_<?=$campoaux?>.value,'<?=$campoaux?>','')" autocomplete="off">
  <input name="dbh_<?=$campo?>" type="hidden" value="<?=$dbh_nome?>">
<?



}

function db_label($tab, $label, $campo = "") {
	$campo = ($campo == "") ? $label : $campo;
?>
  <strong>
  <label for="db_<?=$campo?>">
  <a href="" class="rotulos" onClick="js_lista('dbforms/db_<?=$tab?>.php','db_<?=$campo?>' + '==' + document.form1.db_<?=$campo?>.value,'<?=$campo?>',05,50,780);return false">
    <?=ucwords($label)?>:
  </a>
  </label>
  </strong>
<?



}
/************************************/
// Parametro $validacao
// 0 Aceita qualquer coisa
// 1 Aceita apenas numeros
// 2 Aceita apenas letras
function db_text($campo, $tamanho, $max, $db_nome = "", $dbh_nome = "", $validacao = 0) {
?>
  <input name="db_<?=$campo?>" onBlur="js_ValidaCamposText(this,<?=$validacao?>)" id="db_<?=$campo?>" <?=@$readonly?> value="<?=$db_nome?>" type="text" size="<?=$tamanho?>" maxlength="<?=$max?>" autocomplete="off">
  <input name="dbh_<?=$campo?>" type="hidden" value="<?=$dbh_nome?>">
<?



}

/************************************/
function db_file($campo, $tamanho, $max, $dbh_nome = "", $db_nome = "") {
?>
  <input onChange="js_preencheCampo(this.value,this.form.dbh_<?=$campo?>.name)" name="db_<?=$campo?>" id="db_<?=$campo?>" value="<?=$db_nome?>" type="file" size="<?=$tamanho?>" maxlength="<?=$max?>" autocomplete="off"><br>
  <input name="dbh_<?=$campo?>" type="text" value="<?=$dbh_nome?>" size="<?=$tamanho?>" maxlength="<?=$max?>" autocomplete="off">
<?



}
/************************************/
function db_getfile($arq, $text, $funcao = "0") {
	db_postmemory($GLOBALS["_FILES"][$arq]);
	$DB_FILES = $GLOBALS["DB_FILES"];
	$tmp_name = $GLOBALS["tmp_name"];
	$name = $GLOBALS["name"];
	$size = $GLOBALS["size"];
	if ($funcao != "0") {
		if ($name != "") {
			system("rm -f $DB_FILES/$funcao");
			copy($tmp_name, "$DB_FILES/$text");
			return $text;
		} else
			if ($text != "") {
				if ($text != $funcao) {
					system("mv $DB_FILES/$funcao $DB_FILES/$text");
					return $text;
				} else
					return $text;
			} else
				if ($text == "") {
					system("rm -f $DB_FILES/$funcao");
					return "";
				}
	} else
		if ($name != "" && $size == 0) {
			db_erro("O arquivo $name n�o foi encontrado ou ele est� vazio. Verifique o seu caminho e o seu tamanho e tente novamente.");
		} else {
			copy($tmp_name, "$DB_FILES/$text");
			return $text;
		}
}

/////////////////  datas dos relatorios da RLF //////////////
function bimestre_meses($bimestre = 1) {
	$mes[0] = $bimestre + ($bimestre -1); // primeiro mes do bimestre
	$mes[1] = $mes[0] + 1; // segundo mes do bimestre
	return $mes;
}
function ultimo_dia_mes($mes = 1, $ano = "") {
	$res = db_query("select fc_ultimodiames($ano,$mes)"); //fc_ultimodiames(anousu,mes); // retorna ultimo da do mes
	$ultimo_dia = pg_result($res, 0, 0);
	return $ultimo_dia;
}
function data_periodo($anousu,$tipo='1B'){
   //#10#// retorna datas bimestrais, quadrimestras ou semestrais
   //#20#// tipo: [1B|2B|3B|4B|5B|6B|1Q|2Q|3Q|1S|2S]
   //#20#//
   $mes_ini = '';
   $mes_fin = '';
   $texto ='';
   $abrev ='';

   if ($tipo=='1B'){
      $mes_ini=1;  $mes_fin=2;
      $texto = 'PRIMEIRO BIMESTRE';
      $abrev = 'Bimestre';
   } elseif($tipo=='2B'){
      $mes_ini=3;  $mes_fin=4;
      $texto = 'SEGUNDO BIMESTRE';
      $abrev = 'Bimestre';
   } elseif($tipo=='3B'){
      $mes_ini=5;  $mes_fin=6;
      $texto = 'TERCEIRO BIMESTRE';
      $abrev = 'Bimestre';
   } elseif($tipo=='4B'){
      $mes_ini=7;  $mes_fin=8;
      $texto = 'QUARTO BIMESTRE';
      $abrev = 'Bimestre';
   } elseif($tipo=='5B'){
      $mes_ini=9;  $mes_fin=10;
      $texto = 'QUINTO BIMESTRE';
      $abrev = 'Bimestre';
   } elseif($tipo=='6B'){
      $mes_ini=11;  $mes_fin=12;
      $texto = 'SEXTO BIMESTRE';
      $abrev = 'Bimestre';
   } elseif($tipo=='1Q'){
      $mes_ini=1;  $mes_fin=4;
      $texto = 'PRIMEIRO QUADRIMESTRE';
      $abrev = 'Quadrimestre';
   } elseif($tipo=='2Q'){
      $mes_ini=5;  $mes_fin=8;
      $texto = 'SEGUNDO QUADRIMESTRE';
      $abrev = 'Quadrimestre';
   } elseif($tipo=='3Q'){
      $mes_ini=9;  $mes_fin=12;
      $texto = 'TERCEIRO QUADRIMESTRE';
      $abrev = 'Quadrimestre';
   } elseif($tipo=='1S'){
      $mes_ini=1;  $mes_fin=6;
      $texto = 'PRIMEIRO SEMESTRE';
      $abrev = 'Semestre';
   } elseif($tipo=='2S'){

      $mes_ini=7;  $mes_fin=12;
      $texto = 'SEGUNDO SEMESTRE';
      $abrev = 'Semestre';

   } elseif ($tipo == 'JAN') {

     $mes_ini = 1;
     $mes_fin = 1;
     $texto   = 'JANEIRO';
     $abrev   = 'M�s';

   } elseif ($tipo == 'FEV') {

     $mes_ini = 2;
     $mes_fin = 2;
     $texto   = 'FEVEREIRO';
     $abrev   = 'M�s';

   } elseif ($tipo == 'MAR') {

     $mes_ini = 3;
     $mes_fin = 3;
     $texto   = 'MAR�O';
     $abrev   = 'M�s';

   } elseif ($tipo == 'ABR') {

     $mes_ini = 4;
     $mes_fin = 4;
     $texto   = 'ABRIL';
     $abrev   = 'M�s';

   } elseif ($tipo == 'MAI') {

     $mes_ini = 5;
     $mes_fin = 5;
     $texto   = 'MAIO';
     $abrev   = 'M�s';

   } elseif ($tipo == 'JUN') {

     $mes_ini = 6;
     $mes_fin = 6;
     $texto   = 'JUNHO';
     $abrev   = 'M�s';

   } elseif ($tipo == 'JUL') {

     $mes_ini = 7;
     $mes_fin = 7;
     $texto   = 'JULHO';
     $abrev   = 'M�s';

   } elseif ($tipo == 'AGO') {

     $mes_ini = 8;
     $mes_fin = 8;
     $texto   = 'AGOSTO';
     $abrev   = 'M�s';

   } elseif ($tipo == 'SET') {

     $mes_ini = 9;
     $mes_fin = 9;
     $texto   = 'SETEMBRO';
     $abrev   = 'M�s';

   } elseif ($tipo == 'OUT') {

     $mes_ini = 10;
     $mes_fin = 10;
     $texto  = 'OUTUBRO';
     $abrev  = 'M�s';

   } elseif ($tipo == 'NOV') {

     $mes_ini = 11;
     $mes_fin = 11;
     $texto  = 'NOVEMBRO';
     $abrev  = 'M�s';

   } elseif ($tipo == 'DEZ') {

     $mes_ini = 12;
     $mes_fin = 12;
     $texto  = 'DEZEMBRO';
     $abrev  = 'M�s';

   } else {
       echo "Datas inv�lidas";
       exit;
   }

   $data_ini = $anousu."-".$mes_ini."-01";
   $data_fin = $anousu."-".$mes_fin."-".ultimo_dia_mes($mes_fin, $anousu);
   $matriz[0] = $data_ini;
   $matriz[1] = $data_fin;
   $matriz['texto']=$texto;
   $matriz['periodo']=$abrev;

   return $matriz;

}
function datas_bimestre($bimestre, $ano) {
	if ($ano == "")
		$ano = db_getsession("DB_anousu");
	$meses = bimestre_meses($bimestre);
	$data_ini = $ano."-".$meses[0]."-01";
	$data_fin = $ano."-".$meses[1]."-".ultimo_dia_mes($meses[1], $ano);
	$matriz[0] = $data_ini;
	$matriz[1] = $data_fin;
	return $matriz;
}
// essa retorna o quadrimestre, por sorte fevereiro so entra no bimestral
function datas_quadrimestre($quadrimestre, $ano) {
	if ($ano == "")
		$ano = db_getsession("DB_anousu");
	if ($quadrimestre == 1) {
		$matriz[0] = $ano."-01-01";
		$matriz[1] = $ano."-04-".ultimo_dia_mes(4, $ano);
	} else
		if ($quadrimestre == 2) {
			$matriz[0] = $ano."-05-01";
			$matriz[1] = $ano."-08-".ultimo_dia_mes(8, $ano);
		} else { // terceiro quadrimestre
			$matriz[0] = $ano."-09-01";
			$matriz[1] = $ano."-12-31";
		}
	return $matriz;
}
/////////////////////////////////////////////////////////////////////////////////


function assinaturas($pdf,$classinatura,$tipo='LRF', $lVerificaQuebra = true, $lOutPut = true){
 //#10#// assinaturas dos relatorios da LRF
 //#10#// e de gest�o fiscal
 //#20#//
 //#20#// tipo = [LRF,GF,]
 //#20#// pdf = instancia da classe pdf
 //#20#// classinatura = instancia da classe classinatura
 //#20#// LRF = relatorios da LRF execu�o or�amentaria
 //#20#// GF  = relatorios da LRF get�o fiscal
 //#20#// BG  = relatorios da 4320 Balan�o geral
  $controle =  "______________________________"."\n"."Controle Interno";
  $sec      =  "______________________________"."\n"."Secretaria da Fazenda";
  $cont     =  "______________________________"."\n"."Contadoria";
  $pref     =  "______________________________"."\n"."Prefeito";

  $ass_pref     = $classinatura->assinatura(1000,$pref);
  $ass_sec      = $classinatura->assinatura(1002,$sec);
  $ass_cont     = $classinatura->assinatura(1005,$cont);
  $ass_controle = $classinatura->assinatura(1009,$controle);


  if ($tipo=='LRF' || $tipo =='BG'){
       if ($lVerificaQuebra && ($pdf->gety() > ($pdf->h - 30))){
           $pdf->addPage($pdf->CurOrientation);
	         $pdf->Ln(14);
       }
       $largura = ( $pdf->w ) / 3;

       $pos = $pdf->gety();
       $pdf->multicell($largura,3,$ass_pref,0,"C",0,0);

       // o rpps n�o tem a assinatura abaixo
       global $db21_idtribunal;
       db_sel_instit(db_getsession("DB_instit"));

       $pdf->setxy($largura*1,$pos);

       $pdf->multicell($largura,3,$ass_cont,0,"C",0,0);


       if ($db21_idtribunal==6  || $db21_idtribunal==7   ){
           //  n�o tem esse campo 6-RPPS(Autarquia) , 7-RPPS (Exceto Autarquia )
       } else {
           $pdf->setxy($largura*2,$pos);
           $pdf->multicell($largura,3,$ass_sec,0,"C",0,0);

       }


  } elseif ($tipo=='GF'){
       $largura = ( ($pdf->w)-15 ) / 4;

       $pos = $pdf->gety();
       $pdf->multicell($largura,3,$ass_pref,0,"C",0,0);

       $pdf->setxy($largura*1,$pos);
       $pdf->multicell($largura,3,$ass_sec,0,"C",0,0);

       $pdf->setxy($largura*2,$pos);
       $pdf->multicell($largura,3,$ass_cont,0,"C",0,0);

       $pdf->setxy($largura*3,$pos);
       $pdf->multicell($largura,3,$ass_controle,0,"C",0,0);

  }

  if ($lOutPut) {
    $pdf->Output();
  }
}

// Retorna periodo conforme mes
function db_retorna_periodo($mes = 1, $tipo = "B"){
  $periodo = "0";

  if ($tipo == "B") {  // retorna bimestre do mes
    switch ($mes) {
      case  1 :
      case  2 : $periodo = "1B";
                break;
      case  3 :
      case  4 : $periodo = "2B";
                break;
      case  5 :
      case  6 : $periodo = "3B";
                break;
      case  7 :
      case  8 : $periodo = "4B";
                break;
      case  9 :
      case 10 : $periodo = "5B";
                break;
      case 11 :
      case 12 : $periodo = "6B";
                break;
    }
  }

  if ($tipo == "Q") {  // retorna quadrimestre do mes
    switch ($mes) {
      case  1 :
      case  2 :
      case  3 :
      case  4 : $periodo = "1Q";
                break;
      case  5 :
      case  6 :
      case  7 :
      case  8 : $periodo = "2Q";
                break;
      case  9 :
      case 10 :
      case 11 :
      case 12 : $periodo = "3Q";
                break;
    }
  }

  if ($tipo == "S") {  // retorna semestre do mes
    switch ($mes) {
      case  1 :
      case  2 :
      case  3 :
      case  4 :
      case  5 :
      case  6 : $periodo = "1S";
                break;
      case  7 :
      case  8 :
      case  9 :
      case 10 :
      case 11 :
      case 12 : $periodo = "2S";
                break;
    }
  }

  return $periodo;
}


?>