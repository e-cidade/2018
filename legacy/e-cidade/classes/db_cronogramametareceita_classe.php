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
//CLASSE DA ENTIDADE cronogramametareceita
class cl_cronogramametareceita { 
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
   var $o127_sequencial = 0; 
   var $o127_cronogramaperspectivareceita = 0; 
   var $o127_mes = 0; 
   var $o127_percentual = 0; 
   var $o127_valor = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 o127_sequencial = int4 = Código Sequencial 
                 o127_cronogramaperspectivareceita = int4 = Código do Cronograma 
                 o127_mes = int4 = Mês 
                 o127_percentual = float4 = Percentual Correspondente 
                 o127_valor = float8 = Valores para Execução  do cronograma 
                 ";
   //funcao construtor da classe 
   function cl_cronogramametareceita() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("cronogramametareceita"); 
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
       $this->o127_sequencial = ($this->o127_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["o127_sequencial"]:$this->o127_sequencial);
       $this->o127_cronogramaperspectivareceita = ($this->o127_cronogramaperspectivareceita == ""?@$GLOBALS["HTTP_POST_VARS"]["o127_cronogramaperspectivareceita"]:$this->o127_cronogramaperspectivareceita);
       $this->o127_mes = ($this->o127_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["o127_mes"]:$this->o127_mes);
       $this->o127_percentual = ($this->o127_percentual == ""?@$GLOBALS["HTTP_POST_VARS"]["o127_percentual"]:$this->o127_percentual);
       $this->o127_valor = ($this->o127_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["o127_valor"]:$this->o127_valor);
     }else{
       $this->o127_sequencial = ($this->o127_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["o127_sequencial"]:$this->o127_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($o127_sequencial){ 
      $this->atualizacampos();
     if($this->o127_cronogramaperspectivareceita == null ){ 
       $this->erro_sql = " Campo Código do Cronograma nao Informado.";
       $this->erro_campo = "o127_cronogramaperspectivareceita";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o127_mes == null ){ 
       $this->erro_sql = " Campo Mês nao Informado.";
       $this->erro_campo = "o127_mes";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o127_percentual == null ){ 
       $this->erro_sql = " Campo Percentual Correspondente nao Informado.";
       $this->erro_campo = "o127_percentual";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o127_valor == null ){ 
       $this->o127_valor = "0";
     }
     if($o127_sequencial == "" || $o127_sequencial == null ){
       $result = db_query("select nextval('cronogramametareceita_o127_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: cronogramametareceita_o127_sequencial_seq do campo: o127_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->o127_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from cronogramametareceita_o127_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $o127_sequencial)){
         $this->erro_sql = " Campo o127_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->o127_sequencial = $o127_sequencial; 
       }
     }
     if(($this->o127_sequencial == null) || ($this->o127_sequencial == "") ){ 
       $this->erro_sql = " Campo o127_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into cronogramametareceita(
                                       o127_sequencial 
                                      ,o127_cronogramaperspectivareceita 
                                      ,o127_mes 
                                      ,o127_percentual 
                                      ,o127_valor 
                       )
                values (
                                $this->o127_sequencial 
                               ,$this->o127_cronogramaperspectivareceita 
                               ,$this->o127_mes 
                               ,$this->o127_percentual 
                               ,$this->o127_valor 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Valores para Metas  da receita ($this->o127_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Valores para Metas  da receita já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Valores para Metas  da receita ($this->o127_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o127_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->o127_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,14888,'$this->o127_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2621,14888,'','".AddSlashes(pg_result($resaco,0,'o127_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2621,14889,'','".AddSlashes(pg_result($resaco,0,'o127_cronogramaperspectivareceita'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2621,14890,'','".AddSlashes(pg_result($resaco,0,'o127_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2621,14892,'','".AddSlashes(pg_result($resaco,0,'o127_percentual'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2621,14893,'','".AddSlashes(pg_result($resaco,0,'o127_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($o127_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update cronogramametareceita set ";
     $virgula = "";
     if(trim($this->o127_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o127_sequencial"])){ 
       $sql  .= $virgula." o127_sequencial = $this->o127_sequencial ";
       $virgula = ",";
       if(trim($this->o127_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Sequencial nao Informado.";
         $this->erro_campo = "o127_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o127_cronogramaperspectivareceita)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o127_cronogramaperspectivareceita"])){ 
       $sql  .= $virgula." o127_cronogramaperspectivareceita = $this->o127_cronogramaperspectivareceita ";
       $virgula = ",";
       if(trim($this->o127_cronogramaperspectivareceita) == null ){ 
         $this->erro_sql = " Campo Código do Cronograma nao Informado.";
         $this->erro_campo = "o127_cronogramaperspectivareceita";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o127_mes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o127_mes"])){ 
       $sql  .= $virgula." o127_mes = $this->o127_mes ";
       $virgula = ",";
       if(trim($this->o127_mes) == null ){ 
         $this->erro_sql = " Campo Mês nao Informado.";
         $this->erro_campo = "o127_mes";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o127_percentual)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o127_percentual"])){ 
       $sql  .= $virgula." o127_percentual = $this->o127_percentual ";
       $virgula = ",";
       if(trim($this->o127_percentual) == null ){ 
         $this->erro_sql = " Campo Percentual Correspondente nao Informado.";
         $this->erro_campo = "o127_percentual";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o127_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o127_valor"])){ 
        if(trim($this->o127_valor)=="" && isset($GLOBALS["HTTP_POST_VARS"]["o127_valor"])){ 
           $this->o127_valor = "0" ; 
        } 
       $sql  .= $virgula." o127_valor = $this->o127_valor ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($o127_sequencial!=null){
       $sql .= " o127_sequencial = $this->o127_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->o127_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14888,'$this->o127_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o127_sequencial"]) || $this->o127_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2621,14888,'".AddSlashes(pg_result($resaco,$conresaco,'o127_sequencial'))."','$this->o127_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o127_cronogramaperspectivareceita"]) || $this->o127_cronogramaperspectivareceita != "")
           $resac = db_query("insert into db_acount values($acount,2621,14889,'".AddSlashes(pg_result($resaco,$conresaco,'o127_cronogramaperspectivareceita'))."','$this->o127_cronogramaperspectivareceita',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o127_mes"]) || $this->o127_mes != "")
           $resac = db_query("insert into db_acount values($acount,2621,14890,'".AddSlashes(pg_result($resaco,$conresaco,'o127_mes'))."','$this->o127_mes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o127_percentual"]) || $this->o127_percentual != "")
           $resac = db_query("insert into db_acount values($acount,2621,14892,'".AddSlashes(pg_result($resaco,$conresaco,'o127_percentual'))."','$this->o127_percentual',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o127_valor"]) || $this->o127_valor != "")
           $resac = db_query("insert into db_acount values($acount,2621,14893,'".AddSlashes(pg_result($resaco,$conresaco,'o127_valor'))."','$this->o127_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Valores para Metas  da receita nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->o127_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Valores para Metas  da receita nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->o127_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o127_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($o127_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($o127_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14888,'$o127_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2621,14888,'','".AddSlashes(pg_result($resaco,$iresaco,'o127_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2621,14889,'','".AddSlashes(pg_result($resaco,$iresaco,'o127_cronogramaperspectivareceita'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2621,14890,'','".AddSlashes(pg_result($resaco,$iresaco,'o127_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2621,14892,'','".AddSlashes(pg_result($resaco,$iresaco,'o127_percentual'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2621,14893,'','".AddSlashes(pg_result($resaco,$iresaco,'o127_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from cronogramametareceita
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($o127_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o127_sequencial = $o127_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Valores para Metas  da receita nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$o127_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Valores para Metas  da receita nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$o127_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$o127_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:cronogramametareceita";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $o127_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cronogramametareceita ";
     $sql .= "      inner join cronogramaperspectivareceita  on  cronogramaperspectivareceita.o126_sequencial = cronogramametareceita.o127_cronogramaperspectivareceita";
     $sql .= "      inner join orcreceita  on  orcreceita.o70_anousu = cronogramaperspectivareceita.o126_anousu and  orcreceita.o70_codrec = cronogramaperspectivareceita.o126_codrec";
     $sql .= "      inner join cronogramaperspectiva  on  cronogramaperspectiva.o124_sequencial = cronogramaperspectivareceita.o126_cronogramaperspectiva";
     $sql2 = "";
     if($dbwhere==""){
       if($o127_sequencial!=null ){
         $sql2 .= " where cronogramametareceita.o127_sequencial = $o127_sequencial "; 
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
   function sql_query_file ( $o127_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cronogramametareceita ";
     $sql2 = "";
     if($dbwhere==""){
       if($o127_sequencial!=null ){
         $sql2 .= " where cronogramametareceita.o127_sequencial = $o127_sequencial "; 
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