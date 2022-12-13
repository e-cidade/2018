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
//CLASSE DA ENTIDADE rhestagioavaliacaoobs
class cl_rhestagioavaliacaoobs { 
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
   var $h61_sequencial = 0; 
   var $h61_rhestagioavaliacao = 0; 
   var $h61_tipo = 0; 
   var $h61_observacoes = null; 
   var $h61_recomendacoes = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 h61_sequencial = int4 = Cód. Sequencial 
                 h61_rhestagioavaliacao = int4 = Cód. Avaliação 
                 h61_tipo = int4 = Tipo 
                 h61_observacoes = text = Obs. 
                 h61_recomendacoes = text = Recomendações 
                 ";
   //funcao construtor da classe 
   function cl_rhestagioavaliacaoobs() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhestagioavaliacaoobs"); 
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
       $this->h61_sequencial = ($this->h61_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["h61_sequencial"]:$this->h61_sequencial);
       $this->h61_rhestagioavaliacao = ($this->h61_rhestagioavaliacao == ""?@$GLOBALS["HTTP_POST_VARS"]["h61_rhestagioavaliacao"]:$this->h61_rhestagioavaliacao);
       $this->h61_tipo = ($this->h61_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["h61_tipo"]:$this->h61_tipo);
       $this->h61_observacoes = ($this->h61_observacoes == ""?@$GLOBALS["HTTP_POST_VARS"]["h61_observacoes"]:$this->h61_observacoes);
       $this->h61_recomendacoes = ($this->h61_recomendacoes == ""?@$GLOBALS["HTTP_POST_VARS"]["h61_recomendacoes"]:$this->h61_recomendacoes);
     }else{
       $this->h61_sequencial = ($this->h61_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["h61_sequencial"]:$this->h61_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($h61_sequencial){ 
      $this->atualizacampos();
     if($this->h61_rhestagioavaliacao == null ){ 
       $this->erro_sql = " Campo Cód. Avaliação nao Informado.";
       $this->erro_campo = "h61_rhestagioavaliacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h61_tipo == null ){ 
       $this->erro_sql = " Campo Tipo nao Informado.";
       $this->erro_campo = "h61_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($h61_sequencial == "" || $h61_sequencial == null ){
       $result = db_query("select nextval('rhestagioavaliacaoobs_h61_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhestagioavaliacaoobs_h61_sequencial_seq do campo: h61_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->h61_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhestagioavaliacaoobs_h61_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $h61_sequencial)){
         $this->erro_sql = " Campo h61_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->h61_sequencial = $h61_sequencial; 
       }
     }
     if(($this->h61_sequencial == null) || ($this->h61_sequencial == "") ){ 
       $this->erro_sql = " Campo h61_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhestagioavaliacaoobs(
                                       h61_sequencial 
                                      ,h61_rhestagioavaliacao 
                                      ,h61_tipo 
                                      ,h61_observacoes 
                                      ,h61_recomendacoes 
                       )
                values (
                                $this->h61_sequencial 
                               ,$this->h61_rhestagioavaliacao 
                               ,$this->h61_tipo 
                               ,'$this->h61_observacoes' 
                               ,'$this->h61_recomendacoes' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Observações de avaliação ($this->h61_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Observações de avaliação já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Observações de avaliação ($this->h61_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->h61_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->h61_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,10896,'$this->h61_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,1880,10896,'','".AddSlashes(pg_result($resaco,0,'h61_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1880,10897,'','".AddSlashes(pg_result($resaco,0,'h61_rhestagioavaliacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1880,10898,'','".AddSlashes(pg_result($resaco,0,'h61_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1880,10899,'','".AddSlashes(pg_result($resaco,0,'h61_observacoes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1880,10900,'','".AddSlashes(pg_result($resaco,0,'h61_recomendacoes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($h61_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update rhestagioavaliacaoobs set ";
     $virgula = "";
     if(trim($this->h61_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h61_sequencial"])){ 
       $sql  .= $virgula." h61_sequencial = $this->h61_sequencial ";
       $virgula = ",";
       if(trim($this->h61_sequencial) == null ){ 
         $this->erro_sql = " Campo Cód. Sequencial nao Informado.";
         $this->erro_campo = "h61_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h61_rhestagioavaliacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h61_rhestagioavaliacao"])){ 
       $sql  .= $virgula." h61_rhestagioavaliacao = $this->h61_rhestagioavaliacao ";
       $virgula = ",";
       if(trim($this->h61_rhestagioavaliacao) == null ){ 
         $this->erro_sql = " Campo Cód. Avaliação nao Informado.";
         $this->erro_campo = "h61_rhestagioavaliacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h61_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h61_tipo"])){ 
       $sql  .= $virgula." h61_tipo = $this->h61_tipo ";
       $virgula = ",";
       if(trim($this->h61_tipo) == null ){ 
         $this->erro_sql = " Campo Tipo nao Informado.";
         $this->erro_campo = "h61_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h61_observacoes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h61_observacoes"])){ 
       $sql  .= $virgula." h61_observacoes = '$this->h61_observacoes' ";
       $virgula = ",";
     }
     if(trim($this->h61_recomendacoes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h61_recomendacoes"])){ 
       $sql  .= $virgula." h61_recomendacoes = '$this->h61_recomendacoes' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($h61_sequencial!=null){
       $sql .= " h61_sequencial = $this->h61_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->h61_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10896,'$this->h61_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h61_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,1880,10896,'".AddSlashes(pg_result($resaco,$conresaco,'h61_sequencial'))."','$this->h61_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h61_rhestagioavaliacao"]))
           $resac = db_query("insert into db_acount values($acount,1880,10897,'".AddSlashes(pg_result($resaco,$conresaco,'h61_rhestagioavaliacao'))."','$this->h61_rhestagioavaliacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h61_tipo"]))
           $resac = db_query("insert into db_acount values($acount,1880,10898,'".AddSlashes(pg_result($resaco,$conresaco,'h61_tipo'))."','$this->h61_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h61_observacoes"]))
           $resac = db_query("insert into db_acount values($acount,1880,10899,'".AddSlashes(pg_result($resaco,$conresaco,'h61_observacoes'))."','$this->h61_observacoes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h61_recomendacoes"]))
           $resac = db_query("insert into db_acount values($acount,1880,10900,'".AddSlashes(pg_result($resaco,$conresaco,'h61_recomendacoes'))."','$this->h61_recomendacoes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Observações de avaliação nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->h61_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Observações de avaliação nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->h61_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->h61_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($h61_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($h61_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10896,'$h61_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,1880,10896,'','".AddSlashes(pg_result($resaco,$iresaco,'h61_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1880,10897,'','".AddSlashes(pg_result($resaco,$iresaco,'h61_rhestagioavaliacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1880,10898,'','".AddSlashes(pg_result($resaco,$iresaco,'h61_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1880,10899,'','".AddSlashes(pg_result($resaco,$iresaco,'h61_observacoes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1880,10900,'','".AddSlashes(pg_result($resaco,$iresaco,'h61_recomendacoes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from rhestagioavaliacaoobs
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($h61_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " h61_sequencial = $h61_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Observações de avaliação nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$h61_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Observações de avaliação nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$h61_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$h61_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhestagioavaliacaoobs";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $h61_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhestagioavaliacaoobs ";
     $sql .= "      inner join rhestagioavaliacao  on  rhestagioavaliacao.h56_sequencial = rhestagioavaliacaoobs.h61_rhestagioavaliacao";
     $sql .= "      inner join rhestagiocomissao  on  rhestagiocomissao.h59_sequencial = rhestagioavaliacao.h56_rhestagiocomissao";
     $sql .= "      inner join rhestagioagendadata  on  rhestagioagendadata.h64_sequencial = rhestagioavaliacao.h56_rhestagioagenda";
     $sql2 = "";
     if($dbwhere==""){
       if($h61_sequencial!=null ){
         $sql2 .= " where rhestagioavaliacaoobs.h61_sequencial = $h61_sequencial "; 
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
   function sql_query_file ( $h61_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhestagioavaliacaoobs ";
     $sql2 = "";
     if($dbwhere==""){
       if($h61_sequencial!=null ){
         $sql2 .= " where rhestagioavaliacaoobs.h61_sequencial = $h61_sequencial "; 
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
   function sql_querytipo( $h61_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhestagioavaliacaoobs ";
     $sql .= "      inner join rhestagioavaliacao  on rhestagioavaliacao.h56_sequencial  = rhestagioavaliacaoobs.h61_rhestagioavaliacao";
     $sql .= "      inner join rhestagiocomissao   on rhestagiocomissao.h59_sequencial   = rhestagioavaliacao.h56_rhestagiocomissao";
     $sql .= "      inner join rhestagioagendadata on rhestagioagendadata.h64_sequencial = rhestagioavaliacao.h56_rhestagioagenda";
     $sql .= "      left join rhestagioavaliacaoobsquesito  on h61_sequencial        = h63_rhestagioavaliacaoobs";
     $sql .= "      left join rhestagioavaliacaoobspergunta on h61_sequencial         = h62_rhestagioavaliacaoobs";
     $sql2 = "";
     if($dbwhere==""){
       if($h61_sequencial!=null ){
         $sql2 .= " where rhestagioavaliacaoobs.h61_sequencial = $h61_sequencial "; 
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