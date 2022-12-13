<?
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("libs/db_libcontabilidade.php");
include("classes/db_orcfontes_classe.php");
include("classes/db_orcelemento_classe.php");
include("classes/db_contranslr_classe.php");
include("classes/db_conlancam_classe.php");
include("classes/db_conlancamval_classe.php");
include("classes/db_conlancamdoc_classe.php");
include("classes/db_conlancamlr_classe.php");
include("classes/db_conplanoreduz_classe.php");
include("classes/db_conplanoexe_classe.php");
include("classes/db_conplano_classe.php");
include("classes/db_conplanoconta_classe.php");
include("dbforms/db_conplano.php");
include("dbforms/db_classesgenericas.php");

$cltranslan = new cl_translan;
$cltranslr = new cl_contranslr;
$clconlancam = new cl_conlancam;
$clconlancamdoc = new cl_conlancamdoc;
$clconlancamval = new cl_conlancamval;
$clconlancamlr = new cl_conlancamlr;
$clconplanoreduz = new cl_conplanoreduz;
$clconplanoexe = new cl_conplanoexe;
$cliframe_seleciona = new cl_iframe_seleciona;
$db_conplano = new db_conplano;
// dbforms
$clconplano = new cl_conplano;
$clconplanoconta = new cl_conplanoconta;
$clorcfontes = new cl_orcfontes;
$clorcelemento = new cl_orcelemento;

db_postmemory($HTTP_POST_VARS);
$debug = false;

// colocar + script do paulo aqui .. virada anual
$anousu = db_getsession("DB_anousu");
$dt_ini = $anousu.'-01-01';
$dt_fin = $anousu.'-12-31';
$sqlerro = false;
$doc = "";
$db_opcao = 22;
$db_botao = false;
$erro = false;

// inclusão do conplanoconta
if (isset($Processa_bancos) && ($Processa_bancos == 'Processar')) {
  // obtem uma matriz de chaves
  $chaves = split('#', $chaves);
  if (count($chaves) > 0) {
    for ($i = 0; $i < count($chaves); $i ++) {
      db_inicio_transacao();
      if ($chaves[$i] == "") {
        continue;
      }
      $res = $clconplanoconta->sql_record($clconplanoconta->sql_query($chaves[$i], db_getsession("DB_anousu") - 1));
      if (($clconplanoconta->numrows) > 0) {
        db_fieldsmemory($res, 0);
        $clconplanoconta->c63_banco = "$c63_banco";
        $clconplanoconta->c63_agencia = "$c63_agencia";
        $clconplanoconta->c63_conta = "$c63_conta";
        $clconplanoconta->c63_dvconta = "$c63_dvconta";
        $clconplanoconta->c63_dvagencia = "$c63_dvagencia";
        $clconplanoconta->incluir($c63_codcon, db_getsession("DB_anousu"));
        if ($clconplanoconta->erro_status == '0') {
          db_msgbox($clconplanoconta->erro_msg);
          $erro = true;
          break;
        }
      }
      db_fim_transacao($erro);
    }
    // END FOR
  }
  // END IF
}

// processa contas sintéticas
if (isset($Processa_sintetica) && ($Processa_sintetica == 'Processar')) {
  // obtem uma matriz de chaves
  $chaves = split('#', $chaves);
  if (count($chaves) > 0) {
    
    for ($i = 0; $i < count($chaves); $i ++) {
      
      db_inicio_transacao();
      // seleciona dados do reduzido,   (codcon)
      $res = $clconplano->sql_record($clconplano->sql_query_file($chaves[$i], (db_getsession("DB_anousu") - 1)));
      //echo "<br><br>";
      //echo($clconplano->sql_query_file($chaves[$i], (db_getsession("DB_anousu") - 1)));
      if (($clconplano->numrows) > 0) {
        db_fieldsmemory($res, 0);
        
        $clconplano->c60_anousu = db_getsession("DB_anousu");
        $clconplano->c60_estrut = $c60_estrut;
        $clconplano->c60_descr = $c60_descr;
        $clconplano->c60_codsis = $c60_codsis;
        $clconplano->c60_codcla = $c60_codcla;
        $clconplano->incluir($c60_codcon, $clconplano->c60_anousu);
        if ($clconplano->erro_status == 0) {
          db_msgbox("Plano ".$clconplano->erro_msg);
          $erro = true;
          break;
        }
        
        //rotina que verifica quando é para incluir no orcelemento ou no orcfontes
        $codigo = $c60_estrut;
        $arr_tipo = array("orcelemento" => "3", "orcfontes" => "4");
        if (substr($codigo, 0, 1) == $arr_tipo["orcelemento"] ) {
          
          $res = $clorcelemento->sql_record($clorcelemento->sql_query_file($c60_codcon,db_getsession("DB_anousu")));
          if ($clorcelemento->numrows == 0 ) {
            $clorcelemento->o56_codele = $c60_codcon;
            $clorcelemento->o56_anousu = $anousu;
            $clorcelemento->o56_elemento = substr($codigo, 0, 13);
            $clorcelemento->o56_descr = $c60_descr;
            $clorcelemento->o56_finali = $c60_finali;
            $clorcelemento->o56_orcado = 'true';
            $clorcelemento->incluir($c60_codcon, $anousu);
            $erro_msg = $clorcelemento->erro_msg;
            if ($clorcelemento->erro_status == 0) {
              $sqlerro = true;
              db_msgbox("Elementos da Despesa. ".$clorcelemento->erro_msg);
            }
          }
        } else if (substr($codigo, 0, 1) == $arr_tipo["orcfontes"] ) {
          
          $res = $clorcfontes->sql_record($clorcfontes->sql_query_file($c60_codcon,db_getsession("DB_anousu")));
          if ($clorcfontes->numrows == 0 ) {
            $clorcfontes->o57_codfon = $c60_codcon;
            $clorcfontes->o57_anousu = $anousu;
            $clorcfontes->o57_fonte = $codigo;
            $clorcfontes->o57_descr = $c60_descr;
            $clorcfontes->o57_finali = $c60_finali;
            $clorcfontes->incluir($c60_codcon, $anousu);
            $erro_msg = $clorcfontes->erro_msg;
            if ($clorcfontes->erro_status == 0) {
              $sqlerro = true;
              db_msgbox("Fontes de Receita. ".$clorcfontes->erro_msg);
            }
          }
        }
        
      }
      db_fim_transacao($erro);
    }
  }
}



// processa contas analiticas
if (isset($Processa_analitica) && ($Processa_analitica == 'Processar')) {
  // obtem uma matriz de chaves
  $chaves = split('#', $chaves);
  if (count($chaves) > 0) {
    for ($i = 0; $i < count($chaves); $i ++) {
      db_inicio_transacao();
      
      // seleciona dados do reduzido,   (codcon)
      $res = $clconplanoreduz->sql_record($clconplanoreduz->sql_query($chaves[$i], (db_getsession("DB_anousu") - 1)));
      if (($clconplanoreduz->numrows) > 0) {
        db_fieldsmemory($res, 0);
        
        
        //----------------//--------------
        $clconplanoreduz->c61_codcon = $c61_codcon;
        $clconplanoreduz->c61_anousu = db_getsession("DB_anousu");
        $clconplanoreduz->c61_instit = $c61_instit;
        $clconplanoreduz->c61_codigo = $c61_codigo;
        $clconplanoreduz->c61_contrapartida = $c61_contrapartida;
        $res = $clconplanoreduz->incluir($c61_reduz, db_getsession("DB_anousu"));
        //die(var_dump($clconplanoreduz));
        if ($clconplanoreduz->erro_status == 0) {
          db_msgbox("Reduzido :".$clconplanoreduz->erro_msg);
          $erro = true;
        }
        
        $clconplanoexe->c62_anousu = db_getsession("DB_anousu");
        $clconplanoexe->c62_reduz   = $c61_reduz;
        $clconplanoexe->c62_codrec = $c61_codigo;
        $clconplanoexe->c62_vlrcre = '0';
        $clconplanoexe->c62_vlrdeb = '0';
        $clconplanoexe->incluir(db_getsession("DB_anousu"), $c61_reduz);
        $erro_msg = $clconplanoexe->erro_msg;
        if ($clconplanoexe->erro_status == 0) {
          db_msgbox("Exercício :".$clconplanoexe->erro_msg);
          $erro = true;
        }
        
        $db_opcao = 1;
        // efetua inserção nas transações de receita, despesa..etc
        $db_conplano->evento($clconplanoreduz->c61_reduz, $c61_contrapartida, db_getsession("DB_anousu"), $clconplanoreduz->c61_instit);
        // ---------------------------
        
      }
      db_fim_transacao($erro);
    }
  }
}

if (isset($Processa_saldos) && ($Processa_saldos == 'Processar')) {
  $ano = (db_getsession("DB_anousu") - 1);
  $dtini = $ano.'-01-01';
  $dtfim = $ano.'-12-31';
  
  $chaves = split('#', $chaves);
  if (count($chaves) > 0) {
    
    for ($i = 0; $i < count($chaves); $i ++) {
      if ($chaves[$i]=="") {
        continue;
      }
      $sql  = " select c61_reduz, ";
      $sql .= "        c61_codigo, ";
      $sql .= "	       round(substr(fc_planosaldonovo,3,14)::float8,2)::float8 as saldo_anterior, ";
      $sql .= "	       round(substr(fc_planosaldonovo,17,14)::float8,2)::float8 as saldo_anterior_debito, ";
      $sql .= "	       round(substr(fc_planosaldonovo,31,14)::float8,2)::float8 as saldo_anterior_credito, ";
      $sql .= "	       round(substr(fc_planosaldonovo,45,14)::float8,2)::float8 as saldo_final, ";
      $sql .= "	       substr(fc_planosaldonovo,59,1)::varchar(1) as sinal_anterior, ";
      $sql .= "	       substr(fc_planosaldonovo,60,1)::varchar(1) as sinal_final ";
      $sql .= "   from (select c61_reduz, ";
      $sql .= "	               c61_codcon, ";
      $sql .= "	               c61_codigo, ";
      $sql .= "                fc_planosaldonovo(c61_anousu,c61_reduz ,'$dtini','$dtfim',true) ";
      $sql .= "           from conplanoreduz ";
      $sql .= "             where c61_reduz = ".$chaves[$i]."   and ";
      $sql .= "                   c61_anousu =". (db_getsession("DB_anousu") - 1)."   and  ";
      $sql .= "                   c61_instit = ".db_getsession("DB_instit");
      $sql .= "	 ) as x      ";
      $res = @db_query($sql) or die("ERRO SQL: $sql");
      
      if (pg_numrows($res) > 0) {
        for ($x = 0; $x < pg_numrows($res); $x++) {
          
          db_inicio_transacao();
          
          db_fieldsmemory($res, $x);
          
          $clconplanoexe->c62_anousu = db_getsession("DB_anousu");
          $clconplanoexe->c62_reduz = $c61_reduz;
          $clconplanoexe->c62_codrec = $c61_codigo;
          if ($sinal_final == 'C') {
            $clconplanoexe->c62_vlrcre = $saldo_final;
            $clconplanoexe->c62_vlrdeb = "0";
          } else if ($sinal_final == 'D') {
            $clconplanoexe->c62_vlrdeb = $saldo_final;
            $clconplanoexe->c62_vlrcre = "0";
          } else {
            $clconplanoexe->c62_vlrcre = "0";
            $clconplanoexe->c62_vlrdeb = "0";
          }
          $clconplanoexe->alterar(db_getsession("DB_anousu"), $c61_reduz);
          if ($clconplanoexe->erro_status == "0") {
            $erro = true;
            db_msgbox($clconplanoexe->erro_msg);
          }
          
          db_fim_transacao($erro);
        }
      }
    }
    
  }
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<br><br>
<form name="form1" action="" method="POST">
<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#CCCCCC">
  <tr>
   <td>
     <table border=0 align=center>
     <tr>
       <td colspan=3><h3>Abertura de Exercício </h3></td>
     </tr>    
     <tr>
       <td colspan=3><b>Plano de Contas do exercício anterior </b> </td>
     </tr>
       <tr>
       <td width=40px> &nbsp; </td>
       <td>Importar contas sintéticas </td>
       <td><input type=submit value='Selecionar' name='processa_sintetica' ></td>
     </tr>
     <tr>
       <td width=40px> &nbsp; </td>
       <td>Importar contas analíticas </td>
       <td><input type=submit value='Selecionar' name='processa_plano' ></td>
     </tr>     
     <tr>
       <td width=40px> &nbsp; </td>
       <td>Importar contas bancárias </td>
       <td><input type=submit value='Selecionar' name='processa_bancos'></td>
     </tr>     
     <tr>
       <td colspan=3><b>Implantação de saldos</b> </td>
     </tr>
     <tr>
       <td width=40px> &nbsp; </td>
       <td>Importar saldos para contas do exercício  </td>
       <td><input type=submit name='processa_saldos' value='Selecionar'></td>
     </tr>   
    </table>
   </td>
  </tr>
</table>    

<?
    if (isset ($processa_sintetica) && $processa_sintetica == "Selecionar") {   
         $sql = "select c60_codcon, c60_estrut, trim(c60_descr) as c60_descr
                      from conplano
                      where c60_anousu=".(db_getsession("DB_anousu")-1)."                       
                      EXCEPT
                      select c60_codcon, c60_estrut,  trim(c60_descr) as c60_descr
                      from conplano
                      where c60_anousu=".db_getsession("DB_anousu")."
                      order by c60_estrut
                     ";
		$sql_marca = "";
		$cliframe_seleciona->campos = "c60_codcon,c60_estrut,c60_descr";
		$cliframe_seleciona->legenda = "Contas Sintéticas";
		$cliframe_seleciona->sql = $sql;
		$cliframe_seleciona->sql_marca = $sql_marca;
		$cliframe_seleciona->iframe_height = "200";
		$cliframe_seleciona->iframe_width = "100%";
		$cliframe_seleciona->iframe_nome = "cta_analitica";
		$cliframe_seleciona->chaves = "c60_codcon";
		$cliframe_seleciona->iframe_seleciona(1);
		
        ?> <table border=0 width=100%>
              <tr><td width="100%" align="center" ><input type="button"  name="Processa_sintetica"   value="Processar" onClick="js_sintetica();"> </td></tr>
              <table>               
        <?        
    }
	if (isset ($processa_plano) && $processa_plano == "Selecionar") {
		
		$sql ="select c61_anousu,c61_reduz,c60_estrut, trim(c60_descr) as c60_descr
                   from conplanoreduz
                                inner join conplano on c61_anousu=c60_anousu and c61_codcon=c60_codcon
                  where c60_anousu=".(db_getsession("DB_anousu")-1)." and
                            c61_instit = ".db_getsession("DB_instit")."
                  EXCEPT
                  select ".(db_getsession("DB_anousu")-1)." as c61_anousu,c61_reduz,c60_estrut,trim(c60_descr) as c60_descr
                  from conplanoreduz
                        inner join conplano on c61_anousu=c60_anousu and c61_codcon=c60_codcon
                  where c60_anousu=".db_getsession("DB_anousu")." and
                             c61_instit = ".db_getsession("DB_instit")."
           
                  order by c60_estrut
                   ";
		//	die($sql);
		$sql_marca = "";
		$cliframe_seleciona->campos = "c61_anousu,c61_reduz,c60_estrut,c60_descr";
		$cliframe_seleciona->legenda = "Contas Analiticas";
		$cliframe_seleciona->sql = $sql;
		$cliframe_seleciona->sql_marca = $sql_marca;
		$cliframe_seleciona->iframe_height = "200";
		$cliframe_seleciona->iframe_width = "100%";
		$cliframe_seleciona->iframe_nome = "cta_analitica";
		$cliframe_seleciona->chaves = "c61_reduz";
		$cliframe_seleciona->iframe_seleciona(1);
?>
              <table border=0 width=100%>
              <tr><td width="100%" align="center" ><input type="button"  name="Processa_analitica"   value="Processar" onClick="js_enviar();"> </td></tr>
              <table>               
           <?


	}

	//---
	if (isset ($processa_bancos) && $processa_bancos == "Selecionar") {
		$sql = "select x.* 
		                 from  (select conplanoconta.*	
						 from conplanoconta 
							 inner join conplano on c60_codcon=c63_codcon and c60_anousu=c63_anousu
						 where	c63_anousu = ". (db_getsession("DB_anousu") - 1)."				
		                 ) as x  				
		                     left join conplanoconta cta on  cta.c63_codcon=x.c63_codcon and cta.c63_anousu=".db_getsession("DB_anousu")."
		                 where
		    				 cta.c63_codcon is null
						";
		$sql_marca = "";
		$cliframe_seleciona->campos = "c63_codcon,c63_anousu,c63_banco,c63_conta,c63_dvconta,c63_agencia,c63_dvagencia";
		$cliframe_seleciona->legenda = "Contas Bancárias";
		$cliframe_seleciona->sql = $sql;
		$cliframe_seleciona->sql_marca = $sql_marca;
		$cliframe_seleciona->iframe_height = "200";
		$cliframe_seleciona->iframe_width = "100%";
		$cliframe_seleciona->iframe_nome = "cta_conplanoconta";
		$cliframe_seleciona->chaves = "c63_codcon";
		$cliframe_seleciona->iframe_seleciona(1);
?>
     <table border=0 width=100%>
     <tr><td width="100%" align="center" ><input type="button"  name="processa_bancos"   value="Processar" onClick="js_conplanoconta();"> </td></tr>
     <table>               
     <?


	}

	//---
	if (isset ($processa_saldos) && $processa_saldos == "Selecionar") {
        $ano = (db_getsession("DB_anousu") - 1);
	    $dtini = $ano.'-01-01';
	    $dtfim = $ano.'-12-31';

		$sql = "select
							    c60_estrut,
							    c60_anousu,
							    c61_reduz,
		                        exe.c62_anousu,    
							    exe.c62_vlrdeb,
							    exe.c62_vlrcre,
                                round(substr(fc_planosaldonovo,45,14)::float8,2)::float8 as c68_saldo
				 from (
						select c60_estrut,
                                   c60_anousu,
                                   c61_reduz,						    
                                   fc_planosaldonovo(c60_anousu,c61_reduz ,'$dtini','$dtfim',true)
						from conplanoexe                            
						    inner join conplanoreduz on c61_reduz=c62_reduz and c61_anousu=c62_anousu
						    inner join conplano on c60_codcon=c61_codcon and c60_anousu=c61_anousu
						where c61_anousu=". (db_getsession("DB_anousu") - 1)." and c61_instit=".db_getsession("DB_instit")." and substr(c60_estrut,1,1) in ('1','2','5','6','7','8')
						group by c60_estrut,c60_anousu,c61_reduz
				  ) as x
				        inner join conplanoexe exeant on exeant.c62_anousu = c60_anousu  and exeant.c62_reduz=c61_reduz
		                inner join conplanoexe exe on exe.c62_anousu = ".db_getsession("DB_anousu")."  and exe.c62_reduz=c61_reduz
		          order by c60_estrut
		          ";
		$sql_marca = "";
		$cliframe_seleciona->campos = "c60_estrut,c60_anousu,c61_reduz,  c68_saldo,c62_anousu,c62_vlrdeb,c62_vlrcre";
		$cliframe_seleciona->legenda = "Importação de Saldo";
		$cliframe_seleciona->sql = $sql;
		$cliframe_seleciona->sql_marca = $sql_marca;
		$cliframe_seleciona->iframe_height = "200";
		$cliframe_seleciona->iframe_width = "100%";
		$cliframe_seleciona->iframe_nome = "cta_saldos";
		$cliframe_seleciona->chaves = "c61_reduz";
		$cliframe_seleciona->iframe_seleciona(1);
?>
     <table border=0 width=100%>
     <tr><td width="100%" align="center" ><input type="button"  name="processa_saldos"   value="Processar" onClick="js_saldos();"> </td></tr>
     <table>               
     <?


	}
?>



</form>




<?



	db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
?>
<script>
function js_sintetica(){
  js_gera_chaves();
  // cria um objeto que indica o tipo de processamento
  obj=document.createElement('input');
  obj.setAttribute('name','Processa_sintetica');  
  obj.setAttribute('type','hidden');
  obj.setAttribute('value','Processar');
  document.form1.appendChild(obj);
  // submete o formulario
  document.form1.submit();
}
function js_enviar(){
  js_gera_chaves();
  // cria um objeto que indica o tipo de processamento
  obj=document.createElement('input');
  obj.setAttribute('name','Processa_analitica');  
  obj.setAttribute('type','hidden');
  obj.setAttribute('value','Processar');
  document.form1.appendChild(obj);
  // submete o formulario
  document.form1.submit();
}
function js_conplanoconta(){
  js_gera_chaves();
  // cria um objeto que indica o tipo de processamento
  obj=document.createElement('input');
  obj.setAttribute('name','Processa_bancos');  
  obj.setAttribute('type','hidden');
  obj.setAttribute('value','Processar');
  document.form1.appendChild(obj);
  // submete o formulario
  document.form1.submit();
}
function js_saldos(){
  js_gera_chaves();
  // cria um objeto que indica o tipo de processamento
  obj=document.createElement('input');
  obj.setAttribute('name','Processa_saldos');  
  obj.setAttribute('type','hidden');
  obj.setAttribute('value','Processar');
  document.form1.appendChild(obj);
  // submete o formulario
  document.form1.submit();
}

</script>

</body>
</html>
