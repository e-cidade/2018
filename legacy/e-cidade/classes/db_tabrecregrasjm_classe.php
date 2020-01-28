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

//MODULO: caixa
//CLASSE DA ENTIDADE tabrecregrasjm
class cl_tabrecregrasjm { 
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
   var $k04_sequencial = 0; 
   var $k04_receit = 0; 
   var $k04_codjm = 0; 
   var $k04_dtini_dia = null; 
   var $k04_dtini_mes = null; 
   var $k04_dtini_ano = null; 
   var $k04_dtini = null; 
   var $k04_dtfim_dia = null; 
   var $k04_dtfim_mes = null; 
   var $k04_dtfim_ano = null; 
   var $k04_dtfim = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 k04_sequencial = int4 = Sequencial 
                 k04_receit = int4 = Receita 
                 k04_codjm = int4 = codigo do juro e multa 
                 k04_dtini = date = Data inicial 
                 k04_dtfim = date = Data final 
                 ";
   //funcao construtor da classe 
   function cl_tabrecregrasjm() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("tabrecregrasjm"); 
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
       $this->k04_sequencial = ($this->k04_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k04_sequencial"]:$this->k04_sequencial);
       $this->k04_receit = ($this->k04_receit == ""?@$GLOBALS["HTTP_POST_VARS"]["k04_receit"]:$this->k04_receit);
       $this->k04_codjm = ($this->k04_codjm == ""?@$GLOBALS["HTTP_POST_VARS"]["k04_codjm"]:$this->k04_codjm);
       if($this->k04_dtini == ""){
         $this->k04_dtini_dia = ($this->k04_dtini_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k04_dtini_dia"]:$this->k04_dtini_dia);
         $this->k04_dtini_mes = ($this->k04_dtini_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k04_dtini_mes"]:$this->k04_dtini_mes);
         $this->k04_dtini_ano = ($this->k04_dtini_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k04_dtini_ano"]:$this->k04_dtini_ano);
         if($this->k04_dtini_dia != ""){
            $this->k04_dtini = $this->k04_dtini_ano."-".$this->k04_dtini_mes."-".$this->k04_dtini_dia;
         }
       }
       if($this->k04_dtfim == ""){
         $this->k04_dtfim_dia = ($this->k04_dtfim_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k04_dtfim_dia"]:$this->k04_dtfim_dia);
         $this->k04_dtfim_mes = ($this->k04_dtfim_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k04_dtfim_mes"]:$this->k04_dtfim_mes);
         $this->k04_dtfim_ano = ($this->k04_dtfim_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k04_dtfim_ano"]:$this->k04_dtfim_ano);
         if($this->k04_dtfim_dia != ""){
            $this->k04_dtfim = $this->k04_dtfim_ano."-".$this->k04_dtfim_mes."-".$this->k04_dtfim_dia;
         }
       }
     }else{
       $this->k04_sequencial = ($this->k04_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k04_sequencial"]:$this->k04_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($k04_sequencial){ 
      $this->atualizacampos();
     if($this->k04_receit == null ){ 
       $this->erro_sql = " Campo Receita nao Informado.";
       $this->erro_campo = "k04_receit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k04_codjm == null ){ 
       $this->erro_sql = " Campo codigo do juro e multa nao Informado.";
       $this->erro_campo = "k04_codjm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k04_dtini == null ){ 
       $this->erro_sql = " Campo Data inicial nao Informado.";
       $this->erro_campo = "k04_dtini_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k04_dtfim == null ){ 
       $this->erro_sql = " Campo Data final nao Informado.";
       $this->erro_campo = "k04_dtfim_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($k04_sequencial == "" || $k04_sequencial == null ){
       $result = db_query("select nextval('tabrecregrasjm_k04_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: tabrecregrasjm_k04_sequencial_seq do campo: k04_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->k04_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from tabrecregrasjm_k04_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $k04_sequencial)){
         $this->erro_sql = " Campo k04_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->k04_sequencial = $k04_sequencial; 
       }
     }
     if(($this->k04_sequencial == null) || ($this->k04_sequencial == "") ){ 
       $this->erro_sql = " Campo k04_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into tabrecregrasjm(
                                       k04_sequencial 
                                      ,k04_receit 
                                      ,k04_codjm 
                                      ,k04_dtini 
                                      ,k04_dtfim 
                       )
                values (
                                $this->k04_sequencial 
                               ,$this->k04_receit 
                               ,$this->k04_codjm 
                               ,".($this->k04_dtini == "null" || $this->k04_dtini == ""?"null":"'".$this->k04_dtini."'")." 
                               ,".($this->k04_dtfim == "null" || $this->k04_dtfim == ""?"null":"'".$this->k04_dtfim."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Regras de juro e multa por receita ($this->k04_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Regras de juro e multa por receita já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Regras de juro e multa por receita ($this->k04_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k04_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->k04_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,8243,'$this->k04_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,1389,8243,'','".AddSlashes(pg_result($resaco,0,'k04_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1389,8244,'','".AddSlashes(pg_result($resaco,0,'k04_receit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1389,8245,'','".AddSlashes(pg_result($resaco,0,'k04_codjm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1389,8246,'','".AddSlashes(pg_result($resaco,0,'k04_dtini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1389,8247,'','".AddSlashes(pg_result($resaco,0,'k04_dtfim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($k04_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update tabrecregrasjm set ";
     $virgula = "";
     if(trim($this->k04_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k04_sequencial"])){ 
       $sql  .= $virgula." k04_sequencial = $this->k04_sequencial ";
       $virgula = ",";
       if(trim($this->k04_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "k04_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k04_receit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k04_receit"])){ 
       $sql  .= $virgula." k04_receit = $this->k04_receit ";
       $virgula = ",";
       if(trim($this->k04_receit) == null ){ 
         $this->erro_sql = " Campo Receita nao Informado.";
         $this->erro_campo = "k04_receit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k04_codjm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k04_codjm"])){ 
       $sql  .= $virgula." k04_codjm = $this->k04_codjm ";
       $virgula = ",";
       if(trim($this->k04_codjm) == null ){ 
         $this->erro_sql = " Campo codigo do juro e multa nao Informado.";
         $this->erro_campo = "k04_codjm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k04_dtini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k04_dtini_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k04_dtini_dia"] !="") ){ 
       $sql  .= $virgula." k04_dtini = '$this->k04_dtini' ";
       $virgula = ",";
       if(trim($this->k04_dtini) == null ){ 
         $this->erro_sql = " Campo Data inicial nao Informado.";
         $this->erro_campo = "k04_dtini_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["k04_dtini_dia"])){ 
         $sql  .= $virgula." k04_dtini = null ";
         $virgula = ",";
         if(trim($this->k04_dtini) == null ){ 
           $this->erro_sql = " Campo Data inicial nao Informado.";
           $this->erro_campo = "k04_dtini_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->k04_dtfim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k04_dtfim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k04_dtfim_dia"] !="") ){ 
       $sql  .= $virgula." k04_dtfim = '$this->k04_dtfim' ";
       $virgula = ",";
       if(trim($this->k04_dtfim) == null ){ 
         $this->erro_sql = " Campo Data final nao Informado.";
         $this->erro_campo = "k04_dtfim_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["k04_dtfim_dia"])){ 
         $sql  .= $virgula." k04_dtfim = null ";
         $virgula = ",";
         if(trim($this->k04_dtfim) == null ){ 
           $this->erro_sql = " Campo Data final nao Informado.";
           $this->erro_campo = "k04_dtfim_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";
     if($k04_sequencial!=null){
       $sql .= " k04_sequencial = $this->k04_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->k04_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8243,'$this->k04_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k04_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,1389,8243,'".AddSlashes(pg_result($resaco,$conresaco,'k04_sequencial'))."','$this->k04_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k04_receit"]))
           $resac = db_query("insert into db_acount values($acount,1389,8244,'".AddSlashes(pg_result($resaco,$conresaco,'k04_receit'))."','$this->k04_receit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k04_codjm"]))
           $resac = db_query("insert into db_acount values($acount,1389,8245,'".AddSlashes(pg_result($resaco,$conresaco,'k04_codjm'))."','$this->k04_codjm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k04_dtini"]))
           $resac = db_query("insert into db_acount values($acount,1389,8246,'".AddSlashes(pg_result($resaco,$conresaco,'k04_dtini'))."','$this->k04_dtini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k04_dtfim"]))
           $resac = db_query("insert into db_acount values($acount,1389,8247,'".AddSlashes(pg_result($resaco,$conresaco,'k04_dtfim'))."','$this->k04_dtfim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Regras de juro e multa por receita nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k04_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Regras de juro e multa por receita nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k04_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k04_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($k04_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($k04_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8243,'$k04_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,1389,8243,'','".AddSlashes(pg_result($resaco,$iresaco,'k04_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1389,8244,'','".AddSlashes(pg_result($resaco,$iresaco,'k04_receit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1389,8245,'','".AddSlashes(pg_result($resaco,$iresaco,'k04_codjm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1389,8246,'','".AddSlashes(pg_result($resaco,$iresaco,'k04_dtini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1389,8247,'','".AddSlashes(pg_result($resaco,$iresaco,'k04_dtfim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from tabrecregrasjm
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($k04_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k04_sequencial = $k04_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Regras de juro e multa por receita nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k04_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Regras de juro e multa por receita nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k04_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$k04_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:tabrecregrasjm";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $k04_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tabrecregrasjm ";
     $sql .= "      inner join tabrec  on  tabrec.k02_codigo = tabrecregrasjm.k04_receit";
     $sql .= "      inner join tabrecjm  on  tabrecjm.k02_codjm = tabrecregrasjm.k04_codjm";
     $sql .= "      inner join tabrecjm  as a on   a.k02_codjm = tabrec.k02_codjm";
     $sql .= "      inner join inflan  on  inflan.i01_codigo = tabrecjm.k02_corr";
     $sql2 = "";
     if($dbwhere==""){
       if($k04_sequencial!=null ){
         $sql2 .= " where tabrecregrasjm.k04_sequencial = $k04_sequencial "; 
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
   function sql_query_file ( $k04_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tabrecregrasjm ";
     $sql2 = "";
     if($dbwhere==""){
       if($k04_sequencial!=null ){
         $sql2 .= " where tabrecregrasjm.k04_sequencial = $k04_sequencial "; 
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
   function sql_query_tabrec ( $k04_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tabrecregrasjm ";
     $sql .= "      inner join tabrec   on tabrec.k02_codigo  = tabrecregrasjm.k04_receit";
     // $sql .= "      inner join tabrecjm on tabrecjm.k02_codjm = tabrecregrasjm.k04_codjm";
     $sql2 = "";
     if($dbwhere==""){
       if($k04_sequencial!=null ){
         $sql2 .= " where tabrecregrasjm.k04_sequencial = $k04_sequencial "; 
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