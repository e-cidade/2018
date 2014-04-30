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
//CLASSE DA ENTIDADE rhestagioavaliacaoobsquesito
class cl_rhestagioavaliacaoobsquesito { 
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
   var $h63_sequencial = 0; 
   var $h63_rhestagioquesito = 0; 
   var $h63_rhestagioavaliacaoobs = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 h63_sequencial = int4 = Cód. Sequencial 
                 h63_rhestagioquesito = int4 = Cód. Requisito 
                 h63_rhestagioavaliacaoobs = int4 = Cód. Observação de avaliação 
                 ";
   //funcao construtor da classe 
   function cl_rhestagioavaliacaoobsquesito() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhestagioavaliacaoobsquesito"); 
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
       $this->h63_sequencial = ($this->h63_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["h63_sequencial"]:$this->h63_sequencial);
       $this->h63_rhestagioquesito = ($this->h63_rhestagioquesito == ""?@$GLOBALS["HTTP_POST_VARS"]["h63_rhestagioquesito"]:$this->h63_rhestagioquesito);
       $this->h63_rhestagioavaliacaoobs = ($this->h63_rhestagioavaliacaoobs == ""?@$GLOBALS["HTTP_POST_VARS"]["h63_rhestagioavaliacaoobs"]:$this->h63_rhestagioavaliacaoobs);
     }else{
       $this->h63_sequencial = ($this->h63_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["h63_sequencial"]:$this->h63_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($h63_sequencial){ 
      $this->atualizacampos();
     if($this->h63_rhestagioquesito == null ){ 
       $this->erro_sql = " Campo Cód. Requisito nao Informado.";
       $this->erro_campo = "h63_rhestagioquesito";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h63_rhestagioavaliacaoobs == null ){ 
       $this->erro_sql = " Campo Cód. Observação de avaliação nao Informado.";
       $this->erro_campo = "h63_rhestagioavaliacaoobs";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($h63_sequencial == "" || $h63_sequencial == null ){
       $result = db_query("select nextval('rhestagioavaliacaoobsquesito_h63_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhestagioavaliacaoobsquesito_h63_sequencial_seq do campo: h63_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->h63_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhestagioavaliacaoobsquesito_h63_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $h63_sequencial)){
         $this->erro_sql = " Campo h63_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->h63_sequencial = $h63_sequencial; 
       }
     }
     if(($this->h63_sequencial == null) || ($this->h63_sequencial == "") ){ 
       $this->erro_sql = " Campo h63_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhestagioavaliacaoobsquesito(
                                       h63_sequencial 
                                      ,h63_rhestagioquesito 
                                      ,h63_rhestagioavaliacaoobs 
                       )
                values (
                                $this->h63_sequencial 
                               ,$this->h63_rhestagioquesito 
                               ,$this->h63_rhestagioavaliacaoobs 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Obs. Avaliação de requisitos ($this->h63_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Obs. Avaliação de requisitos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Obs. Avaliação de requisitos ($this->h63_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->h63_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->h63_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,10893,'$this->h63_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,1879,10893,'','".AddSlashes(pg_result($resaco,0,'h63_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1879,10894,'','".AddSlashes(pg_result($resaco,0,'h63_rhestagioquesito'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1879,10895,'','".AddSlashes(pg_result($resaco,0,'h63_rhestagioavaliacaoobs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($h63_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update rhestagioavaliacaoobsquesito set ";
     $virgula = "";
     if(trim($this->h63_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h63_sequencial"])){ 
       $sql  .= $virgula." h63_sequencial = $this->h63_sequencial ";
       $virgula = ",";
       if(trim($this->h63_sequencial) == null ){ 
         $this->erro_sql = " Campo Cód. Sequencial nao Informado.";
         $this->erro_campo = "h63_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h63_rhestagioquesito)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h63_rhestagioquesito"])){ 
       $sql  .= $virgula." h63_rhestagioquesito = $this->h63_rhestagioquesito ";
       $virgula = ",";
       if(trim($this->h63_rhestagioquesito) == null ){ 
         $this->erro_sql = " Campo Cód. Requisito nao Informado.";
         $this->erro_campo = "h63_rhestagioquesito";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h63_rhestagioavaliacaoobs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h63_rhestagioavaliacaoobs"])){ 
       $sql  .= $virgula." h63_rhestagioavaliacaoobs = $this->h63_rhestagioavaliacaoobs ";
       $virgula = ",";
       if(trim($this->h63_rhestagioavaliacaoobs) == null ){ 
         $this->erro_sql = " Campo Cód. Observação de avaliação nao Informado.";
         $this->erro_campo = "h63_rhestagioavaliacaoobs";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($h63_sequencial!=null){
       $sql .= " h63_sequencial = $this->h63_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->h63_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10893,'$this->h63_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h63_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,1879,10893,'".AddSlashes(pg_result($resaco,$conresaco,'h63_sequencial'))."','$this->h63_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h63_rhestagioquesito"]))
           $resac = db_query("insert into db_acount values($acount,1879,10894,'".AddSlashes(pg_result($resaco,$conresaco,'h63_rhestagioquesito'))."','$this->h63_rhestagioquesito',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h63_rhestagioavaliacaoobs"]))
           $resac = db_query("insert into db_acount values($acount,1879,10895,'".AddSlashes(pg_result($resaco,$conresaco,'h63_rhestagioavaliacaoobs'))."','$this->h63_rhestagioavaliacaoobs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Obs. Avaliação de requisitos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->h63_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Obs. Avaliação de requisitos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->h63_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->h63_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($h63_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($h63_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10893,'$h63_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,1879,10893,'','".AddSlashes(pg_result($resaco,$iresaco,'h63_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1879,10894,'','".AddSlashes(pg_result($resaco,$iresaco,'h63_rhestagioquesito'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1879,10895,'','".AddSlashes(pg_result($resaco,$iresaco,'h63_rhestagioavaliacaoobs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from rhestagioavaliacaoobsquesito
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($h63_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " h63_sequencial = $h63_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Obs. Avaliação de requisitos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$h63_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Obs. Avaliação de requisitos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$h63_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$h63_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhestagioavaliacaoobsquesito";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $h63_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhestagioavaliacaoobsquesito ";
     $sql .= "      inner join rhestagioquesito  on  rhestagioquesito.h51_sequencial = rhestagioavaliacaoobsquesito.h63_rhestagioquesito";
     $sql .= "      inner join rhestagioavaliacaoobs  on  rhestagioavaliacaoobs.h61_sequencial = rhestagioavaliacaoobsquesito.h63_rhestagioavaliacaoobs";
     $sql .= "      inner join rhestagio  on  rhestagio.h50_sequencial = rhestagioquesito.h51_rhestagio";
     $sql .= "      inner join rhestagioavaliacao  as a on   a.h56_sequencial = rhestagioavaliacaoobs.h61_rhestagioavaliacao";
     $sql2 = "";
     if($dbwhere==""){
       if($h63_sequencial!=null ){
         $sql2 .= " where rhestagioavaliacaoobsquesito.h63_sequencial = $h63_sequencial "; 
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
   function sql_query_file ( $h63_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhestagioavaliacaoobsquesito ";
     $sql2 = "";
     if($dbwhere==""){
       if($h63_sequencial!=null ){
         $sql2 .= " where rhestagioavaliacaoobsquesito.h63_sequencial = $h63_sequencial "; 
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