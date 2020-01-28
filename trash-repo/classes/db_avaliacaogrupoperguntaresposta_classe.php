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

//MODULO: Habitacao
//CLASSE DA ENTIDADE avaliacaogrupoperguntaresposta
class cl_avaliacaogrupoperguntaresposta { 
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
   var $db108_sequencial = 0; 
   var $db108_avaliacaogruporesposta = 0; 
   var $db108_avaliacaoresposta = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 db108_sequencial = int4 = Sequencial 
                 db108_avaliacaogruporesposta = int4 = Avalia��o Grupo Resposta 
                 db108_avaliacaoresposta = int4 = Avalia��o Resposta 
                 ";
   //funcao construtor da classe 
   function cl_avaliacaogrupoperguntaresposta() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("avaliacaogrupoperguntaresposta"); 
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
       $this->db108_sequencial = ($this->db108_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["db108_sequencial"]:$this->db108_sequencial);
       $this->db108_avaliacaogruporesposta = ($this->db108_avaliacaogruporesposta == ""?@$GLOBALS["HTTP_POST_VARS"]["db108_avaliacaogruporesposta"]:$this->db108_avaliacaogruporesposta);
       $this->db108_avaliacaoresposta = ($this->db108_avaliacaoresposta == ""?@$GLOBALS["HTTP_POST_VARS"]["db108_avaliacaoresposta"]:$this->db108_avaliacaoresposta);
     }else{
       $this->db108_sequencial = ($this->db108_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["db108_sequencial"]:$this->db108_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($db108_sequencial){ 
      $this->atualizacampos();
     if($this->db108_avaliacaogruporesposta == null ){ 
       $this->erro_sql = " Campo Avalia��o Grupo Resposta nao Informado.";
       $this->erro_campo = "db108_avaliacaogruporesposta";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db108_avaliacaoresposta == null ){ 
       $this->erro_sql = " Campo Avalia��o Resposta nao Informado.";
       $this->erro_campo = "db108_avaliacaoresposta";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($db108_sequencial == "" || $db108_sequencial == null ){
       $result = db_query("select nextval('avaliacaogrupoperguntaresposta_db108_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: avaliacaogrupoperguntaresposta_db108_sequencial_seq do campo: db108_sequencial"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->db108_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from avaliacaogrupoperguntaresposta_db108_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $db108_sequencial)){
         $this->erro_sql = " Campo db108_sequencial maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->db108_sequencial = $db108_sequencial; 
       }
     }
     if(($this->db108_sequencial == null) || ($this->db108_sequencial == "") ){ 
       $this->erro_sql = " Campo db108_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into avaliacaogrupoperguntaresposta(
                                       db108_sequencial 
                                      ,db108_avaliacaogruporesposta 
                                      ,db108_avaliacaoresposta 
                       )
                values (
                                $this->db108_sequencial 
                               ,$this->db108_avaliacaogruporesposta 
                               ,$this->db108_avaliacaoresposta 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Avalia��o Grupo Pergunta Resposta ($this->db108_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Avalia��o Grupo Pergunta Resposta j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Avalia��o Grupo Pergunta Resposta ($this->db108_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db108_sequencial;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     if (!isset($_SESSION["DB_usaAccount"])) {
       
       $resaco = $this->sql_record($this->sql_query_file($this->db108_sequencial));
       if(($resaco!=false)||($this->numrows!=0)){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16931,'$this->db108_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,2988,16931,'','".AddSlashes(pg_result($resaco,0,'db108_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2988,16932,'','".AddSlashes(pg_result($resaco,0,'db108_avaliacaogruporesposta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2988,16933,'','".AddSlashes(pg_result($resaco,0,'db108_avaliacaoresposta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($db108_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update avaliacaogrupoperguntaresposta set ";
     $virgula = "";
     if(trim($this->db108_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db108_sequencial"])){ 
       $sql  .= $virgula." db108_sequencial = $this->db108_sequencial ";
       $virgula = ",";
       if(trim($this->db108_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "db108_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db108_avaliacaogruporesposta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db108_avaliacaogruporesposta"])){ 
       $sql  .= $virgula." db108_avaliacaogruporesposta = $this->db108_avaliacaogruporesposta ";
       $virgula = ",";
       if(trim($this->db108_avaliacaogruporesposta) == null ){ 
         $this->erro_sql = " Campo Avalia��o Grupo Resposta nao Informado.";
         $this->erro_campo = "db108_avaliacaogruporesposta";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db108_avaliacaoresposta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db108_avaliacaoresposta"])){ 
       $sql  .= $virgula." db108_avaliacaoresposta = $this->db108_avaliacaoresposta ";
       $virgula = ",";
       if(trim($this->db108_avaliacaoresposta) == null ){ 
         $this->erro_sql = " Campo Avalia��o Resposta nao Informado.";
         $this->erro_campo = "db108_avaliacaoresposta";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($db108_sequencial!=null){
       $sql .= " db108_sequencial = $this->db108_sequencial";
     }
     if (!isset($_SESSION["DB_usaAccount"])) {
       
       $resaco = $this->sql_record($this->sql_query_file($this->db108_sequencial));
       if($this->numrows>0){
         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,16931,'$this->db108_sequencial','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["db108_sequencial"]) || $this->db108_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,2988,16931,'".AddSlashes(pg_result($resaco,$conresaco,'db108_sequencial'))."','$this->db108_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["db108_avaliacaogruporesposta"]) || $this->db108_avaliacaogruporesposta != "")
             $resac = db_query("insert into db_acount values($acount,2988,16932,'".AddSlashes(pg_result($resaco,$conresaco,'db108_avaliacaogruporesposta'))."','$this->db108_avaliacaogruporesposta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["db108_avaliacaoresposta"]) || $this->db108_avaliacaoresposta != "")
             $resac = db_query("insert into db_acount values($acount,2988,16933,'".AddSlashes(pg_result($resaco,$conresaco,'db108_avaliacaoresposta'))."','$this->db108_avaliacaoresposta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Avalia��o Grupo Pergunta Resposta nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->db108_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Avalia��o Grupo Pergunta Resposta nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->db108_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db108_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($db108_sequencial=null,$dbwhere=null) { 
     
     if (!isset($_SESSION["DB_usaAccount"])) {
       
       if($dbwhere==null || $dbwhere==""){
         $resaco = $this->sql_record($this->sql_query_file($db108_sequencial));
       }else{ 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if(($resaco!=false)||($this->numrows!=0)){
         for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,16931,'$db108_sequencial','E')");
           $resac = db_query("insert into db_acount values($acount,2988,16931,'','".AddSlashes(pg_result($resaco,$iresaco,'db108_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,2988,16932,'','".AddSlashes(pg_result($resaco,$iresaco,'db108_avaliacaogruporesposta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,2988,16933,'','".AddSlashes(pg_result($resaco,$iresaco,'db108_avaliacaoresposta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from avaliacaogrupoperguntaresposta
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($db108_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " db108_sequencial = $db108_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Avalia��o Grupo Pergunta Resposta nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$db108_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Avalia��o Grupo Pergunta Resposta nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$db108_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$db108_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
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
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:avaliacaogrupoperguntaresposta";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $db108_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from avaliacaogrupoperguntaresposta ";
     $sql .= "      inner join avaliacaoresposta  on  avaliacaoresposta.db106_sequencial = avaliacaogrupoperguntaresposta.db108_avaliacaoresposta";
     $sql .= "      inner join avaliacaogruporesposta  on  avaliacaogruporesposta.db107_sequencial = avaliacaogrupoperguntaresposta.db108_avaliacaogruporesposta";
     $sql .= "      inner join avaliacaoperguntaopcao  on  avaliacaoperguntaopcao.db104_sequencial = avaliacaoresposta.db106_avaliacaoperguntaopcao";
     $sql2 = "";
     if($dbwhere==""){
       if($db108_sequencial!=null ){
         $sql2 .= " where avaliacaogrupoperguntaresposta.db108_sequencial = $db108_sequencial "; 
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
  
  function sql_query_pergunta ( $db108_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from avaliacaogrupoperguntaresposta ";
     $sql .= "      inner join avaliacaoresposta       on  db106_sequencial = db108_avaliacaoresposta";
     $sql .= "      inner join avaliacaogruporesposta  on  db107_sequencial = db108_avaliacaogruporesposta";
     $sql .= "      inner join avaliacaoperguntaopcao  on  db104_sequencial = db106_avaliacaoperguntaopcao";
     $sql .= "      inner join avaliacaopergunta       on  db103_sequencial = db104_avaliacaopergunta";
     $sql2 = "";
     if($dbwhere==""){
       if($db108_sequencial!=null ){
         $sql2 .= " where avaliacaogrupoperguntaresposta.db108_sequencial = $db108_sequencial "; 
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
   function sql_query_file ( $db108_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from avaliacaogrupoperguntaresposta ";
     $sql2 = "";
     if($dbwhere==""){
       if($db108_sequencial!=null ){
         $sql2 .= " where avaliacaogrupoperguntaresposta.db108_sequencial = $db108_sequencial "; 
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