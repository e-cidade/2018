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

//MODULO: orcamento
//CLASSE DA ENTIDADE cronogramabasecalculoreceita
class cl_cronogramabasecalculoreceita { 
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
   var $o125_sequencial = 0; 
   var $o125_cronogramaperspectivareceita = 0; 
   var $o125_mes = 0; 
   var $o125_percentual = 0; 
   var $o125_valor = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 o125_sequencial = int4 = Código Sequencial 
                 o125_cronogramaperspectivareceita = int4 = Perspectiva do Cronograma 
                 o125_mes = int4 = Mês 
                 o125_percentual = int4 = Percentual Correspondente 
                 o125_valor = int4 = Valor 
                 ";
   //funcao construtor da classe 
   function cl_cronogramabasecalculoreceita() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("cronogramabasecalculoreceita"); 
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
       $this->o125_sequencial = ($this->o125_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["o125_sequencial"]:$this->o125_sequencial);
       $this->o125_cronogramaperspectivareceita = ($this->o125_cronogramaperspectivareceita == ""?@$GLOBALS["HTTP_POST_VARS"]["o125_cronogramaperspectivareceita"]:$this->o125_cronogramaperspectivareceita);
       $this->o125_mes = ($this->o125_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["o125_mes"]:$this->o125_mes);
       $this->o125_percentual = ($this->o125_percentual == ""?@$GLOBALS["HTTP_POST_VARS"]["o125_percentual"]:$this->o125_percentual);
       $this->o125_valor = ($this->o125_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["o125_valor"]:$this->o125_valor);
     }else{
       $this->o125_sequencial = ($this->o125_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["o125_sequencial"]:$this->o125_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($o125_sequencial){ 
      $this->atualizacampos();
     if($this->o125_cronogramaperspectivareceita == null ){ 
       $this->erro_sql = " Campo Perspectiva do Cronograma nao Informado.";
       $this->erro_campo = "o125_cronogramaperspectivareceita";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o125_mes == null ){ 
       $this->erro_sql = " Campo Mês nao Informado.";
       $this->erro_campo = "o125_mes";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o125_percentual == null ){ 
       $this->erro_sql = " Campo Percentual Correspondente nao Informado.";
       $this->erro_campo = "o125_percentual";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o125_valor == null ){ 
       $this->erro_sql = " Campo Valor nao Informado.";
       $this->erro_campo = "o125_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($o125_sequencial == "" || $o125_sequencial == null ){
       $result = db_query("select nextval('cronogramabasecalculo_o125_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: cronogramabasecalculo_o125_sequencial_seq do campo: o125_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->o125_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from cronogramabasecalculo_o125_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $o125_sequencial)){
         $this->erro_sql = " Campo o125_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->o125_sequencial = $o125_sequencial; 
       }
     }
     if(($this->o125_sequencial == null) || ($this->o125_sequencial == "") ){ 
       $this->erro_sql = " Campo o125_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into cronogramabasecalculoreceita(
                                       o125_sequencial 
                                      ,o125_cronogramaperspectivareceita 
                                      ,o125_mes 
                                      ,o125_percentual 
                                      ,o125_valor 
                       )
                values (
                                $this->o125_sequencial 
                               ,$this->o125_cronogramaperspectivareceita 
                               ,$this->o125_mes 
                               ,$this->o125_percentual 
                               ,$this->o125_valor 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Bases de calculo das receitas para Cronograma ($this->o125_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Bases de calculo das receitas para Cronograma já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Bases de calculo das receitas para Cronograma ($this->o125_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o125_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->o125_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,14878,'$this->o125_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2619,14878,'','".AddSlashes(pg_result($resaco,0,'o125_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2619,14879,'','".AddSlashes(pg_result($resaco,0,'o125_cronogramaperspectivareceita'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2619,14880,'','".AddSlashes(pg_result($resaco,0,'o125_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2619,14882,'','".AddSlashes(pg_result($resaco,0,'o125_percentual'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2619,14883,'','".AddSlashes(pg_result($resaco,0,'o125_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($o125_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update cronogramabasecalculoreceita set ";
     $virgula = "";
     if(trim($this->o125_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o125_sequencial"])){ 
       $sql  .= $virgula." o125_sequencial = $this->o125_sequencial ";
       $virgula = ",";
       if(trim($this->o125_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Sequencial nao Informado.";
         $this->erro_campo = "o125_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o125_cronogramaperspectivareceita)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o125_cronogramaperspectivareceita"])){ 
       $sql  .= $virgula." o125_cronogramaperspectivareceita = $this->o125_cronogramaperspectivareceita ";
       $virgula = ",";
       if(trim($this->o125_cronogramaperspectivareceita) == null ){ 
         $this->erro_sql = " Campo Perspectiva do Cronograma nao Informado.";
         $this->erro_campo = "o125_cronogramaperspectivareceita";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o125_mes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o125_mes"])){ 
       $sql  .= $virgula." o125_mes = $this->o125_mes ";
       $virgula = ",";
       if(trim($this->o125_mes) == null ){ 
         $this->erro_sql = " Campo Mês nao Informado.";
         $this->erro_campo = "o125_mes";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o125_percentual)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o125_percentual"])){ 
       $sql  .= $virgula." o125_percentual = $this->o125_percentual ";
       $virgula = ",";
       if(trim($this->o125_percentual) == null ){ 
         $this->erro_sql = " Campo Percentual Correspondente nao Informado.";
         $this->erro_campo = "o125_percentual";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o125_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o125_valor"])){ 
       $sql  .= $virgula." o125_valor = $this->o125_valor ";
       $virgula = ",";
       if(trim($this->o125_valor) == null ){ 
         $this->erro_sql = " Campo Valor nao Informado.";
         $this->erro_campo = "o125_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($o125_sequencial!=null){
       $sql .= " o125_sequencial = $this->o125_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->o125_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14878,'$this->o125_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o125_sequencial"]) || $this->o125_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2619,14878,'".AddSlashes(pg_result($resaco,$conresaco,'o125_sequencial'))."','$this->o125_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o125_cronogramaperspectivareceita"]) || $this->o125_cronogramaperspectivareceita != "")
           $resac = db_query("insert into db_acount values($acount,2619,14879,'".AddSlashes(pg_result($resaco,$conresaco,'o125_cronogramaperspectivareceita'))."','$this->o125_cronogramaperspectivareceita',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o125_mes"]) || $this->o125_mes != "")
           $resac = db_query("insert into db_acount values($acount,2619,14880,'".AddSlashes(pg_result($resaco,$conresaco,'o125_mes'))."','$this->o125_mes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o125_percentual"]) || $this->o125_percentual != "")
           $resac = db_query("insert into db_acount values($acount,2619,14882,'".AddSlashes(pg_result($resaco,$conresaco,'o125_percentual'))."','$this->o125_percentual',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o125_valor"]) || $this->o125_valor != "")
           $resac = db_query("insert into db_acount values($acount,2619,14883,'".AddSlashes(pg_result($resaco,$conresaco,'o125_valor'))."','$this->o125_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Bases de calculo das receitas para Cronograma nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->o125_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Bases de calculo das receitas para Cronograma nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->o125_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o125_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($o125_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($o125_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14878,'$o125_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2619,14878,'','".AddSlashes(pg_result($resaco,$iresaco,'o125_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2619,14879,'','".AddSlashes(pg_result($resaco,$iresaco,'o125_cronogramaperspectivareceita'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2619,14880,'','".AddSlashes(pg_result($resaco,$iresaco,'o125_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2619,14882,'','".AddSlashes(pg_result($resaco,$iresaco,'o125_percentual'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2619,14883,'','".AddSlashes(pg_result($resaco,$iresaco,'o125_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from cronogramabasecalculoreceita
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($o125_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o125_sequencial = $o125_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Bases de calculo das receitas para Cronograma nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$o125_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Bases de calculo das receitas para Cronograma nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$o125_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$o125_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:cronogramabasecalculoreceita";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $o125_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cronogramabasecalculoreceita ";
     $sql .= "      inner join cronogramaperspectivareceita  on  cronogramaperspectivareceita.o126_sequencial = cronogramabasecalculoreceita.o125_cronogramaperspectivareceita";
     $sql .= "      inner join orcreceita  on  orcreceita.o70_anousu = cronogramaperspectivareceita.o126_anousu and  orcreceita.o70_codrec = cronogramaperspectivareceita.o126_codrec";
     $sql .= "      inner join cronogramaperspectiva  on  cronogramaperspectiva.o124_sequencial = cronogramaperspectivareceita.o126_cronogramaperspectiva";
     $sql2 = "";
     if($dbwhere==""){
       if($o125_sequencial!=null ){
         $sql2 .= " where cronogramabasecalculoreceita.o125_sequencial = $o125_sequencial "; 
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
   function sql_query_file ( $o125_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cronogramabasecalculoreceita ";
     $sql2 = "";
     if($dbwhere==""){
       if($o125_sequencial!=null ){
         $sql2 .= " where cronogramabasecalculoreceita.o125_sequencial = $o125_sequencial "; 
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