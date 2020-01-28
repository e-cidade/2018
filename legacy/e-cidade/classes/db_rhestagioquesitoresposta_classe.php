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
//CLASSE DA ENTIDADE rhestagioquesitoresposta
class cl_rhestagioquesitoresposta { 
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
   var $h54_sequencial = 0; 
   var $h54_rhestagioquesitopergunta = 0; 
   var $h54_rhestagiocriterio = 0; 
   var $h54_descr = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 h54_sequencial = int4 = Cód. Sequencial 
                 h54_rhestagioquesitopergunta = int4 = Cód. Pergunta 
                 h54_rhestagiocriterio = int4 = Cód. Critério 
                 h54_descr = text = Descrição 
                 ";
   //funcao construtor da classe 
   function cl_rhestagioquesitoresposta() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhestagioquesitoresposta"); 
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
       $this->h54_sequencial = ($this->h54_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["h54_sequencial"]:$this->h54_sequencial);
       $this->h54_rhestagioquesitopergunta = ($this->h54_rhestagioquesitopergunta == ""?@$GLOBALS["HTTP_POST_VARS"]["h54_rhestagioquesitopergunta"]:$this->h54_rhestagioquesitopergunta);
       $this->h54_rhestagiocriterio = ($this->h54_rhestagiocriterio == ""?@$GLOBALS["HTTP_POST_VARS"]["h54_rhestagiocriterio"]:$this->h54_rhestagiocriterio);
       $this->h54_descr = ($this->h54_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["h54_descr"]:$this->h54_descr);
     }else{
       $this->h54_sequencial = ($this->h54_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["h54_sequencial"]:$this->h54_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($h54_sequencial){ 
      $this->atualizacampos();
     if($this->h54_rhestagioquesitopergunta == null ){ 
       $this->erro_sql = " Campo Cód. Pergunta nao Informado.";
       $this->erro_campo = "h54_rhestagioquesitopergunta";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h54_rhestagiocriterio == null ){ 
       $this->erro_sql = " Campo Cód. Critério nao Informado.";
       $this->erro_campo = "h54_rhestagiocriterio";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h54_descr == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "h54_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($h54_sequencial == "" || $h54_sequencial == null ){
       $result = db_query("select nextval('rhestagioquesitoresposta_h54_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhestagioquesitoresposta_h54_sequencial_seq do campo: h54_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->h54_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhestagioquesitoresposta_h54_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $h54_sequencial)){
         $this->erro_sql = " Campo h54_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->h54_sequencial = $h54_sequencial; 
       }
     }
     if(($this->h54_sequencial == null) || ($this->h54_sequencial == "") ){ 
       $this->erro_sql = " Campo h54_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhestagioquesitoresposta(
                                       h54_sequencial 
                                      ,h54_rhestagioquesitopergunta 
                                      ,h54_rhestagiocriterio 
                                      ,h54_descr 
                       )
                values (
                                $this->h54_sequencial 
                               ,$this->h54_rhestagioquesitopergunta 
                               ,$this->h54_rhestagiocriterio 
                               ,'$this->h54_descr' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Respostas de requisitos para estágio ($this->h54_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Respostas de requisitos para estágio já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Respostas de requisitos para estágio ($this->h54_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->h54_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->h54_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,10870,'$this->h54_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,1873,10870,'','".AddSlashes(pg_result($resaco,0,'h54_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1873,10871,'','".AddSlashes(pg_result($resaco,0,'h54_rhestagioquesitopergunta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1873,10872,'','".AddSlashes(pg_result($resaco,0,'h54_rhestagiocriterio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1873,10873,'','".AddSlashes(pg_result($resaco,0,'h54_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($h54_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update rhestagioquesitoresposta set ";
     $virgula = "";
     if(trim($this->h54_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h54_sequencial"])){ 
       $sql  .= $virgula." h54_sequencial = $this->h54_sequencial ";
       $virgula = ",";
       if(trim($this->h54_sequencial) == null ){ 
         $this->erro_sql = " Campo Cód. Sequencial nao Informado.";
         $this->erro_campo = "h54_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h54_rhestagioquesitopergunta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h54_rhestagioquesitopergunta"])){ 
       $sql  .= $virgula." h54_rhestagioquesitopergunta = $this->h54_rhestagioquesitopergunta ";
       $virgula = ",";
       if(trim($this->h54_rhestagioquesitopergunta) == null ){ 
         $this->erro_sql = " Campo Cód. Pergunta nao Informado.";
         $this->erro_campo = "h54_rhestagioquesitopergunta";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h54_rhestagiocriterio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h54_rhestagiocriterio"])){ 
       $sql  .= $virgula." h54_rhestagiocriterio = $this->h54_rhestagiocriterio ";
       $virgula = ",";
       if(trim($this->h54_rhestagiocriterio) == null ){ 
         $this->erro_sql = " Campo Cód. Critério nao Informado.";
         $this->erro_campo = "h54_rhestagiocriterio";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h54_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h54_descr"])){ 
       $sql  .= $virgula." h54_descr = '$this->h54_descr' ";
       $virgula = ",";
       if(trim($this->h54_descr) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "h54_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($h54_sequencial!=null){
       $sql .= " h54_sequencial = $this->h54_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->h54_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10870,'$this->h54_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h54_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,1873,10870,'".AddSlashes(pg_result($resaco,$conresaco,'h54_sequencial'))."','$this->h54_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h54_rhestagioquesitopergunta"]))
           $resac = db_query("insert into db_acount values($acount,1873,10871,'".AddSlashes(pg_result($resaco,$conresaco,'h54_rhestagioquesitopergunta'))."','$this->h54_rhestagioquesitopergunta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h54_rhestagiocriterio"]))
           $resac = db_query("insert into db_acount values($acount,1873,10872,'".AddSlashes(pg_result($resaco,$conresaco,'h54_rhestagiocriterio'))."','$this->h54_rhestagiocriterio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h54_descr"]))
           $resac = db_query("insert into db_acount values($acount,1873,10873,'".AddSlashes(pg_result($resaco,$conresaco,'h54_descr'))."','$this->h54_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Respostas de requisitos para estágio nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->h54_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Respostas de requisitos para estágio nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->h54_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->h54_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($h54_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($h54_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10870,'$h54_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,1873,10870,'','".AddSlashes(pg_result($resaco,$iresaco,'h54_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1873,10871,'','".AddSlashes(pg_result($resaco,$iresaco,'h54_rhestagioquesitopergunta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1873,10872,'','".AddSlashes(pg_result($resaco,$iresaco,'h54_rhestagiocriterio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1873,10873,'','".AddSlashes(pg_result($resaco,$iresaco,'h54_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from rhestagioquesitoresposta
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($h54_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " h54_sequencial = $h54_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Respostas de requisitos para estágio nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$h54_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Respostas de requisitos para estágio nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$h54_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$h54_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhestagioquesitoresposta";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $h54_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhestagioquesitoresposta ";
     $sql .= "      inner join rhestagiocriterio  on  rhestagiocriterio.h52_sequencial = rhestagioquesitoresposta.h54_rhestagiocriterio";
     $sql .= "      inner join rhestagioquesitopergunta  on  rhestagioquesitopergunta.h53_sequencial = rhestagioquesitoresposta.h54_rhestagioquesitopergunta";
     $sql .= "      inner join rhestagio  on  rhestagio.h50_sequencial = rhestagiocriterio.h52_rhestagio";
     $sql .= "      inner join rhestagioquesito  as a on   a.h51_sequencial = rhestagioquesitopergunta.h53_rhestagioquesito";
     $sql2 = "";
     if($dbwhere==""){
       if($h54_sequencial!=null ){
         $sql2 .= " where rhestagioquesitoresposta.h54_sequencial = $h54_sequencial "; 
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
   function sql_query_file ( $h54_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhestagioquesitoresposta ";
     $sql2 = "";
     if($dbwhere==""){
       if($h54_sequencial!=null ){
         $sql2 .= " where rhestagioquesitoresposta.h54_sequencial = $h54_sequencial "; 
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