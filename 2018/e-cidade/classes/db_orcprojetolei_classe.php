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
//CLASSE DA ENTIDADE orcprojetolei
class cl_orcprojetolei { 
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
   var $o138_sequencial = 0; 
   var $o138_numerolei = null; 
   var $o138_data_dia = null; 
   var $o138_data_mes = null; 
   var $o138_data_ano = null; 
   var $o138_data = null; 
   var $o138_instit = 0; 
   var $o138_textolei = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 o138_sequencial = int4 = Código Sequencial 
                 o138_numerolei = varchar(25) = Número da Lei 
                 o138_data = date = Data do Projeto 
                 o138_instit = int4 = Código da Instituição 
                 o138_textolei = text = Texto do Projeto 
                 ";
   //funcao construtor da classe 
   function cl_orcprojetolei() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("orcprojetolei"); 
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
       $this->o138_sequencial = ($this->o138_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["o138_sequencial"]:$this->o138_sequencial);
       $this->o138_numerolei = ($this->o138_numerolei == ""?@$GLOBALS["HTTP_POST_VARS"]["o138_numerolei"]:$this->o138_numerolei);
       if($this->o138_data == ""){
         $this->o138_data_dia = ($this->o138_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["o138_data_dia"]:$this->o138_data_dia);
         $this->o138_data_mes = ($this->o138_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["o138_data_mes"]:$this->o138_data_mes);
         $this->o138_data_ano = ($this->o138_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["o138_data_ano"]:$this->o138_data_ano);
         if($this->o138_data_dia != ""){
            $this->o138_data = $this->o138_data_ano."-".$this->o138_data_mes."-".$this->o138_data_dia;
         }
       }
       $this->o138_instit = ($this->o138_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["o138_instit"]:$this->o138_instit);
       $this->o138_textolei = ($this->o138_textolei == ""?@$GLOBALS["HTTP_POST_VARS"]["o138_textolei"]:$this->o138_textolei);
     }else{
       $this->o138_sequencial = ($this->o138_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["o138_sequencial"]:$this->o138_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($o138_sequencial){ 
      $this->atualizacampos();
     if($this->o138_numerolei == null ){ 
       $this->erro_sql = " Campo Número da Lei nao Informado.";
       $this->erro_campo = "o138_numerolei";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o138_data == null ){ 
       $this->erro_sql = " Campo Data do Projeto nao Informado.";
       $this->erro_campo = "o138_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o138_instit == null ){ 
       $this->erro_sql = " Campo Código da Instituição nao Informado.";
       $this->erro_campo = "o138_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($o138_sequencial == "" || $o138_sequencial == null ){
       $result = db_query("select nextval('orcprojetolei_o138_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: orcprojetolei_o138_sequencial_seq do campo: o138_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->o138_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from orcprojetolei_o138_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $o138_sequencial)){
         $this->erro_sql = " Campo o138_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->o138_sequencial = $o138_sequencial; 
       }
     }
     if(($this->o138_sequencial == null) || ($this->o138_sequencial == "") ){ 
       $this->erro_sql = " Campo o138_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into orcprojetolei(
                                       o138_sequencial 
                                      ,o138_numerolei 
                                      ,o138_data 
                                      ,o138_instit 
                                      ,o138_textolei 
                       )
                values (
                                $this->o138_sequencial 
                               ,'$this->o138_numerolei' 
                               ,".($this->o138_data == "null" || $this->o138_data == ""?"null":"'".$this->o138_data."'")." 
                               ,$this->o138_instit 
                               ,'$this->o138_textolei' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Projeto de Lei para Suplementação ($this->o138_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Projeto de Lei para Suplementação já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Projeto de Lei para Suplementação ($this->o138_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o138_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->o138_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,17682,'$this->o138_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3123,17682,'','".AddSlashes(pg_result($resaco,0,'o138_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3123,17683,'','".AddSlashes(pg_result($resaco,0,'o138_numerolei'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3123,17684,'','".AddSlashes(pg_result($resaco,0,'o138_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3123,17685,'','".AddSlashes(pg_result($resaco,0,'o138_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3123,17686,'','".AddSlashes(pg_result($resaco,0,'o138_textolei'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($o138_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update orcprojetolei set ";
     $virgula = "";
     if(trim($this->o138_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o138_sequencial"])){ 
       $sql  .= $virgula." o138_sequencial = $this->o138_sequencial ";
       $virgula = ",";
       if(trim($this->o138_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Sequencial nao Informado.";
         $this->erro_campo = "o138_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o138_numerolei)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o138_numerolei"])){ 
       $sql  .= $virgula." o138_numerolei = '$this->o138_numerolei' ";
       $virgula = ",";
       if(trim($this->o138_numerolei) == null ){ 
         $this->erro_sql = " Campo Número da Lei nao Informado.";
         $this->erro_campo = "o138_numerolei";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o138_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o138_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["o138_data_dia"] !="") ){ 
       $sql  .= $virgula." o138_data = '$this->o138_data' ";
       $virgula = ",";
       if(trim($this->o138_data) == null ){ 
         $this->erro_sql = " Campo Data do Projeto nao Informado.";
         $this->erro_campo = "o138_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["o138_data_dia"])){ 
         $sql  .= $virgula." o138_data = null ";
         $virgula = ",";
         if(trim($this->o138_data) == null ){ 
           $this->erro_sql = " Campo Data do Projeto nao Informado.";
           $this->erro_campo = "o138_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->o138_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o138_instit"])){ 
       $sql  .= $virgula." o138_instit = $this->o138_instit ";
       $virgula = ",";
       if(trim($this->o138_instit) == null ){ 
         $this->erro_sql = " Campo Código da Instituição nao Informado.";
         $this->erro_campo = "o138_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o138_textolei)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o138_textolei"])){ 
       $sql  .= $virgula." o138_textolei = '$this->o138_textolei' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($o138_sequencial!=null){
       $sql .= " o138_sequencial = $this->o138_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->o138_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17682,'$this->o138_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o138_sequencial"]) || $this->o138_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3123,17682,'".AddSlashes(pg_result($resaco,$conresaco,'o138_sequencial'))."','$this->o138_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o138_numerolei"]) || $this->o138_numerolei != "")
           $resac = db_query("insert into db_acount values($acount,3123,17683,'".AddSlashes(pg_result($resaco,$conresaco,'o138_numerolei'))."','$this->o138_numerolei',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o138_data"]) || $this->o138_data != "")
           $resac = db_query("insert into db_acount values($acount,3123,17684,'".AddSlashes(pg_result($resaco,$conresaco,'o138_data'))."','$this->o138_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o138_instit"]) || $this->o138_instit != "")
           $resac = db_query("insert into db_acount values($acount,3123,17685,'".AddSlashes(pg_result($resaco,$conresaco,'o138_instit'))."','$this->o138_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o138_textolei"]) || $this->o138_textolei != "")
           $resac = db_query("insert into db_acount values($acount,3123,17686,'".AddSlashes(pg_result($resaco,$conresaco,'o138_textolei'))."','$this->o138_textolei',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Projeto de Lei para Suplementação nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->o138_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Projeto de Lei para Suplementação nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->o138_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o138_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($o138_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($o138_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17682,'$o138_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3123,17682,'','".AddSlashes(pg_result($resaco,$iresaco,'o138_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3123,17683,'','".AddSlashes(pg_result($resaco,$iresaco,'o138_numerolei'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3123,17684,'','".AddSlashes(pg_result($resaco,$iresaco,'o138_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3123,17685,'','".AddSlashes(pg_result($resaco,$iresaco,'o138_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3123,17686,'','".AddSlashes(pg_result($resaco,$iresaco,'o138_textolei'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from orcprojetolei
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($o138_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o138_sequencial = $o138_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Projeto de Lei para Suplementação nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$o138_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Projeto de Lei para Suplementação nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$o138_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$o138_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:orcprojetolei";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $o138_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcprojetolei ";
     $sql .= "      inner join db_config  on  db_config.codigo = orcprojetolei.o138_instit";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql .= "      inner join db_tipoinstit  on  db_tipoinstit.db21_codtipo = db_config.db21_tipoinstit";
     $sql2 = "";
     if($dbwhere==""){
       if($o138_sequencial!=null ){
         $sql2 .= " where orcprojetolei.o138_sequencial = $o138_sequencial "; 
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
   function sql_query_file ( $o138_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcprojetolei ";
     $sql2 = "";
     if($dbwhere==""){
       if($o138_sequencial!=null ){
         $sql2 .= " where orcprojetolei.o138_sequencial = $o138_sequencial "; 
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