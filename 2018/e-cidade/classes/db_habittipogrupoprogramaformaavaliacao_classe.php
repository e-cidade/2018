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

//MODULO: Habitacao
//CLASSE DA ENTIDADE habittipogrupoprogramaformaavaliacao
class cl_habittipogrupoprogramaformaavaliacao { 
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
   var $ht06_sequencial = 0; 
   var $ht06_habitformaavaliacao = 0; 
   var $ht06_habittipogrupoprograma = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ht06_sequencial = int4 = Sequencial 
                 ht06_habitformaavaliacao = int4 = Forma de Avaliação 
                 ht06_habittipogrupoprograma = int4 = Tipo de Grupo 
                 ";
   //funcao construtor da classe 
   function cl_habittipogrupoprogramaformaavaliacao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("habittipogrupoprogramaformaavaliacao"); 
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
       $this->ht06_sequencial = ($this->ht06_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ht06_sequencial"]:$this->ht06_sequencial);
       $this->ht06_habitformaavaliacao = ($this->ht06_habitformaavaliacao == ""?@$GLOBALS["HTTP_POST_VARS"]["ht06_habitformaavaliacao"]:$this->ht06_habitformaavaliacao);
       $this->ht06_habittipogrupoprograma = ($this->ht06_habittipogrupoprograma == ""?@$GLOBALS["HTTP_POST_VARS"]["ht06_habittipogrupoprograma"]:$this->ht06_habittipogrupoprograma);
     }else{
       $this->ht06_sequencial = ($this->ht06_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ht06_sequencial"]:$this->ht06_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ht06_sequencial){ 
      $this->atualizacampos();
     if($this->ht06_habitformaavaliacao == null ){ 
       $this->erro_sql = " Campo Forma de Avaliação nao Informado.";
       $this->erro_campo = "ht06_habitformaavaliacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ht06_habittipogrupoprograma == null ){ 
       $this->erro_sql = " Campo Tipo de Grupo nao Informado.";
       $this->erro_campo = "ht06_habittipogrupoprograma";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ht06_sequencial == "" || $ht06_sequencial == null ){
       $result = db_query("select nextval('habittipogrupoprogramaformaavaliacao_ht06_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: habittipogrupoprogramaformaavaliacao_ht06_sequencial_seq do campo: ht06_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ht06_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from habittipogrupoprogramaformaavaliacao_ht06_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ht06_sequencial)){
         $this->erro_sql = " Campo ht06_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ht06_sequencial = $ht06_sequencial; 
       }
     }
     if(($this->ht06_sequencial == null) || ($this->ht06_sequencial == "") ){ 
       $this->erro_sql = " Campo ht06_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into habittipogrupoprogramaformaavaliacao(
                                       ht06_sequencial 
                                      ,ht06_habitformaavaliacao 
                                      ,ht06_habittipogrupoprograma 
                       )
                values (
                                $this->ht06_sequencial 
                               ,$this->ht06_habitformaavaliacao 
                               ,$this->ht06_habittipogrupoprograma 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Forma de Avaliação do Tipo de Grupo de Programa ($this->ht06_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Forma de Avaliação do Tipo de Grupo de Programa já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Forma de Avaliação do Tipo de Grupo de Programa ($this->ht06_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ht06_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ht06_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,16981,'$this->ht06_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2994,16981,'','".AddSlashes(pg_result($resaco,0,'ht06_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2994,16982,'','".AddSlashes(pg_result($resaco,0,'ht06_habitformaavaliacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2994,16983,'','".AddSlashes(pg_result($resaco,0,'ht06_habittipogrupoprograma'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ht06_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update habittipogrupoprogramaformaavaliacao set ";
     $virgula = "";
     if(trim($this->ht06_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ht06_sequencial"])){ 
       $sql  .= $virgula." ht06_sequencial = $this->ht06_sequencial ";
       $virgula = ",";
       if(trim($this->ht06_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "ht06_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ht06_habitformaavaliacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ht06_habitformaavaliacao"])){ 
       $sql  .= $virgula." ht06_habitformaavaliacao = $this->ht06_habitformaavaliacao ";
       $virgula = ",";
       if(trim($this->ht06_habitformaavaliacao) == null ){ 
         $this->erro_sql = " Campo Forma de Avaliação nao Informado.";
         $this->erro_campo = "ht06_habitformaavaliacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ht06_habittipogrupoprograma)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ht06_habittipogrupoprograma"])){ 
       $sql  .= $virgula." ht06_habittipogrupoprograma = $this->ht06_habittipogrupoprograma ";
       $virgula = ",";
       if(trim($this->ht06_habittipogrupoprograma) == null ){ 
         $this->erro_sql = " Campo Tipo de Grupo nao Informado.";
         $this->erro_campo = "ht06_habittipogrupoprograma";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ht06_sequencial!=null){
       $sql .= " ht06_sequencial = $this->ht06_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ht06_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16981,'$this->ht06_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ht06_sequencial"]) || $this->ht06_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2994,16981,'".AddSlashes(pg_result($resaco,$conresaco,'ht06_sequencial'))."','$this->ht06_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ht06_habitformaavaliacao"]) || $this->ht06_habitformaavaliacao != "")
           $resac = db_query("insert into db_acount values($acount,2994,16982,'".AddSlashes(pg_result($resaco,$conresaco,'ht06_habitformaavaliacao'))."','$this->ht06_habitformaavaliacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ht06_habittipogrupoprograma"]) || $this->ht06_habittipogrupoprograma != "")
           $resac = db_query("insert into db_acount values($acount,2994,16983,'".AddSlashes(pg_result($resaco,$conresaco,'ht06_habittipogrupoprograma'))."','$this->ht06_habittipogrupoprograma',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Forma de Avaliação do Tipo de Grupo de Programa nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ht06_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Forma de Avaliação do Tipo de Grupo de Programa nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ht06_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ht06_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ht06_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ht06_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16981,'$ht06_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2994,16981,'','".AddSlashes(pg_result($resaco,$iresaco,'ht06_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2994,16982,'','".AddSlashes(pg_result($resaco,$iresaco,'ht06_habitformaavaliacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2994,16983,'','".AddSlashes(pg_result($resaco,$iresaco,'ht06_habittipogrupoprograma'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from habittipogrupoprogramaformaavaliacao
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ht06_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ht06_sequencial = $ht06_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Forma de Avaliação do Tipo de Grupo de Programa nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ht06_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Forma de Avaliação do Tipo de Grupo de Programa nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ht06_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ht06_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:habittipogrupoprogramaformaavaliacao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ht06_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from habittipogrupoprogramaformaavaliacao ";
     $sql .= "      inner join habittipogrupoprograma  on  habittipogrupoprograma.ht02_sequencial = habittipogrupoprogramaformaavaliacao.ht06_habittipogrupoprograma";
     $sql .= "      inner join habitformaavaliacao  on  habitformaavaliacao.ht07_sequencial = habittipogrupoprogramaformaavaliacao.ht06_habitformaavaliacao";
     $sql2 = "";
     if($dbwhere==""){
       if($ht06_sequencial!=null ){
         $sql2 .= " where habittipogrupoprogramaformaavaliacao.ht06_sequencial = $ht06_sequencial "; 
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
   function sql_query_file ( $ht06_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from habittipogrupoprogramaformaavaliacao ";
     $sql2 = "";
     if($dbwhere==""){
       if($ht06_sequencial!=null ){
         $sql2 .= " where habittipogrupoprogramaformaavaliacao.ht06_sequencial = $ht06_sequencial "; 
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