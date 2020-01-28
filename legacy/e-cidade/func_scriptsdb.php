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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("libs/db_libpessoal.php");
include("dbforms/db_classesgenericas.php");
db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_GET_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$debug = isset($debug) ? $debug : null;


$subpes = db_anofolha()."/".db_mesfolha();
if($opcao == "avos"){
	$avos = retorna_avos($perai,$antes,$peraf);
	echo "
        <script>
          parent.document.form1.navos.value = '$avos';
        </script>
       ";
}else if($opcao == "faltas"){
	$falt = numero_faltas($registro,$perai,$peraf);
	echo "
        <script>
          parent.document.form1.r30_faltas.value = '$falt';
        </script>
       ";
}else if($opcao == "vfalta"){

  echo "<script>nDiasGozoFerias = null;</script>";
  
  $vfal = tabela_gozo($nfalt,$navos);

  if($difperaq < 24 ){
    
    if ( ($difperaq%2) == 0 ) {
      $vfal = ($difperaq/2) * 2.5; // Dois dias e meio
    }
    $falant = $vfal;
    $vfal = (int)$vfal;
    if($falant > $vfal){
      $vfal ++;
    }
  }

  if(isset($nDiasGozoFerias) && $nDiasGozoFerias > 30) {
    $vfal = $nDiasGozoFerias;
    echo "<script>
            nDiasGozoFerias = $nDiasGozoFerias;
          </script>";
  }

  if (!isset($iVfal)){
    
    $iVfal = "1";
  }
  echo "
        <script>
          parent.document.form1.r30_ndias.value = '".$vfal."';
          parent.document.form1.r30_ndias.readOnly                 = true;
          parent.document.form1.r30_ndias.style.backgroundColor    = '#DEB887';
          parent.js_montaselect($vfal, false);
          
          if(nDiasGozoFerias > 30){

            parent.document.form1.r30_ndias.readOnly              = false;
            parent.document.form1.r30_ndias.style.backgroundColor = '#FFFFFF';
              
            /**
             * Adicionao a função ao evento do elemento
             */
            parent.r30_ndias.addEventListener('change', parent.js_validamtipo);

          }
          if($vfal == 0){
            if(confirm('Este funcionário perdeu o direito à férias - Motivo: faltas.\\n\\nConfirma gravação do período de Férias?')){
              obj = parent.document.createElement('input');
              obj.setAttribute('name','semdireito');
              obj.setAttribute('type','hidden');
              obj.setAttribute('value','semdireito');
              parent.document.form1.appendChild(obj);

              parent.document.form1.action = 'pes4_cadferia004.php';
              parent.document.form1.submit();

            }else{
              parent.location.href = 'pes4_cadferia001.php';
            }
          }
          if ($iVfal == 0) {
          
              obj = parent.document.createElement('input');
              obj.setAttribute('name','semdireito');
              obj.setAttribute('type','hidden');
              obj.setAttribute('value','semdireito');
              parent.document.form1.appendChild(obj);

              parent.document.form1.action = 'pes4_cadferia004.php';
              parent.document.form1.submit();	
          
          }
          
        </script>
       ";
}else if($opcao == "vmtipo"){
  $nsaldo = dias_gozo("",$mtipo,$ndias);
  echo "
        <script>
          parent.document.form1.nsaldo.value = '$nsaldo';
          parent.js_verificadataini(1);
        </script>
       ";
}else if($opcao == "vafast"){
  $afast = verificaseexisteafastamentonoperiodo($registro, $ini, $fim);
  if($afast == true){
	  echo "
	        <script>
	          if(!confirm('Existe afastamento cadastrado para este período gozo.\\n\\nDeseja continuar?')){
              parent.document.form1.r30_per1i_dia.value = '';
              parent.document.form1.r30_per1i_mes.value = '';
              parent.document.form1.r30_per1i_ano.value = '';
              parent.document.form1.r30_per1i.value     = '';

              parent.document.form1.r30_per1f_dia.value = '';
              parent.document.form1.r30_per1f_mes.value = '';
              parent.document.form1.r30_per1f_ano.value = '';
              parent.document.form1.r30_per1f.value     = '';
            }
	        </script>
	       ";
  }
} else if($opcao == "perafast") {

  global $pessoal, $cfpess;
  
  //echo "<br><br> AQUI = " ;
  //die();
  
  $perafst = 0;
  $condicaoaux = " and r01_regist =".$registro;
  
  if( db_selectmax( "pessoal", "select r01_regist from pessoal ".bb_condicaosubpes( "r01_").$condicaoaux )){
  	
 	  if( db_selectmax( "cfpess", "select r11_anousu from cfpess ".bb_condicaosubpes( "r11_"))){
	    $perafst = afas_periodo_aquisitivo($perai, $peraf);
 	  }
  }
  
  if($perafst >= 180){
  	
	  if ($mensagemlote=='n'){
	     echo "
	          <script>
	                 alert('Funcionário perdeu direito a este período por afastamento.\\n\\n Altere período aquisitivo .');
	                 parent.document.form1.db_opcao.disabled=false;
	         </script>";
	   }else{

       echo "
          <script>
                 alert('Funcionário perdeu direito a este período por afastamento.\\n\\n Altere período aquisitivo ou clique em próximo.');
                 parent.document.form1.db_opcao.disabled=false;
          </script>";
     }

  } else { 
  	
    if ($debug == true) {
       echo "<script>";           
       echo "obj = parent.document.createElement('input');";
       echo "obj.setAttribute('name','debug');";
       echo "obj.setAttribute('type','hidden');";
       echo "obj.setAttribute('value',true);";
       echo "parent.document.form1.appendChild(obj);";
       echo "</script>";
    }
    
		echo "
	        <script>

            perai =     parent.document.form1.r30_perai_ano.value;
            perai+= '-'+parent.document.form1.r30_perai_mes.value;
            perai+= '-'+parent.document.form1.r30_perai_dia.value;

            peraf =     parent.document.form1.r30_peraf_ano.value;
            peraf+= '-'+parent.document.form1.r30_peraf_mes.value;
            peraf+= '-'+parent.document.form1.r30_peraf_dia.value;

            per1i = '';
            if(parent.document.form1.r30_per1i_ano){
              per1i =     parent.document.form1.r30_per1i_ano.value;
              per1i+= '-'+parent.document.form1.r30_per1i_mes.value;
              per1i+= '-'+parent.document.form1.r30_per1i_dia.value;
            }

            per1f = '';
            if(parent.document.form1.r30_per1f_ano){
              per1f =     parent.document.form1.r30_per1f_ano.value;
              per1f+= '-'+parent.document.form1.r30_per1f_mes.value;
              per1f+= '-'+parent.document.form1.r30_per1f_dia.value;
            }

            per2i = '';
            if(parent.document.form1.r30_per2i_ano){
              per2i =     parent.document.form1.r30_per2i_ano.value;
              per2i+= '-'+parent.document.form1.r30_per2i_mes.value;
              per2i+= '-'+parent.document.form1.r30_per2i_dia.value;
            }

            per2f = '';
            if(parent.document.form1.r30_per2f_ano){
              per2f =     parent.document.form1.r30_per2f_ano.value;
              per2f+= '-'+parent.document.form1.r30_per2f_mes.value;
              per2f+= '-'+parent.document.form1.r30_per2f_dia.value;
            }

            obj = parent.document.createElement('input');
            obj.setAttribute('name','r30_perai');
            obj.setAttribute('type','hidden');
            obj.setAttribute('value',perai);
            parent.document.form1.appendChild(obj);

            obj = parent.document.createElement('input');
            obj.setAttribute('name','r30_peraf');
            obj.setAttribute('type','hidden');
            obj.setAttribute('value',peraf);
            parent.document.form1.appendChild(obj);

            obj = parent.document.createElement('input');
            obj.setAttribute('name','r30_per1i');
            obj.setAttribute('type','hidden');
            obj.setAttribute('value',per1i);
            parent.document.form1.appendChild(obj);

            obj = parent.document.createElement('input');
            obj.setAttribute('name','r30_per1f');
            obj.setAttribute('type','hidden');
            obj.setAttribute('value',per1f);
            parent.document.form1.appendChild(obj);

            obj = parent.document.createElement('input');
            obj.setAttribute('name','r30_per2i');
            obj.setAttribute('type','hidden');
            obj.setAttribute('value',per2i);
            parent.document.form1.appendChild(obj);

            obj = parent.document.createElement('input');
            obj.setAttribute('name','r30_per2f');
            obj.setAttribute('type','hidden');
            obj.setAttribute('value',per2f);
            parent.document.form1.appendChild(obj);
            
            obj = parent.document.createElement('input');
            obj.setAttribute('name','ndiassaldo');
            obj.setAttribute('type','hidden');
            obj.setAttribute('value',parent.document.form1.nsaldo.value);
            parent.document.form1.appendChild(obj);

            parent.document.form1.action = 'pes4_cadferia005.php';
	          parent.document.form1.submit();
	        </script>
	       ";
  }

}else if($opcao == "enviarescis"){
  include("classes/db_rhregime_classe.php");
  include("classes/db_gerfs13_classe.php");
  $clrhregime = new cl_rhregime;
  $clgerfs13 = new cl_gerfs13;
  echo "
        <script>
          parent.document.form1.pagar_13_salario_na_rescisao.value = 'true';
        </script>
       ";
  $result_rescisao = $clrhregime->sql_record($clrhregime->sql_query_rescisao(null,"distinct r59_13sal,r59_479clt,r59_mfgts","","rh30_codreg= $regime and r59_causa=$causa and r59_13sal='t'"));
  if($clrhregime->numrows > 0){
    db_fieldsmemory($result_rescisao, 0);
    $mensagem = "";
    if($r59_479clt == 't'){
      $mensagem = "Informar o valor da indenização art. 479 CLT.";
    }
    if($r59_mfgts > 0){
      $mensagem = "\\nVerifique: Funcionário com direito multa FGTS de: ".db_formatar($r59_mfgts,"f")."%";
    }
    if($mensagem != ""){
      db_msgbox($mensagem);
    }
    $result_gerfs13 = $clgerfs13->sql_record($clgerfs13->sql_query_file($rh05_recis_ano,$rh05_recis_mes,$regist,null,"*"));
    if($clgerfs13->numrows > 0){
      
      echo "
            <script>
              if(!confirm('Este funcionário tem 13o salário neste mês.\\n Apagá-lo para pagar na rescisão?')){
                parent.document.form1.pagar_13_salario_na_rescisao.value = 'false';
              }
            </script>
           ";
    }
  }
  echo "
        <script>
          parent.document.form1.submit();
        </script>
       ";
}else if($opcao == "dadosrescis"){
  include("classes/db_rhpesrescisao_classe.php");
  $clrhpesrescisao = new cl_rhpesrescisao;
  $result_dadosrescis = $clrhpesrescisao->sql_record($clrhpesrescisao->sql_query_rescisao($seqpes));
  if($clrhpesrescisao->numrows > 0){
    db_fieldsmemory($result_dadosrescis,0);
    $x = array("1"=>"Trabalhado","2"=>"Aviso indenizado","3"=>"Sem aviso");
    echo "
          <script>
            parent.document.form1.rh05_recis_dia.value = '".$rh05_recis_dia."';
            parent.document.form1.rh05_recis_mes.value = '".$rh05_recis_mes."';
            parent.document.form1.rh05_recis_ano.value = '".$rh05_recis_ano."';
            parent.document.form1.rh05_recis.value = '".$rh05_recis_dia."'+'/'+'".$rh05_recis_mes."'+'/'+'".$rh05_recis_ano."';

	          parent.document.form1.rh05_causa.value     = '".$rh05_causa."';
	          parent.document.form1.r59_descr.value      = '".$r59_descr."';
	          parent.document.form1.rh05_caub.value      = '".$rh05_caub."';
	          parent.document.form1.r59_descr1.value     = '".$r59_descr1."';
	          parent.document.form1.taviso.value         = '".$x[$rh05_taviso]."';

            parent.document.form1.rh05_aviso_dia.value = '".$rh05_aviso_dia."';
            parent.document.form1.rh05_aviso_mes.value = '".$rh05_aviso_mes."';
            parent.document.form1.rh05_aviso_ano.value = '".$rh05_aviso_ano."';
            parent.document.form1.rh05_aviso.value = '".$rh05_aviso_dia."'+'/'+'".$rh05_aviso_mes."'+'/'+'".$rh05_aviso_ano."';

      	    parent.document.form1.rh05_mremun.value    = '".$rh05_mremun."';
	  </script>
	 ";
  }
}else if($opcao == "dadosdiversos"){
  include("classes/db_pesdiver_classe.php");
  $clpesdiver = new cl_pesdiver;
  $arr_diversos = split(",",$div);
  $erro = false;
  echo "
        <script>
            formula = parent.document.form1.r02_form.value;
	    /*
            for(var i=0; i<formula.length; i++){
	      if(formula[i] == ' '){
                formula[i] = '';
              }
	    }
	    parent.document.form1.r02_form.value = formula;
	    */
       ";
  for($i=0; $i<count($arr_diversos); $i++){
    $diverso = $arr_diversos[$i];

    $result_valordiversos = $clpesdiver->sql_record($clpesdiver->sql_query_file(db_anofolha(),db_mesfolha(),$diverso,db_getsession('DB_instit'),"r07_valor"));
    if($clpesdiver->numrows > 0){
      db_fieldsmemory($result_valordiversos, 0);
      echo "formula = formula.replace('".$diverso."','".$r07_valor."');\n";
    }else{
      echo "alert('Diverso (".$arr_diversos[$i].") informado não é válido. ');";
      $erro = true;
      break;
    }
  }
  if($erro == false){
    echo "
            valor = eval(formula);
	    valor = new Number(valor);
            if(isNaN(valor)){
              alert('Expressão informada não válida, verifique.');
              parent.document.form1.r02_form.select();
              parent.document.form1.r02_form.focus();
	    }else if(valor == 'Infinity'){
              alert('Erro: Divisão por 0 (zero).\\n\\nVerifique sua expressão ou diversos com valor zerado.');
              parent.document.form1.r02_form.select();
              parent.document.form1.r02_form.focus();
            }else{
	      parent.document.form1.r02_valor.value = valor.toFixed(2);
	      parent.document.getElementById('db_opcao').disabled = false;
	      parent.document.getElementById('mensagem').innerHTML = '';
	    }
         ";
  }
  echo "
        </script>
       ";
}
?>
