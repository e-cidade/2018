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
//CLASSE DA ENTIDADE portariatipodoccoletiva
class cl_portariatipodoccoletiva { 
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
   var $h38_sequencial = 0; 
   var $h38_portariatipo = 0; 
   var $h38_modportariacoletiva = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 h38_sequencial = int4 = Sequencial 
                 h38_portariatipo = int4 = Tipo 
                 h38_modportariacoletiva = int4 = Modelo Portaria Coletiva 
                 ";
   //funcao construtor da classe 
   function cl_portariatipodoccoletiva() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("portariatipodoccoletiva"); 
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
       $this->h38_sequencial = ($this->h38_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["h38_sequencial"]:$this->h38_sequencial);
       $this->h38_portariatipo = ($this->h38_portariatipo == ""?@$GLOBALS["HTTP_POST_VARS"]["h38_portariatipo"]:$this->h38_portariatipo);
       $this->h38_modportariacoletiva = ($this->h38_modportariacoletiva == ""?@$GLOBALS["HTTP_POST_VARS"]["h38_modportariacoletiva"]:$this->h38_modportariacoletiva);
     }else{
       $this->h38_sequencial = ($this->h38_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["h38_sequencial"]:$this->h38_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($h38_sequencial){ 
      $this->atualizacampos();
     if($this->h38_portariatipo == null ){ 
       $this->erro_sql = " Campo Tipo nao Informado.";
       $this->erro_campo = "h38_portariatipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h38_modportariacoletiva == null ){ 
       $this->erro_sql = " Campo Modelo Portaria Coletiva nao Informado.";
       $this->erro_campo = "h38_modportariacoletiva";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($h38_sequencial == "" || $h38_sequencial == null ){
       $result = db_query("select nextval('portariatipocoletiva_h38_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: portariatipocoletiva_h38_sequencial_seq do campo: h38_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->h38_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from portariatipocoletiva_h38_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $h38_sequencial)){
         $this->erro_sql = " Campo h38_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->h38_sequencial = $h38_sequencial; 
       }
     }
     if(($this->h38_sequencial == null) || ($this->h38_sequencial == "") ){ 
       $this->erro_sql = " Campo h38_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into portariatipodoccoletiva(
                                       h38_sequencial 
                                      ,h38_portariatipo 
                                      ,h38_modportariacoletiva 
                       )
                values (
                                $this->h38_sequencial 
                               ,$this->h38_portariatipo 
                               ,$this->h38_modportariacoletiva 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "portariatipodoccoletiva ($this->h38_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "portariatipodoccoletiva já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "portariatipodoccoletiva ($this->h38_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->h38_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->h38_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,12217,'$this->h38_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2123,12217,'','".AddSlashes(pg_result($resaco,0,'h38_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2123,12218,'','".AddSlashes(pg_result($resaco,0,'h38_portariatipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2123,12219,'','".AddSlashes(pg_result($resaco,0,'h38_modportariacoletiva'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($h38_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update portariatipodoccoletiva set ";
     $virgula = "";
     if(trim($this->h38_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h38_sequencial"])){ 
       $sql  .= $virgula." h38_sequencial = $this->h38_sequencial ";
       $virgula = ",";
       if(trim($this->h38_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "h38_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h38_portariatipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h38_portariatipo"])){ 
       $sql  .= $virgula." h38_portariatipo = $this->h38_portariatipo ";
       $virgula = ",";
       if(trim($this->h38_portariatipo) == null ){ 
         $this->erro_sql = " Campo Tipo nao Informado.";
         $this->erro_campo = "h38_portariatipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h38_modportariacoletiva)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h38_modportariacoletiva"])){ 
       $sql  .= $virgula." h38_modportariacoletiva = $this->h38_modportariacoletiva ";
       $virgula = ",";
       if(trim($this->h38_modportariacoletiva) == null ){ 
         $this->erro_sql = " Campo Modelo Portaria Coletiva nao Informado.";
         $this->erro_campo = "h38_modportariacoletiva";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($h38_sequencial!=null){
       $sql .= " h38_sequencial = $this->h38_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->h38_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12217,'$this->h38_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h38_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,2123,12217,'".AddSlashes(pg_result($resaco,$conresaco,'h38_sequencial'))."','$this->h38_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h38_portariatipo"]))
           $resac = db_query("insert into db_acount values($acount,2123,12218,'".AddSlashes(pg_result($resaco,$conresaco,'h38_portariatipo'))."','$this->h38_portariatipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h38_modportariacoletiva"]))
           $resac = db_query("insert into db_acount values($acount,2123,12219,'".AddSlashes(pg_result($resaco,$conresaco,'h38_modportariacoletiva'))."','$this->h38_modportariacoletiva',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "portariatipodoccoletiva nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->h38_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "portariatipodoccoletiva nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->h38_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->h38_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($h38_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($h38_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12217,'$h38_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2123,12217,'','".AddSlashes(pg_result($resaco,$iresaco,'h38_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2123,12218,'','".AddSlashes(pg_result($resaco,$iresaco,'h38_portariatipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2123,12219,'','".AddSlashes(pg_result($resaco,$iresaco,'h38_modportariacoletiva'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from portariatipodoccoletiva
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($h38_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " h38_sequencial = $h38_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "portariatipodoccoletiva nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$h38_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "portariatipodoccoletiva nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$h38_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$h38_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:portariatipodoccoletiva";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $h38_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from portariatipodoccoletiva ";
     $sql .= "      inner join portariatipo  on  portariatipo.h30_sequencial = portariatipodoccoletiva.h38_portariatipo";
     $sql .= "      inner join db_relatorio  on  db_relatorio.db63_sequencial = portariatipodoccoletiva.h38_modportariacoletiva";
     $sql .= "      inner join tipoasse  on  tipoasse.h12_codigo = portariatipo.h30_tipoasse";
     $sql .= "      inner join portariaenvolv  on  portariaenvolv.h42_sequencial = portariatipo.h30_portariaenvolv";
     $sql .= "      inner join portariatipoato  on  portariatipoato.h41_sequencial = portariatipo.h30_portariatipoato";
     $sql .= "      inner join portariaproced  on  portariaproced.h40_sequencial = portariatipo.h30_portariaproced";
     $sql .= "      inner join db_gruporelatorio  on  db_gruporelatorio.db13_sequencial = db_relatorio.db63_db_gruporelatorio";
     $sql .= "      inner join db_tiporelatorio  on  db_tiporelatorio.db14_sequencial = db_relatorio.db63_db_tiporelatorio";
     $sql2 = "";
     if($dbwhere==""){
       if($h38_sequencial!=null ){
         $sql2 .= " where portariatipodoccoletiva.h38_sequencial = $h38_sequencial "; 
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
   function sql_query_file ( $h38_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from portariatipodoccoletiva ";
     $sql2 = "";
     if($dbwhere==""){
       if($h38_sequencial!=null ){
         $sql2 .= " where portariatipodoccoletiva.h38_sequencial = $h38_sequencial "; 
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