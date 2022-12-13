<?php
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

//MODULO: protocolo
$clprocandamint->rotulo->label();
$clprocandamintusu->rotulo->label();
$clprotprocesso->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("p58_codproc");
$clrotulo->label("p61_codandam");
$clrotulo->label("login");
$clrotulo->label("descrdepto");
$clrotulo->label("z01_nome");
$clrotulo->label("p51_descr");
$clrotulo->label("nome");
$clrotulo->label("p58_numero");

$result_proc=$clprotprocesso->sql_record($clprotprocesso->sql_query($p58_codproc,"*"));
if ($clprotprocesso->numrows!=0) {

  db_fieldsmemory($result_proc,0);
  $p58_numero .= "/{$p58_ano}"; 
}

$dtproc = db_formatar($p58_dtproc,'d');

/**
 * Tipos de despachos
 * padrao: 1 - Despacho
 */
$aTipos = array(1 => 'Despacho');
$oDaoTipoDespacho = new cl_tipodespacho();
$sSqlTIpos = $oDaoTipoDespacho->sql_query_file();
$rsTipos = $oDaoTipoDespacho->sql_record($sSqlTIpos);

if ($oDaoTipoDespacho->numrows > 1) {

  $aTipos = array();

  for ($iRow = 0; $iRow < $oDaoTipoDespacho->numrows; $iRow++) {

    $oDadosTipo = db_utils::fieldsMemory($rsTipos, $iRow);
    $aTipos[$oDadosTipo->p100_sequencial] = $oDadosTipo->p100_descricao;
  }
}
?>
<script type="text/javascript">

/**
 * Valida campo despacho antes de enviar formulario
 *
 * @access public
 * @return bool
 */
function js_submit(){

  /**
   * Quantidade de caracteres do campo despacho, ignorando espacos vazios
   */
  var iDespachoCaracteres = document.form1.p78_despacho.value.trim().length;

  /**
   * Parametro com minimo de caracteres permitidos para o despacho 
   */
  var iDespachoMinimoCaracteres = document.form1.p90_minchardesp.value;

  /**
   * Parametro obrigando ou nao inclusao do despacho 
   */
  var lDespachoObrigatorio = document.form1.despachoob.value;

  /**
   * Cadastra despacho
   * - Despacho não é obrigatorio
   * - Tamanho do campo despacho vazio
   */
  if ( lDespachoObrigatorio == false && iDespachoCaracteres == 0 ) {		
    return true;
	}

  /**
   * Bloqueia inclusao
   * - Informou despacho, mas é menor que o minimo de caracteres 
   */
  if ( iDespachoMinimoCaracteres > 0 && iDespachoCaracteres < iDespachoMinimoCaracteres ) {	

    alert("É necessário digitar no mínimo " + iDespachoMinimoCaracteres + " caracteres para o despacho!!");
    document.form1.p78_despacho.focus();
    return false;				
  }
	
  return true;
}

</script>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td>
    <fieldset>
      <legend><b>Dados do Processo</b> </legend>
      <table>
        <tr>
          <td nowrap title="<?=$Tp58_codproc?>" align='left' >
           <?=$Lp58_codproc;?>
          </td>
          <td>
          <?db_input("p58_codproc",15,$Ip58_codproc,true,"text",3);?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=$Tp58_numero?>" align='left' >
           <?=$Lp58_numero;?>
          </td>
          <td>
          <?db_input("p58_numero",15,$Ip58_numero,true,"text",3);?>
          </td>
          <td title="<?=$Tp58_codigo?>" align='left'>
          <?=$Lp58_codigo?>
	        </td>
	        <td nowrap >
          <?db_input("p58_codigo",8,$Ip58_codigo,true,"text",3);
	          db_input("p51_descr",32,$Ip51_descr,true,"text",3)
	        ?>
          </td>
        </tr>
        <tr>
          <td title="<?=$Tp58_dtproc?>"align='left'>
           <?=$Lp58_dtproc;?>
	        </td>
	        <td>
          <?db_input("dtproc",10,$Ip58_dtproc,true,"text",3);?>
          </td>
          <td title="<?=$Tp58_hora?>">
            <b>Hora:</b>
          </td>
          <td>
          <?db_input("p58_hora",10,$Ip58_hora,true,"text",3);?>
          </td>
        </tr>
        <tr>
          <td title="<?=$login?>"align='left'>
           <?=$Llogin;?>
          </td>
          <td>
          <?db_input("login",40,$Ilogin,true,"text",3);?>
          </td>
        </tr>
        <tr>
          <td title="<?=$Tp58_numcgm?>"align='left'>
            <?=$Lp58_numcgm?>
          </td>
          <td nowrap title="<?=$Tz01_nome?>">
            <?db_input("p58_numcgm",6,$Ip58_numcgm,true,"text",3);
              db_input("z01_nome",32,$Iz01_nome,true,"text",3);
             ?>
          </td>
          <td title="<?=$p58_requer?>"align='left'>
           <?=$Lp58_requer?>
          </td>
          <td>
            <? 
             db_input("p58_requer",40,$Ip58_requer,true,"text",3);
            ?>
          </td>
        </tr> 
        <tr>
          <td title="<?=$Tp58_coddepto?>"align='left'>
            <?=$Lp58_coddepto?>
          </td>
          <td nowrap title="<?=$Tdescrdepto?>">
          <?db_input("p58_coddepto",6,$Ip58_coddepto,true,"text",3);
            db_input("descrdepto",32,$descrdepto,true,"text",3);?>
          </td>
          <td title="<?=$Tp58_codandam?>" align='left' >
           <?=$Lp58_codandam?>
          </td>
          <td>
             <?db_input("p58_codandam",15,$Ip58_codandam,true,"text",3);?>
          </td>
        </tr>
        <tr>
          <td title="<?=$Tp58_obs?>" align='left' >
             <?=$Lp58_obs?>
          </td>
          <td colspan=3 >
            <?db_textarea("p58_obs",1,90,$Ip58_obs,true,"text",3);?>
          </td>
        </tr>

        <tr>
          <td nowrap title="<?=@$Tp78_publico?>" >
           <?=@$Lp78_publico?>
          </td>
          <td>
            <?php
              $x = array("t"=>"Sim","f"=>"Não");
              db_select('p78_publico',$x,true,1,"");
            ?>
          </td>
        </tr>

        <tr<?php if (count($aTipos) < 2) { echo ' style="display:none;"'; } ?>>
          <td nowrap title="<?php echo $Tp78_tipodespacho; ?>" >
            <?php echo $Lp78_tipodespacho; ?>
          </td>
          <td>
            <?php db_select('p78_tipodespacho', $aTipos, true, 1, ""); ?>
          </td>
        </tr>

        <tr>
          <td nowrap title="<?=@$Tp78_despacho?>" colspan="4" >
          <fieldset>
          <legend>
             <?=@$Lp78_despacho?>
          </legend>
          <?php
            db_textarea('p78_despacho',12,100,$Ip78_despacho,true,'text',$db_opcao,"");
            $despachoob = false;
            $p90_minchardesp = 0;

            $sWhereParametrosProtocolo = "p90_instit = " . db_getsession('DB_instit');
            $sSqlParametrosProtocolo = $clprotparam->sql_query_file(null, '*', null, $sWhereParametrosProtocolo);
            $result_protparam = $clprotparam->sql_record($sSqlParametrosProtocolo);

            if ( $clprotparam->numrows > 0 ) {
  	           
              db_fieldsmemory($result_protparam, 0);
              
  	          if ( isset($p90_despachoob) && $p90_despachoob == "t") {
                $despachoob = true;
              }

              if (isset($p90_minchardesp) && $p90_minchardesp != "") {			
                echo "<br><b>*Mínimo de {$p90_minchardesp} caracteres para o despacho.</b>";
              } 
            }
            db_input("despachoob",40,"",true,"hidden",3);
            db_input("p90_minchardesp",40,"",true,"hidden",3);
          ?> 
          </fieldset>
          </td>
        </tr>
       </table>
     </fieldset>    
    </td>
  </tr>
  <tr>
    <td colspan=4 align='center' >
      <?
      $sSqlTransferido = $clproctransfer->sql_query_trans(null,
                                                          "p62_codtran as codtran ", 
                                                          "p62_codtran desc",
                                                          "p64_codtran is null and p63_codproc = {$p58_codproc}"
                                                         );
      $rsTransferido = $clproctransfer->sql_record($sSqlTransferido);
      if ($clproctransfer->numrows != 0) {  

        db_msgbox('Processo não encontrado.\nUSUÁRIO : verifique última posição do processo.');
        $db_botao = false;
      }
      $sSqlTransferidoInterno = $clproctransferintand->sql_query_cert(null, 
                                                                      "*",
                                                                      null,
                                                                      "p87_codandam <> {$p58_codandam}
                                                                       and p58_codproc=$p58_codproc"
                                                                     );  
      $rsTransferidoInterno        = $clproctransferintand->sql_record($sSqlTransferidoInterno);
      $iLinhasTransferenciaInterna = $clproctransferintand->numrows;
      if ($iLinhasTransferenciaInterna != 0) {
        
        db_msgbox('Processo não encontrado.\nUSUÁRIO : verifique última posição do processo.');
        $db_botao = false;
      }
      ?>
      <input name="incluir" type="submit" id="db_opcao" value="Incluir" <?=($db_botao==false?"disabled":"") ?> onclick='return js_submit();' >
      <input name="voltar" type="button" id="voltar" value="Voltar" onclick='top.corpo.location.href="pro4_procandamint001.php"' >
      <?
      if (isset($incluir) && $sqlerro == false){
        ?>
        <input name="imprimir" type="button" id="imprimir" value="Imprimir Despacho" onclick='js_imprime();' >
        <?
        }else{
        ?>
        <input name="imprimir" type="button" id="imprimir" value="Imprimir Despacho" disabled  onclick='js_imprime();' >
        <?
        }
      ?>
    </td>
  </tr>
</table>
</center>
</form>
<script>
document.form1.p78_despacho.style.width='100%';

function js_imprime() {

  var iCodigoAndamentoInterno = '<?php echo (isset($codprocandamint) ? $codprocandamint : null); ?>';
  var iCodigoProcesso = document.form1.p58_codproc.value;
  var sUrl = 'pro2_despachointer002.php?codproc=' + iCodigoProcesso + '&codprocandamint=' + iCodigoAndamentoInterno;

  if ( empty(iCodigoAndamentoInterno) ) {
    alert('Codigo do andamento não informado.');
  }

  jan = window.open(sUrl,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}
</script>
