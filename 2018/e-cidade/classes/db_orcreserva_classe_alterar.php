<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

//MODULO: orcamento
//CLASSE DA ENTIDADE orcreserva
class cl_orcreserva { 
   // cria variaveis de erro 
   var $rotulo     = null; 
   var $query_sql  = null; 
   var $numrows    = 0; 
   var $erro_status= null; 
   var $erro_sql   = null; 
   var $erro_banco = null;  
   var $erro_msg   = null;  
   var $erro_campo = null;  
   var $pagina_retorno = null; 
   // cria variaveis do arquivo 
   var $o80_codres = 0; 
   var $o80_anousu = 0; 
   var $o80_coddot = 0; 
   var $o80_dtfim_dia = null; 
   var $o80_dtfim_mes = null; 
   var $o80_dtfim_ano = null; 
   var $o80_dtfim = null; 
   var $o80_dtini_dia = null; 
   var $o80_dtini_mes = null; 
   var $o80_dtini_ano = null; 
   var $o80_dtini = null; 
   var $o80_dtlanc_dia = null; 
   var $o80_dtlanc_mes = null; 
   var $o80_dtlanc_ano = null; 
   var $o80_dtlanc = null; 
   var $o80_valor = 0; 
   var $o80_descr = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 o80_codres = int8 = Código 
                 o80_anousu = int4 = Exercício 
                 o80_coddot = int4 = Código da Dotação 
                 o80_dtfim = date = Data Final 
                 o80_dtini = date = Data Início 
                 o80_dtlanc = date = Data lançamento 
                 o80_valor = float8 = Valor da Reserva 
                 o80_descr = text = Descrição 
                 ";
   //funcao construtor da classe 
   function cl_orcreserva() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("orcreserva"); 
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
       $this->o80_codres = ($this->o80_codres == ""?@$GLOBALS["HTTP_POST_VARS"]["o80_codres"]:$this->o80_codres);
       $this->o80_anousu = ($this->o80_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["o80_anousu"]:$this->o80_anousu);
       $this->o80_coddot = ($this->o80_coddot == ""?@$GLOBALS["HTTP_POST_VARS"]["o80_coddot"]:$this->o80_coddot);
       if($this->o80_dtfim == ""){
         $this->o80_dtfim_dia = ($this->o80_dtfim_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["o80_dtfim_dia"]:$this->o80_dtfim_dia);
         $this->o80_dtfim_mes = ($this->o80_dtfim_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["o80_dtfim_mes"]:$this->o80_dtfim_mes);
         $this->o80_dtfim_ano = ($this->o80_dtfim_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["o80_dtfim_ano"]:$this->o80_dtfim_ano);
         if($this->o80_dtfim_dia != ""){
            $this->o80_dtfim = $this->o80_dtfim_ano."-".$this->o80_dtfim_mes."-".$this->o80_dtfim_dia;
         }
       }
       if($this->o80_dtini == ""){
         $this->o80_dtini_dia = ($this->o80_dtini_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["o80_dtini_dia"]:$this->o80_dtini_dia);
         $this->o80_dtini_mes = ($this->o80_dtini_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["o80_dtini_mes"]:$this->o80_dtini_mes);
         $this->o80_dtini_ano = ($this->o80_dtini_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["o80_dtini_ano"]:$this->o80_dtini_ano);
         if($this->o80_dtini_dia != ""){
            $this->o80_dtini = $this->o80_dtini_ano."-".$this->o80_dtini_mes."-".$this->o80_dtini_dia;
         }
       }
       if($this->o80_dtlanc == ""){
         $this->o80_dtlanc_dia = ($this->o80_dtlanc_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["o80_dtlanc_dia"]:$this->o80_dtlanc_dia);
         $this->o80_dtlanc_mes = ($this->o80_dtlanc_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["o80_dtlanc_mes"]:$this->o80_dtlanc_mes);
         $this->o80_dtlanc_ano = ($this->o80_dtlanc_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["o80_dtlanc_ano"]:$this->o80_dtlanc_ano);
         if($this->o80_dtlanc_dia != ""){
            $this->o80_dtlanc = $this->o80_dtlanc_ano."-".$this->o80_dtlanc_mes."-".$this->o80_dtlanc_dia;
         }
       }
       $this->o80_valor = ($this->o80_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["o80_valor"]:$this->o80_valor);
       $this->o80_descr = ($this->o80_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["o80_descr"]:$this->o80_descr);
     }else{
       $this->o80_codres = ($this->o80_codres == ""?@$GLOBALS["HTTP_POST_VARS"]["o80_codres"]:$this->o80_codres);
     }
   }
   // funcao para inclusao
   function incluir ($o80_codres){ 
      $this->atualizacampos();
     if($this->o80_anousu == null ){ 
       $this->erro_sql = " Campo Exercício nao Informado.";
       $this->erro_campo = "o80_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o80_coddot == null ){ 
       $this->erro_sql = " Campo Código da Dotação nao Informado.";
       $this->erro_campo = "o80_coddot";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o80_dtfim == null ){ 
       $this->erro_sql = " Campo Data Final nao Informado.";
       $this->erro_campo = "o80_dtfim_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o80_dtini == null ){ 
       $this->erro_sql = " Campo Data Início nao Informado.";
       $this->erro_campo = "o80_dtini_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o80_dtlanc == null ){ 
       $this->erro_sql = " Campo Data lançamento nao Informado.";
       $this->erro_campo = "o80_dtlanc_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o80_valor == null ){ 
       $this->erro_sql = " Campo Valor da Reserva nao Informado.";
       $this->erro_campo = "o80_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o80_descr == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "o80_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($o80_codres == "" || $o80_codres == null ){
       $result = @pg_query("select nextval('orcreserva_o80_codres_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: orcreserva_o80_codres_seq do campo: o80_codres"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->o80_codres = pg_result($result,0,0); 
     }else{
       $result = @pg_query("select last_value from orcreserva_o80_codres_seq");
       if(($result != false) && (pg_result($result,0,0) < $o80_codres)){
         $this->erro_sql = " Campo o80_codres maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->o80_codres = $o80_codres; 
       }
     }
     if(($this->o80_codres == null) || ($this->o80_codres == "") ){ 
       $this->erro_sql = " Campo o80_codres nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into orcreserva(
                                       o80_codres 
                                      ,o80_anousu 
                                      ,o80_coddot 
                                      ,o80_dtfim 
                                      ,o80_dtini 
                                      ,o80_dtlanc 
                                      ,o80_valor 
                                      ,o80_descr 
                       )
                values (
                                $this->o80_codres 
                               ,$this->o80_anousu 
                               ,$this->o80_coddot 
                               ,".($this->o80_dtfim == "null" || $this->o80_dtfim == ""?"null":"'".$this->o80_dtfim."'")." 
                               ,".($this->o80_dtini == "null" || $this->o80_dtini == ""?"null":"'".$this->o80_dtini."'")." 
                               ,".($this->o80_dtlanc == "null" || $this->o80_dtlanc == ""?"null":"'".$this->o80_dtlanc."'")." 
                               ,$this->o80_valor 
                               ,'$this->o80_descr' 
                      )";

     $result = @pg_exec($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Reserva de Saldo ($this->o80_codres) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Reserva de Saldo já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Reserva de Saldo ($this->o80_codres) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o80_codres;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $resaco = $this->sql_record($this->sql_query_file($this->o80_codres));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = pg_query("insert into db_acountkey values($acount,5313,'$this->o80_codres','I')");
       $resac = pg_query("insert into db_acount values($acount,788,5313,'','".AddSlashes(pg_result($resaco,0,'o80_codres'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,788,5306,'','".AddSlashes(pg_result($resaco,0,'o80_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,788,5307,'','".AddSlashes(pg_result($resaco,0,'o80_coddot'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,788,5310,'','".AddSlashes(pg_result($resaco,0,'o80_dtfim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,788,5309,'','".AddSlashes(pg_result($resaco,0,'o80_dtini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,788,5308,'','".AddSlashes(pg_result($resaco,0,'o80_dtlanc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,788,5311,'','".AddSlashes(pg_result($resaco,0,'o80_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,788,5312,'','".AddSlashes(pg_result($resaco,0,'o80_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($o80_codres=null) { 
      $this->atualizacampos();
     $sql = " update orcreserva set ";
     $virgula = "";
     if(trim($this->o80_codres)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o80_codres"])){ 
       $sql  .= $virgula." o80_codres = $this->o80_codres ";
       $virgula = ",";
       if(trim($this->o80_codres) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "o80_codres";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o80_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o80_anousu"])){ 
       $sql  .= $virgula." o80_anousu = $this->o80_anousu ";
       $virgula = ",";
       if(trim($this->o80_anousu) == null ){ 
         $this->erro_sql = " Campo Exercício nao Informado.";
         $this->erro_campo = "o80_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o80_coddot)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o80_coddot"])){ 
       $sql  .= $virgula." o80_coddot = $this->o80_coddot ";
       $virgula = ",";
       if(trim($this->o80_coddot) == null ){ 
         $this->erro_sql = " Campo Código da Dotação nao Informado.";
         $this->erro_campo = "o80_coddot";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o80_dtfim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o80_dtfim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["o80_dtfim_dia"] !="") ){ 
       $sql  .= $virgula." o80_dtfim = '$this->o80_dtfim' ";
       $virgula = ",";
       if(trim($this->o80_dtfim) == null ){ 
         $this->erro_sql = " Campo Data Final nao Informado.";
         $this->erro_campo = "o80_dtfim_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["o80_dtfim_dia"])){ 
         $sql  .= $virgula." o80_dtfim = null ";
         $virgula = ",";
         if(trim($this->o80_dtfim) == null ){ 
           $this->erro_sql = " Campo Data Final nao Informado.";
           $this->erro_campo = "o80_dtfim_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->o80_dtini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o80_dtini_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["o80_dtini_dia"] !="") ){ 
       $sql  .= $virgula." o80_dtini = '$this->o80_dtini' ";
       $virgula = ",";
       if(trim($this->o80_dtini) == null ){ 
         $this->erro_sql = " Campo Data Início nao Informado.";
         $this->erro_campo = "o80_dtini_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["o80_dtini_dia"])){ 
         $sql  .= $virgula." o80_dtini = null ";
         $virgula = ",";
         if(trim($this->o80_dtini) == null ){ 
           $this->erro_sql = " Campo Data Início nao Informado.";
           $this->erro_campo = "o80_dtini_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->o80_dtlanc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o80_dtlanc_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["o80_dtlanc_dia"] !="") ){ 
       $sql  .= $virgula." o80_dtlanc = '$this->o80_dtlanc' ";
       $virgula = ",";
       if(trim($this->o80_dtlanc) == null ){ 
         $this->erro_sql = " Campo Data lançamento nao Informado.";
         $this->erro_campo = "o80_dtlanc_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["o80_dtlanc_dia"])){ 
         $sql  .= $virgula." o80_dtlanc = null ";
         $virgula = ",";
         if(trim($this->o80_dtlanc) == null ){ 
           $this->erro_sql = " Campo Data lançamento nao Informado.";
           $this->erro_campo = "o80_dtlanc_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->o80_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o80_valor"])){ 
       $sql  .= $virgula." o80_valor = $this->o80_valor ";
       $virgula = ",";
       if(trim($this->o80_valor) == null ){ 
         $this->erro_sql = " Campo Valor da Reserva nao Informado.";
         $this->erro_campo = "o80_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o80_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o80_descr"])){ 
       $sql  .= $virgula." o80_descr = '$this->o80_descr' ";
       $virgula = ",";
       if(trim($this->o80_descr) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "o80_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where  o80_codres = $this->o80_codres
";
     $resaco = $this->sql_record($this->sql_query_file($this->o80_codres));
     if($this->numrows>0){       $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = pg_query("insert into db_acountkey values($acount,5313,'$this->o80_codres','A')");
       if(isset($GLOBALS["HTTP_POST_VARS"]["o80_codres"]))
         $resac = pg_query("insert into db_acount values($acount,788,5313,'".AddSlashes(pg_result($resaco,0,'o80_codres'))."','$this->o80_codres',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["o80_anousu"]))
         $resac = pg_query("insert into db_acount values($acount,788,5306,'".AddSlashes(pg_result($resaco,0,'o80_anousu'))."','$this->o80_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["o80_coddot"]))
         $resac = pg_query("insert into db_acount values($acount,788,5307,'".AddSlashes(pg_result($resaco,0,'o80_coddot'))."','$this->o80_coddot',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["o80_dtfim"]))
         $resac = pg_query("insert into db_acount values($acount,788,5310,'".AddSlashes(pg_result($resaco,0,'o80_dtfim'))."','$this->o80_dtfim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["o80_dtini"]))
         $resac = pg_query("insert into db_acount values($acount,788,5309,'".AddSlashes(pg_result($resaco,0,'o80_dtini'))."','$this->o80_dtini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["o80_dtlanc"]))
         $resac = pg_query("insert into db_acount values($acount,788,5308,'".AddSlashes(pg_result($resaco,0,'o80_dtlanc'))."','$this->o80_dtlanc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["o80_valor"]))
         $resac = pg_query("insert into db_acount values($acount,788,5311,'".AddSlashes(pg_result($resaco,0,'o80_valor'))."','$this->o80_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["o80_descr"]))
         $resac = pg_query("insert into db_acount values($acount,788,5312,'".AddSlashes(pg_result($resaco,0,'o80_descr'))."','$this->o80_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     $result = @pg_exec($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Reserva de Saldo nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->o80_codres;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Reserva de Saldo nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->o80_codres;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o80_codres;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($o80_codres=null) { 
     $resaco = $this->sql_record($this->sql_query_file($o80_codres));
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = pg_query("insert into db_acountkey values($acount,5313,'".pg_result($resaco,$iresaco,'o80_codres')."','E')");
         $resac = pg_query("insert into db_acount values($acount,788,5313,'','".AddSlashes(pg_result($resaco,$iresaco,'o80_codres'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,788,5306,'','".AddSlashes(pg_result($resaco,$iresaco,'o80_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,788,5307,'','".AddSlashes(pg_result($resaco,$iresaco,'o80_coddot'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,788,5310,'','".AddSlashes(pg_result($resaco,$iresaco,'o80_dtfim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,788,5309,'','".AddSlashes(pg_result($resaco,$iresaco,'o80_dtini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,788,5308,'','".AddSlashes(pg_result($resaco,$iresaco,'o80_dtlanc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,788,5311,'','".AddSlashes(pg_result($resaco,$iresaco,'o80_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,788,5312,'','".AddSlashes(pg_result($resaco,$iresaco,'o80_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from orcreserva
                    where ";
     $sql2 = "";
      if($o80_codres != ""){
      if($sql2!=""){
        $sql2 .= " and ";
      }
      $sql2 .= " o80_codres = $o80_codres ";
}
     $result = @pg_exec($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Reserva de Saldo nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$o80_codres;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Reserva de Saldo nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$o80_codres;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$o80_codres;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       } 
     } 
   } 
   // funcao do recordset 
   function sql_record($sql) { 
     $result = @pg_query($sql);
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
        $this->erro_sql   = "Record Vazio na Tabela:orcreserva";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $o80_codres=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcreserva ";
     $sql .= "      inner join orcdotacao  on  orcdotacao.o58_anousu = orcreserva.o80_anousu and  orcdotacao.o58_coddot = orcreserva.o80_coddot";
     $sql .= "      inner join db_config  on  db_config.codigo = orcdotacao.o58_instit";
     $sql .= "      inner join orctiporec  on  orctiporec.o15_codigo = orcdotacao.o58_codigo";
     $sql .= "      inner join orcfuncao  on  orcfuncao.o52_funcao = orcdotacao.o58_funcao";
     $sql .= "      inner join orcsubfuncao  on  orcsubfuncao.o53_subfuncao = orcdotacao.o58_subfuncao";
     $sql .= "      inner join orcprograma  on  orcprograma.o54_anousu = orcdotacao.o58_anousu and  orcprograma.o54_programa = orcdotacao.o58_programa";
     $sql .= "      inner join orcelemento  on  orcelemento.o56_codele = orcdotacao.o58_codele and orcelemento.o56_anousu = orcdotacao.o58_anousu";
     $sql .= "      inner join orcprojativ  on  orcprojativ.o55_anousu = orcdotacao.o58_anousu and  orcprojativ.o55_projativ = orcdotacao.o58_projativ";
     $sql .= "      inner join orcorgao  on  orcorgao.o40_anousu = orcdotacao.o58_anousu and  orcorgao.o40_orgao = orcdotacao.o58_orgao";
     $sql .= "      inner join orcunidade  on  orcunidade.o41_anousu = orcdotacao.o58_anousu and  orcunidade.o41_orgao = orcdotacao.o58_orgao and  orcunidade.o41_unidade = orcdotacao.o58_unidade";
     $sql .= "      left outer join orcreservaaut on o83_codres= o80_codres";

     $sql2 = "";
     if($dbwhere==""){
       if($o80_codres!=null ){
         $sql2 .= " where orcreserva.o80_codres = $o80_codres "; 
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
   function sql_query_file ( $o80_codres=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcreserva ";
     $sql2 = "";
     if($dbwhere==""){
       if($o80_codres!=null ){
         $sql2 .= " where orcreserva.o80_codres = $o80_codres "; 
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
   function atualiza_valor($codres,$valor){
              $sql ="update orcreserva
                         set o80_valor=$valor
                         where o80_codres=$codres";
             @pg_exec($sql);
    }
}
?>