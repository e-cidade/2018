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
//CLASSE DA ENTIDADE rhestagioavaliacaoresposta
class cl_rhestagioavaliacaoresposta { 
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
   var $h58_sequencial = 0; 
   var $h58_rhestagioquesitoresposta = 0; 
   var $h58_rhestagioavaliacao = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 h58_sequencial = int4 = C�d. Sequencial 
                 h58_rhestagioquesitoresposta = int4 = C�d. Resposta 
                 h58_rhestagioavaliacao = int4 = C�d. Avalia��o 
                 ";
   //funcao construtor da classe 
   function cl_rhestagioavaliacaoresposta() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhestagioavaliacaoresposta"); 
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
       $this->h58_sequencial = ($this->h58_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["h58_sequencial"]:$this->h58_sequencial);
       $this->h58_rhestagioquesitoresposta = ($this->h58_rhestagioquesitoresposta == ""?@$GLOBALS["HTTP_POST_VARS"]["h58_rhestagioquesitoresposta"]:$this->h58_rhestagioquesitoresposta);
       $this->h58_rhestagioavaliacao = ($this->h58_rhestagioavaliacao == ""?@$GLOBALS["HTTP_POST_VARS"]["h58_rhestagioavaliacao"]:$this->h58_rhestagioavaliacao);
     }else{
       $this->h58_sequencial = ($this->h58_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["h58_sequencial"]:$this->h58_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($h58_sequencial){ 
      $this->atualizacampos();
     if($this->h58_rhestagioquesitoresposta == null ){ 
       $this->erro_sql = " Campo C�d. Resposta nao Informado.";
       $this->erro_campo = "h58_rhestagioquesitoresposta";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h58_rhestagioavaliacao == null ){ 
       $this->erro_sql = " Campo C�d. Avalia��o nao Informado.";
       $this->erro_campo = "h58_rhestagioavaliacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($h58_sequencial == "" || $h58_sequencial == null ){
       $result = db_query("select nextval('rhestagioavaliacaoresposta_h58_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhestagioavaliacaoresposta_h58_sequencial_seq do campo: h58_sequencial"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->h58_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhestagioavaliacaoresposta_h58_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $h58_sequencial)){
         $this->erro_sql = " Campo h58_sequencial maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->h58_sequencial = $h58_sequencial; 
       }
     }
     if(($this->h58_sequencial == null) || ($this->h58_sequencial == "") ){ 
       $this->erro_sql = " Campo h58_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhestagioavaliacaoresposta(
                                       h58_sequencial 
                                      ,h58_rhestagioquesitoresposta 
                                      ,h58_rhestagioavaliacao 
                       )
                values (
                                $this->h58_sequencial 
                               ,$this->h58_rhestagioquesitoresposta 
                               ,$this->h58_rhestagioavaliacao 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Avalia��o de respostas ($this->h58_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Avalia��o de respostas j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Avalia��o de respostas ($this->h58_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->h58_sequencial;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->h58_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,10881,'$this->h58_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,1876,10881,'','".AddSlashes(pg_result($resaco,0,'h58_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1876,10882,'','".AddSlashes(pg_result($resaco,0,'h58_rhestagioquesitoresposta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1876,10883,'','".AddSlashes(pg_result($resaco,0,'h58_rhestagioavaliacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($h58_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update rhestagioavaliacaoresposta set ";
     $virgula = "";
     if(trim($this->h58_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h58_sequencial"])){ 
       $sql  .= $virgula." h58_sequencial = $this->h58_sequencial ";
       $virgula = ",";
       if(trim($this->h58_sequencial) == null ){ 
         $this->erro_sql = " Campo C�d. Sequencial nao Informado.";
         $this->erro_campo = "h58_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h58_rhestagioquesitoresposta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h58_rhestagioquesitoresposta"])){ 
       $sql  .= $virgula." h58_rhestagioquesitoresposta = $this->h58_rhestagioquesitoresposta ";
       $virgula = ",";
       if(trim($this->h58_rhestagioquesitoresposta) == null ){ 
         $this->erro_sql = " Campo C�d. Resposta nao Informado.";
         $this->erro_campo = "h58_rhestagioquesitoresposta";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h58_rhestagioavaliacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h58_rhestagioavaliacao"])){ 
       $sql  .= $virgula." h58_rhestagioavaliacao = $this->h58_rhestagioavaliacao ";
       $virgula = ",";
       if(trim($this->h58_rhestagioavaliacao) == null ){ 
         $this->erro_sql = " Campo C�d. Avalia��o nao Informado.";
         $this->erro_campo = "h58_rhestagioavaliacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($h58_sequencial!=null){
       $sql .= " h58_sequencial = $this->h58_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->h58_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10881,'$this->h58_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h58_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,1876,10881,'".AddSlashes(pg_result($resaco,$conresaco,'h58_sequencial'))."','$this->h58_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h58_rhestagioquesitoresposta"]))
           $resac = db_query("insert into db_acount values($acount,1876,10882,'".AddSlashes(pg_result($resaco,$conresaco,'h58_rhestagioquesitoresposta'))."','$this->h58_rhestagioquesitoresposta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h58_rhestagioavaliacao"]))
           $resac = db_query("insert into db_acount values($acount,1876,10883,'".AddSlashes(pg_result($resaco,$conresaco,'h58_rhestagioavaliacao'))."','$this->h58_rhestagioavaliacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Avalia��o de respostas nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->h58_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Avalia��o de respostas nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->h58_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->h58_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($h58_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($h58_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10881,'$h58_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,1876,10881,'','".AddSlashes(pg_result($resaco,$iresaco,'h58_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1876,10882,'','".AddSlashes(pg_result($resaco,$iresaco,'h58_rhestagioquesitoresposta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1876,10883,'','".AddSlashes(pg_result($resaco,$iresaco,'h58_rhestagioavaliacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from rhestagioavaliacaoresposta
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($h58_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " h58_sequencial = $h58_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Avalia��o de respostas nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$h58_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Avalia��o de respostas nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$h58_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$h58_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhestagioavaliacaoresposta";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $h58_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhestagioavaliacaoresposta ";
     $sql .= "      inner join rhestagioquesitoresposta  on  rhestagioquesitoresposta.h54_sequencial = rhestagioavaliacaoresposta.h58_rhestagioquesitoresposta";
     $sql .= "      inner join rhestagioavaliacao  on  rhestagioavaliacao.h56_sequencial = rhestagioavaliacaoresposta.h58_rhestagioavaliacao";
     $sql .= "      inner join rhestagiocriterio  on  rhestagiocriterio.h52_sequencial = rhestagioquesitoresposta.h54_rhestagiocriterio";
     $sql .= "      inner join rhestagioquesitopergunta  on  rhestagioquesitopergunta.h53_sequencial = rhestagioquesitoresposta.h54_rhestagioquesitopergunta";
     $sql .= "      inner join rhestagiocomissao  on  rhestagiocomissao.h59_sequencial = rhestagioavaliacao.h56_rhestagiocomissao";
     $sql .= "      inner join rhestagioagendadata  on  rhestagioagendadata.h64_sequencial = rhestagioavaliacao.h56_rhestagioagenda";
     $sql2 = "";
     if($dbwhere==""){
       if($h58_sequencial!=null ){
         $sql2 .= " where rhestagioavaliacaoresposta.h58_sequencial = $h58_sequencial "; 
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
   function sql_query_file ( $h58_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhestagioavaliacaoresposta ";
     $sql2 = "";
     if($dbwhere==""){
       if($h58_sequencial!=null ){
         $sql2 .= " where rhestagioavaliacaoresposta.h58_sequencial = $h58_sequencial "; 
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