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
//CLASSE DA ENTIDADE empsolicitaanulitem
class cl_empsolicitaanulitem { 
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
   var $e36_sequencial = 0; 
   var $e36_empempitem = 0; 
   var $e36_empsolicitaanul = 0; 
   var $e36_vrlanu = 0; 
   var $e36_qtdanu = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 e36_sequencial = int4 = Código Sequencial 
                 e36_empempitem = int4 = Item do Empenho 
                 e36_empsolicitaanul = int4 = Código da Solicitação 
                 e36_vrlanu = float4 = Valor Anulado 
                 e36_qtdanu = float4 = Quantidade Anulada 
                 ";
   //funcao construtor da classe 
   function cl_empsolicitaanulitem() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("empsolicitaanulitem"); 
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
       $this->e36_sequencial = ($this->e36_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["e36_sequencial"]:$this->e36_sequencial);
       $this->e36_empempitem = ($this->e36_empempitem == ""?@$GLOBALS["HTTP_POST_VARS"]["e36_empempitem"]:$this->e36_empempitem);
       $this->e36_empsolicitaanul = ($this->e36_empsolicitaanul == ""?@$GLOBALS["HTTP_POST_VARS"]["e36_empsolicitaanul"]:$this->e36_empsolicitaanul);
       $this->e36_vrlanu = ($this->e36_vrlanu == ""?@$GLOBALS["HTTP_POST_VARS"]["e36_vrlanu"]:$this->e36_vrlanu);
       $this->e36_qtdanu = ($this->e36_qtdanu == ""?@$GLOBALS["HTTP_POST_VARS"]["e36_qtdanu"]:$this->e36_qtdanu);
     }else{
       $this->e36_sequencial = ($this->e36_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["e36_sequencial"]:$this->e36_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($e36_sequencial){ 
      $this->atualizacampos();
     if($this->e36_empempitem == null ){ 
       $this->erro_sql = " Campo Item do Empenho nao Informado.";
       $this->erro_campo = "e36_empempitem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e36_empsolicitaanul == null ){ 
       $this->erro_sql = " Campo Código da Solicitação nao Informado.";
       $this->erro_campo = "e36_empsolicitaanul";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e36_vrlanu == null ){ 
       $this->erro_sql = " Campo Valor Anulado nao Informado.";
       $this->erro_campo = "e36_vrlanu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e36_qtdanu == null ){ 
       $this->e36_qtdanu = "0";
     }
     if($e36_sequencial == "" || $e36_sequencial == null ){
       $result = db_query("select nextval('empsolicitaanulitem_e36_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: empsolicitaanulitem_e36_sequencial_seq do campo: e36_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->e36_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from empsolicitaanulitem_e36_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $e36_sequencial)){
         $this->erro_sql = " Campo e36_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->e36_sequencial = $e36_sequencial; 
       }
     }
     if(($this->e36_sequencial == null) || ($this->e36_sequencial == "") ){ 
       $this->erro_sql = " Campo e36_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into empsolicitaanulitem(
                                       e36_sequencial 
                                      ,e36_empempitem 
                                      ,e36_empsolicitaanul 
                                      ,e36_vrlanu 
                                      ,e36_qtdanu 
                       )
                values (
                                $this->e36_sequencial 
                               ,$this->e36_empempitem 
                               ,$this->e36_empsolicitaanul 
                               ,$this->e36_vrlanu 
                               ,$this->e36_qtdanu 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Itens da solicitação ($this->e36_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Itens da solicitação já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Itens da solicitação ($this->e36_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e36_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->e36_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,10911,'$this->e36_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,1883,10911,'','".AddSlashes(pg_result($resaco,0,'e36_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1883,10912,'','".AddSlashes(pg_result($resaco,0,'e36_empempitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1883,10913,'','".AddSlashes(pg_result($resaco,0,'e36_empsolicitaanul'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1883,10914,'','".AddSlashes(pg_result($resaco,0,'e36_vrlanu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1883,10969,'','".AddSlashes(pg_result($resaco,0,'e36_qtdanu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($e36_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update empsolicitaanulitem set ";
     $virgula = "";
     if(trim($this->e36_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e36_sequencial"])){ 
       $sql  .= $virgula." e36_sequencial = $this->e36_sequencial ";
       $virgula = ",";
       if(trim($this->e36_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Sequencial nao Informado.";
         $this->erro_campo = "e36_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e36_empempitem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e36_empempitem"])){ 
       $sql  .= $virgula." e36_empempitem = $this->e36_empempitem ";
       $virgula = ",";
       if(trim($this->e36_empempitem) == null ){ 
         $this->erro_sql = " Campo Item do Empenho nao Informado.";
         $this->erro_campo = "e36_empempitem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e36_empsolicitaanul)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e36_empsolicitaanul"])){ 
       $sql  .= $virgula." e36_empsolicitaanul = $this->e36_empsolicitaanul ";
       $virgula = ",";
       if(trim($this->e36_empsolicitaanul) == null ){ 
         $this->erro_sql = " Campo Código da Solicitação nao Informado.";
         $this->erro_campo = "e36_empsolicitaanul";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e36_vrlanu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e36_vrlanu"])){ 
       $sql  .= $virgula." e36_vrlanu = $this->e36_vrlanu ";
       $virgula = ",";
       if(trim($this->e36_vrlanu) == null ){ 
         $this->erro_sql = " Campo Valor Anulado nao Informado.";
         $this->erro_campo = "e36_vrlanu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e36_qtdanu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e36_qtdanu"])){ 
        if(trim($this->e36_qtdanu)=="" && isset($GLOBALS["HTTP_POST_VARS"]["e36_qtdanu"])){ 
           $this->e36_qtdanu = "0" ; 
        } 
       $sql  .= $virgula." e36_qtdanu = $this->e36_qtdanu ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($e36_sequencial!=null){
       $sql .= " e36_sequencial = $this->e36_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->e36_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10911,'$this->e36_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e36_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,1883,10911,'".AddSlashes(pg_result($resaco,$conresaco,'e36_sequencial'))."','$this->e36_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e36_empempitem"]))
           $resac = db_query("insert into db_acount values($acount,1883,10912,'".AddSlashes(pg_result($resaco,$conresaco,'e36_empempitem'))."','$this->e36_empempitem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e36_empsolicitaanul"]))
           $resac = db_query("insert into db_acount values($acount,1883,10913,'".AddSlashes(pg_result($resaco,$conresaco,'e36_empsolicitaanul'))."','$this->e36_empsolicitaanul',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e36_vrlanu"]))
           $resac = db_query("insert into db_acount values($acount,1883,10914,'".AddSlashes(pg_result($resaco,$conresaco,'e36_vrlanu'))."','$this->e36_vrlanu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e36_qtdanu"]))
           $resac = db_query("insert into db_acount values($acount,1883,10969,'".AddSlashes(pg_result($resaco,$conresaco,'e36_qtdanu'))."','$this->e36_qtdanu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Itens da solicitação nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->e36_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Itens da solicitação nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->e36_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e36_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($e36_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($e36_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10911,'$e36_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,1883,10911,'','".AddSlashes(pg_result($resaco,$iresaco,'e36_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1883,10912,'','".AddSlashes(pg_result($resaco,$iresaco,'e36_empempitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1883,10913,'','".AddSlashes(pg_result($resaco,$iresaco,'e36_empsolicitaanul'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1883,10914,'','".AddSlashes(pg_result($resaco,$iresaco,'e36_vrlanu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1883,10969,'','".AddSlashes(pg_result($resaco,$iresaco,'e36_qtdanu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from empsolicitaanulitem
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($e36_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " e36_sequencial = $e36_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Itens da solicitação nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$e36_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Itens da solicitação nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$e36_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$e36_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:empsolicitaanulitem";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $e36_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from empsolicitaanulitem ";
     $sql .= "      inner join empsolicitaanul  on  empsolicitaanul.e35_sequencial = empsolicitaanulitem.e36_empsolicitaanul";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = empsolicitaanul.e35_usuario";
     $sql .= "      inner join empempenho  on  empempenho.e60_numemp = empsolicitaanul.e35_numemp";
     $sql2 = "";
     if($dbwhere==""){
       if($e36_sequencial!=null ){
         $sql2 .= " where empsolicitaanulitem.e36_sequencial = $e36_sequencial "; 
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
   function sql_query_file ( $e36_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from empsolicitaanulitem ";
     $sql2 = "";
     if($dbwhere==""){
       if($e36_sequencial!=null ){
         $sql2 .= " where empsolicitaanulitem.e36_sequencial = $e36_sequencial "; 
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