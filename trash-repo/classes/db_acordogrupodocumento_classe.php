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

//MODULO: Acordos
//CLASSE DA ENTIDADE acordogrupodocumento
class cl_acordogrupodocumento { 
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
   var $ac06_sequencial = 0; 
   var $ac06_acordogrupo = 0; 
   var $ac06_tipodocumento = 0; 
   var $ac06_documentotemplate = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ac06_sequencial = int4 = Sequencial 
                 ac06_acordogrupo = int4 = Acordo Grupo 
                 ac06_tipodocumento = int4 = Tipo de Documento 
                 ac06_documentotemplate = int4 = Documento Template 
                 ";
   //funcao construtor da classe 
   function cl_acordogrupodocumento() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("acordogrupodocumento"); 
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
       $this->ac06_sequencial = ($this->ac06_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ac06_sequencial"]:$this->ac06_sequencial);
       $this->ac06_acordogrupo = ($this->ac06_acordogrupo == ""?@$GLOBALS["HTTP_POST_VARS"]["ac06_acordogrupo"]:$this->ac06_acordogrupo);
       $this->ac06_tipodocumento = ($this->ac06_tipodocumento == ""?@$GLOBALS["HTTP_POST_VARS"]["ac06_tipodocumento"]:$this->ac06_tipodocumento);
       $this->ac06_documentotemplate = ($this->ac06_documentotemplate == ""?@$GLOBALS["HTTP_POST_VARS"]["ac06_documentotemplate"]:$this->ac06_documentotemplate);
     }else{
       $this->ac06_sequencial = ($this->ac06_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ac06_sequencial"]:$this->ac06_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ac06_sequencial){ 
      $this->atualizacampos();
     if($this->ac06_acordogrupo == null ){ 
       $this->erro_sql = " Campo Acordo Grupo nao Informado.";
       $this->erro_campo = "ac06_acordogrupo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ac06_tipodocumento == null ){ 
       $this->erro_sql = " Campo Tipo de Documento nao Informado.";
       $this->erro_campo = "ac06_tipodocumento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ac06_documentotemplate == null ){ 
       $this->erro_sql = " Campo Documento Template nao Informado.";
       $this->erro_campo = "ac06_documentotemplate";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ac06_sequencial == "" || $ac06_sequencial == null ){
       $result = db_query("select nextval('acordogrupodocumento_ac06_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: acordogrupodocumento_ac06_sequencial_seq do campo: ac06_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ac06_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from acordogrupodocumento_ac06_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ac06_sequencial)){
         $this->erro_sql = " Campo ac06_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ac06_sequencial = $ac06_sequencial; 
       }
     }
     if(($this->ac06_sequencial == null) || ($this->ac06_sequencial == "") ){ 
       $this->erro_sql = " Campo ac06_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into acordogrupodocumento(
                                       ac06_sequencial 
                                      ,ac06_acordogrupo 
                                      ,ac06_tipodocumento 
                                      ,ac06_documentotemplate 
                       )
                values (
                                $this->ac06_sequencial 
                               ,$this->ac06_acordogrupo 
                               ,$this->ac06_tipodocumento 
                               ,$this->ac06_documentotemplate 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Acordo Grupo Documento ($this->ac06_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Acordo Grupo Documento já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Acordo Grupo Documento ($this->ac06_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ac06_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ac06_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,16104,'$this->ac06_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2825,16104,'','".AddSlashes(pg_result($resaco,0,'ac06_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2825,16105,'','".AddSlashes(pg_result($resaco,0,'ac06_acordogrupo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2825,16106,'','".AddSlashes(pg_result($resaco,0,'ac06_tipodocumento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2825,16107,'','".AddSlashes(pg_result($resaco,0,'ac06_documentotemplate'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ac06_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update acordogrupodocumento set ";
     $virgula = "";
     if(trim($this->ac06_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac06_sequencial"])){ 
       $sql  .= $virgula." ac06_sequencial = $this->ac06_sequencial ";
       $virgula = ",";
       if(trim($this->ac06_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "ac06_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac06_acordogrupo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac06_acordogrupo"])){ 
       $sql  .= $virgula." ac06_acordogrupo = $this->ac06_acordogrupo ";
       $virgula = ",";
       if(trim($this->ac06_acordogrupo) == null ){ 
         $this->erro_sql = " Campo Acordo Grupo nao Informado.";
         $this->erro_campo = "ac06_acordogrupo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac06_tipodocumento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac06_tipodocumento"])){ 
       $sql  .= $virgula." ac06_tipodocumento = $this->ac06_tipodocumento ";
       $virgula = ",";
       if(trim($this->ac06_tipodocumento) == null ){ 
         $this->erro_sql = " Campo Tipo de Documento nao Informado.";
         $this->erro_campo = "ac06_tipodocumento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac06_documentotemplate)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac06_documentotemplate"])){ 
       $sql  .= $virgula." ac06_documentotemplate = $this->ac06_documentotemplate ";
       $virgula = ",";
       if(trim($this->ac06_documentotemplate) == null ){ 
         $this->erro_sql = " Campo Documento Template nao Informado.";
         $this->erro_campo = "ac06_documentotemplate";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ac06_sequencial!=null){
       $sql .= " ac06_sequencial = $this->ac06_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ac06_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16104,'$this->ac06_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac06_sequencial"]) || $this->ac06_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2825,16104,'".AddSlashes(pg_result($resaco,$conresaco,'ac06_sequencial'))."','$this->ac06_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac06_acordogrupo"]) || $this->ac06_acordogrupo != "")
           $resac = db_query("insert into db_acount values($acount,2825,16105,'".AddSlashes(pg_result($resaco,$conresaco,'ac06_acordogrupo'))."','$this->ac06_acordogrupo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac06_tipodocumento"]) || $this->ac06_tipodocumento != "")
           $resac = db_query("insert into db_acount values($acount,2825,16106,'".AddSlashes(pg_result($resaco,$conresaco,'ac06_tipodocumento'))."','$this->ac06_tipodocumento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac06_documentotemplate"]) || $this->ac06_documentotemplate != "")
           $resac = db_query("insert into db_acount values($acount,2825,16107,'".AddSlashes(pg_result($resaco,$conresaco,'ac06_documentotemplate'))."','$this->ac06_documentotemplate',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Acordo Grupo Documento nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ac06_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Acordo Grupo Documento nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ac06_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ac06_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ac06_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ac06_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16104,'$ac06_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2825,16104,'','".AddSlashes(pg_result($resaco,$iresaco,'ac06_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2825,16105,'','".AddSlashes(pg_result($resaco,$iresaco,'ac06_acordogrupo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2825,16106,'','".AddSlashes(pg_result($resaco,$iresaco,'ac06_tipodocumento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2825,16107,'','".AddSlashes(pg_result($resaco,$iresaco,'ac06_documentotemplate'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from acordogrupodocumento
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ac06_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ac06_sequencial = $ac06_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Acordo Grupo Documento nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ac06_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Acordo Grupo Documento nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ac06_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ac06_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:acordogrupodocumento";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ac06_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from acordogrupodocumento ";
     $sql .= "      inner join db_documentotemplate  on  db_documentotemplate.db82_sequencial = acordogrupodocumento.ac06_documentotemplate";
     $sql .= "      inner join acordogrupo  on  acordogrupo.ac02_sequencial = acordogrupodocumento.ac06_acordogrupo";
     $sql .= "      inner join db_config  on  db_config.codigo = db_documentotemplate.db82_instit";
     $sql .= "      inner join db_documentotemplatetipo  on  db_documentotemplatetipo.db80_sequencial = db_documentotemplate.db82_templatetipo";
     $sql .= "      inner join acordonatureza  on  acordonatureza.ac01_sequencial = acordogrupo.ac02_acordonatureza";
     $sql .= "      inner join acordotipo  on  acordotipo.ac04_sequencial = acordogrupo.ac02_acordotipo";
     $sql2 = "";
     if($dbwhere==""){
       if($ac06_sequencial!=null ){
         $sql2 .= " where acordogrupodocumento.ac06_sequencial = $ac06_sequencial "; 
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
   function sql_query_file ( $ac06_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from acordogrupodocumento ";
     $sql2 = "";
     if($dbwhere==""){
       if($ac06_sequencial!=null ){
         $sql2 .= " where acordogrupodocumento.ac06_sequencial = $ac06_sequencial "; 
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