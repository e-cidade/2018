<?php
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_utils.php");

require_once("dbforms/db_funcoes.php");

require_once("classes/db_rhpesdoc_classe.php");
require_once("classes/db_db_uf_classe.php");

$oDaoDb_uf    = new cl_db_uf;
$oDaoRhPesDoc = new cl_rhpesdoc(); 
$oDaoRhPesDoc->rotulo->label();
$oDaoDb_uf->rotulo->label();

$db_opcao     = 2;

$oRotulos = new rotulocampo();
$oRotulos->label('rh16_regist');
$oRotulos->label('z01_cgccpf ');
$oRotulos->label('rh16_titele');
$oRotulos->label('rh16_secaoe');
$oRotulos->label('rh16_zonael');
$oRotulos->label('rh16_reserv');
$oRotulos->label('rh16_ctps_n');
$oRotulos->label('rh16_ctps_s');
$oRotulos->label('rh16_ctps_d');
$oRotulos->label('rh16_catres');

// Objeto com os itens do array ($_GET)
$oGet  = db_utils::postMemory($_GET);
// Objeto com os itens do array ($_POST)
$oPost = db_utils::postMemory($_POST);


if(isset($oGet->iMatricula)){
  
  $sSqlDocumentos = " select z01_cgccpf,                                        \n";
  $sSqlDocumentos.= "        rhpesdoc.*                                         \n";
  $sSqlDocumentos.= "   from rhpessoal                                          \n";
  $sSqlDocumentos.= "        inner join rhpesdoc on rh16_regist = rh01_regist   \n";
  $sSqlDocumentos.= "        inner join cgm      on rh01_numcgm = z01_numcgm    \n";
  $sSqlDocumentos.= "  where rh01_regist = {$oGet->iMatricula}                  \n";
  $rsDocumentos   = db_query($sSqlDocumentos);
  
  if($rsDocumentos && pg_num_rows($rsDocumentos) > 0) {
    
    $oDocumentos        = db_utils::fieldsMemory($rsDocumentos, 0);
    $rh16_regist        = $oDocumentos->rh16_regist; 
    $z01_cgccpf         = $oDocumentos->z01_cgccpf ;
    $rh16_titele        = $oDocumentos->rh16_titele;
    $rh16_secaoe        = $oDocumentos->rh16_secaoe;
    $rh16_zonael        = $oDocumentos->rh16_zonael;
    $rh16_reserv        = $oDocumentos->rh16_reserv;
    $rh16_ctps_n        = $oDocumentos->rh16_ctps_n;
    $rh16_ctps_s        = $oDocumentos->rh16_ctps_s;
    $rh16_ctps_d        = $oDocumentos->rh16_ctps_d;
    $rh16_catres        = $oDocumentos->rh16_catres;
    $rh16_ctps_uf       = $oDocumentos->rh16_ctps_uf;
    $rh16_pis           = $oDocumentos->rh16_pis;
    $rh16_carth_n       = $oDocumentos->rh16_carth_n;
    $r16_carth_cat      = $oDocumentos->r16_carth_cat;
    if($oDocumentos->rh16_carth_val != ''){
    	
	    $aRh16_carth_val    = explode("-", $oDocumentos->rh16_carth_val);
	    $rh16_carth_val_dia = $aRh16_carth_val[2];
	    $rh16_carth_val_mes = $aRh16_carth_val[1];
	    $rh16_carth_val_ano = $aRh16_carth_val[0];
    }
    
  }
}//js_verificaCGCCPF(this)
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?php
      db_app::load("estilos.css");
      db_app::load("scripts.js");
      db_app::load("strings.js");
      
      db_app::load("prototype.js"); 
    ?>
  </head>
  <style>

  </style>
  <body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#cccccc">
  

  
  <br>
    <center>
    <form name = "form1">
    <Fieldset style="width: 550px">
      <legend>
        <b>DOCUMENTOS </b>
      </legend>
        <table border="0">
          <tr>
            <td nowrap title="<?=@$Trh16_titele?>"><?=@$Lrh16_titele?></td>
            <td>
              <?
              db_input('rh16_titele',17,$Irh16_titele,true,'text',$db_opcao,"")
              ?>
            </td>
            <td><?=@$Lrh16_zonael?></td>
            <td>
              <?
              db_input('rh16_zonael',5,$Irh16_zonael,true,'text',$db_opcao,"")
              ?>
            </td>
            <td><?=@$Lrh16_secaoe?></td>
            <td>
              <?
              db_input('rh16_secaoe',5,$Irh16_secaoe,true,'text',$db_opcao,"")
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Trh16_reserv?>"><?=@$Lrh16_reserv?></td>
            <td><?
              db_input('rh16_reserv',17,$Irh16_reserv,true,'text',$db_opcao,"")
              ?>
            </td>
            <td><?=@$Lrh16_catres?></td>
            <td><?
                db_input('rh16_catres',5,$Irh16_catres,true,'text',$db_opcao,"")
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=$Tz01_cgccpf?>"><B>CPF: </B></td>
            <td>
              <?
              //db_input('z01_cgccpf',17, null, null,'text',$db_opcao," onblur='js_verificaCGCCPF(this);' ")
              ?>
              <input type="text" id="z01_cgccpf" size="17" maxlength="11" style="background-color: rgb(230,228,241)" onblur="js_verificaCGCCPF(this);" value="<?=$z01_cgccpf ?>">
            </td>
            <td><?//=@$Lrh16_catres?></td>
            <td>
              <?
                //db_input('rh16_catres',5,$Irh16_catres,true,'text',$db_opcao,"")
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Trh16_ctps_n?>"><?=@$Lrh16_ctps_n?>
            </td>
            <td>
              <?
              db_input('rh16_ctps_n',17,$Irh16_ctps_n,true,'text',$db_opcao,"")
              ?>
            </td>
            <td><?=@$Lrh16_ctps_s?>
            </td>
            <td><?
            db_input('rh16_ctps_s',5,$Irh16_ctps_s,true,'text',$db_opcao,"")
            ?>
            </td>
            <td><?=@$Lrh16_ctps_d?>
            </td>
            <td><?
            db_input('rh16_ctps_d',5,$Irh16_ctps_d,true,'text',$db_opcao,"")
            ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Trh16_ctps_uf?>"><?
            db_ancora(@$Lrh16_ctps_uf,"",3);
            ?>
            </td>
            <td colspan='5'>
              <?
                $rsEstados = $oDaoDb_uf->sql_record($oDaoDb_uf->sql_query_file(null,"db12_codigo as rh16_ctps_uf,db12_uf"));
                db_selectrecord("rh16_ctps_uf",$rsEstados,true,$db_opcao,"","","","0-Nenhum...");
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Trh16_pis?>"><?=@$Lrh16_pis?>
            </td>
            <td><?
                db_input('rh16_pis',17,$Irh16_pis,true,'text',$db_opcao,"onblur = js_validaPis(this.value);")
                ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Trh16_carth_n?>"><?=@$Lrh16_carth_n?>
            </td>
            <td><?
                db_input('rh16_carth_n',17,$Irh16_carth_n,true,'text',$db_opcao,"")
                ?>
            </td>
            <td nowrap title="<?=@$Tr16_carth_cat?>"><?=@$Lr16_carth_cat?>
            </td>
            <td><?
                db_input('r16_carth_cat',5,$Ir16_carth_cat,true,'text',$db_opcao,"")
                ?>
            </td>
            <td nowrap title="<?=@$Trh16_carth_val?>"><?=@$Lrh16_carth_val?>
            </td>
            <td><?
                db_inputdata('rh16_carth_val',@$rh16_carth_val_dia,@$rh16_carth_val_mes,@$rh16_carth_val_ano,true,'text',$db_opcao,"")
                ?>
            </td>
          </tr>
        </table>
      </fieldset>
      <input type="button" value="Salvar"    onclick="js_salvar();">
      <input type="button" value="Cancelar"  onclick="parent.js_fechaJanelaManutencao();">
  </center>
  </body>
</html>
<script>

function js_salvar() {

  var sUrlRPC               = "pes1_cadastroDocumentos.RPC.php";
  
  var iMatricula            = parent.$F("rh01_regist");
  var iTitulo               = $F("rh16_titele");
  var iZona                 = $F("rh16_zonael");
  var iSecao                = $F("rh16_secaoe");
  var iCertificado          = $F("rh16_reserv");
  var iCategoria            = $F("rh16_catres");
  var iCpf                  = $F("z01_cgccpf");
  var iCtps                 = $F("rh16_ctps_n");
  var iSerie                = $F("rh16_ctps_s");
  var iDigito               = $F("rh16_ctps_d");
  var sUfCtps               = $F("rh16_ctps_uf");
  var iPis                  = $F("rh16_pis");
  var iCnh                  = $F("rh16_carth_n");
  var sCategoriaCnh         = $F("r16_carth_cat");
  var dValidadeCnh          = $F("rh16_carth_val");
  var oParametros           = new Object();
  var msgDiv                = "Salvando Alterações \n Por Favor Aguarde ...";
  
  oParametros.exec          = 'salvar';
  oParametros.iMatricula    = iMatricula;
  oParametros.iTitulo       = iTitulo;
  oParametros.iZona         = iZona;
  oParametros.iSecao        = iSecao;
  oParametros.iCertificado  = iCertificado;
  oParametros.iCategoria    = iCategoria;
  oParametros.iCpf          = iCpf;
  oParametros.iCtps         = iCtps;
  oParametros.iSerie        = iSerie;
  oParametros.iDigito       = iDigito;
  oParametros.sUfCtps       = sUfCtps;
  oParametros.iPis          = iPis;
  oParametros.iCnh          = iCnh;
  oParametros.sCategoriaCnh = sCategoriaCnh;
  oParametros.dValidadeCnh  = dValidadeCnh;
  js_divCarregando(msgDiv,'msgBox');
   
   var oAjaxLista  = new Ajax.Request(sUrlRPC,
                                             {method: "post",
                                              parameters:'json='+Object.toJSON(oParametros),
                                              onComplete: js_retornoDocumentos
                                             });   
}

function js_retornoDocumentos(oAjax) {
    
    js_removeObj('msgBox');
    var oRetorno = eval("("+oAjax.responseText+")");
    
    // se o retorno do csv "status" for 1, significa que nao ocorreram erros e exibimos a opção de download
    
    if (oRetorno.iStatus == 1) {

       alert(oRetorno.sMessage.urlDecode());
    } else {  // senão  Exibimos o erro ocorriodo na geração do CSV
      
      alert(oRetorno.sMessage.urlDecode());
      return false;
    
    }
}

</script>