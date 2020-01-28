<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
//CLASSE DA ENTIDADE cidadaotiporetorno
class cl_cidadaotiporetorno { 
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
   var $ov04_sequencial = 0; 
   var $ov04_seq = 0; 
   var $ov04_tiporetorno = 0; 
   var $ov04_cidadao = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ov04_sequencial = int4 = Sequencial 
                 ov04_seq = int4 = Sequencial 
                 ov04_tiporetorno = int4 = Tipo Retorno 
                 ov04_cidadao = int4 = Cidadão 
                 ";
   //funcao construtor da classe 
   function cl_cidadaotiporetorno() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("cidadaotiporetorno"); 
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
       $this->ov04_sequencial = ($this->ov04_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ov04_sequencial"]:$this->ov04_sequencial);
       $this->ov04_seq = ($this->ov04_seq == ""?@$GLOBALS["HTTP_POST_VARS"]["ov04_seq"]:$this->ov04_seq);
       $this->ov04_tiporetorno = ($this->ov04_tiporetorno == ""?@$GLOBALS["HTTP_POST_VARS"]["ov04_tiporetorno"]:$this->ov04_tiporetorno);
       $this->ov04_cidadao = ($this->ov04_cidadao == ""?@$GLOBALS["HTTP_POST_VARS"]["ov04_cidadao"]:$this->ov04_cidadao);
     }else{
       $this->ov04_sequencial = ($this->ov04_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ov04_sequencial"]:$this->ov04_sequencial);
       $this->ov04_seq = ($this->ov04_seq == ""?@$GLOBALS["HTTP_POST_VARS"]["ov04_seq"]:$this->ov04_seq);
     }
   }
   // funcao para inclusao
   function incluir ($ov04_sequencial){ 
      $this->atualizacampos();
     if($this->ov04_tiporetorno == null ){ 
       $this->erro_sql = " Campo Tipo Retorno nao Informado.";
       $this->erro_campo = "ov04_tiporetorno";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ov04_cidadao == null ){ 
       $this->erro_sql = " Campo Cidadão nao Informado.";
       $this->erro_campo = "ov04_cidadao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ov04_sequencial == "" || $ov04_sequencial == null ){
       $result = db_query("select nextval('cidadaotiporetorno_ov04_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: cidadaotiporetorno_ov04_sequencial_seq do campo: ov04_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ov04_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from cidadaotiporetorno_ov04_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ov04_sequencial)){
         $this->erro_sql = " Campo ov04_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ov04_sequencial = $ov04_sequencial; 
       }
     }
     if(($this->ov04_sequencial == null) || ($this->ov04_sequencial == "") ){ 
       $this->erro_sql = " Campo ov04_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into cidadaotiporetorno(
                                       ov04_sequencial 
                                      ,ov04_seq 
                                      ,ov04_tiporetorno 
                                      ,ov04_cidadao 
                       )
                values (
                                $this->ov04_sequencial 
                               ,$this->ov04_seq 
                               ,$this->ov04_tiporetorno 
                               ,$this->ov04_cidadao 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Tipo de Retorno para o cidadao ($this->ov04_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Tipo de Retorno para o cidadao já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Tipo de Retorno para o cidadao ($this->ov04_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ov04_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     if (!isset($_SESSION["DB_usaAccount"])) {

       $resaco = $this->sql_record($this->sql_query_file($this->ov04_sequencial));
       if(($resaco!=false)||($this->numrows!=0)){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14761,'$this->ov04_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,2597,14761,'','".AddSlashes(pg_result($resaco,0,'ov04_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2597,14762,'','".AddSlashes(pg_result($resaco,0,'ov04_seq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2597,14763,'','".AddSlashes(pg_result($resaco,0,'ov04_tiporetorno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2597,14764,'','".AddSlashes(pg_result($resaco,0,'ov04_cidadao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ov04_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update cidadaotiporetorno set ";
     $virgula = "";
     if(trim($this->ov04_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ov04_sequencial"])){ 
       $sql  .= $virgula." ov04_sequencial = $this->ov04_sequencial ";
       $virgula = ",";
       if(trim($this->ov04_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "ov04_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ov04_seq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ov04_seq"])){ 
       $sql  .= $virgula." ov04_seq = $this->ov04_seq ";
       $virgula = ",";
       if(trim($this->ov04_seq) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "ov04_seq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ov04_tiporetorno)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ov04_tiporetorno"])){ 
       $sql  .= $virgula." ov04_tiporetorno = $this->ov04_tiporetorno ";
       $virgula = ",";
       if(trim($this->ov04_tiporetorno) == null ){ 
         $this->erro_sql = " Campo Tipo Retorno nao Informado.";
         $this->erro_campo = "ov04_tiporetorno";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ov04_cidadao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ov04_cidadao"])){ 
       $sql  .= $virgula." ov04_cidadao = $this->ov04_cidadao ";
       $virgula = ",";
       if(trim($this->ov04_cidadao) == null ){ 
         $this->erro_sql = " Campo Cidadão nao Informado.";
         $this->erro_campo = "ov04_cidadao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ov04_sequencial!=null){
       $sql .= " ov04_sequencial = $this->ov04_sequencial";
     }
     
     if (!isset($_SESSION["DB_usaAccount"])) {
       
       $resaco = $this->sql_record($this->sql_query_file($this->ov04_sequencial));
       if($this->numrows>0){
         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,14761,'$this->ov04_sequencial','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ov04_sequencial"]) || $this->ov04_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,2597,14761,'".AddSlashes(pg_result($resaco,$conresaco,'ov04_sequencial'))."','$this->ov04_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ov04_seq"]) || $this->ov04_seq != "")
             $resac = db_query("insert into db_acount values($acount,2597,14762,'".AddSlashes(pg_result($resaco,$conresaco,'ov04_seq'))."','$this->ov04_seq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ov04_tiporetorno"]) || $this->ov04_tiporetorno != "")
             $resac = db_query("insert into db_acount values($acount,2597,14763,'".AddSlashes(pg_result($resaco,$conresaco,'ov04_tiporetorno'))."','$this->ov04_tiporetorno',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ov04_cidadao"]) || $this->ov04_cidadao != "")
             $resac = db_query("insert into db_acount values($acount,2597,14764,'".AddSlashes(pg_result($resaco,$conresaco,'ov04_cidadao'))."','$this->ov04_cidadao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tipo de Retorno para o cidadao nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ov04_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Tipo de Retorno para o cidadao nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ov04_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ov04_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ov04_sequencial=null,$dbwhere=null) { 
     
     if (!isset($_SESSION["DB_usaAccount"])) {
       if($dbwhere==null || $dbwhere==""){
         $resaco = $this->sql_record($this->sql_query_file($ov04_sequencial));
       }else{ 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if(($resaco!=false)||($this->numrows!=0)){
         for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,14761,'$ov04_sequencial','E')");
           $resac = db_query("insert into db_acount values($acount,2597,14761,'','".AddSlashes(pg_result($resaco,$iresaco,'ov04_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,2597,14762,'','".AddSlashes(pg_result($resaco,$iresaco,'ov04_seq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,2597,14763,'','".AddSlashes(pg_result($resaco,$iresaco,'ov04_tiporetorno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,2597,14764,'','".AddSlashes(pg_result($resaco,$iresaco,'ov04_cidadao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from cidadaotiporetorno
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ov04_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ov04_sequencial = $ov04_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tipo de Retorno para o cidadao nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ov04_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Tipo de Retorno para o cidadao nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ov04_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ov04_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:cidadaotiporetorno";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ov04_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cidadaotiporetorno ";
     $sql .= "      inner join tiporetorno  on  tiporetorno.ov22_sequencial = cidadaotiporetorno.ov04_tiporetorno";
     $sql .= "      inner join cidadao  on  cidadao.ov02_sequencial = cidadaotiporetorno.ov04_seq and  cidadao.ov02_seq = cidadaotiporetorno.ov04_cidadao";
     $sql .= "      inner join situacaocidadao  on  situacaocidadao.ov16_sequencial = cidadao.ov02_situacaocidadao";
     $sql2 = "";
     if($dbwhere==""){
       if($ov04_sequencial!=null ){
         $sql2 .= " where cidadaotiporetorno.ov04_sequencial = $ov04_sequencial "; 
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
   function sql_query_file ( $ov04_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cidadaotiporetorno ";
     $sql2 = "";
     if($dbwhere==""){
       if($ov04_sequencial!=null ){
         $sql2 .= " where cidadaotiporetorno.ov04_sequencial = $ov04_sequencial "; 
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