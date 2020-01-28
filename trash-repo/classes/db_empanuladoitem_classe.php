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

//MODULO: empenho
//CLASSE DA ENTIDADE empanuladoitem
class cl_empanuladoitem { 
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
   var $e37_sequencial = 0; 
   var $e37_empempitem = 0; 
   var $e37_empanulado = 0; 
   var $e37_vlranu = 0; 
   var $e37_qtd = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 e37_sequencial = int4 = Código Sequencial 
                 e37_empempitem = int4 = Códido do Item 
                 e37_empanulado = int4 = Código da Anulaçao 
                 e37_vlranu = float4 = Valor Anulado 
                 e37_qtd = float4 = Quantidade Anulada 
                 ";
   //funcao construtor da classe 
   function cl_empanuladoitem() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("empanuladoitem"); 
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
       $this->e37_sequencial = ($this->e37_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["e37_sequencial"]:$this->e37_sequencial);
       $this->e37_empempitem = ($this->e37_empempitem == ""?@$GLOBALS["HTTP_POST_VARS"]["e37_empempitem"]:$this->e37_empempitem);
       $this->e37_empanulado = ($this->e37_empanulado == ""?@$GLOBALS["HTTP_POST_VARS"]["e37_empanulado"]:$this->e37_empanulado);
       $this->e37_vlranu = ($this->e37_vlranu == ""?@$GLOBALS["HTTP_POST_VARS"]["e37_vlranu"]:$this->e37_vlranu);
       $this->e37_qtd = ($this->e37_qtd == ""?@$GLOBALS["HTTP_POST_VARS"]["e37_qtd"]:$this->e37_qtd);
     }else{
       $this->e37_sequencial = ($this->e37_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["e37_sequencial"]:$this->e37_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($e37_sequencial){ 
      $this->atualizacampos();
     if($this->e37_empempitem == null ){ 
       $this->erro_sql = " Campo Códido do Item nao Informado.";
       $this->erro_campo = "e37_empempitem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e37_empanulado == null ){ 
       $this->erro_sql = " Campo Código da Anulaçao nao Informado.";
       $this->erro_campo = "e37_empanulado";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e37_vlranu == null ){ 
       $this->erro_sql = " Campo Valor Anulado nao Informado.";
       $this->erro_campo = "e37_vlranu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e37_qtd == null ){ 
       $this->e37_qtd = "0";
     }
     if($e37_sequencial == "" || $e37_sequencial == null ){
       $result = db_query("select nextval('empanuladoitem_e37_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: empanuladoitem_e37_sequencial_seq do campo: e37_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->e37_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from empanuladoitem_e37_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $e37_sequencial)){
         $this->erro_sql = " Campo e37_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->e37_sequencial = $e37_sequencial; 
       }
     }
     if(($this->e37_sequencial == null) || ($this->e37_sequencial == "") ){ 
       $this->erro_sql = " Campo e37_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into empanuladoitem(
                                       e37_sequencial 
                                      ,e37_empempitem 
                                      ,e37_empanulado 
                                      ,e37_vlranu 
                                      ,e37_qtd 
                       )
                values (
                                $this->e37_sequencial 
                               ,$this->e37_empempitem 
                               ,$this->e37_empanulado 
                               ,$this->e37_vlranu 
                               ,$this->e37_qtd 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Itens da anulação ($this->e37_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Itens da anulação já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Itens da anulação ($this->e37_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e37_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->e37_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,10918,'$this->e37_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,1885,10918,'','".AddSlashes(pg_result($resaco,0,'e37_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1885,10919,'','".AddSlashes(pg_result($resaco,0,'e37_empempitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1885,10920,'','".AddSlashes(pg_result($resaco,0,'e37_empanulado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1885,10921,'','".AddSlashes(pg_result($resaco,0,'e37_vlranu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1885,10922,'','".AddSlashes(pg_result($resaco,0,'e37_qtd'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($e37_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update empanuladoitem set ";
     $virgula = "";
     if(trim($this->e37_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e37_sequencial"])){ 
       $sql  .= $virgula." e37_sequencial = $this->e37_sequencial ";
       $virgula = ",";
       if(trim($this->e37_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Sequencial nao Informado.";
         $this->erro_campo = "e37_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e37_empempitem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e37_empempitem"])){ 
       $sql  .= $virgula." e37_empempitem = $this->e37_empempitem ";
       $virgula = ",";
       if(trim($this->e37_empempitem) == null ){ 
         $this->erro_sql = " Campo Códido do Item nao Informado.";
         $this->erro_campo = "e37_empempitem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e37_empanulado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e37_empanulado"])){ 
       $sql  .= $virgula." e37_empanulado = $this->e37_empanulado ";
       $virgula = ",";
       if(trim($this->e37_empanulado) == null ){ 
         $this->erro_sql = " Campo Código da Anulaçao nao Informado.";
         $this->erro_campo = "e37_empanulado";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e37_vlranu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e37_vlranu"])){ 
       $sql  .= $virgula." e37_vlranu = $this->e37_vlranu ";
       $virgula = ",";
       if(trim($this->e37_vlranu) == null ){ 
         $this->erro_sql = " Campo Valor Anulado nao Informado.";
         $this->erro_campo = "e37_vlranu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e37_qtd)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e37_qtd"])){ 
        if(trim($this->e37_qtd)=="" && isset($GLOBALS["HTTP_POST_VARS"]["e37_qtd"])){ 
           $this->e37_qtd = "0" ; 
        } 
       $sql  .= $virgula." e37_qtd = $this->e37_qtd ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($e37_sequencial!=null){
       $sql .= " e37_sequencial = $this->e37_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->e37_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10918,'$this->e37_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e37_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,1885,10918,'".AddSlashes(pg_result($resaco,$conresaco,'e37_sequencial'))."','$this->e37_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e37_empempitem"]))
           $resac = db_query("insert into db_acount values($acount,1885,10919,'".AddSlashes(pg_result($resaco,$conresaco,'e37_empempitem'))."','$this->e37_empempitem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e37_empanulado"]))
           $resac = db_query("insert into db_acount values($acount,1885,10920,'".AddSlashes(pg_result($resaco,$conresaco,'e37_empanulado'))."','$this->e37_empanulado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e37_vlranu"]))
           $resac = db_query("insert into db_acount values($acount,1885,10921,'".AddSlashes(pg_result($resaco,$conresaco,'e37_vlranu'))."','$this->e37_vlranu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e37_qtd"]))
           $resac = db_query("insert into db_acount values($acount,1885,10922,'".AddSlashes(pg_result($resaco,$conresaco,'e37_qtd'))."','$this->e37_qtd',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Itens da anulação nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->e37_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Itens da anulação nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->e37_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e37_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($e37_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($e37_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10918,'$e37_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,1885,10918,'','".AddSlashes(pg_result($resaco,$iresaco,'e37_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1885,10919,'','".AddSlashes(pg_result($resaco,$iresaco,'e37_empempitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1885,10920,'','".AddSlashes(pg_result($resaco,$iresaco,'e37_empanulado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1885,10921,'','".AddSlashes(pg_result($resaco,$iresaco,'e37_vlranu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1885,10922,'','".AddSlashes(pg_result($resaco,$iresaco,'e37_qtd'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from empanuladoitem
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($e37_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " e37_sequencial = $e37_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Itens da anulação nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$e37_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Itens da anulação nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$e37_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$e37_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:empanuladoitem";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $e37_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from empanuladoitem ";
     $sql .= "      inner join empempitem  on  empempitem.e62_sequencial = empanuladoitem.e37_empempitem";
     $sql .= "      inner join empanulado  on  empanulado.e94_codanu = empanuladoitem.e37_empanulado";
     $sql .= "      inner join pcmater  on  pcmater.pc01_codmater = empempitem.e62_item";
     $sql .= "      inner join empempenho  on  empempenho.e60_numemp = empempitem.e62_numemp";
     $sql .= "      inner join empempenho  as a on   a.e60_numemp = empanulado.e94_numemp";
     $sql2 = "";
     if($dbwhere==""){
       if($e37_sequencial!=null ){
         $sql2 .= " where empanuladoitem.e37_sequencial = $e37_sequencial "; 
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
   function sql_query_file ( $e37_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from empanuladoitem ";
     $sql2 = "";
     if($dbwhere==""){
       if($e37_sequencial!=null ){
         $sql2 .= " where empanuladoitem.e37_sequencial = $e37_sequencial "; 
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