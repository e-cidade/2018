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

//MODULO: configuracoes
//CLASSE DA ENTIDADE db_docparag
class cl_db_docparag { 
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
   var $db04_docum = 0; 
   var $db04_idparag = 0; 
   var $db04_ordem = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 db04_docum = int4 = Código 
                 db04_idparag = int4 = Códido do parágrafo 
                 db04_ordem = int4 = Ordem do parágrafo 
                 ";
   //funcao construtor da classe 
   function cl_db_docparag() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("db_docparag"); 
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
       $this->db04_docum = ($this->db04_docum == ""?@$GLOBALS["HTTP_POST_VARS"]["db04_docum"]:$this->db04_docum);
       $this->db04_idparag = ($this->db04_idparag == ""?@$GLOBALS["HTTP_POST_VARS"]["db04_idparag"]:$this->db04_idparag);
       $this->db04_ordem = ($this->db04_ordem == ""?@$GLOBALS["HTTP_POST_VARS"]["db04_ordem"]:$this->db04_ordem);
     }else{
       $this->db04_docum = ($this->db04_docum == ""?@$GLOBALS["HTTP_POST_VARS"]["db04_docum"]:$this->db04_docum);
       $this->db04_idparag = ($this->db04_idparag == ""?@$GLOBALS["HTTP_POST_VARS"]["db04_idparag"]:$this->db04_idparag);
     }
   }
   // funcao para inclusao
   function incluir ($db04_docum,$db04_idparag){ 
      $this->atualizacampos();
     if($this->db04_ordem == null ){ 
       $this->erro_sql = " Campo Ordem do parágrafo nao Informado.";
       $this->erro_campo = "db04_ordem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->db04_docum = $db04_docum; 
       $this->db04_idparag = $db04_idparag; 
     if(($this->db04_docum == null) || ($this->db04_docum == "") ){ 
       $this->erro_sql = " Campo db04_docum nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->db04_idparag == null) || ($this->db04_idparag == "") ){ 
       $this->erro_sql = " Campo db04_idparag nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into db_docparag(
                                       db04_docum 
                                      ,db04_idparag 
                                      ,db04_ordem 
                       )
                values (
                                $this->db04_docum 
                               ,$this->db04_idparag 
                               ,$this->db04_ordem 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Paragrafos do documento ($this->db04_docum."-".$this->db04_idparag) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Paragrafos do documento já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Paragrafos do documento ($this->db04_docum."-".$this->db04_idparag) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db04_docum."-".$this->db04_idparag;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->db04_docum,$this->db04_idparag));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,3595,'$this->db04_docum','I')");
       $resac = db_query("insert into db_acountkey values($acount,3596,'$this->db04_idparag','I')");
       $resac = db_query("insert into db_acount values($acount,519,3595,'','".AddSlashes(pg_result($resaco,0,'db04_docum'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,519,3596,'','".AddSlashes(pg_result($resaco,0,'db04_idparag'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,519,3597,'','".AddSlashes(pg_result($resaco,0,'db04_ordem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($db04_docum=null,$db04_idparag=null) { 
      $this->atualizacampos();
     $sql = " update db_docparag set ";
     $virgula = "";
     if(trim($this->db04_docum)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db04_docum"])){ 
        if(trim($this->db04_docum)=="" && isset($GLOBALS["HTTP_POST_VARS"]["db04_docum"])){ 
           $this->db04_docum = "0" ; 
        } 
       $sql  .= $virgula." db04_docum = $this->db04_docum ";
       $virgula = ",";
       if(trim($this->db04_docum) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "db04_docum";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db04_idparag)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db04_idparag"])){ 
        if(trim($this->db04_idparag)=="" && isset($GLOBALS["HTTP_POST_VARS"]["db04_idparag"])){ 
           $this->db04_idparag = "0" ; 
        } 
       $sql  .= $virgula." db04_idparag = $this->db04_idparag ";
       $virgula = ",";
       if(trim($this->db04_idparag) == null ){ 
         $this->erro_sql = " Campo Códido do parágrafo nao Informado.";
         $this->erro_campo = "db04_idparag";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db04_ordem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db04_ordem"])){ 
        if(trim($this->db04_ordem)=="" && isset($GLOBALS["HTTP_POST_VARS"]["db04_ordem"])){ 
           $this->db04_ordem = "0" ; 
        } 
       $sql  .= $virgula." db04_ordem = $this->db04_ordem ";
       $virgula = ",";
       if(trim($this->db04_ordem) == null ){ 
         $this->erro_sql = " Campo Ordem do parágrafo nao Informado.";
         $this->erro_campo = "db04_ordem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($db04_docum!=null){
       $sql .= " db04_docum = $this->db04_docum";
     }
     if($db04_idparag!=null){
       $sql .= " and  db04_idparag = $this->db04_idparag";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->db04_docum,$this->db04_idparag));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,3595,'$this->db04_docum','A')");
         $resac = db_query("insert into db_acountkey values($acount,3596,'$this->db04_idparag','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db04_docum"]))
           $resac = db_query("insert into db_acount values($acount,519,3595,'".AddSlashes(pg_result($resaco,$conresaco,'db04_docum'))."','$this->db04_docum',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db04_idparag"]))
           $resac = db_query("insert into db_acount values($acount,519,3596,'".AddSlashes(pg_result($resaco,$conresaco,'db04_idparag'))."','$this->db04_idparag',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db04_ordem"]))
           $resac = db_query("insert into db_acount values($acount,519,3597,'".AddSlashes(pg_result($resaco,$conresaco,'db04_ordem'))."','$this->db04_ordem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Paragrafos do documento nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->db04_docum."-".$this->db04_idparag;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Paragrafos do documento nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->db04_docum."-".$this->db04_idparag;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db04_docum."-".$this->db04_idparag;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($db04_docum=null,$db04_idparag=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($db04_docum,$db04_idparag));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,3595,'$db04_docum','E')");
         $resac = db_query("insert into db_acountkey values($acount,3596,'$db04_idparag','E')");
         $resac = db_query("insert into db_acount values($acount,519,3595,'','".AddSlashes(pg_result($resaco,$iresaco,'db04_docum'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,519,3596,'','".AddSlashes(pg_result($resaco,$iresaco,'db04_idparag'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,519,3597,'','".AddSlashes(pg_result($resaco,$iresaco,'db04_ordem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from db_docparag
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($db04_docum != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " db04_docum = $db04_docum ";
        }
        if($db04_idparag != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " db04_idparag = $db04_idparag ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Paragrafos do documento nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$db04_docum."-".$db04_idparag;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Paragrafos do documento nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$db04_docum."-".$db04_idparag;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$db04_docum."-".$db04_idparag;
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
        $this->erro_sql   = "Record Vazio na Tabela:db_docparag";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $db04_docum=null,$db04_idparag=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_docparag ";
     $sql .= "      inner join db_paragrafo  on  db_paragrafo.db02_idparag = db_docparag.db04_idparag";
     $sql .= "      inner join db_documento  on  db_documento.db03_docum = db_docparag.db04_docum and db03_instit = " . db_getsession("DB_instit");
     $sql2 = "";
     if($dbwhere==""){
       if($db04_docum!=null ){
         $sql2 .= " where db_docparag.db04_docum = $db04_docum "; 
       } 
       if($db04_idparag!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " db_docparag.db04_idparag = $db04_idparag "; 
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
   function sql_query_doc ( $db04_docum=null,$db04_idparag=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_docparag ";
     $sql .= "      inner join db_paragrafo  on  db_paragrafo.db02_idparag = db_docparag.db04_idparag";
     $sql .= "      inner join db_documento  on  db_documento.db03_docum   = db_docparag.db04_docum and db03_instit = " . db_getsession("DB_instit");
     $sql .= "      inner join db_tipodoc    on  db_tipodoc.db08_codigo    = db_documento.db03_tipodoc";
     $sql2 = "";
     if($dbwhere==""){
       if($db04_docum!=null ){
         $sql2 .= " where db_docparag.db04_docum = $db04_docum "; 
       } 
       if($db04_idparag!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " db_docparag.db04_idparag = $db04_idparag "; 
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
   function sql_query_file ( $db04_docum=null,$db04_idparag=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_docparag ";
     $sql2 = "";
     if($dbwhere==""){
       if($db04_docum!=null ){
         $sql2 .= " where db_docparag.db04_docum = $db04_docum "; 
       } 
       if($db04_idparag!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " db_docparag.db04_idparag = $db04_idparag "; 
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