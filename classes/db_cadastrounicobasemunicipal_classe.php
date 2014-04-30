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

//MODULO: social
//CLASSE DA ENTIDADE cadastrounicobasemunicipal
class cl_cadastrounicobasemunicipal { 
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
   var $as09_sequencial = 0; 
   var $as09_tiporegistro = 0; 
   var $as09_chaveregistro = null; 
   var $as09_conteudolinha = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 as09_sequencial = int4 = Código Base Municipal 
                 as09_tiporegistro = int4 = Tipo de Registro 
                 as09_chaveregistro = varchar(100) = Chave de Registro 
                 as09_conteudolinha = text = Conteúdo da Linha 
                 ";
   //funcao construtor da classe 
   function cl_cadastrounicobasemunicipal() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("cadastrounicobasemunicipal"); 
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
       $this->as09_sequencial = ($this->as09_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["as09_sequencial"]:$this->as09_sequencial);
       $this->as09_tiporegistro = ($this->as09_tiporegistro == ""?@$GLOBALS["HTTP_POST_VARS"]["as09_tiporegistro"]:$this->as09_tiporegistro);
       $this->as09_chaveregistro = ($this->as09_chaveregistro == ""?@$GLOBALS["HTTP_POST_VARS"]["as09_chaveregistro"]:$this->as09_chaveregistro);
       $this->as09_conteudolinha = ($this->as09_conteudolinha == ""?@$GLOBALS["HTTP_POST_VARS"]["as09_conteudolinha"]:$this->as09_conteudolinha);
     }else{
       $this->as09_sequencial = ($this->as09_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["as09_sequencial"]:$this->as09_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($as09_sequencial){ 
      $this->atualizacampos();
     if($this->as09_tiporegistro == null ){ 
       $this->erro_sql = " Campo Tipo de Registro nao Informado.";
       $this->erro_campo = "as09_tiporegistro";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->as09_chaveregistro == null ){ 
       $this->erro_sql = " Campo Chave de Registro nao Informado.";
       $this->erro_campo = "as09_chaveregistro";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->as09_conteudolinha == null ){ 
       $this->erro_sql = " Campo Conteúdo da Linha nao Informado.";
       $this->erro_campo = "as09_conteudolinha";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($as09_sequencial == "" || $as09_sequencial == null ){
       $result = db_query("select nextval('cadastrounicobasemunicipal_as09_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: cadastrounicobasemunicipal_as09_sequencial_seq do campo: as09_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->as09_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from cadastrounicobasemunicipal_as09_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $as09_sequencial)){
         $this->erro_sql = " Campo as09_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->as09_sequencial = $as09_sequencial; 
       }
     }
     if(($this->as09_sequencial == null) || ($this->as09_sequencial == "") ){ 
       $this->erro_sql = " Campo as09_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into cadastrounicobasemunicipal(
                                       as09_sequencial 
                                      ,as09_tiporegistro 
                                      ,as09_chaveregistro 
                                      ,as09_conteudolinha 
                       )
                values (
                                $this->as09_sequencial 
                               ,$this->as09_tiporegistro 
                               ,'$this->as09_chaveregistro' 
                               ,'$this->as09_conteudolinha' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "cadastrounicobasemunicipal ($this->as09_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "cadastrounicobasemunicipal já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "cadastrounicobasemunicipal ($this->as09_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
     $this->erro_sql .= "Valores : ".$this->as09_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     if (!isset($_SESSION["DB_usaAccount"])) {
      
       $resaco = $this->sql_record($this->sql_query_file($this->as09_sequencial));
       if(($resaco!=false)||($this->numrows!=0)){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19166,'$this->as09_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3405,19166,'','".AddSlashes(pg_result($resaco,0,'as09_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3405,19167,'','".AddSlashes(pg_result($resaco,0,'as09_tiporegistro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3405,19168,'','".AddSlashes(pg_result($resaco,0,'as09_chaveregistro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3405,19169,'','".AddSlashes(pg_result($resaco,0,'as09_conteudolinha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($as09_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update cadastrounicobasemunicipal set ";
     $virgula = "";
     if(trim($this->as09_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["as09_sequencial"])){ 
       $sql  .= $virgula." as09_sequencial = $this->as09_sequencial ";
       $virgula = ",";
       if(trim($this->as09_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Base Municipal nao Informado.";
         $this->erro_campo = "as09_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->as09_tiporegistro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["as09_tiporegistro"])){ 
       $sql  .= $virgula." as09_tiporegistro = $this->as09_tiporegistro ";
       $virgula = ",";
       if(trim($this->as09_tiporegistro) == null ){ 
         $this->erro_sql = " Campo Tipo de Registro nao Informado.";
         $this->erro_campo = "as09_tiporegistro";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->as09_chaveregistro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["as09_chaveregistro"])){ 
       $sql  .= $virgula." as09_chaveregistro = '$this->as09_chaveregistro' ";
       $virgula = ",";
       if(trim($this->as09_chaveregistro) == null ){ 
         $this->erro_sql = " Campo Chave de Registro nao Informado.";
         $this->erro_campo = "as09_chaveregistro";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->as09_conteudolinha)!="" || isset($GLOBALS["HTTP_POST_VARS"]["as09_conteudolinha"])){ 
       $sql  .= $virgula." as09_conteudolinha = '$this->as09_conteudolinha' ";
       $virgula = ",";
       if(trim($this->as09_conteudolinha) == null ){ 
         $this->erro_sql = " Campo Conteúdo da Linha nao Informado.";
         $this->erro_campo = "as09_conteudolinha";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($as09_sequencial!=null){
       $sql .= " as09_sequencial = $this->as09_sequencial";
     }
     if (!isset($_SESSION["DB_usaAccount"])) {
       
       $resaco = $this->sql_record($this->sql_query_file($this->as09_sequencial));
       if($this->numrows>0){
         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,19166,'$this->as09_sequencial','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["as09_sequencial"]) || $this->as09_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3405,19166,'".AddSlashes(pg_result($resaco,$conresaco,'as09_sequencial'))."','$this->as09_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["as09_tiporegistro"]) || $this->as09_tiporegistro != "")
             $resac = db_query("insert into db_acount values($acount,3405,19167,'".AddSlashes(pg_result($resaco,$conresaco,'as09_tiporegistro'))."','$this->as09_tiporegistro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["as09_chaveregistro"]) || $this->as09_chaveregistro != "")
             $resac = db_query("insert into db_acount values($acount,3405,19168,'".AddSlashes(pg_result($resaco,$conresaco,'as09_chaveregistro'))."','$this->as09_chaveregistro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["as09_conteudolinha"]) || $this->as09_conteudolinha != "")
             $resac = db_query("insert into db_acount values($acount,3405,19169,'".AddSlashes(pg_result($resaco,$conresaco,'as09_conteudolinha'))."','$this->as09_conteudolinha',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "cadastrounicobasemunicipal nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->as09_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "cadastrounicobasemunicipal nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->as09_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->as09_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($as09_sequencial=null,$dbwhere=null) { 
     
     if (!isset($_SESSION["DB_usaAccount"])) {
       
       if($dbwhere==null || $dbwhere==""){
         $resaco = $this->sql_record($this->sql_query_file($as09_sequencial));
       }else{ 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if(($resaco!=false)||($this->numrows!=0)){
         for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,19166,'$as09_sequencial','E')");
           $resac = db_query("insert into db_acount values($acount,3405,19166,'','".AddSlashes(pg_result($resaco,$iresaco,'as09_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,3405,19167,'','".AddSlashes(pg_result($resaco,$iresaco,'as09_tiporegistro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,3405,19168,'','".AddSlashes(pg_result($resaco,$iresaco,'as09_chaveregistro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,3405,19169,'','".AddSlashes(pg_result($resaco,$iresaco,'as09_conteudolinha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from cadastrounicobasemunicipal
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($as09_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " as09_sequencial = $as09_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "cadastrounicobasemunicipal nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$as09_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "cadastrounicobasemunicipal nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$as09_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$as09_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:cadastrounicobasemunicipal";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $as09_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cadastrounicobasemunicipal ";
     $sql2 = "";
     if($dbwhere==""){
       if($as09_sequencial!=null ){
         $sql2 .= " where cadastrounicobasemunicipal.as09_sequencial = $as09_sequencial "; 
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
   function sql_query_file ( $as09_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cadastrounicobasemunicipal ";
     $sql2 = "";
     if($dbwhere==""){
       if($as09_sequencial!=null ){
         $sql2 .= " where cadastrounicobasemunicipal.as09_sequencial = $as09_sequencial "; 
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
  
  /**
   * Vinculo da base municipal com o cidadao
   * @param string $as09_sequencial
   * @param string $campos
   * @param string $ordem
   * @param string $dbwhere
   * @return string sql
   */
  function sql_query_base_cidadao ($as09_sequencial=null,$campos="*",$ordem=null,$dbwhere="") {
    
    $sql = "select ";
    if($campos != "*" ) {
      
      $campos_sql = split("#",$campos);
      $virgula = "";
      for($i=0;$i<sizeof($campos_sql);$i++) {
        
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from cadastrounicobasemunicipal ";
    $sql .= "      inner join cidadaocadastrounico on as02_codigounicocidadao = as09_chaveregistro";
    $sql .= "                                     and as09_tiporegistro = 4";
    $sql2 = "";
    if($dbwhere=="") {
      
      if($as09_sequencial!=null ){
        $sql2 .= " where cadastrounicobasemunicipal.as09_sequencial = $as09_sequencial ";
      }
    } else if($dbwhere != "") {
      
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if($ordem != null ) {
      
      $sql       .= " order by ";
      $campos_sql = split("#",$ordem);
      $virgula    = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }
  
  /**
   * Vinculo da base municipal com a familia
   * @param string $as09_sequencial
   * @param string $campos
   * @param string $ordem
   * @param string $dbwhere
   * @return string sql
   */
  function sql_query_base_familia ($as09_sequencial=null,$campos="*",$ordem=null,$dbwhere="") {
  
    $sql = "select ";
    if($campos != "*" ) {
  
      $campos_sql = split("#",$campos);
      $virgula = "";
      for($i=0;$i<sizeof($campos_sql);$i++) {
  
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from cadastrounicobasemunicipal ";
    $sql .= "      inner join cidadaofamiliacadastrounico on as15_codigofamiliarcadastrounico = as09_chaveregistro ";  
    $sql .= "                                            and  as09_tiporegistro = 1 ";
    $sql2 = "";
    if($dbwhere=="") {
  
      if($as09_sequencial!=null ){
        $sql2 .= " where cadastrounicobasemunicipal.as09_sequencial = $as09_sequencial ";
      }
    } else if($dbwhere != "") {
  
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if($ordem != null ) {
  
      $sql       .= " order by ";
      $campos_sql = split("#",$ordem);
      $virgula    = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
  
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }
}
?>