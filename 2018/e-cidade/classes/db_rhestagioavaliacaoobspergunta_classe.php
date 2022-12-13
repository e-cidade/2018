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

//MODULO: recursoshumanos
//CLASSE DA ENTIDADE rhestagioavaliacaoobspergunta
class cl_rhestagioavaliacaoobspergunta { 
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
   var $h62_sequencial = 0; 
   var $h62_rhestagioquesitopergunta = 0; 
   var $h62_rhestagioavaliacaoobs = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 h62_sequencial = int4 = Cód. Sequencial 
                 h62_rhestagioquesitopergunta = int4 = Cód. Requisito de pergunta 
                 h62_rhestagioavaliacaoobs = int4 = Cód. Obs. Avaliação 
                 ";
   //funcao construtor da classe 
   function cl_rhestagioavaliacaoobspergunta() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhestagioavaliacaoobspergunta"); 
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
       $this->h62_sequencial = ($this->h62_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["h62_sequencial"]:$this->h62_sequencial);
       $this->h62_rhestagioquesitopergunta = ($this->h62_rhestagioquesitopergunta == ""?@$GLOBALS["HTTP_POST_VARS"]["h62_rhestagioquesitopergunta"]:$this->h62_rhestagioquesitopergunta);
       $this->h62_rhestagioavaliacaoobs = ($this->h62_rhestagioavaliacaoobs == ""?@$GLOBALS["HTTP_POST_VARS"]["h62_rhestagioavaliacaoobs"]:$this->h62_rhestagioavaliacaoobs);
     }else{
       $this->h62_sequencial = ($this->h62_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["h62_sequencial"]:$this->h62_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($h62_sequencial){ 
      $this->atualizacampos();
     if($this->h62_rhestagioquesitopergunta == null ){ 
       $this->erro_sql = " Campo Cód. Requisito de pergunta nao Informado.";
       $this->erro_campo = "h62_rhestagioquesitopergunta";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h62_rhestagioavaliacaoobs == null ){ 
       $this->erro_sql = " Campo Cód. Obs. Avaliação nao Informado.";
       $this->erro_campo = "h62_rhestagioavaliacaoobs";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($h62_sequencial == "" || $h62_sequencial == null ){
       $result = db_query("select nextval('rhestagioavaliacaoobspergunta_h62_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhestagioavaliacaoobspergunta_h62_sequencial_seq do campo: h62_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->h62_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhestagioavaliacaoobspergunta_h62_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $h62_sequencial)){
         $this->erro_sql = " Campo h62_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->h62_sequencial = $h62_sequencial; 
       }
     }
     if(($this->h62_sequencial == null) || ($this->h62_sequencial == "") ){ 
       $this->erro_sql = " Campo h62_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhestagioavaliacaoobspergunta(
                                       h62_sequencial 
                                      ,h62_rhestagioquesitopergunta 
                                      ,h62_rhestagioavaliacaoobs 
                       )
                values (
                                $this->h62_sequencial 
                               ,$this->h62_rhestagioquesitopergunta 
                               ,$this->h62_rhestagioavaliacaoobs 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Obs. Pergunta de avaliações ($this->h62_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Obs. Pergunta de avaliações já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Obs. Pergunta de avaliações ($this->h62_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->h62_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->h62_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,10901,'$this->h62_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,1881,10901,'','".AddSlashes(pg_result($resaco,0,'h62_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1881,10902,'','".AddSlashes(pg_result($resaco,0,'h62_rhestagioquesitopergunta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1881,10903,'','".AddSlashes(pg_result($resaco,0,'h62_rhestagioavaliacaoobs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($h62_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update rhestagioavaliacaoobspergunta set ";
     $virgula = "";
     if(trim($this->h62_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h62_sequencial"])){ 
       $sql  .= $virgula." h62_sequencial = $this->h62_sequencial ";
       $virgula = ",";
       if(trim($this->h62_sequencial) == null ){ 
         $this->erro_sql = " Campo Cód. Sequencial nao Informado.";
         $this->erro_campo = "h62_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h62_rhestagioquesitopergunta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h62_rhestagioquesitopergunta"])){ 
       $sql  .= $virgula." h62_rhestagioquesitopergunta = $this->h62_rhestagioquesitopergunta ";
       $virgula = ",";
       if(trim($this->h62_rhestagioquesitopergunta) == null ){ 
         $this->erro_sql = " Campo Cód. Requisito de pergunta nao Informado.";
         $this->erro_campo = "h62_rhestagioquesitopergunta";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h62_rhestagioavaliacaoobs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h62_rhestagioavaliacaoobs"])){ 
       $sql  .= $virgula." h62_rhestagioavaliacaoobs = $this->h62_rhestagioavaliacaoobs ";
       $virgula = ",";
       if(trim($this->h62_rhestagioavaliacaoobs) == null ){ 
         $this->erro_sql = " Campo Cód. Obs. Avaliação nao Informado.";
         $this->erro_campo = "h62_rhestagioavaliacaoobs";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($h62_sequencial!=null){
       $sql .= " h62_sequencial = $this->h62_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->h62_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10901,'$this->h62_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h62_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,1881,10901,'".AddSlashes(pg_result($resaco,$conresaco,'h62_sequencial'))."','$this->h62_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h62_rhestagioquesitopergunta"]))
           $resac = db_query("insert into db_acount values($acount,1881,10902,'".AddSlashes(pg_result($resaco,$conresaco,'h62_rhestagioquesitopergunta'))."','$this->h62_rhestagioquesitopergunta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h62_rhestagioavaliacaoobs"]))
           $resac = db_query("insert into db_acount values($acount,1881,10903,'".AddSlashes(pg_result($resaco,$conresaco,'h62_rhestagioavaliacaoobs'))."','$this->h62_rhestagioavaliacaoobs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Obs. Pergunta de avaliações nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->h62_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Obs. Pergunta de avaliações nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->h62_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->h62_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($h62_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($h62_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10901,'$h62_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,1881,10901,'','".AddSlashes(pg_result($resaco,$iresaco,'h62_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1881,10902,'','".AddSlashes(pg_result($resaco,$iresaco,'h62_rhestagioquesitopergunta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1881,10903,'','".AddSlashes(pg_result($resaco,$iresaco,'h62_rhestagioavaliacaoobs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from rhestagioavaliacaoobspergunta
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($h62_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " h62_sequencial = $h62_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Obs. Pergunta de avaliações nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$h62_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Obs. Pergunta de avaliações nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$h62_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$h62_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhestagioavaliacaoobspergunta";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $h62_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhestagioavaliacaoobspergunta ";
     $sql .= "      inner join rhestagioquesitopergunta  on  rhestagioquesitopergunta.h53_sequencial = rhestagioavaliacaoobspergunta.h62_rhestagioquesitopergunta";
     $sql .= "      inner join rhestagioavaliacaoobs  on  rhestagioavaliacaoobs.h61_sequencial = rhestagioavaliacaoobspergunta.h62_rhestagioavaliacaoobs";
     $sql .= "      inner join rhestagioquesito  on  rhestagioquesito.h51_sequencial = rhestagioquesitopergunta.h53_rhestagioquesito";
     $sql .= "      inner join rhestagioavaliacao  as a on   a.h56_sequencial = rhestagioavaliacaoobs.h61_rhestagioavaliacao";
     $sql2 = "";
     if($dbwhere==""){
       if($h62_sequencial!=null ){
         $sql2 .= " where rhestagioavaliacaoobspergunta.h62_sequencial = $h62_sequencial "; 
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
   function sql_query_file ( $h62_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhestagioavaliacaoobspergunta ";
     $sql2 = "";
     if($dbwhere==""){
       if($h62_sequencial!=null ){
         $sql2 .= " where rhestagioavaliacaoobspergunta.h62_sequencial = $h62_sequencial "; 
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