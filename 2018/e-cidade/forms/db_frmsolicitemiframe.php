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
?>
<form name="form1">
  <?
  $cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
  $clsolicitem->rotulo->label();
  $clpcdotac->rotulo->label();
  $clrotulo = new rotulocampo;
  $clrotulo->label("o56_elemento");
  $clrotulo->label("o56_descr");
  $clrotulo->label("descrdepto");
  $clrotulo->label("pc13_coddot");
  $clrotulo->label("pc11_numero");
  $setanovaaltura = "120";
  $desabilitabotao=null;
  $result_gerareserva = $clpcparam->sql_record($clpcparam->sql_query_file(db_getsession("DB_instit"),"pc30_gerareserva,pc30_passadepart,pc30_digval,pc30_ultdotac"));
  if ($clpcparam->numrows > 0){
    db_fieldsmemory($result_gerareserva,0);
    if ($pc30_ultdotac == "t"){
      if (!isset($pc13_coddot) && trim(@$pc13_coddot) == ""){
        if (db_getsession("DB_coddot",false) != null){
          $res_pcdotac = @db_query("select pc13_codigo as coddotseq, pc13_coddot as coddot, pc13_anousu, pc13_depto, descrdepto
                                      from pcdotac
                                 inner join db_depart on db_depart.coddepto = pcdotac.pc13_depto
                    where pc13_anousu = ".db_getsession("DB_anousu")." and
                          pc13_coddot = ".db_getsession("DB_coddot")."
                    order by pc13_codigo desc limit 1");
          if (@pg_numrows($res_pcdotac) > 0){
            db_fieldsmemory($res_pcdotac,0);
            $pc13_coddot = $coddot;

          }
        }
      }
    }

  }

  ?>
  <script>
    function js_ver(){
      campo = "";
      x = document.form1;
      variavelusada = "<?=$tquant?>";
      <?if($db_opcao == 1 && $errado!="true"){?>
      val = new Number(parent.document.form1.quant_rest.value);
      if(val==0 || val==''){
      }else{
        document.form1.incluir.disabled=false;
        if(variavelusada!="true"){
          document.form1.pc13_quant.readOnly=false;
          document.form1.pc13_quant.style.backgroundColor="";
        }
      }
      <?}else if($db_opcao==3){?>
      if(document.form1.pc13_coddot.value==''){
        document.form1.excluir.disabled=true;
      }else{
        document.form1.excluir.disabled=false;
      }
      <?}?>
      //alert("document.form1."+campo+".disabled=true \n document.form1."+campo+".disabled=false");
    }
    function js_pesquisapc13_coddot(mostra){
      qry= 'obriga_depto=sim';
      if (document.form1.o56_elemento.value != ""){
        qry+= '&elemento='+document.form1.o56_elemento.value;
      }
      <?if(@$pc30_passadepart=='t'){?>
      qry+= '&departamento=<?=(db_getsession("DB_coddepto"))?>';
      <?}?>
      qry+= '&retornadepart=true';
      qry+= '&pactoplano=<?=$iPactoPlano?>';
      if(mostra==true){
        qry+= '&funcao_js=parent.lanc_dotac.js_mostraorcdotacao1|o58_coddot';
        js_OpenJanelaIframe('CurrentWindow.corpo.iframe_solicitem','db_iframe_orcdotacao',
          'func_permorcdotacao.php?'+qry,'Pesquisa',true,'0');
      }else{
        qry+= '&pesquisa_chave='+document.form1.pc13_coddot.value;
        js_OpenJanelaIframe('CurrentWindow.corpo.iframe_solicitem',
          'db_iframe_orcdotacao',
          'func_permorcdotacao.php?'+qry+'&funcao_js=parent.js_mostraorcdotacao','Pesquisa',false);
      }
    }
  </script>
  <?php db_input('pc13_codigo',10,$Ipc13_codigo,true,'hidden',3); ?>
  <?php $pc13_anousu = db_getsession("DB_anousu"); ?>
  <?php db_input('pc13_anousu',4,$Ipc13_anousu,true,'hidden',3); ?>
  <?php db_input('pc11_numero',4,$Ipc11_numero,true,'hidden',3); ?>
  <?php db_input('tquant',8,0,true,'hidden',3); ?>
  <?php db_input('errado',8,0,true,'hidden',3); ?>
  <?php db_input("itens",50,0,true,"hidden",3); ?>
  <?php db_input("dotac_itens",50,0,true,"hidden",3); ?>
  <?php
  // Alteração feita para processo de compra e licitacao
  if (isset($param) && trim($param) != ""){
    db_input("param",10,"",false,"hidden",3);
    db_input("codproc",10,"",false,"hidden",3);
    db_input("codliclicita",10,"",false,"hidden",3);

    $parametro = "&param=".$param;
    if (isset($codproc) && trim($codproc) != ""){
      $parametro .= "&codproc=".$codproc;
    }

    if (isset($codliclicita) && trim($codliclicita) != ""){
      $parametro .= "&codliclicita=".$codliclicita;
    }
  } else {
    $parametro = "";
  }
  ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  ?>
  <center>
    <table border="0">
      <tr>
        <td colspan="8">
          <?
          ?>
  </center>
  </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tpc13_coddot?>">
      <?

      if($pc13_codigo==null ){
        $pc13_codigo=0;
      }

      $ancora_dotac=$clsolicitem->sql_record(
        $clsolicitem->sql_query_ancoradotorc ($pc13_codigo,
                                              "pc13_codigo as codigo_dotac",
                                              null,"pc11_codigo = $pc13_codigo"));

      if($clsolicitem->numrows>0) {
        db_fieldsmemory($ancora_dotac,0);

        if (isset($codigo_dotac)){
          //if ($codigo_dotac==null){
          db_ancora(@$Lpc13_coddot,"js_pesquisapc13_coddot(true);",($db_opcao!=1?"3":"1"),'','pc13_coddot');
        }else{
          echo "$Lpc13_coddot";
          $desabilitabotao=true;
        }
      }else{

        echo "$Lpc13_coddot";
        $desabilitabotao=true;

      }

      ?>
    </td>
    <td nowrap>
      <?
      db_input('pc13_coddot',8,$Ipc13_coddot,true,'text',3,'','pc13_coddot');
      db_input('pc13_sequencial',8,0,true,'hidden',3);
      ?>
    </td>
    <td nowrap title="<?=@$Tpc13_quant?>">
      <?=@$Lpc13_quant?>
    </td>
    <td>
      <?
      db_input('pc13_quant',10,$Ipc13_quant,true,'text',$db_opcao,"onchange='js_verquant(this.value);js_preenche_valor();'");
      if(isset($tquant)){
        if($tquant=='true'){
          $result_val_rest = $clpcdotac->sql_record($clpcdotac->sql_query_depart(null,null,null,"pc11_vlrun as pc11_valormaximo,sum(pc13_valor) as pc13_valorincluso",""," pc13_codigo = ".@$pc13_codigo." group by pc11_vlrun,pc13_codigo "));
          if($clpcdotac->numrows>0){
            db_fieldsmemory($result_val_rest,0);

            if($pc13_valorincluso==0 || isset($opcao) && ($opcao=="alterar" || $opcao=="excluir")){
              $pc13_valor_limite = $pc11_valormaximo;
            }else{
              $pc13_valor_limite = $pc11_valormaximo-$pc13_valorincluso;
            }
          }
          $desabilitarcampos = true;
          $jscriptvalor = "onchange='js_vervalor();'";
          echo "
	  <script>
	    var opcao = document.createElement('input');
	    opcao.setAttribute('type','hidden');
	    opcao.setAttribute('name','pc13_valorlimite');";
          if(isset($pc13_valor_limite)){
            echo"
	    opcao.setAttribute('value','$pc13_valor_limite');";
          }else{
            echo"
	    jspc13_valor_limite = parent.document.form1.pc11_vlrun.value;
	    opcao.setAttribute('value',jspc13_valor_limite);";
          }
          echo"
	    document.form1.appendChild(opcao);
	    function js_vervalor(){
	      valinf = new Number(document.form1.pc13_valor.value);
	      valmax = new Number(document.form1.pc13_valorlimite.value);
	      if(valinf>valmax){
		alert('ATENÇÃO:\\n\\n Informe um valor menor ou igual a R$ '+document.form1.pc13_valorlimite.value+' (Valor restante, não \\ninformado em dotações).\\n\\nAdministrador:');
                document.form1.pc13_valor.value = '';
                document.form1.pc13_valor.focus();
              }
	      if(valinf<=0){
		alert('ATENÇÃO:\\n\\n Informe um valor válido.\\n\\nAdministrador:');
                document.form1.pc13_valor.value = '';
                document.form1.pc13_valor.focus();
              }
	    }
	    document.form1.pc13_quant.readOnly=true;
	  </script>
	  ";
        }else{
          $desabilitarcampos = false;
          $jscriptvalor = "";
        }
      }else{
        echo "<script>document.form1.pc13_quant.readOnly=false;</script>";
      }
      $result_quant_rest = $clpcdotac->sql_record($clpcdotac->sql_query_depart(null,
                                                                               null,
                                                                               null,
                                                                               "pc11_quant as pc11_quant_sql,
                                                    pc13_codigo as pc13_codigo_sql,
                                                    sum(pc13_quant) as pc13_quant_sql",
                                                                               "",
                                                                               " pc13_codigo = ".@$pc13_codigo." group by pc11_quant,pc13_codigo "));
      $valmax = 0;
      $vallin = 0;
      if($clpcdotac->numrows > 0){
        db_fieldsmemory($result_quant_rest,0);
        $valmax = $pc11_quant_sql;
        $vallin = $pc13_quant_sql;
        if($desabilitarcampos!=true){
          $valres = $valmax - $vallin;
        }else{
          $valres = $pc11_quant_sql;
        }
        echo "
        <script>
          valmax = '".@$valmax."';
          pos = valmax.indexOf('.');
          if(pos!=-1){
            tam = valmax.length;
            qts = valmax.slice((pos+1),tam);
            dec = qts.length;
          }else{
            dec = 2;
          }
          recebe = new Number(".@$valres.");
          recebe = new Number(recebe.toFixed(dec));

          valres = recebe;

          vallin = '".@$vallin."';
          pos = vallin.indexOf('.');
          if(pos!=-1){
            tam = vallin.length;
            qts = vallin.slice((pos+1),tam);
            dec = qts.length;
	    if(dec==1){
	      dec = 2;
	    }
          }else{
            dec = 2;
          }
          quant = new Number(".@$pc13_quant.");
          quant = new Number(quant.toFixed(dec));";
        if(isset($opcao) && ($opcao=="excluir" || $opcao=="alterar") && $desabilitarcampos!=true){
          echo "
          recebe = new Number(quant + valres);";
        }else{
          echo "
          recebe = new Number(valres);";
        }
        echo "
          if(recebe == 0 && parent.document.form1.pc11_quant.value==''){
            recebe = '';
          }

	        document.form1.pc13_quant.value = recebe;


          if (parent.document.form1.pc01_servico.value != 'f') {
	          if (parent.document.form1.pc11_servicoquantidade.value == 'false' ) {
	            parent.document.form1.quant_rest.value = 1;
	          } else {
              parent.document.form1.quant_rest.value = recebe;
	          }
          } else {
            parent.document.form1.quant_rest.value = recebe;
          }";

        echo "
        </script>
        ";
      }else{
        echo "<script>parent.document.form1.quant_rest.value = parent.document.form1.pc11_quant.value;</script>";
        echo "<script>document.form1.pc13_quant.value = parent.document.form1.quant_rest.value</script>";
      }
      ?>
    </td>
    <td nowrap title="<?=@$Tpc13_valor?>">
      <?=@$Lpc13_valor?>
    </td>
    <td nowrap>
      <?
      $pc11_vlrun = 0;
      db_input('pc13_valor', 10, $Ipc13_valor, true, 'text', 1, "$jscriptvalor");
      if(isset($pc13_codigo) && $pc13_codigo!=""){
        $pc13_valor = 0;
        $result_vlrun = $clsolicitem->sql_record($clsolicitem->sql_query_file($pc13_codigo,"pc11_vlrun"));
        if($clsolicitem->numrows>0){
          db_fieldsmemory($result_vlrun,0);
        }
      }
      if($desabilitarcampos==true){
        $jdescampos = "true";
      }else{
        $jdescampos = "false";
      }
      echo "
      <script>
        if(document.form1.pc13_quant.value==''){
          document.form1.pc13_quant.value = parent.document.form1.quant_rest.value;
	}
        function js_preenche_valor(){
    var quant_atual   = '';
        quant_atual   = document.form1.pc13_quant.value;
	  desabilitarcampos = $jdescampos;
	  opcao = '';
	  if(desabilitarcampos==true){
	  ";
      if(isset($pc13_valor_limite) && $pc13_valor_limite!=""){
        echo"
	    valorlimite = '$pc13_valor_limite';";
      }else{
        echo"
	    valorlimite = '$pc11_vlrun'";
      }
      echo"
	    opcao = '".@$opcao."';
	  }
	  valor = 0;
	  ";
      if(isset($pc11_vlrun) && $pc11_vlrun!=""){
        echo "valor = '$pc11_vlrun';";
      }else{
        echo "if(parent.document.form1.pc11_vlrun.value!=''){
                  valor = parent.document.form1.pc11_vlrun.value;
                }";
      }
      echo "
	  if(valor!=''&&quant_atual!=''){
	    pos = valor.indexOf('.');
	    if(pos!=-1){
	      tam = valor.length;
	      qts = valor.slice((pos+1),tam);
	      dec = qts.length;
	      if(dec==1){
		dec   = 2;
	      }
	    }else{
	      dec = 2;
	    }

	    valor = new Number(valor);
	    quant = new Number(document.form1.pc13_quant.value);
	    if(desabilitarcampos==false){
	      x = Number(valor*quant);

	    }else{
	      if(opcao=='alterar' || opcao=='excluir'){
		x = Number(valor*quant);
	      }else{
		x = Number(valorlimite);
		if(document.form1.pc13_valorlimite.value==0){
		  x = Number(0);
		}
	      }
	    }
	    ";

      if ($db_opcao == 1){
        echo "document.form1.pc13_valor.value = x.toFixed(dec);";
      }

      if($pc30_digval=="t"){
        echo "parent.document.form1.pc11_vlrun.value = valor.toFixed(dec);";
      }else{
        //echo "parent.document.form1.pc11_vlrun.value = 0;";
      }
      echo "
	  }
        }
	if(document.form1.pc13_quant.value!=''){
	  js_preenche_valor();
	}
      </script>
      ";
      ?>
      <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?>>
      <?
      if($db_botao!=false && $db_opcao!=1){
        echo "<input name='novo' type='button' id='novo' value='Novo' onclick='parent.document.location.href=\"com1_solicitem001.php?pc11_codigo=$pc13_codigo&pc11_numero=$pc11_numero&opcao=alterar$parametro\"'>";
      }

      ?>
    </td>
  </tr>
  <tr>
    <?
    if (isset($pc13_coddot)) {
      $rsDotacao = $clorcdotacao->sql_record($clorcdotacao->sql_query(db_getsession("DB_anousu"), $pc13_coddot,"o15_tipo"));
      if ($clorcdotacao->numrows > 0 ) {

        echo "<td><b>Contrapartida:</b></td><td colspan=5>";
        $oDotacao = db_utils::fieldsMemory($rsDotacao, 0);
        if ($oDotacao->o15_tipo == 1) {
          /*
           * Buscamos as contrapartidas da dotacao
           */
          $oDaoDotacaocontr = db_utils::getDao("orcdotacaocontr");
          $oDaoTipoRec      = db_utils::getDao("orctiporec");

          /*
           * Procuramos contrapartidas cadastradas que estão ativas para a dotacao, caso nao encontramos nenhuma,
           * trazemos todos os recursos cadastrados.
           */
          $rsContrapartidas = $oDaoDotacaocontr->sql_record($oDaoDotacaocontr->sql_query_convenios (
            $pc13_coddot, db_getsession("DB_anousu"),
            date("Y-m-d",db_getsession("DB_datausu")),
            null,"o15_codigo,o15_descr")
          );

          $iNumRows         = $oDaoDotacaocontr->numrows;
          if ($oDaoDotacaocontr->numrows == 0) {

            $rsContrapartidas = $oDaoTipoRec->sql_record($oDaoTipoRec->sql_query_convenios(
              date("Y-m-d",db_getsession("DB_datausu")),
              null,
              "o15_codigo,o15_descr")
            );
            $iNumRows         = $oDaoTipoRec->numrows;

          }
          if ($rsContrapartidas){
            if (!isset($pc19_orctiporec) || $pc19_orctiporec == 0) {
              $pc19_orctiporec = $iRecursoPlano;
            }
            db_selectrecord("pc19_orctiporec",$rsContrapartidas,true,$db_opcao,"", "", "","0-Selecione");
          }
        }
      }
      echo "</td>";
    }
    ?>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tpc13_depto?>">
      <?
      db_ancora(@$Lpc13_depto,"",3);
      ?>
    </td>
    <td nowrap colspan="2">
      <?
      db_input('pc13_depto',8,$Ipc13_depto,true,'text',3);
      ?>
      <?
      db_input('descrdepto',30,$Idescrdepto,true,'text',3,'');
      ?>
    </td>
    <?
    if(isset($pc13_codigo)){
      $where_elemento = "";
      if(isset($pc13_coddot)){
        $result_pcdotacele = $clpcdotac->sql_record($clpcdotac->sql_query_file($pc13_codigo,null,$pc13_coddot," pc13_codele "));
        if($clpcdotac->numrows>0){
          db_fieldsmemory($result_pcdotacele,0);
        }
      }
      if(!isset($pc13_codele) || (isset($pc13_codele) && trim($pc13_codele)=="")){
        $result_elemento = $clsolicitemele->sql_record($clsolicitemele->sql_query_file($pc13_codigo,null,"pc18_solicitem,pc18_codele"));
        if($clsolicitemele->numrows==0){
          if(isset($o56_elemento) && $o56_elemento!=""){
            $o56_elemento_ant = $o56_elemento;
          }
          $virgula_where  = "";
          if(isset($pc13_codigo)){
            $result_solicitempcmater = $clsolicitempcmater->sql_record($clsolicitempcmater->sql_query_file(null,null," distinct pc16_codmater","","pc16_solicitem=".$pc13_codigo));
            $numrows_solicitempcmater= $clsolicitempcmater->numrows;
            for($i=0;$i<$numrows_solicitempcmater;$i++){
              db_fieldsmemory($result_solicitempcmater,$i);
              $where_elemento = $virgula_where.$pc16_codmater;
              $virgula_where  = ",";
            }
            $and_elemento = "";
            if(trim($where_elemento)!=""){
              $and_elemento = " and ";
              $where_elemento = " pc01_codmater in ($where_elemento) ";
            }
            $where_elemento.= $and_elemento." substr(o56_elemento,8,6)::int > 0 and o56_anousu = ".db_getsession("DB_anousu");
          }
        }else{
          db_fieldsmemory($result_elemento,0);
        }
      }else{
        $result_codele = $clorcelemento->sql_record($clorcelemento->sql_query_file(null,null,"o56_elemento as o56_elemento_ant,substr(o56_descr,1,40) as o56_descr_ant",""," o56_anousu = ".db_getsession("DB_anousu")." and  o56_codele=$pc13_codele"));
        if($clorcelemento->numrows>0){
          db_fieldsmemory($result_codele,0);
        }
      }
      if(!isset($pc18_solicitem)){
        // echo ($clpcmaterele->sql_query(null,null,"distinct o56_elemento,substr(o56_descr,1,40) as o56_descr,o56_codele","o56_descr",$where_elemento));
        $sql_record = $clpcmaterele->sql_record($clpcmaterele->sql_query(null,null,"distinct o56_elemento,substr(o56_descr,1,40) as o56_descr,o56_codele","o56_descr",$where_elemento));
        $numrows_pcmatereleteste = $clpcmaterele->numrows;
        $dad_select = array();
        for($contamaterele=0;$contamaterele<$numrows_pcmatereleteste;$contamaterele++){
          db_fieldsmemory($sql_record,$contamaterele);
          $dad_select["$o56_elemento"] = $o56_elemento." - ".$o56_descr;
          if($i=0 && !isset($o56_elemento_ant) || (isset($o56_elemento_ant) && trim($o56_elemento_ant)=="")){
            $o56_elemento_ant = $o56_elemento;
          }
        }
        if(isset($o56_elemento_ant)){
          $o56_elemento = "$o56_elemento_ant";
        }
      }

      if (strlen($where_elemento) == 0){
        $numrows_materele = 0;
      }else {
        $numrows_materele = $clpcmaterele->numrows;
      }
      if($numrows_materele>0){
        ?>
        <td nowrap title="<?=@$To56_elemento?>">
          <?
          if(!isset($opcao) || (isset($opcao) && $opcao!="excluir" && $opcao!="alterar" && $opcao != "")){
            echo "<strong>";
            db_ancora("Sub. ele.:","",3);
            echo "</strong>";
          }else{
            db_ancora($Lo56_elemento,"",3);
          }
          ?>
        </td>
        <td nowrap colspan="2">
          <?
          if(!isset($opcao) || (isset($opcao) && $opcao!="excluir" && $opcao!="alterar" && $opcao != "")){
            db_select("o56_elemento",$dad_select,true,2);
          }else{
            $o56_descr = $o56_descr_ant;
            db_input('o56_descr',40,$Io56_descr,true,'text',3);
            db_input('o56_elemento',10,$Io56_elemento,true,'hidden',3);
          }
          ?>
        </td>
        <?
      }else{
        if (isset($pc18_codele)){
          $result_elemen = $clorcelemento->sql_record($clorcelemento->sql_query_file(null,null,"o56_elemento",""," o56_anousu = ".db_getsession("DB_anousu")." and o56_codele=".@$pc18_codele));
          $numrows_materele = $clorcelemento->numrows;
          if($clorcelemento->numrows>0){
            db_fieldsmemory($result_elemen,0);
          }
        }
        ?>
        <td nowrap>
          <?
          //db_ancora($Lo56_elemento,"",3);
          ?>
        </td>
        <td nowrap colspan="2">
          <?
          db_input('o56_elemento',15,$Io56_elemento,true,'hidden',3);
          ?>
        </td>
        <?
      }

    }

    ?>
  </tr>

  <?
  if($pc30_gerareserva=='t'){
    if (isset($atual)&&$atual==""&&isset($pc13_coddot)&&trim($pc13_coddot)!=""){
      $result = db_dotacaosaldo(8, 2, 2, "true", "o58_coddot=$pc13_coddot", db_getsession("DB_anousu"));
      db_fieldsmemory($result, 0, true);
      $tot = ((0 + $atual_menos_reservado) - (0 + $pc13_valor));
      if(isset($o80_valor)){
        $tot += $o80_valor;
        $atual_menos_reservado += $o80_valor;
      }

    }
    ?>
    <tr>
      <td nowrap><strong>Saldo da dotação: </strong></td>
      <td nowrap>
        <?
        db_input('atual',10,0,true,'text',3)
        ?>
      </td>
      <td nowrap><strong>Saldo reservado: </strong></td>
      <td nowrap>
        <?
        db_input('reservado',10,0,true,'text',3)
        ?>
      </td>
      <td nowrap><strong>Valor disponível: </strong></td>
      <td nowrap>
        <?
        db_input('atual_menos_reservado',10,0,true,'text',3);
        ?>
        <?
        if($db_opcao!=1 && isset($pc13_coddot) && isset($pc13_codigo) && isset($pc13_sequencial)){
          $result_pesq_pcdotac = $clorcreservasol->sql_record($clorcreservasol->sql_query_orcreserva(null,null,"o82_codres",
                                                                                                     "",
                                                                                                     "o82_pcdotac={$pc13_sequencial}
	                                                       and o80_coddot=$pc13_coddot and o80_anousu=$pc13_anousu"));
          if($clorcreservasol->numrows>0){
            echo "<input type='checkbox' name='nreserva' value='nreserva' checked onChange='js_desreserva(this,$pc13_codigo,$pc13_coddot);'>";
            echo "<strong>Saldo reservado</strong>";
          }else{
            echo "<input type='checkbox' name='nreserva' value='nreserva'>";
            echo "<strong>Saldo não reservado</strong>";
          }
        }else if($db_opcao==1){
          if (isset($pc13_coddot)&&trim($pc13_coddot)!=""){
            $texto = "<strong>Saldo a reservar</strong>";
          } else {
            $texto = "";
          }
          echo "<input type='checkbox' name='nreserva' value='nreserva' checked>";
          echo $texto;
        }

        ?>
      </td>
    </tr>
    <?
  }else{
    $setanovaaltura = "140";
  }
  ?>

  </table>
  <center>
    <table width="100%" height="100%" border="0">
      <tr>
        <td width="100%" colspan="4" height="100%">
          <center>
            <table border = "0" width="100%" height="100%">
              <tr align="center">
                <td align="center" width="100%" height="100%">
                  <?
                  $where_coddot = "";
                  if(isset($pc13_coddot) && $pc13_coddot != "" && !isset($incluir) && !isset($alterar) && !isset($excluir)){
                    //$where_coddot = " and pc13_coddot<>$pc13_coddot";
                  }
                  $chavepri= array("pc13_sequencial"=>@$pc13_sequencial);
                  $cliframe_alterar_excluir->chavepri= $chavepri;
                  $cliframe_alterar_excluir->sql     = $clpcdotac->sql_query_descrdot(null,null,null,"pc13_sequencial, pc13_codigo,pc13_coddot,pc19_orctiporec,o56_descr,pc13_anousu,pc13_quant,pc13_valor","pc13_codigo"," pc13_codigo =".@$pc13_codigo." and pc13_anousu=".@$pc13_anousu);
                  $cliframe_alterar_excluir->campos  = "pc13_codigo,pc13_coddot,pc19_orctiporec, o56_descr,pc13_anousu,pc13_quant,pc13_valor";
                  $cliframe_alterar_excluir->legenda = "DOTAÇÕES LANÇADAS";
                  $cliframe_alterar_excluir->strFormatar = '';
                  $cliframe_alterar_excluir->iframe_height = $setanovaaltura;
                  $cliframe_alterar_excluir->iframe_width  = "100%";
                  $val = 1;
                  if($db_opcao==3 && $db_botao==false){
                    $val = 4;
                  }
                  if(isset($pc13_coddot) && trim($pc13_coddot) != ""){
                    $cliframe_alterar_excluir->msg_vazio  = ($db_opcao==1?"Inclusão":($db_opcao==2||$db_opcao==22?"Alteração":"Exclusão"))." da dotação $pc13_coddot";
                  }elseif(isset($pc13_codigo) && trim($pc13_codigo) != ""){
                    $cliframe_alterar_excluir->msg_vazio  = "Nenhuma dotação encontrada para este item";
                  }else{
                    $cliframe_alterar_excluir->msg_vazio  = "Cadastre o item e após, suas dotações";
                  }
                  $cliframe_alterar_excluir->opcoes  = $val;
                  $cliframe_alterar_excluir->fieldset  = false;
                  $cliframe_alterar_excluir->iframe_alterar_excluir(1);//$db_opcao;
                  ?>
                </td>
              </tr>
            </table>
          </center>
        </td>
      </tr>
    </table>
  </center>
  <script>

    var oGet = js_urlToObject();
    if (oGet && oGet.db_opcion == 'alterar') {

      $('pc13_coddot').value = '';
      $('db_opcao').disabled = true;
    }

    function js_desreserva(nreserva,item,dotac_item){
      var frm          = document.form1;
      var retira       = new String(frm.itens.value);
      var retira_dotac = new String(frm.dotac_itens.value);
      var achou        = false;
      var sustenido    = "";
      if (nreserva.checked == false){
        if (confirm("Tem certeza da exclusão desta reserva?")){
          // Adiciona verificando se jah nao esta incluido como item a ser excluido a reserva
          if (retira.length > 0){
            var str  = retira.split("#");

            for(var i = 0; i < str.length; i++){
              if (str[i] == item){
                achou = true;
                break;
              }
            }
          }

          if (achou == false){
            frm.itens.value       += item       + "#";
            frm.dotac_itens.value += dotac_item + "#";
          }
        } else {
          // Remarca e retira da lista de itens a serem excluidas as reservas
          nreserva.checked = true;

          if (retira.length > 0){
            var str        = retira.split("#");
            var str_dotac  = retira_dotac.split("#");
            var fica       = new String("");
            var fica_dotac = new String("");

            for(var i = 0; i < str.length; i++){
              if (str[i] != item){
                fica       += sustenido+str[i];
                fica_dotac += sustenido+str_dotac[i];
                sustenido   = "#";
              }
            }

            if (fica.length > 0){
              if (fica != "#"){
                frm.itens.value       = fica;
                frm.dotac_itens.value = fica_dotac;
              } else {
                frm.itens.value       = "";
                frm.dotac_itens.value = "";
              }
            }
          }
        }
      } else {
        // Caso, tenha marcado novamente verifica se item nao esta na lista e estando somente retira para nao excluir reserva
        if (retira.length > 0){
          var str        = retira.split("#");
          var str_dotac  = retira_dotac.split("#");
          var fica       = new String("");
          var fica_dotac = new String("");

          for(var i = 0; i < str.length; i++){
            if (str[i] != item){
              fica       += sustenido+str[i];
              fica_dotac += sustenido+str_dotac[i];
              sustenido   = "#";
            }
          }

          if (fica.length > 0){
            if (fica != "#"){
              frm.itens.value       = fica;
              frm.dotac_itens.value = fica_dotac;
            } else {
              frm.itens.value       = "";
              frm.dotac_itens.value = "";
            }
          }
        }
      }

//  alert(frm.itens.value);
//  alert(frm.dotac_itens.value);

      return true;
    }
    function js_dot(){
      var opcao = document.createElement("input");
      opcao.setAttribute("type","hidden");
      opcao.setAttribute("name","pesquisa_dot");
      opcao.setAttribute("value","true");
      document.form1.appendChild(opcao);
      document.form1.submit();
    }
    /*
     function js_pesquisapc13_coddot(mostra){
     qry= 'obriga_depto=sim';
    <?if($numrows_materele>0){?>
     qry+= '&elemento='+document.form1.o56_elemento.value;
    <?}?>
  <?if($pc30_passadepart=='t'){?>
     qry+= '&departamento=<?=(db_getsession("DB_coddepto"))?>';
    <?}?>
     qry+= '&retornadepart=true';
     if(mostra==true){
     qry+= '&funcao_js=parent.lanc_dotac.js_mostraorcdotacao1|o58_coddot';
     js_OpenJanelaIframe('CurrentWindow.corpo.iframe_solicitem','db_iframe_orcdotacao','func_permorcdotacao.php?'+qry,'Pesquisa',true,'0');
     }else{
     qry+= '&pesquisa_chave='+document.form1.pc13_coddot.value;
     js_OpenJanelaIframe('CurrentWindow.corpo.iframe_solicitem','db_iframe_orcdotacao','func_permorcdotacao.php?'+qry+'&funcao_js=parent.js_mostraorcdotacao','Pesquisa',false);
     }
     }
     */
    function js_mostraorcdotacao1(chave1,chave2){
      document.form1.pc13_coddot.value = chave1;
      document.form1.pc13_depto.value = chave2;
      js_dot();
      (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_solicitem.db_iframe_orcdotacao.hide();
    }

    function js_mostraorcdotacao(chave1){
      document.form1.pc13_coddot.value = chave1;
      db_iframe_orcdotacao.hide();
    }
    function js_pesquisapc13_depto(){
      if(document.form1.pc13_depto.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo.iframe_solicitem',
          'db_iframe_depart',
          'func_db_depart.php?pesquisa_chave='+document.form1.pc13_depto.value+
          '&funcao_js=parent.lanc_dotac.js_mostradb_depart','Pesquisa',false,'0');
      }else{
        document.form1.descrdepto.value = '';
      }
    }
    function js_mostradb_depart(chave,erro){
      document.form1.descrdepto.value = chave;
      if(erro==true){
        document.form1.pc13_depto.focus();
        document.form1.pc13_depto.value = '';
      }
    }

    function js_verquant(campo) {


      max   = parseFloat(parent.document.form1.quant_rest.value);


      if(campo.indexOf(',') != -1) {

        document.form1.pc13_quant.value = document.form1.pc13_quant.value.replace(',','.');
        campo = document.form1.pc13_quant.value;
      }
      campo = parseFloat(campo);
      if(max!=0 && (campo>max)){
        alert("A quantidade informada na dotação deve ser inferior ou igual a "+max+" (Quantidade restante do item).");
        document.form1.pc13_quant.value = "";
        document.form1.pc13_valor.value = "";
        document.form1.pc13_quant.focus();
      }else if(max==0){
        alert("Todos os materiais deste lançamento já foram incluídos em dotações.");
        document.form1.pc13_quant.value = "";
        document.form1.pc13_quant.focus();
      }
    }
    /*
     if(parent.documento.form1.pc11_codigo.value!=''){
     document.form1.pc13_codigo.value = parent.documento.form1.pc11_codigo.value;
     }
     */

    <?
    if(isset($opcao) && $opcao=="excluir" || ($db_opcao==3 && $db_botao==false)){
    }else if($desabilitarcampos==true && $errado!="true"){
    ?>
    val = new Number(document.form1.pc13_valorlimite.value);
    document.form1.pc13_quant.readOnly = true;
    document.form1.pc13_quant.style.backgroundColor="#DEB887";
    if(val!=0 || val!=''){
      document.form1.pc13_quant.value="1";
      document.form1.pc13_valor.readOnly = false;
      document.form1.pc13_valor.style.backgroundColor="";
    }else{
      <?
      if(isset($pc13_valor_limite) && $pc13_valor_limite==0 && $pc11_vlrun>0){
        echo '
        document.form1.pc13_quant.value="0";
        parent.document.form1.quant_rest.value="0";
        document.form1.pc13_valor.readOnly = false;
        document.form1.pc13_valor.style.backgroundColor="#FFF";
        ';
        if($db_opcao == 1){
         echo "document.form1.incluir.disabled=true";
        }
      }
      ?>

    }
    <?
      if(isset($tquant) && $tquant=='true'){
        echo "document.form1.pc13_quant.readOnly=true;";
      }
    }else{
    ?>
    val = new Number(parent.document.form1.quant_rest.value);
    if(val!=0 || val!=''){
      document.form1.pc13_quant.readOnly=false;
      document.form1.pc13_quant.style.backgroundColor="";
      document.form1.pc13_valor.readOnly=false;
      document.form1.pc13_valor.style.backgroundColor="#FFF";
    }
    <?
    }
    ?>
    <?
       if ($pc30_ultdotac == "t"&&@$flag_dotac == true){
    ?>
    document.form1.submit();
    <?
       }
    ?>

    function js_openContrapartidas(iCodDot, iCodItem) {

      var nValorDot    = $F('pc13_valor');
      var iSolicitacao = $F('pc11_numero');
      var sUrl         = 'com4_solicitacontrapartida.php?iCodDot='+iCodDot;
      sUrl            += '&iSolicitacao='+iSolicitacao+'&nValor='+nValorDot+'&iCodItem='+iCodItem;
      js_OpenJanelaIframe('CurrentWindow.corpo.iframe_solicitem',
        'db_iframe_contrapartida',sUrl,
        'Cadastro de Contrapartida',true,'0');
    }
    js_ver();

    if (!empty(parent.document.getElementById('pc16_codmater').value)) {
      parent.js_buscarQuantidadeRestanteItemEstimativa();
    }
  </script>

  <?
  if ($desabilitabotao==true){
    echo "<script> document.form1.incluir.disabled=true </script>";
    $desabilitabotao=false;
  }
  ?>
