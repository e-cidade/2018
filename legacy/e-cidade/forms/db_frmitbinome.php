<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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

$clitbinome->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("it01_guia");

require_once(modification("dbforms/db_classesgenericas.php"));

$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
if(isset($opcao) && $opcao == "alterar"){
  echo "<script>parent.iframe_transm.location.href='itb1_itbinome002.php?chavepesquisa=$it03_guia&chavepesquisa1=$it03_seq&db_opcao=2&db_botao=true'</script>";
}
if(isset($opcao) && $opcao == "excluir"){
  echo "<script>parent.iframe_transm.location.href='itb1_itbinome003.php?chavepesquisa=$it03_guia&chavepesquisa1=$it03_seq&db_opcao=3&db_botao=true'</script>";
}

$clitbinomecgm->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("it21_numcgm");
$clrotulo->label("z01_nome");

/**
 * Valida parametro CGM Obrigatorio - paritbi
 */
$lHabilitaEdicaoCGM = false;

$oParItbi     = db_utils::getDao('paritbi');
$sSqlParItbi  = $oParItbi->sql_query( DB_getsession('DB_anousu'), 'it24_cgmobrigatorio' );
$rsDAOParItbi = db_query($sSqlParItbi);
if( $rsDAOParItbi ){

  if( pg_num_rows($rsDAOParItbi) <> 0){

    if( db_utils::fieldsMemory($rsDAOParItbi, 0)->it24_cgmobrigatorio == 't' ){
      $lHabilitaEdicaoCGM = true;
    }
  }
}
?>
  <fieldset>
		<legend>Transmitentes</legend>

    <form name="form1" method="post" action="">

			<table>

        <tr>
          <td nowrap title="<?php echo $Tit03_guia; ?>">
            <?php
              db_ancora($Lit03_guia,"js_pesquisait03_guia(true);",3);
            ?>
          </td>
          <td colspan="3">
            <?php
              db_input('it03_guia',14,$Iit03_guia,true,'text',3," onchange='js_pesquisait03_guia(false);'");

              db_input('it03_seq',10,$Iit03_seq,true,'hidden',3,"");
              db_input('it03_seq',8, $Iit03_seq,true,'hidden',$db_opcao,"","it03_seq_old");
              if($db_opcao == 2){
                echo "<script>document.form1.it03_seq_old.value='$it03_seq'</script>";
              }
            ?>
          </td>
        </tr>

			  <tr>
			    <td nowrap title="<?php echo $Tit21_numcgm; ?>">
			       <?php
              $GLOBALS['Lit21_numcgm'] = 'CGM/Nome:';
		          db_ancora($Lit21_numcgm,"js_pesquisait21_numcgm(true);",$db_opcao);
			       ?>
			    </td>
			    <td nowrap colspan="3">
			      <?php
			        db_input('it21_numcgm',14,$Iit21_numcgm,true,'text',$db_opcao," onchange='js_pesquisait21_numcgm(false);'");
			        db_input('z01_nome',52,$Iz01_nome,true,'text',3,'');
			      ?>
			    </td>
			  </tr>

			  <tr>
			    <td nowrap><?php echo $Lit03_nome; ?></td>
          <td nowrap colspan="3">
		    	  <?php
			        db_input('it03_nome',70,$Iit03_nome,true,'text',$db_opcao,"");
			      ?>
			    </td>
			  </tr>

			  <tr>
			    <td nowrap title="<?php echo @$Tit03_cpfcnpj; ?>"><?php echo @$Lit03_cpfcnpj; ?></td>
			    <td>
			      <?php
			        db_input('it03_cpfcnpj',14,$Iit03_cpfcnpj,true,'text',$db_opcao,"  onBlur='js_verificaCGCCPF(this)' onKeyDown='return js_controla_tecla_enter(this,event);' onKeyUp='js_limpa(this)' ");
			      ?>
			      <script type="text/javascript">
			        function js_limpa(obj){
			          x = obj.value;
			          y = x.replace('.','');
			          y = y.replace('/','');
			          y = y.replace('-','');
			          document.form1.it03_cpfcnpj.value = y;
			        }
			      </script>

          </td>
          <td width="87px"><?php echo $Lit03_endereco; ?></td>
			    <td>
			      <?php
			        db_input('it03_endereco',39,$Iit03_endereco,true,'text',$db_opcao,"");
			      ?>
			    </td>
			  </tr>

			  <tr>
			    <td nowrap title="<?php echo $Tit03_numero; ?>"><?php echo $Lit03_numero; ?></td>
			    <td>
			      <?php
			        db_input('it03_numero',14,$Iit03_numero,true,'text',$db_opcao,"");
			      ?>
          </td>
          <td><?php echo $Lit03_bairro; ?></td>
			    <td>
			      <?php
			        db_input('it03_bairro',39,$Iit03_bairro,true,'text',$db_opcao,"");
			      ?>
			    </td>
			  </tr>

			  <tr>
			    <td nowrap title="<?php echo $Tit03_cxpostal; ?>"><?php echo $Lit03_cxpostal; ?></td>
			    <td>
			      <?php
			        db_input('it03_cxpostal',14,$Iit03_cxpostal,true,'text',$db_opcao,"");
			      ?>
          </td>
          <td><?php echo $Lit03_compl; ?></td>
	        <td>
			      <?php
			        db_input('it03_compl',39,$Iit03_compl,true,'text',$db_opcao,"");
			      ?>
			    </td>
			  </tr>

        <tr>
          <td nowrap title="<?php echo $Tit03_uf; ?>"><?php echo $Lit03_uf; ?></td>
          <td nowrap>
            <?php
              db_input('it03_uf',14,$Iit03_uf,true,'text',$db_opcao,"");
            ?>
          </td>
          <td nowrap title="<?php echo $Tit03_cep; ?>"><?php echo $Lit03_cep; ?></td>
          <td nowrap>
            <?php
              db_input('it03_cep',14,$Iit03_cep,true,'text',$db_opcao,"");
            ?>
          </td>
        </tr>

			  <tr>
			    <td nowrap title="<?php echo $Tit03_munic; ?>"><?php echo $Lit03_munic; ?></td>
			    <td colspan="3">
  		  	  <?php
	  	  	    db_input('it03_munic',70,$Iit03_munic,true,'text',$db_opcao,"");
		    	  ?>
		      </td>
		    </tr>

	  	  <tr>
		      <td nowrap title="<?php echo $Tit03_mail; ?>"><?php echo $Lit03_mail; ?></td>
			    <td nowrap colspan="3">
			      <?php
			        db_input('it03_mail',70,$Iit03_mail,true,'text',$db_opcao,"");
			      ?>
			    </td>
		    </tr>

        <tr>
          <td nowrap title="<?php echo $Tit03_sexo; ?>"><?php echo $Lit03_sexo; ?></td>
          <td colspan="3">
            <?php
              if( empty($aOptionsSexo) || ( $lHabilitaEdicaoCGM === false ) ){
                $aOptionsSexo = array('m'=>'Masculino','f'=>'Feminino');
              }
              db_select('it03_sexo',$aOptionsSexo,true,$db_opcao,"");
            ?>
          </td>
        </tr>

		    <tr>
		      <td nowrap title="<?php echo $Tit03_princ; ?>"><?php echo $Lit03_princ; ?></td>
			    <td colspan="3">
			      <?php
			        if (!isset($it03_guia) or trim($it03_guia)=='') {
			          $it03_guia = 'NULL';
			        }
			        $result = $clitbinome->sql_record($clitbinome->sql_query_file(null,"*",null," it03_guia = $it03_guia and it03_princ = 't' and upper(it03_tipo) = 'T'"));
			        if($clitbinome->numrows > 0 && $db_opcao == 1){
			          $x = array("f"=>"NÃO");
			        }else{
			          $x = array("t"=>"SIM","f"=>"NÃO");
			        }
			        db_select('it03_princ',$x,true,$db_opcao,"");
			      ?>
			    </td>
        </tr>

		    <tr>
		    	<td colspan="4" align="center">
		    	  <input name="bt_opcao" type="submit" id="bt_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>"<?=($db_botao==false?"disabled":"")?> onClick="return js_valida();" />
		    	  <input name="novo" type="button" id="novo" value="Novo" onclick="return js_novo();" />
			    </td>
        </tr>

        <tr>
          <td align="top" colspan="4">
            <?php
		    	    $chavepri = array("it03_guia"=>@$it03_guia,"it03_seq"=>@$it03_seq);
		    	    $sql      = $clitbinome->sql_query_file(null,"*",null," it03_guia = $it03_guia and upper(it03_tipo) = 'T'");
              $cliframe_alterar_excluir->chavepri      = $chavepri;
	            $cliframe_alterar_excluir->campos        = "it03_guia,it03_seq,it03_nome,it03_princ";
		    	    $cliframe_alterar_excluir->sql           = $sql;
		    	    $cliframe_alterar_excluir->legenda       = "Transmitentes";
		    	    $cliframe_alterar_excluir->msg_vazio     = "<font size='1'>Nenhum transmitente Cadastrado!</font>";
		    	    $cliframe_alterar_excluir->textocabec    = "darkblue";
		          $cliframe_alterar_excluir->textocorpo    = "black";
		          $cliframe_alterar_excluir->fundocabec    = "#aacccc";
		          $cliframe_alterar_excluir->fundocorpo    = "#ccddcc";
		          $cliframe_alterar_excluir->iframe_width  = "100%";
		          $cliframe_alterar_excluir->iframe_height = "170";
		          $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
		    	    $re = $clitbinome->sql_record($clitbinome->sql_query_file(null,"*",null," it03_guia = $it03_guia and upper(it03_tipo) = '".@$tiponome."'"));   //$clitbinome->sql_query("","","*",""," it03_guia = $it03_guia"));
		    	    if($clitbinome->numrows > 0) {
			          echo "<script>parent.document.formaba.constr.disabled = false</script>";
			        }
			      ?>
          </td>
        </tr>
      </table>
    </form>
  </fieldset>
<script type="text/javascript">

   function js_valida (){

    if( !isNumeric($F('it03_cpfcnpj')) && $F('it03_cpfcnpj') != '' ){

      alert('CNPJ/CPF deve ser preenchido somente com números.');
      return false;
    }

    if( !isNumeric($F('it03_numero')) && $F('it03_numero') != '' ){

      alert('Números deve ser preenchido somente com números.');
      return false;
    }

    if( !isNumeric($F('it03_cep')) && $F('it03_cep') != '' ){

      alert('CEP deve ser preenchido somente com números.');
      return false;
    }

    return true;
  }

  function js_novo(){

    document.form1.reset();
    parent.iframe_transm.location.href='itb1_itbinome001.php?it03_guia='+document.form1.it03_guia.value;
  }

  var aCampos = [ 'it03_nome',     'it03_cpfcnpj',
                  'it03_endereco', 'it03_numero',
                  'it03_bairro',   'it03_cxpostal',
                  'it03_compl',    'it03_munic',
                  'it03_mail',     'it03_uf',
                  'it03_cep',      'it03_sexo' ];

<?php
  if( $lHabilitaEdicaoCGM === true ){
?>
  (function(){

    for(var iCampo = 0; iCampo < aCampos.length; iCampo++ ){

      var oCampo = $( aCampos[iCampo] );
      oCampo.readOnly              = true;
      oCampo.style.backgroundColor = '#DEB887';
    }
  })();
<?php
  }
?>

function js_pesquisait03_guia(mostra){

  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo.iframe_compnome','db_iframe_itbi','func_itbi.php?funcao_js=parent.js_mostraitbi1|it01_guia|it01_guia','Pesquisa',true);
  }else{

     if(document.form1.it03_guia.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo.iframe_compnome','db_iframe_itbi','func_itbi.php?pesquisa_chave='+document.form1.it03_guia.value+'&funcao_js=parent.js_mostraitbi','Pesquisa',false);
     }else{
        document.form1.it01_guia.value = '';
     }
  }
}

function js_mostraitbi(chave,erro){

  document.form1.it01_guia.value = chave;
  if(erro==true){

    document.form1.it03_guia.focus();
    document.form1.it03_guia.value = '';
  }
}

function js_mostraitbi1(chave1,chave2){

  document.form1.it03_guia.value = chave1;
  document.form1.it01_guia.value = chave2;
  db_iframe_itbi.hide();
}

function js_mostraitbinomecgm(chave,erro){

  document.form1.it21_numcgm.value = chave;
  if(erro==true){

    document.form1.it21_itbinome.focus();
    document.form1.it21_itbinome.value = '';
  }
}

function js_mostraitbinomecgm1(chave1,chave2) {

  document.form1.it21_itbinome.value = chave1;
  document.form1.it21_numcgm.value = chave2;
  db_iframe_itbinomecgm.hide();
}
function js_pesquisait21_numcgm(mostra){

  if(mostra==true) {
    js_OpenJanelaIframe('','db_iframe_cgm','func_nome.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome&testanome=1','Pesquisa',true);
  }else{

    if(document.form1.it21_numcgm.value != '') {
      js_OpenJanelaIframe('','db_iframe_cgm','func_nome.php?pesquisa_chave='+document.form1.it21_numcgm.value+'&funcao_js=parent.js_mostracgm&testanome=1','Pesquisa',false);
    } else {
      document.form1.z01_nome.value = '';
    }
  }
}

function js_mostracgm(erro,chave) {

  document.form1.z01_nome.value = chave;
  if(erro==true) {

    document.form1.it21_numcgm.focus();
    document.form1.it21_numcgm.value = '';
  }

  parent.iframe_transm.location.href='itb1_itbinome001.php?mostraitbinomecgm=t&it21_numcgm='+document.form1.it21_numcgm.value+'&it03_guia='+document.form1.it03_guia.value;
}

function js_mostracgm1(chave1,chave2) {

  document.form1.it21_numcgm.value = chave1;
  document.form1.z01_nome.value    = chave2;
  db_iframe_cgm.hide();
  parent.iframe_transm.location.href='itb1_itbinome001.php?mostraitbinomecgm=t&it21_numcgm='+chave1+'&z01_nome='+chave2+'&it03_guia='+document.form1.it03_guia.value;
}

function js_pesquisa() {
  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_itbinome','func_itbinome.php?funcao_js=parent.js_preenchepesquisa|it03_seq|it03_guia','Pesquisa',true);
}

function js_preenchepesquisa(chave,chave1) {

  db_iframe_itbinome.hide();
  <?php
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave+'&chavepesquisa1='+chave1";
  }
  ?>
}
</script>