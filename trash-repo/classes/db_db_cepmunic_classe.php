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

//MODULO: protocolo
//CLASSE DA ENTIDADE db_cepmunic
class cl_db_cepmunic { 
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
   var $db10_codigo = 0; 
   var $db10_munic = null; 
   var $db10_cep = null; 
   var $db10_uf = 0; 
   var $db10_codibge = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 db10_codigo = int8 = Código 
                 db10_munic = varchar(60) = Município 
                 db10_cep = varchar(8) = Cep 
                 db10_uf = int8 = UF 
                 db10_codibge = int4 = Código do município no cadastro do IBGE 
                 ";
   //funcao construtor da classe 
   function cl_db_cepmunic() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("db_cepmunic"); 
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
       $this->db10_codigo = ($this->db10_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["db10_codigo"]:$this->db10_codigo);
       $this->db10_munic = ($this->db10_munic == ""?@$GLOBALS["HTTP_POST_VARS"]["db10_munic"]:$this->db10_munic);
       $this->db10_cep = ($this->db10_cep == ""?@$GLOBALS["HTTP_POST_VARS"]["db10_cep"]:$this->db10_cep);
       $this->db10_uf = ($this->db10_uf == ""?@$GLOBALS["HTTP_POST_VARS"]["db10_uf"]:$this->db10_uf);
       $this->db10_codibge = ($this->db10_codibge == ""?@$GLOBALS["HTTP_POST_VARS"]["db10_codibge"]:$this->db10_codibge);
     }else{
       $this->db10_codigo = ($this->db10_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["db10_codigo"]:$this->db10_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($db10_codigo){ 
      $this->atualizacampos();
     if($this->db10_munic == null ){ 
       $this->erro_sql = " Campo Município nao Informado.";
       $this->erro_campo = "db10_munic";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db10_cep == null ){ 
       $this->erro_sql = " Campo Cep nao Informado.";
       $this->erro_campo = "db10_cep";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db10_uf == null ){ 
       $this->erro_sql = " Campo UF nao Informado.";
       $this->erro_campo = "db10_uf";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db10_codibge == null ){ 
       $this->db10_codibge = "0";
     }
     if($db10_codigo == "" || $db10_codigo == null ){
       $result = db_query("select nextval('db_cepmunic_db10_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: db_cepmunic_db10_codigo_seq do campo: db10_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->db10_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from db_cepmunic_db10_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $db10_codigo)){
         $this->erro_sql = " Campo db10_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->db10_codigo = $db10_codigo; 
       }
     }
     if(($this->db10_codigo == null) || ($this->db10_codigo == "") ){ 
       $this->erro_sql = " Campo db10_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into db_cepmunic(
                                       db10_codigo 
                                      ,db10_munic 
                                      ,db10_cep 
                                      ,db10_uf 
                                      ,db10_codibge 
                       )
                values (
                                $this->db10_codigo 
                               ,'$this->db10_munic' 
                               ,'$this->db10_cep' 
                               ,$this->db10_uf 
                               ,$this->db10_codibge 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cep do municipio ($this->db10_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cep do municipio já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cep do municipio ($this->db10_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db10_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->db10_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,4819,'$this->db10_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,648,4819,'','".AddSlashes(pg_result($resaco,0,'db10_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,648,4820,'','".AddSlashes(pg_result($resaco,0,'db10_munic'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,648,4829,'','".AddSlashes(pg_result($resaco,0,'db10_cep'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,648,4821,'','".AddSlashes(pg_result($resaco,0,'db10_uf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,648,6013,'','".AddSlashes(pg_result($resaco,0,'db10_codibge'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($db10_codigo=null) { 
      $this->atualizacampos();
     $sql = " update db_cepmunic set ";
     $virgula = "";
     if(trim($this->db10_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db10_codigo"])){ 
       $sql  .= $virgula." db10_codigo = $this->db10_codigo ";
       $virgula = ",";
       if(trim($this->db10_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "db10_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db10_munic)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db10_munic"])){ 
       $sql  .= $virgula." db10_munic = '$this->db10_munic' ";
       $virgula = ",";
       if(trim($this->db10_munic) == null ){ 
         $this->erro_sql = " Campo Município nao Informado.";
         $this->erro_campo = "db10_munic";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db10_cep)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db10_cep"])){ 
       $sql  .= $virgula." db10_cep = '$this->db10_cep' ";
       $virgula = ",";
       if(trim($this->db10_cep) == null ){ 
         $this->erro_sql = " Campo Cep nao Informado.";
         $this->erro_campo = "db10_cep";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db10_uf)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db10_uf"])){ 
       $sql  .= $virgula." db10_uf = $this->db10_uf ";
       $virgula = ",";
       if(trim($this->db10_uf) == null ){ 
         $this->erro_sql = " Campo UF nao Informado.";
         $this->erro_campo = "db10_uf";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db10_codibge)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db10_codibge"])){ 
        if(trim($this->db10_codibge)=="" && isset($GLOBALS["HTTP_POST_VARS"]["db10_codibge"])){ 
           $this->db10_codibge = "0" ; 
        } 
       $sql  .= $virgula." db10_codibge = $this->db10_codibge ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($db10_codigo!=null){
       $sql .= " db10_codigo = $this->db10_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->db10_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4819,'$this->db10_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db10_codigo"]))
           $resac = db_query("insert into db_acount values($acount,648,4819,'".AddSlashes(pg_result($resaco,$conresaco,'db10_codigo'))."','$this->db10_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db10_munic"]))
           $resac = db_query("insert into db_acount values($acount,648,4820,'".AddSlashes(pg_result($resaco,$conresaco,'db10_munic'))."','$this->db10_munic',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db10_cep"]))
           $resac = db_query("insert into db_acount values($acount,648,4829,'".AddSlashes(pg_result($resaco,$conresaco,'db10_cep'))."','$this->db10_cep',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db10_uf"]))
           $resac = db_query("insert into db_acount values($acount,648,4821,'".AddSlashes(pg_result($resaco,$conresaco,'db10_uf'))."','$this->db10_uf',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db10_codibge"]))
           $resac = db_query("insert into db_acount values($acount,648,6013,'".AddSlashes(pg_result($resaco,$conresaco,'db10_codibge'))."','$this->db10_codibge',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cep do municipio nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->db10_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cep do municipio nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->db10_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db10_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($db10_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($db10_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4819,'$db10_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,648,4819,'','".AddSlashes(pg_result($resaco,$iresaco,'db10_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,648,4820,'','".AddSlashes(pg_result($resaco,$iresaco,'db10_munic'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,648,4829,'','".AddSlashes(pg_result($resaco,$iresaco,'db10_cep'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,648,4821,'','".AddSlashes(pg_result($resaco,$iresaco,'db10_uf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,648,6013,'','".AddSlashes(pg_result($resaco,$iresaco,'db10_codibge'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from db_cepmunic
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($db10_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " db10_codigo = $db10_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cep do municipio nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$db10_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cep do municipio nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$db10_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$db10_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:db_cepmunic";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $db10_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_cepmunic ";
     $sql .= "      inner join db_uf  on  db_uf.db12_codigo = db_cepmunic.db10_uf";
     $sql2 = "";
     if($dbwhere==""){
       if($db10_codigo!=null ){
         $sql2 .= " where db_cepmunic.db10_codigo = $db10_codigo "; 
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
   function sql_query_file ( $db10_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_cepmunic ";
     $sql2 = "";
     if($dbwhere==""){
       if($db10_codigo!=null ){
         $sql2 .= " where db_cepmunic.db10_codigo = $db10_codigo "; 
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