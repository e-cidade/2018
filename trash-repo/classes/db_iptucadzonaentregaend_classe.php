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

//MODULO: cadastro
//CLASSE DA ENTIDADE iptucadzonaentregaend
class cl_iptucadzonaentregaend { 
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
   var $j87_sequencial = 0; 
   var $j87_iptucadzonaentrega = 0; 
   var $j87_lograd = 0; 
   var $j87_numero = 0; 
   var $j87_compl = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 j87_sequencial = int4 = Sequencial do iptucadzonaentregaend 
                 j87_iptucadzonaentrega = int4 = Codigo da zona de entrega 
                 j87_lograd = int4 = cód. Logradouro 
                 j87_numero = int4 = Numero do imovel 
                 j87_compl = varchar(20) = Complemento 
                 ";
   //funcao construtor da classe 
   function cl_iptucadzonaentregaend() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("iptucadzonaentregaend"); 
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
       $this->j87_sequencial = ($this->j87_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["j87_sequencial"]:$this->j87_sequencial);
       $this->j87_iptucadzonaentrega = ($this->j87_iptucadzonaentrega == ""?@$GLOBALS["HTTP_POST_VARS"]["j87_iptucadzonaentrega"]:$this->j87_iptucadzonaentrega);
       $this->j87_lograd = ($this->j87_lograd == ""?@$GLOBALS["HTTP_POST_VARS"]["j87_lograd"]:$this->j87_lograd);
       $this->j87_numero = ($this->j87_numero == ""?@$GLOBALS["HTTP_POST_VARS"]["j87_numero"]:$this->j87_numero);
       $this->j87_compl = ($this->j87_compl == ""?@$GLOBALS["HTTP_POST_VARS"]["j87_compl"]:$this->j87_compl);
     }else{
       $this->j87_sequencial = ($this->j87_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["j87_sequencial"]:$this->j87_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($j87_sequencial){ 
      $this->atualizacampos();
     if($this->j87_iptucadzonaentrega == null ){ 
       $this->erro_sql = " Campo Codigo da zona de entrega nao Informado.";
       $this->erro_campo = "j87_iptucadzonaentrega";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j87_lograd == null ){ 
       $this->erro_sql = " Campo cód. Logradouro nao Informado.";
       $this->erro_campo = "j87_lograd";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j87_numero == null ){ 
       $this->erro_sql = " Campo Numero do imovel nao Informado.";
       $this->erro_campo = "j87_numero";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j87_compl == null ){ 
       $this->erro_sql = " Campo Complemento nao Informado.";
       $this->erro_campo = "j87_compl";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($j87_sequencial == "" || $j87_sequencial == null ){
       $result = db_query("select nextval('iptucadzonaentregaend_j87_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: iptucadzonaentregaend_j87_sequencial_seq do campo: j87_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->j87_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from iptucadzonaentregaend_j87_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $j87_sequencial)){
         $this->erro_sql = " Campo j87_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->j87_sequencial = $j87_sequencial; 
       }
     }
     if(($this->j87_sequencial == null) || ($this->j87_sequencial == "") ){ 
       $this->erro_sql = " Campo j87_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into iptucadzonaentregaend(
                                       j87_sequencial 
                                      ,j87_iptucadzonaentrega 
                                      ,j87_lograd 
                                      ,j87_numero 
                                      ,j87_compl 
                       )
                values (
                                $this->j87_sequencial 
                               ,$this->j87_iptucadzonaentrega 
                               ,$this->j87_lograd 
                               ,$this->j87_numero 
                               ,'$this->j87_compl' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Endereco da zona de entrega ($this->j87_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Endereco da zona de entrega já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Endereco da zona de entrega ($this->j87_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j87_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->j87_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,8058,'$this->j87_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,1361,8058,'','".AddSlashes(pg_result($resaco,0,'j87_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1361,8053,'','".AddSlashes(pg_result($resaco,0,'j87_iptucadzonaentrega'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1361,8055,'','".AddSlashes(pg_result($resaco,0,'j87_lograd'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1361,8056,'','".AddSlashes(pg_result($resaco,0,'j87_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1361,8057,'','".AddSlashes(pg_result($resaco,0,'j87_compl'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($j87_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update iptucadzonaentregaend set ";
     $virgula = "";
     if(trim($this->j87_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j87_sequencial"])){ 
       $sql  .= $virgula." j87_sequencial = $this->j87_sequencial ";
       $virgula = ",";
       if(trim($this->j87_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial do iptucadzonaentregaend nao Informado.";
         $this->erro_campo = "j87_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j87_iptucadzonaentrega)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j87_iptucadzonaentrega"])){ 
       $sql  .= $virgula." j87_iptucadzonaentrega = $this->j87_iptucadzonaentrega ";
       $virgula = ",";
       if(trim($this->j87_iptucadzonaentrega) == null ){ 
         $this->erro_sql = " Campo Codigo da zona de entrega nao Informado.";
         $this->erro_campo = "j87_iptucadzonaentrega";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j87_lograd)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j87_lograd"])){ 
       $sql  .= $virgula." j87_lograd = $this->j87_lograd ";
       $virgula = ",";
       if(trim($this->j87_lograd) == null ){ 
         $this->erro_sql = " Campo cód. Logradouro nao Informado.";
         $this->erro_campo = "j87_lograd";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j87_numero)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j87_numero"])){ 
       $sql  .= $virgula." j87_numero = $this->j87_numero ";
       $virgula = ",";
       if(trim($this->j87_numero) == null ){ 
         $this->erro_sql = " Campo Numero do imovel nao Informado.";
         $this->erro_campo = "j87_numero";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j87_compl)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j87_compl"])){ 
       $sql  .= $virgula." j87_compl = '$this->j87_compl' ";
       $virgula = ",";
       if(trim($this->j87_compl) == null ){ 
         $this->erro_sql = " Campo Complemento nao Informado.";
         $this->erro_campo = "j87_compl";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($j87_sequencial!=null){
       $sql .= " j87_sequencial = $this->j87_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->j87_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8058,'$this->j87_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j87_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,1361,8058,'".AddSlashes(pg_result($resaco,$conresaco,'j87_sequencial'))."','$this->j87_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j87_iptucadzonaentrega"]))
           $resac = db_query("insert into db_acount values($acount,1361,8053,'".AddSlashes(pg_result($resaco,$conresaco,'j87_iptucadzonaentrega'))."','$this->j87_iptucadzonaentrega',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j87_lograd"]))
           $resac = db_query("insert into db_acount values($acount,1361,8055,'".AddSlashes(pg_result($resaco,$conresaco,'j87_lograd'))."','$this->j87_lograd',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j87_numero"]))
           $resac = db_query("insert into db_acount values($acount,1361,8056,'".AddSlashes(pg_result($resaco,$conresaco,'j87_numero'))."','$this->j87_numero',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j87_compl"]))
           $resac = db_query("insert into db_acount values($acount,1361,8057,'".AddSlashes(pg_result($resaco,$conresaco,'j87_compl'))."','$this->j87_compl',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Endereco da zona de entrega nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->j87_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Endereco da zona de entrega nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->j87_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j87_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($j87_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($j87_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8058,'$j87_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,1361,8058,'','".AddSlashes(pg_result($resaco,$iresaco,'j87_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1361,8053,'','".AddSlashes(pg_result($resaco,$iresaco,'j87_iptucadzonaentrega'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1361,8055,'','".AddSlashes(pg_result($resaco,$iresaco,'j87_lograd'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1361,8056,'','".AddSlashes(pg_result($resaco,$iresaco,'j87_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1361,8057,'','".AddSlashes(pg_result($resaco,$iresaco,'j87_compl'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from iptucadzonaentregaend
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($j87_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " j87_sequencial = $j87_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Endereco da zona de entrega nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$j87_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Endereco da zona de entrega nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$j87_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$j87_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:iptucadzonaentregaend";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
}
?>