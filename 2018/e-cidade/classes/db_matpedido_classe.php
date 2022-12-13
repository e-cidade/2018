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

//MODULO: material
//CLASSE DA ENTIDADE matpedido
class cl_matpedido { 
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
   var $m97_sequencial = 0; 
   var $m97_db_almox = 0; 
   var $m97_coddepto = 0; 
   var $m97_data_dia = null; 
   var $m97_data_mes = null; 
   var $m97_data_ano = null; 
   var $m97_data = null; 
   var $m97_login = 0; 
   var $m97_hora = null; 
   var $m97_origem = 0; 
   var $m97_obs = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 m97_sequencial = int8 = Sequencial 
                 m97_db_almox = int8 = Almoxarifado 
                 m97_coddepto = int8 = Departamento 
                 m97_data = date = Data 
                 m97_login = int8 = Login 
                 m97_hora = char(10) = Hora 
                 m97_origem = int8 = Origem 
                 m97_obs = text = Observação 
                 ";
   //funcao construtor da classe 
   function cl_matpedido() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("matpedido"); 
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
       $this->m97_sequencial = ($this->m97_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["m97_sequencial"]:$this->m97_sequencial);
       $this->m97_db_almox = ($this->m97_db_almox == ""?@$GLOBALS["HTTP_POST_VARS"]["m97_db_almox"]:$this->m97_db_almox);
       $this->m97_coddepto = ($this->m97_coddepto == ""?@$GLOBALS["HTTP_POST_VARS"]["m97_coddepto"]:$this->m97_coddepto);
       if($this->m97_data == ""){
         $this->m97_data_dia = ($this->m97_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["m97_data_dia"]:$this->m97_data_dia);
         $this->m97_data_mes = ($this->m97_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["m97_data_mes"]:$this->m97_data_mes);
         $this->m97_data_ano = ($this->m97_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["m97_data_ano"]:$this->m97_data_ano);
         if($this->m97_data_dia != ""){
            $this->m97_data = $this->m97_data_ano."-".$this->m97_data_mes."-".$this->m97_data_dia;
         }
       }
       $this->m97_login = ($this->m97_login == ""?@$GLOBALS["HTTP_POST_VARS"]["m97_login"]:$this->m97_login);
       $this->m97_hora = ($this->m97_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["m97_hora"]:$this->m97_hora);
       $this->m97_origem = ($this->m97_origem == ""?@$GLOBALS["HTTP_POST_VARS"]["m97_origem"]:$this->m97_origem);
       $this->m97_obs = ($this->m97_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["m97_obs"]:$this->m97_obs);
     }else{
       $this->m97_sequencial = ($this->m97_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["m97_sequencial"]:$this->m97_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($m97_sequencial){ 
      $this->atualizacampos();
     if($this->m97_db_almox == null ){ 
       $this->erro_sql = " Campo Almoxarifado nao Informado.";
       $this->erro_campo = "m97_db_almox";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m97_coddepto == null ){ 
       $this->erro_sql = " Campo Departamento nao Informado.";
       $this->erro_campo = "m97_coddepto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m97_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "m97_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m97_login == null ){ 
       $this->erro_sql = " Campo Login nao Informado.";
       $this->erro_campo = "m97_login";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m97_hora == null ){ 
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "m97_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m97_origem == null ){ 
       $this->erro_sql = " Campo Origem nao Informado.";
       $this->erro_campo = "m97_origem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m97_obs == null ){ 
       $this->m97_obs = "0";
     }
     if($m97_sequencial == "" || $m97_sequencial == null ){
       $result = db_query("select nextval('matpedido_m97_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: matpedido_m97_sequencial_seq do campo: m97_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->m97_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from matpedido_m97_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $m97_sequencial)){
         $this->erro_sql = " Campo m97_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->m97_sequencial = $m97_sequencial; 
       }
     }
     if(($this->m97_sequencial == null) || ($this->m97_sequencial == "") ){ 
       $this->erro_sql = " Campo m97_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into matpedido(
                                       m97_sequencial 
                                      ,m97_db_almox 
                                      ,m97_coddepto 
                                      ,m97_data 
                                      ,m97_login 
                                      ,m97_hora 
                                      ,m97_origem 
                                      ,m97_obs 
                       )
                values (
                                $this->m97_sequencial 
                               ,$this->m97_db_almox 
                               ,$this->m97_coddepto 
                               ,".($this->m97_data == "null" || $this->m97_data == ""?"null":"'".$this->m97_data."'")." 
                               ,$this->m97_login 
                               ,'$this->m97_hora' 
                               ,$this->m97_origem 
                               ,'$this->m97_obs' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "matpedido ($this->m97_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "matpedido já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "matpedido ($this->m97_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m97_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->m97_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,15231,'$this->m97_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2684,15231,'','".AddSlashes(pg_result($resaco,0,'m97_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2684,15232,'','".AddSlashes(pg_result($resaco,0,'m97_db_almox'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2684,15234,'','".AddSlashes(pg_result($resaco,0,'m97_coddepto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2684,15235,'','".AddSlashes(pg_result($resaco,0,'m97_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2684,15236,'','".AddSlashes(pg_result($resaco,0,'m97_login'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2684,15237,'','".AddSlashes(pg_result($resaco,0,'m97_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2684,15238,'','".AddSlashes(pg_result($resaco,0,'m97_origem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2684,15239,'','".AddSlashes(pg_result($resaco,0,'m97_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($m97_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update matpedido set ";
     $virgula = "";
     if(trim($this->m97_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m97_sequencial"])){ 
       $sql  .= $virgula." m97_sequencial = $this->m97_sequencial ";
       $virgula = ",";
       if(trim($this->m97_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "m97_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m97_db_almox)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m97_db_almox"])){ 
       $sql  .= $virgula." m97_db_almox = $this->m97_db_almox ";
       $virgula = ",";
       if(trim($this->m97_db_almox) == null ){ 
         $this->erro_sql = " Campo Almoxarifado nao Informado.";
         $this->erro_campo = "m97_db_almox";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m97_coddepto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m97_coddepto"])){ 
       $sql  .= $virgula." m97_coddepto = $this->m97_coddepto ";
       $virgula = ",";
       if(trim($this->m97_coddepto) == null ){ 
         $this->erro_sql = " Campo Departamento nao Informado.";
         $this->erro_campo = "m97_coddepto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m97_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m97_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["m97_data_dia"] !="") ){ 
       $sql  .= $virgula." m97_data = '$this->m97_data' ";
       $virgula = ",";
       if(trim($this->m97_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "m97_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["m97_data_dia"])){ 
         $sql  .= $virgula." m97_data = null ";
         $virgula = ",";
         if(trim($this->m97_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "m97_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->m97_login)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m97_login"])){ 
       $sql  .= $virgula." m97_login = $this->m97_login ";
       $virgula = ",";
       if(trim($this->m97_login) == null ){ 
         $this->erro_sql = " Campo Login nao Informado.";
         $this->erro_campo = "m97_login";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m97_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m97_hora"])){ 
       $sql  .= $virgula." m97_hora = '$this->m97_hora' ";
       $virgula = ",";
       if(trim($this->m97_hora) == null ){ 
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "m97_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m97_origem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m97_origem"])){ 
       $sql  .= $virgula." m97_origem = $this->m97_origem ";
       $virgula = ",";
       if(trim($this->m97_origem) == null ){ 
         $this->erro_sql = " Campo Origem nao Informado.";
         $this->erro_campo = "m97_origem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m97_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m97_obs"])){ 
       $sql  .= $virgula." m97_obs = '$this->m97_obs' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($m97_sequencial!=null){
       $sql .= " m97_sequencial = $this->m97_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->m97_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15231,'$this->m97_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m97_sequencial"]) || $this->m97_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2684,15231,'".AddSlashes(pg_result($resaco,$conresaco,'m97_sequencial'))."','$this->m97_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m97_db_almox"]) || $this->m97_db_almox != "")
           $resac = db_query("insert into db_acount values($acount,2684,15232,'".AddSlashes(pg_result($resaco,$conresaco,'m97_db_almox'))."','$this->m97_db_almox',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m97_coddepto"]) || $this->m97_coddepto != "")
           $resac = db_query("insert into db_acount values($acount,2684,15234,'".AddSlashes(pg_result($resaco,$conresaco,'m97_coddepto'))."','$this->m97_coddepto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m97_data"]) || $this->m97_data != "")
           $resac = db_query("insert into db_acount values($acount,2684,15235,'".AddSlashes(pg_result($resaco,$conresaco,'m97_data'))."','$this->m97_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m97_login"]) || $this->m97_login != "")
           $resac = db_query("insert into db_acount values($acount,2684,15236,'".AddSlashes(pg_result($resaco,$conresaco,'m97_login'))."','$this->m97_login',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m97_hora"]) || $this->m97_hora != "")
           $resac = db_query("insert into db_acount values($acount,2684,15237,'".AddSlashes(pg_result($resaco,$conresaco,'m97_hora'))."','$this->m97_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m97_origem"]) || $this->m97_origem != "")
           $resac = db_query("insert into db_acount values($acount,2684,15238,'".AddSlashes(pg_result($resaco,$conresaco,'m97_origem'))."','$this->m97_origem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m97_obs"]) || $this->m97_obs != "")
           $resac = db_query("insert into db_acount values($acount,2684,15239,'".AddSlashes(pg_result($resaco,$conresaco,'m97_obs'))."','$this->m97_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "matpedido nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->m97_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "matpedido nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->m97_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m97_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($m97_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($m97_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15231,'$m97_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2684,15231,'','".AddSlashes(pg_result($resaco,$iresaco,'m97_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2684,15232,'','".AddSlashes(pg_result($resaco,$iresaco,'m97_db_almox'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2684,15234,'','".AddSlashes(pg_result($resaco,$iresaco,'m97_coddepto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2684,15235,'','".AddSlashes(pg_result($resaco,$iresaco,'m97_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2684,15236,'','".AddSlashes(pg_result($resaco,$iresaco,'m97_login'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2684,15237,'','".AddSlashes(pg_result($resaco,$iresaco,'m97_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2684,15238,'','".AddSlashes(pg_result($resaco,$iresaco,'m97_origem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2684,15239,'','".AddSlashes(pg_result($resaco,$iresaco,'m97_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from matpedido
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($m97_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " m97_sequencial = $m97_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "matpedido nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$m97_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "matpedido nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$m97_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$m97_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:matpedido";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $m97_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from matpedido ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = matpedido.m97_login";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = matpedido.m97_coddepto";
     $sql .= "      inner join db_almox  on  db_almox.m91_codigo = matpedido.m97_db_almox";
     $sql .= "      inner join db_config  on  db_config.codigo = db_depart.instit";
     $sql .= "      left  join db_depart  as a on   a.coddepto = db_almox.m91_depto";    
     $sql2 = "";
     if($dbwhere==""){
       if($m97_sequencial!=null ){
         $sql2 .= " where matpedido.m97_sequencial = $m97_sequencial "; 
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
   function sql_query_matpedido($m97_sequencial=null,$campos="*",$ordem=null,$dbwhere="") {
    $sql = "select ";
    if ($campos != "*" ) {
      $campos_sql = split("#",$campos);
      $virgula = "";
      for ($i=0; $i<sizeof($campos_sql); $i++) {
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from matpedido ";
    $sql .= "      inner join matpedidoitem   on  matpedidoitem.m98_matpedido = matpedido.m97_sequencial";
    $sql .= "      inner join matmater   on  matmater.m60_codmater = matpedidoitem.m98_matmater";
    $sql .= "      inner join matunid a  on  a.m61_codmatunid = matmater.m60_codmatunid";
    $sql .= "      left join matmaterunisai on matmaterunisai.m62_codmater = matmater.m60_codmater";
    $sql .= "      left join matunid b  on  b.m61_codmatunid = matmaterunisai.m62_codmatunid";
    //$sql .= "      left  join atendrequiitem on atendrequiitem.m43_codmatrequiitem = matrequiitem.m41_codigo";
    $sql2 = "";
    if ($dbwhere=="") {
      if ($m97_sequencial!=null ) {
        $sql2 .= " where matpedido.m97_sequencial = $m97_sequencial ";
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null ) {
      $sql .= " order by ";
      $campos_sql = split("#",$ordem);
      $virgula = "";
      for ($i=0; $i<sizeof($campos_sql); $i++) {
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }
  
   function sql_query_matpedidorequi($m97_sequencial=null,$campos="*",$ordem=null,$dbwhere="") {
    $sql = "select ";
    if ($campos != "*" ) {
      $campos_sql = split("#",$campos);
      $virgula = "";
      for ($i=0; $i<sizeof($campos_sql); $i++) {
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from matpedido ";
    $sql .= "      inner join matpedidoitem   on  matpedidoitem.m98_matpedido = matpedido.m97_sequencial";
    $sql .= "      inner join matmater   on  matmater.m60_codmater = matpedidoitem.m98_matmater";
    $sql .= "      inner join matunid a  on  a.m61_codmatunid = matmater.m60_codmatunid";
    $sql .= "      left join matmaterunisai on matmaterunisai.m62_codmater = matmater.m60_codmater";
     $sql .= "      left join matunid b  on  b.m61_codmatunid = matmaterunisai.m62_codmatunid";
    $sql .= "      left join matestoqueinimeimatpedidoitem on matestoqueinimeimatpedidoitem.m99_matpedidoitem = matpedidoitem.m98_sequencial";
    $sql .= "      left join matestoqueinimei on matestoqueinimei.m82_codigo = matestoqueinimeimatpedidoitem.m99_matestoqueinimei";    
    $sql2 = "";
    if ($dbwhere=="") {
      if ($m97_sequencial!=null ) {
        $sql2 .= " where matpedido.m97_sequencial = $m97_sequencial ";
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null ) {
      $sql .= " order by ";
      $campos_sql = split("#",$ordem);
      $virgula = "";
      for ($i=0; $i<sizeof($campos_sql); $i++) {
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }
  
   function sql_query_matpedidoanul($m97_sequencial=null,$campos="*",$ordem=null,$dbwhere="") {
    $sql = "select ";
    if ($campos != "*" ) {
      $campos_sql = split("#",$campos);
      $virgula = "";
      for ($i=0; $i<sizeof($campos_sql); $i++) {
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from matpedido ";
    $sql .= "      inner join matpedidoitem   on  matpedidoitem.m98_matpedido = matpedido.m97_sequencial";
    $sql .= "      inner join matmater   on  matmater.m60_codmater = matpedidoitem.m98_matmater";
    $sql .= "      inner join matunid a  on  a.m61_codmatunid = matmater.m60_codmatunid";
    $sql .= "      left join matmaterunisai on matmaterunisai.m62_codmater = matmater.m60_codmater";
    $sql .= "      left join matunid b  on  b.m61_codmatunid = matmaterunisai.m62_codmatunid";
    $sql .= "      left join matanulitempedido on matanulitempedido.m101_matpedidoitem = matpedidoitem.m98_sequencial";    
    $sql .= "      left join matanulitem on matanulitem.m103_codigo = matanulitempedido.m101_matanulitem";    

    $sql2 = "";
    if ($dbwhere=="") {
      if ($m97_sequencial!=null ) {
        $sql2 .= " where matpedido.m97_sequencial = $m97_sequencial ";
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null ) {
      $sql .= " order by ";
      $campos_sql = split("#",$ordem);
      $virgula = "";
      for ($i=0; $i<sizeof($campos_sql); $i++) {
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }
  
  
  function sql_query_almoxleft($m97_codigo=null,$campos="*",$ordem=null,$dbwhere="") {
    $sql = "select  distinct ";
    if ($campos != "*" ) {
      $campos_sql = split("#",$campos);
      $virgula = "";
      for ($i=0; $i<sizeof($campos_sql); $i++) {
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
  } else {
      $sql .= $campos;
    }
    $sql .= " from matpedido ";
    $sql .= "   inner join db_usuarios     on db_usuarios.id_usuario  = matpedido.m97_login                 ";
    $sql .= "   inner join db_depart       on db_depart.coddepto      = matpedido.m97_coddepto                 ";
    $sql .= "   inner join db_almox        on db_almox.m91_codigo     = matpedido.m97_db_almox                 ";
    $sql .= "   inner join db_depart almox on almox.coddepto          = db_almox.m91_depto                 ";
    $sql .= "  	inner join matpedidoitem     on matpedidoitem.m98_matpedido = matpedido.m97_sequencial           ";

		
    $sql2 = "";
    if ($dbwhere=="") {
      if ($m97_codigo!=null ) {
        $sql2 .= " where matpedido.m97_sequencial = $m97_sequencial ";
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null ) {
      $sql .= " order by ";
      $campos_sql = split("#",$ordem);
      $virgula = "";
      for ($i=0; $i<sizeof($campos_sql); $i++) {
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }
  
  function sql_query_almox($m97_sequencial=null,$campos="*",$ordem=null,$dbwhere="") {
    $sql = "select ";
    if ($campos != "*" ) {
      $campos_sql = split("#",$campos);
      $virgula = "";
      for ($i=0; $i<sizeof($campos_sql); $i++) {
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from matpedido ";
    $sql .= "      inner join db_usuarios     on  db_usuarios.id_usuario = matpedido.m97_login";
    $sql .= "      inner join db_depart       on  db_depart.coddepto     = matpedido.m97_coddepto";
    $sql .= "      inner join db_almox        on  db_almox.m91_codigo    = matpedido.m97_db_almox";
    $sql .= "      inner join db_depart as almox on  almox.coddepto         = db_almox.m91_depto";
    //$sql .= "      inner join matpedidoitem     on matpedidoitem.m98_matpedido = matpedido.m97_sequencial";    
    //$sql .= "      inner join matpedidotransf on matpedidotransf.m100_matpedidoitem=matpedidoitem.m98_sequencial";      
    $sql2 = "";
    if ($dbwhere=="") {
      if ($m97_sequencial!=null ) {
        $sql2 .= " where matpedido.m97_sequencial = $m97_sequencial ";
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null ) {
      $sql .= " order by ";
      $campos_sql = split("#",$ordem);
      $virgula = "";
      for ($i=0; $i<sizeof($campos_sql); $i++) {
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }
   // funcao do sql 
   function sql_query_file ( $m97_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from matpedido ";
     $sql2 = "";
     if($dbwhere==""){
       if($m97_sequencial!=null ){
         $sql2 .= " where matpedido.m97_sequencial = $m97_sequencial "; 
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