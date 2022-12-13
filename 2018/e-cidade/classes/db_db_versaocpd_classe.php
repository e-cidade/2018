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

//MODULO: configuracoes
//CLASSE DA ENTIDADE db_versaocpd
class cl_db_versaocpd { 
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
   var $db33_codcpd = 0; 
   var $db33_codver = 0; 
   var $db33_obs = null; 
   var $db33_obscpd = null; 
   var $db33_data_dia = null; 
   var $db33_data_mes = null; 
   var $db33_data_ano = null; 
   var $db33_data = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 db33_codcpd = int4 = Código da Observação 
                 db33_codver = int4 = Código da Versão 
                 db33_obs = text = Observações da Release 
                 db33_obscpd = text = Observações para o CPD 
                 db33_data = date = Data da Inclusão 
                 ";
   //funcao construtor da classe 
   function cl_db_versaocpd() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("db_versaocpd"); 
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
       $this->db33_codcpd = ($this->db33_codcpd == ""?@$GLOBALS["HTTP_POST_VARS"]["db33_codcpd"]:$this->db33_codcpd);
       $this->db33_codver = ($this->db33_codver == ""?@$GLOBALS["HTTP_POST_VARS"]["db33_codver"]:$this->db33_codver);
       $this->db33_obs = ($this->db33_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["db33_obs"]:$this->db33_obs);
       $this->db33_obscpd = ($this->db33_obscpd == ""?@$GLOBALS["HTTP_POST_VARS"]["db33_obscpd"]:$this->db33_obscpd);
       if($this->db33_data == ""){
         $this->db33_data_dia = ($this->db33_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["db33_data_dia"]:$this->db33_data_dia);
         $this->db33_data_mes = ($this->db33_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["db33_data_mes"]:$this->db33_data_mes);
         $this->db33_data_ano = ($this->db33_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["db33_data_ano"]:$this->db33_data_ano);
         if($this->db33_data_dia != ""){
            $this->db33_data = $this->db33_data_ano."-".$this->db33_data_mes."-".$this->db33_data_dia;
         }
       }
     }else{
       $this->db33_codcpd = ($this->db33_codcpd == ""?@$GLOBALS["HTTP_POST_VARS"]["db33_codcpd"]:$this->db33_codcpd);
     }
   }
   // funcao para inclusao
   function incluir ($db33_codcpd){ 
      $this->atualizacampos();
     if($this->db33_codver == null ){ 
       $this->erro_sql = " Campo Código da Versão nao Informado.";
       $this->erro_campo = "db33_codver";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db33_obs == null ){ 
       $this->erro_sql = " Campo Observações da Release nao Informado.";
       $this->erro_campo = "db33_obs";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db33_obscpd == null ){ 
       $this->erro_sql = " Campo Observações para o CPD nao Informado.";
       $this->erro_campo = "db33_obscpd";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db33_data == null ){ 
       $this->erro_sql = " Campo Data da Inclusão nao Informado.";
       $this->erro_campo = "db33_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($db33_codcpd == "" || $db33_codcpd == null ){
       $result = db_query("select nextval('db_versaocpd_db33_codcpd_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: db_versaocpd_db33_codcpd_seq do campo: db33_codcpd"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->db33_codcpd = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from db_versaocpd_db33_codcpd_seq");
       if(($result != false) && (pg_result($result,0,0) < $db33_codcpd)){
         $this->erro_sql = " Campo db33_codcpd maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->db33_codcpd = $db33_codcpd; 
       }
     }
     if(($this->db33_codcpd == null) || ($this->db33_codcpd == "") ){ 
       $this->erro_sql = " Campo db33_codcpd nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into db_versaocpd(
                                       db33_codcpd 
                                      ,db33_codver 
                                      ,db33_obs 
                                      ,db33_obscpd 
                                      ,db33_data 
                       )
                values (
                                $this->db33_codcpd 
                               ,$this->db33_codver 
                               ,'$this->db33_obs' 
                               ,'$this->db33_obscpd' 
                               ,".($this->db33_data == "null" || $this->db33_data == ""?"null":"'".$this->db33_data."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Versão Para o CPD ($this->db33_codcpd) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Versão Para o CPD já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Versão Para o CPD ($this->db33_codcpd) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db33_codcpd;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->db33_codcpd));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,5880,'$this->db33_codcpd','I')");
       $resac = db_query("insert into db_acount values($acount,941,5880,'','".AddSlashes(pg_result($resaco,0,'db33_codcpd'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,941,5879,'','".AddSlashes(pg_result($resaco,0,'db33_codver'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,941,5881,'','".AddSlashes(pg_result($resaco,0,'db33_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,941,5882,'','".AddSlashes(pg_result($resaco,0,'db33_obscpd'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,941,5883,'','".AddSlashes(pg_result($resaco,0,'db33_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($db33_codcpd=null) { 
      $this->atualizacampos();
     $sql = " update db_versaocpd set ";
     $virgula = "";
     if(trim($this->db33_codcpd)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db33_codcpd"])){ 
       $sql  .= $virgula." db33_codcpd = $this->db33_codcpd ";
       $virgula = ",";
       if(trim($this->db33_codcpd) == null ){ 
         $this->erro_sql = " Campo Código da Observação nao Informado.";
         $this->erro_campo = "db33_codcpd";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db33_codver)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db33_codver"])){ 
       $sql  .= $virgula." db33_codver = $this->db33_codver ";
       $virgula = ",";
       if(trim($this->db33_codver) == null ){ 
         $this->erro_sql = " Campo Código da Versão nao Informado.";
         $this->erro_campo = "db33_codver";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db33_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db33_obs"])){ 
       $sql  .= $virgula." db33_obs = '$this->db33_obs' ";
       $virgula = ",";
       if(trim($this->db33_obs) == null ){ 
         $this->erro_sql = " Campo Observações da Release nao Informado.";
         $this->erro_campo = "db33_obs";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db33_obscpd)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db33_obscpd"])){ 
       $sql  .= $virgula." db33_obscpd = '$this->db33_obscpd' ";
       $virgula = ",";
       if(trim($this->db33_obscpd) == null ){ 
         $this->erro_sql = " Campo Observações para o CPD nao Informado.";
         $this->erro_campo = "db33_obscpd";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db33_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db33_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["db33_data_dia"] !="") ){ 
       $sql  .= $virgula." db33_data = '$this->db33_data' ";
       $virgula = ",";
       if(trim($this->db33_data) == null ){ 
         $this->erro_sql = " Campo Data da Inclusão nao Informado.";
         $this->erro_campo = "db33_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["db33_data_dia"])){ 
         $sql  .= $virgula." db33_data = null ";
         $virgula = ",";
         if(trim($this->db33_data) == null ){ 
           $this->erro_sql = " Campo Data da Inclusão nao Informado.";
           $this->erro_campo = "db33_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";
     if($db33_codcpd!=null){
       $sql .= " db33_codcpd = $this->db33_codcpd";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->db33_codcpd));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5880,'$this->db33_codcpd','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db33_codcpd"]))
           $resac = db_query("insert into db_acount values($acount,941,5880,'".AddSlashes(pg_result($resaco,$conresaco,'db33_codcpd'))."','$this->db33_codcpd',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db33_codver"]))
           $resac = db_query("insert into db_acount values($acount,941,5879,'".AddSlashes(pg_result($resaco,$conresaco,'db33_codver'))."','$this->db33_codver',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db33_obs"]))
           $resac = db_query("insert into db_acount values($acount,941,5881,'".AddSlashes(pg_result($resaco,$conresaco,'db33_obs'))."','$this->db33_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db33_obscpd"]))
           $resac = db_query("insert into db_acount values($acount,941,5882,'".AddSlashes(pg_result($resaco,$conresaco,'db33_obscpd'))."','$this->db33_obscpd',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db33_data"]))
           $resac = db_query("insert into db_acount values($acount,941,5883,'".AddSlashes(pg_result($resaco,$conresaco,'db33_data'))."','$this->db33_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Versão Para o CPD nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->db33_codcpd;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Versão Para o CPD nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->db33_codcpd;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db33_codcpd;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($db33_codcpd=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($db33_codcpd));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5880,'$db33_codcpd','E')");
         $resac = db_query("insert into db_acount values($acount,941,5880,'','".AddSlashes(pg_result($resaco,$iresaco,'db33_codcpd'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,941,5879,'','".AddSlashes(pg_result($resaco,$iresaco,'db33_codver'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,941,5881,'','".AddSlashes(pg_result($resaco,$iresaco,'db33_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,941,5882,'','".AddSlashes(pg_result($resaco,$iresaco,'db33_obscpd'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,941,5883,'','".AddSlashes(pg_result($resaco,$iresaco,'db33_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from db_versaocpd
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($db33_codcpd != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " db33_codcpd = $db33_codcpd ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Versão Para o CPD nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$db33_codcpd;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Versão Para o CPD nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$db33_codcpd;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$db33_codcpd;
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
        $this->erro_sql   = "Record Vazio na Tabela:db_versaocpd";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $db33_codcpd=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_versaocpd ";
     $sql .= "      inner join db_versao  on  db_versao.db30_codver = db_versaocpd.db33_codver";
     $sql2 = "";
     if($dbwhere==""){
       if($db33_codcpd!=null ){
         $sql2 .= " where db_versaocpd.db33_codcpd = $db33_codcpd "; 
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
   function sql_query_file ( $db33_codcpd=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_versaocpd ";
     $sql2 = "";
     if($dbwhere==""){
       if($db33_codcpd!=null ){
         $sql2 .= " where db_versaocpd.db33_codcpd = $db33_codcpd "; 
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