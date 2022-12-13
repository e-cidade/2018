<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));

require_once(modification("dbforms/db_funcoes.php"));

require_once(modification("classes/db_empautpresta_classe.php"));
require_once(modification("classes/db_empprestatip_classe.php"));
require_once(modification("classes/db_empautoriza_classe.php"));
require_once(modification("classes/db_empauthist_classe.php"));
require_once(modification("classes/db_empautitem_classe.php"));
require_once(modification("classes/db_empautidot_classe.php"));
require_once(modification("classes/db_emphist_classe.php"));
require_once(modification("classes/db_emptipo_classe.php"));
require_once(modification("classes/db_cflicita_classe.php"));
require_once(modification("classes/db_pctipocompra_classe.php"));
require_once(modification("classes/db_empparametro_classe.php"));
require_once(modification("classes/db_pcparam_classe.php"));
require_once(modification("classes/db_concarpeculiar_classe.php"));

require_once(modification("model/CgmFactory.model.php"));
require_once(modification("model/fornecedor.model.php"));

require_once(modification("classes/db_empautorizaprocesso_classe.php"));

db_postmemory($HTTP_POST_VARS);

$clempautpresta                   = new cl_empautpresta;
$clempprestatip                   = new cl_empprestatip;
$clempautoriza                    = new cl_empautoriza;
$clempauthist                     = new cl_empauthist;
$clemphist                        = new cl_emphist;
$clempautitem                     = new cl_empautitem;
$clempautidot                     = new cl_empautidot;
$clemptipo                        = new cl_emptipo;
$clcflicita                       = new cl_cflicita;
$clpctipocompra                   = new cl_pctipocompra;
$clempparametro                   = new cl_empparametro;
$clpcparam 	                      = new cl_pcparam;
$clconcarpeculiar                 = new cl_concarpeculiar;
$oDaoEmpenhoProcessoAdminitrativo = new cl_empautorizaprocesso;


$db_opcao    = 1;
$db_botao    = true;
$sUrlEmpenho = "emp1_empempenho001.php";
$iAnoUsu     = db_getsession("DB_anousu");
$iAnoData    = date("Y", db_getsession("DB_datausu"));
$rsEmpParam  = $clempparametro->sql_record($clempparametro->sql_query($iAnoUsu));
if ($clempparametro->numrows > 0) {
  db_fieldsmemory($rsEmpParam,0);
  if ($e30_notaliquidacao != '') {
    $sUrlEmpenho = "emp4_empempenho001.php";
  }
}

if(isset($incluir) || isset($autori_importa)) {

	if(isset($autori_importa)){
	  $result = $clempautoriza->sql_record( $clempautoriza->sql_query_processo($autori_importa) );
    db_fieldsmemory($result,0);
	}

  try {

    $oFornecedor = new fornecedor($e54_numcgm);
    $oFornecedor->verificaBloqueioAutorizacaoEmpenho(null);
    $iStatusBloqueio = $oFornecedor->getStatusBloqueio();

  } catch (Exception $eException) {

    $sqlerro  = true;
    $erro_msg = $eException->getMessage();
  }

  if ($iStatusBloqueio == 2) {

    $erro_msg  = "\\nusuário:\\n\\n Fornecedor com débito na prefeitura !\\n\\n\\n\\n";
    db_msgbox($erro_msg);
  }
}

if (isset($incluir)) {
  $sqlerro=false;

  /* [Extensão] Programação Financeira */

  if($sqlerro==false){

	  $res_pcparam = $clpcparam->sql_record($clpcparam->sql_query_file(db_getsession("DB_instit"),"pc30_fornecdeb"));
	  if ($clpcparam->numrows > 0) {
	    db_fieldsmemory($res_pcparam,0);

	    $sql_fornec     = "select * from fc_tipocertidao($e54_numcgm,'c','".date("Y-m-d",db_getsession("DB_datausu"))."','') as retorno";
	    $res_fornec     = @db_query($sql_fornec);
	    $numrows_fornec = @pg_numrows($res_fornec);

	    if ($numrows_fornec > 0) {
	      db_fieldsmemory($res_fornec,0);
	    }

	    if (isset($retorno) && $retorno == "positiva") {

	      if ($pc30_fornecdeb == 3 && $iStatusBloqueio != 1) {
	        $clempautoriza->erro_campo = "e54_numcgm";
	        $erro_msg = "Fornecedor com debito";
	        $sqlerro  = true;
	      }
	    }
	  }
  }
  // Valida Ano da Sessao e da Data da Sessao... devem ser a mesma
  if ($sqlerro == false) {

    if ((int)$iAnoUsu <> (int)$iAnoData) {

      $sDataUsu = date("d/m/Y", db_getsession("DB_datausu"));
      $erro_msg = "ERRO: Ano da Sessão ($iAnoUsu) diferente do Ano da Data Atual ($sDataUsu)!";
      $sqlerro  = true;

    }

  }

  db_inicio_transacao();

  if (!$sqlerro) {

    $clempautoriza->e54_autori  = $e54_autori;
    $clempautoriza->e54_numcgm  = $e54_numcgm;
    $clempautoriza->e54_login   = db_getsession("DB_id_usuario") ;
    $clempautoriza->e54_resumo  = $e54_resumo;
    $clempautoriza->e54_anousu  = $iAnoUsu;
    ;
    $clempautoriza->e54_codcom  = $e54_codcom;
    $clempautoriza->e54_destin  = $e54_destin;
    $clempautoriza->e54_tipol   = $e54_tipol ;
    $clempautoriza->e54_numerl  = $e54_numerl;
    $clempautoriza->e54_emiss   = date("Y-m-d",db_getsession("DB_datausu"));
    $clempautoriza->e54_codtipo = $e54_codtipo;
    $clempautoriza->e54_instit  = db_getsession("DB_instit");
    $clempautoriza->e54_depto   = db_getsession("DB_coddepto");

    $clempautoriza->e54_valor  = '';
    $clempautoriza->e54_praent = '';
    $clempautoriza->e54_entpar = '';
    $clempautoriza->e54_conpag = '';
    $clempautoriza->e54_codout = '';
    $clempautoriza->e54_contat = '';
    $clempautoriza->e54_telef  = '';
    $clempautoriza->e54_numsol = '';
    $clempautoriza->e54_anulad = null;
    $clempautoriza->e54_concarpeculiar = $e54_concarpeculiar;

    $clempautoriza->incluir(null);
    if ($clempautoriza->erro_status == 0) {

      $sqlerro=true;
      $erro_msg = $clempautoriza->erro_msg;
    } else {
      $ok_msg   = $clempautoriza->erro_msg;
    }
    $e54_autori = $clempautoriza->e54_autori;
  }

  if (!$sqlerro) {
    $clempauthist->e57_autori  = $e54_autori;
    $clempauthist->e57_codhist = $e57_codhist;
    $clempauthist->incluir($e54_autori);
    $erro_msg = $clempauthist->erro_msg;
    if ($clempauthist->erro_status==0) {
      $sqlerro=true;
    }
  }

  if (isset($e44_tipo)&&$e44_tipo!="") {
    $result = $clempprestatip->sql_record($clempprestatip->sql_query_file($e44_tipo,"e44_obriga"));
    db_fieldsmemory($result,0);
    if ($e44_obriga!=0) {
      $clempautpresta->e58_autori = $e54_autori;
      $clempautpresta->e58_tipo   = $e44_tipo;
      $clempautpresta->incluir();
      if ($clempautpresta->erro_status==0) {
        $sqlerro=true;
      }
    }
  }

  /**
   * Inclui processo administrativo em empautorizaprocesso
   */
  if (!$sqlerro && isset($e150_numeroprocesso) && !empty($e150_numeroprocesso)) {

    $oDaoEmpenhoProcessoAdminitrativo->e150_numeroprocesso = $e150_numeroprocesso;
    $oDaoEmpenhoProcessoAdminitrativo->e150_empautoriza    = $e54_autori;
    $oDaoEmpenhoProcessoAdminitrativo->incluir(null);

    if ($oDaoEmpenhoProcessoAdminitrativo->erro_status == 0) {

      $sqlerro  = true;
      $erro_msg = $oDaoEmpenhoProcessoAdminitrativo->erro_msg;
    }
  }


  db_fim_transacao($sqlerro);

} else if (isset($autori_importa)) {

	$sqlerro=false;

  /* [Extensão] Programação Financeira */

  db_inicio_transacao();

  if ($sqlerro == false) {
	  //verifica inclui os registros do empautoriza
	  $result = $clempautoriza->sql_record($clempautoriza->sql_query_file($autori_importa));
	  db_fieldsmemory($result,0);

	  $clempautoriza->e54_autori = $e54_autori;
	  $clempautoriza->e54_numcgm = $e54_numcgm;
	  $clempautoriza->e54_login  = db_getsession("DB_id_usuario") ;
	  $clempautoriza->e54_resumo = str_replace("'"," ",$e54_resumo);
	  $clempautoriza->e54_anousu = $iAnoUsu;

	  $clempautoriza->e54_codcom = $e54_codcom;
	  $clempautoriza->e54_destin = $e54_destin;
	  $clempautoriza->e54_tipol  = $e54_tipol ;
    $clempautoriza->e54_numerl = $e54_numerl;
	  $clempautoriza->e54_emiss  = date("Y-m-d",db_getsession("DB_datausu"));
	  //db_msgbox(date("Y-m-d",db_getsession("DB_datausu")));
	  $clempautoriza->e54_codtipo = $e54_codtipo;
	  $clempautoriza->e54_instit = db_getsession("DB_instit");

	  $clempautoriza->e54_valor  = $e54_valor;
	  $clempautoriza->e54_praent = $e54_praent;
	  $clempautoriza->e54_entpar = $e54_entpar;
	  $clempautoriza->e54_conpag = $e54_conpag;
	  $clempautoriza->e54_codout = $e54_codout;
	  $clempautoriza->e54_contat = $e54_contat;
	  $clempautoriza->e54_telef  = $e54_telef;
	  $clempautoriza->e54_numsol = $e54_numsol;
	  $clempautoriza->e54_anulad = null;
	  $clempautoriza->e54_depto  = db_getsession("DB_coddepto");
	  $clempautoriza->e54_concarpeculiar = $e54_concarpeculiar;

	  $clempautoriza->incluir(null);
	  if ($clempautoriza->erro_status==0) {
	    $sqlerro=true;
	    $erro_msg= $clempautoriza->erro_msg;
	  } else {
	    $ok_msg= $clempautoriza->erro_msg;
	    $nova_autori =$clempautoriza->e54_autori;
	  }
	  //final
  }

  if (isset($e44_tipo)&&$e44_tipo!=""&&$sqlerro==false) {
    $result = $clempprestatip->sql_record($clempprestatip->sql_query_file($e44_tipo,"e44_obriga"));
    db_fieldsmemory($result,0);
    if ($e44_obriga!=0) {
      $clempautpresta->e58_autori = $nova_autori;
      $clempautpresta->e58_tipo   = $e44_tipo;
      $clempautpresta->incluir();
      if ($clempautpresta->erro_status==0) {
        $sqlerro=true;
      }
    }
  }

  //rotina que verifica sem tem registro no empauthist
  $result=$clempauthist->sql_record($clempauthist->sql_query_file($autori_importa));
  if ($sqlerro==false &&$clempauthist->numrows>0) {
    db_fieldsmemory($result,0);

    $e54_autori = $clempautoriza->e54_autori;
    $clempauthist->e57_autori=$nova_autori;
    $clempauthist->e57_codhist=$e57_codhist;
    $clempauthist->incluir($nova_autori);
    $erro_msg= $clempauthist->erro_msg;
    if ($clempauthist->erro_status==0) {
      $sqlerro=true;
    }
  }
  //final

  //rotina para importar da tabela empautdot
  if ($sqlerro==false) {
    $result = $clempautidot->sql_record($clempautidot->sql_query_file($autori_importa,"*",null,"e56_autori= $autori_importa  and   e56_anousu =".$iAnoUsu));

    if ($clempautidot->numrows>0) {
      db_fieldsmemory($result,0);
      $clempautidot->e56_autori  = $nova_autori;
      $clempautidot->e56_anousu  = $iAnoUsu;

      $clempautidot->e56_coddot  = $e56_coddot;
      $clempautidot->incluir($nova_autori);
      $erro_msg =$clempautidot->erro_msg;
      if ($clempautidot->erro_status==0) {
        $sqlerro=true;
      }
    }
  }
  //final

  //rotina para importar da tabela empautitem
  if ($sqlerro==false) {
    $result = $clempautitem->sql_record($clempautitem->sql_query_file($autori_importa));
    $numrows=$clempautitem->numrows;

    $result02 = $clempautitem->sql_record($clempautitem->sql_query_file($nova_autori,null,"max(e55_sequen)+1 as e55_sequen"));
    db_fieldsmemory($result02,0);
    if ($e55_sequen=='') {
      $e55_sequen=1;
    }

    if ($numrows>0) {
      for ($i=0; $i<$numrows; $i++) {
        db_fieldsmemory($result,$i);
        $e55_descr = str_replace("'","",$e55_descr);
        $valor_tot = $e55_quant*$e55_vlrun;

        //$valorunitarioautitem = db_formatar($e55_vltot/$e55_quant,"v"," ",4);
        $clempautitem->e55_autori            = $nova_autori ;
        $clempautitem->e55_sequen            = ($i + 1);
        $clempautitem->e55_item              = $e55_item ;
        $clempautitem->e55_quant             = $e55_quant;
        $clempautitem->e55_servicoquantidade = $e55_servicoquantidade;

        if ($valor_tot != $e55_vltot) {
          $clempautitem->e55_vltot = $valor_tot;
        } else {
          $clempautitem->e55_vltot = $e55_vltot;
        }

        $clempautitem->e55_descr  = str_replace("'"," ",$e55_descr);
        $clempautitem->e55_codele = $e55_codele;
        $clempautitem->e55_vlrun  = $e55_vlrun;
        //db_msgbox($e55_vlrun);
        $clempautitem->incluir($nova_autori,($i + 1));
        $erro_msg= $clempautitem->erro_msg;
        if ($clempautitem->erro_status=="0") {
          $sqlerro=true;
          break;
        }
        $e55_sequen;
      }
    }
  }

  /**
   * Inclui processo administrativo em empautorizaprocesso
   */
  if (!$sqlerro && isset($e150_numeroprocesso) && !empty($e150_numeroprocesso)) {

    $oDaoEmpenhoProcessoAdminitrativo->e150_numeroprocesso = $e150_numeroprocesso;
    $oDaoEmpenhoProcessoAdminitrativo->e150_empautoriza    = $e54_autori;
    $oDaoEmpenhoProcessoAdminitrativo->incluir(null);

    if ($oDaoEmpenhoProcessoAdminitrativo->erro_status == 0) {

      $sqlerro  = true;
      $erro_msg = $oDaoEmpenhoProcessoAdminitrativo->erro_msg;
    }
  }

  //final


  $e54_autori= $nova_autori;
  db_fim_transacao($sqlerro);

} else if (isset($pesq_ult)&&$pesq_ult==true) {
  $result_ultalt=$clempautoriza->sql_record($clempautoriza->sql_query(null,"e54_numcgm  ,z01_nome  ,e54_login   ,e54_codcom  ,e54_destin  ,e54_valor   ,e54_anousu  ,e54_tipol   ,e54_numerl  ,e54_praent  ,e54_entpar  ,e54_conpag  ,e54_codout  ,e54_contat  ,e54_telef   ,e54_numsol  ,e54_anulad  ,e54_emiss   ,e54_resumo  ,e54_codtipo ,e54_instit  ,e54_depto","e54_autori desc limit 1","e54_instit =".db_getsession("DB_instit")." and e54_login=".db_getsession("DB_id_usuario")));
  if ($clempautoriza->numrows>0) {
    db_fieldsmemory($result_ultalt,0);
  }
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
  db_app::load("scripts.js,
                prototype.js,
                widgets/windowAux.widget.js,
                strings.js,
                widgets/dbtextField.widget.js,
                dbViewNotificaFornecedor.js,
                dbmessageBoard.widget.js,
                dbautocomplete.widget.js,
                dbcomboBox.widget.js,
                datagrid.widget.js,
                widgets/dbtextFieldData.widget.js,
                DBFormCache.js,
                estilos.css,
                grid.style.css
  ");
?>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >

<center>
  <div style="margin-top: 20px; width: 700px; ">
  	<?
  	  include(modification("forms/db_frmempautoriza.php"));
  	?>
  </div>
</center>
</body>
</html>
<?
if(isset($incluir) || isset($autori_importa) ) {
  if($sqlerro==true){
    db_msgbox($erro_msg);
    $db_botao=true;
    if(isset($incluir) && $clempautoriza->erro_campo!=""){
      echo "<script> document.form1.".$clempautoriza->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clempautoriza->erro_campo.".focus();</script>";
    }
  }else{
    //db_msgbox($ok_msg);
    echo "
           <script>
                parent.mo_camada('empautitem');
	   </script>
         ";
    db_redireciona("emp1_empautoriza005.php?chavepesquisa=$e54_autori");
  }
}
?>
