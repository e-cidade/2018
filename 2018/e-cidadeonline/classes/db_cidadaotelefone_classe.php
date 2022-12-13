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

//MODULO: ouvidoria
//CLASSE DA ENTIDADE cidadaotelefone
class cl_cidadaotelefone { 
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
   var $ov07_sequencial = 0; 
   var $ov07_seq = 0; 
   var $ov07_cidadao = 0; 
   var $ov07_numero = null; 
   var $ov07_tipotelefone = 0; 
   var $ov07_ddd = null; 
   var $ov07_ramal = null; 
   var $ov07_obs = null; 
   var $ov07_principal = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ov07_sequencial = int4 = Sequencial 
                 ov07_seq = int4 = Sequencial 
                 ov07_cidadao = int4 = Cidadão 
                 ov07_numero = varchar(10) = Número 
                 ov07_tipotelefone = int4 = Tipo Telefone 
                 ov07_ddd = varchar(5) = DDD 
                 ov07_ramal = varchar(10) = Ramal 
                 ov07_obs = text = Observação 
                 ov07_principal = bool = Principal 
                 ";
   //funcao construtor da classe 
   function cl_cidadaotelefone() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("cidadaotelefone"); 
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
       $this->ov07_sequencial = ($this->ov07_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ov07_sequencial"]:$this->ov07_sequencial);
       $this->ov07_seq = ($this->ov07_seq == ""?@$GLOBALS["HTTP_POST_VARS"]["ov07_seq"]:$this->ov07_seq);
       $this->ov07_cidadao = ($this->ov07_cidadao == ""?@$GLOBALS["HTTP_POST_VARS"]["ov07_cidadao"]:$this->ov07_cidadao);
       $this->ov07_numero = ($this->ov07_numero == ""?@$GLOBALS["HTTP_POST_VARS"]["ov07_numero"]:$this->ov07_numero);
       $this->ov07_tipotelefone = ($this->ov07_tipotelefone == ""?@$GLOBALS["HTTP_POST_VARS"]["ov07_tipotelefone"]:$this->ov07_tipotelefone);
       $this->ov07_ddd = ($this->ov07_ddd == ""?@$GLOBALS["HTTP_POST_VARS"]["ov07_ddd"]:$this->ov07_ddd);
       $this->ov07_ramal = ($this->ov07_ramal == ""?@$GLOBALS["HTTP_POST_VARS"]["ov07_ramal"]:$this->ov07_ramal);
       $this->ov07_obs = ($this->ov07_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["ov07_obs"]:$this->ov07_obs);
       $this->ov07_principal = ($this->ov07_principal == "f"?@$GLOBALS["HTTP_POST_VARS"]["ov07_principal"]:$this->ov07_principal);
     }else{
       $this->ov07_sequencial = ($this->ov07_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ov07_sequencial"]:$this->ov07_sequencial);
       $this->ov07_seq = ($this->ov07_seq == ""?@$GLOBALS["HTTP_POST_VARS"]["ov07_seq"]:$this->ov07_seq);
     }
   }
   // funcao para inclusao
   function incluir ($ov07_sequencial){ 
      $this->atualizacampos();
     if($this->ov07_cidadao == null ){ 
       $this->erro_sql = " Campo Cidadão nao Informado.";
       $this->erro_campo = "ov07_cidadao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ov07_numero == null ){ 
       $this->erro_sql = " Campo Número nao Informado.";
       $this->erro_campo = "ov07_numero";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ov07_tipotelefone == null ){ 
       $this->erro_sql = " Campo Tipo Telefone nao Informado.";
       $this->erro_campo = "ov07_tipotelefone";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ov07_ddd == null ){ 
       $this->ov07_ddd = "0";
     }
     if($this->ov07_ramal == null ){ 
       $this->ov07_ramal = "0";
     }
     if($this->ov07_principal == null ){ 
       $this->erro_sql = " Campo Principal nao Informado.";
       $this->erro_campo = "ov07_principal";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ov07_sequencial == "" || $ov07_sequencial == null ){
       $result = db_query("select nextval('cidadaotelefone_ov07_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: cidadaotelefone_ov07_sequencial_seq do campo: ov07_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ov07_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from cidadaotelefone_ov07_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ov07_sequencial)){
         $this->erro_sql = " Campo ov07_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ov07_sequencial = $ov07_sequencial; 
       }
     }
     if(($this->ov07_sequencial == null) || ($this->ov07_sequencial == "") ){ 
       $this->erro_sql = " Campo ov07_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into cidadaotelefone(
                                       ov07_sequencial 
                                      ,ov07_seq 
                                      ,ov07_cidadao 
                                      ,ov07_numero 
                                      ,ov07_tipotelefone 
                                      ,ov07_ddd 
                                      ,ov07_ramal 
                                      ,ov07_obs 
                                      ,ov07_principal 
                       )
                values (
                                $this->ov07_sequencial 
                               ,$this->ov07_seq 
                               ,$this->ov07_cidadao 
                               ,'$this->ov07_numero' 
                               ,$this->ov07_tipotelefone 
                               ,'$this->ov07_ddd' 
                               ,'$this->ov07_ramal' 
                               ,'$this->ov07_obs' 
                               ,'$this->ov07_principal' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cadastro de telefones do cidadao ($this->ov07_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cadastro de telefones do cidadao já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cadastro de telefones do cidadao ($this->ov07_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ov07_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     if (!isset($_SESSION["DB_usaAccount"])) {
       
       $resaco = $this->sql_record($this->sql_query_file($this->ov07_sequencial));
       if(($resaco!=false)||($this->numrows!=0)){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14749,'$this->ov07_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,2599,14749,'','".AddSlashes(pg_result($resaco,0,'ov07_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2599,14750,'','".AddSlashes(pg_result($resaco,0,'ov07_seq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2599,14751,'','".AddSlashes(pg_result($resaco,0,'ov07_cidadao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2599,14752,'','".AddSlashes(pg_result($resaco,0,'ov07_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2599,14753,'','".AddSlashes(pg_result($resaco,0,'ov07_tipotelefone'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2599,14754,'','".AddSlashes(pg_result($resaco,0,'ov07_ddd'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2599,14755,'','".AddSlashes(pg_result($resaco,0,'ov07_ramal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2599,14756,'','".AddSlashes(pg_result($resaco,0,'ov07_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2599,14859,'','".AddSlashes(pg_result($resaco,0,'ov07_principal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ov07_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update cidadaotelefone set ";
     $virgula = "";
     if(trim($this->ov07_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ov07_sequencial"])){ 
       $sql  .= $virgula." ov07_sequencial = $this->ov07_sequencial ";
       $virgula = ",";
       if(trim($this->ov07_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "ov07_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ov07_seq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ov07_seq"])){ 
       $sql  .= $virgula." ov07_seq = $this->ov07_seq ";
       $virgula = ",";
       if(trim($this->ov07_seq) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "ov07_seq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ov07_cidadao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ov07_cidadao"])){ 
       $sql  .= $virgula." ov07_cidadao = $this->ov07_cidadao ";
       $virgula = ",";
       if(trim($this->ov07_cidadao) == null ){ 
         $this->erro_sql = " Campo Cidadão nao Informado.";
         $this->erro_campo = "ov07_cidadao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ov07_numero)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ov07_numero"])){ 
       $sql  .= $virgula." ov07_numero = '$this->ov07_numero' ";
       $virgula = ",";
       if(trim($this->ov07_numero) == null ){ 
         $this->erro_sql = " Campo Número nao Informado.";
         $this->erro_campo = "ov07_numero";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ov07_tipotelefone)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ov07_tipotelefone"])){ 
       $sql  .= $virgula." ov07_tipotelefone = $this->ov07_tipotelefone ";
       $virgula = ",";
       if(trim($this->ov07_tipotelefone) == null ){ 
         $this->erro_sql = " Campo Tipo Telefone nao Informado.";
         $this->erro_campo = "ov07_tipotelefone";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ov07_ddd)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ov07_ddd"])){ 
       $sql  .= $virgula." ov07_ddd = '$this->ov07_ddd' ";
       $virgula = ",";
     }
     if(trim($this->ov07_ramal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ov07_ramal"])){ 
       $sql  .= $virgula." ov07_ramal = '$this->ov07_ramal' ";
       $virgula = ",";
     }
     if(trim($this->ov07_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ov07_obs"])){ 
       $sql  .= $virgula." ov07_obs = '$this->ov07_obs' ";
       $virgula = ",";
     }
     if(trim($this->ov07_principal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ov07_principal"])){ 
       $sql  .= $virgula." ov07_principal = '$this->ov07_principal' ";
       $virgula = ",";
       if(trim($this->ov07_principal) == null ){ 
         $this->erro_sql = " Campo Principal nao Informado.";
         $this->erro_campo = "ov07_principal";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ov07_sequencial!=null){
       $sql .= " ov07_sequencial = $this->ov07_sequencial";
     }
     
     if (!isset($_SESSION["DB_usaAccount"])) {
       
       $resaco = $this->sql_record($this->sql_query_file($this->ov07_sequencial));
       if($this->numrows>0){
         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,14749,'$this->ov07_sequencial','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ov07_sequencial"]) || $this->ov07_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,2599,14749,'".AddSlashes(pg_result($resaco,$conresaco,'ov07_sequencial'))."','$this->ov07_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ov07_seq"]) || $this->ov07_seq != "")
             $resac = db_query("insert into db_acount values($acount,2599,14750,'".AddSlashes(pg_result($resaco,$conresaco,'ov07_seq'))."','$this->ov07_seq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ov07_cidadao"]) || $this->ov07_cidadao != "")
             $resac = db_query("insert into db_acount values($acount,2599,14751,'".AddSlashes(pg_result($resaco,$conresaco,'ov07_cidadao'))."','$this->ov07_cidadao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ov07_numero"]) || $this->ov07_numero != "")
             $resac = db_query("insert into db_acount values($acount,2599,14752,'".AddSlashes(pg_result($resaco,$conresaco,'ov07_numero'))."','$this->ov07_numero',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ov07_tipotelefone"]) || $this->ov07_tipotelefone != "")
             $resac = db_query("insert into db_acount values($acount,2599,14753,'".AddSlashes(pg_result($resaco,$conresaco,'ov07_tipotelefone'))."','$this->ov07_tipotelefone',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ov07_ddd"]) || $this->ov07_ddd != "")
             $resac = db_query("insert into db_acount values($acount,2599,14754,'".AddSlashes(pg_result($resaco,$conresaco,'ov07_ddd'))."','$this->ov07_ddd',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ov07_ramal"]) || $this->ov07_ramal != "")
             $resac = db_query("insert into db_acount values($acount,2599,14755,'".AddSlashes(pg_result($resaco,$conresaco,'ov07_ramal'))."','$this->ov07_ramal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ov07_obs"]) || $this->ov07_obs != "")
             $resac = db_query("insert into db_acount values($acount,2599,14756,'".AddSlashes(pg_result($resaco,$conresaco,'ov07_obs'))."','$this->ov07_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ov07_principal"]) || $this->ov07_principal != "")
             $resac = db_query("insert into db_acount values($acount,2599,14859,'".AddSlashes(pg_result($resaco,$conresaco,'ov07_principal'))."','$this->ov07_principal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de telefones do cidadao nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ov07_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de telefones do cidadao nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ov07_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ov07_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ov07_sequencial=null,$dbwhere=null) { 
     
     if (!isset($_SESSION["DB_usaAccount"])) {
       
       if($dbwhere==null || $dbwhere==""){
         $resaco = $this->sql_record($this->sql_query_file($ov07_sequencial));
       }else{ 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if(($resaco!=false)||($this->numrows!=0)){
         for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,14749,'$ov07_sequencial','E')");
           $resac = db_query("insert into db_acount values($acount,2599,14749,'','".AddSlashes(pg_result($resaco,$iresaco,'ov07_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,2599,14750,'','".AddSlashes(pg_result($resaco,$iresaco,'ov07_seq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,2599,14751,'','".AddSlashes(pg_result($resaco,$iresaco,'ov07_cidadao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,2599,14752,'','".AddSlashes(pg_result($resaco,$iresaco,'ov07_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,2599,14753,'','".AddSlashes(pg_result($resaco,$iresaco,'ov07_tipotelefone'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,2599,14754,'','".AddSlashes(pg_result($resaco,$iresaco,'ov07_ddd'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,2599,14755,'','".AddSlashes(pg_result($resaco,$iresaco,'ov07_ramal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,2599,14756,'','".AddSlashes(pg_result($resaco,$iresaco,'ov07_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,2599,14859,'','".AddSlashes(pg_result($resaco,$iresaco,'ov07_principal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from cidadaotelefone
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ov07_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ov07_sequencial = $ov07_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de telefones do cidadao nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ov07_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de telefones do cidadao nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ov07_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ov07_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:cidadaotelefone";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ov07_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cidadaotelefone ";
     $sql .= "      inner join cidadao          on cidadao.ov02_sequencial         = cidadaotelefone.ov07_cidadao 
                                               and cidadao.ov02_seq                = cidadaotelefone.ov07_seq";
     $sql .= "      inner join telefonetipo     on telefonetipo.ov23_sequencial    = cidadaotelefone.ov07_tipotelefone";
     $sql .= "      inner join situacaocidadao  on situacaocidadao.ov16_sequencial = cidadao.ov02_situacaocidadao";
     $sql2 = "";
     if($dbwhere==""){
       if($ov07_sequencial!=null ){
         $sql2 .= " where cidadaotelefone.ov07_sequencial = $ov07_sequencial "; 
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
   function sql_query_file ( $ov07_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cidadaotelefone ";
     $sql2 = "";
     if($dbwhere==""){
       if($ov07_sequencial!=null ){
         $sql2 .= " where cidadaotelefone.ov07_sequencial = $ov07_sequencial "; 
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
   function sql_query_telefonetipo ( $ov07_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cidadaotelefone ";
     $sql .= "      inner join telefonetipo  on  telefonetipo.ov23_sequencial = cidadaotelefone.ov07_tipotelefone";
     $sql2 = "";
     if($dbwhere==""){
       if($ov07_sequencial!=null ){
         $sql2 .= " where cidadaotelefone.ov07_sequencial = $ov07_sequencial "; 
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