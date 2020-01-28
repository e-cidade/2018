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

//MODULO: veiculos
//CLASSE DA ENTIDADE veicmotoristascentral
class cl_veicmotoristascentral { 
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
   var $ve41_sequencial = 0; 
   var $ve41_veicmotoristas = 0; 
   var $ve41_veiccadcentral = 0; 
   var $ve41_dtini_dia = null; 
   var $ve41_dtini_mes = null; 
   var $ve41_dtini_ano = null; 
   var $ve41_dtini = null; 
   var $ve41_dtfim_dia = null; 
   var $ve41_dtfim_mes = null; 
   var $ve41_dtfim_ano = null; 
   var $ve41_dtfim = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ve41_sequencial = int4 = Cód. Sequencial 
                 ve41_veicmotoristas = int4 = Motorista 
                 ve41_veiccadcentral = int4 = Central 
                 ve41_dtini = date = Inicial 
                 ve41_dtfim = date = Final 
                 ";
   //funcao construtor da classe 
   function cl_veicmotoristascentral() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("veicmotoristascentral"); 
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
       $this->ve41_sequencial = ($this->ve41_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ve41_sequencial"]:$this->ve41_sequencial);
       $this->ve41_veicmotoristas = ($this->ve41_veicmotoristas == ""?@$GLOBALS["HTTP_POST_VARS"]["ve41_veicmotoristas"]:$this->ve41_veicmotoristas);
       $this->ve41_veiccadcentral = ($this->ve41_veiccadcentral == ""?@$GLOBALS["HTTP_POST_VARS"]["ve41_veiccadcentral"]:$this->ve41_veiccadcentral);
       if($this->ve41_dtini == ""){
         $this->ve41_dtini_dia = ($this->ve41_dtini_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ve41_dtini_dia"]:$this->ve41_dtini_dia);
         $this->ve41_dtini_mes = ($this->ve41_dtini_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ve41_dtini_mes"]:$this->ve41_dtini_mes);
         $this->ve41_dtini_ano = ($this->ve41_dtini_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ve41_dtini_ano"]:$this->ve41_dtini_ano);
         if($this->ve41_dtini_dia != ""){
            $this->ve41_dtini = $this->ve41_dtini_ano."-".$this->ve41_dtini_mes."-".$this->ve41_dtini_dia;
         }
       }
       if($this->ve41_dtfim == ""){
         $this->ve41_dtfim_dia = ($this->ve41_dtfim_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ve41_dtfim_dia"]:$this->ve41_dtfim_dia);
         $this->ve41_dtfim_mes = ($this->ve41_dtfim_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ve41_dtfim_mes"]:$this->ve41_dtfim_mes);
         $this->ve41_dtfim_ano = ($this->ve41_dtfim_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ve41_dtfim_ano"]:$this->ve41_dtfim_ano);
         if($this->ve41_dtfim_dia != ""){
            $this->ve41_dtfim = $this->ve41_dtfim_ano."-".$this->ve41_dtfim_mes."-".$this->ve41_dtfim_dia;
         }
       }
     }else{
       $this->ve41_sequencial = ($this->ve41_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ve41_sequencial"]:$this->ve41_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ve41_sequencial){ 
      $this->atualizacampos();
     if($this->ve41_veicmotoristas == null ){ 
       $this->erro_sql = " Campo Motorista nao Informado.";
       $this->erro_campo = "ve41_veicmotoristas";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve41_veiccadcentral == null ){ 
       $this->erro_sql = " Campo Central nao Informado.";
       $this->erro_campo = "ve41_veiccadcentral";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve41_dtini == null ){ 
       $this->erro_sql = " Campo Inicial nao Informado.";
       $this->erro_campo = "ve41_dtini_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve41_dtfim == null ){ 
       $this->ve41_dtfim = "null";
     }
     if($ve41_sequencial == "" || $ve41_sequencial == null ){
       $result = db_query("select nextval('veicmotoristascentral_ve41_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: veicmotoristascentral_ve41_sequencial_seq do campo: ve41_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ve41_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from veicmotoristascentral_ve41_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ve41_sequencial)){
         $this->erro_sql = " Campo ve41_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ve41_sequencial = $ve41_sequencial; 
       }
     }
     if(($this->ve41_sequencial == null) || ($this->ve41_sequencial == "") ){ 
       $this->erro_sql = " Campo ve41_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into veicmotoristascentral(
                                       ve41_sequencial 
                                      ,ve41_veicmotoristas 
                                      ,ve41_veiccadcentral 
                                      ,ve41_dtini 
                                      ,ve41_dtfim 
                       )
                values (
                                $this->ve41_sequencial 
                               ,$this->ve41_veicmotoristas 
                               ,$this->ve41_veiccadcentral 
                               ,".($this->ve41_dtini == "null" || $this->ve41_dtini == ""?"null":"'".$this->ve41_dtini."'")." 
                               ,".($this->ve41_dtfim == "null" || $this->ve41_dtfim == ""?"null":"'".$this->ve41_dtfim."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Central Motoristas ($this->ve41_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Central Motoristas já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Central Motoristas ($this->ve41_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ve41_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ve41_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,11276,'$this->ve41_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,1939,11276,'','".AddSlashes(pg_result($resaco,0,'ve41_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1939,11277,'','".AddSlashes(pg_result($resaco,0,'ve41_veicmotoristas'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1939,11278,'','".AddSlashes(pg_result($resaco,0,'ve41_veiccadcentral'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1939,11286,'','".AddSlashes(pg_result($resaco,0,'ve41_dtini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1939,11287,'','".AddSlashes(pg_result($resaco,0,'ve41_dtfim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ve41_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update veicmotoristascentral set ";
     $virgula = "";
     if(trim($this->ve41_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve41_sequencial"])){ 
       $sql  .= $virgula." ve41_sequencial = $this->ve41_sequencial ";
       $virgula = ",";
       if(trim($this->ve41_sequencial) == null ){ 
         $this->erro_sql = " Campo Cód. Sequencial nao Informado.";
         $this->erro_campo = "ve41_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve41_veicmotoristas)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve41_veicmotoristas"])){ 
       $sql  .= $virgula." ve41_veicmotoristas = $this->ve41_veicmotoristas ";
       $virgula = ",";
       if(trim($this->ve41_veicmotoristas) == null ){ 
         $this->erro_sql = " Campo Motorista nao Informado.";
         $this->erro_campo = "ve41_veicmotoristas";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve41_veiccadcentral)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve41_veiccadcentral"])){ 
       $sql  .= $virgula." ve41_veiccadcentral = $this->ve41_veiccadcentral ";
       $virgula = ",";
       if(trim($this->ve41_veiccadcentral) == null ){ 
         $this->erro_sql = " Campo Central nao Informado.";
         $this->erro_campo = "ve41_veiccadcentral";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve41_dtini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve41_dtini_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ve41_dtini_dia"] !="") ){ 
       $sql  .= $virgula." ve41_dtini = '$this->ve41_dtini' ";
       $virgula = ",";
       if(trim($this->ve41_dtini) == null ){ 
         $this->erro_sql = " Campo Inicial nao Informado.";
         $this->erro_campo = "ve41_dtini_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ve41_dtini_dia"])){ 
         $sql  .= $virgula." ve41_dtini = null ";
         $virgula = ",";
         if(trim($this->ve41_dtini) == null ){ 
           $this->erro_sql = " Campo Inicial nao Informado.";
           $this->erro_campo = "ve41_dtini_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ve41_dtfim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve41_dtfim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ve41_dtfim_dia"] !="") ){ 
       $sql  .= $virgula." ve41_dtfim = '$this->ve41_dtfim' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ve41_dtfim_dia"])){ 
         $sql  .= $virgula." ve41_dtfim = null ";
         $virgula = ",";
       }
     }
     $sql .= " where ";
     if($ve41_sequencial!=null){
       $sql .= " ve41_sequencial = $this->ve41_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ve41_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11276,'$this->ve41_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve41_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,1939,11276,'".AddSlashes(pg_result($resaco,$conresaco,'ve41_sequencial'))."','$this->ve41_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve41_veicmotoristas"]))
           $resac = db_query("insert into db_acount values($acount,1939,11277,'".AddSlashes(pg_result($resaco,$conresaco,'ve41_veicmotoristas'))."','$this->ve41_veicmotoristas',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve41_veiccadcentral"]))
           $resac = db_query("insert into db_acount values($acount,1939,11278,'".AddSlashes(pg_result($resaco,$conresaco,'ve41_veiccadcentral'))."','$this->ve41_veiccadcentral',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve41_dtini"]))
           $resac = db_query("insert into db_acount values($acount,1939,11286,'".AddSlashes(pg_result($resaco,$conresaco,'ve41_dtini'))."','$this->ve41_dtini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve41_dtfim"]))
           $resac = db_query("insert into db_acount values($acount,1939,11287,'".AddSlashes(pg_result($resaco,$conresaco,'ve41_dtfim'))."','$this->ve41_dtfim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Central Motoristas nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ve41_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Central Motoristas nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ve41_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ve41_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ve41_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ve41_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11276,'$ve41_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,1939,11276,'','".AddSlashes(pg_result($resaco,$iresaco,'ve41_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1939,11277,'','".AddSlashes(pg_result($resaco,$iresaco,'ve41_veicmotoristas'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1939,11278,'','".AddSlashes(pg_result($resaco,$iresaco,'ve41_veiccadcentral'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1939,11286,'','".AddSlashes(pg_result($resaco,$iresaco,'ve41_dtini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1939,11287,'','".AddSlashes(pg_result($resaco,$iresaco,'ve41_dtfim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from veicmotoristascentral
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ve41_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ve41_sequencial = $ve41_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Central Motoristas nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ve41_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Central Motoristas nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ve41_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ve41_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:veicmotoristascentral";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $ve41_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from veicmotoristascentral ";
     $sql .= "      inner join veicmotoristas  on  veicmotoristas.ve05_codigo = veicmotoristascentral.ve41_veicmotoristas";
     $sql .= "      inner join veiccadcentral  on  veiccadcentral.ve36_sequencial = veicmotoristascentral.ve41_veiccadcentral";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = veicmotoristas.ve05_numcgm";
     $sql .= "      inner join veiccadcategcnh  on  veiccadcategcnh.ve30_codigo = veicmotoristas.ve05_veiccadcategcnh";
     $sql .= "      inner join veiccadmotoristasit  on  veiccadmotoristasit.ve33_codigo = veicmotoristas.ve05_veiccadmotoristasit";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = veiccadcentral.ve36_coddepto";
     $sql2 = "";
     if($dbwhere==""){
       if($ve41_sequencial!=null ){
         $sql2 .= " where veicmotoristascentral.ve41_sequencial = $ve41_sequencial "; 
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
   function sql_query_file ( $ve41_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from veicmotoristascentral ";
     $sql2 = "";
     if($dbwhere==""){
       if($ve41_sequencial!=null ){
         $sql2 .= " where veicmotoristascentral.ve41_sequencial = $ve41_sequencial "; 
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