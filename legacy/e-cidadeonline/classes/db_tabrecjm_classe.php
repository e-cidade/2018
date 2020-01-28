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

//MODULO: caixa
//CLASSE DA ENTIDADE tabrecjm
class cl_tabrecjm { 
   // cria variaveis de erro 
   var $rotulo     = null; 
   var $query_sql  = null; 
   var $numrows    = 0; 
   var $numrows_incluir = 0; 
   var $numrows_alterar = 0; 
   var $numrows_excluir = 0; 
   var $erro_status= null; 
   var $erro_sql   = null; 
   var $erro_banco = null;  
   var $erro_msg   = null;  
   var $erro_campo = null;  
   var $pagina_retorno = null; 
   // cria variaveis do arquivo 
   var $k02_codjm = 0; 
   var $k02_corr = null; 
   var $k02_juros = 0; 
   var $k02_desco1 = 0; 
   var $k02_desco2 = 0; 
   var $k02_desco3 = 0; 
   var $k02_desco4 = 0; 
   var $k02_desco5 = 0; 
   var $k02_desco6 = 0; 
   var $k02_dtdes1_dia = null; 
   var $k02_dtdes1_mes = null; 
   var $k02_dtdes1_ano = null; 
   var $k02_dtdes1 = null; 
   var $k02_dtdes2_dia = null; 
   var $k02_dtdes2_mes = null; 
   var $k02_dtdes2_ano = null; 
   var $k02_dtdes2 = null; 
   var $k02_dtdes3_dia = null; 
   var $k02_dtdes3_mes = null; 
   var $k02_dtdes3_ano = null; 
   var $k02_dtdes3 = null; 
   var $k02_dtdes4_dia = null; 
   var $k02_dtdes4_mes = null; 
   var $k02_dtdes4_ano = null; 
   var $k02_dtdes4 = null; 
   var $k02_dtdes5_dia = null; 
   var $k02_dtdes5_mes = null; 
   var $k02_dtdes5_ano = null; 
   var $k02_dtdes5 = null; 
   var $k02_dtdes6_dia = null; 
   var $k02_dtdes6_mes = null; 
   var $k02_dtdes6_ano = null; 
   var $k02_dtdes6 = null; 
   var $k02_integr = 'f'; 
   var $k02_dtfrac_dia = null; 
   var $k02_dtfrac_mes = null; 
   var $k02_dtfrac_ano = null; 
   var $k02_dtfrac = null; 
   var $k02_mulfra = 0; 
   var $k02_limmul = 0; 
   var $k02_jurpar = 0; 
   var $k02_desjm = 'f'; 
   var $k02_caldes = 'f'; 
   var $k02_jurdia = 'f'; 
   var $k02_juracu = 'f'; 
   var $k02_corven = 'f'; 
   var $k02_instit = 0; 
   var $k02_sabdom = 'f'; 
   var $lixojur = 0; 
   var $lixomul = 0; 
   var $k02_jurparate = 0; 
   var $k02_juroslimite = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 k02_codjm = int4 = Código do Juro e Multa 
                 k02_corr = varchar(5) = inflator para correcao 
                 k02_juros = float8 = juros 
                 k02_desco1 = float8 = desconto 1 
                 k02_desco2 = float8 = desconto 2 
                 k02_desco3 = float8 = desconto 3 
                 k02_desco4 = float8 = desconto 4 
                 k02_desco5 = float8 = desconto 5 
                 k02_desco6 = float8 = desconto 6 
                 k02_dtdes1 = date = Desc. Parcelado 
                 k02_dtdes2 = date = Desc. Parcelado 
                 k02_dtdes3 = date = Desc. Parcelado 
                 k02_dtdes4 = date = Desc. Única 
                 k02_dtdes5 = date = Desc. Única 
                 k02_dtdes6 = date = Desc. Única 
                 k02_integr = bool = Desconto Integral 
                 k02_dtfrac = date = Multa Fracionada 
                 k02_mulfra = float8 = Fração Dia 
                 k02_limmul = float8 = Limite 
                 k02_jurpar = float8 = juros parcela 
                 k02_desjm = bool = desconto juro e multa 
                 k02_caldes = bool = Desconto após o vencimento 
                 k02_jurdia = bool = juros por dia 
                 k02_juracu = bool = juros cumulativo 
                 k02_corven = bool = Correção Vencimento 
                 k02_instit = int4 = instituicao 
                 k02_sabdom = bool = sabado e domingo 
                 lixojur = int8 = Taxa de Lixo 
                 lixomul = int8 = Taxa de multa 
                 k02_jurparate = int4 = Calcular juros de financiamento ate 
                 k02_juroslimite = float4 = Limite de Juros 
                 ";
   //funcao construtor da classe 
   function cl_tabrecjm() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("tabrecjm"); 
     $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
   }
   //funcao erro 
   function erro($mostra,$retorna) { 
     if(($this->erro_status == "0") || ($mostra == true && $this->erro_status != null )){
        echo "<script>alert(\"".$this->erro_msg."\");</script>";
        if($retorna==true){
           echo "<script>location.href='".$this->pagina_retorno."'</script>";
        }
     }
   }
   // funcao para atualizar campos
   function atualizacampos($exclusao=false) {
     if($exclusao==false){
       $this->k02_codjm = ($this->k02_codjm == ""?@$GLOBALS["HTTP_POST_VARS"]["k02_codjm"]:$this->k02_codjm);
       $this->k02_corr = ($this->k02_corr == ""?@$GLOBALS["HTTP_POST_VARS"]["k02_corr"]:$this->k02_corr);
       $this->k02_juros = ($this->k02_juros == ""?@$GLOBALS["HTTP_POST_VARS"]["k02_juros"]:$this->k02_juros);
       $this->k02_desco1 = ($this->k02_desco1 == ""?@$GLOBALS["HTTP_POST_VARS"]["k02_desco1"]:$this->k02_desco1);
       $this->k02_desco2 = ($this->k02_desco2 == ""?@$GLOBALS["HTTP_POST_VARS"]["k02_desco2"]:$this->k02_desco2);
       $this->k02_desco3 = ($this->k02_desco3 == ""?@$GLOBALS["HTTP_POST_VARS"]["k02_desco3"]:$this->k02_desco3);
       $this->k02_desco4 = ($this->k02_desco4 == ""?@$GLOBALS["HTTP_POST_VARS"]["k02_desco4"]:$this->k02_desco4);
       $this->k02_desco5 = ($this->k02_desco5 == ""?@$GLOBALS["HTTP_POST_VARS"]["k02_desco5"]:$this->k02_desco5);
       $this->k02_desco6 = ($this->k02_desco6 == ""?@$GLOBALS["HTTP_POST_VARS"]["k02_desco6"]:$this->k02_desco6);
       if($this->k02_dtdes1 == ""){
         $this->k02_dtdes1_dia = ($this->k02_dtdes1_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k02_dtdes1_dia"]:$this->k02_dtdes1_dia);
         $this->k02_dtdes1_mes = ($this->k02_dtdes1_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k02_dtdes1_mes"]:$this->k02_dtdes1_mes);
         $this->k02_dtdes1_ano = ($this->k02_dtdes1_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k02_dtdes1_ano"]:$this->k02_dtdes1_ano);
         if($this->k02_dtdes1_dia != ""){
            $this->k02_dtdes1 = $this->k02_dtdes1_ano."-".$this->k02_dtdes1_mes."-".$this->k02_dtdes1_dia;
         }
       }
       if($this->k02_dtdes2 == ""){
         $this->k02_dtdes2_dia = ($this->k02_dtdes2_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k02_dtdes2_dia"]:$this->k02_dtdes2_dia);
         $this->k02_dtdes2_mes = ($this->k02_dtdes2_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k02_dtdes2_mes"]:$this->k02_dtdes2_mes);
         $this->k02_dtdes2_ano = ($this->k02_dtdes2_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k02_dtdes2_ano"]:$this->k02_dtdes2_ano);
         if($this->k02_dtdes2_dia != ""){
            $this->k02_dtdes2 = $this->k02_dtdes2_ano."-".$this->k02_dtdes2_mes."-".$this->k02_dtdes2_dia;
         }
       }
       if($this->k02_dtdes3 == ""){
         $this->k02_dtdes3_dia = ($this->k02_dtdes3_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k02_dtdes3_dia"]:$this->k02_dtdes3_dia);
         $this->k02_dtdes3_mes = ($this->k02_dtdes3_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k02_dtdes3_mes"]:$this->k02_dtdes3_mes);
         $this->k02_dtdes3_ano = ($this->k02_dtdes3_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k02_dtdes3_ano"]:$this->k02_dtdes3_ano);
         if($this->k02_dtdes3_dia != ""){
            $this->k02_dtdes3 = $this->k02_dtdes3_ano."-".$this->k02_dtdes3_mes."-".$this->k02_dtdes3_dia;
         }
       }
       if($this->k02_dtdes4 == ""){
         $this->k02_dtdes4_dia = ($this->k02_dtdes4_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k02_dtdes4_dia"]:$this->k02_dtdes4_dia);
         $this->k02_dtdes4_mes = ($this->k02_dtdes4_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k02_dtdes4_mes"]:$this->k02_dtdes4_mes);
         $this->k02_dtdes4_ano = ($this->k02_dtdes4_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k02_dtdes4_ano"]:$this->k02_dtdes4_ano);
         if($this->k02_dtdes4_dia != ""){
            $this->k02_dtdes4 = $this->k02_dtdes4_ano."-".$this->k02_dtdes4_mes."-".$this->k02_dtdes4_dia;
         }
       }
       if($this->k02_dtdes5 == ""){
         $this->k02_dtdes5_dia = ($this->k02_dtdes5_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k02_dtdes5_dia"]:$this->k02_dtdes5_dia);
         $this->k02_dtdes5_mes = ($this->k02_dtdes5_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k02_dtdes5_mes"]:$this->k02_dtdes5_mes);
         $this->k02_dtdes5_ano = ($this->k02_dtdes5_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k02_dtdes5_ano"]:$this->k02_dtdes5_ano);
         if($this->k02_dtdes5_dia != ""){
            $this->k02_dtdes5 = $this->k02_dtdes5_ano."-".$this->k02_dtdes5_mes."-".$this->k02_dtdes5_dia;
         }
       }
       if($this->k02_dtdes6 == ""){
         $this->k02_dtdes6_dia = ($this->k02_dtdes6_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k02_dtdes6_dia"]:$this->k02_dtdes6_dia);
         $this->k02_dtdes6_mes = ($this->k02_dtdes6_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k02_dtdes6_mes"]:$this->k02_dtdes6_mes);
         $this->k02_dtdes6_ano = ($this->k02_dtdes6_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k02_dtdes6_ano"]:$this->k02_dtdes6_ano);
         if($this->k02_dtdes6_dia != ""){
            $this->k02_dtdes6 = $this->k02_dtdes6_ano."-".$this->k02_dtdes6_mes."-".$this->k02_dtdes6_dia;
         }
       }
       $this->k02_integr = ($this->k02_integr == "f"?@$GLOBALS["HTTP_POST_VARS"]["k02_integr"]:$this->k02_integr);
       if($this->k02_dtfrac == ""){
         $this->k02_dtfrac_dia = ($this->k02_dtfrac_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k02_dtfrac_dia"]:$this->k02_dtfrac_dia);
         $this->k02_dtfrac_mes = ($this->k02_dtfrac_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k02_dtfrac_mes"]:$this->k02_dtfrac_mes);
         $this->k02_dtfrac_ano = ($this->k02_dtfrac_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k02_dtfrac_ano"]:$this->k02_dtfrac_ano);
         if($this->k02_dtfrac_dia != ""){
            $this->k02_dtfrac = $this->k02_dtfrac_ano."-".$this->k02_dtfrac_mes."-".$this->k02_dtfrac_dia;
         }
       }
       $this->k02_mulfra = ($this->k02_mulfra == ""?@$GLOBALS["HTTP_POST_VARS"]["k02_mulfra"]:$this->k02_mulfra);
       $this->k02_limmul = ($this->k02_limmul == ""?@$GLOBALS["HTTP_POST_VARS"]["k02_limmul"]:$this->k02_limmul);
       $this->k02_jurpar = ($this->k02_jurpar == ""?@$GLOBALS["HTTP_POST_VARS"]["k02_jurpar"]:$this->k02_jurpar);
       $this->k02_desjm = ($this->k02_desjm == "f"?@$GLOBALS["HTTP_POST_VARS"]["k02_desjm"]:$this->k02_desjm);
       $this->k02_caldes = ($this->k02_caldes == "f"?@$GLOBALS["HTTP_POST_VARS"]["k02_caldes"]:$this->k02_caldes);
       $this->k02_jurdia = ($this->k02_jurdia == "f"?@$GLOBALS["HTTP_POST_VARS"]["k02_jurdia"]:$this->k02_jurdia);
       $this->k02_juracu = ($this->k02_juracu == "f"?@$GLOBALS["HTTP_POST_VARS"]["k02_juracu"]:$this->k02_juracu);
       $this->k02_corven = ($this->k02_corven == "f"?@$GLOBALS["HTTP_POST_VARS"]["k02_corven"]:$this->k02_corven);
       $this->k02_instit = ($this->k02_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["k02_instit"]:$this->k02_instit);
       $this->k02_sabdom = ($this->k02_sabdom == "f"?@$GLOBALS["HTTP_POST_VARS"]["k02_sabdom"]:$this->k02_sabdom);
       $this->lixojur = ($this->lixojur == ""?@$GLOBALS["HTTP_POST_VARS"]["lixojur"]:$this->lixojur);
       $this->lixomul = ($this->lixomul == ""?@$GLOBALS["HTTP_POST_VARS"]["lixomul"]:$this->lixomul);
       $this->k02_jurparate = ($this->k02_jurparate == ""?@$GLOBALS["HTTP_POST_VARS"]["k02_jurparate"]:$this->k02_jurparate);
       $this->k02_juroslimite = ($this->k02_juroslimite == ""?@$GLOBALS["HTTP_POST_VARS"]["k02_juroslimite"]:$this->k02_juroslimite);
     }else{
       $this->k02_codjm = ($this->k02_codjm == ""?@$GLOBALS["HTTP_POST_VARS"]["k02_codjm"]:$this->k02_codjm);
     }
   }
   // funcao para inclusao
   function incluir ($k02_codjm){ 
      $this->atualizacampos();
     if($this->k02_corr == null ){ 
       $this->erro_sql = " Campo inflator para correcao nao Informado.";
       $this->erro_campo = "k02_corr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k02_juros == null ){ 
       $this->erro_sql = " Campo juros nao Informado.";
       $this->erro_campo = "k02_juros";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k02_desco1 == null ){ 
       $this->k02_desco1 = "0";
     }
     if($this->k02_desco2 == null ){ 
       $this->k02_desco2 = "0";
     }
     if($this->k02_desco3 == null ){ 
       $this->k02_desco3 = "0";
     }
     if($this->k02_desco4 == null ){ 
       $this->k02_desco4 = "0";
     }
     if($this->k02_desco5 == null ){ 
       $this->k02_desco5 = "0";
     }
     if($this->k02_desco6 == null ){ 
       $this->k02_desco6 = "0";
     }
     if($this->k02_integr == null ){ 
       $this->k02_integr = "f";
     }
     if($this->k02_mulfra == null ){ 
       $this->k02_mulfra = "0";
     }
     if($this->k02_limmul == null ){ 
       $this->k02_limmul = "0";
     }
     if($this->k02_jurpar == null ){ 
       $this->k02_jurpar = "0";
     }
     if($this->k02_caldes == null ){ 
       $this->k02_caldes = "f";
     }
     if($this->k02_jurdia == null ){ 
       $this->k02_jurdia = "f";
     }
     if($this->k02_juracu == null ){ 
       $this->k02_juracu = "f";
     }
     if($this->k02_corven == null ){ 
       $this->k02_corven = "f";
     }
     if($this->k02_instit == null ){ 
       $this->k02_instit = "0";
     }
     if($this->k02_sabdom == null ){ 
       $this->k02_sabdom = "f";
     }
     if($this->lixojur == null ){ 
       $this->lixojur = "0";
     }
     if($this->lixomul == null ){ 
       $this->lixomul = "0";
     }
     if($this->k02_jurparate == null ){ 
       $this->k02_jurparate = "0";
     }
     if($this->k02_juroslimite == null ){ 
       $this->k02_juroslimite = "0";
     }
     if($k02_codjm == "" || $k02_codjm == null ){
       $result = db_query("select nextval('tabrecjm_k02_codjm_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: tabrecjm_k02_codjm_seq do campo: k02_codjm"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->k02_codjm = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from tabrecjm_k02_codjm_seq");
       if(($result != false) && (pg_result($result,0,0) < $k02_codjm)){
         $this->erro_sql = " Campo k02_codjm maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->k02_codjm = $k02_codjm; 
       }
     }
     if(($this->k02_codjm == null) || ($this->k02_codjm == "") ){ 
       $this->erro_sql = " Campo k02_codjm nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into tabrecjm(
                                       k02_codjm 
                                      ,k02_corr 
                                      ,k02_juros 
                                      ,k02_desco1 
                                      ,k02_desco2 
                                      ,k02_desco3 
                                      ,k02_desco4 
                                      ,k02_desco5 
                                      ,k02_desco6 
                                      ,k02_dtdes1 
                                      ,k02_dtdes2 
                                      ,k02_dtdes3 
                                      ,k02_dtdes4 
                                      ,k02_dtdes5 
                                      ,k02_dtdes6 
                                      ,k02_integr 
                                      ,k02_dtfrac 
                                      ,k02_mulfra 
                                      ,k02_limmul 
                                      ,k02_jurpar 
                                      ,k02_desjm 
                                      ,k02_caldes 
                                      ,k02_jurdia 
                                      ,k02_juracu 
                                      ,k02_corven 
                                      ,k02_instit 
                                      ,k02_sabdom 
                                      ,lixojur 
                                      ,lixomul 
                                      ,k02_jurparate 
                                      ,k02_juroslimite 
                       )
                values (
                                $this->k02_codjm 
                               ,'$this->k02_corr' 
                               ,$this->k02_juros 
                               ,$this->k02_desco1 
                               ,$this->k02_desco2 
                               ,$this->k02_desco3 
                               ,$this->k02_desco4 
                               ,$this->k02_desco5 
                               ,$this->k02_desco6 
                               ,".($this->k02_dtdes1 == "null" || $this->k02_dtdes1 == ""?"null":"'".$this->k02_dtdes1."'")." 
                               ,".($this->k02_dtdes2 == "null" || $this->k02_dtdes2 == ""?"null":"'".$this->k02_dtdes2."'")." 
                               ,".($this->k02_dtdes3 == "null" || $this->k02_dtdes3 == ""?"null":"'".$this->k02_dtdes3."'")." 
                               ,".($this->k02_dtdes4 == "null" || $this->k02_dtdes4 == ""?"null":"'".$this->k02_dtdes4."'")." 
                               ,".($this->k02_dtdes5 == "null" || $this->k02_dtdes5 == ""?"null":"'".$this->k02_dtdes5."'")." 
                               ,".($this->k02_dtdes6 == "null" || $this->k02_dtdes6 == ""?"null":"'".$this->k02_dtdes6."'")." 
                               ,'$this->k02_integr' 
                               ,".($this->k02_dtfrac == "null" || $this->k02_dtfrac == ""?"null":"'".$this->k02_dtfrac."'")." 
                               ,$this->k02_mulfra 
                               ,$this->k02_limmul 
                               ,$this->k02_jurpar 
                               ,'$this->k02_desjm' 
                               ,'$this->k02_caldes' 
                               ,'$this->k02_jurdia' 
                               ,'$this->k02_juracu' 
                               ,'$this->k02_corven' 
                               ,$this->k02_instit 
                               ,'$this->k02_sabdom' 
                               ,$this->lixojur 
                               ,$this->lixomul 
                               ,$this->k02_jurparate 
                               ,$this->k02_juroslimite 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = " ($this->k02_codjm) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = " já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = " ($this->k02_codjm) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k02_codjm;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->k02_codjm));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,386,'$this->k02_codjm','I')");
       $resac = db_query("insert into db_acount values($acount,76,386,'','".AddSlashes(pg_result($resaco,0,'k02_codjm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,76,387,'','".AddSlashes(pg_result($resaco,0,'k02_corr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,76,388,'','".AddSlashes(pg_result($resaco,0,'k02_juros'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,76,395,'','".AddSlashes(pg_result($resaco,0,'k02_desco1'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,76,396,'','".AddSlashes(pg_result($resaco,0,'k02_desco2'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,76,397,'','".AddSlashes(pg_result($resaco,0,'k02_desco3'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,76,398,'','".AddSlashes(pg_result($resaco,0,'k02_desco4'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,76,399,'','".AddSlashes(pg_result($resaco,0,'k02_desco5'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,76,400,'','".AddSlashes(pg_result($resaco,0,'k02_desco6'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,76,401,'','".AddSlashes(pg_result($resaco,0,'k02_dtdes1'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,76,402,'','".AddSlashes(pg_result($resaco,0,'k02_dtdes2'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,76,403,'','".AddSlashes(pg_result($resaco,0,'k02_dtdes3'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,76,404,'','".AddSlashes(pg_result($resaco,0,'k02_dtdes4'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,76,405,'','".AddSlashes(pg_result($resaco,0,'k02_dtdes5'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,76,406,'','".AddSlashes(pg_result($resaco,0,'k02_dtdes6'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,76,413,'','".AddSlashes(pg_result($resaco,0,'k02_integr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,76,414,'','".AddSlashes(pg_result($resaco,0,'k02_dtfrac'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,76,415,'','".AddSlashes(pg_result($resaco,0,'k02_mulfra'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,76,416,'','".AddSlashes(pg_result($resaco,0,'k02_limmul'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,76,417,'','".AddSlashes(pg_result($resaco,0,'k02_jurpar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,76,418,'','".AddSlashes(pg_result($resaco,0,'k02_desjm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,76,419,'','".AddSlashes(pg_result($resaco,0,'k02_caldes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,76,420,'','".AddSlashes(pg_result($resaco,0,'k02_jurdia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,76,421,'','".AddSlashes(pg_result($resaco,0,'k02_juracu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,76,422,'','".AddSlashes(pg_result($resaco,0,'k02_corven'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,76,423,'','".AddSlashes(pg_result($resaco,0,'k02_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,76,424,'','".AddSlashes(pg_result($resaco,0,'k02_sabdom'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,76,7378,'','".AddSlashes(pg_result($resaco,0,'lixojur'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,76,7379,'','".AddSlashes(pg_result($resaco,0,'lixomul'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,76,9581,'','".AddSlashes(pg_result($resaco,0,'k02_jurparate'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,76,18933,'','".AddSlashes(pg_result($resaco,0,'k02_juroslimite'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($k02_codjm=null) { 
      $this->atualizacampos();
     $sql = " update tabrecjm set ";
     $virgula = "";
     if(trim($this->k02_codjm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k02_codjm"])){ 
       $sql  .= $virgula." k02_codjm = $this->k02_codjm ";
       $virgula = ",";
       if(trim($this->k02_codjm) == null ){ 
         $this->erro_sql = " Campo Código do Juro e Multa nao Informado.";
         $this->erro_campo = "k02_codjm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k02_corr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k02_corr"])){ 
       $sql  .= $virgula." k02_corr = '$this->k02_corr' ";
       $virgula = ",";
       if(trim($this->k02_corr) == null ){ 
         $this->erro_sql = " Campo inflator para correcao nao Informado.";
         $this->erro_campo = "k02_corr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k02_juros)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k02_juros"])){ 
       $sql  .= $virgula." k02_juros = $this->k02_juros ";
       $virgula = ",";
       if(trim($this->k02_juros) == null ){ 
         $this->erro_sql = " Campo juros nao Informado.";
         $this->erro_campo = "k02_juros";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k02_desco1)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k02_desco1"])){ 
        if(trim($this->k02_desco1)=="" && isset($GLOBALS["HTTP_POST_VARS"]["k02_desco1"])){ 
           $this->k02_desco1 = "0" ; 
        } 
       $sql  .= $virgula." k02_desco1 = $this->k02_desco1 ";
       $virgula = ",";
     }
     if(trim($this->k02_desco2)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k02_desco2"])){ 
        if(trim($this->k02_desco2)=="" && isset($GLOBALS["HTTP_POST_VARS"]["k02_desco2"])){ 
           $this->k02_desco2 = "0" ; 
        } 
       $sql  .= $virgula." k02_desco2 = $this->k02_desco2 ";
       $virgula = ",";
     }
     if(trim($this->k02_desco3)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k02_desco3"])){ 
        if(trim($this->k02_desco3)=="" && isset($GLOBALS["HTTP_POST_VARS"]["k02_desco3"])){ 
           $this->k02_desco3 = "0" ; 
        } 
       $sql  .= $virgula." k02_desco3 = $this->k02_desco3 ";
       $virgula = ",";
     }
     if(trim($this->k02_desco4)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k02_desco4"])){ 
        if(trim($this->k02_desco4)=="" && isset($GLOBALS["HTTP_POST_VARS"]["k02_desco4"])){ 
           $this->k02_desco4 = "0" ; 
        } 
       $sql  .= $virgula." k02_desco4 = $this->k02_desco4 ";
       $virgula = ",";
     }
     if(trim($this->k02_desco5)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k02_desco5"])){ 
        if(trim($this->k02_desco5)=="" && isset($GLOBALS["HTTP_POST_VARS"]["k02_desco5"])){ 
           $this->k02_desco5 = "0" ; 
        } 
       $sql  .= $virgula." k02_desco5 = $this->k02_desco5 ";
       $virgula = ",";
     }
     if(trim($this->k02_desco6)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k02_desco6"])){ 
        if(trim($this->k02_desco6)=="" && isset($GLOBALS["HTTP_POST_VARS"]["k02_desco6"])){ 
           $this->k02_desco6 = "0" ; 
        } 
       $sql  .= $virgula." k02_desco6 = $this->k02_desco6 ";
       $virgula = ",";
     }
     if(trim($this->k02_dtdes1)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k02_dtdes1_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k02_dtdes1_dia"] !="") ){ 
       $sql  .= $virgula." k02_dtdes1 = '$this->k02_dtdes1' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["k02_dtdes1_dia"])){ 
         $sql  .= $virgula." k02_dtdes1 = null ";
         $virgula = ",";
       }
     }
     if(trim($this->k02_dtdes2)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k02_dtdes2_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k02_dtdes2_dia"] !="") ){ 
       $sql  .= $virgula." k02_dtdes2 = '$this->k02_dtdes2' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["k02_dtdes2_dia"])){ 
         $sql  .= $virgula." k02_dtdes2 = null ";
         $virgula = ",";
       }
     }
     if(trim($this->k02_dtdes3)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k02_dtdes3_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k02_dtdes3_dia"] !="") ){ 
       $sql  .= $virgula." k02_dtdes3 = '$this->k02_dtdes3' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["k02_dtdes3_dia"])){ 
         $sql  .= $virgula." k02_dtdes3 = null ";
         $virgula = ",";
       }
     }
     if(trim($this->k02_dtdes4)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k02_dtdes4_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k02_dtdes4_dia"] !="") ){ 
       $sql  .= $virgula." k02_dtdes4 = '$this->k02_dtdes4' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["k02_dtdes4_dia"])){ 
         $sql  .= $virgula." k02_dtdes4 = null ";
         $virgula = ",";
       }
     }
     if(trim($this->k02_dtdes5)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k02_dtdes5_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k02_dtdes5_dia"] !="") ){ 
       $sql  .= $virgula." k02_dtdes5 = '$this->k02_dtdes5' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["k02_dtdes5_dia"])){ 
         $sql  .= $virgula." k02_dtdes5 = null ";
         $virgula = ",";
       }
     }
     if(trim($this->k02_dtdes6)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k02_dtdes6_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k02_dtdes6_dia"] !="") ){ 
       $sql  .= $virgula." k02_dtdes6 = '$this->k02_dtdes6' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["k02_dtdes6_dia"])){ 
         $sql  .= $virgula." k02_dtdes6 = null ";
         $virgula = ",";
       }
     }
     if(trim($this->k02_integr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k02_integr"])){ 
       $sql  .= $virgula." k02_integr = '$this->k02_integr' ";
       $virgula = ",";
     }
     if(trim($this->k02_dtfrac)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k02_dtfrac_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k02_dtfrac_dia"] !="") ){ 
       $sql  .= $virgula." k02_dtfrac = '$this->k02_dtfrac' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["k02_dtfrac_dia"])){ 
         $sql  .= $virgula." k02_dtfrac = null ";
         $virgula = ",";
       }
     }
     if(trim($this->k02_mulfra)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k02_mulfra"])){ 
        if(trim($this->k02_mulfra)=="" && isset($GLOBALS["HTTP_POST_VARS"]["k02_mulfra"])){ 
           $this->k02_mulfra = "0" ; 
        } 
       $sql  .= $virgula." k02_mulfra = $this->k02_mulfra ";
       $virgula = ",";
     }
     if(trim($this->k02_limmul)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k02_limmul"])){ 
        if(trim($this->k02_limmul)=="" && isset($GLOBALS["HTTP_POST_VARS"]["k02_limmul"])){ 
           $this->k02_limmul = "0" ; 
        } 
       $sql  .= $virgula." k02_limmul = $this->k02_limmul ";
       $virgula = ",";
     }
     if(trim($this->k02_jurpar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k02_jurpar"])){ 
        if(trim($this->k02_jurpar)=="" && isset($GLOBALS["HTTP_POST_VARS"]["k02_jurpar"])){ 
           $this->k02_jurpar = "0" ; 
        } 
       $sql  .= $virgula." k02_jurpar = $this->k02_jurpar ";
       $virgula = ",";
     }
     if(trim($this->k02_desjm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k02_desjm"])){ 
       $sql  .= $virgula." k02_desjm = '$this->k02_desjm' ";
       $virgula = ",";
     }
     if(trim($this->k02_caldes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k02_caldes"])){ 
       $sql  .= $virgula." k02_caldes = '$this->k02_caldes' ";
       $virgula = ",";
     }
     if(trim($this->k02_jurdia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k02_jurdia"])){ 
       $sql  .= $virgula." k02_jurdia = '$this->k02_jurdia' ";
       $virgula = ",";
     }
     if(trim($this->k02_juracu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k02_juracu"])){ 
       $sql  .= $virgula." k02_juracu = '$this->k02_juracu' ";
       $virgula = ",";
     }
     if(trim($this->k02_corven)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k02_corven"])){ 
       $sql  .= $virgula." k02_corven = '$this->k02_corven' ";
       $virgula = ",";
     }
     if(trim($this->k02_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k02_instit"])){ 
        if(trim($this->k02_instit)=="" && isset($GLOBALS["HTTP_POST_VARS"]["k02_instit"])){ 
           $this->k02_instit = "0" ; 
        } 
       $sql  .= $virgula." k02_instit = $this->k02_instit ";
       $virgula = ",";
     }
     if(trim($this->k02_sabdom)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k02_sabdom"])){ 
       $sql  .= $virgula." k02_sabdom = '$this->k02_sabdom' ";
       $virgula = ",";
     }
     if(trim($this->lixojur)!="" || isset($GLOBALS["HTTP_POST_VARS"]["lixojur"])){ 
        if(trim($this->lixojur)=="" && isset($GLOBALS["HTTP_POST_VARS"]["lixojur"])){ 
           $this->lixojur = "0" ; 
        } 
       $sql  .= $virgula." lixojur = $this->lixojur ";
       $virgula = ",";
     }
     if(trim($this->lixomul)!="" || isset($GLOBALS["HTTP_POST_VARS"]["lixomul"])){ 
        if(trim($this->lixomul)=="" && isset($GLOBALS["HTTP_POST_VARS"]["lixomul"])){ 
           $this->lixomul = "0" ; 
        } 
       $sql  .= $virgula." lixomul = $this->lixomul ";
       $virgula = ",";
     }
     if(trim($this->k02_jurparate)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k02_jurparate"])){ 
        if(trim($this->k02_jurparate)=="" && isset($GLOBALS["HTTP_POST_VARS"]["k02_jurparate"])){ 
           $this->k02_jurparate = "0" ; 
        } 
       $sql  .= $virgula." k02_jurparate = $this->k02_jurparate ";
       $virgula = ",";
     }
     if(trim($this->k02_juroslimite)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k02_juroslimite"])){ 
        if(trim($this->k02_juroslimite)=="" && isset($GLOBALS["HTTP_POST_VARS"]["k02_juroslimite"])){ 
           $this->k02_juroslimite = "0" ; 
        } 
       $sql  .= $virgula." k02_juroslimite = $this->k02_juroslimite ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($k02_codjm!=null){
       $sql .= " k02_codjm = $this->k02_codjm";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->k02_codjm));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,386,'$this->k02_codjm','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k02_codjm"]) || $this->k02_codjm != "")
           $resac = db_query("insert into db_acount values($acount,76,386,'".AddSlashes(pg_result($resaco,$conresaco,'k02_codjm'))."','$this->k02_codjm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k02_corr"]) || $this->k02_corr != "")
           $resac = db_query("insert into db_acount values($acount,76,387,'".AddSlashes(pg_result($resaco,$conresaco,'k02_corr'))."','$this->k02_corr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k02_juros"]) || $this->k02_juros != "")
           $resac = db_query("insert into db_acount values($acount,76,388,'".AddSlashes(pg_result($resaco,$conresaco,'k02_juros'))."','$this->k02_juros',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k02_desco1"]) || $this->k02_desco1 != "")
           $resac = db_query("insert into db_acount values($acount,76,395,'".AddSlashes(pg_result($resaco,$conresaco,'k02_desco1'))."','$this->k02_desco1',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k02_desco2"]) || $this->k02_desco2 != "")
           $resac = db_query("insert into db_acount values($acount,76,396,'".AddSlashes(pg_result($resaco,$conresaco,'k02_desco2'))."','$this->k02_desco2',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k02_desco3"]) || $this->k02_desco3 != "")
           $resac = db_query("insert into db_acount values($acount,76,397,'".AddSlashes(pg_result($resaco,$conresaco,'k02_desco3'))."','$this->k02_desco3',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k02_desco4"]) || $this->k02_desco4 != "")
           $resac = db_query("insert into db_acount values($acount,76,398,'".AddSlashes(pg_result($resaco,$conresaco,'k02_desco4'))."','$this->k02_desco4',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k02_desco5"]) || $this->k02_desco5 != "")
           $resac = db_query("insert into db_acount values($acount,76,399,'".AddSlashes(pg_result($resaco,$conresaco,'k02_desco5'))."','$this->k02_desco5',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k02_desco6"]) || $this->k02_desco6 != "")
           $resac = db_query("insert into db_acount values($acount,76,400,'".AddSlashes(pg_result($resaco,$conresaco,'k02_desco6'))."','$this->k02_desco6',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k02_dtdes1"]) || $this->k02_dtdes1 != "")
           $resac = db_query("insert into db_acount values($acount,76,401,'".AddSlashes(pg_result($resaco,$conresaco,'k02_dtdes1'))."','$this->k02_dtdes1',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k02_dtdes2"]) || $this->k02_dtdes2 != "")
           $resac = db_query("insert into db_acount values($acount,76,402,'".AddSlashes(pg_result($resaco,$conresaco,'k02_dtdes2'))."','$this->k02_dtdes2',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k02_dtdes3"]) || $this->k02_dtdes3 != "")
           $resac = db_query("insert into db_acount values($acount,76,403,'".AddSlashes(pg_result($resaco,$conresaco,'k02_dtdes3'))."','$this->k02_dtdes3',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k02_dtdes4"]) || $this->k02_dtdes4 != "")
           $resac = db_query("insert into db_acount values($acount,76,404,'".AddSlashes(pg_result($resaco,$conresaco,'k02_dtdes4'))."','$this->k02_dtdes4',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k02_dtdes5"]) || $this->k02_dtdes5 != "")
           $resac = db_query("insert into db_acount values($acount,76,405,'".AddSlashes(pg_result($resaco,$conresaco,'k02_dtdes5'))."','$this->k02_dtdes5',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k02_dtdes6"]) || $this->k02_dtdes6 != "")
           $resac = db_query("insert into db_acount values($acount,76,406,'".AddSlashes(pg_result($resaco,$conresaco,'k02_dtdes6'))."','$this->k02_dtdes6',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k02_integr"]) || $this->k02_integr != "")
           $resac = db_query("insert into db_acount values($acount,76,413,'".AddSlashes(pg_result($resaco,$conresaco,'k02_integr'))."','$this->k02_integr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k02_dtfrac"]) || $this->k02_dtfrac != "")
           $resac = db_query("insert into db_acount values($acount,76,414,'".AddSlashes(pg_result($resaco,$conresaco,'k02_dtfrac'))."','$this->k02_dtfrac',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k02_mulfra"]) || $this->k02_mulfra != "")
           $resac = db_query("insert into db_acount values($acount,76,415,'".AddSlashes(pg_result($resaco,$conresaco,'k02_mulfra'))."','$this->k02_mulfra',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k02_limmul"]) || $this->k02_limmul != "")
           $resac = db_query("insert into db_acount values($acount,76,416,'".AddSlashes(pg_result($resaco,$conresaco,'k02_limmul'))."','$this->k02_limmul',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k02_jurpar"]) || $this->k02_jurpar != "")
           $resac = db_query("insert into db_acount values($acount,76,417,'".AddSlashes(pg_result($resaco,$conresaco,'k02_jurpar'))."','$this->k02_jurpar',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k02_desjm"]) || $this->k02_desjm != "")
           $resac = db_query("insert into db_acount values($acount,76,418,'".AddSlashes(pg_result($resaco,$conresaco,'k02_desjm'))."','$this->k02_desjm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k02_caldes"]) || $this->k02_caldes != "")
           $resac = db_query("insert into db_acount values($acount,76,419,'".AddSlashes(pg_result($resaco,$conresaco,'k02_caldes'))."','$this->k02_caldes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k02_jurdia"]) || $this->k02_jurdia != "")
           $resac = db_query("insert into db_acount values($acount,76,420,'".AddSlashes(pg_result($resaco,$conresaco,'k02_jurdia'))."','$this->k02_jurdia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k02_juracu"]) || $this->k02_juracu != "")
           $resac = db_query("insert into db_acount values($acount,76,421,'".AddSlashes(pg_result($resaco,$conresaco,'k02_juracu'))."','$this->k02_juracu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k02_corven"]) || $this->k02_corven != "")
           $resac = db_query("insert into db_acount values($acount,76,422,'".AddSlashes(pg_result($resaco,$conresaco,'k02_corven'))."','$this->k02_corven',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k02_instit"]) || $this->k02_instit != "")
           $resac = db_query("insert into db_acount values($acount,76,423,'".AddSlashes(pg_result($resaco,$conresaco,'k02_instit'))."','$this->k02_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k02_sabdom"]) || $this->k02_sabdom != "")
           $resac = db_query("insert into db_acount values($acount,76,424,'".AddSlashes(pg_result($resaco,$conresaco,'k02_sabdom'))."','$this->k02_sabdom',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["lixojur"]) || $this->lixojur != "")
           $resac = db_query("insert into db_acount values($acount,76,7378,'".AddSlashes(pg_result($resaco,$conresaco,'lixojur'))."','$this->lixojur',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["lixomul"]) || $this->lixomul != "")
           $resac = db_query("insert into db_acount values($acount,76,7379,'".AddSlashes(pg_result($resaco,$conresaco,'lixomul'))."','$this->lixomul',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k02_jurparate"]) || $this->k02_jurparate != "")
           $resac = db_query("insert into db_acount values($acount,76,9581,'".AddSlashes(pg_result($resaco,$conresaco,'k02_jurparate'))."','$this->k02_jurparate',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k02_juroslimite"]) || $this->k02_juroslimite != "")
           $resac = db_query("insert into db_acount values($acount,76,18933,'".AddSlashes(pg_result($resaco,$conresaco,'k02_juroslimite'))."','$this->k02_juroslimite',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k02_codjm;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k02_codjm;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k02_codjm;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($k02_codjm=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($k02_codjm));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,386,'$k02_codjm','E')");
         $resac = db_query("insert into db_acount values($acount,76,386,'','".AddSlashes(pg_result($resaco,$iresaco,'k02_codjm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,76,387,'','".AddSlashes(pg_result($resaco,$iresaco,'k02_corr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,76,388,'','".AddSlashes(pg_result($resaco,$iresaco,'k02_juros'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,76,395,'','".AddSlashes(pg_result($resaco,$iresaco,'k02_desco1'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,76,396,'','".AddSlashes(pg_result($resaco,$iresaco,'k02_desco2'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,76,397,'','".AddSlashes(pg_result($resaco,$iresaco,'k02_desco3'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,76,398,'','".AddSlashes(pg_result($resaco,$iresaco,'k02_desco4'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,76,399,'','".AddSlashes(pg_result($resaco,$iresaco,'k02_desco5'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,76,400,'','".AddSlashes(pg_result($resaco,$iresaco,'k02_desco6'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,76,401,'','".AddSlashes(pg_result($resaco,$iresaco,'k02_dtdes1'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,76,402,'','".AddSlashes(pg_result($resaco,$iresaco,'k02_dtdes2'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,76,403,'','".AddSlashes(pg_result($resaco,$iresaco,'k02_dtdes3'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,76,404,'','".AddSlashes(pg_result($resaco,$iresaco,'k02_dtdes4'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,76,405,'','".AddSlashes(pg_result($resaco,$iresaco,'k02_dtdes5'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,76,406,'','".AddSlashes(pg_result($resaco,$iresaco,'k02_dtdes6'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,76,413,'','".AddSlashes(pg_result($resaco,$iresaco,'k02_integr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,76,414,'','".AddSlashes(pg_result($resaco,$iresaco,'k02_dtfrac'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,76,415,'','".AddSlashes(pg_result($resaco,$iresaco,'k02_mulfra'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,76,416,'','".AddSlashes(pg_result($resaco,$iresaco,'k02_limmul'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,76,417,'','".AddSlashes(pg_result($resaco,$iresaco,'k02_jurpar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,76,418,'','".AddSlashes(pg_result($resaco,$iresaco,'k02_desjm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,76,419,'','".AddSlashes(pg_result($resaco,$iresaco,'k02_caldes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,76,420,'','".AddSlashes(pg_result($resaco,$iresaco,'k02_jurdia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,76,421,'','".AddSlashes(pg_result($resaco,$iresaco,'k02_juracu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,76,422,'','".AddSlashes(pg_result($resaco,$iresaco,'k02_corven'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,76,423,'','".AddSlashes(pg_result($resaco,$iresaco,'k02_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,76,424,'','".AddSlashes(pg_result($resaco,$iresaco,'k02_sabdom'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,76,7378,'','".AddSlashes(pg_result($resaco,$iresaco,'lixojur'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,76,7379,'','".AddSlashes(pg_result($resaco,$iresaco,'lixomul'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,76,9581,'','".AddSlashes(pg_result($resaco,$iresaco,'k02_jurparate'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,76,18933,'','".AddSlashes(pg_result($resaco,$iresaco,'k02_juroslimite'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from tabrecjm
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($k02_codjm != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k02_codjm = $k02_codjm ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k02_codjm;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k02_codjm;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$k02_codjm;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao do recordset 
   function sql_record($sql) { 
     $result = db_query($sql);
     if($result==false){
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:tabrecjm";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $k02_codjm=null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from tabrecjm ";
     $sql .= "      inner join inflan  on  inflan.i01_codigo = tabrecjm.k02_corr";
     $sql2 = "";
     if($dbwhere==""){
       if($k02_codjm!=null ){
         $sql2 .= " where tabrecjm.k02_codjm = $k02_codjm "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
   // funcao do sql 
   function sql_query_file ( $k02_codjm=null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from tabrecjm ";
     $sql2 = "";
     if($dbwhere==""){
       if($k02_codjm!=null ){
         $sql2 .= " where tabrecjm.k02_codjm = $k02_codjm "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
}
?>